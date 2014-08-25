<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$currentdate=nowDate();
$year=substr($currentdate,0,4);
$month=substr($currentdate,5,2);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานพนักงานขาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
$(document).ready(function(){  
    $("#id_user").autocomplete({
        source: "s_user.php",
        minLength:2
    }); 
});

function check_search(){
	if(document.getElementById("search1").checked){
		document.getElementById("id_user").value ='';
		document.getElementById("id_user").disabled =true;
	}else if(document.getElementById("search2").checked){
		document.getElementById("id_user").disabled =false;
		document.getElementById("id_user").focus();
		
	}
}
function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if(document.getElementById("search2").checked){
		if (document.getElementById("id_user").value =="") {
			theMessage = theMessage + "\n -->  กรุณาระุบุชื่อพนักงาน";
		}
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
	return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		document.getElementById("id_user").focus();
		return false;
	}
}
</script>

</head>
<body>
<form name="form1" method="post" action="frm_report.php">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="wrapper" style="width:800px;">
				<div align="center"><h2>แสดงรายงานพนักงานขาย</h2></div>
				<fieldset><legend><B>เลือกเงื่อนไขการแสดงรายงาน</B></legend>
					<div style="padding:20px;">
						<table width="600" border="0"  align="center">
						<tr>
							<td height="50" align="center"><input type="radio" name="typesearch" id="search1" value="1" onclick="check_search()" checked><input type="hidden" name="sent" id="sent" value="s1"> แสดงทั้งหมด
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="typesearch" id="search2" value="2" onclick="check_search()"> ตามรายชื่อ <input type="text" name="id_user" id="id_user" size="30" disabled></td>	
						</tr>
						<tr>
							<td height="50" align="left" >
							<fieldset><legend>เลือกช่วงที่สนใจ</legend>
							<div style="padding-left:120px;">
							<input type="radio" name="SelectChart" value="a1" checked="checked">ดูข้อมูลทั้งหมดในปี ค.ศ. :
								<select name="year1">
									<option value="2011" <?php if($year == "2011"){ echo "selected";}?> selected>2011</option>
									<option value="2012" <?php if($year == "2012"){ echo "selected";}?>>2012</option>
									<option value="2013" <?php if($year == "2013"){ echo "selected";}?>>2013</option>
									<option value="2014" <?php if($year == "2014"){ echo "selected";}?>>2014</option>
									<option value="2015" <?php if($year == "2015"){ echo "selected";}?>>2015</option>
									<option value="2016" <?php if($year == "2016"){ echo "selected";}?>>2016</option>
									<option value="2017" <?php if($year == "2017"){ echo "selected";}?>>2017</option>
									<option value="2018" <?php if($year == "2018"){ echo "selected";}?>>2018</option>
									<option value="2019" <?php if($year == "2019"){ echo "selected";}?>>2019</option>
									<option value="2020" <?php if($year == "2020"){ echo "selected";}?>>2020</option>
									<option value="2021" <?php if($year == "2021"){ echo "selected";}?>>2021</option>
									<option value="2022" <?php if($year == "2022"){ echo "selected";}?>>2022</option>
									<option value="2023" <?php if($year == "2023"){ echo "selected";}?>>2023</option>
									<option value="2024" <?php if($year == "2024"){ echo "selected";}?>>2024</option>
									<option value="2025" <?php if($year == "2025"){ echo "selected";}?>>2025</option>
								</select><br>
							<input type="radio" name="SelectChart" value="a2">ดูข้อมูลในเดือน :
								<select name="month">
									<option value="01" <?php if($month == "01"){ echo "selected";}?> selected>มกราคม</option>
									<option value="02" <?php if($month == "02"){ echo "selected";}?>>กุมภาพันธ์</option>
									<option value="03" <?php if($month == "03"){ echo "selected";}?>>มีนาคม</option>
									<option value="04" <?php if($month == "04"){ echo "selected";}?>>เมษายน</option>
									<option value="05" <?php if($month == "05"){ echo "selected";}?>>พฤษภาคม</option>
									<option value="06" <?php if($month == "06"){ echo "selected";}?>>มิถุนายน</option>
									<option value="07" <?php if($month == "07"){ echo "selected";}?>>กรกฎาคม</option>
									<option value="08" <?php if($month == "08"){ echo "selected";}?>>สิงหาคม</option>
									<option value="09" <?php if($month == "09"){ echo "selected";}?>>กันยายน</option>
									<option value="10" <?php if($month == "10"){ echo "selected";}?>>ตุลาคม</option>
									<option value="11" <?php if($month == "11"){ echo "selected";}?>>พฤศจิกายน</option>
									<option value="12" <?php if($month == "12"){ echo "selected";}?>>ธันวาคม</option>
								</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								ปี ค.ศ. :
								<select name="year">
									<option value="2011" <?php if($year == "2011"){ echo "selected";}?> selected>2011</option>
									<option value="2012" <?php if($year == "2012"){ echo "selected";}?>>2012</option>
									<option value="2013" <?php if($year == "2013"){ echo "selected";}?>>2013</option>
									<option value="2014" <?php if($year == "2014"){ echo "selected";}?>>2014</option>
									<option value="2015" <?php if($year == "2015"){ echo "selected";}?>>2015</option>
									<option value="2016" <?php if($year == "2016"){ echo "selected";}?>>2016</option>
									<option value="2017" <?php if($year == "2017"){ echo "selected";}?>>2017</option>
									<option value="2018" <?php if($year == "2018"){ echo "selected";}?>>2018</option>
									<option value="2019" <?php if($year == "2019"){ echo "selected";}?>>2019</option>
									<option value="2020" <?php if($year == "2020"){ echo "selected";}?>>2020</option>
									<option value="2021" <?php if($year == "2021"){ echo "selected";}?>>2021</option>
									<option value="2022" <?php if($year == "2022"){ echo "selected";}?>>2022</option>
									<option value="2023" <?php if($year == "2023"){ echo "selected";}?>>2023</option>
									<option value="2024" <?php if($year == "2024"){ echo "selected";}?>>2024</option>
									<option value="2025" <?php if($year == "2025"){ echo "selected";}?>>2025</option>
								</select>
							<br><br>
							</div>
							</fieldset>
							</td>
						</tr>
						<tr><td align="center"><br><input type="submit" value="  OK  " onclick="return checkdata()">&nbsp;<input type="button" value="CLOSE" onclick="javascript:window.close();"></td></tr>
						</table>
					
						<div id="panel" style="padding-top: 10px;"></div>
					</div>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          
</form>
</body>
</html>