<?php
include("../../config/config.php");
$datepicker = $_GET['datepicker'];

if(!empty($datepicker)){
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

<!-- <div align="right"><a href="cash_day_radio_all_pdf.php?date=<?php echo "$datepicker"; ?>&type=all" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานทั้งหมด)</span></a></div> -->

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>วันที่รับเช็ค</td>
    <td>เลขที่เช็ค</td>
    <td>ธนาคาร</td>
    <td>สาขา</td>
    <td>วันที่บนเช็ค</td>
    <td>วันที่นำเช็คเข้า</td>
    <td>บัญชีที่นำเข้า</td>
    <td>จำนวนเงิน</td>
    <td>ใบเสร็จรับเช็ค</td>
	<td>สถานะเช็ค</td>
</tr>

<?php
$query=pg_query("select * from public.\"DTACCheque\" WHERE \"D_DateReceive\"='$datepicker' ");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $D_DateReceive = $resvc['D_DateReceive'];
    $D_ChequeNo = $resvc['D_ChequeNo'];
    $D_BankID = $resvc['D_BankID'];
    $D_BankBranch = $resvc['D_BankBranch'];
    $D_DateOnChq = $resvc['D_DateOnChq'];
    $D_DateEntBank = $resvc['D_DateEntBank'];
    $BAccount = $resvc['BAccount'];
	$D_Amount = $resvc['D_Amount'];
    $D_RecNo = $resvc['D_RecNo'];
    $status = $resvc['status'];

	if($status==0){$status="รับเช็คเข้า";}
	if($status==1){$status="เช็คผ่าน";}
	if($status==2){$status="เช็คตีคืน";}
	if($status==8){$status="รอผลเช็ค";}
	if($status==9){$status="ยกเลิกรายการเช็ค";}

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td align="center"><?php echo $D_DateReceive; ?></td>
        <td align="center"><?php echo $D_ChequeNo; ?></td>
        <td align="center"><?php echo $D_BankID; ?></td>
        <td align="center"><?php echo $D_BankBranch; ?></td>
        <td align="center"><?php echo $D_DateOnChq; ?></td>
        <td align="center"><?php echo $D_DateEntBank; ?></td>
        <td align="center"><?php echo $BAccount; ?></td>
        <td align="right"><?php echo $D_Amount; ?></td>
        <td align="center"><?php echo $D_RecNo; ?></td>
		<td align="center"><?php echo $status; ?></td>
		<td></td>
    </tr>
    
<?php
}

if($num_row==0){echo "<tr><td><center>ไม่พบข้อมูล</center></td></tr>";}

//=============================================//

?>

</table>

<?php
}
?>