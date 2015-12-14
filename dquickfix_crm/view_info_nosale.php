<?php //require_once('header.php');?>
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
if (isset($_GET['recordID'])) {
  $colname_Recordset1 = $_GET['recordID'];
}
mysql_select_db($database_connection, $connection);
$query_Recordset1 = "SELECT T2 . * , DATE_FORMAT( DATE_ADD( T2.`date` , INTERVAL 7
DAY ) , '%Y-%m-%d' ) AS InsertDate
FROM issue_info AS T2
LEFT JOIN customer_details AS T1 ON T2.customer_id = T1.id WHERE T1.id = '$colname_Recordset1' ORDER BY T2.id ASC";
$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

?>
<link rel="stylesheet" type="text/css" href="css/colorbox.css"/>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="SpryAssets/jquery.colorbox.js"></script>
<script>
	$(document).ready(function(){
		$(".iframe1").colorbox({iframe:true, width:"60%", height:"80%"});
	});
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#btnClose").click(function () {
            parent.jQuery.colorbox.close();
            return false;
        });
    });
</script>

<h1 align="center">Issue Information</h1>
<!--<div align="right"><a href="issue_info_view.php?recordID=<?php echo $_GET['recordID'];?>&customer_id=<?php echo $_GET['customer_id'];?>">New</a>&nbsp;&nbsp;--><input type="button" name="btnClose" id="btnClose" class="btn" value="Close" /></div>
<?php if ($totalRows_Recordset1 > 0) { ?>
<?php $c=1; do { ?>
  <div align="center">
    <fieldset style="width:600px;">
      <legend style="color:#900;"><strong>Case - <?php echo $c++;?> </strong></legend>
      <table width="100%" align="left" border="0" cellspacing="3" cellpadding="3">
      <tr>
          <td width="21%"><strong>
            <label>Issue Id:</label>
            </strong></td>
          <td width="79%"><?php echo $row_Recordset1['issueId']; ?></td>
        </tr>
        <tr>
          <td width="21%"><strong>
            <label>Issue :</label>
            </strong></td>
          <td width="79%"><?php echo $row_Recordset1['subject']; ?></td>
        </tr>
        <tr>
          <td><strong>
            <label>Case Notes :</label>
            </strong></td>
          <td><?php echo $row_Recordset1['notes']; ?></td>
        </tr>
        <tr>
          <td><strong>
            <label>Status :</label>
            </strong></td>
          <td><?php echo $row_Recordset1['status']; ?></td>
        </tr>
        <tr>
          <td><?php
		  			$expire_time = strtotime($row_Recordset1['InsertDate']); 
					$today_time  = strtotime("now"); 
			  ?></td>
          <td><?php
		  if ($expire_time >= $today_time) {
		  ?>
            <!--<a href="edit_info.php?recordID1=<?php echo $_GET['recordID']; ?>&recordID=<?php echo $row_Recordset1['id']; ?>&page=view">Reopen</a>
            <?php } ?> <a href="view_history.php?recordID1=<?php echo $_GET['recordID']; ?>&recordID=<?php echo $row_Recordset1['id']; ?>&page=view">View</a>-->
        </tr>
      </table>
    </fieldset>
  </div>
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));?>
<?php } ?>
<br />
<?php //require_once('footer.php');?>
<?php
mysql_free_result($Recordset1);
?>
