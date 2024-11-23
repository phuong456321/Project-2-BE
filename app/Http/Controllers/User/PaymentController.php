<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Thư viện để gửi yêu cầu HTTP
use Illuminate\Support\Str; // Thư viện để tạo chuỗi ngẫu nhiên

class PaymentController extends Controller
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
}
