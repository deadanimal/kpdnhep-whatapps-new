<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFkMessageReplyIdFromFeedEmailDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_email_details', function($table) {
            $table->dropForeign(['message_reply_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_email_details', function($table) {
            $table->foreign('message_reply_id')->references('message_id')->on('feed_email_details');
        });
    }
}
