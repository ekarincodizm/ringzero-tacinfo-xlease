<?php
session_start();
include("../../config/config.php");

//ตรวจสอบเบื้องต้นว่ารายการนี้อนุมัติหรือยังเพื่อป้องกันการอนุมัติซ้ำ
$querychk = pg_query("select * from public.\"fuser\" where \"password\" is null and \"id_user\"='$_GET[iduser]' ");
$numchk = pg_num_rows($querychk);
if($numchk>0){ //แสดงว่ายังไม่อนุมัติ
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ตั้ง password</title>
</head>

<body>
<div style="color:red; padding:10px; text-align: center;">
ห้าม Share User หรือ Password เด็ดขาด หากมีปัญหาเกิดขึ้น<br>
User ที่มีชื่อเป็นผู้ทำรายการจะต้องเป็นผู้รับผิดชอบ<br><br>
หากมีข้อสงสัยหรือคิดว่าผู้อื่นทราบ password<br>
กรุณาเปลี่ยนใหม่ทันที หรือแจ้งที่ HelpDesk
</div>
<form action="reset_pwd.php" method="post">
<input type="hidden" name="h_id" value="<?php echo $_GET["iduser"]; ?>" />
<table width="500" border="0" cellpadding="1">
  <tr>
    <td colspan="2" style="padding-left:5px;"><div align="left"><strong>ตั้งรหัสเข้าใช้งาน </strong></div></td>
  </tr>
  <tr>
    <td width="138" style="padding-left:5px;">ชื่อ</td>
    <td width="352"><?php echo $_GET["fullname"]; ?></td>
  </tr>
  <tr>
    <td style="padding-left:5px;">username</td>
    <td><?php echo $_GET["uname"]; ?></td>
  </tr>
  <tr>
    <td style="padding-left:5px;">ใส่รหัส เข้าใช้งาน </td>
    <td><input type="text" name="update_pwd" /></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" value="SAVE" /></td>
  </tr>
</table>
</form>
</body>
</html>
<?php
}else{
	echo "<div style=\"text-align:center;padding:20px;\"><h1>รายการนี้ได้รับการอนุมัติไปแล้ว กรุณาตรวจสอบอีกครั้ง !!</h1>";
	echo "<input type=\"button\" value=\" ตกลง \"  onclick=\"javascript:opener.location.reload(true);self.close();\"></div>";
}
?>