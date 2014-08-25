<?php
include("../config/config.php");
$now_date = nowDate();//ดึง วันที่จาก server

$id = pg_escape_string($_GET['id']);
?>

<table cellpadding="5" cellspacing="1" border="0" width="100%" bgcolor="#E0E0E0">
<tr style="font-weight:bold; background-color:#71B8FF">
    <td>AcID</td>
    <td>AmtDr</td>
    <td>AmtCr</td>
</tr>

<?php
$qry=pg_query("select A.*,B.* from account.\"IntAccHead\" A LEFT OUTER JOIN account.\"IntAccDetail\" B on A.\"auto_id\" = B.\"autoid_abh\" WHERE A.\"auto_id\"='$id' AND A.\"cancel\"='FALSE' ");
while($res=pg_fetch_array($qry)){
    $AcID = $res["AcID"];
    $AmtDr = $res["AmtDr"];
    $AmtCr = $res["AmtCr"];
    
    $sum_dr+=$AmtDr;
    $sum_cr+=$AmtCr;
    
    $qry_name=pg_query("select * from account.\"AcTable\" WHERE \"AcID\"='$AcID' ");
    if($res_name=pg_fetch_array($qry_name)){
        $AcName = $res_name["AcName"];
    }
?>
<tr style="background-color:#FFFFFF">
    <td><?php echo "$AcID:$AcName"; ?></td>
    <td align="right"><?php echo number_format($AmtDr,2); ?></td>
    <td align="right"><?php echo number_format($AmtCr,2); ?></td>
</tr>
<?php
}
?>
<tr style="font-weight:bold; background-color:#FFFFFF">
    <td>รวม</td>
    <td align="right"><?php echo number_format($sum_dr,2); ?></td>
    <td align="right"><?php echo number_format($sum_cr,2); ?></td>
</tr>
</table>