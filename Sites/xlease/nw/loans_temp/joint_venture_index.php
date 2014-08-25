<?php
include('../../config/config.php');
$main = pg_escape_string($_POST["main"]); // ผู้กู้หลัก
if($main == "")
{
	$main = pg_escape_string($_GET["main"]);
	$main = str_replace("ThaiaceReplaceSharp","#",$main);
}

$contype = pg_escape_string($_GET["contype"]); // ประเภทสินเชื่อ
$conCompany = pg_escape_string($_GET["conCompany"]); // บริษัท

// ชื่อประเภทสินเชื่อแบบเต็ม
$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contype') ");
$chk_con_type = pg_fetch_result($qry_chk_con_type,0);

// หา จำนวนวันที่ให้ส่งใบแจ้งหนี้ก่อนครบกำหนด
$qry_default_invoice_period = pg_query("SELECT \"thcap_get_config\"('create_invoice_period','$contype')");
$default_invoice_period = pg_result($qry_default_invoice_period,0);
$default_invoice_period = floor($default_invoice_period); // ถ้ามีเศษ ปัดลงเสมอ
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ผูกสัญญาเงินกู้ชั่วคราว</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act_home_index2.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
var main;
main = '<?php echo $main; ?>';

$(document).ready(function(){
	document.getElementById("btnSave").style.visibility = 'hidden';
	document.getElementById("reqUseLoans").style.visibility = 'hidden';
	document.getElementById("conCreditRef").style.visibility = 'hidden';
	document.getElementById("fs1").style.visibility = 'hidden';
	document.getElementById("fs2").style.visibility = 'hidden';
	document.getElementById("chkContractRef").style.visibility = 'hidden';
	
	//CreateNewRow();
	//guarantorNewRow(); // ผู้ค้ำประกัน
	chkadd(); // รายละเอียดที่อยู่
 
 
	$("#conDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });	
	$("#conStartDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });	
	/*$("#conEndDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });	*/
	$("#conFirstDue").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	
	/*$("#main").autocomplete({
        source: "listcus_main.php",
        minLength:1
    });*/
	
	/*$("#cusadd").autocomplete({
        source: "listcus_main.php",
        minLength:1
    });*/
	
	$("#conCreditRef").autocomplete({ // สัญญากู้นี้ใช้วงเงินไหน
        source: "listcontract.php",
        minLength:1
    });
	$('#pick_receipt1').autocomplete({
		source: "listreceipt.php",
        minLength:1
	});
	$('#pick_receipt2').autocomplete({
		source: "listreceipt.php",
        minLength:1
	});
	$('#pick_receipt3').autocomplete({
		source: "listreceipt.php",
        minLength:1
	});
	
	var chk_con_type = '<?php echo $chk_con_type; ?>';
	
	if(chk_con_type=='HIRE_PURCHASE'||chk_con_type=='LEASING')
	{
		$('#close_before').hide();
		$('#vat_close_before').hide();
		$('#noUse').click();
		$('#use_money').hide();
	}
});


function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function validate() 
{
	var custype = '<?php echo pg_escape_string($_GET['custype']); ?>';
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	var contype = '<?php echo $contype; ?>';
	var chk_con_type = '<?php echo $chk_con_type; ?>';

	if (document.frm.conid.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ เลขที่สัญญา";
	}
	
	//ตรวจสอบจำนวนตัวอักษรของเลขที่สัญญาใหม่  15 - 20 ตัวอักษร
	var stringnum = document.frm.conid.value.length;
	if(stringnum != 15 && stringnum != 20){
		theMessage = theMessage + "\n -->  เลขที่สัญญาควรจะจำนวน 15 หรือ 20 ตัวอักษรเท่านั้น !";
	}
	
	if (document.frm.contype.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือก ประเภทสินเชื่อ";
	}
	
	if (document.frm.conloanamt.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ จำนวนเงินกู้ หรือ ยอดจัด";
	}
	
	if (document.frm.conloanamt.value < 0) {
	theMessage = theMessage + "\n -->  จำนวนเงินกู้ ห้ามน้อยกว่า 0";
	}
	
	if (document.frm.conLoanIniRate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ % ดอกเบี้ยที่ตกลงต่อปี";
	}
	
	if (document.frm.conLoanIniRate.value < 0) {
	theMessage = theMessage + "\n -->  % ดอกเบี้ยที่ตกลงต่อปี ห้ามน้อยกว่า 0";
	}
	
	if (document.frm.conTerm.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ จำนวนเดือน";
	}
	
	if (document.frm.conTerm.value < 0) {
	theMessage = theMessage + "\n -->  จำนวนเดือน ห้ามน้อยกว่า 0";
	}
	
	if (document.frm.conExtRentMinPay.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ เงินขั้นต่ำค่าเช่า";
	}
	
	if (document.frm.conMinPay.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ เงินประกัน (ค่างวด)";
	}
	
	if (document.frm.conPenaltyRate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ ค่าติดทางทวงถาม กรณีไม่จ่าย";
	}
	
	if (document.frm.conDate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือก วันที่ทำสัญญา";
	}
	
	if (document.frm.conStartDate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือก วันที่รับเงิน";
	}
	
	if (document.frm.conFirstDue.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือก วันที่ครบกำหนดชำระงวดแรก";
	}	
	
	if (document.frm.main.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ ข้อมูลเจ้าของ";
	}
	
	
	if (document.frm.conRepeatDueDay.value=="00") {
	theMessage = theMessage + "\n -->  กรุณาเลือกวันที่จ่าย";
	}
	
	if (document.frm.address.value=="..........") {
	theMessage = theMessage + "\n -->  กรุณากดปุ่ม บันทึก อีกครั้ง";
	}
	
	if(document.getElementById("itUse").checked == false && document.getElementById("noUse").checked == false){
		theMessage = theMessage + "\n -->  กรุณาเลือก ใช้วงเงินหรือไม่";
	}
	
	if (document.getElementById("chkContractRef").value=="3") // 1 : ถูกต้อง || 2 : ยังระบุค่าไม่ครบ || 3 : เลขที่สัญญาดังกล่าวใช้ไม่ได้ เพราะเจ้าของคนนั้นไม่มีสิทธิ์ใช้ 
	{
		theMessage = theMessage + "\n -->  สัญญาวงเงินที่เลือกไม่สามารถใช้ได้!! กรุณาเลือกสัญญาวงเงินที่จะใช้ใหม่";
	}
	
	if($('#edit_addr_chkbx').is(':checked')==true)
	{
		if(document.frm.f_no.value=='')
		{
			theMessage = theMessage + "\n -->  กรุณาระบุเลขที่";
		}
		if(document.frm.f_subno.value==''&&$('#f_subnochk').is(':checked')==false)
		{
			theMessage = theMessage + "\n -->  กรุณาระบุหมู่ที่";
		}
		if(document.frm.f_soi.value==''&&$('#f_soichk').is(':checked')==false)
		{
			theMessage = theMessage + "\n -->  กรุณาระบุซอย";
		}
		if(document.frm.f_rd.value==''&&$('#f_rdchk').is(':checked')==false)
		{
			theMessage = theMessage + "\n -->  กรุณาระบุถนน";
		}
		if(document.frm.f_tum.value=='')
		{
			theMessage = theMessage + "\n -->  กรุณาระบุแขวง/ตำบล";
		}
		if(document.frm.f_aum.value=='')
		{
			theMessage = theMessage + "\n -->  กรุณาระบุเขต/อำเภอ";
		}
		if(document.frm.A_PRO.value=='')
		{
			theMessage = theMessage + "\n -->  กรุณาระบุจังหวัด";
		}
		if(document.frm.f_post.value==''&&$('#f_postchk').is(':checked')==false)
		{
			theMessage = theMessage + "\n -->  กรุณาระบุรหัสไปรษณีย์";
		}		
	}else{
		if(custype!='new'){
			if(document.frm.address.value == " "){
				theMessage = theMessage + "\n -->  กรุณาระบุ รายละเอียดที่อยู่";
			}
		}
	}

	// If no errors, submit the form
	if (theMessage == noErrors){
		//return true;
		//chklist();
		if(document.getElementById("valuechk").value=="1")
		{
			alert(' เลขที่สัญญานี้มีในระบบแล้ว กรุณาเปลี่ยนด้วยครับ ');
			return false;	
		}
		else if(document.getElementById("valuechk").value=="2")
		{
			alert(' เลขที่สัญญานี้กำลังรออนุมัติ กรุณาเปลี่ยนด้วยครับ ');
			return false;
		}
		else
		{
			if(chk_con_type=='JOINT_VENTURE')
			{
				var order = $('input[name="all_pick_itm[]"]:checked');
				var all_order = $(order).length;
				var start_r = 0;
				var order_val = '';
				var addr_val = '';
				while(start_r<all_order)
				{
					order_val = $(order[start_r]).val();
					addr_val = $(order[start_r]).parent().parent().find('input[name="H_addr[]"]').val();
					if(addr_val!='')
					{
						$(order[start_r]).val(order_val+','+addr_val);
					}
					$(order[start_r]).removeAttr('disabled');
					start_r++;
				}
			}
			
			return true;			
		}
	}
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}

function checkcon(){

	$.post("checkid.php",{
			id : document.frm.conid.value
			
		},
		function(data){		
			
				if(data=='No'){
						//alert(' รหัสซ้ำครับกรุณาเปลี่ยนด้วย ');
						document.getElementById("conid").style.backgroundColor ="#FF0000";
						var textalert = ' เลขที่สัญญานี้ มีอยู่ในระบบแล้ว ';
						$("#checkconid").css('color','#ff0000');
						$("#checkconid").html(textalert);
						document.getElementById("valuechk").value='1';
				}else if(data == 'YES'){
						document.getElementById("conid").style.backgroundColor = "#33FF33";
						$("#checkconid").html("");
						document.getElementById("valuechk").value='0';
				}
				else if(data=='Dup')
				{
					document.getElementById("conid").style.backgroundColor ="#FF0000";
					var textalert = ' เลขที่สัญญานี้ กำลังรออนุมัติ ';
					$("#checkconid").css('color','#ff0000');
					$("#checkconid").html(textalert);
					document.getElementById("valuechk").value='2';
				}
		});
};

function penalty(){ // หาค่าติดตามถวงถาม
	$.post("checkPenalty.php",{
		conloanamt : document.frm.conloanamt.value,
		contype : document.frm.contype.value,
		conMinPay : document.frm.conMinPay.value,
		conExtRentMinPay : document.frm.conExtRentMinPay.value
	},
	function(data){
		document.getElementById("conPenaltyRate").value = data;
	});
};

function chkadd(){
	$.post("address.php",{
			id : document.frm.cusadd.value			
		},
		function(data){		
			$("#address").text(data);
		}
	);
};		

function chklist(){
	if(document.getElementById("valuechk").value=="1"){
		alert(' เลขที่สัญญานี้มีในระบบแล้ว กรุณาเปลี่ยนด้วยครับ ');
		return false;	
	}else if(document.getElementById("valuechk").value=="2"){
		alert(' เลขที่สัญญานี้กำลังรอ กรุณาเปลี่ยนด้วยครับ ');
		return false;
	}else{
		return true;
	}		
}

</script>

<script type="text/javascript">
function FcGenPayTerm()
{  
	var theMessage2 = "Please complete the following: \n-----------------------------------\n";
	var noErrors2 = theMessage2
	
	//ตรวจสอบจำนวนตัวอักษรของเลขที่สัญญาใหม่ 15 - 20 ตัวอักษร
	var stringnum = document.frm.conid.value.length;
	if(stringnum != 15 && stringnum != 20){
		theMessage2 = theMessage2 + "\n -->  เลขที่สัญญาควรจะจำนวน 15 หรือ 20 ตัวอักษรเท่านั้น !";
	}

	if (document.frm.conTerm.value=="") {
	theMessage2 = theMessage2 + "\n -->  กรุณาระบุ จำนวนเดือน";
	}
	
	if (document.frm.conTerm.value < 0) {
	theMessage2 = theMessage2 + "\n -->  จำนวนเดือน ห้ามน้อยกว่า 0";
	}
	
	if (document.frm.conInvoicePeriod.value=="") {
	theMessage2 = theMessage2 + "\n -->  กรุณาระบุ จำนวนวันที่ให้ส่งใบแจ้งหนี้ก่อนครบกำหนด";
	}
	
	if (document.frm.conFirstDue.value=="") {
	theMessage2 = theMessage2 + "\n -->  กรุณาระบุ วันที่ครบกำหนดชำระงวดแรก";
	}
	
	if (document.frm.conMinPay.value=="") {
	theMessage2 = theMessage2 + "\n -->  กรุณาระบุ เงินประกัน (ค่างวด)";
	}
	
	if (document.frm.conExtRentMinPay.value=="") {
	theMessage2 = theMessage2 + "\n -->  กรุณาระบุ เงินขั้นต่ำค่าเช่า";
	}
	
	if (document.frm.conRepeatDueDay.value=="00") {
	theMessage2 = theMessage2 + "\n -->  กรุณาลือก จ่ายทุกวันๆ";
	}
	
	// If errors, submit the form
	if (theMessage2 != noErrors2)
	{
		alert(theMessage2);
	}
	else
	{
		var genPayTerm = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร genPayTerm  
			url: "genPayTerm.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
			data:"term="+$("#conTerm").val()+"&conFirstDue="+$("#conFirstDue").val()+"&conMinPay="+$("#conMinPay").val()+"&conRepeatDueDay="+$("#conRepeatDueDay").val(), // ส่งตัวแปรแบบ GET
			async: false  
		}).responseText;
		$("#genPayTerm").html(genPayTerm); // นำค่า genPayTerm มาแสดงใน div ที่ชื่อ genPayTerm
		
		document.getElementById("btnSave").style.visibility = 'visible';
	}
}

