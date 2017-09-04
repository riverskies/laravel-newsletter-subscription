<?php

use Faker\Generator as Faker;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;

$factory->define(NewsletterSubscription::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
    ];
});
