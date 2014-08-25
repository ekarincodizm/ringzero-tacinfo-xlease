<?php
include("../../config/config.php");

//รับค่าในกรณีที่กดค้นหาแล้ว
$bill_id = $_GET['bill_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ดูรายการสินทรัพย์สำหรับเช่า-ขาย</title>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="css/act.css" />
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="lib/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
	if (!newWindow.opener) newWindow.opener = self;
}

$(document).ready(function(){
	
	$("#bill_id").autocomplete({		
        source: "find_bill.php",
        minLength:1
    });
});
function validate_frm(){
	var assetID = $('#bill_id').val();
	if(assetID=='')
	{
		alert('กรุณาระบุไอดีสินทรัพย์ก่อนครับ');
		return false;
	}
	else
	{
		$('#frm_search_bill').submit();
	}
}
function print_asset_report(assetid,realdata){
	if(assetid=='')
	{
		alert('ผิดพลาด : ไม่พบรหัสสินทรัพย์');
	}
	else
	{
		popU('print_asset_report_pdf.php?assetid='+assetid+'&realdata='+realdata,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1064,height=800');
	}
}
</script>

</head>
<body>

<center>
<h1>(THCAP) ดูรายการสินทรัพย์สำหรับเช่า-ขาย</h1>
<form name="frm_search_bill" id="frm_search_bill" action="view_appvDetail.php" method="get">
<div style="margin:15px 10px; display:block; text-align:center; width:1000px;">
<fieldset>
	<legend><b>ค้นหาใบสั่งซื้อหรือใบเสร็จ</b></legend>
    <div style="display:block; margin:15px;">
			<table  align="center">
				
				<tr>
					<td align="left">
						<span><font color="gray">ค้นหาด้วย:ชื่อผู้ขาย , เลขที่ใบสั่งซื้อ , เลขที่ใบเสร็จ , ราคาสินค้า </font></span>
					</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="bill_id" id="bill_id" style="width:350px;" value="<?php echo $bill_id; ?>" />
						<input type="button" name="btn_submit" id="btn_submit" value="ค้นหา" onclick="validate_frm();" />
					</td>
				</tr>	
			</table>	
		
    </div>
</fieldset>
</div>
</form>
<?php 
//ตัวแปล realdata สำหรับใช้ในหน้า show_appvDetail เพื่อนำข้อมูลจากตารางจริงมาแสดง ไม่ใช่จาก Temp โดยรายละเอียดจะ comment ไว้ที่ไฟล์ show_appvDetail.php
$realdata = 1;
require("show_appvDetail.php"); 
?>
</center>
</body>
</html>