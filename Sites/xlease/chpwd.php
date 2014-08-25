<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>เปลี่ยน password</title>
</head>

<body>

<form action="reset_pwd.php" method="post">
<input type="hidden" name="h_id" value="<?php echo pg_escape_string($_GET["iduser"]); ?>" />
<table width="500" border="0" cellpadding="1">
  <tr>
    <td colspan="2" style="padding-left:5px;"><div align="left"><strong>เปลี่ยนรหัสเข้าใช้งาน </strong></div></td>
  </tr>
  <tr>
    <td width="138" style="padding-left:5px;">ชื่อ</td>
    <td width="352"><?php echo pg_escape_string($_GET["fullname"]); ?></td>
  </tr>
  <tr>
    <td style="padding-left:5px;">username</td>
    <td><?php echo pg_escape_string($_GET["uname"]); ?></td>
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
