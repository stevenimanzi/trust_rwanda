<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id');
            $table->integer('category_id')->nullable();
            $table->string('category', 50);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->decimal('price');
            $table->string('price_unit', 20)->nullable();
            $table->string('image_url', 255)->nullable();
            $table->integer('stock_quantity')->nullable()->default(0);
            $table->boolean('is_fresh_produce')->nullable()->default(0);
            $table->date('harvest_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('batch_number', 50)->nullable();
            $table->boolean('is_visible')->nullable()->default(1);
            $table->integer('views')->nullable()->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->boolean('is_flash_deal')->nullable()->default(0);
            $table->integer('discount_percent')->nullable()->default(0);
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->integer('views_count')->nullable()->default(0);
            $table->string('promo_status')->nullable()->default('none');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
