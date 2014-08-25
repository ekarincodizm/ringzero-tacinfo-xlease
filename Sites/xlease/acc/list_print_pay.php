<?php
session_start();
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
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
<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.style6 {font-size: small}
-->
</style>
<!-- InstanceEndEditable -->
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
   $ms_mode=pg_escape_string($_POST["s_mode"]);
   $ms_year=pg_escape_string($_POST["s_year"]);
   $ms_type=pg_escape_string($_POST["s_type"]);
   
   if($ms_mode=="ALL")
   {
   ?>
      
   <?php  echo "<meta http-equiv=\"refresh\" content=\"0;URL=list_all_pay.php?p_type=$ms_type&p_year=$ms_year\">";   
   }
   else if($ms_mode=="MONTH") //เลือกเดือน
   {
   ?>
     <form method="post" action="list_month_pay.php">
	 <br />  
	<table width="784" border="0" cellpadding="1" style="font-size:small;">
  <tr style="background-color:#E9ECD5">
    <td colspan="5"><div align="center">พิมพ์ใบสำคัญจ่าย เดือนจากเดือนที่ต้องการ </div></td>
    </tr>
  <tr>
    <td width="143">ปีที่เลือก <?php echo " "; echo $ms_year; echo " "; ?> เลือกเดือน</td>
    <td width="122">
      <div align="left">
	    <input type="hidden" name="f_year" value="<?php echo $ms_year; ?>" />
        <input type="hidden" name="f_type" value="<?php echo $ms_type; ?>" />
		<select name="f_mon" style="width:100px;">
          <option value="1">มกราคม</option>
          <option value="2">กุมพาพันธ์</option>
          <option value="3">มีนาึคม</option>
          <option value="4">เมษายน</option>
          <option value="5">พฤษภาคม</option>
          <option value="6">มิถุนายน</option>
          <option value="7">กรกฏาคม</option>
          <option value="8">สิงหาคม</option>
          <option value="9">กันยายน</option>
          <option value="10">ตุลาคม</option>
          <option value="11">พฤศจิกายน</option>
          <option value="12">ธันวาคม</option>
        </select>
        </div></td>
    <td width="256"><input name="submit" type="submit" value="NEXT" /></td>
    <td width="173"></td>
    <td width="68">&nbsp;</td>
  </tr>
</table>
  </form>
   <?php
   }
   else if($ms_mode=="ID")
   {
   ?>
    <form name="f_id" method="post" action="list_id_pay.php">
	<table width="784" border="0" cellpadding="1" style="font-size:small;">
  <tr style="background-color:#E9ECD5">
    <td colspan="3"><div align="center">พิมพ์ใบสำคัญจ่าย ระบุเลขที่ต้องการพิมพ์ </div></td>
    </tr>
  <tr>
    <td width="111">ระบุเลขที่ต้องการ</td>
    <td width="408"><input type="text"   id="idno_names" name="idno_names" onKeyUp="findNames();" style="width:400px;" /><input name="h_id" type="hidden" id="h_id" value="" /></td>
    <td width="251"><input type="submit" value="NEXT" /></td>
    </tr>
</table>
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
		return "listdata_vacc.php?q=" + this.value;
    });	
}	

make_autocom("idno_names","h_id");
</script>
	</form>
	
   <?php
   }
   
  ?>
  <a href="#" class="style6" onclick="window.location='frm_print_pay.php'"><- BACK</a> </div>
 </div>

 	

<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
