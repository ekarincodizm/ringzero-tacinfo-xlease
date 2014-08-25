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
   
    
    if(empty($_GET["cus_id"]))
	{
	 $listName=$_POST[txtnames];
     $countName=strlen($listName);
   
     $strcus_id=substr($listName,0,$digit_cusid);
     $strname=substr($listName,$digit_cusid+1,$countName);
	}
	else
	{
	    $strcus_id=$_GET["cus_id"];
		$strname=$_GET["cus_name"];
	}  
   
       echo $strcus_id."<br>".$strname;   
  
   ?>
  
  </div>
  <div class="style5" style="height:20px;"><a href="frm_inputcar.php?p_cusid=<?php echo $strcus_id; ?>&p_cusname=<?php echo $strname; ?>">เพิ่มข้อมูลรถแท็กซี่</a></div>
   <div class="style5" >
    <div class="style6" style="width:180px; float:left;">ตรวจสอบทะเบียน - เลขตัวถัง</div>
	<div style="widows:350px; float:left;">
     <form method="post" action="av_step3.php">
	  <input type="hidden" name="pcus_id" value="<?php echo $strcus_id; ?>" />
	  <input type="hidden" name="pcus_name" value="<?php echo $strname; ?>" />
	<input type="text" size="60" id="txtcar" name="txtcar" onkeyup="findNames();" style="height:20;"/><input type="submit" value="NEXT" />
    <table id="name_table" bgcolor="#FFFAFA" border="0" cellspacing="0" cellpadding="0" width="100" />            
    <tbody id="name_table_body"></tbody>
    </table>
	</form>
	<div style="visibility:hidden; width:100px;" id="popupcar" >
   	</div>
	</div>
   </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
