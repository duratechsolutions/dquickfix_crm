<?php require_once('header.php');?>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
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

$currentPage = "search_customer.php";

$maxRows_Recordset1 = 25;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

if ((isset($_REQUEST['computer_id'])) && ($_REQUEST['computer_id'] != '')) {
	
		if ($_REQUEST['status'] == '2') {
			$status = 'inactive';
		} else {
			$status = 'active';
		}
		
		$updateSQL = sprintf("UPDATE computer_details SET status=%s WHERE id=%s",
						   GetSQLValueString($status, "text"),
						   GetSQLValueString($_REQUEST['computer_id'], "int"));
		
		mysql_select_db($database_connection, $connection);
		$Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());


}


$query = '';
$url = '';
if ((isset($_REQUEST['customer_id'])) && ($_REQUEST['customer_id'] != '')) {
  $query = " AND T1.id = '$_REQUEST[customer_id]'";
  $url = "&customer_id=$_REQUEST[customer_id]";
}

if ((isset($_REQUEST['first_name'])) && ($_REQUEST['first_name'] != '')) {
  $query .= " AND T1.first_name LIKE '%$_REQUEST[first_name]%'";
  $url = "&first_name=$_REQUEST[first_name]";
}
if ((isset($_REQUEST['last_name'])) && ($_REQUEST['last_name'] != '')) {
  $query .= " AND T1.last_name LIKE '%$_REQUEST[last_name]%'";
  $url .= "&last_name=$_REQUEST[last_name]";
}
if ((isset($_REQUEST['phone'])) && ($_REQUEST['phone'] != '')) {
  $query .= " AND T1.phone LIKE '%$_REQUEST[phone]%'";
  $url .= "&phone=$_REQUEST[phone]";
}
if ((isset($_REQUEST['email'])) && ($_REQUEST['email'] != '')) {
  $query .= " AND T1.email LIKE '%$_REQUEST[email]%'";
  $url .= "&email=$_REQUEST[email]";
}
	
mysql_select_db($database_connection, $connection);

$query_Recordset1 = "SELECT T3.payment_id, T1.id AS custID, T1 . * , T2.*, T2.id AS compId, T2.status AS cStatus, T4.plan, CONCAT_WS( '', T1.first_name, ' ', T1.last_name ) AS cName, T1.email, T3.payment_status
FROM customer_details AS T1
LEFT JOIN computer_details T2 ON ( T1.id = T2.customer_id )
LEFT JOIN payments T3 ON T2.id = T3.computer_details_id
LEFT JOIN item T4 ON T2.plan = T4.item_id WHERE 1=1 $query ";


//$query_Recordset1 = "SELECT T4.*, T4.id AS compId, T4.status AS cStatus, T5.plan, T3.payment_status, T1.*, T2.*, T1.id AS custID FROM computer_details T4 INNER JOIN customer_details AS T1 ON T4.customer_id = T1.id LEFT JOIN issue_info T2 ON T4.id = T2.computer_details_id LEFT JOIN payments T3 ON T1.id = T3.customer_id LEFT JOIN item T5 ON T5.item_id = T4.plan WHERE 1=1 $query GROUP BY T4.id";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $connection) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
?>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="SpryAssets/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="css/colorbox.css"/>
<script>
	$.noConflict();
	jQuery(document).ready(function(){
		jQuery(".iframe").colorbox({iframe:true, width:"60%", height:"90%"});
	});

</script>

<h2 align="center">Search Customer</h2>
<?php  echo $_SESSION['MM_Username'];  ?>
<form action="search_customer.php" method="post" name="form1">

<table width="50%" align="center" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="26%"><div align="right">Customer Id</div></td>
    <td width="2%">&nbsp;</td>
    <td width="72%"><input type="text" name="customer_id" id="account_id" /></td>
  </tr>
  <tr>
    <td><div align="right">First name</div></td>
    <td>&nbsp;</td>
    <td><input type="text" name="first_name" id="first_name" /></td>
  </tr>
  <tr>
    <td><div align="right">Last name</div></td>
    <td>&nbsp;</td>
    <td><input type="text" name="last_name" id="last_name" /></td>
  </tr>
  <tr>
    <td><div align="right">Phone no</div></td>
    <td>&nbsp;</td>
    <td>
    <input type="text" name="phone" id="phone_no" />
