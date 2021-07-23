<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<?
header("location:index2.php");
date_default_timezone_set('Asia/Kuala_Lumpur');
foreach($_REQUEST as $key=>$value){
$key."=>".$value."<br />\n";
}

$file='counter.txt';
$count=file_get_contents($file);
file_put_contents($file,$count,LOCK_EX);

include ("include/db_connection.php");
 
$query=mysql_query("SELECT CA_CASEID, COUNT(CA_CASEID) FROM pct_case WHERE CA_RCVDT >'2012-07-01' && CA_RCVTYP='S23' GROUP BY CA_RCVTYP ") or die(mysql_error());
while($row = mysql_fetch_array($query)){
	 $cal=$row['COUNT(CA_CASEID)'];
}
 "<br>Jumlah Semakan Aduan Sys Baru ".$count; 

?>
	
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>eAduan::Home</title>
<link rel="stylesheet" href="css/styleie.css" >
<link href="css/table.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header"> <?php 
  require("logo.php");
  ?></div>
<div id="navigation">		    
  <div class="mainMenu">
  <?php 
  require("topnav.php");
  ?>
  </div>
</div>
	
<div id="content" align="justify"> 
<div>

<?php

include ("include/db_connection.php");

$lang = $_GET['lang'];

//////////////////////not to use get coz can then get the db
$type='P02';

echo "<br/><br/>";
echo "<form method='post' >";

echo "<table align='center' width='80%'>";

echo "<tr><td class='td'>";
//echo "<a href='cara_penggunaan_n.php?lang=2&content_type=$type'>Bahasa Melayu </a>";
echo "<br/>";
echo "<input type='hidden' name='type' value=".$type." />" ;
//echo "<a href='cara_penggunaan_n.php?lang=1&content_type=$type'>Bahasa Inggeris </a>";


echo "<br/>";



	$query = mysql_query("SELECT * from content_tab where LANG = '$lang' and CTNT_CD='P01' ") or die(mysql_error());

	
	$row = mysql_fetch_array($query) or die(mysql_error());
	
	$Desc = $row['CTNT_DESC'];
	
	echo $Desc;
	
echo "<p align='center'><br><br>";	


echo " Jumlah Aduan Bermula 1 Julai 2012  : <b>". $cal;
echo "</b><br>Jumlah Semakan Aduan Atas Talian : <b>".$count; 
	
	echo "</td></tr>";
echo "<tr><td class='td'><br><br>";
echo "<strong>Jika aduan anda adalah berkaitan salahlaku pegawai KPDNKK, sila ke <a href=http://eintegriti.kpdnkk.gov.my>http://eintegriti.kpdnkk.gov.my</a>. Terima kasih.</strong;>";
echo "</td></tr>";
echo "<tr><td><br><br>";
echo "<p align='center'><font ='3' color ='red'><strong>Sesuai dipapar menggunakan Mozilla Firefox 8.5 dan Google Chrome 9 ";
echo "</td></tr>";
echo "</table>";
echo "</form>";

 ?>
</div>
</div>

<div id="footer">
  <?php 
  require("footer.php");
  ?>
</div>

</body></html>
