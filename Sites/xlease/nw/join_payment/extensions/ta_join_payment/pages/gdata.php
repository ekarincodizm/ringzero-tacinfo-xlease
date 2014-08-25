<?php
ob_start();
session_start();
require_once("../../sys_setup.php");
include("../../../../../config/config.php");

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = $_GET["q"];


$sql_select=pg_query("SELECT m.id,v.\"IDNO\",m.cpro_name,v.\"C_REGIS\",v.\"full_name\",v.\"P_ACCLOSE\",m.cancel,m.car_license,m.idno as idno2 FROM public.\"VJoinMain\" m left join \"VJoin\" v on m.idno=v.\"IDNO\" 
WHERE ((v.\"IDNO\" like '%$q%') OR (v.\"C_REGIS\" like '%$q%') OR (m.cpro_name like '%$q%')) and m.deleted='0' 
ORDER BY v.\"IDNO\" desc LIMIT 20");

while($res_cn=pg_fetch_array($sql_select)){
    $IDNO = trim($res_cn["IDNO"]);
    $id = trim($res_cn["id"]);
    $full_name = trim($res_cn["full_name"]);
    $car_license = trim($res_cn["C_REGIS"]);
	$car_license = $res_cn["C_REGIS"];
	$P_ACCLOSE = $res_cn["P_ACCLOSE"];
	$cancel = $res_cn["cancel"];
	if($cancel!=0){
		
		$car_license = trim($res_cn["car_license"]);
		$IDNO = trim($res_cn["idno2"]);
		if($cancel==1)$cc = " <font color=red>(ยกเลิกแล้ว-ถอดป้าย/เปลี่ยนสี)</font>" ;
		else if($cancel==2)$cc = " <font color=red>(ยกเลิกแล้ว-รถยึด)</font>" ;
		else if($cancel==3)$cc = " <font color=red>(ยกเลิกแล้ว-ขายคืน)</font>" ;
		else if($cancel==4)$cc = " <font color=red>(ยกเลิกแล้ว-โอนสิทธิ์)</font>" ;
		
		$full_name = trim($res_cn["cpro_name"]) ;
		
	}else if($cancel==0 && $P_ACCLOSE=='t'){
		
		$full_name = trim($res_cn["cpro_name"]);
	}

    $display_IDNO = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $IDNO);
    $display_C_REGIS = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $car_license);
    $display_A_NAME = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $full_name);
   


    echo "<li onselect=\"this.setText('$IDNO : $car_license - $full_name ').setValue('$id'); \">$display_IDNO : $display_C_REGIS - $display_A_NAME $cc</li>";
	$cc=null;
}
ob_end_flush();
?>