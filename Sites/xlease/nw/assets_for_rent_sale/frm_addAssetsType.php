<?php
include("../../config/config.php");

$assetsType = $_POST["assetsType"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เพิ่มประเภทสินทรัพย์</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	if (document.frm1.assetsType.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุชื่อประเภทสินทรัพย์";
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
<form name="frm1" method="post" action="process_addAssetsType.php">
<center><h2>เพิ่มประเภทสินทรัพย์</h2></center>
<center>
ชื่อประเภทสินทรัพย์ : <input type="text" name="assetsType" size="40" value="<?php echo $assetsType; ?>">
<br><br>
<input type="submit" name="add" value=" ตกลง " onclick="return validate();"> &nbsp;&nbsp;&nbsp; <input type="button" value="ยกเลิก/ปิด" onclick="javascript:window.close();">
</center>
</form>
</body>
</html>