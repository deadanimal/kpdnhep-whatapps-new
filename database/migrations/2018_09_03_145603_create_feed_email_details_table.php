<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedEmailDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_email_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('feed_email_id')->unsigned();
            $table->integer('supporter_id')->unsigned()->nullable(); // for task force usage. round robin concept. null if not active so can be pass to other person next time
            $table->string('subject');
            $table->text('body');
            $table->string('message_id')->index();
            $table->string('message_reply_id')->nullable();
            $table->softDeletes(); // archive purpose
            $table->timestamps();

            $table->foreign('feed_email_id')->references('id')->on('feed_emails');
            $table->foreign('supporter_id')->references('id')->on('sys_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('feed_email_details');
    }
}
