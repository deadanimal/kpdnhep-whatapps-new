<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTipTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tip', function(Blueprint $table)
		{
			$table->text('NO_KES', 65535)->nullable();
			$table->text('KOD_NEGERI', 65535)->nullable();
			$table->text('KOD_CAWANGAN', 65535)->nullable();
			$table->text('AKTA', 65535)->nullable();
			$table->text('ASAS_TINDAKAN', 65535)->nullable();
			$table->text('SERAHAN_AGENSI', 65535)->nullable();
			$table->text('C01', 65535)->nullable();
			$table->text('TKH_KEJADIAN', 65535)->nullable();
			$table->text('TKH_SERAHAN', 65535)->nullable();
			$table->text('PENGELASAN_KES', 65535)->nullable();
			$table->text('KATEGORI_KESALAHAN', 65535)->nullable();
			$table->text('KESALAHAN', 65535)->nullable();
			$table->text('PEGAWAI_SERBUAN_RO', 65535)->nullable();
			$table->text('PEGAWAI_PENYIASAT_AIO', 65535)->nullable();
			$table->text('PEGAWAI_PENYIASAT_IO', 65535)->nullable();
			$table->text('NO_REPORT_POLIS', 65535)->nullable();
			$table->text('NO_SSM', 65535)->nullable();
			$table->text('NAMA_PREMIS_SYARIKAT', 65535)->nullable();
			$table->text('ALAMAT', 65535)->nullable();
			$table->text('JENAMA_PREMIS', 65535)->nullable();
			$table->text('KAWASAN', 65535)->nullable();
			$table->text('JENIS_PERNIAGAAN', 65535)->nullable();
			$table->text('KATEGORI_PREMIS', 65535)->nullable();
			$table->text('JENIS_PREMIS', 65535)->nullable();
			$table->text('STATUS_OKT', 65535)->nullable();
			$table->text('JANTINA', 65535)->nullable();
			$table->text('NAMA_OKT', 65535)->nullable();
			$table->text('TARAF_KERAKYATAN', 65535)->nullable();
			$table->text('CATATAN_KERAKYATAN', 65535)->nullable();
			$table->text('NO_IC_PASPORT', 65535)->nullable();
			$table->text('NILAI_TRANSAKSI', 65535)->nullable();
			$table->text('KESBRG_ID', 65535)->nullable();
			$table->text('KATEGORI_BRG_RAMPASAN', 65535)->nullable();
			$table->text('JENIS_BRG_RAMPASAN', 65535)->nullable();
			$table->text('JENIS_BRG_RAMPASAN2', 65535)->nullable();
			$table->text('KARYA_TEMPATAN_ANTARABANGSA', 65535)->nullable();
			$table->text('JENAMA_BRG_RAMPASAN', 65535)->nullable();
			$table->text('BOB', 65535)->nullable();
			$table->text('NO_SEAL_SSM', 65535)->nullable();
			$table->text('UNIT', 65535)->nullable();
			$table->text('TONG', 65535)->nullable();
			$table->text('TANGKI', 65535)->nullable();
			$table->text('LITER', 65535)->nullable();
			$table->text('KG', 65535)->nullable();
			$table->text('NILAI_RAMPASAN', 65535)->nullable();
			$table->text('DICATAT_OLEH', 65535)->nullable();
			$table->text('TKH_DICATAT', 65535)->nullable();
			$table->text('DIKEMASKINI_OLEH', 65535)->nullable();
			$table->text('TKH_DIKEMASKINI', 65535)->nullable();
			$table->text('DITERIMA_OLEH', 65535)->nullable();
			$table->text('TKH_DITERIMA', 65535)->nullable();
			$table->text('TKH_KOMPAUN_DIKELUARKAN', 65535)->nullable();
			$table->text('NILAI_KOMPAUN_DITAWARKAN', 65535)->nullable();
			$table->text('TKH_KOMPAUN_DISERAHKAN', 65535)->nullable();
			$table->text('TKH_KOMPAUN_DIBAYAR', 65535)->nullable();
			$table->text('NILAI_KOMPAUN_DIBAYAR', 65535)->nullable();
			$table->text('NO_RESIT_PEMBAYARAN_KOMPAUN', 65535)->nullable();
			$table->text('PEGAWAI_PENDAKWA', 65535)->nullable();
			$table->text('MAHKAMAH', 65535)->nullable();
			$table->text('NO_PENDAFTARAN_MAHKAMAH', 65535)->nullable();
			$table->text('TKH_DAFTAR_MAHKAMAH', 65535)->nullable();
			$table->text('TKH_SEBUTAN', 65535)->nullable();
			$table->text('TKH_BICARA', 65535)->nullable();
			$table->text('TKH_DENDA', 65535)->nullable();
			$table->text('NILAI_DENDA', 65535)->nullable();
			$table->text('TKH_PENJARA', 65535)->nullable();
			$table->text('TEMPOH_PENJARA', 65535)->nullable();
			$table->text('TKH_DNAA', 65535)->nullable();
			$table->text('TKH_NFA', 65535)->nullable();
			$table->text('TKH_AD', 65535)->nullable();
			$table->text('TKH_KES_TUTUP', 65535)->nullable();
			$table->text('STATUS_GROUP', 65535)->nullable();
			$table->text('STATUS_KES', 65535)->nullable();
			$table->text('STATUS_KES_DET', 65535)->nullable();
			$table->text('PERGERAKAN_IP', 65535)->nullable();
			$table->text('STATUS_EKSIBIT', 65535)->nullable();
			$table->text('WEEK', 65535)->nullable();
			$table->text('TPR', 65535)->nullable();
			$table->text('BS_DALAM_SIASATAN', 65535)->nullable();
			$table->text('C02', 65535)->nullable();
			$table->text('C03', 65535)->nullable();
			$table->text('C04', 65535)->nullable();
			$table->text('C05', 65535)->nullable();
			$table->text('C06', 65535)->nullable();
			$table->text('C07', 65535)->nullable();
			$table->text('C08', 65535)->nullable();
			$table->text('C09', 65535)->nullable();
			$table->text('C10', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tip');
	}

}
