<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentReceipt;
use Illuminate\View\View;

class PendingDeliveryController extends Controller
{
    public function index(): View
    {
        $deliveries = PaymentReceipt::query()
            ->where('provider', 'paystack')
            ->where('status', 'success')
            ->where('payment_type', 'order')
            ->orderByDesc('paid_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.pending-deliveries', [
            'deliveries' => $deliveries,
        ]);
    }
}
