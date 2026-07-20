<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->id();
            $table->integer('referrer_id');
            $table->integer('buyer_id');
            $table->integer('order_id');
            $table->integer('product_id');
            $table->decimal('product_price');
            $table->decimal('commission_amount');
            $table->string('status', 50)->nullable()->default('pending');
            $table->dateTime('created_at')->nullable()->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_commissions');
    }
};
