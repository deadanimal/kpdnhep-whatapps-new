<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedTelegramDetailsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_telegram_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('feed_telegram_id')->unsigned();
            $table->string('update_id')->unique();

            // message
            $table->string('message_id')->unique();

            $table->longText('message_text');
            $table->datetime('message_date');

            $table->longText('message_text_edited')->nullable();
            $table->datetime('message_text_edited_date')->nullable();

            $table->boolean('is_fowarded')->default(0);

            $table->integer('supporter_id')->unsigned()->nullable(); // for task force usage. round robin concept. null if not active so can be pass to other person next time

            $table->boolean('is_input')->default(true); // status of i/o
            $table->boolean('is_have_attachment')->default(false); // if the data have an attachment
            $table->boolean('is_read')->default(false); // is read. for read counter
            $table->boolean('is_ticketed')->default(false); // is selected for create as a ticket. so cannot be reselected.

            $table->string('ticketable_type')->nullable(); // polymorphism purpose
            $table->integer('ticketable_id')->nullable(); // polymorphism purpose

            $table->softDeletes(); // archive purpose
            $table->timestamps();

            $table->foreign('feed_telegram_id')->references('id')->on('feed_telegrams');
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
        Schema::dropIfExists('feed_telegram_details');
    }
}
