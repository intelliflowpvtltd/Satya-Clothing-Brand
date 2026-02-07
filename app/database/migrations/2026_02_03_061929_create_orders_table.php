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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('order_number')->unique();
            $table->foreignId('address_id')->constrained()->onDelete('restrict');

            // Pricing
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('cgst_amount', 12, 2)->default(0);
            $table->decimal('sgst_amount', 12, 2)->default(0);
            $table->decimal('igst_amount', 12, 2)->default(0);
            $table->decimal('shipping_charges', 12, 2)->default(0);
            $table->decimal('cod_charges', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);

            // Coupon (FK added in separate migration after coupons table exists)
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();

            // Payment
            $table->enum('payment_method', ['upi', 'card', 'netbanking', 'wallet', 'emi', 'cod'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('pending');
            $table->string('payment_gateway')->nullable(); // razorpay, payu, etc.
            $table->string('transaction_id')->nullable();
            $table->json('payment_details')->nullable();

            // Order status
            $table->enum('order_status', [
                'pending',
                'confirmed',
                'packed',
                'shipped',
                'out_for_delivery',
                'delivered',
                'cancelled',
                'returned'
            ])->default('pending');

            // Shipping
            $table->string('courier_name')->nullable();
            $table->string('awb_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->date('expected_delivery_date')->nullable();

            // Additional
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->boolean('is_gift')->default(false);
            $table->text('gift_message')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('order_status');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
