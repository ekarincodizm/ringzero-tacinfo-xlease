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
	background: rgb(240, 240, 240);
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
            if (inputField.value.length > 0) {
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
			document.frm_input.f_color.disabled = false;
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
			document.frm_input.f_color.disabled = true;
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
			document.frm_input.f_color.style.backgroundColor="";
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
    </script>

<script>


function calcfunc() {
var val1 = parseFloat(document.frm_input.count_payment.value); //จำนวนงวด
var val2 = parseFloat(document.frm_input.price_payment.value); //ค่างวด

var val_donw1 = parseFloat(document.frm_input.first_price.value); //เงินต้นลูกค้า
var val_down2 = parseFloat(document.frm_input.acc_first_price.value); // เงินต้นบัญชี


parseFloat(document.frm_input.resbs.value=val1*val2);
//var resvs=parseFloat(document.frm_input.resbs.value=val1*val2);



//document.frm_reserve.cost_cal.value=val1-val2;

//var val_begin = parseFloat(document.frm_input.first_price.value);

//document.frm_input.reval.value=val1*val2;

//var val_d1 = parseFloat(document.frm_reserve.cost_down.value);

//document.frm_reserve.cost_fin.value=val_s2-val_d1;
}


function validate() 
{
chkrq();
 var theMessage = "Please complete the following: \n-----------------------------------\n";
 var noErrors = theMessage;

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
	if (document.frm_input.gas_system.disabled == false) {
		if (document.frm_input.gas_system.value=="") {
		theMessage = theMessage + "\n -->  กรุณาระบุระบบแก๊สรถยนต์";
		}
	}

if (document.frm_input.f_carnum.value=="") {

theMessage = theMessage + "\n -->  กรุณาใส่เลขตัวถัง";
}


if (document.frm_input.f_marnum.value=="") {

theMessage = theMessage + "\n -->  กรุณาใส่เลขเครื่อง";
}

if (document.frm_input.f_yearcar.value=="") {

theMessage = theMessage + "\n -->  กรุณาใส่ปีรถ";
}


if(document.getElementById("guide2").checked){
		if(document.getElementById("GuidePeople").value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุชื่อผู้แนะนำ";
		}
}else if(document.getElementById("guide1").checked){
}else{
	theMessage = theMessage + "\n -->  กรุณาระบุว่ามีค่าแนะนำหรือไม่";
}


/*if (document.frm_input.downprice.value=="") {

theMessage = theMessage + "\n -->  กรุณาใส่เงินดาวน์";
}

if (document.frm_input.count_payment.value=="") {

theMessage = theMessage + "\n -->  กรุณาใส่จำนวนงวด";
}

if (document.frm_input.price_payment.value=="") {

theMessage = theMessage + "\n -->  กรุณาใส่ค่างวด";
}



if (parseFloat(document.frm_input.first_price.value) > (parseFloat(document.frm_input.resbs.value))) {

theMessage = theMessage +"\n --> เงินต้นลูกค้ามากกว่า ยอดรวมผ่อนชำระ";
}

if (parseFloat(document.frm_input.acc_first_price.value) > (parseFloat(document.frm_input.resbs.value))) {

theMessage = theMessage + "\n --> เงินต้นทางบัญชีมากกว่า ยอดรวมผ่อนชำระ";
}*/



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
});



</script>

<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }

