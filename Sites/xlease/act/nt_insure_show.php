<?php
include("../config/config.php");
$id = pg_escape_string($_GET['id']);

//ค้นหายอดค้าง
$sum_outstanding1 = 0;
$qry_inf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsForceDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$id' ");
if($res_inf=pg_fetch_array($qry_inf)){
    $sum_outstanding1 = $res_inf["sum_outstanding"];
}
if($sum_outstanding1 != 0){
    $arr_typeshow[103] = $sum_outstanding1;
}

$sum_outstanding2 = 0;
$qry_inuf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsUnforceDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$id' ");
if($res_inuf=pg_fetch_array($qry_inuf)){
    $sum_outstanding2 = $res_inuf["sum_outstanding"];
}
if($sum_outstanding2 != 0){
    $arr_typeshow[102] = $sum_outstanding2;
}

$qry_amt=pg_query("select \"CusAmt\",\"TypeDep\" from carregis.\"CarTaxDue\" WHERE \"cuspaid\" = 'false' AND \"IDNO\"='$id' ");
while($res_amt=pg_fetch_array($qry_amt)){
    $CusAmt = $res_amt["CusAmt"];
    $TypeDep = $res_amt["TypeDep"];
    $arr_typeshow[$TypeDep] = $CusAmt;
}

@ksort($arr_typeshow);

$qry_if=pg_query("select \"CusLetID\" from letter.\"send_address\" WHERE \"IDNO\"='$id' AND \"CusState\"='0' AND \"active\"='TRUE' ");
if($res_if=pg_fetch_array($qry_if)){
    $CusLetID = $res_if["CusLetID"];
}

$qry_sdt=pg_query("select \"send_date\" from letter.\"send_detail\" WHERE \"CusLetID\"='$CusLetID' AND \"detail\"='4' ORDER BY \"send_date\" DESC");
if($res_sdt=pg_fetch_array($qry_sdt)){
    $send_date = $res_sdt["send_date"];
}
?>

<form action="nt_insure_add.php" method="post">

<table cellpadding="5" cellspacing="1" border="0" width="100%" bgcolor="#E0E0E0">
<tr bgcolor="#64B1FF" style="font-weight:bold; text-align:center">
    <td width="10%">เลือกชำระ</td>
    <td width="70%">ประเภท</td>
    <td width="20%">ยอดเงิน</td>
</tr>
<?php
if(count($arr_typeshow) > 0){
    foreach($arr_typeshow as $k => $v){
        $qry_nn=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$k'");
        if($res_nn=pg_fetch_array($qry_nn)){
            $TName = $res_nn["TName"];
        }
    echo "
    <tr bgcolor=\"#F8F8F8\">
        <td align=center><input type=\"checkbox\" name=\"chkbox[]\" value=\"$k#$v\" checked class=\"db\"></td>
        <td>$k : $TName</td>
        <td align=right>".number_format($v,2)."</td>
    </tr>
    ";
    }
}
?>
<tr bgcolor="#FFFFDD">
    <td align=center></td>
    <td>ค่าธรรมเนียม</td>
    <td align=right><input type="text" name="fee" id="fee" value="0.00" size="20" style="text-align:right"></td>
</tr>
<tr bgcolor="#D9FFD9">
    <td align=center></td>
    <td>วันที่แจ้งหนังสือ</td>
    <td align=right><input type="text" id="datepicker" name="datepicker" value="<?php echo $send_date; ?>" size="17" style="text-align:center"></td>
</tr>
</table>

<div align="right" style="margin:20px 0px 5px 0px"><input type="submit" name="btnsubmit" id="btnsubmit" value="บันทึก" class="ui-button"></div>
<input type="hidden" name="idno" value="<?php echo "$id"; ?>">
</form>

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
</script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>

