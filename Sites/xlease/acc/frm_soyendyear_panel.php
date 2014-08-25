<?php
include("../config/config.php");
$yy = pg_escape_string($_GET['yy']);

$qry_name=pg_query("SELECT \"acclosedate\" FROM account.\"VSOYEndYear\" where EXTRACT(YEAR FROM \"acclosedate\")='$yy' ORDER BY \"idno\" ASC ");
if($res_name=pg_fetch_array($qry_name)){
    $acclosedate = $res_name["acclosedate"];
}
?>
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#FFFFFF">
        <td colspan="2" align="left">ปี <?php echo $yy; ?></td>
        <td colspan="4" align="center" bgcolor="#79BCFF">รายการที่เกิดขึ้น ณ วันที่ : <?php echo $acclosedate; ?></td>
        <td colspan="8"></td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">เลขที่สัญญา</td>
        <td align="center">ชื่อผู้เช่าซื้อ</td>
        <td align="center">จำนวนงวดที่ค้าง</td>
        <td align="center">งวดชำระ</td>
        <td align="center">งวดต้องชำระ</td>
        <td align="center">งวดใช้รับรู้รายได้</td>
        <td align="center">ดอกผลรับรู้</td>
        <td align="center">ดอกผลคงเหลือ</td>
        <td align="center">ดอกผลทั้งหมด</td>
        <td align="center">ลูกหนี้คงเหลือ</td>
        <td align="center">ลูกหนี้สุทธิ</td>
        <td align="center">มูลค่าลูกหนี้สุทธิ<br>หลังหักหลักประกัน</td>
        <td align="center">อัตราสำรอง</td>
        <td align="center">หนี้สงสัยจะสูญ</td>
    </tr>
   
<?php
if( !empty($yy) ){

$t_overdue = 0;
$qry_name=pg_query("SELECT * FROM account.\"VSOYEndYear\" where EXTRACT(YEAR FROM \"acclosedate\")='$yy' ORDER BY overdue,\"idno\" ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $idno = $res_name["idno"];
    $customer_name = $res_name["customer_name"];
    $effpay = $res_name["effpay"];
    $overdue = $res_name["overdue"];
    $paid = $res_name["paid"];
    $mustpay = $res_name["mustpay"];
    $rlthisy = $res_name["rlthisy"]; $rlthisy = round($rlthisy,2);
    $rltothisy = $res_name["rltothisy"]; $rltothisy = round($rltothisy,2);
    $rlremain = $res_name["rlremain"]; $rlremain = round($rlremain,2);
    $rlall = $res_name["rlall"]; $rlall = round($rlall,2);
    
    $aroutstanding = $res_name["aroutstanding"]; $aroutstanding = round($aroutstanding,2);
    $urtotal = $res_name["urtotal"]; $urtotal = round($urtotal,2);
    $aroutafterguarantee = $res_name["aroutafterguarantee"]; $aroutafterguarantee = round($aroutafterguarantee,2);
    $writeoffrate = $res_name["writeoffrate"];
    $backupwriteoff = $res_name["backupwriteoff"]; $backupwriteoff = round($backupwriteoff,2);
    
    $sum_rlthisy += $rlthisy;
    $sum_rlremain += $rlremain;
    $sum_rlall += $rlall;
    $sum_aroutstanding += $aroutstanding;
    $sum_urtotal += $aroutstanding-$urtotal;
    $sum_aroutafterguarantee += $aroutafterguarantee;
    $sum_backupwriteoff += $backupwriteoff;
    
    //------------------------------------------//
    if($t_overdue != $overdue){
    ?>
        <tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
            <td colspan="6" align="right">รวม</td>
            <td align="right"><?php echo number_format($t_sum_rlthisy,2); ?></td>
            <td align="right"><?php echo number_format($t_sum_rlremain,2); ?></td>
            <td align="right"><?php echo number_format($t_sum_rlall,2); ?></td>
            <td align="right"><?php echo number_format($t_sum_aroutstanding,2); ?></td>
            <td align="right"><?php echo number_format($t_sum_urtotal,2); ?></td>
            <td align="right"><?php echo number_format($t_sum_aroutafterguarantee,2); ?></td>
            <td></td>
            <td align="right"><?php echo number_format($t_sum_backupwriteoff,2); ?></td>
        </tr>
    <?php
        $t_sum_rlthisy = 0;
        $t_sum_rlremain = 0;
        $t_sum_rlall = 0;
        $t_sum_aroutstanding = 0;
        $t_sum_urtotal = 0;
        $t_sum_aroutafterguarantee = 0;
        $t_sum_backupwriteoff = 0;
    }
    
    $t_sum_rlthisy += $rlthisy;
    $t_sum_rlremain += $rlremain;
    $t_sum_rlall += $rlall;
    $t_sum_aroutstanding += $aroutstanding;
    $t_sum_urtotal += $aroutstanding-$urtotal;
    $t_sum_aroutafterguarantee += $aroutafterguarantee;
    $t_sum_backupwriteoff += $backupwriteoff;
    
    
    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
    <td align="center"><?php echo "$idno"; ?></a></td>
    <td align="left"><?php echo "$customer_name"; ?></td>
    <td align="center"><?php echo "$overdue"; ?></td>
    <td align="center"><?php echo "$paid"; ?></td>
    <td align="center"><?php echo "$mustpay"; ?></td>
    <td align="center"><?php echo "$effpay"; ?></td>
    <td align="right"><?php echo number_format($rlthisy,2); ?></td>
    <td align="right"><?php echo number_format($rlremain,2); ?></td>
    <td align="right"><?php echo number_format($rlall,2); ?></td>
    <td align="right"><?php echo number_format($aroutstanding,2); ?></td>
    <td align="right"><?php echo number_format($aroutstanding-$urtotal,2); ?></td>
    <td align="right"><?php echo number_format($aroutafterguarantee,2); ?></td>
    <td align="center"><?php echo $writeoffrate; ?></td>
    <td align="right"><?php echo number_format($backupwriteoff,2); ?></td>
</tr>
<?php
$t_overdue = $overdue;
}

} // end check รับตัวแปร

