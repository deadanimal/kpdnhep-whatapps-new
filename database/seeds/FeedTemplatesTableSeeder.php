<?php

use Illuminate\Database\Seeder;

class FeedTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_seeds = [
            [ 'category' => 'bot', 'title' => 'New Feedback', 'body' => 'Terima kasih kerana menghubungi KPDNHEP. Sila kemukakan butiran aduan seperti berikut :

a) Nama (Nama Penuh Mengikut K/P)
b) No.K/P (Sila Nyatakan No K/P Yang Sah)
c) Alamat surat menyurat
d) No telefon untuk dihubungi
e) Email (Sekiranya ada)
f) Nama Premis
g) Alamat Premis (Sila Nyatakan Alamat Lengkap Termasuk Daerah)
h) Keterangan Aduan
i) Gambar Bukti (Sekiranya Ada)
j) Sekiranya Aduan Berkenaan Transaksi Atas Talian Mohon Kemukakan Nama Bank Dan No Akaun Bank / No Transaksi FPX Yang Terlibat

Setelah selesai, sila taip TAMAT. 
Terima kasih.

*Hanya aduan melalui pesanan teks sahaja yang akan diproses.*
*Sekiranya Tuan/Puan gagal untuk melengkapkan maklumat yang diperlukan dalam tempoh 3 hari bekerja, pihak kami menganggap Tuan/Puan tidak berminat untuk meneruskan aduan ini dan seterusnya aduan ini akan DITUTUP*
'],
            [ 'category' => 'bot', 'title' => 'Ticket Created', 'body' => 'Terima kasih atas aduan yang dikemukakan. Aduan tuan/puan akan diproses dalam masa 1 hari bekerja.'],
            [ 'category' => 'agensi', 'title' => 'MyIPO - Perbadanan Harga Intelek Malaysia', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di bawah bidang kuasa 

*PERBADANAN HARTA INTELEK MALAYSIA*

http://www.myipo.gov.my/en/contact/?lang=en

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'MYCC - Suruhanjaya Persaingan Malaysia', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di bawah bidang kuasa 

*SURUHANJAYA PERSAINGAN MALAYSIA*

http://www.mycc.gov.my/contact-us

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'SSM - Suruhanjaya Syarikat Malaysia', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di bawah bidang kuasa 

*SURUHANJAYA SYARIKAT MALAYSIA*

http://www.ssm.com.my/Pages/contact-us.aspx

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'Kastam', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP.  
Untuk makluman, pelaksanaan SST adalah dibawah Jabatan Kastam Diraja Malaysia.

Bidang kuasa KPDNHEP adalah berkenaan kenaikan harga barangan atau perkhidmatan (Bukan berkaitan caj perkhidmatan SST).

Untuk maklumat lanjut mengenai isu SST, sila rujuk pihak *JABATAN KASTAM DIRAJA MALAYSIA (ADUAN BERKENAAN SST)* 
Jabatan Kastam Diraja Malaysia, 
Kompleks Kementerian Kewangan No 3,
Persiaran Perdana, Presint 2,
62596, Putrajaya 
03-8323 7499/7522 @ 03 8882 2289/2303/2492/2617

https://www.mysst.customs.gov.my/SSTOffice

https://www.mysst.customs.gov.my/RegisterBussiness#ServiceTax

http://aduan.customs.gov.my/aduanawam/index1.php

Sekian Terima Kasih
KPDNHEP 
'],
            [ 'category' => 'agensi', 'title' => 'SKMM - Suruhanjaya Komunikasi Dan Multimedia Malaysia', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*SURUHANJAYA KOMUNIKASI DAN MULTIMEDIA MALAYSIA*

https://aduan.skmm.gov.my/

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'CFM - Customer Forum Of Malaysia', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*CONSUMER FORUM OF MALAYSIA*

http://complaint.cfm.my/

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'BNM - Bank Negara Malaysia', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*BANK NEGARA MALAYSIA*

http://www.bnm.gov.my/index.php?ch=en_about&pg=en_about_office&ac=40

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'Imigresen', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*JABATAN IMIGRESEN MALAYSIA*

http://app.imi.gov.my/feedback/index.php?lang=1

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'JKM - Jabatan Kebajikan Masyarakat', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*JABATAN KEBAJIKAN MASYARAKAT*

http://www.jkm.gov.my/

Sila rujuk pautan tersebut di ruangan maklum balas untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'JAKIM - Jabatan Kemajuan Islam Malaysia', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*JABATAN KEMAJUAN ISLAM MALAYSIA*

http://www.islam.gov.my/maklumbalas

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'JKR - Jabatan Kerja Raya', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*JABATAN KERJA RAYA*

https://aduan.jkr.gov.my/

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'KSM - Kementerian Sumber Manusia', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*KEMENTERIAN SUMBER MANUSIA*

http://mohr.spab.gov.my/eApps/system/index.do

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'KKM (Kesihatan) - Kementerian Kesihatan Malaysia', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*KEMENTERIAN KESIHATAN MALAYSIA*

http://moh.spab.gov.my/eApps/system/index.do
 
Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'KPKT (Perumahan) - Kementerian Perumahan Dan Kerajaan Tempatan', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*KEMENTERIAN PERUMAHAN DAN KERAJAAN TEMPATAN*

https://aduan.kpkt.gov.my/aduan-online/entry/aduanperumahan.cfm

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'Mahkamah', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa *MAHKAMAH*

Sila rujuk pihak tersebut untuk perhatian selanjutnya.

Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'MOA (Pertanian) - Kementerian Pertanian Dan Industri Asas Tani', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*KEMENTERIAN PERTANIAN DAN INDUSTRI ASAS TANI*

http://www.moa.gov.my/hubungi-kami

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'MOT (Pengangkutan) / JPJ', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*KEMENTERIAN PENGANGKUTAN *

http://mot.spab.gov.my/eApps/system/index.do

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'MOTAC (Pelancongan)', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*KEMENTERIAN PELANCONGAN, SENI DAN BUDAYA MALAYSIA*

http://motac.spab.gov.my/eApps/system/index.do

Sila rujuk pihak tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'PDRM', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*POLIS DIRAJA MALAYSIA*

Sila rujuk pihak tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'Penerbangan / MAVCOM', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*SURUHANJAYA PENERBANGAN MALAYSIA*

https://www.mavcom.my/en/consumer/make-a-complaint/

Sila rujuk pihak tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'PBT', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*PIHAK BERKUASA TEMPATAN*

Sila rujuk pihak tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'TTPM', 'body' => 'Tuan/ Puan,

Berdasarkan semakan, aduan tuan/puan disarankan untuk memfailkan tuntutan tuan/puan kepada *TRIBUNAL TUNTUTAN PENGGUNA MALAYSIA (TTPM)* melalui saluran-saluran berikut:

a) Sistem e-Tribunal V2 di https://ttpm.kpdnkk.gov.my 
b) Hadir ke Pejabat TTPM yang berhampiran.
c) Talian Hotline TTPM 1-800-889-811

Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'sprm', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*SURUHANJAYA PENCEGAHAN RAHSUAH MALAYSIA*

