<?php
include("../../config/config.php");
$term = $_GET['term'];

$term=strtr($term, "-", " "); //แปลงค่าที่คีย์ - ให้เป็นช่องว่าง
$term=ereg_replace('[[:space:]]+', '', trim($term)); //ตัดช่องว่างออก

$qry_name=pg_query("select a.\"CusID\",a.\"full_name\", b.\"N_IDCARD\" from \"VSearchCus\" a
left join \"Fn\" b on a.\"CusID\"=b.\"CusID\" 
WHERE replace(replace(a.\"full_name\",' ',''),'-','') like '%$term%' or replace(replace(b.\"N_IDCARD\",' ',''),'-','') like '%$term%'");
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $name=$res_name["full_name"];
	$CusID = trim($res_name["CusID"]);
	$N_IDCARD = trim($res_name["N_IDCARD"]);
	
	//หาว่า cusid นี้กำลังรออนุมัติอยู่หรือไม่
	$qry_temp=pg_query("select * from \"Customer_Temp\" where \"CusID\"='$CusID' and \"statusapp\"='2'");
	$num_temp=pg_num_rows($qry_temp);
	if($num_temp>0){
		$txtalert="#อยู่ในระหว่างรออนุมัติ";
	}else{
		$txtalert="";
	}

    $dt['value'] = $CusID."#".$name."#".$N_IDCARD."-".$txtalert;
    $dt['label'] = "{$CusID}, {$name} ,{$N_IDCARD} {$txtalert}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
