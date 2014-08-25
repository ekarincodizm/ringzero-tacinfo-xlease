<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<script>
function checkField(){
	var text = document.getElementById("h_arti_id").value;
		if(text==""||text==null){
			alert("กรุณาระบุเลขที่สัญญา");
			return false;
		}
}
function calcfunc() {

var val1 = parseFloat(document.frm_beginx.s_cost_car.value); //ค่ารถ
//var val2 = parseFloat(document.frm_beginx.s_cost_vat.value); //vat รถ
     val_cal=(val1*7)/100;
     
	 parseFloat(document.frm_beginx.s_cost_vat.value=val_cal);
     parseFloat(document.frm_beginx.s_total.value=val_cal+val1);
     
 
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

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <form name="frm_beginx" method="post" action="process_before_update.php" onsubmit="return checkField()">
  <table width="788" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">
  <tr>
    <td colspan="4">&nbsp;</td>
    </tr>
  <tr style="background-color:#DEE7BE;">
    <td colspan="4"><div align="center">บันทึกต้นทุนรถ</div></td>
    </tr>
  <tr style="background-color:#EDF1DA;">
    <td width="127" style="padding:3px;">ค้นหาเลขที่สัญญา</td>
    <td colspan="3"><input name="show_arti_topic" type="text" id="show_arti_topic" size="50" />
    <input name="h_arti_id" type="hidden" id="h_arti_id" value="" />
    <input name="submit" type="submit" value="NEXT" /></td>
    </tr>
</table>
</form>
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

make_autocom("show_arti_topic","h_arti_id");
</script>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
