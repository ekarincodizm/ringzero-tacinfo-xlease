<?php
include("../../config/config.php");
$month = $_GET['month'];
$year = $_GET['year'];
$user=$_GET['userreceive'];
$detail=$_GET['detail'];

if($user==""){
	$userreceive="is not null";
}else{
	$userreceive="='$user'";
}

?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<div align="right" style="padding: 5px 5px 5px 5px"><a href="../../acc/fvoucher_report_pdf.php?month=<?php echo $month; ?>&year=<?php echo $year; ?>&user=<?php echo $user; ?>&detail=<?php echo $detail?>" target="_blank"><u>พิมพ์รายงาน</u></a></div>

<table cellpadding="5" cellspacing="1" border="0" width="100%" bgcolor="#F0F0F0">
<tr bgcolor="#6FB7FF" style="font-weight:bold; text-align:center">
    <td>JobID</td>
    <td>รูปแบบ</td>
    <td>รหัส</td>
    <td>วันที่ทำรายการ</td>
    <td>รายละเอียด</td>
    <td width="70">abh id</td>
    <td width="90">ยอดเงินสด</td>
    <td width="90">รับ</td>
    <td width="90">จ่าย</td>
    <td width="90">ยอดเช็ค</td>
</tr>
<?php
$j = 0;
if($detail==""){
	$qry=pg_query("select * from account.tal_voucher WHERE \"receipt_id\" $userreceive AND \"autoid_abh\" is null AND EXTRACT(MONTH FROM \"recp_date\")='$month' and EXTRACT(YEAR FROM \"recp_date\")='$year' ORDER BY \"job_id\",\"vc_id\" ASC");
}else{
	$qry=pg_query("select * from account.tal_voucher WHERE \"vc_detail\" like '%$detail%' AND \"autoid_abh\" is null AND EXTRACT(MONTH FROM \"recp_date\")='$month' and EXTRACT(YEAR FROM \"recp_date\")='$year' ORDER BY \"vc_detail\" ASC");
}

while($res=pg_fetch_array($qry)){
    $j++;
    $vc_id = $res["vc_id"];
    $vc_detail = $res["vc_detail"];
    $do_date = $res["do_date"];
    $job_id = $res["job_id"];
    $cash_amt = $res["cash_amt"];
    $approve_id = $res["approve_id"];
    $chq_acc_no = $res["chq_acc_no"];
    $chque_no = $res["chque_no"];
    $autoid_abh = $res["autoid_abh"];

if($j > 1){
    
if($old_job == $job_id){
?>
<tr valign=top bgcolor="#FFFFFF">
    <td align="center"><?php echo "$begin_jobid"; ?></td>
    <td align="center"><?php echo "$begin_chq_acc_no"; ?></td>
    <td align="center"><a href="#" onclick="javascript:popU('../../acc/show_voucherdetail.php?vc_id=<?php echo $begin_vc_id?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')"><u><?php echo "$begin_vc_id"; ?></u></a></td>
    <td align="center"><?php echo "$begin_do_date"; ?></td>
    <td><?php echo nl2br($begin_vc_detail); ?></td>
    <td align="center"><?php echo "$begin_autoid_abh"; ?></td>

    <td align="right"><?php echo number_format($begin_cash_amt,2); ?></td>
    <td></td>
    <td></td>
    <td align="right"><?php echo number_format($begin_Amount,2); ?></td>
</tr>
<?php
}else{
    
    $sum_sum = $sum_sub1_plus+$sum_sub1_lob;
?>
<tr valign=top bgcolor="#FFFFFF">
    <td align="center"><?php echo "$begin_jobid"; ?></td>
    <td align="center"><?php echo "$begin_chq_acc_no"; ?></td>
    <td align="center"><a href="#" onclick="javascript:popU('../../acc/show_voucherdetail.php?vc_id=<?php echo $begin_vc_id?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')"><u><?php echo "$begin_vc_id"; ?></u></a></td>
    <td align="center"><?php echo "$begin_do_date"; ?></td>
    <td><?php echo nl2br($begin_vc_detail); ?></td>
    <td align="center"><?php echo "$begin_autoid_abh"; ?></td>

    <td align="right"><?php echo number_format($begin_cash_amt,2); ?></td>
<?php
if($sum_sum >= 0){
    $sum_j += $sum_sum;
    echo "<td></td>";
    echo "<td align=\"right\">".number_format($sum_sum,2)."</td>";
}else{
    $sum_r += $sum_sum;
    echo "<td align=\"right\">".number_format($sum_sum,2)."</td>";
    echo "<td></td>";
}
?>
    <td align="right"><?php echo number_format($begin_Amount,2); ?></td>
    <tr><td colspan="10"></td></tr>
</tr>
<?php

    $all_sum_sum += $sum_sum;

    $sum_sum = 0;
    $sum_sub1_plus = 0;
    $sum_sub1_lob = 0;
}

}

if(empty($chq_acc_no)){
    $chq_acc_no_text = "เงินสด";
}else{
    $chq_acc_no_text = "เช็ค";
}

if( empty($autoid_abh) ){
    $autoid_abh_text = "ยังไม่ลงบัญชี";
}else{
    $autoid_abh_text = "$autoid_abh";
}

$Amount = 0;

if(empty($chq_acc_no)){
    if($cash_amt >= 0){
        $sum_sub1_plus+=$cash_amt;
    }else{
        $sum_sub1_lob+=$cash_amt;
    }
    $sum_all1+=$cash_amt;
}else{
    $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
    if($res_chq=pg_fetch_array($qry_chq)){
        $Amount = $res_chq["Amount"];
    }
    $sum_sub2+=$Amount;
    $sum_all2+=$Amount;
}

$begin_jobid = $job_id;
$begin_chq_acc_no = $chq_acc_no_text;
$begin_vc_id = $vc_id;
$begin_do_date = $do_date;
$begin_vc_detail = $vc_detail;
$begin_autoid_abh = $autoid_abh_text;
$begin_cash_amt = $cash_amt;
$begin_Amount = $Amount;

$begin_chq_acc_no2 = $chq_acc_no;
$begin_chque_no2 = $chque_no;

$old_job = $job_id;
}

