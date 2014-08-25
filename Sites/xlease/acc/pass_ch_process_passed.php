<?php
include("../config/config.php");
session_start();
$id_user=$_SESSION["av_iduser"];
$dateqry=pg_escape_string($_POST["qryDate"]);
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
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

 #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
    .td {
		padding:0px 3px 0px 3px;
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
<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  
   <b>Cheque Pass</b><hr />
   
   <table width="800" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
  <tr style="background-color:#EBFB91;">
    <td colspan="7">Result วันที่  <?php echo $dateqry; ?>
      <input type="button" value="BACK" onclick="window.location='receipt_ch_enterbank.php'"  /></td>
    </tr>

  <?php
   
 // echo "T"."<br>";
 //print_r($chose_listss=pg_escape_string($_POST["chose_lists"]));
    
	$chose_lists=$_POST["chose_lists"];
  	
	
	$dateqq=pg_escape_string($_POST["qryDate"]); 
	 
   	for ($i=0;$i<count($chose_lists);$i++) 
	{ 
		$In_nos=$chose_lists[$i];
	    $In_no=trim($In_nos);
     
	 //Update date
  
      $qry_chq=pg_query("select * from \"FCheque\"  WHERE \"ChequeNo\" ='$In_no'");
	  $res_cc=pg_fetch_array($qry_chq);
	  
	  $pid=$res_cc["PostID"];
    
	
	
     $re_accno=pg_query("select accno_for_cheque_enter('$pid', '$In_no')");
	 $res_accno=pg_fetch_result($re_accno,0);
	 
   

	 
	$update_d="update \"FCheque\" SET   \"DateEnterBank\"='$dateqq',\"AccBankEnter\"='$res_accno'
	WHERE \"ChequeNo\"='$In_no' and \"IsReturn\" = 'false' and \"IsPass\" = 'false' and \"Accept\" = 'true'";

	if($result=pg_query($update_d)){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) นำเช็คเข้าธนาคาร', '$add_date')");
		//ACTIONLOG---
		$status ="Update ข้อมูลแล้ว";
	}else{
		$status ="error Update  fuser ".$update_d;
	}
   
     //end update
   
  } 
  $nowdate=date("Y-m-d"); 


  $qrybb=pg_query("select DISTINCT  \"AccBankEnter\" from \"FCheque\" 
                  where \"DateEnterBank\"='$dateqq'
				  ");
  while($resbb=pg_fetch_array($qrybb))
  {
  
   $abb=$resbb["AccBankEnter"];
  

  $qry_bk=pg_query("select * from bankofcompany where accno='$abb'");
  while($res_bk=pg_fetch_array($qry_bk))
  {
    $accbk=$res_bk["accno"];
    $accs=$res_bk["bankname"];
	$accbr=$res_bk["bankbranch"];
  ?> 
   <tr style="background-color:#DDE6B7">
    <td colspan="7">      ธนาคาร <?php echo "  ".$accs."  ".$accbr; ?></td>
    </tr>
    
  <tr style="background-color:#EEF2DB">
    <td width="25">No.</td>
    <td width="101">ChequeNo</td>
    <td width="147">Bank Name </td>
    <td width="149">BankBranch</td>
    <td width="110">date on
      cheque</td>
    <td width="115"><div align="center">dateEnter
      Bank</div></td>
    <td width="113"><div align="center">AmtOnCheque</div></td>
  </tr>

  <?php
  $qry_chqs=pg_query("select A.*,B.*,C.* from \"FCheque\"  A
                     LEFT OUTER JOIN \"BankCheque\" B ON A.\"BankName\"=B.\"BankCode\"
					 LEFT OUTER JOIN bankofcompany C ON A.\"AccBankEnter\"=C.accno
                     WHERE (\"DateEnterBank\"='$dateqq') AND (A.\"AccBankEnter\"='$accbk')");
					 
  $numr=pg_num_rows($qry_chqs);
  if($numr==0)
  {
  ?>
    <td colspan="7">Result
     ไม่พบข้อมูลของ ธนาคาร <?php echo $accbk."  ".$accs."  ".$accbr; ?></td>
    </tr> 
  <?php
  }
  else
  {
  
  $n=0; 
  		
  $sumamt=0;			
  $qry_chq=pg_query("select A.*,B.*,C.* from \"FCheque\"  A
                     LEFT OUTER JOIN \"BankCheque\" B ON A.\"BankName\"=B.\"BankCode\"
					 LEFT OUTER JOIN bankofcompany C ON A.\"AccBankEnter\"=C.accno
                     WHERE (\"DateEnterBank\"='$dateqq') AND (A.\"AccBankEnter\"='$accbk')");
					 		 
  while($res_chq=pg_fetch_array($qry_chq))
  {		
   
    
   
    $n++; 			
	$cq_no=$res_chq["ChequeNo"];
	$p_id=$res_chq["PostID"];
	$ch_date=$res_chq["DateOnCheque"];
	
   ?>  
  <tr style="background-color:#FFFFFF; font:bold;">
    <td ><?php echo $n; ?></td>
    <td ><?php echo $cq_no; ?></td>
    <td width="147"><?php echo $res_chq["BankName"]; ?></td>
    <td><?php echo $res_chq["BankBranch"]; ?></td>
    <td style="text-align:right;"><span style="text-align:center;"><?php echo $res_chq["DateOnCheque"]; ?></span></td>
    <td style="text-align:center; background-color:#C8DDF2;"><?php echo $res_chq["DateEnterBank"]; ?></span></td>
    <td style="text-align:right; background-color:#CEFF9D;"><?php echo number_format($res_chq["AmtOnCheque"],2); ?></td>
  </tr>
 <?php
     $sumamt=$sumamt+$res_chq["AmtOnCheque"];
    }
  ?>
  <tr style="background-color:#DFF4F7;">
  <td colspan="6">รวมทั้งหมด  <?php echo $n; ?> รายการ</td>
    <td style="text-align:right; background-color:#FDF7DF;"><?php echo number_format($sumamt,2); ?></td>
  </tr>
	
  <?php
   
    }
   }
   }
  ?>	
</table>

<br />
<div style="margin-left:auto; margin-right:auto; width:300px; text-align:center;"><button onclick="window.location='report_passchequq_pdf.php?qdate=<?php echo $dateqry; ?>'">Print PDF</button></div>
  </div>

<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
