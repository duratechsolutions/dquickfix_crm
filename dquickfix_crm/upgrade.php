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
	
	
	if ((isset($_POST['plan'])) && $_POST['plan_id'] != $_POST['plan']) {
		  $updateSQL = sprintf("UPDATE computer_details SET plan=%s, amount=%s, valid_upto=%s, customer_id=%s, activation_date=%s, computer_information=%s, os=%s, activation_date=%s, upgrade=%s, updated_by=%s WHERE id=%s",
							   GetSQLValueString($_POST['plan'], "text"),
							   GetSQLValueString($_POST['amount'], "int"),
							   GetSQLValueString($date1, "date"),
							   GetSQLValueString($_POST['customer_id'], "int"),
							   GetSQLValueString($date, "date"),
							   GetSQLValueString($_POST['computer_information'], "text"),
							   GetSQLValueString($_POST['os'], "text"),
							   GetSQLValueString($date, "date"),
							   GetSQLValueString('Y', "text"),
							   GetSQLValueString($_SESSION['UserId'], "int"),
							   GetSQLValueString($_POST['id'], "int"));
		
		  mysql_select_db($database_connection, $connection);
		  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());
		  
		  $insertSQL = sprintf("INSERT INTO history_computer_details (computer_details_id, plan, amount, valid_upto, customer_id, computer_information, os, activation_date, info_id, created_by, date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
							   GetSQLValueString($_POST['id'], "int"),
							   GetSQLValueString($_POST['plan'], "text"),
							   GetSQLValueString($_POST['amount'], "int"),
							   GetSQLValueString($date1, "date"),
							   GetSQLValueString($_POST['customer_id'], "int"),
							   GetSQLValueString($_POST['computer_information'], "text"),
							   GetSQLValueString($_POST['os'], "text"),
							   GetSQLValueString($date, "date"),
							   GetSQLValueString($_POST['info_id'], "int"),
							   GetSQLValueString($_SESSION['UserId'], "int"),
							   GetSQLValueString(date('Y-m-d H:i:s'), "datetime"));
		
		  mysql_select_db($database_connection, $connection);
		  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());
		  
		  $computer_details_id = mysql_insert_id();
		  
		  $payment_id = substr(md5(rand()),0,10);
		  
		  $insertSQL = sprintf("INSERT INTO payments (customer_id, computer_details_id, payment_id, plan, payment_amount) VALUES (%s, %s, %s, %s, %s)",
							   GetSQLValueString($_POST['customer_id'], "int"),
							   GetSQLValueString($computer_details_id, "int"),
							   GetSQLValueString($payment_id, "text"),
							   GetSQLValueString($_POST['plan'], "text"),
							   GetSQLValueString($_POST['amount'], "int"));
		
		  mysql_select_db($database_connection, $connection);
		  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());
		  
		  $payment_id = mysql_insert_id();
		  
		  $updateSQL = sprintf("UPDATE computer_details SET upgrade_pay_id=%s WHERE id=%s",
							   GetSQLValueString($payment_id, "int"),
							   GetSQLValueString($_POST['id'], "int"));
		
		  mysql_select_db($database_connection, $connection);
		  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());
		  
		  $insertGoTo = "view_details.php?customer_id=".$_POST['customer_id'];
		  /*if (isset($_SERVER['QUERY_STRING'])) {
			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
			$insertGoTo .= $_SERVER['QUERY_STRING'];
		  }*/
		  header(sprintf("Location: %s", $insertGoTo));
	} else {
		$insertGoTo = "view_details.php?customer_id=".$_POST['customer_id'];	
		header(sprintf("Location: %s", $insertGoTo));
	}

  
}
$colname_Recordset1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_Recordset1 = $_GET['recordID'];
}
mysql_select_db($database_connection, $connection);
$query_Recordset1 = sprintf("SELECT T1.*, T2.payment_status FROM computer_details T1 INNER JOIN payments T2 ON T1.id = T2.computer_details_id WHERE T1.id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

list($d, $m, $y) = explode("-", $row_Recordset1['valid_upto']);
$date = "{$y}-{$m}-{$d}";
list($d1, $m1, $y1) = explode("-", $row_Recordset1['activation_date']);
$date1 = "{$y1}-{$m1}-{$d1}";



mysql_select_db($database_connection, $connection);
$query_Recordset2 = "SELECT * FROM item WHERE item_id >= 11 AND item_id != 14 ORDER BY item_id";
$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);


?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>

<script>
function myAjax(value) {
	  var cid = $("#cid").val();
      $.ajax({type: "POST", data: "mode=2&cid="+cid+"&plan_id=" + value, url:"ajax.php",success:function(result){
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
<h1 align="center">Upgrade Payments</h1>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Plan:</td>
      <td><span id="spryselect1">
      
      <select name="plan" id="plan" onchange="myAjax(this.value);" disabled="disabled">
      	<option value="">Plan</option>
      <?php do { ?>
      <?php
	  //$disabled = 'disabled="disabled"'; 
	  /*if ($row_Recordset2['item_id'] >= $row_Recordset1['plan']) {
			$disabled = '';  
	  } 
	  
	  if ($row_Recordset1['plan'] >= 1 && $row_Recordset1['plan'] <= 3) {
		  if ($row_Recordset2['item_id'] > 3) {
			$disabled = 'disabled="disabled"'; 
		  }
	  }
	  if ($row_Recordset1['payment_status'] != "paid") {
		  if ($row_Recordset2['item_id'] >= 11) {
			$disabled = 'disabled="disabled"'; 
		  }
	  }*/
	  /* 
	  if ($row_Recordset1['plan'] == 2) {
			if ($row_Recordset2['item_id'] == 1) {
				$disabled = '';  
			}
	  } */
	 
	  
	  
	  
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
    <tr valign="top">
      <td nowrap="nowrap" align="right">Computer Information:</td>
      <td>
      <span id="sprytextarea1">
        <textarea name="computer_information" cols="30" rows="5"><?php echo htmlentities($row_Recordset1['computer_information'], ENT_COMPAT, ''); ?></textarea>
      <span class="textareaRequiredMsg">A value is required.</span></span>
      </td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Os:</td>
      <td>
      <?php 
		mysql_select_db($database_connection, $connection);
		$query_Recordset2 = "SELECT * FROM os";
		$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_error());
		$row_Recordset2 = mysql_fetch_assoc($Recordset2);
		$totalRows_Recordset2 = mysql_num_rows($Recordset2);
	  ?>
      <select name="os">
      <option value="" <?php if (!(strcmp("", $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?>>Select</option>
      <?php do { ?>
      	<option <?php if (!(strcmp($row_Recordset2['os'], $row_Recordset1['os']))) {echo "selected=\"selected\"";} ?> value="<?php echo $row_Recordset2['os']?>"><?php echo $row_Recordset2['os']?></option>
      <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
      </select></td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update" /><input name="new_user" type="button" onclick="MM_goToURL('parent','view_details.php?customer_id=<?php echo $row_Recordset1['customer_id']; ?>');return document.MM_returnValue" value="Back" /></td>
    </tr>
  </table>
  <input type="hidden" id="cid" name="id" value="<?php echo $row_Recordset1['id']; ?>">
  <input type="hidden" name="info_id" value="<?php echo $row_Recordset1['info_id']; ?>">
  <input type="hidden" name="plan_id" value="<?php echo $row_Recordset1['plan']; ?>">
  <input type="hidden" name="customer_id" value="<?php echo $row_Recordset1['customer_id']; ?>" />
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "date", {format:"yyyy-mm-dd"});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {validateOn:["blur"]});

//-->
</script>
<?php require_once('footer.php');?>