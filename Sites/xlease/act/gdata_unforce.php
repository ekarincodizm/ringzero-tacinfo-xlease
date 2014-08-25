<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = trim(pg_escape_string($_GET["q"]));

$qry_dc=pg_query("select \"TempInsID\", \"InsUFIDNO\", \"full_name\" from insure.\"VInsUnforceDetail\"
                     WHERE (\"TempInsID\" like '%$q%' or \"InsUFIDNO\" like '%$q%' or \"full_name\" like '%$q%') ORDER BY \"InsUFIDNO\" ASC LIMIT 100
                     ");
while($res_dc=pg_fetch_array($qry_dc)){
    $TempInsID = $res_dc["TempInsID"]; if(empty($TempInsID)) $TempInsID = "ไม่มีเลขรับแจ้ง";
    $InsUFIDNO = $res_dc["InsUFIDNO"]; if(empty($InsUFIDNO)) $InsUFIDNO = "ไม่มีรหัสประกัน";
    $full_name = $res_dc["full_name"]; if(empty($full_name)) $full_name = "ไม่พบชื่อ";
    $full_name = str_replace("'", "'", $full_name);
        echo "<li onselect=\"this.setText('$TempInsID : $InsUFIDNO - $full_name').setValue('$InsUFIDNO'); \">$TempInsID : $InsUFIDNO - $full_name</li>";
}
?>