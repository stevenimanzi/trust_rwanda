<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->string('icon_class', 50)->nullable();
            $table->string('type')->nullable()->default('general');
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
