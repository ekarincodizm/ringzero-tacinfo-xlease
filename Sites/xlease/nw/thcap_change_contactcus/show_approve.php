<?php
session_start();
include("../../config/config.php");
$contractID=trim($_GET["contractID"]);
$waitapp=trim($_GET["waitapp"]);

//ค้นหาข้อมูลที่แก้ไข
$qrydata=pg_query("select * from \"thcap_ContactCus_Temp\" a
where \"contractID\"='$contractID' and \"appStatus\" ='2' ");
$numidno=pg_num_rows($qrydata);

if($numidno==0){ //แสดงว่ารายการนี้อาจได้รับการอนุมัติไปก่อนหน้านี้แล้ว
	echo "<center><h2>ไม่พบเลขที่สัญญานี้รออนุมัติ อาจได้รับการอนุมัติก่อนหน้านี้ กรุณาตรวจสอบ<h2></center>";
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติเปลี่ยนลำดับคนในสัญญา </title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

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
		if(confirm('ยืนยันการอนุมัติ')==true){return true;}
		else{return false;}
	}
	else{
		if(confirm('ยืนยันการไม่อนุมัติ')==true){return true;}
		else{return false;}
	}
}
</script>

</head>
<body onload="$('#result').focus();">

<div style="text-align:center;"><h2>อนุมัติเปลี่ยนลำดับคนในสัญญา</h2></div>
<div><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u>เลขที่สัญญา : <?php echo $contractID;?></u></font></span></div>
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>
<?php
//วน 2 รอบเพื่อเปรียบเทียบ
for($i=1;$i<=2;$i++){
	if($i==1){
		$txt= "ข้อมูลเก่า";
		$color="#CECECE";
		$color1="#E8E8E8";
		$order="order by \"CusState\",\"ranking\"";
	}else{
		$txt= "ข้อมูลใหม่";
		$color="#CDB7B5";
		$color1="#FFE4E1";
		$order="order by \"CusState\",\"ranking_New\"";
	}
	
	//ค้นหาข้อมูลที่แก้ไข
	$qrydata=pg_query("select a.*,b.\"fullname\" as \"addUser\",c.\"thcap_fullname\" as \"cusname\" from \"thcap_ContactCus_Temp\" a
	left join \"Vfuser\" b on a.\"addUser\"=b.\"id_user\"
	left join \"vthcap_ContactCus_detail\" c on a.\"CusID\"=c.\"CusID\" and a.\"contractID\"=c.\"contractID\"
	where a.\"contractID\"='$contractID' and \"appStatus\" ='2' $order");
	
	?>
	<td valign="top">
		<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="<?php echo $color;?>">
			<tr ><td colspan="4"><b>(<?php echo $txt;?>)</b></td></tr>
			<?php
			
			while($res_app=pg_fetch_array($qrydata)){
				$CusID=$res_app["CusID"]; // รหัสลูกค้า
				$CusState=$res_app["CusState"];//สถานะลูกค้า 
				$CusID=$res_app["CusID"];//รหัสลูกค้า
				$addUser=$res_app["addUser"];//รหัสพนักงานที่ทำรายการ
				$addStamp=$res_app["addStamp"];//-- วันเวลาที่ทำรายการ
				$cusname=$res_app["cusname"];//-- ชื่อลูกค้า
				
				if($i==1){
					$ranking=$res_app["ranking"];//จัดลำดับของลูกค้า
				}else{
					$ranking=$res_app["ranking_New"];//ลำดับของลูกค้าที่ถูกจัดใหม่
				}
				
				if($ranking==""){
					$ranking="-";
				}
				if($CusState==0){
					$txtcus="ผู้กู้หลัก/ผู้เช่าซื้อ";
				}else if($CusState==1){
					$txtcus="ผู้กู้ร่วม";
				}else if($CusState==2){
					$txtcus="ผู้ค้ำ";
				}
				
				?>
				<tr height="30" bgcolor="<?php echo $color1;?>">
					<td align="right"><?php echo $txtcus;?> : </td>
					<td bgcolor="#FFFFFF"><?php echo "$cusname ( คนที่  $ranking)";?></td>
				</tr>
			<?php

			}
			?>
		</table>
	</td>
<?php			
}
?>
</tr>
<?php
if($waitapp=='yes'){
	echo "<tr align=\"center\" height=\"50\"><td colspan=2><input type=\"button\" value=\"ปิด\" onclick=\"window.close()\"></td></tr>";
}else{
?>
<form name="my" method="post" action="process_change.php">
<tr height="30" bgcolor="#FFFFFF">
	<td valign="top" colspan="2"><b>หมายเหตุ :</b><br>
	<textarea name="result" id="result" cols="50" rows="5" ></textarea></td>
</tr>
<tr align="center">
	<td colspan=2><br>
	<input name="btn1" id="btn1" type="submit" value="อนุมัติ" onclick=" return confirmappv('1')"/>
	<input name="btn2" id="btn2" type="submit" value="ไม่อนุมัติ" onclick=" return confirmappv('0')"/>
	<input type="hidden" name="contractID" id="contractID" value="<?php echo $contractID;?>">
	</td>
</tr>
</form>
<?php } ?>
</table>
