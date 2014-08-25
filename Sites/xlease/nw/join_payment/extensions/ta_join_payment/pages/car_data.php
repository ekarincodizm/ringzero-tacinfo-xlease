<?php
require_once("../../sys_setup.php");
include("../../../../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select \"IDNO\",full_name,\"C_REGIS\",asset_id,\"P_ACCLOSE\" from \"VJoin\" WHERE \"C_REGIS\" LIKE '%$term%' OR \"full_name\" LIKE '%$term%' OR \"IDNO\" LIKE '%$term%' ORDER BY \"IDNO\" desc LIMIT(20) ");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];

    $C_REGIS=$res_name["C_REGIS"];

	$CarID = trim($res_name["asset_id"]);
	
$P_ACCLOSE = $res_name["P_ACCLOSE"];

	if($P_ACCLOSE=='true'){//เมื่อ ปิดสัญญาให้ดึง ข้อมูลจาก main
		
		$query5 = "SELECT cpro_name,idno,car_license FROM \"VJoinMain\" WHERE asset_id='$CarID' and idno='$IDNO' ";		

				$sql_query5 = pg_query($query5);
	
				if($sql_row5 = pg_fetch_array($sql_query5))
				{			
					$full_name = $sql_row5['cpro_name'];
					$IDNO = $sql_row5['idno'];
					$CarID = $sql_row5['car_license'];

				}
	}
    
    $dt['value'] = $C_REGIS."#".$CarID."#".$IDNO."#".$P_ACCLOSE;
    $dt['label'] = "{$IDNO}, {$C_REGIS} {$full_name} ";
    $matches[] = $dt;
	$cc=null;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 20);
print json_encode($matches);
?>
