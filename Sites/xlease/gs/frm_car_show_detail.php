<?php 
session_start();  
include("../config/config.php");
$get_id_user = $_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language=javascript>
<!--
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
//-->
</script>
    
</head>
<body>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td>

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>

<div class="wrapper">

<?php

$id = pg_escape_string($_GET['id']);

$qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$id' ");
if($res_name2=pg_fetch_array($qry_name2)){
    $asset_id = $res_name2["asset_id"]; 
    $full_name = $res_name2["full_name"];
    $G_IDNO = $res_name2["IDNO"];
    $G_CusID = $res_name2["CusID"];
    $asset_type = $res_name2["asset_type"];   
    $C_REGIS = $res_name2["C_REGIS"];
    $car_regis = $res_name2["car_regis"]; 
	$C_StartDate = $res_name2["C_StartDate"];
    $C_StartDate = date("Y-m-d",strtotime($C_StartDate)); 
        if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
}

$_SESSION["sescr_idno"] = $G_IDNO;
$_SESSION["sescr_scusid"] = $G_CusID;

?>

<fieldset>

<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
   <tr align="left">
      <td width="10%"><b>ชื่อ/สกุล</b></td>
      <td width="40%" class="text_gray"><?php echo $full_name." (".$id.")"; ?></td>
      <td align="right" width="50%">
            <input type="button" value="บันทึกการติดตาม" onclick="javascript:popU('follow_up_cus.php','aaa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=530,height=600')">
      </td>
   </tr>
   <tr align="left">
      <td><b>ทะเบียนรถ</b></td>
      <td class="text_gray"><?php echo $show_regis; ?></td>
   </tr>
   <tr align="left">
      <td><b>วันเริ่มต้น</b></td>
      <td class="text_gray"><?php echo $C_StartDate; ?></td>
   </tr>
</table>

<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDCarTax</td>
        <td align="center">วันครบกำหนด</td>
        <td align="center">ประเภท</td>
        <td align="center">วันนัด</td>
        <td align="center">ยอดเงินตามใบเสร็จ</td>
        <td align="center">ค่าธรรมเนียมอื่น</td>
        <td align="center">รวม</td>
    </tr>
    
<?php
    $qry_if=pg_query("select * from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$id' ORDER BY \"IDCarTax\" ASC");
    $rows = pg_num_rows($qry_if);
    while($res_if=pg_fetch_array($qry_if)){
        $IDCarTax = $res_if["IDCarTax"];
        $TaxDueDate = $res_if["TaxDueDate"];
        $MeterTax = $res_if["MeterTax"];
        $TaxValue = $res_if["TaxValue"];
            if($MeterTax == 'f'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
        $ChargeValue = $res_if["ChargeValue"];
        $ApointmentDate = $res_if["ApointmentDate"];
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$IDCarTax"; ?></td>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="left"><?php echo "$show_meter"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td>
        <td align="right"><?php echo number_format($TaxValue,2); ?></td>
        <td align="right"><?php echo number_format($ChargeValue,2); ?></td>
        <td align="right"><?php echo number_format($TaxValue+$ChargeValue,2); ?></td>
    </tr>
<?php
    }
    if($rows == 0){
?>     
    <tr><td colspan="10" align="center">- ไม่พบข้อมูล -</td></tr>  
<?php
    }
?> 
</table> 

</fieldset>

</div>
        </td>
    </tr>
</table>

</body>
</html>