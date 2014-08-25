<?php
include("../../config/config.php");
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา...
?>

<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime();

$cancelID = $_GET["cancelID"]; // รหัส Temp ที่จะขอยกเลิก
$statusAppv = $_GET["statusAppv"];
if($cancelID==""){
	
	$cancelID = $_POST["cancelID"]; 
	if(isset($_POST["appv"])){
		$statusAppv="1";//อนุมัติ
	}
	else{
		$statusAppv="0";//ไม่อนุมัติ
	}
}


pg_query("BEGIN");
$status = 0;

// ตรวจสอบก่อนว่าเคยทำรายการอนุมัติไปก่อนหน้านี้แล้วหรือยัง เพื่อป้องกันการทำรายการพร้อมกัน
$qry_chkAppv = pg_query("select \"Approved\" from \"thcap_asset_cancel\" where \"cancelID\" = '$cancelID' ");
$res_chkAppv = pg_fetch_result($qry_chkAppv,0);

if($res_chkAppv == "t")
{
	$status++;
	$error = "รายการนี้ถูก อนุมัติ ไปก่อนหน้านี้แล้ว";
}
elseif($res_chkAppv == "f")
{
	$status++;
	$error = "รายการนี้ถูก ปฏิเสธ ไปก่อนหน้านี้แล้ว";
}

if($statusAppv == "0")
{ // ถ้าไม่อนุมัติ
	$qryNoCancel = "UPDATE \"thcap_asset_cancel\" SET \"Approved\" = 'FALSE', \"appvID\" = '$id_user', \"appvStamp\" = '$logs_any_time'
					where \"cancelID\" = '$cancelID' and \"Approved\" is null ";
	if($chkNoCancel = pg_query($qryNoCancel)){}else{$status++;}
}
elseif($statusAppv == "1")
{ // ถ้าอนุมัติ
	// อนุมัติรายการ
	$qryOKCancel = "UPDATE \"thcap_asset_cancel\" SET \"Approved\" = 'TRUE', \"appvID\" = '$id_user', \"appvStamp\" = '$logs_any_time'
					where \"cancelID\" = '$cancelID' and \"Approved\" is null ";
	if($chkOKCancel = pg_query($qryOKCancel)){}else{$status++;}
	
	// หารหัส ใบเสร็จ/ใบสั่งซื้อ
	$qry_sAsset = pg_query("select * from \"thcap_asset_cancel\" where \"cancelID\" = '$cancelID' ");
	while($res_sAsset = pg_fetch_array($qry_sAsset))
	{
		$assetID = $res_sAsset["assetID"]; // รหัส ใบเสร็จ/ใบสั่งซื้อ
	}
	
	// update ตารางใบเสร็จ/ใบสั่งซื้อ
	$qryMainCancel = "UPDATE \"thcap_asset_biz\" SET \"ActiveStatus\" = '0'
					where \"assetID\" = '$assetID' and \"ActiveStatus\" = '1' ";
	if($chkMainCancel = pg_query($qryMainCancel)){}else{$status++;}
	
	// update สินทรัพย์แต่ละตัวให้ถูกยกเลิกไปด้วย
	$qrySonCancel = "UPDATE \"thcap_asset_biz_detail\" SET \"materialisticStatus\" = '9'
					where \"assetID\" = '$assetID' and \"materialisticStatus\" = '1' ";
	if($chkSonCancel = pg_query($qrySonCancel)){}else{$status++;}
}

if($status == 0)
{
	//ACTIONLOG
		if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) อนุมัติยกเลิกใบเสร็จ/ใบสั่งซื้อ สินทรัพย์สำหรับเช่า-ขาย', '$logs_any_time')")); else $status++;
	//ACTIONLOG---
	
	pg_query("COMMIT");
	echo "<br><center><h2><font color=\"#0000FF\">บันทึกสมบูรณ์</font></h2></center>";
	echo "<br><center><input type=\"button\" value=\" ตกลง \" onClick=\"RefreshMe()\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br><center><h2><font color=\"#FF0000\">บันทึกผิดพลาด !!</font></h2></center>";
	echo "<br><center><font color=\"#FF0000\">$error</font></center>";
	echo "<br><center><input type=\"button\" value=\" ตกลง \" onClick=\"RefreshMe()\"></center>";
}
?>