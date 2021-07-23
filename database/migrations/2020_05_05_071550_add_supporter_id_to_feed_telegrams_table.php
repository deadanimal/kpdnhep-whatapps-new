<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupporterIdToFeedTelegramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_telegrams', function ($table) {
            $table->integer('supporter_id')->nullable()->unsigned()->after('id');

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
        Schema::table('feed_telegrams', function ($table) {
            $table->dropForeign(['supporter_id']);

            $table->dropColumn('supporter_id');
        });
    }
}
