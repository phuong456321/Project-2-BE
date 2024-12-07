<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Carbon\Carbon;
class PaymentWithStripeController extends Controller
{
    public function stripe(Request $request)
    {
        //Vilidator
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|min:1',
            'product_id' => 'required|exists:products,id',
        ]);

        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));

        try {
            $response = $stripe->checkout->sessions->create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $request->name,
                            ],
                            'unit_amount' => $request->amount * 100,
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
            ]);
            if (isset($response->id) && $response->id != '') {
                session()->put('product_id', $request->product_id);
                session()->put('name', $request->name);
                session()->put('amount', $request->amount);
                return redirect($response->url);
            } else {
                return response()->json(['error' => 'Failed to create checkout session'], 500);
            }
        } catch (ApiErrorException $e) {
            dd($e);
            return redirect()->route('stripe.cancel')->with('error', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
        try {
            $response = $stripe->checkout->sessions->retrieve($request->session_id);

            $payment = new Payment();
            $payment->user_id = auth()->user()->id;
            $payment->stripe_payment_intent_id = $response->payment_intent;
            $payment->stripe_checkout_session_id = $request->session_id;
            $payment->transaction_id = $response->id;
            $payment->amount = session()->get('amount');
            $payment->currency = $response->currency;
            $payment->status = $response->status;
            $payment->payment_method = "Stripe";
            $payment->payment_status = $response->payment_status;
            $payment->product_id = session()->get('product_id');
            $payment->quantity = 1;
            $payment->price = $response->amount_total / 100;
            $payment->total_amount = $response->amount_total / 100;
            $payment->completed_at = now();
            $payment->save();
            session()->forget(['name', 'amount', 'product_id']);


            // Lấy thông tin gói sản phẩm
            $product = Product::where('id', $payment->product_id)->first();
            if ($product) {
                // Lấy người dùng từ payment
                $user = User::find($payment->user_id);

                // Nâng cấp người dùng lên Premium
                $user->plan = 'premium';  // Đánh dấu người dùng là premium
                $user->save();

                // Tính toán ngày hết hạn dựa trên chu kỳ sản phẩm
                $expirationDate = Carbon::now();
                if ($product->cycles == 'monthly') {
                    $expirationDate->addMonth();
                } elseif ($product->cycles == 'six_months') {
                    $expirationDate->addMonths(6);
                } elseif ($product->cycles == 'yearly') {
                    $expirationDate->addYear();
                }

                // Lưu thông tin gói đã mua vào bảng user_product
                // Lưu thông tin vào bảng pivot
                $user->products()->attach($product->id, [
                    'purchased_at' => now(),
                    'expired_at' => $expirationDate,
                ]);
            }
            return redirect()->route('profile', auth()->user()->id)->with('success', 'Payment is successfully');
        } catch (ApiErrorException $e) {
            return redirect()->route('cancel')->with('error', $e->getMessage());
        }
    }

    public function cancel()
    {
        return response("Payment is canceled", 200);
    }
}
