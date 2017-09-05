<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Riverskies\LaravelNewsletterSubscription\Providers\NewsletterSubscriptionServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected $table;

    public function setUp()
    {
        parent::setUp();
        $this->table = config('newsletter_subscription.table_name');
        $this->withFactories(__DIR__.'/../database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [NewsletterSubscriptionServiceProvider::class];
    }
}
