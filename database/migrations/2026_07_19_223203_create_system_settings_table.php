<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->string('setting_key', 50);
            $table->text('setting_value')->nullable();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
};
