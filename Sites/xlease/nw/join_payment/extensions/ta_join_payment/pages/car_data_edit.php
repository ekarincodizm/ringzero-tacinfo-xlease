<?php
require_once("../../sys_setup.php");
include("../../../../../config/config.php");
$q = $_GET['term'];

//$qry_name=pg_query("select idno,cpro_name,car_license,carid from \"VJoinMain\" WHERE car_license LIKE '%$term%' OR idno LIKE '%$term%' OR cpro_name LIKE '%$term%' ORDER BY idno ASC LIMIT(50) ");
$qry_name=pg_query("SELECT m.id,v.\"IDNO\",m.cpro_name,v.\"C_REGIS\",v.\"full_name\",v.\"P_ACCLOSE\",m.cancel,m.car_license,m.idno as idno2,m.carid FROM public.\"VJoinMain\" m left join \"VJoin\" v on m.idno=v.\"IDNO\"  
WHERE ((v.\"IDNO\" like '%$q%') OR (v.\"C_REGIS\" like '%$q%') OR (m.cpro_name like '%$q%')) and m.deleted='0' 
ORDER BY v.\"IDNO\" desc LIMIT 20");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name))
{
	$idno=$res_name["IDNO"];
	$full_name=$res_name["full_name"];
	$id=$res_name["id"];
	$car_regis=$res_name["C_REGIS"];
	$CarID = trim($res_name["carid"]);

	$cancel = $res_name["cancel"];
	$P_ACCLOSE = $res_name["P_ACCLOSE"];

	if($cancel!=0)
	{
		$car_regis = trim($res_name["car_license"]);
		$idno = trim($res_name["idno2"]);
		$full_name = trim($res_name["cpro_name"]);
		if($cancel==1)$cc = " <font color=red>(ยกเลิกแล้ว-ถอดป้าย/เปลี่ยนสี)</font>" ;
		else if($cancel==2)$cc = " <font color=red>(ยกเลิกแล้ว-รถยึด)</font>" ;
		else if($cancel==3)$cc = " <font color=red>(ยกเลิกแล้ว-ขายคืน)</font>" ;
		else if($cancel==4)$cc = " <font color=red>(ยกเลิกแล้ว-โอนสิทธิ์)</font>" ;
	}
	else if($cancel==0 && $P_ACCLOSE=='t')
	{
		$full_name = trim($res_name["cpro_name"]);
	}

	// ถ้า carid ไม่มีค่า
	if($CarID == "")
	{
		// ให้ไปเอาที่ asset_id."IDNO" แทน
		$qry_CarID = pg_query("select \"asset_id\" from \"Fp\" where \"IDNO\" = '$idno' ");
		$CarID = pg_result($qry_CarID,0);
	}
    
    $dt['value'] = $car_regis."#".$CarID."#".$idno."#".$id."#".$P_ACCLOSE;
    $dt['label'] = "{$idno}, {$car_regis} {$full_name} {$cc}";
    $matches[] = $dt;
	$cc=null;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 20);
print json_encode($matches);
?>
