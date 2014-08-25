<?php
include("../../config/config.php");
include("../function/checknull.php");
$date = date("Y-m-d H:i:s");
$doer = $_SESSION['av_iduser'];

$checkdebt = $_POST['checkdebt'];
$addtxtdebt = $_POST['addtxtdebt'];
$checkdebt2 = $_POST['checkdebt2'];
$method=pg_escape_string($_POST['method']);
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
pg_query("BEGIN WORK");
$status = 0;

if($method=="saveprint"){
	for($i=0;$i<sizeof($checkdebt);$i++){
		//ตรวจสอบก่อนว่ารายการนี้มีผู้ทำรายการไปแล้วหรือไม่
		$qrychk=pg_query("select \"invoiceID\" from \"Vthcap_send_invoice\" where \"invoiceID\"='$checkdebt[$i]'
		and (status_sent='FALSE' or print_user is not null)");
		$numchk=pg_num_rows($qrychk);
		if($numchk>0){ //แสดงว่ามีการยกเลิกหรือทำรายการพิมพ์ก่อนหน้านี้แล้ว
			$status=-1;
			break;
		}
		
		if($addtxtdebt[$i]!="0"){ //กรณีที่มีการยกเลิกการส่ง
			$addtxt=checknull($addtxtdebt[$i]);
			//update ข้อมูลว่ามีการยกเลิก
			$upcancel="UPDATE thcap_sendinvlist SET select_user='$doer', select_date=LOCALTIMESTAMP(0),select_status=$addtxt,status_sent='FALSE'
			WHERE \"invoiceID\"='$checkdebt[$i]'";
			
			if($resup=pg_query($upcancel)){
			}else{
				$status++;
			}
		}else{
			//update ข้อมูลว่ามีการพิมพ์แล้ว
			$up="UPDATE thcap_sendinvlist SET print_user='$doer', print_date=LOCALTIMESTAMP(0) 
			WHERE \"invoiceID\"='$checkdebt[$i]'";
			
			if($resup=pg_query($up)){
			}else{
				$status++;
			}
			
			if($i==(sizeof($checkdebt)-1)){
				$debtInvID=$debtInvID.$checkdebt[$i];
			}else{
				$debtInvID=$debtInvID.$checkdebt[$i].",";
			}
		}
	}
	//$debtInvID=serialize($checkdebt); //แปลง array เป็นสตริงเพื่อส่งค่าแบบ GET
}else if($method=="reprint"){
	for($i=0;$i<sizeof($checkdebt2);$i++){		
		if($i==(sizeof($checkdebt2)-1)){
			$debtInvID=$debtInvID.$checkdebt2[$i];
		}else{
			$debtInvID=$debtInvID.$checkdebt2[$i].",";
		}
		
	}
}

if($status==-1){
	pg_query("ROLLBACK");
	echo "<center>มีบางรายการที่ทำรายการไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ</center>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_debtnotice.php'>";
}else if($status==0){	
	pg_query("COMMIT");
	//ACTIONLOG
		if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$doer', '(THCAP) ส่งใบแจ้งหนี้เงินกู้-ค่าเช่า(พิมพ์)', LOCALTIMESTAMP(0))")); else $status++;
	//ACTIONLOG---
	echo "<h2>บันทึกสมบูรณ์</h2>";
	if($debtInvID!=""){
		echo "<script type=\"text/javascript\">";
		echo "javascript:popU('print_debt_invoice_pdf.php?debtInvID=$debtInvID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";		
		echo "</script>";
	}
	echo "<meta http-equiv='refresh' content='2; URL=frm_debtnotice.php'>";
}else{
	pg_query("ROLLBACK");
	echo "<center>บันทึกผิดพลาด</center>";
	echo "<input type=\"button\" value=\"กลับหน้าแรก\" onclick=\"window.location.href='frm_debtnotice.php'\">";
}
?>