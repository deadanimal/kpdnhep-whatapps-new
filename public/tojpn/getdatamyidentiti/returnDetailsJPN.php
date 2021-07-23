<?php
/**
 * Created by PhpStorm.
 * User: jahari
 * Date: 1/19/2016
 * Time: 5:16 PM
 */
$workingpath=$_POST['workingpath'];
require "../../$workingpath/admin/include/session.php";
//session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");
$AgencyCode= "110012";
$BranchCode= "eAduan";
//$UserId = "780914118745";
//$UserId = isset($_POST['id'])?$_POST['id']:null;
//$UserId=isset($_SESSION['MB_ICNEW'])?$_SESSION['MB_ICNEW']:$_POST['id'];
if (isset($_SESSION['MB_ICNEW'])) {
    $UserId=$_SESSION['MB_ICNEW'];
}
else {
    session_unset();
    $UserId = isset($_POST['id'])?$_POST['id']:null;
}
$TransactionCode = "T2";
//$RequestDateTime = "2016-01-14 10:15:00";
$RequestDateTime = date("Y-m-d")."T". date("H:i:s");
//$ICNumber = "600201716159";
$ICNumber = isset($_POST['nokp'])?$_POST['nokp']:null;
$RequestIndicator = "A";

try
{
// line bwh utk test simulasi masalah teknikal
//    $client = new SoapClient("http://10.23.150.199/tojpn/tomyidentiti/crswsdev.wsdl");
//     line bwh utk test di server development eaduan
//   $client = new SoapClient("http://10.23.150.194/tojpn/tomyidentiti/crswsdev.wsdl");
//    $client = new SoapClient("http://".$_SERVER['SERVER_NAME']."/tojpn/tomyidentiti/crswsdev.wsdl");

    // line bwh utk capaian server production eaduan
    // fail esb2016_DP2.myidentity.gov.my.crt dalam folder /etc/pki/tls/certs/
    // sekiranya fail cert yang baru diberikan oleh pihak JPN hanya perlu salin ke folder berkenaan.
    $filewsdl=($_SERVER['SERVER_NAME']=='eaduan.kpdnkk.gov.my' || $_SERVER['SERVER_NAME']=='e-aduan.kpdnkk.gov.my')?'crsservice.wsdl':'crswsdev.wsdl';
    $wsdl = "http://".$_SERVER['SERVER_NAME']."/tojpn/tomyidentiti/".$filewsdl;
    $client = new SoapClient($wsdl);
//    $client = new SoapClient($wsdl,$options3);



    //$Nama_Pengadu = "AHMAD BIN SANI";
    $Nama_Pengadu = isset($_POST['nama_pengadu'])?$_POST['nama_pengadu']:null;

    $nocheckname = isset($_POST['numcondition'])?true:false;

    $response = $client->retrieveCitizensData(array(
        "AgencyCode" => $AgencyCode,
        "BranchCode" => $BranchCode,
        "UserId" => $UserId,
        "TransactionCode" => $TransactionCode,
        "RequestDateTime" => $RequestDateTime,
        "ICNumber" => $ICNumber,
        "RequestIndicator" => $RequestIndicator,

    ));

    //var_dump($response);
    $response = get_object_vars($response); // Pull parameters from SOAP connection
    // Sort out the parameters and grab their data
    $AgencyCode = $response['AgencyCode'];
    $BranchCode = $response['BranchCode'];
    $UserId = $response['UserId'];
    $TransactionCode = $response['TransactionCode'];
    $ReplyDateTime = $response['ReplyDateTime'];
    $ReplyIndicator = trim($response['ReplyIndicator']);
    $ICNumber = $response['ICNumber'];
    $Name = isset($response['Name'])?$response['Name']:null;
    $DateOfBirth = isset($response['DateOfBirth'])?$response['DateOfBirth']:null;
    $Gender = isset($response['Gender'])?$response['Gender']:null;
    $Race = isset($response['Race'])?$response['Race']:null;
    $Religion = isset($response['Religion'])?$response['Religion']:null;
    $PermanentAddress1 = isset($response['PermanentAddress1'])?$response['PermanentAddress1']:null;
    $PermanentAddress2 = isset($response['PermanentAddress2'])?$response['PermanentAddress2']:null;
    $PermanentAddress3 = isset($response['PermanentAddress3'])?$response['PermanentAddress3']:null;
    $PermanentAddressPostcode = isset($response['PermanentAddressPostcode'])?$response['PermanentAddressPostcode']:null;
    $PermanentAddressCityCode = isset($response['PermanentAddressCityCode'])?$response['PermanentAddressCityCode']:null;
    $PermanentAddressCityDesc = isset($response['PermanentAddressCityDesc'])?$response['PermanentAddressCityDesc']:null;
    $PermanentAddressStateCode = isset($response['PermanentAddressStateCode'])?$response['PermanentAddressStateCode']:null;
    $CorrespondenceAddress1 = isset($response['CorrespondenceAddress1'])?$response['CorrespondenceAddress1']:null;
    $CorrespondenceAddress2 = isset($response['CorrespondenceAddress2'])?$response['CorrespondenceAddress2']:null;
    $CorrespondenceAddress3 = isset($response['CorrespondenceAddress3'])?$response['CorrespondenceAddress3']:null;
    $CorrespondenceAddress4 = isset($response['CorrespondenceAddress4'])?$response['CorrespondenceAddress4']:null;
    $CorrespondenceAddress5 = isset($response['CorrespondenceAddress5'])?$response['CorrespondenceAddress5']:null;
    $CorrespondenceAddressPostcode = isset($response['CorrespondenceAddressPostcode'])?$response['CorrespondenceAddressPostcode']:null;
    $CorrespondenceAddressCityCode = isset($response['CorrespondenceAddressCityCode'])?$response['CorrespondenceAddressCityCode']:null;
    $CorrespondenceAddressCityDescription = isset($response['CorrespondenceAddressCityDescription'])?$response['CorrespondenceAddressCityDescription']:null;
    $CorrespondenceAddressStateCode = isset($response['CorrespondenceAddressStateCode'])?$response['CorrespondenceAddressStateCode']:null;
    $CorrespondenceAddressCountryCode = isset($response['CorrespondenceAddressCountryCode'])?$response['CorrespondenceAddressCountryCode']:null;
    $OldICnumber = isset($response['OldICnumber'])?$response['OldICnumber']:null;
    $DateOfDeath = isset($response['DateOfDeath'])?$response['DateOfDeath']:null;
    $CitizenshipStatus = isset($response['CitizenshipStatus'])?$response['CitizenshipStatus']:null;
    $ResidentialStatus = isset($response['ResidentialStatus'])?trim($response['ResidentialStatus']):null;
    $EmailAddress = isset($response['EmailAddress'])?$response['EmailAddress']:null;
    $MobilePhoneNumber = isset($response['MobilePhoneNumber'])?$response['MobilePhoneNumber']:null;
    $AddressStatus = isset($response['AddressStatus'])?$response['AddressStatus']:null;
    $RecordStatus = isset($response['RecordStatus'])?trim($response['RecordStatus']):null;
    $NewICNumber = isset($response['NewICNumber'])?$response['NewICNumber']:null;
    $CorrespondenceAddressUpdateDate = isset($response['CorrespondenceAddressUpdateDate'])?$response['CorrespondenceAddressUpdateDate']:null;
    $CorrespondenceAddressUpdateBy = isset($response['CorrespondenceAddressUpdateBy'])?$response['CorrespondenceAddressUpdateBy']:null;
    $VerifyStatus = isset($response['VerifyStatus'])?$response['VerifyStatus']:null;
    $MessageCode =   isset($response['MessageCode'])?$response['MessageCode']:null;
    $Message =   isset($response['Message'])?$response['Message']:null;
    //header("Content-Type: image/png");
    //$hexpic= $response['Photo'];
    //$data = pack("H" . strlen($hexpic), $hexpic);

    //echo $data;
    //echo "Request :<br>", htmlentities($soapClient->__getLastRequest()), "<br>";

    $proceedgetdata=false;
    if ($ReplyIndicator=='1' || $ReplyIndicator=='2') { //1 - Success  2 - Alert)
        if (($nocheckname)) {
            $matchname=true;
        }else {
            // untuk buang space dalam nama yg disi dan nama dari jpn kemudian bandingkan
            $matchname= (strtoupper(implode(explode(' ',$Name))) == strtoupper(implode(explode(' ',$Nama_Pengadu))))?true:false;
        }
        if ($matchname){
          if ($ResidentialStatus=='B' ||
              $ResidentialStatus=='C' ||
              $ResidentialStatus=='M' ||
              $ResidentialStatus=='P' ||
              $ResidentialStatus==''
          ){ // Warganegara dan Pemastautin Tetap
              if ($RecordStatus=='2' ||
                  $RecordStatus=='B' ||
                  $RecordStatus=='H') { // Sudah Meninggal
                $Msg = "Individu direkodkan telah meninggal dunia";
                  $StatusPengadu='6'; // Individu direkodkan telah meninggal dunia
                  $proceedgetdata=true; // kalu nok juga data
              } else {
                // Dapatkan rekod jpn letak dlm array
                $Msg = "";
                $proceedgetdata=true;
                  if ($ResidentialStatus=='B' ||
                      $ResidentialStatus=='C') {
                      $StatusPengadu='1';  // Warganegara
                  } else {
                      $StatusPengadu='2'; // Pemastautin Tetap
                  }

              }
          } else {
              $Msg="Bukan Warga Negara";
              $proceedgetdata=true;
              $StatusPengadu='3'; // Bukan Warganegara
          }
        } else {
            $Msg = "Nama tidak sepadan dengan Kad Pengenalan";
            $StatusPengadu='4'; // Nama tidak sama dengan No. kp
        }
    }
    else {
        //$Msg = "No. Kp tiada dalam pangkalan data MyIdentiti"; // Status Pengadu = 4
        $Msg = "No. Kp tidak Sah";
        $StatusPengadu='5'; // No. Kp tidak Sah
    }

    $arr = array();
    $arr['StatusPengadu']=$StatusPengadu;
    if ($proceedgetdata==true){
        require("../../$workingpath/admin/include/db_connection.php");
        require ("../../$workingpath/admin/application/aduan_baharu/label.php");
        $arr['Matching'] = 'Y';
        $arr['Msg'] = $Msg;
        $arr['ReplyIndicator'] = $ReplyIndicator;
        $arr['ResidentialStatus'] = $ResidentialStatus;
        $arr['RecordStatus'] = $RecordStatus;
        $arr['Message'] = $Message;
        $arr['Name'] = $Name;
        $arr['CorrespondenceAddress1'] = trim($CorrespondenceAddress1);
        $arr['CorrespondenceAddress2'] = trim($CorrespondenceAddress2);
        $arr['CorrespondenceAddressPostcode'] = trim($CorrespondenceAddressPostcode);
        $arr['CorrespondenceAddressCityCode'] = trim($CorrespondenceAddressCityCode);
        $arr['CorrespondenceAddressCityDescription'] = trim($CorrespondenceAddressCityDescription);
        $arr['CorrespondenceAddressStateCode'] = trim($CorrespondenceAddressStateCode);
        $arr['CorrespondenceAddressCountryCode'] = trim($CorrespondenceAddressCountryCode);
        $arr['EmailAddress'] = trim($EmailAddress);
        $arr['MobilePhoneNumber'] = trim(str_ireplace("-","",$MobilePhoneNumber));
        $arr['Gender'] = ($Gender=='0')?'0':(($Gender=='L')?'M':'F');
        $arr['InfoGender'] = ($Gender=='0')?'':(($Gender=='L')?'Lelaki':'Perempuan');
        $arr['DateOfBirth'] = trim($DateOfBirth);
        $arr['Age']=date_diff(date_create($DateOfBirth), date_create('today'))->y;
        ///*
        switch ($arr['Age']) {
            case ($arr['Age'] <= 18):
                $arr['AgeRange']=$lbl17;
        break;
            case ($arr['Age'] >= 19 and $arr['Age'] <= 25):
                $arr['AgeRange']=$lbl18;
        break;
            case ($arr['Age'] >= 26 and $arr['Age'] <= 40):
                $arr['AgeRange']=$lbl19;
        break;
            case ($arr['Age'] >= 41 and $arr['Age'] <= 55):
                $arr['AgeRange']=$lbl20;
        break;
            case ($arr['Age'] >= 56):
                $arr['AgeRange']=$lbl21;
        break;
            default:
                $arr['AgeRange']='0';
        }
        //*/
        if ($ResidentialStatus=='B' ||
            $ResidentialStatus=='C' ||
            $ResidentialStatus=='M' ||
            $ResidentialStatus=='P' ||
            $ResidentialStatus=='') {
            $arr['Warganegara']='malaysia';
            $arr['WarganegaraInfo']='Warganegara/Pemastautin Tetap';
        } else {
            $arr['Warganegara']='oth';
            $arr['WarganegaraInfo']='Lain-lain';
        }

        $qkodnegeri = mysql_query("SELECT kodsistem, keterangan FROM kodmapping WHERE kategori='negeri' AND koddiberi='".$arr['CorrespondenceAddressStateCode']."'");
        while($negerirow  = mysql_fetch_array($qkodnegeri)) {
            $arr['KodNegeri']=$negerirow['kodsistem'];
            $arr['NamaNegeri']=$negerirow['keterangan'];
        }
        $qkoddaerah = mysql_query("SELECT kodsistem, keterangan FROM kodmapping WHERE kategori='daerah' AND koddiberi='".$arr['CorrespondenceAddressCityCode']."'");
        while($daerahrow  = mysql_fetch_array($qkoddaerah)) {
            $arr['KodDaerah']=$daerahrow['kodsistem'];
            $arr['NamaDaerah']=$daerahrow['keterangan'];
        }
        $arr['UserId'] = $UserId;
        $arr['ICNumber'] = $ICNumber;
        //
        $arr['ReqRepDateTime']=$RequestDateTime." (Request Time) <==> ".$ReplyDateTime." (Reply Time)";
        //
    }
    else {
        $arr['Matching'] = 'N';
        $arr['Msg'] = $Msg;
        //
        $arr['ReplyIndicator'] = $ReplyIndicator;
        $arr['ResidentialStatus'] = $ResidentialStatus;
        $arr['RecordStatus'] = $RecordStatus;
        $arr['Message'] = $Message;
        $arr['ICNumber'] = $ICNumber;
        $arr['Name'] = "";
        $arr['EmailAddress'] = "";
        $arr['MobilePhoneNumber'] = "";
        $arr['CorrespondenceAddressPostcode'] = "";
        $arr['NamaNegeri']="";
        $arr['NamaDaerah']="";
        $arr['InfoGender'] ="";
        $arr['Gender'] ='0';
        $arr['Age']='';
        $arr['AgeRange']='0';
        $arr['Warganegara']='0';
        $arr['KodNegeri']='0';
        $arr['KodDaerah']='0';

        //
    }
    echo json_encode($arr);
    //exit();
}
catch (SoapFault $fault)
{
    $arr=array();
    //$arr['Error']=trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
    //$arr['Error']='code: '.$fault->faultcode.' | string: '.$fault->faultstring.' | actor: '.$fault->faultactor.' | name: '.$fault->faultname;
    $arr['Msg']="Masalah teknikal";
    $arr['KodDaerah']='0';
    $StatusPengadu='7'; // Masalah teknikal
    $ReplyDateTime='';
    $ReplyIndicator='';
    $arr['StatusPengadu']=$StatusPengadu;
    echo json_encode($arr);
}
///////////////////////////////////////create log file for myIdentity server response////////////////////////////
$ip = $_SERVER['REMOTE_ADDR']; //Get there ip address.
$file = "logmyidentity.txt"; //Where the log will be saved.
$open = fopen($file, "a+"); //open the file, (logmyidentity.txt).
fwrite($open,$ip."|"); // Get Ip Address
fwrite($open,$AgencyCode."|"); // Agency Code: 110012
fwrite($open,$BranchCode."|"); // Branch COde: eAduan
fwrite($open,base64_encode($UserId)."|"); // encode UserId: for Agency is Login Id, for Public is ic number
//fwrite($open,$UserId."|"); // UserId: for Agency is Login Id, for Public is ic number
fwrite($open,$TransactionCode."|"); // T2 for Agency, T7 for Public
fwrite($open,$RequestDateTime."|"); // Request Datetime from eaduan server
fwrite($open,base64_encode($ICNumber)."|"); // encode Ic Number user (search key)
//fwrite($open,$ICNumber."|"); // Ic Number user (search key)
fwrite($open,$RequestIndicator."|"); // A:basic and non basic data C:basic and non basic include foto
fwrite($open,$ReplyDateTime."|"); // Reply Datetime from MyIdentiti Server
fwrite($open,$ReplyIndicator."|"); // 0-Error 1-Success 2-Alert
fwrite($open,$StatusPengadu); // Status Pengadu: 1 -7
fwrite($open, "\n"); // next line
fclose($open); // you must ALWAYS close the opened file once you have finished.
//////////////////////////////////akhir create log file for myIdentity server response//////////////////////////////
exit();
?>