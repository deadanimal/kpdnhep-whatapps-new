<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiryPaperCaseLootsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiry_paper_case_loots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kesbrg_id')->nullable();
            $table->string('no_kes')->nullable();
            $table->string('kategori_brg_rampasan')->nullable();
            $table->string('jenis_brg_rampasan')->nullable();
            $table->string('jenis_brg_rampasan2')->nullable();
            $table->string('karya_tempatan_antarabangsa')->nullable();
            $table->string('jenama_brg_rampasan')->nullable();
            $table->string('bob')->nullable();
            $table->string('no_seal_ssm')->nullable();
            $table->string('unit1')->nullable();
            $table->string('tong1')->nullable();
            $table->string('tangki1')->nullable();
            $table->string('liter1')->nullable();
            $table->string('kg1')->nullable();
            $table->decimal('nilai_rampasan1')->nullable();
            $table->decimal('nilai_kenderaan1')->nullable();
            $table->string('dicatat_oleh')->nullable();
            $table->timestamp('tkh_dicatat')->nullable();
            $table->string('dikemaskini_oleh')->nullable();
            $table->timestamp('tkh_dikemaskini')->nullable();
            $table->text('l01')->nullable();
            $table->text('l02')->nullable();
            $table->text('l03')->nullable();
            $table->text('l04')->nullable();
            $table->text('l05')->nullable();
            $table->text('l06')->nullable();
            $table->text('l07')->nullable();
            $table->text('l08')->nullable();
            $table->text('l09')->nullable();
            $table->text('l10')->nullable();
            $table->string('diterima_oleh')->nullable();
            $table->dateTime('tkh_diterima')->nullable();
            $table->decimal('nilai_rampasan')->nullable();
            $table->unsignedInteger('enquiry_paper_case_temp_id')->nullable();
            $table->string('unit')->nullable();
            $table->string('tong')->nullable();
            $table->string('tangki')->nullable();
            $table->string('liter')->nullable();
            $table->string('kg')->nullable();
            $table->decimal('nilai_kenderaan')->nullable();
            $table->string('unit_metric')->nullable();
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
        Schema::dropIfExists('enquiry_paper_case_loots');
    }
}
