<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Update</title>
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

        h1 {
            color: #1a1a2e;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-packed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-shipped {
            background: #cce5ff;
            color: #004085;
        }

        .status-out_for_delivery {
            background: #fff3cd;
            color: #856404;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .timeline {
            margin: 30px 0;
            padding-left: 30px;
            border-left: 3px solid #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -36px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e9ecef;
        }

        .timeline-item.completed::before {
            background: #28a745;
        }

        .timeline-item.current::before {
            background: #e94560;
            box-shadow: 0 0 0 4px rgba(233, 69, 96, 0.2);
        }

        .timeline-title {
            font-weight: 600;
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

        .order-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
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

        <h1>Order Update</h1>
        <p style="color: #666;">Order #{{ $order->order_number }}</p>

        <p>Hi {{ $order->user->display_name }},</p>

        @php
        $statusMessages = [
        'confirmed' => 'Great news! Your order has been confirmed and is being prepared.',
        'packed' => 'Your order has been packed and is ready for shipping.',
        'shipped' => 'Your order is on its way! It has been handed over to our courier partner.',
        'out_for_delivery' => 'Your order is out for delivery. Please ensure someone is available to receive it.',
        'delivered' => 'Your order has been delivered successfully. We hope you love your purchase!',
        'cancelled' => 'Your order has been cancelled. If you have any questions, please contact our support.',
        ];
        @endphp

        <p>{{ $statusMessages[$order->order_status] ?? 'Your order status has been updated.' }}</p>

        <div style="text-align: center;">
            <span class="status-badge status-{{ $order->order_status }}">
                {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
            </span>
        </div>

        @if($order->awb_number && in_array($order->order_status, ['shipped', 'out_for_delivery']))
        <div class="order-box">
            <strong>Tracking Details</strong><br>
            Courier: {{ $order->courier_name }}<br>
            Tracking Number: {{ $order->awb_number }}
        </div>
        @endif

        @php
        $statuses = ['confirmed', 'packed', 'shipped', 'out_for_delivery', 'delivered'];
        $currentIndex = array_search($order->order_status, $statuses);
        @endphp

        @if($order->order_status !== 'cancelled')
        <div class="timeline">
            @foreach($statuses as $index => $status)
            @php
            $isCompleted = $index < $currentIndex;
                $isCurrent=$index===$currentIndex;
                @endphp
                <div class="timeline-item {{ $isCompleted ? 'completed' : ($isCurrent ? 'current' : '') }}">
                <div class="timeline-title">{{ ucfirst(str_replace('_', ' ', $status)) }}</div>
        </div>
        @endforeach
    </div>
    @endif

    <div style="text-align: center;">
        <a href="{{ route('account.orders.show', $order) }}" class="btn">View Order Details</a>
    </div>

    <div class="footer">
        <p>Need help? Contact us at <a href="mailto:support@store.com">support@store.com</a></p>
        <p>&copy; {{ date('Y') }} FashionHub. All rights reserved.</p>
    </div>
    </div>
</body>

</html>