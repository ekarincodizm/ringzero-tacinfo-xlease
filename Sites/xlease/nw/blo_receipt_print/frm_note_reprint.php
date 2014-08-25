<?php
include("../../config/config.php");

$id = pg_escape_string($_GET["id"]);
$qry_sel = pg_query("SELECT * FROM \"blo_receipt_reprint\" where id = '$id'");
$result = pg_fetch_array($qry_sel);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เหตุผลที่ขอพิมพ์</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<div style="background-color:#D0D0D0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:0px; text-align:left">
<b>เลขที่ใบเสร็จ</b> : <?php echo $result["receiptid"]; ?>
</div>

<div style="background-color:#F0F0F0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px; text-align:left">
<b>เหตุผลที่ขอพิมพ์</b> <br /><textarea cols="80" rows="5" readonly><?php echo $result["reprint_reason"]; ?></textarea>
</div>

<div align="center">
<input type="button" value=" ปิด " onclick="window.close();" style="width:100px;height:50px;" >
</div>
</body>
</html>






