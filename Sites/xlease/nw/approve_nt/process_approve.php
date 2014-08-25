<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$IDNO=$_POST["IDNO"]; 
$NTID2=$_POST["NTID"]; 
$cusname=$_POST["cusname"]; 
$status_approve=$_POST["status_approve"]; //สถานะการ approve
$result_noapprove=$_POST["result"]; //เหตุผลที่ไม่อนุมัติ
$curdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
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
pg_query("BEGIN WORK");
$status = 0;

$query = pg_query("select \"NTID\" from \"NTHead\" where cancel='FALSE' and \"remark\" is null and \"IDNO\"='$IDNO' group by \"NTID\" order by \"NTID\"");
while($res_co = pg_fetch_array($query)){
	$NTID = $res_co["NTID"]; 
	
	//ตรวจสอบข้อมูลก่อนว่าได้มีการ approve ก่อนหน้านี้หรือยัง
	$checkapp=pg_query("select * from \"nw_statusNT\" where \"NTID\" = '$NTID' and \"statusNT\" IN('1','2')");
	$numrowcheck=pg_num_rows($checkapp);
	if($numrowcheck>0){ //แสดงว่ามีการ approve ก่อนหน้านี้แล้ว
		$status=-1;
		break;
	}else{
		$update="update \"nw_statusNT\" set \"statusNT\"='$status_approve', 
											result_noapprove='$result_noapprove', 
											user_approve='$id_user', 
											date_approve='$curdate'
											where \"NTID\" = '$NTID'";
		if($result=pg_query($update)){
		}else{
			$ins_error=$result;
			$status++;
		}
	}
}
/*
if($status_approve == 2){
	$up_fp="update \"Fp\" set \"P_LAWERFEE\"='FALSE' where \"IDNO\" = '$IDNO'";
	if($result=pg_query($up_fp)){
	}else{
		$up_error=$result;
		$status++;
	}
}
*/
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) อนุมัติการสร้าง NT', '$curdate')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
	echo "<FORM METHOD=GET ACTION=\"#\">";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
	echo "</FORM>";
}else if($status>0){
	pg_query("ROLLBACK");
	echo $ins_error."<br>$up_error";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<meta http-equiv='refresh' content='4; URL=compare_nt.php?IDNO=$IDNO&NTID=$NTID2&cusname=$cusname'>";
}else{
	pg_query("ROLLBACK");
	echo "<font size=4><b>มีการทำรายการก่อนหน้านี้แล้วค่ะ</b></font><br><br>";
	echo "<FORM METHOD=GET ACTION=\"#\">";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
	echo "</FORM>";
}
?>
</td>
</tr>
</table>
</body>
</html>