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
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
<script>
function data_change(field)
     {
          var check = true;
          var value = field.value; //get characters
          //check that all characters are digits, ., -, or ""
          for(var i=0;i < field.value.length; ++i)
          {
               var new_key = value.charAt(i); //cycle through characters
               if(((new_key < "0") || (new_key > "9")) && 
                    !(new_key == ""))
               {
                    check = false;
                    break;
               }
          }
     
     }




function validate() 
{

 var theMessage = "Please complete the following: \n-----------------------------------\n";
 var noErrors = theMessage





if (document.frm_edit.f_name.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ชื่อ";
}

if (document.frm_edit.f_surname.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ นามสกุล";
}
if (document.frm_edit.f_no.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ บ้านเลขที่";
}


if (document.frm_edit.f_subno.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ หมู่ที่";
}


if (document.frm_edit.f_soi.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ซอย";
}

if (document.frm_edit.f_rd.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ถนน";
}

if (document.frm_edit.f_aum.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ แขวง/ตำบล";
}


if (document.frm_edit.f_tum.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ เขต/อำเภอ";
}

if (document.frm_edit.f_carname.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ยี่ห้อรถ";
}


if (document.frm_edit.f_caryear.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ รุ่นปี";
}

if (document.frm_edit.f_carnum.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ เลขตัวถัง";
}

if (document.frm_edit.f_carmar.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ เลขเครื่องยนต์";
}

if (document.frm_edit.f_carregis.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่  ทะเบียน";
}

if (document.frm_edit.f_carcolor.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ สีรถ";
}

if (document.frm_edit.f_carmi.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ เลขไมล์";
}

if (document.frm_edit.f_exp_date.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ วันต่อภาษี";
}




if (document.frm_edit.f_year_acc.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ปีทางบัญชี";
}

if (document.frm_edit.f_san.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ สัญชาติ";
}


if (document.frm_edit.f_age.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ อายุ";
}





if (document.frm_edit.f_card.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ บัตรแสดงตัว";
}


if (document.frm_edit.f_cardid.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ เลขที่บัตร";
}


if (document.frm_edit.f_datecard.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ วันที่ออกบัตร";
}



if (document.frm_edit.f_card_by.value=="") {
theMessage = theMessage + "\n -->  กรุณาใส่ ผู้ออกบัตร";
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
.style6 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
<!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:60px; padding-left:10px;">
  <?php
  include("../config/config.php");
  //$gp_cusid=pg_escape_string($_GET["p_cusid"]); 
         //$gp_cusname=pg_escape_string($_GET["p_cusname"]);
	       $srcid=trim(pg_escape_string($_POST["idno_names"]));
		   
		   if(empty($srcid))
		   {
		    $edt_idno=pg_escape_string($_GET["idnog"]);
		   }
		   else
		   {
		   
           $subid=explode(":",$srcid);		 
		 
		   $edt_idno=$subid[0];
		   }
		   echo  $srcid."<br>";
		 echo $edt_idno;
		 
         
  $qry_fp=pg_query("select * from \"Fp\" where (\"IDNO\" ='$edt_idno') ");
  $res_fp=pg_fetch_array($qry_fp);
  
  if(empty($res_fp["IDNO"]))
  {
    echo "LOCKED "."<br>";
	echo $resback="<input type=\"button\" value=\"BACK\" onclick=\"javascript:history.back()\"  />";
  }
  else
  {

  
  
  $fp_cusid=trim($res_fp["CusID"]);
  $fp_carid=trim($res_fp["asset_id"]);
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
    <form name="frm_edit" method="post" action="edit_acc.php" onsubmit="return validate(this);">
	  <input type="hidden" name="fidno" value="<?php echo $edt_idno; ?>" />
	  <input type="hidden" name="fcus_id" value="<?php echo $fp_cusid; ?>" />
	  <input type="hidden" name="fcar_id" value="<?php echo $fp_carid; ?>" />
	  <table width="785" border="0" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="3" style="background-color:#FFFFCC;">ข้อมูลผู้ทำสัญญา</td>
	<?php
	  $qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\" ='$fp_cusid' ");
	  $res_fa1=pg_fetch_array($qry_fa1);
	  $fa1_cusid=trim($res_fa1["CusID"]);
      $fa1_firname=trim($res_fa1["A_FIRNAME"]);
	  $fa1_name=trim($res_fa1["A_NAME"]);
	  $fa1_surname=trim($res_fa1["A_SIRNAME"]);
	  $fa1_pair=trim($res_fa1["A_PAIR"]);
	  $fa1_no=trim($res_fa1["A_NO"]);
	  $fa1_subno=trim($res_fa1["A_SUBNO"]);
      $fa1_soi=trim($res_fa1["A_SOI"]);
	  $fa1_rd=trim($res_fa1["A_RD"]);	
	  $fa1_tum=trim($res_fa1["A_TUM"]);	
	  $fa1_aum=trim($res_fa1["A_AUM"]);
	  $fa1_pro=trim($res_fa1["A_PRO"]);	
	  $fa1_post=trim($res_fa1["A_POST"]);
	  
	  
	  $qry_Fn=pg_query("select * from \"Fn\" where \"CusID\" ='$fp_cusid' ");
	  $res_fn1=pg_fetch_array($qry_Fn);
	?>
    </tr>
 
  <tr>
    <td width="144">ชื่อ-นามสกุล</td>
    <td><?php echo $fa1_name." ".$fa1_surname; ?></td>
    <td>เลขที่สัญญา <?php echo $edt_idno; ?></td>
    </tr>
  <?php	
  $qry_cc=pg_query("select * from \"ContactCus\" where \"IDNO\" ='$edt_idno' ");
  $res_cc=pg_fetch_array($qry_cc);
  $residno_cc=$res_cc["IDNO"];
   if(empty($residno_cc))
    {
  ?>
   <?php
    }
	else
	{
	 $qry_fn=pg_query("select A.*,C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\",C.\"CusID\" 
	                   from \"ContactCus\" A
                       LEFT OUTER JOIN \"Fa1\" C on C.\"CusID\" = A.\"CusID\" 
					   where A.\"IDNO\"='$edt_idno' AND \"CusState\"!=0 order by \"CusState\" ");
					      
      while($res_fn=pg_fetch_assoc($qry_fn))
      {
	   $fullname=trim($res_fn["A_FIRNAME"])." ".trim($res_fn["A_NAME"])." ".trim($res_fn["A_SIRNAME"]);
	   $a++;                 
   ?>
	<?php
	}
   ?>
   <?php
     }
   ?>
  <tr>
    <td colspan="3" style="background-color:#FFFFCC;">ข้อมูลรถแท็กซี่</td>
	<?php 
	 $qry_car=pg_query("select *,to_char(\"C_TAX_ExpDate\", 'YYYY-MM-DD') AS exp_date from \"VCarregistemp\" where \"IDNO\" ='$edt_idno' ");
	 $res_fc=pg_fetch_array($qry_car);
	 $fc_carid=trim($res_fc["CarID"]);
	 $fc_name=trim($res_fc["C_CARNAME"]);
	 $fc_year=trim($res_fc["C_YEAR"]);
	 $fc_regis=trim($res_fc["C_REGIS"]);
	 
	 $fcs_regis_by=trim($res_fc["C_REGIS_BY"]);
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
	 
	 
	 $fc_color=trim($res_fc["C_COLOR"]);
	 $fc_num=trim($res_fc["C_CARNUM"]);
	 $fc_mar=trim($res_fc["C_MARNUM"]);
	 $fc_mi=trim($res_fc["C_Milage"]);
	 
	 $fc_expert=trim($res_fc["exp_date"]);
	 
	 
	 $fc_mon=trim($res_fc["C_TAX_MON"]);

	 
	 
	 
	?>
    </tr>
  <tr>
    <td>ยี่ห้อรถ</td>
    <td width="198"><?php echo $fc_name; ?></td>
    <td width="385">รุ่นปี
      <?php echo $fc_year; ?></td>
  </tr>
  
  <tr>
    <td>ทะเบียน</td>
    <td><?php echo $fc_regis; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style="background-color:#FFFFCC;">ข้อมูลสัญญา</td>
    </tr>
  <tr>
    <td>วันที่ทำสัญญา</td>
    <td><?php echo $fp_stdate; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>จำนวนงวด</td>
    <td><?php echo $fp_ptotal; ?></td>
    <td>ค่างวดรวม vat <?php echo $fp_pmonth+$fp_pvat; ?></td>
    </tr>
  <tr>
    <td>ดาวน์รวม vat </td>
    <td><?php echo $fp_pdown+$fp_pvatofdown; ?></td>
    <td>วันที่งวดแรก
      <?php echo $fp_fdate; ?></td>
  </tr>
  <tr>
    <td>เงินต้นลูกค้า</td>
    <td><?php echo $fp_begin; ?></td>
    <td>ปีทางบัญชีที่ทำสัญญา
      <?php echo $fp_cusby_year; ?></td>
    </tr>

  <tr>
    <td>เงินต้นทางบัญชี</td>
    <td><input type="text" name="f_pbeginx" value="<?php echo $fp_beginx; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td><input name="submit" type="submit" value="SAVE" /></td>
    <td><input type="button" value="BACK" onclick="window.location='frm_accidno.php'" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<?php
 }
?>
<div style="height:300px; overflow:auto;"> 

<table width="95%" border="0" style="background-color:#999999;" cellpadding="1" cellspacing="1">
<?php 
$qry_cpm=pg_query("select * from \"AccPayment\" where \"IDNO\"='$edt_idno' ");
$numr=pg_num_rows($qry_cpm);
if($numr==0)
{
?>
 <tr style="background-color:#FDE2AC">
    <td colspan="6">ยังไม่ได้สร้างข้อมูล Accpayment</td>
  </tr>
<?php
}
else
{
?>  
  <tr style="background-color:#EEF2DB;">
    <td colspan="6">ตารางแสดง Accpayment </td>
  </tr>
  
	
 
  <tr style="background-color:#D0DCA0">
    <td width="106">DueNo</td>
    <td width="110">DueDate</td>
    <td width="110">Remine</td>
    <td width="152">Priciple</td>
    <td width="125">Interest</td>
    <td width="143">AccuInt</td>
  </tr>
  
   <?php
  while($rescus=pg_fetch_array($qry_cpm))
  {
  ?>	
  <tr style="background-color:#EEF2DB">
    <td width="106"><?php echo $rescus["DueNo"]; ?></td>
    <td width="110"><?php echo $rescus["DueDate"]; ?></td>
    <td width="110" style="text-align:right;"><?php echo number_format($rescus["Remine"],2); ?></td>
    <td width="152" style="text-align:right;"><?php echo number_format($rescus["Priciple"],2); ?></td>
    <td width="125" style="text-align:right;"><?php echo number_format($rescus["Interest"],2); ?></td>
    <td width="143" style="text-align:right;"><?php echo number_format($rescus["AccuInt"],2); ?></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 <?php
 }
 ?> 
</table>
</div>
<div style="height:50px;">
</div>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
