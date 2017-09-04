<?php

namespace Tests\Unit\Jobs;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Riverskies\LaravelNewsletterSubscription\Jobs\SendNewsletterSubscriptionConfirmation;
use Riverskies\LaravelNewsletterSubscription\Mail\NewsletterSubscriptionConfirmation;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;
use Tests\TestCase;

class SendNewsletterSubscriptionConfirmationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function confirmation_email_is_sent_to_the_correct_address()
    {
        Mail::fake();
        $subscription = factory(NewsletterSubscription::class)->create(['email'=>'john@example.com']);

        SendNewsletterSubscriptionConfirmation::dispatch($subscription);

        Mail::assertSent(NewsletterSubscriptionConfirmation::class, function($mail) {
            return $mail->hasTo('john@example.com');
        });
    }
}
