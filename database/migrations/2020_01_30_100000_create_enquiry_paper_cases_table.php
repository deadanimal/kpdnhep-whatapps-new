<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiryPaperCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiry_paper_cases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_kes')->nullable();
            $table->string('kod_negeri')->nullable();
            $table->string('kod_cawangan')->nullable();
            $table->string('akta')->nullable();
            $table->string('asas_tindakan')->nullable();
            $table->date('tkh_kejadian')->nullable();
            $table->date('tkh_serahan')->nullable();
            $table->string('pengelasan_kes')->nullable();
            $table->text('kategori_kesalahan')->nullable();
            $table->text('kesalahan')->nullable();
            $table->string('pegawai_serbuan_ro')->nullable();
            $table->string('pegawai_penyiasat_aio')->nullable();
            $table->string('pegawai_penyiasat_io')->nullable();
            $table->string('no_report_polis')->nullable();
            $table->string('no_ssm')->nullable();
            $table->string('nama_premis_syarikat')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jenama_premis')->nullable();
            $table->string('kawasan')->nullable();
            $table->string('jenis_perniagaan')->nullable();
            $table->string('kategori_premis')->nullable();
            $table->string('jenis_premis')->nullable();
            $table->string('status_okt')->nullable();
            $table->string('jantina')->nullable();
            $table->text('nama_okt')->nullable();
            $table->string('taraf_kerakyatan')->nullable();
            $table->string('catatan_kerakyatan')->nullable();
            $table->string('no_ic_pasport')->nullable();
            $table->decimal('nilai_award_app_shj1')->nullable();
            $table->date('tkh_kompaun_dikeluarkan')->nullable();
            $table->decimal('nilai_kompaun_ditawarkan1')->nullable();
            $table->date('tkh_kompaun_diserahkan')->nullable();
            $table->date('tkh_kompaun_dibayar')->nullable();
            $table->decimal('nilai_kompaun_dibayar1')->nullable();
            $table->string('no_resit_pembayaran_kompaun')->nullable();
            $table->string('pegawai_pendakwa')->nullable();
            $table->string('mahkamah')->nullable();
            $table->string('no_pendaftaran_mahkamah')->nullable();
            $table->date('tkh_daftar_mahkamah')->nullable();
            $table->date('tkh_sebutan')->nullable();
            $table->date('tkh_bicara')->nullable();
            $table->date('tkh_denda')->nullable();
            $table->decimal('nilai_denda1')->nullable();
            $table->date('tkh_penjara')->nullable();
            $table->string('tempoh_penjara')->nullable();
            $table->date('tkh_dnaa')->nullable();
            $table->date('tkh_nfa')->nullable();
            $table->date('tkh_ad')->nullable();
            $table->date('tkh_kes_tutup')->nullable();
            $table->string('status_kes')->nullable();
            $table->string('status_kes_det')->nullable();
            $table->string('pergerakan_ep')->nullable();
            $table->text('status_eksibit')->nullable();
            $table->string('week')->nullable();
            $table->string('tpr')->nullable();
            $table->string('bs_dalam_siasatan')->nullable();
            $table->string('dicatat_oleh')->nullable();
            $table->date('tkh_dicatat')->nullable();
            $table->string('dikemaskini_oleh')->nullable();
            $table->date('tkh_dikemaskini')->nullable();
            $table->text('c01')->nullable();
            $table->text('c02')->nullable();
            $table->text('c03')->nullable();
            $table->text('c04')->nullable();
            $table->text('c05')->nullable();
            $table->text('c06')->nullable();
            $table->text('c07')->nullable();
            $table->text('c08')->nullable();
            $table->text('c09')->nullable();
            $table->text('c10')->nullable();
            $table->string('serahan_agensi')->nullable();
            $table->string('diterima_oleh')->nullable();
            $table->date('tkh_diterima')->nullable();
            $table->decimal('nilai_transaksi2')->nullable();
            $table->string('status_grp')->nullable();
            $table->decimal('nilai_transaksi')->nullable();
            $table->unsignedInteger('enquiry_paper_case_temp_id')->nullable();
            $table->decimal('nilai_award_app_shj')->nullable();
            $table->decimal('nilai_kompaun_ditawarkan')->nullable();
            $table->decimal('nilai_kompaun_dibayar')->nullable();
            $table->decimal('nilai_denda')->nullable();
            $table->string('case_status_code')->nullable();
            $table->string('complaint_case_number')->nullable();
            $table->text('note')->nullable();
            $table->text('result')->nullable();
            $table->text('answer')->nullable();
            $table->unsignedInteger('assign_by_user_id')->nullable();
            $table->unsignedInteger('io_user_id')->nullable();
            $table->unsignedInteger('aio_user_id')->nullable();
            $table->unsignedInteger('close_by_user_id')->nullable();
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
        Schema::dropIfExists('enquiry_paper_cases');
    }
}
