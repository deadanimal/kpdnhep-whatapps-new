<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCaseRelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_rel', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('CR_RELID')->index('CR_RELID');
			$table->string('CR_CASEID', 14);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('case_rel');
	}

}
