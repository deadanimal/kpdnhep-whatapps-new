<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCaseDocTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_doc', function(Blueprint $table)
		{
                        $table->integer('id', true);
			$table->string('CC_CASEID', 20);
			$table->string('CC_PATH', 200)->nullable();
			$table->string('CC_IMG', 200)->nullable();
			$table->string('CC_IMG_NAME', 200)->nullable();
			$table->string('CC_IMG_CAT', 1)->nullable();
                        $table->text('CC_REMARKS', 65535)->nullable();
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
		Schema::drop('case_doc');
	}

}
