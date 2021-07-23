<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFeedbackWhatsappAddMediaDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_whatsapp_details', function ($table) {
            $table->string('message_type')->nullable()->after('message_cuid');
            $table->string('message_caption')->nullable()->after('message_type');
            $table->string('message_mimetype')->nullable()->after('message_caption');
            $table->string('message_size')->nullable()->after('message_mimetype');
            $table->string('message_duration')->nullable()->after('message_size');
            $table->string('message_thumb')->nullable()->after('message_duration');
            $table->string('message_url')->nullable()->after('message_thumb');
            $table->string('message_lng')->nullable()->after('message_url');
            $table->string('message_lat')->nullable()->after('message_lng');
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
            $table->dropColumn('message_type');
            $table->dropColumn('message_caption');
            $table->dropColumn('message_mimetype');
            $table->dropColumn('message_size');
            $table->dropColumn('message_duration');
            $table->dropColumn('message_thumb');
            $table->dropColumn('message_url');
            $table->dropColumn('message_lng');
            $table->dropColumn('message_lat');
        });
    }
}
