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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('size');
            $table->string('color');
            $table->string('color_code')->nullable(); // Hex color code
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->decimal('price_adjustment', 10, 2)->default(0); // Additional price for this variant
            $table->string('variant_sku')->nullable();
            $table->timestamps();

            $table->index('product_id');
            $table->index(['size', 'color']);
            $table->index('stock_quantity');
            $table->unique(['product_id', 'size', 'color']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
