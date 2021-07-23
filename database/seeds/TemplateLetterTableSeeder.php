<?php

use Illuminate\Database\Seeder;

class TemplateLetterTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('template_letter')->delete();
        
        \DB::table('template_letter')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'SURAT AKUAN PENERIMAAN ADUAN',
                'header' => '<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>',
                'body' => '<p style="margin-left: 560px;"><span style="font-size:12px;">Tarikh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :<br />
No. Aduan&nbsp;&nbsp; : #NOADUAN#</span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">#NAMAPENGADU#</span></p>

<div><span style="font-size:12px;">#ALAMATPENGADU#</span></div>

<div><span style="font-size:12px;">#DAERAHPENGADU#</span></div>

<div><span style="font-size:12px;">#NEGERIPENGADU#</span></div>

<div>&nbsp;</div>

<div>&nbsp;</div>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">Tuan/Puan, </span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><strong><span style="font-size:12px;">PENGESAHAN PENERIMAAN ADUAN (#NOADUAN#)</span></strong></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">Dengan segala hormatnya perkara di atas adalah dirujuk,</span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa aduan tuan/puan telah diterima dan kini di dalam proses untuk diambil tindakan selanjutnya dalam masa 21 hari bekerja.</span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sebarang pertanyaan atau semakan aduan tuan/puan bolehlah melayari dan log masuk ke Portal e-Aduan KPDNKK di <strong>https://e-aduan.kpdnkk.gov.my </strong>atau menghubungi talian Hotline KPDNKK 1-800-886-800 dengan mengemukakan No. Kad Pengenalan atau&nbsp; No. Aduan tuan/puan. </span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</span></p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">&quot;BERKHIDMAT UNTUK NEGARA&quot;</span><br />
<span style="font-size:12px;">&quot;KUASA PENGGUNA : SATU PENGGUNA SATU SUARA&quot;</span></p>

<p><span style="font-size:12px;">Sekian, terima kasih.</span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">Kementerian Perdagangan Dalam Negeri, Koperasi dan Kepenggunaan (KPDNKK)</span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left: 0cm; margin-right: 0cm; text-align: center;"><span style="font-size:10px;"><em>**(Ini adalah surat cetakan computer yang dijana melalui Sistem eAduan 2.0 KPDNKK, tandatangan adalah tidak diperlukan.)</em></span></p>',
                'letter_type' => '01',
                'letter_cat' => NULL,
                'letter_code' => '1',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-06-09 11:59:10',
                'updated_at' => '2018-03-13 20:57:46',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'SURAT MAKLUMAN LANTIKAN PEGAWAI PENYIASAT ADUAN',
                'header' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>',
                'body' => '<p style="margin-left: 480px; margin-right: 0cm; text-align: justify;"><span style="font-size:12px;">Tarikh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :<br />
No. Aduan&nbsp; : #NOADUAN#</span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">#NAMAPENGADU#</span></p>

<div><span style="font-size:12px;">#ALAMATPENGADU#</span></div>

<div><span style="font-size:12px;">#POSKODPENGADU#</span></div>

<div><span style="font-size:12px;">#DAERAHPENGADU#</span></div>

<div><span style="font-size:12px;">#NEGERIPENGADU#</span></div>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">Tuan/Puan, </span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;"><strong>PEMBERITAHUAN PELANTIKAN PEGAWAI PENYIASAT ADUAN<br />
No. Aduan&nbsp; : #NOADUAN#</strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">Dengan segala hormatnya perkara di atas adalah dirujuk.</span></p>

<p><span style="font-size:12px;">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa saya merupakan Pegawai Penyiasat bagi No Aduan tuan/puan seperti di atas. Sebarang pertanyaan/maklumbalas berkenaan aduan ini bolehlah menghubungi saya melalui No. Telefon : #NOTELPEJABATPEGAWAI# atau emel ke (#EMAILPEGAWAIPENYIASAT#).</span></p>

<p><span style="font-size:12px;">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span> <span style="font-size:12px;">Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</span></p>',
                'footer' => '<p><span style="font-size:12px;">Sekian, terima kasih.</span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:12px;">&quot;BERKHIDMAT UNTUK NEGARA&quot;<br />
