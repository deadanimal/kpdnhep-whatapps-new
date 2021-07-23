<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_menu', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('menu_txt', 100)->nullable();
			$table->string('menu_txt_en', 100)->nullable();
			$table->string('menu_loc', 100)->nullable();
			$table->integer('menu_parent_id')->nullable();
			$table->char('default_menu', 1)->nullable();
			$table->integer('sort')->nullable();
			$table->string('icon_name', 50)->nullable();
			$table->string('balloon_title', 100)->nullable();
			$table->integer('module_ind')->nullable();
			$table->string('remarks', 100)->nullable();
			$table->char('hide_ind', 1)->nullable();
			$table->char('menu_cat', 1)->nullable()->default(1); // add by Sukri 20170828
			$table->text('pic', 65535)->nullable();
			$table->string('route_name', 100)->nullable();
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
		Schema::drop('sys_menu');
	}

}
