<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIntegritiCaseInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('integriti_case_info', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('IN_CASEID', 20)->nullable();
			$table->string('IN_DEPTCD', 5)->nullable();
			$table->string('IN_DOCTYP', 2)->nullable();
			$table->string('IN_FILEREF', 20)->nullable();
			$table->string('IN_INVSTS', 3)->comment('Status Aduan');
			$table->string('IN_IPSTS', 3)->nullable()->comment('Status Penyiasatan');
			$table->string('IN_CASESTS', 2)->nullable();
			$table->string('IN_RCVTYP', 4)->nullable();
			$table->string('IN_RCVBY', 30)->nullable();
			$table->dateTime('IN_RCVDT')->nullable();
			$table->string('IN_ASGTO', 30)->nullable();
			$table->string('IN_ASGBY', 30)->nullable();
			$table->dateTime('IN_ASGDT')->nullable();
			$table->string('IN_INVBY', 30)->nullable();
			$table->dateTime('IN_INVDT')->nullable();
			$table->string('IN_COMPLETEBY', 30)->nullable();
			$table->dateTime('IN_COMPLETEDT')->nullable();
			$table->string('IN_CLOSEBY', 30)->nullable();
			$table->dateTime('IN_CLOSEDT')->nullable();
			$table->string('IN_ROUTETOHQIND', 1)->nullable();
			$table->string('IN_MRGIND', 1)->nullable();
			$table->string('IN_MERGE', 11)->nullable();
			$table->text('IN_ANSWER', 65535)->nullable();
			$table->text('IN_RESULT', 65535)->nullable();
			$table->text('IN_RECOMMEND', 65535)->nullable();
			$table->string('IN_RECOMMENDTYP', 10)->nullable();
			$table->string('IN_SUMMARY_TITLE', 200)->nullable();
			$table->text('IN_SUMMARY', 65535);
			$table->string('IN_BRNCD', 5)->nullable();
			$table->string('IN_CMPLCAT', 7)->nullable();
			$table->string('IN_CMPLCD', 10)->nullable();
			$table->string('IN_CMPLTYP', 20)->nullable();
			$table->text('IN_CRDNT', 65535)->nullable();
			$table->string('IN_AREACD', 2)->nullable();
			$table->string('IN_SSP', 3)->nullable();
			$table->string('IN_IPNO', 20)->nullable()->comment('No. IP');
			$table->char('IN_ACCESSIND', 1)->nullable();
			$table->string('IN_ACCESSBY', 10)->nullable();
			$table->dateTime('IN_ACCESSDATE')->nullable();
			$table->string('IN_MAGNCD', 4)->nullable();
			$table->string('IN_SEXCD', 1)->nullable();
			$table->string('IN_NAME', 60)->nullable();
			$table->string('IN_DOCNO', 15)->nullable();
			$table->string('IN_AGE', 20)->nullable();
			$table->longtext('IN_MYIDENTITY_ADDR')->nullable();
			$table->string('IN_MYIDENTITY_POSTCD', 10)->nullable();
			$table->string('IN_MYIDENTITY_DISTCD', 4)->nullable();
			$table->string('IN_MYIDENTITY_STATECD', 4)->nullable();
			$table->longtext('IN_ADDR')->nullable();
			$table->string('IN_DISTCD', 50)->nullable();
			$table->string('IN_POSTCD', 10)->nullable();
			$table->string('IN_STATECD', 4)->nullable();
			$table->string('IN_NATCD', 10)->nullable();
			$table->string('IN_COUNTRYCD', 10)->nullable();
			$table->string('IN_TELNO', 20)->nullable();
			$table->string('IN_FAXNO', 20)->nullable();
			$table->string('IN_EMAIL', 60)->nullable();
			$table->string('IN_MOBILENO', 20)->nullable();
			$table->string('IN_RACECD', 10)->nullable();
			$table->string('IN_STATUSPENGADU', 1)->nullable();
			$table->string('IN_RESIDENTIALSTATUS', 1)->nullable();
			$table->char('IN_SECRETFLAG', 1)->nullable();
			$table->string('IN_MEETINGNUM', 20)->nullable();
			$table->string('IN_AGAINSTNM', 250)->nullable();
			$table->string('IN_REFTYPE', 10)->nullable();
			$table->string('IN_BGK_CASEID', 20)->nullable();
			$table->string('IN_TTPMNO', 30)->nullable();
			$table->string('IN_TTPMFORM', 10)->nullable();
			$table->string('IN_REFOTHER', 30)->nullable();
			$table->string('IN_AGAINSTLOCATION', 10)->nullable();
			$table->string('IN_AGAINST_BRSTATECD', 10)->nullable();
			$table->string('IN_AGENCYCD', 10)->nullable();
			$table->string('IN_CREATED_BY', 30)->nullable();
			$table->dateTime('IN_CREATED_AT')->nullable();
			$table->dateTime('IN_UPDATED_AT')->nullable();
			$table->string('IN_UPDATED_BY', 30)->nullable();
			$table->dateTime('IN_DELETED_AT')->nullable();
			$table->string('IN_DELETED_BY', 30)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('integriti_case_info');
	}

}
