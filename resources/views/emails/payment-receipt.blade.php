<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #1e1e1e;">
    <p>Hello {{ $receipt->customer_name }},</p>

    <p>Thank you for your payment. Your transaction has been confirmed.</p>

    <p>
        <strong>Reference:</strong> {{ $receipt->reference }}<br>
        <strong>Amount:</strong> ₦{{ number_format($receipt->amount_kobo / 100, 2) }}<br>
        <strong>Payment Date:</strong> {{ optional($receipt->paid_at)->format('M d, Y h:i A') }}<br>
        <strong>Items:</strong> {{ $receipt->items_description ?: 'N/A' }}
    </p>

    <p>
        Download your PDF receipt here:
        <a href="{{ $downloadUrl }}">{{ $downloadUrl }}</a>
    </p>

    <p>Regards,<br>Kingdom Books Team</p>
</body>
</html>
