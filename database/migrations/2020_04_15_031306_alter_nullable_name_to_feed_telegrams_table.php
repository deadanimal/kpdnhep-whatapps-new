<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNullableNameToFeedTelegramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_telegrams', function (Blueprint $table) {
            $table->string('username')->nullable()->change();
            $table->string('first_name')->nullable()->change();
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
            $table->string('username')->change();
            $table->string('first_name')->change();
        });
    }
}
