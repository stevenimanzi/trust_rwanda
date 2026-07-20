<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_id');
            $table->string('property_type')->default('house');
            $table->string('listing_type')->default('sale');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->decimal('price');
            $table->string('price_period')->default('once');
            $table->string('address', 255)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('sector', 100)->nullable();
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_verified')->default(0);
            $table->string('youtube_video_id', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};
