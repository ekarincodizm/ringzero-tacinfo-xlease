<?php
include("../config/config.php");
$datepicker = pg_escape_string($_GET['datepicker']);

if(empty($datepicker)){
    exit;
}
?>

<style type="text/css">
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
</style>

<script type="text/javascript">
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
</script>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>bank_no</td>
    <td>ref1</td>
    <td>ref2</td>
    <td>ref_name</td>
    <td>amt</td>
    <td>post_to_idno</td>
    <td>post_on_date</td>
    <td>post_on_asa_sys</td>
    <td>tran_type</td>
    <td>select</td>
</tr>

<?php
$nub = 0;
$query=pg_query("select * from \"TranPay\" WHERE \"tr_date\"='$datepicker' ORDER BY \"bank_no\" ASC");
while($resvc=pg_fetch_array($query)){
    $bank_no = $resvc['bank_no'];
    $ref1 = $resvc['ref1'];
    $ref2 = $resvc['ref2'];
    $ref_name = $resvc['ref_name'];
    $amt = $resvc['amt'];
    $post_to_idno = $resvc['post_to_idno'];
    $post_on_date = $resvc['post_on_date'];
    $post_on_asa_sys = $resvc['post_on_asa_sys'];
    $id_tranpay = $resvc['id_tranpay'];
    $tran_type = $resvc['tran_type'];
    
    $res_bank = "";
    $qry_bank=pg_query("select \"bank_code\"('$bank_no')");
    $res_bank=pg_fetch_result($qry_bank,0);

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td align="center"><?php echo $res_bank; ?></td>
        <td><?php echo $ref1; ?></td>
        <td><?php echo $ref2; ?></td>
        <td><?php echo $ref_name; ?></td>
        <td align="right"><?php echo number_format($amt,2); ?></td>
        <td><?php echo $post_to_idno; ?></td>
        <td><?php echo $post_on_date; ?></td>
        <td><?php echo $post_on_asa_sys; ?></td>
        <td><?php echo $tran_type; ?></td>
        <td align="center">
<?php if(empty($post_to_idno) || $post_to_idno == ""){ ?>
        <input type="button" name="btn1" id="btn1" value="ทำรายการนี้" onclick="javascript:popU('back_fine_tranpay_step1.php?id=<?php echo $id_tranpay; ?>&bank=<?php echo $res_bank; ?>&idno=<?php echo $post_to_idno; ?>&date=<?php echo $datepicker; ?>&amt=<?php echo $amt; ?>&trantype=<?php echo $tran_type; ?>','<?php echo "close_fine_$id_tranpay"; ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=800,height=600');">
<?php }else{ ?>
        <input type="button" name="btn1" id="btn1" value="ทำรายการนี้" onclick="javascript:popU('back_fine_tranpay_step2.php?id=<?php echo $id_tranpay; ?>&bank=<?php echo $res_bank; ?>&idno=<?php echo $post_to_idno; ?>&date=<?php echo $datepicker; ?>&amt=<?php echo $amt; ?>&trantype=<?php echo $tran_type; ?>','<?php echo "close_fine_$id_tranpay"; ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=800,height=600');">
<?php } ?>
        </td>
    </tr>
<?php
}
?>
</table>