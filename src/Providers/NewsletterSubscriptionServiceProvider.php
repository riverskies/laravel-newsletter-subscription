<?php

namespace Riverskies\LaravelNewsletterSubscription\Providers;

use Illuminate\Support\ServiceProvider;
use Riverskies\LaravelNewsletterSubscription\HashIdsSubscriptionCodeGenerator;

class NewsletterSubscriptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations/');
        $this->loadViewsFrom(__DIR__.'/../../resources/views/', 'riverskies');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('subscription-code-generator', function() {
            return new HashIdsSubscriptionCodeGenerator(config('app.key'));
        });
    }
}
