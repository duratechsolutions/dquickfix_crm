<?php

function buildNavigation($page, $totalPages_Recordset1, $prev_Recordset1, $next_Recordset1, $separator = " | ", $max_links = 10, $show_page = true,$url, $URL_String='')
{
    GLOBAL $maxRows_Recordset1, $totalRows_Recordset1;
    $pagesArray = "";
    $firstArray = "";
    $lastArray  = "";
    if ($max_links < 2)
        $max_links = 2;
    if ($page <= $totalPages_Recordset1 && $page >= 0) {
        if ($page > ceil($max_links / 2)) {
            $fgp = $page - ceil($max_links / 2) > 0 ? $page - ceil($max_links / 2) : 1;
            $egp = $page + ceil($max_links / 2);
            if ($egp >= $totalPages_Recordset1) {
                $egp = $totalPages_Recordset1 + 1;
                $fgp = $totalPages_Recordset1 - ($max_links - 1) > 0 ? $totalPages_Recordset1 - ($max_links - 1) : 1;
            }
        } else {
            $fgp = 0;
            $egp = $totalPages_Recordset1 >= $max_links ? $max_links : $totalPages_Recordset1 + 1;
        }
        if ($totalPages_Recordset1 >= 1) {
            #	------------------------
            #	Searching for $_GET vars
            #	------------------------
            $_get_vars = $URL_String;	
            if (!empty($_GET) || !empty($HTTP_GET_VARS)) {
                $_GET = empty($_GET) ? $HTTP_GET_VARS : $_GET;
                foreach ($_GET as $_get_name => $_get_value) {
                    if ($_get_name != "page") {
                        //$_get_vars .= "&$_get_name=$_get_value";
                    }
                }
            }
            $successivo = $page + 1;
            $precedente = $page - 1;
            $firstArray = ($page > 0) ? "<li><a class=\"active\" href=\"$url?page=$precedente$_get_vars\">$prev_Recordset1</a></li>" : "<li><a href=\"JAVASCRIPT:void(0);\">$prev_Recordset1</a></li>";
            # ----------------------
            # page numbers
            # ----------------------
            for ($a = $fgp + 1; $a <= $egp; $a++) {
                $theNext = $a - 1;
                if ($show_page) {
                    $textLink = $a;
                } else {
                    $min_l    = (($a - 1) * $maxRows_Recordset1) + 1;
                    $max_l    = ($a * $maxRows_Recordset1 >= $totalRows_Recordset1) ? $totalRows_Recordset1 : ($a * $maxRows_Recordset1);
                    $textLink = "$min_l - $max_l";
                }
                $_ss_k = floor($theNext / 26);
                if ($theNext != $page) {
                    $pagesArray .= "<li><a href=\"$url?page=$theNext$_get_vars\">";
                    $pagesArray .= "$textLink</a></li>" . ($theNext < $egp - 1 ? $separator : "");
                } else {
                    $pagesArray .= "<li class=\"active\"><a href=\"JAVASCRIPT:void(0);\">$textLink</a></li>" . ($theNext < $egp - 1 ? $separator : "");
                }
            }
            $theNext    = $page + 1;
            $offset_end = $totalPages_Recordset1;
            $lastArray  = ($page < $totalPages_Recordset1) ? "<li><a href=\"$url?page=$successivo$_get_vars\">$next_Recordset1</a></li>" : "<li><a href=\"JAVASCRIPT:void(0);\">$next_Recordset1</a></li>";
        }
    }
    return array(
        $firstArray,
        $pagesArray,
        $lastArray
    );
}

?>