<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');

        // Define settings structure
        $settingsGroups = [
            'general' => [
                'site_name' => ['label' => 'Site Name', 'type' => 'text', 'default' => 'E-Commerce Store'],
                'site_tagline' => ['label' => 'Tagline', 'type' => 'text', 'default' => 'Your one-stop fashion destination'],
                'site_email' => ['label' => 'Contact Email', 'type' => 'email', 'default' => 'info@example.com'],
                'site_phone' => ['label' => 'Contact Phone', 'type' => 'text', 'default' => '+91 9876543210'],
                'site_address' => ['label' => 'Address', 'type' => 'textarea', 'default' => ''],
            ],
            'store' => [
                'currency' => ['label' => 'Currency', 'type' => 'text', 'default' => 'INR'],
                'currency_symbol' => ['label' => 'Currency Symbol', 'type' => 'text', 'default' => '₹'],
                'gst_rate' => ['label' => 'GST Rate (%)', 'type' => 'number', 'default' => '18'],
                'gst_number' => ['label' => 'GST Number', 'type' => 'text', 'default' => ''],
                'min_order_amount' => ['label' => 'Min Order Amount', 'type' => 'number', 'default' => '0'],
                'free_shipping_threshold' => ['label' => 'Free Shipping Above', 'type' => 'number', 'default' => '999'],
                'shipping_charge' => ['label' => 'Shipping Charge', 'type' => 'number', 'default' => '49'],
            ],
            'orders' => [
                'order_prefix' => ['label' => 'Order Number Prefix', 'type' => 'text', 'default' => 'ORD'],
                'return_days' => ['label' => 'Return Window (days)', 'type' => 'number', 'default' => '7'],
                'cod_enabled' => ['label' => 'Cash on Delivery', 'type' => 'boolean', 'default' => '1'],
                'cod_min_amount' => ['label' => 'COD Min Amount', 'type' => 'number', 'default' => '0'],
                'cod_max_amount' => ['label' => 'COD Max Amount', 'type' => 'number', 'default' => '10000'],
            ],
            'social' => [
                'facebook_url' => ['label' => 'Facebook URL', 'type' => 'url', 'default' => ''],
                'instagram_url' => ['label' => 'Instagram URL', 'type' => 'url', 'default' => ''],
                'twitter_url' => ['label' => 'Twitter/X URL', 'type' => 'url', 'default' => ''],
                'youtube_url' => ['label' => 'YouTube URL', 'type' => 'url', 'default' => ''],
            ],
            'seo' => [
                'meta_title' => ['label' => 'Default Meta Title', 'type' => 'text', 'default' => ''],
                'meta_description' => ['label' => 'Default Meta Description', 'type' => 'textarea', 'default' => ''],
                'meta_keywords' => ['label' => 'Default Meta Keywords', 'type' => 'text', 'default' => ''],
                'google_analytics_id' => ['label' => 'Google Analytics ID', 'type' => 'text', 'default' => ''],
            ],
        ];

        // Get current values
        foreach ($settingsGroups as $group => &$fields) {
            foreach ($fields as $key => &$field) {
                $field['value'] = Setting::get($key, $field['default']);
            }
        }

        return view('admin.settings.index', compact('settingsGroups'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $settings = $request->except(['_token', '_method']);

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // Clear settings cache
        Cache::forget('settings');

        return back()->with('success', 'Settings updated successfully.');
    }
}
