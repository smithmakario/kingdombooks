<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_receipts', function (Blueprint $table): void {
            $table->id();
            $table->string('reference')->unique();
            $table->string('payment_type', 30)->default('order');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('delivery_preference')->nullable();
            $table->text('items_description')->nullable();
            $table->unsignedBigInteger('amount_kobo');
            $table->string('currency', 3)->default('NGN');
            $table->string('provider', 30)->default('paystack');
            $table->string('status', 30)->default('success');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->json('paystack_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_receipts');
    }
};
