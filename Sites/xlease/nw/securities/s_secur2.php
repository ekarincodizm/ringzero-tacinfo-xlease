<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

//หาเลขที่โฉนดโดยที่ไม่ได้เชื่อมโยง หรือไม่ได้รออนุมัติขึ้นมา แต่ถ้าสถานะนั้นเป็น true ก็ให้สามารถแสดงค่าได้
$qry_name=pg_query("select \"securID\", \"numDeed\", \"guaranID\", \"numLand\", \"numBook\", \"numPage\", \"pageSurvey\", \"district\",
					\"condoregisnum\", \"condobuildingname\", \"condoroomnum\", \"condofloor\", \"condobuildingnum\"
				from \"nw_securities\" a
				WHERE \"numDeed\" like '%$term%' and (a.\"securID\") NOT IN(select b.\"securID\" from \"nw_linknumsecur\" b where cancel='TRUE') 
				and (a.\"securID\") NOT IN(select c.\"securID\" from \"temp_linknumsecur\" c  
				left join \"temp_linksecur\" d on c.auto_id=d.auto_id where \"statusApp\"='2')");
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
	$securID=$res_name["securID"];
    $numDeed=$res_name["numDeed"];
	$guaranID=$res_name["guaranID"];
	$numLand = trim($res_name["numLand"]);
	$numBook = trim($res_name["numBook"]);
	$numPage = trim($res_name["numPage"]);
	$pageSurvey = trim($res_name["pageSurvey"]);
	$district = trim($res_name["district"]);
	//$proName = trim($res_name["proName"]);
	list($dis,$aum) = explode("/",$district);
	
	$condoregisnum = trim($res_name["condoregisnum"]);
	$condobuildingname = trim($res_name["condobuildingname"]);
	$condoroomnum = trim($res_name["condoroomnum"]); //ห้องชุดเลขที่
	$condofloor = trim($res_name["condofloor"]); //ชั้นที่
	$condobuildingnum = trim($res_name["condobuildingnum"]); //อาคารเลขที่
	
    if($guaranID=="1" || $guaranID=="3"){
		$txtg="ที่ดิน";
		$dt['value'] = $securID."#เลขที่โฉนด ".$numDeed;
		$dt['label'] = "{$securID}:{$txtg}: เลขที่โฉนด {$numDeed}, เลขที่ดิน {$numLand}, เล่มที่ {$numBook},ต.{$dis},อ.{$aum}";
    }else{
		$txtg="ห้องชุด";
		$dt['value'] = $securID."#เลขที่โฉนด ".$numDeed;
		$dt['label'] = "{$securID}:{$txtg}: เลขที่โฉนด {$numDeed}, ห้องชุดเลขที่ {$condoroomnum}, ทะเบียนอาคารชุด {$condoregisnum},ต.{$dis},อ.{$aum}";
	}
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
