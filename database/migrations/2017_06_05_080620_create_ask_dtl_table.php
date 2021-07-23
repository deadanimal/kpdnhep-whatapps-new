<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAskDtlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ask_dtl', function(Blueprint $table)
		{
			$table->integer('AD_DTLID', true);
			$table->string('AD_ASKID', 12)->index('DTL_AD_ASKID_INFO');
			$table->string('AD_TYPE', 1)->nullable();
			$table->text('AD_DESC', 65535)->nullable();
			$table->integer('AD_DOCATTCHID')->nullable();
			$table->string('AD_ASKSTS', 2)->nullable(); // Add by Suk 20170921
                        $table->string('AD_CURSTS', 1)->nullable(); // Add by Suk 20170921
			$table->string('AD_CREBY', 30)->nullable();
			$table->dateTime('AD_CREDT')->nullable();
			$table->string('AD_MODBY', 30)->nullable();
			$table->dateTime('AD_MODDT')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ask_dtl');
	}

}
