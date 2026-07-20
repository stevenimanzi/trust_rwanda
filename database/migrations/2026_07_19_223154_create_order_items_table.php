<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->integer('vendor_id');
            $table->integer('quantity');
            $table->decimal('price_at_purchase');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
