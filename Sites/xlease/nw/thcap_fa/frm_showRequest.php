<?php
include("../../config/config.php");
$cussearch = pg_escape_string($_GET['cusid']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) FA ดูข้อมูลบิลขอสินเชื่อ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $("#sbill").autocomplete({		
        source: "s_request.php",
        minLength:2
    });

    $('#btn1').click(function(){
		var aaaa = $("#sbill").val();
        var brokenstring=aaaa.split("#");
		
		$("#panel").text('กำลังค้นหาข้อมูล โปรดรอซักครู่....');
		$("#panel").load("fa_bill_detail.php?prebillIDMaster="+ brokenstring[0]);
		
		
    });
	$('#btn2').click(function(){
		$("#sbill").val('');
		$("#sbill").focus();
		
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

<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>

<fieldset><legend><B>(THCAP) FA ดูข้อมูลบิลขอสินเชื่อ</B></legend>
<div class="ui-widget" align="center">
<div style="text-align:center;"><b>ค้นจากเลขที่ใบแจ้งหนี้, ผู้ขายบิล, ลูกหนี้ของผู้ขายบิล</b></div>
<div style="margin:0;padding-bottom:10px;">
<input id="sbill" name="sbill" size="80" value="<?php echo $cussearch; ?>" />&nbsp;
<input type="button" id="btn1" value="ค้นหา"/><input type="button" id="btn2" value="ค้นใหม่"/>
</div>
 </fieldset>

        </td>
    </tr>
</table>
<div id="panelcus" style="padding-top: 0px;" align="center"></div>
<div style="padding:0px;"></div>
<div id="panelcusrelative" style="padding-top: 0px;" align="center"></div>
<div style="padding:0px;"></div>
<div id="panel" style="padding-top: 10px;" align="center"></div>
<!-- thcap -->
<div style="padding:0px;"></div>
<div id="panelthcap" style="padding-top: 10px;" align="center"></div>

</div>

</body>
</html>