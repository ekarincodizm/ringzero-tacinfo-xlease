<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = trim(pg_escape_string($_GET["q"]));

$qry_cn=pg_query("select \"InsID\", \"full_name\", \"InsFIDNO\" from insure.\"VInsForceDetail\"
                  WHERE (\"InsFIDNO\" like '%$q%') or (\"InsID\" like '%$q%') or (\"full_name\" like '%$q%') ORDER BY \"InsID\" ASC LIMIT 100");
while($res_dc=pg_fetch_array($qry_cn)){
    $InsID = $res_dc["InsID"]; if(empty($InsID)) $InsID = "ไม่มีเลขกรมธรรม์";
    $full_name = $res_dc["full_name"];
    $InsFIDNO = $res_dc["InsFIDNO"]; if(empty($InsFIDNO)) $InsFIDNO = "ไม่มีรหัสประกัน";
		
    echo "<li onselect=\"this.setText('$InsID : $InsFIDNO - $full_name').setValue('$InsFIDNO'); \">$InsID : $InsFIDNO - $full_name</li>";
}
?>