<?php 
session_start();  
include("../config/config.php"); 
$get_id_user = $_SESSION["av_iduser"];
$d_id = pg_escape_string($_GET['id']);
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

<fieldset><legend><B>แก้ไขข้อมูลการชำระเงิน</B></legend>

<?php
$qry_iff=pg_query("select * from carregis.\"DetailCarTax\" WHERE \"IDDetail\"='$d_id'");
if($res_iff=pg_fetch_array($qry_iff)){
        $IDCarTax = $res_iff["IDCarTax"];
        $CoPayDate = $res_iff["CoPayDate"];
        $TaxValue = $res_iff["TaxValue"];
        $BillNumber = $res_iff["BillNumber"];
        $TypePay = $res_iff["TypePay"];
        
        $qry_name33=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypePay' ");
        if($res_name33=pg_fetch_array($qry_name33)){
            $TName = $res_name33["TName"];
        }
}
?> 

<form id="frm_1" name="frm_1" method="post" action="frm_other_pay_edit_ok.php">
<input type="hidden" name="did" value="<?php echo $d_id; ?>">
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
   <tr align="left">
      <td><b>เลขที่ระบบทะเบียน</b></td>
      <td colspan="3" class="text_gray"><?php echo $IDCarTax; ?></td>
   </tr>
   <tr align="left">
      <td><b>จำนวนเงินตามใบเสร็จ</b></td>
      <td colspan="0" class="text_gray"><?php echo number_format($TaxValue,2); ?> บาท.</td>
      <td><b>บริการ</b></td>
      <td colspan="0" class="text_gray"><?php echo $TName; ?></td>
   </tr>
   <tr align="left">
      <td><b>วันที่ตามใบเสร็จ</b></td>
      <td colspan="0" class="text_gray"><input name="copaydate" id="copaydate" type="text" readonly="true" size="15" value="<?php echo $CoPayDate; ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.copaydate,'yyyy-mm-dd',this)" value="ปฏิทิน"/></td>
      <td><b>เลขที่ใบเสร็จ</b></td>
      <td colspan="0" class="text_gray"><input type="text" name="billnumber" value="<?php echo $BillNumber; ?>"></td>
   </tr>
</table>
</fieldset>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   แก้ไข   "></td>
   </tr>
</table>
</form>

</div>
        </td>
    </tr>
</table>

</body>
</html>