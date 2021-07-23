<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedWhatsappDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_whatsapp_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('feed_whatsapp_id')->unsigned();
            $table->integer('supporter_id')->unsigned()->nullable(); // for task force usage. round robin concept. null if not active so can be pass to other person next time
            $table->text('message'); // data mesage
            $table->string('message_uid'); // uid data
            $table->string('message_cuid'); // cuid data
            $table->boolean('is_input')->default(true); // status of i/o
            $table->boolean('is_have_attachment')->default(false); // if the data have an attachment
            $table->boolean('is_read')->default(false); // is read. for read counter
            $table->boolean('is_ticketed')->default(false); // is selected for create as a ticket. so cannot be reselected.
            $table->string('ticketable_type')->nullable(); // polymorphism purpose
            $table->integer('ticketable_id')->nullable(); // polymorphism purpose
            $table->softDeletes(); // archive purpose
            $table->timestamps();

            $table->foreign('feed_whatsapp_id')->references('id')->on('feed_whatsapps');
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
        Schema::drop('feed_whatsapp_details');
    }
}
