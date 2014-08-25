<?php
include("../../config/config.php");
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา...

$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime();

$assetCancel = $_POST["assetCancel"]; // รหัสใบเสร็จ/ใบสั่งซื้อที่จะขอยกเลิก
$reason = $_POST["reason"]; // เหตุผลในการขอยกเลิก

pg_query("BEGIN");
$status = 0;

$reason = checknull($reason);

// ตรวจสอบก่อนว่า มีการขอยกเลิกไปแล้วหรือยัง
$qry_chkHave = pg_query("select * from \"thcap_asset_cancel\" where \"assetID\" = '$assetCancel' and \"Approved\" is null ");
$rowChkHave = pg_num_rows($qry_chkHave);

// ตรวจสอบก่อนว่า เคยมีรายการในใบเสร็จใบสั่งซื้อนี้ถูกยกเลิกไปแล้วหรือยัง
$qry_chkList = pg_query("select b.* from \"thcap_asset_biz_detail\" a, \"thcap_contract_asset\" b where a.\"assetDetailID\" = b.\"assetDetailID\" and a.\"assetID\" = '$assetCancel'");
$rowChkList = pg_num_rows($qry_chkList);

if($rowChkList > 0 || $rowChkHave > 0)
{ // ถ้ามีบางรายการในใบเสร็จถูกนำไปใช้แล้ว
	$status++;
	if($rowChkHave > 0)
	{
		$error = "ไม่สามารถทำรายการได้ เนื่องจากมีการขอยกเลิกใบเสร็จนี้อยู่แล้ว";
	}
	elseif($rowChkList > 0)
	{
		$error = "ไม่สามารถทำรายการได้ เนื่องจากมีบางรายการใน ใบเสร็จ/ใบสั่งซื้อ ถูกในไปใช้แล้ว";
	}
}
else
{
	$qryAddCancel = "INSERT INTO \"thcap_asset_cancel\"(\"assetID\", \"doerID\", \"doerStamp\", \"reason\")
					VALUES('$assetCancel','$id_user','$logs_any_time',$reason)";
	if($chkAddCancel = pg_query($qryAddCancel)){}else{$status++;}
}

if($status == 0)
{
	//ACTIONLOG
		if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) ยกเลิกใบเสร็จ/ใบสั่งซื้อ สินทรัพย์สำหรับเช่า-ขาย', '$logs_any_time')")); else $status++;
	//ACTIONLOG---
	
	pg_query("COMMIT");
	echo "<br><center><h2><font color=\"#0000FF\">บันทึกสมบูรณ์</font></h2></center>";
	echo "<br><center><input type=\"button\" value=\" ตกลง \" onClick=\"window.location='frm_Index.php'\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br><center><h2><font color=\"#FF0000\">บันทึกผิดพลาด !!</font></h2></center>";
	echo "<br><center><font color=\"#FF0000\">$error</font></center>";
	echo "<br><center><input type=\"button\" value=\" ตกลง \" onClick=\"window.location='frm_Index.php'\"></center>";
}
?>