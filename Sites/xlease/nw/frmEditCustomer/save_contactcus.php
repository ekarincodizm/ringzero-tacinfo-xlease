<?php
session_start();
include("../../config/config.php");

$idno=$_POST["idno"];
$txtnames=$_POST["txtnames"];
$CusID2=explode("#",$txtnames);
$cus_sn=$CusID2[0];
$stsup = $_POST["stsup"];
$cus_state = $_POST["CusState"];

$userid=$_SESSION['uid'];
$officeid=$_SESSION["av_officeid"];

$date=date("Y-m-d H:i:s");
pg_query("BEGIN WORK");
$status = 0;

if($stsup=="update"){
	//ค้นหาข้อมูลในตาราง  ContactCus_Temp ว่ามีหรือยัง
	$qrytemp=pg_query("select * from \"ContactCus_Temp\" where \"IDNO\"='$idno' and \"statusApp\"='9'");
	$numtemp=pg_num_rows($qrytemp);
	
	if($numtemp==0){ //ยังไม่มีข้อมูลในตารางนี้
		//add ข้อมูลลงในตารางนี้ก่อน
		$i=0;
		$qrycon=pg_query("select * from \"ContactCus\" where \"IDNO\"='$idno' order by \"CusState\"");
		while($rescon=pg_fetch_array($qrycon)){
			$CusState=$rescon["CusState"];
			$CusID=$rescon["CusID"];
			
			
			$instemp="INSERT INTO \"ContactCus_Temp\"(
            \"IDNO\", \"CusState\", \"CusID\", \"statusApp\")
				VALUES ('$idno', '$i','$CusID', '9');";
			if($resins=pg_query($instemp)){
			}else{
				$status++;
			}
			
			$i++;
		}
	}
	//หลังจากมีข้อมูลแล้วก็ให้ update ข้อมูลในตาราง temp
	$contactup = "update \"ContactCus_Temp\" set \"CusID\"='$cus_sn' where \"IDNO\" = '$idno' and \"CusState\" = '$cus_state'";
	if($resultup=pg_query($contactup)){
	}else{
		$status++;
	}

}else if($stsup=="add"){
	//ค้นหาข้อมูลในตาราง  ContactCus_Temp ว่ามีหรือยัง
	$qrytemp=pg_query("select * from \"ContactCus_Temp\" where \"IDNO\"='$idno' and \"statusApp\"='9'");
	$numtemp=pg_num_rows($qrytemp);
	if($numtemp==0){ //ยังไม่มีข้อมูลในตารางนี้
		//add ข้อมูลลงในตารางนี้ก่อน
		$qrycon=pg_query("select * from \"ContactCus\" where \"IDNO\"='$idno' order by \"CusState\"");
		$i=0;
		while($rescon=pg_fetch_array($qrycon)){
			$CusState=$rescon["CusState"];
			$CusID=$rescon["CusID"];
			
			$instemp="INSERT INTO \"ContactCus_Temp\"(
            \"IDNO\", \"CusState\", \"CusID\", \"statusApp\")
				VALUES ('$idno', '$i','$CusID', '9');";
			if($resins=pg_query($instemp)){
			}else{
				$status++;
			}
			$i++;
		}
	}
	
	$add_ccus=pg_query("select count(*) AS c_cc from \"ContactCus_Temp\" WHERE \"IDNO\"='$idno' AND \"statusApp\"='9'");
	$resccus=pg_fetch_array($add_ccus);

	$cs_cc=$resccus["c_cc"];

	$in_cus="insert into \"ContactCus_Temp\" (\"CusID\",\"CusState\",\"IDNO\",\"statusApp\") values ('$cus_sn','$cs_cc','$idno','9')";
	if($result=pg_query($in_cus)){
	}else{
		$status++;
	}		
}else if($stsup=="confirm"){
	//update ข้อมูลให้เป็นรออนุมัติ
	$resultcancel = $_POST["resultcancel"];
	$contactup = "update \"ContactCus_Temp\" set \"statusApp\"='2',\"userRequest\"='$userid',\"userStamp\"='$date',\"resultcancel\"='$resultcancel' where \"IDNO\" = '$idno' and \"statusApp\" = '9'";
	if($resultup=pg_query($contactup)){
	}else{
		$status++;
	}
}
 /*************/
 
if($status == 0){
	pg_query("COMMIT");
	//pg_query("ROLLBACK");
	echo "<div align=center style=\"padding:20px;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";
	echo "<meta http-equiv=\"refresh\" content=\"2;URL=frm_edit_cus.php?idnog=$idno&status=9\">"; 
}else{
	pg_query("ROLLBACK");
}

 

?>