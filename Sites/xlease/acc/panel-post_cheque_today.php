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

<div align="right"><a href="post_cheque_today_pdf.php?date=<?php echo "$datepicker"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>เลขที่เช็ค</td>
    <td>ธนาคาร</td>
    <td>สาขา</td>
    <td>เลขที่สัญญา</td>
    <td>ยอดของสัญญา</td>
    <td>ยอดบนเช็ค</td>
</tr>

<?php
$nub = 0;
$query=pg_query("select \"ChequeNo\",\"PostID\",COUNT(\"PostID\") AS cpid from \"VPostChequeToday\" WHERE \"PrnDate\"='$datepicker' GROUP BY \"ChequeNo\",\"PostID\" ORDER BY \"ChequeNo\" ASC");
while($resvc=pg_fetch_array($query)){
    $n++;
    $ChequeNo = $resvc['ChequeNo'];
    $PostID = $resvc['PostID'];
    $cpid = $resvc['cpid'];
    
    $query_detail=pg_query("select * from \"VPostChequeToday\" WHERE \"PostID\"='$PostID' AND \"ChequeNo\"='$ChequeNo' ORDER BY \"IDNO\" ASC");
    while($resvc_detail=pg_fetch_array($query_detail)){
        $BankName = $resvc_detail['BankName'];
        $BankBranch = $resvc_detail['BankBranch'];
        $IDNO = $resvc_detail['IDNO'];
        $CusAmount = $resvc_detail['CusAmount'];
        $AmtOnCheque = $resvc_detail['AmtOnCheque'];
        
        $sum_CusAmount += $CusAmount;
        $chk_last_row++;

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td align="center"><?php echo $ChequeNo; ?></td>
        <td><?php echo $BankName; ?></td>
        <td><?php echo $BankBranch; ?></td>
        <td><?php echo $IDNO; ?></td>
        <td align="right"><?php echo number_format($CusAmount,2); ?></td>
        <?php
            if(($chk_last_row == $cpid) ){
                if($sum_CusAmount == $AmtOnCheque){
                    echo "<td align=\"right\"><u>".number_format($AmtOnCheque,2)."</u></td>";
                }else{
                    echo "<td align=\"right\" style=\"color:#ff0000; font-weight:bold\"><u>".number_format($AmtOnCheque,2)."</u></td>";
                }
                $sum_CusAmount = 0;
                $chk_last_row = 0;
            }else{
                echo "<td align=\"right\"></td>";
            }
        ?>
    </tr>
<?php
$nub++;

    }//detail
}

if($n > 0){
    echo "<tr><td colspan=\"6\" align=\"left\"><b>จำนวนเช็คทั้งหมด $n รายการ</b></td></tr>";
}else{
    echo "<tr><td colspan=\"6\" align=\"center\">- ไม่พบข้อมูล -</td></tr>";
}

?>
</table>