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

<script type="text/javascript">
function toggleContent() {
  // Get the DOM reference
  var contentId = document.getElementById("content");
  // Toggle 
  contentId.style.display == "block" ? contentId.style.display = "none" : 
contentId.style.display = "block"; 
}
</script>


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

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:60px; padding-left:10px;">
   
   <table width="782" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
  <tr style="background-color:#EBFB91;">
    <td colspan="8">รายการเช็ค ประจำวันที่ <?php echo $dateqry; ?> <input type="button" value="BACK" onclick="window.location='receipt_ch.php'"  /></td>
    </tr>

  <tr style="background-color:#EEF2DB">
    <td width="27">No.</td>
    <td width="90">Date<br />
EnterBank</td>
    <td width="117">ChequeNo.</td>
    <td width="117">Bank Name</td>
    <td width="142">BankBranch</td>
    <td width="81">Date<br />
OnCheque</td>
    <td width="110">AmtOnCheque</td>
    <td width="73">status</td>
  </tr>
  <?php
   include("../config/config.php");
  $nowdate=date("Y-m-d"); 
 
  if(empty($_POST["qryDate"]))
  {
    $dateqry=pg_escape_string($_GET["qryDate"]);
  }
  else
  {
    $dateqry=pg_escape_string($_POST["qryDate"]);
  }
  
  
  $qry_chq=pg_query("select A.* from \"FCheque\"  A
                     
                     WHERE  (\"Accept\"=TRUE) AND (\"IsReturn\"=FALSE) AND (\"DateEnterBank\" ='$dateqry')  ");
  while($res_chq=pg_fetch_array($qry_chq))
  {		
  
  
    $n++; 			
	$cq_no=$res_chq["ChequeNo"];
	$p_id=$res_chq["PostID"];
	$ch_date=$res_chq["DateEnterBank"];
	
	$pbname=$res_chq["BankName"];
	$pbbranch=trim($res_chq["BankBranch"]);
	$pamt=$res_chq["AmtOnCheque"];
	 
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

	$bt_pass="<input type=\"button\" value=\"pass\" onclick=\"window.location='pass_ch_processing.php?icno=$cq_no&postid=$p_id&userid=$id_user&bname=$pbname&bbranch=$pbbranch&amt=$pamt&qday=$dateqry'\">";
		
		}
	
	}	
  ?>  
  <tr style="background-color:#E8EE66; font:bold;">
    <td ><?php echo $n; ?></td>
    <td width="90"><?php echo $res_chq["DateEnterBank"]; ?></td>
    <td width="117"><?php echo $cq_no; ?></td>
    <td><?php echo $res_chq["BankName"]; ?></td>
    <td><?php echo $res_chq["BankBranch"]; ?></td>
    <td><?php echo $res_chq["DateOnCheque"]; ?></td>
    <td style="text-align:right;"><?php echo number_format($res_chq["AmtOnCheque"],2); ?></td>
    <td style="text-align:center; background-color:#99FF99;"><!-- <button onclick="toggleContent()">Toggle</button> -->
	<?php echo $bt_pass; ?></td>
  </tr>
 
  <tr style="background-color:#DFF4F7;">
  <td colspan="8">
  <div>
   <?php
   $a=0;
   $qry_dc=pg_query("select A.*,B.*,C.* from \"DetailCheque\" A 
                     LEFT OUTER JOIN \"VContact\" B ON A.\"IDNO\"=B.\"IDNO\"
					 LEFT OUTER JOIN \"TypePay\" C ON A.\"TypePay\"=C.\"TypeID\"
                     WHERE  (A.\"ChequeNo\"='$cq_no') AND (A.\"PostID\"='$p_id')   
				    ");
	while($res_dc=pg_fetch_array($qry_dc))
	{
	  $a++;
	   $ref1s=trim($res_tr["ref1"]);
   $ref2s=trim($res_tr["ref2"]);
      $qry_id=pg_query("select \"C_REGIS\",car_regis, \"IDNO\",\"TranIDRef1\",\"TranIDRef2\" from \"VContact\" where (\"TranIDRef1\"='$ref1s') AND (\"TranIDRef2\"='$ref2s')");
   $res_id=pg_fetch_array($qry_id);
   $idno=$res_id["IDNO"];
	    $ptype=$res_dc["TName"];
	  if($res_dc["C_REGIS"]=="")
		{
		
		$rec_regis=$res_dc["car_regis"];
			
		
		}
		else
		{
		
		$rec_regis=$res_dc["C_REGIS"];
		}
	  
	  	   $view_idno="<a href=\"../post/frm_viewcuspayment.php?idno_names=$res_dc[IDNO]&type=outstanding\" target=\"_blank\">$res_dc[IDNO]</a>";
  			
       echo  "- เช็คที่"." ".$a."  IDNO : ".$view_idno."  ชื่อ : ".$res_dc["full_name"]."     ทะเบียน : ".$rec_regis."&nbsp;&nbsp;&nbsp;     ชำระค่า :".$ptype."&nbsp;&nbsp;&nbsp;  ยอดเช็ค : ".number_format($res_dc["CusAmount"],2)."<br>"; 
	   }
    ?>
    </td>
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
