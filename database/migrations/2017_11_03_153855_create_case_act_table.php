<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCaseActTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_act', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('CT_CASEID', 14)->nullable();
			$table->string('CT_IPNO', 20)->nullable();
			$table->string('CT_AKTA', 20)->nullable();
			$table->string('CT_SUBAKTA', 20)->nullable();
			$table->string('CT_CREBY', 30)->nullable();
			$table->dateTime('CT_CREDT')->nullable();
			$table->string('CT_MODBY', 30)->nullable();
			$table->dateTime('CT_MODDT')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('case_act');
	}

}
