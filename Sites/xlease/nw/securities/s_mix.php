<?php
include("../../config/config.php");

$term = $_GET['term'];
if($term == ""){
$term = $_POST['term'];
$post = 1;

$qry_name=pg_query("select 1 as type ,\"securID\",\"guaranID\",\"numDeed\",\"numLand\",\"numBook\",\"numPage\",\"pageSurvey\",\"district\",\"proName\",
condoregisnum,condobuildingname,condoroomnum,condofloor,condobuildingnum from \"nw_securities\" a 
left join \"nw_province\" b on a.\"proID\"=b.\"proID\" where a.\"numDeed\" = '$term'
union 
select 2 as type ,\"securID\",\"guaranID\",\"numDeed\",\"numLand\",\"numBook\",\"numPage\",\"pageSurvey\",\"district\",\"proName\",
condoregisnum,condobuildingname,condoroomnum,condofloor,condobuildingnum
from \"temp_securities\" c 
left join \"nw_province\" d on c.\"proID\"=d.\"proID\"
where (c.\"securID\") NOT IN(select e.\"securID\" from \"nw_securities\" e) and (c.\"numDeed\"  = '$term')");




}else{

$qry_name=pg_query("select 1 as type ,\"securID\",\"guaranID\",\"numDeed\",\"numLand\",\"numBook\",\"numPage\",\"pageSurvey\",\"district\",\"proName\",
condoregisnum,condobuildingname,condoroomnum,condofloor,condobuildingnum from \"nw_securities\" a 
left join \"nw_province\" b on a.\"proID\"=b.\"proID\" where a.\"numDeed\" like '%$term%' or a.\"numLand\" like '%$term%' or condobuildingname like '%$term%' or condoregisnum like '%$term%' or a.\"district\" like '%$term%'
union 
select 2 as type ,\"securID\",\"guaranID\",\"numDeed\",\"numLand\",\"numBook\",\"numPage\",\"pageSurvey\",\"district\",\"proName\",
condoregisnum,condobuildingname,condoroomnum,condofloor,condobuildingnum
from \"temp_securities\" c 
left join \"nw_province\" d on c.\"proID\"=d.\"proID\"
where (c.\"securID\") NOT IN(select e.\"securID\" from \"nw_securities\" e) and (c.\"numDeed\" like '%$term%' or c.\"numLand\" like '%$term%' or condobuildingname like '%$term%' or condoregisnum like '%$term%' or c.\"district\" like '%$term%')");

}
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
	$proName = trim($res_name["proName"]);
	
	$condoregisnum = trim($res_name["condoregisnum"]);
	$condobuildingname = trim($res_name["condobuildingname"]);
	$condoroomnum = trim($res_name["condoroomnum"]); //ห้องชุดเลขที่
	$condofloor = trim($res_name["condofloor"]); //ชั้นที่
	$condobuildingnum = trim($res_name["condobuildingnum"]); //อาคารเลขที่

   
	if($guaranID=="1" || $guaranID=="3"){
		$dt['value'] = $securID."#เลขที่โฉนด ".$numDeed."#เลขที่ดิน ".$numLand;
		$dt['label'] = "เลขที่โฉนด {$numDeed}, เลขที่ดิน {$numLand}, เล่มที่ {$numBook}, หน้าที่ {$numPage}, หน้าสำรวจ {$pageSurvey}, ตำบล/อำเภอ {$district}, จังหวัด{$proName}";
    }else{

		$dt['value'] = $securID."#เลขที่โฉนด ".$numDeed."#ทะเบียนอาคารชุด ".$condoregisnum;
		$dt['label'] = "เลขที่โฉนด {$numDeed}, ห้องชุดเลขที่ {$condoroomnum}, ชั้นที่ {$condofloor}, อาคารเลขที่ {$condobuildingnum}, ชื่ออาคารชุด {$condobuildingname}, ทะเบียนอาคารชุด {$condoregisnum}";
	}
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

if($post != "1"){
	$matches = array_slice($matches, 0, 100);
	print json_encode($matches);
}else{
	print $securID."#เลขที่โฉนด ".$numDeed."#ทะเบียนอาคารชุด ".$condoregisnum;	
}
?>
