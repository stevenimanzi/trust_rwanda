<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->integer('id');
            $table->string('email', 191);
            $table->dateTime('created_at')->nullable()->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};
