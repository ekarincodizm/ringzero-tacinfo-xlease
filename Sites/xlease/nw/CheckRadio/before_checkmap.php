<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$('#btnsearchmoney').click(function(){
		if(document.getElementById('money').value != "")
		{
			document.getElementById('pane2').style.display="block";
			$('#pane2').html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
			$("#pane2").load("checkmap_money.php?money="+document.getElementById('money').value);
		}
		else
		{
			document.getElementById('pane2').style.display="none";
		}
    });
    $('#btnsearchall').click(function(){
		document.getElementById('pane2').style.display="block";
        $('#pane2').html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $("#pane2").load("checkmap.php");
    });
});
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.getElementById("money").focus();
		return false;
	}
	return true;
}
</script>

</head>

<body>

<div align="center">
		แสดงรายการที่จำนวนเงินต่างกันมากกว่า
		<input type="hidden" name="typecheck" value="havemoney">
		<input type="text" name="money" id="money" size="10" onkeypress="return check_number(event);" style="text-align:right;"> ด้วย <input type="button" id="btnsearchmoney" value="ตกลง">
		<input type="hidden" name="typecheck" value="nomoney">
		&nbsp; <input type="button" id="btnsearchall" value="ไม่ต้องแสดงรายการที่มีผลต่าง">
	
<div id="pane2" align="left" style="margin-top:10px"></div>
</div>

</body>
</html>