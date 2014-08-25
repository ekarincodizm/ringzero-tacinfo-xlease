<?php
include("../config/config.php");
?>

<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#F0F0F0">
<tr bgcolor="#6FB7FF" style="font-weight:bold; text-align:center">
    <td>JobID</td>
    <td>รูปแบบ</td>
    <td>รหัส</td>
    <td>วันที่ทำรายการ</td>
    <td>รายละเอียด</td>
    <td>ยอดเงิน</td>
    <td></td>
    <td></td>
</tr>
<?php
$qry=pg_query("select A.*,B.* from account.tal_voucher A LEFT OUTER JOIN account.\"job_voucher\" B on A.\"job_id\" = B.\"job_id\" WHERE \"approve_id\" is not null AND \"receipt_id\" is null ORDER BY A.\"job_id\",\"vc_id\" ASC");
while($res=pg_fetch_array($qry)){
    $j++;
    $vc_id = $res["vc_id"];
    $vc_detail = $res["vc_detail"];
    $do_date = $res["do_date"];
    $job_id = $res["job_id"];   if($j==1){ $old_job = $job_id; }
    $cash_amt = $res["cash_amt"];
    $approve_id = $res["approve_id"];
    $chq_acc_no = $res["chq_acc_no"];
    $chque_no = $res["chque_no"];

    if($old_job != $job_id){
        echo "<tr><td colspan=10><hr style=\"margin:0px; padding:0px; height: 1px\"></td></tr>";
    }
    
    if(empty($chq_acc_no)){
        $money = $cash_amt;
    }else{
        $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
        if($res_chq=pg_fetch_array($qry_chq)){
            $ChqID = $res_chq["ChqID"];
            $money = $res_chq["Amount"];
        }
    }

?>
<tr valign=top bgcolor="#FFFFFF">
    <td align="center"><?php echo $job_id; ?></td>
    <td align="center"><?php if(empty($chq_acc_no)){ echo "เงินสด"; }else{ echo "เช็ค"; } ?></td>
    <td align="center"><?php echo $vc_id; ?></td>
    <td align="center"><?php echo $do_date; ?></td>
    <td><?php echo nl2br($vc_detail); ?></td>
    <td align="right"><?php echo number_format($money,2); ?></td>
<?php
$arr_chk_approve = explode("#",$approve_id);
if( $arr_chk_approve[count($arr_chk_approve)-1] == "P" ){
?>
    <td align=center><input type="button" name="btn_add" id="btn_add" value="ทำรายการนี้" onclick="javascript:add_rc('<?php echo $vc_id; ?>','1')"></td>
    <td align=center><span style="color:#969696">รายการนี้ ถูกพิมพ์แล้ว</span></td>
<?php
}else{
?>
    <td align=center><input type="button" name="btn_add" id="btn_add" value="ทำรายการนี้" onclick="javascript:add_rc('<?php echo $vc_id; ?>','0')"></td>
    <td align=center><input type="button" class="abc111" name="btn_print" id="btn_print" value="พิมพ์โดยไม่มีผู้รับเงิน" onclick="javascript:window.open('fvoucher_print.php?id=<?php echo "$vc_id"; ?>' , 'fv45s7s8a4s5s4a<?php echo "$vp_id"; ?>','menuber=no,toolbar=yes,location=no,scrollbars=no, status=no,resizable=no,width=800,height=600')"></td>
<?php
}
?>
</tr>
<?php
    $old_job = $job_id;
    $sum_sub+=$money;
}

if($j == 0){
    echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>


<script type="text/javascript">
$('.abc111').click(function(){
    $('#divshow').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
    $('#divshow').load('fvoucher_receipt_list_show.php');
});
</script>