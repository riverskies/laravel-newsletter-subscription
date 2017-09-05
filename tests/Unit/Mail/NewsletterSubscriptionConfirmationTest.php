<?php

namespace Tests\Unit\Mail;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Riverskies\LaravelNewsletterSubscription\Mail\NewsletterSubscriptionConfirmation;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;
use Tests\TestCase;

class NewsletterSubscriptionConfirmationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function email_has_the_correct_subject()
    {
        $subscription = factory(NewsletterSubscription::class)->make();
        $mail = new NewsletterSubscriptionConfirmation($subscription);

        $this->assertEquals('Thanks for signing up to our newsletter!', $mail->build()->subject);
    }

    /** @test */
    public function email_has_the_correct_subscription()
    {
        $subscription = factory(NewsletterSubscription::class)->make();
        $mail = new NewsletterSubscriptionConfirmation($subscription);

        $this->assertTrue($mail->subscription->is($subscription));
    }

    /** @test */
    public function email_does_contain_an_unsubscribe_link()
    {
        $this->withoutExceptionHandling();
        $subscription = factory(NewsletterSubscription::class)->create();
        $mail = new NewsletterSubscriptionConfirmation($subscription);

        $this->assertContains(url($this->getUnsubscribeUrlFor($subscription->hash)), $mail->build()->render());
    }
}
