<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>(THCAP) รับวางบิล-ตั้งเจ้าหนี้</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui-1.10.2/css/ui-lightness/jquery-ui-1.10.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-ui-1.10.2.custom.min.js"></script>
	
</head>
<script type="text/javascript">
$(document).ready(function(){ 
	
	document.getElementById('span_conid_name').style.display = 'none';
	document.getElementById('span_conid').style.display = 'none';

	//วันที่ใบกำกับภาษี   
	$("#date_invoice").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
	 });
	 
	//วันที่ของเอกสารอ้างอิง
	$("#date_ref_no").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	//วันที่ครบกำหนดชำระ
	$("#date_due").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
});

$(function() {
	$( document ).tooltip();
});

function show_span_conid(){
	var ele=$('input[name="chk_debtor"]');  
	if(($(ele).is(':checked')))
	{  
		document.getElementById('span_conid').style.display='block';
		document.getElementById('span_conid_name').style.display = 'block';
		
	}
	else
	{	
		document.getElementById('span_conid').style.display = 'none';
		document.getElementById('span_conid_name').style.display = 'none';
		document.getElementById('txt_conid').value = "";
	}
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
//ตรวจสอบค่าก่อนส่งไปหน้า process
function chk_data(){
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	//ส่วน detail
	theMessage+=chk_inputdata_list_detail();
	//จบส่วน detail
	
	//ส่วนบัญชี
	theMessage+=chk_inputdata_accdetail();
	//จบส่วนบัญชี
	
	//หนี้ที่ต้องการจะตั้ง
	theMessage+=chk_inputdata_adddebt();
	//จบหนี้ที่ต้องการจะตั้ง
	
	if (theMessage == noErrors) {
		return true;
	} 
	else
	{		
		alert(theMessage);
		return false;
	}

}

</script>
 <style>
	label {
		display: inline-block;
		width: 5em;
	}
</style>
</head>
<body>
<center><h2>(THCAP) รับวางบิล-ตั้งเจ้าหนี้</h2></center>
<br>
<!--เพิ่มรายการ-->
<div id="add" name="add" align="center">
<form name="frm1" method="post" action="process_ap.php">
	<fieldset style="width:70%" align="center"><legend><B>เพิ่มรายการ</B></legend>
		<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
			<td> 
			<?php include('frm_list_detail.php');		?>
			<!--บัญชี-->	
			</td>
			</tr>
			<tr>
			<td align="center"> 			
			<div>			
				<?php
				$v_page="billingthecreditors"; // เพื่อใช้ในการ ตรวจสอบว่าจะไม่แสดง คอลัม จำนวนภาษีหัก ณ ที่จ่าย ,เลขที่ใบภาษีหัก ณ ที่จ่าย
				include('frm_accdetail.php');	?>			
			</div>	
			<!--จบบัญชี-->
			</td>
			</tr>
			</table>
			<!--กรอบหนี้ที่ต้องการจะตั้ง-->
			<div name="debt" id="debt">
				<div align="center" name="showdebt" id="showdebt">
					<?php include('frm_adddebt.php');		?>				
				</div>		
				<center>
			</div>
			<!--จบกรอบหนี้ที่ต้องการจะตั้ง-->
			<center>
				<input type="submit" value="ยืนยันรายการ" onclick="return chk_data();">
			</center>
	</fieldset>
</form>
</div>
<?php 	
	//รายการรออนุมัติ
	include('frm_pending_items.php');
?>


</body>
</html>