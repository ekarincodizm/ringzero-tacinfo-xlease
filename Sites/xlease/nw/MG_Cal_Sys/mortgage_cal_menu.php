<?php

session_start();


// address.php - ไฟล์สำหรับ แสดงผลเกี่ยวกับข้อมูลที่อยู่


require_once("setup/sys_setup.php");


// HTML HEADER
echo "

<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>เมนูคำนวณสัญญาจำนอง</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"".$lo_ext_current_temp."css/view.css\" media=\"all\">
<script type=\"text/javascript\" src=\"".$lo_ext_current_temp."scripts/view.js\"></script>
<script type=\"text/javascript\" src=\"".$lo_ext_current_temp."scripts/calendar.js\"></script>
</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"".$lo_ext_current_temp."pictures/top.png\" alt=\"\">
	
	<div id=\"form_container\">
		<div id=\"form_logon\">
	
		</div>

";

// HTML FORM ---------------------------------------------------------------------------------------------------------------
// เมนูข้อมูลพนักงาน
if($_GET["action"] == ""){
	echo "
		<form id=\"form_289641\" class=\"appnitro\" >
			<div class=\"form_description\">
				<h2>เมนูคำนวณสัญญาจำนอง</h2>
				<p>โปรดเลือกรายการที่ท่านต้องการดำเนินงาน</p>
			</div>						
			<ul >
	";
	

		
		echo "<label class=\"description\" for=\"element_1\"><a href=\"mortgage_cal_sys.php\">
		คำนวนสัญญาจำนอง</a></label>";
	
		echo "<label class=\"description\" for=\"element_1\"><a href=\"mortgage_cal_refinance.php\">
		คำนวนสัญญาจำนอง รีไฟแนนซ์( Refinance )</a></label>";
		
}
echo "
			</ul>
			<div class=form_description></div>
			
			
	  </form>
	<div id=\"footer\"></div>
</div>
	<img id=\"bottom\" src=\"".$lo_ext_current_temp."pictures/bottom.png\" alt=\"\">
</body>
</html>

";
?>