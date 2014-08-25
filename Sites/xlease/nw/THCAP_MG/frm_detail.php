<?php
//session_start();
include("../../config/config.php");
$mgSettingID=$_GET["mgSettingID"];

$query=pg_query("select * from public.\"thcap_mg_setting\" where \"mgSettingID\" = '$mgSettingID'");
$result=pg_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>คำอธิบายรายละเอียดประเภทสินเชื่อ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<table width="550" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
    <td>      
		<div class="wrapper">
			<fieldset><legend><B>รายละเอียด</B></legend>	
				<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
				<tr align="left">
					<td align="right" valign="top"><b>วันที่เริ่มบังคับใช้</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php echo $result["mgsActiveDate"];?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>อัตราดอกเบี้ยสูงสุดที่กฎหมายกำหนด</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["lawMaxInterest"];?></td>
					<td>%</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>จำนวนเดือนที่กู้ได้สูงสุดตามที่กฎหมายกำหนด</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["lawMaxMonthTerm"];?></td>
					<td>เดือน</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>อัตราภาษีมูลค่าเพิ่มที่กฎหมายกำหนด</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["lawVATRate"];?></td>
					<td>%</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>อัตราภาษีธุรกิจเฉพาะที่กฎหมายกำหนด</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["lawSBTRate"];?></td>
					<td>%</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>อัตราภาษีท้องถิ่นที่กฎหมายกำหนด</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["lawLTRate"];?></td>
					<td>%</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ค่าติดตามทวงถามประจำเดือน</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["comPenaltyC"];?></td>
					<td>บาท</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>อัตราดอกเบี้ยสูงสุดที่บริษัทกำหนด</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["comMaxInterest"];?></td>
					<td>%</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ระยะเวลาการผ่อนสูงสุดที่บริษัทกำหนด</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["comMaxMonthTerm"];?></td>
					<td>เดือน</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ค่าติดตามกรณีค้างชำระ</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["comPenaltyD"];?></td>
					<td>บาท</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ค่าเตือนโดยทนาย</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["comLawyerFee"];?></td>
					<td>บาท</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ค่าปรับปิดบัญชีก่อนกำหนด (คิดจากยอดกู้เริ่มต้น)</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["comCloseAccFee"];?></td>
					<td>%</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ค่าติดตามทวงถามกรณีมีการฟ้องร้อง</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="right"><?php echo $result["comPenaltyF"];?></td>
					<td>บาท</td>
				</tr>
				<tr align="center">
				  <td colspan=3 height="50"><input name="button" type="button" onclick="javascript:window.close();" value=" Close " /></td>
				</tr>
				</table>
			</fieldset> 
		</div>
    </td>
</tr>
</table>         
</body>
</html>