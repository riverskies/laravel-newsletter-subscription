<?php

Route::post('/subscribe', 'Riverskies\LaravelNewsletterSubscription\Http\Controllers\NewsletterSubscriptionController@store');
Route::get('/unsubscribe/{subscription}', 'Riverskies\LaravelNewsletterSubscription\Http\Controllers\NewsletterSubscriptionController@destroy');
