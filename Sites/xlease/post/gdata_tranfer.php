<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = $_GET["q"];

/*
$sql_select=pg_query("select A.*,B.*,C.* from  \"Fp\"  A
                          LEFT OUTER JOIN \"Fc\" B ON B.\"CarID\"=A.asset_id
                          LEFT OUTER JOIN \"Fa1\" C ON C.\"CusID\"=A.\"CusID\"
                         where (A.\"IDNO\" like '%$q%') OR (B.\"C_REGIS\" like '%$q%') OR (B.\"C_CARNUM\" like '%$q%') OR  (C.\"A_NAME\" like '%$q%')  ORDER BY \"IDNO\" LIMIT 100");
*/

//$sql_select = pg_query("SELECT * FROM \"UNContact\" WHERE (\"IDNO\" LIKE '%$q%') OR (\"C_REGIS\" LIKE '%$q%') OR (\"C_CARNUM\" LIKE '%$q%') OR (\"full_name\" LIKE '%$q%') ORDER BY \"IDNO\"  ASC LIMIT 100 ");

/*
$sql_select = pg_query("SELECT \"UNContact\".\"IDNO\" , \"UNContact\".\"C_REGIS\" , \"UNContact\".\"full_name\" , \"UNContact\".\"C_CARNUM\" , \"Fn\".\"N_IDCARD\" 
						FROM \"UNContact\" , \"Fn\" , \"ContactCus\" 
						WHERE \"UNContact\".\"IDNO\" = \"ContactCus\".\"IDNO\" and \"ContactCus\".\"CusID\" = \"Fn\".\"CusID\" 
						and ((\"UNContact\".\"IDNO\" LIKE '%$q%') OR (\"UNContact\".\"C_REGIS\" LIKE '%$q%') OR (\"UNContact\".\"C_CARNUM\" LIKE '%$q%') OR (\"UNContact\".\"full_name\" LIKE '%$q%') OR (\"Fn\".\"N_IDCARD\" like '%$q%')) 
						ORDER BY \"UNContact\".\"IDNO\"  ASC LIMIT 100 ");
*/ // comment ทิ้ง เนื่องจาก join ตารางเยอะเกินความจำเป็น คือตาราง ContactCus ไม่ได้ใช้ ไม่จำเป็นต้อง join เพราะทำให้ได้ข้อมูลเกินมา และผิด

$sql_select = pg_query("SELECT \"UNContact\".\"IDNO\" , \"UNContact\".\"C_REGIS\" , \"UNContact\".\"full_name\" , \"UNContact\".\"C_CARNUM\" , \"Fn\".\"N_IDCARD\" 
						FROM \"UNContact\" , \"Fn\"
						WHERE \"UNContact\".\"CusID\" = \"Fn\".\"CusID\" 
						and ((\"UNContact\".\"IDNO\" LIKE '%$q%') OR (\"UNContact\".\"C_REGIS\" LIKE '%$q%') OR (\"UNContact\".\"C_CARNUM\" LIKE '%$q%') OR (\"UNContact\".\"full_name\" LIKE '%$q%') OR (\"Fn\".\"N_IDCARD\" like '%$q%')) 
						ORDER BY \"UNContact\".\"IDNO\"  ASC LIMIT 100 ");

while($res_cn=pg_fetch_array($sql_select)){
    $IDNO = $res_cn["IDNO"];
    $C_REGIS = trim($res_cn["C_REGIS"]);
    $full_name = trim($res_cn["full_name"]);
    $C_CARNUM = trim($res_cn["C_CARNUM"]);
	$N_IDCARD = $res_cn["N_IDCARD"];
   
    $display_IDNO = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $IDNO);
    $display_C_REGIS = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $C_REGIS);
    $display_full_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $full_name);
    $display_C_CARNUM = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $C_CARNUM);
	$display_N_IDCARD = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $N_IDCARD);
    echo "<li onselect=\"this.setText('$IDNO : $C_REGIS - $display_full_name - $C_CARNUM').setValue('$IDNO'); \">$display_IDNO $slock : $display_C_REGIS - $display_full_name - $display_C_CARNUM - $display_N_IDCARD</li>";
}
?>