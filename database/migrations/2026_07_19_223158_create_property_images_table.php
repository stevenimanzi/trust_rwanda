<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->integer('property_id');
            $table->string('image_url', 255);
            $table->integer('sort_order')->default(0);
            $table->string('alt_text', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_images');
    }
};
