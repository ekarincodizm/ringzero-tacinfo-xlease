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
<script>
function validate() 
{

 var theMessage = "Please complete the following: \n-----------------------------------\n";
 var noErrors = theMessage





if (document.frm_editcus.f_name.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ชื่อ";
}

if (document.frm_editcus.f_sirname.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ นามสกุล";
}

if (document.frm_editcus.f_san.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ สัญชาติ";
}

if (document.frm_editcus.f_age.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ อายุ";
}

if (document.frm_editcus.f_card.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ บัตรแสดงตัว";
}

if (document.frm_editcus.f_cardid.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ เลขที่บัตร";
}

if (document.frm_editcus.f_card_date.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ วันที่ออกบัตร";
}

if (document.frm_editcus.f_by.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ผู้ออกบัตร";
}

if (document.frm_editcus.f_no.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ บ้านเลขที่";
}

if (document.frm_editcus.f_subno.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ หมู่ที่";
}

if (document.frm_editcus.f_soi.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ซอย";
}

if (document.frm_editcus.f_rd.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ถนน";
}

if (document.frm_editcus.f_tum.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ตำบล";
}

if (document.frm_editcus.f_aum.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ อำเภอ";
}

if (document.frm_editcus.f_province.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ จังหวัด";
}


if (document.frm_editcus.f_sirname.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ชื่อ";
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
<script type="text/javascript">
function fn_cus()
{
  var s1=document.frm_editcus.fh_adds.value;
  var s2="";
  var tcard="ที่อยู่ตามบัตรประชาชน"
  if(document.frm_editcus.f_extadd.value==2)
  {
    
	//alert("ใช้ที่อยู่ปัจจุบัน");
	document.frm_editcus.f_ext.disabled=false;
	document.frm_editcus.f_ext.value=s1;
	document.frm_editcus.f_ext.focus();
    
  }
  else if(document.frm_editcus.f_extadd.value==1)
  {
   document.frm_editcus.f_ext.disabled=true;
   document.frm_editcus.f_ext.value=tcard;
  }
   else if(document.frm_editcus.f_extadd.value==0)
  {
   document.frm_editcus.f_ext.disabled=true;
   document.frm_editcus.f_ext.value=s1;
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
  <div class="style5" style="width:auto; height:60px; padding-left:10px;">
  <?php
  include("../config/config.php");
   //echo  $gp_cusid=pg_escape_string($_GET["p_cusid"]; 
         $gp_cusname=pg_escape_string($_GET["p_cusname"]);
        
		
		
   
   echo $getidno=pg_escape_string($_GET["fidno"]);
    	$cus_state=pg_escape_string($_GET["CusState"]); 
		$stsup=pg_escape_string($_GET["stsup"]);
		 $edt_idno=pg_escape_string($_GET["fIDNO"]);
	
  ?>
  </div>
  
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
    <form name="frm_editcus" method="post" action="save_contactcus.php" onsubmit="return validate(this);">
	  <input type="hidden" name="fidno" value="<?php echo $getidno; ?>" />
	  <input type="hidden" name="fcus_id" value="<?php echo $fp_cusid; ?>" />
	  <input type="hidden" name="fcar_id" value="<?php echo $fp_carid; ?>" />
	  <input type="hidden" name="cus_state" value="<?php echo $cus_state;?>" >
	  <input type="hidden" name="stsup" value="<?php echo $stsup;?>">
	  ผู้ค้ำประกัน
	  <table width="785" border="0" cellpadding="1" cellspacing="1">
  
	 <tr>
		<td colspan="5" style="background-color:#FFFFCC;">ข้อมูลผู้ค้ำประกัน</td></tr>
		 <tr>
		<td width="121">คำนำหน้า</td>
		<td width="218"><input type="text" name="fir_name" tabindex="1"/></td>
		<td width="97">สัญชาติ</td>
		<td width="244"><input type="text" name="f_san" tabindex="13" /></td>
		<td width="89">&nbsp;</td>
		 </tr>
		 <tr>
		<td width="121">ชื่อ </td>
		<td width="218"><input type="text" name="f_name" tabindex="2" /></td>
		<td>อายุ</td>
		<td><input type="text" name="f_age" tabindex="14" /></td>
		<td>&nbsp;</td>
		 </tr>
	  <tr>
		<td width="121">นามสกุล </td>
		<td width="218"><input type="text" name="f_sirname" tabindex="3" /></td>
		<td>บัตรแสดงตัว</td>
		<td><input type="text" name="f_card"  tabindex="15" /></td>
		<td>&nbsp;</td>
	  </tr>
		<tr>
		<td>คู่สมรส</td>
		<td><input type="text" name="f_pair" tabindex="4" /></td>
		<td>เลขที่บัตร</td>
		<td><input type="text" name="f_cardid" tabindex="16" /></td>
		<td>&nbsp;</td>
		</tr><tr>
		<td>ที่อยู่ เลขที่ </td>
		<td><input type="text" name="f_no" tabindex="5" /></td>
		<td>วันที่ออกบัตร</td>
		<td><input type="text" name="f_card_date" tabindex="17" />
		  <input name="button_otdate" type="button" onclick="displayCalendar(document.frm_editcus.f_card_date,'yyyy-mm-dd',this)" value="ปฏิทิน" tabindex="18" /></td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>หมู่ที่</td>
		<td><input type="text" name="f_subno" tabindex="6" /></td>
		<td>ออกให้โดย</td>
		<td><input type="text" name="f_by"  tabindex="19"/></td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
	    <td>ซอย</td>
	    <td><input type="text" name="f_soi" tabindex="7" /></td>
	    <td>ที่อยู่ใช้ติดต่อ</td>
	    <td><select name="f_extadd" onchange="fn_cus();" tabindex="20">
          <option value="0">กรุณาเลืิอกที่ติดต่อ</option>
          <option value="1">ใช้ที่อยู่ตามบัตรประชาชน</option>
          <option value="2">ใช้ที่อยู่ปัจุบัน</option>
        </select></td>
	    <td>&nbsp;</td>
	  </tr>
	  <tr>
	    <td>ถนน</td>
	    <td><input type="text" name="f_rd" tabindex="8" /></td>
	    <td colspan="3" rowspan="5" valign="top"><input type="hidden" name="fh_adds" value="<?php echo " "; ?>" />
	      <textarea name="f_ext" cols="50" rows="5" disabled="disabled" tabindex="21" ><?php echo $ext_addr; ?></textarea></td>
	    </tr>
	  <tr>
	    <td>ตำบล/แขวง</td>
	    <td><input type="text" name="f_tum" tabindex="9" /></td>
	    </tr>
	  <tr>
	    <td>อำเภอ/เขต</td>
	    <td><input type="text" name="f_aum" tabindex="10" /></td>
	    </tr>
	  <tr>
	    <td>จังหวัด</td>
	    <td><select name="f_province" size="1" tabindex="11">
          <option value="" selected>เลือก</option>
          <option value="กระบี่">กระบี่</option>
          <option value="กรุงเทพ">กรุงเทพมหานคร</option>
          <option value="กาญจนบุรี">กาญจนบุรี</option>
          <option value="กาฬสินธุ์">กาฬสินธุ์</option>
          <option value="กำแพงเพชร">กำแพงเพชร</option>
          <option value="ขอนแก่น">ขอนแก่น</option>
          <option value="จันทบุรี">จันทบุรี</option>
          <option value="ฉะเชิงเทรา">ฉะเชิงเทรา</option>
          <option value="ชลบุรี">ชลบุรี</option>
          <option value="ชัยนาท">ชัยนาท</option>
          <option value="ชัยภูมิ">ชัยภูมิ</option>
          <option value="ชุมพร">ชุมพร</option>
          <option value="เชียงราย">เชียงราย</option>
          <option value="เชียงใหม่">เชียงใหม่</option>
          <option value="ตรัง">ตรัง</option>
          <option value="ตราด">ตราด</option>
          <option value="ตาก">ตาก</option>
          <option value="นครนายก">นครนายก</option>
          <option value="นครปฐม">นครปฐม</option>
          <option value="นครพนม">นครพนม</option>
          <option value="นครราชสีมา">นครราชสีมา</option>
          <option value="นครศรีธรรมราช">นครศรีธรรมราช</option>
          <option value="นครสวรรค์">นครสวรรค์</option>
          <option value="นนทบุรี">นนทบุรี</option>
          <option value="นราธิวาส">นราธิวาส</option>
          <option value="น่าน">น่าน</option>
          <option value="บุรีรัมย์">บุรีรัมย์</option>
          <option value="ปทุมธานี">ปทุมธานี</option>
          <option value="ประจวบคีรีขันธ์">ประจวบคีรีขันธ์</option>
          <option value="ปราจีนบุรี">ปราจีนบุรี</option>
          <option value="ปัตตานี">ปัตตานี</option>
          <option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา</option>
          <option value="พะเยา">พะเยา</option>
          <option value="พังงา">พังงา</option>
          <option value="พัทลุง">พัทลุง</option>
          <option value="พิจิตร">พิจิตร</option>
          <option value="พิษณุโลก">พิษณุโลก</option>
          <option value="เพชรบุรี">เพชรบุรี</option>
          <option value="เพชรบูรณ์">เพชรบูรณ์</option>
          <option value="แพร่">แพร่</option>
          <option value="ภูเก็ต">ภูเก็ต</option>
          <option value="มหาสารคาม">มหาสารคาม</option>
          <option value="มุกดาหาร">มุกดาหาร</option>
          <option value="แม่ฮ่องสอน">แม่ฮ่องสอน</option>
          <option value="ยโสธร">ยโสธร</option>
          <option value="ยะลา">ยะลา</option>
          <option value="ร้อยเอ็ด">ร้อยเอ็ด</option>
          <option value="ระนอง">ระนอง</option>
          <option value="ระยอง">ระยอง</option>
          <option value="ราชบุรี">ราชบุรี</option>
          <option value="ลพบุรี">ลพบุรี</option>
          <option value="ลำปาง">ลำปาง</option>
          <option value="ลำพูน">ลำพูน</option>
          <option value="เลย">เลย</option>
          <option value="ศรีสะเกษ">ศรีสะเกษ</option>
          <option value="สกลนคร">สกลนคร</option>
          <option value="สงขลา">สงขลา</option>
          <option value="สตูล">สตูล</option>
          <option value="สมุทรปราการ">สมุทรปราการ</option>
          <option value="สมุทรสงคราม">สมุทรสงคราม</option>
          <option value="สมุทรสาคร">สมุทรสาคร</option>
          <option value="สระแก้ว">สระแก้ว</option>
          <option value="สระบุรี">สระบุรี</option>
          <option value="สิงห์บุรี">สิงห์บุรี</option>
          <option value="สุโขทัย">สุโขทัย</option>
          <option value="สุพรรณบุรี">สุพรรณบุรี</option>
          <option value="สุราษฎร์ธานี">สุราษฎร์ธานี</option>
          <option value="สุรินทร์">สุรินทร์</option>
          <option value="หนองคาย">หนองคาย</option>
          <option value="หนองบัวลำภู">หนองบัวลำภู</option>
          <option value="อ่างทอง">อ่างทอง</option>
          <option value="อำนาจเจริญ">อำนาจเจริญ</option>
          <option value="อุดรธานี">อุดรธานี</option>
          <option value="อุตรดิตถ์">อุตรดิตถ์</option>
          <option value="อุทัยธานี">อุทัยธานี</option>
          <option value="อุบลราชธานี">อุบลราชธานี</option>
        </select></td>
	    </tr>
	  <tr>
	    <td>รหัสไปรษณีย์</td>
	    <td><input type="text" name="f_post" value="<?php echo $fa1_post; ?>" tabindex="12"/></td>
	    </tr>
	  <tr>
	    <td>อาชีพ</td>
	    <td><input type="text" name="f_occ" value="" tabindex="12" /></td>
	    <td colspan="3">&nbsp;</td>
	    </tr>
	  <tr>
	    <td>&nbsp;</td>
	    <td><input type="submit" value="SAVE" tabindex="22" /></td>
	    <td colspan="3">&nbsp;</td>
	    </tr>
	</table>
</form>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
