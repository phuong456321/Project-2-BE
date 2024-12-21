<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            
            // Stripe specific fields
            $table->string('stripe_payment_intent_id')->nullable(); // Stripe Payment Intent ID
            $table->string('stripe_checkout_session_id')->nullable(); // Stripe Checkout Session ID
            
            // Momo specific fields
            $table->string('momo_transaction_id')->nullable(); // Momo Transaction ID
            $table->string('momo_payment_request_id')->nullable(); // Momo Payment Request ID
            $table->string('momo_status')->nullable(); // Trạng thái giao dịch Momo (successful, failed, pending, etc.)

            $table->string('transaction_id');
            $table->decimal('amount', 10, 2);  // Số tiền thanh toán (ví dụ: 100.50 USD)
            $table->string('currency');
            $table->string('status');  // Trạng thái giao dịch (succeeded, failed, etc.)
            $table->string('payment_method');  // Phương thức thanh toán (momo, stripe)
            $table->string('payment_status');  // Trạng thái thanh toán (approved, declined)
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);  // Số lượng sản phẩm
            $table->decimal('price', 10, 2);  // Giá sản phẩm
            $table->decimal('tax_amount', 10, 2)->nullable();  // Thuế nếu có
            $table->decimal('fee_amount', 10, 2)->nullable();  // Phí giao dịch nếu có
            $table->decimal('total_amount', 10, 2);  // Tổng số tiền thanh toán (bao gồm thuế và phí)
            $table->timestamp('completed_at')->nullable();  // Thời điểm giao dịch hoàn tất
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
