<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - Kingdom Books</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f7f7f7; color: #1e1e1e; }
        .wrap { max-width: 760px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 8px 30px rgba(0,0,0,.08); overflow: hidden; }
        .header { background: #14233b; color: #fff; padding: 28px; }
        .header h1 { margin: 0 0 4px; font-size: 1.5rem; }
        .header p { margin: 0; opacity: .9; }
        .content { padding: 28px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 24px; }
        .label { font-size: .75rem; text-transform: uppercase; color: #666; letter-spacing: .05em; margin-bottom: 4px; }
        .value { font-size: .95rem; font-weight: 600; }
        .line { margin: 18px 0; border-top: 1px solid #ececec; }
        .amount { font-size: 2rem; color: #0e7b4d; font-weight: 700; }
        .actions { margin-top: 22px; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { display: inline-block; padding: 12px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; }
        .btn-primary { background: #14233b; color: #fff; }
        .btn-secondary { border: 1px solid #14233b; color: #14233b; background: #fff; }
        .note { margin-top: 14px; color: #666; font-size: .9rem; }
        @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } .content { padding: 20px; } }
    </style>
</head>
<body>
<main class="wrap">
    <section class="header">
        <h1>Payment Receipt</h1>
        <p>Your payment has been confirmed successfully.</p>
    </section>

    <section class="content">
        <div class="grid">
            <div>
                <div class="label">Receipt Reference</div>
                <div class="value">{{ $receipt->reference }}</div>
            </div>
            <div>
                <div class="label">Payment Date</div>
                <div class="value">{{ optional($receipt->paid_at)->format('M d, Y h:i A') }}</div>
            </div>
            <div>
                <div class="label">Customer</div>
                <div class="value">{{ $receipt->customer_name }}</div>
            </div>
            <div>
                <div class="label">Email</div>
                <div class="value">{{ $receipt->customer_email }}</div>
            </div>
            <div>
                <div class="label">Phone</div>
                <div class="value">{{ $receipt->customer_phone ?: 'N/A' }}</div>
            </div>
            <div>
                <div class="label">Payment Type</div>
                <div class="value">{{ ucfirst($receipt->payment_type) }}</div>
            </div>
        </div>

        <div class="line"></div>

        <div class="label">Items</div>
        <div class="value">{{ $receipt->items_description ?: 'N/A' }}</div>

        @if($receipt->customer_address)
            <div class="line"></div>
            <div class="label">Delivery Address</div>
            <div class="value">{{ $receipt->customer_address }}</div>
        @endif

        @if($receipt->delivery_preference)
            <div style="margin-top: 12px;">
                <div class="label">Delivery Preference</div>
                <div class="value">{{ $receipt->delivery_preference }}</div>
            </div>
        @endif

        <div class="line"></div>

        <div class="label">Amount Paid</div>
        <div class="amount">₦{{ number_format($receipt->amount_kobo / 100, 2) }}</div>

        <div class="actions">
            <a class="btn btn-primary" href="{{ route('receipts.download', $receipt->reference) }}">Download PDF Receipt</a>
            <a class="btn btn-secondary" href="/">Back to Home</a>
        </div>

        <p class="note">A copy of this receipt has been sent to your email if mail delivery is configured.</p>
    </section>
</main>
</body>
</html>
