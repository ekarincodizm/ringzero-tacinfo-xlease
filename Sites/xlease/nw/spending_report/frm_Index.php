<?php
include("../../config/config.php");
$now_date = nowDate();
$now_year = date('Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">

    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:11px;
    text-align:center;
}
</style>

</head>
<body>
<!-- <form method="POST" action="../../pChart/reportSpending.php" target="_blank"> -->
<form method="POST" action="../../pChart/reportSpending.php" target="_blank">
<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>รายงานสถิติการใช้จ่ายเงินรวม</B></legend>

<div align="center">
<h2>กรุณาเลือกรูปแบบการดูสถิติการใช้จ่ายเงิน</h2>
</div>
<table width="850" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td><fieldset><legend>เลือกช่วงที่สนใจ</legend>
<input type="radio" name="SelectChart" value="a1" checked="checked">ดูข้อมูลรวมในแต่ละเดือน<br>
<input type="radio" name="SelectChart" value="a2">ดูข้อมูลแต่ละวันในเดือน
	<select id="month" name="month">
		<option value="01">มกราคม</option>
		<option value="02">กุมภาพันธ์</option>
		<option value="03">มีนาคม</option>
		<option value="04">เมษายน</option>
		<option value="05">พฤษภาคม</option>
		<option value="06">มิถุนายน</option>
		<option value="07">กรกฎาคม</option>
		<option value="08">สิงหาคม</option>
		<option value="09">กันยายน</option>
		<option value="10">ตุลาคม</option>
		<option value="11">พฤศจิกายน</option>
		<option value="12">ธันวาคม</option>
	</select>
<br><br>
</fieldset></td>

<td><fieldset><legend>เปรียบเทียบกับช่วงเวลา</legend>
<input type="radio" name="SelectChartB" value="3" checked="checked">ข้อมูลปี
	<select id="year1" name="year1">
	<?php
		for($i=10 ; $i >= 0 ; $i--)
		{
			$this_year = $now_year - $i;
			$this_year_th = $this_year + 543;
			echo "<option value=$this_year selected=\"selected\">$this_year_th</option>";
		}
	?>
	</select>
	เทียบกับ 3 ปีย้อนหลัง<br>
<input type="radio" name="SelectChartB" value="5">ข้อมูลปี
	<select id="year2" name="year2">
	<?php
		for($i=10 ; $i >= 0 ; $i--)
		{
			$this_year = $now_year - $i;
			$this_year_th = $this_year + 543;
			echo "<option value=$this_year selected=\"selected\">$this_year_th</option>";
		}
	?>
	</select>
	เทียบกับ 5 ปีย้อนหลัง<br>
<input type="radio" name="SelectChartB" value="2">เปรียบเทียบกับเฉพาะปี
	<select id="year3" name="year3">
	<?php
		for($i=10 ; $i >= 0 ; $i--)
		{
			$this_year = $now_year - $i;
			$this_year_th = $this_year + 543;
			if($i==1)
			{
				echo "<option value=$this_year selected=\"selected\">$this_year_th</option>";
			}
			else
			{
				echo "<option value=$this_year>$this_year_th</option>";
			}
		}
	?>
	</select>
	กับปี
	<select id="year4" name="year4">
	<?php
		for($i=10 ; $i >= 0 ; $i--)
		{
			$this_year = $now_year - $i;
			$this_year_th = $this_year + 543;
			echo "<option value=$this_year selected=\"selected\">$this_year_th</option>";
		}
	?>
	</select>
</fieldset></td>

<td><fieldset><legend>เปรียบเทียบกับข้อมูลใด</legend>
<input type="radio" name="SelectWhat" value="w1" checked="checked">ไม่มี (เฉพาะสถิติการใช้จ่ายเงินอย่างเดียว)<br>
<input type="radio" name="SelectWhat" value="w2">ยอดสินเชื่อที่ปล่อยรวม
<br><br>
</fieldset></td>

<td><input type="submit" value="แสดงกราฟ"></td>

</tr>
</table>
</form>
<div id="divshow" style="margin-top:10px"></div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>