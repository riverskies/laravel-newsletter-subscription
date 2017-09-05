<?php

namespace Tests\Feature;

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
        $this->withoutExceptionHandling();
        Mail::fake();
        $response = $this->post('/subscribe', ['email'=>'john@example.com']);

        $response->assertRedirect('/');
        $this->assertDatabaseHas($this->table, ['email'=>'john@example.com']);
        $response->assertSessionHas('flash', 'You will receive the latest news at john@example.com');
    }

    /** @test */
    public function people_who_already_subscribed_cannot_create_a_new_subscription()
    {
        Queue::fake();
        factory(NewsletterSubscription::class)->create(['email'=>'john@example.com']);
        $this->assertCount(1, NewsletterSubscription::all());

        $response = $this->post('/subscribe', ['email'=>'john@example.com']);

        $response->assertRedirect('/');
        $this->assertCount(1, NewsletterSubscription::all());
        $this->assertDatabaseHas($this->table, ['email'=>'john@example.com']);
        $response->assertSessionHas('flash', 'You will receive the latest news at john@example.com');
        Queue::assertNotPushed(SendNewsletterSubscriptionConfirmation::class);
    }

    /** @test */
    public function a_confirmation_email_is_queued_to_be_sent_after_each_new_subscription()
    {
        Queue::fake();
        $this->post('/subscribe', ['email'=>'john@example.com']);

        $subscription = NewsletterSubscription::first();

        Queue::assertPushed(SendNewsletterSubscriptionConfirmation::class, function($job) use ($subscription) {
            return $job->subscription->is($subscription);
        });
    }

    /** @test */
    public function the_email_is_required()
    {
        $response = $this->post('/subscribe', []);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('email');
        $this->assertEmpty(NewsletterSubscription::all());
    }

    /** @test */
    public function the_email_must_be_a_valid_address()
    {
        $response = $this->post('/subscribe', ['email'=>'gibberish']);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('email');
        $this->assertEmpty(NewsletterSubscription::all());
    }
}
