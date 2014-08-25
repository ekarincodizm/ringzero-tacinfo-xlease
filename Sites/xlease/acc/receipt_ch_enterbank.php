<?php
session_start();
include("../config/config.php");

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
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
    <style type="text/css">

    .mouseOut {
    background: #708090;
    color: #FFFAFA;
    }

    .mouseOver {
    background: #FFFAFA;
    color: #000000;
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
	</style>
   


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
<div id="warppage" style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <b>Enter Date Bank</b> <hr />
   <input name="button2" type="button" onclick="window.close()" value="CLOSE" />
   <form name="frm_ch" method="post" action="list_cheque_enterbank.php">
  เลือกวันที่บนเช็ค 
    <input name="qryDate" type="text" readonly="true" value="<?php echo date("Y/m/d"); ?>"/>
      <input name="button" type="button" onclick="displayCalendar(document.frm_ch.qryDate,'yyyy/mm/dd',this)" value="ปฏิทิน" />
    
             <input type="submit" value="NEXT" />
  </form>		 
             <br />
			 <?php
			 $c_date=date("Y-m-d");
			 $qry_ch=pg_query("select *  from \"FCheque\"  
                               WHERE (\"IsReturn\"=FALSE) AND (\"DateOnCheque\" < '$c_date')  AND (\"IsPass\" = false) AND (\"Accept\" = true) 
							     AND (\"AccBankEnter\" IS NULL)");
			 $numr_ch=pg_num_rows($qry_ch);
			 if($numr_ch==0)
			 {
			  
			 }
			 else
			 {
			 ?>
			 <form name="frm_ren" method="post" action="pass_ch_process_passed.php">
			 <table  width="800" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
			<tr style="background-color:#EBFB91;">
				<td colspan="8" class="td">รายการเช็คที่ยังไม่ได้ลงวันที่เข้าธนาคาร <?php echo $dateqry; ?></td>
			</tr>
			<tr style="background-color:#EEF2DB">
				<td width="29" class="td">No.</td>
				<td width="94" class="td">ChequeNo.</td>
				<td width="95" class="td">PostID</td>
				<td width="92" class="td">Bank Name </td>
				<td width="156" class="td">BankBranch</td>
				<td width="103" class="td">DateOnChque</td>
				<td width="139" class="td"><div align="center">AmtOnCheque</div></td>
				<td width="67" class="td"><div align="center">select</div></td>
			</tr>
			 <?php
			 $n=0;
			 while($res_chr=pg_fetch_array($qry_ch))
			 {
			  $n++;
                                                  $p_id=$res_chr["PostID"];
			  $cq_no=$res_chr["ChequeNo"];
			  if(!empty($res_chr["DateEnterBank"]))
			  {
				$checked="รอเช็คผ่าน";
			  }
			  else
			  {
			  
				
			  $checked="<input type=\"checkbox\" name=\"chose_lists[]\" value=\"$cq_no\">";
			  
			  }
			 ?>
			  <tr style="background-color:#FFC; font:bold;">
				<td  class="td"><?php echo $n; ?></td>
				<td width="94" class="td"><?php echo $cq_no; ?></td>
				<td width="95" class="td"><?php echo $p_id; ?></td>
				<td width="92" class="td"><?php echo $res_chr["BankName"]; ?></td>
				<td class="td"><?php echo $res_chr["BankBranch"]; ?></td>
				<td class="td"><?php echo $res_chr["DateOnCheque"]; ?></td>
				<td style="text-align:right;" class="td"><?php echo number_format($res_chr["AmtOnCheque"],2); ?></td>
				<td style="text-align:center; background-color:#99FF99;" class="td"><!-- <button onclick="toggleContent()">Toggle</button> -->
				<?php echo $checked; ?></td>
			  </tr>
			 <?php
			   }
			 ?>
			  <tr style="background-color:#EEF2DB">
				  <td colspan="7" ><div align="right">วันที่เช็คเข้าธนาคาร <input name="qryDate" type="text" readonly="true" value="<?php echo date("Y/m/d"); ?>"/>
     </div></td>
				  <td ><div align="center">
					<input type="submit" value="update" />
				  </div></td>
			  </tr>	
			  
			 <?php  
			 }
			 ?>
     
	</table>
	</form>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
