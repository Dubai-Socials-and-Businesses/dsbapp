<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            if($event->type == 'checkout.session.completed') {
                $session = $event->data->object;
                $customerEmail = $session->customer_details->email ?? null;
                $customerName = $session->customer_details->name ?? null;
                $amountTotal = $session->amount_total / 100; // amount in AED
                $paymentStatus = $session->payment_status;
                $clientReferenceId = $session->client_reference_id ?? null;

                $user = User::where('email', $customerEmail)->first();
                if(!$user) {
                    $user = new User();
                    $user->email = $customerEmail;
                    $user->name = $customerName ?? $customerEmail;
                    $user->password = bcrypt($customerEmail);
                    $user->role = 'user';
                    $user->save();
                }
                $payment = new Payment();
                $payment->name = $session->customer_details->name ?? null;
                $payment->email = $session->customer_details->email ?? null;
                $payment->payment_status = $session->payment_status;
                $payment->client_reference_id = $session->client_reference_id ?? null;
                $payment->amount_total = $session->amount_total ?? 0;
            }
            return response()->json(['success' => true,'status' => $event->type]);

        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            // Some other error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
