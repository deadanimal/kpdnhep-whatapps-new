<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovePkFromFeedTelegramDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_telegrams', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->unique()->index()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_telegrams', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
