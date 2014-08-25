<?php 
session_start();
include("../config/config.php");

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>

<div class="wrapper">
 
<fieldset><legend><b>สร้างเลขที่บัญชี</b></legend>

<form method="post" action="ac_table_add_ok.php">
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center">
    <tr>
        <td align="left" width="20%"><b>AcID</b></td>
        <td width="80%"><input type="text" name="acid"></td>
    </tr>
    <tr>
        <td align="left"><b>AcName</b></td>
        <td><input type="text" name="acname"></td>
    </tr>
    <tr>
        <td align="left"><b>AcType</b></td>
        <td><input type="text" name="actype"></td>
    </tr>
    <tr>
        <td align="left"><b>Status</b></td>
        <td><input type="text" name="status"></td>
    </tr>
    <tr>
        <td align="left"><b>Delable</b></td>
        <td><input type="radio" name="delable" value="false" checked> ไม่ลบ <input type="radio" name="delable" value="true"> ลบ</td>
    </tr>
    <tr>
        <td align="left"><b>ShowOnFS</b></td>
        <td><input type="radio" name="showonfs" value="false"> ไม่แสดง <input type="radio" name="showonfs" value="true" checked> แสดง</td>
    </tr>
    <tr>
        <td align="left"></td>
        <td><input type="submit" value="  เพิ่ม  "> <input type="reset" value=" ยกเลิก "></td>
    </tr>
</table>
</form>

</div>
		</td>
	</tr>
</table>

</body>
</html>