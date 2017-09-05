<?php

namespace Riverskies\LaravelNewsletterSubscription;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    protected $fillable = ['email'];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('newsletter_subscription.table_name'));
        parent::__construct($attributes);
    }

    /**
     * @return string
     */
    public function getHashAttribute()
    {
        return app('subscription-code-generator')->encodeFor($this);
    }
}
