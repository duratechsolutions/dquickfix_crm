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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset1 = 10;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$query = '';
if ((isset($_REQUEST['customer_id'])) && ($_REQUEST['customer_id'] != '')) {
  $query = " AND T1.id LIKE '%$_REQUEST[customer_id]%'";
  $url = "&id=$_REQUEST[customer_id]";
}
if ((isset($_REQUEST['first_name'])) && ($_REQUEST['first_name'] != '')) {
  $query .= " AND T1.first_name LIKE '%$_REQUEST[first_name]%'";
  $url .= "&first_name=$_REQUEST[first_name]";
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
$query_Recordset1 = "SELECT T1.*, T2.*, T1.id AS custID FROM customer_details AS T1 LEFT JOIN issue_info T2 ON T1.id = T2.customer_id WHERE 1=1 $query";
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


<h1 align="center">Search Customer</h1>
<form action="" method="post" name="form1">

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
    <td><span id="sprytextfield1">
    <input type="text" name="phone" id="phone_no" />
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
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
    <td>First Name</td>
    <td>Last Name</td>
    <td>Email</td>
    <td>Phone</td>
    <td>Status</td>
    <td>Edit</td>
    <td>Add</td>
    <td>View</td>
  </tr>
  <?php if ($totalRows_Recordset1 > 0)  {
  do { ?>
    <tr>
      <td><?php echo $row_Recordset1['custID']; ?></td>
      <td><?php echo $row_Recordset1['first_name']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['last_name']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['email']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['phone']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['status']; ?>&nbsp; </td>
      <td><a href="edit_customer.php?recordID=<?php echo $row_Recordset1['custID']; ?><?php echo $url;?>">Edit</a></td>
      <td><a href="issue_info.php?customer_id=<?php echo $row_Recordset1['custID']; ?><?php echo $url;?>">Add</a></td>
      <td><a href="view_details.php?customer_id=<?php echo $row_Recordset1['custID']; ?><?php echo $url;?>">View</a></td>
    </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
    <?php } else { ?>
        <tr>
      <td colspan="9" align="center"><span style="color:#F00;">No Records Found!</span></td>
    </tr>
   <?php } ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_Recordset1 + 1) ?> to <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> of <?php echo $totalRows_Recordset1 ?>
<?php } ?>
<?php require_once('footer.php');?>
<?php
mysql_free_result($Recordset1);
?>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {validateOn:["blur"], isRequired:false});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email", {validateOn:["blur"], isRequired:false});
//-->
</script>
