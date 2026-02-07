<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>

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
            --primary-dark: #600018;
            --accent-color: #B8956A;
            --accent-light: #d4b896;
            --sidebar-width: 260px;
            --header-height: 70px;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.18);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand h4 {
            color: #fff;
            font-weight: 600;
            margin: 0;
        }

        .sidebar-brand small {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu .menu-header {
            padding: 0.75rem 1.5rem 0.5rem;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .sidebar-menu .nav-link {
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .sidebar-menu .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            border-left-color: var(--accent-color);
        }

        .sidebar-menu .nav-link i {
            font-size: 1.1rem;
            width: 24px;
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Enhanced Header */
        .admin-header {
            height: var(--header-height);
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            border-bottom: 1px solid #e9ecef;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.03);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-center {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Search Bar */
        .header-search {
            position: relative;
            width: 320px;
        }

        .header-search .search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .header-search .search-icon {
            position: absolute;
            left: 14px;
            color: #6c757d;
            font-size: 1rem;
            z-index: 2;
            transition: color 0.2s ease;
        }

        .header-search .search-input {
            width: 100%;
            padding: 0.65rem 1rem 0.65rem 2.75rem;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            font-size: 0.875rem;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .header-search .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(128, 0, 32, 0.1), 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .header-search .search-input:focus+.search-icon,
        .header-search .search-input:focus~.search-icon {
            color: var(--primary-color);
        }

        .header-search .search-shortcut {
            position: absolute;
            right: 12px;
            background: #f1f3f4;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            font-size: 0.7rem;
            color: #6c757d;
            font-weight: 500;
            border: 1px solid #e0e0e0;
        }

        .header-search .search-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            border: 1px solid #e9ecef;
            display: none;
            z-index: 1000;
            overflow: hidden;
        }

        .header-search .search-dropdown.show {
            display: block;
            animation: slideDown 0.2s ease;
        }

        .search-dropdown-header {
            padding: 0.75rem 1rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .search-dropdown-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .search-dropdown-item:hover {
            background: #f8f9fa;
        }

        .search-dropdown-item i {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        /* Date Time Card */
        .datetime-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border-radius: 12px;
            color: #fff;
            box-shadow: 0 4px 15px rgba(128, 0, 32, 0.25);
        }

        .datetime-card .datetime-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .datetime-card .datetime-content {
            line-height: 1.3;
        }

        .datetime-card .time {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .datetime-card .date {
            font-size: 0.7rem;
            opacity: 0.85;
            font-weight: 500;
        }

        /* Notification Bell */
        .notification-btn {
            position: relative;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .notification-btn:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .notification-btn i {
            font-size: 1.25rem;
            color: #495057;
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 18px;
            height: 18px;
            background: #dc3545;
            border-radius: 50%;
            font-size: 0.65rem;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .notification-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 360px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid #e9ecef;
            display: none;
            z-index: 1001;
            overflow: hidden;
        }

        .notification-dropdown.show {
            display: block;
            animation: slideDown 0.25s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .notification-header .mark-read {
            font-size: 0.75rem;
            color: var(--primary-color);
            cursor: pointer;
            font-weight: 500;
        }

        .notification-header .mark-read:hover {
            text-decoration: underline;
        }

        .notification-list {
            max-height: 320px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1rem 1.25rem;
            display: flex;
            gap: 0.875rem;
            border-bottom: 1px solid #f1f3f4;
            transition: background 0.15s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .notification-item.unread {
            background: #fff5f7;
        }

        .notification-item .notif-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-item .notif-icon.order {
            background: #d4edda;
            color: #155724;
        }

        .notification-item .notif-icon.stock {
            background: #fff3cd;
            color: #856404;
        }

        .notification-item .notif-icon.review {
            background: #cce5ff;
            color: #004085;
        }

        .notification-item .notif-icon.alert {
            background: #f8d7da;
            color: #721c24;
        }

        .notification-item .notif-content {
            flex: 1;
            min-width: 0;
        }

        .notification-item .notif-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1a1a2e;
            margin-bottom: 0.25rem;
        }

        .notification-item .notif-text {
            font-size: 0.8rem;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notification-item .notif-time {
            font-size: 0.7rem;
            color: #adb5bd;
            margin-top: 0.25rem;
        }

        .notification-footer {
            padding: 0.875rem 1.25rem;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .notification-footer a {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary-color);
            text-decoration: none;
        }

        .notification-footer a:hover {
            text-decoration: underline;
        }

        /* User Profile Dropdown */
        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem 0.5rem 0.5rem;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .user-profile-btn:hover {
            background: #f8f9fa;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .user-avatar {
            position: relative;
            width: 38px;
            height: 38px;
        }

        .user-avatar img,
        .user-avatar .avatar-initials {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            object-fit: cover;
        }

        .user-avatar .avatar-initials {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-avatar .online-status {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background: #28a745;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .user-info {
            text-align: left;
            line-height: 1.3;
        }

        .user-info .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1a1a2e;
        }

        .user-info .user-role {
            font-size: 0.7rem;
            color: #6c757d;
        }

        .user-profile-btn .dropdown-arrow {
            color: #adb5bd;
            font-size: 0.75rem;
            transition: transform 0.2s ease;
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 240px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid #e9ecef;
            display: none;
            z-index: 1001;
            overflow: hidden;
        }

        .user-dropdown.show {
            display: block;
            animation: slideDown 0.25s ease;
        }

        .user-dropdown-header {
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: #fff;
        }

        .user-dropdown-header .dropdown-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .user-dropdown-header .dropdown-name {
            font-weight: 600;
            font-size: 1rem;
        }

        .user-dropdown-header .dropdown-email {
            font-size: 0.75rem;
            opacity: 0.85;
        }

        .user-dropdown-menu {
            padding: 0.5rem 0;
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            color: #495057;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }

        .user-dropdown-item:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        .user-dropdown-item i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .user-dropdown-divider {
            height: 1px;
            background: #e9ecef;
            margin: 0.5rem 0;
        }

        .user-dropdown-item.logout {
            color: #dc3545;
        }

        .user-dropdown-item.logout:hover {
            background: #fff5f5;
            color: #dc3545;
        }

        .admin-content {
            padding: 1.5rem;
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            border: 1px solid #e9ecef;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a1a2e;
        }

        .stat-card .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
        }

        /* Utility Classes */
        .bg-primary-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        }

        .bg-accent-gradient {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
        }

        /* Table Styles */
        .table-modern {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .table-modern th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-bottom: 2px solid #e9ecef;
        }

        .table-modern td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 1199.98px) {
            .header-search {
                width: 240px;
            }

            .datetime-card .date {
                display: none;
            }
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .header-search {
                display: none;
            }

            .datetime-card {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .user-info {
                display: none;
            }

            .notification-dropdown {
                width: 300px;
                right: -60px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-bag-heart me-2"></i>E-Commerce</h4>
            <small>Admin Panel</small>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-header">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                <span>Dashboard</span>
            </a>

            <div class="menu-header">Catalog</div>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i>
                <span>Categories</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Products</span>
            </a>

            <div class="menu-header">Sales</div>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Orders</span>
            </a>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Customers</span>
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i>
                <span>Coupons</span>
            </a>

            <div class="menu-header">System</div>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <!-- Left Section: Toggle + Search -->
            <div class="header-left">
                <button class="btn btn-link d-lg-none p-0" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <!-- Search Bar -->
                <div class="header-search">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="search-input" id="headerSearch" placeholder="Search products, orders..." autocomplete="off">
                        <span class="search-shortcut">Ctrl+K</span>
                    </div>
                    <div class="search-dropdown" id="searchDropdown">
                        <div class="search-dropdown-header">Quick Actions</div>
                        <div class="search-dropdown-item" onclick="window.location.href='{{ route('admin.products.create') }}'">
                            <i class="bi bi-plus-circle" style="background: #d4edda; color: #155724;"></i>
                            <span>Add New Product</span>
                        </div>
                        <div class="search-dropdown-item" onclick="window.location.href='{{ route('admin.orders.index') }}'">
                            <i class="bi bi-receipt" style="background: #cce5ff; color: #004085;"></i>
                            <span>View Orders</span>
                        </div>
                        <div class="search-dropdown-item" onclick="window.location.href='{{ route('admin.customers.index') }}'">
                            <i class="bi bi-people" style="background: #fff3cd; color: #856404;"></i>
                            <span>Manage Customers</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Center Section: Date/Time -->
            <div class="header-center">
                <div class="datetime-card">
                    <div class="datetime-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div class="datetime-content">
                        <div class="time" id="liveTime">00:00:00</div>
                        <div class="date" id="liveDate">Loading...</div>
                    </div>
                </div>
            </div>

            <!-- Right Section: Notifications + Profile -->
            <div class="header-right">
                <!-- Notifications -->
                <div class="position-relative" id="notificationWrapper">
                    <button class="notification-btn" id="notificationBtn">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge" id="notifBadge">3</span>
                    </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h6>Notifications</h6>
                            <span class="mark-read" id="markAllRead">Mark all as read</span>
                        </div>
                        <div class="notification-list">
                            <div class="notification-item unread">
                                <div class="notif-icon order">
                                    <i class="bi bi-bag-check"></i>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-title">New Order Received</div>
                                    <div class="notif-text">Order #ORD-2024-001 worth ₹2,499</div>
                                    <div class="notif-time">2 minutes ago</div>
                                </div>
                            </div>
                            <div class="notification-item unread">
                                <div class="notif-icon stock">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-title">Low Stock Alert</div>
                                    <div class="notif-text">Premium Cotton Shirt (M, Blue) - Only 3 left</div>
                                    <div class="notif-time">15 minutes ago</div>
                                </div>
                            </div>
                            <div class="notification-item unread">
                                <div class="notif-icon review">
                                    <i class="bi bi-star"></i>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-title">New Review Submitted</div>
                                    <div class="notif-text">5-star review on Designer Kurta Set</div>
                                    <div class="notif-time">1 hour ago</div>
                                </div>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="#">View All Notifications</a>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="position-relative" id="profileWrapper">
                    <div class="user-profile-btn" id="profileBtn">
                        <div class="user-avatar">
                            @if(Auth::guard('admin')->user()->avatar)
                            <img src="{{ asset('storage/' . Auth::guard('admin')->user()->avatar) }}" alt="Avatar">
                            @else
                            <div class="avatar-initials">
                                {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 2)) }}
                            </div>
                            @endif
                            <span class="online-status"></span>
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ Auth::guard('admin')->user()->name }}</div>
                            <div class="user-role">{{ ucfirst(Auth::guard('admin')->user()->role ?? 'Administrator') }}</div>
                        </div>
                        <i class="bi bi-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="user-dropdown" id="profileDropdown">
                        <div class="user-dropdown-header">
                            <div class="dropdown-avatar">
                                {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 2)) }}
                            </div>
                            <div class="dropdown-name">{{ Auth::guard('admin')->user()->name }}</div>
                            <div class="dropdown-email">{{ Auth::guard('admin')->user()->email }}</div>
                        </div>
                        <div class="user-dropdown-menu">
                            <a href="{{ route('admin.profile') }}" class="user-dropdown-item">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="user-dropdown-item">
                                <i class="bi bi-gear"></i>
                                <span>Settings</span>
                            </a>
                            <div class="user-dropdown-divider"></div>
                            <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="user-dropdown-item logout w-100 border-0 bg-transparent text-start">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="admin-content">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('show');
        });

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.getElementById('sidebarToggle');

            if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // ===== Live Date/Time =====
        function updateDateTime() {
            const now = new Date();
            const timeEl = document.getElementById('liveTime');
            const dateEl = document.getElementById('liveDate');

            if (timeEl) {
                timeEl.textContent = now.toLocaleTimeString('en-IN', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });
            }

            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('en-IN', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // ===== Search Dropdown =====
        const searchInput = document.getElementById('headerSearch');
        const searchDropdown = document.getElementById('searchDropdown');

        searchInput?.addEventListener('focus', function() {
            searchDropdown?.classList.add('show');
        });

        searchInput?.addEventListener('blur', function(e) {
            setTimeout(() => {
                searchDropdown?.classList.remove('show');
            }, 200);
        });

        // Keyboard shortcut (Ctrl+K)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput?.focus();
            }
        });

        // ===== Notification Dropdown =====
        const notifBtn = document.getElementById('notificationBtn');
        const notifDropdown = document.getElementById('notificationDropdown');
        const notifWrapper = document.getElementById('notificationWrapper');

        notifBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            notifDropdown?.classList.toggle('show');
            // Close profile dropdown if open
            document.getElementById('profileDropdown')?.classList.remove('show');
        });

        // Mark all as read
        document.getElementById('markAllRead')?.addEventListener('click', function() {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            const badge = document.getElementById('notifBadge');
            if (badge) {
                badge.style.display = 'none';
            }
        });

        // ===== Profile Dropdown =====
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        const profileWrapper = document.getElementById('profileWrapper');

        profileBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown?.classList.toggle('show');
            // Close notification dropdown if open
            notifDropdown?.classList.remove('show');
        });

        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            if (!notifWrapper?.contains(e.target)) {
                notifDropdown?.classList.remove('show');
            }
            if (!profileWrapper?.contains(e.target)) {
                profileDropdown?.classList.remove('show');
            }
        });

        // Close dropdowns on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                notifDropdown?.classList.remove('show');
                profileDropdown?.classList.remove('show');
                searchDropdown?.classList.remove('show');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>