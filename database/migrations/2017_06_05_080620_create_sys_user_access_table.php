<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysUserAccessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_user_access', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('USR_ACCSS_USERID_ADMINS');
			$table->integer('admin_id')->index('USR_ACCSS_ADMINID_ADMINS');
			$table->string('role_code', 25);
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
		Schema::drop('sys_user_access');
	}

}
