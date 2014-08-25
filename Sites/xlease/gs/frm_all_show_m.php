<?php 
include("../config/config.php"); 
if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
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
 
<fieldset><legend><b>แสดงรายการ รายเดือน</b></legend>

<form method="post" action="" name="f_list" id="f_list">
<div align="left">
<b>เลือกเดือน</b>
<select name="mm">
<?php
if(empty($mm)){
    $nowmonth = date("m");
}else{
    $nowmonth = $mm;
}
$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
for($i=0; $i<12; $i++){
    $a+=1;
    if($a > 0 AND $a <10) $a = "0".$a;
    if($nowmonth != $a){
        echo "<option value=\"$a\">$month[$i]</option>";
    }else{
        echo "<option value=\"$a\" selected>$month[$i]</option>";
    }
    
}
?>    
</select>
<b>ปี</b> 
<select name="yy">
<?php
if(empty($yy)){
    $nowyear = date("Y");
}else{
    $nowyear = $yy;
}
$year_a = $nowyear + 5; 
$year_b =  $nowyear - 5;

$s_b = $year_b+543;

while($year_b <= $year_a){
    if($nowyear != $year_b){
        echo "<option value=\"$year_b\">$s_b</option>";
    }else{
        echo "<option value=\"$year_b\" selected>$s_b</option>";
    }
    $year_b += 1;
    $s_b +=1;
}
?>
</select><input type="submit" name="submit" value="ค้นหา">
</div>
</form>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">ประเภทบริการ</td>
        <td align="center">ลูกค้าชำระ</td>
        <td align="center">เลขที่ใบเสร็จ</td>
        <td align="center">ยอดชำระ</td>
        <td align="center">ค่าธรรมเนียม</td>
        <td align="center">รวมเงิน</td>
        <td align="center" bgcolor="#FFBBFF">วันที่ชำระ</td>
        <td align="center" bgcolor="#FFBBFF">เลขที่ใบเสร็จ</td>
        <td align="center" bgcolor="#FFBBFF">จำนวนเงิน</td>
        <td align="center" bgcolor="#FFBBFF">สถานะการชำระ</td>
    </tr>
   
<?php
if( isset($mm) ){

        $qry_name=pg_query("SELECT * FROM carregis.\"CarTaxDue\" where EXTRACT(MONTH FROM \"ApointmentDate\")='$mm' AND EXTRACT(YEAR FROM \"ApointmentDate\")='$yy' ORDER BY \"IDNO\" ASC ");
        $rows = pg_num_rows($qry_name);
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $CusAmt = $res_name["CusAmt"];
            $TypeDep = $res_name["TypeDep"];
            $ApointmentDate = $res_name["ApointmentDate"]; if(empty($ApointmentDate)) $ApointmentDate = "-";
            $MeterTax = $res_name["MeterTax"];
            $TaxValue = $res_name["TaxValue"];
            $ChargeValue = $res_name["ChargeValue"];
            $BillNumber = $res_name["BillNumber"];
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
                
                $summary = $TaxValue+$ChargeValue;
                
                $sum_TaxValue+=$TaxValue;
                $sum_ChargeValue+=$ChargeValue;
                $sum_CusAmt+=$CusAmt;
                $sum_summary+=$summary;
            
        $qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        if($res_name2=pg_fetch_array($qry_name2)){
            $asset_id = $res_name2["asset_id"];
            $full_name = $res_name2["full_name"];
            $asset_type = $res_name2["asset_type"];
            $C_REGIS = $res_name2["C_REGIS"];
            $car_regis = $res_name2["car_regis"];
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
        }else{
            $full_name = "ไม่พบข้อมูล";
            $show_regis = "ไม่พบข้อมูล";
        }
        
            $O_DATE = "";
            $O_RECEIPT = "";
            $O_MONEY = "";
            $PayType = "";
        $qry_vcus=pg_query("select * from \"FOtherpay\" WHERE  \"RefAnyID\"='$IDCarTax'");
        if($resvc=pg_fetch_array($qry_vcus)){
            $O_DATE = $resvc["O_DATE"];
            $O_RECEIPT = $resvc["O_RECEIPT"];
            $O_MONEY = $resvc["O_MONEY"];
            $PayType = $resvc["PayType"];
            $sum_O_MONEY+=$O_MONEY;
        }
        
        if(!empty($TypeDep)){
            $TName = "";
            $pieces = explode(",", $TypeDep);
            for($i=0; $i<count($pieces);$i++){
                    $get_type = $pieces[$i];
                    $qry_name4=pg_query("select * from \"TypePay\" WHERE \"TypeID\"='$get_type' ");
                    if($res_name4=pg_fetch_array($qry_name4)){
                        if(count($pieces) == $i+1){  
                            $TName .= $res_name4["TName"];
                        }else{
                            $TName .= $res_name4["TName"].",";
                        }
                    }
            }
        }else{
            if($MeterTax == 't'){
                $TName = "มิเตอร์/ภาษี";
            }else{
                $TName = "มิเตอร์";
            }
        }
        
        $in+=1;
        if($in%2==0){
            echo "<tr class=\"odd\" valign=\"top\">";
        }else{
            echo "<tr class=\"even\" valign=\"top\">";
        }
?>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$show_regis"; ?></td>
        <td align="left"><?php echo "$TName"; ?></td>
        <td align="right"><?php echo number_format($CusAmt,2); ?></td>
        <td align="left"><?php echo "$BillNumber"; ?></td>
        <td align="right"><?php echo number_format($TaxValue,2); ?></td>
        <td align="right"><?php echo number_format($ChargeValue,2); ?></td>
        <td align="right"><?php echo number_format($summary,2); ?></td>
        <?php if($in%2==0){ ?>
        <td align="center" bgcolor="#FFFBFF"><?php echo "$O_DATE"; ?></td>
        <td align="left" bgcolor="#FFFBFF"><?php echo "$O_RECEIPT"; ?></td>
        <td align="right" bgcolor="#FFFBFF"><?php echo number_format($O_MONEY,2); ?></td>
        <td align="left" bgcolor="#FFFBFF"><?php echo "$PayType"; ?></td>
        <?php }else{ ?>
        <td align="center" bgcolor="#FFEAFF"><?php echo "$O_DATE"; ?></td>
        <td align="left" bgcolor="#FFEAFF"><?php echo "$O_RECEIPT"; ?></td>
        <td align="right" bgcolor="#FFEAFF"><?php echo number_format($O_MONEY,2); ?></td>
        <td align="left" bgcolor="#FFEAFF"><?php echo "$PayType"; ?></td>
        <?php } ?>
    </tr>
 <?php
        }
}

if($rows > 0){

 ?>
    <tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
        <td align="right" colspan="4">รวมยอดเงิน</td>
        <td align="right"><?php echo number_format($sum_CusAmt,2); ?></td>
        <td></td>
        <td align="right"><?php echo number_format($sum_TaxValue,2); ?></td>
        <td align="right"><?php echo number_format($sum_ChargeValue,2); ?></td>
        <td align="right"><?php echo number_format($sum_summary,2); ?></td>
        <td colspan="2"></td>
        <td align="right"><?php echo number_format($sum_O_MONEY,2); ?></td>
        <td></td>
    </tr>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="2"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td align="right" colspan="14"><a href="frm_all_show_m_print.php?m=<?php echo "$mm"; ?>&y=<?php echo "$yy"; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์</b></a></td>
    </tr>                                                                      
<?php } ?>
</table>

</div>

        </td>
	</tr>
</table>

</body>
</html>