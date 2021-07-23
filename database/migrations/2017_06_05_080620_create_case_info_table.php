<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCaseInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_info', function(Blueprint $table)
		{
                        $table->integer('id', true);
			$table->string('CA_CASEID', 14)->nullable();
			$table->string('CA_DEPTCD', 5)->nullable();
			$table->string('CA_DOCTYP', 2)->nullable();
			$table->string('CA_FILEREF', 20)->nullable();// Add By Sukri 20170823 - No.Rujukan
			$table->string('CA_INVSTS', 2);
			$table->string('CA_CASESTS', 2);
			$table->string('CA_RCVTYP', 4)->nullable();
			$table->string('CA_RCVBY', 30)->nullable();
                        $table->dateTime('CA_RCVDT')->nullable();
			$table->string('CA_ASGTO', 30)->nullable();
			$table->string('CA_ASGBY', 30)->nullable();
			$table->dateTime('CA_ASGDT')->nullable();
			$table->string('CA_INVBY', 30)->nullable();
			$table->dateTime('CA_INVDT')->nullable();
			$table->string('CA_COMPLETEBY', 30)->nullable(); // Add By Sukri 20170823 - Selesai Oleh
			$table->dateTime('CA_COMPLETEDT')->nullable(); // Add By Sukri 20170823 - Tarikh Selesai
			$table->string('CA_CLOSEBY', 30)->nullable();
			$table->dateTime('CA_CLOSEDT')->nullable();
                        $table->string('CA_ROUTETOHQIND', 1)->nullable(); // Add By Sukri 20171031 - Indicator utk route aduan ke HQ
			$table->string('CA_MRGIND', 1)->nullable();
			$table->string('CA_MERGE', 11)->nullable();
			$table->text('CA_ANSWER', 65535)->nullable();
			$table->text('CA_RESULT', 65535)->nullable();
                        $table->text('CA_RECOMMEND', 65535)->nullable(); // Add By Sukri 20170823 - Saranan -> simpanan sementara
                        $table->text('CA_SUMMARY', 65535);
			$table->string('CA_BRNCD', 5)->nullable();
			$table->string('CA_CMPLCAT', 7)->nullable();
			$table->string('CA_CMPLCD', 10)->nullable();
			$table->string('CA_CMPLKEYWORD', 10)->nullable(); // Add By Sukri 20170918
			$table->string('CA_TTPMTYP', 5)->nullable(); // Add By Sukri 20171008 - Kategori TTPM Penuntut,Penentang
			$table->string('CA_TTPMNO', 50)->nullable(); // Add By Sukri 20171008 - No.TTPM
			$table->string('CA_ONLINECMPL_IND', 1)->nullable(); // Add By Sukri 20170918 - Indicator utk pernah buat aduan d di pihak diadu
			$table->string('CA_ONLINEADD_IND', 1)->nullable(); // Add By Sukri 20170918 - Indicator utk alamat penuh pengadu
			$table->string('CA_ONLINECMPL_URL', 255)->nullable(); // Add By Sukri 20170918
			$table->string('CA_ONLINECMPL_PROVIDER', 10)->nullable(); // Add By Sukri 20170918
			$table->string('CA_ONLINECMPL_CASENO', 50)->nullable(); // Add By Sukri 20170918
                        $table->string('CA_ONLINECMPL_BANKCD', 5)->nullable(); // Add By Sukri 20171008 - Nama Bank
                        $table->string('CA_ONLINECMPL_ACCNO', 50)->nullable();
			$table->decimal('CA_ONLINECMPL_AMOUNT', 10)->nullable();
                        $table->string('CA_ONLINECMPL_PYMNTTYP', 5)->nullable(); // Add By Sukri 20171103 - Cara Pembayaran
                        $table->text('CA_CRDNT', 65535)->nullable(); // Add By Sukri 20171103 - Koordinat
                        $table->string('CA_BPANO', 50)->nullable(); // Add By Akmal - 20171008 - No. Aduan BPA
                        $table->string('CA_SERVICENO', 50)->nullable(); // Add By Akmal - 20171008 - No. Tali Khidmat
			$table->string('CA_AREACD', 2)->nullable(); // Add By Sukri 20170803 - Kawasan Kes
			$table->string('CA_SSP', 20)->nullable(); // Add By Sukri 20170803 - Kes SSP
			$table->string('CA_IPNO', 20)->nullable(); // Add By Sukri 20170803 - No. IP
			$table->string('CA_AKTA', 20)->nullable(); // Add By Sukri 20170803 - Akta
			$table->string('CA_SUBAKTA', 20)->nullable(); // Add By Sukri 20170803 - Kod Akta
			$table->string('CA_MAGNCD', 4)->nullable(); // Add By Sukri 20170803 - Kementerian/Agensi
			$table->string('CA_SEXCD', 1)->nullable();
			$table->string('CA_NAME', 60)->nullable();
			$table->string('CA_DOCNO', 15)->nullable();
			$table->string('CA_AGE', 20)->nullable();
			$table->longtext('CA_MYIDENTITY_ADDR')->nullable();
                        $table->string('CA_MYIDENTITY_POSCD', 10)->nullable();
                        $table->string('CA_MYIDENTITY_DISTCD', 4)->nullable();
                        $table->string('CA_MYIDENTITY_STATECD', 4)->nullable();
			$table->longtext('CA_ADDR')->nullable();
			$table->string('CA_DISTCD', 4)->nullable();
			$table->string('CA_POSCD', 10)->nullable();
			$table->string('CA_STATECD', 4)->nullable();
			$table->string('CA_NATCD', 10)->nullable();
			$table->string('CA_COUNTRYCD', 10)->nullable();
			$table->string('CA_TELNO', 20)->nullable();
			$table->string('CA_FAXNO', 20)->nullable();
			$table->string('CA_EMAIL', 60)->nullable();
			$table->string('CA_MOBILENO', 20)->nullable();
			$table->string('CA_RACECD', 2)->nullable();
			$table->string('CA_STATUSPENGADU', 1)->nullable();
			$table->string('CA_RESIDENTIALSTATUS', 1)->nullable();
			$table->string('CA_AGAINSTNM', 250)->nullable();
			$table->longtext('CA_AGAINSTADD')->nullable();
			$table->string('CA_AGAINST_POSTCD', 10)->nullable();
			$table->string('CA_AGAINST_TELNO', 20)->nullable();
			$table->string('CA_AGAINST_FAXNO', 20)->nullable();
			$table->string('CA_AGAINST_MOBILENO', 20)->nullable();
			$table->string('CA_AGAINST_PREMISE', 4)->nullable();
			$table->string('CA_AGAINST_DISTCD', 4)->nullable();
			$table->string('CA_AGAINST_STATECD', 4)->nullable();
			$table->string('CA_AGAINST_EMAIL', 60)->nullable();
			$table->string('CA_CREBY', 30)->nullable();
			$table->dateTime('CA_CREDT')->nullable();
			$table->string('CA_MODBY', 30)->nullable();
			$table->dateTime('CA_MODDT')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('case_info');
	}

}
