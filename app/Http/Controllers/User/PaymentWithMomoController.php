<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;

class PaymentWithMomoController extends Controller
{
    public function payWithMomo(Request $request)
    {
        // Kiểm tra và xác thực dữ liệu yêu cầu
        $request->validate([
            'amount' => 'required|numeric',
            'orderId' => 'required|string',
            // Thêm các trường cần thiết khác
        ]);

        // Thông tin thanh toán
        $partnerCode = 'YOUR_PARTNER_CODE';
        $accessKey = 'YOUR_ACCESS_KEY';
        $secretKey = 'YOUR_SECRET_KEY';
        $orderId = $request->orderId;
        $amount = $request->amount;
        $orderInfo = 'Thanh toán đơn hàng ' . $orderId;
        $redirectUrl = 'YOUR_REDIRECT_URL';
        $ipnUrl = 'YOUR_IPN_URL';
        $requestId = Str::uuid(); // Tạo ID yêu cầu ngẫu nhiên
        $extraData = ''; // Dữ liệu bổ sung nếu cần

        // Tạo timestamp
        $timestamp = now()->format('YmdHis');

        // Tạo chuỗi để mã hóa
        $rawHash = "partnerCode=$partnerCode&accessKey=$accessKey&requestId=$requestId&amount=$amount&orderId=$orderId&orderInfo=$orderInfo&redirectUrl=$redirectUrl&ipnUrl=$ipnUrl&extraData=$extraData&timestamp=$timestamp";
        
        // Mã hóa chuỗi bằng SHA256
        $signature = hash_hmac('sha256', $rawHash, $secretKey);

        // Dữ liệu gửi đến Momo
        $data = [
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => $extraData,
            'signature' => $signature,
            'timestamp' => $timestamp,
        ];

        // Gửi yêu cầu đến API của Momo
        $response = Http::post('https://test-payment.momo.vn/gw_payment/transactionProcessor', $data);

        // Kiểm tra phản hồi từ Momo
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['message' => 'Payment initiation failed'], 500);
        }
    }

    public function handlePaymentSuccess($paymentId)
{
    // Lấy thông tin giao dịch
    $payment = Payment::findOrFail($paymentId);
    
    // Kiểm tra nếu giao dịch thành công và thanh toán là cho gói Premium
    if ($payment->status == 'succeeded' && $payment->payment_method == 'momo') { // Hoặc 'stripe'
        
        // Lấy thông tin gói sản phẩm
        $product = Product::find($payment->product_id);
        
        if ($product) {
            // Lấy người dùng từ payment
            $user = User::find($payment->user_id);
            
            // Nâng cấp người dùng lên Premium
            $user->is_premium = true;  // Đánh dấu người dùng là premium
            $user->save();
            
            // Tính toán ngày hết hạn dựa trên chu kỳ sản phẩm
            $expirationDate = now();
            if ($product->cycles == 'monthly') {
                $expirationDate->addMonth();
            } elseif ($product->cycles == 'six_months') {
                $expirationDate->addMonths(6);
            } elseif ($product->cycles == 'yearly') {
                $expirationDate->addYear();
            }

            // Lưu thông tin gói đã mua vào bảng user_product
            $user->products()->create([
                'product_id' => $payment->product_id,
                'purchased_at' => now(),
                'expired_at' => $expirationDate,  // Gán ngày hết hạn
            ]);
        }
    }
}
}
