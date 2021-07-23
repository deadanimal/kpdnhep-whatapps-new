<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrncdToCaseDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('case_dtl', function (Blueprint $table) {
            $table->string('CD_BRNCD_FROM', 5)->nullable()->after('CD_ACTTO');
            $table->string('CD_BRNCD_TO', 5)->nullable()->after('CD_BRNCD_FROM');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_dtl', function (Blueprint $table) {
            $table->dropColumn('CD_BRNCD_FROM');
            $table->dropColumn('CD_BRNCD_TO');
        });
    }
}
