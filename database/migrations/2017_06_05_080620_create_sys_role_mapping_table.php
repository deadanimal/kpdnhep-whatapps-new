<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysRoleMappingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_role_mapping', function(Blueprint $table)
		{
			$table->string('role_code', 25);
			$table->integer('menu_id')->index('ROLE_MAP_MENUID_MENU');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sys_role_mapping');
	}

}
