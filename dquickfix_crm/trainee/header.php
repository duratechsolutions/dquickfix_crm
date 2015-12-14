<?php require_once('Connections/connection.php'); ?>
<?php
error_reporting(0);
ob_start();

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}
require_once('access_denied.php');
require_once('logout.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DQuickFix-CRM</title>
<script type="text/javascript" src="SpryAssets/jquery-1.2.6.min.js"></script>
<script type="text/javascript" language="javascript">
function DisableBackButton() {
window.history.forward()
}
DisableBackButton();
window.onload = DisableBackButton;
window.onpageshow = function(evt) { if (evt.persisted) DisableBackButton() }
window.onunload = function() { void (0) }
</script>
<script>
if (typeof window.event != 'undefined') {
  document.onkeydown = function() {
    var t = event.srcElement.type;
    var kc = event.keyCode;
    return ((kc != 8 && kc != 13 && kc != 27) || (t == 'text' && kc != 13 && kc != 27) ||
            (t == 'textarea' && kc != 27) || (t == 'button' && kc == 13) || (t == 'submit' && kc == 13) ||
            (t == 'password' && kc != 27 && kc != 13) || (t == '' && kc == 13));
    }
} else {
  document.onkeypress = function(e) {
    var t = e.target.type;
    var kc = e.keyCode;
    if ((kc != 8 && kc != 13 && kc != 27) || (t == 'text' && kc != 13 && kc != 27) ||
       (t == 'textarea' && kc != 27) || (t == 'button' && kc == 13) || (t == 'submit' && kc == 13) ||
       (t == 'password' && kc != 27 && kc != 13) || (t == '' && kc == 13)) {
        return true;
    } else {
        return false;
    }
  }
}
       function preventBack(){window.history.forward();}
        setTimeout("preventBack()", 10);
        window.onunload=function(){null};
</script>
<script type="text/javascript">
<!--
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
<script>
function open_win() {
	window.open("http://dqf.showmypc.com/host.html")
}
function open_win_minor() {
	window.open("http://tool.dquickfix.net","MsgWindow","width=500,height=500");
}
function open_win_remote() {
	window.open("http://tech.dquickfix.net","MsgWindow","width=500,height=500");
}
</script>
</head>
<body style="background-color:#FFFFFF;">
	<img src="images/head.jpg" align="middle" alt="DQuickFix"" />

<div align="center">
  <?php if ((isset($_SESSION['MM_Level'])) && ($_SESSION['MM_Level'] == '1')) { ?>	
  <input name="new_user" type="button" onclick="MM_goToURL('parent','create_user.php');return document.MM_returnValue" value="New Agent" /> 
  <input name="payment_details" type="button" onclick="MM_goToURL('parent','payment_details.php');return document.MM_returnValue" value="Payment" />
  <input name="ip_manager" type="button" onclick="MM_goToURL('parent','ip_manager.php');return document.MM_returnValue" value="Ip manager" />
  <?php } ?>
  <input name="new_customer" type="button" onclick="MM_goToURL('parent','customer_details.php');return document.MM_returnValue" value="New Customer" />
  <input name="my_inbox" type="button" onclick="MM_goToURL('parent','my_inbox.php');return document.MM_returnValue" value="My Inbox" />
  <input name="search_customer" type="button" onclick="MM_goToURL('parent','search_customer.php');return document.MM_returnValue" value="Search Customer" />
  
  <input name="new_customer" type="button" onclick="MM_goToURL('parent','important.php');return document.MM_returnValue" value="Important Numbers" />
  <input name="search_customer" type="button" onclick="MM_goToURL('parent','thinks.php');return document.MM_returnValue" value="Things to Remember" />
  <button type="button" data-toggle="modal" data-target="#<?php echo $msg_id=$data['msg_id'];?>" title="Comment" onClick="open_win_minor();">Tool Minor</button>
  
 <button type="button" data-toggle="modal" data-target="#<?php echo $msg_id=$data['msg_id'];?>" title="Comment" onClick="open_win_remote();">Remote</button>
  
  
  
 
  
  
   
   
  <?php if ((!isset($_SESSION['UserId'])) && ($_SESSION['UserId'] == '')) { ?>
  <input name="login" type="button" onclick="MM_goToURL('parent','login.php');return document.MM_returnValue" value="LogIn" />
  <?php } else { ?>
  <a href="<?php echo $logoutAction ?>" style="text-decoration:none;"><input name="logut" type="button" value="Logout" /></a>
  <?php } ?>
  
</div>
