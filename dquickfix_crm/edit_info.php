<?php //require_once('header.php');?>
<?php ob_start(); require_once('Connections/connection.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
	
  $insertSQL = sprintf("INSERT INTO history_issue_info (info_id, subject, notes, status, customer_id, created_by) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
					   GetSQLValueString($_POST['subject'], "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['customer_id'], "int"),
                       GetSQLValueString($_SESSION['UserId'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());
	
	
  $updateSQL = sprintf("UPDATE issue_info SET subject=%s, notes=%s, status=%s WHERE id=%s",
                       GetSQLValueString($_POST['subject'], "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());
  $msg = 'Successfully Updated!';
  if ($_POST['page'] == 'inbox') {
	  //$insertGoTo = "my_inbox.php";
	  $msg = 'Successfully Updated!';
  } else {
	  $insertGoTo = "view_details.php?customer_id=".$_POST['customer_id'];
  }
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  //header(sprintf("Location: %s", $insertGoTo));

}


$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_connection, $connection);
$query_DetailRS1 = sprintf("SELECT * FROM issue_info  WHERE id = %s", GetSQLValueString($colname_DetailRS1, "int"));
$DetailRS1 = mysql_query($query_DetailRS1, $connection) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);
?>
<script type="text/javascript">
<!--
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>

<link rel="stylesheet" type="text/css" href="css/colorbox.css"/>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="SpryAssets/jquery.colorbox.js"></script>

<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<h1 align="center">Edit Issue Information of Customer Id : <?=$_GET['customer_id']?></h1>
<?php if ($msg != '') { ?>
<h3 align="center" style="color:#090;"><?=$msg?></h3>
<?php } ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Subject:</td>
      <td><span id="sprytextfield1">
        <input type="text" name="subject" value="<?php echo htmlentities($row_DetailRS1['subject'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Notes:</td>
      <td><span id="sprytextarea1">
        <textarea name="notes" id="notes" cols="45" rows="5"><?php echo htmlentities($row_DetailRS1['notes'], ENT_COMPAT, 'utf-8'); ?></textarea>
      <span class="textareaRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Status:</td>
      <td><span id="spryselect1">
      
        
        <select name="status" id="status">
          <option value="" <?php if (!(strcmp("", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>Select</option>
          <option value="Open" <?php if (!(strcmp("Open", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>Open</option>
          <option value="Refund" <?php if (!(strcmp("Refund", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>Refund</option>
          <option value="No Sale" <?php if (!(strcmp("No Sale", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>No Sale</option>
          <option value="Resolved" <?php if (!(strcmp("Resolved", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>Resolved</option>
          <option value="Charge back" <?php if (!(strcmp("Charge back", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>Charge back</option>
          <option value="Unresolved Customer had to go" <?php if (!(strcmp("Unresolved Customer had to go", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>Unresolved Customer had to go</option>
          <option value="Unresolved get to do something" <?php if (!(strcmp("Unresolved get to do something", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>Unresolved get to do something</option>
          <option value="Unresolved out of scope" <?php if (!(strcmp("Unresolved out of scope", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>Unresolved out of scope</option>
          <option value="Unresolved disconnected" <?php if (!(strcmp("Unresolved disconnected", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>Unresolved disconnected</option>
          <option value="Open Escalating" <?php if (!(strcmp("Open Escalating", $row_DetailRS1['status']))) {echo "selected=\"selected\"";} ?>>Escalating</option>                                               
        </select>
       <span class="selectRequiredMsg">Please select an item.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <?php 
	  	if ($_GET['page'] == 'inbox') { 
			$url = 'my_inbox.php'; 
		} if ($_GET['page'] == 'view') { 
			$url = "view_info.php?recordID=$_GET[recordID1]&customer_id=$row_DetailRS1[customer_id]"; 
		} else { 
			$url = "view_details.php?customer_id=$row_DetailRS1[customer_id]"; 
		} 
	  ?>
      <td><input type="submit" value="Update" />
      <!--<input name="new_user" type="button" onclick="MM_goToURL('parent','<?=$url?>');return document.MM_returnValue" value="Back" />--><input type="button" name="btnClose" class="btn" value="Close" onclick="parent.jQuery.colorbox.close()" /> <a style="text-decoration:none;" href="<?=$url?>"><input type="button" value="Back" /></a></td>
    </tr>
  </table>
  <input type="hidden" name="customer_id" value="<?php echo $row_DetailRS1['customer_id']; ?>" />
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_DetailRS1['id']; ?>">
  <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>">
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {validateOn:["blur"]});
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
//-->
</script>

<?php
mysql_select_db($database_connection, $connection);
$query_Recordset1 = "SELECT T1.*, DATE_FORMAT(T1.`date`, '%d-%m-%Y %H:%i:%s') AS `date`  FROM history_issue_info AS T1 WHERE info_id = '$row_DetailRS1[id]' ORDER BY id DESC";
$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>



<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <th width="18%">Subject</th>
    <th width="49%">Notes</th>
    <th width="20%">Status</th>
    <th width="13%">Date</th>
  </tr>
  <?php do { ?>
  <tr style="font-size:12px;">
    <td><?php echo $row_Recordset1['subject'];?></td>
    <td><?php echo $row_Recordset1['notes'];?></td>
    <td><?php echo $row_Recordset1['status'];?></td>
    <td><?php echo $row_Recordset1['date'];?></td>
  </tr>
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));?>

</table>


<?php //require_once('footer.php');?>
<?php
mysql_free_result($DetailRS1);
?>
