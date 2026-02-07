<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed', 'free_shipping', 'buy_x_get_y'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('min_order_value', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->timestamp('valid_from');
            $table->timestamp('valid_to');
            $table->integer('usage_limit')->nullable(); // Total uses allowed
            $table->integer('usage_per_customer')->default(1);
            $table->integer('times_used')->default(0);
            $table->json('applicable_categories')->nullable(); // Array of category IDs
            $table->json('applicable_products')->nullable(); // Array of product IDs
            $table->boolean('auto_apply')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('code');
            $table->index('is_active');
            $table->index(['valid_from', 'valid_to']);
        });

        // Coupon usage tracking
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('discount_applied', 10, 2);
            $table->timestamps();

            $table->index(['coupon_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
    }
};
