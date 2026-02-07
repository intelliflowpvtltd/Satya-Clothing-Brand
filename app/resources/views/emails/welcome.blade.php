<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
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
            margin-bottom: 20px;
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

        <h1>Welcome, {{ $user->display_name }}! 🎉</h1>

        <p>Thank you for joining FashionHub! We're excited to have you as part of our community.</p>

        <p>Here's what you can do now:</p>
        <ul>
            <li>Browse our latest collections</li>
            <li>Save your favorite items to wishlist</li>
            <li>Get exclusive offers and discounts</li>
            <li>Enjoy fast and free shipping on orders over ₹999</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ url('/shop') }}" class="btn">Start Shopping</a>
        </div>

        <p>If you have any questions, feel free to reach out to our support team at <a href="mailto:support@store.com">support@store.com</a>.</p>

        <p>Happy Shopping!<br>The FashionHub Team</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} FashionHub. All rights reserved.</p>
            <p>You received this email because you signed up at FashionHub.</p>
        </div>
    </div>
</body>

</html>