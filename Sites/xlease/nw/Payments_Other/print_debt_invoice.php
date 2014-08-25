<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>พิมพ์ใบแจ้งหนี้</title>

<link href="act.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />   
<style type="text/css">
tr {
	height:25px;
}
</style>

</head>

<body>
<div align="center">
    <div style="width:1024px;">
        <h1>(THCAP) พิมพ์ใบแจ้งหนี้</h1>
        <fieldset style="padding:15px;">
            <legend><b>ค้นหาเลขที่สัญญา</b></legend>
            <div style="display:block;">
                <span style="margin-right:10px;">เลขที่สัญญา, ชื่อ - สกุล, บัตรประจำตัว, ใบแจ้งหนี้</span>
                <input type="text" name="tbx_receipt" id="tbx_receipt" style="width:300px; margin-right:10px;" />
                <input type="button" name="btn_search" id="btn_search" value="ค้นหา" onclick="javascript:show_debt_invoice();" />
            </div>
        </fieldset>
        <div id="div_show_debt_invoice"></div>
		<div style="padding-top:10px;">
			<?php
			include "frm_history_print_allinvoice.php";
			?>
		</div>
    </div>
</div>
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#tbx_receipt').autocomplete({
		source: "find_rcid.php",
        minLength:1
    });
});
function show_debt_invoice(){
	var contractid = $('#tbx_receipt').val();
	if(contractid=='')
	{
		alert('โปรดระบุเลขที่สัญญา');
	}
	else
	{
		$.post('gen_debt_invoice_result.php',{contractid:contractid},function(data){
			$('#div_show_debt_invoice').html(data);
		});
	}
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function print_debt_invoice(debt_id){
	popU('print_debt_invoice_pdf.php?invoiceID='+debt_id,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=750');	
}
</script>
</body>
</html>