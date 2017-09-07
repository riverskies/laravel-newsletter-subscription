<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Riverskies\LaravelNewsletterSubscription\Mail\NewsletterSubscriptionConfirmation;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;
use Tests\TestCase;

class MarkdownConfirmationEmailTest extends TestCase
{
    /** @test */
    public function markdown_email_is_sent_by_default()
    {
        Mail::fake();
        $this->assertEquals('markdown', config('newsletter_subscription.mail'));
        $subscription = factory(NewsletterSubscription::class)->make();
        $mail = (new NewsletterSubscriptionConfirmation($subscription))->build();
        $this->assertNull($mail->view);
    }

    /** @test */
    public function html_email_is_sent_if_configured()
    {
        Mail::fake();
        config(['newsletter_subscription.mail' => 'html']);
        $this->assertEquals('html', config('newsletter_subscription.mail'));
        $subscription = factory(NewsletterSubscription::class)->make();
        $mail = (new NewsletterSubscriptionConfirmation($subscription))->build();
        $this->assertNotNull($mail->view);
    }
}
