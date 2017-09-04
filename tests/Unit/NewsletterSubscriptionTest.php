<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;
use Tests\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_subscription_can_generate_its_hash()
    {
        $subscription = factory(NewsletterSubscription::class)->create();
        $this->assertNotEmpty($subscription->hash);
    }
}
