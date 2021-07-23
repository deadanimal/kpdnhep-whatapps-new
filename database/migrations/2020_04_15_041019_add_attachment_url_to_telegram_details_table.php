<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttachmentUrlToTelegramDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_telegram_details', function (Blueprint $table) {
            $table->string('attachment_url')->nullable()->after('is_have_attachment');
            $table->string('attachment_mime')->nullable()->after('attachment_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_telegram_details', function (Blueprint $table) {
            $table->dropColumn('attachment_url');
            $table->dropColumn('attachment_mime');
        });
    }
}
