<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Riverskies\LaravelNewsletterSubscription\HashIdsSubscriptionCodeGenerator;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;
use Tests\TestCase;

class HashIdsSubscriptionCodeGeneratorTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function codes_are_at_least_24_characters_long()
    {
        $generator = new HashIdsSubscriptionCodeGenerator('test-salt');

        $code = $generator->encodeFor(factory(NewsletterSubscription::class)->create());

        $this->assertTrue(strlen($code) >= 24);
    }

    /** @test */
    public function codes_can_only_contain_lowercase_letters_and_numbers()
    {
        $generator = new HashIdsSubscriptionCodeGenerator('test-salt');

        $code = $generator->encodeFor(factory(NewsletterSubscription::class)->create());

        $this->assertRegExp('/^[a-z0-9]+$/', $code);
    }

    /** @test */
    public function codes_for_the_same_subscription_are_the_same()
    {
        $generator = new HashIdsSubscriptionCodeGenerator('test-salt');

        $code1 = $generator->encodeFor(factory(NewsletterSubscription::class)->make(['id'=>1]));
        $code2 = $generator->encodeFor(factory(NewsletterSubscription::class)->make(['id'=>1]));

        $this->assertEquals($code1, $code2);
    }

    /** @test */
    public function codes_for_different_subscriptions_are_different()
    {
        $generator = new HashIdsSubscriptionCodeGenerator('test-salt');

        $code1 = $generator->encodeFor(factory(NewsletterSubscription::class)->make(['id'=>1]));
        $code2 = $generator->encodeFor(factory(NewsletterSubscription::class)->make(['id'=>2]));

        $this->assertNotEquals($code1, $code2);
    }

    /** @test */
    public function codes_for_different_salts_are_different()
    {
        $generator1 = new HashIdsSubscriptionCodeGenerator('test-salt');
        $generator2 = new HashIdsSubscriptionCodeGenerator('different-salt');

        $code1 = $generator1->encodeFor(factory(NewsletterSubscription::class)->make(['id'=>1]));
        $code2 = $generator2->encodeFor(factory(NewsletterSubscription::class)->make(['id'=>1]));

        $this->assertNotEquals($code1, $code2);
    }

    /** @test */
    public function decoded_codes_return_the_subscription()
    {
        $subscription = factory(NewsletterSubscription::class)->create();
        $generator = new HashIdsSubscriptionCodeGenerator('test-salt');
        $code = $generator->encodeFor($subscription);

        $returnedSubscription = $generator->decode($code);

        $this->assertTrue($returnedSubscription->is($subscription));
    }

    /** @test */
    public function invalid_codes_that_cannot_be_decoded_return_null()
    {
        $generator = new HashIdsSubscriptionCodeGenerator('test-salt');

        $returnedValue = $generator->decode('invalid-code');

        $this->assertNull($returnedValue);
    }
}
