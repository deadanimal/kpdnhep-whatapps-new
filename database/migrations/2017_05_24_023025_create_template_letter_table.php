<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateLetterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_letter', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100)->nullable();
            $table->longtext('header')->nullable();
            $table->longtext('body')->nullable();
            $table->longtext('footer')->nullable();
            $table->char('letter_type', 5)->nullable();
            $table->integer('letter_cat')->nullable();
            $table->string('letter_code', 10)->nullable();
            $table->char('status', 1)->nullable();
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
        Schema::dropIfExists('template_letter');
    }
}
