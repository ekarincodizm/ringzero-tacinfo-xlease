<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) ตรวจรับกรมธรรม์</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../../act/act.css"></link> 
    <link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>	
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	//กรณีภาคสมัครใจ
	$("#car_id").autocomplete({
        source: "gdata_insid.php",
        minLength:1,
		delay:800
    });

    $('#btn1').click(function(){
		var aaaa = $("#car_id").val();
        var brokenstring=aaaa.split("#");
		$('#panel').html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $("#panel").load("frm_insure_unforce_insid.php?UnforceID="+ brokenstring[0]);
    });
	//จบกรณีภาคสมัครใจ
	
});
function validate(){

    var theMessage = "";
    var noErrors = theMessage;

    if (document.search.car_id.value == "") {
        theMessage = "กรุณาใส่คำที่ต้องการค้นหา";
    }

    // If no errors, submit the form
    if (theMessage == noErrors) {
        return true;
    } else {
        // If errors were found, show alert message
        alert(theMessage);
        return false;
    }
}
</script>
   
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td background=><img src="../../images/bg_01.jpg" height="15" width="700"></td>
</tr>
<tr>
	<td align="center" valign="top" background="../../images/bg_02.jpg" style="background-repeat:repeat-y">
		<div class="header"><h1>(THCAP) ตรวจรับกรมธรรม์</h1></div>
		<div class="wrapper">
			<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();" style="cursor:pointer;" /></div>
			<fieldset><legend><b>ค้นหา</b></legend>
				<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
					<tr align="left">
						<td width="20%"><b>ป้อนข้อมูลเพื่อค้นหา</b></td>
						<td width="80%" align="left">เลขตัวถัง, ทะเบียนรถ
							<div id="show1"><input type="text" id="car_id" name="car_id" size="78"><input type="button" name="submit1" id="btn1" value="   ค้นหา   " style="cursor:pointer;" /></div>   
						</td>
					</tr>
					<tr><td><br></td></tr>
				</table>
			</fieldset>		
		</div>
		<div id="panel"></div>
	</td>
</tr>
<tr>
	<td><img src="../../images/bg_03.jpg" width="700" height="15"></td>
</tr>
</table>

</body>
</html>