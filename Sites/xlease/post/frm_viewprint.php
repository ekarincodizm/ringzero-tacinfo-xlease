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
   echo  $gp_cusid=$_GET["p_cusid"]; 
         $gp_cusname=$_GET["p_cusname"];
		 
		 $edt_idno=$_POST["idno_names"];
		 
  $qry_fp=pg_query("select * from \"Fp\" where \"IDNO\" ='$edt_idno'");
  $res_fp=pg_fetch_array($qry_fp);
  

  
  
  $fp_cusid=$res_fp["CusID"];
  $fp_carid=$res_fp["CarID"];
  $fp_stdate=$res_fp["P_STDATE"];
  $fp_pmonth=$res_fp["P_MONTH"];   
  $fp_pvat=$res_fp["P_VAT"];
  $fp_ptotal=$res_fp["P_TOTAL"];
  $fp_pdown=$res_fp["P_DOWN"];
  $fp_pvatofdown=$res_fp["P_VatOfDown"];
  $fp_begin=$res_fp["P_BEGIN"];
  $fp_beginx=$res_fp["P_BEGINX"];
  $fp_fdate=$res_fp["P_FDATE"];	
  $fp_cusby_year=$res_fp["P_CustByYear"];
   
  ?>
  </div>
  
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
    <form name="frm_edit" method="post" action="edit_cus.php">
	  <input type="hidden" name="fidno" value="<?php echo $edt_idno; ?>" />
	  <input type="hidden" name="fcus_id" value="<?php echo $fp_cusid; ?>" />
	  <input type="hidden" name="fcar_id" value="<?php echo $fp_carid; ?>" />
      <table width="785" border="0" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="3" style="background-color:#FFFFCC;">แก้ไขข้อมูลผู้ทำสัญญา</td>
	<?php
	  $qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\" ='$fp_cusid' ");
	  $res_fa1=pg_fetch_array($qry_fa1);
	  $fa1_cusid=$res_fa1["CusID"];
      $fa1_firname=$res_fa1["A_FIRNAME"];
	  $fa1_name=$res_fa1["A_NAME"];
	  $fa1_surname=$res_fa1["A_SIRNAME"];
	  $fa1_pair=$res_fa1["A_PAIR"];
	  $fa1_no=$res_fa1["A_NO"];
	  $fa1_subno=$res_fa1["A_SUBNO"];	
      $fa1_soi=$res_fa1["A_SOI"];	
	  $fa1_rd=$res_fa1["A_RD"];	
	  $fa1_tum=$res_fa1["A_TUM"];	
	  $fa1_aum=$res_fa1["A_AUM"];
	  $fa1_pro=$res_fa1["A_PRO"];	
	  $fa1_post=$res_fa1["A_POST"];
	  
	  
	  
	?>
    </tr>
  <tr>
    <td width="106">ชื่อ</td>
    <td width="241"><input type="text" name="f_name" value="<?php echo $fa1_name; ?>"/></td>
    <td width="428">&nbsp;</td>
  </tr>
  <tr>
    <td>นามสกุล</td>
    <td><input type="text" name="f_surname" value="<?php echo $fa1_surname; ?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>ชื่อ คู่สมรส</td>
    <td><input type="text" name="f_pair" value="<?php echo $fa1_pair; ?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>เลขที่</td>
    <td><input type="text" name="f_no" value="<?php echo $fa1_no; ?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>SubNo</td>
    <td><input type="text" name="f_subno" value="<?php echo $fa1_subno; ?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>ซอย</td>
    <td><input type="text" name="f_soi" value="<?php echo $fa1_soi; ?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>ถนน</td>
    <td><input type="text" name="f_rd" value="<?php echo $fa1_rd; ?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>เขต/อำเภอ</td>
    <td><input type="text" name="f_aum" value="<?php echo $fa1_aum; ?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>แขวง/ตำบล</td>
    <td><input type="text" name="f_tum" value="<?php echo $fa1_tum; ?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>จังหวัด</td>
    <td>	
	<select name="f_province" size="1">
		<option value="<?php echo $fa1_pro; ?>" selected><?php echo $fa1_pro; ?></option>
		<?php
		$query_province=pg_query("select * from \"nw_province\" where \"proName\" != '$fa1_pro' order by \"proID\"");
		while($res_pro = pg_fetch_array($query_province)){
		?>
		<option value="<?php echo $res_pro["proName"];?>" <?php if($res_pro["proName"]==$fa1_pro){?>selected<?php }?>><?php echo $res_pro["proName"];?></option>
		<?php
		}
		?>
	</select>	</td>
    <td>&nbsp;</td>
  </tr>
 
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style="background-color:#FFFFCC;">แก้ไขข้อมูลรถแท็กซี่</td>
	<?php 
	 $qry_car=pg_query("select *,to_char(\"C_TAX_ExpDate\", 'YYYY-MM-DD') AS exp_date from \"VCarregistemp\" where \"IDNO\" ='$edt_idno' ");
	 $res_fc=pg_fetch_array($qry_car);
	 $fc_carid=$res_fc["CarID"];
	 $fc_name=$res_fc["C_CARNAME"];
	 $fc_year=$res_fc["C_YEAR"];
	 $fc_regis=$res_fc["C_REGIS"];
	 
	 $fcs_regis_by=$res_fc["C_REGIS_BY"];
	 if(empty($fcs_regis_by))
	 {
	  $fc_regis_by="เลือก";
	  $reg_value=" ";
	 }
	 else
	 {
	   $fc_regis_by=$fcs_regis_by;
	   $reg_value=$fcs_regis_by;
	 }
	 
	 
	 $fc_color=$res_fc["C_COLOR"];
	 $fc_num=$res_fc["C_CARNUM"];
	 $fc_mar=$res_fc["C_MARNUM"];
	 $fc_mi=$res_fc["C_Milage"];
	 
	 $fc_expert=$res_fc["exp_date"];
	 
	 
	 $fc_mon=$res_fc["C_TAX_MON"];

	 
	 
	 
	?>
    </tr>
  <tr>
    <td>ยี่ห้อรถ</td>
    <td><input type="text" name="f_carname" value="<?php echo $fc_name; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>รุ่นปี</td>
    <td><input type="text" name="f_caryear" value="<?php echo $fc_year; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>เลขตัวถัง</td>
    <td><input type="text" name="f_carnum" value="<?php echo $fc_num; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>เลขเครื่องยนต์</td>
    <td><input type="text" name="f_carmar" value="<?php echo $fc_mar; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>ทะเบียน</td>
    <td><input type="text" name="f_carregis" value="<?php echo $fc_regis; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>จังหวัดที่จดทะเบียน</td>
    <td>
	<select name="f_pprovince" size="1">
		<option value="<?php echo $reg_value; ?>" selected><?php echo $fc_regis_by; ?></option>
		<?php
		$query_province=pg_query("select * from \"nw_province\" where \"proName\" != '$fc_regis_by' order by \"proID\"");
		while($res_pro = pg_fetch_array($query_province)){
		?>
		<option value="<?php echo $res_pro["proName"];?>" <?php if($res_pro["proName"]==$fc_regis_by){?>selected<?php }?>><?php echo $res_pro["proName"];?></option>
		<?php
		}
		?>
	</select>
	</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>สี</td>
    <td><input type="text" name="f_carcolor" value="<?php echo $fc_color; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>เลขไมล์</td>
    <td><input type="text" name="f_carmi" value="<?php echo $fc_mi;?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>วันที่ต่ออายุภาษี</td>
    <td><input type="text" name="f_exp_date" value="<?php echo $fc_expert; ?>" />
      <input name="button22" type="button" onclick="displayCalendar(document.frm_edit.f_exp_date,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style="background-color:#FFFFCC;">แก้ไขข้อมูลสัญญา</td>
    </tr>
  <tr>
    <td>วันที่ทำสัญญา</td>
    <td><input type="text" name="f_pstdate" value="<?php echo $fp_stdate; ?>" />
      <input name="button2" type="button" onclick="displayCalendar(document.frm_edit.f_pstdate,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>ค่างวดไม่รวม vat </td>
    <td><input type="text" name="f_pmonth" value="<?php echo $fp_pmonth; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>vat ค่างวด </td>
    <td><input type="text" name="f_pvat" value="<?php echo $fp_pvat; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>จำนวนงวด</td>
    <td><input type="text" name="f_ptotal" value="<?php echo $fp_ptotal; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>ดาวน์ไม่รวม vat </td>
    <td><input type="text" name="f_pdown" value="<?php echo $fp_pdown; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>vat เงินดาวน์ </td>
    <td><input type="text" name="f_vatofdown" value="<?php echo $fp_pvatofdown; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>เงินต้นลูกค้า</td>
    <td><input type="text" name="f_pbegin" value="<?php echo $fp_begin; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>เงินต้นทางบัญชี</td>
    <td><input type="text" name="f_pbeginx" value="<?php echo $fp_beginx; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>วันที่งวดแรก</td>
    <td><input name="f_startDate" type="text" readonly="true" value="<?php echo $fp_fdate; ?>"/> <input name="button" type="button" onclick="displayCalendar(document.frm_edit.f_startDate,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>ปีทางบัญชีที่ทำสัญญา</td>
    <td><input type="text" name="f_year_acc" value="<?php echo $fp_cusby_year; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="preview" type="button" value="ตัวอย่างก่อนบันทึก" /></td>
    <td><input name="submit" type="submit" value="NEXT" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
