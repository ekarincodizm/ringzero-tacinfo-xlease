<?php
include("../../config/config.php");
$revChqID = $_GET["revChqID"]; //รหัสเช็คที่ต้องการ map
$bankChqNo = $_GET["bankChqNo"]; //เลขที่เช็คที่ต้องการ map
$contractID = $_GET["contractID"]; //เลขที่สัญญา

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>map เช็คเลขที่ <?php echo $bankChqNo?> กับใบเสร็จที่ออกไปแล้ว</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
$(document).ready(function(){
	$("#receiptID").autocomplete({
		source: "s_mapreceiptID.php?contractID="+'<?php echo $contractID;?>',
		minLength:1
	});
	
	$('#btn1').click(function(){
		$('#panel').load('Channel_detail.php?mapchq=yes&receiptID='+ $("#receiptID").val()+'&revChqID='+ '<?php echo $revChqID;?>');
	});
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div style="width:700px;margin:0px auto;" >
	<div style="padding-top:25px;text-align:center;"><h2>map เช็คเลขที่ <?php echo $bankChqNo?> กับใบเสร็จที่ออกไปแล้ว</h2></div>
	<div align="right"><input type="button" value="X ปิด" onclick="window.close();"></div>
	<fieldset>
		<div style="padding:10px;text-align:center;"><b>ค้นหาใบเสร็จที่จะ map : <input type="text" name="receiptID" id="receiptID" size="60"><input type="button" id="btn1" value="ค้นหา"></div>
	</fieldset>
	<div id="panel" style="padding-top:10px;"></div>
</div>
</body>
</html>