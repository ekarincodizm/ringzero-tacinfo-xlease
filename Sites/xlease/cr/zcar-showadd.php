<?php
include("../config/config.php");
$c = pg_escape_string($_POST['c']);
$nowdate = nowDate();//ดึง วันที่จาก server
?>

<script type="text/javascript">
$("#dateother<?php echo $c; ?>").datepicker({
    showOn: 'button',
    buttonImage: 'calendar.gif',
    buttonImageOnly: true,
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
});
</script>

<div id="TextBoxDiv<?php echo $c; ?>" style="border: 1px #C0C0C0 dashed; padding: 3px 3px 3px 3px; margin-top:3px">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="50%">เลขที่ใบเสร็จ <input type="text" name="txt_bill_other<?php echo $c; ?>" id="txt_bill_other<?php echo $c; ?>" size="15">
<select name="selecttype<?php echo $c; ?>" id="selecttype<?php echo $c; ?>">
<?php
$qry_inf=pg_query("select \"TypeID\",\"TName\" from \"TypePay\" WHERE \"TypeDep\"='C' ORDER BY \"TypeID\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $TypeID = $res_inf["TypeID"];
    $TName = $res_inf["TName"];
    echo "<option value=\"$TypeID\">$TypeID, $TName</option>";
}
?>
</select>
    </td>
    <td width="25%">จำนวนเงิน <input type="text" name="txt_money_other<?php echo $c; ?>" id="txt_money_other<?php echo $c; ?>" size="15" style="text-align:right"></td>
    <td width="25%">วันที่ <input type="text" id="dateother<?php echo $c; ?>" name="dateother<?php echo $c; ?>" size="13" value="<?php echo $nowdate; ?>"></td>
</tr>
</table>
</div>