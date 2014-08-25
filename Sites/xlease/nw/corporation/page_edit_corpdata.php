<?php
include("../../config/config.php");

$corp_regis = $_GET["corp_regis"];
$corpID = $_GET["corpID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขลูกค้านิติบุคคล</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache"> 
    <link type="text/css" rel="stylesheet" href="act.css"></link>	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <!--<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>-->
	<script type="text/javascript" src="lib/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){


	
	// ช่องค้นหาของกรรมการ
	$("#BoardName1").autocomplete({
        source: "s_userid.php",
        minLength:1
    });
	
	// ช่องค้นหาของผู้ถือหุ้น
	$("#ShareName1").autocomplete({
        source: "s_userid.php",
        minLength:1
    });
	
	// ช่องค้นหาของผู้รับมอบอำนาจ
	$("#AttorneyName1").autocomplete({
        source: "s_userid.php",
        minLength:1
    });

	//H_adds_off();
	//M_adds_off();
	document.frm1.updatelistbox.style.visibility = 'hidden';
	
    $("#datepicker_regis").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#datepicker_last").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
});

function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.frm1.corp_regis.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ เลขทะเบียนนิติบุคคล";
	}
	
	if (document.frm1.corpName_THA.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ ชื่อนิติบุคคลภาษาไทย";
	}
	
	if (document.frm1.phone.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ เบอร์โทรศัพท์ลูกค้านิติบุคคล";
	}
	
	if (document.frm1.corpType.value=="ไม่ระบุ") {
	theMessage = theMessage + "\n -->  กรุณาเลือก ประเภทนิติบุคคล ";
	}
	
	if (document.frm1.datepicker_regis.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ วันที่จดทะเบียนบริษัท ";
	}
	
	if (document.getElementById("selete_adds_main1").checked == true || document.getElementById("selete_adds_one1").checked == true || document.getElementById("selete_adds_two1").checked == true) {
	
		if(document.getElementById("selete_adds_main1").checked == true)
		{
			if (document.frm1.C_HomeNumber.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ บ้านเลขที่ ของ ที่อยู่ตามหนังสือรับรอง";
			}
			
			if (document.frm1.C_Road.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ ถนน ของ ที่อยู่ตามหนังสือรับรอง";
			}
			
			if (document.frm1.C_State.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ เขต/อำเภอ ของ ที่อยู่ตามหนังสือรับรอง";
			}
			
			if (document.frm1.C_Province.value=="00") {
			theMessage = theMessage + "\n -->  กรุณาเลือก จังหวัด ของ ที่อยู่ตามหนังสือรับรอง";
			}
			
			if (document.frm1.C_Postal_code.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ รหัสไปรษณีย์ ของ ที่อยู่ตามหนังสือรับรอง";
			}
		}
		
		if(document.getElementById("selete_adds_one1").checked == true)
		{
			if (document.frm1.H_HomeNumber.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ บ้านเลขที่ ของ ที่อยู่สำนักงานใหญ่";
			}
			
			if (document.frm1.H_Road.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ ถนน ของ ที่อยู่สำนักงานใหญ่";
			}
			
			if (document.frm1.H_State.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ เขต/อำเภอ ของ ที่อยู่สำนักงานใหญ่";
			}
			
			if (document.frm1.H_Province.value=="00") {
			theMessage = theMessage + "\n -->  กรุณาเลือก จังหวัด ของ ที่อยู่สำนักงานใหญ่";
			}
			
			if (document.frm1.H_Postal_code.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ รหัสไปรษณีย์ ของ ที่อยู่สำนักงานใหญ่";
			}
		}
		
		if(document.getElementById("selete_adds_two1").checked == true)
		{
			if (document.frm1.M_HomeNumber.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ บ้านเลขที่ ของ ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)";
			}
			
			if (document.frm1.M_Road.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ ถนน ของ ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)";
			}
			
			if (document.frm1.M_State.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ เขต/อำเภอ ของ ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)";
			}
			
			if (document.frm1.M_Province.value=="00") {
			theMessage = theMessage + "\n -->  กรุณาเลือก จังหวัด ของ ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)";
			}
			
			if (document.frm1.M_Postal_code.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ รหัสไปรษณีย์ ของ ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)";
			}
		}
	}
	else{
	theMessage = theMessage + "\n -->  กรุณาระบุที่อยู่ อย่างน้อยหนึ่งที่อยู่ ";
	}
	
	if (document.frm1.acc_Name1.value!="" && document.frm1.acc_Number1.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ เลขที่บัญชี";
	}
	
	if (document.getElementById("selete_adds_two3").checked == true && document.getElementById("selete_adds_one1").checked == false) {
	theMessage = theMessage + "\n -->  กรุณาเลือกที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)ใหม่";
	}
	
	if (document.getElementById("selete_adds_two2").checked == true && document.getElementById("selete_adds_main1").checked == false) {
	theMessage = theMessage + "\n -->  กรุณาเลือกที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)ใหม่";
	}
	
	if (document.getElementById("selete_adds_one2").checked == true && document.getElementById("selete_adds_main1").checked == false) {
	theMessage = theMessage + "\n -->  กรุณาเลือกที่อยู่สำนักงานใหญ่ ใหม่";
	}
	
	if((!isNaN(parseFloat(document.frm1.initial_capital.value)) && isFinite(document.frm1.initial_capital.value)) == false && document.frm1.initial_capital.value != ""){
	theMessage = theMessage + "\n -->  ทุนจดทะเบียนเริ่มแรก ต้องเป็นตัวเลขเท่านั้น";
	}
	
	if((!isNaN(parseFloat(document.frm1.current_capital.value)) && isFinite(document.frm1.current_capital.value)) == false && document.frm1.current_capital.value != ""){
	theMessage = theMessage + "\n -->  ทุนจดทะเบียนปัจจุบัน ต้องเป็นตัวเลขเท่านั้น";
	}
	
	if((!isNaN(parseFloat(document.frm1.asset_avg.value)) && isFinite(document.frm1.asset_avg.value)) == false && document.frm1.asset_avg.value != ""){
	theMessage = theMessage + "\n -->  สินทรัพย์เฉลี่ย ต้องเป็นตัวเลขเท่านั้น";
	}
	
	if((!isNaN(parseFloat(document.frm1.revenue_avg.value)) && isFinite(document.frm1.revenue_avg.value)) == false && document.frm1.revenue_avg.value != ""){
	theMessage = theMessage + "\n -->  รายได้เฉลี่ย ต้องเป็นตัวเลขเท่านั้น";
	}
	
	if((!isNaN(parseFloat(document.frm1.debt_avg.value)) && isFinite(document.frm1.debt_avg.value)) == false && document.frm1.debt_avg.value != ""){
	theMessage = theMessage + "\n -->  หนี้สินเฉลี่ย ต้องเป็นตัวเลขเท่านั้น";
	}
	
	if((!isNaN(parseFloat(document.frm1.net_profit.value)) && isFinite(document.frm1.net_profit.value)) == false && document.frm1.net_profit.value != ""){
	theMessage = theMessage + "\n -->  กำไรสุทธิ ต้องเป็นตัวเลขเท่านั้น";
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

function H_adds_off()
{
	document.frm1.hh1_f.disabled = true;
	document.frm1.hh2_f.disabled = true;
	document.frm1.hh3_f.disabled = true;
	document.frm1.hh4_f.disabled = true;
	document.frm1.hh5_f.disabled = true;
	document.frm1.hh_other.disabled = true;
	
	document.frm1.H_HomeNumber.disabled = true;
	document.frm1.H_room.disabled = true;
	document.frm1.H_LiveFloor.disabled = true;
	document.frm1.H_Moo.disabled = true;
	document.frm1.H_Building.disabled = true;
	document.frm1.H_Village.disabled = true;
	document.frm1.H_Lane.disabled = true;
	document.frm1.H_Road.disabled = true;
	document.frm1.H_District.disabled = true;
	document.frm1.H_State.disabled = true;
	document.frm1.H_Province.disabled = true;
	document.frm1.H_Postal_code.disabled = true;
	document.frm1.H_Country.disabled = true;
	document.frm1.H_phone.disabled = true;
	document.frm1.H_tor.disabled = true;
	document.frm1.H_Fax.disabled = true;
	document.frm1.H_Live_it.disabled = true;
	document.frm1.H_Completion.disabled = true;
	document.frm1.H_Acquired.disabled = true;
	document.frm1.H_purchase_price.disabled = true;
	
	
	document.frm1.hh1_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hh2_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hh3_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hh4_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hh5_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hh_other.value = "ไม่เปิดเผยข้อมูล";
	
	document.frm1.H_HomeNumber.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_room.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_LiveFloor.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Moo.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Building.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Village.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Lane.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Road.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_District.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_State.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Province.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Postal_code.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Country.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_phone.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_tor.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Fax.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Live_it.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Completion.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_Acquired.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.H_purchase_price.value = "ไม่เปิดเผยข้อมูล";
}

function M_adds_off()
{
	document.frm1.hm1_f.disabled = true;
	document.frm1.hm2_f.disabled = true;
	document.frm1.hm3_f.disabled = true;
	document.frm1.hm4_f.disabled = true;
	document.frm1.hm5_f.disabled = true;
	document.frm1.hm_other.disabled = true;
	
	document.frm1.M_HomeNumber.disabled = true;
	document.frm1.M_room.disabled = true;
	document.frm1.M_LiveFloor.disabled = true;
	document.frm1.M_Moo.disabled = true;
	document.frm1.M_Building.disabled = true;
	document.frm1.M_Village.disabled = true;
	document.frm1.M_Lane.disabled = true;
	document.frm1.M_Road.disabled = true;
	document.frm1.M_District.disabled = true;
	document.frm1.M_State.disabled = true;
	document.frm1.M_Province.disabled = true;
	document.frm1.M_Postal_code.disabled = true;
	document.frm1.M_Country.disabled = true;
	document.frm1.M_phone.disabled = true;
	document.frm1.M_tor.disabled = true;
	document.frm1.M_Fax.disabled = true;
	document.frm1.M_Live_it.disabled = true;
	document.frm1.M_Completion.disabled = true;
	document.frm1.M_Acquired.disabled = true;
	document.frm1.M_purchase_price.disabled = true;
	
	document.frm1.hm1_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hm2_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hm3_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hm4_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hm5_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hm_other.value = "ไม่เปิดเผยข้อมูล";
	
	document.frm1.M_HomeNumber.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_room.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_LiveFloor.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Moo.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Building.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Village.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Lane.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Road.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_District.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_State.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Province.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Postal_code.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Country.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_phone.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_tor.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Fax.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Live_it.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Completion.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_Acquired.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.M_purchase_price.value = "ไม่เปิดเผยข้อมูล";
}

function H_adds_new()
{
	document.frm1.hh1_f.disabled = false;
	document.frm1.hh2_f.disabled = false;
	document.frm1.hh3_f.disabled = false;
	document.frm1.hh4_f.disabled = false;
	document.frm1.hh5_f.disabled = false;
	document.frm1.hh_other.disabled = false;
	
	document.frm1.H_HomeNumber.disabled = false;
	document.frm1.H_room.disabled = false;
	document.frm1.H_LiveFloor.disabled = false;
	document.frm1.H_Moo.disabled = false;
	document.frm1.H_Building.disabled = false;
	document.frm1.H_Village.disabled = false;
	document.frm1.H_Lane.disabled = false;
	document.frm1.H_Road.disabled = false;
	document.frm1.H_District.disabled = false;
	document.frm1.H_State.disabled = false;
	document.frm1.H_Province.disabled = false;
	document.frm1.H_Postal_code.disabled = false;
	document.frm1.H_Country.disabled = false;
	document.frm1.H_phone.disabled = false;
	document.frm1.H_tor.disabled = false;
	document.frm1.H_Fax.disabled = false;
	document.frm1.H_Live_it.disabled = false;
	document.frm1.H_Completion.disabled = false;
	document.frm1.H_Acquired.disabled = false;
	document.frm1.H_purchase_price.disabled = false;
	
	
	document.frm1.hh1_f.value = "";
	document.frm1.hh2_f.value = "";
	document.frm1.hh3_f.value = "";
	document.frm1.hh4_f.value = "";
	document.frm1.hh5_f.value = "";
	document.frm1.hh_other.value = "";
	
	document.frm1.H_HomeNumber.value = "";
	document.frm1.H_room.value = "";
	document.frm1.H_LiveFloor.value = "";
	document.frm1.H_Moo.value = "";
	document.frm1.H_Building.value = "";
	document.frm1.H_Village.value = "";
	document.frm1.H_Lane.value = "";
	document.frm1.H_Road.value = "";
	document.frm1.H_District.value = "";
	document.frm1.H_State.value = "";
	document.frm1.H_Province.value = "00";
	document.frm1.H_Postal_code.value = "";
	document.frm1.H_Country.value = "";
	document.frm1.H_phone.value = "";
	document.frm1.H_tor.value = "";
	document.frm1.H_Fax.value = "";
	document.frm1.H_Live_it.value = "";
	document.frm1.H_Completion.value = "";
	document.frm1.H_Acquired.value = "";
	document.frm1.H_purchase_price.value = "";
}

function M_adds_new()
{
	document.frm1.hm1_f.disabled = false;
	document.frm1.hm2_f.disabled = false;
	document.frm1.hm3_f.disabled = false;
	document.frm1.hm4_f.disabled = false;
	document.frm1.hm5_f.disabled = false;
	document.frm1.hm_other.disabled = false;
	
	document.frm1.M_HomeNumber.disabled = false;
	document.frm1.M_room.disabled = false;
	document.frm1.M_LiveFloor.disabled = false;
	document.frm1.M_Moo.disabled = false;
	document.frm1.M_Building.disabled = false;
	document.frm1.M_Village.disabled = false;
	document.frm1.M_Lane.disabled = false;
	document.frm1.M_Road.disabled = false;
	document.frm1.M_District.disabled = false;
	document.frm1.M_State.disabled = false;
	document.frm1.M_Province.disabled = false;
	document.frm1.M_Postal_code.disabled = false;
	document.frm1.M_Country.disabled = false;
	document.frm1.M_phone.disabled = false;
	document.frm1.M_tor.disabled = false;
	document.frm1.M_Fax.disabled = false;
	document.frm1.M_Live_it.disabled = false;
	document.frm1.M_Completion.disabled = false;
	document.frm1.M_Acquired.disabled = false;
	document.frm1.M_purchase_price.disabled = false;
	
	document.frm1.hm1_f.value = "";
	document.frm1.hm2_f.value = "";
	document.frm1.hm3_f.value = "";
	document.frm1.hm4_f.value = "";
	document.frm1.hm5_f.value = "";
	document.frm1.hm_other.value = "";
	
	document.frm1.M_HomeNumber.value = "";
	document.frm1.M_room.value = "";
	document.frm1.M_LiveFloor.value = "";
	document.frm1.M_Moo.value = "";
	document.frm1.M_Building.value = "";
	document.frm1.M_Village.value = "";
	document.frm1.M_Lane.value = "";
	document.frm1.M_Road.value = "";
	document.frm1.M_District.value = "";
	document.frm1.M_State.value = "";
	document.frm1.M_Province.value = "00";
	document.frm1.M_Postal_code.value = "";
	document.frm1.M_Country.value = "";
	document.frm1.M_phone.value = "";
	document.frm1.M_tor.value = "";
	document.frm1.M_Fax.value = "";
	document.frm1.M_Live_it.value = "";
	document.frm1.M_Completion.value = "";
	document.frm1.M_Acquired.value = "";
	document.frm1.M_purchase_price.value = "";
}

function H_adds_C()
{
	document.frm1.hh1_f.disabled = true;
	document.frm1.hh2_f.disabled = true;
	document.frm1.hh3_f.disabled = true;
	document.frm1.hh4_f.disabled = true;
	document.frm1.hh5_f.disabled = true;
	document.frm1.hh_other.disabled = true;
	
	document.frm1.H_HomeNumber.disabled = true;
	document.frm1.H_room.disabled = true;
	document.frm1.H_LiveFloor.disabled = true;
	document.frm1.H_Moo.disabled = true;
	document.frm1.H_Building.disabled = true;
	document.frm1.H_Village.disabled = true;
	document.frm1.H_Lane.disabled = true;
	document.frm1.H_Road.disabled = true;
	document.frm1.H_District.disabled = true;
	document.frm1.H_State.disabled = true;
	document.frm1.H_Province.disabled = true;
	document.frm1.H_Postal_code.disabled = true;
	document.frm1.H_Country.disabled = true;
	document.frm1.H_phone.disabled = true;
	document.frm1.H_tor.disabled = true;
	document.frm1.H_Fax.disabled = true;
	document.frm1.H_Live_it.disabled = true;
	document.frm1.H_Completion.disabled = true;
	document.frm1.H_Acquired.disabled = true;
	document.frm1.H_purchase_price.disabled = true;
	
	
	document.frm1.hh1_f.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.hh2_f.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.hh3_f.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.hh4_f.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.hh5_f.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.hh_other.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	
	document.frm1.H_HomeNumber.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_room.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_LiveFloor.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Moo.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Building.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Village.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Lane.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Road.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_District.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_State.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Province.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Postal_code.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Country.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_phone.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_tor.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Fax.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Live_it.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Completion.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_Acquired.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.H_purchase_price.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
}

function M_adds_C()
{
	document.frm1.hm1_f.disabled = true;
	document.frm1.hm2_f.disabled = true;
	document.frm1.hm3_f.disabled = true;
	document.frm1.hm4_f.disabled = true;
	document.frm1.hm5_f.disabled = true;
	document.frm1.hm_other.disabled = true;
	
	document.frm1.M_HomeNumber.disabled = true;
	document.frm1.M_room.disabled = true;
	document.frm1.M_LiveFloor.disabled = true;
	document.frm1.M_Moo.disabled = true;
	document.frm1.M_Building.disabled = true;
	document.frm1.M_Village.disabled = true;
	document.frm1.M_Lane.disabled = true;
	document.frm1.M_Road.disabled = true;
	document.frm1.M_District.disabled = true;
	document.frm1.M_State.disabled = true;
	document.frm1.M_Province.disabled = true;
	document.frm1.M_Postal_code.disabled = true;
	document.frm1.M_Country.disabled = true;
	document.frm1.M_phone.disabled = true;
	document.frm1.M_tor.disabled = true;
	document.frm1.M_Fax.disabled = true;
	document.frm1.M_Live_it.disabled = true;
	document.frm1.M_Completion.disabled = true;
	document.frm1.M_Acquired.disabled = true;
	document.frm1.M_purchase_price.disabled = true;
	
	document.frm1.hm1_f.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.hm2_f.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.hm3_f.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.hm4_f.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.hm5_f.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.hm_other.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	
	document.frm1.M_HomeNumber.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_room.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_LiveFloor.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Moo.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Building.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Village.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Lane.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Road.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_District.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_State.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Province.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Postal_code.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Country.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_phone.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_tor.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Fax.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Live_it.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Completion.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_Acquired.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
	document.frm1.M_purchase_price.value = "ใช้ที่อยู่ตามหนังสือรับรอง";
}

function M_adds_H()
{
	document.frm1.hm1_f.disabled = true;
	document.frm1.hm2_f.disabled = true;
	document.frm1.hm3_f.disabled = true;
	document.frm1.hm4_f.disabled = true;
	document.frm1.hm5_f.disabled = true;
	document.frm1.hm_other.disabled = true;
	
	document.frm1.M_HomeNumber.disabled = true;
	document.frm1.M_room.disabled = true;
	document.frm1.M_LiveFloor.disabled = true;
	document.frm1.M_Moo.disabled = true;
	document.frm1.M_Building.disabled = true;
	document.frm1.M_Village.disabled = true;
	document.frm1.M_Lane.disabled = true;
	document.frm1.M_Road.disabled = true;
	document.frm1.M_District.disabled = true;
	document.frm1.M_State.disabled = true;
	document.frm1.M_Province.disabled = true;
	document.frm1.M_Postal_code.disabled = true;
	document.frm1.M_Country.disabled = true;
	document.frm1.M_phone.disabled = true;
	document.frm1.M_tor.disabled = true;
	document.frm1.M_Fax.disabled = true;
	document.frm1.M_Live_it.disabled = true;
	document.frm1.M_Completion.disabled = true;
	document.frm1.M_Acquired.disabled = true;
	document.frm1.M_purchase_price.disabled = true;
	
	document.frm1.hm1_f.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.hm2_f.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.hm3_f.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.hm4_f.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.hm5_f.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.hm_other.value = "ใช้ที่อยู่สำนักงานใหญ่";
	
	document.frm1.M_HomeNumber.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_room.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_LiveFloor.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Moo.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Building.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Village.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Lane.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Road.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_District.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_State.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Province.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Postal_code.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Country.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_phone.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_tor.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Fax.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Live_it.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Completion.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_Acquired.value = "ใช้ที่อยู่สำนักงานใหญ่";
	document.frm1.M_purchase_price.value = "ใช้ที่อยู่สำนักงานใหญ่";
}

function C_adds_new()
{
	document.frm1.hc1_f.disabled = false;
	document.frm1.hc2_f.disabled = false;
	document.frm1.hc3_f.disabled = false;
	document.frm1.hc4_f.disabled = false;
	document.frm1.hc5_f.disabled = false;
	document.frm1.hc_other.disabled = false;
	
	document.frm1.C_HomeNumber.disabled = false;
	document.frm1.C_room.disabled = false;
	document.frm1.C_LiveFloor.disabled = false;
	document.frm1.C_Moo.disabled = false;
	document.frm1.C_Building.disabled = false;
	document.frm1.C_Village.disabled = false;
	document.frm1.C_Lane.disabled = false;
	document.frm1.C_Road.disabled = false;
	document.frm1.C_District.disabled = false;
	document.frm1.C_State.disabled = false;
	document.frm1.C_Province.disabled = false;
	document.frm1.C_Postal_code.disabled = false;
	document.frm1.C_Country.disabled = false;
	document.frm1.C_phone.disabled = false;
	document.frm1.C_tor.disabled = false;
	document.frm1.C_Fax.disabled = false;
	document.frm1.C_Live_it.disabled = false;
	document.frm1.C_Completion.disabled = false;
	document.frm1.C_Acquired.disabled = false;
	document.frm1.C_purchase_price.disabled = false;
	
	document.frm1.hc1_f.value = "";
	document.frm1.hc2_f.value = "";
	document.frm1.hc3_f.value = "";
	document.frm1.hc4_f.value = "";
	document.frm1.hc5_f.value = "";
	document.frm1.hc_other.value = "";
	
	document.frm1.C_HomeNumber.value = "";
	document.frm1.C_room.value = "";
	document.frm1.C_LiveFloor.value = "";
	document.frm1.C_Moo.value = "";
	document.frm1.C_Building.value = "";
	document.frm1.C_Village.value = "";
	document.frm1.C_Lane.value = "";
	document.frm1.C_Road.value = "";
	document.frm1.C_District.value = "";
	document.frm1.C_State.value = "";
	document.frm1.C_Province.value = "00";
	document.frm1.C_Postal_code.value = "";
	document.frm1.C_Country.value = "";
	document.frm1.C_phone.value = "";
	document.frm1.C_tor.value = "";
	document.frm1.C_Fax.value = "";
	document.frm1.C_Live_it.value = "";
	document.frm1.C_Completion.value = "";
	document.frm1.C_Acquired.value = "";
	document.frm1.C_purchase_price.value = "";
}

function C_adds_off()
{
	document.frm1.hc1_f.disabled = true;
	document.frm1.hc2_f.disabled = true;
	document.frm1.hc3_f.disabled = true;
	document.frm1.hc4_f.disabled = true;
	document.frm1.hc5_f.disabled = true;
	document.frm1.hc_other.disabled = true;
	
	document.frm1.C_HomeNumber.disabled = true;
	document.frm1.C_room.disabled = true;
	document.frm1.C_LiveFloor.disabled = true;
	document.frm1.C_Moo.disabled = true;
	document.frm1.C_Building.disabled = true;
	document.frm1.C_Village.disabled = true;
	document.frm1.C_Lane.disabled = true;
	document.frm1.C_Road.disabled = true;
	document.frm1.C_District.disabled = true;
	document.frm1.C_State.disabled = true;
	document.frm1.C_Province.disabled = true;
	document.frm1.C_Postal_code.disabled = true;
	document.frm1.C_Country.disabled = true;
	document.frm1.C_phone.disabled = true;
	document.frm1.C_tor.disabled = true;
	document.frm1.C_Fax.disabled = true;
	document.frm1.C_Live_it.disabled = true;
	document.frm1.C_Completion.disabled = true;
	document.frm1.C_Acquired.disabled = true;
	document.frm1.C_purchase_price.disabled = true;
	
	document.frm1.hc1_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hc2_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hc3_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hc4_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hc5_f.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.hc_other.value = "ไม่เปิดเผยข้อมูล";
	
	document.frm1.C_HomeNumber.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_room.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_LiveFloor.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Moo.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Building.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Village.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Lane.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Road.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_District.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_State.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Province.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Postal_code.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Country.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_phone.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_tor.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Fax.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Live_it.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Completion.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_Acquired.value = "ไม่เปิดเผยข้อมูล";
	document.frm1.C_purchase_price.value = "ไม่เปิดเผยข้อมูล";
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
	if (!newWindow.opener) newWindow.opener = self;
}
</script>

<script type="text/javascript">
    function testlistbox()
	{  
        var datalist2 = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datalist2  
              url: "data_for_list2.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false  
        }).responseText;          
        $("select#IndustypeID").html(datalist2); // นำค่า datalist2 มาแสดงใน listbox ที่ชื่อ IndustypeID  
        // ชื่อตัวแปร และ element ต่างๆ สามารถเปลี่ยนไปตามการกำหนด
    }
