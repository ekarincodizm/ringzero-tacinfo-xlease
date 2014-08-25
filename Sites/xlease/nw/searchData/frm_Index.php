<?php
include("../../config/config.php");
set_time_limit(0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ตรวจสอบข้อมูลในฐานข้อมูล</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){
	$('#btn1').click(function(){
		validate();
		
		var mysearch = $("#mysearch").val();
		var whereData = '';
		var showView;
		var showSerial;
		
		if(document.getElementById("whereData1").checked == true){whereData = $("#whereData1").val();}
		else if(document.getElementById("whereData2").checked == true){whereData = $("#whereData2").val();}
		
		if(document.getElementById("showView").checked == true){showView = 1;} else{showView = 2;}
		if(document.getElementById("showSerial").checked == true){showSerial = 1;} else{showSerial = 2;}
		
		if(mysearch != '' && whereData != '')
		{
			$("#panel").text('กำลังค้นหาข้อมูล  โปรดรอซักครู่....');
			$("#panel").load("searchData.php?mydata="+mysearch+"&whereData="+whereData+"&showView="+showView+"&showSerial="+showSerial);
		}
	});	
});

function validate()
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if ($("#mysearch").val() == ""){
		theMessage = theMessage + "\n -->  กรุณาระบุ คำที่ต้องการค้นหา";
	}
	
	if (document.getElementById("whereData1").checked == false && document.getElementById("whereData2").checked == false){
		theMessage = theMessage + "\n -->  กรุณาเลือก รูปแบบการค้นหา";
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
		return true;
	} 
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>

</head>
<body>
<center>
<form name="frm1" method="post" action="frm_Index.php">
<table width="850" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr align="center">
        <td>
			<fieldset><legend><B>ตรวจสอบข้อมูลในฐานข้อมูล</B></legend>
				<input type="checkbox" name="showSerial" id="showSerial"> รวมฟิลด์ Serial
				&nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="showView" id="showView"> รวม VIEW
				&nbsp;&nbsp;&nbsp;
				<font color="#FF0000">* ถ้าค้นหาใน VIEW ด้วยจะใช้เวลานานมาก ถ้าไม่จำเป็นไม่แนะนำให้เลือก</font>
				<br><br>
				รูปแบบการค้นหา :
				<input type="radio" name="whereData" id="whereData1" value="1"> เท่ากับ
				<input type="radio" name="whereData" id="whereData2" value="2"> LIKE
				&nbsp;&nbsp;&nbsp;
				<input id="mysearch" name="mysearch" size="60" value="<?php echo $mysearch; ?>" />
				<input type="button" id="btn1" value="ค้นหา"/>
			</fieldset>
		</td>
	</tr>
	<tr align="center">
		<td>
			<br><div id="panel" style="padding-top: 0px;" align="center"></div>
		</td>
	</tr>
</table>
</form>
</center>
</body>
</html>