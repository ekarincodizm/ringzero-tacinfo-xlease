<?php
session_start();
include("../../config/config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เปรียบเทียบจำนวนการชักชวนและจับคู่</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper">
				<div style="float:left"><input type="button" value="  กลับ  " onclick="window.location='frm_IndexSummary.php'"></div> 
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding: 10px;"></div>
				<fieldset><legend><B>เงื่อนไขในการเปรียบเทียบจำนวนการชักชวนและจับคู่</B></legend>
					<form method="post" name="form1" action="summary_Compare2.php">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:20px" align="center">
						<tr>
							<td height="30" width="150"></td>
							<td width="10"><input type="radio" name="con_invitecompare" value="1" checked></td><td width="80" align="right"> ระหว่างเดือน</td>
							<td>
								<select name="m_1_1">
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
								ปี ค.ศ.
								<select name="y_1_1">
									<option value="2011">2011</option>
									<option value="2012">2012</option>
									<option value="2013">2013</option>
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2016">2016</option>
									<option value="2017">2017</option>
									<option value="2018">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
									<option value="2022">2022</option>
									<option value="2023">2023</option>
									<option value="2024">2024</option>
									<option value="2025">2025</option>
								</select>
								และ
							</td>
						<tr>
						<tr>
							<td colspan="2" align="right"></td><td align="right">เดือน </td>
							<td>
								<select name="m_1_2">
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
								ปี ค.ศ.
								<select name="y_1_2">
									<option value="2011">2011</option>
									<option value="2012">2012</option>
									<option value="2013">2013</option>
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2016">2016</option>
									<option value="2017">2017</option>
									<option value="2018">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
									<option value="2022">2022</option>
									<option value="2023">2023</option>
									<option value="2024">2024</option>
									<option value="2025">2025</option>
								</select>
							</td>
						</tr>
						<tr><td colspan="3" height="15"></td></tr>
						<tr>
							<td height="30" width="150"></td>
							<td><input type="radio" name="con_invitecompare" value="2"></td><td align="right"> ระหว่างไตรมาส</td>
							<td>
								<select name="m_2_1">
									<option value="1">ที่ 1 (มกราคม - มีนาคม)</option>
									<option value="2">ที่ 2 (เมษายน - มิถุนายน)</option>
									<option value="3">ที่ 3 (กรกฎาคม - กันยายน)</option>
									<option value="4">ที่ 4 (ตุลาคม - ธันวาคม)</option>
								</select>
								ปี ค.ศ.
								<select name="y_2_1">
									<option value="2011">2011</option>
									<option value="2012">2012</option>
									<option value="2013">2013</option>
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2016">2016</option>
									<option value="2017">2017</option>
									<option value="2018">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
									<option value="2022">2022</option>
									<option value="2023">2023</option>
									<option value="2024">2024</option>
									<option value="2025">2025</option>
								</select>
								และ
							</td>
						<tr>
						<tr>
							<td colspan="2" align="right"></td><td align="right">ไตรมาส </td>
							<td>
								<select name="m_2_2">
									<option value="1">ที่ 1 (มกราคม - มีนาคม)</option>
									<option value="2">ที่ 2 (เมษายน - มิถุนายน)</option>
									<option value="3">ที่ 3 (กรกฎาคม - กันยายน)</option>
									<option value="4">ที่ 4 (ตุลาคม - ธันวาคม)</option>
								</select>
								ปี ค.ศ.
								<select name="y_2_2">
									<option value="2011">2011</option>
									<option value="2012">2012</option>
									<option value="2013">2013</option>
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2016">2016</option>
									<option value="2017">2017</option>
									<option value="2018">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
									<option value="2022">2022</option>
									<option value="2023">2023</option>
									<option value="2024">2024</option>
									<option value="2025">2025</option>
								</select>
							</td>
						</tr>
						<td colspan="3" height="15"></td></tr>
						<tr>
							<td height="30" width="150"></td>
							<td><input type="radio" name="con_invitecompare" value="3"></td><td align="right"> ระหว่างปี ค.ศ.</td>
							<td>
								<select name="y_3_1">
									<option value="2011">2011</option>
									<option value="2012">2012</option>
									<option value="2013">2013</option>
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2016">2016</option>
									<option value="2017">2017</option>
									<option value="2018">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
									<option value="2022">2022</option>
									<option value="2023">2023</option>
									<option value="2024">2024</option>
									<option value="2025">2025</option>
								</select>
								และ ปี ค.ศ.
								<select name="y_3_2">
									<option value="2011">2011</option>
									<option value="2012">2012</option>
									<option value="2013">2013</option>
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2016">2016</option>
									<option value="2017">2017</option>
									<option value="2018">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
									<option value="2022">2022</option>
									<option value="2023">2023</option>
									<option value="2024">2024</option>
									<option value="2025">2025</option>
								</select>
							</td>
						<tr>
						<tr><td colspan="4" height="50" align="center"><input type="submit" value=" ค้นหา "></td></tr>
					</table>
					</form>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          

</body>
</html>