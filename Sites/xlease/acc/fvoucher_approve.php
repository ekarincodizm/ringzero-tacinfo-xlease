<?php
include("../config/config.php");
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
    $(function(){
        $(window).bind("beforeunload",function(event){
            window.opener.$('div#div_admin_menu').load('list_admin_menu.php');
        });
    });
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
    </style>

<script language="Javascript">
function selectAll2(select){
    with (document.frm1){
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
    }
}
</script>

</head>
<body>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<div class="ui-widget">

<fieldset><legend><B>Approve Voucher - รายการรอให้ Admin อนุมัติ</B></legend>

<form name="frm1" id="frm1" action="fvoucher_approve_insert.php" method="post">

<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#F0F0F0">
<tr bgcolor="#6FB7FF" style="font-weight:bold; text-align:center">
    <td><a href="javascript:selectAll2('chkbox');"><u>ทั้งหมด</u></a></td>
    <td>รูปแบบ</td>
    <td>รหัส</td>
    <td>รายละเอียด</td>
    <td>ยอดเงิน</td>
    <td>วันที่ทำรายการ</td>
    <td>JobID</td>
</tr>
<?php
$i = 0;
$qry=pg_query("select A.*,B.* from account.tal_voucher A LEFT OUTER JOIN account.\"job_voucher\" B on A.\"job_id\" = B.\"job_id\" WHERE A.\"approve_id\" is null ORDER BY A.\"job_id\" ASC");
while($res=pg_fetch_array($qry)){
    $i++;
    $vc_id = $res["vc_id"];
    $vc_detail = $res["vc_detail"];
    $do_date = $res["do_date"];
    $job_id = $res["job_id"];
    $cash_amt = $res["cash_amt"];
    $approve_id = $res["approve_id"];
    $chq_acc_no = $res["chq_acc_no"];
    $chque_no = $res["chque_no"];
    
    $marker_id = $res["marker_id"];
    
    if(empty($chq_acc_no)){
        $chk_cheq = "N";
        $money = $cash_amt;
    }else{
        $chk_cheq = "C";
        $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
        if($res_chq=pg_fetch_array($qry_chq)){
            $money = $res_chq["Amount"];
        }
    }
    
    echo "<tr valign=top bgcolor=\"#FFFFFF\">";
?>
    <td align="center"><input type="checkbox" name="chkbox[]" id="chkbox" value="<?php echo "$chk_cheq#$vc_id#$job_id#$marker_id#$do_date"; ?>"></td>
    <td align="center"><?php if(empty($chq_acc_no)){ echo "เงินสด"; }else{ echo "เช็ค"; } ?></td>
    <td align="center"><?php echo $vc_id; ?></td>
    <td><?php echo nl2br($vc_detail); ?></td>
    <td align="right"><?php echo number_format($money,2); ?></td>
    <td align="center"><?php echo $do_date; ?></td>
    <td align="center"><?php echo $job_id; ?></td>
</tr>
<?php
}
?>
</table>

<?php
if($i > 0){
?>
<div align="center" style="margin: 15px 0px 15px 0px">
<input type="submit" name="btnsubmit" id="btnsubmit" value="อนุมัติรายการที่เลือก" class="ui-button">
<input type="submit" name="btnsubmit" id="btnsubmit" value="ยกเลิกรายการที่เลือก" class="ui-button">
</div>
<?php
}else{
?>
<div align="center" style="margin: 5px 0px 5px 0px">- ไม่พบข้อมูล -</div>
<?php
}
?>
</form>

</fieldset>

</div>

        </td>
    </tr>
</table>

</body>
</html>