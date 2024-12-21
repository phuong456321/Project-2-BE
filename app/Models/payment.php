<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stripe_payment_intent_id',
        'stripe_checkout_session_id',
        'momo_transaction_id',
        'momo_payment_request_id',
        'momo_status',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_status',
        'product_id',
        'quantity',
        'price',
        'tax_amount',
        'fee_amount',
        'total_amount',
        'completed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
