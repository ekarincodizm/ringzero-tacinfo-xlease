<?php
session_start();
include("../../config/config.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$qry_username=pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$app_user' ");
while($res_username=pg_fetch_array($qry_username))
{
	$username = trim($res_username["username"]);
}

$debtID=pg_escape_string($_GET["debtID"]);
$appv=pg_escape_string($_GET["appv"]);
//ตรวจสอบการส่งค่า
if($debtID==""){
	$debtID=pg_escape_string($_POST["debtID"]);
	$apppg=pg_escape_string($_POST["appv"]);
	if($apppg=="อนุมัติ"){ 
		$appv='TRUE';//อนุมัติ
	}else{
		$appv='FALSE';//ไม่อนุมัติ
	}
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
//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน) ป้องกันการทำงานแบบ concurrent
$qry_check=pg_query("select * from \"thcap_temp_except_debt\" where \"debtID\" = '$debtID' and \"Approve\" is not null");
$num_check=pg_num_rows($qry_check);
if($num_check == 1){
		echo "รายการนี้ได้ทำการอนุมัติหรือไม่อนุมัติไปแล้ว";
		echo "<meta http-equiv='refresh' content='2; URL=frm_approve.php'>";
}else{ //กรณียังไม่ได้รับการอนุมัติก่อนหน้านี้
	pg_query("BEGIN WORK");
	$status = 0;
	
	
	$qry_check = "SELECT thcap_process_except_debt('{".$debtID."}','approve','$app_user','$appv')";
	if($resultD=pg_query($qry_check)){}else{$status++;}
	

	
// QUERY เก่า ==============================================================================================================================================================================-
	// $up="UPDATE thcap_temp_except_debt
		// SET  \"appvUser\"='$username',\"appvStamp\"='$app_date',\"Approve\"='$appv' where \"debtID\"='$debtID' ";
	// if($res=pg_query($up)){
	// }else{
		// $status++;
	// }
	
	// if($appv == "TRUE")
	// {
		// $upmain="UPDATE thcap_temp_otherpay_debt
			// SET  \"debtStatus\"='3' where \"debtID\"='$debtID' ";
		// if($res_main=pg_query($upmain)){
		// }else{
			// $status++;
		// }
	// }
// QUERY เก่า ==============================================================================================================================================================================-	
	
	if($status == 0){
	//ACTIONLOG
		//$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(THCAP) อนุมัติการยกเว้นหนี้', '$app_date')");
	//ACTIONLOG---
		pg_query("COMMIT");
		echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
		echo "<input type=button value=\"ปิด\" onclick=\"javascript:RefreshMe();\" />";
		//echo "<meta http-equiv='refresh' content='2; URL=frm_approve.php'>";
	}else{
		pg_query("ROLLBACK");
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br>";
		echo "<input type=button value=\"ปิด\" onclick=\"javascript:RefreshMe();\" />";
		//echo "<input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='frm_approve.php'\">";
	}
	
}
?>
</td>
</tr>
</table>
</body>
</html>