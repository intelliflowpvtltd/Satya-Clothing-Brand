<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .container {
            background: #fff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .logo span {
            color: #e94560;
        }

        .success-icon {
            font-size: 48px;
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            color: #1a1a2e;
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center;
        }

        .order-number {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        .order-items {
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .item-name {
            font-weight: 600;
        }

        .item-variant {
            font-size: 13px;
            color: #666;
        }

        .summary {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .summary-row.total {
            font-weight: 700;
            font-size: 18px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .address-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .address-box h3 {
            margin-top: 0;
            font-size: 16px;
        }

        .btn {
            display: inline-block;
            background: #e94560;
            color: #fff !important;
            padding: 14px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">Fashion<span>Hub</span></div>
        </div>

        <div class="success-icon">✓</div>
        <h1>Order Confirmed!</h1>
        <div class="order-number">Order #{{ $order->order_number }}</div>

        <p>Hi {{ $order->user->display_name }},</p>
        <p>Thank you for your order! We've received it and will start processing it shortly.</p>

        <div class="order-items">
            <h3>Order Items</h3>
            @foreach($order->items as $item)
            <div class="item">
                <div>
                    <div class="item-name">{{ $item->product_name }}</div>
                    <div class="item-variant">{{ $item->variant_details }} × {{ $item->quantity }}</div>
                </div>
                <div>₹{{ number_format($item->total, 0) }}</div>
            </div>
            @endforeach
        </div>

        <div class="summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>₹{{ number_format($order->subtotal, 0) }}</span>
            </div>
            @if($order->discount > 0)
            <div class="summary-row">
                <span>Discount</span>
                <span style="color: #28a745;">-₹{{ number_format($order->discount, 0) }}</span>
            </div>
            @endif
            @if($order->cod_charges > 0)
            <div class="summary-row">
                <span>COD Charges</span>
                <span>₹{{ number_format($order->cod_charges, 0) }}</span>
            </div>
            @endif
            <div class="summary-row total">
                <span>Total</span>
                <span>₹{{ number_format($order->total_amount, 0) }}</span>
            </div>
        </div>

        <div class="address-box">
            <h3>Delivery Address</h3>
            <p style="margin: 0;">
                <strong>{{ $order->address->full_name }}</strong><br>
                {{ $order->address->address_line1 }}<br>
                @if($order->address->address_line2){{ $order->address->address_line2 }}<br>@endif
                {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}<br>
                Phone: {{ $order->address->mobile }}
            </p>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('account.orders.show', $order) }}" class="btn">Track Order</a>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} FashionHub. All rights reserved.</p>
        </div>
    </div>
</body>

</html>