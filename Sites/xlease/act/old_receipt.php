<?php
set_time_limit(0);
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<style type="text/css">
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
<script language=javascript>
$(document).ready(function(){
	$('#btn1').click(function(){
		$("#btn1").attr('disabled',true);
		$("#panel").text('กำลังค้นหาข้อมูล...เนื่องจากข้อมูลมีจำนวนมาก ระบบอาจจะใช้เวลาประมวลผลนาน 1-5 นาที');
		$("#btn1").attr('disabled',false);
		
    });	
	
	$('#btn2').click(function(){
		$("#btn2").attr('disabled',true);
		$("#panel").text('กำลังค้นหาข้อมูล...เนื่องจากข้อมูลมีจำนวนมาก ระบบอาจจะใช้เวลาประมวลผลนาน 1-5 นาที');
		$("#btn2").attr('disabled',false);
		
    });	
	$('#btn3').click(function(){
		$("#btn2").attr('disabled',true);
		$("#panel").text('กำลังค้นหาข้อมูล...เนื่องจากข้อมูลมีจำนวนมาก ระบบอาจจะใช้เวลาประมวลผลนาน 1-5 นาที');
		$("#btn2").attr('disabled',false);
		
    });
});
</script>
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    <td>       
		<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
		<div style="clear:both;"></div>

		<fieldset><legend><B>ตัดใบเสร็จประกันเก่า</B></legend>
		<div align="center" style="padding:20px">
			<div class="ui-widget">
				<table width="100%" border="0" cellSpacing="1" cellPadding="3">
				<tr style="font-weight:bold;" valign="top"align="center" >
					<td>
					<input type="button" id="btn1" value="ประกันภัยภาคบังคับ (พรบ.)" style="width:250px;height:50px;" onclick="window.location='old_receipt2.php'">
					<input type="button" id="btn2" value="ประกันภัยภาคสมัครใจ" style="width:250px;height:50px;" onclick="window.location='old_receipt_uf.php'">
					<input type="button" id="btn3" value="ประกันภัยคุ้มครองหนี้" style="width:250px;height:50px;" onclick="window.location='old_receipt_live.php'">
					</td>
				</tr>
				</table>
			</div>
		</div>
		</fieldset>
		<div id="panel" style="padding-top: 10px;"></div>
    </td>
</tr>
</table>

</body>
</html>