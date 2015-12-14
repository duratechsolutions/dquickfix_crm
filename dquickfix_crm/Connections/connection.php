<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
//ini_set('display_errors',1); 
//error_reporting(E_ALL);

if (!isset($_SESSION)) {
  session_start();
}

$hostname_connection = "localhost";
$database_connection = "dqfdb";
$username_connection = "dqfdb";
$password_connection = "Kay@4202045";
$connection = mysql_connect($hostname_connection, $username_connection, $password_connection) or trigger_error(mysql_error(),E_USER_ERROR); 
?>