<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>(THCAP)ใส่เลขที่ EMS</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>	
</head>

</head>
<body>
<center><h2>(THCAP) ใส่เลขที่ EMS</h2></center>
<br>
<fieldset><legend><B>รายการที่อยู่ระหว่างใส่เลขที่ EMS</B></legend>
<!---ข้อมูลที่อยู่ระหว่างใส่เลขที่ EMS-->
<div id="betweenems">
<?php include("frm_listbetweenems.php");?>
</div>
</fieldset>
<br>
<fieldset><legend><B>ประวัติ 30 รายการล่าสุด(<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_historityall.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </B></legend>
<?php include("frm_histority_limit.php");?>
</fieldset>
</body>
</html>