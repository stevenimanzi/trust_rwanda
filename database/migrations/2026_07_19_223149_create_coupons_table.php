<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('vendor_id');
            $table->string('code', 50);
            $table->integer('discount_percentage');
            $table->date('expiry_date');
            $table->string('status')->nullable()->default('active');
            $table->dateTime('created_at')->nullable()->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
