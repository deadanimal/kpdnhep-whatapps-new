<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIntegritiCaseRelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('integriti_case_rel', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('IN_RELID')->index('IN_RELID')->nullable();
			$table->string('IN_CASEID', 20)->nullable();
                        $table->string('IN_CREATED_BY', 30)->nullable();
                        $table->dateTime('IN_CREATED_AT')->nullable();
			$table->dateTime('IN_UPDATED_AT')->nullable();
			$table->string('IN_UPDATED_BY', 30)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('integriti_case_rel');
	}

}
