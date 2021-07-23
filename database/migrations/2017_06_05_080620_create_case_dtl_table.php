<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCaseDtlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_dtl', function(Blueprint $table)
		{
			$table->integer('CD_DTLID', true);
			$table->string('CD_CASEID', 14)->index('DTL_CD_CASEID_INFO');
			$table->string('CD_TYPE', 1)->nullable();
			$table->text('CD_DESC', 65535)->nullable();
                        $table->string('CD_ACTTYPE', 10)->nullable();
                        $table->string('CD_INVSTS', 2)->nullable();
			$table->string('CD_CASESTS', 2)->nullable();
			$table->string('CD_CURSTS', 1)->nullable();
			$table->string('CD_ACTFROM', 50)->nullable();
			$table->string('CD_ACTTO', 50)->nullable();
			$table->integer('CD_DOCATTCHID_PUBLIC')->nullable(); // Add by nadia 20170807  -surat untuk pengadu
			$table->integer('CD_DOCATTCHID_ADMIN')->nullable(); //Add by nadia 20170807 -surat kepada pegawai
			$table->string('CD_CREBY', 30)->nullable();
			$table->dateTime('CD_CREDT')->nullable();
			$table->string('CD_MODBY', 30)->nullable();
			$table->dateTime('CD_MODDT')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('case_dtl');
	}

}
