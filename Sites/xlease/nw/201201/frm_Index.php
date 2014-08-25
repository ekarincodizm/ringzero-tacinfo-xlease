<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$('#btn1').click(function(){
		$("#btn1").attr('disabled',true);
		$("#panel").text('กำลังจัดการข้อมูล...ระบบอาจจะใช้เวลาประมวลผลนาน 1-5 นาที');
		$("#panel").load("detail.php");
		$("#btn1").attr('disabled',false);
		
    });	
	
		$('#btn3').click(function(){
		$("#btn3").attr('disabled',true);
		$("#panel").text('กำลังจัดการข้อมูล...ระบบอาจจะใช้เวลาประมวลผลนาน 1-5 นาที');
		$("#panel").load("detail_pgf.php");
		$("#btn3").attr('disabled',false);
		
    });	
});
</script>   
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="text-align:center;"><h2>คำนวณดอกเบี้ยชั่วคราวเดือนกุมภาพันธ์</h2></div>
<div style="clear:both;"></div>
<fieldset><legend></legend>

<div align="center">
<form method="post" name="form1" action="summary.php">
<div class="ui-widget">
<p align="center">
<input type="button" id="btn3" value="คำนวณการจ่ายเงินต้นและดอกเบี้ย"/><input type="submit" id="btn2" value="จัดการยอดสรุปสิ้นเดือน"/>
</p>
<div id="panel"></div>

</div>
</form>
</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>