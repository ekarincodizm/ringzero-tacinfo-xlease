<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AV. leasing co.,ltd</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
    <style type="text/css">

    .mouseOut {
    background: #708090;
    color: #FFFAFA;
    }

    .mouseOver {
    background:#00CCCC;
    color: #000000;
    }
    
	</style>
   


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

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> AV.LEASING</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:40px; padding-left:10px;"></div>
  <form method="post" action="frm_edit.php">
  ตรวจสอบ 
    <input type="text" size="100" id="idno_names" name="idno_names" onKeyUp="findNames();" style="height:20;"/>
	<input name="h_id" type="hidden" id="h_id" value="" />
             <input type="submit" value="NEXT" />
             <input name="button" type="button" onclick="window.location='../list_menu.php'" value="BACK" />
            
	<script type="text/javascript">
function make_autocom(autoObj,showObj){
	var mkAutoObj=autoObj; 
	var mkSerValObj=showObj; 
	new Autocomplete(mkAutoObj, function() {
		this.setValue = function(id) {		
			document.getElementById(mkSerValObj).value = id;
		}
		if ( this.isModified )
			this.setValue("");
		if ( this.value.length < 1 && this.isNotClick ) 
			return ;	
		return "listdata.php?q=" + this.value;
    });	
}	

make_autocom("idno_names","h_id");
</script>
  </form>	
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