http://www.sprm.gov.my/index.php/hubungi-kami/ibu-pejabat

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'Suruhanjaya Tenaga', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*SURUHANJAYA TENAGA*

https://aduan.st.gov.my/

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'TNB', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*TENAGA NASIONAL BERHAD*

https://www.tnb.com.my/contact-us/customer-care/

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'SIRIM', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*SIRIM*

http://www.sirim.my/index.php/contacts

Sila rujuk pihak tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'JPN', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*JABATAN PENDAFTARAN NEGARA*

http://jpn.spab.gov.my/eApps/system/index.do

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'KATS', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*KEMENTERIAN AIR, TANAH DAN SUMBER ASLI*

http://www.kats.gov.my/ms-my/hubungikami/Pages/default.aspx

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'PROTON', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*PROTON*

https://www.proton.com/en/shopping-tools/contact-us?selectedform=General-Enquiry

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'LPKP', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*LEMBAGA PERLESENAN KENDERAAN PERDAGANGAN*

Sila rujuk pihak tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'kdn', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*KEMENTERIAN DALAM NEGERI*

http://www.moha.gov.my/index.php/ms/maklumbalas-aduan

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'agensi', 'title' => 'KPM', 'body' => 'Tuan/ Puan,
Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP dan lebih menjurus kepada bidang kuasa 

*KEMENTERIAN PENDIDIKAN MALAYSIA*

https://www.moe.gov.my/index.php/my/khidmat-pelanggan-2

Sila rujuk pautan tersebut untuk perhatian selanjutnya.
Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'khas', 'title' => 'Maklumat Tak Lengkap', 'body' => 'Tuan/Puan

Terima kasih kerana menghubungi KPDNHEP. Sila lengkapkan butiran aduan seperti berikut :

a) Nama (Nama Penuh Mengikut K/P)
b) No.K/P (Sila Nyatakan No K/P Yang Sah)
c) Alamat surat menyurat
d) No telefon untuk dihubungi
e) Email (Sekiranya ada)
f) Nama Premis
g) Alamat Premis (Sila Nyatakan Alamat Lengkap Termasuk Daerah)
h) Keterangan Aduan
i) Gambar Bukti (Sekiranya Ada)
j) Sekiranya Aduan Berkenaan Transaksi Atas Talian Mohon Kemukakan Nama Bank Dan No Akaun Bank / No Transaksi FPX Yang Terlibat

Setelah selesai, sila taip TAMAT. 
Terima kasih.

