<?php require_once('header.php');?>
<?php require_once('Connections/connection.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
	  $theValue = ($theValue != "") ? "'" . date("Y-m-d",strtotime($theValue)) . "'" : "NULL";
      break;
	case "time":
	  $theValue = ($theValue != "") ? "'" . date("H:i:s",strtotime($theValue)) . "'" : "NULL";
      break;
    case "datetime":
	  $theValue = ($theValue != "") ? "'" . date("Y-m-d H:i:s",strtotime($theValue)) . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
	list($d, $m, $y) = explode("-", $_POST['activation_date']);
	$date = "{$y}-{$m}-{$d}";
	list($d1, $m1, $y1) = explode("-", $_POST['valid_upto']);
	$date1 = "{$y1}-{$m1}-{$d1}";
	
  $updateSQL = sprintf("UPDATE computer_details SET plan=%s, amount=%s, valid_upto=%s, customer_id=%s, activation_date=%s, os=%s, activation_date=%s WHERE id=%s",
                       GetSQLValueString($_POST['plan'], "text"),
                       GetSQLValueString($_POST['amount'], "int"),
                       GetSQLValueString($date1, "date"),
                       GetSQLValueString($_POST['customer_id'], "int"),
                       GetSQLValueString($_POST['mac_id'], "text"),
                       GetSQLValueString($_POST['os'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());
  
  $insertGoTo = "view_details.php?customer_id=".$_POST['customer_id'];
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $insertGoTo));

  
}
$colname_Recordset1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_Recordset1 = $_GET['recordID'];
}
mysql_select_db($database_connection, $connection);
$query_Recordset1 = sprintf("SELECT computer_details . * FROM computer_details WHERE id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

list($d, $m, $y) = explode("-", $row_Recordset1['valid_upto']);
$date = "{$y}-{$m}-{$d}";
list($d1, $m1, $y1) = explode("-", $row_Recordset1['activation_date']);
$date1 = "{$y1}-{$m1}-{$d1}";



mysql_select_db($database_connection, $connection);
$query_Recordset2 = "SELECT * FROM item";
$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);


?>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script>
function myAjax(value) {
      $.ajax({type: "POST", data: "mode=1&plan_id=" + value, url:"ajax.php",success:function(result){
        $("#existEmail").html(result);
		var str = result;
		var exploded = str.split('|');
		$("#amount").val(exploded[0]);
		$("#valid_upto").val(exploded[1]);
      }});
}
function upgradeAjax(value) {

	$("#plan").attr("disabled", false);
}

