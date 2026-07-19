<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ad_analytics', function (Blueprint $table) {
            $table->id();
            $table->integer('ad_id');
            $table->string('event_type')->nullable()->default('view');
            $table->string('user_ip', 45)->nullable();
            $table->timestamp('logged_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ad_analytics');
    }
};
