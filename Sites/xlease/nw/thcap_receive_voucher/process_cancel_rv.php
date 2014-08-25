<?php
include("../../config/config.php");
require('../../thaipdfclass.php');
session_start();
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php 
//----------------- รับข้อมูล ----------------------------------
$doer = $_SESSION['av_iduser'];
$datenow = nowDateTime();
$note = pg_escape_string($_POST['note']);
$data = $_POST["set_voucherID"];
$set_voucherID = explode(",",$data);

$status = 0;
$concurrent = 0;

pg_query("Begin");

for($i=0;$i<count($set_voucherID);$i++){
	
	$voucherID[$i]=$set_voucherID[$i];
	
	$qry = "select * from v_thcap_temp_voucher_details_receive where \"voucherID\"='$voucherID[$i]'";
	
	if($detail = pg_query($qry)){
		
		    $res_detail = pg_fetch_array($detail);
			
			$voucherDate = $res_detail['voucherDate'];
			$voucherTime = $res_detail['voucherTime'];
			$doerFull = $res_detail['doerFull'];
			$doerStamp = $res_detail['doerStamp'];
			$appvFull = $res_detail['appvFull'];
			$appvStamp = $res_detail['appvStamp'];
			$auditFull = $res_detail['auditFull'];
			$auditStamp = $res_detail['auditStamp'];
			$voucherRemark = $res_detail['voucherRemark'];
			$fromChannelDetails = $res_detail['fromChannelDetails'];
			$abh_id = $res_detail['abh_id'];
			
	}else{
		echo "Query Error!";
	}
	// -------------------- Process ---------------------------
		//ตรวจสอบว่ามีรายการรออนุมัติอยู่แล้วหรือทำการอนุมัติยกเลิกไปเรียบร้อยแล้ว
		$qry_concurrent = pg_query("select \"voucherID\" from thcap_temp_voucher_cancel where \"voucherID\"='$voucherID[$i]' and \"appvStatus\" in ('9','1')");
		$num = pg_num_rows($qry_concurrent);
		
		if($num>0){
			$concurrent++;
		}
		
		$qry_ins = "insert into thcap_temp_voucher_cancel (\"voucherID\",\"doerID\",\"doerStamp\",\"doerRemark\",\"appvStatus\") values ('$voucherID[$i]','$doer','$datenow','$note','9')";
	
		if(pg_query($qry_ins)){
		}else{
			$status++;
		}
	// -------------------- End Process ---------------------------
}

if($concurrent>0){
	pg_query("ROLLBACK");
	$alert="มีบางรายการถูกขอยกเลิกไปก่อนหน้านี้แล้ว";
}else{
	if($status == 0){
		pg_query("COMMIT");
		$alert="บันทึกข้อมูลสำเร็จแล้ว";
	}else{
		pg_query("ROLLBACK");
		$alert="บันทึกข้อมูลล้มเหลว";
	}
}
?>
<script>
function refres(){
	self.close();
}
</script>
<html>
		<center>
			<H1><?php echo $alert ?></H1><br>
			<input type="button" name="OK" value=" ปิด " onclick="refres();">
		</center>
</html>