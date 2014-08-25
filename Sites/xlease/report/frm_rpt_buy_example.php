<?php
include("../config/config.php");
$mm = $_GET['mm'];
$yy = $_GET['yy'];
?>

<div align="right">
<input name="button" type="button" onClick="window.open('frm_rpt_buy_example_pdf.php?mm=<?php echo $mm; ?>&yy=<?php echo $yy; ?>','k3js823j41fds5fs5fdsfsd5','')" value=" Print PDF " />
</div>
        
<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">วันที่</td>
      <td align="center">เลขที่สัญญา</td>
      <td align="center">ซื้อมาจาก</td>
      <td align="center">ยี่ห้อรถยนต์</td>
      <td align="center">มูลค่า</td>
      <td align="center">VAT</td>
      <td align="center">รวม</td>
   </tr>

<?php
$j = 0;

$qry_in=pg_query("SELECT * FROM account.\"AccountBookHead\" where EXTRACT(MONTH FROM \"acb_date\")='$mm' AND EXTRACT(YEAR FROM \"acb_date\")='$yy' AND \"cancel\"='false' ORDER BY \"acb_date\" ");
while($res_in=pg_fetch_array($qry_in)){
    $auto_id = $res_in["auto_id"];
    $acb_detail = $res_in["acb_detail"];
    $acb_date = $res_in["acb_date"];
        $strDate = date("d",strtotime($acb_date));

    $qry_in3=pg_query("SELECT * FROM account.\"BookBuy\" where \"bh_id\"='$auto_id' ");
    if($res_in3=pg_fetch_array($qry_in3)){
        $buy_from = $res_in3["buy_from"];
        $buy_receiptno = $res_in3["buy_receiptno"];
        $pay_buy = $res_in3["pay_buy"];
        $to_hp_id = $res_in3["to_hp_id"];
    }else{
        continue;
    }
    
    $sum_AmtDr = 0;
    $qry_in2=pg_query("SELECT * FROM account.\"AccountBookDetail\" where \"autoid_abh\"='$auto_id' AND \"AcID\"='4700' AND \"AmtDr\" <> '0' AND \"AmtCr\" = '0' ORDER BY \"auto_id\" ");
    if($res_in2=pg_fetch_array($qry_in2)){
        $sum_AmtDr += $res_in2["AmtDr"];
    }
    
    if($sum_AmtDr == 0){
        continue;
    }
    
    $sum_vat = 0;
    $qry_in4=pg_query("SELECT \"AmtDr\" FROM account.\"AccountBookDetail\" where \"autoid_abh\"='$auto_id' AND \"AcID\"<>'4700' AND \"AmtDr\" <> '0' AND \"AmtCr\" = '0' ORDER BY \"auto_id\" ");
    if($res_in4=pg_fetch_array($qry_in4)){
        $sum_vat += $res_in4["AmtDr"];
    }
    
    $qry_in5=pg_query("SELECT \"C_CARNAME\" FROM \"UNContact\" where \"IDNO\"='$to_hp_id' ");
    if($res_in5=pg_fetch_array($qry_in5)){
        $C_CARNAME = $res_in5["C_CARNAME"];
    }
    
    $j++;
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
      <td align="center"><?php echo "$strDate"; ?></td>
      <td align="center"><?php echo $to_hp_id; ?></td>
      <td align="left"><?php echo $buy_from; ?></td>
      <td align="left"><?php echo $C_CARNAME; ?></td>
      <td align="right"><?php echo number_format($sum_AmtDr,2); ?></td>
      <td align="right"><?php echo number_format($sum_vat,2); ?></td>
      <td align="right"><?php echo number_format($sum_AmtDr+$sum_vat,2); ?></td>
   </tr>
<?php

$sum1 += $sum_AmtDr;
$sum2 += $sum_vat;
$sum3 += ($sum_AmtDr+$sum_vat);
} // WHILE

if($j > 0){
?>

<tr bgcolor="#FFFFCA" style="font-size:11px; font-weight:bold;">
      <td align="right" colspan="4">มีลูกค้าทั้งหมด (ราย) : <?php echo $j; ?></td>
      <td align="right"><?php echo number_format($sum1,2); ?></td>
      <td align="right"><?php echo number_format($sum2,2); ?></td>
      <td align="right"><?php echo number_format($sum3,2); ?></td>
</tr>
<?php
}else{
    echo "<tr><td align=center colspan=7>- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>