</script>
<h1 align="center">Edit Computer Details</h1>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Plan:</td>
      <td><span id="spryselect1">
      
      <select name="plan" id="plan" onchange="myAjax(this.value);" disabled="disabled">
      	<option value="">Plan</option>
      <?php do { ?>
      <?php
	  $disabled = 'disabled="disabled"'; 
	  /*if ($row_Recordset2['item_id'] >= $row_Recordset1['plan']) {
			$disabled = '';  
	  }*/  
	  if ($row_Recordset1['plan'] == 3 || $row_Recordset1['plan'] == 4 || $row_Recordset1['plan'] == 5) {
			$disabled = '';  
	  }
	  else if ($row_Recordset1['plan'] == 1) {
			if ($row_Recordset2['item_id'] == 6 || $row_Recordset2['item_id'] == 7 || $row_Recordset2['item_id'] == 8 || $row_Recordset2['item_id'] == 2 || $row_Recordset2['item_id'] == 1) {
				$disabled = '';  
			}
	  }
	  else if ($row_Recordset1['plan'] == 2) {
			if ($row_Recordset2['item_id'] == 6 || $row_Recordset2['item_id'] == 7 || $row_Recordset2['item_id'] == 8 || $row_Recordset2['item_id'] == 2) {
				$disabled = '';  
			}
	  } 
	  if ($row_Recordset1['plan'] == 6) {
			if ($row_Recordset2['item_id'] == 6 || $row_Recordset2['item_id'] == 7 || $row_Recordset2['item_id'] == 8) {
				$disabled = '';  
			}
	  } 
	  if ($row_Recordset1['plan'] == 7) {
			if ($row_Recordset2['item_id'] == 7 || $row_Recordset2['item_id'] == 8) {
				$disabled = '';  
			}
	  }
	  if ($row_Recordset1['plan'] == 8) {
			if ($row_Recordset2['item_id'] == 8) {
				$disabled = '';  
			}
	  }
	  
	  ?>
      	<option value="<?=$row_Recordset2['item_id']?>" <?=$disabled?> <?php if ($row_Recordset2['item_id'] == $row_Recordset1['plan']) { echo 'selected="selected"'; } ?>><?=$row_Recordset2['plan']?></option>
      <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
      </select>      
      
        
      <span class="selectRequiredMsg">Please select an item.</span></span><input type="button" id="upgrade" onclick="upgradeAjax();" value="Upgrade" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Amount:</td>
      <td><span id="sprytextfield1">
        <input type="text" name="amount" readonly="readonly" id="amount" value="<?php echo htmlentities($row_Recordset1['amount'], ENT_COMPAT, ''); ?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Activation Date:</td>
      <td><input type="text" name="activation_date" value="<?php if ($row_Recordset1['activation_date'] != '') { echo $date1; } else { echo date('d-m-Y'); };?>" readonly="readonly" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Valid Upto:</td>
      <td><span id="sprytextfield2">
      <input type="text" name="valid_upto" readonly="readonly" id="valid_upto" value="<?php echo $date; ?>" size="32" /> 
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Customer Id:</td>
      <td><input type="text" name="customer_id" value="<?php echo htmlentities($row_Recordset1['customer_id'], ENT_COMPAT, ''); ?>" readonly="readonly" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Mac Id:</td>
      <td><input type="text" name="mac_id" value="<?php echo htmlentities($row_Recordset1['mac_id'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Os:</td>
      <td><select name="os">
        <option value="" <?php if (!(strcmp("", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <option value="Windows 98" <?php if (!(strcmp("Windows 98", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows 98</option>
        <option value="Windows NT" <?php if (!(strcmp("Windows NT", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows NT</option>
        <option value="Windows 98 Second Edition " <?php if (!(strcmp("Windows 98 Second Edition ", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows 98 Second Edition </option>
        <option value="Windows 2000" <?php if (!(strcmp("Windows 2000", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows 2000</option>
        <option value="Windows Me" <?php if (!(strcmp("Windows Me", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows Me</option>
        <option value="Windows XP" <?php if (!(strcmp("Windows XP", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows XP</option>
        <option value="Windows XP 64-bit Edition" <?php if (!(strcmp("Windows XP 64-bit Edition", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows XP 64-bit Edition</option>
        <option value="Windows XP Home Edition" <?php if (!(strcmp("Windows XP Home Edition", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows XP Home Edition</option>
        <option value="Windows XP Media Center Edition" <?php if (!(strcmp("Windows XP Media Center Edition", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows XP Media Center Edition</option>
        <option value="Windows XP Professional" <?php if (!(strcmp("Windows XP Professional", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows XP Professional</option>
        <option value="Windows XP Professional x64 Edition" <?php if (!(strcmp("Windows XP Professional x64 Edition", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows XP Professional x64 Edition</option>
        <option value="Windows XP Starter Edition" <?php if (!(strcmp("Windows XP Starter Edition", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows XP Starter Edition</option>
        <option value="Windows XP Tablet PC Edition" <?php if (!(strcmp("Windows XP Tablet PC Edition", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows XP Tablet PC Edition</option>
        <option value="Windows Server 2003" <?php if (!(strcmp("Windows Server 2003", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows Server 2003</option>
        <option value="Windows Server 2003 R2" <?php if (!(strcmp("Windows Server 2003 R2", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows Server 2003 R2</option>
        <option value="Windows Vista" <?php if (!(strcmp("Windows Vista", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows Vista</option>
        <option value="Windows 7" <?php if (!(strcmp("Windows 7", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows 7</option>
        <option value="Windows 8" <?php if (!(strcmp("Windows 8", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Windows 8</option>
      </select></td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update" /><input name="new_user" type="button" onclick="MM_goToURL('parent','view_details.php?customer_id=<?php echo $row_Recordset1['customer_id']; ?>');return document.MM_returnValue" value="Back" /></td>
    </tr>
  </table>
  <input type="hidden" name="id" value="<?php echo $row_Recordset1['id']; ?>">
  <input type="hidden" name="customer_id" value="<?php echo $row_Recordset1['customer_id']; ?>" />
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "date", {format:"yyyy-mm-dd"});
//-->
</script>
<?php require_once('footer.php');?>