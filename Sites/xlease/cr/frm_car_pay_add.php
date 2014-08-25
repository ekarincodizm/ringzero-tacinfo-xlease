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

<fieldset><legend><B>เพิ่มข้อมูลการชำระเงิน</B></legend>

<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDCarTax</td>
        <td align="center">วันที่จ่าย</td>
        <td align="center">ประเภท</td>
        <td align="center">หมายเลขใบเสร็จ</td>
        <td align="center">ยอดเงิน</td>
        <td align="center">#</td>
    </tr>
    
<?php
    $NubLobOne = 0;
    $qry_iff=pg_query("select A.*,B.* from carregis.\"CarTaxDue\" A LEFT OUTER JOIN carregis.\"DetailCarTax\" B on A.\"IDCarTax\" = B.\"IDCarTax\" 
    WHERE \"IDNO\"='$IDNO' AND \"TaxValue\" is not null ORDER BY A.\"IDCarTax\" DESC ");
    
    $rows = pg_num_rows($qry_iff);
    while($res_iff=pg_fetch_array($qry_iff)){
        $IDDetail = $res_iff["IDDetail"];
        $IDCarTax44 = $res_iff["IDCarTax"];
        $IDNO44 = $res_iff["IDNO"];
        $CoPayDate44 = $res_iff["CoPayDate"];
        $TaxValue44 = $res_iff["TaxValue"];
        $BillNumber = $res_iff["BillNumber"];
        //$TypeDep = $res_iff["TypeDep"];
        //$TypePay[] = $res_iff["TypePay"];
        $TypePay[] = $res_iff["TypePay"]."_".$IDCarTax44;
        
        if($res_iff["TypePay"] == -1){
            $NubLobOne += 1;
        }
        
        $qry_name33=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$res_iff[TypePay]' ");
        if($res_name33=pg_fetch_array($qry_name33)){
            $TName = $res_name33["TName"];
        }

        
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
        <td align="center"><?php echo "$CoPayDate44"; ?></td>
        <td align="left"><?php echo "$TName"; ?></td>
        <td align="center"><?php echo "$BillNumber"; ?></td>
        <td align="right"><?php echo number_format($TaxValue44,2); ?></td>
        <td align="center"><a href="#" onclick="javascript:popU('frm_other_pay_edit.php?id=<?php echo "$IDDetail"; ?>','POP_EDIT<?php echo $IDDetail; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')"><u>แก้ไข</u></a></td>
    </tr>
<?php
    }
    if($rows == 0){
?> 
    <tr><td colspan=10 align="center">- ไม่พบข้อมูล -</td></tr>
<?php } ?>
</table> 

<?php
$c_101 = @in_array('101_'.$cid, $TypePay);
$c_105 = @in_array('105_'.$cid, $TypePay);

$chk_gg = 0;
$qry_inf=pg_query("select * from \"TypePay\" WHERE \"TypeDep\"='C' AND \"TypeID\"<>'101' AND \"TypeID\"<>'105' AND \"TypeID\"<>'-1' ORDER BY \"TypeID\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $TypeID = $res_inf["TypeID"];
    if(@in_array($TypeID.'_'.$cid, $TypePay)){
        $chk_gg++;
    }
}
?>

<form id="frm_1" name="frm_1" method="post" action="frm_car_pay_add_ok.php" onsubmit="return validate(this)">
<input type="hidden" name="iduser" value="<?php echo $get_id_user; ?>">
<input type="hidden" name="cid" value="<?php echo $cid; ?>">
<input type="hidden" name="typedep" value="<?php echo $TypeDep; ?>">
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
   <tr align="left">
      <td><b>เลขที่ระบบทะเบียน</b></td>
      <td class="text_gray"><?php echo $cid; ?></td>
      <td><b>วันที่นัดลูกค้า</b></td>
      <td class="text_gray"><?php echo $ApointmentDate; ?></td>
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
      <td colspan="0" class="text_gray"><?php echo "$show_meter"; ?></td>
      <td><b>ค่าใช้จ่าย</b></td>
      <td colspan="0" class="text_gray"><?php echo $CusAmt; ?> บาท.</td>
   </tr>
