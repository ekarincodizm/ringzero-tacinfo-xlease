<?php
include("../../config/config.php");


$bill_id = $_GET['tempID'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) รายการสินทรัพย์สำหรับเช่า-ขาย</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="../view_assets_for_rent_sale/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../view_assets_for_rent_sale/jquery-ui-1.8.21.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="../view_assets_for_rent_sale/jquery.coolfieldset.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
	if (!newWindow.opener) newWindow.opener = self;
}
function print_asset_report(assetid,realdata){
	if(assetid=='')
	{
		alert('ผิดพลาด : ไม่พบรหัสสินทรัพย์');
	}
	else
	{
		popU('../view_assets_for_rent_sale/print_asset_report_pdf.php?assetid='+assetid+'&realdata='+realdata,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1064,height=800');
	}
}
</script>
</head>
<body>
<center>
<?php require("../view_assets_for_rent_sale/show_appvDetail.php"); ?>
</center>
</body>
</html>