<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ออก NT 1681</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){
    $("#car").autocomplete({
        source: "s_cusid.php",
        minLength:1
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_AddNT.php?car="+ $("#car").val());
    });
});

$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
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

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center;padding-bottom: 10px;"><h2>ออก NT 1681</h2></div>

			<fieldset><legend><B>ค้นหาข้อมูล</B></legend>

			<div class="ui-widget" align="center">

			<div style="margin:0">
			<b>เลขทะเบียนรถ, เลขวิทยุ, เลขที่สัญญาวิทยุ, ชื่อลูกค้า</b>&nbsp;
			<input id="car" name="car" size="60" />&nbsp;
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