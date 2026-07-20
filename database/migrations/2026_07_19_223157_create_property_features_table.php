<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('property_features', function (Blueprint $table) {
            $table->id();
            $table->integer('property_id');
            $table->string('feature_name', 100);
            $table->string('feature_value', 255);
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_features');
    }
};
