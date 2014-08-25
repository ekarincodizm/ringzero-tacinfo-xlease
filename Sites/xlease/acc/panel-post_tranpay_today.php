<?php
include("../config/config.php");
$datepicker = pg_escape_string($_GET['datepicker']);

if(empty($datepicker)){
    exit;
}
?>

<style type="text/css">
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
</style>

<div align="right"><a href="post_tranpay_today_pdf.php?date=<?php echo "$datepicker"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>ธนาคาร</td>
    <td>วันที่โอน</td>
    <td>เวลาที่โอน</td>
    <td>terminal_id</td>
    <td>ref1</td>
    <td>ref2</td>
    <td>ref_name</td>
    <td>post_to_idno</td>
    <td>จำนวนเงิน</td>
</tr>

<?php
$nub = 0;
$query=pg_query("select * from \"TranPay\" WHERE \"post_on_date\"='$datepicker' ORDER BY \"bank_no\",\"tr_date\",\"tr_time\" ASC");
while($resvc=pg_fetch_array($query)){
    $n++;
    $bank_no = $resvc['bank_no'];
    $tr_date = $resvc['tr_date'];
    $tr_time = $resvc['tr_time'];
    $terminal_id = $resvc['terminal_id'];
    $ref1 = $resvc['ref1'];
    $ref2 = $resvc['ref2'];
    $ref_name = $resvc['ref_name'];
    $post_to_idno = $resvc['post_to_idno'];
    $amt = $resvc['amt'];
    
    if(($old_bank != $bank_no) && $n!=1){
        echo "<tr><td colspan=\"9\" align=\"right\"><b>ธนาคาร $old_bank_name รวม $nub รายการ</b></td></tr>";
        $nub = 0;
    }
    
    $bankname = "";
    $query2=pg_query("select \"bankname\" from \"bankofcompany\" WHERE \"bankno\"='$bank_no' ");
    if($resvc2=pg_fetch_array($query2)){
        $bankname = $resvc2['bankname'];
    }

        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\" align=\"left\">";
        }else{
            echo "<tr class=\"even\" align=\"left\">";
        }
?>
        <td><?php echo $bankname; ?></td>
        <td align="center"><?php echo $tr_date; ?></td>
        <td align="center"><?php echo $tr_time; ?></td>
        <td><?php echo $terminal_id; ?></td>
        <td><?php echo $ref1; ?></td>
        <td><?php echo $ref2; ?></td>
        <td><?php echo $ref_name; ?></td>
        <td><?php echo $post_to_idno; ?></td>
        <td align="right"><?php echo number_format($amt,2); ?></td>
    </tr>
<?php
$nub++;
$old_bank = $bank_no;
$old_bank_name = $bankname;
}

if($n>0){
    echo "<tr><td colspan=\"9\" align=\"right\"><b>ธนาคาร $old_bank_name รวม $nub รายการ</b></td></tr>";
}else{
    echo "<tr><td colspan=\"9\" align=\"center\">- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>