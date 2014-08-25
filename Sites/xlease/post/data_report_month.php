<?php
session_start();
set_time_limit (0); 
ini_set("memory_limit","256M"); 
  $s_mon=$_POST["f_mon"];
  $s_yea=$_POST["f_year"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AV. leasing co.,ltd</title>
<script type="text/javascript">
var xmlHttp;
	function createXMLHttpRequest() 
	{
	 if (window.ActiveXObject) {
		    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		 } 
			else if (window.XMLHttpRequest) {
			 xmlHttp = new XMLHttpRequest();
		 }
	}
function CreateExcelSheet()
{
 createXMLHttpRequest();

var x=myTable.rows;

var xls = new ActiveXObject("Excel.Application");
xls.visible = true;
//xls.Workbooks.Add
var newBook = xls.Workbooks.Add;
newBook.Worksheets.Add;
newBook.Worksheets.Activate;
newBook.Worksheets(1).Name="summary";
newBook.Worksheets(1).Columns("A").columnwidth=10;
newBook.Worksheets(1).Columns("B").columnwidth=12;
newBook.Worksheets(1).Columns("C").columnwidth=30;
newBook.Worksheets(1).Columns("D").columnwidth=10;
newBook.Worksheets(1).Columns("E").columnwidth=10;
newBook.Worksheets(1).Columns("F").columnwidth=16;
newBook.Worksheets(1).Columns("G").columnwidth=16;
newBook.Worksheets(1).Columns("H").columnwidth=16;
newBook.Worksheets(1).Columns("I").columnwidth=18;
for (i = 0; i < x.length; i++)
{
var y = x[i].cells;

for (j = 0; j < y.length; j++)
{
xls.Cells( i+1, j+1).Value = y[j].innerText;
}
}




}
</script>

<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
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
<h1 class="style4"> AV.LEASING</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;"><input type="button" onclick="window.location='frm_create_xls.php?m=<?php echo $s_mon; ?>&y=<?php echo $s_yea; ?>'" value="Excel Sheet : เช่าซื้อรถยนต์"> <input type="button" onclick="window.location='frm_create_xls_gas.php?m=<?php echo $s_mon; ?>&y=<?php echo $s_yea; ?>'" value="Excel Sheet : เช่าซื้อแก๊ส">
<?php

//global $reslast,$rescc,$rescount;


 
include("../config/config.php");

 

 $qry_rpt=pg_query("SELECT A.*,B.\"TranIDRef1\",B.\"TranIDRef2\",B.\"ComeFrom\" FROM \"VContact\" A
                    INNER JOIN \"Fp\" B ON B.\"IDNO\"=A.\"IDNO\" 
					where   EXTRACT(MONTH FROM B.\"P_STDATE\")='$s_mon' AND EXTRACT(YEAR FROM B.\"P_STDATE\")='$s_yea' order by A.\"IDNO\" ");
 
 $count=pg_num_rows($qry_rpt);
 $a=0;

?>
  
  <button onclick="javascript:window.close();">ปิดหน้านี้</button>
  <table id="myTable" width="773" border="0" style="background-color:#CCCCCC;">
  <tr style="background-color:#FFFFCC;">
    <td colspan="9">รายงานประจำเดือน  <?php 
	
	if($s_mon == '1') { $s_c= "มกราคม"; } else
	if($s_mon == '2') {  $s_c= "กุมพาพันธ์"; } else
	if($s_mon == '3') { $s_c= "มีนาคม"; } else
	if($s_mon == '4') {  $s_c= "เมษายน"; } else
	if($$s_mon== '5') {  $s_c= "พฤษภาคม"; } else
	if($s_mon == '6') {  $s_c= "มิถุนายน"; } else
	if($s_mon == '7') {  $s_c= "กรกฏาคม"; } else
	if($s_mon == '8') {  $s_c= "สิืงหาคม"; } else
	if($s_mon == '9') {  $s_c= "กันยายน"; } else
	if($s_mon == '10') {  $s_c= "ตุลาคม"; } else
	if($s_mon == '11') {  $s_c= "พฤศจิกายน"; } else {  $s_c= "ธันวาคม"; }

   $sthais_year=$s_yea+543;
	
	 echo $s_c." พ.ศ. ".$sthais_year; ?> </td>
    </tr>
 
  <?php
   if($count==0)
   {
   ?>
   <tr>
    <td colspan="9">ไม่พบข้อมูล <input type="button" value="BACK" onclick="window.location='report_monthav.php'" /></td>
    </tr>
   <?php
   }
   else
   {
   ?>	
   <tr style="background-color:#FFFFFF;">
    <td width="31"><div align="center">no.</div></td>
    <td width="85"><div align="center">IDNO</div></td>
    <td width="173">ชื่อ - นามสกุล</td>
    <td width="61"><div align="center">ทะเบียน</div></td>
    <td width="81"><div align="center">วันทำสัญญา</div></td>
    <td align="center" width="70">TranID<br />
      Ref1</td>
    <td width="70">TranID<br />
      Ref2</td>
    <td width="90"><div align="center">เงินต้น</div></td>
    <td width="74"><div align="center">comefrom</div></td>
   </tr>
 
  <?php
    while($reslast=pg_fetch_array($qry_rpt))
   {
       if(substr($reslast["IDNO"],6,2) != 22){
    $a++;
	  
	$trn_cdate=pg_query("select c_datethai('$reslast[P_STDATE]')");
	$res_cdate=pg_fetch_result($trn_cdate,0);  
  
  ?>

 
 <tr style="background-color:#FFF5F2;">
    <td style="padding:2px;"><?php echo $a; ?></td>
    <td style="padding:2px;"><?php echo $reslast["IDNO"]; ?></td>
    <td style="padding:2px;"><?php echo $reslast["full_name"]; ?></td>
    <td style="padding:2px;"><?php   
			 if($reslast["C_REGIS"]==""){
				 $rec_regis=$reslast["car_regis"];
				 
			 }else{
				 $rec_regis=$reslast["C_REGIS"];
				
			}
	
	          echo $rec_regis; ?></td>
    <td align="center" style="padding:2px; text-align:center;"><?php echo $res_cdate; ?></td>
    <td style="text-align:center; padding:2px;"><?php echo $reslast["TranIDRef1"]; ?></td>
    <td align="center" style="text-align:center; padding:2px;"><?php echo $reslast["TranIDRef2"]; ?></td>
    <td align="center" style="text-align:right; padding:2px;"><?php echo number_format($reslast["P_BEGINX"],2); ?></td>
    <td style="padding-left:5px; padding:2px;"><?php echo $reslast["ComeFrom"]; ?></td>
  </tr>
  
   
<?php
    $sum_begin=$sum_begin+$reslast["P_BEGINX"];   
    }
 }
?>
<tr >
    <td colspan="5"></td>
    <td colspan="3"></td>
    <td width="74"></td>
  </tr>
<tr style="background-color:#CCFF00;">
    <td colspan="9">รวมยอด <?php echo $a; ?> รายการ <a href="report_month_pdf.php?m=<?php echo $s_mon;?>&y=<?php echo $s_yea; ?>">PDF</a> </td>
    </tr>
  <tr >
    <td colspan="5"></td>
    <td colspan="3"></td>
    <td width="74"></td>
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
