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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('restrict');
            $table->foreignId('order_item_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');

            $table->enum('reason', [
                'wrong_size',
                'damaged',
                'defective',
                'not_as_described',
                'changed_mind',
                'other'
            ]);
            $table->text('description')->nullable();
            $table->json('images')->nullable();

            $table->enum('return_method', ['self_ship', 'pickup'])->default('pickup');
            $table->string('pickup_awb')->nullable();

            $table->enum('status', [
                'requested',
                'approved',
                'rejected',
                'pickup_scheduled',
                'picked_up',
                'received',
                'qc_passed',
                'qc_failed',
                'refund_initiated',
                'refund_completed',
                'cancelled'
            ])->default('requested');

            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();

            // Refund details
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->enum('refund_status', ['pending', 'processing', 'completed', 'failed'])->nullable();
            $table->string('refund_transaction_id')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
