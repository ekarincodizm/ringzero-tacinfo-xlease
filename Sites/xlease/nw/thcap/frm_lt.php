<?php
include("../../config/config.php");

$show = pg_escape_string($_GET['show']);
$contractID = pg_escape_string($_GET['contractID']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ส่งจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	
	if($("#show").val()!=""){
		$("#panel").load("frm_lt_list.php?contractID="+ $("#contractID").val());
	}
	
    $("#contractID").autocomplete({
        source: "s_letter.php",
        minLength:1
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_lt_list.php?contractID="+ $("#contractID").val());
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

		<fieldset><legend><B>(THCAP) ส่งจดหมาย</B></legend>
		<div class="ui-widget" align="center">
			<div style="padding-left:80px;">ค้นได้จาก เลขที่สัญญา, ชื่อผู้กู้หลัก, ผู้กู้ร่วม, เลขบัตรผู้กู้หลัก, เลขบัตรผู้กู้ร่วม</div>
			<div style="margin:0">
				<b>ค้นหา เลขที่สัญญา</b>&nbsp;
				<input type="text" id="contractID" name="contractID" size="60" value="<?php if($show == 1){echo $contractID;} ?>" />&nbsp;
				<input type="button" id="btn1" value="ค้นหา"/>
				<input type="hidden" id="show" value="<?php echo $show; ?>"/>
			</div>
			<div id="panel" style="padding-top: 10px;"></div>
		</div>
		</fieldset>
       </td>
</tr>
</table>
</body>
</html>