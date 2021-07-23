<?php

namespace App\Repositories;

use App\Aduan\Runner;

class RunnerRepository
{
    /**
     * To generate running number form various rule (up to 3) and type of number.
     *
     * Lets learn some magic!
     * pertanyaan                               P1800001            rule_1 = P; rule_2 = 18;
     * aduan pengguna awam / web                01800001            rule_1 = X; rule_2 = 18;
     * aduan call center                        001800001           rule_1 = X; rule_2 = 18
     * aduan mobile app                         0001800001          rule_1 = X; rule_2 = 18
     * aduan profil tinggi (sas) (deprecated)   SAS1800001          rule_1 = X; rule_2 = 18
     * aduan profil tinggi (aduan khas)         AK02000001          rule_1 = AK; rule_2 = 20
     * integriti                                INT00001/01/2018    rule_1 = INT; rule_2 = 01; rule_3 = 18
     *
     * @param $rule_1
     * @param $rule_2
     * @param null $rule_3
     * @param null $rule_4
     * @return string
     */
    public static function generateAppNumber($rule_1, $rule_2, $rule_3 = null, $rule_4 = null)
    {
        $runner = Runner::where('rule_1', '=', $rule_1)
            ->where('rule_2', '=', $rule_2)
            ->where('rule_3', '=', $rule_1 == 'INT' ? $rule_3 : NULL)
            ->first();

        if (!$runner) {
            $runnerCreate = new Runner;
            $runnerCreate->insert([
                'rule_1' => $rule_1,
                'rule_2' => $rule_2,
                'rule_3' => $rule_1 == 'INT' ? $rule_3 : NULL,
                'index' => 1
            ]);

            $runningNumberRaw = 1;
        } else {
            Runner::where('rule_1', '=', $rule_1)
                ->where('rule_2', '=', $rule_2)
                ->where('rule_3', '=', $rule_1 == 'INT' ? $rule_3 : NULL)
                ->increment('index');

            $runner = Runner::where('rule_1', '=', $rule_1)
                ->where('rule_2', '=', $rule_2)
                ->where('rule_3', '=', $rule_1 == 'INT' ? $rule_3 : NULL)
                ->first();

            $runningNumberRaw = $runner->index;
        }

        switch ($rule_1) {
            case 'P':
                return 'P' . $rule_2 . str_pad($runningNumberRaw, 5, '0', STR_PAD_LEFT);
                break;
            case 'X':
                return $rule_3 . $rule_2 . str_pad($runningNumberRaw, 5, '0', STR_PAD_LEFT);
                break;
            case 'AK':
                return $rule_3 . $rule_2 . str_pad($runningNumberRaw, 5, '0', STR_PAD_LEFT);
                break;
//            case 'CASE':
//                return 'PD/'.$rule_2.'/'.$rule_3.'/'.str_pad($runningNumberRaw, 5, '0', STR_PAD_LEFT).'/'.$rule_4;
//                break;
            case 'INT':
                return 'INT' . str_pad($runningNumberRaw, 5, '0', STR_PAD_LEFT) . '/' . $rule_2 . '/' . $rule_3;
                break;
            default:
                return '';
                break;
        }
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Runner::class;
    }
}
