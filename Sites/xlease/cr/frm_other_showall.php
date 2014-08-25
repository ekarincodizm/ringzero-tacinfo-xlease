<?php 
include("../config/config.php"); 
if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    
</head>
<body>
<?php include("menu.php"); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td>

<fieldset><legend><b>แสดงรายการชำระค่าอื่นๆ รายเดือน</b></legend>

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

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">วันครบกำหนด</td>
        <td align="center">จำนวนเงิน</td>
        <td align="center">รูปแบบ</td>
        <td align="center">วันนัด</td>
        <td align="center">สถานะ</td>
    </tr>
   
<?php
if( isset($mm) ){

        $qry_name=pg_query("select * from carregis.\"CarTaxDue\"
        where EXTRACT(MONTH FROM \"TaxDueDate\")='$mm' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$yy' AND (\"TypeDep\"!='101' AND \"TypeDep\"!='105' AND \"TypeDep\"!='-1') ORDER BY \"TaxDueDate\" ASC ");
    
        $rows = pg_num_rows($qry_name);
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $CusAmt = $res_name["CusAmt"];
            $TypeDep = $res_name["TypeDep"];
            $ApointmentDate = $res_name["ApointmentDate"];
            $TaxValue = $res_name["TaxValue"];
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            
        $qry_name2=pg_query("select \"asset_id\",\"full_name\",\"asset_type\",\"C_REGIS\",\"car_regis\" from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
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
        

$qry_name4=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypeDep' ");
if($res_name4=pg_fetch_array($qry_name4)){
    $TName = $res_name4["TName"];
}

        
        $in+=1;
        if($in%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$show_regis"; ?></td>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="right"><?php echo number_format($CusAmt,2); ?></td>
        <td align="left"><?php echo "$TName"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td>
        <td align="center">
            <?php if( empty($TaxValue) ){ ?>
                <img src="clock.png" border="0" width="16" height="16" align="absmiddle" alt="รอข้อมูลการชำระเงิน">
            <?php }else{ ?>
                <img src="accept.png" border="0" width="16" height="16" align="absmiddle" alt="สำเร็จ">
            <?php } ?>
        </td>
    </tr>
 <?php
        }
}

if($rows > 0){

 ?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="2"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td align="right" colspan="10"><a href="frm_other_showall_print.php?m=<?php echo "$mm"; ?>&y=<?php echo "$yy"; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์</b></a></td>
    </tr>                                                                      
<?php } ?>
</table>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>