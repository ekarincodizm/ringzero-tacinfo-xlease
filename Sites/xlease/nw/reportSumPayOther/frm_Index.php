<?php
session_start();
include("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานสรุปรายได้อื่นๆ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
<script language="javascript">
function checkdata() {
	if(document.form1.year2.value <= document.form1.year1.value) {
		alert("กรุณาเลือกปีเริ่มต้นน้อยกว่าปีสิ้นสุด");
		return false;
	}else{
		return true;
	}
}
</script>    
</head>
<body>
<form method="post" name="form1" action="frm_Report.php">
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr><td align="center"><h1>รายงานสรุปรายได้อื่นๆ</h1></td></tr>
	<tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper">
				<div align="right"></div> 
				<fieldset><legend><B>เงื่อนไขการรายงาน</B></legend>
					<table width="100%" border="0" align="center">
						<tr>
							<td align="right" width="40%"><b>รายการรับชำระ</b></td><td width="10"><b>:</b></td>
							<td>
								<select name="TypeID">
								<?php
									$query_type=pg_query("select \"TypeID\",\"TName\" from \"TypePay\" where \"TypeRec\"='N' order by \"TypeID\"");
									while($res_type=pg_fetch_array($query_type)){
									?>
									<option value="<?php echo $res_type["TypeID"];?>"><?php echo $res_type["TName"];?></option>
									<?php
									}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right"><b>ปีที่รายงาน </b></td><td><b>:</b></td>
							<td>ตั้งแต่ พ.ศ.
								<select name="year1">
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
								ถึง พ.ศ.
								<select name="year2">
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
						<tr>
							<td colspan="3" height="50" align="center"><input type="submit" value="ตกลง" onclick="return checkdata();"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
						</tr>
					</table>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          
</form>
</body>
</html>