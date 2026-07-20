<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->decimal('total_amount');
            $table->string('payment_method', 50)->default('momo');
            $table->string('payment_status')->nullable()->default('pending');
            $table->string('delivery_status')->nullable()->default('pending');
            $table->text('delivery_address')->nullable();
            $table->string('delivery_phone', 20)->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
