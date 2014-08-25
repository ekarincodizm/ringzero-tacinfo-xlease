<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION["session_company_name"]; ?></title>
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
	<h1 class="style4"> AV.LEASING</h1>
</div>

<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
	<div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
	<div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
	<div class="style5" style="width:auto; height:100px;"> 
		<form method="post" name="form1" action="process_nw_transfer.php">
		<table width="800" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
			<tr style="background-color:#FCF1C5;">
				<td colspan="3">รายการโอน</td><td align="right" width="20"><button onclick="window.location='frm_transpaydate.php'">BACK</button></td>
			</tr> 
			<tr style="background-color:#F5F7E1;">
				<td align="center" height="80" colspan="4">ส่งเงินไปสาขา
					<select name="type_branch">
						<option value="2">จรัญ/ติวานนท์</option>
					</select>
					<input type="hidden" name="ref1" value="<?php echo $_GET["r1"];?>">
					<input type="hidden" name="ref2" value="<?php echo $_GET["r2"];?>">
					<input type="hidden" name="tr_date" value="<?php echo $_GET["trd"];?>">
					<input type="hidden" name="PostID" value="<?php echo $_GET["plog"];?>">
					<input type="submit" value="บันทึก">
				</td>
			</tr>
		</table>	
		</form>
    </div>
</div>
</body>
</html>
