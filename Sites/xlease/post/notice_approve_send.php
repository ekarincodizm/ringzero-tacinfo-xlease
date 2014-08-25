<?php
session_start();
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$idno = $_GET['idno'];
$approve=$_GET['approve'];
if($idno==""){
	$idno = $_POST['idno'];
}

?>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>Approve Cancel NT</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    </head>
<body>

<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>

<table width="600" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
<fieldset>
<legend><b>Approve Cancel NT</b></legend>
<div align="center">
<?php
pg_query("BEGIN WORK");
$status = 0;

/*
	มี 2 กรณี คือกดอนุมัติและไม่อนุมัติแต่จะซับซ้อนตรงไม่มีการอัพเดทสถานะใดๆ กรณีไม่อนุมัติ ทำให้ยากต่อการตรวจสอบ
*/
if($approve=="no"){
	$qry=pg_query("select * from \"NTHead\" where \"remark\"is null and \"cancelid\" is null and \"cancel_date\" is null and \"IDNO\"='$idno' and cancel='FALSE'");
	$num_qry=pg_num_rows($qry);
	
	if($num_qry > 0){
		echo "<div style=\"padding:10px\"><h2>รายการนี้ไม่ได้รับการอนุมัติก่อนหน้านี้แล้ว</h2></div>";
		echo "<meta http-equiv='refresh' content='2; URL=notice_approve.php'>";
	}else{
		//ต้องตรวจสอบก่อนว่าได้รับการอนุมัติหรือยัง
		$qry_app=pg_query("select * from \"NTHead\" where cancel='FALSE' and \"IDNO\"='$idno'");
		$num_app=pg_num_rows($qry_app);
		if($num_app ==0){ //แสดงว่ามีการอนุมัติก่อนหน้านี้แล้ว
			echo "<div style=\"padding:10px\"><h2>รายการนี้ได้รับการอนุมัติก่อนหน้านี้แล้ว</h2></div>";
			echo "<meta http-equiv='refresh' content='2; URL=notice_approve.php'>";
		}else{
			$qry_notice=pg_query("select * from \"NTHead\" where \"IDNO\" = '$idno' and \"cancel\"='FALSE' ");
			
			while($res_cancel2=pg_fetch_array($qry_notice)){
				$NTID=$res_cancel2["NTID"];
					
				$qry_nwstatus=pg_query("select \"statusOld\" from \"nw_statusNT\" where \"NTID\"='$NTID'");
				list($statusOld)=pg_fetch_array($qry_nwstatus);
				$num_nw=pg_num_rows($qry_nwstatus);
				if($num_nw>0){
					$result2=pg_query("Update \"nw_statusNT\" SET \"statusNT\"='$statusOld' WHERE \"NTID\"='$NTID'");
					if(!$result2){
						$status++;
					}
				}
			}
			
			$result2=pg_query("Update \"NTHead\" SET \"remark\"=null, \"cancelid\"=null, \"cancel_date\"=null WHERE \"IDNO\"='$idno' and cancel='FALSE'");
			if(!$result2){
				$status++;
			}
			
			if($status == 0){
				pg_query("COMMIT");
				//pg_query("ROLLBACK");
				echo "บันทึกข้อมูลเรียบร้อยแล้ว<br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  กลับ  \" onclick=\"javascript:location='notice_approve.php'\">";
			}else{
				pg_query("ROLLBACK");
				echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  กลับ  \" onclick=\"javascript:location='notice_approve.php'\">";
			}
		}
	}
}else{ //กรณีอนุมัติ
	$qry=pg_query("select * from \"NTHead\" where \"remark\"is null and \"cancelid\" is null and \"cancel_date\" is null and \"IDNO\"='$idno' and cancel='FALSE'");
	$num_qry=pg_num_rows($qry);
	if($num_qry > 0){
		echo "<div style=\"padding:10px\"><h2>รายการนี้ไม่ได้รับการอนุมัติก่อนหน้านี้แล้ว</h2></div>";
		echo "<meta http-equiv='refresh' content='2; URL=notice_approve.php'>";
	}else{
		//ตรวจสอบก่อนว่ามีการอนุมัติหรือยัง
		$qry_app=pg_query("select * from \"NTHead\" where cancel='FALSE' and \"IDNO\"='$idno'");
		$num_app=pg_num_rows($qry_app);
		if($num_app == 0){ //แสดงว่ามีการอนุมัติก่อนหน้านี้แล้ว
			echo "<div style=\"padding:10px\"><h2>รายการนี้ได้รับการอนุมัติก่อนหน้านี้แล้ว</h2></div>";
			echo "<meta http-equiv='refresh' content='2; URL=notice_approve.php'>";
		}else{
		
			//ดึงเลขกลุ่มล่าสุดเพื่อใช้ในการระบุกลุ่มของ NT
				$qry_ingroup = pg_query("SELECT MAX(\"ntgroup\") as \"ntgnum\" FROM \"NTHead_log_notappvcancel\" ");
				list($numgroup) = pg_fetch_array($qry_ingroup);
				IF($numgroup == ""){
					$numgroup = '0';
				}else{
					$numgroup++;
				}
		
		
		
			$query_cancel=pg_query("select * from \"NTHead\" where \"remark\"is not null and \"cancelid\" is not null and \"cancel_date\" is not null and \"IDNO\"='$idno' and cancel='FALSE'");
			while($res_cancel2=pg_fetch_array($query_cancel)){
				$NTID=$res_cancel2["NTID"];
				$result=pg_query("Update \"nw_statusNT\" SET \"statusNT\"='6' WHERE \"NTID\"='$NTID'");
				if(!$result){
					$status++;
				}
				
				$remarkold=$res_cancel2["remark"];
				$cancelidold=$res_cancel2["cancelid"];
				$cancel_dateold=$res_cancel2["cancel_date"];
				
				//เพิ่มข้อมูลลงตาราง NTHead_log_notappvcancel
				$qry_in = pg_query("INSERT INTO \"NTHead_log_notappvcancel\"( \"NTID\", remark_old, cancelid_old, cancel_date, app_user, app_date,ntgroup)
						VALUES ('$NTID', '$remarkold', '$cancelidold','$cancel_dateold','$user_id' , LOCALTIMESTAMP(0),$numgroup)");
				IF($qry_in){}else{ $status++;}	
				
			}

			$result1=pg_query("Update \"Fp\" SET \"P_LAWERFEEAmt\"='0', \"P_LAWERFEE\"='false' WHERE \"IDNO\"='$idno';");
			if(!$result1){
				$status++;
			}

			$result2=pg_query("Update \"NTHead\" SET \"cancel\"='true' WHERE \"IDNO\"='$idno' and \"cancel\"='false'");
			if(!$result2){
				$status++;
			}
		
			if($status == 0){
				//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติยกเลิก NT', '$datelog')");
				//ACTIONLOG---
				pg_query("COMMIT");
				//pg_query("ROLLBACK");
				echo "บันทึกข้อมูลเรียบร้อยแล้ว<br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  ปิด  \" onclick=\"javascript:opener.location.reload(true);self.close();\">";
			}else{
				pg_query("ROLLBACK");
				echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  ปิด \" onclick=\"javascript:location='notice_approve.php'\">";
			}
		}
	}
}
?>
</div>
</fieldset>
        </td>
    </tr>
</table>

</body>
</html>