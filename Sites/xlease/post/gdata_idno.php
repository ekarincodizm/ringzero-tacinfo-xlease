<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = $_GET["q"];


$sql_select=pg_query("select A.*,B.*,C.* from  \"Fp\"  A
                          LEFT OUTER JOIN \"VCarregistemp\" B ON B.\"IDNO\"=A.\"IDNO\"
                          LEFT OUTER JOIN \"Fa1\" C ON C.\"CusID\"=A.\"CusID\"
                         where (A.\"IDNO\" like '%$q%') OR (B.\"C_REGIS\" like '%$q%') OR (B.\"C_CARNUM\" like '%$q%') OR  (C.\"A_NAME\" like '%$q%') OR (A.\"TranIDRef1\" like '%$q%') OR (A.\"TranIDRef2\" like '%$q%') LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $IDNO = trim($res_cn["IDNO"]);
    $C_REGIS = trim($res_cn["C_REGIS"]);
    $A_NAME = trim($res_cn["A_NAME"]);
    $C_CARNUM = trim($res_cn["C_CARNUM"]);
    $TranIDRef1 = trim($res_cn["TranIDRef1"]);
    $TranIDRef2 = trim($res_cn["TranIDRef2"]);
    
        $display_IDNO = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $IDNO);
        $display_C_REGIS = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $C_REGIS);
        $display_A_NAME = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $A_NAME);
        $display_C_CARNUM = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $C_CARNUM); 
        echo "<li onselect=\"this.setText('$IDNO : $A_NAME').setValue('$IDNO'); \">$display_IDNO : $display_A_NAME</li>";
}
?>