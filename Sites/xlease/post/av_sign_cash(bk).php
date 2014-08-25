<?php
session_start();
header('Content-type: text/html; charset=utf-8');
header('Cache-Control: no-cache');
header('Expires');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
ini_set('session.cache_limiter', 'private');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">


<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<title>AV. leasing co.,ltd</title>
    <style type="text/css">

    .mouseOut {
    background: #708090;
    color: #FFFAFA;
    }

    .mouseOver {
    background: #FFFAFA;
    color: #000000;
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

        function callback() {
		    resource.setContentType ("text/html;charset=utf-8");
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
				     document.frm_input.sta_cusid.value=0;	
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
			document.frm_input.sta_cusid.value=1;
            clearNames();
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
			document.frm_input.sta_carid.value=1;
            clearNamess();
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

if (document.frm_input.f_carnum.value=="") {

theMessage = theMessage + "\n -->  กรุณาใส่เลขตัวถัง";
}


if (document.frm_input.f_marnum.value=="") {

theMessage = theMessage + "\n -->  กรุณาใส่เลขเครื่อง";
}

if (document.frm_input.downprice.value=="") {

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
<h1 class="style4"> AV.LEASING</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:40px; padding-left:10px;"><br />
  </div>
  <form method="post" action="save_av_cash.php" name="frm_input" onsubmit="return validate(this);" >
  <table width="763" border="0">
  <tr>
    <td width="123">&nbsp;</td>
    <td width="193">&nbsp;</td>
    <td width="433">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style="background-color:#FFFFCC;">รายละเอียดผู้เช่าซื้อ</td>
    </tr>
  <tr>
    <td>คำนำหน้า</td>
    <td><input type="hidden" name="sta_cusid"/><input type="text" name="f_firname"  /></td>
    <td>ตรวจสอบชื่อ หรือ นามสกุล
      <input type="text" size="30" id="txtnames" name="txtnames" onkeyup="findNames();" style="height:20;"/><input type="hidden" name="s_val" onkeyup="findNames();"  />
      <input name="button3" type="button" onclick="clr_cus();" value="ค้นหาใหม่" /></td>
  </tr>
  <tr>
    <td>ชื่อ</td>
    <td><input type="text" name="f_name"  /></td>
    <td>
	   <div id="name_table">
   <div id="name_table_body"></div>
   </div>
   <div style="visibility:hidden;" id="popup"></div>	</td>
  </tr>
  <tr>
    <td>นามสกุล</td>
    <td><input type="text" name="f_sirname"  /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style="background-color:#B8D8C8">รถยนต์</td>
    </tr>
  <tr>
    <td>ทะเบียน</td>
    <td><input type="text" name="f_regis"  /></td>
    <td>ตรวจสอบเลขตัวถัง หรือ เลขทะเบียน
	<input type="text" size="30" id="txtnamess" name="txtnamess" onkeyup="findNamess();" style="height:20;"/><input type="hidden" name="s_vals" onkeyup="findNamess();"  /><input type="button" onclick="clr_car();" value="ค้นหาใหม่" /></td>
  </tr>
  <tr>
    <td>เลขตัวถัง</td>
    <td><input type="text" name="f_carnum"  />
      <input type="hidden" name="sta_carid" value="0"/></td>
    <td>   <div id="name_tables">
   <div id="name_table_bodys"></div>
   </div>
   <div style="visibility:hidden;" id="popups">    </div></td>
  </tr>
  <tr>
    <td>เลขเครื่อง</td>
    <td><input type="text" name="f_marnum"  /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style="background-color:#CDEAED;">รายการสัญญาเช่าซื้อรถยนต์</td>
    </tr>
  <tr>
    <td>วันที่ทำสัญญา</td>
    <td colspan="2"><input name="signDate" type="text" readonly="true" value="<?php echo date("Y/m/d"); ?>"/>
      <input name="button" type="button" onclick="displayCalendar(document.frm_input.signDate,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
    </tr>
  <tr>
    <td>ซื้อมาจาก</td>
    <td colspan="2"><input name="f_comefrom" type="text" size="60" /></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="SAVE"  /></td>
    <td><input type="reset" value="เริ่มใหม่ทั้งหมด"   />
      <input name="button4" type="button" onclick="javascript:history.back();" value="BACK" /></td>
  </tr>
</table>

  
  
  
  
  <br />
  <br />
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