<?php if(empty($c_101) AND empty($c_105)){ ?>
    <tr align="left">
      <td><b>เลขที่ใบเสร็จ (มิเตอร์)</b></td>
      <td class="text_gray"><input type="text" name="billnumber1"></td>
      <td colspan="2"><b>จำนวนเงิน</b> <input type="text" name="taxvalue1" size="10">
      <b>วันที่</b> <input name="copaydate1" id="copaydate1" type="text" readonly="true" size="15" value="<?php echo date("Y-m-d"); ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.copaydate1,'yyyy-mm-dd',this)" value="ปฏิทิน"/>      
      </td>
   </tr>
<?php }elseif(empty($c_105)){ ?>
   <tr align="left">
      <td><b>เลขที่ใบเสร็จ (มิเตอร์)</b></td>
      <td class="text_gray"><input type="text" name="billnumber1"></td>
      <td colspan="2"><b>จำนวนเงิน</b> <input type="text" name="taxvalue1" size="10">
      <b>วันที่</b> <input name="copaydate1" id="copaydate1" type="text" readonly="true" size="15" value="<?php echo date("Y-m-d"); ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.copaydate1,'yyyy-mm-dd',this)" value="ปฏิทิน"/>      
      </td>
   </tr>
<?php }elseif(empty($c_101) AND $TypeDep == '101'){ ?>
   <tr align="left">
      <td><b>เลขที่ใบเสร็จ (ภาษี)</b></td>
      <td class="text_gray"><input type="text" name="billnumber2"></td>
      <td colspan="2"><b>จำนวนเงิน</b> <input type="text" name="taxvalue2" size="10">
      <b>วันที่</b> <input name="copaydate2" id="copaydate2" type="text" readonly="true" size="15" value="<?php echo date("Y-m-d"); ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.copaydate2,'yyyy-mm-dd',this)" value="ปฏิทิน"/>      
      </td>
   </tr>
<?php }elseif( !empty($c_101) AND !empty($c_105) AND $chk_gg==0 ){ ?>
   <tr align="left">
      <td><b>ค่าอื่นๆ</b></td>
      <td>
<b>เลขที่ใบเสร็จ</b>
<input type="text" name="billnumber3" size="13">
<b>ประเภท</b>
<select name="selecttype" id="selecttype">
<?php
$qry_inf=pg_query("select * from \"TypePay\" WHERE \"TypeDep\"='C' ORDER BY \"TypeID\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $TypeID = $res_inf["TypeID"];
    $TName = $res_inf["TName"];
    echo "<option value=\"$TypeID\">$TName</option>";
}
?>
</select>
      </td>
      <td colspan="2"><b>จำนวนเงิน</b> <input type="text" name="taxvalue3" size="10">
      <b>วันที่</b> <input name="copaydate3" id="copaydate3" type="text" readonly="true" size="15" value="<?php echo date("Y-m-d"); ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.copaydate3,'yyyy-mm-dd',this)" value="ปฏิทิน"/>      
      </td>
   </tr>
<?php }elseif( !empty($c_105) AND $TypeDep == '105' AND $chk_gg==0 ){ ?>
   <tr align="left">
      <td><b>ค่าอื่นๆ</b></td>
      <td>
<b>เลขที่ใบเสร็จ</b>
<input type="text" name="billnumber3" size="13">
<b>ประเภท</b>
<select name="selecttype" id="selecttype">
<?php
$qry_inf=pg_query("select * from \"TypePay\" WHERE \"TypeDep\"='C' ORDER BY \"TypeID\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $TypeID = $res_inf["TypeID"];
    $TName = $res_inf["TName"];
    echo "<option value=\"$TypeID\">$TName</option>";
}
?>
</select>
      </td>
      <td colspan="2"><b>จำนวนเงิน</b> <input type="text" name="taxvalue3" size="10">
      <b>วันที่</b> <input name="copaydate3" id="copaydate3" type="text" readonly="true" size="15" value="<?php echo date("Y-m-d"); ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.copaydate3,'yyyy-mm-dd',this)" value="ปฏิทิน"/>      
      </td>
   </tr>
<?php } ?>

   <tr align="left">
      <td><b>เพิ่มหมายเหตุ</b></td>
      <td colspan="4"><textarea name="remark" rows="5" cols="50" style="font-size:11px"></textarea></td>
   </tr>
   <tr align="left">
      <td><b>หมายเหตุ</b> </td>
      <td><textarea name="hiddenremark" rows="5" cols="50" style="font-size:11px; background-color:#E0E0E0;" readonly><?php echo $remark; ?></textarea></td>

      <td align="center" style="background-color:#CEFFCE;">
<input type="button" value="    รับเล่มเข้า    " onclick="window.location='frm_book_in_d_send.php?cid=<?php echo $cid; ?>'">      
    </td>
      <td align="center" style="background-color:#FFFFCE;">
<input type="button" value="เพิ่มค่าธรรมเนียม" onclick="javascript:popU('frm_car_pay_charge_add.php?cid=<?php echo $cid; ?>','P<?php echo $cid; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')">
    </td>
<!--
<?php if($TypeDep == '105' AND $NubLobOne == 0){ ?>
      <td align="center" style="background-color:#FFFFCE;"><input type="button" value="เพิ่มค่าธรรมเนียม" onclick="javascript:popU('frm_car_pay_charge_add.php?cid=<?php echo $cid; ?>','P<?php echo $cid; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"></td>
<?php }else{ ?>

<?php } ?>

<?php if($TypeDep == '101' AND $NubLobOne != 2){ ?>
      <td align="center" style="background-color:#FFFFCE;"><input type="button" value="เพิ่มค่าธรรมเนียม" onclick="javascript:popU('frm_car_pay_charge_add.php?cid=<?php echo $cid; ?>','P<?php echo $cid; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"></td>
<?php }else{ ?>

<?php } ?>
-->

   </tr>
</table>
</fieldset>

<?php if(empty($c_101) AND empty($c_105)){ ?>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   บันทึก   "></td>
   </tr>
</table>
<?php }elseif(empty($c_105)){ ?>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   บันทึก   "></td>
   </tr>
</table>
<?php }elseif(empty($c_101) AND $TypeDep == '101'){ ?>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   บันทึก   "></td>
   </tr>
</table>
<?php }elseif( !empty($c_101) AND !empty($c_105) AND $chk_gg==0 ){ ?>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   บันทึก   "></td>
   </tr>
</table>
<?php }elseif( !empty($c_105) AND $TypeDep == '105' AND $chk_gg==0 ){ ?>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
   <tr align="center">
      <td><br><input type="submit" name="submit" value="   บันทึก   "></td>
   </tr>
</table>
<?php } ?>

</form>

</div>
        </td>
    </tr>
</table>

</body>
</html>