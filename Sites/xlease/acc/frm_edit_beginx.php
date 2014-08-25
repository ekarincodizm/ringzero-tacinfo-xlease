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
function calcfunc() {

var val1 = parseFloat(document.frm_beginx.s_cost_car.value); //ค่ารถ
//var val2 = parseFloat(document.frm_beginx.s_cost_vat.value); //vat รถ
     var val_cal=(val1*7)/100;
     var sl_cal=Math.round(val_cal*100)/100;
	 
	 parseFloat(document.frm_beginx.s_cost_vat.value=sl_cal);
	 
	 
	var ms=val_cal+val1;
	var vs_result=Math.round(ms*100)/100;
	 
     parseFloat(document.frm_beginx.s_total.value=vs_result);
     
 
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
  <?php
  $ssl=pg_query("select * from account.\"CostOfCar\" WHERE \"IDNO\"='$cs_idno' ");
  $res_s=pg_fetch_array($ssl);
  $vds=$res_s["venderid"];
	   $sql_vd=pg_query("select * from account.vender WHERE \"VenderID\"='$vds' ");
	   $res_vd=pg_fetch_array($sql_vd);
  ?>
  
  <form name="frm_beginx" method="post" action="process_update_beginx.php">
  <input type="hidden" name="s_idno" value="<?php echo $cs_idno; ?>"  />
  <table width="788" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">
  <tr>
    <td colspan="4">&nbsp;</td>
    </tr>
  <tr style="background-color:#DEE7BE;">
    <td colspan="4"><div align="center">บันทึกต้นทุนรถ <?php echo $res_s["IDNO"]; ?></div></td>
    </tr>
  
  <tr style="background-color:#EDF1DA;">
    <td width="127" style="padding:3px;">ซื้อมาจาก</td>
    <td colspan="3">
	<select name="s_vender">
	
	<option value="<?php echo $res_s["venderid"]; ?>"><?php echo $res_vd["type_vd"]." ".$res_vd["vd_name"]; ?></option>
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
    <td colspan="3"><input type="text" name="s_date" value="<?php echo $res_s["vd_vat_date"]; ?>" /><input name="button" type="button" onclick="displayCalendar(document.frm_beginx.s_date,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
    </tr>
  <tr style="background-color:#EDF1DA;">
    <td style="padding:3px;">เลขที่ใบกำกับ</td>
    <td colspan="3"><input type="text" name="s_number" value="<?php echo $res_s["bill_no"]; ?>" /></td>
    </tr>
  <tr style="background-color:#EDF1DA;">
    <td style="padding:3px;">เลขที่ใบเสร็จ</td>
    <td colspan="3"><input type="text" name="s_id_rec" value="<?php echo $res_s["vat_no"]; ?>" /></td>
    </tr>
  <tr style="background-color:#EDF1DA;">
    <td style="padding:3px;">ราคารถ</td>
    <td colspan="3"><input type="text" name="s_cost_car" onkeyup="calcfunc()" value="<?php echo $res_s["cost_of_car"]; ?>" /></td>
    </tr>
  <tr style="background-color:#EDF1DA;">
    <td style="padding:3px;">ยอด vat </td>
    <td colspan="3"><input type="text" name="s_cost_vat" value="<?php echo $res_s["vat_of_cost"]; ?>"  />&nbsp;&nbsp;cost + vat =&nbsp;<input type="text" readonly="" name="s_total" border="0" style="border:0px; text-align:right; background-color:#DDE6B7;"    /></td>
    </tr>
  <tr style="background-color:#A8D38D">
    <td colspan="4"><div align="center"><input type="submit" value="save" /></div></td>
    </tr>
</table>
</form>

  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
