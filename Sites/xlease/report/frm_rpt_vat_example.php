<?php
set_time_limit(0);
include("../config/config.php");
$mm = $_GET['mm'];
$yy = $_GET['yy'];
?>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
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

$qry_in=pg_query("SELECT * FROM \"FVat\" where EXTRACT(MONTH FROM \"V_Date\")='$mm' AND EXTRACT(YEAR FROM \"V_Date\")='$yy' AND \"Cancel\"='FALSE' ORDER BY \"V_Receipt\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $V_Date = $res_in["V_Date"];
    $V_DueNo = $res_in["V_DueNo"];
    $V_Receipt = $res_in["V_Receipt"];
    $IDNO = $res_in["IDNO"];
    $VatValue = $res_in["VatValue"]; $VatValue = round($VatValue,2);
    $V_memo = $res_in["V_memo"];

    $qry_fp=pg_query("SELECT \"CusID\",\"asset_type\",\"asset_id\",\"P_MONTH\" FROM \"Fp\" where \"IDNO\"='$IDNO'");
    if($res_fp=pg_fetch_array($qry_fp)){
            $CusID = $res_fp["CusID"];
            $asset_type = $res_fp["asset_type"];
            $asset_id = $res_fp["asset_id"];
            $P_MONTH = $res_fp["P_MONTH"];
    }
    
    $R_Date = "";
    $R_Receipt = "";
    if(empty($V_memo) || $V_memo == ""){
        $qry_fr=pg_query("SELECT \"R_Date\",\"R_Receipt\" FROM \"Fr\" where \"R_DueNo\"='$V_DueNo' AND \"IDNO\"='$IDNO' AND \"Cancel\"='FALSE'");
    }else{
        $qry_fr=pg_query("SELECT \"R_Date\",\"R_Receipt\" FROM \"Fr\" where \"R_DueNo\"='$V_DueNo' AND \"IDNO\"='$IDNO' AND \"R_Receipt\"='$V_memo' AND \"Cancel\"='FALSE'");
    }

    if($res_fr=pg_fetch_array($qry_fr)){
        $R_Date = $res_fr["R_Date"];
        $R_Receipt = $res_fr["R_Receipt"];
        $rs4=pg_query("select \"money_for_reportvat\"('$R_Receipt','$IDNO')");
        $money=pg_fetch_result($rs4,0);
    }else{
        $money = $P_MONTH;
    }
    $money = round($money,2);

    $rs1=pg_query("select \"customer_name\"('$CusID')");
    $full_name=pg_fetch_result($rs1,0);
    
    $rs2=pg_query("select \"asset_name\"('$asset_type','$asset_id')");
    $asset_name=pg_fetch_result($rs2,0);
    
    $rs3=pg_query("select \"asset_regis\"('$asset_type','$asset_id')");
    $asset_regis=pg_fetch_result($rs3,0);
    
    $sum_money = $money+$sum_money;
    $sum_VatValue = $VatValue+$sum_VatValue;
    $sum_amt = ($money+$VatValue)+$sum_amt;
    
    $i++;
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
      <td align="right"><?php echo number_format($money+$VatValue,2); ?></td>
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

<div style="float:left"><input name="btnjournal" id="btnjournal" type="button" value="บันทึกลง Journal" /><span id="journal_result" style="padding-left:10px"></span></div>
<div style="float:right"><input name="button" type="button" onClick="window.open('frm_print_rpt_vat.php?mm=<?php echo $mm; ?>&yy=<?php echo $yy; ?>','f4fd5fsd4w8f4dsf5dsf4','')" value="Print PDF" /></div>
<div style="clear:both"></div>

<script type="text/javascript">
function replayStatus1(){
    if($("#journal_result").html()=="") $("#journal_result").html("กำลังประมวลผล...");
    else $("#journal_result").html("");
}

$('#btnjournal').click(function(){
    var divplaying= setInterval("replayStatus1()", 500);
    $.post('frm_rpt_vat_journal.php',{
        mm: '<?php echo $mm; ?>',
        yy: '<?php echo $yy; ?>',
        vat: '<?php echo $sum_VatValue; ?>'
    },
    function(data){
        if(data.success){
            clearInterval(divplaying);
            $("#journal_result").html(data.message);
        }else{
            clearInterval(divplaying);
            $("#journal_result").html(data.message);
        }
    },'json');
});
</script>