</script>

	
<!---- หน้าต่าง Popup รูปภาพ ---->

	<!-- Add jQuery library -->
	
	<script type="text/javascript"  src="lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript"  src="source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.0.6" media="screen" />
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
	<link rel="stylesheet"  type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
	<script type="text/javascript"  src="source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>

	<script type="text/javascript">
		$(document).ready(function() {
		
			$('.fancyboxa').fancybox({
				minWidth: 450,
				maxWidth: 450
						
			});
			$('.fancyboxb').fancybox({	
				minWidth: 450,
				maxWidth: 450
			  });
			
			$(".pdforpic").fancybox({
			   minWidth: 500,
			   maxWidth: 800,
			   'height' : '600',
			   'autoScale' : true,
			   'transitionIn' : 'none',
			   'transitionOut' : 'none',
			   'type' : 'iframe'
			});

		});
	</script>
	<!-- จบหน้าต่าง Popup รูปภาพ -->	

</head>
<body>
<?php
//----- ข้อมูลนิติบุคคล

	// หาจำนวนที่แก้ไขครั้งล่าสุด
	/*$query_maxedit = pg_query("select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"Approved\" = 'false' ");
	while($res_maxedit = pg_fetch_array($query_maxedit))
	{
		$maxedit = $res_maxedit["maxedit"];
	}*/
	
