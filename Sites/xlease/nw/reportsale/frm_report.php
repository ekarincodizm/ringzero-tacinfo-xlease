<?php
session_start();
include("../../config/config.php");
$typesearch = pg_escape_string($_POST["typesearch"]);
$SelectChart= pg_escape_string($_POST["SelectChart"]);

if($SelectChart=="a1"){
	$year = pg_escape_string($_POST["year1"]);
	$conmonth="";
	$txtcon="ประจำเดือนมกราคม-ธันวาคม";
}else{
	$month = pg_escape_string($_POST["month"]);
	$conmonth="AND (EXTRACT(MONTH FROM a.\"startDate\")='$month')";
	$year = pg_escape_string($_POST["year"]);
	$txtcon="ประจำเดือน";
	if($month == "01"){
		$txtmonth="มกราคม";
	}else if($month == "02"){
		$txtmonth="กุมภาพันธ์";
	}else if($month == "03"){
		$txtmonth="มีนาคม";
	}else if($month == "04"){
		$txtmonth="เมษายน";
	}else if($month == "05"){
		$txtmonth="พฤษภาคม";
	}else if($month == "06"){
		$txtmonth="มิถุนายน";
	}else if($month == "07"){
		$txtmonth="กรกฎาคม";
	}else if($month == "08"){
		$txtmonth="สิงหาคม";
	}else if($month == "09"){
		$txtmonth="กันยายน";
	}else if($month == "10"){
		$txtmonth="ตุลาคม";
	}else if($month == "11"){
		$txtmonth="พฤศจิกายน";
	}else if($month == "12"){
		$txtmonth="ธันวาคม";
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานพนักงานขาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="wrapper" style="width:600px;">
				<?php
				if($typesearch == '1'){
					include "frm_allreport.php";
				}else{
					include "frm_onlyreport.php";
				}
				?>
			</div>
        </td>
    </tr>
</table>    
</body>
</html>