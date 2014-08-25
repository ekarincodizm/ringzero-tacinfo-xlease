<?php
include("../config/config.php");		
$auto_id=$_GET["auto_id"]; 

$qrychq=pg_query("select \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
       \"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
       \"C_TAX_MON\", \"C_StartDate\", \"fullname\", \"keyStamp\", \"C_CAR_CC\", 
       \"RadioID\", \"CarType\" from \"Carregis_temp\" a
	   left join \"Vfuser\" b on a.\"keyUser\"=b.\"id_user\"
		where \"auto_id\"='$auto_id'");
$reschq=pg_fetch_array($qrychq);
list($C_REGIS,$C_CARNAME,$C_YEAR,$C_REGIS_BY,$C_COLOR,$C_CARNUM,$C_MARNUM,$C_Milage,
$C_TAX_ExpDate,$C_TAX_MON,$C_StartDate,$fullname,$keyStamp,$C_CAR_CC,$RadioID,$CarType)=$reschq;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>แสดงรายละเอียดรถทั้งหมด</title>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h2>- รายละเอียดรถยนต์ -</h2>
	</div>

	<div id="warppage"  style="width:570px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
		<table width="550" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right" width="150" valign="top">ทะเบียนรถ : </td>
			<td bgcolor="#FFFFFF"><?php echo $C_REGIS;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right" valign="top">ยี่ห้อรถ : </td>
			<td bgcolor="#FFFFFF"><?php echo $C_CARNAME;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right"  valign="top">รุ่นปี : </td>
			<td bgcolor="#FFFFFF"><?php echo $C_YEAR;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right"  valign="top">จังหวัดที่จดทะเบียน : </td>
			<td bgcolor="#FFFFFF"><?php echo $C_REGIS_BY;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right">สีรถ : </td>
			<td bgcolor="#FFFFFF"><?php echo $C_COLOR;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right">เลขตัวถัง : </td><td bgcolor="#FFFFFF"><?php echo $C_CARNUM;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right">เลขเครื่องยนต์ : </td>
			<td bgcolor="#FFFFFF"><?php echo $C_MARNUM;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right">ไมล์ : </td>
			<td bgcolor="#FFFFFF"><?php echo $C_Milage;?>
			</td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right">วันที่ต่ออายุภาษี : </td>
			<td bgcolor="#FFFFFF"><?php echo $C_TAX_ExpDate;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA"height="25">
			<td align="right">ค่าภาษี : </td>
			<td bgcolor="#FFFFFF"><?php echo $C_TAX_MON;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA">
			<td align="right" valign="top">วันที่จดทะเบียน : </td>
			<td bgcolor="#FFFFFF"><?php echo $C_StartDate;?></td>
		</tr>
		<tr  bgcolor="#FFCCCC" height="25">
			<td align="right" valign="top">CC รถ : </td>
			<td bgcolor="#FFECEC"><?php echo $C_CAR_CC;?></td>
		</tr>
		<tr  bgcolor="#FFCCCC" height="25">
			<td align="right">เลขวิทยุ : </td>
			<td bgcolor="#FFECEC"><?php echo $RadioID;?></td>
		</tr>
		<tr  bgcolor="#FFCCCC" height="25">
			<td align="right">รูปแบบรถยนต์ : </td>
			<td bgcolor="#FFECEC">
				<?php 
				if($CarType=="0"){
					echo "รถนั่งทั่วไป";
				}else if($CarType=="1"){
					echo "แท็กซี่บริษัท";
				}else if($CarType=="2"){
					echo "แท็กซี่เขียวเหลือง";
				}else if($CarType=="3"){
					echo "แท็กซี่สีอื่นๆ";
				}else{
					echo "ไม่ระบุ";
				}
				?>
			</td>
		</tr>
		<tr>
			<td colspan="4" height="80" bgcolor="#FFFFFF" align="center">
			<input type="button" value="ปิดหน้านี้" onclick="window.close();">
			</td>
		</tr>
		</table>
	<!--</form>-->
	</div>
</div>
</form>
</body>
</html>
