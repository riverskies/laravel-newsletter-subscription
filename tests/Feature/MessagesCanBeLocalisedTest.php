<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;
use Tests\TestCase;

class MessagesCanBeLocalisedTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function subscribe_message_is_localised()
    {
        Mail::fake();
        app()->setLocale('hu');

        $response = $this->post($this->config('subscribe_url'), ['email'=>'john@example.com']);

        $response->assertSessionHas($this->config('session_message_key'), 'Sikeresen feliratkozott hírlevelünkre a(z) john@example.com email címmel');
    }

    /** @test */
    public function unsubscribe_message_is_localised()
    {
        app()->setLocale('hu');
        $subscription = factory(NewsletterSubscription::class)->create();

        $response = $this->get($this->getUnsubscribeUrlFor($subscription->hash));

        $response->assertSessionHas($this->config('session_message_key'), "Sikeresen leiratkozott hírlevelünkről a(z) {$subscription->email} email címmel");
    }
}
