<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysBrnTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_brn', function(Blueprint $table)
		{
			$table->string('BR_BRNCD', 5)->primary();
			$table->string('BR_BRNNM', 60);
			$table->string('BR_ADDR1', 100)->nullable();
			$table->string('BR_ADDR2', 100)->nullable();
			$table->string('BR_DISTCD', 40)->nullable();
			$table->string('BR_POSCD', 10)->nullable();
			$table->string('BR_STATECD', 2)->nullable();
			$table->string('BR_TELNO', 20)->nullable();
			$table->string('BR_FAXNO', 20)->nullable();
			$table->string('BR_EMAIL', 60)->nullable();
			$table->text('BR_OTHDIST', 65535)->nullable();
			$table->string('BR_REFNM', 200)->nullable();
			$table->string('BR_DEPTCD', 5)->nullable();
			$table->string('BR_OTHSTATE', 2)->nullable();
			$table->string('BR_REFADD1', 100)->nullable();
			$table->string('BR_REFADD2', 100)->nullable();
			$table->string('BR_REFADD3', 100)->nullable();
			$table->string('BR_CREBY', 30)->nullable();
			$table->dateTime('BR_CREDT')->nullable();
			$table->string('BR_MODBY', 30)->nullable();
			$table->dateTime('BR_MODDT')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sys_brn');
	}

}
