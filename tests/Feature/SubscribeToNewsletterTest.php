<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Riverskies\LaravelNewsletterSubscription\Jobs\SendNewsletterSubscriptionConfirmation;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;
use Tests\TestCase;

class SubscribeToNewsletterTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function people_can_subscribe_to_receive_newsletters()
    {
        Mail::fake();
        $this->withoutExceptionHandling();
        $email = app(\Faker\Generator::class)->email;

        $response = $this->post(
            $this->config('subscribe_url'),
            ['email' => $email],
            ['HTTP_REFERER' => '/original-url']
        );

        $response->assertRedirectedBack('/original-url');
        $this->assertDatabaseHas($this->config('table_name'), ['email'=>$email]);
        $response->assertSessionHas($this->config('session_message_key'), "You will receive the latest news at {$email}");
        $response->assertSessionHas($this->config('session_message_key'), trans('riverskies::newsletter_subscription.subscribe', ['email'=>$email]));
    }

    /** @test */
    public function people_who_already_subscribed_cannot_create_a_new_subscription()
    {
        Queue::fake();
        factory(NewsletterSubscription::class)->create(['email'=>'john@example.com']);
        $this->assertCount(1, NewsletterSubscription::all());

        $response = $this->post($this->config('subscribe_url'), ['email'=>'john@example.com']);

        $response->assertRedirectedBack();
        $this->assertCount(1, NewsletterSubscription::all());
        $this->assertDatabaseHas($this->config('table_name'), ['email'=>'john@example.com']);
        $response->assertSessionHas($this->config('session_message_key'), trans('riverskies::newsletter_subscription.subscribe', ['email'=>'john@example.com']));
        Queue::assertNotPushed(SendNewsletterSubscriptionConfirmation::class);
    }

    /** @test */
    public function people_who_have_unsubscribed_before_will_enable_their_old_subscription_when_subscribing_again()
    {
        Queue::fake();
        factory(NewsletterSubscription::class)->create(['email'=>'john@example.com', 'deleted_at'=>Carbon::now()]);
        $this->assertCount(1, NewsletterSubscription::withTrashed()->get());
        $this->assertCount(0, NewsletterSubscription::all());

        $response = $this->post($this->config('subscribe_url'), ['email'=>'john@example.com']);

        $response->assertRedirectedBack();
        $this->assertCount(1, NewsletterSubscription::all());
        $this->assertCount(1, NewsletterSubscription::withTrashed()->get());
        $this->assertDatabaseHas($this->config('table_name'), ['email'=>'john@example.com', 'deleted_at'=>null]);
        $response->assertSessionHas($this->config('session_message_key'), trans('riverskies::newsletter_subscription.subscribe', ['email'=>'john@example.com']));
        Queue::assertPushed(SendNewsletterSubscriptionConfirmation::class);
    }

    /** @test */
    public function a_confirmation_email_is_queued_to_be_sent_after_each_new_subscription()
    {
        Queue::fake();
        $this->post($this->config('subscribe_url'), ['email'=>'john@example.com']);

        $subscription = NewsletterSubscription::first();

        Queue::assertPushed(SendNewsletterSubscriptionConfirmation::class, function($job) use ($subscription) {
            return $job->subscription->is($subscription);
        });
    }

    /** @test */
    public function the_email_is_required()
    {
        $response = $this->post($this->config('subscribe_url'), []);

        $response->assertRedirectedBack();
        $response->assertSessionHasErrors('email');
        $this->assertEmpty(NewsletterSubscription::all());
    }

    /** @test */
    public function the_email_must_be_a_valid_address()
    {
        $response = $this->post($this->config('subscribe_url'), ['email'=>'gibberish']);

        $response->assertRedirectedBack();
        $response->assertSessionHasErrors('email');
        $this->assertEmpty(NewsletterSubscription::all());
    }
}
