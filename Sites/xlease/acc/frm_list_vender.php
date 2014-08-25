<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }

-->
</style>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <button onclick="window.location='frm_add_vender.php'"> เพิ่ม vender </button>
  <table width="770" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">
  <tr style="background-color:#BFD8F0">
    <td colspan="5"><div align="center">Vender List </div></td>
    </tr>
  <tr style="background-color:#FFFFFF;">
    <td width="90">VenderID</td>
    <td width="200">vender</td>
    <td width="186">Address</td>
    <td width="200">Tel</td>
    <td width="60">edit</td>
  </tr>
  <?php
  include("../config/config.php");
  $sql_vd=pg_query("select * from account.vender");
  while($res_vd=pg_fetch_array($sql_vd))
  {
  ?>
  <tr style="background-color:#FFFFFF;">
    <td><?php echo $res_vd["VenderID"]; ?></td>
    <td><?php echo $res_vd["type_vd"]." ".$res_vd["vd_name"]; ?></td>
    <td><?php echo $res_vd["vd_address"]; ?></td>
    <td><?php echo $res_vd["vd_tel"]; ?></td>
    <td><a href="frm_edit_vender.php?vid=<?php echo $res_vd["VenderID"]; ?>">แก้ไข</a></td>
  </tr>
  <?php
  }
  pg_close();
  ?>
</table>

  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
