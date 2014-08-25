<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$app_user = $_SESSION["av_iduser"];
$nowdate = nowDateTime();
$date = nowDate();
$app_date = $nowdate;

$revChqID = pg_escape_string($_GET["revChqID"]);
$statusapp = pg_escape_string($_GET["statusapp"]);
$revChqStatus = pg_escape_string($_GET["revChqStatus"]);
$keepChqDate = "null";
 
pg_query("BEGIN WORK");
$status = 0;
$concurrent = 0;

if($statusapp=="")
{
	$statusapp = pg_escape_string($_POST["statusapp"]); 
	$revChqID = $_POST["revchqid"];
	$keepChqDate = pg_escape_string($_POST["keepChqDate"]);
	$result = $_POST["res"]; //หมายเหตุ
	
	if($keepChqDate != ""){
		if($keepChqDate == $date){ 
			$keepChqDate = "'".$nowdate."'"; 
		}else{
			$keepChqDate = "'".$keepChqDate." "."23:59:59'";
		}
	}else{
		$keepChqDate = "'".$nowdate."'";
	}	

	if($statusapp==1){
		$revChqStatus2=8;
	}else if($statusapp==0){
		$revChqStatus2=4;
	}else{
		$status++;
	}

	for($z=0;$z<sizeof($revChqID);$z++)
	{
		$revChqID2 = checknull(pg_escape_string($revChqID[$z]));
		$result2 = checknull(pg_escape_string($result[$z])); // หมายเหตุ
		
		//ตรวจสอบข้อมูลก่อนว่ามีการบันทึก chq ในตาราง  thcap_receive_cheque_keeper หรือยังถ้ามีแล้ว แสดงมีการทำรายการก่อนหน้านี้แล้ว
		$qrychk=pg_query("SELECT \"revChqID\" FROM finance.\"thcap_receive_cheque_keeper\"  WHERE \"revChqID\" = $revChqID2");
		$numchk=pg_num_rows($qrychk);
		if($numchk>0){
			$concurrent++; //แสดงว่ามีการทำรายการก่อนหน้านี้แล้ว
			break;
		}
		$qry_fr=pg_query("select * from finance.\"V_thcap_receive_cheque_chqManage\" a WHERE a.\"revChqID\" = $revChqID2");
		$res_fr=pg_fetch_array($qry_fr); 
		$revChqStatus=$res_fr["revChqStatus"];
	
	
		if($revChqStatus=="9"){
			$keepFrom=1;
		}else if($revChqStatus=="2"){
			$keepFrom=2;
		}else{
			$status++;
		}	
	
	    $upkeep="update finance.\"thcap_receive_cheque\" set \"revChqStatus\"='$revChqStatus2'
						where \"revChqID\" = $revChqID2";
		if($res_up=pg_query($upkeep)){
		}else{
			$status++;
		}
		
		$inskeep="INSERT INTO finance.\"thcap_receive_cheque_keeper\" 
			(\"revChqID\", \"keepChqDate\", \"keepFrom\", \"keeperID\", \"keeperStamp\",\"result\")
			VALUES ($revChqID2, $keepChqDate, '$keepFrom', '$app_user', '$app_date', $result2)";
		if(pg_query($inskeep)){
		}else{
			$status++;
		}
       
	}

}
else
{
	if($revChqStatus=="9"){
		$keepFrom=1;
	}else if($revChqStatus=="2"){
		$keepFrom=2;
	}

	if($statusapp==1){
		$revChqStatus2=8;
	}else if($statusapp==0){
		$revChqStatus2=4;
	}
	
	//ตรวจสอบว่าทำรายการไปแล้วหรือยัง
	$chkup=pg_query("select \"revChqID\" from finance.\"thcap_receive_cheque\" where \"revChqID\"='$revChqID' and \"revChqStatus\"='9'");
	$numrow = pg_num_rows($chkup);
	if($numrow==0){
		$concurrent++;
	}
	
	$upkeep="update finance.\"thcap_receive_cheque\" set \"revChqStatus\"='$revChqStatus2'
			where \"revChqID\"='$revChqID' ";
	if($res_up=pg_query($upkeep)){
	}else{
		$status++;
	}
	
	$inskeep="INSERT INTO finance.\"thcap_receive_cheque_keeper\" 
		(\"revChqID\",\"keepChqDate\",\"keepFrom\",\"keeperID\",\"keeperStamp\")
		VALUES ('$revChqID',$keepChqDate,'$keepFrom','$app_user','$app_date') ";
	if(pg_query($inskeep)){
		
	}else{
		$status++;
	}
}
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
 <?php

 if($concurrent>0){
	pg_query("ROLLBACK");
	echo "<font size=4><b>มีบางรายการได้รับไปก่อนหน้านี้แล้ว กรุณาทำรายการใหม่อีกครั้ง</b></font><br><br>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_keepcheque.php'>";
 }else{
	if($status == 0){
	pg_query("COMMIT");
		echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
		//if($statusapp==1){
		echo "<meta http-equiv='refresh' content='2; URL=frm_keepcheque.php'>";
		//}else{
		//	echo "<meta http-equiv='refresh' content='2; URL=frm_keepcheque.php'>";
		//}
	}else{
		pg_query("ROLLBACK");
		echo $inskeep."<br>$up_error";
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
		echo "<input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='frm_keepcheque.php'\">";
	}
 }

?>
</td>
</tr>
</table>
</body>
</html>