<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAskDocTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ask_doc', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('askid', 14)->nullable();
			$table->string('path', 200)->nullable();
			$table->string('img', 200)->nullable();
			$table->string('img_name', 200)->nullable();
			$table->text('remarks', 65535)->nullable();
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
		Schema::drop('ask_doc');
	}

}
