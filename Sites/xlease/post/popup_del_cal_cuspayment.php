<?php
include("../config/config.php");

$typeDep = pg_escape_string($_GET["typeDep"]);
$idcarTax = pg_escape_string($_GET["idcarTax"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ลบข้อมูล</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8">
    <meta http-equiv="Pragma" content="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		function RefreshMe()
		{
			opener.location.reload(true);
			self.close();
		}
		
		function chkPermit()
		{
			if(document.getElementById("Permit").checked == true)
			{
				document.getElementById("ok").disabled = false;
			}
			else
			{
				document.getElementById("ok").disabled = true;
			}
		}
		
		function DeleteDataPermit(TName,IdCarTax)
		{ // ยอมให้ลบได้ แม้ ยอดค้างชำระนี้มีการคิดต้นทุนไว้แล้ว ก็ตาม
			$.post("process_frm_cal_cuspayment.php",{
				typeDep : TName,
				idcarTax : IdCarTax,
				permit : 'yes'
			},
			function(data){
				if(data=='0'){
						alert('ขอยกเลิกการลบข้อมูลเสร็จสิ้น');
						RefreshMe()
				}else if(data == '1' || data == '2'){
						alert('ไม่สามารถขอยกเลิกการลบข้อมูลได้ กรุณาลองใหม่ในภายหลัง !');
						RefreshMe();
				}else{
					alert(data);
					RefreshMe();
				}
			});
		}
	</script>
</head>
<body>
	<center>
		<h2><font color="#FF0000">ไม่สามารถลบข้อมูลได้ เนื่องจาก ยอดค้างชำระนี้มีการคิดต้นทุนไว้แล้ว!</font></h2>
		<input type="checkbox" name="Permit" id="Permit" onChange="chkPermit();"> ยืนยันที่จะลบ แม้จะมีการคิดต้นทุนไว้แล้วก็ตาม
		<br><br>
		<input type="button" name="ok" id="ok" value="ยืนยันที่จะลบ" disabled onClick="DeleteDataPermit('<?php echo "$typeDep"; ?>','<?php echo "$idcarTax"; ?>');"> <input type="button" value="ยกเลิก" onClick="window.close();">
	</center>
</body>
</html>