function myConType()
{
	if(document.frm.contype.value=="FA")
	{
		$("#fontConType").text('บิล :');
		
		var billFA = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร billFA  
			url: "billFA.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
			data:"main="+main, // ส่งตัวแปรแบบ GET
			async: false
		}).responseText;
		$("#billFA").html(billFA); // นำค่า billFA มาแสดงใน div ที่ชื่อ billFA
		
		billNewRow(); // เพิ่มบิล
	}
	else
	{
		$("#fontConType").text('');
		
		var billFA = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร billFA  
			url: "empty.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
			//data:"" // ส่งตัวแปรแบบ GET
			async: false  
		}).responseText;
		$("#billFA").html(billFA); // นำค่า billFA มาแสดงใน div ที่ชื่อ billFA
	}
	
	if(document.getElementById("chk_con_type").value == "JOINT_VENTURE")
	{
		document.getElementById("DPCtext1").style.visibility = 'visible';
		document.getElementById("DPCtext2").style.visibility = 'visible';
	}
	else
	{
		document.getElementById("DPCtext1").style.visibility = 'hidden';
		document.getElementById("DPCtext2").style.visibility = 'hidden';
	}
}

function useIt() // ใช้วงเงิน
{
	document.getElementById("conCreditRef").style.visibility = 'visible';
	document.getElementById("fs1").style.visibility = 'visible';
	document.getElementById("fs2").style.visibility = 'visible';
	
	document.getElementById("conCreditRef").style.backgroundColor ="#FFFFFF";
	document.getElementById("conCreditRef").readOnly = false;
	
	if(document.frm.contype.value=="FA" || document.frm.contype.value=="PN")
	{
		document.getElementById("reqUseLoans").style.visibility = 'visible';
	}
	else
	{
		document.getElementById("reqUseLoans").style.visibility = 'hidden';
	}
	
	var borrowersEmpty = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร borrowersEmpty  
		url: "emptyContractRef.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		//data:"" // ส่งตัวแปรแบบ GET
		async: false  
	}).responseText;
	$("#borrowersGroup").html(borrowersEmpty); // นำค่า borrowersEmpty มาแสดงใน div ที่ชื่อ borrowersGroup
}

