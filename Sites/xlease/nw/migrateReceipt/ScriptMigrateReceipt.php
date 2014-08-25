<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include("../../config/config.php");
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา...

pg_query("BEGIN WORK");
$status=0;

$qry_2012 = pg_query("select * from public.\"thcap_temp_int_201201\" where \"receiptID\" is not null and \"isReceiveReal\"='1' and \"receiptID\" not in(select distinct(\"receiptID\") from public.\"thcap_temp_receipt_otherpay\") ");
while($res_2012 = pg_fetch_array($qry_2012))
{
	$receiptID = $res_2012["receiptID"]; // เลขที่ใบเสร็จ
	$byChannel = $res_2012["byChannel"]; // ช่องทางการจ่าย
	$receiveAmount = $res_2012["receiveAmount"]; // จำนวนเงินที่จ่าย
	$receiveDate = $res_2012["receiveDate"]; // วันที่จ่าย
	
	//เช็คค่าว่างของตัวแปร เพื่อใช้ในการ insert ลงฐานข้อมูล
	$byChannel = checknull($byChannel); // ช่องทางการจ่าย
	
	$qry_in_receipt_otherpay = "insert into public.\"thcap_temp_receipt_otherpay\" (\"receiptID\",\"debtID\",\"netAmt\",\"vatAmt\",\"debtAmt\",\"whtAmt\") values ('$receiptID',null,'$receiveAmount','0.00','$receiveAmount','0.00') ";
	if($result=pg_query($qry_in_receipt_otherpay)){
	}else{
		$status++;
	}
	
	$qry_in_channel = "insert into public.\"thcap_temp_receipt_channel\" (\"receiptID\",\"byChannel\",\"ChannelAmt\",\"receiveDate\") values ('$receiptID',$byChannel,'$receiveAmount','$receiveDate') ";
	if($result=pg_query($qry_in_channel)){
	}else{
		$status++;
	}
}

//--- ถ้ามี ภาษีหัก ณ ที่จ่าย แต่ไม่ระบุเลขที่อ้างอิงให้ update เป็น "ไม่ระบุ"
$qry_update_whtRef = "update public.\"thcap_temp_receipt_details\" set \"whtRef\" = 'ไม่ระบุ'
						where (\"whtRef\" = '' or \"whtRef\" is null)
						and \"receiptID\" in(select \"receiptID\" from public.\"thcap_temp_receipt_otherpay\" where \"whtAmt\" > '0.00')";
if($result = pg_query($qry_update_whtRef)){
}else{
	$status++;
}

if($status==0)
{
	pg_query("COMMIT");
	//pg_query("ROLLBACK"); // test
	echo "<br><center><h2><font color=\"#0000FF\">Migrate สำเร็จ</font></h2></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br><center><h2><font color=\"#FF0000\">Migrate ผิดพลาด!!</font></h2></center>";
}
?>