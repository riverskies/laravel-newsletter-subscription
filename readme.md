# Laravel Newsletter Subscription
A simple package to enable newsletter subscriptions within the context of a Laravel application.

## Installation
```sh
$ composer require riverskies/laravel-newsletter-subscription
```


## Laravel 5.4 or earlier
Add the service provider to your `config/app.php` file:
```php
'providers' => [
    // ...
    Riverskies\LaravelNewsletterSubscription\Providers\NewsletterSubscriptionServiceProvider::class,
];
```

## Usage
Include a simple form anywhere within your pages.
```html
<form action="/subscribe" method="POST">
    {{ csrf_field() }}
    <input type="email" name="email"/>
    <button type="submit">Subscribe</button>
</form>
```
You might also want to include notification display on the same page.
```html
@if(session('flash'))
    <p>{{ session('flash') }}</p>
@endif
```

### Subscriptions
This package collects email addresses and stores them in the database. You can access these subscriptions by querying the `Riverskies\LaravelNewsletterSubscription\NewsletterSubscription` Eloquent model.

### Note
This package uses the `Mail` facade to deliver the emails and delivery is queued, so ensure your `QUEUE_DRIVER` in your environment config is set accordingly.

## Publishing assets
You can override the database table name, the associated URLs, email template format and the session key by overriding the default configuration values.
```sh
$ php artisan vendor:publish --tag='newsletter-subscription-config'
```

You can design the confirmation email by overriding the default view.
```sh
$ php artisan vendor:publish --tag='newsletter-subscription-views'
```

You can localise/change the messages by overriding the default localisation values.
```sh
$ php artisan vendor:publish --tag='newsletter-subscription-translations'
```

## Considerations
This package uses [`hashids/hashids`](https://github.com/ivanakimov/hashids.php) to help derive unsubscribe links from the `id` fields of the subscription records. Those hashes are not stored in the database but instead encoded/decoded at runtime. To generate unique codes, this package uses the `APP_KEY` from the environment settings. If that changes, previously generated unsubscribe links will no longer work.

## Contributions
PRs are welcome as long as they are following `PSR-2` standards and include all the corresponding tests that the change requires (not to mention that those should not break any previous behaviour either).
