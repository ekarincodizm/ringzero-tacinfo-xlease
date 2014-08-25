<?php
session_start();
include("../../config/config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>จัดการจำนวนงวดสูงสุด - ต่ำสุด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
 <script>
function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.form1.height_term.value=="") {
	theMessage = theMessage + "\n -->  กรุณากรอกค่าสูงสุด";
	}

	if (document.form1.low_term.value=="") {
	theMessage = theMessage + "\n -->  กรุณาำกรอกค่าต่ำสุด";
	}

	if (document.form1.limit_term.value=="") {
	theMessage = theMessage + "\n -->  กรุณากรอก Limit กันลูกค้าอู่";
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
	return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ((charCode > 31 && charCode != 45) && (charCode < 48 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		return false;
	}
	return true;
}
</script>   
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper">
				<div style="float:left"><input type="button" value="  กลับ  " onclick="window.location='frm_Setup.php'"></div> 
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding-bottom: 10px;"></div>   
				
				<fieldset><legend><B>จัดการจำนวนงวดสูงสุด - ต่ำสุด</B></legend>
					<div style="padding:20px;">
					<table width="90%" cellpadding="1" cellspacing="1" border="0" bgcolor="#FFFFFF" align="center">
						<tr height="50" bgcolor="#FFFFFF">
							<td align="center"><b>จำนวนงวดคงเหลือจากงวดสุดท้ายที่ต้องการให้แสดงในระบบ</b>
							</td>
						</tr>
						<?php
							
							$qry_max=pg_query("SELECT MAX(\"setupID\") AS \"maxsetup\" FROM refinance.\"setup_term\"");
							if($res_max=pg_fetch_array($qry_max)){
								$maxsetup=$res_max["maxsetup"];
							}
							
							if($maxsetup == "" ){
								$height_term = "ยังไม่ตั้งค่า";
								$low_term = "ยังไม่ตั้งค่า";
								$limit_term ="ยังไม่ตั้งค่า";
							}else{
								$qry_term=pg_query("SELECT * FROM refinance.\"setup_term\" where \"setupID\" = '$maxsetup'");
								if($res_term=pg_fetch_array($qry_term)){
									$height_term=$res_term["height_term"];
									$low_term=$res_term["low_term"];
									$limit_term=$res_term["limit_term"];
								}
							}							
						?>
						<tr height="50" bgcolor="#FFFFFF">
							<td align="center"><b>ค่าสูงสุด :</b> <font color=red><u><?php echo $height_term;?></u></font>, <b>ค่าต่ำสุด :</b> <font color=red><u><?php echo $low_term;?></u></font>, <b>Limit ลูกค้าอู่ :</b> <font color=red><u><?php echo $limit_term;?></u></font></td>
						</tr>
					</table>
					
					<form method="post" name="form1" action="process_setupterm.php">
					<fieldset><legend><B>กำหนดค่า</B></legend>
					<table width="90%" cellpadding="1" cellspacing="1" border="0" bgcolor="#FFFFFF" align="center">
						<tr height="50" bgcolor="#FFFFFF">
							<td align="center"><b>ค่าสูงสุด :</b> <input type="text" name="height_term" size="10" onkeypress="return check_number(event);" style="text-align:right"> <b>ค่าต่ำสุด :</b> <input type="text" name="low_term" size="10" onkeypress="return check_number(event);" style="text-align:right"> <b>Limit ลูกค้าอู่ :</b> <input type="text" name="limit_term" size="10" onkeypress="return check_number(event);" style="text-align:right"></td>
						</tr>
					</table>
					</fieldset>
					<table width="90%" border="0" align="center">
						<tr height="50" align="center"><td><input type="submit"value="บันทึก" onclick="return checkdata()"><input type="reset"value="ยกเลิก"></td></tr>
					</table>
					</form>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          

</body>
</html>