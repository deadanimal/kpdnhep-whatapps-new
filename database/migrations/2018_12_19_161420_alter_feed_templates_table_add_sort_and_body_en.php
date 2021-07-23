<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFeedTemplatesTableAddSortAndBodyEn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_templates', function ($table) {
            $table->integer('sort')->default(99)->after('id');
            $table->text('body_en')->nullable()->after('body');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_templates', function ($table) {
            $table->dropColumn('sort');
            $table->dropColumn('body_en');
        });
    }
}
