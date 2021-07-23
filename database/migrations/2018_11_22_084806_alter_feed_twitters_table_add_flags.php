<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFeedTwittersTableAddFlags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_twitters', function ($table) {
            $table->string('is_mention')->default(0)->after('is_active');
            $table->string('is_quote')->default(0)->after('is_mention');
            $table->string('is_input')->default(1)->after('is_quote');
            $table->string('is_message')->default(0)->after('is_input');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_twitters', function ($table) {
            $table->dropColumn('is_mention');
            $table->dropColumn('is_quote');
            $table->dropColumn('is_input');
            $table->dropColumn('is_message');
        });
    }
}
