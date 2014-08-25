<?php
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both"></div>

<fieldset><legend><B>พิมพ์ใบเสร็จเป็นชุด</B></legend>

<div style="margin:5px" align="center">

<input type="button" value="ค่างวด" name="btn1" id="btn1" class="ui-button" onclick="window.location='recript_set_h.php'">
<input type="button" value="ค่าอื่นๆ" name="btn2" id="btn2" class="ui-button" onclick="window.location='recript_set_o.php'">
<input type="button" value="ใบกำกับภาษี" name="btn3" id="btn3" class="ui-button" onclick="window.location='recript_set_b.php'">

</div>

</fieldset>

		</td>
	</tr>
</table>

</body>
</html>