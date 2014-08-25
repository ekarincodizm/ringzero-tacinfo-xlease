<?php 
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
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

<fieldset><legend><B>แก้ไขข้อมูล</B></legend>

<?php

$qry_dt=pg_query("SELECT * FROM carregis.\"DetailCarTax\" WHERE \"IDDetail\"='$id' ORDER BY \"IDDetail\" ");
if($res_dt=pg_fetch_array($qry_dt)){
    $IDDetail = $res_dt["IDDetail"];
    $TaxValue = $res_dt["TaxValue"];
    $BillNumber = $res_dt["BillNumber"];
    $CoPayDate = $res_dt["CoPayDate"];
    $TypePay = $res_dt["TypePay"];
}
?>

<form id="frm_1" name="frm_1" method="post" action="frm_car_admin_edit_detail_ok.php">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
    <tr align="left">
        <td><b>รูปแบบบริการ</b></td>
        <td colspan="3">
<select name="typepay">
<?php
$qry_name8=pg_query("select * from \"TypePay\" WHERE \"TypeDep\"='C' ORDER BY \"TypeID\" ASC ");
while($res_name8=pg_fetch_array($qry_name8)){
    if($res_name8['TypeID'] == $TypePay){
        echo "<option value=\"$res_name8[TypeID]\" selected>$res_name8[TName]</option>";
    }else{
        echo "<option value=\"$res_name8[TypeID]\">$res_name8[TName]</option>";
    }
}
?>
</select>
        </td>
    </tr>
   <tr align="left">
      <td><b>เลขที่ใบเสร็จ</b></td>
      <td colspan="0"><input type="text" name="billnumber" value="<?php echo $BillNumber; ?>"></td>
      <td><b>จำนวนเงินตามใบเสร็จ</b> </td>
      <td colspan="0"><input type="text" name="taxvalue" value="<?php echo $TaxValue; ?>"></td>
   </tr>   
   <tr align="left">
      <td><b>วันที่ตามใบเสร็จ</b></td>
      <td colspan="3">
        <input name="copaydate" id="copaydate" type="text" readonly="true" size="15" value="<?php echo $CoPayDate; ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.copaydate,'yyyy-mm-dd',this)" value="ปฏิทิน"/>
      </td>
   </tr>
</table>

<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   บันทึก   "><input type="button" onclick="javascript:window.close();" value=" ปิดหน้านี้ "></td>
   </tr>
</table>
</form>

</fieldset>

</body>
</html>