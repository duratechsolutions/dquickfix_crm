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

	
	mysql_select_db($database_connection, $connection);
	$query_Recordset1 = sprintf("SELECT email, phone FROM customer_details WHERE email = %s OR phone = %s", 
								GetSQLValueString($_POST['email'], 'text'),
								GetSQLValueString($_POST['phone'], "text"));
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	if (!$totalRows_Recordset1 > 0) {
		
		if ($_POST['phone'] == "000-000-0000") {
			$_POST['phone'] = '';	
		}
		if ($_POST['alternate_phone'] == "000-000-0000") {
			$_POST['alternate_phone'] = '';	
		}

	
		  $insertSQL = sprintf("INSERT INTO customer_details (title, first_name, last_name, phone, alternate_phone, email, street, address, city, country, create_by) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
							   GetSQLValueString($_SESSION['UserId'], "int"));
		
		  mysql_select_db($database_connection, $connection);
		  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());
		  $msg = 'Successfully inserted';
		  $id = mysql_insert_id();
		  
		  if ((isset($_POST["noSale"])) && ($_POST["noSale"] != "")) {
			  $insertGoTo = "issue_info.php?customer_id=".$id."&nosale=yes";
			  if (isset($_SERVER['QUERY_STRING'])) {
				$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
				$insertGoTo .= $_SERVER['QUERY_STRING'];
			  }
		  }
		  if ((isset($_POST["saveNext"])) && ($_POST["saveNext"] != "")) {
			  $insertGoTo = "computer_details.php?customer_id=".$id;
			  if (isset($_SERVER['QUERY_STRING'])) {
				$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
				$insertGoTo .= $_SERVER['QUERY_STRING'];
			  }
		  }
		  header(sprintf("Location: %s", $insertGoTo));
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
?>
<script type="text/javascript" src="SpryAssets/jquery-1.2.6.min.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">

<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
<h2 align="center">Customer Details</h2>

<?php if ($msg != '') { ?><div align="center" style="color:#F00;"><?php echo $msg;  ?></div><?php } ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" border="0">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Title</td>
      <td><span id="spryselect1">
        <select name="title">
          <option>Select</option>
          <option value="Mr." <?php if ($_POST['title'] == 'Mr.') { echo 'selected="selected"'; } ?>>Mr.</option>
          <option value="Miss." <?php if ($_POST['title'] == 'Miss.') { echo 'selected="selected"'; } ?>>Miss.</option>
          <option value="Mrs." <?php if ($_POST['title'] == 'Mrs.') { echo 'selected="selected"'; } ?>>Mrs.</option>
          &nbsp;
        </select>
      <span class="selectRequiredMsg">Please select an item.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">First name:</td>
      <td><span id="sprytextfield1">
        <input type="text" name="first_name" value="<?php echo $_POST['first_name'];?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Last name:</td>
      <td><span id="sprytextfield2">
        <input type="text" name="last_name" value="<?php echo $_POST['last_name'];?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Phone:</td>
      <td><span id="sprytextfield3">
      <input type="text" name="phone" value="<?php echo $_POST['phone'];?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Alternate phone:</td>
      <td><span id="sprytextfield4">
        <input type="text" name="alternate_phone" value="<?php echo $_POST['alternate_phone'];?>" size="32" />
      <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email:</td>
      <td><span id="sprytextfield5">
      <input type="text" name="email" value="<?php echo $_POST['email'];?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Street:</td>
      <td><input type="text" name="street" value="<?php echo $_POST['street'];?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Address:</td>
      <td><span id="sprytextarea1">
        <textarea name="address" cols="50" rows="5"><?php echo $_POST['address'];?></textarea>
      <span class="textareaRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">City:</td>
      <td><input type="text" name="city" value="<?php echo $_POST['city'];?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">State</td>
      <td><input type="text" name="state" value="<?php echo $_POST['state'];?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Country:</td>
      <td><select name="country">
        <option value="" <?php if (!(strcmp("", $_POST['country']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <option value="United States" selected="selected" <?php if (!(strcmp("United States", $_POST['country']))) {echo "selected=\"selected\"";} ?>>United States</option>
        <option value="Canada" <?php if (!(strcmp("Canada", $_POST['country']))) {echo "selected=\"selected\"";} ?>>Canada</option>
        <option value="Australia" <?php if (!(strcmp("Australia", $_POST['country']))) {echo "selected=\"selected\"";} ?>>Australia</option>
        <option value="United Kingdom" <?php if (!(strcmp("United Kingdom", $_POST['country']))) {echo "selected=\"selected\"";} ?>>United Kingdom</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" name="saveNext" value="Next" /> &nbsp;&nbsp;<input type="submit" name="noSale" value="No Sale" /> </td>
    </tr>
  </table>
  <input type="hidden" name="create_by" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "phone_number", {validateOn:["blur"], format:"phone_custom", pattern:"000-000-0000", hint:"000-000-0000", useCharacterMasking:true});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "phone_number", {isRequired:false, validateOn:["blur"], format:"phone_custom", pattern:"000-000-0000", hint:"000-000-0000", useCharacterMasking:true});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "email", {validateOn:["blur"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {validateOn:["blur"]});
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
//-->
</script>
<?php require_once('footer.php');?>