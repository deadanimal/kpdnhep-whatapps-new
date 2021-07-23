<?php

use Illuminate\Database\Seeder;

class TemplateEmailTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('template_email')->delete();
        
        \DB::table('template_email')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'EMEL PENERIMAAN',
                'header' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt">#NAMAPENGADU#</span></p>

<div><span style="font-size:11pt">#ALAMATPENGADU#</span></div>

<div><span style="font-size:11pt">#DAERAHPENGADU#</span></div>

<div><span style="font-size:11pt">#NEGERIPENGADU#</span></div>',
                'body' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Tuan/Puan, </span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">PENGESAHAN PENERIMAAN ADUAN (#NOADUAN#)</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Dengan segala hormatnya perkara di atas adalah dirujuk,</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa aduan tuan/puan telah diterima dan kini di dalam proses untuk diambil tindakan selanjutnya dalam masa 21 hari bekerja.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sebarang pertanyaan atau semakan aduan tuan/puan bolehlah menghubungi talian Hotline KPDNKK 1-800-886-800 atau melayari Portal e-Aduan KPDNKK di <strong>https://e-aduan.kpdnkk.gov.my</strong> dengan memasukkan &nbsp;&nbsp;no. aduan tuan/puan.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt">&nbsp;</span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p><span style="font-size:13.0pt">Sekian, terima kasih.</span></p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">&ldquo;BERKHIDMAT UNTUK NEGARA&rdquo;</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Kementerian Perdagangan Dalam Negeri, Koperasi dan Kepenggunaan (KPDNKK)</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">(Ini adalah surat cetakan komputer, tandatangan tidak diperlukan.)</span></span></p>',
                'email_type' => '01',
                'email_cat' => NULL,
                'email_code' => '1',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-06-09 16:00:00',
                'updated_at' => '2017-11-24 15:35:43',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'EMEL PENUGASAN',
                'header' => NULL,
                'body' => NULL,
                'footer' => NULL,
                'email_type' => '02',
                'email_cat' => NULL,
                'email_code' => NULL,
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:49:10',
                'updated_at' => '2017-08-21 15:49:10',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'EMEL PENUGASAN SEMULA',
                'header' => NULL,
                'body' => NULL,
                'footer' => NULL,
                'email_type' => '03',
                'email_cat' => NULL,
                'email_code' => NULL,
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:49:44',
                'updated_at' => '2017-08-21 15:49:44',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'EMEL GABUNG',
                'header' => NULL,
                'body' => NULL,
                'footer' => NULL,
                'email_type' => '04',
                'email_cat' => NULL,
                'email_code' => NULL,
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:50:06',
                'updated_at' => '2017-08-21 15:50:06',
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'EMEL PINDAH',
                'header' => NULL,
                'body' => NULL,
                'footer' => NULL,
                'email_type' => '05',
                'email_cat' => NULL,
                'email_code' => NULL,
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:50:16',
                'updated_at' => '2017-08-21 15:50:16',
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'EMEL PENYIASATAN',
                'header' => NULL,
                'body' => NULL,
                'footer' => NULL,
                'email_type' => '06',
                'email_cat' => NULL,
                'email_code' => NULL,
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:50:28',
                'updated_at' => '2017-08-21 15:50:28',
            ),
            6 => 
            array (
                'id' => 7,
                'title' => 'EMEL PENUTUPAN',
                'header' => NULL,
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

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">#ALAMATCAWANGANPEGAWAIPENYIASAT#</span></span></p>',
                'email_type' => '01',
                'email_cat' => NULL,
                'email_code' => '9',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:50:39',
                'updated_at' => '2017-11-06 19:00:00',
            ),
            7 => 
            array (
                'id' => 8,
                'title' => 'EMEL BUKA SEMULA',
                'header' => NULL,
                'body' => NULL,
                'footer' => NULL,
                'email_type' => '08',
                'email_cat' => NULL,
                'email_code' => NULL,
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2017-08-21 15:50:51',
                'updated_at' => '2017-08-21 15:51:00',
            ),
            8 => 
            array (
                'id' => 9,
                'title' => 'EMEL ADUAN LUAR BIDANG KUASA',
                'header' => '<p>#NAMAPENGADU#</p>

<p>#ALAMATPENGADU#</p>

<p>#DAERAHPENGADU#</p>

<p>#NEGERIPENGADU#</p>',
                'body' => '<p>Tuan/Puan,</p>

<p>&nbsp;</p>

<p>PEMBERITAHUAN ADUAN DILUAR BIDANG KUASA&nbsp; (#NOADUAN#)</p>

<p>&nbsp;</p>

<p>Dengan segala hormatnya perkara di atas adalah dirujuk,</p>

<p>&nbsp;</p>

<p>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa aduan tuan/puan di luar bidang kuasa Kementerian Perdagangan Dalam Negeri Koperasi Dan Kepenggunaan.</p>

<p>&nbsp;</p>

<p>3.&nbsp; &nbsp; &nbsp; #JAWAPANKEPADAPENGADU</p>

<p>&nbsp;</p>

<p>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sebarang pertanyaan atau semakan aduan tuan/puan bolehlah menghubungi talian Hotline KPDNKK 1-800-886-800 atau melayari Portal e-Aduan KPDNKK di <strong>https://e-aduan.kpdnkk.gov.my</strong> dengan memasukkan &nbsp;&nbsp;no. aduan tuan/puan.</p>

<p>&nbsp;</p>

<p>5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</p>

<p>&nbsp;</p>

<p>Sekian, terima kasih.</p>',
                'footer' => NULL,
                'email_type' => '01',
                'email_cat' => NULL,
                'email_code' => '8',
                'status' => '1',
                'created_by' => '1783',
                'updated_by' => '1783',
                'created_at' => '2017-12-04 13:04:35',
                'updated_at' => '2017-12-04 13:07:57',
            ),
            9 => 
            array (
                'id' => 10,
                'title' => 'EMEL MAKLUMAT TIDAK LENGKAP',
                'header' => '<p>#NAMAPENGADU#</p>

<p>#ALAMATPENGADU#</p>

<p>#DAERAHPENGADU#</p>

<p>#NEGERIPENGADU#</p>',
                'body' => '<p>Tuan/Puan,</p>

<p>&nbsp;</p>

<p>PENGESAHAN PENERIMAAN ADUAN (#NOADUAN#)</p>

<p>&nbsp;</p>

<p>Dengan segala hormatnya perkara di atas adalah dirujuk,</p>

<p>&nbsp;</p>

<p>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa maklumat aduan tidak lengkap. Mohon pihak tuan/puan mengemaskini aduan dalam masa 7 hari.</p>

<p>3.&nbsp; &nbsp; &nbsp; #JAWAPANKEPADAPENGADU</p>

<p>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sebarang pertanyaan atau semakan aduan tuan/puan bolehlah menghubungi talian Hotline KPDNKK 1-800-886-800 atau melayari Portal e-Aduan KPDNKK di <strong>https://e-aduan.kpdnkk.gov.my</strong> dengan memasukkan &nbsp;&nbsp;no. aduan tuan/puan.</p>

<p>5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</p>

<p>&nbsp;</p>

<p>Sekian, terima kasih.</p>',
                'footer' => NULL,
                'email_type' => '01',
                'email_cat' => NULL,
                'email_code' => '7',
                'status' => '1',
                'created_by' => '1783',
                'updated_by' => '1783',
                'created_at' => '2017-12-04 15:19:17',
                'updated_at' => '2017-12-04 15:19:47',
            ),
            10 => 
            array (
                'id' => 11,
                'title' => 'EMEL MOHON RUJUK KE AGENSI/KEMENTERIAN LAIN',
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

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sehubungan dengan itu, adalah dipohon kerjasama pihak tuan/puan untuk mengambil tindakan terhadap aduan ini berdasarkan tindakan dan prosedur di Agensi/Kementerian tuan/puan. Sebarang maklumbalas hendaklah dimaklumkan secara terus kepada pengadu dan disalinkan kepada Kementerian ini bagi tujuan rekod.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Sekian, terima kasih.</span></span></p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">&ldquo;BERKHIDMAT UNTUK NEGARA&rdquo;</span></strong></span></p>',
                'email_type' => '02',
                'email_cat' => NULL,
                'email_code' => '4',
                'status' => '1',
                'created_by' => '1783',
                'updated_by' => '1783',
                'created_at' => '2017-12-07 10:21:48',
                'updated_at' => '2017-12-07 16:43:40',
            ),
            11 => 
            array (
                'id' => 12,
                'title' => 'EMEL PERTANYAAN DITERIMA',
                'header' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt">#NAMAPENGADU#</span></p>

<div><span style="font-size:11pt">#ALAMATPENGADU#</span></div>

<div><span style="font-size:11pt">#DAERAHPENGADU#</span></div>

<div><span style="font-size:11pt">#NEGERIPENGADU#</span></div>',
                'body' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Tuan/Puan, </span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">PENGESAHAN PENERIMAAN PERTANYAAN(#NOADUAN#)</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Dengan segala hormatnya perkara di atas adalah dirujuk,</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adalah dimaklumkan bahawa pertanyaan tuan/puan telah diterima dan kini di dalam proses untuk diambil tindakan selanjutnya dalam masa 21 hari bekerja.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sebarang pertanyaan atau semakan pertanyaan tuan/puan bolehlah menghubungi talian Hotline KPDNKK 1-800-886-800 atau melayari Portal e-Aduan KPDNKK di <strong>https://e-aduan.kpdnkk.gov.my</strong> dengan memasukkan &nbsp;&nbsp;no. pertanyaan tuan/puan.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt">&nbsp;</span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pihak Kementerian mengucapkan ribuan terima kasih di atas kesudian pihak tuan/puan bekerjasama dalam meningkatkan mutu perkhidmatan kami.</span></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p><span style="font-size:13.0pt">Sekian, terima kasih.</span></p>',
                'footer' => '<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><strong><span style="font-size:13.0pt">&ldquo;BERKHIDMAT UNTUK NEGARA&rdquo;</span></strong></span></p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm; text-align:justify"><span style="font-size:11pt"><span style="font-size:13.0pt">Kementerian Perdagangan Dalam Negeri, Koperasi dan Kepenggunaan (KPDNKK)</span></span></p>

<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><span style="font-size:13.0pt">(Ini adalah surat cetakan komputer, tandatangan tidak diperlukan.)</span></span></p>',
                'email_type' => '01',
                'email_cat' => NULL,
                'email_code' => '6',
                'status' => '1',
                'created_by' => '1783',
                'updated_by' => '2',
                'created_at' => '2017-12-20 16:17:25',
                'updated_at' => '2017-12-20 16:51:21',
            ),
        ));
        
        
    }
}