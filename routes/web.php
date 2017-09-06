<?php

Route::group(['middleware' => 'web'], function() {
    Route::post(config('newsletter_subscription.subscribe_url'),
        'Riverskies\LaravelNewsletterSubscription\Http\Controllers\NewsletterSubscriptionController@store');
    Route::get(config('newsletter_subscription.unsubscribe_url'),
        'Riverskies\LaravelNewsletterSubscription\Http\Controllers\NewsletterSubscriptionController@destroy');
});