function noUseIt() // ไม่ใช้วงเงิน
{
	document.getElementById("conCreditRef").style.visibility = 'visible';
	document.getElementById("fs1").style.visibility = 'visible';
	document.getElementById("fs2").style.visibility = 'visible';
	
	document.getElementById("conCreditRef").style.backgroundColor ="#CCCCCC";
	document.getElementById("conCreditRef").value ="";
	document.getElementById("conCreditRef").readOnly = true;
	
	if(document.frm.contype.value=="FA" || document.frm.contype.value=="PN")
	{
		document.getElementById("reqUseLoans").style.visibility = 'visible';
	}
	else
	{
		document.getElementById("reqUseLoans").style.visibility = 'hidden';
	}
	
	var selectBorrowers = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร selectBorrowers  
		url: "selectBorrowers.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		//data:"main="+main, // ส่งตัวแปรแบบ GET
		async: false
	}).responseText;
	$("#borrowersGroup").html(selectBorrowers); // นำค่า selectBorrowers มาแสดงใน div ที่ชื่อ borrowersGroup
	
	CreateNewRow();
	guarantorNewRow(); // ผู้ค้ำประกัน
}

function groupNamne()
{
	var txtCon = document.getElementById("conCreditRef").value; // เลขที่สัญญาวงเงินที่จะใช้
	var conType = document.frm.contype.value; // ประเภทสินเชื่อ
	
	var borrowersGroup = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร borrowersGroup  
		url: "borrowersGroup.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		data:"contractID="+txtCon+"&conType="+conType+"&main="+main, // ส่งตัวแปรแบบ GET
		async: false
	}).responseText;
	$("#borrowersGroup").html(borrowersGroup); // นำค่า borrowersGroup มาแสดงใน div ที่ชื่อ borrowersGroup
}

function pick_Order(){
	var state = $('#pickOrder1').is(':checked');
	
	$('.hide').hide();
	
	if(state==true)
	{
		$('input[name="pickOrdertype"]').removeAttr('checked');
		$('#tr_add_rct').show();
		$('input[name="pick_receipt2"]').val('');
	}
	else
	{
		$('#pick_receipt1').val('');
		$('#tr_pick_type').show();
	}
}

