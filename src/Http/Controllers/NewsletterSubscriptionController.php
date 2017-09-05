<?php

namespace Riverskies\LaravelNewsletterSubscription\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Riverskies\LaravelNewsletterSubscription\Jobs\SendNewsletterSubscriptionConfirmation;
use Riverskies\LaravelNewsletterSubscription\NewsletterSubscription;

class NewsletterSubscriptionController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate(['email'=>'required|email']);
        $existingSubscription = NewsletterSubscription::whereEmail($data['email'])->get();

        if (!$existingSubscription->count()) {
            $subscription = NewsletterSubscription::create(['email'=>$data['email']]);
            SendNewsletterSubscriptionConfirmation::dispatch($subscription);
        }

        return redirect()->back()
            ->with('flash', 'You will receive the latest news at ' . $data['email']);
    }

    /**
     * @param $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($hash)
    {
        $subscription = app('subscription-code-generator')->decode($hash);
        $subscription->delete();

        return redirect()->back()
            ->with('flash', 'You will no longer receive our newsletter at ' . $subscription->email);
    }
}
