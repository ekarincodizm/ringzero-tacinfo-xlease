<?php // Start Program
set_time_limit(0);
include("../../config/config.php");

$printID = pg_escape_string($_GET['printID']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ระบบแจ้งเตือนรถหมดอายุและถอดป้าย</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
	<script language=javascript>
		function validate()
		{
			if(document.getElementById("doerNote").value == '')
			{
				alert('กรุณาระบุหมายเหตุในการพิมพ์ซ้ำ');
				return false;
			}
			else
			{
				return true;
			}
		}
	</script>
</head>
<body>
	<center>
		<h2>หมายเหตุในการพิมพ์ซ้ำ</h2>
		<form method="post" action="process_reprint.php" onSubmit="return validate()">
			<textarea name="doerNote" id="doerNote" rows="4" cols="50"></textarea>
			<br/><br/>
			<input type="hidden" name="printID" value="<?php echo $printID; ?>" />
			<input type="submit" style="cursor:pointer;" value="พิมพ์" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" style="cursor:pointer;" value="ยกเลิก/ปิด" onClick="window.close();" />
		</form>
	</center>
</body>
</html>