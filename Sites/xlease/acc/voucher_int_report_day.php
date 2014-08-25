<?php
include("../config/config.php");

$now_date = nowDate();//ดึง วันที่จาก server

if(empty($_POST['datepicker'])){
    $search_date = $now_date;
}else{
    $search_date = pg_escape_string($_POST['datepicker']);
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

<?php
    $qry_name=pg_query("SELECT \"vc_id\" FROM account.tal_voucher WHERE \"print_date\" = '$search_date' AND \"finish\"='true' ORDER BY \"vc_id\" ASC");
    while($res_name=pg_fetch_array($qry_name)){
        $vc_id = $res_name["vc_id"];
?>

    $("#<?php echo "show-$vc_id"; ?>").click(function () {
        $("#<?php echo $vc_id; ?>").toggle();
    });

    $("#<?php echo $vc_id; ?>").hide();
<?php
    }
?>
    
});
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

.line {
    background-color: #D0D0D0;
    height: 1px;
}

.wdiv {
    float: left;
    width: 33%;
}
.header{
    font-size: 13px;
    font-weight: bold;
    text-align: left;
}
.list{
    font-size: 13px;
    font-weight: normal;
    line-height: 19px;
}
.detailheader{
    font-size: 12px;
    font-weight: bold;
    text-align: left;
}
.detail{
    font-size: 12px;
    font-weight: normal;
    line-height: 16px;
}

.odd1{
    background-color:#E0E0E0;
}
.even1{
    background-color:#F0F0F0;
}
.odd2{
    background-color:#E1F0FF;
}
.even2{
    background-color:#EAF4FF;
}
</style>

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
        
<div style="float:left">
<input name="button" type="button" class="ui-button" onclick="window.location='voucher_int_report_day.php'"  value="รายงานประจำวัน" disabled /><input name="button" type="button" class="ui-button" onclick="window.location='voucher_int_report_approve.php'" value="รายงานที่อนุมัติ" />
</div>
<div style="float:right"><input name="button" type="button" class="ui-button" onclick="window.close();" value=" ปิด " /></div>
<div style="clear:both;"></div>

<fieldset><legend><B>รายงานประจำวัน <?php echo $search_date; ?></B></legend>

<form name="frm1" id="frm1" action="" method="post">
<div class="ui-widget" align="center">
วันที่ <input type="text" id="datepicker" name="datepicker" value="<?php echo $search_date; ?>" size="15" style="text-align:center"> <input type="submit" name="submit" value="ค้นหา">
</div>
</form>

<div style="margin:10px;">

<div class="ui-widget">

<table cellpadding="3" cellspacing="1" border="0" width="100%">
<tr align="center" bgcolor="#42A0FF" style="font-weight:bold; line-height:20px">
    <td>รหัส</td>
    <td>รายละเอียด</td>
    <td>จำนวนเงิน</td>
</tr>

<?php
$qry_name=pg_query("SELECT * FROM account.tal_voucher WHERE \"print_date\" = '$search_date' AND \"finish\"='true' ORDER BY \"vc_id\" ASC");
while($res_name=pg_fetch_array($qry_name)){
    $sumcash_amt = 0;
    $vc_id = $res_name["vc_id"];
    $vc_detail = $res_name["vc_detail"]; $arr_detail = explode("\n",$vc_detail); $vc_detail = $arr_detail[0];
    $cash_amt = $res_name["cash_amt"];
    $amt_change = $res_name["amt_change"]; $sumcash_amt = number_format($cash_amt+$amt_change,2);
    
    $sumall += ($cash_amt+$amt_change);
    
    $in1+=1;
    if($in1%2==0){
        echo "<tr class=\"odd1\" style=\"line-height:18px\">";
    }else{
        echo "<tr class=\"even1\" style=\"line-height:18px\">";
    }
    
    echo "
        <td><u id=\"show-$vc_id\" style=\"cursor:pointer;\">$vc_id</u></td>
        <td>$vc_detail</td>
        <td align=right>$sumcash_amt</td>
    </tr>";

    echo "
    <tr>
        <td id=\"$vc_id\" colspan=\"3\" style=\"border-bottom:1px dotted #000000\">
    <table cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"550\">
    <tr align=\"center\" bgcolor=\"#42A0FF\" style=\"font-weight:bold; line-height:20px\">
        <td>รหัส</td>
        <td>Dr</td>
        <td>Cr</td>
    </tr>";
    
    $in = 0;
    $qry_detail=pg_query("SELECT * FROM account.\"IntAccDetail\" WHERE \"RefID\" = '$vc_id' ORDER BY \"auto_id\" ASC");
    while($res_detail=pg_fetch_array($qry_detail)){
        $AcID = $res_detail["AcID"];
        $AmtDr = number_format($res_detail["AmtDr"],2);
        $AmtCr = number_format($res_detail["AmtCr"],2);
        
            $qry1=pg_query("SELECT \"AcName\" FROM account.\"AcTable\" WHERE \"AcID\"='$AcID' ");
            if($res1=pg_fetch_array($qry1)){
                $AcName = $res1["AcName"];
            }
        
        $in+=1;
        if($in%2==0){
            echo "<tr id=\"$vc_id\" class=\"odd2\" style=\"line-height:15px\">";
        }else{
            echo "<tr id=\"$vc_id\" class=\"even2\" style=\"line-height:15px\">";
        }
        
        echo "
            <td>$AcID: $AcName</td>
            <td align=right>$AmtDr</td>
            <td align=right>$AmtCr</td>
        </tr>";
    }
    echo "
    </table>
        </td>
    </tr>";
}

if($in1 == 0){
?>
<tr>
    <td align="center" colspan="10">- ไม่พบข้อมูล -</td>
</tr>
<?php
}else{
?>

<tr>
    <td align="right" colspan="2"><b>รวมเงิน</b></td>
    <td align="right"><b><u><?php echo number_format($sumall,2); ?></u></b></td>
</tr>
<?php
}
?>
</table>

</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>