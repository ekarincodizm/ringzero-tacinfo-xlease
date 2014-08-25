<?php
include("../../config/config.php");

$corp_regis = $_GET["corp_regis"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ยืนยัน</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>
<center>
<br>
<h3><font color="#FF0000">แน่ใจหรือไม่ว่าไม่ต้องการให้แสดงรายการนี้อีก!!(ทะเบียนนิติบุคคล:<?php echo $corp_regis ?>)</font></h3>
<br>
<input type="button" value="ยืนยัน" onclick="window.location='process_hidden_corp.php?corp_regis=<?php echo $corp_regis; ?>'"> &nbsp;&nbsp;&nbsp;
<input type="button" value="ยกเลิก" onclick="javascript:window.close();">
</center>
</body>
</html>