//แสดงรายการสุดท้าย
$sum_sum = $sum_sub1_plus+$sum_sub1_lob;
?>
<tr valign=top bgcolor="#FFFFFF">
    <td align="center"><?php echo "$begin_jobid"; ?></td>
    <td align="center"><?php echo "$begin_chq_acc_no"; ?></td>
    <td align="center"><a href="#" onclick="javascript:popU('show_voucherdetail.php?vc_id=<?php echo $begin_vc_id?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')"><u><?php echo "$begin_vc_id"; ?></u></a></td>
    <td align="center"><?php echo "$begin_do_date"; ?></td>
    <td><?php echo nl2br($begin_vc_detail); ?></td>
    <td align="center"><?php echo "$begin_autoid_abh"; ?></td>

    <td align="right"><?php echo number_format($begin_cash_amt,2); ?></td>
<?php
if($sum_sum >= 0){
    $sum_j += $sum_sum;
    echo "<td></td>";
    echo "<td align=\"right\">".number_format($sum_sum,2)."</td>";
}else{
    $sum_r += $sum_sum;
    echo "<td align=\"right\">".number_format($sum_sum,2)."</td>";
    echo "<td></td>";
}
?>
    <td align="right"><?php echo number_format($begin_Amount,2); ?></td>
</tr>
<?php

//แสดงสรุปผลรวมทั้งหมด
if($j == 0){
    echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";
}else{
?>

<tr bgcolor="#D2FFD2">
    <td colspan="4" style="color:red; font-weight:bold">รวมยอดเงินทั้งสิ้น <?php echo number_format($sum_all1+$sum_all2,2); ?> บาท.</td>
    <td colspan="2" align=right style=font-weight:bold>ผลรวม</td>
    <td align="right" style="font-weight:bold"><?php echo number_format($sum_all1,2); ?></td>
    <td align="right" style="font-weight:bold"><?php echo number_format($sum_r,2); ?></td>
    <td align="right" style="font-weight:bold"><?php echo number_format($sum_j,2); ?></td>
    <td align="right" style="font-weight:bold"><?php echo number_format($sum_all2,2); ?></td>
</tr>

<?php
}
?>
</table>