&quot;KUASA PENGGUNA : SATU PENGGUNA SATU SUARA&quot;</span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><br />
<span style="font-size:12px;">Saya yang menurut perintah,</span></p>

<p>&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><br />
<span style="font-size:12px;"><strong>(#NAMAPEGAWAIPENYIASAT#)</strong><br />
#ALAMATCAWANGANPEGAWAIPENYIASAT#<br />
Kementerian Perdagangan Dalam Negeri, Koperasi dan Kepenggunaan (KPDNKK)</span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left: 0cm; margin-right: 0cm; text-align: center;"><span style="font-size:10px;"><em>**(Ini adalah surat cetakan computer yang dijana melalui Sistem eAduan 2.0 KPDNKK, tandatangan adalah tidak diperlukan.)</em></span></p>',
                'letter_type' => '01',
                'letter_cat' => NULL,
                'letter_code' => '2',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-11 10:00:45',
                'updated_at' => '2018-03-13 21:13:31',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'SURAT ARAHAN TUGAS RASMI',
                'header' => '<p style="text-align:center"><u>ARAHAN TUGAS RASMI</u></p>

<table border="0" cellpadding="10" cellspacing="0" style="width:800px">
<tbody>
<tr>
<td style="width:50%">Negeri : #NEGERIPEGAWAI#</td>
<td>&nbsp;</td>
</tr>
<tr>
<td style="width:50%">Cawangan : #CAWANGANPEGAWAI#</td>
<td>Rujukan ATR : B.PGK.(SK)</td>
</tr>
</tbody>
</table>

<p><u><em>Untuk Diisi Oleh KP / KPPK / KU / KPPN / KPPD</em></u></p>

<table border="0" cellpadding="10" cellspacing="0" style="width:800px">
<tbody>
<tr>
<td style="width:50%">1. Tarikh : #TARIKHPENUGASAN#</td>
<td style="width:50%">Masa : #MASAPENUGASAN#</td>
</tr>
<tr>
<td style="width:50%">2. Nama Pegawai-Pegawai Yang Menerima Tugas</td>
<td style="width:50%">&nbsp;</td>
</tr>
</tbody>
</table>

<table border="0" cellpadding="10" cellspacing="0" style="width:800px">
<tbody>
<tr>
<td style="width:40%">&nbsp;&nbsp;&nbsp; Ketua Pasukan Pemeriksaan</td>
<td>: #NAMAPEGAWAIPENUGASAN#</td>
</tr>
</tbody>
</table>',
                'body' => '<table border="0" cellpadding="10" cellspacing="0" style="width:800px">
<tbody>
<tr>
<td style="width:40%">3. Jenis Arahan</td>
<td>: Tugasan rasmi</td>
</tr>
<tr>
<td style="width:40%">4. Tugas yang diarahkan</td>
<td>: ____________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">5. Maklumat Tambahan</td>
<td>: ____________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">6. Tempat</td>
<td>: ____________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">7. Pegawai Yang Memberi Arahan</td>
<td>: ____________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">
<p>&nbsp;</p>

<p>&nbsp;&nbsp; Tandatangan</p>
</td>
<td>
<p>&nbsp;</p>

<p>: ____________________________________________________________</p>
</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp; Nama</td>
<td>: ____________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp; Jawatan</td>
<td>: ____________________________________________________________</td>
</tr>
</tbody>
</table>',
                'footer' => '<p><u><em>Untuk Diisi Oleh Ketua Pasukan / Pegawai Serbuan / Pegawai Pasukan</em></u></p>

<table border="0" cellpadding="10" cellspacing="0" style="width:800px">
<tbody>
<tr>
<td style="width:50%">1. Rujukan Aduan</td>
<td style="width:50%">: #NOADUAN#</td>
</tr>
<tr>
<td style="width:50%">2. Maklumat Kenderaan Yang Digunakan</td>
<td style="width:50%">&nbsp;</td>
</tr>
<tr>
<td style="width:50%">&nbsp;&nbsp;&nbsp; No. Pendaftaran (Pejabat)</td>
<td style="width:50%">: __________________________________________________</td>
</tr>
<tr>
<td style="width:50%">3. Maklumat Tambahan</td>
<td style="width:50%">: __________________________________________________</td>
</tr>
</tbody>
</table>',
                'letter_type' => '02',
                'letter_cat' => NULL,
                'letter_code' => '2',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:34:00',
                'updated_at' => '2017-10-03 23:10:00',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'SURAT MAKLUMAN HASIL SIASATAN',
                'header' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">#NAMAPENGADU#</span></span></p>

<div><span style="font-size:11pt">#ALAMATPENGADU#</span></div>

<div><span style="font-size:11pt">#POSKODPENGADU#</span></div>

<div><span style="font-size:11pt">#DAERAHPENGADU#</span></div>

<div><span style="font-size:11pt">#NEGERIPENGADU#</span></div>',
                'body' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Tuan/Puan, </span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">MAKLUMAN HASIL SIASATAN (#NOADUAN#)</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Dengan segala hormatnya perkara di atas adalah dirujuk,</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa penyiasatan ke atas aduan tuan/puan telah dilaksanakan dan mendapati bahawa: </span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">#JAWAPANKEPADAPENGADU#</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Sekian, terima kasih.</span></span></p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">&ldquo;BERKHIDMAT UNTUK NEGARA&rdquo;</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Saya yang menurut perintah,</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">(#NAMAPEGAWAIPENYIASAT#)</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">#ALAMATCAWANGANPEGAWAIPENYIASAT#</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">(Ini adalah surat cetakan computer, tandatangan tidak diperlukan.)</span></span></p>',
                'letter_type' => '01',
                'letter_cat' => NULL,
                'letter_code' => '3',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:35:28',
                'updated_at' => '2017-09-14 02:58:59',
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'SURAT MAKLUMAN ADUAN LUAR BIDANG KUASA',
                'header' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">#NAMAPENGADU#</span></span></p>

<div><span style="font-size:11pt">#ALAMATPENGADU#</span></div>

<div><span style="font-size:11pt">#POSKODPENGADU#</span></div>

<div><span style="font-size:11pt">#DAERAHPENGADU#</span></div>

<div><span style="font-size:11pt">#NEGERIPENGADU#</span></div>',
                'body' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Tuan/Puan, </span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">(#NOADUAN#)</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Dengan segala hormatnya perkara di atas adalah dirujuk,</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak&nbsp; Kementerian mengucapkan terima kasih di atas aduan yang telah dikemukakan kepada kami. Walau bagaimanapun, berdasarkan penelitian kami adalah didapati bahawa aduan ini adalah di luar bidangkuasa Kementerian ini. Sebarang pertanyaan tuan/puan bolehlah menghubungi talian Hotline KPDNKK <strong>1-800-886-800</strong> atau pejabat KPDNKK Negeri yang terdekat.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Sekian, terima kasih.</span></span></p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">&ldquo;BERKHIDMAT UNTUK NEGARA&rdquo;</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Saya yang menurut perintah,</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">(#NAMAPEGAWAIPENYIASAT#)</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">#ALAMATCAWANGANPEGAWAIPENYIASAT#</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">(Ini adalah surat cetakan computer, tandatangan tidak diperlukan.)</span></span></p>',
                'letter_type' => '01',
                'letter_cat' => NULL,
                'letter_code' => '8',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:35:48',
                'updated_at' => '2018-02-05 16:03:20',
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'SURAT RUJUK KE AGENSI KPDNKK',
                'header' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">#NAMAAGENSI#</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">#ALAMATAGENSI#</span></span></p>',
                'body' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Tuan/Puan, </span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">(#NOADUAN#)</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Dengan segala hormatnya perkara di atas adalah dirujuk,</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian telah menerima aduan seperti berikut:</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">#KETERANGANADUAN#</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa penyiasatan ke atas aduan tuan/puan telah dilaksanakan dan mendapati bahawa: </span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">#JAWAPANKEPADAPENGADU#</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sehubungan dengan itu, adalah dipohon kerjasama pihak tuan/puan untuk mengambil tindakan terhadap aduan ini berdasarkan tindakan dan prosedur di Agensi tuan/puan. Sebarang maklumbalas hendaklah dimaklumkan secara terus kepada pengadu dan disalinkan kepada Kementerian ini bagi tujuan rekod.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Sekian, terima kasih.</span></span></p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">&ldquo;BERKHIDMAT UNTUK NEGARA&rdquo;</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Saya yang menurut perintah,</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">(#NAMAPEGAWAIPENYIASAT#)</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">#ALAMATCAWANGANPEGAWAIPENYIASAT#</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">(Ini adalah surat cetakan komputer, tandatangan tidak diperlukan.)</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">Salinan kepada:</span></span></p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">#NAMAPENGADU#</span></span></p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">#ALAMATPENGADU#</span></span></p>',
                'letter_type' => '01',
                'letter_cat' => NULL,
                'letter_code' => '4',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:36:17',
                'updated_at' => '2018-02-23 15:00:00',
            ),
            6 => 
            array (
                'id' => 7,
                'title' => 'SURAT RUJUKAN ADUAN KE TTPM',
                'header' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">#NAMAPENGADU#</span></span></p>

<div><span style="font-size:11pt">#ALAMATPENGADU#</span></div>

<div><span style="font-size:11pt">#POSKODPENGADU#</span></div>

<div><span style="font-size:11pt">#DAERAHPENGADU#</span></div>

<div><span style="font-size:11pt">#NEGERIPENGADU#</span></div>',
                'body' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Tuan/Puan, </span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">(#NOADUAN#)</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Dengan segala hormatnya perkara di atas adalah dirujuk,</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak&nbsp; Kementerian mengucapkan terima kasih di atas aduan yang telah dikemukakan kepada kami. Walau bagaimanapun, berdasarkan penelitian kami adalah didapati bahawa pihak tuan/puan disarankan untuk merujuk aduan ini kepada pihak Tribunal Tuntutan Pengguna Malaysia (TTPM). Sebarang pertanyaan tuan/puan bolehlah menghubungi talian Hotline TTPM <strong>1-800-889-811</strong> / <strong>03-8882 5822</strong> atau melayari portal <strong>http//:ttpm.kpdnkk.gov.my</strong></span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Sekian, terima kasih.</span></span></p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">&ldquo;BERKHIDMAT UNTUK NEGARA&rdquo;</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Saya yang menurut perintah,</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">(#NAMAPEGAWAIPENYIASAT#)</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">#ALAMATCAWANGANPEGAWAIPENYIASAT#</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">(Ini adalah surat cetakan computer, tandatangan tidak diperlukan.)</span></span></p>',
                'letter_type' => '01',
                'letter_cat' => NULL,
                'letter_code' => '5',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:36:43',
                'updated_at' => '2017-09-14 03:24:06',
            ),
            7 => 
            array (
                'id' => 8,
                'title' => 'SURAT MAKLUMAT TIDAK LENGKAP',
                'header' => '<p style="text-align: justify;">#NAMAPENGADU#</p>

<p style="text-align: justify;">#ALAMATPENGADU#</p>

<p style="text-align: justify;">#DAERAHPENGADU#</p>

<p style="text-align: justify;">#NEGERIPENGADU#</p>',
                'body' => '<p style="text-align: justify;">Tuan/Puan,</p>

<p style="text-align: justify;">&nbsp;</p>

<p style="text-align: justify;">PENGESAHAN PENERIMAAN ADUAN (#NOADUAN#)</p>

<p style="text-align: justify;">&nbsp;</p>

<p style="text-align: justify;">Dengan segala hormatnya perkara di atas adalah dirujuk,</p>

<p style="text-align: justify;">&nbsp;</p>

<p style="text-align: justify;">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa maklumat aduan tidak lengkap. Mohon pihak tuan/puan mengemaskini aduan dalam masa 14 hari.</p>

<p style="text-align: justify;">3.&nbsp; &nbsp; &nbsp; #JAWAPANKEPADAPENGADU#</p>

<p style="text-align: justify;">4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sebarang pertanyaan atau semakan aduan tuan/puan bolehlah menghubungi talian Hotline KPDNKK 1-800-886-800 atau melayari Portal e-Aduan KPDNKK di <strong>https://e-aduan.kpdnkk.gov.my</strong> dengan memasukkan no. aduan tuan/puan.</p>

<p style="text-align: justify;">5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</p>

<p style="text-align: justify;">&nbsp;</p>

<p style="text-align: justify;">Sekian, terima kasih.</p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><strong>&quot;BERKHIDMAT UNTUK NEGARA&quot;</strong></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">Kementerian Perdagangan Dalam Negeri, Koperasi Dan Kepenggunaan (KPDNKK)</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">(Ini adalah surat cetakan komputer, tandatangan tidak diperlukan.)</p>',
                'letter_type' => '01',
                'letter_cat' => NULL,
                'letter_code' => '7',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2018-02-13 09:20:00',
                'updated_at' => '2018-02-14 11:20:00',
            ),
            8 => 
            array (
                'id' => 9,
            'title' => 'SURAT ARAHAN TUGAS RASMI (PEMERIKSAAN)',
                'header' => '<table border="0" style="width: 100%;">
<tbody>
<tr>
<td style="width: 20%; text-align: center;"><img alt="eAduanV2" src="img/logo2_0.png" style="width: 18%;" /></td>
<td align="center">
<p>KEMENTERIAN PERDAGANGAN DALAM NEGERI,</p>

<p>KOPERASI DAN KEPENGGUNAAN</p>
</td>
<td style="width: 20%; text-align: center;"><img alt="KPDNKK" src="assets/images/kpdnkk-128x112.png" style="width: 6%;" /></td>
</tr>
</tbody>
</table>

<hr />
<p style="text-align:center"><u>ARAHAN TUGAS RASMI (PEMERIKSAAN)</u></p>

<table border="0" style="width:100%">
<tbody>
<tr>
<td style="width:50%">Negeri : #NEGERIPEGAWAI#</td>
<td>&nbsp;</td>
</tr>
<tr>
<td style="width:50%">Cawangan : #CAWANGANPEGAWAI#</td>
<td>Rujukan ATR : __________________________________</td>
</tr>
</tbody>
</table>

<p><u><em>Untuk Diisi Oleh KP / KPPK / KU / KPPN / KPPD</em></u></p>

<table border="0" style="width: 100%;">
<tbody>
<tr>
<td style="width:50%">1. Tarikh : #TARIKHPENUGASAN#</td>
<td style="width:50%">Masa : #MASAPENUGASAN#</td>
</tr>
</tbody>
</table>

<table border="0" style="width: 100%;">
<tbody>
<tr>
<td>2. Nama Pegawai-Pegawai Yang Menerima Tugas</td>
</tr>
</tbody>
</table>

<table border="0" style="width:100%">
<tbody>
<tr>
<td style="width:40%">&nbsp;&nbsp;&nbsp; Ketua Pasukan Pemeriksaan</td>
<td>: #NAMAPEGAWAIPENUGASAN#</td>
</tr>
</tbody>
</table>',
                'body' => '<table border="0" cellpadding="5" style="width: 100%;">
<tbody>
<tr>
<td style="width:40%">3. Jenis Arahan</td>
<td>: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">4. Tugas Yang Diarahkan</td>
<td>: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">5. Maklumat Tambahan</td>
<td>: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">6. Tempat</td>
<td>: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">7. Pegawai Yang Memberi</td>
<td>: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">8. Pegawai Yang Menyedia</td>
<td>: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp; Tandatangan</td>
<td>: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp; Nama</td>
<td>: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp; Jawatan</td>
<td>: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp; Tarikh</td>
<td>: ________________________________________________________</td>
</tr>
</tbody>
</table>',
                'footer' => '<p><u><em>Untuk Diisi Oleh Ketua Pasukan / Pegawai Serbuan / Pegawai Pasukan</em></u></p>

<table border="0" style="width: 100%;">
<tbody>
<tr>
<td style="width:40%">1. Rujukan Aduan</td>
<td style="width:60%">: #NOADUAN#</td>
</tr>
</tbody>
</table>

<table border="0" style="width: 100%;">
<tbody>
<tr>
<td>2. Maklumat Kenderaan Yang Digunakan</td>
</tr>
</tbody>
</table>

<table border="0" cellpadding="5" style="width: 100%;">
<tbody>
<tr>
<td style="width: 40%; text-align: center;">No. Pendaftaran (Pejabat)</td>
<td style="width: 60%; text-align: center;">No. Pendaftaran (Sendiri)</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp;&nbsp; ___________________________________</td>
<td style="width:60%">&nbsp;&nbsp;________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">3. Maklumat Tambahan</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
</tbody>
</table>',
                'letter_type' => '02',
                'letter_cat' => NULL,
                'letter_code' => '2',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2018-02-13 09:30:00',
                'updated_at' => '2018-02-14 11:30:00',
            ),
            9 => 
            array (
                'id' => 10,
            'title' => 'SURAT ARAHAN TUGAS RASMI (PENYIASATAN)',
                'header' => '<table border="0" style="width: 100%;">
<tbody>
<tr>
<td style="width: 20%; text-align: center;"><img alt="eAduanV2" src="img/logo2_0.png" style="width: 18%;" /></td>
<td align="center">
<p>KEMENTERIAN PERDAGANGAN DALAM NEGERI,</p>

<p>KOPERASI DAN KEPENGGUNAAN</p>
</td>
<td style="width: 20%; text-align: center;"><img alt="KPDNKK" src="assets/images/kpdnkk-128x112.png" style="width: 6%;" /></td>
</tr>
</tbody>
</table>

<hr />
<p style="text-align:center">ARAHAN TUGAS RASMI (PENYIASATAN)</p>

<table border="0" style="width: 100%;">
<tbody>
<tr>
<td style="width:50%">&nbsp;</td>
<td style="width:50%">Rujukan ATR : ________________________________</td>
</tr>
</tbody>
</table>

<p><u><em>Untuk Diisi Oleh KP / KU / PPDN / KC</em></u></p>

<table border="0" style="width: 100%;">
<tbody>
<tr>
<td style="width:50%">1. Tarikh : #TARIKHPENUGASAN#</td>
<td style="width:50%">Masa : #MASAPENUGASAN#</td>
</tr>
</tbody>
</table>

<table border="0" style="width: 100%;">
<tbody>
<tr>
<td>2. Nama Pegawai-Pegawai Yang Menerima Tugas</td>
</tr>
</tbody>
</table>

<table border="0" style="width: 100%;">
<tbody>
<tr>
<td style="width:40%">&nbsp;&nbsp;&nbsp; Pegawai Serbuan</td>
<td style="width:60%">: #NAMAPEGAWAIPENUGASAN#</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp;&nbsp; Pegawai Penyiasat</td>
<td style="width:60%">: #NAMAPEGAWAIPENUGASAN#</td>
</tr>
</tbody>
</table>',
                'body' => '<table border="0" cellpadding="5" style="width: 100%;">
<tbody>
<tr>
<td style="width:40%">3. Jenis Arahan</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">4. Tugas Yang Diarahkan</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">5. Maklumat Tambahan</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">6. Tempat</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">7. Pegawai Yang Memberi Arahan</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp; Tandatangan</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp; Nama</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp; Jawatan</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">&nbsp;&nbsp; Tarikh</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
</tbody>
</table>',
                'footer' => '<p><u><em>Untuk Diisi Oleh Pegawai Serbuan / Pegawai Penyiasat</em></u></p>

<table border="0" cellpadding="5" style="width: 100%;">
<tbody>
<tr>
<td style="width:40%">1. Rujukan Kertas Siasatan</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">2. Rujukan Aduan</td>
<td style="width:60%">: #NOADUAN#</td>
</tr>
<tr>
<td style="width:40%">3. Rujukan Tribunal</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">4. Rujukan Lain</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
<tr>
<td style="width:40%">5. Kenderaan : ______________________</td>
<td style="width:60%">&nbsp;&nbsp;No. Pendaftaran : ______________________________________</td>
</tr>
<tr>
<td style="width:40%">6. Maklumat Tambahan</td>
<td style="width:60%">: ________________________________________________________</td>
</tr>
</tbody>
</table>',
                'letter_type' => '02',
                'letter_cat' => NULL,
                'letter_code' => '2',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2018-02-13 09:40:00',
                'updated_at' => '2018-02-14 11:40:00',
            ),
            10 => 
            array (
                'id' => 11,
                'title' => 'SURAT MAKLUMAN PINDAH ADUAN',
                'header' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11px">#NAMAPENGADU#</span></p>

<div><span style="font-size:11px">#ALAMATPENGADU#</span></div>

<div><span style="font-size:11px">#POSKODPENGADU#</span></div>

<div><span style="font-size:11px">#DAERAHPENGADU#</span></div>

<div><span style="font-size:11px">#NEGERIPENGADU#</span></div>',
                'body' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11px">Tuan/Puan, </span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11px"><strong>PEMBERITAHUAN PELANTIKAN PEGAWAI PENYIASAT ADUAN </strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11px"><strong>(#NOADUAN#)</strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11px">Dengan segala hormatnya perkara di atas adalah dirujuk,</span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p><span style="font-size:11px">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa saya merupakan Pegawai Penyiasat bagi aduan tuan/puan. Sebarang pertanyaan/maklumbalas berkenaan aduan ini bolehlah menghubungi saya melalui alamat emel (#EMAILPEGAWAIPENYIASAT#).</span></p>

<p><span style="font-size:11px">Sekian, terima kasih.</span></p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11px"><strong>&ldquo;BERKHIDMAT UNTUK NEGARA&rdquo;</strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11px">Saya yang menurut perintah,</span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11px"><strong>(#NAMAPEGAWAIPENYIASAT#)</strong></span></p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11px">#ALAMATCAWANGANPEGAWAIPENYIASAT#</span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11px">(Ini adalah surat cetakan computer, tandatangan tidak diperlukan.)</span></p>',
                'letter_type' => '01',
                'letter_cat' => NULL,
                'letter_code' => '0',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2018-03-24 11:40:13',
                'updated_at' => '2018-03-24 11:58:13',
            ),
            11 => 
            array (
                'id' => 12,
                'title' => 'SURAT SELESAI - MAKLUMAT TIDAK LENGKAP',
                'header' => '<p style="text-align: justify;">#NAMAPENGADU#</p>

<p style="text-align: justify;">#ALAMATPENGADU#</p>

<p style="text-align: justify;">#DAERAHPENGADU#</p>

<p style="text-align: justify;">#NEGERIPENGADU#</p>',
                'body' => '<p style="text-align: justify;">Tuan/Puan,</p>

<p style="text-align: justify;">&nbsp;</p>

<p style="text-align: justify;">PENGESAHAN PENERIMAAN ADUAN (#NOADUAN#)</p>

<p style="text-align: justify;">&nbsp;</p>

<p style="text-align: justify;">Dengan segala hormatnya perkara di atas adalah dirujuk,</p>

<p style="text-align: justify;">&nbsp;</p>

<p style="text-align: justify;">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa TUAN/PUAN tidak mengemaskini maklumat aduan dalam tempoh masa yang ditetapkan.</p>

<p style="text-align: justify;">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sebarang pertanyaan atau semakan aduan tuan/puan bolehlah menghubungi talian Hotline KPDNKK 1-800-886-800 atau melayari Portal e-Aduan KPDNKK di <strong>https://e-aduan.kpdnkk.gov.my</strong> dengan memasukkan no. aduan tuan/puan.</p>

<p style="text-align: justify;">4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</p>

<p style="text-align: justify;">&nbsp;</p>

<p style="text-align: justify;">Sekian, terima kasih.</p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><strong>&quot;BERKHIDMAT UNTUK NEGARA&quot;</strong></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">Kementerian Perdagangan Dalam Negeri, Koperasi Dan Kepenggunaan (KPDNKK)</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">(Ini adalah surat cetakan komputer, tandatangan tidak diperlukan.)</p>',
                'letter_type' => '01',
                'letter_cat' => NULL,
                'letter_code' => '12',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2018-03-26 18:18:47',
                'updated_at' => '2018-03-26 18:18:47',
            ),
        ));
        
        
    }
}