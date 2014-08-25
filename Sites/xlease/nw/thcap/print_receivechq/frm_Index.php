<?php
include("../../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) พิมพ์ใบรับเช็ค</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
    
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#recnum").autocomplete({
        source: "s_recchq.php",
        minLength:2
    });

    $('#btn1').click(function(){
		if($("#recnum").val()==""){
			$("#history").show();
			$("#panel").hide();
		}else{
			$("#history").hide();
			$("#panel").show();
			$("#panel").load("frm_Detail.php?recnum="+ $("#recnum").val());
		}
    });

});
</script>    
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
			<fieldset><legend><B>(THCAP) พิมพ์ใบรับเช็ค</B></legend>
				<div align="center">ค้นจาก : เลขที่สัญญา, ใบรับเช็ค หรือ วันที่รับเช็ค <br>
				<b>ใบรับเช็ค : </b><input type="text" id="recnum" name="recnum" size="40" /><input type="button" id="btn1" value="ค้นหา"/>
				</div>
			</fieldset>
        </td>
    </tr>
</table>
<div id="panel" style="padding-top: 10px;"></div>
<div style="width:850px;margin:0 auto;" id="history">
	<?php
	$txthead="ประวัติการพิมพ์ใบรับเช็ค 30 รายการล่าสุด";
	$limit="limit 30";
	include "frm_history.php";
	?>
</div>
</body>
</html>