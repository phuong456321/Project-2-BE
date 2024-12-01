<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $products = Product::all();
    
            // Lấy gói Premium đang sử dụng của người dùng, nếu có
            $activeProduct = $user->products()
        ->wherePivot('expired_at', '>', now())
        ->orderBy('user_product.expired_at', 'desc') // Thêm phần alias
         ->first();
    
            return view('user.premium', compact('products', 'user', 'activeProduct'));
        } catch (\Exception $e) {
            flash()->addError('Đã xảy ra lỗi: ' . $e->getMessage());
            return redirect()->back();
        }    
        
    }

// Hiển thị trang checkout
public function show(Request $request)
{
    $selectedPlan = $request->query('product'); // Nhận gói được chọn từ query string
    $product = Product::find($selectedPlan);

    if (!$product) {
        return redirect()->route('premium'); // Quay lại trang Premium nếu plan không hợp lệ
    }

    return view('checkout/checkout', [
        'product' => $product,
    ]);
}

// Xử lý thanh toán
public function processPayment(Request $request)
{
    $paymentMethod = $request->input('payment_method');
    $amount = $request->input('amount'); // Số tiền cần thanh toán
    $name = $request->input('name'); // Tên gói được chọn
    if ($paymentMethod === 'stripe') {
        return redirect()->route('stripe.stripe', ['amount' => $amount, 'name' => $name]);
    } elseif ($paymentMethod === 'momo') {
        return redirect()->route('momo.createPayment', ['amount' => $amount]);
    }

    return back()->with('error', 'Invalid payment method selected!');
}
}
