<?php
//include("../config/config.php");

function insertZeroInFunction($inputValue , $digit )
{
	$str = "" . $inputValue;
	while (strlen($str) < $digit){
		$str = "0" . $str;
	}
	return $str;
}
	

// ตรวจสอบ และให้ CusID ถัดไปกับลูกค้าใหม่ *ลูกค้าที่ยังไม่อนุมัติ รหัสนำหน้าด้วย "CT....."
function GenCT()
{
	$qrylast=pg_query("select count(*) AS rescount from \"Customer_Temp\" where \"CusID\" like 'CT%' ");
	$reslast=pg_fetch_array($qrylast);
	$resc=$reslast[rescount];

	if($resc==0){
		$res_sn=1;
	}else{
		//หา CusID แบบ CT ที่มากที่สุด
		$qrylast_new=pg_query("select \"CusID\" from \"Customer_Temp\" where \"CusID\" like 'CT%' order by \"CusID\" DESC limit 1 ");
		$reslast_new=pg_fetch_array($qrylast_new);
		$res_sn = $reslast_new[CusID];
		$res_sn = str_replace("CT" , "" , $res_sn);
		$res_sn = $res_sn + 1;
	}

	$a = $res_sn;
	return "CT".insertZeroInFunction($a,5);
}


//ตรวจสอบ และให้ CusID ถัดไปกับลูกค้าใหม่ *ลูกค้าที่อนุมัติแล้ว รหัสนำหน้าด้วย "C....."
function GenCus()
{
	$qrylast=pg_query("select count(*) AS rescount from \"Fa1\" ");
	$reslast=pg_fetch_array($qrylast);
	$resc=$reslast[rescount];

	if($resc==0){
		$res_sn=1;
	}else{
		//หา CusID ที่มากที่สุดจากตาราง Fa1
		$qrylast_new=pg_query("select \"CusID\" from \"Fa1\" order by \"CusID\" DESC limit 1 ");
		$reslast_new=pg_fetch_array($qrylast_new);
		$res_sn = $reslast_new[CusID];
		$res_sn = str_replace("C" , "" , $res_sn);
		
		//หา CusID ที่มากที่สุดจากตาราง change_cus
		$qrylast_old=pg_query("select \"Cus_old\" from \"change_cus\" where \"Cus_old\" like 'C%' order by \"Cus_old\" DESC limit 1 ");
		$reslast_old=pg_fetch_array($qrylast_old);
		$res_sn_old = $reslast_old[Cus_old];
		$res_sn_old = str_replace("C" , "" , $res_sn_old);
		
		if($res_sn > $res_sn_old){$res_sn = $res_sn;}
		elseif($res_sn_old > $res_sn){$res_sn = $res_sn_old;}
		
		$res_sn = $res_sn + 1;
	}

	$a = $res_sn;
	return "C".insertZeroInFunction($a,5);
}
?>