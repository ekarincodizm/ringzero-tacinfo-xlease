<?php 
session_start();  
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

</head>
<body>    

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div align="right"><a href="frm_typegas_show.php"><img src="full_page.png" border="0" width="16" height="16" align="absmiddle"> แสดงรายการ</a></div>
<fieldset><legend><B>เพิ่มบริษัท Gas</B></legend>

<form id="frm_1" name="frm_1" method="post" action="frm_typegas_add_ok.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="left">
      <td width="20%"><b>รหัสบริษัท</b></td>
      <td width="80%" class="text_gray"><input type="text" name="id" size="20" maxlength="5"></td>
   </tr>
   <tr align="left">
      <td><b>ชื่อบริษัท</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="name" size="50" maxlength="50"></td>
   </tr>
   <tr align="left">
      <td><b>รุ่น/ประเภท</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="model" size="20"> เช่น "LPG หัวฉีด"</td>
   </tr>
   <tr align="left">
      <td><b>ราคาต้นทุน</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="cost" size="20"></td>
   </tr>
   <tr align="left">
      <td><b>ราคาขายเฉพาะถัง</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="price_tank" size="20"></td>
   </tr>
   <tr align="left">
      <td><b>ราคาขายเฉพาะอุปกรณ์</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="price_device" size="20"></td>
   </tr>
   <tr align="left">
      <td><b>ที่อยู่</b></td>
      <td colspan="3" class="text_gray"><textarea name="address" rows="4" cols="50"></textarea></td>
   </tr>
   <tr align="left">
      <td><b>เบอร์โทร</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="phone" size="50"></td>
   </tr>
   <tr align="left">
      <td><b>หมายเหตุ</b></td>
      <td colspan="3" class="text_gray"><textarea name="memo" rows="4" cols="50"></textarea></td>
   </tr>
</table>

<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="บันทึก"> <input type="button" value=" Back " onclick="location.href='frm_typegas_show.php'"></td>
   </tr>
</table>
</form>

</fieldset>

<div align="center"><br><input type="button" value="กลับหน้าหลัก" onclick="location.href='../list_menu.php'"></div>

        </td>
    </tr>
</table>

</body>
</html>