<?php
include("../config/config.php");

if(isset($_POST['datepicker'])){
    $now_date = pg_escape_string($_POST['datepicker']);
}else{
    $now_date = nowDate();//ดึง วันที่จาก server
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">

    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});

function showdetail(id){
    $('body').append('<div id="dialogedit"></div>');
    $('#dialogedit').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
    $('#dialogedit').load('fvoucher_acc_detail.php?id='+id);
    $('#dialogedit').dialog({
        title: 'แสดงบัญชี Abh ID : '+id,
        resizable: false,
        modal: false,  
        width: 600,
        height: 200,
        close: function(ev, ui){
            $('#dialogedit').remove();
        }
    });
}
</script>

<style type="text/css">
.ui-widget{
    font-family:tahoma;
    font-size:13px;
}
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:11px;
    text-align:center;
}
.BoxYellow {
    margin: 0 auto;
    padding:5px 5px 5px 5px;
    font-size: 12px;
    font-weight: bold;
    color: #666666;
    text-align: center;
    line-height: 20px;
    BORDER-RIGHT: #FCC403 1px solid; BORDER-TOP: #FCC403 1px solid; BORDER-LEFT: #FCC403 1px solid; WIDTH: 500px; BORDER-BOTTOM: #FCC403 1px solid; HEIGHT: auto; BACKGROUND-COLOR: #FFFFD5
}
.odd{
    background-color:#FFFFFF;
    font-size:12px
}
.even{
    background-color:#DFEFFF;
    font-size:12px
}
.btline{
    font-weight: bold;
    border-style: dashed; border-width: 1px; border-color:#000000
}
</style>

</head>
<body>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left">
<input name="button" type="button" onclick="window.location='fvoucher_acc_list.php'" value="งานรอลงบัญชี" />
<input name="button" type="button" onclick="window.location='fvoucher_acc_list_finish.php'" value="งานลงบัญชีแล้ว" disabled />
</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<div class="ui-widget">

<fieldset><legend><B>Voucher - งานลงบัญชีแล้ว</B></legend>

<form name="frmsearch" id="frmsearch" action="" method="post">
<div style="margin: 5px 0px 5px 0px" align="right">
<b>แสดงวันที่</b> <input type="text" id="datepicker" name="datepicker" value="<?php echo $now_date; ?>" size="15">
<input type="submit" name="btnshow" id="btnshow" value="แสดง">
</div>
</form>

<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#F0F0F0">
<tr bgcolor="#6FB7FF" style="font-weight:bold; text-align:center">
    <td>JobID</td>
    <td>รูปแบบ</td>
    <td>รหัส</td>
    <td>abh_id</td>
    <td>วันที่ทำรายการ</td>
    <td>รายละเอียด</td>
    <td>ยอดเงิน</td>
</tr>
<?php
$qry=pg_query("select * from account.tal_voucher WHERE \"receipt_id\" is not null AND \"autoid_abh\" is not null AND \"do_date\"='$now_date' ORDER BY \"job_id\",\"vc_id\" ASC");
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
    $autoid_abh = $res["autoid_abh"];
    
    if(empty($chq_acc_no)){
        $money = $cash_amt;
    }else{
        $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
        if($res_chq=pg_fetch_array($qry_chq)){
            $money = $res_chq["Amount"];
        }
    }
    
    if($old_job != $job_id){
        echo "<tr bgcolor=\"#D9FFD9\" class=\"btline\">
        <td colspan=6 align=right>ผลรวม JobID : $old_job</td>
        <td align=right>".number_format($sum_sub,2)."</td>
        </tr>";
        $sum_sub = 0;
    }
?>
<tr valign=top bgcolor="#FFFFFF">
    <td align="center"><?php echo $job_id; ?></td>
    <td align="center"><?php if(empty($chq_acc_no)){ echo "เงินสด"; }else{ echo "เช็ค"; } ?></td>
    <td align="center"><?php echo $vc_id; ?></td>
    <td align="center"><a onclick="javascript:showdetail('<?php echo $autoid_abh; ?>')" title="แสดงข้อมูล"><b><u><?php echo $autoid_abh; ?></u></b></a></td>
    <td align="center"><?php echo $do_date; ?></td>
    <td><?php echo nl2br($vc_detail); ?></td>
    <td align="right"><?php echo number_format($money,2); ?></td>
</tr>
<?php
    $old_job = $job_id;
    $sum_sub+=$money;
}

if($j>0){
?>
<tr bgcolor="#D9FFD9" class="btline">
    <td colspan=6 align=right>ผลรวม JobID : <?php echo $old_job; ?></td>
    <td align=right><?php echo number_format($sum_sub,2); ?></td>
    
</tr>
<?php
}else{
    echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>

</fieldset>

</div>

        </td>
    </tr>
</table>

</body>
</html>