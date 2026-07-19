<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('message');
            $table->string('icon', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->text('action_url')->nullable();
            $table->string('target_users')->nullable()->default('all');
            $table->string('status')->nullable()->default('draft');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->integer('recipient_count')->nullable()->default(0);
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('push_notifications');
    }
};
