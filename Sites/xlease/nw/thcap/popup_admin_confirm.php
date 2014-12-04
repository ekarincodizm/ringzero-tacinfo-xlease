<html>
<head>
	<title>ยืนยันการทำรายการ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script>
		function checkConfirm() // ตรวจสอบการ checkbox
		{
			if(document.getElementById("checkConfirm").checked == true)
			{
				document.getElementById("btn_confirm").disabled = false;
			}
			else
			{
				document.getElementById("btn_confirm").disabled = true;
			}
		}
		
		function clickConfirm() // ยืนยันการทำรายการ
		{
			window.opener.document.forms[0].isAdminConfirm.value = 'yes';
			window.opener.document.forms[0].adminConfirm.disabled = true;
			window.opener.document.forms[0].showTextAdminConfirm.click();
			window.opener.document.forms[0].btn_appv.disabled = false;
			window.close();
		}
	</script>
</head>
<body>
	<center>
		<h2>ยืนยันการทำรายการ</h2>
		
		<input type="checkbox" id="checkConfirm" style="cursor:pointer;" onClick="checkConfirm();" /> <b>ยืนยันการทำรายการ</b>
		
		<br/><br/>
		
		<font color="gray">* ท่านกำลังเชื่อมการรับเงินเข้ากับสัญญาที่ยังไม่มีในระบบ โปรดตรวจสอบรายการนี้อีกครั้งว่าถูกต้อง<br/>หากท่านมั่นใจว่าข้อมูลถูกต้อง กรุณาติ๊กยืนยันการทำรายการ และกดยืนยัน</font>
		
		<br/><br/>
		
		<input type="button" id="btn_confirm" name="btn_confirm" style="cursor:pointer;" value="ยืนยัน" onClick="clickConfirm();" disabled />
		&nbsp;&nbsp;&nbsp;
		<input type="button" style="cursor:pointer;" value="ยกเลิก/ปิด" onClick="window.close();" />
	</center>
</body>
</html>