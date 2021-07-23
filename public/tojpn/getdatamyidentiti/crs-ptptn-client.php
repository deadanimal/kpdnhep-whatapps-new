<?php
try
{
  // line bwh utk test di local
  //$client = new SoapClient("http://localhost/tomyidentiti/crswsdev.wsdl");
  // line bwh utk test di server development eaduan
  $client = new SoapClient("http://10.23.150.194/tojpn/tomyidentiti/crswsdev.wsdl");
  $AgencyCode= "110012";
  $BranchCode= "eAduan";
  //$UserId = "680809115059";
  $UserId = $_GET['id'];
  $TransactionCode = "T2";
  $RequestDateTime = "2016-01-14 10:15:00";
  //$ICNumber = "600101015155";
  $ICNumber = $_GET['ic'];
  $RequestIndicator = "A";

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
  $ReplyIndicator = $response['ReplyIndicator'];
  $ICNumber = $response['ICNumber'];
  $Name = $response['Name'];
  $DateOfBirth = $response['DateOfBirth'];
  $Gender = $response['Gender'];
  $Race = $response['Race'];
  $Religion = $response['Religion'];
  $PermanentAddress1 = $response['PermanentAddress1'];
  $PermanentAddress2 = $response['PermanentAddress2'];
  $PermanentAddress3 = $response['PermanentAddress3'];
  $PermanentAddressPostcode = $response['PermanentAddressPostcode'];
  $PermanentAddressCityCode = $response['PermanentAddressCityCode'];
  $PermanentAddressCityDesc = $response['PermanentAddressCityDesc'];
  $PermanentAddressStateCode = $response['PermanentAddressStateCode'];
  $CorrespondenceAddress1 = $response['CorrespondenceAddress1'];
  $CorrespondenceAddress2 = $response['CorrespondenceAddress2'];
  $CorrespondenceAddress3 = $response['CorrespondenceAddress3'];
  $CorrespondenceAddress4 = $response['CorrespondenceAddress4'];
  $CorrespondenceAddress5 = $response['CorrespondenceAddress5'];
  $CorrespondenceAddressPostcode = $response['CorrespondenceAddressPostcode'];
  $CorrespondenceAddressCityCode = $response['CorrespondenceAddressCityCode'];
  $CorrespondenceAddressCityDescription = $response['CorrespondenceAddressCityDescription'];
  $CorrespondenceAddressStateCode = $response['CorrespondenceAddressStateCode'];
  $CorrespondenceAddressCountryCode = $response['CorrespondenceAddressCountryCode']; 
  $OldICnumber = $response['OldICnumber']; 
  $DateOfDeath = $response['DateOfDeath'];
  $CitizenshipStatus = $response['CitizenshipStatus'];
  $ResidentialStatus = $response['ResidentialStatus'];
  $EmailAddress = $response['EmailAddress'];
  $MobilePhoneNumber = $response['MobilePhoneNumber'];
  $AddressStatus = $response['AddressStatus'];
  $RecordStatus = $response['RecordStatus'];
  $NewICNumber = $response['NewICNumber']; 
  $CorrespondenceAddressUpdateDate = $response['CorrespondenceAddressUpdateDate']; 
  $CorrespondenceAddressUpdateBy = $response['CorrespondenceAddressUpdateBy']; 
  $VerifyStatus = $response['VerifyStatus'];
  $MessageCode =   $response['MessageCode'];
  $Message =   $response['Message'];
  //header("Content-Type: image/png");  
  //$hexpic= $response['Photo'];
  //$data = pack("H" . strlen($hexpic), $hexpic);  
  
  //echo $data;
  //echo "Request :<br>", htmlentities($soapClient->__getLastRequest()), "<br>";
  echo "AGENCY CODE : ".$AgencyCode,"<br>";
  echo "BRANCH CODE : ".$BranchCode,"<br>";
  echo "USER ID : ".$UserId,"<br>";
  echo "TRANSACTION CODE : ".$TransactionCode,"<br>";
  echo "REPLY DATETIME : ".$ReplyDateTime,"<br>";
  echo "REPLY INDICATOR : ".$ReplyIndicator,"<br>";
  echo "IC NUMBER : ".$ICNumber,"<br>";
  echo "NAME : ".$Name,"<br>";
  echo "DATE OF BIRTH : ".$DateOfBirth,"<br>";
  echo "GENDER : ".$Gender,"<br>";
  echo "RACE : ".$Race,"<br>";
  echo "RELIGION : ".$Religion,"<br>";
  echo "PERMANENT ADDRESS 1 : ".$PermanentAddress1,"<br>";
  echo "PERMANENT ADDRESS 2 : ".$PermanentAddress2,"<br>";
  echo "PERMANENT ADDRESS 3 : ".$PermanentAddress3,"<br>";
  echo "PERMANENT ADDRESS POSTCODE : ".$PermanentAddressPostcode,"<br>";
  echo "PERMANENT ADDRESS CITY CODE : ".$PermanentAddressCityCode,"<br>";
  echo "PERMANENT ADDRESS CITY DESCRIPTION : ".$PermanentAddressCityDesc,"<br>";
  echo "PERMANENT ADDRESS STATE CODE : ".$PermanentAddressStateCode,"<br>";
  echo "CORRESPONDENCE ADDRESS 1 : ".$CorrespondenceAddress1,"<br>";
  echo "CORRESPONDENCE ADDRESS 2 : ".$CorrespondenceAddress2,"<br>";
  echo "CORRESPONDENCE ADDRESS 3 : ".$CorrespondenceAddress3,"<br>";
  echo "CORRESPONDENCE ADDRESS 4 : ".$CorrespondenceAddress4,"<br>";
  echo "CORRESPONDENCE ADDRESS 5 : ".$CorrespondenceAddress5,"<br>";
  echo "CORRESPONDENCE ADDRESS POSTCODE : ".$CorrespondenceAddressPostcode,"<br>";
  echo "CORRESPONDENCE ADDRESS CITY CODE : ".$CorrespondenceAddressCityCode,"<br>";
  echo "CORRESPONDENCE ADDRESS CITY DESCRIPTION : ".$CorrespondenceAddressCityDescription,"<br>";
  echo "CORRESPONDENCE ADDRESS STATE CODE : ".$CorrespondenceAddressStateCode,"<br>";
  echo "CORRESPONDENCE ADDRESS COUNTRY CODE : ".$CorrespondenceAddressCountryCode,"<br>";
  echo "ADDRESS STATUS : ".$AddressStatus,"<br>";
  echo "CORRESPONDENCE ADDRESS UPDATE DATE : ".$CorrespondenceAddressUpdateDate,"<br>";
  echo "CORRESPONDENCE ADDRESS UPDATE BY : ".$CorrespondenceAddressUpdateBy,"<br>";
  echo "OLD IC NUMBER : ".$OldICnumber,"<br>";
  echo "DATE OF DEATH : ".$DateOfDeath,"<br>";
  echo "CITIZENSHIP STATUS : ".$CitizenshipStatus,"<br>";
  echo "RESIDENTIAL STATUS : ".$ResidentialStatus,"<br>";
  echo "EMAIL ADDRESS : ".$EmailAddress,"<br>";
  echo "MOBILE PHONE NUMBER : ".$MobilePhoneNumber,"<br>";
  echo "RECORD STATUS : ".$RecordStatus,"<br>";
  echo "NEW IC NUMBER : ".$NewICNumber,"<br>";
  echo "VERIFY STATUS : ".$VerifyStatus,"<br>";
  echo "MESSAGES CODE : ".$MessageCode,"<br>";
  echo "MESSAGES : ".$Message,"<br>";
  echo "<br>";
  }
catch (SoapFault $fault)
{
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}
  var_dump($response);
?> 