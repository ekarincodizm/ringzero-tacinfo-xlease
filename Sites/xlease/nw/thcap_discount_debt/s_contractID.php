<?php
include("../../config/config.php");
/*
$term = $_GET['term'];

//ค้นหาตาม เลขที่สัญญา
$sql=pg_query("SELECT * FROM \"thcap_contract\" WHERE \"contractID\" LIKE '%$term%' ORDER BY \"contractID\"");
$numrows = pg_num_rows($sql);
if($numrows > 0){
	while($res=pg_fetch_array($sql))
	{
		$contractID=$res["contractID"]; // เลขที่สัญญา
		
		$name = str_replace("'", "\'"," ".$contractID);
		$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");		
		
		$dt['value'] = $contractID;
		$dt['label'] = $display_name;
		$matches[] = $dt;
					
	}				
}
	
if($matches==""){
	$matches[] = "ไม่พบข้อมูล";
}	


$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
*/
$term = $_GET['term'];
$a1[] = "";
//ค้นหาตาม เลขที่สัญญา
$sql=pg_query("select * from public.\"vthcap_ContactCus_detail\"
				WHERE (\"contractID\" LIKE '%$term%')
						and (\"contractID\" in(select \"contractID\" from public.\"thcap_mg_contract\")
						or \"contractID\" in(select \"contractID\" from public.\"thcap_lease_contract\")) 
						order by \"contractID\"");
$numrows = pg_num_rows($sql);
if($numrows > 0){
	while($res=pg_fetch_array($sql))
	{
		$a1[] = $res["contractID"]; // เลขที่สัญญา
		$t1=$res["contractID"]; // เลขที่สัญญา
		$t2=$res["thcap_fullname"]; // ชื่อเต็ม
		$t3=$res["N_IDCARD"]; // บัตรประชาชน
		$t4=$res["N_CARDREF"]; // บัตรอื่นๆ
						
			$qry_chkStatus = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$t1' ");
			while($res_chkStatus = pg_fetch_array($qry_chkStatus))
			{
				$conStatus = $res_chkStatus["conStatus"]; // สถานะของสัญญา
			}
						
						if($conStatus == "11")
						{
							$txtLable = "<font color=\"#CCCCCC\">เลขที่สัญญา:$t1 บัตรประชาชน:$t3 ชื่อ:$t2 บัตรอื่นๆ:$t4(ปิดบัญชีแล้ว)</font>";
						}
						else
						{
							$txtLable = "<font color=\"#000000\">เลขที่สัญญา:$t1 บัตรประชาชน:$t3  ชื่อ:$t2 บัตรอื่นๆ:$t4</font>";
						}
						
		$dt['value'] = $t1;
		$dt['label'] = $txtLable;
		$matches[] = $dt;
					
	}				
}

	
	
//ค้นหาตาม รหัสบัตรประชาชน
$sql=pg_query("select * from public.\"vthcap_ContactCus_detail\"
				WHERE (\"N_IDCARD\" LIKE '%$term%' or \"N_CARDREF\" LIKE '%$term%')
						and (\"contractID\" in(select \"contractID\" from public.\"thcap_mg_contract\")
						or \"contractID\" in(select \"contractID\" from public.\"thcap_lease_contract\")) 
						order by \"contractID\"");
$numrows = pg_num_rows($sql);
if($numrows > 0){
	while($res=pg_fetch_array($sql))
	{
		if(!in_array($res["contractID"],$a1)){
			 $a1[] = $res["contractID"]; // เลขที่สัญญา
				$t1=$res["contractID"]; // เลขที่สัญญา	
				$t2=$res["thcap_fullname"]; // ชื่อเต็ม
				$t3=$res["N_IDCARD"]; // บัตรประชาชน
				$t4=$res["N_CARDREF"]; // บัตรอื่นๆ
						
				$qry_chkStatus = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$t1' ");
				while($res_chkStatus = pg_fetch_array($qry_chkStatus))
				{
					$conStatus = $res_chkStatus["conStatus"]; // สถานะของสัญญา
				}
						
						if($conStatus == "11")
						{
							$txtLable = "<font color=\"#CCCCCC\">เลขที่สัญญา:$t1 บัตรประชาชน:$t3 ชื่อ:$t2 บัตรอื่นๆ:$t4(ปิดบัญชีแล้ว)</font>";
						}
						else
						{
							$txtLable = "<font color=\"#000000\">เลขที่สัญญา:$t1 บัตรประชาชน:$t3  ชื่อ:$t2 บัตรอื่นๆ:$t4</font>";
						}
						
						$dt['value'] = $t1;
						$dt['label'] = $txtLable;
						$matches[] = $dt;
						
		}
	}
}

//ค้นหาตาม ชื่อ
$sql=pg_query("select * from public.\"vthcap_ContactCus_detail\"
				WHERE (\"thcap_fullname\" LIKE '%$term%')
						and (\"contractID\" in(select \"contractID\" from public.\"thcap_mg_contract\")
						or \"contractID\" in(select \"contractID\" from public.\"thcap_lease_contract\")) 
						order by \"contractID\"");
$numrows = pg_num_rows($sql);
if($numrows > 0){
	while($res=pg_fetch_array($sql))
	{
		if(!in_array($res["contractID"],$a1)){
			$a1[] = $res["contractID"]; // เลขที่สัญญา
			$t1=$res["contractID"]; // เลขที่สัญญา
			$t2=$res["thcap_fullname"]; // ชื่อเต็ม
			$t3=$res["N_IDCARD"]; // บัตรประชาชน
			$t4=$res["N_CARDREF"]; // บัตรอื่นๆ
						
				$qry_chkStatus = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$t1' ");
				while($res_chkStatus = pg_fetch_array($qry_chkStatus))
				{
					$conStatus = $res_chkStatus["conStatus"]; // สถานะของสัญญา
				}
						
						if($conStatus == "11")
						{
							$txtLable = "<font color=\"#CCCCCC\">เลขที่สัญญา:$t1 บัตรประชาชน:$t3 ชื่อ:$t2 บัตรอื่นๆ:$t4(ปิดบัญชีแล้ว)</font>";
						}
						else
						{
							$txtLable = "<font color=\"#000000\">เลขที่สัญญา:$t1 บัตรประชาชน:$t3  ชื่อ:$t2 บัตรอื่นๆ:$t4</font>";
						}
						
						$dt['value'] = $t1;
						$dt['label'] = $txtLable;
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