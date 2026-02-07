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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('discount_type', ['none', 'percentage', 'fixed'])->default('none');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->string('fabric')->nullable();
            $table->text('care_instructions')->nullable();
            $table->enum('gender', ['men', 'women', 'unisex', 'kids'])->default('unisex');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_new_arrival');
            $table->index('gender');
            $table->index(['price', 'discount_value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
