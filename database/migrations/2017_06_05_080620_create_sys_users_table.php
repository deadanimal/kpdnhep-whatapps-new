<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 191);
			$table->string('name', 191)->nullable();
			$table->string('icnew', 20)->nullable();
                        $table->longtext('myidentity_address')->nullable();
                        $table->string('myidentity_postcode', 6)->nullable();
			$table->string('myidentity_distrinct_cd', 4)->nullable();
			$table->string('myidentity_state_cd', 2)->nullable();
                        $table->longtext('address')->nullable();
			$table->string('postcode', 6)->nullable();
			$table->string('distrinct_cd', 4)->nullable();
			$table->string('state_cd', 2)->nullable();
			$table->string('ctry_cd', 2)->nullable();
			$table->char('citizen', 1)->nullable();
                        $table->char('status_pengadu', 1)->nullable();
			$table->string('question1', 25)->nullable();
			$table->string('answer1')->nullable();
			$table->string('question2', 25)->nullable();
			$table->string('answer2')->nullable();
			$table->string('question3', 25)->nullable();
			$table->string('answer3')->nullable();
			$table->char('question_ind', 1)->nullable();
			$table->char('question_count', 1)->nullable();
			$table->char('fok_ind', 1)->nullable();
			$table->string('email', 191)->nullable();
			$table->char('user_cat', 1)->nullable();
			$table->string('mobile_no', 20)->nullable();
			$table->string('office_no', 20)->nullable();
			$table->string('brn_cd', 5)->nullable();
			$table->string('dept_cd', 8)->nullable();
			$table->string('job_dest', 30)->nullable();
			$table->text('remark', 65535)->nullable();
			$table->char('job_sts', 1)->nullable();
			$table->char('status', 1)->nullable();
			$table->string('password', 191);
			$table->string('text_password', 191)->nullable();
			$table->string('remember_token', 100)->nullable();
                        $table->string('api_token', 60)->unique()->nullable();
			$table->string('email_token', 100)->nullable();
                        $table->string('user_photo', 100)->nullable();
                        $table->integer('age')->nullable();
                        $table->integer('gender')->nullable();
			$table->string('created_by', 50)->nullable();
			$table->string('updated_by', 50)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sys_users');
	}

}
