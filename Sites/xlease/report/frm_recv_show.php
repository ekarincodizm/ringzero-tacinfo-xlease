<?php
include("../config/config.php");
$mm = $_GET['mm'];
$yy = $_GET['yy'];
?>

<div style="float:right">
<div style="font-size:10px; background-color:#FFC0C0; padding: 3px; width:80px; text-align:center; float:left">ไม่พบ Cusyear</div>
<div style="font-size:10px; background-color:#FFFFE1; padding: 3px; width:80px; text-align:center; float:left">รายการส่วนลด</div>
<div style="clear:both"></div>
</div>
<div style="clear:both"></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">วันที่</td>
      <td align="center">IDNO</td>
      <td align="center">ชื่อผู้เช่าซื้อ</td>
      <td align="center">เลขที่ใบเสร็จ</td>
      <td align="center">TypePay</td>
      <td align="center">ยอดเงิน</td>
      <td align="center">VAT</td>
      <td align="center">สถานะ</td>
   </tr>

<?php
$j = 0;
$arr_othertype = array();
$arr_CustYear = array();
$qry_in=pg_query("SELECT * FROM \"Fr\" where EXTRACT(MONTH FROM \"R_Date\")='$mm' AND EXTRACT(YEAR FROM \"R_Date\")='$yy' AND \"Cancel\"='false' ORDER BY \"R_Date\",\"R_Receipt\",\"R_DueNo\" ASC ");
$num_row = pg_num_rows($qry_in);
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $show_type = "";
    $R_Bank = "";
    $full_name = "";
    $vat = 0;
    $p_sl = 0;
    $R_Money = 0;
    
    $IDNO = $res_in["IDNO"]; 
    $R_DueNo = $res_in["R_DueNo"];
    $R_Receipt = $res_in["R_Receipt"];
    $R_Date = $res_in["R_Date"];
    $R_Money = $res_in["R_Money"];
    $R_Bank = $res_in["R_Bank"];
    $cur_year = $res_in["CustYear"]+543;
    
    $qry_date_number=@pg_query("select \"c_date_number\"('$R_Date')");
    $R_Date=@pg_fetch_result($qry_date_number,0);

    if($j==1) $old_date = $R_Date;
    
    if($R_DueNo == 0){
        $show_type = "เงินดาวน์";
    }elseif($R_DueNo > 98){
        $qry_type=pg_query("select \"TName\" from \"TypePay\" WHERE (\"TypeID\"='$R_DueNo')");
        if($res_type=pg_fetch_array($qry_type)){
            $TName = $res_type["TName"];
        }
        $show_type = "$TName";
    }else{
        $show_type = "ค่างวด";
    }
    
    $qry_in4=pg_query("select \"P_SL\" from \"Fp\" WHERE (\"IDNO\"='$IDNO' AND \"P_TOTAL\"='$R_DueNo')");
    if($res_in4=pg_fetch_array($qry_in4)){
        $p_sl = $res_in4["P_SL"];
        $p_sl = round($p_sl,2);
    }

    $R_Money = round($R_Money,2);
    $R_Money_fm = number_format($R_Money,2);

    $qry_in2=pg_query("select \"full_name\" from \"VContact\" WHERE (\"IDNO\"='$IDNO')");
    if($res_in2=pg_fetch_array($qry_in2)){
        $full_name = $res_in2["full_name"];
    }
    
    $qry_in3=pg_query("select \"VatValue\" from \"FVat\" WHERE (\"IDNO\"='$IDNO' AND \"V_DueNo\"='$R_DueNo' AND \"Cancel\"='FALSE')");
    if($res_in3=pg_fetch_array($qry_in3)){
        $vat = $res_in3["VatValue"];
    }
    
    $vat = round($vat,2);
    $vat_fm = number_format($vat,2);


    if($R_Date != $old_date){
        $show_unique_CustYear = array_unique($arr_CustYear);
        sort($show_unique_CustYear);
        foreach($show_unique_CustYear as $v){
            if( ${'cu_'.$v} == 0 && ${'cu_vat'.$v} == 0 && ${'ca_'.$v} == 0 && ${'ca_vat'.$v} == 0 ){
                
            }else{
                echo "<tr><td colspan=8 style=\"background:#D7FFD7; font-size:11px; font-weight:bold;\" align=left><b>สรุปปี $v : (ธนาคาร ". number_format(${'cu_'.$v},2) ." Vat ". number_format(${'cu_vat'.$v},2) ." | เงินสด ". number_format(${'ca_'.$v},2) ." Vat ". number_format(${'ca_vat'.$v},2) .")</b></td></tr>";
            }
            
            ${'cu_'.$v} = 0;
            ${'cu_vat'.$v} = 0;
            ${'ca_'.$v} = 0;
            ${'ca_vat'.$v} = 0;
        }
        $arr_CustYear = array();
        

        $show_unique_othertype = array_unique($arr_othertype);
        sort($show_unique_othertype);
        foreach($show_unique_othertype as $p){
            $qry_type=pg_query("select \"TName\" from \"TypePay\" WHERE (\"TypeID\"='$p')");
            if($res_type=pg_fetch_array($qry_type)){
                $othertype_name = $res_type["TName"];
            }
            echo "<tr><td colspan=8 style=\"background:#D7FFD7; font-size:11px; font-weight:bold;\" align=left><b>สรุป/รายได้อื่นๆ : $othertype_name : (ธนาคาร ". number_format(${$p.'zcu'},2) ." Vat ". number_format(${$p.'zcu_vat'},2) ." | เงินสด ". number_format(${$p.'zca'},2) ." Vat ". number_format(${$p.'zca_vat'},2) .")</b></td></tr>";
            ${$p.'zcu'} = 0;
            ${$p.'zcu_vat'} = 0;
            ${$p.'zca'} = 0;
            ${$p.'zca_vat'} = 0;
        }

        $arr_othertype = array();
        
        $sum_day_cu_fm = number_format($sum_day_cu,2);
        $sum_day_cu_vat_fm = number_format($sum_day_cu_vat,2);
        $sum_day_ca_fm = number_format($sum_day_ca,2);
        $sum_day_ca_vat_fm = number_format($sum_day_ca_vat,2);
        
        echo "<tr><td colspan=8 style=\"font-size:11px; font-weight:bold;\" align=left><b>สรุปรายวัน $old_date : (ธนาคาร $sum_day_cu_fm Vat $sum_day_cu_vat_fm | เงินสด $sum_day_ca_fm Vat $sum_day_ca_vat_fm)</b></td></tr>";
        
        $sum_day_cu = 0;
        $sum_day_ca = 0;
        $sum_day_cu_vat = 0;
        $sum_day_ca_vat = 0;
    }
    if($R_DueNo < 99){
        $arr_CustYear[] = $res_in["CustYear"]+543;
    }
    $old_date = $R_Date;

    if($R_DueNo > 98){
        $arr_othertype[] = $R_DueNo;
    }
    
    if($R_Bank == "CU" AND $R_DueNo > 98){
        ${$R_DueNo.'zcu'} += $R_Money-$p_sl;
        ${$R_DueNo.'zcu_vat'} += $vat;
    }elseif( ($R_Bank == "CA" OR $R_Bank == "CCA") AND $R_DueNo > 98 ){
        ${$R_DueNo.'zca'} += $R_Money-$p_sl;
        ${$R_DueNo.'zca_vat'} += $vat;
    }elseif($R_Bank == "CU"){
        ${'cu_'.$cur_year} += $R_Money-$p_sl;
        ${'cu_vat'.$cur_year} += $vat;
    }elseif( $R_Bank == "CA" OR $R_Bank == "CCA" ){
        ${'ca_'.$cur_year} += $R_Money-$p_sl;
        ${'ca_vat'.$cur_year} += $vat;
    }

    if( (empty($cur_year) OR $cur_year == "") AND $R_Bank == "CU" ){
        $R_Bank = "ธนาคาร";
    }elseif( (empty($cur_year) OR $cur_year == "") AND ($R_Bank == "CA" OR $R_Bank == "CCA") ){
        $R_Bank = "เงินสด";
    }elseif($R_Bank == "CU" AND $R_DueNo > 98){
        $R_Bank = "ธนาคาร";
        $sum_day_cu += $R_Money-$p_sl;
        $sum_day_cu_vat += $vat;
        $sum_all_day_cu += $R_Money;
        $sum_all_day_cu_vat += $vat;
    }elseif( ($R_Bank == "CA" OR $R_Bank == "CCA") AND $R_DueNo > 98 ){
        $R_Bank = "เงินสด";
        $sum_day_ca += $R_Money-$p_sl;
        $sum_day_ca_vat += $vat;
        $sum_all_day_ca += $R_Money;
        $sum_all_day_ca_vat += $vat;
    }elseif($R_Bank == "CU"){
        $R_Bank = "ธนาคาร";
        $sum_day_cu += $R_Money-$p_sl;
        $sum_day_cu_vat += $vat;
        $sum_all_day_cu += $R_Money;
        $sum_all_day_cu_vat += $vat;
    }elseif($R_Bank == "CA" OR $R_Bank == "CCA"){
        $R_Bank = "เงินสด";
        $sum_day_ca += $R_Money-$p_sl;
        $sum_day_ca_vat += $vat;
        $sum_all_day_ca += $R_Money;
        $sum_all_day_ca_vat += $vat;
    }else{
        $R_Bank = "Error";
    }
        
    $sum_all_money += ($R_Money-$p_sl);
    $sum_all_vat += $vat;
    
    $color_row++;
    if(empty($res_in["CustYear"]) AND $R_DueNo < 99){
        echo "<tr class=\"red\">";
    }elseif($color_row % 2 == 0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }

