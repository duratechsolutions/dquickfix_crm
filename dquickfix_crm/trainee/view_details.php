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

$colname_Recordset1 = "-1";
if (isset($_GET['customer_id'])) {
  $colname_Recordset1 = $_GET['customer_id'];
}
mysql_select_db($database_connection, $connection);
$query_Recordset1 = sprintf("SELECT T1.*, T2.plan AS PlanDetails, T3.payment_status FROM computer_details AS T1 LEFT JOIN item AS T2 ON T1.plan = T2.item_id INNER JOIN payments T3 ON T1.id = T3.computer_details_id WHERE T1.customer_id = %s ORDER BY T1.id ASC", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);



?><script type="text/javascript" src="js/jquery.min.js"></script>
<script src="SpryAssets/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="css/colorbox.css"/>

<script>
	$.noConflict();
	jQuery(document).ready(function(){
		jQuery(".iframe").colorbox({iframe:true, width:"60%", height:"90%"});
	});
</script>

<h1 align="center">View Details</h1>
<?php $c = 1; do { ?>
<?php
$payment_id = "";
if ($row_Recordset1['upgrade'] == 'Y') { 
	$payment_id = $row_Recordset1['upgrade_pay_id'];
	
} else {
	mysql_select_db($database_connection, $connection);
	$query_Recordset2 = "SELECT * FROM payments WHERE computer_details_id = '$row_Recordset1[id]' AND plan = '$row_Recordset1[plan]'";
	$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_error());
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	$totalRows_Recordset2 = mysql_num_rows($Recordset2);
	$payment_id = $row_Recordset2['id'];
}
?>
<div align="center">
    <fieldset style="width:700px;">
      <legend style="color:#900;">Computer Details - <?=$c;?></legend>
      <table width="60%" align="left" border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td width="44%"><label>Plan :</label></td>
          <td width="56%"><?php echo $row_Recordset1['PlanDetails']; ?> <?php if ($row_Recordset1['payment_status'] == 'paid') { ?> <a href="upgrade.php?recordID=<?=$row_Recordset1['id']?>&customer_id=<?=$_GET['customer_id']?>">Upgrade</a> <?php } ?> <!--| <a href="upgrade.php?recordID=<?=$row_Recordset1['id']?>&customer_id=<?=$_GET['customer_id']?>">New</a>--></td>
        </tr>
        <tr>
          <td>Payment Id :</td>
          <td><?php echo $payment_id;?></td>
        </tr>
        <tr>
          <td><label>Amount :</label></td>
          <td><?php echo $row_Recordset1['amount']; ?></td>
        </tr>
        <tr>
          <td><label>Valid upto :</label></td>
          <td><?php echo $row_Recordset1['valid_upto']; ?></td>
        </tr>
        <tr>
          <td><label>Stating Date :</label></td>
          <td><?php echo $row_Recordset1['date']; ?></td>
        </tr>
        <tr>
          <td><label>Mac ID :</label></td>
          <td><?php echo $row_Recordset1['mac_id']; ?></td>
        </tr>
        <tr>
          <td><label>OS :</label></td>
          <td><?php echo $row_Recordset1['os']; ?></td>
        </tr>
        <tr>
          <td><label>Mark and mode :</label></td>
          <td><?php echo $row_Recordset2['mark_and_mode']; ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><a class='iframe' href="view_info.php?recordID=<?php echo $row_Recordset1['id']; ?>&customer_id=<?php echo $row_Recordset1['customer_id']; ?>">View</a>
        </tr>
      </table>
    </fieldset>
    </div>
  <?php $c++; } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));?>
  
<br />
<?php require_once('footer.php');?>
<?php
mysql_free_result($Recordset1);
?>
