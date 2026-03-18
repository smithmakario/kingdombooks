<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    protected $fillable = [
        'reference',
        'payment_type',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'delivery_preference',
        'items_description',
        'amount_kobo',
        'currency',
        'provider',
        'status',
        'paid_at',
        'email_sent_at',
        'paystack_payload',
    ];

    protected function casts(): array
    {
        return [
            'amount_kobo' => 'integer',
            'paid_at' => 'datetime',
            'email_sent_at' => 'datetime',
            'paystack_payload' => 'array',
        ];
    }
}
