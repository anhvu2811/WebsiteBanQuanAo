<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Demo T-shirt',
                    ],
                    'unit_amount' => 2000,
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);

        return response()->json(['id' => $session->id]);
    }

    public function success(Request $request)
    {
        $session_id = $request->get('session_id');
        return view('page.test_stripe.success', compact('session_id'));
    }

    public function cancel()
    {
        return view('page.test_stripe.cancel');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch(\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            Log::info('Stripe checkout.session.completed', ['id' => $session->id]);
        }

        return response()->json(['status' => 'success']);
    }
}
