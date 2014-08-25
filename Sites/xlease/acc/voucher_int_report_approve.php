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
function selectAll(select)
{
    with (document.frm1)
    {
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
        
        if(checkval == true)          
            document.frm1.button2.disabled = false;
        else
            document.frm1.button2.disabled = true;
    }
}

function selectDisable(field){
    var temp=0;
    for (i = 0; i < field.length; i++)
        if( field[i].checked == true ) temp = temp+1;
    
    if(temp > 0){
        document.frm1.button2.disabled = false;
    }else{
        document.frm1.button2.disabled = true;
    }
}
</script>

<style type="text/css">
.ui-widget{
    font-family:tahoma;
    font-size:12px;
}
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:11px;
    text-align:center;
}
</style>

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">
<input name="button" type="button" class="ui-button" onclick="window.location='voucher_int_report_day.php'"  value="รายงานประจำวัน" /><input name="button" type="button" class="ui-button" onclick="window.location='voucher_int_report_approve.php'" value="รายงานที่อนุมัติ" disabled />
</div>
<div style="float:right"><input name="button" type="button" class="ui-button" onclick="window.close();" value=" ปิด " /></div>
<div style="clear:both;"></div>

<fieldset><legend><B>รายงานที่อนุมัติ</B></legend>

<div style="margin:10px;">

<div class="ui-widget">

<form name="frm1" id="frm1" action="voucher_int_approve_update.php" method="post">

<table cellpadding="3" cellspacing="1" border="0" width="100%">
<tr align="center" bgcolor="#42A0FF" style="font-weight:bold; line-height:25px">
    <td width="100">Voucher ID</td>
    <td>วันที่</td>
    <td>รายละเอียด</td>
    <td>เงินสด</td>
    <td>เลขที่เช็ค</td>
    <td>ยอดเงินในเช็ค</td>
    <td>รวม</td>
    <td>ผู้เบิก</td>
    <td>สถานะ</td>
</tr>
<?php
$qry_name=pg_query("SELECT * FROM account.tal_voucher WHERE \"qpprove_id\" is not null ORDER BY \"vc_id\" DESC ");
$num = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $sum = 0;
    $vc_id = $res_name["vc_id"];
    $print_date = $res_name["print_date"];
    $vc_detail = $res_name["vc_detail"]; $arr_detail = explode("\n",$vc_detail); $vc_detail = $arr_detail[0];
    $cash_amt = $res_name["cash_amt"];
    $cq_id = $res_name["cq_id"];
    $cq_amt = $res_name["cq_amt"];
    $maker_id = $res_name["maker_id"];
    $cancel = $res_name["cancel"];
    $acb_id = $res_name["acb_id"];
    $sum = $cash_amt+$cq_amt;
    
    if(substr($acb_id,0,1) != "I"){
        continue;
    }
    
    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\" style=\"line-height:15px\">";
    }else{
        echo "<tr class=\"even\" style=\"line-height:15px\">";
    }
?>
    <td align="center"><?php echo "$vc_id"; ?></td>
    <td align="center"><?php echo "$print_date"; ?></td>
    <td><?php echo "$vc_detail"; ?></td>
    <td align="right"><?php echo number_format($cash_amt,2); ?></td>
    <td align="center"><?php echo "$cq_id"; ?></td>
    <td align="right"><?php echo number_format($cq_amt,2); ?></td>
    <td align="right" style="font-weight:bold; color:red"><?php echo number_format($sum,2); ?></td>
    <td align="center"><?php echo "$maker_id"; ?></td>
    <td align="center">
    <?php
    if($cancel == "f"){
        echo "<span style=\"font-weight:bold; color:green\">ปกติ</span>";
    }else{
        echo "<span style=\"font-weight:bold; color:red\">ยกเลิก</span>";
    }
    ?>
    </td>
</tr>

<?php
}

if($num==0){
?>
<tr>
    <td colspan="10" align="center">- ไม่พบข้อมูล -</td>
</tr>
<?php
}
?>
</table>

</form>

</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>