<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedFacebookDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_facebook_details', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('facebook_id');
            $table->string('profile_id')->nullable(); // for task force usage. round robin concept. null if not active so can be pass to other person next time
            $table->integer('supporter_id')->unsigned()->nullable(); // for task force usage. round robin concept. null if not active so can be pass to other person next time
            $table->text('message'); // data mesage
            $table->boolean('is_input')->default(true); // status of i/o
            $table->boolean('is_have_attachment')->default(false); // if the data have an attachment
            $table->boolean('is_read')->default(false); // is read. for read counter
            $table->boolean('is_ticketed')->default(false); // is selected for create as a ticket. so cannot be reselected.
            $table->string('ticketable_type')->nullable(); // polymorphism purpose
            $table->integer('ticketable_id')->nullable(); // polymorphism purpose
            $table->timestampTz('created_time')->nullable();
            $table->softDeletes(); // archive purpose
            $table->timestamps();

            $table->foreign('facebook_id')->references('id')->on('feed_facebooks');
            $table->foreign('profile_id')->references('id')->on('feed_facebook_profiles');
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
        Schema::drop('feed_facebook_details');
    }
}
