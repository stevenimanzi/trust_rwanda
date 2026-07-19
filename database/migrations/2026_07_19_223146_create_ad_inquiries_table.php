<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ad_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('business', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('package', 100)->nullable();
            $table->text('message')->nullable();
            $table->string('status')->nullable()->default('new');
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ad_inquiries');
    }
};
