<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เพิ่มลูกค้านิติบุคคล</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){

	H_adds_off();
	M_adds_off();
	document.frm1.updatelistbox.style.visibility = 'hidden';
	document.getElementById("emty").style.visibility = 'hidden';
	
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

function select_country_C()
{ // เลือกประเทศ
	if(document.frm1.C_Country.value!="ไทย" && document.frm1.C_Country.value!="")
	{
		document.frm1.C_Province.value = "ไม่ระบุ";
	}
	else if(document.frm1.C_Country.value=="ไทย")
	{
		document.frm1.C_Province.value = "";
	}
}

function select_country_H()
{ // เลือกประเทศ
	if(document.frm1.H_Country.value!="ไทย" && document.frm1.H_Country.value!="")
	{
		document.frm1.H_Province.value = "ไม่ระบุ";
	}
	else if(document.frm1.H_Country.value=="ไทย")
	{
		document.frm1.H_Province.value = "";
	}
}

function select_country_M()
{ // เลือกประเทศ
	if(document.frm1.M_Country.value!="ไทย" && document.frm1.M_Country.value!="")
	{
		document.frm1.M_Province.value = "ไม่ระบุ";
	}
	else if(document.frm1.M_Country.value=="ไทย")
	{
		document.frm1.M_Province.value = "";
	}
}

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
	
	if (document.frm1.corpNationality.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือก สัญชาตินิติบุคคล ";
	}
	
	if(document.frm1.corpNationality.value=="TH")
	{
		if(document.frm1.corpType.value=="บริษัทจำกัด"){
			if(document.frm1.corp_regis.value.length != 13)
			{
				theMessage = theMessage + "\n -->  เลขทะเบียนนิติบุคคล ของสัญชาติไทย ต้องมี 13 หลักเท่านั้น ";
			}
		}
		if(document.frm1.corpType.value=="บริษัทมหาชนจำกัด"){
			if(document.frm1.corp_regis.value.length != 13)
			{
				theMessage = theMessage + "\n -->  เลขทะเบียนนิติบุคคล ของสัญชาติไทย ต้องมี 13 หลักเท่านั้น ";
			}
		}
		if(document.frm1.corpType.value=="ห้างหุ้นส่วนจำกัด"){
			if(document.frm1.corp_regis.value.length != 13)
			{
				theMessage = theMessage + "\n -->  เลขทะเบียนนิติบุคคล ของสัญชาติไทย ต้องมี 13 หลักเท่านั้น ";
			}
		}
	}
	// แก้ไข ให้ช่อง "ผู้มีอำนาจการทำรายการของบริษัท" ให้เป็น Required field
	if (document.frm1.authority.value=="") 
	{
		theMessage = theMessage + "\n -->  กรุณาระบุ ผู้มีอำนาจการทำรายการของบริษัท";
	}
	
	if (document.getElementById("selete_adds_main1").checked == true || document.getElementById("selete_adds_one1").checked == true || document.getElementById("selete_adds_two1").checked == true)
	{
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
			
			if (document.frm1.C_Country.value=="")
			{
				theMessage = theMessage + "\n -->  กรุณาเลือก ประเทศ ของ ที่อยู่ตามหนังสือรับรอง ";
			}
			else if(document.frm1.C_Country.value=="ไทย")
			{ // ถ้าเลือกประเทศไทย
				if (document.frm1.C_Province.value=="" || document.frm1.C_Province.value=="ไม่ระบุ")
				{
					theMessage = theMessage + "\n -->  กรุณาเลือก จังหวัด ของ ที่อยู่ตามหนังสือรับรอง";
				}
			}
			else if(document.frm1.C_Country!="ไทย")
			{ // ถ้าไม่ใช่ประเทศไทย
				if (document.frm1.C_Province.value!="ไม่ระบุ")
				{
					theMessage = theMessage + "\n -->  ถ้า ที่อยู่ตามหนังสือรับรอง ไม่ใช่ประเทศไทย จังหวัดต้อง ไม่ระบุ เท่านั้น";
				}
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
			
			if (document.frm1.H_Country.value=="")
			{
				theMessage = theMessage + "\n -->  กรุณาเลือก ประเทศ ของ ที่อยู่สำนักงานใหญ่ ";
			}
			else if(document.frm1.H_Country.value=="ไทย")
			{ // ถ้าเลือกประเทศไทย
				if (document.frm1.H_Province.value=="" || document.frm1.H_Province.value=="ไม่ระบุ")
				{
					theMessage = theMessage + "\n -->  กรุณาเลือก จังหวัด ของ ที่อยู่สำนักงานใหญ่";
				}
			}
			else if(document.frm1.H_Country!="ไทย")
			{ // ถ้าไม่ใช่ประเทศไทย
				if (document.frm1.H_Province.value!="ไม่ระบุ")
				{
					theMessage = theMessage + "\n -->  ถ้า ที่อยู่สำนักงานใหญ่ ไม่ใช่ประเทศไทย จังหวัดต้อง ไม่ระบุ เท่านั้น";
				}
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
			
			if (document.frm1.M_Country.value=="")
			{
				theMessage = theMessage + "\n -->  กรุณาเลือก ประเทศ ของ ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร) ";
			}
			else if(document.frm1.M_Country.value=="ไทย")
			{ // ถ้าเลือกประเทศไทย
				if (document.frm1.M_Province.value=="" || document.frm1.M_Province.value=="ไม่ระบุ")
				{
					theMessage = theMessage + "\n -->  กรุณาเลือก จังหวัด ของ ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)";
				}
			}
			else if(document.frm1.M_Country!="ไทย")
			{ // ถ้าไม่ใช่ประเทศไทย
				if (document.frm1.M_Province.value!="ไม่ระบุ")
				{
					theMessage = theMessage + "\n -->  ถ้า ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร) ไม่ใช่ประเทศไทย จังหวัดต้อง ไม่ระบุ เท่านั้น";
				}
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
	
	if (document.getElementById("C_order").checked == true && document.frm1.hc_other.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุลักษณะของที่อยู่ตามหนังสือรับรอง";
	}
	
	if (document.getElementById("H_order").checked == true && document.frm1.hh_other.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุลักษณะของที่อยู่สำนักงานใหญ่";
	}
	
	if (document.getElementById("M_order").checked == true && document.frm1.hm_other.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุลักษณะของที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)";
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
	document.frm1.H_Province.value = "00";
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
	document.frm1.M_Province.value = "00";
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
	document.frm1.C_Province.value = "";
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
	document.frm1.C_Province.value = "";
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
    }
</script>

<script type="text/javascript">
    function datacorpold()
	{  
        var datacorp = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datacorp  
              url: "data_for_corp_old.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false  
        }).responseText;
        $("#datacorp").html(datacorp); // นำค่า datacorp มาแสดงใน div ที่ชื่อ datacorp
		
		document.getElementById("emty").style.visibility = 'visible';
		
		var numberdata = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร numberdata  
              url: "number_of_data.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false  
        }).responseText;
        $("#numberdata").html(numberdata); // นำค่า numberdata มาแสดงใน font ที่ชื่อ numberdata
    }
	
	function emty()
	{  
        var datacorp = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datacorp  
              url: "emty.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false  
        }).responseText;
        $("#datacorp").html(datacorp); // นำค่า datacorp มาแสดงใน div ที่ชื่อ datacorp
		
		document.getElementById("emty").style.visibility = 'hidden';
		
		var numberdata = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร numberdata  
              url: "number_of_data.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false  
        }).responseText;
        $("#numberdata").html(numberdata); // นำค่า numberdata มาแสดงใน font ที่ชื่อ numberdata
    }
