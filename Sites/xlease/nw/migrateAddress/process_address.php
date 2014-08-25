<?php
set_time_limit(0);
include("../../config/config.php");
$db1="ta_mortgage_datastore";

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status=0;


$qryname = mysql_query("select * from $db1.vaddrbycontract");
$numname=mysql_num_rows($qryname);

while($resname=mysql_fetch_array($qryname)){
	$contractID=$resname["contract_loans_code"];
	$A_NO=$resname["addr_numaddr"]; if($A_NO==""){ $A_NO="null"; }else{ $A_NO="'".$A_NO."'"; }
	$A_SUBNO=$resname["addr_nummoo"]; if($A_SUBNO==""){ $A_SUBNO="null"; }else{ $A_SUBNO="'".$A_SUBNO."'"; }
	$A_BUILDING=$resname["addr_building"]; if($A_BUILDING==""){ $A_BUILDING="null"; }else{ $A_BUILDING="'".$A_BUILDING."'"; }
	$A_ROOM=$resname["addr_room"]; if($A_ROOM==""){ $A_ROOM="null"; }else{ $A_ROOM="'".$A_ROOM."'"; }
	$A_FLOOR=$resname["addr_floor"]; if($A_FLOOR==""){ $A_FLOOR="null"; }else{ $A_FLOOR="'".$A_FLOOR."'"; }
	$A_VILLAGE=$resname["addr_village"]; if($A_VILLAGE==""){ $A_VILLAGE="null"; }else{ $A_VILLAGE="'".$A_VILLAGE."'"; }
	$A_SOI=$resname["addr_soi"]; if($A_SOI==""){ $A_SOI="null"; }else{ $A_SOI="'".$A_SOI."'"; }
	$A_RD=$resname["addr_road"]; if($A_RD==""){ $A_RD="null"; }else{ $A_RD="'".$A_RD."'"; }
	$A_TUM=$resname["addr_district"]; if($A_TUM==""){ $A_TUM="null"; }else{ $A_TUM="'".$A_TUM."'"; }
	$A_AUM=$resname["amphur_name"]; if($A_AUM==""){ $A_AUM="null"; }else{ $A_AUM="'".$A_AUM."'"; }
	$A_PRO=$resname["province_name"]; if($A_PRO==""){ $A_PRO="null"; }else{ $A_PRO="'".$A_PRO."'"; }
	$A_POST=$resname["zip_code"]; if($A_POST==""){ $A_POST="null"; }else{ $A_POST="'".$A_POST."'"; }
	
	//insert ลงใน temp โดยให้สถานะเป็นอนุมติโดยอัตโนมัติ
	$insert_temp="INSERT INTO \"thcap_addrContractID_temp\"(
			\"contractID\", \"addsType\", \"A_NO\", \"A_SUBNO\",\"A_BUILDING\", \"A_ROOM\", \"A_FLOOR\", 
			\"A_VILLAGE\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"addUser\", 
			\"addStamp\", \"statusApp\", \"appUser\", \"appStamp\")
			VALUES ('$contractID','1',$A_NO,$A_SUBNO,$A_BUILDING,$A_ROOM,$A_FLOOR, 
			$A_VILLAGE, $A_SOI, $A_RD, $A_TUM, $A_AUM, $A_PRO, $A_POST,'000',
			'$add_date','1','000','$add_date')";
	if($res_temp=pg_query($insert_temp)){
	}else{
		$status++;
	}
	
	//ตรวจสอบว่ามีข้อมูลหรือยัง ถ้ามีแล้วให้ผ่าน
	$qryadd=pg_query("select * from \"thcap_addrContractID\" where \"contractID\"='$contractID' and \"addsType\"='1'");
	$numrowadd=pg_num_rows($qryadd);
	
	if($numrowadd==0){
		//insert ลงในตารางหลักเพราะถือว่า ข้อมูลนี้เป็นข้อมูลล่าสุด
		$ins="INSERT INTO \"thcap_addrContractID\"(
				\"contractID\", \"addsType\", \"A_NO\", \"A_SUBNO\",\"A_BUILDING\", \"A_ROOM\", \"A_FLOOR\", 
				\"A_VILLAGE\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\")
				VALUES ('$contractID','1',$A_NO,$A_SUBNO,$A_BUILDING,$A_ROOM,$A_FLOOR, 
				$A_VILLAGE, $A_SOI, $A_RD, $A_TUM, $A_AUM, $A_PRO, $A_POST)";
		if($res=pg_query($ins)){
		}else{
			$status++;
		}
	}		
}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>Migrate ข้อมูลเรียบร้อยแล้ว</b></font></div>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}


?>
