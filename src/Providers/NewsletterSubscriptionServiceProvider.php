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
        $this->app->make('Illuminate\Database\Eloquent\Factory')
            ->load(__DIR__ . '/../../database/factories');

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/newsletter_subscription.php', 'newsletter_subscription'
        );

        $this->publishes([
            __DIR__ . '/../../config/newsletter_subscription.php' => config_path('newsletter_subscription.php'),
        ], 'newsletter-subscription-config');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/riverskies'),
        ], 'newsletter-subscription-views');

        $this->publishes([
            __DIR__.'/../../resources/lang' => resource_path('lang/vendor/riverskies'),
        ], 'newsletter-subscription-translations');

        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations/');
        $this->loadViewsFrom(__DIR__.'/../../resources/views/', 'riverskies');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang/', 'riverskies');
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
