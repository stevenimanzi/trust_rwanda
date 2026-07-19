<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id');
            $table->string('full_name', 100);
            $table->string('email', 100);
            $table->string('phone', 20);
            $table->text('address')->nullable();
            $table->string('password', 255);
            $table->string('role')->default('customer');
            $table->string('shop_name', 100)->nullable();
            $table->string('shop_logo', 255)->nullable();
            $table->text('shop_description')->nullable();
            $table->string('logo_url', 255)->nullable();
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->boolean('is_verified')->nullable()->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('reset_token', 255)->nullable();
            $table->dateTime('token_expiry')->nullable();
            $table->string('subscription_status')->nullable()->default('pending');
            $table->dateTime('subscription_expires_at')->nullable();
            $table->string('otp_code', 6)->nullable();
            $table->dateTime('otp_expiry')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
