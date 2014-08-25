<?php
include("../config/config.php");
$now_date = nowDate();//ดึง วันที่จาก server

$id = pg_escape_string($_GET['id']);
$type = pg_escape_string($_GET['type']);

$qry=pg_query("select * from account.tal_voucher WHERE \"vc_id\"='$id' ");
if($res=pg_fetch_array($qry)){
    $job_id = $res["job_id"];
    $do_date = $res["do_date"];
    $vc_detail = $res["vc_detail"];
    $chque_no = $res["chque_no"];
    $chq_acc_no = $res["chq_acc_no"];

    $money = $res["cash_amt"];
}

if(!empty($chq_acc_no)){
    $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
    if($res_chq=pg_fetch_array($qry_chq)){
        $ChqID = $res_chq["ChqID"];
        $money = $res_chq["Amount"];
    }
}
?>

<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
    <td><b>Job ID</b></td>
    <td><?php echo $job_id; ?></td>
</tr>
<tr>
    <td><b>VC ID</b></td>
    <td><?php echo $id; ?></td>
</tr>
<tr>
    <td><b>วันที่</b></td>
    <td><?php echo $do_date; ?></td>
</tr>
<tr>
    <td valign="top"><b>เรื่อง</b></td>
    <td><textarea name="detail" id="detail" rows="6" cols="60"><?php echo $vc_detail; ?></textarea></td>
</tr>

<?php
if(!empty($chq_acc_no)){
?>
<tr>
    <td><b>เลขที่เช็ค</b></td>
    <td><?php echo $ChqID; ?></td>
</tr>
<tr>
    <td><b>ยอดเงินในเช็ค</b></td>
    <td><?php echo number_format($money,2); ?> บาท.</td>
</tr>
<?php
}else{
?>
<tr>
    <td><b>ยอดเบิก</b></td>
    <td><?php echo number_format($money,2); ?> บาท.</td>
</tr>
<?php
}
?>
<tr>
    <td><b>ยอดใช้ไป</b></td>
    <td><input type="text" name="moneypay" id="moneypay" size="20"> บาท.</td>
</tr>
</table>

<div align="right"><input type="button" name="btnsave" id="btnsave" value="บันทึก"></div>

<script type="text/javascript">
$('#btnsave').click(function(){
    console.log('btnsave');
    $.post('fvoucher_in_update.php',{
        cid: '<?php echo $id; ?>',
        jobid: <?php echo $job_id; ?>,
        moneyold: <?php echo $money; ?>,
        moneypay: $('#moneypay').val(),
        detail: $('#detail').val()
    },
    function(data){
        if(data.success){
            $('#dialogedit').html(data.message);
            $('#divShow').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
            <?php
            if($type == 1){
            ?>
                $('#divShow').load('fvoucher_list_panel.php?type=1');
            <?php
            }else{
            ?>
                $('#divShow').load('fvoucher_list_panel.php?date='+ $('#datepicker').val());
            <?php
            }
            ?>
        }else{
            alert(data.message);
        }
    },'json');
});
</script>