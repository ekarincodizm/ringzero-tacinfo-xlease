<?php
header('Cache-Control: no-cache');
header('Expires');
header('Pragma: no-cache');
header ('Content-type: text/html; charset=utf-8');
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">

<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<style type="text/css">
.mouseOut {
	background: #708090;
	color: #FFFAFA;
}

.mouseOver {
	background: #FFFAFA;
	color: #000000;
}

#warppage
{
	width:800px;
	margin-left:auto;
	margin-right:auto;

	min-height: 5em;
	background: #f4f7f8;
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
}
</style>
<script type="text/javascript">        
var xmlHttp;
var completeDiv;
var inputField;
var nameTable;
var nameTableBody;
var p;
var hidField;
var name=new String();
	
//-- หาเลขที่บัตร
	var HttPRequest = false;
	var chknum; // เช็คว่าเป็นตัวเลขหรือเปล่า
	var chklength; // เช็คความยาว
	var cid; // รหัสลูกค้า
	var idcard; // เลขที่บัตรประจำตัวประชาชน
	var trimidcard; // เลขที่บัตรที่ตัดช่องว่างออกแล้ว
//-- จบการหาเลขที่บัตร

var completeDivs;
var inputFields;
var nameTables;
var nameTableBodys;
var ps;
var hidFields;
var names=new String();

