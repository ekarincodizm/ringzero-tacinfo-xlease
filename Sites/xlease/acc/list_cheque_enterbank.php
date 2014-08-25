<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
$id_user=$_SESSION["av_iduser"];
$dateqry=pg_escape_string($_POST["qryDate"]);

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

function CheckAll() 
{
	for (var i = 0; i < document.frm_ren.elements.length; i++) 
	{
		if(document.frm_ren.elements[i].type == 'checkbox')
	  	{
			document.frm_ren.elements[i].checked = !(document.frm_ren.elements[i].checked);
			document.frm_ren.allcheck.checked;
			// document.getElementById('subm').disabled = false;
       }

   }
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
    
	#res_table
	{
		width:800px;
	
	}
	.td { padding:0px 3px 0px 3px; }
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
<div id="warppage" style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
<br />
 <b>List Cheque Enter Bank </b>
  <hr />
   
   <table width="800" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
  <tr style="background-color:#EBFB91;">
    <td colspan="8">รายการเช็ค ประจำวันที่ <?php echo $dateqry; ?> <input type="button" value="BACK" onclick="window.location='receipt_ch_enterbank.php'"  /></td>
    </tr>

  <tr style="background-color:#EEF2DB">
    <td width="35" class="td">No.</td>
    <td width="84" class="td">ChequeNo.</td>
    <td width="88" class="td">PostID </td>
    <td width="101" class="td">Bank Name </td>
    <td width="118" class="td">BankBranch</td>
    <td width="124" class="td">DateEnterBank</td>
    <td width="120" class="td"><div align="center">AmtOnCheque</div></td>
    <td width="105" class="td"><div align="center">select</div></td>
  </tr>
  <form name="frm_ren" method="post" action="pass_ch_process_date.php">
  <?php
   include("../config/config.php");
  $nowdate=date("Y-m-d"); 
 
  $dateqry=pg_escape_string($_POST["qryDate"]);
  $qry_chq=pg_query("select *  from \"FCheque\"  
                     
                     WHERE (\"IsReturn\"=FALSE) AND (\"DateOnCheque\" ='$dateqry') AND (\"Accept\" = true)");
  while($res_chq=pg_fetch_array($qry_chq))
  {		
  
  
    $n++; 			
	$cq_no=$res_chq["ChequeNo"];
	$p_id=$res_chq["PostID"];
	$ch_date=$res_chq["DateOnCheque"];
	
	$pbname=$res_chq["BankName"];
	$pbbranch=$res_chq["BankBranch"];
	$pamt=$res_chq["AmtOnCheque"];
	 
    if($res_chq["IsPass"]=='t')
	{
	$checked="รับเงินแล้ว";
	}
	else
	{
	 if(!empty($res_chq["DateEnterBank"]))
	  {
	    $checked="รอเช็คผ่าน";
	  }
	  else
	  {
	  
		
	  $checked="<input type=\"checkbox\" name=\"chose_lists[]\" value=\"$cq_no\" onclick=\"disBut(this.form)\">";
	  
	  }
	}
	
	
  ?>  
  <tr style="background-color:#FFC; font:bold;">
    <td class="td" ><?php echo $n; ?></td>
    <td width="84" class="td"><?php echo $cq_no; ?></td>
    <td width="88" class="td"><?php echo $p_id; ?></td>
    <td width="101" class="td"><?php echo $res_chq["BankName"]; ?></td>
    <td class="td"><?php echo $res_chq["BankBranch"]; ?></td>
    <td class="td"><?php echo $res_chq["DateEnterBank"]; ?></td>
    <td style="text-align:right;" class="td"><?php echo number_format($res_chq["AmtOnCheque"],2); ?></td>
    <td style="text-align:center; background-color:#99FF99;" class="td"><!-- <button onclick="toggleContent()">Toggle</button> -->
	<input type="hidden" name="pid[]" value="<?php echo $p_id; ?>" /><?php echo $checked; ?></td>
  </tr>
 
  
	
  <?php
    
   }
  ?>
  <tr style="background-color:#EEF2DB">
      <td colspan="7" ><div align="right">เลือกทั้งหมด</div></td>
      <td ><div align="center">
        <input type="checkbox" name="allcheck" onclick="CheckAll(this);">
      </div></td>
  </tr>	
   <tr style="background-color:#EEF2DB">
      <td colspan="7" ><div align="right">วันที่เช็คเข้าธนาคาร <input name="qryDate" type="text" readonly="true" value="<?php echo date("Y/m/d"); ?>"/>
      <!--<input name="button" type="button" onclick="displayCalendar(document.frm_ren.qryDate,'yyyy/mm/dd',this)" value="ปฏิทิน" /> --></div></td>
      <td ><div align="center">
        <input type="submit" value="Update"  />
      </div></td>
  </tr>	
  </form>
</table>


  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
