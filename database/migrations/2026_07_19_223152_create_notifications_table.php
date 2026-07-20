<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('title', 100)->nullable();
            $table->text('message')->nullable();
            $table->string('type')->nullable()->default('system');
            $table->boolean('is_read')->nullable()->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