$query_corp = pg_query("select * from public.\"th_corp\" where \"corpID\" = '$corpID' ");
while($result_corp = pg_fetch_array($query_corp))
{
	$corp_regis = $result_corp["corp_regis"]; // เลขทะเบียนนิติบุคคล
	$corpType = $result_corp["corpType"]; // ประเภทนิติบุคคล
	$corpName_THA = $result_corp["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
	$corpName_ENG = $result_corp["corpName_ENG"]; // ชื่อนิติบุคคลภาษาอังกฤษ
	$trade_name = $result_corp["trade_name"]; // ชื่อย่อ/เครื่องหมายทางการค้า
	$TaxNumber = $result_corp["TaxNumber"]; // เลขที่ประจำตัวผู้เสียภาษี
	$phone = $result_corp["phone"]; // โทรศัพท์
	$Fax = $result_corp["Fax"]; // Fax
	$mail = $result_corp["mail"];
	$website = $result_corp["website"];
	$date_of_corp = $result_corp["date_of_corp"]; // วันที่จดทะเบียนบริษัท
	$initial_capital = $result_corp["initial_capital"]; // ทุนจดทะเบียนเริ่มแรก
	$authority = $result_corp["authority"]; // ผู้มีอำนาจการทำรายการของบริษัท
	$current_capital = $result_corp["current_capital"]; // ทุนจดทะเบียนปัจจุบัน
	$asset_avg = $result_corp["asset_avg"]; // สินทรัพย์เฉลี่ย
	$revenue_avg = $result_corp["revenue_avg"]; // รายได้เฉลี่ย
	$debt_avg = $result_corp["debt_avg"]; // หนี้สินเฉลี่ย
	$net_profit = $result_corp["net_profit"]; // กำไรสุทธิ
	$date_of_last_data = $result_corp["date_of_last_data"]; // วันที่ของข้อมูลล่าสุด
	$trends_profit = $result_corp["trends_profit"]; // แนวโน้มกำไร
	$BusinessType = $result_corp["BusinessType"]; // ประเภทธุรกิจ
	$IndustypeID_fromtable = $result_corp["IndustypeID"]; // รหัสประเภทอุตสาหกรรม
	$explanation = $result_corp["explanation"]; // คำอธิบายกิจการ
	
	$Proportion_in_country = $result_corp["Proportion_in_country"]; // วันที่ของข้อมูลล่าสุด
	$Proportion_out_country = $result_corp["Proportion_out_country"]; // แนวโน้มกำไร
	$Proportion_Cash = $result_corp["Proportion_Cash"]; // ประเภทธุรกิจ
	$Proportion_Credit = $result_corp["Proportion_Credit"]; // รหัสประเภทอุตสาหกรรม
	$Amount_Employee = $result_corp["Amount_Employee"]; // คำอธิบายกิจการ
	
	$phone_split = split("#",$phone); // แบ่งเบอร์โทรศัพท์ออก
	$phone = $phone_split[0]; // เบอร์โทร
	$tor = $phone_split[1]; // ต่อ
	
	if($IndustypeID == 0)
	{
		$IndustypeName = "ไม่ระบุ";
	}
	else
	{
		$query_Industype = pg_query("select * from public.\"th_corp_industype\" where \"IndustypeID\" = '$IndustypeID' ");
		while($result_Industype = pg_fetch_array($query_Industype))
		{
			$IndustypeName = $result_Industype["IndustypeName"];
		}
	}
}

//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่ตามหนังสือรับรอง
$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corpID\" = '$corpID' and \"addsType\" = '1' ");
$row_adds_C = pg_num_rows($query_adds);
while($result_corp = pg_fetch_array($query_adds))
{
	$C_addsStyle = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
	$C_floor = $result_corp["floor"]; // จำนวนชั้น
	$C_HomeNumber = $result_corp["HomeNumber"]; // บ้านเลขที่
	$C_room = $result_corp["room"]; // หมายเลขห้อง
	$C_LiveFloor = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
	$C_Moo = $result_corp["Moo"]; // หมู่ที่
	$C_Building = $result_corp["Building"]; // อาคาร/สถานที่
	$C_Village = $result_corp["Village"]; // หมู่บ้าน
	$C_Lane = $result_corp["Lane"]; // ซอย
	$C_Road = $result_corp["Road"]; // ถนน
	$C_District = $result_corp["District"]; // แขวง/ตำบล
	$C_State = $result_corp["State"]; // เขต/อำเภอ
	$C_Province = $result_corp["ProvinceID"]; // จังหวัด
	$C_Postal_code = $result_corp["Postal_code"]; // รหัสไปรษณีย์
	$C_Country = $result_corp["Country"]; // ประเทศ
	$C_phone = $result_corp["phone"]; // โทรศัพท์
	$C_Fax = $result_corp["Fax"]; // โทรสาร
	$C_Live_it = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
	$C_Completion = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
	$C_Acquired = $result_corp["Acquired"]; // ได้มาโดย
	$C_purchase_price = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
	
	if($C_addsStyle == "บ้านเดี่ยว" || $C_addsStyle == "บ้านแฝด" || $C_addsStyle == "ทาวน์เฮ้าส์" || $C_addsStyle == "อาคารณิชย์" || $C_addsStyle == "คอนโด" || $C_addsStyle == "โรงงาน" || $C_addsStyle == "ที่พักชั่วคราว" || $C_addsStyle == "ที่ดินเปล่า")
	{
		$C_addsStyle_chk = "1";
	}
	
	$C_phone_split = split("#",$C_phone); // แบ่งเบอร์โทรศัพท์ออก
	$C_phone = $C_phone_split[0]; // เบอร์โทร
	$C_tor = $C_phone_split[1]; // ต่อ
}

//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่สำนักงานใหญ่
$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corpID\" = '$corpID' and \"addsType\" = '2' ");
$row_adds_H = pg_num_rows($query_adds);
while($result_corp = pg_fetch_array($query_adds))
{
	$H_addsStyle = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
	$H_floor = $result_corp["floor"]; // จำนวนชั้น
	$H_HomeNumber = $result_corp["HomeNumber"]; // บ้านเลขที่
	$H_room = $result_corp["room"]; // หมายเลขห้อง
	$H_LiveFloor = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
	$H_Moo = $result_corp["Moo"]; // หมู่ที่
	$H_Building = $result_corp["Building"]; // อาคาร/สถานที่
	$H_Village = $result_corp["Village"]; // หมู่บ้าน
	$H_Lane = $result_corp["Lane"]; // ซอย
	$H_Road = $result_corp["Road"]; // ถนน
	$H_District = $result_corp["District"]; // แขวง/ตำบล
	$H_State = $result_corp["State"]; // เขต/อำเภอ
	$H_Province = $result_corp["ProvinceID"]; // จังหวัด
	$H_Postal_code = $result_corp["Postal_code"]; // รหัสไปรษณีย์
	$H_Country = $result_corp["Country"]; // ประเทศ
	$H_phone = $result_corp["phone"]; // โทรศัพท์
	$H_Fax = $result_corp["Fax"]; // โทรสาร
	$H_Live_it = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
	$H_Completion = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
	$H_Acquired = $result_corp["Acquired"]; // ได้มาโดย
	$H_purchase_price = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
	
	if($H_addsStyle == "บ้านเดี่ยว" || $H_addsStyle == "บ้านแฝด" || $H_addsStyle == "ทาวน์เฮ้าส์" || $H_addsStyle == "อาคารณิชย์" || $H_addsStyle == "คอนโด" || $H_addsStyle == "โรงงาน" || $H_addsStyle == "ที่พักชั่วคราว" || $H_addsStyle == "ที่ดินเปล่า")
	{
		$H_addsStyle_chk = "1";
	}
	
	$H_phone_split = split("#",$H_phone); // แบ่งเบอร์โทรศัพท์ออก
	$H_phone = $H_phone_split[0]; // เบอร์โทร
	$H_tor = $H_phone_split[1]; // ต่อ
}

