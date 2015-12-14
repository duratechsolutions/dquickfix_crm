<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
//ini_set('display_errors',1); 
//error_reporting(E_ALL);

if (!isset($_SESSION)) {
  session_start();
}

$hostname_connection = "dqfdbtrainee.db.10503071.hostedresource.com";
$database_connection = "dqfdbtrainee";
$username_connection = "dqfdbtrainee";
$password_connection = "Dqf@4202045";
$connection = mysql_connect($hostname_connection, $username_connection, $password_connection) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
