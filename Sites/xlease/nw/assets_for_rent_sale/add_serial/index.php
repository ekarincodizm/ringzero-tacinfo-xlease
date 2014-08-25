<?php
include("../../../config/config.php");

$reid = $_GET["reid"]; //หากมีการส่งเลขที่ใบเสร็จมาจากหน้าอื่น
if($reid != ""){ //ให้ค้นหารหัส assetID เพื่อทำรายการต่อ
	$qry_assetid = pg_query("select \"assetID\",\"PurchaseOrder\",\"receiptNumber\" from \"thcap_asset_biz\" where \"PurchaseOrder\" = '$reid' OR \"receiptNumber\" = '$reid' ");
	list($assid_s,$PurchaseOrder_s,$receiptNumber_s) = pg_fetch_array($qry_assetid);
	IF($receiptNumber_s == ""){
		$assid_s = $assid_s."#"."เลขที่ใบสั่งซื้อ ".$PurchaseOrder_s;	
	}else{
		$assid_s = $assid_s."#"."เลขที่ใบเสร็จ ".$receiptNumber_s;	
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) เพิ่มรหัสสินทรัพย์</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
	
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){
<?php if($assid_s != ""){ ?>
	var assid = '<?php echo $assid_s ?>';
	var brokenstring=assid.split("#");
	$("#panel").load("frm_edit.php?bill_id=" + brokenstring[0]);
<?php } ?>
    $("#sreceipt").autocomplete({
        source: "find_bill.php",
        minLength:1
    });
});

function loadpage(){
	var aaaa = $("#sreceipt").val();
        var brokenstring=aaaa.split("#");
        $("#panel").load("frm_edit.php?bill_id="+ brokenstring[0]);
};
</script>

<body>
<table width="100%">
	<tr>
		<td align="center" colspan="2"><h1><b>(THCAP) เพิ่มรหัสสินทรัพย์</b></h1></td>
	</tr>
	<tr>
		<td width="35%"></td>
		<td align="left">
			<input type="text" id="sreceipt" size="70" value="<?php echo $assid_s; ?>"><input type="button" value="ค้นหา" onclick="loadpage();">
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="left">
			<font color="gray">ค้นหาด้วย:ไอดีสินทรัพย์ , ชื่อผู้ขาย , เลขที่ใบเสร็จ , ราคาสินค้า </font>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<div id="panel" ></div>
		</td>
	</tr>
</table>
</body>
</html>