//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)
$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corpID\" = '$corpID' and \"addsType\" = '3' ");
$row_adds_M = pg_num_rows($query_adds);
while($result_corp = pg_fetch_array($query_adds))
{
	$M_addsStyle = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
	$M_floor = $result_corp["floor"]; // จำนวนชั้น
	$M_HomeNumber = $result_corp["HomeNumber"]; // บ้านเลขที่
	$M_room = $result_corp["room"]; // หมายเลขห้อง
	$M_LiveFloor = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
	$M_Moo = $result_corp["Moo"]; // หมู่ที่
	$M_Building = $result_corp["Building"]; // อาคาร/สถานที่
	$M_Village = $result_corp["Village"]; // หมู่บ้าน
	$M_Lane = $result_corp["Lane"]; // ซอย
	$M_Road = $result_corp["Road"]; // ถนน
	$M_District = $result_corp["District"]; // แขวง/ตำบล
	$M_State = $result_corp["State"]; // เขต/อำเภอ
	$M_Province = $result_corp["ProvinceID"]; // จังหวัด
	$M_Postal_code = $result_corp["Postal_code"]; // รหัสไปรษณีย์
	$M_Country = $result_corp["Country"]; // ประเทศ
	$M_phone = $result_corp["phone"]; // โทรศัพท์
	$M_Fax = $result_corp["Fax"]; // โทรสาร
	$M_Live_it = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
	$M_Completion = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
	$M_Acquired = $result_corp["Acquired"]; // ได้มาโดย
	$M_purchase_price = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
	
	if($M_addsStyle == "บ้านเดี่ยว" || $M_addsStyle == "บ้านแฝด" || $M_addsStyle == "ทาวน์เฮ้าส์" || $M_addsStyle == "อาคารณิชย์" || $M_addsStyle == "คอนโด" || $M_addsStyle == "โรงงาน" || $M_addsStyle == "ที่พักชั่วคราว" || $M_addsStyle == "ที่ดินเปล่า")
	{
		$M_addsStyle_chk = "1";
	}
	
	$M_phone_split = split("#",$M_phone); // แบ่งเบอร์โทรศัพท์ออก
	$M_phone = $H_phone_split[0]; // เบอร์โทร
	$M_tor = $M_phone_split[1]; // ต่อ
}
?>
<br>
<center>
<form name="frm1" method="post" action="process_edit_corpdata.php?corpID=<?php echo $corpID; ?>" enctype="multipart/form-data">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center">
			<fieldset><legend><B>เพิ่มลูกค้านิติบุคคล</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
					<tr>
						<td align="right">ชื่อนิติบุคคลภาษาไทย :</td><td><input type="text" name="corpName_THA" size="25" value="<?php echo $corpName_THA; ?>"><font color="#FF0000"><b> * </b></font></td>
						<td align="right">ชื่อนิติบุคคลภาษาอังกฤษ :</td><td><input type="text" name="corpName_ENG" size="25" value="<?php echo $corpName_ENG; ?>"></td>
					</tr>
					<tr>
						<td align="right">ชื่อย่อ/เครื่องหมายทางการค้า :</td><td><input type="text" name="trade_name" size="25" value="<?php echo $trade_name; ?>"></td>
						<td align="right">ประเภทนิติบุคคล :</td>
						<td>
							<select name="corpType">
								<option value="ไม่ระบุ" <?php if($corpType == "ไม่ระบุ"){echo "selected=\"selected\" ";} ?>><เลือกประเภทนิติบุคคล></option>
								<option value="บริษัทจำกัด" <?php if($corpType == "บริษัทจำกัด"){echo "selected=\"selected\" ";} ?>>บริษัทจำกัด</option>
								<option value="บริษัทมหาชนจำกัด" <?php if($corpType == "บริษัทมหาชนจำกัด"){echo "selected=\"selected\" ";} ?>>บริษัทมหาชนจำกัด</option>
								<option value="ห้างหุ้นส่วน" <?php if($corpType == "ห้างหุ้นส่วน"){echo "selected=\"selected\" ";} ?>>ห้างหุ้นส่วน</option>
								<option value="มูลนิธิ" <?php if($corpType == "มูลนิธิ"){echo "selected=\"selected\" ";} ?>>มูลนิธิ</option>
								<option value="สหกรณ์" <?php if($corpType == "สหกรณ์"){echo "selected=\"selected\" ";} ?>>สหกรณ์</option>
								<option value="ธนาคาร" <?php if($corpType == "ธนาคาร"){echo "selected=\"selected\" ";} ?>>ธนาคาร</option>
								<option value="วัด" <?php if($corpType == "วัด"){echo "selected=\"selected\" ";} ?>>วัด</option>
							</select><font color="#FF0000"><b> * </b></font>
						</td>
					</tr>
					<tr>
						<td align="right">เลขทะเบียนนิติบุคคล(13 หลัก) :</td><td><input type="text" name="corp_regis" size="25" value="<?php echo $corp_regis; ?>"><font color="#FF0000"><b> * </b></font></td>
						<td align="right">เลขที่ประจำตัวผู้เสียภาษี(10 หลัก) :</td><td><input type="text" name="TaxNumber" value="<?php echo $TaxNumber; ?>" size="25"></td>
					</tr>
					<tr>
						<td align="right">โทรศัพท์ :</td>
						<td>
							<input type="text" name="phone" value="<?php echo $phone; ?>" size="13"> ต่อ <input type="text" name="tor" value="<?php echo $tor; ?>" size="3"><font color="#FF0000"><b> * </b></font>
						</td>
						<td align="right">โทรสาร :</td><td><input type="text" name="Fax" value="<?php echo $Fax; ?>" size="25"></td>
					</tr>
					<tr>
						<td align="right">E-mail :</td><td><input type="text" name="mail" value="<?php echo $mail; ?>" size="25"></td>
						<td align="right">Website :</td><td><input type="text" name="website" value="<?php echo $website; ?>" size="25"></td>
					</tr>
					<tr>
						<td align="right">วันที่จดทะเบียนบริษัท :</td><td><input type="text" name="datepicker_regis" id="datepicker_regis" value="<?php echo $date_of_corp; ?>" style="text-align:center" size="15" readonly><font color="#FF0000"><b> * </b></font></td>
						<td align="right">ทุนจดทะเบียนเริ่มแรก :</td><td><input type="text" name="initial_capital" value="<?php if($initial_capital != ""){echo $initial_capital;} ?>" size="25"></td>
					</tr>
					<tr>
						<td valign="top" align="right">ผู้มีอำนาจการทำรายการของบริษัท :</td><td colspan="3"><textarea name="authority" cols="70" rows="2"><?php echo $authority; ?></textarea></td>
					</tr>
					<tr>
						<td align="right">วันที่ของข้อมูลล่าสุด :</td><td><input type="text" name="datepicker_last" id="datepicker_last" value="<?php echo $date_of_last_data; ?>" style="text-align:center" size="15" readonly></td>
						<td align="right">ทุนจดทะเบียนปัจจุบัน :</td><td><input type="text" name="current_capital" value="<?php if($current_capital != ""){echo $current_capital;} ?>" size="25"></td>
					</tr>
					<tr>
						<td align="right">สินทรัพย์เฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="asset_avg" value="<?php if($asset_avg != ""){echo $asset_avg;} ?>" size="25"></td>
						<td align="right">รายได้เฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="revenue_avg" value="<?php if($revenue_avg != ""){echo $revenue_avg;} ?>" size="25"></td>
					</tr>
					<tr>
						<td align="right">หนี้สินเฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="debt_avg" value="<?php if($debt_avg != ""){echo $debt_avg;} ?>" size="25"></td>
						<td align="right">กำไรสุทธิ(3 ปีล่าสุด) :</td><td><input type="text" name="net_profit" value="<?php if($net_profit != ""){echo $net_profit;} ?>" size="25"></td>
					</tr>
					<tr>
						<td align="right">แนวโน้มกำไร :</td>
						<td>
							<select name="trends_profit">
								<option value="ไม่ระบุ" <?php if($trends_profit == "ไม่ระบุ"){echo "selected=\"selected\" ";} ?>><เลือกแนวโน้มกำไร></option>
								<option value="เพิ่มขึ้น" <?php if($trends_profit == "เพิ่มขึ้น"){echo "selected=\"selected\" ";} ?>>เพิ่มขึ้น</option>
								<option value="คงที่" <?php if($trends_profit == "คงที่"){echo "selected=\"selected\" ";} ?>>คงที่</option>
								<option value="ลดลง" <?php if($trends_profit == "ลดลง"){echo "selected=\"selected\" ";} ?>>ลดลง</option>
							</select>
						</td>
						<td align="right">ประเภทธุรกิจ :</td>
						<td>
							<select name="BusinessType">
								<option value="ไม่ระบุ" <?php if($BusinessType == "ไม่ระบุ"){echo "selected=\"selected\" ";} ?>><เลือกประเภทธุรกิจ></option>
								<option value="ผลิต" <?php if($BusinessType == "ผลิต"){echo "selected=\"selected\" ";} ?>>ผลิต</option>
								<option value="ซื้อมาขายไป" <?php if($BusinessType == "ซื้อมาขายไป"){echo "selected=\"selected\" ";} ?>>ซื้อมาขายไป</option>
								<option value="บริการ" <?php if($BusinessType == "บริการ"){echo "selected=\"selected\" ";} ?>>บริการ</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right">ประเภทอุตสาหกรรม :</td>
						<td>
							<select name="IndustypeID" id="IndustypeID">
								<option value="0" <?php if($IndustypeID_fromtable == 0){echo "selected=\"selected\" ";} ?>><เลือกประเภทอุตสาหกรรม></option>
								<?php
								$qry_Industype = pg_query("select * from public.\"th_corp_industype\" order by \"IndustypeName\" ");
								while($res_Industype = pg_fetch_array($qry_Industype))
								{
									$IndustypeID = trim($res_Industype["IndustypeID"]);
									$IndustypeName = trim($res_Industype["IndustypeName"]);
								?>
									<option value="<?php echo $IndustypeID; ?>" <?php if($IndustypeID_fromtable == $IndustypeID){echo "selected=\"selected\" ";} ?>><?php echo $IndustypeName; ?></option>
								<?php
								}
								?>
							</select> <a onclick="javascript:popU('../manage_industry/frm_add.php?type=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')" style="cursor:pointer;"><font color="#0000FF"><u> เพิ่ม</u><font></a>
						</td>
						<td></td>
						<td>
							<input type="button" name="updatelistbox" id="updatelistbox" value="click" onclick="testlistbox()">
						</td>
					</tr>
					<tr>
						<td valign="top" align="right">คำอธิบายกิจการ :</td><td colspan="3"><textarea name="explanation" cols="70" rows="2"><?php echo $explanation; ?></textarea></td>
					</tr>
				</table>
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>กรรมการ</B></legend>
			<center>
			
				<input type="button" value="+ เพิ่ม" id="addBoard"> <input type="button" value="- ลบ" id="removeBoard">					
				<table id="tableBoard" align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
			
				<?php
				$query_board = pg_query("select * from public.\"th_corp_board\" where \"corpID\" = '$corpID' ");
				$row_board = pg_num_rows($query_board);
				
				if($row_board == 0)
				{
					$j_row_board = 1;
				?>
							<tr align="center" bgcolor="#E8E8E8">
								<td align="right" width="110">ชื่อกรรมการคนที่ 1 :</td>
								<td><input type="text" name="BoardName1" id="BoardName1" size="50"></td>
								<td>ตัวอย่างลายเซ็นต์:<input type="file" size="32" name="BoardSen1[]" value="" /></td>
							</tr>
				<?php
				}
				else
				{
					$j_row_board = 0;
					while($res_board = pg_fetch_array($query_board))
					{
						$j_row_board++;
						$CusID = $res_board["CusID"];
						$path_signature = $res_board["path_signature"];
						
						// หาว่ามีลูกค้าคนนี้ในระบบหรือไม่
						$query_searchCus = pg_query("select * from public.\"VSearchCus\" where \"CusID\" = '$CusID' ");
						$row_searchCus = pg_num_rows($query_searchCus);
						if($row_searchCus != 0)
						{
							while($res_searchCus = pg_fetch_array($query_searchCus))
							{
								$CusID = $res_searchCus["full_name"];
							}
						}
						
						?>
						<tr align="center" bgcolor="#E8E8E8">
							<td align="right" width="110">ชื่อกรรมการคนที่ <?php echo $j_row_board; ?> :</td>
							<td><input type="text" name="BoardName<?php echo $j_row_board; ?>" id="BoardName<?php echo $j_row_board; ?>" value="<?php echo $CusID; ?>" size="50"></td>
							<td>ตัวอย่างลายเซ็นต์:<input type="file" size="32" name="BoardSen<?php echo $j_row_board; ?>[]" value="" /></td>
							<?php
							if($path_signature != "")
							{
							?>
								<td><a class="fancyboxa" href="upload/<?php echo $path_signature; ?>" data-fancybox-group="gallery" title="<?php echo $CusID; ?>" style="color:#0000FF;"><b><u> แสดงลายเซ็นต์ </u></b></a></td>
							<?php
							}
							?>
						</tr>
						<?php
					}
				}
				?>
				</table>
				<div id="BoardGroup">
				<div id='BoardDiv'>
				</div>
				</div>
				<input type="hidden" name="rowBoard" id="rowBoard" value="<?php echo $j_row_board; ?>">
			</center>
			</fieldset>
			
			<fieldset><legend><B>ผู้ติดต่อ</B></legend>
			<center>
			
				<?php
				$query_communicant= pg_query("select * from public.\"th_corp_communicant_temp\" where \"corpID\" = '$corpID' ");
				$row_communicant = pg_num_rows($query_communicant);
				?>
				
				<input type="button" value="+ เพิ่ม" id="addCommunicant"> <input type="button" value="- ลบ" id="removeCommunicant">
					<table id="tableCommunicant" align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
						<tr align="center" bgcolor="#79BCFF">
							<th width="40">ลำดับที่</th>
							<th>ชื่อผู้ติดต่อ</th>
							<th>ตำแหน่ง</th>
							<th>ประสานงานเรื่อง</th>
							<th>เบอร์โทรศัพท์</th>
							<th>เบอร์มือถือ</th>
							<th>email</th>
						</tr>
						
						<?php
						if($row_communicant == 0)
						{
							$j_row_communicant = 1;
						?>
							<tr bgcolor="#E8E8E8">
								<td align="center">1</td>
								<td><input type="text" name="CommunicantName1" size="30"></td>
								<td><input type="text" name="CommunicantPosition1" size="25"></td>
								<td><input type="text" name="CommunicantCoordinate1" size="30"></td>
								<td><input type="text" name="CommunicantPhone1" size="15"></td>
								<td><input type="text" name="CommunicantMobile1" size="15"></td>
								<td><input type="text" name="CommunicantEmail1" size="20"></td>
							</tr>
						<?php
						}
						else
						{
							$j_row_communicant = 0;
							while($res_communicant = pg_fetch_array($query_communicant))
							{
								$j_row_communicant++;
								$CommunicantName = $res_communicant["CommunicantName"];
								$position = $res_communicant["position"];
								$subject = $res_communicant["subject"];
								$phone = $res_communicant["phone"];
								$mobile = $res_communicant["mobile"];
								$email = $res_communicant["email"];
								
								echo "<tr bgcolor=\"#E8E8E8\">";
								echo "<td align=\"center\">$j_row_communicant</td>";
								echo "<td><input type=\"text\" name=\"CommunicantName$j_row_communicant\" value=\"$CommunicantName\" size=\"30\"></td>";
								echo "<td><input type=\"text\" name=\"CommunicantPosition$j_row_communicant\" value=\"$position\" size=\"25\"></td>";
								echo "<td><input type=\"text\" name=\"CommunicantCoordinate$j_row_communicant\" value=\"$subject\" size=\"30\"></td>";
								echo "<td><input type=\"text\" name=\"CommunicantPhone$j_row_communicant\" value=\"$phone\" size=\"15\"></td>";
								echo "<td><input type=\"text\" name=\"CommunicantMobile$j_row_communicant\" value=\"$mobile\" size=\"15\"></td>";
								echo "<td><input type=\"text\" name=\"CommunicantEmail$j_row_communicant\" value=\"$email\" size=\"20\"></td>";
								echo "</tr>";
							}
						}
						?>
						
					</table>
				<div id="CommunicantGroup1">
				<div id='CommunicantDiv1'>
				</div>
				</div>
				<input type="hidden" name="rowCommunicant" id="rowCommunicant" value="1">
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>ผู้รับมอบอำนาจ</B></legend>
			<center>
				<input type="button" value="+ เพิ่ม" id="addAttorney"> <input type="button" value="- ลบ" id="removeAttorney">					
					<table id="tableAttorney" align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
					
					<?php
					$query_attorney = pg_query("select * from public.\"th_corp_attorney_temp\" where \"corpID\" = '$corpID'  ");
					$row_attorney = pg_num_rows($query_attorney);
					
					if($row_attorney == 0)
					{
						$j_row_attorney = 1;
					?>
						<tr align="center" bgcolor="#E8E8E8">
							<td align="right" width="130">ผู้รับมอบอำนาจคนที่ 1 :</td>
							<td><input type="text" name="AttorneyName1" id="AttorneyName1" size="50"></td>
							<td>ใบรับมอบอำนาจ:<input type="file" size="32" name="AttorneySen1[]" value="" /></td>
						</tr>
					<?php
					}
					else
					{
						$j_row_attorney = 0;
						while($res_attorney = pg_fetch_array($query_attorney))
						{
							$j_row_attorney++;
							$CusID = $res_attorney["CusID"];
							$path_receipt_authority = $res_attorney["path_signature"];
							
							// หาว่ามีลูกค้าคนนี้ในระบบหรือไม่
							$query_searchCus = pg_query("select * from public.\"VSearchCus\" where \"CusID\" = '$CusID' ");
							$row_searchCus = pg_num_rows($query_searchCus);
							if($row_searchCus != 0)
							{
								while($res_searchCus = pg_fetch_array($query_searchCus))
								{
									$CusID = $res_searchCus["full_name"];
								}
							}
							
							?>
							<tr align="center" bgcolor="#E8E8E8">
								<tr align="center" bgcolor="#E8E8E8">
									<td align="right" width="130">ผู้รับมอบอำนาจคนที่ <?php echo $j_row_attorney; ?> :</td>
									<td><input type="text" name="AttorneyName<?php echo $j_row_attorney; ?>" id="AttorneyName<?php echo $j_row_attorney; ?>" value="<?php echo $CusID; ?>" size="50"></td>
									<td>ใบรับมอบอำนาจ:<input type="file" size="32" name="AttorneySen<?php echo $j_row_attorney; ?>[]" value="" /></td>
									<?php
									if($path_receipt_authority != "")
									{
									?>
										<td><a class="fancyboxa" href="upload/<?php echo $path_receipt_authority; ?>" data-fancybox-group="gallery" title=" <?php echo $CusID; ?> " style="color:#0000FF;"><b><u> แสดงใบรับมอบอำนาจ </u></b></a></td>
									<?php
									}
									?>
								</tr>
							</tr>
							<?php
						}
					}
					?>
					</table>
				<div id="AttorneyGroup">
				<div id='AttorneyDiv'>
				</div>
				</div>
				<input type="hidden" name="rowAttorney" id="rowAttorney" value="<?php echo $j_row_attorney; ?>">
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>ผู้ถือหุ้น</B></legend>
			<center>
				<input type="button" value="+ เพิ่ม" id="addShare"> <input type="button" value="- ลบ" id="removeShare">					
					<table id="tableShare" align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
						<tr align="center" bgcolor="#79BCFF">
							<th width="40">ลำดับที่</th>
							<th>ชื่อผู้ถือหุ้น</th>
							<th>จำนวนหุ้น</th>
							<th>มูลค่าหุ้น</th>
							<th>มูลค่าหุ้นที่ถือ</th>
							<th>เปอร์เซ็นต์หุ้น</th>
							<th>ตัวอย่างลายเซ็นต์</th>
							<th></th>
						</tr>
						
					<?php
					$query_share = pg_query("select * from public.\"th_corp_share_temp\" where \"corpID\" = '$corpID'  ");
					$row_share = pg_num_rows($query_share);
					
					if($row_share == 0)
					{
						$j_row_share = 1;
					?>
						<tr bgcolor="#E8E8E8">
							<td align="center">1</td>
							<td><input type="text" name="ShareName1" id="ShareName1" size="30"></td>
							<td><input type="text" name="ShareAmount1" size="10"></td>
							<td><input type="text" name="ShareValue1" size="15"></td>
							<td><input type="text" name="ShareHeld1" size="18" readonly></td>
							<td><input type="text" name="SharePercent1" size="10" readonly></td>
							<td><input type="file" size="30" name="ShareSen1[]" value="" /></td>
						</tr>
					<?php
					}
					else
					{
						$j_row_share = 0;
						while($res_share = pg_fetch_array($query_share))
						{
							$j_row_share++;
							$CusID = $res_share["CusID"];
							$share_amount = $res_share["share_amount"];
							$share_value = $res_share["share_value"];
							$path_signature = $res_share["path_signature"];
							
							// หาว่ามีลูกค้าคนนี้ในระบบหรือไม่
							$query_searchCus = pg_query("select * from public.\"VSearchCus\" where \"CusID\" = '$CusID' ");
							$row_searchCus = pg_num_rows($query_searchCus);
							if($row_searchCus != 0)
							{
								while($res_searchCus = pg_fetch_array($query_searchCus))
								{
									$CusID = $res_searchCus["full_name"];
								}
							}
							
							// หามูลค่าหุ้น
							if($share_amount != "" and $share_value != "")
							{
								$share_held = $share_amount * $share_value;
								if($current_capital != "")
								{
									$share_percent = ($share_held/$current_capital)*100;
								}
								else
								{
									$share_percent = "";
								}
							}
							else
							{
								$share_held = "";
								$$share_percent == "";
							}
							
							echo "<tr bgcolor=\"#E8E8E8\">";
							echo "<td align=\"center\">$j_row_share</td>";
							echo "<td><input type=\"text\" name=\"ShareName$j_row_share\" id=\"ShareName$j_row_share\" value=\"$CusID\" size=\"30\"></td>";
							echo "<td><input type=\"text\" name=\"ShareAmount$j_row_share\" value=\"$share_amount\" size=\"10\" style=\"text-align:right;\"></td>";
							echo "<td><input type=\"text\" name=\"ShareValue$j_row_share\" value=\"$share_value\" size=\"15\" style=\"text-align:right;\"></td>";
							?>
							<td><input type="text" name="ShareHeld<?php echo $j_row_share; ?>" value="<?php if($share_held != ""){echo number_format($share_held,2);} ?>" size="18" style="text-align:right;" readonly></td>
							<td><input type="text" name="SharePercent<?php echo $j_row_share; ?>" value="<?php if($share_percent != ""){echo number_format($share_percent,2)."%";} ?>" size="10" style="text-align:right;" readonly></td>
							<td><input type="file" size="30" name="ShareSen<?php echo $j_row_share; ?>[]" value="" /></td>
							<?php
							if($path_signature != "")
							{
							?>
								<td align="center"><a class="fancyboxa" href="upload/<?php echo $path_signature; ?>" data-fancybox-group="gallery" title=" <?php echo $CusID; ?>" style="color:#0000FF;"><b><u> แสดง<br>ลายเซ็นต์ </u></b></a></td>
							<?php
							}
							?>
							</tr>
							<?php
						}
					}
					?>
					</table>
				<div id="ShareGroup">
				<div id='ShareDiv'>
				</div>
				</div>
				<input type="hidden" name="rowShare" id="rowShare" value="1">
			</center>
			</fieldset>
			
			<br>
			
			
			<fieldset><legend><B>ที่อยู่ตามหนังสือรับรอง</B></legend>
			<center>
			
				<input type="radio" name="selete_adds_main" id="selete_adds_main1" value="main1" <?php if($row_adds_C == 1){echo "checked=\"checked\" ";} ?> onclick="C_adds_new()"> ข้อมูลใหม่
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_main" id="selete_adds_main2" value="main2" <?php if($row_adds_C == 0){echo "checked=\"checked\" ";} ?> onclick="C_adds_off()"> ไม่เปิดเผยข้อมูล
				
				<fieldset><legend><font color="#000000">ลักษณะของที่อยู่</font></legend>
				<center>
					<table>
						<tr>
							<td><input type="radio" name="homestyle_certificate" value="บ้านเดี่ยว" <?php if($C_addsStyle == "บ้านเดี่ยว"){echo "checked=\"checked\" ";} ?>> บ้านเดี่ยว</td><td width="100"><input type="text" name="hc1_f" value="<?php if($C_addsStyle == "บ้านเดี่ยว"){echo $C_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_certificate" value="บ้านแฝด" <?php if($C_addsStyle == "บ้านแฝด"){echo "checked=\"checked\" ";} ?>> บ้านแฝด</td><td width="100"><input type="text" name="hc2_f" value="<?php if($C_addsStyle == "บ้านแฝด"){echo $C_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_certificate" value="ทาวน์เฮ้าส์" <?php if($C_addsStyle == "ทาวน์เฮ้าส์"){echo "checked=\"checked\" ";} ?>> ทาวน์เฮ้าส์</td><td><input type="text" name="hc3_f" value="<?php if($C_addsStyle == "ทาวน์เฮ้าส์"){echo $C_floor;} ?>" size="2"> ชั้น</td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_certificate" value="อาคารณิชย์" <?php if($C_addsStyle == "อาคารณิชย์"){echo "checked=\"checked\" ";} ?>> อาคารณิชย์</td><td width="100"><input type="text" name="hc4_f" value="<?php if($C_addsStyle == "อาคารณิชย์"){echo $C_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_certificate" value="คอนโด" <?php if($C_addsStyle == "คอนโด"){echo "checked=\"checked\" ";} ?>> คอนโด</td><td width="100"><input type="text" name="hc5_f" value="<?php if($C_addsStyle == "คอนโด"){echo $C_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_certificate" value="โรงงาน" <?php if($C_addsStyle == "โรงงาน"){echo "checked=\"checked\" ";} ?>> โรงงาน</td><td></td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_certificate" value="ที่พักชั่วคราว" <?php if($C_addsStyle == "ที่พักชั่วคราว"){echo "checked=\"checked\" ";} ?>> ที่พักชั่วคราว</td><td width="100"></td>
							<td><input type="radio" name="homestyle_certificate" value="ที่ดินเปล่า" <?php if($C_addsStyle == "ที่ดินเปล่า"){echo "checked=\"checked\" ";} ?>> ที่ดินเปล่า</td><td width="100"></td>
							<td><input type="radio" name="homestyle_certificate" value="อื่นๆ" <?php if($C_addsStyle_chk != "1"){echo "checked=\"checked\" ";} ?>> อื่นๆ</td><td><input type="text" name="hc_other" value="<?php if($C_addsStyle_chk != "1"){echo $C_addsStyle;} ?>" size="20"></td>
						</tr>
					</table>
				</center>
				</fieldset>
				
				<fieldset><legend><font color="#000000">รายละเอียด</font></legend>
				<center>
					<table>
						<tr>
							<td align="right">บ้านเลขที่ :</td><td><input type="text" name="C_HomeNumber" value="<?php echo $C_HomeNumber; ?>" size="25"><font color="#FF0000" name="RE_HC1"><b> * </b></font></td>
							<td width="50"></td>
							<td align="right">ห้อง :</td><td><input type="text" name="C_room" value="<?php echo $C_room; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">ชั้น :</td><td><input type="text" name="C_LiveFloor" value="<?php echo $C_LiveFloor; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="C_Moo" value="<?php echo $C_Moo; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="C_Building" value="<?php echo $C_Building; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="C_Village" value="<?php echo $C_Village; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="C_Lane" value="<?php echo $C_Lane; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">ถนน :</td><td><input type="text" name="C_Road" value="<?php echo $C_Road; ?>" size="25"><font color="#FF0000" name="RE_HC2"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="C_District" value="<?php echo $C_District; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="C_State" value="<?php echo $C_State; ?>" size="25"><font color="#FF0000" name="RE_HC3"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">จังหวัด :</td>
							<td>
								<select name="C_Province">
									<option value="00"><เลือกจังหวัด></option>
									<?php
									$query_province=pg_query("select * from public.\"nw_province\" order by \"proName\"");
									while($res_pro = pg_fetch_array($query_province)){
									?>
										<option value="<?php echo $res_pro["proID"];?>" <?php if($res_pro["proID"] == $C_Province){echo "selected=\"selected\"";} ?>><?php echo $res_pro["proName"];?></option>
									<?php
									}
									?>
								</select>
								<font color="#FF0000" name="RE_HC4"><b> * </b></font>
							</td>
							<td width="50"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="C_Postal_code" value="<?php echo $C_Postal_code; ?>" size="25"><font color="#FF0000" name="RE_HC5"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">ประเทศ :</td><td><input type="text" name="C_Country" value="<?php echo $C_Country; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="C_phone" value="<?php echo $C_phone; ?>" size="13"> ต่อ <input type="text" name="C_tor" value="<?php echo $C_tor; ?>" size="3"></td>
						</tr>
						<tr>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="C_Fax" value="<?php echo $C_Fax; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="C_Live_it" value="<?php echo $C_Live_it; ?>" size="23"> ปี</td>
						</tr>
						<tr>
							<td align="right">ปีที่สร้างเสร็จ ( ค.ศ. ) :</td><td><input type="text" name="C_Completion" value="<?php echo $C_Completion; ?>" size="10"> <font color="#777777">Ex:2012</font></td>
							<td width="50"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="C_Acquired" value="<?php echo $C_Acquired; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="C_purchase_price" value="<?php echo $C_purchase_price; ?>" size="20"> บาท</td>
							<td width="50"></td>
							<td></td><td></td>
						</tr>
					</table>
				</center>
				</fieldset>
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>ที่อยู่สำนักงานใหญ่</B></legend>
			<center>
			
				<input type="radio" name="selete_adds_one" id="selete_adds_one1" value="one1" <?php if($row_adds_H == 1){echo "checked=\"checked\" ";} ?> onclick="H_adds_new()"> ข้อมูลใหม่
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_one" id="selete_adds_one2" value="one2" onclick="H_adds_C()"> ใช้ที่อยู่ตามหนังสือรับรอง
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_one" id="selete_adds_one3" value="one3" <?php if($row_adds_H == 0){echo "checked=\"checked\" ";} ?> onclick="H_adds_off()"> ไม่เปิดเผยข้อมูล
				
				<fieldset><legend><font color="#000000">ลักษณะของที่อยู่</font></legend>
				<center>
					<table>
						<tr>
							<td><input type="radio" name="homestyle_headquarters" value="บ้านเดี่ยว" <?php if($H_addsStyle == "บ้านเดี่ยว"){echo "checked=\"checked\" ";} ?>> บ้านเดี่ยว</td><td width="100"><input type="text" name="hh1_f" value="<?php if($H_addsStyle == "บ้านเดี่ยว"){echo $H_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_headquarters" value="บ้านแฝด" <?php if($H_addsStyle == "บ้านแฝด"){echo "checked=\"checked\" ";} ?>> บ้านแฝด</td><td width="100"><input type="text" name="hh2_f" value="<?php if($H_addsStyle == "บ้านแฝด"){echo $H_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_headquarters" value="ทาวน์เฮ้าส์" <?php if($H_addsStyle == "ทาวน์เฮ้าส์"){echo "checked=\"checked\" ";} ?>> ทาวน์เฮ้าส์</td><td><input type="text" name="hh3_f" value="<?php if($H_addsStyle == "ทาวน์เฮ้าส์"){echo $H_floor;} ?>" size="2"> ชั้น</td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_headquarters" value="อาคารณิชย์" <?php if($H_addsStyle == "อาคารณิชย์"){echo "checked=\"checked\" ";} ?>> อาคารณิชย์</td><td width="100"><input type="text" name="hh4_f" value="<?php if($H_addsStyle == "อาคารณิชย์"){echo $H_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_headquarters" value="คอนโด" <?php if($H_addsStyle == "คอนโด"){echo "checked=\"checked\" ";} ?>> คอนโด</td><td width="100"><input type="text" name="hh5_f" value="<?php if($H_addsStyle == "คอนโด"){echo $H_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_headquarters" value="โรงงาน" <?php if($H_addsStyle == "โรงงาน"){echo "checked=\"checked\" ";} ?>> โรงงาน</td><td></td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_headquarters" value="ที่พักชั่วคราว" <?php if($H_addsStyle == "ที่พักชั่วคราว"){echo "checked=\"checked\" ";} ?>> ที่พักชั่วคราว</td><td width="100"></td>
							<td><input type="radio" name="homestyle_headquarters" value="ที่ดินเปล่า" <?php if($H_addsStyle == "ที่ดินเปล่า"){echo "checked=\"checked\" ";} ?>> ที่ดินเปล่า</td><td width="100"></td>
							<td><input type="radio" name="homestyle_headquarters" value="อื่นๆ" <?php if($H_addsStyle_chk != "1"){echo "checked=\"checked\" ";} ?>> อื่นๆ</td><td><input type="text" name="hh_other" value="<?php if($H_addsStyle_chk != "1"){echo $H_addsStyle;} ?>" size="20"></td>
						</tr>
					</table>
				</center>
				</fieldset>
				
				<fieldset><legend><font color="#000000">รายละเอียด</font></legend>
				<center>
					<table>
						<tr>
							<td align="right">บ้านเลขที่ :</td><td><input type="text" name="H_HomeNumber" value="<?php echo $H_HomeNumber; ?>" size="25"><font color="#FF0000" name="RE_HH1"><b> * </b></font></td>
							<td width="50"></td>
							<td align="right">ห้อง :</td><td><input type="text" name="H_room" value="<?php echo $H_room; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">ชั้น :</td><td><input type="text" name="H_LiveFloor" value="<?php echo $H_LiveFloor; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="H_Moo" value="<?php echo $H_Moo; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="H_Building" value="<?php echo $H_Building; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="H_Village" value="<?php echo $H_Village; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="H_Lane" value="<?php echo $H_Lane; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">ถนน :</td><td><input type="text" name="H_Road" value="<?php echo $H_Road; ?>" size="25"><font color="#FF0000" name="RE_HH2"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="H_District" value="<?php echo $H_District; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="H_State" value="<?php echo $H_State; ?>" size="25"><font color="#FF0000" name="RE_HH3"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">จังหวัด :</td>
							<td>
								<select name="H_Province">
									<option value="00"><เลือกจังหวัด></option>
									<?php
									$query_province=pg_query("select * from public.\"nw_province\" order by \"proName\"");
									while($res_pro = pg_fetch_array($query_province)){
									?>
										<option value="<?php echo $res_pro["proID"];?>" <?php if($res_pro["proID"] == $H_Province){echo "selected=\"selected\"";} ?>><?php echo $res_pro["proName"];?></option>
									<?php
									}
									?>
								</select>
								<font color="#FF0000" name="RE_HH4"><b> * </b></font>
							</td>
							<td width="50"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="H_Postal_code" value="<?php echo $H_Postal_code; ?>" size="25"><font color="#FF0000" name="RE_HH5"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">ประเทศ:</td><td><input type="text" name="H_Country" value="<?php echo $H_Country; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="H_phone" value="<?php echo $H_phone; ?>" size="13"> ต่อ <input type="text" name="H_tor" value="<?php echo $H_tor; ?>" size="3"></td>
						</tr>
						<tr>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="H_Fax" value="<?php echo $H_Fax; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="H_Live_it" value="<?php echo $H_Live_it; ?>" size="23"> ปี</td>
						</tr>
						<tr>
							<td align="right">ปีที่สร้างเสร็จ ( ค.ศ. ) :</td><td><input type="text" name="H_Completion" value="<?php echo $H_Completion; ?>" size="10"> <font color="#777777">Ex:2012</font></td>
							<td width="50"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="H_Acquired" value="<?php echo $H_Acquired; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="H_purchase_price" value="<?php echo $H_purchase_price; ?>" size="20"> บาท</td>
							<td width="50"></td>
							<td></td><td></td>
						</tr>
					</table>
				</center>
				</fieldset>
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)</B></legend>
			<center>
			
				<input type="radio" name="selete_adds_two" id="selete_adds_two1" value="two1" <?php if($row_adds_M == 1){echo "checked=\"checked\" ";} ?> onclick="M_adds_new()"> ข้อมูลใหม่
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_two" id="selete_adds_two2" value="two2" onclick="M_adds_C()"> ใช้ที่อยู่ตามหนังสือรับรอง
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_two" id="selete_adds_two3" value="two3" onclick="M_adds_H()"> ใช้ที่อยู่สำนักงานใหญ่
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_two" id="selete_adds_two4" value="two4" <?php if($row_adds_M == 0){echo "checked=\"checked\" ";} ?> onclick="M_adds_off()"> ไม่เปิดเผยข้อมูล
				
				<fieldset><legend><font color="#000000">ลักษณะของที่อยู่</font></legend>
				<center>
					<table>
						<tr>
							<td><input type="radio" name="homestyle_mailing" value="บ้านเดี่ยว" <?php if($M_addsStyle == "บ้านเดี่ยว"){echo "checked=\"checked\" ";} ?>> บ้านเดี่ยว</td><td width="100"><input type="text" name="hm1_f" value="<?php if($M_addsStyle == "บ้านเดี่ยว"){echo $M_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_mailing" value="บ้านแฝด" <?php if($M_addsStyle == "บ้านแฝด"){echo "checked=\"checked\" ";} ?>> บ้านแฝด</td><td width="100"><input type="text" name="hm2_f" value="<?php if($M_addsStyle == "บ้านแฝด"){echo $M_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_mailing" value="ทาวน์เฮ้าส์" <?php if($M_addsStyle == "ทาวน์เฮ้าส์"){echo "checked=\"checked\" ";} ?>> ทาวน์เฮ้าส์</td><td><input type="text" name="hm3_f" value="<?php if($M_addsStyle == "ทาวน์เฮ้าส์"){echo $M_floor;} ?>" size="2"> ชั้น</td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_mailing" value="อาคารณิชย์" <?php if($M_addsStyle == "อาคารณิชย์"){echo "checked=\"checked\" ";} ?>> อาคารณิชย์</td><td width="100"><input type="text" name="hm4_f" value="<?php if($M_addsStyle == "อาคารณิชย์"){echo $M_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_mailing" value="คอนโด" <?php if($M_addsStyle == "คอนโด"){echo "checked=\"checked\" ";} ?>> คอนโด</td><td width="100"><input type="text" name="hm5_f" value="<?php if($M_addsStyle == "คอนโด"){echo $M_floor;} ?>" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_mailing" value="โรงงาน" <?php if($M_addsStyle == "โรงงาน"){echo "checked=\"checked\" ";} ?>> โรงงาน</td><td></td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_mailing" value="ที่พักชั่วคราว" <?php if($M_addsStyle == "ที่พักชั่วคราว"){echo "checked=\"checked\" ";} ?>> ที่พักชั่วคราว</td><td width="100"></td>
							<td><input type="radio" name="homestyle_mailing" value="ที่ดินเปล่า" <?php if($M_addsStyle == "ที่ดินเปล่า"){echo "checked=\"checked\" ";} ?>> ที่ดินเปล่า</td><td width="100"></td>
							<td><input type="radio" name="homestyle_mailing" value="อื่นๆ" <?php if($M_addsStyle_chk != "1"){echo "checked=\"checked\" ";} ?>> อื่นๆ</td><td><input type="text" name="hm_other" value="<?php if($M_addsStyle_chk != "1"){echo $M_addsStyle;} ?>" size="20"></td>
						</tr>
					</table>
				</center>
				</fieldset>
				
				<fieldset><legend><font color="#000000">รายละเอียด</font></legend>
				<center>
					<table>
						<tr>
							<td align="right">บ้านเลขที่ :</td><td><input type="text" name="M_HomeNumber" value="<?php echo $M_HomeNumber; ?>" size="25"><font color="#FF0000" name="RE_HM1"><b> * </b></font></td>
							<td width="50"></td>
							<td align="right">ห้อง :</td><td><input type="text" name="M_room" value="<?php echo $M_room; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">ชั้น :</td><td><input type="text" name="M_LiveFloor" value="<?php echo $M_LiveFloor; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="M_Moo" value="<?php echo $M_Moo; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="M_Building" value="<?php echo $M_Building; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="M_Village" value="<?php echo $M_Village; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="M_Lane" value="<?php echo $M_Lane; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">ถนน :</td><td><input type="text" name="M_Road" value="<?php echo $M_Road; ?>" size="25"><font color="#FF0000" name="RE_HM2"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="M_District" value="<?php echo $M_District; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="M_State" value="<?php echo $M_State; ?>" size="25"><font color="#FF0000" name="RE_HM3"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">จังหวัด :</td>
							<td>
								<select name="M_Province">
									<option value="00"><เลือกจังหวัด></option>
									<?php
									$query_province=pg_query("select * from public.\"nw_province\" order by \"proName\"");
									while($res_pro = pg_fetch_array($query_province)){
									?>
										<option value="<?php echo $res_pro["proID"];?>" <?php if($res_pro["proID"] == $M_Province){echo "selected=\"selected\"";} ?>><?php echo $res_pro["proName"];?></option>
									<?php
									}
									?>
								</select>
								<font color="#FF0000" name="RE_HM4"><b> * </b></font>
							</td>
							<td width="50"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="M_Postal_code" value="<?php echo $M_Postal_code; ?>" size="25"><font color="#FF0000" name="RE_HM5"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">ประเทศ:</td><td><input type="text" name="M_Country" value="<?php echo $M_Country; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="M_phone" value="<?php echo $M_phone; ?>" size="13"> ต่อ <input type="text" name="M_tor" value="<?php echo $M_tor; ?>" size="3"></td>
						</tr>
						<tr>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="M_Fax" value="<?php echo $M_Fax; ?>" size="25"></td>
							<td width="50"></td>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="M_Live_it" value="<?php echo $M_Live_it; ?>" size="23"> ปี</td>
						</tr>
						<tr>
							<td align="right">ปีที่สร้างเสร็จ ( ค.ศ. ) :</td><td><input type="text" name="M_Completion" value="<?php echo $M_Completion; ?>" size="10"> <font color="#777777">Ex:2012</font></td>
							<td width="50"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="M_Acquired" value="<?php echo $M_Acquired; ?>" size="25"></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="M_purchase_price" value="<?php echo $M_purchase_price; ?>" size="20"> บาท</td>
							<td width="50"></td>
							<td></td><td></td>
						</tr>
					</table>
				</center>
				</fieldset>
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>บัญชีธนาคารของลูกค้านิติบุคคล</B></legend>
			<center>
				
				<?php
					$query_bank = pg_query("select * from public.\"th_corp_acc\" where \"corpID\" = '$corpID' ");
					$row_bank = pg_num_rows($query_bank);
				?>
				
				<!--<div id='TextBoxesGroup1'>-->
				
				<input type="button" value="+ เพิ่ม" id="addButton"> <input type="button" value="- ลบ" id="removeButton">
				
				<!--<div id="TextBoxDiv1">-->
					
					<table id="tablebank" align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
						<tr align="center" bgcolor="#79BCFF">
							<th width="40">ลำดับที่</th>
							<th>เลขที่บัญชี</th>
							<th>ชื่อบัญชี</th>
							<th>ธนาคาร</th>
							<th>สาขา</th>
							<th>ประเภทบัญชี</th>
						</tr>
					<?php
					if($row_bank == 0)
					{
						$i = 1;
					?>
						<tr bgcolor="#E8E8E8">
							<td align="center">1</td>
							<td><input type="text" name="acc_Number1" size="25"></td>
							<td><input type="text" name="acc_Name1" size="25"></td>
							<td>
								<select name="bank1">
									<option value="ไม่ระบุ">-เลือกธนาคาร-</option>
									<?php
									$qry_no = pg_query("select * from public.\"BankProfile\" order by \"sort\" ");
									while($res_no=pg_fetch_array($qry_no))
									{
										$bankID = trim($res_no["bankID"]);
										$bankName = trim($res_no["bankName"]);
									?>
										<option value="<?php echo $bankID; ?>"><?php echo $bankName; ?></option>
									<?php
									}
									?>
								</select>
							</td>
							<td><input type="text" name="branch1" size="25"></td>
							<td>
								<select name="acc_type1">
									<option value="ไม่ระบุ">-เลือกประเภทบัญชี-</option>
									<option value="ออมทรัพย์">ออมทรัพย์</option>
									<option value="กระแสรายวัน">กระแสรายวัน</option>
									<option value="ประจำ">ประจำ</option>
								</select>
							</td>
						</tr>
					<?php
					}
					else
					{
						$i=0;
						while($result = pg_fetch_array($query_bank))
						{
							$i++;
							$acc_Number = $result["acc_Number"]; // เลขที่บัญชี
							$bankID_main = $result["bankID"]; // รหัสธนาคาร
							$acc_Name = $result["acc_Name"]; // ชื่อบัญชี
							$branch = $result["branch"]; // สาขา
							$acc_type = $result["acc_type"]; // ประเภทบัญชี
					?>
						<tr id="rowdatabank<?php echo $i; ?>" bgcolor="#E8E8E8">
							<td align="center"><?php echo $i; ?></td>
							<td><input type="text" name="acc_Number<?php echo $i; ?>" value="<?php echo $acc_Number; ?>" size="25"></td>
							<td><input type="text" name="acc_Name<?php echo $i; ?>" value="<?php echo $acc_Name; ?>" size="25"></td>
							<td>
								<select name="bank<?php echo $i; ?>">
									<option value="ไม่ระบุ">-เลือกธนาคาร-</option>
									<?php
									$qry_no = pg_query("select * from public.\"BankProfile\" order by \"sort\" ");
									while($res_no=pg_fetch_array($qry_no))
									{
										$bankID = trim($res_no["bankID"]);
										$bankName = trim($res_no["bankName"]);
									?>
										<option value="<?php echo $bankID; ?>" <?php if($bankID_main == $bankID){echo "selected=\"selected\"";} ?>><?php echo $bankName; ?></option>
									<?php
									}
									?>
								</select>
							</td>
							<td><input type="text" name="branch<?php echo $i; ?>" value="<?php echo $branch; ?>" size="25"></td>
							<td>
								<select name="acc_type<?php echo $i; ?>">
									<option value="ไม่ระบุ" <?php if($acc_type == "ไม่ระบุ"){echo "selected=\"selected\"";} ?>>-เลือกประเภทบัญชี-</option>
									<option value="ออมทรัพย์" <?php if($acc_type == "ออมทรัพย์"){echo "selected=\"selected\"";} ?>>ออมทรัพย์</option>
									<option value="กระแสรายวัน" <?php if($acc_type == "กระแสรายวัน"){echo "selected=\"selected\"";} ?>>กระแสรายวัน</option>
									<option value="ประจำ" <?php if($acc_type == "ประจำ"){echo "selected=\"selected\"";} ?>>ประจำ</option>
								</select>
							</td>
						</tr>
					<?php
						}
					}
					?>
					</table>
				<div id="TextBoxesGroup1">
				<div id='TextBoxDiv1'>
				</div>
				</div>
			</center>
			</fieldset>
			<br>
			<fieldset><legend><B>ข้อมูลอื่นๆ</B></legend>
			<center>
				<div id="panel">
				<?php
					include("corp_other.php");
				?>
				</div>
			</center>
			</fieldset>
			
			<br><br>
			<input type="hidden" name="rowbank" id="rowbank" value="<?php echo $i; ?>">
			<input type="submit" value="ยืนยันการแก้ไข" onclick="return validate();"> &nbsp;&nbsp;&nbsp; <input type="button" value="ยกเลิก/ปิด" onclick="javascript:window.close();">
			<br>
		</td>
	</tr>
