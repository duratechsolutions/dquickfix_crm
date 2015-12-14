<?php //require_once('header.php');?>
<?php ob_start(); require_once('Connections/connection.php'); $issueId = date('Ymdsim'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  
    $insertSQL = sprintf("INSERT INTO issue_info (subject, notes, status, customer_id, no_sale, issueId, created_by, date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['subject'], "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['customer_id'], "int"),
					   GetSQLValueString($_POST['noSale'], "text"),
					   GetSQLValueString($_POST['issueId'], "text"),
					   GetSQLValueString($_SESSION['UserId'], "int"),
                       GetSQLValueString(date("Y-m-d H:i:s"), "datetime"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());

  
  
  $info_id = mysql_insert_id();
  
  $insertSQL = sprintf("INSERT INTO history_issue_info (info_id, subject, notes, status, customer_id, created_by) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($info_id, "int"),
					   GetSQLValueString($_POST['subject'], "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['customer_id'], "int"),
                       GetSQLValueString($_SESSION['UserId'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());
  
  $id = mysql_insert_id();
	  $insertGoTo = "view_info.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $insertGoTo));
  
  
}
?>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
$(document).ready(function(){
	/*$("#status").change(function() {
		var thisOption = $("#status").val();
	
		if(thisOption == 'Open' || thisOption == 'Refund') {
			$(":submit").attr('disabled', false ); 
			$("#save_button").html('');
		} else {
			$(":submit").attr("disabled", true);
			$("#save_button").html('<input type="submit" value="Save" name="save" id="save" />');
		}
	});*/
});
</script>
<h1 align="center">Issue Information of Customer Id : <?=$_GET['customer_id']?> Issue Id : <?php echo $issueId;?></h1>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Issue</td>
      <td><span id="sprytextfield1">
        <input type="text" name="subject" value="" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Case Notes</td>
      <td><span id="sprytextarea1">
        <textarea name="notes" id="notes" cols="45" rows="5"></textarea>
      <span class="textareaRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Status</td>
      <td><span id="spryselect1">
        <select name="status" id="status">
          <option>Select</option>
          <option value="Open" selected="selected">Open</option>
          <option value="Refund">Refund</option>
          <option value="No Sale">No Sale</option>
          <option value="Resolved">Resolved</option>
          <option value="Charge back">Charge back</option>
          <option value="Unresolved Customer had to go">Unresolved Customer had to go</option>
		  <option value="Unresolved get to do something">Unresolved get to do something</option>
          <option value="Unresolved out of scope">Unresolved out of scope</option>
          <option value="Unresolved disconnected">Unresolved disconnected</option>
          <option value="Open Escalating">Escalating</option>                                               
        </select>
      <span class="selectRequiredMsg">Please select an item.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" name="submit" id="submit" value="Save" />&nbsp;&nbsp;<span id="save_button"><input type="button" value="Back" onclick="history.back(-1)" /></span></td>
    </tr>
  </table>
    <?php if ((isset($_GET["nosale"])) && ($_GET["nosale"] == "yes")) {
	  $sale = 'yes';
  } else { $sale = 'no'; } ?>
  <input type="hidden" name="issueId" value="<?php echo $issueId;?>" />
  <input type="hidden" name="noSale" value="<?php echo $sale;?>" />

  <input type="hidden" name="customer_id" value="<?php echo $_GET['customer_id'];?>" />
  <input type="hidden" name="recordID" value="<?php echo $_GET['recordID'];?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {validateOn:["blur"]});
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
//-->
</script>
<?php //require_once('footer.php');?>
