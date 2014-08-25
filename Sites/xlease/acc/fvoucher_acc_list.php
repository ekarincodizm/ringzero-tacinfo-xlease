<?php
include("../config/config.php");
$now_date = nowDate();//ดึง วันที่จาก server
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
    
});

function add_ac(jobid,money,vcid){
    $('body').append('<div id="dialogedit"></div>');
    $('#dialogedit').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
    $('#dialogedit').load('fvoucher_add_acc.php?jobid='+jobid+'&money='+money+'&vcid='+vcid);
    $('#dialogedit').dialog({
        title: 'บันทึกบัญชี JobID : '+jobid,
        resizable: false,
        modal: true,  
        width: 950,
        height: 450,
        close: function(ev, ui){
            $('#dialogedit').remove();
        }
    });
}

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
<input name="button" type="button" onclick="window.location='fvoucher_acc_list.php'" value="งานรอลงบัญชี" disabled />
<input name="button" type="button" onclick="window.location='fvoucher_acc_list_finish.php'" value="งานลงบัญชีแล้ว" />
</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<div class="ui-widget">

<fieldset><legend><B>Voucher - งานรอลงบัญชี</B></legend>

<form name="frm1" id="frm1" action="fvoucher_approve_insert.php" method="post">

<div style="float:right">
<div style="font-size:10px; background-color:#D9FFD9; padding: 3px; width:100px; text-align:center; float:left">รายการรับเข้าแล้ว</div>
<div style="font-size:10px; background-color:#FFD5FF; padding: 3px; width:100px; text-align:center; float:left">รายการยังไม่รับเข้า</div>
<div style="font-size:10px; background-color:#FFFFCA; padding: 3px; width:100px; text-align:center; float:left">รายการลงบัญชีแล้ว</div>
</div>
<div style="clear:both"></div>

<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#F0F0F0">
<tr bgcolor="#6FB7FF" style="font-weight:bold; text-align:center">
    <td>JobID</td>
    <td>รูปแบบ</td>
    <td>รหัส</td>
    <td>วันที่ทำรายการ</td>
    <td>รายละเอียด</td>
    <td>abh id</td>
    <td>ยอดเงิน</td>
</tr>
<?php
$qry=pg_query("select * from account.tal_voucher WHERE \"receipt_id\" is not null AND \"autoid_abh\" is null ORDER BY \"job_id\",\"vc_id\" ASC");
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
    
    $qry_finish=pg_query("select * from account.\"job_voucher\" WHERE \"job_id\"='$job_id' ");
    if($res_finish=pg_fetch_array($qry_finish)){
        $vcp_finish = $res_finish["vcp_finish"];
    }

    if($old_job != $job_id){
        if($old_vcp_finish=="t"){
			echo "<tr bgcolor=\"#D9FFD9\" class=\"btline\">";
        }else{
            echo "<tr bgcolor=\"#FFD5FF\" class=\"btline\">";
        }
        echo "<td colspan=6 align=right><input type=\"button\" name=\"btnadd\" id=\"btnadd\" value=\"ลงบัญชีรายการนี้\" onclick=\"javascript:add_ac('$old_job','$sum_sub','$str_vcid');\"> | ผลรวม JobID : $old_job</td>
        <td align=right>".number_format($sum_sub2,2)."</td>
        </tr>";
		 echo "<tr><td colspan=7 bgcolor=#CCCCCC></td></tr>";
        $sum_sub = 0;
        $sum_sub2 = 0;
        $str_vcid = "";
    }
    
    if(empty($chq_acc_no)){
        $money = $cash_amt;
    }else{
        $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
        if($res_chq=pg_fetch_array($qry_chq)){
            $money = $res_chq["Amount"];
        }
    }
    
$qry_ref=pg_query("select * from account.tal_voucher WHERE \"approve_id\" is not null AND \"autoid_abh\" is not null AND \"job_id\"='$job_id' ORDER BY \"vc_id\" ASC");
while($res_ref=pg_fetch_array($qry_ref)){
    $ref_vc_id = $res_ref["vc_id"];
    $ref_vc_detail = $res_ref["vc_detail"];
    $ref_do_date = $res_ref["do_date"];
    $ref_job_id = $res_ref["job_id"];
    $ref_cash_amt = $res_ref["cash_amt"];
    $ref_approve_id = $res_ref["approve_id"];
    $ref_chq_acc_no = $res_ref["chq_acc_no"];
    $ref_chque_no = $res_ref["chque_no"];
    $ref_autoid_abh = $res_ref["autoid_abh"];
    
    if(empty($ref_chq_acc_no)){
        $ref_money = $ref_cash_amt;
    }else{
        $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$ref_chq_acc_no' AND \"ChqID\"='$ref_chque_no'");
        if($res_chq=pg_fetch_array($qry_chq)){
            $ref_money = $res_chq["Amount"];
        }
    }
?>
<tr valign=top bgcolor="#FFFFCA">
    <td align="center"><?php echo "$ref_job_id"; ?></td>
    <td align="center"><?php if(empty($ref_chq_acc_no)){ echo "เงินสด"; }else{ echo "เช็ค"; } ?></td>
    <td align="center"><?php echo $ref_vc_id; ?></td>
    <td align="center"><?php echo $ref_do_date; ?></td>
    <td><?php echo nl2br($ref_vc_detail); ?></td>
    <td align="center"><?php if( empty($ref_autoid_abh) ){ echo "ยังไม่ลงบัญชี"; }else{ echo "<a onclick=\"javascript:showdetail('$ref_autoid_abh')\" title=\"แสดงข้อมูล\"><b><u>$ref_autoid_abh</u></b></a>"; } ?></td>
    <td align="right"><?php echo number_format($ref_money,2); ?></td>
</tr>
<?php
    $sum_sub2+=$ref_money;
}
?>
<tr valign=top bgcolor="#FFFFFF">
    <td align="center"><?php echo "$job_id"; ?></td>
    <td align="center"><?php if(empty($chq_acc_no)){ echo "เงินสด"; }else{ echo "เช็ค"; } ?></td>
    <td align="center"><?php echo $vc_id; ?></td>
    <td align="center"><?php echo $do_date; ?></td>
    <td><?php echo nl2br($vc_detail); ?></td>
    <td align="center"><?php if( empty($autoid_abh) ){ echo "ยังไม่ลงบัญชี"; }else{ echo "$autoid_abh"; } ?></td>
    <td align="right"><?php echo number_format($money,2); ?></td>
</tr>
<?php
    if( empty($autoid_abh) ){
        $str_vcid .= "$vc_id|";
        $sum_sub+=$money;
    }
    $old_job = $job_id;
    $old_vcp_finish = $vcp_finish;
    $sum_sub2+=$money;
}

if($j > 0){
    
        if($old_vcp_finish=="t"){
            echo "<tr bgcolor=\"#D9FFD9\" class=\"btline\">";
        }else{
            echo "<tr bgcolor=\"#FFD5FF\" class=\"btline\">";
        }
?>
    <td colspan=6 align=right><input type="button" name="btnadd" id="btnadd" value="ลงบัญชีรายการนี้" onclick="javascript:add_ac('<?php echo $old_job; ?>','<?php echo $sum_sub; ?>','<?php echo $str_vcid; ?>');"> | ผลรวม JobID : <?php echo $old_job; ?></td>
    <td align=right><?php echo number_format($sum_sub2,2); ?></td>
</tr>
<?php } ?>
</table>

</form>

</fieldset>

</div>

        </td>
    </tr>
</table>

</body>
</html>