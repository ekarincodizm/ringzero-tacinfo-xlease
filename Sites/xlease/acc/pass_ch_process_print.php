<?php
session_start();
$id_user=$_SESSION["av_iduser"];
$dateqry=pg_escape_string($_POST["qryDate"]);
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>




<title><?php echo $_SESSION["session_company_name"]; ?></title>

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
   
   <table width="782" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
  <tr style="background-color:#EBFB91;">
    <td colspan="5">พิมพ์่เช็ค  
      <input type="button" value="BACK" onclick="window.location='receipt_ch.php'"  /></td>
    </tr>

  <tr style="background-color:#EEF2DB">
    <td width="131">ChequeNo.</td>
    <td width="230">Bank Name </td>
    <td width="183">BankBranch</td>
    <td width="131">AmtOnCheque</td>
    <td width="91">status</td>
  </tr>
  <?php
   include("../config/config.php");
   
   
   
   
  $nowdate=date("Y-m-d"); 


  $qry_chq=pg_query("select A.*,B.* from \"FCheque\"  A
                     LEFT OUTER JOIN \"BankCheque\" B ON A.\"BankName\"=B.\"BankCode\"
                     WHERE \"DateOnCheque\" ='$dateqry'  ");
  while($res_chq=pg_fetch_array($qry_chq))
  {		
  
  
    $n++; 			
	$cq_no=$res_chq["ChequeNo"];
	$p_id=$res_chq["PostID"];
	$ch_date=$res_chq["DateOnCheque"];
	
	 
	if($ch_date >= $nowdate)
	{ 
	  $tr="วันเกิน";
	}
	else
	{
	  $tr="ทำรายการได้";
	   
	    $ch_pass=$res_chq["IsPass"];
		if($ch_pass=='t')
		{
		  $bt_pass="";
		}
		else
		{
		
		
		$bt_pass="<input type=\"button\" value=\"pass\" 
				   onclick=\"window.location='pass_ch_process.php?icno=$cq_no&postid=$p_id&userid=$id_user' \">";
		}
	
	}	
  ?>  
  <tr style="background-color:#E8EE66; font:bold;">
    <td ><?php echo $n; ?><?php echo $cq_no; ?></td>
    <td width="230"><?php echo $res_chq["BankName"]; ?></td>
    <td><?php echo $res_chq["BankBranch"]; ?></td>
    <td style="text-align:right;"><?php echo number_format($res_chq["AmtOnCheque"],2); ?></td>
    <td style="text-align:center; background-color:#99FF99;"><!-- <button onclick="toggleContent()">Toggle</button> -->
	<?php echo $bt_pass; ?></td>
  </tr>
 
  <tr style="background-color:#DFF4F7;">
  <td colspan="5">
  <div>
   <?php
   $a=0;
   $qry_dc=pg_query("select A.*,B.*,C.* from \"DetailCheque\" A 
                     LEFT OUTER JOIN \"VContact\" B ON A.\"IDNO\"=B.\"IDNO\"
					 LEFT OUTER JOIN \"TypePay\" C ON A.\"TypePay\"=C.\"TypeID\"
                     WHERE  A.\"ChequeNo\"='$cq_no'  
				    ");
	while($res_dc=pg_fetch_array($qry_dc))
	{
	  $a++;
	  
	    $ptype=$res_dc["TName"];
	  if($res_dc["C_REGIS"]=="")
		{
		
		$rec_regis=$res_dc["car_regis"];
			
		
		}
		else
		{
		
		$rec_regis=$res_dc["C_REGIS"];
		}
	  
	  
  			
       echo  "- เช็คที่"." ".$a."  IDNO : ".$res_dc["IDNO"]."  ชื่อ : ".$res_dc["full_name"]."     ทะเบียน : ".$rec_regis."&nbsp;&nbsp;&nbsp;     ชำระค่า :".$ptype."&nbsp;&nbsp;&nbsp;  ยอดเช็ค : ".number_format($res_dc["CusAmount"],2)."<br>"; 
	}   
    ?>
  <div>   </td>
    </tr>
	
  <?php
    
   }
  ?>	
</table>


  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
