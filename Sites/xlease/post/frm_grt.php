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
  <div class="style5" style="width:auto; height:60px; padding-left:10px;">
  <?php
  include("../config/config.php");
   echo  $gp_cusid=$_GET["p_cusid"]; 
         $gp_cusname=$_GET["p_cusname"];
		 
		 $edt_idno=$_GET["fIDNO"];
		 
  $qry_fp=pg_query("select * from \"ContactCus\" where \"IDNO\" ='$edt_idno' AND \"CusState\"=1");
  $res_fp=pg_fetch_array($qry_fp);
  $residno=$res_fp["IDNO"];

 
  
  ?>
  </div>
  
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
    <form name="frm_edit" method="post" action="edit_cus.php">
	  <input type="hidden" name="fidno" value="<?php echo $edt_idno; ?>" />
	  <input type="hidden" name="fcus_id" value="<?php echo $fp_cusid; ?>" />
	  <input type="hidden" name="fcar_id" value="<?php echo $fp_carid; ?>" />
	  ผู้ค้ำประกัน
	  <table width="785" border="0" cellpadding="1" cellspacing="1">
   
	<?php
    if(empty($residno))
    {
	?>
	<tr>
    <td colspan="4">ไม่พบข้อมูลผู้ค้ำประกัน <input type="button" value="เพิ่มคนค้ำประกัน" onclick="parent.location='frm_contactcus.php?fIDNO=<?php echo $edt_idno; ?>' "/></td>
    </tr>
    <?php
	}
    else
    {
	 $qry_fn=pg_query("select A.*,C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\",C.\"CusID\" from \"ContactCus\" A
                                   
                                   LEFT OUTER JOIN \"Fa1\" C on C.\"CusID\" = A.\"CusID\"   
where A.\"IDNO\"='$edt_idno' AND \"CusState\"=1 ");
	
	 
    ?>
 <tr>
    <td colspan="4" style="background-color:#FFFFCC;">ข้อมูลผู้ค้ำประกัน</td></tr>
  <tr>
    <td width="57">ลำดับที่ </td>
    <td width="537">ชื่อ - นามสกุล </td>
    <td width="90">แก้ไข</td>
    <td width="90">ลบ</td>
  </tr>
  <?php
   while($res_fn=pg_fetch_assoc($qry_fn))
     {
	   $fullname=trim($res_fn["A_FIRNAME"])." ".trim($res_fn["A_NAME"])." ".trim($res_fn["A_SIRNAME"]);
	   $a++;
  ?>  
  <tr>
    <td><?php echo $a; ?></td>
    <td><?php echo $fullname; ?></td>
    <td><a href="frm_edit_contactcus.php?cusID=<?php echo $res_fn["CusID"]; ?>">แก้ไข</a></td>
    <td>ลบ</td>
  </tr>
   <?php
  }
  ?>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" value="เพิ่มคนค้ำประกัน" onclick="parent.location='frm_contactcus.php?fIDNO=<?php echo $edt_idno; ?>' "/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php
 
  }
  ?>
</table>
</form>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
