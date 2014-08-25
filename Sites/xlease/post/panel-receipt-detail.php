<?php
include("../config/config.php");
$bank = $_GET['bank'];
$datepicker = $_GET['datepicker'];


if(!empty($bank) && !empty($datepicker)){

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
</style>

<table width="100%" cellpadding="3" cellspacing="3" border="0" bgcolor="#DDEEFF">
<tr align="left">
    <td><b>ผลการค้นหา</b></td>
    <td><?php echo "ธนาคาร <b>$bank</b> วันที่ <b>$datepicker</b>"; ?>&nbsp;<a href="receipt_detail_print.php?bank=<?php echo $bank; ?>&datepicker=<?php echo $datepicker; ?>" target="_blank"><span style="font-size:16px; color:#0000FF;">(พิมพ์รายงาน)</span></td>
</tr>
</table>

<br />

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>PostID</td>
    <td>IDNO</td>
    <td>ชื่อ/สกุล</td>
    <td>ReceiptNo</td>
    <td>ประเภท</td>
    <td>จำนวนเงิน</td>
    <td>รวม</td>
    <td>ช่องทาง</td>
</tr>

<?php
$query=pg_query("select * from \"VTranPay_directly\" WHERE (\"bank\"='$bank' AND \"rec_date\"='$datepicker') ORDER BY \"memo\",\"PostID\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $PostID = $resvc['PostID'];
    $IDNO = $resvc['IDNO'];
    $name = $resvc['name'];
    $ReceiptNo = $resvc['ReceiptNo'];
    $Amount = $resvc['amount']; $Amount = round($Amount,2);
    $TypePay = $resvc['TypePay'];
    $memo = $resvc['memo'];
    $Amount_all += $Amount;

	// $query_namecus = pg_query("SELECT full_name, \"CusID\" FROM \"VSearchCus\" where \"CusID\" = '$Cusid'");
	// $result_namecus=pg_fetch_array($query_namecus);
	// $name = $result_namecus['full_name'];
	
    $query_tname=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypePay';");
    if($resvc_tname=pg_fetch_array($query_tname)){
        $TName = $resvc_tname['TName'];
    }
    
    if($nub!=1){
        if($old_id != $PostID){
            $i+=1;
            if($i%2==0){
                echo "<tr class=\"odd\" align=\"left\">";
            }else{
                echo "<tr class=\"even\" align=\"left\">";
            }
?>
                <td><?php echo $tmp_PostID; ?></td>
                <td><?php echo $tmp_IDNO; ?></td>
                <td><?php echo $tmp_name; ?></td>
                <td><?php echo $tmp_ReceiptNo; ?></td>
                <td><?php echo $tmp_TName; ?></td>
                <td align="right"><?php echo number_format($tmp_Amount,2); ?></td>
                <td align="right" style="font-weight:bold; border-bottom:1px solid black;"><?php echo number_format($tmp_sum_rows,2); ?></td>
                <td><?php echo $tmp_memo; ?></td>
            </tr>
<?php
        }else{
            $i+=1;
            if($i%2==0){
                echo "<tr class=\"odd\" align=\"left\">";
            }else{
                echo "<tr class=\"even\" align=\"left\">";
            }
?>
                <td><?php echo $tmp_PostID; ?></td>
                <td><?php echo $tmp_IDNO; ?></td>
                <td><?php echo $tmp_name; ?></td>
                <td><?php echo $tmp_ReceiptNo; ?></td>
                <td><?php echo $tmp_TName; ?></td>
                <td align="right"><?php echo number_format($tmp_Amount,2); ?></td>
                <td align="right"></td>
                <td><?php echo $tmp_memo; ?></td>
            </tr>
<?php
        }
                
    }
    
if($memo != $old_memo AND $nub!=1){
?>
    <tr style="background-color:#FFFFFF; font-weight:bold; font-size:13px; color:#000080" align="left">
        <td colspan="6" align="right">ผลรวม <?php echo $old_memo; ?></td>
        <td align="right" style="border-bottom:3px double #000080;"><?php echo number_format($Amount_memo,2); ?></td>
        <td></td>
    </tr>
<?php
    $Amount_memo = 0;
    $Amount_memo += $Amount;
    $old_memo = $memo;
}else{
    $Amount_memo += $Amount;
    $old_memo = $memo;
}

    if($old_id != $PostID){
        $old_id = $PostID;
        
        if($nub == 1){
            $sum_rows += $Amount;
        }else{
            $sum_rows = 0;
            $sum_rows += $Amount;
        }
    }else{
        $old_id = $old_id;
        $sum_rows += $Amount;
    }
    
    $tmp_PostID = $PostID;
    $tmp_IDNO = $IDNO;
    $tmp_name = $name;
    $tmp_ReceiptNo = $ReceiptNo;
    $tmp_Amount = $Amount;
    $tmp_TName = $TName;
    $tmp_sum_rows = $sum_rows;
    $tmp_memo = $memo;
}

if($num_row!=0){
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td><?php echo $tmp_PostID; ?></td>
        <td><?php echo $tmp_IDNO; ?></td>
        <td><?php echo $tmp_name; ?></td>
        <td><?php echo $tmp_ReceiptNo; ?></td>
        <td><?php echo $tmp_TName; ?></td>
        <td align="right"><?php echo number_format($tmp_Amount,2); ?></td>
        <td align="right" style="font-weight:bold; border-bottom:1px solid black;"><?php echo number_format($tmp_sum_rows,2); ?></td>
        <td><?php echo $tmp_memo; ?></td>
    </tr>
    <tr style="background-color:#FFFFFF; font-weight:bold; font-size:13px; color:#000080" align="left">
        <td colspan="6" align="right">ผลรวม <?php echo $old_memo; ?></td>
        <td align="right" style="border-bottom:3px double #000080;"><?php echo number_format($Amount_memo,2); ?></td>
        <td></td>
    </tr>
    <tr style="background-color:#FFFFFF; font-weight:bold; font-size:13px; color:red" align="left">
        <td colspan="6" align="right">รวมทั้งสิ้น</td>
        <td align="right" style="border-bottom:3px double red;"><?php echo number_format($Amount_all,2); ?></td>
        <td></td>
    </tr>
<?php
}
if($num_row==0){
?>
<tr>
    <td colspan="10" align="center">- ไม่พบข้อมูล -</td>
</tr>
<?php
}
?>
</table>

<?php
}
?>