<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_articles', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('menu_type', 10);
			$table->integer('menu_id');
			$table->date('start_dt');
			$table->date('end_dt');
			$table->string('title_my');
			$table->string('title_en')->nullable();
			$table->text('content_my', 65535);
			$table->text('content_en', 65535);
			$table->string('hits');
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
		Schema::drop('sys_articles');
	}

}
