<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Riverskies\LaravelNewsletterSubscription\Providers\NewsletterSubscriptionServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected $table;
    protected $subscribeUrl;
    protected $unsubscribeUrl;

    /**
     * Default overrides.
     */
    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../database/factories');

        $this->table = config('newsletter_subscription.table_name');
        $this->subscribeUrl = config('newsletter_subscription.subscribe_url');
        $this->unsubscribeUrl = config('newsletter_subscription.unsubscribe_url');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [NewsletterSubscriptionServiceProvider::class];
    }

    /**
     * Helper to get the real unsubscribe URL.
     *
     * @param $hash
     * @return mixed
     */
    public function getUnsubscribeUrlFor($hash)
    {
        return str_replace('{hash}', $hash, $this->unsubscribeUrl);
    }
}
