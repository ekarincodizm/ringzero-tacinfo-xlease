<?php
session_start();
include("../config/config.php");

$now_date = nowDate(); //ดึงข้อมูลวันที่จาก server
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$chkbox = $_POST['chkbox'];
$btnsubmit = pg_escape_string($_POST['btnsubmit']);

$ccc = 0;
$ccc = @count($chkbox);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">

    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    

<style type="text/css">
.ui-widget {
    font-family:tahoma;
    font-size:13px;
}
</style>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input name="button" type="button" onclick="window.location='fvoucher_approve.php'" value="ย้อนกลับ" /></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>Approve Voucher - รายการรอให้ Admin อนุมัติ</B></legend>

<div align="center" style="margin:20px 0px 20px 0px">
<?php
if($ccc == 0){
    echo "กรุณาเลือกรายการ";
}else{


pg_query("BEGIN WORK");
$status = 0;
$allvcid = "";
$statuscheck=0;
foreach($chkbox as $v){

    $arr_v = explode("#",$v);
	//ก่อนทำการ approve ตรวจสอบด้วยว่ารายการนี้ถูก approve ก่อนหน้านี้หรือยัง
	$qrycheck=pg_query("select A.*,B.* from account.tal_voucher A 
	LEFT OUTER JOIN account.\"job_voucher\" B on A.\"job_id\" = B.\"job_id\" 
	WHERE A.\"approve_id\" is not null and vc_id='$arr_v[1]' ORDER BY A.\"job_id\" ");
	$numcheck=pg_num_rows($qrycheck);  //ถ้าเท่ากับ 0 แสดงว่ายังไม่ approve
	if($numcheck>0){
		//ให้เก็บรหัสที่ approve แล้วไว้แสดงค่า
		$allvcid=$allvcid.$arr_v[1]."<br>";
		$statuscheck++;
	}else{
		if($btnsubmit == "อนุมัติรายการที่เลือก"){
		
			if($arr_v[0] == "C"){
				$up_sql=pg_query("UPDATE account.\"job_voucher\" SET \"vcp_finish\"='TRUE',\"end_date\"='$now_date' WHERE \"job_id\"='$arr_v[2]'");
				if(!$up_sql){
					$status++;
				}
			}
			if(substr($arr_v[1],0,2) == "VR"){
				$up_sql=pg_query("UPDATE account.\"job_voucher\" SET \"vcp_finish\"='TRUE',\"end_date\"='$now_date' WHERE \"job_id\"='$arr_v[2]'");
				if(!$up_sql){
					$status++;
				}
				$up_sql=pg_query("UPDATE account.\"voucher\" SET \"receipt_id\"='$arr_v[3]',\"recp_date\"='$arr_v[4]' WHERE \"vc_id\"='$arr_v[1]'");
				if(!$up_sql){
					$status++;
				}
			}
			$up_sql=pg_query("UPDATE account.\"voucher\" SET \"approve_id\"='$user_id',\"appv_date\"='$now_date' WHERE \"vc_id\"='$arr_v[1]'");
			if(!$up_sql){
				$status++;
			}
		
		}else{

			$up_sql=pg_query("UPDATE account.\"job_voucher\" SET \"vcp_finish\"='TRUE',\"cancel\"='TRUE' WHERE \"job_id\"='$arr_v[2]'");
			if(!$up_sql){
				$status++;
			}
			
			$up_sql=pg_query("UPDATE account.\"voucher\" SET \"approve_id\"='$user_id',\"receipt_id\"='cancel' WHERE \"vc_id\"='$arr_v[1]'");
			if(!$up_sql){
				$status++;
			}

		}
    }
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติ Voucher', '$datelog')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว<br>";
	if($statuscheck > 0){
		echo "มีบางรายการที่อนุมัติไปก่อนหน้านี้แล้ว ระบบไม่สามารถอนุมัติซ้ำได้อีก ดังนี้<br>";
		echo "<b>$allvcid</b>";
	}
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
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