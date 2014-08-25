<?php 
session_start();  
include("../config/config.php"); 
$id = pg_escape_string($_GET['id']);
$model = pg_escape_string($_GET['model']);
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
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div align="right"><a href="frm_typegas_show.php"><img src="full_page.png" border="0" width="16" height="16" align="absmiddle"> แสดงรายการ</a></div>
<fieldset><legend><B>แก้ไขบริษัท Gas</B></legend>

<?php
$qry_name=pg_query("SELECT * FROM \"GasCompany\" where \"coid\" = '$id' AND \"model\" = '$model' ");
if($res_name=pg_fetch_array($qry_name)){
    $name = $res_name["coname"];
    $model = $res_name["model"];
    $cost = $res_name["cocost"];   
    $price_tank = $res_name["price_tank"];
    $price_device = $res_name["price_device"];
    $address = $res_name["address"];
    $phone = $res_name["phone"];
    $memo = $res_name["memo"];
}
?>

<form id="frm_1" name="frm_1" method="post" action="frm_typegas_edit_ok.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="left">
      <td width="20%"><b>รหัสบริษัท</b></td>
      <td width="80%" class="text_gray"><input type="hidden" name="id" size="20" maxlength="5" value="<?php echo $id; ?>"><?php echo $id; ?></td>
   </tr>
   <tr align="left">
      <td><b>ชื่อบริษัท</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="name" size="50" maxlength="50" value="<?php echo $name; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>รุ่น/ประเภท</b></td>
      <td colspan="3" class="text_gray"><input type="hidden" name="model" value="<?php echo $model; ?>"><?php echo $model; ?></td>
   </tr>
   <tr align="left">
      <td><b>ราคาต้นทุน</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="cost" size="20" value="<?php echo $cost; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>ราคาขายเฉพาะถัง</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="price_tank" size="20" value="<?php echo $price_tank; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>ราคาขายเฉพาะอุปกรณ์</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="price_device" size="20" value="<?php echo $price_device; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>ที่อยู่</b></td>
      <td colspan="3" class="text_gray"><textarea name="address" rows="4" cols="50"><?php echo $address; ?></textarea></td>
   </tr>
   <tr align="left">
      <td><b>เบอร์โทร</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="phone" size="50" value="<?php echo $phone; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>หมายเหตุ</b></td>
      <td colspan="3" class="text_gray"><textarea name="memo" rows="4" cols="50"><?php echo $memo; ?></textarea></td>
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