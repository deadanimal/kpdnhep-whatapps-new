<?php

namespace App\Repositories;

class MyIdentityRepository
{
    /**
     * create log file for myIdentity server response
     *
     * @param array $array
     */
    public static function generatelog($array)
    {
        // Get ip address
        $ip = $_SERVER['REMOTE_ADDR'];

        $AgencyCode = isset($array['AgencyCode']) ? $array['AgencyCode'] : null;
        $BranchCode = isset($array['BranchCode']) ? $array['BranchCode'] : null;
        $UserId = isset($array['UserId']) ? $array['UserId'] : null;
        $TransactionCode = isset($array['TransactionCode']) ? $array['TransactionCode'] : null;
        $RequestDateTime = isset($array['RequestDateTime']) ? $array['RequestDateTime'] : null;
        $ICNumber = isset($array['ICNumber']) ? $array['ICNumber'] : null;
        $Nama_Pengadu = isset($array['Nama_Pengadu']) ? $array['Nama_Pengadu'] : null;
        $RequestIndicator = isset($array['RequestIndicator']) ? $array['RequestIndicator'] : null;
        $ReplyDateTime = isset($array['ReplyDateTime']) ? $array['ReplyDateTime'] : null;
        $ReplyIndicator = isset($array['ReplyIndicator']) ? $array['ReplyIndicator'] : null;
        // $StatusPengadu = isset($array['StatusPengadu']) ? $array['StatusPengadu'] : null;
        $Name = isset($array['Name']) ? $array['Name'] : null;
        // $Nama_Pengadu = isset($array['Nama_Pengadu']) ? $array['Nama_Pengadu'] : null;
        // $error = isset($array['error']) ? $array['error'] : null;
        $StatusPengadu = isset($array['StatusPengadu']) ? $array['StatusPengadu'] : null;
        $MessageLog = isset($array['MessageLog']) ? $array['MessageLog'] : null;

        // Where the log will be saved
        $file = storage_path("myidentity/logmyidentity-".date('Y-m-d').".txt");

        // open the log file
        $open = fopen($file, "a+") or die("Unable to open file!");

        // Get Ip Address
        fwrite($open,$ip."|");

        // Agency Code: 110012
        fwrite($open,$AgencyCode."|");

        // Branch Code: eAduan
        fwrite($open,$BranchCode."|");

        // encode UserId: for Agency is Login Id, for Public is ic number
        fwrite($open,base64_encode($UserId)."|");

        // TransactionCode: T2 for Agency, T7 for Public
        fwrite($open,$TransactionCode."|");

        // Request Datetime from eaduan server
        fwrite($open,$RequestDateTime."|");

        // encode Ic Number user (search key)
        fwrite($open,base64_encode($ICNumber)."|");

        // Nama_Pengadu: nama daripada borang yang diisi oleh pengadu (form request)
        fwrite($open,$Nama_Pengadu."|");

        // RequestIndicator
        // A:basic and non basic data 
        // C:basic and non basic include foto
        fwrite($open,$RequestIndicator."|");

        // Reply Datetime from MyIdentity Server
        fwrite($open,$ReplyDateTime."|");

        // ReplyIndicator: 0-Error 1-Success 2-Alert
        fwrite($open,$ReplyIndicator."|");

        // Status Pengadu: 1 - 7
        // fwrite($open,$StatusPengadu."|");

        // Name: nama dari MyIdentity Server response
        fwrite($open,$Name."|");

        // Nama_Pengadu: nama daripada borang yang diisi oleh pengadu (form request)
        // fwrite($open,$Nama_Pengadu."|");

        // Status Pengadu: 1 - 7
        fwrite($open,$StatusPengadu."-");

        // error: error message
        // fwrite($open,$error);

        // MessageLog: Message Log
        fwrite($open,$MessageLog);

        // next line
        fwrite($open, "\n");

        // you must ALWAYS close the opened file once you have finished.
        fclose($open);
    }
}
