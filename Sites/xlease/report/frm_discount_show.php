<?php
include("../config/config.php");
$yy = $_GET['yy'];
?>

<div style="float:left"><b>ประจำปี <?php echo $yy+543; ?></b></div>
<div style="float:right"><input name="button" type="button" onClick="window.open('frm_discount_show_pdf.php?yy=<?php echo $yy; ?>','k3js823j41fds5fs5fdsfsd5','')" value=" Print PDF " /><input name="button" type="button" onClick="window.open('frm_discount_ten.php?yy=<?php echo $yy; ?>','f4s78h5s47we8g5s4s','')" value="รายงานสรุปส่วนลดจ่าย" /></div>
<div style="clear:both"></div>
        
<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">เลขที่สัญญา</td>
      <td align="center">ชื่อผู้เช่าซื้อ</td>
      <td align="center">วันที่ปิดบัญชี</td>
      <td align="center">ลูกค้าปี</td>
      <td align="center">ยอดส่วนลดปิดบัญชี</td>
   </tr>

<?php
$j = 0;
$qry_in=pg_query("SELECT * FROM \"Fp\" where \"P_SL\" <> '0' AND EXTRACT(YEAR FROM \"P_CLDATE\")='$yy' ORDER BY \"IDNO\" ");
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $IDNO = $res_in["IDNO"];
    $P_CLDATE = $res_in["P_CLDATE"];
    $P_CustByYear = $res_in["P_CustByYear"];
    $P_SL = $res_in["P_SL"];
    
    $sum_sl += $P_SL;
    
    $fullname = "";
    $qry_in1=pg_query("SELECT \"full_name\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
    if($res_in2=pg_fetch_array($qry_in1)){
        $fullname = $res_in2["full_name"];
    }
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
    
    $qry_date_number=@pg_query("select \"c_date_number\"('$P_CLDATE')");
    $P_CLDATE=@pg_fetch_result($qry_date_number,0);
?>
      <td align="center"><?php echo $IDNO; ?></td>
      <td align="left"><?php echo $fullname; ?></td>
      <td align="center"><?php echo $P_CLDATE; ?></td>
      <td align="center"><?php echo ($P_CustByYear+543); ?></td>
      <td align="right"><?php echo number_format($P_SL,2); ?></td>
   </tr>
<?php
} // WHILE
?>

<tr bgcolor="#FFFFCA" style="font-size:12px; font-weight:bold;">
    <td><?php echo "ทั้งหมด $j รายการ"; ?></td>
    <td align="right" colspan="3"><b>ยอดรวมส่วนลดจ่าย</b></td>
    <td align="right"><?php echo number_format($sum_sl,2); ?></td>
</tr>
</table>

<div align="right" style="padding-top:5px">
<span id="journal_result" style="padding-right:10px"></span><input name="btnjournal" id="btnjournal" type="button" value="บันทึกลง Journal" />
</div>

<script type="text/javascript">
function replayStatus(){
    if($("#journal_result").html()=="") $("#journal_result").html("กำลังประมวลผล...");
    else $("#journal_result").html("");
}

$('#btnjournal').click(function(){
    var divplaying= setInterval("replayStatus()", 500);
    
    $.post('frm_discount_show_journal.php',{
        year: '<?php echo $yy; ?>'
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