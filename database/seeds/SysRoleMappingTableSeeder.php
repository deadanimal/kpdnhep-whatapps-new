<?php

use Illuminate\Database\Seeder;

class SysRoleMappingTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sys_role_mapping')->delete();
        
        \DB::table('sys_role_mapping')->insert(array (
            0 => 
            array (
                'role_code' => '800',
                'menu_id' => 1,
            ),
            1 => 
            array (
                'role_code' => '800',
                'menu_id' => 20,
            ),
            2 => 
            array (
                'role_code' => '800',
                'menu_id' => 19,
            ),
            3 => 
            array (
                'role_code' => '800',
                'menu_id' => 21,
            ),
            4 => 
            array (
                'role_code' => '800',
                'menu_id' => 69,
            ),
            5 => 
            array (
                'role_code' => '800',
                'menu_id' => 74,
            ),
            6 => 
            array (
                'role_code' => '800',
                'menu_id' => 28,
            ),
            7 => 
            array (
                'role_code' => '800',
                'menu_id' => 29,
            ),
            8 => 
            array (
                'role_code' => '800',
                'menu_id' => 30,
            ),
            9 => 
            array (
                'role_code' => '800',
                'menu_id' => 31,
            ),
            10 => 
            array (
                'role_code' => '800',
                'menu_id' => 32,
            ),
            11 => 
            array (
                'role_code' => '100',
                'menu_id' => 74,
            ),
            12 => 
            array (
                'role_code' => '100',
                'menu_id' => 28,
            ),
            13 => 
            array (
                'role_code' => '100',
                'menu_id' => 29,
            ),
            14 => 
            array (
                'role_code' => '100',
                'menu_id' => 30,
            ),
            15 => 
            array (
                'role_code' => '100',
                'menu_id' => 31,
            ),
            16 => 
            array (
                'role_code' => '100',
                'menu_id' => 32,
            ),
            17 => 
            array (
                'role_code' => '800',
                'menu_id' => 67,
            ),
            18 => 
            array (
                'role_code' => '800',
                'menu_id' => 66,
            ),
            19 => 
            array (
                'role_code' => '800',
                'menu_id' => 25,
            ),
            20 => 
            array (
                'role_code' => '100',
                'menu_id' => 69,
            ),
            21 => 
            array (
                'role_code' => '110',
                'menu_id' => 69,
            ),
            22 => 
            array (
                'role_code' => '110',
                'menu_id' => 74,
            ),
            23 => 
            array (
                'role_code' => '110',
                'menu_id' => 28,
            ),
            24 => 
            array (
                'role_code' => '110',
                'menu_id' => 29,
            ),
            25 => 
            array (
                'role_code' => '110',
                'menu_id' => 30,
            ),
            26 => 
            array (
                'role_code' => '110',
                'menu_id' => 31,
            ),
            27 => 
            array (
                'role_code' => '110',
                'menu_id' => 32,
            ),
            28 => 
            array (
                'role_code' => '140',
                'menu_id' => 74,
            ),
            29 => 
            array (
                'role_code' => '140',
                'menu_id' => 69,
            ),
            30 => 
            array (
                'role_code' => '100',
                'menu_id' => 20,
            ),
            31 => 
            array (
                'role_code' => '110',
                'menu_id' => 20,
            ),
            32 => 
            array (
                'role_code' => '140',
                'menu_id' => 20,
            ),
            33 => 
            array (
                'role_code' => '140',
                'menu_id' => 28,
            ),
            34 => 
            array (
                'role_code' => '140',
                'menu_id' => 29,
            ),
            35 => 
            array (
                'role_code' => '140',
                'menu_id' => 30,
            ),
            36 => 
            array (
                'role_code' => '140',
                'menu_id' => 31,
            ),
            37 => 
            array (
                'role_code' => '140',
                'menu_id' => 32,
            ),
            38 => 
            array (
                'role_code' => '120',
                'menu_id' => 83,
            ),
            39 => 
            array (
                'role_code' => '120',
                'menu_id' => 28,
            ),
            40 => 
            array (
                'role_code' => '120',
                'menu_id' => 29,
            ),
            41 => 
            array (
                'role_code' => '120',
                'menu_id' => 30,
            ),
            42 => 
            array (
                'role_code' => '120',
                'menu_id' => 31,
            ),
            43 => 
            array (
                'role_code' => '120',
                'menu_id' => 32,
            ),
            44 => 
            array (
                'role_code' => '120',
                'menu_id' => 69,
            ),
            45 => 
            array (
                'role_code' => '200',
                'menu_id' => 69,
            ),
            46 => 
            array (
                'role_code' => '200',
                'menu_id' => 20,
            ),
            47 => 
            array (
                'role_code' => '200',
                'menu_id' => 74,
            ),
            48 => 
            array (
                'role_code' => '200',
                'menu_id' => 28,
            ),
            49 => 
            array (
                'role_code' => '200',
                'menu_id' => 29,
            ),
            50 => 
            array (
                'role_code' => '200',
                'menu_id' => 30,
            ),
            51 => 
            array (
                'role_code' => '200',
                'menu_id' => 31,
            ),
            52 => 
            array (
                'role_code' => '200',
                'menu_id' => 32,
            ),
            53 => 
            array (
                'role_code' => '210',
                'menu_id' => 69,
            ),
            54 => 
            array (
                'role_code' => '210',
                'menu_id' => 20,
            ),
            55 => 
            array (
                'role_code' => '210',
                'menu_id' => 74,
            ),
            56 => 
            array (
                'role_code' => '210',
                'menu_id' => 28,
            ),
            57 => 
            array (
                'role_code' => '210',
                'menu_id' => 29,
            ),
            58 => 
            array (
                'role_code' => '210',
                'menu_id' => 30,
            ),
            59 => 
            array (
                'role_code' => '210',
                'menu_id' => 31,
            ),
            60 => 
            array (
                'role_code' => '210',
                'menu_id' => 32,
            ),
            61 => 
            array (
                'role_code' => '220',
                'menu_id' => 69,
            ),
            62 => 
            array (
                'role_code' => '220',
                'menu_id' => 20,
            ),
            63 => 
            array (
                'role_code' => '220',
                'menu_id' => 74,
            ),
            64 => 
            array (
                'role_code' => '220',
                'menu_id' => 91,
            ),
            65 => 
            array (
                'role_code' => '220',
                'menu_id' => 28,
            ),
            66 => 
            array (
                'role_code' => '220',
                'menu_id' => 29,
            ),
            67 => 
            array (
                'role_code' => '220',
                'menu_id' => 30,
            ),
            68 => 
            array (
                'role_code' => '220',
                'menu_id' => 31,
            ),
            69 => 
            array (
                'role_code' => '220',
                'menu_id' => 32,
            ),
            70 => 
            array (
                'role_code' => '230',
                'menu_id' => 69,
            ),
            71 => 
            array (
                'role_code' => '230',
                'menu_id' => 20,
            ),
            72 => 
            array (
                'role_code' => '230',
                'menu_id' => 74,
            ),
            73 => 
            array (
                'role_code' => '230',
                'menu_id' => 28,
            ),
            74 => 
            array (
                'role_code' => '230',
                'menu_id' => 29,
            ),
            75 => 
            array (
                'role_code' => '230',
                'menu_id' => 30,
            ),
            76 => 
            array (
                'role_code' => '230',
                'menu_id' => 31,
            ),
            77 => 
            array (
                'role_code' => '230',
                'menu_id' => 32,
            ),
            78 => 
            array (
                'role_code' => '240',
                'menu_id' => 69,
            ),
            79 => 
            array (
                'role_code' => '240',
                'menu_id' => 20,
            ),
            80 => 
            array (
                'role_code' => '240',
                'menu_id' => 74,
            ),
            81 => 
            array (
                'role_code' => '240',
                'menu_id' => 28,
            ),
            82 => 
            array (
                'role_code' => '240',
                'menu_id' => 29,
            ),
            83 => 
            array (
                'role_code' => '240',
                'menu_id' => 30,
            ),
            84 => 
            array (
                'role_code' => '240',
                'menu_id' => 31,
            ),
            85 => 
            array (
                'role_code' => '240',
                'menu_id' => 32,
            ),
            86 => 
            array (
                'role_code' => '250',
                'menu_id' => 69,
            ),
            87 => 
            array (
                'role_code' => '250',
                'menu_id' => 20,
            ),
            88 => 
            array (
                'role_code' => '250',
                'menu_id' => 74,
            ),
            89 => 
            array (
                'role_code' => '310',
                'menu_id' => 69,
            ),
            90 => 
            array (
                'role_code' => '310',
                'menu_id' => 20,
            ),
            91 => 
            array (
                'role_code' => '310',
                'menu_id' => 74,
            ),
            92 => 
            array (
                'role_code' => '310',
                'menu_id' => 28,
            ),
            93 => 
            array (
                'role_code' => '310',
                'menu_id' => 29,
            ),
            94 => 
            array (
                'role_code' => '310',
                'menu_id' => 30,
            ),
            95 => 
            array (
                'role_code' => '310',
                'menu_id' => 31,
            ),
            96 => 
            array (
                'role_code' => '310',
                'menu_id' => 32,
            ),
            97 => 
            array (
                'role_code' => '320',
                'menu_id' => 95,
            ),
            98 => 
            array (
                'role_code' => '320',
                'menu_id' => 69,
            ),
            99 => 
            array (
                'role_code' => '320',
                'menu_id' => 20,
            ),
            100 => 
            array (
                'role_code' => '320',
                'menu_id' => 74,
            ),
            101 => 
            array (
                'role_code' => '320',
                'menu_id' => 28,
            ),
            102 => 
            array (
                'role_code' => '320',
                'menu_id' => 29,
            ),
            103 => 
            array (
                'role_code' => '320',
                'menu_id' => 30,
            ),
            104 => 
            array (
                'role_code' => '320',
                'menu_id' => 31,
            ),
            105 => 
            array (
                'role_code' => '320',
                'menu_id' => 32,
            ),
            106 => 
            array (
                'role_code' => '350',
                'menu_id' => 69,
            ),
            107 => 
            array (
                'role_code' => '350',
                'menu_id' => 20,
            ),
            108 => 
            array (
                'role_code' => '350',
                'menu_id' => 74,
            ),
            109 => 
            array (
                'role_code' => '700',
                'menu_id' => 69,
            ),
            110 => 
            array (
                'role_code' => '700',
                'menu_id' => 19,
            ),
            111 => 
            array (
                'role_code' => '140',
                'menu_id' => 101,
            ),
            112 => 
            array (
                'role_code' => '110',
                'menu_id' => 66,
            ),
            113 => 
            array (
                'role_code' => '700',
                'menu_id' => 66,
            ),
            114 => 
            array (
                'role_code' => '110',
                'menu_id' => 111,
            ),
            115 => 
            array (
                'role_code' => '800',
                'menu_id' => 101,
            ),
            116 => 
            array (
                'role_code' => '800',
                'menu_id' => 102,
            ),
            117 => 
            array (
                'role_code' => '160',
                'menu_id' => 102,
            ),
            118 => 
            array (
                'role_code' => '160',
                'menu_id' => 69,
            ),
            119 => 
            array (
                'role_code' => '160',
                'menu_id' => 21,
            ),
            120 => 
            array (
                'role_code' => '700',
                'menu_id' => 101,
            ),
            121 => 
            array (
                'role_code' => '160',
                'menu_id' => 20,
            ),
            122 => 
            array (
                'role_code' => '160',
                'menu_id' => 74,
            ),
            123 => 
            array (
                'role_code' => '160',
                'menu_id' => 28,
            ),
            124 => 
            array (
                'role_code' => '160',
                'menu_id' => 29,
            ),
            125 => 
            array (
                'role_code' => '160',
                'menu_id' => 30,
            ),
            126 => 
            array (
                'role_code' => '160',
                'menu_id' => 31,
            ),
            127 => 
            array (
                'role_code' => '160',
                'menu_id' => 32,
            ),
            128 => 
            array (
                'role_code' => '160',
                'menu_id' => 83,
            ),
            129 => 
            array (
                'role_code' => '700',
                'menu_id' => 109,
            ),
            130 => 
            array (
                'role_code' => '700',
                'menu_id' => 20,
            ),
            131 => 
            array (
                'role_code' => '800',
                'menu_id' => 111,
            ),
            132 => 
            array (
                'role_code' => '800',
                'menu_id' => 117,
            ),
            133 => 
            array (
                'role_code' => '800',
                'menu_id' => 121,
            ),
            134 => 
            array (
                'role_code' => '110',
                'menu_id' => 101,
            ),
            135 => 
            array (
                'role_code' => '150',
                'menu_id' => 69,
            ),
            136 => 
            array (
                'role_code' => '150',
                'menu_id' => 20,
            ),
            137 => 
            array (
                'role_code' => '150',
                'menu_id' => 74,
            ),
            138 => 
            array (
                'role_code' => '150',
                'menu_id' => 28,
            ),
            139 => 
            array (
                'role_code' => '150',
                'menu_id' => 29,
            ),
            140 => 
            array (
                'role_code' => '150',
                'menu_id' => 30,
            ),
            141 => 
            array (
                'role_code' => '150',
                'menu_id' => 31,
            ),
            142 => 
            array (
                'role_code' => '150',
                'menu_id' => 32,
            ),
            143 => 
            array (
                'role_code' => '150',
                'menu_id' => 102,
            ),
            144 => 
            array (
                'role_code' => '250',
                'menu_id' => 28,
            ),
            145 => 
            array (
                'role_code' => '250',
                'menu_id' => 29,
            ),
            146 => 
            array (
                'role_code' => '250',
                'menu_id' => 31,
            ),
            147 => 
            array (
                'role_code' => '250',
                'menu_id' => 32,
            ),
            148 => 
            array (
                'role_code' => '250',
                'menu_id' => 102,
            ),
            149 => 
            array (
                'role_code' => '250',
                'menu_id' => 30,
            ),
            150 => 
            array (
                'role_code' => '350',
                'menu_id' => 28,
            ),
            151 => 
            array (
                'role_code' => '350',
                'menu_id' => 29,
            ),
            152 => 
            array (
                'role_code' => '350',
                'menu_id' => 30,
            ),
            153 => 
            array (
                'role_code' => '350',
                'menu_id' => 31,
            ),
            154 => 
            array (
                'role_code' => '350',
                'menu_id' => 32,
            ),
            155 => 
            array (
                'role_code' => '350',
                'menu_id' => 102,
            ),
            156 => 
            array (
                'role_code' => '250',
                'menu_id' => 101,
            ),
            157 => 
            array (
                'role_code' => '350',
                'menu_id' => 101,
            ),
            158 => 
            array (
                'role_code' => '150',
                'menu_id' => 66,
            ),
            159 => 
            array (
                'role_code' => '350',
                'menu_id' => 66,
            ),
            160 => 
            array (
                'role_code' => '250',
                'menu_id' => 66,
            ),
            161 => 
            array (
                'role_code' => '310',
                'menu_id' => 66,
            ),
            162 => 
            array (
                'role_code' => '310',
                'menu_id' => 101,
            ),
            163 => 
            array (
                'role_code' => '240',
                'menu_id' => 101,
            ),
            164 => 
            array (
                'role_code' => '230',
                'menu_id' => 101,
            ),
            165 => 
            array (
                'role_code' => '220',
                'menu_id' => 101,
            ),
            166 => 
            array (
                'role_code' => '120',
                'menu_id' => 74,
            ),
            167 => 
            array (
                'role_code' => '120',
                'menu_id' => 66,
            ),
            168 => 
            array (
                'role_code' => '120',
                'menu_id' => 101,
            ),
            169 => 
            array (
                'role_code' => '340',
                'menu_id' => 69,
            ),
            170 => 
            array (
                'role_code' => '340',
                'menu_id' => 20,
            ),
            171 => 
            array (
                'role_code' => '340',
                'menu_id' => 101,
            ),
            172 => 
            array (
                'role_code' => '340',
                'menu_id' => 74,
            ),
            173 => 
            array (
                'role_code' => '340',
                'menu_id' => 66,
            ),
            174 => 
            array (
                'role_code' => '340',
                'menu_id' => 28,
            ),
            175 => 
            array (
                'role_code' => '340',
                'menu_id' => 29,
            ),
            176 => 
            array (
                'role_code' => '340',
                'menu_id' => 30,
            ),
            177 => 
            array (
                'role_code' => '340',
                'menu_id' => 31,
            ),
            178 => 
            array (
                'role_code' => '340',
                'menu_id' => 32,
            ),
            179 => 
            array (
                'role_code' => '170',
                'menu_id' => 69,
            ),
            180 => 
            array (
                'role_code' => '170',
                'menu_id' => 20,
            ),
            181 => 
            array (
                'role_code' => '170',
                'menu_id' => 74,
            ),
            182 => 
            array (
                'role_code' => '170',
                'menu_id' => 28,
            ),
            183 => 
            array (
                'role_code' => '170',
                'menu_id' => 29,
            ),
            184 => 
            array (
                'role_code' => '170',
                'menu_id' => 30,
            ),
            185 => 
            array (
                'role_code' => '170',
                'menu_id' => 31,
            ),
            186 => 
            array (
                'role_code' => '170',
                'menu_id' => 32,
            ),
            187 => 
            array (
                'role_code' => '170',
                'menu_id' => 67,
            ),
            188 => 
            array (
                'role_code' => '120',
                'menu_id' => 121,
            ),
            189 => 
            array (
                'role_code' => '110',
                'menu_id' => 121,
            ),
            190 => 
            array (
                'role_code' => '180',
                'menu_id' => 69,
            ),
            191 => 
            array (
                'role_code' => '180',
                'menu_id' => 20,
            ),
            192 => 
            array (
                'role_code' => '180',
                'menu_id' => 66,
            ),
            193 => 
            array (
                'role_code' => '180',
                'menu_id' => 28,
            ),
            194 => 
            array (
                'role_code' => '180',
                'menu_id' => 29,
            ),
            195 => 
            array (
                'role_code' => '180',
                'menu_id' => 30,
            ),
            196 => 
            array (
                'role_code' => '180',
                'menu_id' => 32,
            ),
            197 => 
            array (
                'role_code' => '190',
                'menu_id' => 69,
            ),
            198 => 
            array (
                'role_code' => '190',
                'menu_id' => 20,
            ),
            199 => 
            array (
                'role_code' => '190',
                'menu_id' => 118,
            ),
            200 => 
            array (
                'role_code' => '190',
                'menu_id' => 121,
            ),
            201 => 
            array (
                'role_code' => '190',
                'menu_id' => 66,
            ),
            202 => 
            array (
                'role_code' => '100',
                'menu_id' => 111,
            ),
            203 => 
            array (
                'role_code' => '140',
                'menu_id' => 111,
            ),
            204 => 
            array (
                'role_code' => '120',
                'menu_id' => 111,
            ),
            205 => 
            array (
                'role_code' => '150',
                'menu_id' => 111,
            ),
            206 => 
            array (
                'role_code' => '180',
                'menu_id' => 111,
            ),
        ));
        
        
    }
}