<?php

namespace Riverskies\LaravelNewsletterSubscription;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    protected $fillable = ['email'];

    /**
     * @return string
     */
    public function getHashAttribute()
    {
        return app('subscription-code-generator')->encodeFor($this);
    }
}
