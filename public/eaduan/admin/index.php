<?
header("location:../index2.php");
session_start();
include("include/db_connection.php");
if (isset($_SESSION['MB_ICNEW']))
{
	header("location:loginstat.php");
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<title>Login eAduan Administration</title>
	
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="stylesheet" type="text/css" href="style.css" />
	
	
	   <SCRIPT language=Javascript>
      <!--
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
      //-->
   </SCRIPT>
	
	
	
</head>

<body>

	<form id="form1" action="checkLogin.php" method="post">
		<fieldset>
		
			<legend>eAduan Administration</legend>
			<br>
			<label for="login"><font color="#000000" style="font-family:Impact;" >User ID</font></label>
			<input type="text" name="myUserName" style="background-color:#CCC;" onkeypress="return isNumberKey(event)" maxlength="12" />
			<div class="clear"></div>
			
			<label for="password"><font color="#000000" style="font-family:Impact" >Password</font></label>
			<input type="password" style="background-color:#CCC;" name="myPassWord"/>
			<div class="clear"></div>
			
			
			<br>
			<div class="clear"></div>
			
			<br />
			
			<input type="submit" style="margin: -20px 0 -20px 287px; " class="button" name="commit" value="Log in"/>	
		</fieldset>
	</form>
	<br><br><br>
	<p align="center">Sesuai dipapar menggunakan Mozilla Firefox 8.5 dan Google Chrome 9 
	
</body>

</html>