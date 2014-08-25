<?php
session_start();
include("../config/config.php");
$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$mm = $_GET['mm'];
$mm_text = $month[$mm];
$yy = $_GET['yy'];
$txt_yy = $_GET['yy']+543;
$nowyear = date('Y');
$yearlater = 10;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<style type="text/css">
.divlist {
    background-color: #F0F0F0;
}
.divlist:hover{
    background-color: #D0D0D0;
}
</style>

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>พิมพ์รายงานสรุป รายวันรับเงิน</B></legend>

<div style="float:left; margin:3px; font-weight:bold"><?php echo "เดือน $mm_text ปี $txt_yy"; ?></div>
<div style="float:right"><input name="button" type="button" onClick="window.open('frm_ten_show_pdf.php?mm=<?php echo $mm; ?>&yy=<?php echo $yy; ?>','sad38dn2821kdk219','')" value="พิมพ์รายงาน PDF" /></div>
<div style="clear:both"></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#E0E0E0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
  <td align="center">วันที่</td>
  <td align="center">ค่าอื่นๆ</td>
<?php
for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
?>
    <td align="center">ปี <?php echo $i+543; ?></td>
<?php
}
?>
</tr>
<?php
$qry_in=pg_query("SELECT * FROM \"Fr\" where EXTRACT(MONTH FROM \"R_Date\")='$mm' AND EXTRACT(YEAR FROM \"R_Date\")='$yy' AND \"Cancel\"='false' 
ORDER BY \"R_Date\",\"R_Receipt\",\"R_DueNo\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $IDNO = $res_in["IDNO"];
    $R_DueNo = $res_in["R_DueNo"];
    $R_Receipt = $res_in["R_Receipt"];
    $R_Date = $res_in["R_Date"];    if($j==1) $old_date = $R_Date;
    $R_Money = $res_in["R_Money"];
    $R_Bank = $res_in["R_Bank"];
    $cur_year = $res_in["CustYear"];
    $arr_CustYear[] = $res_in["CustYear"];
    
    $vat = 0; //set default VAT.
    $qry_vat=pg_query("select \"VatValue\" from \"FVat\" WHERE (\"IDNO\"='$IDNO' AND \"V_DueNo\"='$R_DueNo')");
    if($res_vat=pg_fetch_array($qry_vat)){
        $vat = $res_vat["VatValue"];
    }


    if($R_Date != $old_date){
        
        $qry_date_number=@pg_query("select \"c_date_number\"('$old_date')");
        $fm_old_date=@pg_fetch_result($qry_date_number,0);

?>

<tr class="divlist">
    <td align="center"><?php echo "$fm_old_date"; ?></td>
    <td align="right"><?php echo number_format($sumother,2); ?><br /><?php echo number_format($sumothervat,2); ?></td>
<?php
    for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
        $money = number_format(${'sum_'.$i},2);
        $moneyvat = number_format(${'sumvat_'.$i},2);
        echo "<td align=\"right\">$money<br />$moneyvat</td>";
        $money = 0;
        $moneyvat = 0;
        ${'sum_'.$i} = 0;
        ${'sumvat_'.$i} = 0;
    }
?>
</tr>

<?php
        $sumother = 0;
        $sumothervat = 0;
    }
    $old_date = $R_Date;
    
    if($R_DueNo > 98){
        $sumother += $R_Money;
        $sumothervat += $vat;
        $allsumother += $R_Money;
        $allsumothervat += $vat;
    }else{
        for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
            if($cur_year == $i){
                ${'sum_'.$i} += $R_Money;
                ${'sumvat_'.$i} += $vat;
                ${'allsum_'.$i} += $R_Money;
                ${'allsumvat_'.$i} += $vat;
            }
        }
    }

}//end while

        $qry_date_number=@pg_query("select \"c_date_number\"('$old_date')");
        $fm_old_date=@pg_fetch_result($qry_date_number,0);
?>

<tr class="divlist">
    <td align="center"><?php echo "$fm_old_date"; ?></td>
    <td align="right"><?php echo number_format($sumother,2); ?><br /><?php echo number_format($sumothervat,2); ?></td>
<?php
    for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
        $money = number_format(${'sum_'.$i},2);
        $moneyvat = number_format(${'sumvat_'.$i},2);
        echo "<td align=\"right\">$money<br />$moneyvat</td>";
        $money = 0;
        $moneyvat = 0;
        ${'sum_'.$i} = 0;
        ${'sumvat_'.$i} = 0;
    }
?>
</tr>

<tr bgcolor="#FFFFCE">
    <td align="center"><b>Total</b></td>
    <td align="right"><?php echo number_format($allsumother,2); ?><br /><?php echo number_format($allsumothervat,2); ?></td>
<?php
    for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
        $money = number_format(${'allsum_'.$i},2);
        $moneyvat = number_format(${'allsumvat_'.$i},2);
        echo "<td align=\"right\">$money<br />$moneyvat</td>";
        $money = 0;
        $moneyvat = 0;
        ${'allsum_'.$i} = 0;
        ${'allsumvat_'.$i} = 0;
    }
?>
</tr>

</table>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>