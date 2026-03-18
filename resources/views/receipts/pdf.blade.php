<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $receipt->reference }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1e1e1e; font-size: 13px; }
        .header { border-bottom: 2px solid #14233b; padding-bottom: 10px; margin-bottom: 16px; }
        .header h1 { margin: 0; font-size: 20px; color: #14233b; }
        .muted { color: #666; }
        .row { margin: 8px 0; }
        .label { font-weight: bold; display: inline-block; min-width: 170px; }
        .amount { font-size: 18px; color: #0e7b4d; font-weight: bold; margin-top: 12px; }
        .footer { margin-top: 24px; font-size: 11px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kingdom Books - Payment Receipt</h1>
        <div class="muted">Reference: {{ $receipt->reference }}</div>
    </div>

    <div class="row"><span class="label">Payment Date:</span> {{ optional($receipt->paid_at)->format('M d, Y h:i A') }}</div>
    <div class="row"><span class="label">Customer Name:</span> {{ $receipt->customer_name }}</div>
    <div class="row"><span class="label">Email:</span> {{ $receipt->customer_email }}</div>
    <div class="row"><span class="label">Phone:</span> {{ $receipt->customer_phone ?: 'N/A' }}</div>
    <div class="row"><span class="label">Payment Type:</span> {{ ucfirst($receipt->payment_type) }}</div>
    <div class="row"><span class="label">Items:</span> {{ $receipt->items_description ?: 'N/A' }}</div>
    <div class="row"><span class="label">Delivery Address:</span> {{ $receipt->customer_address ?: 'N/A' }}</div>
    <div class="row"><span class="label">Delivery Preference:</span> {{ $receipt->delivery_preference ?: 'N/A' }}</div>
    <div class="amount">Amount Paid: ₦{{ number_format($receipt->amount_kobo / 100, 2) }}</div>

    <div class="footer">
        This document confirms that payment was received successfully through Paystack.
    </div>
</body>
</html>
