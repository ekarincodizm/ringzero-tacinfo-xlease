<?php
include("../../config/config.php");
$term = $_GET['term'];
$a1[] = "";

//ค้นหาตาม เลขที่สัญญา
$qry_name=pg_query("SELECT * FROM  public.\"vthcap_ContactCus_detail\" where \"contractID\" LIKE '%$term%' order by \"contractID\"");
$numrows = pg_num_rows($qry_name);
if($numrows > 0){
	while($res=pg_fetch_array($qry_name)){
		
			$a1[] = $res["contractID"]; // เลขที่สัญญา
			$t1=$res["contractID"]; // เลขที่สัญญา
			$t2=$res["thcap_fullname"]; // ชื่อเต็ม
			$t3=$res["N_IDCARD"]; // บัตรประชาชน
			$t4=$res["N_CARDREF"]; // บัตรอื่นๆ
		
			$txtLable = "<font color=\"#000000\">เลขที่สัญญา:$t1 บัตรประชาชน:$t3  ชื่อ:$t2 บัตรอื่นๆ:$t4</font>";
							
			$dt['value'] = $t1;
			$dt['label'] = $txtLable;
			$matches[] = $dt;
				
	}
}

//ค้นหาตาม รหัสบัตรประชาชน
$qry_name=pg_query("SELECT * FROM  public.\"vthcap_ContactCus_detail\" where \"N_IDCARD\" LIKE '%$term%' or \"N_CARDREF\" LIKE '%$term%' order by \"N_IDCARD\"");
$numrows = pg_num_rows($qry_name);
if($numrows > 0){
	while($res=pg_fetch_array($qry_name)){
		if(!in_array($res["contractID"],$a1)){	
			$a1[] = $res["contractID"]; // เลขที่สัญญา
			$t1=$res["contractID"]; // เลขที่สัญญา
			$t2=$res["thcap_fullname"]; // ชื่อเต็ม
			$t3=$res["N_IDCARD"]; // บัตรประชาชน
			$t4=$res["N_CARDREF"]; // บัตรอื่นๆ
		
			$txtLable = "<font color=\"#000000\">เลขที่สัญญา:$t1 บัตรประชาชน:$t3  ชื่อ:$t2 บัตรอื่นๆ:$t4</font>";
							
			$dt['value'] = $t1;
			$dt['label'] = $txtLable;
			$matches[] = $dt;
		}		
	}
}	

//ค้นหาตาม ชื่อลูกค้า
$qry_name=pg_query("SELECT * FROM  public.\"vthcap_ContactCus_detail\" where \"thcap_fullname\" LIKE '%$term%' order by \"thcap_fullname\"");
$numrows = pg_num_rows($qry_name);
if($numrows > 0){
	while($res=pg_fetch_array($qry_name)){
		if(!in_array($res["contractID"],$a1)){	
			$a1[] = $res["contractID"]; // เลขที่สัญญา
			$t1=$res["contractID"]; // เลขที่สัญญา
			$t2=$res["thcap_fullname"]; // ชื่อเต็ม
			$t3=$res["N_IDCARD"]; // บัตรประชาชน
			$t4=$res["N_CARDREF"]; // บัตรอื่นๆ
		
			$txtLable = "<font color=\"#000000\">เลขที่สัญญา:$t1 บัตรประชาชน:$t3  ชื่อ:$t2 บัตรอื่นๆ:$t4</font>";
							
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
