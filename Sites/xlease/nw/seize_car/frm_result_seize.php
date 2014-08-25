<?php
session_start();
include("../../config/config.php");
$idno = $_GET['idno'];
$ntid = $_GET['ntid'];
$method = $_GET['method'];

if($method == "edit"){
	$query_up=pg_query("select * from \"nw_seize_car\" where \"IDNO\" = '$idno' and \"NTID\" = '$ntid' and \"status_approve\" = '1'");
	if($res_up=pg_fetch_array($query_up)){
		$yellow_date = $res_up["yellow_date"];
		$seize_result = $res_up["seize_result"];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"><link>
	<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
<script type="text/javascript">
function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.form1.seize_result.value=="") {
	theMessage = theMessage + "\n -->  กรุณากรอกรายละเอียด-หมายเหตุ";
	}
	
	// If no errors, submit the form
	if (theMessage == noErrors) {
	return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>
</head>
<body>

<fieldset><legend><B>ส่งเรื่องยึดรถ</B></legend>

<form name="form1" action="process_result_seize.php" method="post">
<input type="hidden" name="idno" value="<?php echo $idno; ?>">
<input type="hidden" name="ntid" value="<?php echo $ntid; ?>">
<?php
	if($method == "edit"){
		echo "<input type=\"hidden\" name=\"method\" value=\"edit\">";
	}else{
		echo "<input type=\"hidden\" name=\"method\" value=\"add\">";
	}
?>
<table width="100%">
<tr>
    <td width="20%"><b>IDNO</b></td>
    <td width="80%"><?php echo $idno; ?></td>
</tr>
<tr>
    <td width="20%"><b>วันที่ได้รับใบเหลือง</b></td>
    <td width="80%"><input name="yellow_date" type="text" readonly="true"  value="<?php if($method == "edit"){echo $yellow_date;}else{echo date("Y/m/d");} ?>"/>
		<input name="button" type="button" onclick="displayCalendar(document.form1.yellow_date,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
</tr>
<tr>
    <td valign="top"><b>รายละเอียด-หมายเหตุ</b></td>
    <td><textarea name="seize_result" id="seize_result" rows="5" cols="50"><?php echo $seize_result;?></textarea></td>
</tr>
<tr>
    <td></td>
    <td><input name="btnButton1" type="submit" value="ยืนยัน"onclick="return checkdata()" /><input name="btnButton2" type="reset" value="ยกเลิก" onclick="javascript:window.close();" /></td>
</tr>
</table>
</form>

</fieldset> 


</body>
</html>