<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrgWorkingDayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('org_working_day', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('work_day')->nullable();
			$table->char('work_code', 5)->nullable();
			$table->string('state_code', 5)->nullable();
			$table->string('created_by', 50)->nullable();
			$table->timestamps();
			$table->string('updated_by', 50)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('org_working_day');
	}

}
