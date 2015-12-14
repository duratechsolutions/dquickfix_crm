<?php require_once('header.php');?>
<?php require_once('Connections/connection.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	
	$colname_Recordset1 = "-1";
	if (isset($_POST['email'])) {
	  $colname_Recordset1 = $_POST['email'];
	}
	$colname_Recordset2 = "-1";
	if (isset($_POST['username'])) {
	  $colname_Recordset2 = $_POST['username'];
	}
	mysql_select_db($database_connection, $connection);
	$query_Recordset1 = sprintf("SELECT email, username FROM login WHERE email = %s OR username = %s", 
							GetSQLValueString($colname_Recordset1, "text"),
							GetSQLValueString($colname_Recordset2, "text"));
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	if (!$totalRows_Recordset1 > 0) { 
		  $insertSQL = sprintf("INSERT INTO login (username, email, password, first_name, last_name, emp_id, `level`) VALUES (%s, %s, %s, %s, %s, %s, %s)",
							   GetSQLValueString($_POST['username'], "text"),
							   GetSQLValueString($_POST['email'], "text"),
							   GetSQLValueString(md5($_POST['password']), "text"),
							   GetSQLValueString($_POST['first_name'], "text"),
							   GetSQLValueString($_POST['last_name'], "text"),
							   GetSQLValueString($_POST['emp_id'], "text"),
							   GetSQLValueString($_POST['level'], "int"));
		
		  mysql_select_db($database_connection, $connection);
		  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());
		
		  /*$insertGoTo = "create_user.php?status=true";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
			$insertGoTo .= $_SERVER['QUERY_STRING'];
		  }
		  header(sprintf("Location: %s", $insertGoTo));*/
                  $msg = "Agent successfully created!";
	} else {
		if ($row_Recordset1['email'] == $_POST['email']) { 
			$msg = 'Email Already Registered!';	
		}
		if ($row_Recordset1['username'] == $_POST['username']) {
			$msg = 'Username Already Registered!';	
		}
	}
}

?>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script> 
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<h1 align="center">Create New Agent</h1>
<?php if ($msg != '') { ?>
<div align="center" style="color:#F00;"><?php echo $msg;  ?></div>
<?php } ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Username</td>
      <td><span id="sprytextfield5">
        <input type="text" name="username" id="username" value="<?php echo $_POST['username'];?>" size="32" />
        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email:</td>
      <td><span id="sprytextfield1">
        <input type="text" name="email" value="<?php echo $_POST['email'];?>" size="32" />
        <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Password:</td>
      <td><span id="sprypassword1">
        <input type="password" name="password" value="" size="32" />
        <span class="passwordRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">First Name:</td>
      <td><span id="sprytextfield2">
        <input type="text" name="first_name" value="<?php echo $_POST['first_name'];?>" size="32" />
        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Last Name:</td>
      <td><span id="sprytextfield3">
        <input type="text" name="last_name" value="<?php echo $_POST['last_name'];?>" size="32" />
        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Emp Id:</td>
      <td><span id="sprytextfield4">
        <input type="text" name="emp_id" value="<?php echo $_POST['emp_id'];?>" size="32" />
        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Create Agent" /></td>
    </tr>
  </table>
  <input type="hidden" name="level" value="2" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email", {validateOn:["blur"]});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["blur"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none", {validateOn:["blur"]});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "none", {validateOn:["blur"]});
//-->
</script>
<?php require_once('footer.php');?>