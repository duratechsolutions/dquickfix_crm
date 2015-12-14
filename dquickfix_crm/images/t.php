<?php

 if(!empty($_SERVER['HTTP_MOD_REWRITE'])){
     echo "Mod_Rewrite is running";
 } else {
     echo "Mod Rewrite is not running";
 }
 
echo  phpinfo();

?>
