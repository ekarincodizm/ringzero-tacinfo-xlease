<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>script check ใบเสร็จ ของ THCAP</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
function popU(U,N,T){
newWindow = window.open(U,N,T); 	
}
</script>
</head>
<body>
<div style="margin-top:50px;"></div>
<div align="center"">
<table frame="box" width="650px">
	<tr>
		<td align="center">
			<fieldset><legend>ตรวจสอบว่ามีการ gen เลขแต่ไม่มีใบเสร็จ</legend><br>
			<input type="button" value=" ใบเสร็จ " style="width:250px;height:80px;" onclick="window.open('Sheet1.php')">
			<p>
			<input type="button" value=" ใบกำกับ " style="width:250px;height:80px;" onclick="window.open('Sheet2.php')">			
			</fieldset>
		</td>
	</tr>
	<tr>
		<td align="center">
			<fieldset><legend>ตรวจสอบว่ามีใบเสร็จ แต่เป็นการ gen ใบกำกับให้กับใบเสร็จที่ไม่มี VAT</legend><br>
			<input type="button" value=" taxinvoice_details "  style="width:250px;height:80px;" onclick="window.open('Sheet5.php')">
			<p>
			<input type="button" value="  taxinvoice_otherpay/cancel " style="width:250px;height:80px;" onclick="window.open('Sheet6.php')">
			</fieldset>
		</td>
	</tr>	
</table>
</div>
</body>