function pick_type() {
	var state = $('#pickOrdertype1').is(':checked');
	
	$('.sub_hide').hide();
	
	if(state==true)
	{
		$('#tr_find_rct1').show();
		$('input[name="pick_receipt2"]').val('');
	}
	else
	{
		$('#tr_find_rct2').show();
		$('input[name="pick_receipt2"]').val('');
	}
}
function fct_add_receipt(){
	var rcid_full = $('#pick_receipt1').val().split('#');
	var rcID = rcid_full[0];
	var picked_itm = $('input[name="all_pick_itm[]"]');
	var all_picked_itm = $(picked_itm).length;
	var picked_val = '';
	var chk_itm = 0;
	var start_n = 0;
	if(rcID=='')
	{
		alert('กรุณาเลือกใบเสร็จก่อนครับ');
	}
	else
	{
		while(start_n<all_picked_itm)
		{
			picked_val = $(picked_itm[start_n]).val();
			picked_arr_val = picked_val.split(',');
			if(picked_arr_val[0]==rcID)
			{
				chk_itm++;
			}
			start_n++;
		}
		if(chk_itm==0)
		{
			$.post('genOrder.php',{rcid:rcID,type:'all'},function(data){
				if(data!='0')
				{
					$('#pick').show();
					$('#show_pick').append(data);
					$('.currentaddr').show();
				}
				else
				{
					alert('ไม่พบรายการสินค้าในใบเสร็จที่ระบุ');
				}
			});
		}
		else
		{
			alert('ไม่สามารถใช้ใบเสร็จนี้ได้  เนื่องจากคุณได้เลือกรายการสินค้าในใบเสร็จนี้แล้ว');
		}
	}
}
function remove_select_itm() {
	var sum_all = $('input[name="all_pick_itm[]"]:checked').length;
	if(sum_all<1)
	{
		alert('กรุณาเลือกรายการที่ต้องการลบก่อนครับ');
	}
	else
	{
		$('input[name="all_pick_itm[]"]:checked').parent().parent().remove();
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function delete_this_row(elem){
	$(elem).parent().parent().remove();
}
function edit_addr() {
	if($('#edit_addr_chkbx').is(':checked')==true)
	{
		$('.tr_addr').hide();
		$('.tr_edit_addr').show();
	}
	else
	{
		$('.tr_edit_addr').hide();
		$('.tr_addr').show();
	}
}
</script>
<script type="text/javascript">
function add_new_cus(elem1,elem2){
	var doc_h = $(document).height();
	var doc_w = $(document).width();
	var scrll_t = $('body').scrollTop();
	
	var popup_h = $('.popup_new_cus').height();
	var popup_w = 450;
	
	var popup_pst_y = (doc_w/2)+(scrll_t/2)-150;
	var popup_pst_x = (doc_w/2)-(popup_w/2);
	
	$('.popup_new_cus').css('margin-top',popup_pst_y);
	$('.popup_new_cus').css('margin-left',popup_pst_x);
	
	$('.overlay').css('height',doc_h);
	
	$('#popup_input').val(elem1);
	$('#popup_cbx').val(elem2);
	
	$('#nc_prefix_name').val('');
	$('#nc_first_name').val('');
	$('#nc_last_name').val('');
	$('#nc_id_card').val('');
	
	$('.overlay').fadeIn(1000);
}
function close_popup(){
	var elem = $('#popup_cbx').val();
	$('#'+elem).removeAttr('checked');
	$('.overlay').fadeOut(1000);
}
function validate_nc(){
	var prefix = $('#nc_prefix_name').val();
	var fname = $('#nc_first_name').val();
	var lname = $('#nc_last_name').val();
	var id_card = $('#nc_id_card').val();
	
	var msg = "โปรดระบุ\r\n-------------------------------------------------------------------------------------------\r\n";
	var chk_msg = msg;
	
	if(prefix=='')
	{
		msg+='\r\n\t--> คำนำหน้า';
	}
	if(fname=='')
	{
		msg+='\r\n\t--> ชื่อลูกค้า';
	}
	if(lname=='')
	{
		msg+='\r\n\t--> นามสกุลลูกค้า';
	}
	if(id_card=='')
	{
		msg+='\r\n\t--> เลขบัตรประชาชนลูกค้า';
	}
	if(check_card()==false)
	{
		msg+='\r\n\t--> เลขบัตรประชาชนลูกค้าไม่ถูกต้อง';
	}
	if(msg!=chk_msg)
	{
		alert(msg);
	}
	else
	{
		$.post('add_new_cus.php',{prefix:prefix,fname:fname,lname:lname,id_card:id_card},function(data){
			if(data=='1')
			{
				get_new_cus();
			}
			else
			{
				alert('การทำรายการล้มเหลว');
			}
		});
	}
}
function get_new_cus(){
	var prefix = $('#nc_prefix_name').val();
	var fname = $('#nc_first_name').val();
	var lname = $('#nc_last_name').val();
	
	$.post('get_new_cus.php',{prefix:prefix,fname:fname,lname:lname},function(data){
		var input = $('#popup_input').val();
		$('#'+input).val(data);
		var elem = $('#popup_cbx').val();
		$('#'+elem).attr('disabled','disabled');
		$('.overlay').fadeOut(1000);
	});
}
function check_card(){
	var data = $('#nc_id_card').val();
	if(data.length==13)
	{
		var digit = data.split('');
		var i = 0;
		var m = 13;
		var sum = 0;
		while(i<12)
		{
			var s = digit[i]*m;
			sum = sum+s;
			i++;
			m--;
		}
		var chk_digit = 11-(sum%11);
		if(digit[12]==chk_digit)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
function change_addr(elem){
	<?php
		$arr_cus = split("#",$main);
		$cus_id = $arr_cus[0];
	?>
	var cusid = '<?php echo $cus_id; ?>';
	var doc_h = $(document).height();
	var doc_w = $(document).width();
	var scrll_t = $(window).scrollTop();
	
	var popup_h = $('.popup_pick_addr').html();
	var popup_w = 450;
	
	var popup_pst_y = scrll_t+50;
	var popup_pst_x = (doc_w/2)-(popup_w/2)-150;
	
	$(elem).addClass('focus_list');
	
	$('.popup_pick_addr').css('margin-top',popup_pst_y);
	$('.popup_pick_addr').css('margin-left',popup_pst_x);
	
	$('.overlay1').css('height',doc_h);
	
	document.frm_add_new_addr.reset();
	
	$.post('gen_address.php',{cusid:cusid},function(data){
		$('.list_add').html(data);
		$('.overlay1').fadeIn(1000);
	});
}
function close_popup1(){
	$('.overlay1').fadeOut(1000);
}
function close_popup2(){
	$('.overlay2').fadeOut(1000);
}
function validate_na() {
	
	var cusid = '<?php echo $cus_id; ?>';
	
	var msg = "โปรดระบุ\r\n-------------------------------------------------------------------------------------------\r\n";
	var chk_msg = msg;
	
	if($('#na_homenumber').val()=='')
	{
		msg+='\r\n\t--> บ้านเลขที่';
	}
	if($('#na_tambon').val()=='')
	{
		msg+='\r\n\t--> ตำบล/แขวง';
	}
	if($('#na_district').val()=='')
	{
		msg+='\r\n\t--> อำเภอ/เขต';
	}
	if($('#na_province').val()=='')
	{
		msg+='\r\n\t--> จังหวัด';
	}
	if($('#na_zipcode').val()=='')
	{
		msg+='\r\n\t--> รหัสไปรษณีย์';
	}
	if(msg!=chk_msg)
	{
		alert(msg);
	}
	else
	{
		var na_room = $('#na_room').val();
		var na_floor = $('#na_floor').val();
		var na_homenumber = $('#na_homenumber').val();
		var na_building = $('#na_building').val();
		var na_moo = $('#na_moo').val();
		var na_village = $('#na_village').val();
		var na_soi = $('#na_soi').val();
		var na_road = $('#na_road').val();
		var na_tambon = $('#na_tambon').val();
		var na_district = $('#na_district').val();
		var na_province = $('#na_province').val();
		var na_zipcode = $('#na_zipcode').val();
		
		$.post('save_new_address.php',{na_room:na_room,na_floor:na_floor,na_homenumber:na_homenumber,na_building:na_building,na_moo:na_moo,na_village:na_village,na_soi:na_soi,na_road:na_road,na_tambon:na_tambon,na_district:na_district,na_province:na_province,na_zipcode:na_zipcode,cusid:cusid},function(data){
			if(data!='1')
			{
				if(data=='2')
				{
					alert('มีที่อยู่นี้แล้ว');
				}
				else
				{
					alert('บันทึกข้อมูลไม่สำเร็จ');
				}
			}
			else
			{
				$.post('gen_address.php',{cusid:cusid},function(data){
					$('.list_add').html(data);
				});
				document.frm_add_new_addr.reset();
			}
		});
	}
}
function pick_this_addr(assetid,full_addr){
	$('.focus_list').parent().find('.span_addr').html(full_addr);
	$('.focus_list').parent().parent().find('input[name="H_addr[]"]').val('0,'+assetid);
	$('.focus_list').removeClass('focus_list');
	$('.overlay1').fadeOut(1000);
}
function delete_this_addr(assetid,full_addr) {
	var cusid = '<?php echo $cus_id; ?>';
	$.post('delete_addr.php',{assetid:assetid},function(data){
		if(data!='1')
		{
			if(data=='2')
			{
				alert('ไม่สามารถลบที่อยู่นี้ได้เนื่องจากที่อยู่นี้ถูกนำไปใช้แล้ว');
			}
			else
			{
				alert('ไม่สามารถลบที่อยู่นี้ได้');
			}
		}
		else
		{
			var elem = $('.span_addr');
			var sum_elem = $(elem).length;
			
			var i = 0;
			
			while(i<sum_elem)
			{
				if($(elem[i]).html()==full_addr)
				{
					$(elem[i]).html('ใช้ที่อยู่เดียวกันกับสัญญา');
					$(elem[i]).parent().parent().find('input[name="H_addr[]"]').val('1,0');
				}
				i++;
			}
			$.post('gen_address.php',{cusid:cusid},function(data){
				$('.list_add').html(data);
			});
		}
	});
}
function change_addr_to_contract(){
	var elem = $('input[name="all_pick_itm[]"]');
	var all_elem = $(elem).length;
	
	var i = 0;
	
	while(i<all_elem)
	{
		if($('#use_same_contract').is(':checked')==true)
		{
			$(elem[i]).parent().parent().find('input[name="H_addr[]"]').val('1,0');
			$(elem[i]).parent().parent().find('.span_addr').html('ใช้ที่อยู่เดียวกันกับสัญญา');
		}
		else
		{
			$(elem[i]).parent().parent().find('input[name="H_addr[]"]').val('');
			$(elem[i]).parent().parent().find('.span_addr').html('ไม่ระบุ');
		}
		i++;
	}
}
function change_each_addr_to_contract() {
	$('.focus_list').parent().find('.span_addr').html('ใช้ที่อยู่เดียวกันกับสัญญา');
	$('.focus_list').parent().parent().find('input[name="H_addr[]"]').val('1,0');
	$('.focus_list').removeClass('focus_list');
	$('.overlay1').fadeOut(1000);
}
</script>

</head>
<body >
<div class="overlay">
	<div class="popup_new_cus">
    	<div class="note">
        	หมายเหตุ : การเพิ่มลูกค้าใหม่ในหน้านี้อนุญาติให้เพิ่มเฉพาะลูกค้าบุคคลธรรมดาที่มีหมายเลขบัตรประชาชนและมีสัญชาติไทยเท่านั้น ห้ามเพิ่มลูกค้านิติบุคคลหรือลูกค้าประเภทอื่น ๆ ที่นอกเหนือจากนี้โดยเด็ดขาด !!! 
        </div>
        <div class="data">
        	<input type="hidden" name="popup_input" id="popup_input" />
        	<input type="hidden" name="popup_cbx" id="popup_cbx" />
        	<div class="x" onclick="close_popup();"></div>
        	<div class="data_head">ข้อมูลลูกค้าใหม่</div>
        	<table border="0" cellpadding="5" cellspacing="1">
            	<tr>
                	<td>คำนำหน้า : </td>
                    <td><input type="text" name="nc_prefix_name" id="nc_prefix_name" size="30" /></td>
                </tr>
                <tr>
                	<td>ชื่อลูกค้า : </td>
                    <td><input type="text" name="nc_first_name" id="nc_first_name" size="30" /></td>
                </tr>
                <tr>
                	<td>นามสกุลลูกค้า : </td>
                    <td><input type="text" name="nc_last_name" id="nc_last_name" size="30" /></td>
                </tr>
                <tr>
                	<td>เลขบัตรประชาชน : </td>
                    <td><input type="text" name="nc_id_card" id="nc_id_card" size="30" /></td>
                </tr>
            </table>
            <div class="submit_nc_frm">
            	<input type="button" name="submit_nc" id="submit_nc" class="ui-button-icon-primary" value="บันทึก" onclick="validate_nc();" />
            </div>
        </div>
    </div>
    <div class="alert"></div>
</div>
<!--------------------------------------------------------------------------------------- -->
<div class="overlay1">
	<div class="popup_pick_addr">
    	<div class="note1">
        	หมายเหตุ : หากเคยเพิ่มที่อยู่แล้วและไม่มีการแก้ไขเปลี่ยนแปลงที่อยู่ให้เลือกที่อยู่จากรายการด้านล่าง  แต่หากยังไม่เคยเพิ่มหรือมีการแก้ไขเปลี่ยนแปลงที่อยู่ให้ทำการเพิ่มที่อยู่ใหม่ครับ
        </div>
        <div class="data1">
        	<div class="x1" onclick="close_popup1();"></div>
        	<div class="data_head1">เลือกรายการที่ตั้งเครื่อง</div>
            <div class="same_contract"><input type="button" name="btn_same_contract" id="btn_same_contract" onclick="change_each_addr_to_contract();" value="ใช้ที่อยู่เดียวกันกับสัญญา" /></div>
        	<div class="list_add"></div>
            <div class="data_head1">หรือ เพิ่มใหม่</div>
            <div class="div_add_new_addr">
            	<div align="center">
                	<form name="frm_add_new_addr" id="frm_add_new_addr">
                        <table border="0" cellpadding="5" cellspacing="1" width="400">
                            <tr>
                                <td>ห้อง:</td>
                                <td><input type="text" name="na_room" id="na_room" size="45" /></td>
                            </tr>
                            <tr>
                                <td>ชั้น:</td>
                                <td><input type="text" name="na_floor" id="na_floor" size="45" /></td>
                            </tr>
                            <tr>
                                <td>บ้านเลขที่<span class="hilight">*</span>:</td>
                                <td><input type="text" name="na_homenumber" id="na_homenumber" size="45" /></td>
                            </tr>
                            <tr>
                                <td>อาคาร:</td>
                                <td><input type="text" name="na_building" id="na_building" size="45" /></td>
                            </tr>
                            <tr>
                                <td>หมู่:</td>
                                <td><input type="text" name="na_moo" id="na_moo" size="45" /></td>
                            </tr>
                            <tr>
                                <td>หมู่บ้าน:</td>
                                <td><input type="text" name="na_village" id="na_village" size="45" /></td>
                            </tr>
                            <tr>
                                <td>ซอย:</td>
                                <td><input type="text" name="na_soi" id="na_soi" size="45" /></td>
                            </tr>
                            <tr>
                                <td>ถนน:</td>
                                <td><input type="text" name="na_road" id="na_road" size="45" /></td>
                            </tr>
                            <tr>
                                <td>ตำบล/แขวง<span class="hilight">*</span>:</td>
                                <td><input type="text" name="na_tambon" id="na_tambon" size="45" /></td>
                            </tr>
                             <tr>
                                <td>อำเภอ/เขต<span class="hilight">*</span>:</td>
                                <td><input type="text" name="na_district" id="na_district" size="45" /></td>
                            </tr>
                            <tr>
                                <td>จังหวัด<span class="hilight">*</span>:</td>
                                <td>
                                    <select name="na_province" id="na_province">
                                        <option value="">-------------------- เลือกจังหวัด --------------------</option>
                                        <?php
                                            $qr_province = pg_query("select * from \"nw_province\" order by \"proName\" asc");
                                            while($rs_province = pg_fetch_array($qr_province))
                                            {
                                                $proID = $rs_province['proID'];
                                                $proName = $rs_province['proName'];
                                                echo "<option value=\"$proName\">$proName</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>รหัสไปรษณี<span class="hilight">*</span>:</td>
                                <td><input type="text" name="na_zipcode" id="na_zipcode" size="45" /></td>
                            </tr>
                        </table>
                    </form>
            	</div>
            </div>
            <div class="submit_na_frm">
            	<input type="button" name="submit_na" id="submit_na" class="ui-button-icon-primary" value="บันทึก" onclick="validate_na();" />
            </div>
        </div>
    </div>
    <div class="alert1"></div>
</div>

<center><h2>(THCAP) ผูกสัญญาเงินกู้ชั่วคราว</h2></center>
<table width="900" frame="border" cellspacing="3" cellpadding="3" style="margin-top:1px" align="center" bgcolor="#AAFFAA">
<tr>
	<td><br></td>
</tr>
<tr>
	<td align="center">
		สัญญาล่าสุดหมวด MG
	</td>
	<td align="center">
		สัญญาล่าสุดหมวด LI
	</td>
	<td align="center">
		สัญญาล่าสุดหมวด SM
	</td>
	<td align="center">
		สัญญาล่าสุดหมวด FA
	</td>
	<td align="center">
		สัญญาล่าสุดหมวด PN
	</td>
	<td align="center">
		สัญญาล่าสุดหมวด CG
	</td>
</tr>
<tr>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'MG'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'LI'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'SM'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'FA'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'PN'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'CG'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
</tr>
<tr>
	<td><br></td>
</tr>		
</table>
<form name="frm" action="query.php" method="POST">
<table width="900" border="0" cellspacing="3" cellpadding="3" style="margin-top:1px" align="center" bgcolor="#DDFFAA" id="tble">
<tr>
	<td width="30%"><br></td>
	<td width="70%"><br></td>
	<input type="hidden" name="valuechk" id="valuechk">
</tr>
<tr>
	<td align="right">เลขที่สัญญา <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conid" id="conid" size="50" onkeyup="javascript : checkcon()" onblur="javascript : checkcon();"><span id="checkconid" name="checkconid"></span></td>
</tr>
<tr>
	<td align="right">ประเภทสินเชื่อ <font color="#FF0000"><b> * </b></font> : </td>
	<td>
		<input type="text" name="contype" value="<?php echo $contype; ?>" readonly style="background:#CCCCCC;">
		<input type="hidden" name="chk_con_type" id="chk_con_type" value="<?php echo $chk_con_type; ?>">
	</td>	
</tr>
<tr>
	<td align="right">บริษัท : </td>
	<td>
		<input type="text" name="conCompany" value="<?php echo $conCompany; ?>" readonly style="background:#CCCCCC;">
	</td>
</tr>
<tr>
	<td align="right">ยอดลงทุน <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conloanamt" id="conloanamt" value="0" onkeyup="javascript : penalty();" onblur="javascript : penalty();"></td>
</tr>
<tr>
	<td align="right">% ดอกเบี้ยที่ตกลงต่อปี <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conLoanIniRate"><?php if($chk_con_type == "JOINT_VENTURE"){ echo " <font color=\"#FF0000\">หากเป็นอัตราดอกเบี้ยต่อเดือนให้กรอกโดยนำอัตรานั้นมาคูณ 12</font>"; } ?></td>
</tr>
<tr>
	<td align="right">จำนวนวันที่ให้ส่งใบแจ้งหนี้ก่อนครบกำหนด <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conInvoicePeriod" id="conInvoicePeriod" value="<?php echo $default_invoice_period; ?>" readOnly style="background:#CCCCCC"></td>
</tr>
<tr>
	<td align="right">จำนวนเดือน <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conTerm" id="conTerm"></td>
</tr>
<tr>
	<td align="right">เงินประกัน (ค่างวด) <font color="#FF0000"><b> * </b></font>  : </td>
	<td><input type="textbox" name="conMinPay" id="conMinPay" value="0" onkeyup="javascript : penalty();" onblur="javascript : penalty();"> <font color="#FF0000">ถ้าจ่ายไม่เท่ากันทุกงวดในส่วนนี้จะเป็น 0</font></td>
</tr>
<tr>
	<td align="right">ยอดขั้นต่ำค่าเช่า<font color="#FF0000"><b> * </b></font>  : </td>
	<td><input type="textbox" name="conExtRentMinPay" id="conExtRentMinPay" value="0" onkeyup="javascript : penalty();" onblur="javascript : penalty();"> <font color="#FF0000">ถ้าจ่ายไม่เท่ากันทุกงวดในส่วนนี้จะเป็น 0</font></td>
</tr>
<tr>
	<td align="right">ค่าติดทางทวงถาม กรณีไม่จ่าย <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conPenaltyRate" id="conPenaltyRate" value="400" readonly style="background:#CCCCCC"></td>
</tr>
<tr>
	<td align="right">วันที่ทำสัญญา <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conDate" id="conDate"></td>
</tr>
<tr>
	<td align="right"><span id="textStartDate">วันที่รับเงิน</span> <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conStartDate" id="conStartDate"></td>
</tr>
<tr>
	<td align="right">วันที่ครบกำหนดชำระงวดแรก <font color="#FF0000"><b> * </b></font>  : </td>
	<td><input type="textbox" name="conFirstDue" id="conFirstDue"><?php if($chk_con_type == "JOINT_VENTURE"){ echo " <font color=\"#FF0000\">หากมี BEGINNING ณ วันรับสินค้า ให้เลือกเป็นวันที่รับสินค้า</font>"; } ?></td>
</tr>
<tr>
	<td align="right">จ่ายทุกวันๆ <font color="#FF0000"><b> * </b></font>  : </td>
	<td>
		<select name="conRepeatDueDay" id="conRepeatDueDay">
			<option value="00">กรุณาเลือกวันที่จ่าย</option>
			<?php
			for($i=1;$i<=28;$i++)
			{
				if(strlen($i) == 1){$i = "0".$i;}
				echo "<option value=\"$i\">".$i."</option>";
			}
			?>	
		</select>
	</td>
</tr>
<?php
if($chk_con_type == "JOINT_VENTURE")
{
	$qry_conFineRate = pg_query("select \"thcap_get_config\"('penalty_rate','$contype')");
	$conFineRate = pg_fetch_result($qry_conFineRate,0);
?>
<tr>
	<td align="right">% เบี้ยปรับผิดนัด : </td>
	<td><input type="textbox" name="conFineRate" id="conFineRate" value="<?php echo $conFineRate; ?>" readOnly style="background:#CCCCCC"></td>
</tr>
<?php
}
?>

<tr id="use_money">
	<td align="right">ใช้วงเงินหรือไม่ <font color="#FF0000"><b> * </b></font> : </td>
	<td>
		<input type="radio" name="useloans" id="noUse" onchange="noUseIt()">ไม่ใช้วงเงิน
		&nbsp;&nbsp;&nbsp;
		<input type="radio" name="useloans" id="itUse" onchange="useIt()">ใช้วงเงิน
	</td>
</tr>

<tr>
	<td align="right"><font id="fs1">สัญญากู้นี้ใช้วงเงิน </font><font id="reqUseLoans" color="#FF0000"><b> * </b></font><font id="fs2"> : </font></td>
	<td><input type="textbox" name="conCreditRef" id="conCreditRef" onkeyup="groupNamne()" onblur="groupNamne()"></td>
</tr>
<tr>
	<td colspan="2" align="center"><div id="billFA"></div></td>
</tr>

<tr>
	<td colspan="2"> <hr width="650"> </td>
</tr>
<tr>
	<td align="right">เจ้าของ <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="main" id="main" size="50" value="<?php echo $main; ?>" readonly style="background:#CCCCCC;"></td>
</tr>

<tr>
	<td colspan="2" align="center"><div id="borrowersGroup"><input type="text" name="chkContractRef" id="chkContractRef" value="1" readonly></div></td>
</tr>

<tr>
	<td align="right">เลือกที่อยู่จากชื่อ :</td>
	<td>
		<input type="textbox" name="cusadd" id="cusadd" size="50" value="<?php echo $main; ?>" readonly style="background:#CCCCCC;" onkeyup="javascript : chkadd()" onblur="javascript : chkadd()">
	</td>
</tr>
<tr class="tr_addr">
	<td align="right">รายละเอียดที่อยู่ <font color="#FF0000"><b> * </b></font> :</td>
	<td><textarea cols="50" name="address"  id="address" rows="5" readonly style="background:#CCCCCC;"></textarea></td>
</tr>
<tr>
	<td align="right"></td>
	<td>
		<label><input type="checkbox" name="edit_addr_chkbx" id="edit_addr_chkbx" value="edited" onchange="edit_addr();" />แก้ไขที่อยู่สัญญา</label>
	</td>
</tr>
<tr class="tr_edit_addr">
	<td align="right">รายละเอียดที่อยู่ใหม่ <font color="#FF0000"><b> * </b></font> :</td>
	<td></td>
</tr>
<tr class="tr_edit_addr">
	<td colspan="2">
    	<table width="100%" cellSpacing="1" cellPadding="1" border="0">
            <tr valign="top">
                <td colspan="2">
                    <table width="100%" border="0" cellpadding="1" cellspacing="1" align="center">	
                        <tr>
                            <td align="right" width="30%">ห้อง :</td>
                            <td width="70%"><input type="text" name="f_room" /></td>
                        </tr>
                        <tr>
                            <td align="right">ชั้น :</td>
                            <td><input type="text" name="f_floor" /></td>
                        </tr>
                        <tr>
                            <td align="right">เลขที่ :</td>
                            <td><input type="text" name="f_no" /><font color="red" >*</font></td>
                        </tr>
                        <tr>
                            <td align="right">หมู่ที่ :</td>
                            <td><input type="text" name="f_subno" /><font color="red" >*</font>
                            <input type="checkbox" id="f_subnochk" onClick="javaScript:if(this.checked){document.frm.f_subno.disabled=true;document.frm.f_subno.value='';}else{document.frm.f_subno.disabled=false;}">ไม่มีข้อมูล
                            </td>
                        </tr>
                        <tr>
                            <td align="right">หมู่บ้าน :</td>
                            <td><input type="text" name="f_ban" size="50"/></td>
                        </tr>
                        <tr>
                            <td align="right">อาคาร/สถานที่ :</td>
                            <td><input type="text" name="f_building" size="50"/></td>
                        </tr>
                        <tr>
                            <td align="right">ซอย :</td>
                            <td><input type="text" name="f_soi" /><font color="red" >*</font>
                            <input type="checkbox" id="f_soichk" onClick="javaScript:if(this.checked){document.frm.f_soi.disabled=true;document.frm.f_soi.value='';}else{document.frm.f_soi.disabled=false;}">ไม่มีข้อมูล
                            </td>
                        </tr>
                        <tr>
                            <td align="right">ถนน :</td>
                            <td><input type="text" name="f_rd" /><font color="red" >*</font>
                            <input type="checkbox" id="f_rdchk" onClick="javaScript:if(this.checked){document.frm.f_rd.disabled=true;document.frm.f_rd.value='';}else{document.frm.f_rd.disabled=false;}">ไม่มีข้อมูล
                            </td>
                        </tr>
                        <tr>
                            <td align="right">แขวง/ตำบล :</td>
                            <td><input type="text" name="f_tum" /><font color="red" >*</font></td>
                        </tr>
                        <tr>
                            <td align="right">เขต/อำเภอ :</td>
                            <td><input type="text" name="f_aum" /><font color="red" >*</font></td>
                        </tr>
                        <tr>
                            <td align="right">จังหวัด :</td>
                            <td>	
                                <select name="A_PRO" size="1">
                                <?php
                                echo "<option value=\"\">---เลือก---</option>";
                                $query_province=pg_query("select * from \"nw_province\"  order by \"proID\"");
                                while($res_pro = pg_fetch_array($query_province)){
                                ?>
                                    <option value="<?php echo $res_pro["proName"];?>" ><?php echo $res_pro["proName"];?></option>
                                    <?php
                                }
                                ?>
                                </select><font color="red" >*</font>	
                            </td>
                        </tr>
                        <tr>
                            <td align="right">รหัสไปรษณีย์ :</td>
                            <td><input type="text" name="f_post" maxlength="5"/><font color="red" >*</font>
                            <input type="checkbox" id="f_postchk" onClick="javaScript:if(this.checked){document.frm.f_post.disabled=true;document.frm.f_post.value='';}else{document.frm.f_post.disabled=false;}">ไม่มีข้อมูล
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
    	</table>
    </td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="button" name="seeold" id="seeold" value="  แสดงตารางการผ่อนชำระ  " onclick="FcGenPayTerm()"></td>
</tr>
<tr>
	<td colspan="2" align="center">
		<div id="genPayTerm">

		</div>
	</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" id="btnSave" value="บันทึก" onclick="return validate();"></td>
</tr>
<tr>
	<td><br></td>
</tr>	
</table>

<div style="margin-top:5px;"></div>
	<?php 
	$where = "\"conRepeatDueDay\" is not null ";
	include("table_waitapp.php");
	
	?>
</form>
</body>

<script>
	myConType(); // ตรวจสอบประเภทสินเชื่อและทำตามเงื่อนไงที่กำหนด
	penalty(); // หาค่าติดตามถวงถาม
</script>

</html>