</table>
</form>
</center>
</body>

<?php
if($row_adds_C == 0) // ถ้าไม่เปิดเผยข้อมูล ที่อยู่ตามหนังสือรับรอง
{
	echo "<script type=\"text/javascript\"> C_adds_off(); </script> ";
}

if($row_adds_H == 0) // ถ้าไม่เปิดเผยข้อมูล ที่อยู่สำนักงานใหญ่
{
	echo "<script type=\"text/javascript\"> H_adds_off(); </script> ";
}

if($row_adds_M == 0) // ถ้าไม่เปิดเผยข้อมูล ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)
{
	echo "<script type=\"text/javascript\"> M_adds_off(); </script> ";
}
?>

<script type="text/javascript">
//var counter = 1;
var counter = <?php echo $i; ?>;

var nubBoard = <?php echo $j_row_board; ?>;
var nubShare = <?php echo $j_row_share; ?>;
var nubCommunicant = <?php echo $j_row_communicant; ?>;
var nubAttorney = <?php echo $j_row_attorney; ?>;

$(document).ready(function(){
	$('#addButton').click(function()
	{
		counter++;
		if(counter <= <?php echo $i; ?>)
		{
			//if(counter == 1)
			if(counter == 1)
			{
				document.getElementById("tablebank").style.visibility = 'visible';
				document.getElementById("rowbank").value = counter;
			}
			else
			{
				document.getElementById("rowdatabank"+counter).style.visibility = 'visible';
				document.getElementById("rowbank").value = counter;
			}
		}
		//else if(counter > 1)
		else if(counter > <?php echo $i; ?>)
		{
			console.log(counter);
			var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
			table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
			+ '	<tr bgcolor="#E8E8E8">'
			+ '		<td align="center" width="40">'+ counter +'</td>'
			+ '		<td>'
			+ '			<input type="text" name="acc_Number'+ counter +'" size="25" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="acc_Name'+ counter +'" size="25" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<select name="bank'+ counter +'"><option value="ไม่ระบุ">-เลือกธนาคาร-</option>'
						<?php
						$qry_no = pg_query("select * from public.\"BankProfile\" order by \"sort\" ");
						while($res_no=pg_fetch_array($qry_no))
						{
							$bankID = trim($res_no["bankID"]);
							$bankName = trim($res_no["bankName"]);
						?>
			+ '				<option value="<?php echo $bankID; ?>"><?php echo $bankName; ?></option>'
						<?php
						}
						?>
			+ '			</select>'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="branch'+ counter +'" size="25" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<select name="acc_type'+ counter +'"><option value="ไม่ระบุ">-เลือกประเภทบัญชี-</option><option value="ออมทรัพย์">ออมทรัพย์</option><option value="กระแสรายวัน">กระแสรายวัน</option><option value="ประจำ">ประจำ</option></select>'
			+ '		</td>'
			+ '	</tr>'
			+ '	</table>'
			
			newTextBoxDiv.html(table);

			newTextBoxDiv.appendTo("#TextBoxesGroup1");
				
			document.getElementById("rowbank").value = counter;
		}
    }
	);

	$("#removeButton").click(function(){
		//if(counter==1){
		if(counter<=<?php echo $i; ?>){
			if(counter==1){
				document.getElementById("tablebank").style.visibility = 'hidden';
				/*document.frm1.acc_Number1.value = "";
				document.frm1.acc_Name1.value = "";
				document.frm1.branch1.value = "";*/
			}
			else{
				document.getElementById("rowdatabank"+counter).style.visibility = 'hidden';
				/*document.frm1.acc_Number1.value = "";
				document.frm1.acc_Name1.value = "";
				document.frm1.branch1.value = "";*/
			}
        }
        if(counter==0){
            //alert("ห้ามลบ !!!");
			document.getElementById("rowbank").value = counter;
            return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
		document.getElementById("rowbank").value = counter;
        console.log(counter);
        updateSummary();
		
		//document.getElementById("rowbank").value = counter;
    });
	
	
	//--- กรรมการ
	
$('#addBoard').click(function()
	{
		nubBoard++;
		if(nubBoard == 1)
		{
			document.getElementById("tableBoard").style.visibility = 'visible';
			document.getElementById("rowBoard").value = nubBoard;
		}
		else if(nubBoard > 1)
		{
			console.log(nubBoard);
			var newBoardBoxDiv = $(document.createElement('div')).attr("id", 'BoardDiv' + nubBoard);
			table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
			+ '	<tr bgcolor="#E8E8E8">'
			+ '		<td align="right" width="110">ชื่อกรรมการคนที่ '+ nubBoard +' :</td>'
			+ '		<td><input type="text" name="BoardName'+ nubBoard +'" id="BoardName'+ nubBoard +'" size="50" /></td>'
			+ '		<td>ตัวอย่างลายเซ็นต์:<input type="file" size="32" name="BoardSen'+ nubBoard +'[]" value="" /></td>'
			+ '	</tr>'
			+ '	</table>'
			
			newBoardBoxDiv.html(table);

			newBoardBoxDiv.appendTo("#BoardGroup");
				
			document.getElementById("rowBoard").value = nubBoard;
			
			$("#BoardName" + nubBoard).autocomplete({
				source: "s_userid.php",
				minLength:1
			});
		}
    }
	);

	$("#removeBoard").click(function(){
		if(nubBoard==1){
            document.getElementById("tableBoard").style.visibility = 'hidden';
			document.frm1.BoardName1.value = "";
			document.frm1.BoardSen1.value = "";
        }
        if(nubBoard==0){
            //alert("ห้ามลบ !!!");
			document.getElementById("rowBoard").value = nubBoard;
            return false;
        }
        $("#BoardDiv" + nubBoard).remove();
        nubBoard--;
        console.log(nubBoard);
        updateSummary();
		
		document.getElementById("rowBoard").value = nubBoard;
    });
	
	//--- ผู้ถือหุ้น
	
	$('#addShare').click(function()
	{
		nubShare++;
		if(nubShare == 1)
		{
			document.getElementById("tableShare").style.visibility = 'visible';
			document.getElementById("rowShare").value = nubShare;
		}
		else if(nubShare > 1)
		{
			console.log(nubShare);
			var newShareBoxDiv = $(document.createElement('div')).attr("id", 'ShareDiv' + nubShare);
			table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
			+ '	<tr bgcolor="#E8E8E8">'
			+ '		<td align="center" width="40">'+ nubShare +'</td>'
			+ '		<td>'
			+ '			<input type="text" name="ShareName'+ nubShare +'" id="ShareName'+ nubShare +'" size="30" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="ShareAmount'+ nubShare +'" size="10" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="ShareValue'+ nubShare +'" size="15" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="ShareHeld'+ nubShare +'" size="18" readOnly />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="SharePercent'+ nubShare +'" size="10" readOnly />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="file" size="32" name="ShareSen'+ nubShare +'[]" value="" />'
			+ '		</td>'
			+ '	</tr>'
			+ '	</table>'
			
			newShareBoxDiv.html(table);

			newShareBoxDiv.appendTo("#ShareGroup");
				
			document.getElementById("rowShare").value = nubShare;
			
			$("#ShareName" + nubShare).autocomplete({
				source: "s_userid.php",
				minLength:1
			});
		}
    }
	);

	$("#removeShare").click(function(){
		if(nubShare==1){
            document.getElementById("tableShare").style.visibility = 'hidden';
			document.frm1.ShareName1.value = "";
			document.frm1.ShareSen1.value = "";
        }
        if(nubShare==0){
            //alert("ห้ามลบ !!!");
			document.getElementById("rowShare").value = nubShare;
            return false;
        }
        $("#ShareDiv" + nubShare).remove();
        nubShare--;
        console.log(nubShare);
        updateSummary();
		
		document.getElementById("rowShare").value = nubShare;
    });
	
	//--- ผู้ติดต่อ
	
	$('#addCommunicant').click(function()
	{
		nubCommunicant++;
		if(nubCommunicant == 1)
		{
			document.getElementById("tableCommunicant").style.visibility = 'visible';
			document.getElementById("rowCommunicant").value = nubCommunicant;
		}
		else if(nubCommunicant > 1)
		{
			console.log(nubCommunicant);
			var newCommunicantDiv = $(document.createElement('div')).attr("id", 'CommunicantDiv' + nubCommunicant);
			table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
			+ '	<tr bgcolor="#E8E8E8">'
			+ '		<td align="center" width="40">'+ nubCommunicant +'</td>'
			+ '		<td>'
			+ '			<input type="text" name="CommunicantName'+ nubCommunicant +'" size="30" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="CommunicantPosition'+ nubCommunicant +'" size="25" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="CommunicantCoordinate'+ nubCommunicant +'" size="30" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="CommunicantPhone'+ nubCommunicant +'" size="15" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="CommunicantMobile'+ nubCommunicant +'" size="15" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="CommunicantEmail'+ nubCommunicant +'" size="20" />'
			+ '		</td>'
			+ '	</tr>'
			+ '	</table>'
			
			newCommunicantDiv.html(table);

			newCommunicantDiv.appendTo("#CommunicantGroup1");
				
			document.getElementById("rowCommunicant").value = nubCommunicant;
		}
    }
	);

	$("#removeCommunicant").click(function(){
		if(nubCommunicant==1){
            document.getElementById("tableCommunicant").style.visibility = 'hidden';
			document.frm1.CommunicantName1.value = "";
			document.frm1.CommunicantPosition1.value = "";
			document.frm1.CommunicantPhone1.value = "";
			document.frm1.CommunicantMobile1.value = "";
			document.frm1.CommunicantEmail1.value = "";
        }
        if(nubCommunicant==0){
            //alert("ห้ามลบ !!!");
			document.getElementById("rowCommunicant").value = nubCommunicant;
            return false;
        }
        $("#CommunicantDiv" + nubCommunicant).remove();
        nubCommunicant--;
        console.log(nubCommunicant);
        updateSummary();
		
		document.getElementById("rowCommunicant").value = nubCommunicant;
    });
	
	//--- ผู้รับมอบอำนาจ
	
	$('#addAttorney').click(function()
	{
		nubAttorney++;
		if(nubAttorney == 1)
		{
			document.getElementById("tableAttorney").style.visibility = 'visible';
			document.getElementById("rowAttorney").value = nubAttorney;
		}
		else if(nubAttorney > 1)
		{
			console.log(nubAttorney);
			var newAttorneyDiv = $(document.createElement('div')).attr("id", 'AttorneyDiv' + nubAttorney);
			table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
			+ '	<tr bgcolor="#E8E8E8">'
			+ '		<td align="right" width="130">ผู้รับมอบอำนาจคนที่ '+ nubAttorney +' :</td>'
			+ '		<td><input type="text" name="AttorneyName'+ nubAttorney +'" id="AttorneyName'+ nubAttorney +'" size="50"></td>'
			+ '		<td>ใบรับมอบอำนาจ:<input type="file" size="32" name="AttorneySen'+ nubAttorney +'[]" value="" /></td>'
			+ '	</tr>'
			+ '	</table>'
			
			newAttorneyDiv.html(table);

			newAttorneyDiv.appendTo("#AttorneyGroup");
				
			document.getElementById("rowAttorney").value = nubAttorney;
			
			$("#AttorneyName" + nubAttorney).autocomplete({
				source: "s_userid.php",
				minLength:1
			});
		}
    }
	);

	$("#removeAttorney").click(function(){
		if(nubAttorney==1){
            document.getElementById("tableAttorney").style.visibility = 'hidden';
			document.frm1.AttorneyName1.value = "";
			document.frm1.AttorneySen1.value = "";
        }
        if(nubAttorney==0){
            //alert("ห้ามลบ !!!");
			document.getElementById("rowAttorney").value = nubAttorney;
            return false;
        }
        $("#AttorneyDiv" + nubAttorney).remove();
        nubAttorney--;
        console.log(nubAttorney);
        updateSummary();
		
		document.getElementById("rowAttorney").value = nubAttorney;
    });
	
	//--- กำหนดข้อมูลอื่นๆ
	
		var other1 = '<?php echo "$Proportion_in_country"; ?>';
		var other2 = '<?php echo "$Proportion_out_country"; ?>';
		var other3 = '<?php echo "$Proportion_Cash"; ?>';
		var other4 = '<?php echo "$Proportion_Credit"; ?>';
		var other5 = '<?php echo "$Amount_Employee"; ?>';
	
		document.frm1.Proportion_in_country.value = other1;
		document.frm1.Proportion_out_country.value = other2;
		document.frm1.Proportion_Cash.value = other3;
		document.frm1.Proportion_Credit.value = other4;
		document.frm1.Amount_Employee.value = other5;
	
	//--- จบการกำหนดข้อมูลอื่นๆ
	
});
</script>



</html>

