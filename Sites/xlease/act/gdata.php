<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = trim(pg_escape_string($_GET["q"]));

//$qry_cn=pg_query("select \"IDNO\",\"full_name\",\"C_CARNUM\" from insure.\"VInsForceDetail\" WHERE (\"full_name\" like '%$q%' or \"IDNO\" like '%$q%' or \"C_CARNUM\" like '%$q%') AND \"IDNO\" is not null ");

/*
$qry_cn=pg_query("select A.*,B.*,C.* from \"Fc\" A 
LEFT OUTER JOIN \"Fp\" B ON A.\"CarID\"=B.\"asset_id\"
LEFT OUTER JOIN \"Fa1\" C ON C.\"CusID\"=B.\"CusID\"
WHERE \"A_NAME\" like '%$q%' or \"A_SIRNAME\" like '%$q%' or \"C_CARNUM\" like '%$q%' or \"C_REGIS\" like '%$q%'
");
*/
/*
$qry_cn=pg_query("select * from \"VContact\" WHERE (\"full_name\" like '%$q%' or \"IDNO\" like '%$q%' or \"C_CARNUM\" like '%$q%' or \"C_REGIS\" like '%$q%') LIMIT 100");
while($res_cn=pg_fetch_array($qry_cn)){
    $IDNO = $res_cn["IDNO"]; if(empty($IDNO)) $IDNO = "ไม่มี IDNO";
    $full_name = $res_cn["full_name"];
    $C_CARNUM = $res_cn["C_CARNUM"]; if(empty($C_CARNUM)) $C_CARNUM = "ไม่มี เลขตัวถัง";
    $C_REGIS = $res_cn["C_REGIS"]; if(empty($C_REGIS)) $C_REGIS = "ไม่มี เลขทะเบียน";
        echo "<li onselect=\"this.setText('$IDNO - $C_REGIS - $C_CARNUM - $full_name').setValue('$IDNO'); \">$IDNO - $C_REGIS - $C_CARNUM - $full_name</li>";
}
*/
$qry_cn=pg_query("select \"IDNO\",\"full_name\",\"C_CARNUM\",\"C_REGIS\" from \"UNContact\" WHERE (\"full_name\" like '%$q%' or \"IDNO\" like '%$q%' or \"C_CARNUM\" like '%$q%' or \"C_REGIS\" like '%$q%') ORDER BY \"IDNO\" LIMIT 100");
while($res_cn=pg_fetch_array($qry_cn)){
    $IDNO = $res_cn["IDNO"]; if(empty($IDNO)) $IDNO = "ไม่มี IDNO";
    $full_name = $res_cn["full_name"];
    $C_CARNUM = $res_cn["C_CARNUM"]; if(empty($C_CARNUM)) $C_CARNUM = "ไม่มี เลขตัวถัง";
    $C_REGIS = $res_cn["C_REGIS"]; if(empty($C_REGIS)) $C_REGIS = "ไม่มี เลขทะเบียน";
        echo "<li onselect=\"this.setText('$IDNO - $C_REGIS - $C_CARNUM - $full_name').setValue('$IDNO'); \">$IDNO - $C_REGIS - $C_CARNUM - $full_name</li>";
}
?>