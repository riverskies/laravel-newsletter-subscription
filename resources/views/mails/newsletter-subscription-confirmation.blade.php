@component('mail::message')
# Happy days!

You have successfully subscribed to our newsletter!

If you wish to unsubscribe, you can do that [here]({{ url($subscription->unsubscribeUrl) }}) or by copying the below URL into your browser:

{{ url($subscription->unsubscribeUrl) }}

Thanks,<br>
The {{ config('app.name') }} Team
@endcomponent
