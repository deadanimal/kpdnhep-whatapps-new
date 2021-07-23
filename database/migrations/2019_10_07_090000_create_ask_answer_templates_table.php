<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAskAnswerTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ask_answer_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category', 10)->nullable();
            $table->string('code', 10)->nullable();
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->text('body_en')->nullable();
            $table->unsignedInteger('sort')->nullable();
            $table->char('status', 1)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ask_answer_templates');
    }
}
