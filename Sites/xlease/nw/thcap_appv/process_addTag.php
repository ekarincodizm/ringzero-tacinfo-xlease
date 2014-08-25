<?php
session_start();
include("../../config/config.php");
$rootpath = redirect($_SERVER['PHP_SELF'],'');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>เพิ่ม TAG ของใบสำคัญ</title>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<center>
<div style="height:50px; width:auto; text-align:center; opacity:20;"><h1></h1></div>


<?php
pg_query("BEGIN WORK");
$status=0;
// ---------------------------------------------------------------------------------------------
// รับค่าต่างๆที่ POST มา
// ---------------------------------------------------------------------------------------------
$s_conid =pg_escape_string($_POST['s_conid']);//เลขที่สัญญา
$voucherID =pg_escape_string($_POST['voucherID']);
// ---------------------------------------------------------------------------------------------
// ตรวจสอบว่า มีการทำรายการไปหรือยัง
// ---------------------------------------------------------------------------------------------

$qry_chk = pg_query("select \"autoID\" from \"thcap_temp_voucher_tag\" where \"voucherID\" ='$voucherID' and \"contractID\"='$s_conid'");
$nub_rows = pg_num_rows($qry_chk);
if($nub_rows>0){	   
		$status++;
}else{
	if((empty($s_conid)) or (empty($voucherID))){
		echo "<div align=center><font color=\"#FF0000\">ผิดผลาด กรุณาลองอีกครั้ง</font></div>";
	}else{

	// ---------------------------------------------------------------------------------------------
	// เพิ่มข้อมูล
	// ---------------------------------------------------------------------------------------------
	$in_sql="INSERT INTO \"thcap_temp_voucher_tag\" (\"voucherID\",\"contractID\") VALUES ('$voucherID','$s_conid') ";
	if($result=pg_query($in_sql)) {
		
	} else {
		$status++;
	}	
}
}	
if($status == 0)
{	pg_query("COMMIT");
	echo "<center><div align=center>บันทึกข้อมูลเรียบร้อยแล้ว</div></center>";
	echo "<script language=\"JavaScript\" type=\"text/javascript\">	
		opener.location.reload(true);";
	echo "</script>";
}
else
{	pg_query("ROLLBACK");
	if($nub_rows>0){
	echo "<div align=center><font color=\"#FF0000\">ผิดพลาด เลขที่ voucherID กับ เลขที่สัญญานี้ได้ทำการเพิ่มไปแล้ว</font></div>";
	}else{
	echo "<center><div align=center><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด</font></div></center>";
	}
}

?>
<div align="center">
<center><br> 
<form name="frm_back" method="GET" action="<?php echo $rootpath."nw/thcap_appv/frm_tag.php?voucherID=$voucherID"?>">
	<input type="submit" value="กลับ" class="ui-button">
	<input type="button" value="  Close  " onclick="javascript:window.close();">
	<INPUT TYPE="hidden" NAME="voucherID" VALUE="<?php echo $voucherID ; ?>">
	
</form>

</center>
</div>
</body>
</html>