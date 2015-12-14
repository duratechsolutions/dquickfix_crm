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

 
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
	mysql_select_db($database_connection, $connection);
	echo $query_Recordset1 = sprintf("SELECT email, phone FROM customer_details WHERE (email = %s OR phone = %s) AND id != %s", 
								GetSQLValueString($_POST['email'], 'text'),
								GetSQLValueString($_POST['phone'], "text"),
								GetSQLValueString($_POST['id'], 'int'));
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	if (!$totalRows_Recordset1 > 0) {
	
		  $updateSQL = sprintf("UPDATE customer_details SET title=%s, first_name=%s, last_name=%s, phone=%s, alternate_phone=%s, email=%s, street=%s, address=%s, city=%s, country=%s WHERE id=%s",
							   GetSQLValueString($_POST['title'], "text"),
							   GetSQLValueString($_POST['first_name'], "text"),
							   GetSQLValueString($_POST['last_name'], "text"),
							   GetSQLValueString($_POST['phone'], "text"),
							   GetSQLValueString($_POST['alternate_phone'], "text"),
							   GetSQLValueString($_POST['email'], "text"),
							   GetSQLValueString($_POST['street'], "text"),
							   GetSQLValueString($_POST['address'], "text"),
							   GetSQLValueString($_POST['city'], "text"),
							   GetSQLValueString($_POST['country'], "text"),
							   GetSQLValueString($_POST['id'], "int"));
		
		  mysql_select_db($database_connection, $connection);
		  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());
		
		  $updateGoTo = "search_customer.php";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
			$updateGoTo .= $_SERVER['QUERY_STRING'];
		  }
		  header(sprintf("Location: %s", $updateGoTo));
	} else {
		if ($row_Recordset1['email'] == $_POST['email']) {
			$msg = 'Email Already Registered!';			
		}
		else if ($row_Recordset1['phone'] == $_POST['phone']) {
			$msg = 'Phone Already Registered!';			
		}
		else if ($row_Recordset1['email'] == $_POST['email'] && $row_Recordset1['phone'] == $_POST['phone']) {
			$msg = 'Email and Phone are Already Registered!';			
		} else {
			$msg = 'Record Already Registered!';	
		}
	}
}

$colname_Recordset1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_Recordset1 = $_GET['recordID'];
}
mysql_select_db($database_connection, $connection);
$query_Recordset1 = sprintf("SELECT * FROM customer_details WHERE id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	

?>

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<h1 align="center">Edit Customer Details of Customer Id : <?=$_GET['customer_id']?></h1>
<?php if ($msg != '') { ?><div align="center" style="color:#F00;"><?php echo $msg;  ?></div><?php } ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
  <tr valign="baseline">
      <td nowrap="nowrap" align="right">Title</td>
      <td><select name="title">
        <option>Select</option>
        <option value="Mr." <?php if ($row_Recordset1['title'] == 'Mr.') { echo 'selected="selected"'; } ?>>Mr.</option>
        <option value="Miss." <?php if ($row_Recordset1['title'] == 'Miss.') { echo 'selected="selected"'; } ?>>Miss.</option>
        <option value="Mrs." <?php if ($row_Recordset1['title'] == 'Mrs.') { echo 'selected="selected"'; } ?>>Mrs.</option>
        &nbsp;
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">First name:</td>
      <td><span id="sprytextfield1">
        <input type="text" name="first_name" value="<?php echo htmlentities($row_Recordset1['first_name'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Last name:</td>
      <td><span id="sprytextfield2">
        <input type="text" name="last_name" value="<?php echo htmlentities($row_Recordset1['last_name'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Phone:</td>
      <td><span id="sprytextfield3">
      <input type="text" name="phone" value="<?php echo htmlentities($row_Recordset1['phone'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Alternate phone:</td>
      <td><span id="sprytextfield4">
        <input type="text" name="alternate_phone" value="<?php echo htmlentities($row_Recordset1['alternate_phone'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email:</td>
      <td><span id="sprytextfield5">
      <input type="text" name="email" value="<?php echo htmlentities($row_Recordset1['email'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Street:</td>
      <td><input type="text" name="street" value="<?php echo htmlentities($row_Recordset1['street'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Address:</td>
      <td><textarea name="address" cols="50" rows="5"><?php echo htmlentities($row_Recordset1['address'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">City:</td>
      <td><input type="text" name="city" value="<?php echo htmlentities($row_Recordset1['city'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Country:</td>
      <td><select name="country">
        <option value="" <?php if (!(strcmp("", $row_Recordset1['country']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <option value="United States" selected="selected" <?php if (!(strcmp("United States", $row_Recordset1['country']))) {echo "selected=\"selected\"";} ?>>United States</option>
        <option value="India" <?php if (!(strcmp("India", $row_Recordset1['country']))) {echo "selected=\"selected\"";} ?>>India</option>
        <?php
do {  
?>
        <option value="<?php echo $row_Recordset1['id']?>"<?php if (!(strcmp($row_Recordset1['id'], $row_Recordset1['country']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset1['id']?></option>
        <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_Recordset1['id']; ?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "phone_number", {validateOn:["blur"], format:"phone_custom", pattern:"000-000-0000", hint:"000-000-0000"});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "phone_number", {isRequired:false, validateOn:["blur"], format:"phone_custom", pattern:"000-000-0000", hint:"000-000-0000"});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "email", {validateOn:["blur"]});
//-->
</script>
<?php require_once('footer.php');?>
<?php
mysql_free_result($Recordset1);
?>
