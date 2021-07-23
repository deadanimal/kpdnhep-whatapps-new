<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class LogMyIdentityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function openFile(Request $request)
    {
        $getFileLog = base64_decode($request->FileLog);
        $getTransactionCode = $request->TransactionCode;
        $getRequestDateTime = $request->RequestDateTime;
        $getRequestHours = $request->RequestHours;
        $getRequestMinutes = $request->RequestMinutes;
        $getReplyDateTime = $request->ReplyDateTime;
        $getReplyHours = $request->ReplyHours;
        $getReplyMinutes = $request->ReplyMinutes;
        $getReplyIndicator = $request->ReplyIndicator;
        $getStatusPengadu = $request->StatusPengadu;
        $getUserId = $request->UserId;
        $getSearchKP = $request->SearchKP;
        $getCondition = $getTransactionCode . '|';
        $getCondition .= $getReplyIndicator . '|';
        $getCondition .= $getStatusPengadu . '|';
        $getCondition .= $getRequestDateTime . '|';
        $getCondition .= $getReplyDateTime . '|';
        $getCondition .= $getUserId . '|';
        $getCondition .= $getSearchKP . '|';
        $getCondition .= $getRequestHours . '|';
        $getCondition .= $getRequestMinutes . '|';
        $getCondition .= $getReplyHours . '|';
        $getCondition .= $getReplyMinutes;
        $myfile = fopen('../storage/myidentity/' . $request->FileLog, 'r') or die("Unable to open file!");

        while (!feof($myfile)) {
            echo fgets($myfile) . "<br>";
            $readline = fgets($myfile);
            $line = explode('|', $readline);
            if (!empty($line[0])) {
                $ip = $line[0];
                $AgencyCode = $line[1];
                $BranchCode = $line[2];
                $UserId = base64_decode($line[3]);
                $TransactionCode = $line[4];
                $RequestDateTime = $line[5];
                $SearchKP = base64_decode($line[6]);
                $RequestIndicator = $line[7];
                $ReplyDateTime = $line[8];
                $ReplyIndicator = $line[9];
                $StatusPengadu = trim($line[10]);

                $param1 = trim(($getTransactionCode != '') ? $TransactionCode : '');
                $param2 = trim(($getReplyIndicator != '') ? $ReplyIndicator : '');
                $param3 = trim(($getStatusPengadu != '') ? $StatusPengadu : '');
                $param4 = trim(($getRequestDateTime != '') ? substr($RequestDateTime, 0, 10) : '');
                $param5 = trim(($getReplyDateTime != '') ? substr($ReplyDateTime, 0, 10) : '');
                $param6 = trim(($getUserId != '') ? $UserId : '');
                $param7 = trim(($getSearchKP != '') ? $SearchKP : '');
                $param8 = trim(($getRequestHours != '') ? substr($RequestDateTime, 11, 2) : '');
                $param9 = trim(($getRequestMinutes != '') ? substr($RequestDateTime, 14, 2) : '');
                $param10 = trim(($getReplyHours != '') ? substr($ReplyDateTime, 11, 2) : '');
                $param11 = trim(($getReplyMinutes != '') ? substr($ReplyDateTime, 14, 2) : '');
                $logCondition = $param1 . '|' . $param2 . '|' . $param3 . '|' . $param4 . '|' . $param5 . '|' . $param6 . '|' . $param7 . '|' . $param8 . '|';
                $logCondition .= $param9 . '|' . $param10 . '|' . $param11;
            }
        }

        fclose($myfile);
    }

    public function carianIdentity(Request $request)
    {
//        $ip='';
//        $AgencyCode='';
//        $BranchCode='';
//        $UserId='';
//        $TransactionCode='';
//        $RequestDateTime='';
//        $SearchKP='';
//        $RequestIndicator='';
//        $ReplyDateTime='';
//        $ReplyIndicator='';
//        $StatusPengadu='';
        $FileLog = isset($request->FileLog) ? $request->FileLog : '';
        $getFileLog = base64_decode($FileLog);
//        $getFileLog = base64_decode($request->FileLog);isset($request->ReplyIndicator) ? $request->ReplyIndicator : '';
        $getTransactionCode = isset($request->TransactionCode) ? $request->TransactionCode : '';
        $getRequestDateTime = isset($request->RequestDateTime) ? date('Y-m-d', strtotime($request->RequestDateTime)) : '';
        $getRequestHours = isset($request->RequestHours) ? $request->RequestHours : '';
        $getRequestMinutes = isset($request->RequestMinutes) ? $request->RequestMinutes : '';
        $getReplyDateTime = isset($request->ReplyDateTime) ? $request->ReplyDateTime : '';
        $getReplyHours = isset($request->ReplyHours) ? $request->ReplyHours : '';
        $getReplyMinutes = isset($request->ReplyMinutes) ? $request->ReplyMinutes : '';
        $getReplyIndicator = isset($request->ReplyIndicator) ? $request->ReplyIndicator : '';
        $getStatusPengadu = isset($request->StatusPengadu) ? $request->StatusPengadu : '';
        $getUserId = isset($request->UserId) ? trim($request->UserId) : '';
        $getSearchKP = isset($request->SearchKP) ? trim($request->SearchKP) : '';
        $getCondition = $getTransactionCode . '|';
        $getCondition .= $getReplyIndicator . '|';
        $getCondition .= $getStatusPengadu . '|';
        $getCondition .= $getRequestDateTime . '|';
        $getCondition .= $getReplyDateTime . '|';
        $getCondition .= $getUserId . '|';
        $getCondition .= $getSearchKP . '|';
        $getCondition .= $getRequestHours . '|';
        $getCondition .= $getRequestMinutes . '|';
        $getCondition .= $getReplyHours . '|';
        $getCondition .= $getReplyMinutes;
//        $logCondition = '';
        $bil = 1;
        if ($request->FileLog != '') {
            $myfile = fopen('../storage/myidentity/' . $getFileLog, 'r') or die("Unable to open file!");
            $i = 0;
            $rows = [];
            while (!feof($myfile)) {
//                echo fgets($myfile) . "<br>";
                $readline = fgets($myfile);

                $param = explode('|', $readline);
                $ip = isset($param[0]) ? $param[0] : '';
                $AgencyCode = isset($param[1]) ? $param[1] : '';
                $BranchCode = isset($param[2]) ? $param[2] : '';
                $UserId = isset($param[2]) ? base64_decode($param[3]) : '';
                $TransactionCode = isset($param[4]) ? $param[4] : '';
                $RequestDateTime = isset($param[5]) ? $param[5] : '';
                $SearchKP = isset($param[6]) ? base64_decode($param[6]) : '';
                $RequestIndicator = isset($param[7]) ? $param[7] : '';
                $ReplyDateTime = isset($param[8]) ? $param[8] : '';
                // $ReplyIndicator = isset($param[9]) ? $param[9] : '';
                $ReplyIndicator = isset($param[10]) ? $param[10] : '';
                // $StatusPengadu = trim(isset($param[10]) ? $param[10] : '');
                $StatusPengadu = trim(isset($param[12]) ? $param[12] : '');

                $param1 = trim(($getTransactionCode != '') ? $TransactionCode : '');
                $param2 = trim(($getReplyIndicator != '') ? $ReplyIndicator : '');
                // $param3 = trim(($getStatusPengadu != '') ? $StatusPengadu : '');
                $param3 = trim(($getStatusPengadu != '') ? substr($StatusPengadu, 0, 1) : '');
                $param4 = trim(($getRequestDateTime != '') ? substr($RequestDateTime, 0, 10) : '');
                $param5 = trim(($getReplyDateTime != '') ? substr($ReplyDateTime, 0, 10) : '');
                $param6 = trim(($getUserId != '') ? $UserId : '');
                $param7 = trim(($getSearchKP != '') ? $SearchKP : '');
                $param8 = trim(($getRequestHours != '') ? substr($RequestDateTime, 11, 2) : '');
                $param9 = trim(($getRequestMinutes != '') ? substr($RequestDateTime, 14, 2) : '');
                $param10 = trim(($getReplyHours != '') ? substr($ReplyDateTime, 11, 2) : '');
                $param11 = trim(($getReplyMinutes != '') ? substr($ReplyDateTime, 14, 2) : '');
                $logCondition = $param1 . '|' . $param2 . '|' . $param3 . '|' . $param4 . '|' . $param5 . '|' . $param6 . '|' . $param7 . '|' . $param8 . '|';
                $logCondition .= $param9 . '|' . $param10 . '|' . $param11;

                //dd($getCondition);
                //"T2||1||||||||"
                //"T7||1||||||||"
                if ($getCondition == '|') {
                    $line = explode('|', $readline);
                    if (!empty($line[0])) {
                        $rows[$i] = $line;
                    }
                } elseif ($getCondition == $logCondition) {
                    $line = explode('|', $readline);
                    if (!empty($line[0])) {
                        $rows[$i] = $line;
                    }
                }


//                if (!empty($line[0])) {
//                    
//                    $ip=$line[0];
//                    $AgencyCode=$line[1];
//                    $BranchCode=$line[2];
//                    $UserId=base64_decode($line[3]);
//                    $TransactionCode=$line[4];
//                    $RequestDateTime=$line[5];
//                    $SearchKP=base64_decode($line[6]);
//                    $RequestIndicator=$line[7];
//                    $ReplyDateTime=$line[8];
//                    $ReplyIndicator=$line[9];
//                    $StatusPengadu=trim($line[10]);
//                    $ip=isset($line[0]) ? $line[0] : '';
//                    $AgencyCode=isset($line[1]) ? $line[1] : '';
//                    $BranchCode=isset($line[2]) ? $line[2] : '';
//                    $UserId=isset($line[2]) ? base64_decode($line[3]) : '';
//                    $TransactionCode=isset($line[4]) ? $line[4] : '';
//                    $RequestDateTime=isset($line[5]) ? $line[5] : '';
//                    $SearchKP=isset($line[6]) ? base64_decode($line[6]) : '';
//                    $RequestIndicator=isset($line[7]) ? $line[7] : '';
//                    $ReplyDateTime=isset($line[8]) ? $line[8] : '';
//                    $ReplyIndicator=isset($line[9]) ? $line[9] : '';
//                    $StatusPengadu=trim(isset($line[10]) ? $line[10] : '');


//                }
                $i++;
            }
            fclose($myfile);
        }
        return view('logmyidentity.senarai',
            compact('rows', 'getCondition', 'logCondition', 'ip', 'AgencyCode', 'BranchCode', 'UserId', 'TransactionCode',
                'RequestDateTime', 'SearchKP', 'RequestIndicator', 'ReplyDateTime', 'ReplyIndicator', 'StatusPengadu', 'bil',
                'request'));
    }
}
