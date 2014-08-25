<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>สร้างเลขที่สัญญา VIP</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"><link>
	<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function checkdata(){
	if(document.getElementById('idtfpen').value == ""){
			alert("กรุณากรอกเลขที่ใบสั่ง");
			document.getElementById('idtfpen').focus();
			return false;
	}else{
		return true;
	}
}
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		return false;
	}
	return true;
}
$(document).ready(function(){
    $("#idno").autocomplete({
        source: "s_idno.php",
        minLength:1
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_create.php?idno="+ $("#idno").val());
    });
});


</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>

<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
		<td>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
			<div style="clear:both; padding-bottom: 10px;"></div>

			<fieldset><legend><B>ค้นหาเลขที่สัญญา</B></legend>

			<div class="ui-widget" align="center">

			<div style="margin:0">
			<b>เลขที่สัญญา,ทะเบียนรถ, ยี่ห้อ</b>&nbsp;
			<input id="idno" name="idno" size="60" />&nbsp;
			<input type="button" id="btn1" value="ค้นหา"/>
			</div>

			<div id="panel" style="padding-top: 20px;"></div>

			</div>

			 </fieldset>

        </td>
    </tr>
</table>


</body>
</html>