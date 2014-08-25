<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานรับชำระชั่วคราว 1681</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#makerStamp").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#tacTempDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
    
    $('#btn1').click(function(){
        $("#btn1").attr('disabled', true);
        $("#panel").text('กำลังค้นหาข้อมูล ....');
        $("#panel").load("frm_showreport.php?status=2&date="+ $("#makerStamp").val() );
        $("#btn1").attr('disabled', false);
    });

    $('#btn2').click(function(){
		$("#btn2").attr('disabled', true);
        $("#panel").text('กำลังค้นหาข้อมูล ....');
        $("#panel").load("frm_showreport.php?status=1&date="+ $("#tacTempDate").val());
		$("#btn2").attr('disabled', false);
    });
    
});
function check_search(){
	if(document.getElementById("search2").checked){
		document.getElementById("makerStamp").disabled =false;
		document.getElementById("tacTempDate").value ='';
		document.getElementById("btn1").disabled =false;
		document.getElementById("tacTempDate").disabled = true;
		document.getElementById("btn2").disabled = true;
	}else if(document.getElementById("search1").checked){
		document.getElementById("makerStamp").disabled =true;
		document.getElementById("btn1").disabled =true;
		document.getElementById("tacTempDate").disabled = false;
		document.getElementById("makerStamp").value = '';
		document.getElementById("btn2").disabled = false;
	}
}
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
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center;padding-bottom: 10px;"><h2>รายงานรับชำระชั่วคราว 1681</h2></div>
			<fieldset><legend><B>เงื่อนไขการรายงาน</B></legend>
				<div class="ui-widget" align="center">
					<div style="float:left; margin:0;padding-left:80px;">
						<input type="radio" name="search" id="search1" value="1" onclick="check_search()" checked ><b>ค้นจากรับชำระวันที่</b>
						<input type="text" id="tacTempDate" name="tacTempDate" value="<?php echo nowDate(); ?>" size="15" style="text-align: center;" disabled>&nbsp;
						<input type="button" name="btn2" id="btn2" value="เริ่มค้น"/>
					</div>
					<div style="float:right; margin:0;padding-right:80px;">
						<input type="radio" name="search" id="search2" value="2" onclick="check_search()"><b>ค้นจากเหตุการณ์วันที่</b>&nbsp;
						<input type="text" id="makerStamp" name="makerStamp" value="<?php echo nowDate(); ?>" size="15" style="text-align: center;" disabled>&nbsp;
						<input type="button" name="btn1" id="btn1" value="เริ่มค้น" disabled />
					</div>
					<div style="clear:both;"></div>
				</div>
			</fieldset>
        </td>
    </tr>
	<tr><td align="center"><div id="panel" style="padding-top: 10px;"></div></td></tr>
</table>

</body>
</html>