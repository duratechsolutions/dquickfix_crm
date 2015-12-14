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
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset1 = 25;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysql_select_db($database_connection, $connection);
if ((isset($_SESSION['MM_Level'])) && ($_SESSION['MM_Level'] == '1')) {
	$query_Recordset1 = "SELECT T1.*, T2.*, T1.id AS IssueId, DATE_FORMAT(T1.date, '%d-%m-%Y') AS IssueDate FROM issue_info AS T1 LEFT JOIN customer_details AS T2 ON T1.customer_id = T2.id ORDER BY T1.id DESC";
} else {
	$query_Recordset1 = "SELECT T1.*, T2.*, T1.id AS IssueId, DATE_FORMAT(T1.date, '%d-%m-%Y') AS IssueDate FROM issue_info AS T1 LEFT JOIN customer_details AS T2 ON T1.customer_id = T2.id WHERE created_by = '$_SESSION[UserId]' ORDER BY T1.id DESC";
}
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
<link rel="stylesheet" type="text/css" href="css/colorbox.css"/>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="SpryAssets/jquery.colorbox.js"></script>
<script>
	$(document).ready(function(){
		$(".iframe").colorbox({iframe:true, width:"60%", height:"80%"});
	});
</script>
<h2 align="center">My Inbox</h2>
<br /><br />
<table border="1" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td>Customer Id</td>
    <td>First Name</td>
    <td>Last Name</td>
    <td>Email</td>
    <td>Subject</td>
    <td>Date</td>
    <td>Status</td>
  </tr>
  <?php if ($totalRows_Recordset1 > 0)  {
  do { ?>
    <tr>
      <td><?php echo $row_Recordset1['customer_id']; ?></td>
      <td><?php echo $row_Recordset1['first_name']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['last_name']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['email']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['subject']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['IssueDate']; ?></td>
      <td><a class='iframe' href="edit_info.php?recordID=<?php echo $row_Recordset1['IssueId']; ?>&page=inbox&customer_id=<?php echo $row_Recordset1['customer_id']; ?>"><?php echo $row_Recordset1['status']; ?></a></td>
    </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
    <?php } else { ?>
        <tr>
      <td colspan="10" align="center"><span style="color:#F00;">No Records Found!</span></td>
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
<?php require_once('footer.php');?>
<?php
mysql_free_result($Recordset1);
?>
