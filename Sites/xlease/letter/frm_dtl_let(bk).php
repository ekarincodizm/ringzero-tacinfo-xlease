<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AV. leasing co.,ltd</title>
<script language="javascript">
function add_select()
{
 var fn_add=document.frm_letter.f_fn_add.value;
 //var fn_add=document.frm_letter.type_add.value;
 if(document.frm_letter.type_add.value==1)
 {
    //alert("ที่อยู่เดิม");  
	document.frm_letter.f_add.disabled=true;
	document.frm_letter.f_subadd.disabled=true;
	document.frm_letter.f_soi.disabled=true;
	document.frm_letter.f_road.disabled=true;
	document.frm_letter.f_tum.disabled=true;
	document.frm_letter.f_aum.disabled=true;
	document.frm_letter.f_province.disabled=true;
	document.frm_letter.f_post.disabled=true;
  
 }
 else if(document.frm_letter.type_add.value==2)
 {
    alert("กรุณาใส่ที่อยู่");
    document.frm_letter.f_add.disabled=false;
	document.frm_letter.f_subadd.disabled=false;
	document.frm_letter.f_soi.disabled=false;
	document.frm_letter.f_road.disabled=false;
	document.frm_letter.f_tum.disabled=false;
	document.frm_letter.f_aum.disabled=false;
	document.frm_letter.f_province.disabled=false;
	document.frm_letter.f_post.disabled=false;
	
  
 }
 else
 {
  alert("กรุณาทำรายการที่อยู่");
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
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <?php 
  $idno=pg_escape_string($_GET["IDNO"]); 
  $qry_let=pg_query("select  A.\"C_REGIS\",A.car_regis,A.\"IDNO\",A.\"full_name\",B.\"N_ContactAdd\",B.\"CusID\" from \"VContact\" A  
                     LEFT OUTER JOIN \"Fn\" B on B.\"CusID\"=A.\"CusID\" 
					 WHERE   A.\"IDNO\"='$idno'
					 
					");
	$resvcon=pg_fetch_array($qry_let);
	if($resvcon["C_REGIS"]=="")
		{
		
		$rec_regis=$resvcon["car_regis"]; 
		$rec_cnumber=$resvcon["gas_number"];
		$res_band=$resvcon["gas_name"];
		}
		else
		{
		
		$rec_regis=$resvcon["C_REGIS"];
		$rec_cnumber=$resvcon["C_CARNUM"];
		$res_band=$resvcon["C_CARNAME"];
		}
		
  

	

  ?>	
 
   <form action="process_save.php" method="post" name="frm_letter" >
  <table width="100%" border="0">
  <tr style="background-color:#ffffff">
    <td colspan="6">	</td>
    </tr> 
   
  <tr>
    <td width="139">IDNO ชื่อ-นามสกุล </td>
    <td colspan="2"><?php echo $resvcon["full_name"]; ?></td>
    <td width="85">ทะเบียน</td>
    <td width="132"><?php echo $rec_regis; ?></td>
    <td width="116">&nbsp;</td>
  </tr>
  
  <tr>
    <td>ข้อมูลที่อยู่</td>
    <td colspan="5" rowspan="2" valign="top" style="background-color:#EBF2FA;">
	<input type="hidden" name="f_idno" value="<?php echo $idno; ?>"  />
	<input type="hidden" name="f_fn_add" value="<?php echo $resvcon["N_ContactAdd"];?>"  />
	<?php echo $resvcon["N_ContactAdd"];?></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    </tr>
  <tr>
   <tr>
    <td>ชื่อผู้รับจดหมาย</td>
    <td colspan="5"><span style="background-color:#EBF2FA;">
      <input type="text" name="f_name"  size="60" value="<?php echo $resvcon["full_name"]; ?>" tabindex="1" />
    </span></td>
   </tr>
    <td>เลือกที่ส่งจดหมาย</td>
    <td colspan="2">
		<select name="type_add" onchange="add_select();" tabindex="2">
			<option value="0">เลือกที่ส่งจดหมาย</option>
			<option value="1">ใช้ที่อยู่ปัจจุบัน</option>
			<option value="2">ใช้ที่อยู่อื่น ๆ</option>
		</select>	</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>เลขที่ หมู่ที่ </td>
    <td colspan="5"><input type="text" name="f_add" tabindex="3"  /></td>
    </tr>
  <tr>
    <td>หมู่บ้าน</td>
    <td colspan="5"><input type="text" name="f_subadd" tabindex="4"  /></td>
    </tr>
  <tr>
    <td>ซอย</td>
    <td colspan="5"><input type="text" name="f_soi"  /></td>
    </tr>
  <tr>
    <td>ถนน</td>
    <td colspan="5"><input type="text" name="f_road" /></td>
  </tr>
  <tr>
    <td>แขวง/ตำบล</td>
    <td colspan="5"><input type="text" name="f_tum"  /></td>
  </tr>
  <tr>
    <td>เขต/อำเภอ</td>
    <td colspan="5"><input type="text" name="f_aum"  /></td>
  </tr>
  <tr>
    <td>จังหวัด</td>
    <td colspan="5"><select name="f_province" size="1">
      <option value="0" selected="selected">เลือกจังหวัด</option>
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
	  <option value="บึงกาฬ">บึงกาฬ</option>
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
      <option value="ศรีษะเกษ">ศรีษะเกษ</option>
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
    <td colspan="5"><input type="text" name="f_post" /></td>
  </tr>
  <tr>
    <td>ประเภทจดหมาย</td>
    <td colspan="5">
	<select name="f_types" id="f_types">
	 <option value="0">เลือกประเภทจดหมาย</option>
	 <?php
	 $qry_type=pg_query("select * from letter.type_letter ");
	 while($restype=pg_fetch_array($qry_type))
	 {
	 ?>
	 <option value="<?php echo $restype["auto_id"]; ?>" ><?php echo $restype["type_name"]; ?></option>
	 
	 <?php
	 }
	 ?>
	</select>	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="69"><input type="submit" value="บันทึก"  /></td>
    <td width="124">&nbsp;</td>
    <td colspan="3"><input type="reset" /></td>
    </tr>
</table>
</form>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
