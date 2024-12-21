<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentWithMomoController extends Controller
{
    public function createPayment(Request $request)
    {
        try {
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
            $orderInfo = "Thanh toán qua MoMo";
            $amount = $request->amount * 25000;
            $orderId = Str::uuid();
            $redirectUrl = config('momo.returnUrl');
            $ipnUrl = config('momo.notifyUrl');
            $extraData = "";
            $requestId = time();
            $requestType = "payWithMethod";
            $userId = auth()->user()->id; // Lấy user_id của người dùng hiện tại
            $productId = $request->product_id; // Lấy product_id từ request

            // Tạo chuỗi dữ liệu để tạo chữ ký HMAC SHA256
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            // Tạo dữ liệu gửi yêu cầu POST
            $data = [
                'partnerCode' => $partnerCode,
                'partnerName' => "Test",
                'storeId' => "MomoTestStore",
                'momo_payment_request_id' => $requestId,
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
                'userId' => $userId,
                'productId' => $productId,
            ];

            // Gửi yêu cầu POST đến MoMo API
            $response = $this->execPostRequest("https://test-payment.momo.vn/v2/gateway/api/create", $data);

            $jsonResult = json_decode($response, true);
            // Lưu giao dịch vào cơ sở dữ liệu trước khi redirect đến MoMo
            Payment::create([
                'user_id' => $userId,
                'transaction_id' => $orderId,
                'amount' => $amount,
                'currency' => 'VND',
                'status' => 'pending',
                'payment_method' => 'momo',
                'payment_status' => 'pending',
                'product_id' => $productId,
                'quantity' => 1,
                'price' => $amount,
                'total_amount' => $amount,
                'completed_at' => null,
            ]);
            // Chuyển hướng người dùng đến MoMo thanh toán
            return redirect($jsonResult['payUrl']);
        } catch (\Exception $e) {
            Log::error('Lỗi tạo giao dịch MoMo', ['error' => $e->getMessage()]);
            flash()->error('Lỗi tạo giao dịch MoMo: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->route('home');
        }
    }

    // Hàm gửi yêu cầu POST đến MoMo API
    private function execPostRequest($url, $data)
    {
        $response = Http::timeout(5)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Content-Length' => strlen(json_encode($data)),
            ])
            ->post($url, $data);

        return $response->body();
    }

    public function return(Request $request)
    {
        // Lấy dữ liệu từ MoMo gửi về
        $responseData = $request->all();
        Log::info('MoMo Return Data:', $responseData);

        try {
            // Các tham số cần thiết từ MoMo
            $partnerCode = $responseData['partnerCode'];
            $orderId = $responseData['orderId'];
            $requestId = $responseData['requestId'];
            $amount = $responseData['amount'];
            $transId = $responseData['transId'];
            $resultCode = $responseData['resultCode'];
            $message = $responseData['message'];

            // Kiểm tra giao dịch có hợp lệ hay không
            if ($resultCode == 0) {
                // Tìm giao dịch trong cơ sở dữ liệu theo transaction_id (orderId)
                $payment = Payment::where('transaction_id', $orderId)->first();
                if ($payment) {
                    // Cập nhật thông tin thanh toán khi MoMo trả về
                    $payment->momo_transaction_id = $transId;
                    $payment->momo_payment_request_id = $requestId;
                    $payment->momo_status = $message;
                    $payment->status = 'successful';
                    $payment->payment_status = 'approved';
                    $payment->completed_at = Carbon::now();
                    $payment->save();
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
                        $user->products()->attach($product->id, [
                            'purchased_at' => now(),
                            'expired_at' => $expirationDate,
                        ]);
                    }
                    flash()->success('Giao dịch thành công');
                    return redirect()->route('home');
                } else {
                    Log::error('Giao dịch không tồn tại trong cơ sở dữ liệu', ['orderId' => $orderId]);
                    flash()->error('Không tìm thấy giao dịch');
                    return redirect()->route('home');
                }
            } else {
                Log::error('MoMo giao dịch thất bại', ['orderId' => $orderId, 'resultCode' => $resultCode]);
                flash()->error('Giao dịch thất bại');
                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi xử lý MoMo return', ['error' => $e->getMessage()]);
            flash()->error('Lỗi xử lý giao dịch: ' . $e->getMessage());
            return redirect()->route('home');
        }
    }





}