-->
</style>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div  id="warppage" style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <b>ซื้อสดรถแท็กซี่</b>
  <hr />
  <form method="post" action="save_av_cash.php" name="frm_input" onsubmit="return validate(this);" >
  <table width="800" border="0">
  <tr>
    <td width="123">&nbsp;</td>
    <td width="165">&nbsp;</td>
    <td width="461">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style="background-color:#FFFFCC;">รายละเอียดผู้ซื้อสด</td>
    </tr>
	  <tr>
    <td colspan="3" style="background-color:#C7ECFA;">ตรวจสอบชื่อ, เลขที่บัตรประจำตัว<br />
      <input type="text" size="60" id="txtnames" name="txtnames" onkeyup="findNames();" style="height:20;" tabindex="1" autocomplete=off />
      <input name="button3" type="button" onclick="clr_cus();" value="ค้นหาใหม่" />
	    <div style="width: 400px;height:1px;overflow-y:auto;" id="divcus">
			  <table id="name_table" name="name_table">
				   <tbody id="name_table_body" name="name_table_body"></tbody>
			   </table>
		   <div style="visibility:hidden;" id="popup" name="popup"></div>	 
		</div>
		</td>
    </tr>

  <tr>
    <td>คำนำหน้า</td>
    <td><input type="hidden" name="sta_cusid" id="sta_cusid" value="0"/><input type="text" name="f_firname" onkeyup="passrq(this);" /><font color="red">*</font></td>
    <td><input type="hidden" name="s_val" onkeyup="findNames();"  /><input type="hidden" name="newidcard" id="newidcard" value="0" /></td>
  </tr>
  <tr>
    <td>ชื่อ</td>
    <td><input type="text" name="f_name" onkeyup="passrq(this);"/><font color="red">*</font></td>
    <td>	   	</td>
  </tr>
  <tr>
    <td>นามสกุล</td>
    <td><input type="text" name="f_sirname" onkeyup="passrq(this);" /><font color="red">*</font></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>เลขบัตรประชาชน</td>
    <td><input type="text" name="f_idcard" onkeyup="passrq(this);" /><font color="red">*</font></td>
    <td>&nbsp;</td>
  </tr>
 	 <tr>
    <td colspan="3" style="background-color:#B8D8C8">ตรวจสอบเลขตัวถัง หรือ เลขทะเบียน<br />
      <input type="text" size="60" id="txtnamess" name="txtnamess" onkeyup="findNamess();" style="height:20;"  autocomplete=off />
      <input name="button5" type="button" onclick="clr_car();" value="ค้นหาใหม่" />
	   <div style="width: 400px;height:1px;overflow-y:auto;" id="divcar">
		<table id="name_tables">  
			<tbody id="name_table_bodys"></tbody>
		</table>
	 </div>
   <div style="visibility:hidden;" id="popups">    </div>	  </td>
    </tr>
		<tr>
			<td >ประเภทรถ </td>
			<td >
				<select name="f_type_vehicle" id="f_type_vehicle" onchange="show_brand_func();lockcat(this);passrq(this);">
					<?php 	$qry_sel_astype = pg_query("select \"astypeID\",\"astypeName\" from \"thcap_asset_biz_astype\" where \"astypeName\" LIKE 'รถ%'  AND \"astypeStatus\" = '1'");
							echo "<option value=\"\" >- เลือกประเภทรถ -</option>";
							while($re_sel_astype = pg_fetch_array($qry_sel_astype)){
								$astype_astypeID = $re_sel_astype["astypeID"];
								$astype_astypeName = $re_sel_astype["astypeName"];	
								echo "<option value=\"$astype_astypeID\" >$astype_astypeName</option>";
							}
					?>		
					
				<?php 
						$qry_sel_astype = pg_query("select \"astypeID\" from \"thcap_asset_biz_astype\" where \"astypeName\" = 'รถจักรยานยนต์'  AND \"astypeStatus\" = '1'"); 
						list($motercycle) = pg_fetch_array($qry_sel_astype);
						
						echo "<input type=\"hidden\" name=\"chk_mocy\" value=\"$motercycle\">";			
				?>
				</select><font color="red">*</font>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr id="tr_show_brand" >
			<td >ยี่ห้อ </td>
			<td colspan="2"><span id="show_brand"></td>
			
		</tr>
		<tr id="tr_show_model">
			<td >รุ่น </td>
			<td colspan="2"><span id="show_model"></td></td>
			
		</tr>
  <tr>
    <td>ทะเบียน</td>
    <td><input type="text" name="f_regis"  /></td>
    <td><input type="hidden" name="s_vals" onkeyup="findNamess();"  /></td>
  </tr>
  <tr>
    <td>เลขตัวถัง</td>
    <td><input type="text" name="f_carnum" onkeyup="passrq(this);" /><font color="red">*</font>
      <input type="hidden" name="sta_carid" id="sta_carid" value="0"/></td>
    <td>   </td>
  </tr>
  <tr>
    <td>เลขเครื่อง</td>
    <td><input type="text" name="f_marnum" onkeyup="passrq(this);" /><font color="red">*</font></td>
    <td>&nbsp;</td>
  </tr>
