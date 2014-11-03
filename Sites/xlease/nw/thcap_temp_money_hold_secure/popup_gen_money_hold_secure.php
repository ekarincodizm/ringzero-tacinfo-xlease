<?php 
include("../../config/config.php");

$typeGen = pg_escape_string($_GET["typeGen"]); // ประเภทเงิน 997 - secure เงินค้ำ 998 - hold เงินพัก

if($typeGen == "997")
{
	$typeGenText = "เงินค้ำประกัน";
}
elseif($typeGen == "998")
{
	$typeGenText = "เงินพักรอตัดรายการ";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) GEN รายงาน<?php echo $typeGenText; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script>
		$(document).ready(function(){
			$("#dateGen").datepicker({
				showOn: 'button',
				buttonImage: '../thcap/images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			});
		});
		
		function validate()
		{
			if(document.getElementById("dateGen").value == '')
			{
				alert('กรุณา ระบุวันที่ GEN ข้อมูล');
				return false;
			}
			else
			{
				$("#loading").html('กำลังโหลดข้อมูล...');
				return true;
			}
		}
	</script>
</head>
<body>
	<center>
		<form method="post" action="process_gen_money_hold_secure.php">
			<h2>GEN รายงาน<?php echo $typeGenText; ?></h2>
			<br/>
			วันที่ GEN ข้อมูล :
			<input type="textbox" name="dateGen" id="dateGen" size="10" />
			<br/><br/>
			<input type="hidden" name="typeGen" id="typeGen" value="<?php echo $typeGen; ?>" />
			<input type="submit" value="GEN" onClick="return validate();" style="cursor:pointer;" />
			<input type="button" value="ยกเลิก/ปิด" onClick="window.close();" style="cursor:pointer;" />
			<br/>
			<div id="loading" style="margin-top:10px;"></div>
		</form>
	</center>
</body>
</html>