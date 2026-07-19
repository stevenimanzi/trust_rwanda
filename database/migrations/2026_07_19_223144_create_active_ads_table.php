<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('active_ads', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->text('target_url')->nullable();
            $table->string('ad_type')->nullable();
            $table->string('placement', 50)->nullable();
            $table->string('image_url', 255)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('priority')->nullable()->default(0);
            $table->string('status')->nullable()->default('active');
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('active_ads');
    }
};
