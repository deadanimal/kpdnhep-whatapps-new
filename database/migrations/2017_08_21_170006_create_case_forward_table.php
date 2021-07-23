<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCaseForwardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_forward', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('CF_CASEID', 14)->nullable();
			$table->string('CF_FWRD_CASEID', 14)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('case_forward');
	}

}
