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
    | Subscribe URL route
    |--------------------------------------------------------------------------
    |
    | You can override the route to subscribe to a newsletter.
    |
    */

    'subscribe_url' => '/subscribe',

    /*
    |--------------------------------------------------------------------------
    | Unsubscribe URL route
    |--------------------------------------------------------------------------
    |
    | You can override the route to unsubscribe from a newsletter. Ensure
    | that you keep the {hash} parameter within the specified route.
    |
    */

    'unsubscribe_url' => '/unsubscribe/{hash}'
];
