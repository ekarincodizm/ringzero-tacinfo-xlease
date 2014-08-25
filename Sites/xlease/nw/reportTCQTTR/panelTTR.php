<?php
include("../../config/config.php");
$datepicker = $_GET['datepicker'];
$condition = $_GET['condition'];

if($condition=="0"){
	$txtcon="b.\"PostDate\"='$datepicker'";
}else{
	$txtcon="a.\"D_DateEntBank\"='$datepicker'";
}
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
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>

<div align="right"><a href="ttr_pdf.php?date=<?php echo "$datepicker"; ?>&condition=<?php echo $condition;?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานทั้งหมด)</span></a></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>เลขที่ใบเสร็จ</td>
    <td>IDNO</td>
    <td>ชื่อลูกค้า</td>
    <td>ทะเบียน</td>
    <td>TypePay</td>
    <td>TName</td>
	<td>เวลาที่ชำระ</td>
    <td>จำนวนเงิน</td>
</tr>

<?php
$query=pg_query("SELECT a.\"D_ChequeNo\", a.\"D_BankName\" FROM \"FTACCheque\" a
				left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
				where $txtcon and a.\"cancel\"='FALSE' group by a.\"D_ChequeNo\", a.\"D_BankName\"");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
	$D_ChequeNo2 = $resvc['D_ChequeNo'];
    $D_BankName2 = $resvc['D_BankName'];
	 
	$old_ChequeNo = "0";
	$old_BankName = "0";
	
    $query_VContact=pg_query("select *,a.\"PostID\" as post from \"FTACCheque\" a
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	left join \"TypePay\" c on a.\"TypePay\"=c.\"TypeID\"
	where a.\"D_ChequeNo\"='$D_ChequeNo2' and  a.\"D_BankName\"='$D_BankName2' and a.\"cancel\"='FALSE' and $txtcon");
    while($res_VContact=pg_fetch_array($query_VContact)){ 
		$nub+=1;
		$auto_id = $res_VContact['auto_id'];
        $PostID = $res_VContact['post'];
        $COID = $res_VContact['COID'];
		$fullname = $res_VContact['fullname'];
		$carregis = $res_VContact['carregis'];
		$TypePay = $res_VContact['TypePay'];
        $TName = $res_VContact['TName'];
		$AmtPay = $res_VContact['AmtPay'];
		$refreceipt = $res_VContact['refreceipt'];
		$D_DateEntBank = $res_VContact['D_DateEntBank'];
		$D_ChequeNo = "";
		$D_BankName = "";
		$D_ChequeNo = $res_VContact['D_ChequeNo'];
		$D_BankName = $res_VContact['D_BankName'];
		
		if(($D_ChequeNo != $old_ChequeNo) && ($D_BankName != $old_BankName)){	
			echo "<tr><td colspan=7><b>เลขที่เช็ค:$D_ChequeNo ชื่อธนาคาร : $D_BankName</b></td></tr>";
		}
		
		$sum_amt+=$AmtPay;
		$sum_amt_all+=$AmtPay;
		
		$typecode = "";
		$typecode = $refreceipt[2];

		if($typecode == "N"){
			$n_sum += $AmtPay;
		}elseif($typecode == "R"){
			$r_sum += $AmtPay;
		}elseif($typecode == "K"){
			$k_sum += $AmtPay;
		}

		$i+=1;
		if($i%2==0){
			echo "<tr class=\"odd\" align=\"left\">";
		}else{
			echo "<tr class=\"even\" align=\"left\">";
		}
	?>
        <td align="center"><?php echo "$refreceipt"; ?></td>
        <td align="center"><?php echo $COID; ?></td>
        <td><?php echo $fullname; ?></td>
        <td align="left"><?php echo $carregis; ?></td>
        <td align="center"><?php echo $TypePay; ?></td>
        <td><?php echo $TName; ?></td>
		<td align="center"><?php echo $D_DateEntBank; ?></td>
        <td align="right"><?php echo number_format($AmtPay,2); ?></td>
    </tr>
<?php
	$old_ChequeNo = $D_ChequeNo;
    $old_BankName = $D_BankName;
	}
	echo "<tr><td class=\"sum\" align=\"center\"><a href=\"ttr_only_pdf.php?date=$datepicker&D_ChequeNo=$old_ChequeNo&D_BankName=$old_BankName&condition=$condition\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
	<td colspan=3 class=\"sum\"><b>รวม N: ".number_format($n_sum,2)." | รวม R: ".number_format($r_sum,2)." | รวม K: ".number_format($k_sum,2)."</b></td>
	<td colspan=3 class=\"sum\" align=right><b>รวมเงิน</b></td><td align=right class=\"sum\"><b>".number_format($sum_amt,2)."</b></td></tr>";
	$sum_amt = 0;
	$n_sum = 0;
	$r_sum = 0;
	$k_sum = 0;
	
}

echo "<tr>
<td colspan=7 class=\"sumall\" align=right><b>รวมเงินทั้งหมด</b></td>
<td align=right class=\"sumall\"><b>".number_format($sum_amt_all,2)."</b></td></tr>";

if($num_row==0){
?>
<tr>
    <td colspan="8" align="center">- ไม่พบข้อมูล -</td>
</tr>
<?php
}
?>

</table>

<?php
}
?>