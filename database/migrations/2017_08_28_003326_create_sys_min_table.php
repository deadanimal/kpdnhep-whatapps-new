<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysMinTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_min', function(Blueprint $table)
		{
                        Schema::dropIfExists('sys_min');
			$table->integer('id', true);
			$table->string('MI_MINCD', 4)->nullable();
			$table->string('MI_LANG', 3)->nullable();
			$table->text('MI_DESC', 65535)->nullable();
			$table->string('MI_RVWBY', 30)->nullable();
			$table->string('MI_INVBY', 30)->nullable();
			$table->string('MI_MINTYP', 1)->nullable();
			$table->string('MI_CREBY', 30)->nullable();
			$table->dateTime('MI_CREDT')->nullable();
			$table->string('MI_MODBY', 30)->nullable();
			$table->dateTime('MI_MODDT')->nullable();
			$table->string('MI_STATECD', 5)->nullable();
			$table->string('MI_ADDR', 200)->nullable();
			$table->string('MI_POSCD', 10)->nullable();
			$table->string('MI_DISTCD', 4)->nullable();
			$table->string('MI_COUNTRY', 3)->nullable();
			$table->string('MI_TELNO', 20)->nullable();
			$table->string('MI_FAXNO', 20)->nullable();
			$table->string('MI_EMAIL', 60)->nullable();
			$table->string('MI_OFFNM', 30)->nullable();
			$table->string('MI_OFFNM2', 30)->nullable();
			$table->char('MI_STS', 1)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sys_min');
	}

}
