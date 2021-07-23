<?php
$host="10.23.155.67"; // Host name 
$username="base@WSX"; // Mysql username 
$password="@WSX1qaz"; // Mysql password 

$db_name="eaduanlive"; // Database name 
//$tbl_name="user"; // Table name

$conn = mysql_connect("$host", "$username", "$password"); 

if(!$conn){ 
    header("location:index.php");
}else{
    header("location:index2.php");
}

/*function mylookup($Table, $fName, $sWhere){
  $sSQL = "SELECT " . $fName . " FROM " . $Table . " WHERE " . $sWhere;
  $result1 = mysql_query($sSQL);
  $intRecCount1 = mysql_num_rows($result1);
  if($intRecCount1 > 0){
        $row1 = mysql_fetch_array($result1, MYSQL_BOTH);
	     return $row1['0'];
  }  else  return ""; 
} */



mysql_select_db("$db_name")or die("cannot select DB");
?>
