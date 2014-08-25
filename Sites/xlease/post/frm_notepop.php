<?php
include("../config/config.php");

$runrow = $_GET["runrow"];
$qry_sel = pg_query("SELECT * FROM \"NTHead_log_notappvcancel\" where runrow = '$runrow'");
$result = pg_fetch_array($qry_sel);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เหตุผลการไม่อนุมัติ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<div style="background-color:#D0D0D0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:0px; text-align:left">
<b>NTID</b> : <?php echo $result["NTID"]; ?>
</div>

<div style="background-color:#F0F0F0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px; text-align:left">
<b>เหตุผลที่ไม่อนุมัติ</b> : <br /><?php echo $result["noteapp"]; ?>
</div>

<div align="center">
<input type="button" value=" ปิด " onclick="window.close();" style="width:100px;height:50px;" >
</div>
</body>
</html>






