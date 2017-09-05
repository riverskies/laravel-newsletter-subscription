<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Riverskies\LaravelNewsletterSubscription\Providers\NewsletterSubscriptionServiceProvider;

class TestCase extends OrchestraTestCase
{
    private $config;

    /**
     * Default overrides.
     */
    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../database/factories');

        $this->config = config('newsletter_subscription');
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
        return str_replace('{hash}', $hash, $this->config['unsubscribe_url']);
    }

    /**
     * Config accessor.
     *
     * @param $key
     * @return mixed
     */
    public function config($key)
    {
        $this->assertArrayHasKey($key, $this->config);
        return $this->config[$key];
    }
}
