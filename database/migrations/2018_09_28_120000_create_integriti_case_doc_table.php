<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIntegritiCaseDocTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('integriti_case_doc', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('IC_CASEID', 20)->nullable();
			$table->string('IC_PATH', 200)->nullable();
			$table->string('IC_DOCNAME', 200)->nullable();
			$table->string('IC_DOCFULLNAME', 200)->nullable();
            $table->string('IC_DOCTYPE', 30)->nullable();
            $table->char('IC_DOCCAT', 1)->nullable();
			$table->text('IC_REMARKS', 65535)->nullable();
			$table->string('IC_CREATED_BY', 30)->nullable();
            $table->dateTime('IC_CREATED_AT')->nullable();
			$table->dateTime('IC_UPDATED_AT')->nullable();
			$table->string('IC_UPDATED_BY', 30)->nullable();
            $table->dateTime('IC_DELETED_AT')->nullable();
            $table->string('IC_DELETED_BY', 30)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('integriti_case_doc');
	}

}
