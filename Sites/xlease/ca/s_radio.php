<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = pg_escape_string($_GET["q"]);

$sql_select=pg_query("SELECT \"COID\",\"RadioNum\",\"RadioCar\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",c.\"CusID\" FROM \"RadioContract\" a
left join \"GroupCus\" b on a.\"RadioRelationID\"=b.\"GroupCusID\"
left join \"GroupCus_Active\" c on b.\"GroupCusID\"=c.\"GroupCusID\"
left join \"Fa1\" d on c.\"CusID\"=d.\"CusID\"
where b.\"GStatus\"='ACTIVE' and (\"COID\" LIKE '%$q%' OR \"RadioNum\" LIKE '%$q%' OR \"RadioCar\" LIKE '$q') ORDER BY \"COID\" ASC LIMIT 100;");
while($res_name=pg_fetch_array($sql_select)){
    $COID=trim($res_name["COID"]);
    $RadioNum=trim($res_name["RadioNum"]);
    $RadioCar=trim($res_name["RadioCar"]);
	$CusID=trim($res_name["CusID"]);
	$cusname=trim($res_name["A_FIRNAME"]).trim($res_name["A_NAME"])." ".trim($res_name["A_SIRNAME"]);

        $S_IDNO = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $COID);
        $S_full_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $cusname);
        echo "<li onselect=\"this.setText('$COID : $cusname').setValue('$COID'); \">$S_IDNO : $S_full_name</li>";
}
?>