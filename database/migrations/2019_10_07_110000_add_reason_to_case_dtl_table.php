<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonToCaseDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('case_dtl', function (Blueprint $table) {
            $table->string('CD_REASON', 10)->nullable()->after('CD_BRNCD_TO');
            $table->integer('CD_REASON_DURATION')->nullable()->after('CD_REASON');
            $table->date('CD_REASON_DATE_FROM')->nullable()->after('CD_REASON_DURATION');
            $table->date('CD_REASON_DATE_TO')->nullable()->after('CD_REASON_DATE_FROM');
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
            $table->dropColumn('CD_REASON');
            $table->dropColumn('CD_REASON_DURATION');
            $table->dropColumn('CD_REASON_DATE_FROM');
            $table->dropColumn('CD_REASON_DATE_TO');
        });
    }
}
