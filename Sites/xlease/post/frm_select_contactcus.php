<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

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
			idno_s=document.getElementById("fidno");  
        }

        function findNames() {
            initVars();
            if (inputField.value.length > 2) {
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
            if (xmlHttp.readyState == 4) {
                if (xmlHttp.status == 200) {
				    name =  document.getElementById("popup").innerHTML = xmlHttp.responseText;
					
					//document.frm_input.s_val.value=name;
		           
			        
			      
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
			document.getElementById('divco').style.height = "300px";
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
			clearNames();
			document.getElementById('divco').style.height="1px";
			document.getElementById("name_table").style.height="1px";
			document.getElementById("popup").style.height="1px";
			
			
			
			if(document.frm_input.txtnames.value =='ไม่พบข้อมูล')
			{
			  //alert('Notfound');
			 	
			  document.frm_input.newcus.value ='เพิ่มข้อมูลใหม่';
			  document.frm_input.newcus.disabled =false;	
			  document.getElementById('newcus').onclick = function()
				{
				 
				  var msd=document.frm_input.b_id.value
				  document.location.href="frm_contactcus.php?fidno="+ msd;
				}
			
			}
			else
			{
			  //alert('found');
			
			 
			  document.frm_input.newcus.value ='บันทึกข้อมูล';
			  document.frm_input.newcus.disabled =false;		
			   document.getElementById('newcus').onclick = function()
				{
				  // alert('found');
				  //	document.loction='frm_contactcus.php';	
				  var msds=document.frm_input.b_id.value;
				      txname=document.frm_input.txtnames.value;  
				  document.location.href="save_old_contactcus.php?fidno="+msds+"&cid="+txname; 
				}
			}
			
			
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
		
		function clr_cus()
		{
		 document.frm_input.txtnames.value='';
		 document.frm_input.txtnames.focus();
		 callback();
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

<body style="background-color:#ffffff; margin-top:0px;" >

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> AV.LEASING</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:60px; padding-left:10px;">
  <?php
  include("../config/config.php");
	$num_cc=$_GET["num_cc"];
		 
	 echo	 $edt_idno=$_GET["fIDNO"];
 
  ?>
  </div>
  
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
    <form name="frm_input" method="post" action="save_contactcus.php" onsubmit="return validate(this);">
	  <input type="hidden" name="fidno" id="fidno" value="<?php echo $edt_idno; ?>" />
	  <input type="hidden" name="fcus_id" value="<?php echo $fp_cusid; ?>" />
	  <input type="hidden" name="fcar_id" value="<?php echo $fp_carid; ?>" />
	  <input type="hidden" name="b_id" id="b_id" value="<?php echo $edt_idno; ?>" />
	  ผู้ค้ำประกัน (คนที่ <?php echo $num_cc;?>)
	  <table width="785" border="0" cellpadding="1" cellspacing="1">
	<tr><td colspan="3" style="background-color:#FFFFCC;" height="30"><u>คำแนะนำ</u> ระหว่างชื่อและนามสกุลให้เว้นแค่ 1 วรรค</td></tr>
	<tr>
		<td width="180">ค้นหาชื่อ-สกุลผู้ค้ำประกัน</td>
		<td width="413">
			<input type="hidden" name="sta_cusid"/>
			<input type="text" size="50" id="txtnames" name="txtnames" onkeyup="findNames();" style="height:20;"/>
			<input type="hidden" name="s_val" onkeyup="findNames();"  />
			<input name="button3" type="button" onclick="clr_cus();" value="ค้นหาใหม่" />
		<input type="button" name="newcus" id="newcus" value="เพิ่มข้อมูลใหม่" disabled="TRUE"/></td>
		</tr>
	</table>
	<div style="width: 400px;height:1px;overflow-y:auto;" id="divco">
	<table id="name_table" bgcolor="#FFFAFA" border="0" cellspacing="0" cellpadding="0" />            
            <tbody id="name_table_body"></tbody>
    </table>
	<div style="visibility:hidden;" id="popup"></div>
	</div>
	
	
</form>
  </div>
  
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
