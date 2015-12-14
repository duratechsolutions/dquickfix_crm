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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="SpryAssets/jquery.colorbox.js"></script>

<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<h1 align="center">History of Issue Information of Customer Id : <?=$_GET['customer_id']?></h1>
<h3 align="center" style="color:#090;"><?=$msg?></h3>
<div align="right" style="padding:10px;"><input type="button" value="Back" onclick="history.back(-1)" /></div>
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