if($rows > 0){
?>

    <tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
        <td colspan="6" align="right">รวม</td>
        <td align="right"><?php echo number_format($t_sum_rlthisy,2); ?></td>
        <td align="right"><?php echo number_format($t_sum_rlremain,2); ?></td>
        <td align="right"><?php echo number_format($t_sum_rlall,2); ?></td>
        <td align="right"><?php echo number_format($t_sum_aroutstanding,2); ?></td>
        <td align="right"><?php echo number_format($t_sum_urtotal,2); ?></td>
        <td align="right"><?php echo number_format($t_sum_aroutafterguarantee,2); ?></td>
        <td></td>
        <td align="right"><?php echo number_format($t_sum_backupwriteoff,2); ?></td>
    </tr>

    <tr bgcolor="#79BCFF" style="font-size:11px; font-weight:bold;">
        <td colspan="6" align="right">สรุปยอดสำหรับลูกค้าประจำปี</td>
        <td align="right"><?php echo number_format($sum_rlthisy,2); ?></td>
        <td align="right"><?php echo number_format($sum_rlremain,2); ?></td>
        <td align="right"><?php echo number_format($sum_rlall,2); ?></td>
        <td align="right"><?php echo number_format($sum_aroutstanding,2); ?></td>
        <td align="right"><?php echo number_format($sum_urtotal,2); ?></td>
        <td align="right"><?php echo number_format($sum_aroutafterguarantee,2); ?></td>
        <td></td>
        <td align="right"><?php echo number_format($sum_backupwriteoff,2); ?></td>
    </tr>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="2"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td align="right" colspan="12"><a href="frm_soyendyear_print.php?yy=<?php echo "$yy";?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>รายงานแสดงการรับรู้รายได้ตามงวดค้าง</b></a> | <a href="frm_soyendyear_age.php?yy=<?php echo "$yy";?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์รายงานแยกอายุลูกหนี้</b></a></td>
    </tr>
<?php
}else{
?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="center" colspan="20">- ไม่พบข้อมูล -</td>
    </tr>
<?php
}
?>
</table>