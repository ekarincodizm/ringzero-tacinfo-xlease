<?php
include("../config/config.php");
$idno = pg_escape_string($_GET['idno']);
?>


<table width="100%" cellpadding="3" cellspacing="1" border="0" bgcolor="#000000">
<tr style="font-weight:bold" align="center" bgcolor="#ACACAC">
    <td>วันที่</td>
    <td>เลขที่ใบเสร็จ</td>
    <td>ยอดเงิน</td>
    <td>TypePay</td>
    <td>ที่มา/ที่ไป</td>
</tr>
<?php
$qry_remain=pg_query("select * FROM \"FOtherpay\" WHERE \"IDNO\" = '$idno' AND \"O_Type\"='200' AND \"Cancel\"='FALSE' ORDER BY \"O_DATE\" ASC");
$num_remain = pg_num_rows($qry_remain);
while($res_remain=pg_fetch_array($qry_remain)){
    $O_DATE=$res_remain["O_DATE"];
    $O_RECEIPT=$res_remain["O_RECEIPT"];
    $O_MONEY=$res_remain["O_MONEY"];
    $O_Type=$res_remain["O_Type"];
    $PayType=$res_remain["PayType"];
    $RefAnyID=$res_remain["RefAnyID"];
?>
<tr style="background-color:#9BCDFF; font-size:13px" align="left">
    <td align="center"><?php echo $O_DATE; ?></td>
    <td align="center"><?php echo $O_RECEIPT; ?></td>
    <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
    <td align="center"><?php echo $O_Type; ?></td>
    <td align="left"><?php echo "$PayType/$RefAnyID"; ?></td>
</tr>
<?php

$qry_2=pg_query("select * FROM \"FOtherpay\" WHERE \"O_memo\" = '$O_RECEIPT' AND \"O_Type\"='299' AND \"Cancel\"='FALSE' ORDER BY \"O_DATE\" ASC");
$num_2 = pg_num_rows($qry_2);
while($res_2=pg_fetch_array($qry_2)){
    $n_O_DATE=$res_2["O_DATE"];
    $n_O_RECEIPT=$res_2["O_RECEIPT"];
    $n_O_MONEY=$res_2["O_MONEY"];
    $n_O_Type=$res_2["O_Type"];
    $n_PayType=$res_2["PayType"];
    $n_RefAnyID=$res_2["RefAnyID"];
    
    $chk_money += $n_O_MONEY;
?>
<tr style="background-color:#DDEEFF; font-size:13px" align="left">
    <td align="center"><?php echo $n_O_DATE; ?></td>
    <td align="center"><?php echo $n_O_RECEIPT; ?></td>
    <td align="right"><?php echo number_format($n_O_MONEY,2); ?></td>
    <td align="center"><?php echo $n_O_Type; ?></td>
    <td align="left"><?php echo "$n_PayType/$n_RefAnyID"; ?></td>
</tr>
<?php
}


if((abs($chk_money) != $O_MONEY) && $num_2 != 0){
    $summary = $O_MONEY+$chk_money;
    echo "<tr style=\"background-color:#FFC0C0; font-size:13px\" align=\"left\">";
    echo "<td align=\"center\" colspan=\"5\">- ยอดเงินรายการนี้ ยังใช้เหลือเป็นเงิน $summary บาท -</td></tr>";
}

$chk_money=0;

if($num_2 == 0){
    echo "<tr style=\"background-color:#FFC0C0; font-size:13px\" align=\"left\">";
    echo "<td align=\"center\" colspan=\"5\">- รายการนี้ยังไม่มีการใช้ -</td></tr>";
}

echo "<tr bgcolor=\"#C0C0C0\"><td colspan=\"5\" style=\"height:1px\"></td></tr>";

}

if($num_remain == 0){
    echo "<tr bgcolor=\"#FFFFFF\"><td align=\"center\" colspan=\"5\">- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>

