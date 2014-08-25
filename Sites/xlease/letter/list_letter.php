<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AV. leasing co.,ltd</title>
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>

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

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> AV.LEASING</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:20px; padding-left:10px;">REPORT LETTER &nbsp;&nbsp;&nbsp;<a href="../list_menu.php"></a><br />
  </div>
    <div class="style5" style="width:auto; height:20px; padding-left:10px;"><a href="frm_report_letter.php">[รายงานส่งจดหมาย]</a> <a href="frm_edit_letter.php">[แก้ไขที่อยู่]</a>&nbsp;<a href="../list_menu.php">[กลับเมนูหลัก]</a><br />
  </div>
  <div class="style5" style="width:auto; height:60px; padding-left:0px;">
  <div style="padding-left:10px;">
  <form id="form1" name="form1" method="post" action="list_letter.php"> 
  <input name="report_Date" type="text" readonly="true"  value="<?php echo date("Y/m/d"); ?>" onchange="document.form1.submit();"/>
      <input name="button" type="button" onclick="displayCalendar(document.form1.report_Date,'yyyy/mm/dd',this)" value="ปฏิทิน" />
    
	
</form>
</div>
  
  </div>
  <div>
  <table width="798" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
  <?php 
	$rdate=pg_escape_string($_POST["report_Date"]);
	$qry_dlet=pg_query("select A.*,B.* FROM letter.send_detail A 
	                    LEFT OUTER JOIN letter.send_address B on A.\"CusLetID\"=B.\"CusLetID\" 
					    WHERE send_date='$rdate'");
	$numr=pg_num_rows($qry_dlet);	
	if($numr==0)
	{				
	?>
    <tr style="background-color:#DDE6B7;">
    <td colspan="5"><div align="center">ไม่มีรายการส่งจดหมาย <?php echo $rdate; ?></div></td>
    </tr>
	<?php
	}
	else
	{
	?>
	 <tr style="background-color:#DDE6B7;">
    <td colspan="5"><div align="center">รายการส่งจดหมาย <?php echo $rdate; ?></div></td>
    </tr>
  <tr  style="background-color:#FAFDEC;">
    <td width="24">No.</td>
    <td width="87">SendID</td>
    <td width="176">Name</td>
    <td width="280">Address</td>
    <td width="215">Detail</td>
    </tr>
	<?php
	while($res_dlet=pg_fetch_array($qry_dlet))
	{				   
	 $n++;
	$m_type="";
	?>
  <tr style="background-color:#FFFFFF;">
    <td><?php echo $n; ?></td>
    <td><?php echo $res_dlet["sendID"]; ?></td>
    <td><?php echo $res_dlet["name"]; ?></td>
    <td><?php echo $res_dlet["dtl_ads"]; ?></td>
    <td><?php 
	    $ars=explode(",",$res_dlet["detail"]);
		 
		 for($i=0;$i<count($ars);$i++)
			{
			   $varr=$ars[$i];
			   $qry_type=pg_query("select * from letter.type_letter WHERE auto_id='$varr'");
			   $res_type=pg_fetch_array($qry_type);
			   
			   $m_type=$m_type.$res_type["type_name"]." ";
			}
		
		echo $m_type;	 
	    ?></td>
  </tr>
  <?php
    }
   }
  ?>
</table>

  </div>
  
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
