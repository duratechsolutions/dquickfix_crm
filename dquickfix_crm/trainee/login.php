<?php require_once('Connections/connection.php'); ?>
<?php mysql_select_db($database_connection, $connection);
	$query_Recordset1 = "SELECT ip FROM ip_manager";
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	$ipAddresses = array();
	if ($totalRows_Recordset1 > 0) {
		do {
			$ipAddresses[] = $row_Recordset1['ip'];
		} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
		
	}
	if (!in_array($_SERVER['REMOTE_ADDR'], $ipAddresses, true)) {
		header("Location: http://dquickfix.in/");
	}

?>
<?php 
ob_start();
?>

<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=md5($_POST['pass']);
  $MM_fldUserAuthorization = "level";
  $MM_redirectLoginSuccess = "customer_details.php";
  $MM_redirectLoginFailed = "login.php?status=1";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_connection, $connection);
  	
  $LoginRS__query=sprintf("SELECT * FROM login WHERE username=%s AND password='$password'",
  GetSQLValueString($loginUsername, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $connection) or die(mysql_error());
  $row_LoginRS = mysql_fetch_assoc($LoginRS);
  
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'level');
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	 
	$_SESSION['MM_Level'] = $row_LoginRS['level'];
	$_SESSION['UserId'] = $row_LoginRS['user_id'];

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" contents="noindex"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DQUICKFIX - Login to CRM</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />

<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet'>
<link rel="stylesheet" href="css/style.css">
<SCRIPT>
function click()
{
if (event.button==2)
    {   
        alert('This action cannot be performed!');
    }
}document.onmousedown=click
// - ->
</SCRIPT> 
</head>

<body style="background-color:#FFFFFF;">

	<!-- TOP BAR -->
	<div id="top-bar">
		
		<div class="page-full-width">
			

		</div> <!-- end full-width -->	
	
	</div> <!-- end top-bar -->
	
	
	
	<!-- HEADER -->
	<div id="header">
		
		<div class="page-full-width cf">
	
			<div id="login-intro" class="fl">
			
				<h1>Login to DQUICKFIX - CRM</h1>
				<h5>Enter your credentials below</h5>
			
			</div> <!-- login-intro -->
			
			<!-- Change this image to your own company's logo -->
			<!-- The logo will automatically be resized to 39px height. -->
			<a href="#" id="company-branding" class="fr"><img src="images/logo.png" alt="DQuickFix" /></a>
			
		</div> <!-- end full-width -->	

	</div> <!-- end header -->
<div id="content">

<?php if ($_GET['status'] == '1') { ?><div align="center" style="color:#F00;"><?php echo 'Invalid Username OR Password';  ?></div><?php } ?>

<form action="#" method="POST" id="login-form">
		
			<fieldset>

				<p>
					<label for="login-username">Username</label>
					<span id="sprytextfield1">
					<input type="text" name="username" id="username" class="round full-width-input" autofocus />
					<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
				</p>

				<p>
					<label for="login-password">Password</label>
					<span id="sprypassword1">
					<input type="password" name="pass" id="pass" class="round full-width-input" />
					<span class="passwordRequiredMsg">A value is required.</span></span>
				</p>				
				
<input type="submit" name="submit" id="submit" value="LOG IN"/>
				
			</fieldset>

			<br/><div class="information-box round">Click on the "LOG IN" button to continue</div>

		</form>
</div>

<div id="footer">

		<p>&copy; Copyright 2012 <a href="#">DQuickFix</a>. All rights reserved.</p>

	
	</div> <!-- end footer -->
	
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
//-->
</script>
</body>
</html>