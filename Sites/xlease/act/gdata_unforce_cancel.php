<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = pg_escape_string($_GET["q"]);
/*
$qry_dc=pg_query("select A.*,B.* from \"insure\".\"InsureUnforce\" A 
                     LEFT OUTER JOIN insure.\"VInsUnforceDetail\" B ON A.\"IDNO\"=B.\"IDNO\"
                     WHERE (\"InsUFIDNO\" like '%$q%' OR \"InsID\" like '%$q%' OR \"TempInsID\" like '%$q%') AND \"Cancel\" = 'FALSE'
                     ORDER BY \"InsID\",\"InsUFIDNO\" ASC 
                     ");*/
$qry_dc=pg_query("select * from \"insure\".\"InsureUnforce\"
                     WHERE (\"InsUFIDNO\" like '%$q%' OR \"InsID\" like '%$q%' OR \"TempInsID\" like '%$q%') AND \"Cancel\" = 'FALSE'
                     ORDER BY \"InsID\",\"InsUFIDNO\" ASC LIMIT 100 
                     ");
while($res_dc=pg_fetch_array($qry_dc)){
    $InsUFIDNO = $res_dc["InsUFIDNO"];
    $InsID = $res_dc["InsID"];
    $TempInsID = $res_dc["TempInsID"];
    //$full_name = $res_dc["full_name"];
        //$full_name = str_replace("'", "'", $full_name);
        $display_InsUFIDNO = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $InsUFIDNO);
        $display_InsID = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $InsID);
        $display_TempInsID = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $TempInsID);
        echo "<li onselect=\"this.setText('$InsID : $InsUFIDNO - $TempInsID').setValue('$InsUFIDNO'); \">$display_InsID : $display_InsUFIDNO - $display_TempInsID</li>";
}
?>