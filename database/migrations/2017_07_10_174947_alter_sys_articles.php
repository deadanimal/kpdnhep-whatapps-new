<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSysArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('sys_articles');
        Schema::create('sys_articles', function(Blueprint $table) 
            {
                $table->integer('id', true);
                $table->string('menu_type', 10)->nullable();
                $table->integer('menu_id')->nullable();
                $table->date('start_dt')->nullable();
                $table->date('end_dt')->nullable();
                $table->string('title_my');
                $table->string('title_en')->nullable();
                $table->text('content_my', 65535);
                $table->text('content_en', 65535)->nullable();
                $table->string('hits')->nullable();
                $table->string('cat', 25)->nullable(); // Add By Akmal 20171019 - Kategori
                $table->string('photo', 50)->nullable(); // Add By Akmal 20171019 - Gambar
                $table->string('link', 50)->nullable(); // Add By Akmal 20171019 - Pautan
                $table->char('status', 1)->nullable(); // Add By Akmal
                $table->string('created_by', 50)->nullable();
                $table->timestamps();
                $table->string('updated_by', 50)->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
