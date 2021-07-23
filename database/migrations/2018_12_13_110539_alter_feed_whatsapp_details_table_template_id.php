<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFeedWhatsappDetailsTableTemplateId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_whatsapp_details', function ($table) {
            $table->integer('template_id')->unsigned()->nullable()->after('ticketable_id');

            $table->foreign('template_id')->references('id')->on('feed_templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_whatsapp_details', function ($table) {
            $table->dropColumn('template_id');
        });
    }
}
