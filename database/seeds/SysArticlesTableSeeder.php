<?php

use Illuminate\Database\Seeder;

class SysArticlesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sys_articles')->delete();
        
        \DB::table('sys_articles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'menu_type' => NULL,
                'menu_id' => 57,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'PENGENALAN',
                'title_en' => 'INTRODUCTION',
                'content_my' => '<p style="text-align:justify"><span style="font-size:12px"><strong>PENGENALAN</strong></span></p>

<p style="text-align:justify"><span style="font-size:12px">&nbsp;<strong>Sistem E-Aduan KPDNKK</strong> telah diwujudkan bagi <strong>menyimpan dan menguruskan semua aduan yang diterima melalui pelbagai sumber aduan</strong> di Kementerian ini. Sistem ini digunakan oleh orang awam untuk membuat aduan kepada KPDNKK manakala Bahagian Gerakan Kepenggunaan, Bahagian Pembangunan Perniagaan, Bahagian Perdagangan Dalam Negeri, Bahagian Penguatkuasa dan Cawangan KPDNKK di seluruh Negara menggunakan sistem ini bagi mengemaskini dan menguruskan aduan yang diterima.</span></p>

<p style="text-align:justify"><span style="font-size:12px"><strong>LATAR BELAKANG</strong></span></p>

<p style="text-align:justify"><span style="font-size:12px">&nbsp;Sistem Pengurusan Aduan KPDNKK yang dikenali sebagai sistem e-Aduan ini telah dibangunkan lebih 12 tahun lalu dan mula digunakan pada 14 Jun 2004. KPDNKK membangunkan sistem e-Aduan KPDNKK ini bagi membolehkan pengguna membuat aduan dengan menggunakan kemudahan internet tanpa perlu hadir ke Pejabat KPDNKK yang berdekatan.</span></p>

<p style="text-align:justify"><span style="font-size:12px">&nbsp;Namun ianya telah ditambahbaik pada tahun 2011 dan juga telah dibangunkan semula pada 2017 bagi mengaplikasi proses kerja baru bagi pengurusan aduan KPDNKK serta menangani isu-isu keselamatan ICT.</span></p>

<p style="text-align:justify"><span style="font-size:12px">&nbsp;Sistem ini juga diperluaskan dengan kewujudan aplikasi Ez ADU KPDNKK bagi pengguna membuat aduan melalui telefon pintar atau peranti mobile yang lain. Aplikasi telefon pintar Ez ADU KPDNKK diwujudkan sebagai kemudahan kepada pengguna membuat aduan kepada KPDNKK melalui telefon pintar atau peranti mobil yang lain dengan lebih mudah dan cepat.</span></p>',
                'content_en' => '<p style="text-align:justify"><span style="font-size:12px"><strong>INTRODUCTION</strong></span></p>

<p style="text-align:justify"><span style="font-size:12px">&nbsp;<strong>The KPDNKK E-Complaint System</strong> has been created to <strong>store and manage all complaints received through various sources of complaints</strong> in this Ministry. This system is used by the general public to lodge a complaint with KPDNKK while the consumer affair division, business development division, domestic trade division, enforcement division, and KPDNKK branches nationwide use this system to update and manage complaints received.</span></p>

<p style="text-align:justify"><span style="font-size:12px"><strong>BACKGROUND</strong></span></p>

<p style="text-align:justify"><span style="font-size:12px">&nbsp;The KPDNKK Complaint Management System known as the e-Aduan system was developed over 12 years ago and was first used on 14 June 2004. KPDNKK develops this KPDNKK e-Complaint system to enable users to submit complaints using internet facilities without having to attend the nearest branch office.</span></p>

<p style="text-align:justify"><span style="font-size:12px">&nbsp;However, this system has been upgrade in 2011 and rebuilt in 2017 to apply new work processes for KPDNKK complaints management in handling ICT security issues.</span></p>

<p style="text-align:justify"><span style="font-size:12px">&nbsp;This system is expanded with the existence of Ez Adu KPDNKK applications for user to make complaints via smartphones or other mobile devices. Application EZ ADU KPDNKK on smartphone, created as a convenience to the user to make a complaint to the KPDNKK via smartphones or other mobile devices more easily and quickly.</span></p>',
                'hits' => NULL,
                'cat' => NULL,
                'photo' => NULL,
                'link' => NULL,
                'status' => NULL,
                'created_by' => '1',
                'created_at' => '2017-06-15 09:00:00',
                'updated_at' => '2017-10-17 16:55:00',
                'updated_by' => '1',
            ),
            1 => 
            array (
                'id' => 2,
                'menu_type' => NULL,
                'menu_id' => 58,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'ADUAN KEPADA KPDNKK',
                'title_en' => 'COMPLAINTS TO KPDNKK',
                'content_my' => '<p><span style="font-size:12px">Pelbagai saluran telah disediakan bagi pengguna membuat aduan kepada KPDNKK:</span></p>

<ol>
<li>
<p><span style="font-size:12px">Membuat aduan secara online di <a href="https://eaduanv2.kpdnkk.gov.my" target="_blank">https://eaduanv2.kpdnkk.gov.my</a></span></p>
</li>
<li>
<p><span style="font-size:12px">Memuat turun Aplikasi Telefon Pintar Ez ADU KPDNKK di:</span></p>

<ol style="list-style-type:lower-roman">
<li>
<p><span style="font-size:12px">Google Play<br />
<a href="https://play.google.com/store/apps/details?id=com.kpdnkk.ezaduforandroid&amp;hl=en" target="_blank"><img src="../img/google-play-badge_bm.svg" style="height:40px; width:135px" /> </a></span></p>
</li>
<li>
<p><span style="font-size:12px">App Store<br />
<a href="https://itunes.apple.com/my/app/ez-adu-kpdnkk/id1012529241?mt=8" target="_blank"><img src="../img/app_store_badge_bm.svg" style="height:40px; width:135px" /> </a></span></p>
</li>
</ol>
</li>
<li>
<p><span style="font-size:12px">Talian Hotline Aduan 1-800-886-800<br />
Pengadu hendaklah menyediakan maklumat-maklumat berikut semasa membuat aduan secara talian hotline untuk kemudahan komunikasi bagi tujuan penyiasatan.</span></p>

<ol style="list-style-type:lower-roman">
<li><span style="font-size:12px">No Kad Pengenalan</span></li>
<li><span style="font-size:12px">Nama Penuh</span></li>
<li><span style="font-size:12px">No. Telefon</span></li>
<li><span style="font-size:12px">Email Pengadu (jika ada)</span></li>
<li><span style="font-size:12px">Nama Premis Pengadu</span></li>
<li><span style="font-size:12px">Alamat Premis</span></li>
<li><span style="font-size:12px">Keterangan Aduan</span></li>
<li><span style="font-size:12px">Dan beberapa maklumat yang diperlukan mengikut kategori aduan yang dikemukakan.</span></li>
</ol>
</li>
<li>
<p><span style="font-size:12px">Hadir sendiri ke mana-mana pejabat / cawangan KPDNKK yang berhampiran</span></p>
</li>
</ol>',
                'content_en' => '<p><span style="font-size:12px">Various channels have been made available to consumers to lodge complaints with KPDNKK:</span></p>

<ol>
<li>
<p><span style="font-size:12px">Make a complaint online at <a href="https://eaduanv2.kpdnkk.gov.my" target="_blank">https://eaduanv2.kpdnkk.gov.my</a></span></p>
</li>
<li>
<p><span style="font-size:12px">Get Smartphone Application Ez ADU KPDNKK In:</span></p>

<ol style="list-style-type:lower-roman">
<li>
<p><span style="font-size:12px">Google Play<br />
<a href="https://play.google.com/store/apps/details?id=com.kpdnkk.ezaduforandroid&amp;hl=en" target="_blank"><img src="../img/google-play-badge.png" style="height:40px; width:135px" /> </a></span></p>
</li>
<li>
<p><span style="font-size:12px">App Store<br />
<a href="https://itunes.apple.com/my/app/ez-adu-kpdnkk/id1012529241?mt=8" target="_blank"><img src="../img/apple-store-badge.svg" style="height:40px; width:135px" /> </a></span></p>
</li>
</ol>
</li>
<li>
<p><span style="font-size:12px">Complaint Hotline Number 1-800-886-800<br />
The complainant should provide the following information when making a hotline complaint for communication convenience for investigation purposes.</span></p>

<ol style="list-style-type:lower-roman">
<li><span style="font-size:12px">IC Number</span></li>
<li><span style="font-size:12px">Full Name</span></li>
<li><span style="font-size:12px">Phone Number</span></li>
<li><span style="font-size:12px">Email Address (If Available)</span></li>
<li><span style="font-size:12px">Premise Name</span></li>
<li><span style="font-size:12px">Premise Address</span></li>
<li><span style="font-size:12px">Complaint Description</span></li>
<li><span style="font-size:12px">Some of the information required by the category of submitted complaints.</span></li>
</ol>
</li>
<li>
<p><span style="font-size:12px">Attend to the nearest KPDNKK office / branch</span></p>
</li>
</ol>',
                'hits' => NULL,
                'cat' => NULL,
                'photo' => NULL,
                'link' => NULL,
                'status' => NULL,
                'created_by' => '1',
                'created_at' => '2017-10-03 10:00:00',
                'updated_at' => '2017-10-09 21:14:00',
                'updated_by' => '1',
            ),
            2 => 
            array (
                'id' => 3,
                'menu_type' => NULL,
                'menu_id' => 59,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'APLIKASI MUDAH ALIH',
                'title_en' => 'MOBILE APPLICATIONS',
                'content_my' => '<p><span style="font-size:12px">Pengguna boleh memuat turun Aplikasi Telefon Pintar Ez ADU KPDNKK di:</span></p>

<ol>
<li>
<p><span style="font-size:12px">Google Play<br />
<a href="https://play.google.com/store/apps/details?id=com.kpdnkk.ezaduforandroid&amp;hl=en" target="_blank"><img src="../img/google-play-badge_bm.svg" style="height:40px; width:135px" /> </a></span></p>
</li>
<li>
<p><span style="font-size:12px">App Store<br />
<a href="https://itunes.apple.com/my/app/ez-adu-kpdnkk/id1012529241?mt=8" target="_blank"><img src="../img/app_store_badge_bm.svg" style="height:40px; width:135px" /> </a></span></p>
</li>
</ol>',
                'content_en' => '<p><span style="font-size:12px">Get Smartphone Application Ez ADU KPDNKK In:</span></p>

<ol>
<li>
<p><span style="font-size:12px">Google Play<br />
<a href="https://play.google.com/store/apps/details?id=com.kpdnkk.ezaduforandroid&amp;hl=en" target="_blank"><img src="../img/google-play-badge.png" style="height:40px; width:135px" /> </a></span></p>
</li>
<li>
<p><span style="font-size:12px">App Store<br />
<a href="https://itunes.apple.com/my/app/ez-adu-kpdnkk/id1012529241?mt=8" target="_blank"><img src="../img/apple-store-badge.svg" style="height:40px; width:135px" /> </a></span></p>
</li>
</ol>',
                'hits' => NULL,
                'cat' => NULL,
                'photo' => NULL,
                'link' => NULL,
                'status' => NULL,
                'created_by' => '1',
                'created_at' => '2017-10-03 10:30:00',
                'updated_at' => '2017-10-17 17:00:00',
                'updated_by' => '1',
            ),
            3 => 
            array (
                'id' => 4,
                'menu_type' => NULL,
                'menu_id' => 60,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'PAUTAN AGENSI',
                'title_en' => 'AGENCY LINK',
                'content_my' => '<p><span style="font-size:12px">Pautan Agensi</span></p>',
                'content_en' => '<p><span style="font-size:12px">Agency Link</span></p>',
                'hits' => NULL,
                'cat' => NULL,
                'photo' => NULL,
                'link' => NULL,
                'status' => NULL,
                'created_by' => '1',
                'created_at' => '2017-10-03 11:00:00',
                'updated_at' => '2017-10-17 17:05:00',
                'updated_by' => '1',
            ),
            4 => 
            array (
                'id' => 5,
                'menu_type' => NULL,
                'menu_id' => 70,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'FAQ',
                'title_en' => 'FAQ',
                'content_my' => '<p><span style="font-size:12px">Soalan yang sering ditanya</span></p>',
            'content_en' => '<p><span style="font-size:12px">Frequently asked questions (FAQ)</span></p>',
                'hits' => NULL,
                'cat' => NULL,
                'photo' => NULL,
                'link' => NULL,
                'status' => NULL,
                'created_by' => '1',
                'created_at' => '2017-10-03 11:30:00',
                'updated_at' => '2017-10-17 17:10:00',
                'updated_by' => '1',
            ),
            5 => 
            array (
                'id' => 6,
                'menu_type' => NULL,
                'menu_id' => 82,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'HUBUNGI KAMI',
                'title_en' => 'CONTACT US',
                'content_my' => '<p><span style="font-size:12px">Hubungi kami</span></p>',
                'content_en' => '<p><span style="font-size:12px">Contact us</span></p>',
                'hits' => NULL,
                'cat' => NULL,
                'photo' => NULL,
                'link' => NULL,
                'status' => NULL,
                'created_by' => '1',
                'created_at' => '2017-10-17 17:15:00',
                'updated_at' => '2017-10-17 17:15:00',
                'updated_by' => '1',
            ),
            6 => 
            array (
                'id' => 7,
                'menu_type' => NULL,
                'menu_id' => 58,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Cara Membuat Aduan',
                'title_en' => 'How To Make A Complaint?',
                'content_my' => 'Jadilah Pengguna Bijak Dengan Membuat dan Menyemak Aduan Berkaitan Perdagangan Dalam Negeri, Koperasi Dan Kepenggunaan.',
                'content_en' => 'Be a Smart Consumer By Making and Examining Complaints on Domestic Trade, Cooperatives and Consumerism.',
                'hits' => NULL,
                'cat' => '2',
                'photo' => NULL,
                'link' => 'portal',
                'status' => '1',
                'created_by' => '1',
                'created_at' => '2017-10-19 20:00:00',
                'updated_at' => '2017-10-19 20:05:00',
                'updated_by' => '1',
            ),
            7 => 
            array (
                'id' => 8,
                'menu_type' => NULL,
                'menu_id' => 59,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Aplikasi Mudah Alih KPDNKK',
                'title_en' => 'KPDNKK Mobile Applications',
                'content_my' => 'Semak dan Hantar Aduan Anda Melalui Aplikasi EzADU KPDNKK Di Peranti Pintar Anda. Muat turun sekarang.',
                'content_en' => 'Check and Submit Your Complaints Through EzADU KPDNKK Mobile Application On Your Smart Device. Download now.',
                'hits' => NULL,
                'cat' => '2',
                'photo' => NULL,
                'link' => 'portal',
                'status' => '1',
                'created_by' => '1',
                'created_at' => '2017-10-19 20:10:00',
                'updated_at' => '2017-10-19 20:15:00',
                'updated_by' => '1',
            ),
            8 => 
            array (
                'id' => 9,
                'menu_type' => NULL,
                'menu_id' => 70,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'FAQ',
                'title_en' => 'FAQ',
                'content_my' => 'Soalan yang sering ditanya',
                'content_en' => 'List of frequently ask questions',
                'hits' => NULL,
                'cat' => '2',
                'photo' => NULL,
                'link' => 'portal',
                'status' => '0',
                'created_by' => '1',
                'created_at' => '2017-10-19 20:20:00',
                'updated_at' => '2017-12-18 23:48:24',
                'updated_by' => '1',
            ),
            9 => 
            array (
                'id' => 10,
                'menu_type' => NULL,
                'menu_id' => NULL,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Maklumat',
                'title_en' => 'Information',
                'content_my' => 'Jadilah Pengguna Bijak Dengan Membuat dan Menyemak Aduan Berkaitan Perdagangan Dalam Negeri, Koperasi Dan Kepenggunaan.',
                'content_en' => 'Be a Smart Consumer By Making and Examining Complaints on Domestic Trade, Cooperatives and Consumerism.',
                'hits' => NULL,
                'cat' => NULL,
                'photo' => NULL,
                'link' => NULL,
                'status' => NULL,
                'created_by' => '1',
                'created_at' => '2017-10-21 23:55:00',
                'updated_at' => '2017-10-21 23:55:00',
                'updated_by' => '1',
            ),
            10 => 
            array (
                'id' => 11,
                'menu_type' => NULL,
                'menu_id' => NULL,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'SISTEM EADUAN KPDNKK',
                'title_en' => 'SISTEM EADUAN KPDNKK',
                'content_my' => 'Selamat Datang ke sistem Eaduan KPDNKK yang telah dibangunkan khusus bagi membolehkan pengguna membuat aduan terus ke KPDNKK tentang perkara-perkara berkaitan perdagangan dalam negeri, koperasi dan kepenggunaan.',
                'content_en' => 'Selamat Datang ke sistem Eaduan KPDNKK yang telah dibangunkan khusus bagi membolehkan pengguna membuat aduan terus ke KPDNKK tentang perkara-perkara berkaitan perdagangan dalam negeri, koperasi dan kepenggunaan.',
                'hits' => NULL,
                'cat' => '3',
                'photo' => NULL,
                'link' => NULL,
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => '2017-12-18 19:01:34',
                'updated_by' => '1',
            ),
            11 => 
            array (
                'id' => 12,
                'menu_type' => NULL,
                'menu_id' => 58,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Portal Eaduan',
                'title_en' => 'Portal Eaduan',
                'content_my' => 'Cara-Cara Membuat Aduan

Portal Eaduan ini telah dibangunkan bagi memudahkan pengguna untuk membuat dan menyemak aduan dengan mudah dan cepat.',
                'content_en' => 'Cara-Cara Membuat Aduan

Portal Eaduan ini telah dibangunkan bagi memudahkan pengguna untuk membuat dan menyemak aduan dengan mudah dan cepat.',
                'hits' => NULL,
                'cat' => '4',
                'photo' => NULL,
                'link' => 'portal',
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => '2017-12-18 19:50:46',
                'updated_by' => '1',
            ),
            12 => 
            array (
                'id' => 13,
                'menu_type' => NULL,
                'menu_id' => 59,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Aplikasi EzADU',
                'title_en' => 'Aplikasi EzADU',
                'content_my' => 'Untuk Peranti Pintar Android & iOS

Anda juga boleh menghantar aduan anda melalui aplikasi peranti mudah alih pintar EzADU.  Muat turun sekarang dari Google Play dan Apple AppStore',
                'content_en' => 'Untuk Peranti Pintar Android & iOS

Anda juga boleh menghantar aduan anda melalui aplikasi peranti mudah alih pintar EzADU.  Muat turun sekarang dari Google Play dan Apple AppStore',
                'hits' => NULL,
                'cat' => '4',
                'photo' => NULL,
                'link' => 'portal',
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => '2017-12-18 19:50:41',
                'updated_by' => '1',
            ),
            13 => 
            array (
                'id' => 14,
                'menu_type' => NULL,
                'menu_id' => 70,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Soalan Lazim',
                'title_en' => 'Soalan Lazim',
                'content_my' => 'Gedung Ilmu Sistem Eaduan 

Membuat aduan mengenai aktiviti perdagangan dalam negeri dan kepenggunaan adalah hak dan tanggungjawab anda sebagai pengguna yang bijak.',
                'content_en' => 'Gedung Ilmu Sistem Eaduan 

Membuat aduan mengenai aktiviti perdagangan dalam negeri dan kepenggunaan adalah hak dan tanggungjawab anda sebagai pengguna yang bijak.',
                'hits' => NULL,
                'cat' => '4',
                'photo' => NULL,
                'link' => 'portal',
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => '2017-12-18 19:50:33',
                'updated_by' => '1',
            ),
            14 => 
            array (
                'id' => 15,
                'menu_type' => NULL,
                'menu_id' => 60,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Organisasi',
                'title_en' => 'Organisasi',
                'content_my' => 'Maklumat Mengenai KPDNKK

KPDNKK bertanggungjawab dalam perlaksanaan dasar dan penguatkuasaan undang-undang berkaitan dengan perdagangan dalam negeri dan kepenggunaan',
                'content_en' => 'Maklumat Mengenai KPDNKK

KPDNKK bertanggungjawab dalam perlaksanaan dasar dan penguatkuasaan undang-undang berkaitan dengan perdagangan dalam negeri dan kepenggunaan',
                'hits' => NULL,
                'cat' => '4',
                'photo' => NULL,
                'link' => 'portal',
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => '2017-12-18 19:50:19',
                'updated_by' => '1',
            ),
            15 => 
            array (
                'id' => 16,
                'menu_type' => NULL,
                'menu_id' => NULL,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Ikuti Kami Di Media Sosial',
                'title_en' => 'Follow Us On Social Media',
                'content_my' => '~facebook#http://www.facebook.com
~twitter#http://www.twitter.com
~googleplus#http://www.google.com',
                'content_en' => '~facebook#http://www.facebook.com
~twitter#http://www.twitter.com
~googleplus#http://www.google.com',
                'hits' => NULL,
                'cat' => '5',
                'photo' => NULL,
                'link' => NULL,
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => '2017-12-18 23:13:57',
                'updated_by' => '1',
            ),
            16 => 
            array (
                'id' => 17,
                'menu_type' => NULL,
                'menu_id' => NULL,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Hubungi Kami',
                'title_en' => 'Hubungi Kami',
                'content_my' => '~Hotline :#
~1 - 800 - 886 - 800#
~+603 - 8882 6088#
~+603 - 8882 6245#',
                'content_en' => '~Hotline :#
~1 - 800 - 886 - 800#
~+603 - 8882 6088#
~+603 - 8882 6245#',
                'hits' => NULL,
                'cat' => '6',
                'photo' => NULL,
                'link' => NULL,
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => '2017-12-18 23:41:29',
                'updated_by' => '1',
            ),
            17 => 
            array (
                'id' => 18,
                'menu_type' => NULL,
                'menu_id' => NULL,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Maklumat',
                'title_en' => 'Maklumat',
                'content_my' => '~Hakcipta#http://www.google.com
~Penafian#http://www.google.com
~Dasar Privasi#http://www.google.com
~Dasar Keselamatan#http://www.google.com',
                'content_en' => '~Hakcipta#http://www.google.com
~Penafian#http://www.google.com
~Dasar Privasi#http://www.google.com
~Dasar Keselamatan#http://www.google.com',
                'hits' => NULL,
                'cat' => '6',
                'photo' => NULL,
                'link' => NULL,
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => '2017-12-18 23:42:55',
                'updated_by' => '1',
            ),
            18 => 
            array (
                'id' => 19,
                'menu_type' => NULL,
                'menu_id' => NULL,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Pautan',
                'title_en' => 'Pautan',
                'content_my' => '~Portal Rasmi KPDNKK#http://www.google.com
~Aplikasi EzADU di Google Play#http://www.google.com
~Aplikasi EzADU di Apple Appstore#http://www.google.com',
                'content_en' => '~Portal Rasmi KPDNKK#http://www.google.com
~Aplikasi EzADU di Google Play#http://www.google.com
~Aplikasi EzADU di Apple Appstore#http://www.google.com',
                'hits' => NULL,
                'cat' => '6',
                'photo' => NULL,
                'link' => NULL,
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => '2017-12-18 23:44:09',
                'updated_by' => '1',
            ),
            19 => 
            array (
                'id' => 20,
                'menu_type' => NULL,
                'menu_id' => NULL,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Title Info',
                'title_en' => 'Title Info',
                'content_my' => 'Content Info',
                'content_en' => 'Testing Announcement',
                'hits' => 'info',
                'cat' => '7',
                'photo' => NULL,
                'link' => NULL,
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'updated_by' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'menu_type' => NULL,
                'menu_id' => NULL,
                'start_dt' => '2017-01-01',
                'end_dt' => '2017-12-31',
                'title_my' => 'Title Danger',
                'title_en' => 'Title Danger',
                'content_my' => 'Content Danger',
                'content_en' => '<b>DANGER</b>',
                'hits' => 'danger',
                'cat' => '7',
                'photo' => NULL,
                'link' => NULL,
                'status' => '1',
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'updated_by' => NULL,
            ),
        ));
        
        
    }
}