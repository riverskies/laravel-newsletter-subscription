<?php

namespace Riverskies\LaravelNewsletterSubscription\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;

class NewsletterSubscriptionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var NewsletterSubscription
     */
    public $subscription;

    /**
     * Create a new message instance.
     *
     * @param NewsletterSubscription $subscription
     */
    public function __construct(NewsletterSubscription $subscription)
    {
        $this->subscription = $subscription;
        $this->subject('Thanks for signing up to our newsletter!');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('riverskies::mails.newsletter-subscription-confirmation');
    }
}
