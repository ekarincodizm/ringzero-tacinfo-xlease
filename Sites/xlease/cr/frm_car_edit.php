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

</head>
<body>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td>

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>

<div class="wrapper">

<?php

$cid = pg_escape_string($_GET['cid']);

$qry_name=pg_query("SELECT * FROM carregis.\"CarTaxDue\" where \"IDCarTax\" = '$cid' ");
if($res_name=pg_fetch_array($qry_name)){
    $IDNO = $res_name["IDNO"];
    $remark = $res_name["remark"];
    $CusAmt = $res_name["CusAmt"];   
    $ApointmentDate = $res_name["ApointmentDate"];
    $TaxDueDate = $res_name["TaxDueDate"];
        $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
    $TypeDep = $res_name["TypeDep"];
        if($TypeDep == '105'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
}

$qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
if($res_name2=pg_fetch_array($qry_name2)){
    $asset_id = $res_name2["asset_id"]; 
    $full_name = $res_name2["full_name"];
    $G_IDNO = $res_name2["IDNO"];
    $G_CusID = $res_name2["CusID"];
    $asset_type = $res_name2["asset_type"];   
    $C_REGIS = $res_name2["C_REGIS"];
    $car_regis = $res_name2["car_regis"]; 
        if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }   
    $C_COLOR = $res_name2["C_COLOR"];
    $C_CARNAME = $res_name2["C_CARNAME"];
	$C_StartDate = $res_name2["C_StartDate"];
    $C_StartDate = date("Y-m-d",strtotime($C_StartDate));
}

$_SESSION["sescr_idno"] = $G_IDNO;
$_SESSION["sescr_scusid"] = $G_CusID; 
 
?>

<fieldset><legend><B>แก้ไขการเจรจา</B></legend>

<form id="frm_1" name="frm_1" method="post" action="frm_car_edit_ok.php">
<input type="hidden" name="iduser" value="<?php echo $get_id_user; ?>">
<input type="hidden" name="cid" value="<?php echo $cid; ?>">
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
   <tr align="left">
      <td width="20%"><b>เลขที่ระบบทะเบียน</b></td>
      <td width="80%" colspan="3" class="text_gray"><?php echo $cid; ?></td>
   </tr>
   <tr align="left">
      <td><b>ชื่อ/สกุล</b></td>
      <td colspan="0" class="text_gray"><?php echo $full_name." (".$IDNO.")"; ?></td>
      <td><b>ทะเบียนรถ</b></td>
      <td colspan="0" class="text_gray"><?php echo $show_regis; ?></td>
   </tr>
   <tr align="left">
      <td><b>ประเภทรถ</b></td>
      <td colspan="0" class="text_gray"><?php echo $C_CARNAME; ?></td>
      <td><b>สีรถ</b></td>
      <td colspan="0" class="text_gray"><?php echo $C_COLOR; ?></td>
   </tr>
   <tr align="left">
      <td><b>วันเริ่มต้น</b></td>
      <td colspan="0" class="text_gray"><?php echo $C_StartDate; ?></td>
      <td><b>วันครบกำหนด</b></td>
      <td colspan="0" class="text_gray"><?php echo $TaxDueDate; ?></td>
   </tr>
   <tr align="left">
      <td><b>รูปแบบ</b></td>
      <td colspan="3" class="text_gray"><?php echo $show_meter; ?></td>
   </tr>
   <tr align="left">
      <td><b>ค่าใช้จ่าย</b></td>
      <td colspan="3" class="text_gray"><?php echo $CusAmt; ?> บาท.</td>
   </tr>
   <tr align="left">
      <td><b>กำหนดวันนัดลูกค้า</b></td>
      <td colspan="3">
        <input name="apointmentdate" id="apointmentdate" type="text" readonly="true" size="15" value="<?php echo $ApointmentDate; ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.apointmentdate,'yyyy-mm-dd',this)" value="ปฏิทิน"/>      
      </td>
   </tr>
   <tr align="left">
      <td><b>เพิ่มหมายเหตุ</b></td>
      <td colspan="3"><textarea name="remark" rows="5" cols="60" style="font-size:11px"></textarea></td>
   </tr>
   <tr align="left">
      <td><b>หมายเหตุ</b> </td>
      <td colspan="3"><textarea name="hiddenremark" rows="5" cols="60" style="font-size:11px; background-color:#E0E0E0;" readonly><?php echo $remark; ?></textarea></td>
   </tr>
</table>
</fieldset>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   บันทึก   "></td>
   </tr>
</table>
</form>

</div>
        </td>
    </tr>
</table>

</body>
</html>