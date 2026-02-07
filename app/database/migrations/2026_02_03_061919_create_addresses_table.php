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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('mobile', 15);
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('landmark')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pincode', 10);
            $table->enum('address_type', ['home', 'work', 'other'])->default('home');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index('user_id');
            $table->index('pincode');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
