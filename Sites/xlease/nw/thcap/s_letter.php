<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);
/*
$qry_name=pg_query("select a.\"contractID\",a.\"CusID\",a.\"CusState\",b.\"full_name\",b.\"IDCARD\" from \"thcap_ContactCus\" a
left join \"VSearchCusCorp\" b on a.\"CusID\"=b.\"CusID\"
where a.\"contractID\" LIKE '%$term%' or b.\"full_name\" LIKE '%$term%' or b.\"IDCARD\" LIKE '%$term%' order by a.\"contractID\"");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $contractID = trim($res_name["contractID"]);
	$full_name = trim($res_name["full_name"]);
	$IDCARD = trim($res_name["IDCARD"]);
	$CusState = trim($res_name["CusState"]);
	if($CusState=="0"){
		$txtcus="ผู้กู้หลัก";
	}else if($CusState=="1"){
		$txtcus="ผู้กู้ร่วม";
	}else{
		$txtcus="ผู้ค้ำ";
	}
	
	
	$name = str_replace("'", "\'"," ".$contractID.""." / ".$full_name."($txtcus)".""." / ".$IDCARD);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
    
	$dt['value'] = $contractID;
    $dt['label'] = $display_name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
*/?>
<?php 

$a1[] = "";
//ค้นหาตาม เลขที่สัญญา
$sql=pg_query("select a.\"contractID\",a.\"CusID\",a.\"CusState\",b.\"full_name\",b.\"IDCARD\" from \"thcap_ContactCus\" a
left join \"VSearchCusCorp\" b on a.\"CusID\"=b.\"CusID\"
where a.\"contractID\" LIKE '%$term%' order by a.\"contractID\"");
$numrows = pg_num_rows($sql);
if($numrows > 0){
	while($res=pg_fetch_array($sql))
	{
		$a1[] = $res["contractID"]; // เลขที่สัญญา
		$contractID = trim($res["contractID"]);
		$full_name = trim($res["full_name"]);
		$IDCARD = trim($res["IDCARD"]);
		$CusState = trim($res["CusState"]);
		if($CusState=="0"){
			$txtcus="ผู้กู้หลัก";
		}else if($CusState=="1"){
			$txtcus="ผู้กู้ร่วม";
		}else{
			$txtcus="ผู้ค้ำ";
		}
		
		
		$name = str_replace("'", "\'"," ".$contractID.""." / ".$full_name."($txtcus)".""." / ".$IDCARD);
		$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
		
		$dt['value'] = $contractID;
		$dt['label'] = $display_name;
		$matches[] = $dt;
					
	}				
}

	
	
//ค้นหาตาม รหัสบัตรประชาชน
$sql=pg_query("select a.\"contractID\",a.\"CusState\",b.\"full_name\",b.\"IDCARD\" from \"thcap_ContactCus\" a
left join \"VSearchCusCorp\" b on a.\"CusID\"=b.\"CusID\"
where b.\"IDCARD\" LIKE '%$term%' order by a.\"contractID\"");
$numrows = pg_num_rows($sql);
if($numrows > 0){
	while($res=pg_fetch_array($sql))
	{
		if(!in_array($res["contractID"],$a1)){
				$a1[] = $res["contractID"]; // เลขที่สัญญา
				$contractID = trim($res["contractID"]);
				$full_name = trim($res["full_name"]);
				$IDCARD = trim($res["IDCARD"]);
				$CusState = trim($res["CusState"]);
				if($CusState=="0"){
					$txtcus="ผู้กู้หลัก";
				}else if($CusState=="1"){
					$txtcus="ผู้กู้ร่วม";
				}else{
					$txtcus="ผู้ค้ำ";
				}
				
				
				$name = str_replace("'", "\'"," ".$contractID.""." / ".$full_name."($txtcus)".""." / ".$IDCARD);
				$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
				
				$dt['value'] = $contractID;
				$dt['label'] = $display_name;
				$matches[] = $dt;
		}
	}
}

//ค้นหาตาม ชื่อ
$sql=pg_query("select a.\"contractID\",a.\"CusState\",b.\"full_name\",b.\"IDCARD\" from \"thcap_ContactCus\" a
left join \"VSearchCusCorp\" b on a.\"CusID\"=b.\"CusID\"
where b.\"full_name\" LIKE '%$term%' order by a.\"contractID\"");
$numrows = pg_num_rows($sql);
if($numrows > 0){
	while($res=pg_fetch_array($sql))
	{
		if(!in_array($res["contractID"],$a1)){
				$a1[] = $res["contractID"]; // เลขที่สัญญา
				$contractID = trim($res["contractID"]);
				$full_name = trim($res["full_name"]);
				$IDCARD = trim($res["IDCARD"]);
				$CusState = trim($res["CusState"]);
				if($CusState=="0"){
					$txtcus="ผู้กู้หลัก";
				}else if($CusState=="1"){
					$txtcus="ผู้กู้ร่วม";
				}else{
					$txtcus="ผู้ค้ำ";
				}
				
				
				$name = str_replace("'", "\'"," ".$contractID.""." / ".$full_name."($txtcus)".""." / ".$IDCARD);
				$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
				
				$dt['value'] = $contractID;
				$dt['label'] = $display_name;
				$matches[] = $dt;
		}
			
	}
}	


if($matches==""){
	$matches[] = "ไม่พบข้อมูล";
}	


$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>

