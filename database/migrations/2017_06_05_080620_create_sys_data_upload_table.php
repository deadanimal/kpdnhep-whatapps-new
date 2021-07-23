<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysDataUploadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_data_upload', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('doc_attach_id')->nullable();
			$table->string('title')->nullable();
			$table->string('status', 5)->nullable();
			$table->char('migrated_ind', 1)->nullable();
			$table->text('remark', 65535)->nullable();
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
		Schema::drop('sys_data_upload');
	}

}
