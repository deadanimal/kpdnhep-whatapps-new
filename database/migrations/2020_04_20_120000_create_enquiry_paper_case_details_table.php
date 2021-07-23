<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiryPaperCaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiry_paper_case_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('case_number')->nullable();
            $table->string('case_status_code')->nullable();
            $table->string('is_current_status')->nullable();
            $table->text('detail_note')->nullable();
            $table->text('detail_result')->nullable();
            $table->text('detail_answer')->nullable();
            $table->unsignedInteger('action_from_user_id')->nullable();
            $table->string('action_from_branch_code')->nullable();
            $table->unsignedInteger('action_to_user_id')->nullable();
            $table->string('action_to_branch_code')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiry_paper_case_details');
    }
}
