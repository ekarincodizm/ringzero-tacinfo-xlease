<?php
session_start();
include("../../config/config.php");
$condition=$_GET["condition"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานสินเชื่อประจำปี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper">
				<div style="float:left"><input type="button" value="  กลับ  " onclick="window.location='frm_Index.php'"></div> 
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding: 10px;"></div> 
				<fieldset><legend><B>เงื่อนไขในการรายงานสินเชื่อประจำปี</B></legend>
					<form method="post" name="form1" action="show_Annual.php">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:20px" align="center">
						<tr height="30">
							<td width="200"></td>
							<td><input type="radio" name="condition" value="1" <?php if($condition==1 || $condition==""){ echo "checked";}?>>ประจำปี พ.ศ.
								<select name="year">
									<option value="1997">2540</option>
									<option value="1998">2541</option>
									<option value="1999">2542</option>
									<option value="2000">2543</option>
									<option value="2001">2544</option>
									<option value="2002">2545</option>
									<option value="2003">2546</option>
									<option value="2004">2547</option>
									<option value="2005">2548</option>
									<option value="2006">2549</option>
									<option value="2007">2550</option>
									<option value="2008">2551</option>
									<option value="2009">2552</option>
									<option value="2010">2553</option>
									<option value="2011">2554</option>
									<option value="2012">2555</option>
									<option value="2013">2556</option>
									<option value="2014">2557</option>
									<option value="2015">2558</option>
									<option value="2016">2559</option>
									<option value="2017">2560</option>
									<option value="2018">2561</option>
									<option value="2019">2562</option>
									<option value="2020">2563</option>
									<option value="2021">2564</option>
									<option value="2022">2565</option>
									<option value="2023">2566</option>
									<option value="2024">2567</option>
									<option value="2025">2568</option>
								</select>
							</td>
						</tr>
						<tr height="30">
							<td></td>
							<td><input type="radio" name="condition" value="2" <?php if($condition==2){ echo "checked";}?>>เปรียบเทียบระหว่างปี พ.ศ.
								<select name="y1">
									<option value="1997">2540</option>
									<option value="1998">2541</option>
									<option value="1999">2542</option>
									<option value="2000">2543</option>
									<option value="2001">2544</option>
									<option value="2002">2545</option>
									<option value="2003">2546</option>
									<option value="2004">2547</option>
									<option value="2005">2548</option>
									<option value="2006">2549</option>
									<option value="2007">2550</option>
									<option value="2008">2551</option>
									<option value="2009">2552</option>
									<option value="2010">2553</option>
									<option value="2011">2554</option>
									<option value="2012">2555</option>
									<option value="2013">2556</option>
									<option value="2014">2557</option>
									<option value="2015">2558</option>
									<option value="2016">2559</option>
									<option value="2017">2560</option>
									<option value="2018">2561</option>
									<option value="2019">2562</option>
									<option value="2020">2563</option>
									<option value="2021">2564</option>
									<option value="2022">2565</option>
									<option value="2023">2566</option>
									<option value="2024">2567</option>
									<option value="2025">2568</option>
								</select>
								และปี พ.ศ.
								<select name="y2">
									<option value="1998">2541</option>
									<option value="1999">2542</option>
									<option value="2000">2543</option>
									<option value="2001">2544</option>
									<option value="2002">2545</option>
									<option value="2003">2546</option>
									<option value="2004">2547</option>
									<option value="2005">2548</option>
									<option value="2006">2549</option>
									<option value="2007">2550</option>
									<option value="2008">2551</option>
									<option value="2009">2552</option>
									<option value="2010">2553</option>
									<option value="2011">2554</option>
									<option value="2012">2555</option>
									<option value="2013">2556</option>
									<option value="2014">2557</option>
									<option value="2015">2558</option>
									<option value="2016">2559</option>
									<option value="2017">2560</option>
									<option value="2018">2561</option>
									<option value="2019">2562</option>
									<option value="2020">2563</option>
									<option value="2021">2564</option>
									<option value="2022">2565</option>
									<option value="2023">2566</option>
									<option value="2024">2567</option>
									<option value="2025">2568</option>
								</select>
							</td>
						</tr>
						<tr height="30">
							<td></td>
							<td><input type="radio" name="condition" value="3" <?php if($condition==3){ echo "checked";}?>>
							เปรียบเทียบหลายปี ระหว่าง พ.ศ.
								<select name="year1" id="year1">
									<option value="1997">2540</option>
									<option value="1998">2541</option>
									<option value="1999">2542</option>
									<option value="2000">2543</option>
									<option value="2001">2544</option>
									<option value="2002">2545</option>
									<option value="2003">2546</option>
									<option value="2004">2547</option>
									<option value="2005">2548</option>
									<option value="2006">2549</option>
									<option value="2007">2550</option>
									<option value="2008">2551</option>
									<option value="2009">2552</option>
									<option value="2010">2553</option>
									<option value="2011">2554</option>
									<option value="2012">2555</option>
									<option value="2013">2556</option>
									<option value="2014">2557</option>
									<option value="2015">2558</option>
									<option value="2016">2559</option>
									<option value="2017">2560</option>
									<option value="2018">2561</option>
									<option value="2019">2562</option>
									<option value="2020">2563</option>
									<option value="2021">2564</option>
									<option value="2022">2565</option>
									<option value="2023">2566</option>
									<option value="2024">2567</option>
									<option value="2025">2568</option>
								</select>
								ถึง
								<select name="year2" id="year2">
									<option value="1998">2541</option>
									<option value="1999">2542</option>
									<option value="2000">2543</option>
									<option value="2001">2544</option>
									<option value="2002">2545</option>
									<option value="2003">2546</option>
									<option value="2004">2547</option>
									<option value="2005">2548</option>
									<option value="2006">2549</option>
									<option value="2007">2550</option>
									<option value="2008">2551</option>
									<option value="2009">2552</option>
									<option value="2010">2553</option>
									<option value="2011">2554</option>
									<option value="2012">2555</option>
									<option value="2013">2556</option>
									<option value="2014">2557</option>
									<option value="2015">2558</option>
									<option value="2016">2559</option>
									<option value="2017">2560</option>
									<option value="2018">2561</option>
									<option value="2019">2562</option>
									<option value="2020">2563</option>
									<option value="2021">2564</option>
									<option value="2022">2565</option>
									<option value="2023">2566</option>
									<option value="2024">2567</option>
									<option value="2025">2568</option>
							</select>
							</td>
						</tr>
						<tr><td colspan="2" height="80" align="center"><input type="submit" value=" ค้นหา "></td></tr>
					</table>
					</form>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          

</body>
</html>