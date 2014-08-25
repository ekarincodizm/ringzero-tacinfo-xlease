<?php
include("../config/config.php");
$mm = pg_escape_string($_POST['mm']);
$yy = pg_escape_string($_POST['yy']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<div align="center">
<div class="header"><h1>AV.LEASING</h1></div>

<div class="wrapper">

<input name="button" type="button" onclick="window.location='frm_rpt_vat.php'" value="กลับหน้าพิมพ์รายงาน" />
<input name="button" type="button" onClick="window.open('frm_print_rpt_vat.php?mm=<?php echo $mm; ?>&yy=<?php echo $yy; ?>','mywindow','')" value="           Print           " />
<br><br>
<table width="1024" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">วันที่</td>
      <td align="center">งวดที่/รหัส</td>
      <td align="center">เลขที่ใบกำกับ</td>
      <td align="center">เลขที่สัญญา</td>
      <td align="center">ชื่อลูกค้า</td>
      <td align="center">ชื่อทรัพย์สิน</td>
      <td align="center">เลขทะเบียน</td>
      <td align="center">มูลค่า</td>
      <td align="center">VAT</td>
      <td align="center">ยอดรวม</td>
      <td align="center">วันที่ชำระ</td>
      <td align="center">เลขที่ใบเสร็จ</td>
   </tr>

<?php
$j = 0;
$qry_in=pg_query("SELECT * FROM \"VRptVat\" where EXTRACT(MONTH FROM \"V_Date\")='$mm' AND EXTRACT(YEAR FROM \"V_Date\")='$yy' ORDER BY \"V_Receipt\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $V_Date = $res_in["V_Date"];
    $V_DueNo = $res_in["V_DueNo"];
    $V_Receipt = $res_in["V_Receipt"];
    $IDNO = $res_in["IDNO"];
    $full_name = $res_in["full_name"];
    $asset_name = $res_in["asset_name"];
    $asset_regis = $res_in["asset_regis"];
    $money = $res_in["money"];
    $VatValue = $res_in["VatValue"];
    $amtincvat = $res_in["amtincvat"];
    $R_Date = $res_in["R_Date"];
    $R_Receipt = $res_in["R_Receipt"];
    
    $sum_money = $money+$sum_money;
    $sum_VatValue = $VatValue+$sum_VatValue;
    $sum_amt = $amtincvat+$sum_amt;
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
      <td align="center"><?php echo $V_Date; ?></td>
      <td align="center"><?php echo $V_DueNo; ?></td>
      <td align="left"><?php echo $V_Receipt; ?></td>
      <td align="left"><?php echo $IDNO; ?></td>
      <td align="left"><?php echo $full_name; ?></td>
      <td align="left"><?php echo $asset_name; ?></td>
      <td align="left"><?php echo $asset_regis; ?></td>
      <td align="right"><?php echo number_format($money,2); ?></td>
      <td align="right"><?php echo number_format($VatValue,2); ?></td>
      <td align="right"><?php echo number_format($amtincvat,2); ?></td>
      <td align="center"><?php echo $R_Date; ?></td>
      <td align="center"><?php echo $R_Receipt; ?></td>
   </tr>
<?php
}
?>

<tr bgcolor="#79BCFF" style="font-size:11px; font-weight:bold;">
      <td align="right" colspan="7">มีลูกค้าทั้งหมด (ราย) : <?php echo $j; ?></td>
      <td align="right"><?php echo number_format($sum_money,2); ?></td>
      <td align="right"><?php echo number_format($sum_VatValue,2); ?></td>
      <td align="right"><?php echo number_format($sum_amt,2); ?></td>
      <td colspan=2></td>
   </tr>       
   
</table>

</div>
</div>

<div align="center">
<br> 

<input name="button" type="button" onclick="window.location='frm_rpt_vat.php'" value="กลับหน้าพิมพ์รายงาน" />
<input name="button" type="button" onClick="window.open('frm_print_rpt_vat.php?mm=<?php echo $mm; ?>&yy=<?php echo $yy; ?>','mywindow','')" value="           Print           " />
</div>

</body>
</html>