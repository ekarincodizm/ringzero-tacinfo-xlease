<?php 
include("../config/config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>คำแนะนำ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../images/act.css"></link>
	<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
<fieldset><legend><b>คำแนะนำในการตั้งรหัสผ่าน(Password)</b></legend>

<font color ="blue"><table width="100%" cellpadding="3" cellspacing="0" border="0">
    <tr>
        <td width="3%"></td>
        <td colspan="2">เพื่อให้มั่นใจว่าข้อมูลของท่านมีความปลอดภัย จึงขอแนะนำให้ท่านสร้างรหัสผ่านที่ปลอดภัยและจำง่าย กฎและคำแนะนำในการสร้างรหัสผ่านดังต่อไปนี้ จะช่วยท่านให้สร้างรหัสผ่านที่มีความปลอดภัย</td>	
    </tr>    
    <tr>
        <td colspan="3"><u><b>กฎในการตั้งรหัสผ่าน(Password)<b></u></td>       
    </tr>
    <tr>
         <td align="right">*</td>
         <td colspan="2">ต้องมีความยาว 6-10 ตัวอักษร</td>	
    </tr>
	    <tr>
         <td align="right">*</td>
         <td colspan="2">ใช้ตัวอักษรภาษาอังกฤษได้ (a-z, A-Z)</td>	
    </tr>
	    <tr>
         <td align="right">*</td>
         <td colspan="2">ใช้ตัวเลขได้ (0-9)</td>	
    </tr>
	    <tr>
         <td align="right">*</td>
         <td colspan="2">ต้องไม่ใช้คำว่า "password"</td>	
    </tr>
	<tr>
         <td align="right">*</td>
         <td colspan="2">ต้องไม่เหมือนหรือคล้ายกับชื่อผู้ใช้</td>	
    </tr>
	<tr>
        <td colspan="3"><u><b>คำแนะนำ</u></b></td> 		
    </tr>
	<tr>
		<td align="right"></td>
        <td colspan="2">1. การสร้างรหัสผ่านที่มีความปลอดภัย</td>
        
    </tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="1">&nbsp;&nbsp;&nbsp;* ไม่ควรใช้คำที่เดาได้ง่าย เช่น คำในพจนานุกรม หรือชื่อของคน สถานที่ หรือสิ่งของ</td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="1">&nbsp;&nbsp;&nbsp;* ควรใช้การผสมกันของตัวอักษร ตัวเลข และ/หรือสัญญลักษณ์</td>
	</tr>	
	<tr>
		<td colspan="2"></td>
		<td colspan="1">&nbsp;&nbsp;&nbsp;* ไม่ควรใช้ตัวอักษรเรียงลำดับต่อเนื่องกัน (เช่น abcdef) หรือตัวเลขเรียงลำดับต่อเนื่องกัน (เช่น 123456)
หรือแป้นพิมพ์เรียงลำดับต่อเนื่องกัน(เช่น asdfghjkl)
</td>
	</tr>
	<tr>
		<td align="right"></td>
        <td colspan="2">2. การตั้งรหัสผ่านที่จำได้ง่าย</td>        
    </tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="1">&nbsp;&nbsp;&nbsp;* ผสมคำตั้งแต่สองคำขึ้นไปเข้าด้วยกัน แล้วนำไปรวมกับตัวเลข</td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="1">&nbsp;&nbsp;&nbsp;* ย่อกลุ่มคำ หรือสำนวนที่ท่านสามารถจดจำได้</td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="1">&nbsp;&nbsp;&nbsp;* ตัดสระออกจากคำพูดซึ่งเป็นที่ชื่นชอบ แล้วเพิ่มตัวเลขลงไป</td>
	</tr>
	<tr>
		<td align="right"></td>
        <td colspan="2">3. การป้องกันรหัสผ่าน</td>        
    </tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="1">&nbsp;&nbsp;&nbsp;* เก็บรักษารหัสผ่านของท่านไว้ในที่ปลอดภัย</td>
	</tr>
	
	<br>
	<tr>
		<td colspan="3"align="center" >
		<input type="button" name="close" value="  ปิด   " onclick="javascript:window.close();">
		</td>
		
	</tr>
</table>
</font>


</fieldset>


</body>
</html>