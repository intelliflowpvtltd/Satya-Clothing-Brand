<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - Admin Panel</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #800020;
            --primary-light: #a0324d;
            --accent-color: #B8956A;
            --off-white: #FAF9F6;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--off-white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            max-width: 1000px;
            min-height: 580px;
            background: #fff;
            border-radius: 20px;
            display: flex;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            margin: 1rem;
        }

        .auth-brand {
            width: 45%;
            background: linear-gradient(135deg, var(--primary-color) 0%, #600018 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .auth-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }

        .auth-brand::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 80%;
            height: 80%;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 50%;
        }

        .auth-brand h2 {
            color: #fff;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .auth-brand p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }

        .auth-features {
            margin-top: 2rem;
            position: relative;
            z-index: 1;
        }

        .auth-features li {
            color: rgba(255, 255, 255, 0.9);
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .auth-features li i {
            color: var(--accent-color);
        }

        .auth-form {
            width: 55%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-form h3 {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 0.5rem;
        }

        .auth-form .subtitle {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .form-floating>.form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 1rem 0.75rem;
        }

        .form-floating>.form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(128, 0, 32, 0.1);
        }

        .btn-login {
            background: var(--primary-color);
            border: none;
            padding: 0.875rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #600018;
            transform: translateY(-1px);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .link-primary-custom {
            color: var(--primary-color);
        }

        .link-primary-custom:hover {
            color: #600018;
        }

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                max-width: 450px;
            }

            .auth-brand {
                width: 100%;
                padding: 2rem;
                min-height: auto;
            }

            .auth-form {
                width: 100%;
                padding: 2rem;
            }

            .auth-features {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-brand">
            <h2><i class="bi bi-bag-heart me-2"></i>E-Commerce</h2>
            <p>Welcome to the admin panel. Manage your store, products, orders, and customers all in one place.</p>

            <ul class="auth-features list-unstyled">
                <li><i class="bi bi-check-circle-fill"></i> Complete Store Management</li>
                <li><i class="bi bi-check-circle-fill"></i> Real-time Analytics & Reports</li>
                <li><i class="bi bi-check-circle-fill"></i> Order & Inventory Tracking</li>
                <li><i class="bi bi-check-circle-fill"></i> Customer Relationship Management</li>
            </ul>
        </div>

        <div class="auth-form">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>