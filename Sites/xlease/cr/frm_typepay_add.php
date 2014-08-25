<?php 
session_start();  
include("../config/config.php"); 
$get_id_user = $_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>

</head>
<body>    
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบ TypePay</h1></div>

<div class="wrapper">
<div align="right"><a href="frm_typepay_show.php"><img src="full_page.png" border="0" width="16" height="16" align="absmiddle"> แสดงรายการ</a></div>
<fieldset><legend><B>เพิ่มข้อมูล TypePay</B></legend>

<form id="frm_1" name="frm_1" method="post" action="frm_typepay_add_ok.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="left">
      <td width="20%"><b>TypeID</b></td>
      <td width="80%" class="text_gray"><input type="text" name="typeid" size="20"></td>
   </tr>
   <tr align="left">
      <td><b>TName</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="tname" size="50"></td>
   </tr>
   <tr align="left">
      <td><b>UseVat</b></td>
      <td colspan="3" class="text_gray">
        <input type="radio" name="uservat" value="TRUE">TRUE
        <input type="radio" name="uservat" value="FALSE" checked>FALSE
      </td>
   </tr>
   <tr align="left">
      <td><b>ประเภทใบเสร็จ</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="typerec" size="20"></td>
   </tr>
   <tr align="left">
      <td><b>ฝ่ายที่แสดง</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="typepay" size="20"></td>
   </tr>
</table>
</fieldset>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="บันทึก"></td>
   </tr>
</table>
</form>

</div>
        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>