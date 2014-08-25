<?php
session_start();
include("../../config/config.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

/*$revTranID=$_GET["revTranID"]; 
$app=$_GET["app"];  */

$revTranID=$_POST["revTranID"];

if(isset($_POST["appv"])){
	$app="1";//กดอนุมัติ
}else{
	$app="0";//กดไม่อนุมัติ
}
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
<?php
// อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
$qry_check=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"revTranStatus\"='5' and \"revTranID\" = '$revTranID'");
$num_check=pg_num_rows($qry_check);
if($num_check == 0){
		echo "รายการนี้ได้รับการอนุมัติไปแล้ว";
		echo "<meta http-equiv='refresh' content='2; URL=frm_AppKeySystem.php'>";
}else{ //กรณียังไม่ได้รับการอนุมัติก่อนหน้านี้
	pg_query("BEGIN WORK");
	
	
	//update ข้อมูลเบื้่องต้นว่าใครเป็นผู้อนุมัติ(บัญชี)		
	$upd="update \"finance\".\"thcap_receive_transfer_action\" set \"appvXID\"='$app_user',
		\"appvXStamp\"='$app_date',
		\"appvXStatus\"='$app' where \"revTranID\"='$revTranID'";
	
	if($resins=pg_query($upd)){
	}else{
		$status++;
	}
	
	if($app==0){ //กรณีไม่อนุมัติให้ update สถานะเป็นไม่อนุมัติไปโดยปริยาย
		$statusapp=0;
		$txtlog="ไม่อนุมัติคีย์ผ่านระบบ";
	}else{
		$statusapp=9;
		$txtlog="อนุมัติคีย์ผ่านระบบ";
	}
	
	//update ข้อมูลให้เป็นสถานะรอการเงินอนุมัติ
	$upd="update \"finance\".\"thcap_receive_transfer\" set \"revTranStatus\"='$statusapp' where \"revTranID\"='$revTranID'";		
	if($res_upd=pg_query($upd)){
	}else{
		$status++;
	}
	
	//หาข้อมูลเพื่อนำมาเก็บใน log
	$qrydata=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$revTranID'");
	if($resdata=pg_fetch_array($qrydata)){
		$BAccount=$resdata["BAccount"];
		$bankRevBranch=$resdata["bankRevBranch"];
		$bankRevAmt=$resdata["bankRevAmt"];
		$bankRevStamp=$resdata["bankRevStamp"];
	}
	
	
	
	if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
	\"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\") 
	VALUES ('$txtlog','$revTranID','$app_user', '$app_date','$BAccount',
	'$bankRevBranch','$bankRevAmt','$bankRevStamp')")); else $status++;
}
	
if($status == 0){
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_AppKeySystem.php'>";
}else{
	pg_query("ROLLBACK");
	echo $insnw;
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br>";
	echo "<input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='frm_AppKeySystem.php'\">";
}
	

?>
</td>
</tr>
</table>
</body>
</html>