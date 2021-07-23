<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
     */

    'accepted'             => ':attribute mesti diterima pakai.',
    'active_url'           => ':attribute bukan URL yang sah.',
    'after'                => ':attribute mesti tarikh selepas :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => ':attribute hanya boleh mengandungi huruf.',
    'alpha_dash'           => ':attribute boleh mengandungi huruf, nombor, dan sengkang.',
    'alpha_num'            => ':attribute boleh mengandungi huruf dan nombor.',
    'array'                => ':attribute mesti jujukan.',
    'before'               => ':attribute mesti tarikh sebelum :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => ':attribute mesti mengandungi antara :min dan :max.',
        'file'    => ':attribute mesti mengandungi antara :min dan :max kilobait.',
        'string'  => ':attribute mesti mengandungi antara :min dan :max aksara.',
        'array'   => ':attribute mesti mengandungi antara :min dan :max perkara.',
    ],
    'boolean'              => ':attribute mesti benar atau palsu.',
    'confirmed'            => ':attribute pengesahan yang tidak sepadan.',
    'date'                 => ':attribute bukan tarikh yang sah.',
    'date_format'          => ':attribute tidak mengikut format :format.',
    'different'            => ':attribute dan :other mesti berlainan.',
    'dimensions'           => ':attribute tidak sah',
    'digits'               => ':attribute mesti :digits.',
    'digits_between'       => ':attribute mesti mengandungi antara :min dan :max digits.',
    'distinct'             => ':attribute adalah nilai yang berulang',
    'email'                => ':attribute tidak sah.',
    'exists'               => ':attribute tidak sah.',
    'file'                 => ':attribute mesti fail yang sah.',
    'filled'               => ':attribute diperlukan.',
    'image'                => ':attribute mesti imej.',
    'in'                   => ':attribute tidak sah.',
    'in_array'             => ':attribute tidak wujud dalam :other.',
    'integer'              => ':attribute mesti integer.',
    'ip'                   => ':attribute mesti alamat IP yang sah.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => ':attribute mesti JSON yang sah.',
    'max'                  => [
        'numeric' => 'Jumlah :attribute mesti tidak melebihi :max.',
        'file'    => 'Jumlah :attribute mesti tidak melebihi :max kilobait.',
        'string'  => 'Jumlah :attribute mesti tidak melebihi :max aksara.',
        'array'   => 'Jumlah :attribute mesti tidak melebihi :max perkara.',
    ],
    'mimes'                => ':attribute mesti fail type: :values.',
    'mimetypes'            => ':attribute mesti fail type: :values.',
    'min'                  => [
        'numeric' => 'Jumlah :attribute mesti sekurang-kurangnya :min.',
        'file'    => 'Jumlah :attribute mesti sekurang-kurangnya :min kilobait.',
        'string'  => 'Jumlah :attribute mesti sekurang-kurangnya :min aksara.',
        'array'   => 'Jumlah :attribute mesti sekurang-kurangnya :min perkara.',
    ],
    'not_in'               => ':attribute tidak sah.',
    'numeric'              => ':attribute mesti nombor.',
    'present'              => ':attribute mesti wujud.',
    'regex'                => ':attribute format tidak sah.',
    'required'             => 'Ruangan :attribute diperlukan.',
    'required_if'          => 'Ruangan :attribute diperlukan bila :other sama dengan :value.',
    'required_unless'      => 'Ruangan :attribute diperlukan sekiranya :other ada dalam :values.',
    'required_with'        => 'Ruangan :attribute diperlukan bila :values wujud.',
    'required_with_all'    => 'Ruangan :attribute diperlukan bila :values wujud.',
    'required_without'     => 'Ruangan :attribute diperlukan bila :values tidak wujud.',
    'required_without_all' => 'Ruangan :attribute diperlukan bila kesemua :values wujud.',
    'same'                 => 'Ruangan :attribute dan :other mesti sepadan.',
    'size'                 => [
        'numeric' => 'Saiz :attribute mesti :size.',
        'file'    => 'Saiz :attribute mesti :size kilobait.',
        'string'  => 'Saiz :attribute mesti :size aksara.',
        'array'   => 'Saiz :attribute mesti mengandungi :size perkara.',
    ],
    'string'               => ':attribute mesti aksara.',
    'timezone'             => ':attribute mesti zon masa yang sah.',
    'unique'               => ':attribute telah wujud.',
    'uploaded'             => ':attribute gagal dimuat naik.',
    'url'                  => ':attribute format tidak sah.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
     */

    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'captcha' => [
            'captcha' => 'Captcha tidak sah.',
        ],
        'CA_ONLINECMPL_AMOUNT' => [
            'max' => 'Jumlah Kerugian mesti tidak melebihi RM :max.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
     */

    'attributes'           => [
        'cat' => 'Kategori',
        'code' => 'Kod',
        'descr' => 'Penerangan',
        'sort' => 'Susunan',
        'title' => 'Tajuk',
        'letter_type' => 'Jenis Surat',
        'letter_code' => 'Kod Surat',
        'email_type' => 'Jenis Emel',
        'email_code' => 'Kod Emel',
        'start_dt' => 'Tarikh Mula',
        'end_dt' => 'Tarikh Tamat',
        'menu_id' => 'Menu',
        'title_my' => 'Tajuk (BM)',
        'title_en' => 'Tajuk (BI)',
        'content_my' => 'Isi Artikal (BM)',
        'content_en' => 'Isi Artikal (BI)',
        'BR_BRNCD' => 'Kod Cawangan',
        'BR_BRNNM' => 'Nama',
        'BR_STATECD' => 'Negeri',
        'BR_DISTCD' => 'Daerah',
        'BR_EMAIL' => 'Emel',
        'passwordOld' => 'Kata Laluan Lama',
        'passwordNew' => 'Kata Laluan Baru',
        'passwordRepeat' => 'Ulang Kata Laluan Baru',
        'doc_title' => 'Nama Fail',
        'usernameicno' => 'No. IC',
        'usernamepassport' => 'Pasport',
        'name' => 'Nama',
        'email' => 'Emel',
        'mobile_no' => 'No. Tel Bimbit',
        'gender' => 'Jantina',
        'age' => 'Umur',
        'address' => 'Alamat',
        'postcode' => 'Poskod',
        'state_cd' => 'Negeri',
        'distrinct_cd' => 'Daerah',
        'ctry_cd' => 'Negara',
        'password' => 'Kata Laluan',
        'password_confirmation' => 'Ulang Kata Laluan',
        'username' => 'No. Kad Pengenalan (Baru)',
        'g-recaptcha-response' => 'ReCaptcha',
        'CA_AGAINST_POSTCD' => 'Poskod',
        'holiday_name' => 'Nama Cuti',
        'holiday_date' => 'Tarikh Cuti',
        'repeat_yearly' => 'Berulang Setiap Tahun',
        'state_code' => 'Negeri',
        'CA_ONLINECMPL_AMOUNT' => 'Jumlah Kerugian (RM)',
        'pic' => 'Gambar profil',
        'IN_SUMMARY_TITLE' => 'Tajuk Aduan',
        'CA_ONLINECMPL_CASENO' => 'No. Aduan Rujukan',
        'CA_AGAINSTNM' => 'Nama (Syarikat/Premis/Penjual)',
        'CA_ONLINECMPL_URL' => 'Laman Web / URL / ID',
        'CA_ONLINECMPL_ACCNO' => 'No. Akaun Bank / No. Transaksi FPX',
        'NO_KES' => 'Nombor Kes',
        'KOD_NEGERI' => 'Negeri',
        'KOD_CAWANGAN' => 'Cawangan',
        'AKTA' => 'Akta',
        'ASAS_TINDAKAN' => 'Asas Tindakan',
        'SERAHAN_AGENSI' => 'Serahan Agensi',
        'TKH_KEJADIAN' => 'Tarikh Kejadian',
        'TKH_SERAHAN' => 'Tarikh Serahan',
        'PENGELASAN_KES' => 'Pengelasan Kes',
        'KATEGORI_KESALAHAN' => 'Kategori Kesalahan',
        'KESALAHAN' => 'Kesalahan',
        'PEGAWAI_SERBUAN_RO' => 'Pegawai Serbuan RO',
        'PEGAWAI_PENYIASAT_AIO' => 'Pegawai Penyiasat AIO',
        'PEGAWAI_PENYIASAT_IO' => 'Pegawai Penyiasat IO',
        'NO_REPORT_POLIS' => 'Nombor Repot Polis',
        'NO_SSM' => 'Nombor SSM',
        'NAMA_PREMIS_SYARIKAT' => 'Nama Premis Syarikat',
        'ALAMAT' => 'Alamat',
        'JENAMA_PREMIS' => 'Jenama Premis',
        'KAWASAN' => 'Kawasan',
        'JENIS_PERNIAGAAN' => 'Jenis Perniagaan',
        'KATEGORI_PREMIS' => 'Kategori Premis',
        'JENIS_PREMIS' => 'Jenis Premis',
        'STATUS_OKT' => 'Status OKT',
    ],
];