function createXMLHttpRequest() {
	if (window.ActiveXObject) {
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	else if (window.XMLHttpRequest) {
		xmlHttp = new XMLHttpRequest();                
	}
	if(!xmlHttp && document.createElement){
		xmlHttp = new XMLHttpRequest("Content-Type: text/html; charset=utf-8");
	}
	return xmlHttp;
}
function initVars() {
	inputField = document.getElementById("txtnames");            
	nameTable = document.getElementById("name_table");
	completeDiv = document.getElementById("popup");
	nameTableBody = document.getElementById("name_table_body");
	hidField=document.getElementById("s_val");
}

function findNames() {
	initVars();
	if (inputField.value.length > 1) {
		createXMLHttpRequest();            
		var url = "cus_listdata.php?names=" + inputField.value;                        
		xmlHttp.open("GET", url, true);
		xmlHttp.onreadystatechange = callback;
		xmlHttp.send(null);
	} else {
		clearNames();
	}
}
	
function findidcard() { // หาเลขที่บัตร
	HttPRequest = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		HttPRequest = new XMLHttpRequest();
		if (HttPRequest.overrideMimeType) {
			HttPRequest.overrideMimeType('text/html');
		}
	} else if (window.ActiveXObject) { // IE
		try {
			HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
		 
	if (!HttPRequest) {
		alert('Cannot create XMLHTTP instance');
		return false;
	}
	
	var url = "cus_idcard_listdata.php?names=" + cid;
	HttPRequest.open('GET',url,false);
	HttPRequest.send(null);
	 
	//document.getElementById("mySpan").innerHTML = HttPRequest.responseText;
	idcard = HttPRequest.responseText;
}
		
function isNumber(n) { // หาว่าเป็นตัวเลขใช่หรือไม่
	return !isNaN(parseFloat(n)) && isFinite(n);
}

function callback() {
	if (xmlHttp.readyState == 4) {
		if (xmlHttp.status == 200) {
			name =  document.getElementById("popup").innerHTML = xmlHttp.responseText;
			
			//document.frm_input.s_val.value=name;
			 document.frm_input.f_firname.disabled = false;
			 document.frm_input.f_firname.value='';
			 document.frm_input.f_name.disabled = false;
			  document.frm_input.f_name.value='';
			 document.frm_input.f_sirname.disabled = false;
			 document.frm_input.f_sirname.value='';
			 document.frm_input.f_idcard.disabled = false;
			 document.frm_input.f_idcard.value='';
			 document.frm_input.sta_cusid.value=0;	
			 chkrq();
			setNames();
		} else if (xmlHttp.status == 204){
	
			clearNames();
		}
	}
}
	
function setNames() {         
	p = name.split(",");  
	clearNames();
	var size = p.length;
	setOffsets();
	var row, cell, txtNode;
	for (var i = 0; i < size; i++) {
		var nextNode =p[i] 
		row = document.createElement("tr");
		cell = document.createElement("td");
		
		cell.onmouseout = function() {this.className='mouseOver';};
		cell.onmouseover = function() {this.className='mouseOut';};
		cell.setAttribute("bgcolor", "#FFFAFA");
		cell.setAttribute("border", "0");
		cell.onclick = function() { populateName(this); } ;                             

		txtNode = document.createTextNode(nextNode);
		cell.appendChild(txtNode);
		row.appendChild(cell);
		nameTableBody.appendChild(row);
		
	}
}

function setOffsets() {
	var end = inputField.offsetWidth;
	var left = calculateOffsetLeft(inputField);
	var top = calculateOffsetTop(inputField) + inputField.offsetHeight;

	nameTable.style.border = "black 0px solid";
	nameTable.style.left = left + "px";
	nameTable.style.top = top + "px";
	nameTable.style.width = end + "px";
	document.getElementById('divcus').style.height = "300px";
}
	
function calculateOffsetLeft(field) {
	return calculateOffset(field, "offsetLeft");
}

function calculateOffsetTop(field) {
	return calculateOffset(field, "offsetTop");
}

function calculateOffset(field, attr) {
	var offset = 0;
	while(field) {
		offset += field[attr]; 
		field = field.offsetParent;
	}	
	return offset;
}

function populateName(cell) {	
	inputField.value = cell.firstChild.nodeValue;
	document.frm_input.s_val.value=cell.firstChild.nodeValue;
	document.frm_input.f_firname.disabled = true;
	document.frm_input.f_firname.value ='ใช้ข้อมูลที่เลือก';
	document.frm_input.f_name.disabled = true;	
	document.frm_input.f_name.value='ใช้ข้อมูลที่เลือก';
	document.frm_input.f_sirname.disabled = true;
	document.frm_input.f_sirname.value='ใช้ข้อมูลที่เลือก';
	document.frm_input.f_idcard.disabled = true;
	document.frm_input.f_idcard.value='ใช้ข้อมูลที่เลือก';
	document.frm_input.f_firname.style.backgroundColor="";
	document.frm_input.f_name.style.backgroundColor="";
	document.frm_input.f_sirname.style.backgroundColor="";
	document.frm_input.f_idcard.style.backgroundColor="";
		
	document.frm_input.sta_cusid.value=1;
		
	if(document.frm_input.f_firname.value == 'ใช้ข้อมูลที่เลือก')
	{
		cid = document.frm_input.s_val.value;
		cid = cid.substring(0,6);
		findidcard();
		trimidcard = idcard.replace(/ /g,''); // ตัดช่องว่างออก โดยใส่ g เข้าไปจะเป็นการให้หาช่องว่างทุกอันในประโยคนี้
		trimidcard = trimidcard.replace(/-/g,''); // ตัดเครื่องหมายขีดออกโดยใส่ g เข้าไปจะเป็นการให้หาเครื่องหมายขีดทุกอันในประโยคนี้
		chknum = isNumber(trimidcard); // เช็คว่าเป็นตัวเลขหรือไม่
		if(chknum == true)
		{
			chklength = trimidcard.length;
			if(chklength == 13)
			{
				//document.frm_input.f_idcard.value = trimidcard;
				document.frm_input.newidcard.value = 1;
			}
			else
			{
				document.frm_input.f_idcard.disabled = false;
				document.frm_input.f_idcard.value='กรุณาใส่เลขที่บัตรใหม่';
				document.frm_input.newidcard.value = 2;
			}
		}
		else
		{
			document.frm_input.f_idcard.disabled = false;
			document.frm_input.f_idcard.value='กรุณาใส่เลขที่บัตรใหม่';
			document.frm_input.newidcard.value = 2;
		}
	}
		
	clearNames();
		
	document.getElementById('divcus').style.height="1px";
	document.getElementById("name_table").style.height="1px";
	document.getElementById("popup").style.height="1px";
	
	if(document.frm_input.txtnames.value=="ไม่พบข้อมูล")
	{
	   document.frm_input.sta_cusid.value=0;
	   callback();
	}
					
}

function clearNames() {
	var ind = nameTableBody.childNodes.length;
	
	for (var i = ind - 1; i >= 0 ; i--) {
		 nameTableBody.removeChild(nameTableBody.childNodes[i]);
	}
	completeDiv.style.border = "none";
}
//-----

function initVarss() {
	inputFields = document.getElementById("txtnamess");            
	nameTables = document.getElementById("name_tables");
	completeDivs = document.getElementById("popups");
	nameTableBodys = document.getElementById("name_table_bodys");
	hidFields=document.getElementById("s_vals");
}

function findNamess() {
	initVarss();
	
	if (inputFields.value.length > 0) {
		createXMLHttpRequest();            
		var url = "car_listdata.php?carname=" + inputFields.value;                        
		xmlHttp.open("GET", url, true);
		xmlHttp.onreadystatechange = callbacks;
		xmlHttp.send(null);
	} else {
		clearNamess();

	}
}

function callbacks() {
	if (xmlHttp.readyState == 4) {
		if (xmlHttp.status == 200) {
			names =  document.getElementById("popups").innerHTML = xmlHttp.responseText;
			//document.frm_input.s_val.value=name;
		
			document.frm_input.s_vals.value='';
						
			document.frm_input.f_carnum.disabled = false;
			document.frm_input.f_carnum.value='';
			document.frm_input.f_regis.disabled = false;
			document.frm_input.f_regis.value='';	
			document.frm_input.f_marnum.disabled = false;
			document.frm_input.f_marnum.value='';
			document.frm_input.f_carcolor.disabled = false;
			document.frm_input.f_yearcar.value='';		
			document.frm_input.f_yearcar.disabled = false;
			document.frm_input.f_radio.value='';		
			document.frm_input.f_radio.disabled = false;
			document.frm_input.C_Milage.value='';
			document.frm_input.C_Milage.disabled = false;
			document.frm_input.f_type_vehicle.value='';
			document.frm_input.f_type_vehicle.disabled = false;
			document.frm_input.f_useful_vehicle.disabled = false;
			document.frm_input.f_status_vehicle.disabled = false;
			document.frm_input.gas_system.value='';
			document.frm_input.gas_system.disabled = false;
			chkrq();		
			document.frm_input.sta_carid.value=0;
			
			setNamess();
		} else if (xmlHttp.status == 204){
			clearNamess();
		}
	}
}
	
function setNamess() {         
	ps = names.split(",");  
	clearNamess();
	var size = ps.length;
	setOffsetss();
	var rows, cells, txtNodes;
	for (var ii = 0; ii < size; ii++) {
		var nextNodes =ps[ii] 
		rows = document.createElement("tr");
		cells = document.createElement("td");
		
		cells.onmouseout = function() {this.className='mouseOver';};
		cells.onmouseover = function() {this.className='mouseOut';};
		cells.setAttribute("bgcolor", "#FFFAFA");
		cells.setAttribute("border", "0");
		cells.onclick = function() { populateNames(this); } ;                             

		txtNodes = document.createTextNode(nextNodes);
		cells.appendChild(txtNodes);
		rows.appendChild(cells);
		nameTableBodys.appendChild(rows);
	}
}

function setOffsetss() {
	var end = inputFields.offsetWidth;
	var height = inputFields.offsetHeight;
	var left = calculateOffsetLefts(inputFields);
	var top = calculateOffsetTops(inputFields) + inputFields.offsetHeight;

	nameTables.style.border = "black 0px solid";
	nameTables.style.left = left + "px";
	nameTables.style.top = top + "px";
	nameTables.style.width = end + "px";
	document.getElementById('divcar').style.height = "300px";
}
	
function calculateOffsetLefts(fields) {
  return calculateOffsets(fields, "offsetLefts");
}

function calculateOffsetTops(fields) {
  return calculateOffsets(fields, "offsetTops");
}

function calculateOffsets(fields, attrs) {
  var offsets = 0;
  while(fields) {
	offsets += fields[attrs]; 
	fields = fields.offsetParent;
  }
  return offsets;
}

function populateNames(cells) {
	inputFields.value = cells.firstChild.nodeValue;
	document.frm_input.s_vals.value=cells.firstChild.nodeValue;
	document.frm_input.f_carnum.disabled = true;
	document.frm_input.f_carnum.value='ใช้ข้อมูลเลือก';
	document.frm_input.f_regis.disabled = true;	
	document.frm_input.f_regis.value='ใช้ข้อมูลที่เลือก';
	document.frm_input.f_marnum.disabled = true;
	document.frm_input.f_marnum.value='ใช้ข้อมูลที่เลือก';	
	document.frm_input.f_carcolor.disabled = true;
	document.frm_input.f_yearcar.value='ใช้ข้อมูลที่เลือก';
	document.frm_input.f_yearcar.disabled = true;
	document.frm_input.f_radio.value='ใช้ข้อมูลที่เลือก';
	document.frm_input.f_radio.disabled = true;
	$('#tr_show_model').hide();
	$('#tr_show_brand').hide();
	document.frm_input.C_Milage.value='ใช้ข้อมูลที่เลือก';
	document.frm_input.C_Milage.disabled = true;
	document.frm_input.f_type_vehicle.value='';
	document.frm_input.f_type_vehicle.disabled = true;
	document.frm_input.f_useful_vehicle.disabled = true;
	document.frm_input.f_status_vehicle.disabled = true;
	document.frm_input.gas_system.value='';
	document.frm_input.gas_system.disabled = true;
	
	document.frm_input.f_carnum.style.backgroundColor="";
	document.frm_input.f_regis.style.backgroundColor="";
	document.frm_input.f_marnum.style.backgroundColor="";
	document.frm_input.f_carcolor.style.backgroundColor="";
	document.frm_input.f_yearcar.style.backgroundColor="";
	document.frm_input.f_radio.style.backgroundColor="";
	document.frm_input.C_Milage.style.backgroundColor="";
	document.frm_input.f_type_vehicle.style.backgroundColor="";
	document.frm_input.f_useful_vehicle.style.backgroundColor="";
	document.frm_input.f_status_vehicle.style.backgroundColor="";
	document.frm_input.gas_system.style.backgroundColor="";
				
	document.frm_input.sta_carid.value=1;
	clearNamess();
	document.getElementById('divcar').style.height="1px";
	document.getElementById("name_tables").style.height="1px";
	document.getElementById("popups").style.height="1px";
	if(document.frm_input.txtnamess.value=="ไม่พบข้อมูล")
	{
	   document.frm_input.sta_carid.value=0;
	   callbacks();
	}
}

function clearNamess() {
	var inds = nameTableBodys.childNodes.length;
	for (var is = inds - 1; is >= 0 ; is--) {
		 nameTableBodys.removeChild(nameTableBodys.childNodes[is]);
	}
	completeDivs.style.borders = "none";
		 
}
	
function clr_car()
{
	document.frm_input.txtnamess.value='';
	document.frm_input.txtnamess.focus();
 
	callbacks();
}
	
function clr_cus()
{ 
	document.frm_input.txtnames.value='';
	document.frm_input.txtnames.focus();
	document.frm_input.sta_cusid.value=0;
	callback();
}
	
function clr_all()
{ 
	clr_car();
	clr_cus();
  
}
	
//-----	

function calcfunc() {
	var val1 = parseFloat(document.frm_input.count_payment.value); //จำนวนงวด
	var val2 = parseFloat(document.frm_input.price_payment.value); //ค่างวด

	var val_donw1 = parseFloat(document.frm_input.first_price.value); //เงินต้นลูกค้า
	var val_down2 = parseFloat(document.frm_input.acc_first_price.value); // เงินต้นบัญชี
	
	parseFloat(document.frm_input.resbs.value=val1*val2);
}

function validate() 
{
	chkrq();
	
	//ตรวจสอบว่ามีเลขบัตรประชาชนในระบบหรือยัง
	$.post("check_idcard.php",{
		f_idcard : $("#f_idcard").val()
	},function(data2){
		if($("#cus2").is(':checked')){
			if(data2!=1 && $('#f_idcard').val()!=""){
				alert("ผู้เช่าซื้อนี้มีอยู่ในระบบแล้ว ให้เลือกจากข้อมูลเดิม");
				return false;
			}
		}
		//ตรวจสอบว่ามีเลขตัวถังนี้ในระบบหรือยัง
		$.post("check_carnum.php",{
			carnum : $("#f_carnum").val()
		},
		function(data){
			if($("#car2").is(':checked')){
				if(data!=1 && $('#f_carnum').val()!=""){
					alert("รถยนต์คันนี้มีอยู่ในระบบแล้ว ให้เลือกจากข้อมูลเดิม");
					return false;
				}
			}
			
			var theMessage = "Please complete the following: \n-----------------------------------\n";
			var noErrors = theMessage;
			
			//กรณีข้อมูลลูกค้าเก่า
			if($("#cus1").is(':checked')){	
				if(document.frm_input.txtnames.value == ""){
					theMessage = theMessage + "\n -->  กรุณาระบุรายละเอียดผู้เช่าซื้อ";
				}
			}
			
			//กรณีเลือกข้อมูลใหม่
			if($("#cus2").is(':checked')){	
				if (document.frm_input.f_firname.value=="") {
					theMessage = theMessage + "\n -->  กรุณาใส่คำขึ้นต้น";
				}

				if (document.frm_input.f_name.value=="") {
					theMessage = theMessage + "\n -->  กรุณาใส่ชื่อ";
				}

				if (document.frm_input.f_sirname.value=="") {
					theMessage = theMessage + "\n -->  กรุณาใส่นามสกุล";
				}

				if (document.frm_input.f_idcard.value=="" || document.frm_input.f_idcard.value=="กรุณาใส่เลขที่บัตรใหม่") {
					theMessage = theMessage + "\n -->  กรุณาใส่เลขที่บัตรประชาชน";
				}
			}
			
			//กรณีข้อมูลรถเก่า
			if($("#car1").is(':checked')){	
				if(document.frm_input.txtnamess.value == ""){
					theMessage = theMessage + "\n -->  กรุณาระบุรายละเอียดรถ";
				}
			}

			//กรณีการคีย์ข้อมูลใหม่
			if($("#car2").is(':checked')){	
				if(document.frm_input.f_type_vehicle.disabled == false){
					if(document.frm_input.f_type_vehicle.value==""){
						theMessage = theMessage + "\n -->  กรุณาระบุประเภทรถ";
					}else{
						if(document.frm_input.f_brand){
							if(document.frm_input.f_brand.value==""){
								theMessage = theMessage + "\n -->  กรุณาระบุยี่ห้อ";
							}else{
								if(document.frm_input.f_model.value==""){
									theMessage = theMessage + "\n -->  กรุณาระบุรุ่น";	
								}
							}
						}	
					}
				}
				if (document.frm_input.f_carnum.value=="") {
					theMessage = theMessage + "\n -->  กรุณาใส่เลขตัวถัง";
				}
				if (document.frm_input.f_marnum.value=="") {
					theMessage = theMessage + "\n -->  กรุณาใส่เลขเครื่อง";
				}
				if (document.frm_input.gas_system.disabled == false) {
					if (document.frm_input.gas_system.value=="") {
					theMessage = theMessage + "\n -->  กรุณาระบุระบบแก๊สรถยนต์";
					}
				}	
				if (document.frm_input.f_yearcar.value=="") {
					theMessage = theMessage + "\n -->  กรุณาใส่ปีรถ";
				}
			}

			if(document.frm_input.f_idcard.value != "" && document.frm_input.f_idcard.value !="กรุณาใส่เลขที่บัตรใหม่" && document.frm_input.f_idcard.value !="ใช้ข้อมูลที่เลือก"){
				if(document.frm_input.checkdigit.value == 'fail'){
					theMessage = theMessage + "\n -->  หมายเลขบัตรประชาชนไม่ถูกต้อง";
				}
			}

			if(document.getElementById("guide2").checked){
				if(document.getElementById("GuidePeople").value==""){
					theMessage = theMessage + "\n -->  กรุณาระบุชื่อผู้แนะนำ";
				}
			}else if(document.getElementById("guide1").checked){
			}else{
				theMessage = theMessage + "\n -->  กรุณาระบุว่ามีค่าแนะนำหรือไม่";
			}

			// If no errors, submit the form
			if (theMessage == noErrors) {
				$('#frm_input').submit();
				return true;
			} 
			else 
			{
				// If errors were found, show alert message
				alert(theMessage);
				return false;
			}
		});
	});
}
$(document).ready(function(){
	$("#GuidePeople").hide();
	$("#nameguide").hide();
	
	$("#guide1").click(function(){
		$("#GuidePeople").hide();
		$("#nameguide").hide();
	});
	
	$("#guide2").click(function(){
		$("#GuidePeople").show();
		$("#nameguide").show();
	});
	
	$("#f_firname").autocomplete({
        source: "s_title.php",
        minLength:1
    });
	
	$("#txtnames").autocomplete({
        source: "s_cus_listdata.php",
        minLength:2
    });
	
	$("#txtnamess").autocomplete({
        source: "s_car_listdata.php",
        minLength:2
    });
	
	//กรณีเลือกข้อมูลลูกค้าเดิม
	$('#cus1').click(function(){
		$('#showcus1').show();
		$('#showcus2').hide();
		$('#sta_cusid').val(1);
	});
	
	//กรณีกรอกข้อมูลลูกค้าใหม่
	$('#cus2').click(function(){
		$('#showcus1').hide();
		$('#showcus2').show();
		
		$('#txtnames').val('');
		$('#f_firname').val('');
		$('#f_name').val('');
		$('#f_sirname').val('');
		$('#f_idcard').val('');
		$('#sta_cusid').val(0);
	});
	
	//กรณีเลือกข้อมูลรถเดิม
	$('#car1').click(function(){
		$('#show1').show();
		$('#show2').hide();
		$('#sta_carid').val(1);
	});
	
	//กรณีกรอกข้อมูลรถใหม่
	$('#car2').click(function(){
		$('#show1').hide();
		$('#show2').show();
		
		$('#txtnamess').val('');
		$('#f_type_vehicle').val('');
		$('#f_regis').val('');
		$('#f_carnum').val('');
		$('#f_marnum').val('');
		$('#f_useful_vehicle').val('');
		$('#f_status_vehicle').val('');
		$('#C_Milage').val('');
		$('#gas_system').val('');
		$('#f_yearcar').val('');
		$('#f_radio').val('');	
		$('#sta_carid').val(0);		
	});
});

<!-- Check digit เลขบัตรประจำตัวประชาชน -->
function digit(){
	if(document.frm_input.f_idcard.value != null && document.frm_input.f_idcard.value !="กรุณาใส่เลขที่บัตรใหม่" && document.frm_input.f_idcard.value !="ใช้ข้อมูลที่เลือก"){
		var str= document.frm_input.f_idcard.value;
		if(str.length < 13 && str.length != ""){
			alert('กรุณากรอกบัตรประชาชนให้ครบ 13 หลัก');
			document.frm_input.checkdigit.value = 'fail';
			
		}else{
			var dig1 = (str.substring(0, 1))*13;
			var dig2 = (str.substring(1, 2))*12;
			var dig3 = (str.substring(2, 3))*11;
			var dig4 = (str.substring(3, 4))*10;
			var dig5 = (str.substring(4, 5))*9;
			var dig6 = (str.substring(5, 6))*8;
			var dig7 = (str.substring(6, 7))*7;
			var dig8 = (str.substring(7, 8))*6;
			var dig9 = (str.substring(8, 9))*5;
			var dig10 = (str.substring(9, 10))*4;
			var dig11 = (str.substring(10, 11))*3;
			var dig12 = (str.substring(11, 12))*2;
			var dig13 = (str.substring(12, 13));
			var digcheck1 = (dig1+dig2+dig3+dig4+dig5+dig6+dig7+dig8+dig9+dig10+dig11+dig12)%11;
			var	digcheck2 = 11-digcheck1;
				digcheck3 =	digcheck2.toString();
			if(digcheck3.length == 2){
				var dig14 = (digcheck2.substring(1, 2));
				
			}else{
				
				var dig14 = digcheck3;
			}

			if(dig14 == dig13){				
				document.frm_input.checkdigit.value = 'pass';
			}else{
				alert('หมายเลขบัตรประชาชนไม่ถูกต้อง');
				document.frm_input.checkdigit.value = 'fail';
			
			}
		}
	}
}

function check_num(e)
{ // ให้พิมพ์ได้เฉพาะตัวเลขและจุด
    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			window.event.returnValue = false;
		}
    }
	else
	{
        key = e.which; // Firefox       
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			key = e.preventDefault();
		}
	}
}
</script>

