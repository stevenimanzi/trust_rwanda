<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('vendor_id');
            $table->string('recipient', 20);
            $table->text('message_body');
            $table->text('gateway_response')->nullable();
            $table->string('status')->nullable()->default('sent');
            $table->dateTime('created_at')->nullable()->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_logs');
    }
};
