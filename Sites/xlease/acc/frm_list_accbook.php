<?php
session_start();
set_time_limit(0);
$se_book=pg_escape_string($_POST["select_book"]);
$se_year=pg_escape_string($_POST["year_select"]);
$se_mount=pg_escape_string($_POST["mount"]);

if($se_mount ==""){
	$sentmonth="";
	$sentmonth2="";
}else{
	$sentmonth="&i_mount=$se_mount";
	$sentmonth2="AND (EXTRACT(MONTH FROM \"acb_date\")='$se_mount' )";
}
include("../config/config.php");

if($se_book=="ALL")
{
  echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_all_sel_month.php?i_year=$se_year$sentmonth\">";
  

}


else if($se_book=="AJ")
{

$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2  AND (type_acb='$se_book') ORDER BY acb_date";
$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='$se_book') ORDER BY acb_date";
}


else if($se_book=="AP")
{
 $sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') ORDER BY acb_date";
$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') ORDER BY acb_date";
}



else if($se_book=="AP-BR")
{
 $sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') AND (ref_id LIKE 'BR%') ORDER BY acb_date";
$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') ORDER BY acb_date";
}


else if($se_book=="AP-RE")
{
 $sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') AND (ref_id LIKE 'RE%') ORDER BY acb_date";
$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') ORDER BY acb_date";
}


else if($se_book=="AP-PSL")
{
 $sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') AND (ref_id LIKE 'PSL%') ORDER BY acb_date";
$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') ORDER BY acb_date";
}

else if($se_book=="AP-BSAL")
{
 $sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') AND (ref_id LIKE 'BSAL%') ORDER BY acb_date";
$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') ORDER BY acb_date";
}

else if($se_book=="AP-VATS")
{
 $sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') AND (ref_id LIKE 'VATS%') ORDER BY acb_date";
$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='AP') ORDER BY acb_date";
}

else if($se_book=="AP-VATB")
{
 $sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='GJ') AND (ref_id LIKE 'VATB%') ORDER BY acb_date";
$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='GJ') ORDER BY acb_date";
}


else
{

$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='$se_book') ORDER BY acb_date";
$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) $sentmonth2 AND (type_acb='$se_book') ORDER BY acb_date";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
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
  <table width="758" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">
  <tr>
    <td width="84">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr style="background-color:#FFFFFF;">
    <td>วันที่</td>
    <td width="101">รหัสบัญชี</td>
    <td>ชื่อ</td>
    <td width="94"><div align="center">Dr</div></td>
    <td width="97"><div align="center">Cr</div></td>
  </tr>
  
  <?php
if($se_book!="ALL"){
  $sql_acb=pg_query($sql_acc);
  while($res_acb=pg_fetch_array($sql_acb))
  {
    $ss=$res_acb["acb_id"];
	
	
	$sql_ls=pg_query("select * from account.\"VAccountBook\" WHERE acb_id='$ss' order by \"AmtDr\" desc ");
	while($res_ls=pg_fetch_array($sql_ls))
	{
	  $sdetail=$res_ls["acb_detail"];
	  
	   $as_date=$res_ls["acb_date"];
		$trn_date=pg_query("select * from c_date_number('$as_date')");
	    $a_date=pg_fetch_result($trn_date,0);
	  
	  $exp_dtl=str_replace("\n","#",$sdetail);
	  $ep_dtl=explode("#",$exp_dtl);
	 
  ?> 
    <tr style="background-color:#EDF1DA">
    <td style="padding:3px;"><?php echo $a_date; ?></td>
    <td style="padding:3px;"><?php echo $res_ls["AcID"]; ?></td>
    <td style="padding:3px;"><?php echo $res_ls["AcName"]; ?></td>
    <td style="text-align:right; padding-right:3px;"><?php echo number_format($res_ls["AmtDr"],2); ?></td>
    <td style="text-align:right; padding-right:3px;"><?php echo number_format($res_ls["AmtCr"],2); ?></td>
  </tr>
   <?php
     
	 $total_str=count($ep_dtl);
	 	 
	 
		for($i=$total_str-1;$i<$total_str;$i++)
		{
		  $res_i=$ep_dtl[$i];
		} 
	 
   }
   ?>
  
   <tr style="background-color:#EBFB91;">
    <td colspan="5"><div align="center">
      <b><?php echo $ss."-".$res_i; ?></b></div></td>
    </tr>
   <?php
   }
 }  
   ?>	
   
   
   
   
   <tr style="background-color:#FFFFFF; padding:3px;">
    <td colspan="4" style="padding:3px;"><div align="center">
      <button onclick="window.location='frm_select_acc.php'">BACK</button></div></td>
    <td style="padding:3px;"><button onclick="window.open('report_pdf_accbook.php?qry1=<?php echo $se_book;?>&qry2=<?php echo $se_year; ?>&qry3=<?php echo $se_mount;?>')">PDF</button></td>
   </tr>
</table>

  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
