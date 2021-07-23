<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegritiMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integriti_meetings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('IM_MEETINGNUM', 20)->nullable()->comment('Bilangan');
            // $table->dateTime('IM_MEETINGDATE')->nullable();
            $table->date('IM_MEETINGDATE')->nullable()->comment('Tarikh');
            $table->string('IM_CHAIRPERSON', 50)->nullable()->comment('Pengerusi');
            $table->char('IM_STATUS', 1)->nullable()->comment('Status');
            $table->dateTime('IM_CREATED_AT')->nullable();
            $table->string('IM_CREATED_BY', 30)->nullable();
            $table->dateTime('IM_UPDATED_AT')->nullable();
            $table->string('IM_UPDATED_BY', 30)->nullable();
            $table->dateTime('IM_DELETED_AT')->nullable();
            $table->string('IM_DELETED_BY', 30)->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integriti_meetings');
    }
}
