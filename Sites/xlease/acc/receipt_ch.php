<?php
session_start();
$id_user=$_SESSION["av_iduser"];
include("../config/config.php");
//$current_server_time = date("Y")."/".date("m")."/".date("d")." ".date("H:i:s");
set_time_limit(0);

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
<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
<!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="warppage" style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <b>Pass Cheque</b>
  <hr />
    <input name="button2" type="button" onclick="window.close()" value="close" />
    <br />
  <form name="frm_ch" method="post" action="list_cheque.php">
  เลือกวันที่ <input name="qryDate" type="text" readonly="true" value="<?php echo date("Y/m/d"); ?>"/>
      <input name="button" type="button" onclick="displayCalendar(document.frm_ch.qryDate,'yyyy/mm/dd',this)" value="ปฏิทิน" />
    
             <input type="submit" value="NEXT" />
             <br />
             <table id="name_table" bgcolor="#FFFAFA" border="0" cellspacing="0" cellpadding="0" />            
             <div style="visibility:hidden;" id="popup"></div>
  </form>	
  <div>
     <table width="782" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
  <tr style="background-color:#EBFB91;">
    <td colspan="8" style="padding-left:10px;"><b>รายการเช็คที่รอการ PASS</b> </td>
    </tr>

  <tr style="background-color:#EEF2DB">
    <td width="28">No.</td>
    <td width="101">Date<br />
Enterbank.</td>
    <td width="97">ChequeNo</td>
    <td width="82">Bank Name</td>
    <td width="157">BankBranch</td>
    <td width="98">Date<br />
OnCheque</td>
    <td width="106">AmtOnCheque</td>
    <td width="88">status</td>
  </tr>
  <?php
  $nowdate=date("Y-m-d"); 
   $qry_chq=pg_query("select \"ChequeNo\", \"PostID\", \"DateEnterBank\", \"BankName\", \"BankBranch\", \"AmtOnCheque\", \"IsPass\", \"DateOnCheque\"
						from \"FCheque\"
                      WHERE (\"Accept\"=TRUE) AND (\"IsReturn\"=FALSE) AND (\"IsPass\"= FALSE) AND (\"DateEnterBank\" < '$nowdate') ORDER BY \"DateEnterBank\"  ");
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
  <tr style="background-color:#D2FFD5; font:bold;">
    <td ><?php echo $n; ?></td>
    <td width="101" style="background-color:#F7FDD0; padding-left:2px;"><?php echo $res_chq["DateEnterBank"]; ?></td>
    <td width="97" style="padding-left:5px;"><b><a href="" onclick="MM_openBrWindow('detail_cheque.php?ch_no=<?php echo $cq_no; ?>&ch_pid=<?php echo $p_id; ?>','','scrollbars=yes,width=620,height=300')"><?php echo $cq_no; ?></a></b></td>
    <td style="padding-left:2px;"><?php echo $res_chq["BankName"]; ?></td>
    <td style="padding-left:2px;"><?php echo $res_chq["BankBranch"]; ?></td>
    <td style="padding-left:2px;"><?php echo $res_chq["DateOnCheque"]; ?></td>
    <td style="text-align:right;"><?php echo number_format($res_chq["AmtOnCheque"],2); ?></td>
    <td style="text-align:center; background-color:#99FF99;"><!-- <button onclick="toggleContent()">Toggle</button> -->
	<?php echo $bt_pass; ?></td>
  </tr>
 
  
   <?php
   /*
   $a=0;
   $qry_dc=pg_query("select A.*,B.*,C.* from \"DetailCheque\" A 
                     LEFT OUTER JOIN \"VContact\" B ON A.\"IDNO\"=B.\"IDNO\"
					 LEFT OUTER JOIN \"TypePay\" C ON A.\"TypePay\"=C.\"TypeID\"
                     WHERE  (A.\"ChequeNo\"='$cq_no') AND (A.\"PostID\"='$p_id')   
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
	  
	   $view_idno="<a href=\"../post/frm_viewcuspayment.php?idno_names=$res_dc[IDNO]&type=outstanding\" target=\"_blank\">$res_dc[IDNO]</a>";
  			
       echo  "- เช็คที่"." ".$a."  IDNO : ".$view_idno."  ชื่อ : ".$res_dc["full_name"]."     ทะเบียน : ".$rec_regis."&nbsp;&nbsp;&nbsp;     ชำระค่า :".$ptype."&nbsp;&nbsp;&nbsp;  ยอดเช็ค : ".number_format($res_dc["CusAmount"],2)."<br>"; 
	}   
    ?>
     </td>
    </tr>
	
  <?php
    */

   }
     ?>	
</table>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
