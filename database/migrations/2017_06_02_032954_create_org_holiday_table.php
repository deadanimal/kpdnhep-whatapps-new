<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('org_holiday');
        Schema::create('org_holiday', function (Blueprint $table) {
            $table->increments('id');
            $table->text('holiday_name', 100);
            $table->date('holiday_date');
            $table->char('work_code', 5);
            $table->char('repeat_yearly', 1);
            $table->string('state_code', 5)->nullable();
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
        Schema::table('org_holiday', function (Blueprint $table) {
            //
        });
    }
}
