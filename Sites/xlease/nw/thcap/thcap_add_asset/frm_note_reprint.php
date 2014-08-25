<?php
include("../../../config/config.php");

$ascenID = $_GET["ascenID"];
$qry_sel = pg_query("SELECT \"noteapp\" FROM \"thcap_asset_biz_detail_central\" where \"ascenID\" = '$ascenID'");
list($note) = pg_fetch_array($qry_sel);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เหตุผลที่ไม่อนุมัติ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<div style="background-color:#F0F0F0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px; text-align:left">
<b>เหตุผลที่ไม่อนุมัติ</b> <br /><textarea cols="80" rows="5" readonly><?php echo $note; ?></textarea>
</div>

<div align="center">
<input type="button" value=" ปิด " onclick="window.close();" style="width:100px;height:50px;" >
</div>
</body>
</html>






