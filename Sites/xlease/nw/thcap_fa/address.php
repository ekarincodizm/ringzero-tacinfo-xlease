<?php
include("../../config/config.php");
$term = pg_escape_string($_POST['id']);
$term = trim($term);
list($CusID,$nname) = explode('#',$term);

//ตรวจสอบว่ามีลูกค้าในระบบหรือไม่
$qrycheck=pg_query("select \"CusID\" from \"VSearchCusCorp\" where \"CusID\"='$CusID'");
$numcheck=pg_num_rows($qrycheck);

//กรณีมีลูกค้าในระบบ
if($numcheck>0){
	//ที่อยู่สำนักงานใหญ่
	$qryadd1=pg_query("SELECT ta_get_cus_com_add('$CusID',2)");
	list($addr1)=pg_fetch_array($qryadd1);

	if(trim($addr1)==""){
		//ที่อยู่ในหนังสือรับรอง
		$qryadd2=pg_query("SELECT ta_get_cus_com_add('$CusID',1)");
		list($addr2)=pg_fetch_array($qryadd2);
		
		if(trim($addr2)==""){
			//ที่อยู่ส่งเอกสาร
			$qryadd3=pg_query("SELECT ta_get_cus_com_add('$CusID',3)");
			list($addr3)=pg_fetch_array($qryadd3);
			$address=$addr3;
		}else{
			$address=$addr2;
		}
	}else{
		$address=$addr1;
	}

	if((trim($addr1)=="" || empty($addr1)) and (trim($addr2)=="" || empty($addr2)) and (trim($addr3)=="" || empty($addr3))){
		echo "..........";
	}else{
		echo $address;
	}
}else{ //กรณีไม่มีลูกค้าในระบบ
	echo "-";
}
?>