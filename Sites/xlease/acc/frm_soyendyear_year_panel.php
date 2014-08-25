<?php
include("../config/config.php");
$datepicker = pg_escape_string($_GET['datepicker']);
$sort = pg_escape_string($_GET['sort']);
?>

<script type="text/javascript">
$('#btnsort1').click(function(){
    $("#showpanel").empty();
    $("#showpanel").html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
    $("#showpanel").load("frm_soyendyear_year_panel.php?sort=custyear&datepicker="+ $("#datepicker").val());
});
$('#btnsort2').click(function(){
    $("#showpanel").empty();
    $("#showpanel").html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
    $("#showpanel").load("frm_soyendyear_year_panel.php?sort=overdue&datepicker="+ $("#datepicker").val());
});
</script>

<style type="text/css">
.result {
    height: 400px;
    width: 100%;
    overflow: scroll;
    border: 0px solid #C0C0C0;
    background-color: #FFFFFF;
    padding: 0 0 0 0;
    margin: 0 0 0 0;
}
</style>

<div align="right">
<input type="button" name="btnsort1" id="btnsort1" value="เรียงตาม ปีสัญญา"><input type="button" name="btnsort2" id="btnsort2" value="เรียงตาม จำนวนงวดที่ค้าง">
</div>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#FFFFFF">
        <td colspan="3"></td>
        <td colspan="4" align="center" bgcolor="#79BCFF">รายการที่เกิดขึ้น ณ วันที่ : <?php echo $datepicker; ?></td>
        <td colspan="8"></td>
    </tr>
    <tr style="font-weight:bold; text-align:center" valign="middle" bgcolor="#79BCFF">
        <td>เลขที่<br />สัญญา</td>
        <td>ชื่อผู้เช่าซื้อ</td>
        <td>ปีสัญญา</td>
        <td>จำนวน<br />งวดที่ค้าง</td>
        <td>งวดชำระ</td>
        <td>งวดต้อง<br />ชำระ</td>
        <td>งวดใช้<br />รับรู้รายได้</td>
        <td>ดอกผล<br />รับรู้</td>
        <td>ดอกผล<br />คงเหลือ</td>
        <td>ดอกผล<br />ทั้งหมด</td>
        <td>ลูกหนี้<br />คงเหลือ</td>
        <td>ลูกหนี้<br />สุทธิ</td>
        <td>มูลค่าลูกหนี้สุทธิ<br>หลังหักหลักประกัน</td>
        <td>อัตรา<br />สำรอง</td>
        <td>หนี้สงสัย<br />จะสูญ</td>
    </tr>

<?php
$inub = 0;

if($sort == "custyear"){
    $qry_name=pg_query("SELECT * FROM account.\"VSOYEndYear\" where \"acclosedate\"='$datepicker' ORDER BY custyear,idno ASC ");
}elseif($sort == "overdue"){
    $qry_name=pg_query("SELECT * FROM account.\"VSOYEndYear\" where \"acclosedate\"='$datepicker' ORDER BY overdue DESC,custyear,idno ASC ");
}

$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $inub+=1;
    $idno = $res_name["idno"];
    $custyear = $res_name["custyear"];
    $customer_name = $res_name["customer_name"];
    $effpay = $res_name["effpay"];
    $overdue = $res_name["overdue"];
    $paid = $res_name["paid"];
    $mustpay = $res_name["mustpay"];
    $rlthisy = $res_name["rlthisy"];
    $rltothisy = $res_name["rltothisy"];
    $rlremain = $res_name["rlremain"];
    $rlall = $res_name["rlall"];
    
    if($inub==1){
        $t_overdue = $custyear;
        $t_overdue2 = $overdue;
    }
    
    $aroutstanding = $res_name["aroutstanding"];
    $urtotal = $res_name["urtotal"];
    $aroutafterguarantee = $res_name["aroutafterguarantee"];
    $writeoffrate = $res_name["writeoffrate"];
    $backupwriteoff = $res_name["backupwriteoff"];
    
    $sum_rlthisy += $rlthisy;
    $sum_rltothisy += $rltothisy;
    $sum_rlremain += $rlremain;
    $sum_rlall += $rlall;
    $sum_aroutstanding += $aroutstanding;
    $sum_urtotal += ($aroutstanding-$urtotal);
    $sum_aroutafterguarantee += $aroutafterguarantee;
    $sum_backupwriteoff += $backupwriteoff;
    
    //------------------------------------------//
    if(($t_overdue != $custyear) AND $sort == "custyear"){
    ?>
        <tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
            <td colspan="7" align="right">จำนวน <?php echo $nub_show; ?> รายการ | รวมยอดเงิน</td>
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
        $nub_show = 0;
        $t_sum_rlthisy = 0;
        $t_sum_rltothisy = 0;
        $t_sum_rlremain = 0;
        $t_sum_rlall = 0;
        $t_sum_aroutstanding = 0;
        $t_sum_urtotal = 0;
        $t_sum_aroutafterguarantee = 0;
        $t_sum_backupwriteoff = 0;
    }
    
    if(($t_overdue2 != $overdue) AND $sort == "overdue"){
    ?>
        <tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
            <td colspan="7" align="right">จำนวน <?php echo $nub_show; ?> รายการ | รวมยอดเงิน</td>
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
        $nub_show = 0;
        $t_sum_rlthisy = 0;
        $t_sum_rltothisy = 0;
        $t_sum_rlremain = 0;
        $t_sum_rlall = 0;
        $t_sum_aroutstanding = 0;
        $t_sum_urtotal = 0;
        $t_sum_aroutafterguarantee = 0;
        $t_sum_backupwriteoff = 0;
    }
    
    $t_sum_rlthisy += $rlthisy;
    $t_sum_rltothisy += $rltothisy;
    $t_sum_rlremain += $rlremain;
    $t_sum_rlall += $rlall;
    $t_sum_aroutstanding += $aroutstanding;
    $t_sum_urtotal += ($aroutstanding-$urtotal);
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
    <td align="center"><?php echo "$custyear"; ?></td>
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
$nub_show++;
$t_overdue = $custyear;
$t_overdue2 = $overdue;
}

if($rows > 0){
?>
    <tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
        <td colspan="7" align="right">จำนวน <?php echo $nub_show; ?> รายการ | รวมยอดเงิน</td>
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
        <td colspan="7" align="right">สรุปยอดสำหรับลูกค้าประจำปี</td>
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
        <td align="right" colspan="13"><a href="frm_soyendyear_print_year.php?yy=<?php echo "$datepicker";?>&sort=<?php echo "$sort"; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>พิมพ์รายงาน</b></a></td>
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

</div>