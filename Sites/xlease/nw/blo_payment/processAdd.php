<?php
include("../../config/config.php");
include("../function/checknull.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>

<?php
$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN");
$status = 0;

$rowDetail = pg_escape_string($_POST["rowDetail"]); // จำนวนหลักทรัพย์ทั้งหมด
$Payer = pg_escape_string($_POST["Payer"]); // ผู้ชำระเงิน
list($CusID,$CusName) = explode('#',$Payer); // รหัสผู้ขาย และชื่อผู้ขาย
$datepicker_pay = pg_escape_string($_POST["datepicker_pay"]); // วันที่จ่ายเงิน
$contractID = pg_escape_string($_POST["contractID"]); // เลขที่สัญญา
$address = pg_escape_string($_POST["address"]); // ที่อยู่

$list_array = "{";
$amount_array = "{";
$vatValue_array = "{";
$sumCosts_array = "{";
$whtValue_array = "{";

for($i=1; $i<=$rowDetail; $i++)
{
	$list = pg_escape_string($_POST["list$i"]); // รหัสรายการ
	$amount = pg_escape_string($_POST["amount$i"]); // จำนวนเงิน ก่อน vat
	$vatValue = pg_escape_string($_POST["vatValue$i"]); // ยอด vat แต่ละรายการ
	$whtValue = pg_escape_string($_POST["whtValue$i"]); // ยอดภาษีหัก ณ ที่จ่าย
	
	if($list == ""){$status++;}
	if($amount == ""){$amount = 0;}
	if($vatValue == ""){$vatValue = 0;}
	if($whtValue == ""){$whtValue = 0;}
	
	$sumCosts = $amount + $vatValue;
	
	if($sumCosts == ""){$sumCosts = 0;}
	
	if($list_array == "{"){$list_array .= "$list";}else{$list_array .= ",$list";}
	if($amount_array == "{"){$amount_array .= "$amount";}else{$amount_array .= ",$amount";}
	if($vatValue_array == "{"){$vatValue_array .= "$vatValue";}else{$vatValue_array .= ",$vatValue";}
	if($sumCosts_array == "{"){$sumCosts_array .= "$sumCosts";}else{$sumCosts_array .= ",$sumCosts";}
	if($whtValue_array == "{"){$whtValue_array .= "$whtValue";}else{$whtValue_array .= ",$whtValue";}
}

$list_array .= "}";
$amount_array .= "}";
$vatValue_array .= "}";
$sumCosts_array .= "}";
$whtValue_array .= "}";

$qry_addAssetDetail = "insert into public.\"blo_receipt_temp\"(\"receiptStamp\", \"contractID\", \"costsID\", \"netAmt\", \"vatAmt\", \"costsAmt\", \"whtAmt\", \"CusID\", \"CusFullAddress\", \"doerID\", \"doerStamp\", \"appvStatus\")
					values('$datepicker_pay', '$contractID', '$list_array', '$amount_array', '$vatValue_array', '$sumCosts_array', '$whtValue_array', '$CusID', '$address', '$id_user', '$logs_any_time', '9')";
if($result = pg_query($qry_addAssetDetail)){
}
else{
	$status++;
	echo $qry_addAssetDetail;
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = "INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(BLO) รับชำระเงิน', '$logs_any_time')";
		if($result = pg_query($sqlaction)){}else{$status++;}
	//ACTIONLOG---
	
	pg_query("COMMIT");
	
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	?>
	<center><input type="button" value="ตกลง" onClick="window.location='frm_Index.php'"></center>
	<?php
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	?>
	<center><input type="button" value="ตกลง" onClick="window.location='frm_Index.php'"></center>
	<?php
}
//--------------- จบการบันทึกข้อมูล
?>
</html>