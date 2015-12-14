<?php require_once('header.php');?>
<?php require_once('Connections/connection.php'); ?>
<?php
$paymentId = substr(md5(rand()),0,10);
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

if ((isset($_GET['delId'])) && ($_GET['delId'] != "")) {
  $deleteSQL = sprintf("DELETE FROM computer_details WHERE id=%s",
                       GetSQLValueString($_GET['delId'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($deleteSQL, $connection) or die(mysql_error());
  
  $deleteSQL = sprintf("DELETE FROM payments WHERE computer_details_id=%s",
                       GetSQLValueString($_GET['delId'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($deleteSQL, $connection) or die(mysql_error());
  
}


if (((isset($_POST["insert_record"])) && ($_POST["insert_record"] != "")) || ((isset($_POST["add_multiple"])) && ($_POST["add_multiple"] != ""))) {
	
	list($d, $m, $y) = explode("-", $_POST['activation_date']);
	$date = "{$y}-{$m}-{$d}";
	list($d1, $m1, $y1) = explode("-", $_POST['valid_upto']);
	$date1 = "{$y1}-{$m1}-{$d1}";
	
	
  $insertSQL = sprintf("INSERT INTO computer_details (plan, amount, valid_upto, customer_id, computer_information, os, activation_date, info_id, created_by, date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['plan'], "text"),
                       GetSQLValueString($_POST['amount'], "double"),
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
  
  $updateSQL = sprintf("UPDATE issue_info SET computer_details_id=%s WHERE id=%s",
                       GetSQLValueString($computer_details_id, "int"),
                       GetSQLValueString($_POST['info_id'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());
  
  
  $insertSQL = sprintf("INSERT INTO payments (customer_id, computer_details_id, payment_id, plan, payment_amount) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['customer_id'], "int"),
					   GetSQLValueString($computer_details_id, "int"),					   
                       GetSQLValueString($_POST['payment_id'], "text"),
					   GetSQLValueString($_POST['plan'], "text"),
					   GetSQLValueString($_POST['amount'], "double"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());
  
  $payment_id = mysql_insert_id();
  
  if ((isset($_POST["insert_record"])) && ($_POST["insert_record"] != "")) {
	  $insertGoTo = "thank_you.php?payment_id=".$_POST['payment_id'];
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $insertGoTo));
  }
}

mysql_select_db($database_connection, $connection);
$query_Recordset2 = "SELECT * FROM item ORDER BY item_id";
$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="SpryAssets/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="css/colorbox.css"/>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script>
function myAjax(value) {
      $.ajax({type: "POST", data: "mode=1&plan_id=" + value, url:"ajax.php",success:function(result){
		var str = result;
		var exploded = str.split('|');
		$("#amount").val(exploded[0]);
		$("#valid_upto").val(exploded[1]);
      }});
}

	$.noConflict();
	jQuery(document).ready(function(){
		jQuery(".iframe").colorbox({iframe:true, width:"60%", height:"90%"});
	});
</script>

<h1 align="center">Computer Details </h1>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Plan:</td>
      <td><span id="spryselect1">
        <select name="plan" id="plan" onchange="myAjax(this.value);">
      	<option value="">Plan</option>
      <?php do { ?>
      <?php
	  if ($row_Recordset2['item_id'] >= 11 && $row_Recordset2['item_id'] != 14) {
			$disabled = 'disabled="disabled"'; 
	  }
	  ?>
      	<option value="<?php echo $row_Recordset2['item_id']?>" <?=$disabled?>><?php echo $row_Recordset2['plan']?></option>
      <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
      </select>
      <span class="selectRequiredMsg">Please select an item.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Amount:</td>
      <td><span id="sprytextfield1">
      <input type="text" name="amount" id="amount" value="" size="32" readonly="readonly" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Activation Date:</td>
      <td><input type="text" name="activation_date" id="activation_date" readonly="readonly" value="<?php echo date('d-m-Y');?>" size="32" /></td>
    </tr>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Valid Upto:</td>
      <td><span id="sprytextfield2">
      <input type="text" name="valid_upto" id="valid_upto" value="" readonly="readonly" size="32" /> 
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Customer Id:</td>
      <td><input type="text" name="customer_id" value="<?php echo $_GET['customer_id'];?>" readonly="readonly" size="32" /></td>
    </tr>
    
    
    <tr valign="top">	
      <td nowrap="nowrap" align="right">Computer Information:</td>
      <td><span id="sprytextarea1">
        <textarea name="computer_information" cols="30" rows="5"></textarea>
      <span class="textareaRequiredMsg">A value is required.</span></span></td>
    </tr>
    
    
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Operating System:</td>
      <td>
      
      <?php 
		mysql_select_db($database_connection, $connection);
		$query_Recordset3 = "SELECT * FROM os";
		$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_error());
		$row_Recordset3 = mysql_fetch_assoc($Recordset3);
		$totalRows_Recordset3 = mysql_num_rows($Recordset3);
	  ?>
      <select name="os">
      <option value="">Select</option>
      <?php do { ?>
      	<option value="<?php echo $row_Recordset3['os']?>"><?php echo $row_Recordset3['os']?></option>
      <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
      </select></td>
    </tr> 
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" name="add_multiple" id="add_multiple" value="Add More" /> &nbsp;&nbsp;&nbsp;<input type="submit" name="insert_record" value="Next" /></td>
    </tr>
  </table>
  
  <input type="hidden" name="info_id" value="<?php echo $_GET['info_id'];?>" />
<?php
	$customer_id = mysql_real_escape_string($_GET['customer_id']);
	mysql_select_db($database_connection, $connection);
	$query_Recordset2 = "SELECT payment_id FROM payments WHERE `customer_id` = '$customer_id' GROUP BY payment_id ORDER BY id DESC";
	$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_error());
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	$totalRows_Recordset2 = mysql_num_rows($Recordset2);
	if ($totalRows_Recordset2 > 0) {
		$paymentId = $row_Recordset2['payment_id'];
	} 
?>
  <input type="hidden" name="payment_id" value="<?php echo $paymentId;?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "date", {format:"yyyy-mm-dd"});

function myValidation(value) {
  return /^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/.test(value)
}
//-->
</script>
<?php
	$customer_id = mysql_real_escape_string($_GET['customer_id']);
	mysql_select_db($database_connection, $connection);
	$query_Recordset1 = "SELECT T3.payment_id AS paymentId, T2.plan AS p, T1.* FROM `computer_details` T1 INNER JOIN payments T3 ON T1.id = T3.computer_details_id LEFT JOIN item T2 ON T1.plan = T2.item_id WHERE T1.`customer_id` = '$customer_id'";
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php if ($totalRows_Recordset1 > 0) { ?>
<table border="1" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td>Plan</td>
    <td>Amount</td>
    <td>Activation date</td>
    <td>Upto</td>
    <td>Customer Id</td>
    <td>Computer Information</td>
    <td>Os</td>
    <td>Payment Id</td>
    <td>Issue</td>
    <td>Delete</td>
  </tr>
  <?php if ($totalRows_Recordset1 > 0)  {
  do { ?>
    <tr>
      <td><?php echo $row_Recordset1['p']; ?></td>
      <td><?php echo $row_Recordset1['amount']; ?></td>
      <td><?php echo $row_Recordset1['activation_date']; ?></td>
      <td><?php echo $row_Recordset1['valid_upto']; ?></td>
      <td><?php echo $row_Recordset1['customer_id']; ?></td>
      <td><?php echo $row_Recordset1['computer_information']; ?></td>
      <td><?php echo $row_Recordset1['os']; ?></td>
      <td><?php echo $row_Recordset1['paymentId']; ?></td>
      <td><a class='iframe' href="issue_info_sale.php?customer_id=<?php echo $row_Recordset1['customer_id']; ?>&computer_id=<?php echo $row_Recordset1['id']; ?>&nosale=no">Add Issue</a></td>
      <td><a href="computer_details.php?info_id=<?php echo $row_Recordset1['info_id']; ?>&customer_id=<?php echo $row_Recordset1['customer_id']; ?>&delId=<?php echo $row_Recordset1['id']; ?>">Delete</a></td>
    </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
    <?php } else { ?>
        <tr>
      <td colspan="9" align="center"><span style="color:#F00;">No Records Found!</span></td>
    </tr>
   <?php } ?>
</table>
<?php } ?>
<script type="text/javascript">
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {validateOn:["blur"]});
</script>
<?php require_once('footer.php');?>
