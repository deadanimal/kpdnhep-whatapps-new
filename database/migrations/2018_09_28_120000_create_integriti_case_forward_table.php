<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIntegritiCaseForwardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('integriti_case_forward', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('IF_CASEID', 20)->nullable();
			$table->string('IF_FORWARD_CASEID', 20)->nullable();
			$table->string('IF_CREATED_BY', 30)->nullable();
			$table->dateTime('IF_CREATED_AT')->nullable();
			$table->dateTime('IF_UPDATED_AT')->nullable();
			$table->string('IF_UPDATED_BY', 30)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('integriti_case_forward');
	}

}
