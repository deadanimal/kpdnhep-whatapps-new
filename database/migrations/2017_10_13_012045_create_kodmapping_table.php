<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKodmappingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kodmapping', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('order_asal')->nullable();
			$table->string('kategori', 30)->nullable();
			$table->string('agensi', 30)->nullable();
			$table->string('koddiberi', 10)->nullable();
			$table->string('infokoddiberi', 75)->nullable();
			$table->string('kodsistem', 10)->nullable();
			$table->string('keterangan', 75)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kodmapping');
	}

}
