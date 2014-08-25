<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
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
    
	.style6 {font-size: small}
    </style>
    <script type="text/javascript">        
        var xmlHttp;
        var completeDiv;
        var inputField;
        var nameTable;
        var nameTableBody;
		var p;
		var name=new String();

        function createXMLHttpRequest() {
            if (window.ActiveXObject) {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            else if (window.XMLHttpRequest) {
                xmlHttp = new XMLHttpRequest();                
            }
        }

        function initVars() {
            inputField = document.getElementById("txtcar");            
            nameTable = document.getElementById("name_table");
            completeDiv = document.getElementById("popupcar");
            nameTableBody = document.getElementById("name_table_body");
        }

        function findNames() {
            initVars();
            if (inputField.value.length > 0) {
                createXMLHttpRequest();            
                var url = "car_listdata.php?names=" + inputField.value;                        
                xmlHttp.open("GET", url, true);
                xmlHttp.onreadystatechange = callback;
                xmlHttp.send(null);
            } else {
                clearNames();
            }
        }

        function callback() {
            if (xmlHttp.readyState == 4) {
                if (xmlHttp.status == 200) {
				    name =  document.getElementById("popupcar").innerHTML = xmlHttp.responseText;
										
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
          var offset = 10;
          while(field) {
            offset += field[attr]; 
            field = field.offsetParent;
          }
          return offset;
        }

        function populateName(cell) {
            inputField.value = cell.firstChild.nodeValue;
            clearNames();
        }

        function clearNames() {
            var ind = nameTableBody.childNodes.length;
            for (var i = ind - 1; i >= 0 ; i--) {
                 nameTableBody.removeChild(nameTableBody.childNodes[i]);
            }
                 completeDiv.style.border = "none";
        }
    </script>
<script>
function validate() 
{

 var theMessage = "Please complete the following: \n-----------------------------------\n";
 var noErrors = theMessage


if (document.frm_input.downprice.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่เงินดาวน์";
}

if (document.frm_input.count_payment.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่จำนวนงวด";
}

if (document.frm_input.price_payment.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ค่างวด";
}

if (document.frm_input.first_price.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่เงินต้นของลูกค้า";
}

if (document.frm_input.acc_first_price.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่เงินต้นทางบัญชี";
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
  <div class="style5" style="width:auto; height:50px; padding-left:10px;">
  <?php 
  

  
   include("../config/config.php"); 
   //car_id=$car_sn&cus_id=$pf_cusid&cus_name=$pf_cusname
   
   //savecar
   if(empty($_GET["car_id"]))
   {
     $final_cusid=$_POST["pcus_id"];
	 $final_cusname=$_POST["pcus_name"];
	
	
	 $listCar=$_POST[txtcar];
     $countCar=strlen($listCar);
   
     $final_carid=substr($listCar,0,$digit_carid);
     
	 
   }
   else
   {
      $final_carid=$_GET["car_id"]; 
	  $final_cusid=$_GET["cus_id"];
	  $final_cusname=$_GET["cus_name"];

   }
   
    echo $final_carid."<br>".$final_cusid."<br>".$final_cusname."<br>";  
   
   ?> 
  </div>
  <div class="style5" style="height:20px;"></div>
   <div class="style5" >
   <form name="frm_input" method="post" action="save_contact.php" onSubmit="return validate(this);">
   <input type="hidden" name="final_carid" value="<?php echo $final_carid; ?>" />
   <input type="hidden" name="final_cusid" value="<?php echo $final_cusid; ?>" />
   <table width="569" border="1">
  <tr>
    <td width="134">วันที่ทำสัญญา</td>
    <td width="419"><input name="signDate" type="text" readonly="true" value="<?php echo date("Y/m/d"); ?>"/>
	        <input name="button" type="button" onclick="displayCalendar(document.frm_input.signDate,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
  </tr>
  <tr>
    <td>เงินดาวน์</td>
    <td><input type="text" name="downprice"  /></td>
  </tr>
  <tr>
    <td>จำนวนงวด</td>
    <td><input name="count_payment" type="text"></td>
  </tr>
  <tr>
    <td>ค่างวด</td>
    <td><input name="price_payment" type="text"></td>
  </tr>
  <tr>
    <td>วันที่งวดแรก</td>
    <td><input name="st_datepayment" type="text" value="<?php echo date("Y/m/d"); ?>"/> <input name="button" type="button" onclick="displayCalendar(document.frm_input.st_datepayment,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
  </tr>
  <tr>
    <td>เงินต้นของลูกค้า</td>
    <td><input name="first_price" type="text"></td>
  </tr>
  <tr>
    <td>เงินต้นทางบัญชี</td>
    <td><input name="acc_first_price" type="text"></td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="NEXT" /></td>
  </tr>
</table>
</form>
</div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
