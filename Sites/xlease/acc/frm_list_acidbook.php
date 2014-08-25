<?php
session_start();
$se_book=pg_escape_string($_POST["select_book"]);
$se_year=pg_escape_string($_POST["year_select"]);
include("../config/config.php");
set_time_limit(90);

  $f_year=pg_escape_string($_POST["se2_year"]);
  $f_acid=pg_escape_string($_POST["acid_id"]);
  
  $se_year=$f_year;
  $se_book=$f_acid;
  $se_mount=pg_escape_string($_POST["mount"]);
  
 if($se_mount ==""){
	$sentmonth2="";
}else{
	$sentmonth2="AND (EXTRACT(MONTH FROM \"acb_date\")='$se_mount' )";
}
  
  $qry_ac=pg_query("select \"AcName\",\"AcID\" from account.\"AcTable\" where \"AcID\"='$f_acid'");
  $res_name=pg_fetch_array($qry_ac);
  
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
<!-- InstanceBeginEditable name="head" -->
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
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
  <table width="758" border="0" cellpadding="1" cellspacing="1" style="background-color:#DEE7BE; font-size:small;">
  <tr>
    <td colspan="5"><align="center"><?php echo $f_acid."  ".$res_name["AcName"]; ?></td>
    </tr>
  <tr style="background-color:#FFFFFF;">
    <td width="123">วันที่</td>
    <td width="280"><div align="left">เลขที่รายการ</div></td>
    <td width="108"><div align="center">Dr</div></td>
    <td width="98"><div align="center">Cr</div></td>
    <td width="133"><div align="center">BL</div></td>
  </tr>
  
  <?php
  
  //--- หายอดยกมาจากเดือนก่อนหน้า
	$total_bl=0;
	if($se_mount != "" && $se_mount != "01")
	{
		$se_mount_old = $se_mount - 1;
		//$len = strlen($se_mount_old);
		
		for($old = 1 ; $old <= $se_mount-1 ; $old++)
		{
			$len = strlen($old);
			if($len == 1)
			{
				$old = "0".$old;
			}
			$sentmonth2_old="AND (EXTRACT(MONTH FROM \"acb_date\")='$old' )";
		
			if($old == "01") // ถ้าเป็นเดือนแรกเอายอดยกมาจาก database ด้วย
			{
				$sql_acid_old=pg_query("select * from account.\"VAccountBook\" WHERE  (EXTRACT(YEAR FROM \"acb_date\")='$f_year') $sentmonth2_old and (\"AcID\"='$f_acid') and (type_acb!='ZZ') ORDER BY  \"AcID\",acb_date,type_acb,acb_id ");
			}
			else // ถ้าไม่ใช้เดือนแรก ไม่ต้องยอดยกมาจาก database จะคำนวนด้วย code แทน
			{
				$sql_acid_old=pg_query("select * from account.\"VAccountBook\" WHERE  (EXTRACT(YEAR FROM \"acb_date\")='$f_year') $sentmonth2_old and (\"AcID\"='$f_acid') and (type_acb!='ZZ') and (type_acb!='AA') ORDER BY  \"AcID\",acb_date,type_acb,acb_id ");
			}
			
			while($res_acb_old=pg_fetch_array($sql_acid_old))
			{
				$res_dr=$res_acb_old["AmtDr"];
				$res_cr=$res_acb_old["AmtCr"];
				$as_date=$res_acb_old["acb_date"];
	 
				$trn_date=pg_query("select * from c_date_number('$as_date')");
				$a_date=pg_fetch_result($trn_date,0);
	 
				if(($res_cr==0) and ($res_dr!=0))
				{
					$total_sum_bl=$total_bl+$res_dr;
				}
				else
				{
					$total_sum_bl=$total_bl-$res_cr;
				}
			
				$total_bl=$total_sum_bl;
			}
		}
		?>
		<tr style="background-color:#EDF1DA">
			<td style="padding:3px;"><?php echo "01-".$se_mount."-".($se_year+543); ?></td>
			<td style="padding:3px;">ยอดยกมา</td>
			<?php
				if($total_bl >= 0)
				{
			?>
					<td style="text-align:right; padding-right:3px;"><?php echo number_format($total_bl,2); ?></td>
					<td style="text-align:right; padding-right:3px;"><?php echo number_format(0,2); ?></td>
			<?php
				}
				else
				{
			?>
					<td style="text-align:right; padding-right:3px;"><?php echo number_format(0,2); ?></td>
					<td style="text-align:right; padding-right:3px;"><?php echo number_format($total_bl*-1,2); ?></td>
			<?php
				}
			?>
			<td style="text-align:right; padding-right:3px;"><?php echo number_format($total_bl,2); ?></td>
		</tr>
		<?php
	}
  //--- จบการหายอดยกมาจากเดือนก่อนหน้า

  
  //---------------------------------------------
  //$total_bl=0;  by deaw
  if($se_mount == "") // ถ้าไม่ได้เลือกเดือน
  {
	$total_sum_bl=0;
	$sql_acid=pg_query("select * from account.\"VAccountBook\" WHERE  (EXTRACT(YEAR FROM \"acb_date\")='$f_year') $sentmonth2 and (\"AcID\"='$f_acid') and (type_acb!='zz') ORDER BY  \"AcID\",acb_date,type_acb,acb_id ");
	while($res_acb=pg_fetch_array($sql_acid))
	{
		$res_dr=$res_acb["AmtDr"];
		$res_cr=$res_acb["AmtCr"];
		$as_date=$res_acb["acb_date"];
	 
		$trn_date=pg_query("select * from c_date_number('$as_date')");
		$a_date=pg_fetch_result($trn_date,0);
	 
	 
	 
		if(($res_cr==0) and ($res_dr!=0))
		{
			$total_sum_bl=$total_bl+$res_dr;
		}
		else
		{
			$total_sum_bl=$total_bl-$res_cr;
		}

		$total_bl=$total_sum_bl;
  ?> 
		<tr style="background-color:#EDF1DA">
		<td style="padding:3px;"><?php echo $a_date; ?></td>
		<td style="padding:3px;"><u><a href="#" onclick="MM_openBrWindow('detail_acid.php?i_acid=<?php echo $res_acb["acb_id"]; ?>','','width=600,height=300','scrollbars=yes')"><?php echo $res_acb["acb_id"]; ?></a></u></td>
		<td style="text-align:right; padding-right:3px;"><?php echo number_format($res_acb["AmtDr"],2); ?></td>
		<td style="text-align:right; padding-right:3px;"><?php echo number_format($res_acb["AmtCr"],2); ?></td>
		<td style="text-align:right; padding-right:3px;"><?php echo number_format($total_bl,2); ?></td>
		</tr>
   <?php
	}
  }
  else // ถ้าเลือกเดือน
  {
	$total_sum_bl=0;
	if($se_mount == "01") // ถ้าเป็นเดือนแรกเอายอดยกมาจาก database ด้วย
	{
		$sql_acid=pg_query("select * from account.\"VAccountBook\" WHERE  (EXTRACT(YEAR FROM \"acb_date\")='$f_year') $sentmonth2 and (\"AcID\"='$f_acid') and (type_acb!='ZZ') ORDER BY  \"AcID\",acb_date,type_acb,acb_id ");
	}
	else // ถ้าไม่ใช้เดือนแรก ไม่ต้องยอดยกมาจาก database จะคำนวนด้วย code แทน
	{
		$sql_acid=pg_query("select * from account.\"VAccountBook\" WHERE  (EXTRACT(YEAR FROM \"acb_date\")='$f_year') $sentmonth2 and (\"AcID\"='$f_acid') and (type_acb!='ZZ') and (type_acb!='AA') ORDER BY  \"AcID\",acb_date,type_acb,acb_id ");
	}
	
	while($res_acb=pg_fetch_array($sql_acid))
	{
		$res_dr=$res_acb["AmtDr"];
		$res_cr=$res_acb["AmtCr"];
		$as_date=$res_acb["acb_date"];
	 
		$trn_date=pg_query("select * from c_date_number('$as_date')");
		$a_date=pg_fetch_result($trn_date,0);
	 
	 
	 
		if(($res_cr==0) and ($res_dr!=0))
		{
			$total_sum_bl=$total_bl+$res_dr;
		}
		else
		{
			$total_sum_bl=$total_bl-$res_cr;
		}

		$total_bl=$total_sum_bl;
  ?> 
		<tr style="background-color:#EDF1DA">
		<td style="padding:3px;"><?php echo $a_date; ?></td>
		<td style="padding:3px;"><u><a href="#" onclick="MM_openBrWindow('detail_acid.php?i_acid=<?php echo $res_acb["acb_id"]; ?>','','width=600,height=300','scrollbars=yes')"><?php echo $res_acb["acb_id"]; ?></a></u></td>
		<td style="text-align:right; padding-right:3px;"><?php echo number_format($res_acb["AmtDr"],2); ?></td>
		<td style="text-align:right; padding-right:3px;"><?php echo number_format($res_acb["AmtCr"],2); ?></td>
		<td style="text-align:right; padding-right:3px;"><?php echo number_format($total_bl,2); ?></td>
		</tr>
   <?php
	}
  }
  //---------------------------------------------
   ?>
  
   <tr style="background-color:#EBFB91;">
    <td colspan="5"><div align="center">
     </div></td>
    </tr>

   
   
   <tr style="background-color:#FFFFFF; padding:3px;">
    <td colspan="3" style="padding:3px;"><div align="center">
      <button onclick="window.location='frm_select_acc.php'">BACK</button></div></td>
    <td colspan="2" style="padding:3px;"><button onclick="window.open('report_pdf_acid.php?qry1=<?php echo $se_book;?>&qry2=<?php echo $se_year; ?>&qry3=<?php echo $se_mount;?>&m_name=<?php echo $res_name["AcName"];?>')">PDF</button></td>
   </tr>
</table>

  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
