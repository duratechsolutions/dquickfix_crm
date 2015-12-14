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

if ((isset($_POST['mode'])) && ($_POST['mode'] == 1)) {
	$colname_Recordset1 = "-1";
	if (isset($_POST['plan_id'])) {
	  $colname_Recordset1 = $_POST['plan_id'];
	}
	mysql_select_db($database_connection, $connection);
	$query_Recordset1 = sprintf("SELECT * FROM item WHERE item_id = %s", 
							GetSQLValueString($colname_Recordset1, "int"));
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	//$val = array();
	if ($totalRows_Recordset1 > 0) {
		$val[] = $row_Recordset1['price'];
		$val[] = date("d-m-Y", mktime(0,0,0,date('m'),date('d')+$row_Recordset1['days'],date('y')));
		echo $v = implode("|", $val);
	}
	
	
}
if ((isset($_POST['mode'])) && ($_POST['mode'] == 2)) {
	$colname_Recordset1 = "-1";
	if (isset($_POST['plan_id'])) {
	  $colname_Recordset1 = $_POST['plan_id'];
	}
	mysql_select_db($database_connection, $connection);
	$query_Recordset1 = sprintf("SELECT * FROM item WHERE item_id = %s", 
							GetSQLValueString($colname_Recordset1, "int"));
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	
	mysql_select_db($database_connection, $connection);
	$query_Recordset2 = "SELECT T2.price, T2.plan FROM computer_details AS T1 LEFT JOIN item AS T2 ON T1.plan = T2.item_id WHERE T1.id = '$_POST[cid]'";
	$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_error());
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	$totalRows_Recordset2 = mysql_num_rows($Recordset2);
	
	if ($_POST['plan_id'] >= 11) {
		$amount = $row_Recordset1['price'] ;
	} else {			
		$amount = number_format($row_Recordset1['price'] - $row_Recordset2['price']);
	}
	//$val = array();
	if ($totalRows_Recordset1 > 0) {
		$val[] = $amount;
		$val[] = date("d-m-Y", mktime(0,0,0,date('m'),date('d')+$row_Recordset1['days'],date('y')));
		echo $v = implode("|", $val);
	}
	
	
}