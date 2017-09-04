<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Riverskies\LaravelNewsletterSubscription\Providers\NewsletterSubscriptionServiceProvider;

class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [NewsletterSubscriptionServiceProvider::class];
    }
}
