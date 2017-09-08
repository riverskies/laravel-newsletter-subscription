<?php

namespace Tests;

use Dotenv\Dotenv;
use Illuminate\Foundation\Testing\TestResponse;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;
use Riverskies\LaravelNewsletterSubscription\Providers\NewsletterSubscriptionServiceProvider;

class TestCase extends OrchestraTestCase
{
    private $config;
    private $language;

    /**
     * Default overrides.
     */
    public function setUp()
    {
        parent::setUp();

        (new Dotenv(__DIR__.'/..'))->load();

        $this->withFactories(__DIR__.'/../database/factories');

        $this->config = config('newsletter_subscription');

        TestResponse::macro('assertRedirectedBack', function($referer = null) {
            if (!$referer) {
                $referer = app('request')->header('referer') ?: '/';
            }
            $this->assertRedirect($referer);
        });
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
     * Assert soft deleted model.
     *
     * @param $table
     * @param $data
     */
    protected function assertDatabaseHasSoftDeleted($table, $data)
    {
        $this->assertDatabaseHas($table, $data);
        $this->assertDatabaseMissing($table, array_merge($data, ['deleted_at'=>null]));
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

    /**
     * Language accessor.
     *
     * @param $key
     * @param array $data
     * @return mixed
     */
    public function language($key, $data = [])
    {
        $this->assertArrayHasKey($key, $this->language);

        return app('translator')->trans($key, $data);
    }
}
