<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique(); // make it as unique
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
        Schema::drop('feed_whatsapps');
    }
}
