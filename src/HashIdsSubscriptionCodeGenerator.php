<?php

namespace Riverskies\LaravelNewsletterSubscription;

use Hashids\Hashids;

class HashIdsSubscriptionCodeGenerator
{
    private $hashIds;

    /**
     * HashIdsSubscriptionCodeGenerator constructor.
     *
     * @param $salt
     */
    public function __construct($salt)
    {
        $this->hashIds = new Hashids($salt, 24, 'abcdefghijklmnopqrstuvwxyz0123456789');
    }

    /**
     * @param NewsletterSubscription $subscription
     * @return string
     */
    public function encodeFor(NewsletterSubscription $subscription)
    {
        return $this->hashIds->encode($subscription->id);
    }

    /**
     * @param $hash
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function decode($hash)
    {
        $id = array_first($this->hashIds->decode($hash));
        return NewsletterSubscription::find($id);
    }
}
