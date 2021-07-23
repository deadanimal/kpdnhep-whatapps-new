<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysRefTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_ref', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('cat', 25)->nullable();
			$table->string('code', 25)->nullable();
			$table->string('descr', 150)->nullable();
			$table->string('descr_en', 150)->nullable();
			$table->integer('sort')->nullable();
			$table->string('param', 5)->nullable();
			$table->char('status', 1);
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
		Schema::drop('sys_ref');
	}

}