</script>

</head>
<body>
<br>
<!-- ข้อมูลลูกค้านิติบุคคลที่ รออนุมัติ และ ไม่อนุมัติ -->
<?php
	$query_old = pg_query("select a.\"corp_regis\" , a.\"corpType\" , a.\"corpName_THA\" , a.\"Approved\" , a.\"corpEdit\" from public.\"th_corp_temp\" a 
							where a.\"corpEdit\" = (select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" b where b.\"corp_regis\" = a.\"corp_regis\") 
							and (a.\"Approved\" is null or a.\"Approved\" = 'false') 
							and a.\"hidden\" = 'false' and a.\"corpID\" = '0' ");
	$numrows_old = pg_num_rows($query_old);
?>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<font id="numberdata"><?php echo "มีข้อมูลนิติบุคคลที่ <u>รออนุมัติ</u> และ <u>ไม่อนุมัติ</u> จำนวน <b>$numrows_old</b> รายการ"; ?></font> <input type="button" name="seeold" id="seeold" value="  แสดง  " onclick="datacorpold()"> <input type="button" name="emty" id="emty" value="  ซ้อน  " onclick="emty()">
			<div id="datacorp">
			
			</div>
		</td>
	</tr>
</table>

<br>
<center>
<form name="frm1" method="post" action="process_addCorp.php" enctype="multipart/form-data">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center">
			<fieldset><legend><B>เพิ่มลูกค้านิติบุคคล</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
					<tr>
						<td align="right">ชื่อนิติบุคคลภาษาไทย :</td><td><input type="text" name="corpName_THA" size="25"><font color="#FF0000"><b> * </b></font></td>
						<td align="right">ชื่อนิติบุคคลภาษาอังกฤษ :</td><td><input type="text" name="corpName_ENG" size="25"></td>
					</tr>
					<tr>
						<td align="right">ชื่อย่อ/เครื่องหมายทางการค้า :</td><td><input type="text" name="trade_name" size="25"></td>
						<td align="right">ประเภทนิติบุคคล :</td>
						<td>
							<select name="corpType">
								<option value="ไม่ระบุ"><เลือกประเภทนิติบุคคล></option>
								<option value="บริษัทจำกัด">บริษัทจำกัด</option>
								<option value="บริษัทมหาชนจำกัด">บริษัทมหาชนจำกัด</option>
								<option value="ห้างหุ้นส่วนจำกัด">ห้างหุ้นส่วนจำกัด</option>
								<option value="ห้างหุ้นส่วนสามัญ">ห้างหุ้นส่วนสามัญ</option>
								<option value="มูลนิธิ">มูลนิธิ</option>
								<option value="สถาบัน">สถาบัน</option>
								<option value="สหกรณ์">สหกรณ์</option>
								<option value="สมาคม">สมาคม</option>
								<option value="วัด">วัด</option>
								<option value="โรงพยาบาล">โรงพยาบาล</option>
								<option value="โรงเรียน">โรงเรียน</option>
								<option value="มหาวิทยาลัย">มหาวิทยาลัย</option>
								<option value="นิติบุคคลหมู่บ้านจัดสรร">นิติบุคคลหมู่บ้านจัดสรร</option>
								<option value="นิติบุคคลอาคารชุด">นิติบุคคลอาคารชุด</option>
							</select><font color="#FF0000"><b> * </b></font>
						</td>
					</tr>
					<tr>
						<td align="right">เลขทะเบียนนิติบุคคล(13 หลัก) :</td><td><input type="text" name="corp_regis" size="25"><font color="#FF0000"><b> * </b></font></td>
						<td align="right">เลขที่ประจำตัวผู้เสียภาษี(10 หลัก) :</td><td><input type="text" name="TaxNumber" size="25"></td>
					</tr>
					<tr>
						<td align="right">สัญชาตินิติบุคคล :</td>
						<td>
							<select name="corpNationality">
								<option value=""><เลือกสัญชาตินิติบุคคล></option>
								<?php
									$qry_country = pg_query("select * from \"VCountry_Active\" order by \"CountryName_THAI\" ");
									while($resCountry = pg_fetch_array($qry_country))
									{
										$CountryCode = $resCountry["CountryCode"];
										$CountryName_THAI = $resCountry["CountryName_THAI"];
										
										echo "<option value=\"$CountryCode\">$CountryName_THAI</option>";
									}
								?>
							</select><font color="#FF0000"><b> * </b></font>
						</td>
						<td></td><td></td>
					</tr>
					<tr>
						<td align="right">โทรศัพท์ :</td>
						<td>
							<input type="text" name="phone" size="13"> ต่อ <input type="text" name="tor" size="3"><font color="#FF0000"><b> * </b></font>
						</td>
						<td align="right">โทรสาร :</td><td><input type="text" name="Fax" size="25"></td>
					</tr>
					<tr>
						<td align="right">E-mail :</td><td><input type="text" name="mail" size="25"></td>
						<td align="right">Website :</td><td><input type="text" name="website" size="25"></td>
					</tr>
					<tr>
						<td align="right">วันที่จดทะเบียนบริษัท :</td><td><input type="text" name="datepicker_regis" id="datepicker_regis" style="text-align:center" size="15" readonly><font color="#FF0000"><b> * </b></font></td>
						<td align="right">ทุนจดทะเบียนเริ่มแรก :</td><td><input type="text" name="initial_capital" size="25"></td>
					</tr>
					<tr>
						<td valign="top" align="right">ผู้มีอำนาจการทำรายการของบริษัท :</td><td colspan="3"><textarea name="authority" cols="70" rows="2"></textarea><font color="#FF0000"><b> * </b></font></td>
					</tr>
					<tr>
						<td align="right">วันที่ของข้อมูลล่าสุด :</td><td><input type="text" name="datepicker_last" id="datepicker_last" style="text-align:center" size="15" readonly></td>
						<td align="right">ประเภทธุรกิจ :</td>
						<td>
							<select name="BusinessType">
								<option value=""><เลือกประเภทธุรกิจ></option>
								<option value="ผลิต">ผลิต</option>
								<option value="ซื้อมาขายไป">ซื้อมาขายไป</option>
								<option value="บริการ">บริการ</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right">ทุนจดทะเบียนปัจจุบัน :</td><td><input type="text" name="current_capital" size="25"></td>
						<td align="right">ชำระแล้ว :</td><td><input type="text" name="paid" size="25"></td>
					</tr>
					<tr>
						<td align="right">สินทรัพย์เฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="asset_avg" size="25"></td>
						<td align="right">รายได้เฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="revenue_avg" size="25"></td>
					</tr>
					<tr>
						<td align="right">หนี้สินเฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="debt_avg" size="25"></td>
						<td align="right">กำไรสุทธิ(3 ปีล่าสุด) :</td><td><input type="text" name="net_profit" size="25"></td>
					</tr>
					<tr>
						<td align="right">ประเภทอุตสาหกรรม :</td>
						<td>
							<select name="IndustypeID" id="IndustypeID">
								<option value=""><เลือกประเภทอุตสาหกรรม></option>
								<?php
								$qry_Industype = pg_query("select * from public.\"th_corp_industype\" order by \"IndustypeName\" ");
								while($res_Industype = pg_fetch_array($qry_Industype))
								{
									$IndustypeID = trim($res_Industype["IndustypeID"]);
									$IndustypeName = trim($res_Industype["IndustypeName"]);
								?>
									<option value="<?php echo $IndustypeID; ?>"><?php echo $IndustypeName; ?></option>
								<?php
								}
								?>
							</select> <a onclick="javascript:popU('../manage_industry/frm_add.php?type=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')" style="cursor:pointer;"><font color="#0000FF"><u> เพิ่ม</u><font></a>
						</td>
						<td align="right">แนวโน้มกำไร :</td>
						<td>
							<select name="trends_profit">
								<option value=""><เลือกแนวโน้มกำไร></option>
								<option value="เพิ่มขึ้น">เพิ่มขึ้น</option>
								<option value="คงที่">คงที่</option>
								<option value="ลดลง">ลดลง</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" align="right">คำอธิบายกิจการ :</td><td colspan="3"><textarea name="explanation" cols="70" rows="2"></textarea></td>
					</tr>
				</table>
				<input type="button" name="updatelistbox" id="updatelistbox" value="click" onclick="testlistbox()">
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>กรรมการ</B></legend>
			<center>
				<input type="button" value="+ เพิ่ม" id="addBoard"> <input type="button" value="- ลบ" id="removeBoard">					
					<table id="tableBoard" align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
						<tr align="center" bgcolor="#E8E8E8">
							<td align="right" width="110">ชื่อกรรมการคนที่ 1 :</td>
							<td><input type="text" name="BoardName1" id="BoardName1" size="70"></td>
							<td>ตัวอย่างลายเซ็นต์:<input type="file" size="32" name="BoardSen1[]" id="BoardSen1" value="" /></td>
						</tr>
					</table>
				<div id="BoardGroup">
				<div id='BoardDiv'>
				</div>
				</div>
				<input type="hidden" name="rowBoard" id="rowBoard" value="1">
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>ผู้ติดต่อ</B></legend>
			<center>
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
						<tr bgcolor="#E8E8E8">
							<td align="center">1</td>
							<td><input type="text" name="CommunicantName1" size="30"></td>
							<td><input type="text" name="CommunicantPosition1" size="25"></td>
							<td><input type="text" name="CommunicantCoordinate1" size="30"></td>
							<td><input type="text" name="CommunicantPhone1" size="15"></td>
							<td><input type="text" name="CommunicantMobile1" size="15"></td>
							<td><input type="text" name="CommunicantEmail1" size="20"></td>
						</tr>
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
						<tr align="center" bgcolor="#E8E8E8">
							<td align="right" width="130">ผู้รับมอบอำนาจคนที่ 1 :</td>
							<td><input type="text" name="AttorneyName1" id="AttorneyName1" size="70"></td>
							<td>ใบรับมอบอำนาจ:<input type="file" size="32" name="AttorneySen1[]" id="AttorneySen1" value="" /></td>
						</tr>
					</table>
				<div id="AttorneyGroup">
				<div id='AttorneyDiv'>
				</div>
				</div>
				<input type="hidden" name="rowAttorney" id="rowAttorney" value="1">
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
						</tr>
						<tr bgcolor="#E8E8E8">
							<td align="center">1</td>
							<td><input type="text" name="ShareName1" id="ShareName1" size="35"></td>
							<td><input type="text" name="ShareAmount1" size="10"></td>
							<td><input type="text" name="ShareValue1" size="15"></td>
							<td><input type="text" name="ShareHeld1" size="20" readonly></td>
							<td><input type="text" name="SharePercent1" size="11" readonly></td>
							<td><input type="file" size="32" name="ShareSen1[]" id="ShareSen1" value="" /></td>
						</tr>
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
			
				<input type="radio" name="selete_adds_main" id="selete_adds_main1" value="main1" checked="checked" onclick="C_adds_new()"> ข้อมูลใหม่
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_main" id="selete_adds_main2" value="main2" onclick="C_adds_off()"> ไม่เปิดเผยข้อมูล
				
				<fieldset><legend><font color="#000000">ลักษณะของที่อยู่</font></legend>
				<center>
					<table>
						<tr>
							<td><input type="radio" name="homestyle_certificate" value="บ้านเดี่ยว" checked="checked"> บ้านเดี่ยว</td><td width="100"><input type="text" name="hc1_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_certificate" value="บ้านแฝด"> บ้านแฝด</td><td width="100"><input type="text" name="hc2_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_certificate" value="ทาวน์เฮ้าส์"> ทาวน์เฮ้าส์</td><td><input type="text" name="hc3_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_certificate" value="อาคารพาณิชย์"> อาคารพาณิชย์</td><td width="100"><input type="text" name="hc4_f" size="2"> ชั้น</td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_certificate" value="คอนโด"> คอนโด</td><td width="100"><input type="text" name="hc5_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_certificate" value="โรงงาน"> โรงงาน</td><td></td>
							<td><input type="radio" name="homestyle_certificate" value="ที่พักชั่วคราว"> ที่พักชั่วคราว</td><td width="100"></td>
							<td><input type="radio" name="homestyle_certificate" value="ที่ดินเปล่า"> ที่ดินเปล่า</td><td width="100"></td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_certificate" value="ไม่ระบุ"> ไม่ระบุ</td><td></td>
							<td><input type="radio" name="homestyle_certificate" id="C_order" value="อื่นๆ"> อื่นๆ</td><td><input type="text" name="hc_other" size="20"></td>
							<td></td><td></td><td></td><td></td>
						</tr>
					</table>
				</center>
				</fieldset>
				
				<fieldset><legend><font color="#000000">รายละเอียด</font></legend>
				<center>
					<table>
						<tr>
							<td align="right">บ้านเลขที่ :</td><td><input type="text" name="C_HomeNumber" size="25"><font color="#FF0000" name="RE_HC1"><b> * </b></font></td>
							<td width="50"></td>
							<td align="right">ห้อง :</td><td><input type="text" name="C_room" size="25"></td>
						</tr>
						<tr>
							<td align="right">ชั้น :</td><td><input type="text" name="C_LiveFloor" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="C_Moo" size="25"></td>
						</tr>
						<tr>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="C_Building" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="C_Village" size="25"></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="C_Lane" size="25"></td>
							<td width="50"></td>
							<td align="right">ถนน :</td><td><input type="text" name="C_Road" size="25"><font color="#FF0000" name="RE_HC2"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="C_District" size="25"></td>
							<td width="50"></td>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="C_State" size="25"><font color="#FF0000" name="RE_HC3"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">จังหวัด :</td>
							<td>
								<select name="C_Province">
									<option value=""><เลือกจังหวัด></option>
									<?php
									$query_province=pg_query("select * from public.\"nw_province\" order by \"proName\"");
									while($res_pro = pg_fetch_array($query_province)){
									?>
										<option value="<?php echo $res_pro["proID"];?>"><?php echo $res_pro["proName"];?></option>
									<?php
									}
									?>
									<option value="ไม่ระบุ">ไม่ระบุ</option>
								</select>
								<font color="#FF0000" name="RE_HC4"><b> * </b></font>
							</td>
							<td width="50"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="C_Postal_code" size="25"><font color="#FF0000" name="RE_HC5"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">ประเทศ :</td>
							<!--<td><input type="text" name="C_Country" size="25"></td>-->
							<td>
								<select name="C_Country" onChange="select_country_C()">
									<option value=""><เลือกประเทศ></option>
									<?php
									$query_C_Country = pg_query("select * from public.\"Country_Code\" where \"Status\" = 'TRUE' order by \"CountryName_THAI\"");
									while($res_C_Coun = pg_fetch_array($query_C_Country)){
									?>
										<option value="<?php echo $res_C_Coun["CountryName_THAI"];?>"><?php echo $res_C_Coun["CountryName_THAI"];?></option>
									<?php
									}
									?>
								</select>
								<font color="#FF0000" name="RE_HC6"><b> * </b></font>
							</td>
							<td width="50"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="C_phone" size="13"> ต่อ <input type="text" name="C_tor" size="3"></td>
						</tr>
						<tr>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="C_Fax" size="25"></td>
							<td width="50"></td>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="C_Live_it" size="23"> ปี</td>
						</tr>
						<tr>
							<td align="right">ปีที่สร้างเสร็จ ( ค.ศ. ) :</td><td><input type="text" name="C_Completion" size="10"> <font color="#777777">Ex:2012</font></td>
							<td width="50"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="C_Acquired" size="25"></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="C_purchase_price" size="20"> บาท</td>
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
			
				<input type="radio" name="selete_adds_one" id="selete_adds_one1" value="one1" onclick="H_adds_new()"> ข้อมูลใหม่
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_one" id="selete_adds_one2" value="one2" onclick="H_adds_C()"> ใช้ที่อยู่ตามหนังสือรับรอง
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_one" id="selete_adds_one3" value="one3" checked="checked" onclick="H_adds_off()"> ไม่เปิดเผยข้อมูล
				
				<fieldset><legend><font color="#000000">ลักษณะของที่อยู่</font></legend>
				<center>
					<table>
						<tr>
							<td><input type="radio" name="homestyle_headquarters" value="บ้านเดี่ยว" checked="checked"> บ้านเดี่ยว</td><td width="100"><input type="text" name="hh1_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_headquarters" value="บ้านแฝด"> บ้านแฝด</td><td width="100"><input type="text" name="hh2_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_headquarters" value="ทาวน์เฮ้าส์"> ทาวน์เฮ้าส์</td><td><input type="text" name="hh3_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_headquarters" value="อาคารพาณิชย์"> อาคารพาณิชย์</td><td width="100"><input type="text" name="hh4_f" size="2"> ชั้น</td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_headquarters" value="คอนโด"> คอนโด</td><td width="100"><input type="text" name="hh5_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_headquarters" value="โรงงาน"> โรงงาน</td><td></td>
							<td><input type="radio" name="homestyle_headquarters" value="ที่พักชั่วคราว"> ที่พักชั่วคราว</td><td width="100"></td>
							<td><input type="radio" name="homestyle_headquarters" value="ที่ดินเปล่า"> ที่ดินเปล่า</td><td width="100"></td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_headquarters" value="ไม่ระบุ"> ไม่ระบุ</td><td></td>
							<td><input type="radio" name="homestyle_headquarters" id="H_order" value="อื่นๆ"> อื่นๆ</td><td><input type="text" name="hh_other" size="20"></td>
							<td></td><td></td><td></td><td></td>
						</tr>
					</table>
				</center>
				</fieldset>
				
				<fieldset><legend><font color="#000000">รายละเอียด</font></legend>
				<center>
					<table>
						<tr>
							<td align="right">บ้านเลขที่ :</td><td><input type="text" name="H_HomeNumber" size="25"><font color="#FF0000" name="RE_HH1"><b> * </b></font></td>
							<td width="50"></td>
							<td align="right">ห้อง :</td><td><input type="text" name="H_room" size="25"></td>
						</tr>
						<tr>
							<td align="right">ชั้น :</td><td><input type="text" name="H_LiveFloor" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="H_Moo" size="25"></td>
						</tr>
						<tr>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="H_Building" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="H_Village" size="25"></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="H_Lane" size="25"></td>
							<td width="50"></td>
							<td align="right">ถนน :</td><td><input type="text" name="H_Road" size="25"><font color="#FF0000" name="RE_HH2"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="H_District" size="25"></td>
							<td width="50"></td>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="H_State" size="25"><font color="#FF0000" name="RE_HH3"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">จังหวัด :</td>
							<td>
								<select name="H_Province">
									<option value=""><เลือกจังหวัด></option>
									<?php
									$query_province=pg_query("select * from public.\"nw_province\" order by \"proName\"");
									while($res_pro = pg_fetch_array($query_province)){
									?>
										<option value="<?php echo $res_pro["proID"];?>"><?php echo $res_pro["proName"];?></option>
									<?php
									}
									?>
									<option value="ไม่ระบุ">ไม่ระบุ</option>
								</select>
								<font color="#FF0000" name="RE_HH4"><b> * </b></font>
							</td>
							<td width="50"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="H_Postal_code" size="25"><font color="#FF0000" name="RE_HH5"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">ประเทศ:</td>
							<!--<td><input type="text" name="H_Country" size="25"></td>-->
							<td>
								<select name="H_Country" onChange="select_country_H()">
									<option value=""><เลือกประเทศ></option>
									<?php
									$query_H_Country = pg_query("select * from public.\"Country_Code\" where \"Status\" = 'TRUE' order by \"CountryName_THAI\"");
									while($res_H_Coun = pg_fetch_array($query_H_Country)){
									?>
										<option value="<?php echo $res_H_Coun["CountryName_THAI"];?>"><?php echo $res_H_Coun["CountryName_THAI"];?></option>
									<?php
									}
									?>
								</select>
								<font color="#FF0000" name="RE_HH6"><b> * </b></font>
							</td>
							<td width="50"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="H_phone" size="13"> ต่อ <input type="text" name="H_tor" size="3"></td>
						</tr>
						<tr>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="H_Fax" size="25"></td>
							<td width="50"></td>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="H_Live_it" size="23"> ปี</td>
						</tr>
						<tr>
							<td align="right">ปีที่สร้างเสร็จ ( ค.ศ. ) :</td><td><input type="text" name="H_Completion" size="10"> <font color="#777777">Ex:2012</font></td>
							<td width="50"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="H_Acquired" size="25"></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="H_purchase_price" size="20"> บาท</td>
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
			
				<input type="radio" name="selete_adds_two" id="selete_adds_two1" value="two1" onclick="M_adds_new()"> ข้อมูลใหม่
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_two" id="selete_adds_two2" value="two2" onclick="M_adds_C()"> ใช้ที่อยู่ตามหนังสือรับรอง
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_two" id="selete_adds_two3" value="two3" onclick="M_adds_H()"> ใช้ที่อยู่สำนักงานใหญ่
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="selete_adds_two" id="selete_adds_two4" value="two4" checked="checked" onclick="M_adds_off()"> ไม่เปิดเผยข้อมูล
				
				<fieldset><legend><font color="#000000">ลักษณะของที่อยู่</font></legend>
				<center>
					<table>
						<tr>
							<td><input type="radio" name="homestyle_mailing" value="บ้านเดี่ยว" checked="checked"> บ้านเดี่ยว</td><td width="100"><input type="text" name="hm1_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_mailing" value="บ้านแฝด"> บ้านแฝด</td><td width="100"><input type="text" name="hm2_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_mailing" value="ทาวน์เฮ้าส์"> ทาวน์เฮ้าส์</td><td><input type="text" name="hm3_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_mailing" value="อาคารพาณิชย์"> อาคารพาณิชย์</td><td width="100"><input type="text" name="hm4_f" size="2"> ชั้น</td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_mailing" value="คอนโด"> คอนโด</td><td width="100"><input type="text" name="hm5_f" size="2"> ชั้น</td>
							<td><input type="radio" name="homestyle_mailing" value="โรงงาน"> โรงงาน</td><td></td>
							<td><input type="radio" name="homestyle_mailing" value="ที่พักชั่วคราว"> ที่พักชั่วคราว</td><td width="100"></td>
							<td><input type="radio" name="homestyle_mailing" value="ที่ดินเปล่า"> ที่ดินเปล่า</td><td width="100"></td>
						</tr>
						<tr>
							<td><input type="radio" name="homestyle_mailing" value="ไม่ระบุ"> ไม่ระบุ</td><td></td>
							<td><input type="radio" name="homestyle_mailing" id="M_order" value="อื่นๆ"> อื่นๆ</td><td><input type="text" name="hm_other" size="20"></td>
						</tr>
					</table>
				</center>
				</fieldset>
				
				<fieldset><legend><font color="#000000">รายละเอียด</font></legend>
				<center>
					<table>
						<tr>
							<td align="right">บ้านเลขที่ :</td><td><input type="text" name="M_HomeNumber" size="25"><font color="#FF0000" name="RE_HM1"><b> * </b></font></td>
							<td width="50"></td>
							<td align="right">ห้อง :</td><td><input type="text" name="M_room" size="25"></td>
						</tr>
						<tr>
							<td align="right">ชั้น :</td><td><input type="text" name="M_LiveFloor" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="M_Moo" size="25"></td>
						</tr>
						<tr>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="M_Building" size="25"></td>
							<td width="50"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="M_Village" size="25"></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="M_Lane" size="25"></td>
							<td width="50"></td>
							<td align="right">ถนน :</td><td><input type="text" name="M_Road" size="25"><font color="#FF0000" name="RE_HM2"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="M_District" size="25"></td>
							<td width="50"></td>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="M_State" size="25"><font color="#FF0000" name="RE_HM3"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">จังหวัด :</td>
							<td>
								<select name="M_Province">
									<option value=""><เลือกจังหวัด></option>
									<?php
									$query_province=pg_query("select * from public.\"nw_province\" order by \"proName\"");
									while($res_pro = pg_fetch_array($query_province)){
									?>
										<option value="<?php echo $res_pro["proID"];?>"><?php echo $res_pro["proName"];?></option>
									<?php
									}
									?>
									<option value="ไม่ระบุ">ไม่ระบุ</option>
								</select>
								<font color="#FF0000" name="RE_HM4"><b> * </b></font>
							</td>
							<td width="50"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="M_Postal_code" size="25"><font color="#FF0000" name="RE_HM5"><b> * </b></font></td>
						</tr>
						<tr>
							<td align="right">ประเทศ:</td>
							<!--<td><input type="text" name="M_Country" name="M_Country" size="25"></td>-->
							<td>
								<select name="M_Country" onChange="select_country_M()">
									<option value=""><เลือกประเทศ></option>
									<?php
									$query_M_Country = pg_query("select * from public.\"Country_Code\" where \"Status\" = 'TRUE' order by \"CountryName_THAI\"");
									while($res_M_Coun = pg_fetch_array($query_M_Country)){
									?>
										<option value="<?php echo $res_M_Coun["CountryName_THAI"];?>"><?php echo $res_M_Coun["CountryName_THAI"];?></option>
									<?php
									}
									?>
								</select>
								<font color="#FF0000" name="RE_HM6"><b> * </b></font>
							</td>
							<td width="50"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="M_phone" size="13"> ต่อ <input type="text" name="M_tor" size="3"></td>
						</tr>
						<tr>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="M_Fax" size="25"></td>
							<td width="50"></td>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="M_Live_it" size="23"> ปี</td>
						</tr>
						<tr>
							<td align="right">ปีที่สร้างเสร็จ ( ค.ศ. ) :</td><td><input type="text" name="M_Completion" size="10"> <font color="#777777">Ex:2012</font></td>
							<td width="50"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="M_Acquired" size="25"></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="M_purchase_price" size="20"> บาท</td>
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
					</table>
				<div id="TextBoxesGroup1">
				<div id='TextBoxDiv1'>
				</div>
				</div>
				<input type="hidden" name="rowbank" id="rowbank" value="1">
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
			<input type="submit" value="บันทึก" onclick="return validate();"> &nbsp;&nbsp;&nbsp; <input type="button" value="เริ่มใหม่ทั้งหมด" onclick="window.location='frm_addCorp.php'">
			<br>
		</td>
	</tr>
</table>
</form>
</center>
</body>

<script type="text/javascript">
var counter = 1;
var nubBoard = 1;
var nubShare = 1;
var nubCommunicant = 1;
var nubAttorney = 1;

$(document).ready(function(){
	$('#addButton').click(function()
	{
		counter++;
		if(counter == 1)
		{
			document.getElementById("tablebank").style.visibility = 'visible';
			document.getElementById("rowbank").value = counter;
		}
		else if(counter > 1)
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
		if(counter==1){
            document.getElementById("tablebank").style.visibility = 'hidden';
			document.frm1.acc_Number1.value = "";
			document.frm1.acc_Name1.value = "";
			document.frm1.branch1.value = "";
        }
        if(counter==0){
            //alert("ห้ามลบ !!!");
			document.getElementById("rowbank").value = counter;
            return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
        console.log(counter);
        updateSummary();
		
		document.getElementById("rowbank").value = counter;
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
			+ '		<td><input type="text" name="BoardName'+ nubBoard +'" id="BoardName'+ nubBoard +'" size="70" /></td>'
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
			+ '			<input type="text" name="ShareName'+ nubShare +'" id="ShareName'+ nubShare +'" size="35" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="ShareAmount'+ nubShare +'" size="10" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="ShareValue'+ nubShare +'" size="15" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="ShareHeld'+ nubShare +'" size="20" readOnly />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="SharePercent'+ nubShare +'" size="11" readOnly />'
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
			+ '		<td><input type="text" name="AttorneyName'+ nubAttorney +'" id="AttorneyName'+ nubAttorney +'" size="70"></td>'
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
});
</script>

</html>