<span class="textfieldInvalidFormatMsg">Invalid format.</span></td>
  </tr>
  <tr>
    <td><div align="right">Email</div></td>
    <td>&nbsp;</td>
    <td><span id="sprytextfield2">
    <input type="text" name="email" id="email" />
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
  </tr>
  <tr>
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
    <td><input type="submit" name="Search" id="Search" value="Search" /></td>
  </tr>
</table>



</form>

<?php if ($query != '') { ?>

<table border="1" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td>Customer Id</td>
    <td>Customer Name</td>
    <td>Email</td>
    <td>Plan</td>
    <td>Active<br />Date</td>
    <td>Upto Date</td>
    <td>Edit</td>
    <td>Add</td>
    <td>View</td>
    <td>Payment<br /> Status</td>
    <td>Payment Id</td>
    <td>Status</td>
  </tr>
  <?php if ($totalRows_Recordset1 > 0)  {
  do { ?>
    <tr>
      <td><a href="edit_customer.php?recordID=<?php echo $row_Recordset1['custID']; ?><?php echo $url;?>"><?php echo $row_Recordset1['custID']; ?></a></td>
      <td><?php echo $row_Recordset1['cName']; ?></td>
      
      <td><?php echo $row_Recordset1['email']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['plan']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['activation_date']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['valid_upto']; ?>&nbsp; </td>
      <td><a href="edit_customer.php?recordID=<?php echo $row_Recordset1['custID']; ?><?php echo $url;?>">Edit</a></td>
      
      <td><?php if ($row_Recordset1['payment_status'] == "paid") { ?><a href="issue_info.php?customer_id=<?php echo $row_Recordset1['custID']; ?><?php echo $url;?>">Add</a><?php } else { ?>Add <?php } ?></td>
	  <?php if ($row_Recordset1['compId'] == "") { ?>
      <td><a class='iframe' href="view_info_nosale.php?recordID=<?php echo $row_Recordset1['custID']; ?>&customer_id=<?php echo $row_Recordset1['custID']; ?>">View Issue</a></td>
      <?php } else { ?>
      <td><a href="view_details.php?customer_id=<?php echo $row_Recordset1['custID']; ?><?php echo $url;?>">View</a></td><?php } ?>
      <td><?php echo $row_Recordset1['payment_status'];?></td>
      <?php if ($row_Recordset1['cStatus'] == "active") { $mode = '2'; } else { $mode = '1'; } ?>
      <td><?php echo $row_Recordset1['payment_id'];?></td>
      <?php if ((isset($_SESSION['MM_Level'])) && ($_SESSION['MM_Level'] == '1')) { ?>	
      <td><a href="search_customer.php?computer_id=<?php echo $row_Recordset1['compId']; ?><?php echo $url;?>&status=<?=$mode?>"><?php if ($row_Recordset1['cStatus'] == "active") { ?>active<?php } else { ?>inactive<?php } ?></a></td>
      <?php } else { ?> 
      	<td><?php if ($row_Recordset1['cStatus'] == "active") { ?>active<?php } else { ?>inactive<?php } ?></td>
      <?php } ?>
    </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
    <?php } else { ?>
        <tr>
      <td colspan="9" align="center"><span style="color:#F00;">No Records Found!</span></td>
    </tr>
   <?php } ?>
</table>
<br />
<table border="0" cellpadding="5" align="center">
  <tr>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("search_customer.php?pageNum_Recordset1=%d%s$url", $currentPage, 0, $queryString_Recordset1); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("search_customer.php?pageNum_Recordset1=%d%s$url", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("search_customer.php?pageNum_Recordset1=%d%s$url", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("search_customer.php?pageNum_Recordset1=%d%s$url", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
<div align="center">
Records <?php echo ($startRow_Recordset1 + 1) ?> to <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> of <?php echo $totalRows_Recordset1 ?>
</div>
<?php } ?>

<?php require_once('footer.php');?>
<?php
mysql_free_result($Recordset1);
?>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {validateOn:["blur"], isRequired:false});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email", {validateOn:["blur"], isRequired:false});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "phone_number", {validateOn:["blur"], format:"phone_custom", pattern:"000-000-0000", hint:"000-000-0000", useCharacterMasking:true});
//-->
</script>
