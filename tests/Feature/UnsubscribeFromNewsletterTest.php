<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Riverskies\LaravelNewsletterSubscription\Mail\NewsletterSubscriptionConfirmation;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;
use Tests\TestCase;

class UnsubscribeFromNewsletterTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function people_can_unsubscribe_from_newsletters()
    {
        $subscription = factory(NewsletterSubscription::class)->create();

        $response = $this->get(
            $this->getUnsubscribeUrlFor($subscription->hash),
            ['HTTP_REFERER' => '/original-url']
        );

        $response->assertRedirectedBack('/original-url');
        $this->assertDatabaseHasSoftDeleted($this->config('table_name'), ['email'=>$subscription->email]);
        $response->assertSessionHas($this->config('session_message_key'), 'You will no longer receive our newsletter at ' . $subscription->email);
        $response->assertSessionHas($this->config('session_message_key'), trans('riverskies::newsletter_subscription.unsubscribe', ['email'=>$subscription->email]));
    }

    /** @test */
    public function the_newsletter_confirmation_email_has_the_correct_unsubscribe_link()
    {
        $subscription = factory(NewsletterSubscription::class)->create();
        $message = new NewsletterSubscriptionConfirmation($subscription);

        $this->assertContains(url($this->getUnsubscribeUrlFor($subscription->hash)), $message->build()->render());
    }
}
