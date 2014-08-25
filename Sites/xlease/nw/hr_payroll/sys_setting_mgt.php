<?php
include("../../config/config.php");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ตั้งค่าพื้นฐานระบบคำนวณเงินเดือน</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
  <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script src="../../jqueryui/js/datetime_picker.js" type="text/javascript"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>


<style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
/* css for timepicker */
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
.ui-timepicker-div dl { text-align: left; }
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
</style>
    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>


        
<fieldset><legend><B>ตั้งค่าพื้นฐานระบบคำนวณเงินเดือน</B></legend>

<div style="margin:5px" align="center">

<select name="set_type" id="set_type">
 <option value="1" >ตั้งค่าพื้นฐานระบบคำนวณเงินเดือน</option>
		                <option value="2" >ตั้งค่าพื้นฐานพนักงานเพศหญิง</option>
                        <option value="3" >ตั้งค่าพื้นฐานพนักงานเพศชาย</option>
                        <option value="4" >ตั้งค่าภาษีเงินได้</option>

</select>

</div>

<div id="panel" style="margin:5px"></div>

</fieldset>

		</td>
	</tr>
</table>
<script type="text/javascript">
$("#panel").load("sys_setting_dtl.php");
$(function(){

	$('#set_type').change(function(){
		if($("#set_type").val()==1){
		$("#panel").load("sys_setting_dtl.php");
		}else if($("#set_type").val()==2){
			$("#panel").load("sys_setting.php?sex_id=2");
		
		}else if($("#set_type").val()==3){
			$("#panel").load("sys_setting.php?sex_id=1");
		}
		else if($("#set_type").val()==4){
			$("#panel").load("tax_setting_dtl.php");
		}
		
	});	

});
</script>
</body>
</html>