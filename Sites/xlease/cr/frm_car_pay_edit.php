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
    $MeterTax = $res_name["MeterTax"];
        if($MeterTax == 'f'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
    $CoPayDate = $res_name["CoPayDate"];
    $TaxValue = $res_name["TaxValue"];
    $ChargeValue = $res_name["ChargeValue"];
    $BillNumber = $res_name["BillNumber"];
    
    $BillNumbers = explode(",", $BillNumber);
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

<script>
function validate(){

    var theMessage = "";
    var noErrors = theMessage;
    var i = 0;

    if (document.frm_1.taxvalue.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนจำนวนเงินตามใบเสร็จ";       
    }
    if (document.frm_1.chargevalue.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนค่าธรรมเนียมอื่นๆ";
    }

    /*for (i = 0; i < document.frm_1.billnumber.length; i++){
        if (document.frm_1.billnumber[i].value == "") {
            theMessage = theMessage + "\n - กรุณาป้อนเลขที่ใบเสร็จ";
        }
    }*/
    
    // If no errors, submit the form
    if (theMessage == noErrors) {
        return true;
    } else {
        // If errors were found, show alert message
        alert(theMessage);
        return false;
    }
}
</script>

<fieldset><legend><B>แก้ไขข้อมูลการชำระเงิน</B></legend>

<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDCarTax</td>
        <td align="center">IDNO</td>
        <td align="center">ชื่อ/สกุล</td>
        <td align="center">ทะเบียน</td>
        <td align="center">วันที่จ่าย</td>
        <td align="center">ยอดเงิน</td>
        <td align="center">ค่าธรรมเนียมอื่น</td>
        <td align="center">รวม</td>
    </tr>
    
<?php

    $qry_iff=pg_query("select A.*,B.* from carregis.\"CarTaxDue\" A LEFT OUTER JOIN carregis.\"DetailCarTax\" B on A.\"IDCarTax\" = B.\"IDCarTax\" 
    WHERE \"IDNO\"='$IDNO' AND \"TaxValue\" is not null ORDER BY A.\"IDCarTax\" DESC ");

    $rows = pg_num_rows($qry_iff);
    while($res_iff=pg_fetch_array($qry_iff)){
        $IDCarTax44 = $res_iff["IDCarTax"];
        $IDNO44 = $res_iff["IDNO"];
        $CoPayDate44 = $res_iff["CoPayDate"];
        $TaxValue44 = $res_iff["TaxValue"];
        $ChargeValue44 = $res_iff["ChargeValue"];

        
        $qry_name22=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO44' ");
        if($res_name22=pg_fetch_array($qry_name22)){
            $full_name22 = $res_name22["full_name"];
            $IDNO22 = $res_name22["IDNO"];
            $asset_type22 = $res_name22["asset_type"];   
            $C_REGIS22 = $res_name22["C_REGIS"];
            $car_regis22 = $res_name22["car_regis"]; 
                if($asset_type22 == 1){ $show_regis22 = $C_REGIS22; } else { $show_regis22 = $car_regis22; }   
        }
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$IDCarTax44"; ?></td>
        <td align="center"><?php echo "$IDNO22"; ?></td>
        <td align="center"><?php echo "$full_name22"; ?></td>
        <td align="center"><?php echo "$show_regis22"; ?></td>
        <td align="center"><?php echo "$CoPayDate44"; ?></td>
        <td align="right"><?php echo number_format($TaxValue44,2); ?></td>
        <td align="right"><?php echo number_format($ChargeValue44,2); ?></td>
        <td align="right"><?php echo number_format($TaxValue44+$ChargeValue44,2); ?></td>
    </tr>
<?php
    }
    if($rows == 0){
?> 
    <tr><td colspan=10 align="center">- ไม่พบข้อมูล -</td></tr>
<?php } ?>
</table> 

<form id="frm_1" name="frm_1" method="post" action="frm_car_pay_edit_ok.php">
<input type="hidden" name="iduser" value="<?php echo $get_id_user; ?>">
<input type="hidden" name="cid" value="<?php echo $cid; ?>">

<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
   <tr align="left">
      <td><b>เลขที่ระบบทะเบียน</b></td>
      <td class="text_gray"><?php echo $cid; ?></td>
      <td></td>
      <td></td>
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
      <td colspan="0" class="text_gray"><?php echo $show_meter; ?></td>
      <td><b>ค่าใช้จ่าย</b></td>
      <td colspan="0" class="text_gray"><?php echo $CusAmt; ?> บาท.</td>
   </tr>
   <tr align="left">
      <td><b>วันที่นัดลูกค้า</b></td>
      <td colspan="3" class="text_gray"><?php echo $ApointmentDate; ?></td>
   </tr>
   <tr align="left">
      <td><b>เลขที่ใบเสร็จ (มิเตอร์)</b></td>
      <td colspan="3"><input type="text" name="billnumber[]" value="<?php echo $BillNumbers[0]; ?>"></td>
   </tr>
   <?php if($MeterTax == 't'){ ?>
   <tr align="left">
      <td><b>เลขที่ใบเสร็จ (ภาษี)</b></td>
      <td colspan="3"><input type="text" name="billnumber[]" value="<?php echo $BillNumbers[1]; ?>"></td>
   </tr>
   <?php } ?>
   <tr align="left">
      <td><b>จำนวนเงินตามใบเสร็จ</b></td>
      <td colspan="0"><input type="text" name="taxvalue" value="<?php echo $TaxValue; ?>"></td>
      <td><b>ค่าธรรมเนียมอื่นๆ</b></td>
      <td colspan="0"><input type="text" name="chargevalue" value="<?php echo $ChargeValue; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>วันที่ตามใบเสร็จ</b></td>
      <td colspan="3">
        <input name="copaydate" id="copaydate" type="text" readonly="true" size="15" value="<?php echo $CoPayDate; ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.copaydate,'yyyy-mm-dd',this)" value="ปฏิทิน"/>      
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