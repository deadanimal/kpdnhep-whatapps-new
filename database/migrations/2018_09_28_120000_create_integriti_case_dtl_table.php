<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIntegritiCaseDtlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('integriti_case_dtl', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('ID_CASEID', 20)->nullable();
			$table->string('ID_TYPE', 1)->nullable();
			$table->text('ID_DESC', 65535)->nullable();
			$table->text('ID_ANSWER', 65535)->nullable();
			$table->string('ID_ACTTYPE', 10)->nullable();
			$table->string('ID_INVSTS', 3)->nullable()->comment('Status_Aduan');
			$table->string('ID_IPSTS', 3)->nullable()->comment('Status_Penyiasatan');
			$table->string('ID_CASESTS', 2)->nullable();
			$table->string('ID_CURSTS', 1)->nullable();
			$table->string('ID_BRNCD', 10)->nullable();
			$table->string('ID_ACTFROM', 50)->nullable();
			$table->string('ID_ACTTO', 50)->nullable();
			$table->integer('ID_DOCATACHID_PUBLIC')->nullable();
			$table->integer('ID_DOCATACHID_ADMIN')->nullable();
			$table->string('ID_CREATED_BY', 30)->nullable();
			$table->dateTime('ID_CREATED_AT')->nullable();
			$table->dateTime('ID_UPDATED_AT')->nullable();
			$table->string('ID_UPDATED_BY', 30)->nullable();
			$table->dateTime('ID_DELETED_AT')->nullable();
			$table->string('ID_DELETED_BY', 30)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('integriti_case_dtl');
	}

}
