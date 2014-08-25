<?php
include("../../config/config.php");
$term = $_GET['term'];
$a1[] = "";
//ค้นหาตาม เลขที่สัญญา
/*$sql=pg_query("select * from public.\"vthcap_ContactCus_detail\"
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
}	*/
$sql=pg_query("select * from thcap_installment_search where \"TIS_Default\" like '%$term%'");
	$numrows = pg_num_rows($sql);
		if($numrows > 0){
			while($res=pg_fetch_array($sql))
			{
			$TIS_Default = $res["TIS_Default"]; // เลขที่สัญญา
			$str=explode("#",$TIS_Default);
			list($string,$contractID)=explode(":",$str[1]);
			$dt['value'] = trim($contractID);
			$dt['label'] = trim($TIS_Default);
			$matches[] = $dt;
					
			}				
		} 

if($matches==""){
	$matches[] = "ไม่พบข้อมูล";
}	


$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>