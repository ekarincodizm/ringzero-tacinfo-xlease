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

$cid = pg_escape_string($_GET['cid']);

$qry_name=pg_query("select * from carregis.\"CarTaxDue\" where \"IDCarTax\" = '$cid'");
if($res_name=pg_fetch_array($qry_name)){
    $IDCarTax = $res_name["IDCarTax"];
    $IDNO = $res_name["IDNO"];
    $remark = $res_name["remark"];
    $CusAmt = $res_name["CusAmt"];
    $TypeDep = $res_name["TypeDep"];
    $ApointmentDate = $res_name["ApointmentDate"];
    $TaxValue = $res_name["TaxValue"];
    $TaxDueDate = $res_name["TaxDueDate"];
        $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
}

$qry_name4=pg_query("select * from \"TypePay\" WHERE \"TypeID\"='$TypeDep' ");
if($res_name4=pg_fetch_array($qry_name4)){
    $TName1 = $res_name4["TName"];
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
}

$_SESSION["sescr_idno"] = $G_IDNO;
$_SESSION["sescr_scusid"] = $G_CusID;

?>

<fieldset><legend><B>เพิ่มข้อมูลการชำระเงิน</B></legend>

<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDCarTax</td>
        <td align="center">วันที่จ่าย</td>
        <td align="center">ใบเสร็จ</td>
        <td align="center">ประเภท</td>
        <td align="center">ยอดเงิน</td>
        <td align="center">ค่าธรรมเนียมอื่น</td>
        <td align="center">รวม</td>
        <td align="center">#</td>
    </tr>
    
<?php

    $qry_iff=pg_query("select A.*,B.* from carregis.\"CarTaxDue\" A LEFT OUTER JOIN carregis.\"DetailCarTax\" B on A.\"IDCarTax\" = B.\"IDCarTax\" 
    WHERE \"IDNO\"='$IDNO' AND \"TaxValue\" is not null ORDER BY A.\"IDCarTax\" DESC ");

    $rows = pg_num_rows($qry_iff);
    while($res_iff=pg_fetch_array($qry_iff)){
            $IDDetail = $res_iff["IDDetail"];
            $IDCarTax44 = $res_iff["IDCarTax"];
            $IDNO44 = $res_iff["IDNO"];
            $TaxValue44 = $res_iff["TaxValue"];
            $ChargeValue44 = $res_iff["ChargeValue"];
            $CoPayDate44 = $res_iff["CoPayDate"];
            $TypePay = $res_iff["TypePay"];
            $BillNumber = $res_iff["BillNumber"];
                //$CoPayDate44 = date("Y-m-d",strtotime($CoPayDate44));
                
        $qry_name33=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypePay' ");
        if($res_name33=pg_fetch_array($qry_name33)){
            $TName = $res_name33["TName"];
        }
                
        $qry_name22=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO44' ");
        if($res_name22=pg_fetch_array($qry_name22)){
            $full_name22 = $res_name22["full_name"];
            $IDNO22 = $res_name22["IDNO"];
            $asset_type22 = $res_name22["asset_type"];
            $C_REGIS22 = $res_name22["C_REGIS"];
            $C_COLOR = $res_name22["C_COLOR"];
            $C_CARNAME = $res_name22["C_CARNAME"];
            $car_regis22 = $res_name22["car_regis"];
                if($asset_type22 == 1){ $show_regis22 = $C_REGIS22; } else { $show_regis22 = $car_regis22; }
        }else{
            $full_name22 = "ไม่พบข้อมูล";
            $show_regis22 = "ไม่พบข้อมูล";
        }
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$IDCarTax44"; ?></td>
        <td align="center"><?php echo "$CoPayDate44"; ?></td>
        <td align="left"><?php echo "$BillNumber"; ?></td>
        <td align="center"><?php echo "$TName"; ?></td>
        <td align="right"><?php echo number_format($TaxValue44,2); ?></td>
        <td align="right"><?php echo number_format($ChargeValue44,2); ?></td>
        <td align="right"><?php echo number_format($TaxValue44+$ChargeValue44,2); ?></td>
        <td align="center"><a href="#" onclick="javascript:popU('frm_other_pay_edit.php?id=<?php echo "$IDDetail"; ?>','POP_EDIT<?php echo $IDDetail; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')"><u>แก้ไข</u></a></td>
    </tr>
<?php
    }
    if($rows == 0){
?> 
    <tr><td colspan=10 align="center">- ไม่พบข้อมูล -</td></tr>
<?php } ?>
</table>

