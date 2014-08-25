<?php
session_start();
include("../../config/config.php");
$auto_id=trim($_GET["auto_id"]);

//ค้นหาข้อมูลที่แก้ไข
$qrydata=pg_query("select \"result\" from \"Carnum_Temp\" where auto_id='$auto_id'");
list($result)=pg_fetch_array($qrydata);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>หมายเหตุในการอนุมัติหรือไม่อนุมัติ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
<div style="text-align:center;"><h2>หมายเหตุในการอนุมัติหรือไม่อนุมัติ</h2></div>
<fieldset><legend><B>รายละเอียด</B></legend>
	<div style="text-align:center;padding:10px;" class="text_gray"><textarea cols="40" rows="4" readonly="true"><?php echo $result;?></textarea></div>
</fieldset> 
<div style="text-align:center;padding:10px;"><input type="button" value="ปิด" onclick="window.close();" style="width:100px;height:30px;"></div>