?>
      <td align="center" title="<?php echo $cur_year; ?>"><?php echo $R_Date; ?></td>
      <td align="center"><?php echo $IDNO; ?></td>
      <td align="left"><?php echo $full_name; ?></td>
      <td align="center"><?php echo $R_Receipt; ?></td>
      <td align="center"><?php echo $show_type." <font color='#808080'>[$R_DueNo]</font>"; ?></td>
      <td align="right"><?php echo $R_Money_fm; ?></td>
      <td align="right"><?php echo $vat_fm; ?></td>
      <td align="center"><?php echo $R_Bank; ?></td>
   </tr>
<?php

    if(!empty($p_sl) AND $p_sl != 0){
        $p_sl_fm = number_format($p_sl,2);
        echo "<tr class=\"yel\">
      <td align=\"center\">$R_Date</td>
      <td align=\"center\">$IDNO</td>
      <td align=\"left\">$full_name</td>
      <td align=\"center\">$R_Receipt</td>
      <td align=\"center\">ส่วนลด</td>
      <td align=\"right\">- $p_sl_fm</td>
      <td align=\"right\">0.00</td>
      <td align=\"center\">$R_Bank</td></tr>";
    }

    if($num_row == $j){
        
        $show_unique_CustYear = array_unique($arr_CustYear);
        sort($show_unique_CustYear);
        foreach($show_unique_CustYear as $v){
            if( ${'cu_'.$v} == 0 && ${'cu_vat'.$v} == 0 && ${'ca_'.$v} == 0 && ${'ca_vat'.$v} == 0 ){
                
            }else{
                echo "<tr><td colspan=8 style=\"background:#D7FFD7; font-size:11px; font-weight:bold;\" align=left><b>สรุปปี $v : (ธนาคาร ". number_format(${'cu_'.$v},2) ." Vat ". number_format(${'cu_vat'.$v},2) ." | เงินสด ". number_format(${'ca_'.$v},2) ." Vat ". number_format(${'ca_vat'.$v},2) .")</b></td></tr>";
            }
            
            ${'cu_'.$v} = 0;
            ${'cu_vat'.$v} = 0;
            ${'ca_'.$v} = 0;
            ${'ca_vat'.$v} = 0;
        }
        $arr_CustYear = array();
        
        $sum_day_cu_fm = number_format($sum_day_cu,2);
        $sum_day_cu_vat_fm = number_format($sum_day_cu_vat,2);
        $sum_day_ca_fm = number_format($sum_day_ca,2);
        $sum_day_ca_vat_fm = number_format($sum_day_ca_vat,2);
        echo "<tr><td colspan=8 style=\"font-size:11px; font-weight:bold;\" align=left><b>สรุปรายวัน $old_date : (ธนาคาร $sum_day_cu_fm Vat $sum_day_cu_vat_fm | เงินสด $sum_day_ca_fm Vat $sum_day_ca_vat_fm)</b></td></tr>";
    }
    
} // WHILE

    $all_money_fm = number_format($sum_all_money,2);
    $all_vat_fm = number_format($sum_all_vat,2);
    
    $sum_all_day_cu = number_format($sum_all_day_cu,2);
    $sum_all_day_cu_vat = number_format($sum_all_day_cu_vat,2);
    $sum_all_day_ca = number_format($sum_all_day_ca,2);
    $sum_all_day_ca_vat = number_format($sum_all_day_ca_vat,2);