<!-- InstanceEndEditable -->
<style type="text/css">
</style>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div  id="warppage" style="width:800px; text-align:left; margin-left:auto; margin-right:auto;"><b>ซื้อสดรถแท็กซี่</b><hr />
<form method="post" action="save_av_cash.php" name="frm_input" id="frm_input">
<table width="800" border="0">
<tr>
    <td>
		<fieldset style="background-color:#FFEFD5;"><legend><b>รายละเอียดผู้เช่าซื้อ</b></legend>
		<table>
		<tr><td colspan="3" bgcolor="#EECBAD"><input type="radio" name="cusdata" id="cus1" checked>ข้อมูลเดิม<input type="radio" name="cusdata" id="cus2">ข้อมูลใหม่</td></tr>		
		<tr>
			<td colspan="3">
				<input type="hidden" name="sta_cusid" id="sta_cusid" value="1" />
				<div id="showcus1">
					<div>ตรวจสอบชื่อ, เลขที่บัตรประจำตัว<font color="red">*</font></div>
					<div><input type="text" size="60" id="txtnames" name="txtnames" style="height:20;" onkeyup="passrq(this);"  /></div>
					<div style="width: 400px;height:1px;overflow-y:auto;" id="divcus">
						<table id="name_table" name="name_table">
							<tbody id="name_table_body" name="name_table_body"></tbody>
						</table>
						<div style="visibility:hidden;" id="popup" name="popup"></div>	
					</div>
				</div>
				<div id="showcus2">
					<table>
					<tr>
						<td width="150">คำนำหน้า</td>
						<td width="250"><input type="text" name="f_firname" id="f_firname" onkeyup="passrq(this);" /><font color="red">*</font></td>
						<td><input type="hidden" name="s_val" onkeyup="findNames();"  /><input type="hidden" name="newidcard" id="newidcard" value="0" /></td>
					</tr>
					<tr>
						<td>ชื่อ</td>
						<td colspan="2"><input type="text" name="f_name" id="f_name" onkeyup="passrq(this);" /><font color="red">*</font></td>
					</tr>
					<tr>
						<td>นามสกุล</td>
						<td colspan="2"><input type="text" name="f_sirname" id="f_sirname" onkeyup="passrq(this);" /><font color="red">*</font></td>
					</tr>
					<tr>
						<td>เลขบัตรประชาชน</td> <input type="hidden" name="checkdigit" id="checkdigit"  >
						<td colspan="2"><input type="text" name="f_idcard" id="f_idcard" maxlength="13" autocomplete="off" onblur="javascript :digit()" onkeypress="javascript :check_num(event)" onkeyup="passrq(this);"/><font color="red">*</font></td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		</table>
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<fieldset style="background-color:#e0e7e9"><legend><b>รายละเอียดรถ</b></legend>
		<table width="500">
		<tr><td colspan="3" bgcolor="#c5ced0"><input type="radio" name="cardata" id="car1" checked>ข้อมูลเดิม<input type="radio" name="cardata" id="car2">ข้อมูลใหม่</td></tr>
		<tr>
			<td colspan="3">
				<input type="hidden" name="sta_carid" id="sta_carid" value="1"/>
				<div id="show1">
					<div>ตรวจสอบเลขตัวถัง หรือ เลขทะเบียน<font color="red"><b>*</b></font></div>
					<div>
						<input type="text" size="60" id="txtnamess" name="txtnamess" style="height:20;"/>
						<!--<input name="button5" type="button" onclick="clr_car();" value="ค้นหาใหม่" />-->
						<div style="width: 400px;height:1px;overflow-y:auto;" id="divcar">
							<table id="name_tables">
							<tbody id="name_table_bodys"></tbody>
							</table>
							<div style="visibility:hidden;" id="popups">    </div>	 
						</div>
					</div>
				</div>
				<div id="show2">
				<table>
				<tr>
					<td width="150">ประเภทรถ </td>
					<td colspan="2">
						<select name="f_type_vehicle" id="f_type_vehicle" onchange="show_brand_func();lockcat(this);passrq(this);">
							<?php 	
							$qry_sel_astype = pg_query("select \"astypeID\",\"astypeName\" from \"thcap_asset_biz_astype\" where \"astypeName\" LIKE 'รถ%'  AND \"astypeStatus\" = '1'");
							echo "<option value=\"\" >- เลือกประเภทรถ -</option>";
							while($re_sel_astype = pg_fetch_array($qry_sel_astype)){
								$astype_astypeID = $re_sel_astype["astypeID"];
								$astype_astypeName = $re_sel_astype["astypeName"];	
								echo "<option value=\"$astype_astypeID\" >$astype_astypeName</option>";
							}
									
							$qry_sel_astype = pg_query("select \"astypeID\" from \"thcap_asset_biz_astype\" where \"astypeName\" = 'รถจักรยานยนต์'  AND \"astypeStatus\" = '1'"); 
							list($motercycle) = pg_fetch_array($qry_sel_astype);
							echo "<input type=\"hidden\" name=\"chk_mocy\" value=\"$motercycle\">";				
							?>	
						</select><font color="red">*</font>
					</td>
				</tr>
				<tr id="tr_show_brand" >
					<td >ยี่ห้อ </td>
					<td colspan="2">
					<span id="show_brand"></span>
					</td>	
				</tr>
				<tr id="tr_show_model">
					<td >รุ่น </td>
					<td colspan="2">
					<span id="show_model"></span>
					</td>
				</tr>
				<tr id="tr_province">
					<td >จังหวัดที่จดทะเบียน </td>
					<td colspan="2">
					<select name="f_province" id="f_province" >
							<?php 
							$qry_sel_province = pg_query("select \"proName\" from \"nw_province\"  order by \"proID\"");
							echo "<option value=\"\" >- เลือกจังหวัด-</option>";
							while($re_sel_province = pg_fetch_array($qry_sel_province)){
								$astype_proName = $re_sel_province["proName"];
								echo "<option value=\"$astype_proName\" >$astype_proName</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>ทะเบียน</td>
					<td width="250"><input type="text" name="f_regis" id="f_regis" onkeyup="passrq(this);"  /></td>
					<td><input type="hidden" name="s_vals" onkeyup="findNamess();"  /></td>
				</tr>
				<tr>
					<td>เลขตัวถัง</td>
					<td colspan="2">
						<input type="text" name="f_carnum"  id="f_carnum" onkeyup="passrq(this);"  /><font color="red">*</font>
					</td>
				</tr>
				<tr>
					<td>เลขเครื่อง</td>
					<td><input type="text" name="f_marnum"  id="f_marnum" onkeyup="passrq(this);"  /><font color="red">*</font></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>สี</td>
					<td colspan="2">
						<div id="showcolor"><select name="f_carcolor" id="f_carcolor">
							<?php
							$qry_sel_carcolor = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '3'");
							while($re_sel_carcolor = pg_fetch_array($qry_sel_carcolor)){
								$carcolor_astypeName = $re_sel_carcolor["elementsName"];
								echo "<option value=\"$carcolor_astypeName\" >$carcolor_astypeName</option>";
							}
							?>
						</select></div>
					</td>
				</tr>
				<tr>
					<td>ชนิดรถ </td>
					<td colspan="2">
						<select name="f_useful_vehicle" id="f_useful_vehicle" >
							<?php 
							$qry_sel_vehicle = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '2'");
							while($re_sel_vehicle = pg_fetch_array($qry_sel_vehicle)){
								$vehicle_astypeName = $re_sel_vehicle["elementsName"];
								echo "<option value=\"$vehicle_astypeName\" >$vehicle_astypeName</option>";
							}
							?>
						</select>
					</td>
				</tr>		
				<tr>
					<td>เป็นรถ </td>
					<td colspan="2">
						<select name="f_status_vehicle" id="f_status_vehicle" >
							<?php 
							$qry_status_vehicle = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '1'");
							while($re_status_vehicle = pg_fetch_array($qry_status_vehicle)){
								$status_vehicle_astypeID = $re_status_vehicle["auto_id"];
								$status_vehicle_astypeName = $re_status_vehicle["elementsName"];
								echo "<option value=\"$status_vehicle_astypeID\" >$status_vehicle_astypeName</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>เลขไมล์ </td>
					<td colspan="2"><input type="text" name="C_Milage" id="C_Milage" size="7px;" onkeypress="check_num(event)"  /> กิโลเมตร</td>
				</tr>
				<tr>
					<td>ระบบแก๊สรถยนต์ </td>
					<td colspan="2">
						<select name="gas_system" id="gas_system" onchange="passrq(this);" >
							<?php
							$qry_gas = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '4'");
							echo "<option value=\"\" >เลือกรายการ</option>";
							while($re_gas = pg_fetch_array($qry_gas)){
								$gas_astypeName = $re_gas["elementsName"];
								echo "<option value=\"$gas_astypeName\" >$gas_astypeName</option>";
							}
							?>
						</select><font color="red">*</font>
					</td>
				</tr>
				<tr>
					<td>ปีรถ (ค.ศ.)</td>
					<td colspan="2"><input type="text" name="f_yearcar" id="f_yearcar" onkeyup="passrq(this);" onkeypress="check_num(event);" /><font color="red">*</font></td>
				</tr>
				<tr>
					<td>รหัสวิทยุ</td>
					<td colspan="2"><input type="text" name="f_radio" id="f_radio" /></td>
				</tr>
				</table>
				</div>
			</td>
		</tr>
		</table>
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<fieldset style="background-color:#E0EEE0;"><legend><b>รายการสัญญาเช่าซื้อรถยนต์</b></legend>
		<table>
		<tr>
			<td>วันที่ทำสัญญา</td>
			<td><input name="signDate" type="text" readonly="true" value="<?php echo date("Y/m/d"); ?>"/>
				<input name="button" type="button" onclick="displayCalendar(document.frm_input.signDate,'yyyy/mm/dd',this)" value="ปฏิทิน" />
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>ซื้อมาจาก</td>
			<td colspan="2"><input name="f_comefrom" type="text" size="60" /></td>
		</tr>
		<tr id="a1" height="30" bgcolor="#FFCCCC">
			<td></td>
			<td colspan="2" valign="top"><input type="radio" name="valGuide" id="guide1" value="0"><b>ไม่มีค่าแนะนำ</b> <input type="radio" name="valGuide" id="guide2" value="1"><b>มีค่าแนะนำ</b><span id="nameguide"> ชื่อผู้แนะนำ : </span><input type="text" name="GuidePeople" id="GuidePeople" size="30"></td>
		</tr>
		<tr>
			<td colspan="3" style="background-color:#C7ECFA;">รายละเอียดสัญญา</td>
		</tr>
		<tr>
			<td colspan="3"><textarea name="contactnote" cols="50"  rows="5"></textarea></td>
		</tr>
		</table>
		</fieldset>
	</td>
<tr>
</table>
<div>
	<input type="button" value="SAVE" onclick="return validate();"  />
	<input type="button" value="เริ่มใหม่ทั้งหมด" onclick="window.location='av_sign_cash.php'">
	<input name="button4" type="button" onclick="window.close();" value="CLOSE" />
</div>

<div>
	<table id="sname_table" bgcolor="#FFFAFA" border="0" cellspacing="0" cellpadding="0" />            
		<tbody id="sname_table_body"></tbody>
	</table>
	<div style="visibility:hidden;" id="spopup"></div>
</div>
</form>	


</div>
</body>
</html>
<script type="text/javascript">
$('#showcus1').show(); //แสดงค้นหาข้อมูลลูกค้า
$('#showcus2').hide(); //แสดงรายละเีอียดลูกค้า

$('#show1').show(); //แสดงค้นหาข้อมูลรถ
$('#show2').hide(); //แสดงรายละเีอียดรถ

$('#tr_show_model').hide();
$('#tr_show_brand').hide();
function show_brand_func(){	
	var type = $('#f_type_vehicle option:selected').attr('value');
	if(type == ''){
		$('#tr_show_brand').hide();
		$('#tr_show_model').hide();
	}else{
		$('#tr_show_brand').show();
		$('#tr_show_model').hide();
		$("#show_brand").load("combo_brand_list.php?type="+type);
	}	
}
function show_model_func(){
	$('#tr_show_model').show();	
	var brandID = $('#f_brand option:selected').attr('value');
	$("#show_model").load("combo_model_list.php?brandID="+brandID);
} 

function lockcat(type){
	if(type.value == document.frm_input.chk_mocy.value){ 
		document.frm_input.f_useful_vehicle.value='';
		document.frm_input.f_useful_vehicle.disabled = true;
		document.frm_input.gas_system.value='';
		document.frm_input.gas_system.disabled = true;
	}else{
		document.frm_input.f_useful_vehicle.value='รถรับจ้าง';
		document.frm_input.f_useful_vehicle.disabled = false;
		document.frm_input.gas_system.value='';
		document.frm_input.gas_system.disabled = false;
	}
}

function chkrq(){
	if (document.frm_input.txtnames.value=="") {
		document.frm_input.txtnames.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_input.f_firname.value=="") {
		document.frm_input.f_firname.style.backgroundColor="#FFCCCC";	
	}
	if (document.frm_input.f_name.value=="") {
		document.frm_input.f_name.style.backgroundColor="#FFCCCC";	
	}
	if (document.frm_input.f_sirname.value=="") {
		document.frm_input.f_sirname.style.backgroundColor="#FFCCCC";	
	}
	if (document.frm_input.f_idcard.value=="" || document.frm_input.f_idcard.value=="กรุณาใส่เลขที่บัตรใหม่") {
		document.frm_input.f_idcard.style.backgroundColor="#FFCCCC";		
	}
	if (document.frm_input.txtnamess.value=="") {
		document.frm_input.txtnamess.style.backgroundColor="#FFCCCC";
	}
	if(document.frm_input.f_type_vehicle.disabled == false){
		if(document.frm_input.f_type_vehicle.value==""){
			document.frm_input.f_type_vehicle.style.backgroundColor="#FFCCCC";	
		}else{
			if(document.frm_input.f_brand){
				if(document.frm_input.f_brand.value==""){
					document.frm_input.f_brand.style.backgroundColor="#FFCCCC";	
				}else{
					if(document.frm_input.f_model.value==""){
						document.frm_input.f_model.style.backgroundColor="#FFCCCC";	
					}
				}
			}	
		}
	}
	if (document.frm_input.gas_system.disabled == false) {
		if (document.frm_input.gas_system.value=="") {
		document.frm_input.gas_system.style.backgroundColor="#FFCCCC";	
		}
	}	
	if (document.frm_input.f_carnum.value=="") {
		document.frm_input.f_carnum.style.backgroundColor="#FFCCCC";	
	}
	if (document.frm_input.f_marnum.value=="") {
		document.frm_input.f_marnum.style.backgroundColor="#FFCCCC";	
	}
	if (document.frm_input.f_yearcar.value=="") {
		document.frm_input.f_yearcar.style.backgroundColor="#FFCCCC";	
	}
	if(document.frm_input.f_idcard.value != "" && document.frm_input.f_idcard.value !="กรุณาใส่เลขที่บัตรใหม่" && document.frm_input.f_idcard.value !="ใช้ข้อมูลที่เลือก"){
		if(document.frm_input.checkdigit.value == 'fail'){
			document.frm_input.checkdigit.style.backgroundColor="#FFCCCC";	
		}
	}
	if(document.getElementById("guide2").checked){
		if(document.getElementById("GuidePeople").value==""){
			document.frm_input.GuidePeople.style.backgroundColor="#FFCCCC";	
		}
	}
}
//สำหรับตรวจว่าหาก textbox กรอกค่าแล้วให้เอาสีแดงออก แต่หากยังไม่กรอกให้ใส่สีแดง สำหรับ Require field
function passrq(object){
	if(object.value != ""){
		object.style.backgroundColor="";
	}else{
		object.style.backgroundColor="#FFCCCC";
	}	
}
</script>