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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile', 15)->nullable()->unique()->after('email');
            $table->timestamp('mobile_verified_at')->nullable()->after('email_verified_at');
            $table->string('avatar')->nullable()->after('mobile_verified_at');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('avatar');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->boolean('newsletter_subscribed')->default(false)->after('date_of_birth');
            $table->boolean('is_active')->default(true)->after('newsletter_subscribed');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->softDeletes();

            $table->index('mobile');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['mobile']);
            $table->dropIndex(['is_active']);
            $table->dropColumn([
                'mobile',
                'mobile_verified_at',
                'avatar',
                'gender',
                'date_of_birth',
                'newsletter_subscribed',
                'is_active',
                'last_login_at',
                'last_login_ip',
                'deleted_at'
            ]);
        });
    }
};
