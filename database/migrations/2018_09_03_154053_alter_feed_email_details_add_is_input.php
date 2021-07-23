<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFeedEmailDetailsAddIsInput extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_email_details', function ($table) {
            $table->boolean('is_input')->default(false)->after('message_reply_id');
            $table->boolean('is_read')->default(false)->after('is_input');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_email_details', function ($table) {
            $table->dropColumn('is_input');
            $table->dropColumn('is_read');
        });
    }
}