*Hanya aduan melalui pesanan teks sahaja yang akan diproses.*
*Sekiranya Tuan/Puan gagal untuk melengkapkan maklumat yang diperlukan dalam tempoh 3 hari bekerja, pihak kami menganggap Tuan/Puan tidak berminat untuk meneruskan aduan ini dan seterusnya aduan ini akan DITUTUP*
'],
            [ 'category' => 'khas', 'title' => 'Senarai Harga', 'body' => 'ISU SENARAI HARGA

Tuan/ Puan,

Terima kasih kerana menghubungi KPDNHEP,
Bagi membuat semakan dan perbandingan barangan terpilih, tuan/puan bolehlah memuat turun aplikasi *Price catcher* di Google Play/App Store. Jadilah pengguna yang bijak dengan membuat pilihan yang tepat.


Sekian, terima kasih
'],
            [ 'category' => 'khas', 'title' => 'data peribadi', 'body' => 'Pihak Kementerian sangat menghargai keprihatinan Tuan/Puan untuk menegakkan *hak-hak pengguna* dan mengambil perhatian atas kesulitan yang berlaku.
Untuk maklumat Tuan/Puan, pada lampiran muka surat 15 (http://www.pdp.gov.my/index.php/en/akta-709/akta-perlindungan-data-peribadi-2010) , data Tuan/Puan adalah diamanahkan untuk dilindungi dan tertakluk di bawah akta tersebut.
Untuk makluman jua, maklumat lengkap diperlukan untuk menjaga integriti data dan memudahkan tindakan penguatkuasaan. Sehubungan itu, sila lengkap kan butiran berikut :
a) Nama (Nama Penuh Mengikut K/P)
b) No.K/P (Sila Nyatakan No K/P Yang Sah)
c) Alamat surat menyurat
d) No telefon untuk dihubungi
e) Email (Sekiranya ada)
f) Nama Premis
g) Alamat Premis (Sila Nyatakan Alamat Lengkap Termasuk Daerah)
h) Keterangan Aduan
i) Gambar Bukti (Sekiranya Ada)
j) Sekiranya Aduan Berkenaan Transaksi Atas Talian Mohon Kemukakan Nama Bank Dan No Akaun Bank / No Transaksi FPX Yang Terlibat
Diharapkan agar isu tersebut akan diambil tindakan yang tepat.

Terima kasih
KPDNHEP
'],
            [ 'category' => 'khas', 'title' => 'Status Aduan', 'body' => 'Tuan/Puan,

Aduan tersebut masih dalam siasatan.

Sebarang pertanyaan boleh dirujuk kepada Pegawai Penyiasat yang bertanggungjawab menjalankan siasatan ke atas aduan tuan.

Pegawai Penyiasat : xxx
No.Telefon : xxx
E-mel : xxx
'],
            [ 'category' => 'khas', 'title' => 'Tiada Tanda Harga', 'body' => 'Tuan/Puan

Adalah dimaklumkan bahawa kerajaan tiada menetapkan/menggariskan harga perkhidmatan/barangan selain daripada barang kawalan. Namun begitu, adalah menjadi satu kesalahan sekiranya pekedai tidak meletakkan tanda harga untuk barangan/perkhidmatan tersebut. 
Jadilah pengguna yang bijak dengan membuat pilihan yang tepat. 

Sekian terima kasih
KPDNHEP
'],
            [ 'category' => 'khas', 'title' => 'Nombor Aduan', 'body' => 'Aduan anda telah didaftarkan ke dalam Sistem e-Aduan KPDNHEP.
No. Aduan:  *01823252*
Semakan Aduan boleh dibuat melalui:
a) Portal eAd`uan https://eaduan.kpdnkk.gov.my 
b) Aplikasi Ez ADU KPDNKK (Android dan IOS)
c) Call Center : 1800 – 886 - 800
d) Emel ke e-aduan@kpdnkk.gov.my 
**Pendaftaran menggunakan Nama dan No. K/P diperlukan untuk semakan melalui Aplikasi Ez ADU.

Sekian, terima kasih
KPDNHEP
'],
            [ 'category' => 'khas', 'title' => 'Kedai SST', 'body' => 'Tuan/ Puan,

Berdasarkan semakan, didapati aduan/pertanyaan tuan/puan adalah di luar bidang kuasa KPDNHEP.  
Untuk makluman, pelaksanaan SST adalah dibawah Jabatan Kastam Diraja Malaysia.

Untuk sebarang semakan kedai yang berdaftar dengan SST, tuan/puan boleh melayari laman web MySST di pautan berikut:

https://sst01.customs.gov.my/account/inquiry

Untuk maklumat lanjut, sila rujuk pihak JABATAN KASTAM DIRAJA MALAYSIA

Jabatan Kastam Diraja Malaysia, 
Kompleks Kementerian Kewangan No 3,
Persiaran Perdana, Presint 2,
62596, Putrajaya 
03-8323 7499/7522 @ 03 8882 2289/2303/2492/2617

1 300 888 500

Sekian Terima Kasih
KPDNHEP
'],
        ];

        foreach ($data_seeds as $seed) {
            \App\Models\Feedback\FeedTemplate::create($seed);
        }
    }
}
