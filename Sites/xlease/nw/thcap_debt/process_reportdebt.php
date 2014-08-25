<?php
session_start();
include("../../config/config.php");
$app_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ
$app_date = nowDateTime(); //วันเวลาที่ทำรายการ

$contractID=$_POST["contractID"]; //เลขที่สัญญา
$debtid = $_POST["debtid"]; //หนี้ที่เลือก
$datepay = $_POST["datepay"]; //วันที่ครบกำหนดรับชำระ
$dateinv = $_POST["dateinv"]; //วันที่ออกใบแจ้งหนี้
$remark = $_POST["remark"]; //หมายเหตุในใบแจ้งหนี้
$chklease = $_POST["chklease"]; //เลือกว่าแสดงเบี้ยปรับหรือไม่ ถ้า =1 แสดงว่าให้แสดงเบี้ยปรับด้วย

if($chklease=="1"){
	$lease=$_POST["lease"]; //เบี้ยปรับยึดตามค่าที่กรอกในหน้าฟอร์ม
}else{
	$lease="0";
}

pg_query("BEGIN WORK");
$status="";
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>บันทึกออกใบแจ้งหนี้</title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">


<?php
$arraydebt="";
for($t=0;$t<sizeof($debtid);$t++){
	//ตรวจสอบข้อมูลก่อนว่ามีรายการใดที่ได้รับการยกเว้นหนี้หรือไม่
	$qrychk=pg_query("select * from thcap_temp_except_debt where \"debtID\"='$debtid[$t]' and (\"Approve\"='TRUE' or \"Approve\" is null)");
	$numchk=pg_num_rows($qrychk); //กรณีมีข้อมูลแสดงว่ามีการรออนุมัติยกเว้นหรือถูกยกเว้นหนี้ไปแล้ว
	if($numchk>0){
		$chk=1;
		break;
	}
	//ต่อ string เพื่อนำไปหาข้อมูลใน qry
	if($arraydebt==""){
		$arraydebt=$debtid[$t];
	}else{
		$arraydebt=$arraydebt.",".$debtid[$t];
	}
}

if($chk!=1){ //กรณีไม่มีการยกเว้นหนี้ให้ทำงานตามปกติ
	$debt='{'."$arraydebt".'}';

	//บันทึกข้อมูล
	$ins=pg_query("SELECT \"thcap_process_setInvdebt\"('$contractID','$debt','$lease','$datepay','$dateinv','$app_user','$remark')");
	list($status) = pg_fetch_array($ins);
}		

if($status != ""){
	pg_query("COMMIT");
	echo "<script type=\"text/javascript\">";
	echo "javascript:popU('../Payments_Other/print_debt_invoice_pdf.php?invoiceID=$status','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";
	echo "</script>";
	echo "<div style=\"margin:0px auto;text-align:center;padding:20px;\"><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2><div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_reportdebt.php'>";
}else{
	pg_query("ROLLBACK");
	echo $status;
	if($chk==1){
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>มีบางรายการกำลังรออนุมัติยกเว้นหนี้ หรือได้รับการยกเว้นหนี้ไปแล้ว กรุณาตรวจสอบ</b></font><br>";	
	}else{
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br>";
	}
	echo "<input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='frm_reportdebt.php'\">";
}
	

?>
</td>
</tr>
</table>
</body>
</html>