<?php
session_start();
include("../../config/config.php");
$auto_id=trim($_GET["auto_id"]);
$contractID=trim($_GET["contractID"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายละเอียดในการเปลี่ยนลำดับ </title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body onload="$('#result').focus();">

<div style="text-align:center;"><h2>รายละเอียดในการเปลี่ยนลำดับ</h2></div>
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
		$order="order by a.\"CusState\",a.\"ranking\"";
	}else{
		$txt= "ข้อมูลที่ขอแก้ไข";
		$color="#CDB7B5";
		$color1="#FFE4E1";
		$order="order by a.\"CusState\",a.\"ranking_New\"";
	}
	
	//ค้นหาข้อมูลที่แก้ไข
	$qrydata=pg_query("select a.\"contractID\",a.ranking,a.\"ranking_New\",a.\"CusState\",c.\"thcap_fullname\" as \"cusname\" from   \"thcap_ContactCus_Temp\" a
	inner join  
	(select * from  \"thcap_ContactCus_Temp\"  
	where auto_id='$auto_id') b on a.\"contractID\"=b.\"contractID\" and a.\"addUser\"=b.\"addUser\" and a.\"addStamp\"=b.\"addStamp\" and 
	a.\"appUser\"=b.\"appUser\" and a.\"appStamp\"=b.\"appStamp\" and a.\"appStatus\"=b.\"appStatus\" 
	left join \"vthcap_ContactCus_detail\" c on a.\"CusID\"=c.\"CusID\" and a.\"contractID\"=c.\"contractID\" $order");
	
	?>
	<td valign="top">
		<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="<?php echo $color;?>">
			<tr ><td colspan="4"><b>(<?php echo $txt;?>)</b></td></tr>
			<?php
			
			while($res_app=pg_fetch_array($qrydata)){
				$CusState=$res_app["CusState"];//สถานะลูกค้า 
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
<tr align="center" height="50"><td colspan=2><input type="button" value="ปิด" onclick="window.close()"></td></tr>
</table>
