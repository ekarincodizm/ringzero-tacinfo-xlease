<?php
include("../config/config.php");
$now_date = nowDate();//ดึง วันที่จาก server

$id = pg_escape_string($_GET['id']);
$bt = pg_escape_string($_GET['bt']);

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
    <td width="20%"><b>ยอดเงิน</b></td>
    <td><?php echo number_format($money,2); ?> บาท.</td>
</tr>
<tr valign="top">
    <td><b>ผู้รับเงิน</b></td>
    <td>

<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
    <td width="30%"><input type="radio" name="chkradio" id="chkradio" value="1" class="aaaaa1" checked> พนักงานภายใน</td>
    <td>
        <select name="list_user" id="list_user">
    <?php
        $qry_user=pg_query("select * from \"Vfuser_active\" WHERE \"status_user\"='TRUE' ORDER BY \"fullname\" ASC ");
        while($res_user=pg_fetch_array($qry_user)){
            $fullname = $res_user["fullname"];
            $id_user = $res_user["id_user"];
    ?>
          <option value="<?php echo $id_user; ?>"><?php echo $fullname; ?></option>
    <?php } ?>
        </select>
    </td>
</tr>
<tr>
    <td><input type="radio" name="chkradio" id="chkradio" value="2" class="aaaaa2"> Vender</td>
    <td>
        <select name="list_vender" id="list_vender">
    <?php
        $qry_user=pg_query("select * from account.\"vender\" ORDER BY \"vd_name\" ASC ");
        while($res_user=pg_fetch_array($qry_user)){
            $VenderID = $res_user["VenderID"];
            $vd_name = $res_user["vd_name"];
    ?>
          <option value="<?php echo $VenderID; ?>"><?php echo $vd_name; ?></option>
    <?php } ?>
        </select>&nbsp;<input type="text" name="vendertxt" id="vendertxt" size="20">
    </td>
</tr>
<tr>
    <td><input type="radio" name="chkradio" id="chkradio" value="3" class="aaaaa3"> เติมเอง</td>
    <td>
<input type="text" name="othertxt" id="othertxt" size="30">
    </td>
</tr>
</table>

</td>

</tr>
</table>

<div style="float:left"><input type="button" name="btnsave" id="btnsave" value=" บันทึก "></div>
<div style="float:right"><input type="button" name="btnprint" id="btnprint" value="พิมพ์ <?php echo $id; ?>" onclick="javascript:window.open('fvoucher_print.php?id=<?php echo "$id"; ?>' , 'fd22da4fsf4f7e<?php echo "$vp_id"; ?>','menuber=no,toolbar=yes,location=no,scrollbars=no, status=no,resizable=no,width=800,height=600')"></div>
<div style="clear:both"></div>

<script type="text/javascript">
$(document).ready(function(){
    $('#btnprint').attr('disabled',true);
    //$('#show_list_user').hide();
});

$('#btnsave').click(function(){
    $.post('fvoucher_receipt_add_update.php',{
        bt: '<?php echo "$bt"; ?>',
        vcid: '<?php echo "$id"; ?>',
        chkradio: $('input[id=chkradio]:checked').val(),
        list_vender: $('#list_vender').val(),
        list_user: $('#list_user').val(),
        othertxt: $('#othertxt').val(),
        vendertxt: $('#vendertxt').val()
    },
    function(data){
        if(data.success){
            //$('#dialogedit').remove();
            $('#list_user').attr('disabled',true);
            $('#list_vender').attr('disabled',true);
            $('#vendertxt').attr('disabled',true);
            $('#othertxt').attr('disabled',true);
            
            $('#btnsave').attr('disabled',true);
            <?php if($bt != 1){ ?>
            $('#btnprint').attr('disabled',false);
            <?php } ?>
            alert(data.message);
            $('#divshow').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
            $('#divshow').load('fvoucher_receipt_list_show.php');
        }else{
            alert(data.message);
        }
    },'json');
});

$('#list_user').click(function(){
    $(".aaaaa1").attr('checked',true);
});
$('#list_vender').click(function(){
    $(".aaaaa2").attr('checked',true);
});
$('#vendertxt').click(function(){
    $(".aaaaa2").attr('checked',true);
});
$('#othertxt').click(function(){
    $(".aaaaa3").attr('checked',true);
});
$('#btnprint').click(function(){
    $('#dialogedit').remove();
    //$('#btnprint').attr('disabled',true);
});
</script>