<?php
session_start();
include("../config/config.php");
$cs_idno=pg_escape_string($_GET["cc_id"]);
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
function validate() 
{

 var theMessage = "Please complete the following: \n-----------------------------------\n";
 var noErrors = theMessage;

if (document.frm_beginx.s_number.value=="") {

theMessage = theMessage + "\n -->  กรุณาใส่เลขใบกำกับ";
}
if (document.frm_beginx.s_vender.value=="") {

theMessage = theMessage + "\n -->  กรุณาเลือก vender";
}

if (document.frm_beginx.s_id_rec.value=="") {

theMessage = theMessage + "\n -->  กรุณาใส่เลขที่ใบเสร็จ";
}
if (document.frm_beginx.s_cost_car.value=="0") {

theMessage = theMessage + "\n -->  กรุณาใส่ราคารถ";
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

<script>
function calcfunc() {

var val1 = parseFloat(document.frm_beginx.s_cost_car.value); //ค่ารถ
//var val2 = parseFloat(document.frm_beginx.s_cost_vat.value); //vat รถ
     var val_cal=(val1*7)/100;
     var sl_cal=Math.round(val_cal*100)/100;
	 
	 parseFloat(document.frm_beginx.s_cost_vat.value=sl_cal);
	 parseFloat(document.frm_beginx.cal_vat.value=sl_cal);
	 
	 
	var ms=val_cal+val1;
	var vs_result=Math.round(ms*100)/100;
	 
     parseFloat(document.frm_beginx.s_total.value=vs_result);
     
 
}

function calcfunc2() {

var v2_costvat = parseFloat(document.frm_beginx.s_cost_vat.value);
var v2_cost = parseFloat(document.frm_beginx.s_cost_car.value);
var s2=v2_cost+v2_costvat;
var res_2vat=Math.round(s2*100)/100;
    
    parseFloat(document.frm_beginx.s_total.value=res_2vat);
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
  <form name="frm_beginx" method="post" action="process_save_beginx.php" onsubmit="return validate(this);" >
  <input type="hidden" name="ss_name" value="<?php echo $cs_idno; ?>" />
  <table width="788" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">
  <tr>
    <td colspan="4">&nbsp;</td>
    </tr>
  <tr style="background-color:#DEE7BE;">
    <td colspan="4"><div align="center">บันทึกต้นทุนรถ</div></td>
    </tr>
  
  <tr style="background-color:#EDF1DA;">
    <td width="127" style="padding:3px;">ซื้อมาจาก</td>
    <td colspan="3">
	<select name="s_vender">
	<option value="">เลือก vender</option>
	<?php 
	$sql_vd=pg_query("select \"VenderID\",type_vd,vd_name FROM account.vender");
	while($res_vd=pg_fetch_array($sql_vd))
	{
	?>
	<option value="<?php echo $res_vd["VenderID"]; ?>"><?php echo $res_vd["type_vd"]." ".$res_vd["vd_name"]; ?></option>
	<?php
	}
	?>
	</select></td>
    </tr>
  <tr style="background-color:#EDF1DA;">
    <td style="padding:3px;">วันที่ใบกำกับ</td>
    <td colspan="3"><input type="text" name="s_date" /><input name="button" type="button" onclick="displayCalendar(document.frm_beginx.s_date,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
    </tr>
  <tr style="background-color:#EDF1DA;">
    <td style="padding:3px;">เลขที่ใบกำกับ</td>
    <td colspan="3"><input type="text" name="s_number" /></td>
    </tr>
  <tr style="background-color:#EDF1DA;">
    <td style="padding:3px;">เลขที่ใบเสร็จ</td>
    <td colspan="3"><input type="text" name="s_id_rec" /></td>
    </tr>
  <tr style="background-color:#EDF1DA;">
    <td style="padding:3px;">ราคารถ</td>
    <td colspan="3"><input type="text" name="s_cost_car" onkeyup="calcfunc()" value="0" /> 
    คำนวณยอด vat  
      <input type="text" name="cal_vat" readonly="" value="0" /></td>
    </tr>
  <tr style="background-color:#EDF1DA;">
    <td style="padding:3px;">ยอด vat </td>
    <td colspan="3"><input type="text" name="s_cost_vat" onkeyup="calcfunc2()" value="0" />&nbsp;&nbsp;cost + vat =&nbsp;<input type="text" readonly="" name="s_total" border="0" style="border:0px; text-align:right; background-color:#DDE6B7;"    /></td>
    </tr>
  <tr style="background-color:#A8D38D">
    <td colspan="4"><div align="center"><input type="submit" value="save" /></div></td>
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