?>

<tr bgcolor="#79BCFF" style="font-size:11px; font-weight:bold;">
    <td align="left" colspan="3">สรุปรวม (ธนาคาร <?php echo $sum_all_day_cu; ?> Vat <?php echo $sum_all_day_cu_vat; ?> | เงินสด <?php echo $sum_all_day_ca; ?> Vat <?php echo $sum_all_day_ca_vat; ?>)</td>
    <td align="right" colspan="2">ทั้งหมด <?php echo $j; ?> รายการ</td>
    <td align="right"><?php echo $all_money_fm; ?></td>
    <td align="right"><?php echo $all_vat_fm; ?></td>
    <td align="right"></td>
</tr>
</table>

<div style="float:left">
<input name="btnjournal" id="btnjournal" type="button" value="บันทึกลง Journal" /><span id="journal_result" style="padding-left:10px"></span>
</div>
<div style="float:right">
<input name="button" type="button" onClick="window.open('frm_recv_print.php?mm=<?php echo $mm; ?>&yy=<?php echo $yy; ?>','mywindow1','')" value="Print PDF" />
<input name="button" type="button" onClick="window.open('frm_ten_show.php?mm=<?php echo $mm; ?>&yy=<?php echo $yy; ?>','mywindow2','')" value="รายงานสรุป รายวันรับเงิน" />
</div>
<div style="clear:both"></div>

<script type="text/javascript">
function replayStatus(){
    if($("#journal_result").html()=="") $("#journal_result").html("กำลังประมวลผล...");
    else $("#journal_result").html("");
}

$('#btnjournal').click(function(){
    var divplaying= setInterval("replayStatus()", 500);
    
    $.post('frm_recv_show_journal.php',{
        mm: '<?php echo $mm; ?>',
        yy: '<?php echo $yy; ?>'
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