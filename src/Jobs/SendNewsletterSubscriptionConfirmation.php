<?php

namespace Riverskies\LaravelNewsletterSubscription\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Riverskies\LaravelNewsletterSubscription\Mail\NewsletterSubscriptionConfirmation;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;

class SendNewsletterSubscriptionConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var NewsletterSubscription
     */
    public $subscription;

    /**
     * Create a new job instance.
     *
     * @param NewsletterSubscription $subscription
     */
    public function __construct(NewsletterSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->subscription->email)->send(
            new NewsletterSubscriptionConfirmation($this->subscription)
        );
    }
}
