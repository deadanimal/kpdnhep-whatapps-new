<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedTelegramsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_telegrams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username'); // make it as unique
            $table->string('first_name'); // make it as unique
            $table->string('last_name')->nullable(); // make it as unique
            $table->boolean('is_active')->default(true); // is active number (have new update from client)
            $table->softDeletes(); // spam purpose
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_telegrams');
    }
}
