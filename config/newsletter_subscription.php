<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | You can override the table name for the newsletter subscriptions.
    |
    */

    'table_name' => 'newsletter_subscriptions',

    /*
    |--------------------------------------------------------------------------
    | Subscribe URL Route
    |--------------------------------------------------------------------------
    |
    | You can override the route to subscribe to a newsletter.
    |
    */

    'subscribe_url' => '/subscribe',

    /*
    |--------------------------------------------------------------------------
    | Unsubscribe URL Route
    |--------------------------------------------------------------------------
    |
    | You can override the route to unsubscribe from a newsletter. Ensure
    | that you keep the {hash} parameter within the specified route.
    |
    */

    'unsubscribe_url' => '/unsubscribe/{hash}',

    /*
    |--------------------------------------------------------------------------
    | Confirmation Email Type
    |--------------------------------------------------------------------------
    |
    | You can specify the email view template type as 'markdown' or 'html'.
    |
    */

    'mail' => 'markdown',

    /*
    |--------------------------------------------------------------------------
    | Session Message Key
    |--------------------------------------------------------------------------
    |
    | This is the key that will be used when flashing messages to the session
    | after a successful transaction (like subscribe or unsubscribe).
    |
    */

    'session_message_key' => 'flash'
];
