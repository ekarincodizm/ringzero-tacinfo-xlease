<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ดูข้อมูลการใช้หลักทรัพย์ค้ำประกัน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $("#numid").autocomplete({
        source: "s_link.php",
        minLength:2
    });

    $('#btn1').click(function(){
		var aaaa = $("#numid").val();
        var brokenstring=aaaa.split("#");
		$("#panel").load("frm_ShowdetailLink.php?numid="+ brokenstring[0]);
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

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="clear:both; padding: 10px;text-align:center;"><h2>ดูข้อมูลการใช้หลักทรัพย์ค้ำประกัน</h2></div>
			<div style="text-align:right;"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center">
					<div style="margin:0;padding-bottom:10px;">
						<b>ค้นหาจาก รหัสเชื่อมโยง, เลขที่โฉนด, เลขที่สัญญา:</b>&nbsp;
						<input id="numid" name="numid" size="60" />&nbsp;
						<input type="button" id="btn1" value="ค้นหา"/>
					</div>
				</div>
			</fieldset>
			
        </td>
    </tr>
</table>

<div id="panel" style="padding-top: 10px;"></div>

</body>
</html>