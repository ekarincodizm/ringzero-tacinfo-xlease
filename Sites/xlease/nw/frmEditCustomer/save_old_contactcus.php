<?php
session_start();

echo $idno=pg_escape_string($_GET["fidno"]);
$v_txt=pg_escape_string($_GET["cid"]);
$cus_state=pg_escape_string($_GET["CusState"]); 
$stsup=pg_escape_string($_GET["stsup"]);

echo $cus_sn=substr($v_txt,0,6);


$officeid=$_SESSION["av_officeid"];

$dat=date("Y/m/d");

include("../../config/config.php");

if($stsup == "update"){
	$contactup = "update \"ContactCus\" set \"CusID\"='$cus_sn' where \"IDNO\" = '$idno' and \"CusState\" = '$cus_state'";
	if($resultup=pg_query($db_connect,$contactup)){
		$statuss ="OK update at ContactCus".$contactup;
		$st="บันทึกข้อมูลเรียบร้อย";
	}else{
		$statuss ="error update ContactCus Re".$contactup;
		$st="เกิดข้อผิดพลาด";
	}	
	if($cus_state == '0'){
		$fpup = "update \"Fp\" set \"CusID\"='$cus_sn' where \"IDNO\" = '$idno'";
		if($resultfp=pg_query($db_connect,$fpup)){
			$statuss ="OK update at Fp".$fpup;
			$st="บันทึกข้อมูลเรียบร้อย";
		}else{
			$statuss ="error update Fp Re".$fpup;
			$st="เกิดข้อผิดพลาด";
		}
	}	
}else{
	$add_ccus=pg_query("select count(*) AS c_cc from \"ContactCus\" WHERE \"IDNO\"='$idno' ");
	$resccus=pg_fetch_array($add_ccus);

	$cs_cc=$resccus["c_cc"];

	$in_cus="insert into \"ContactCus\" (\"CusID\",\"CusState\",\"IDNO\") values ('$cus_sn','$cs_cc','$idno')";
	
	if($result=pg_query($in_cus))
	{
		$statuc ="OK at ContactCus".$in_cus;
	}
	else
	{
		$statuc ="error insert  ContactCus Re".$in_cus;
	}	 
}
//echo "<br>".$statuc;
 
echo "<br>";
echo "    บันทึกข้อมูลเรียบร้อยแ้ล้ว ";
echo "<br>";
 
echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_edit_cus.php?idnog=$idno\">"; 


?>