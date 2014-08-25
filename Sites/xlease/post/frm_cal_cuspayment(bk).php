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

<body style="background-color:#ffffff; margin-top:0px;">
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
$idno=$_POST["a_id"];
  
  
$stdate=$_POST["h_start"];
$endate=$_POST["end_date"];
$s_payment=$_POST["payment"];
$f_date=$_POST["start_date"];
$ldate=$_POST["last_date"];
$fullname=$_POST["a_fullname"]
  
  /*
   echo  //$gp_cusid=$_GET["p_cusid"]; 
         //$gp_cusname=$_GET["p_cusname"];
		 
		 $edt_idno=$_POST["idno_names"];
		 
  $qry_fp=pg_query("select * from \"Fp\" where \"IDNO\" ='$edt_idno'");
  $res_fp=pg_fetch_array($qry_fp);
  

  
  
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
   */
  ?>
  <table width="780" border="0" style="font-size:x-small; background-color:#CCCCCC;" cellspacing="1">
  <tr>
    <td colspan="8">IDNO = <?php echo $idno; ?></td>
    </tr>
  <tr>
    <td colspan="4">ชื่อ - นามสกุล <?php echo $fullname; ?></td>
    <td width="100">&nbsp;</td>
    <td width="122">&nbsp;</td>
    <td colspan="2">ค่างวด = <?php echo number_format($s_payment,2); ?></td>
    </tr>
	<tr style="background-color:#EBFB91;">
    <td colspan="8"><div align="center">ตารางแสดงการชำระค่างวด </div></td>
    </tr>
  <tr style="background-color:#EEEDCC">
    <td width="36">DueNo.</td>
    <td width="83">DueDate<br />
      (วันนัดจ่าย)</td>
    <td width="117">R_Date<br />
      (วันทีี่จ่าย)</td>
    <td width="81">daydelay<br />
      (วันจ่ายล่าช้า)</td>
    <td>caldelay<br />
      (ยอดจ่ายล่าช้า)</td>
    <td>R_Receipt<br />
      (เลขที่ใบเสร็จ)</td>
    <td width="112">V_Receipt<br />
      (เลขที่ใบvat)</td>
    <td width="95">V_date<br />
      (วันที่จ่ายvat)</td>
    </tr>
  
  <?php
   $qry_before=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno')  AND (\"DueDate\" BETWEEN '$f_date' AND '$stdate')  ");
      $n_rowbf=pg_num_rows($qry_before);
      $n_bf=$n_rowbf-1;
    for($i=0;$i<($n_bf);$i++) 
     {
      $resbf=pg_fetch_array($qry_before);
  ?> 
  <tr style="background-color:#33FF33;">
    <td style="padding-left:3px;"><?php echo $resbf["DueNo"]; ?></td>
    <td style="padding-left:5px;"><?php echo $dd_datebf=$resbf["DueDate"]; ?></td>
    <td style="padding-left:5px;"><?php echo $resbf["R_Date"]; ?></td>
    <td style="padding-left:5px; text-align:right;"><?php echo $resbf["daydelay"]; ?></td>
    <td style="text-align:right;"><?php ?>	</td>
    <td style="padding-left:5px;"><?php echo $resbf["R_Receipt"]; ?></td>
    <td style="padding-left:5px;"><?php echo $resbf["V_Receipt"]; ?></td>
    <td style="padding-left:5px;"><?php echo $resbf["V_Date"]; ?></td>
    </tr>
  <?php
    }
  ?>
  
  <?php
  $amtpay=0;
   $qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno')  AND (\"DueDate\" BETWEEN '$stdate' AND '$endate')  ");
   while($resvc=pg_fetch_array($qry_vcus))
   {
   
  ?> 
  <tr style="background-color:#F0FCAB;">
    <td style="padding-left:3px;"><?php echo $resvc["DueNo"]; ?></td>
    <td style="padding-left:5px;"><?php echo $dd_date=$resvc["DueDate"]; ?></td>
    <td style="padding-left:5px;"><?php echo $resvc["R_Date"]; ?></td>
    <td style="padding-left:5px; text-align:right;"><?php echo $resvc["daydelay"]; ?></td>
    <td style="text-align:right;"><?php 
	                                    $calamt="select \"CalAmtDelay\"('$endate','$dd_date',$s_payment)";
										
									    $res_amt=pg_query($db_connect,$calamt);
									
									echo  number_format($res_samt=pg_fetch_result($res_amt,0),2);
										
	                                 
								   ?>	</td>
    <td style="padding-left:5px;"><?php echo $resvc["R_Receipt"]; ?></td>
    <td style="padding-left:5px;"><?php echo $resvc["V_Receipt"]; ?></td>
    <td style="padding-left:5px;"><?php echo $resvc["V_Date"]; ?></td>
    </tr>
  <?php
     $amtpay=$amtpay+$s_payment;
     $x_calamt=$x_calamt+$res_samt;
  }
   
  
  ?>
  
    <?php
	$ssD=$endate;
	$sday=substr($ssD,8,2);
	$smonth=substr($ssD,5,2);
	$syear =substr($ssD,0,4);
	
    $af_sdate=date("Y-m-d" ,mktime(0,0,0,$smonth+1,$sday,$syear));
	
	//$resss=$syear."-".$smonth."-".$sday;
	
   $qry_l=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno')  AND (\"DueDate\" BETWEEN '$af_sdate' AND '$ldate')  ");
      $n_rowl=pg_num_rows($qry_l);
       $n_l=$n_rowl;
    for($i=0;$i<($n_l);$i++) 
     {
      $resl=pg_fetch_array($qry_l);
  ?> 
  <tr style="background-color:#EEF2DB;">
    <td style="padding-left:3px;"><?php echo $resl["DueNo"]; ?></td>
    <td style="padding-left:5px;"><?php echo $dd_datel=$resl["DueDate"]; ?></td>
    <td style="padding-left:5px;"><?php echo $resl["R_Date"]; ?></td>
    <td style="padding-left:5px; text-align:right;"><?php echo $resl["daydelay"]; ?></td>
    <td style="text-align:right;"><?php ?>	</td>
    <td style="padding-left:5px;"><?php echo $resl["R_Receipt"]; ?></td>
    <td style="padding-left:5px;"><?php echo $resl["V_Receipt"]; ?></td>
    <td style="padding-left:5px;"><?php echo $resl["V_Date"]; ?></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
   <tr>
    <td colspan="4" style="text-align:right; background-color:#EEF2DB;">ยอดจ่ายล่าช้าตั้งแต่ <?php echo $stdate; ?> ถึง <?php echo $endate; ?></td>
    <td style="text-align:right; background-color:#E3E7AB;"><?php echo number_format($x_calamt,2); ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
	<tr>
    <td colspan="4" style="text-align:right;background-color:#EEF2DB;">ยอดจ่ายค่างวด <?php echo $stdate; ?> ถึง <?php echo $endate; ?></td>
    <td style="text-align:right; background-color:#E3E7AB;"><?php echo number_format($amtpay,2); ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
   <tr>
     <td colspan="4" style="background-color:#EEF2DB;"><div align="right">รวมยอด</div></td>
     <td style="text-align:right; background-color:#EBFB91;"><?php echo number_format($amtpay+$x_calamt,2) ?></td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     </tr>
   <tr>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td><input type="button" onclick="javascript:history.back(-2);" value="BACK" /></td>
     <td>&nbsp;</td>
     </tr>
</table>

  
  </div>
  
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
