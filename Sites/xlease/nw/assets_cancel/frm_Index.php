<?php
include("../../config/config.php");

$bill_id = $_GET['bill_id'];
$typeUseMenu = $_GET['typeUseMenu']; // ประเภทการทำรายการ appv=ทำรายการอนุมัติ
$cancelID = $_GET['cancelID']; // รหัสการขอยกเลิก

if($cancelID != "" && $typeUseMenu == "appv")
{
	// หารหัส ใบเสร็จ/ใบสั่งซื้อ
	$qry_sAsset = pg_query("select * from \"thcap_asset_cancel\" where \"cancelID\" = '$cancelID' ");
	while($res_sAsset = pg_fetch_array($qry_sAsset))
	{
		$bill_id = $res_sAsset["assetID"]; // รหัส ใบเสร็จ/ใบสั่งซื้อ
		$reason = $res_sAsset["reason"]; // เหตุผล
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ยกเลิกใบเสร็จ สินทรัพย์สำหรับเช่า-ขาย</title>

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

function validate()
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	var typeUseMenu = '<?php echo $typeUseMenu; ?>';

	if (document.frmCancel.reason.value == "" && typeUseMenu != "Appv") {
	theMessage = theMessage + "\n -->  กรุณาระบุ เหตุผลที่ขอยกเลิกด้วยครับ";
	}
	
	// If no errors, submit the form
	if (theMessage == noErrors)
	{
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
<h1>(THCAP) ยกเลิกใบเสร็จ สินทรัพย์สำหรับเช่า-ขาย</h1>
<form name="frm_search_bill" id="frm_search_bill" action="frm_Index.php" method="get">
<div style="margin:15px 10px; display:block; text-align:center; width:1000px;">
<?php
if($typeUseMenu == "appv")
{
?>
	<input type="text" name="bill_id" id="bill_id" style="width:350px;" value="<?php echo $bill_id; ?>" hidden />
	<input type="button" name="btn_submit" id="btn_submit" value="ค้นหา" hidden />
<?php
}
else
{
?>
<fieldset>
	<legend><b>ค้นหาใบเสร็จ/ใบสั่งซื้อ</b></legend>
    <div style="display:block; margin:15px;">
		<table  align="center">
			
			<tr>
				<td align="left">
					<span><font color="gray">ค้นหาด้วย: ชื่อผู้ขาย , เลขที่ใบเสร็จ , เลขที่ใบสั่งซื้อ , ราคารวมvat </font></span>
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
<?php
}
?>
</div>
</form>
<?php require("show_assetDetail.php"); ?>
</center>
</body>
</html>