<form id="frm_1" name="frm_1" method="post" action="frm_other_pay_add_ok.php">
<input type="hidden" name="iduser" value="<?php echo $get_id_user; ?>">
<input type="hidden" name="cid" value="<?php echo $cid; ?>">
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
   <tr align="left">
      <td><b>เลขที่ระบบทะเบียน</b></td>
      <td colspan="3" class="text_gray"><?php echo $IDCarTax; ?></td>
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
      <td><b>วันที่ทำรายการ</b></td>
      <td colspan="0" class="text_gray"><?php echo $TaxDueDate; ?></td>
      <td><b>วันนัดลูกค้า</b></td>
      <td colspan="0" class="text_gray"><?php echo $ApointmentDate; ?></td>
   </tr>
   <tr align="left">
      <td><b>จำนวนเงิน</b></td>
      <td colspan="0" class="text_gray"><?php echo number_format($CusAmt,2); ?> บาท.</td>
      <td><b>บริการ</b></td>
      <td colspan="0" class="text_gray"><?php echo $TName1; ?></td>
   </tr>
   
   <tr align="left" valign="top">
      <td><b>เลือกประเภทบริการ</b></td>
      <td colspan="3">
<div id="files-root">

<select name="typepay"><option value="">เลือก</option>

<?php 
    $qry_inf=pg_query("select * from \"TypePay\" WHERE \"TypeDep\"='C' ORDER BY \"TypeID\" ASC");
    while($res_inf=pg_fetch_array($qry_inf)){
        $TypeID = $res_inf["TypeID"];
        $S_TName = $res_inf["TName"];
        if($TypeDep == $TypeID)
            echo "<option value=\"$TypeID\" selected>$S_TName</option>";
        else
            echo "<option value=\"$TypeID\">$S_TName</option>";
    }

?>

</select>
</div>
      </td>
   </tr>

   
   <tr align="left" valign=top>
      <td><b>เลขที่ใบเสร็จ</b></td>
      <td colspan="0" class="text_gray"><input type="text" name="billnumber" size="17"></td>
      <td><b>วันที่ตามใบเสร็จ</b></td>
      <td colspan="0">
        <input name="copaydate" id="copaydate" type="text" readonly="true" size="15" value="<?php echo date("Y-m-d"); ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.copaydate,'yyyy-mm-dd',this)" value="ปฏิทิน"/>      
      </td>
   </tr>
   <tr align="left">
      <td><b>จำนวนเงินตามใบเสร็จ</b></td>
      <td colspan="3" class="text_gray"><input type="text" name="taxvalue" size="15"> บาท.</td>
 <!--     <td><b>ค่าธรรมเนียมอื่นๆ</b></td>
      <td colspan="0" class="text_gray"><input type="text" name="chargevalue" size="15"> บาท.</td>-->
   </tr>
   <tr align="left">
      <td><b>เพิ่มหมายเหตุ</b></td>
      <td colspan="3"><textarea name="remark" rows="5" cols="50" style="font-size:11px"></textarea></td>
   </tr>
   <tr align="left">
      <td><b>หมายเหตุ</b> </td>
      <td colspan="2"><textarea name="hiddenremark" rows="5" cols="50" style="font-size:11px; background-color:#E0E0E0;" readonly><?php echo $remark; ?></textarea></td>
      <td align="center" style="background-color:#FFFFCE;"><input type="button" value="เพิ่มค่าธรรมเนียม" onclick="javascript:popU('frm_car_pay_charge_add.php?cid=<?php echo $cid; ?>','P<?php echo $cid; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"></td>
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