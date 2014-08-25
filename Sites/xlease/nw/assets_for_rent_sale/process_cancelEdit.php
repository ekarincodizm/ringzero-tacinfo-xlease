<?php
include("../../config/config.php");
include("../function/emplevel.php");

$tempID = pg_escape_string($_POST["tempID"]);
$isCancel = pg_escape_string($_POST["isCancel"]);
$user_id = $_SESSION["av_iduser"];
$emplevel = emplevel($user_id);
pg_query("BEGIN");
$status = 0;

if($isCancel == 0 && $emplevel > 1) // ถ้าเป็นการขอนำรายการกลับมาแก้ไขอีกครั้ง และ level มากกว่า 1
{
	$status = 1;
}
else
{
	$qry_cancel = "update \"thcap_asset_biz_temp\" set \"isCancel\" = '$isCancel', \"CancelID\" = '$user_id', \"CancelStamp\" = \"nowDateTime\"() where \"tempID\" = '$tempID' ";
	if(pg_query($qry_cancel)){}else{$status = 2;}
}
$script= '<script language=javascript>';
if($status == 0)
{
	pg_query("COMMIT");
	//echo 1;
	if($isCancel == 0){
		$script.= " alert('ทำรายการเรียบร้อยแล้ว');
		opener.location.reload(true);
		self.close();";
	}
	else{
		$script.= " alert('ทำรายการเรียบร้อยแล้ว');
		location.href='frm_selectEditAssetsFromReject.php'";
	}
}
elseif($status == 1)
{ // ถ้าเป็นการขอนำรายการกลับมาแก้ไขอีกครั้ง และ level มากกว่า 1
	pg_query("ROLLBACK");
	if($isCancel == 0){
		$script.= " alert('คุณไม่มีสิทธิ์ในการนำรายการกลับ ผู้มีสิทธิ์จะต้อง level<=1');
		opener.location.reload(true);
		self.close();";
		}
	else{
		$script.= " alert('บันทึกผิดพลาด!!');
		location.href='frm_selectEditAssetsFromReject.php'";
	}
	//echo 2;
}
else
{
	pg_query("ROLLBACK");
	if($isCancel == 0){
		$script.= " alert('บันทึกผิดพลาด!!');
		location.reload();";
	}
	else{
		$script.= " alert('บันทึกผิดพลาด!!')
		location.href='frm_selectEditAssetsFromReject.php'";
	}
	//echo 3;
}
$script.= '</script>';
echo $script;
?>