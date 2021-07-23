<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocAttachTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('doc_attach', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('doc_title', 100)->nullable();
			$table->string('file_name', 100)->nullable();
			$table->string('file_name_sys', 100)->nullable();
			$table->string('doctype', 5)->nullable();
			$table->string('remarks', 100)->nullable();
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
		Schema::dropIfExists('doc_attach');
	}

}
