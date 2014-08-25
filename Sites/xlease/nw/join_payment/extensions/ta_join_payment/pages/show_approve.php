<?php
session_start();
include("../../../../../config/config.php");
require_once("../../sys_setup.php");
$deleteid=trim($_GET["deleteid"]);
$readonly=trim($_GET["readonly"]); //กรณีดูข้อมูลอย่างเดียว

if($readonly=='t'){
	$condition="";
	$title="รายละเอียดการอนุมัติ";
}else{
	$condition="and \"appStatus\" ='2'";
	$title="อนุมัติยกเลิกสัญญาเข้าร่วม";
}

//ตรวจสอบว่ารายการนี้ถูกอนุมัติก่อนหน้านี้หรือยัง
$qrydata=pg_query("select a.\"id\",b.\"fullname\" as \"userRequest\",a.\"userStamp\",a.\"resultdelete\",
c.\"fullname\" as \"appUser\",a.\"appStamp\",a.\"appStatus\"
from \"ta_join_main_delete_temp\" a
	left join \"Vfuser\" b on a.\"userRequest\"=b.\"id_user\"
	left join \"Vfuser\" c on a.\"appUser\"=c.\"id_user\"
	where  a.\"deleteid\"='$deleteid' $condition");
$numidno=pg_num_rows($qrydata);
list($id,$userRequest,$userStamp,$resultdelete,$appUser,$appStamp,$appStatus)=pg_fetch_array($qrydata);

if($numidno==0 and $readonly!='t'){ //แสดงว่ารายการนี้อาจได้รับการอนุมัติไปก่อนหน้านี้แล้ว
	echo "<center><h2>ไม่พบเลขที่สัญญานี้รออนุมัติ อาจได้รับการอนุมัติก่อนหน้านี้ กรุณาตรวจสอบ<h2></center>";
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $title;?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function confirmappv(no){
	if(no=='1'){
		if(confirm('ยืนยันการอนุมัติ')==true){
			return true;
		}
		else{return false;}
	}
	else{
		if(confirm('ยืนยันการไม่อนุมัติ')==true){
			return true;
		}
		else{return false;}
	}

}
</script>

</head>
<body>

<div style="text-align:center;"><h2><?php echo $title;?></h2></div>
<div style="padding-left:40px;width:500px;margin:0 auto;"><span onclick="javascript:popU('ta_join_payment_view_new.php?idno_names=<?php echo $id;?>&readonly=t','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=800')" title="ดูข้อมูลเข้าร่วม" style="cursor:pointer"><u><b>ดูข้อมูลเข้าร่วม</b></u></span></div>
<table width="500" border="0" cellpadding="1" cellspacing="1" align="center">
<tr>
	<td valign="top" colspan="2">
		<?php include"ta_join_data.php";?>
	</td>
</tr>
<tr><td valign="top" align="right"><b>เหตุผลที่ขอยกเลิก :</b></td><td><textarea cols="40" rows="4" readonly="true"><?php echo $resultdelete;?></textarea></td></tr>
<tr><td align="right"><b>ผู้ขอยกเลิก :</b></td><td> <?php echo $userRequest;?></td></tr>
<tr><td align="right"><b>วันเวลาที่ขอยกเลิก :</b></td><td> <?php echo $userStamp;?></td></tr>
<?php
if($readonly=='t'){ //
	if($appStatus==0){
		$txtapp='ไม่อนุมัติ';
	}else if($appStatus==1){
		$txtapp='อนุมัติ';
	}
	echo "
	<tr><td align=\"right\"><b>ผู้อนุมัติ :</b></td><td>$appUser</td></tr>
	<tr><td align=\"right\"><b>วันเวลาที่อนุมัติ :</b></td><td>$appStamp</td></tr>
	<tr><td align=\"right\"><b>สถานะการอนุมัติ :</b></td><td><font color=red><b>$txtapp</b></font></td></tr>
	<tr height=\"50\"><td align=\"center\" colspan=\"2\"><hr><input type=\"button\" value=\"ปิด\" onclick=\"window.close()\"></td></tr>";
}else{
?>
<tr align="center">
	<td colspan="2"><hr>
	<form name="my" method="post" action="process_cancel.php">
		<input name="btn1" id="btn1" type="submit" value="อนุมัติ" onclick="return confirmappv('1')"/>
		<input name="btn2" id="btn2" type="submit" value="ไม่อนุมัติ" onclick="return confirmappv('0')"/>
		<input type="hidden" name="deleteid" id="deleteid" value="<?php echo $deleteid;?>">
		<input type="hidden" name="id" id="id" value="<?php echo $id;?>">		
	</form>	
	</td>
</tr>
<?php } ?>
</table>
