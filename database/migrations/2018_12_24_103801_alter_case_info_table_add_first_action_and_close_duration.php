<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCaseInfoTableAddFirstActionAndCloseDuration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('case_info', function($table) {
            $table->date('CA_FA_DATE')->nullable()->after('CA_AGAINST_EMAIL');
            $table->integer('CA_FA_DURATION')->default(0)->after('CA_FA_DATE');
            $table->date('CA_PRECLOSE_DATE')->nullable()->after('CA_CLOSEDT');
            $table->integer('CA_PRECLOSE_DURATION')->default(0)->after('CA_PRECLOSE_DATE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
