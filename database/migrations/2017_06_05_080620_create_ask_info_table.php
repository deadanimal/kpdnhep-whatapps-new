<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAskInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ask_info', function(Blueprint $table)
		{
                        $table->integer('id', true);
			$table->string('AS_ASKID', 14)->nullable();
			$table->string('AS_DEPTCD', 5)->nullable();
			$table->string('AS_ASKSTS', 2);
			$table->string('AS_RCVTYP', 4)->nullable();
			$table->string('AS_RCVBY', 30)->nullable();
                        $table->dateTime('AS_RCVDT')->nullable();
                        $table->string('AS_COMPLETEBY', 30)->nullable(); // Add By Sukri 20170823 - Selesai Oleh
			$table->dateTime('AS_COMPLETEDT')->nullable(); // Add By Sukri 20170823 - Tarikh Selesai
                        $table->string('AS_NAME', 60)->nullable()->comment('Nama');
			$table->string('AS_DOCNO', 15)->nullable()->comment('IC/Pasport');
			$table->string('AS_SEXCD', 1)->nullable()->comment('Jantina');
			$table->string('AS_AGE', 20)->nullable()->comment('Umur');
			$table->text('AS_ADDR', 65535)->nullable()->comment('Alamat');
			$table->integer('AS_POSCD')->nullable()->comment('Poskod');
			$table->string('AS_DISTCD', 4)->nullable()->comment('Daerah');
			$table->string('AS_STATECD', 4)->nullable()->comment('Negeri');
			$table->string('AS_NATCD', 10)->nullable()->comment('Warganegara');
			$table->string('AS_COUNTRYCD', 10)->nullable()->comment('Negara Asal');
			$table->string('AS_EMAIL', 191)->nullable()->comment('Emel');
			$table->string('AS_MOBILENO', 20)->nullable()->comment('No. Tel. Bimbit');
			$table->string('AS_STATUSPENGADU', 1)->nullable()->comment('Status Pengadu');
			$table->text('AS_SUMMARY', 65535);
			$table->text('AS_ANSWER', 65535)->nullable();
			$table->string('AS_CMPLCAT', 10)->nullable()->comment('Kategori'); // Add By Akmal - 20180325 - Kategori
			$table->string('AS_CMPLCD', 10)->nullable()->comment('Subkategori'); // Add By Akmal - 20180325 - Subkategori
			$table->string('AS_BRNCD', 5)->nullable();
			$table->string('AS_USERID', 30)->nullable();
			$table->string('AS_CREBY', 30)->nullable();
			$table->dateTime('AS_CREDT')->nullable();
			$table->string('AS_MODBY', 30)->nullable();
			$table->dateTime('AS_MODDT')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ask_info');
	}

}