<tr>
			<td>ชนิดรถ </td>
			<td>
				<select name="f_useful_vehicle" id="f_useful_vehicle">
					<option value="" >- ไม่ระบุ -</option>
					<option value="รถรับจ้าง" selected>รถรับจ้าง</option>
					<option value="เก๋ง">เก๋ง</option>
					<option value="กระบะ">กระบะ</option>
				</select>
			</td>
		</tr>		
		<tr>
			<td>เป็นรถ </td>
			<td>
				<select name="f_status_vehicle" id="f_status_vehicle">
					<option value="1" selected>รถใหม่</option>
					<option value="2">รถใช้แล้ว</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>เลขไมล์ </td>
			<td colspan="2"><input type="text" name="C_Milage" size="7px;" onkeypress="check_num(event)"/> กิโลเมตร</td>
		</tr>
		<tr>
			  <td>ระบบแก๊สรถยนต์ </td>
			  <td colspan="2">
				<select name="gas_system" onchange="passrq(this);">
					<option value="" selected >- เลือก -</option>
					<option value="ไม่มีระบบ Gas"  >ไม่มีระบบ Gas</option>
					<option value="NGV 100" >NGV 100</option>
					<option value="NGV 80" >NGV 80</option>
					<option value="LPG 100" >LPG 100</option>
				</select><font color="red">*</font>
			  </td>
		</tr>
	<tr>
    <td>สี</td>
    <td><select name="f_color">
	     <option value="เหลือง">เหลือง</option>
		 <option value="เขียว-เหลือง">เขียว-เหลือง</option>
		 <option value="ฟ้า">ฟ้า</option>
		 <option value="ดำ">ดำ</option>
	    </select></td>
    <td></td>
  </tr>
 <tr>
  <tr>
    <td>ปีรถ (ค.ศ.)</td>
    <td><input type="text" name="f_yearcar" onkeyup="passrq(this);"/><font color="red">*</font></td>
    <td>&nbsp;</td>
  </tr>
    <td>รหัสวิทยุ</td>
    <td><input type="text" name="f_radio" value="" /></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" style="background-color:#CDEAED;">รายการสัญญาซื้อสดรถยนต์</td>
    </tr>
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
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="SAVE"  /></td>
    <td><!--<input type="reset" value="เริ่มใหม่ทั้งหมด"   />--><input type="button" value="เริ่มใหม่ทั้งหมด" onclick="window.location='av_sign_cash.php'">
      <input name="button4" type="button" onclick="window.close();" value="CLOSE" /></td>
  </tr>
</table>

  <div>
 
  
  <table id="sname_table" bgcolor="#FFFAFA" border="0" cellspacing="0" cellpadding="0" />            
            <tbody id="sname_table_body"></tbody>
    </table>
  <div style="visibility:hidden;" id="spopup">
    </div>
  </form>	
</div>


<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
<script type="text/javascript">
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

<script type="text/javascript">
	function check_num(e)
{
    var key;
    if(window.event){
        key = window.event.keyCode; // IE
if (key > 57)
      window.event.returnValue = false;
    }else{
        key = e.which; // Firefox       
if (key > 57)
      key = e.preventDefault();
  }
} 
</script>