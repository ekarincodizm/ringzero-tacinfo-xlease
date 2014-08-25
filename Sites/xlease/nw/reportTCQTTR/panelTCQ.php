<?php
include("../../config/config.php");
$datepicker = $_GET['datepicker'];
$condition = $_GET['condition'];

if($condition=="0"){
	$txtcon="b.\"PostDate\"='$datepicker'";
}else{
	$txtcon="date(a.\"D_DatetimeEnterBank\")='$datepicker'";
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

<div align="right"><a href="tcq_pdf.php?date=<?php echo "$datepicker"; ?>&condition=<?php echo $condition;?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานทั้งหมด)</span></a></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>เลขที่ใบเสร็จ</td>
    <td>IDNO</td>
    <td>ชื่อลูกค้า</td>
    <td>ทะเบียน</td>
    <td>TypePay</td>
    <td>TName</td>
	<td>เวลารับชำระ <br>(ชั่วโมง:นาที)</td>
    <td>จำนวนเงิน</td>
</tr>

<?php
$query=pg_query("SELECT a.\"D_BankName\", a.\"D_BankAccount\", a.\"D_DatetimeEnterBank\" FROM \"FTACTran\" a 
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	where $txtcon and a.\"cancel\"='FALSE' group by a.\"D_BankName\",a.\"D_BankAccount\", a.\"D_DatetimeEnterBank\"");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
	$D_BankName2 = $resvc['D_BankName'];
    $D_BankAccount2 = $resvc['D_BankAccount'];
    $D_DatetimeEnterBank2 = $resvc['D_DatetimeEnterBank'];
	
	$old_BankName = "xx";
	$old_BankAccount = "yy";
	$old_DatetimeEnterBank = "zz";
    
    $query_VContact=pg_query("select * from \"FTACTran\" a
	left join \"TypePay\" c on a.\"TypePay\"=c.\"TypeID\"
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	where \"D_BankName\"='$D_BankName2' and  \"D_BankAccount\"='$D_BankAccount2' and \"D_DatetimeEnterBank\"='$D_DatetimeEnterBank2' and $txtcon");
	
	while($res_VContact=pg_fetch_array($query_VContact)){
        $nub+=1;
		$auto_id = $res_VContact['auto_id'];
        $PostID = $res_VContact['PostID'];
        $COID = $res_VContact['COID'];
		$fullname = $res_VContact['fullname'];
		$carregis = $res_VContact['carregis'];
		$TypePay = $res_VContact['TypePay'];
        $TName = $res_VContact['TName'];
		$AmtPay = $res_VContact['AmtPay'];
		$refreceipt = $res_VContact['refreceipt']; if($refreceipt=="") $refreceipt="ไม่พบข้อมูล";
		
		$D_BankName = "";
		$D_BankAccount = "";
		$D_DatetimeEnterBank = "";
		
		$D_BankName = $res_VContact['D_BankName'];
		$D_BankAccount = $res_VContact['D_BankAccount'];
		$D_DatetimeEnterBank = $res_VContact['D_DatetimeEnterBank'];

		$timeEnterBank=substr($D_DatetimeEnterBank,11,8);
    	
		if(($D_BankName != $old_BankName) && ($D_BankAccount != $old_BankAccount) && ($D_DatetimeEnterBank != $old_DatetimeEnterBank)){
			echo "<tr><td colspan=7><b>เลขที่บัญชีธนาคาร:$D_BankAccount ชื่อธนาคาร : $D_BankName  วันเวลาที่โอนเงินเข้า : $D_DatetimeEnterBank</b></td></tr>";
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
			<td align="center"><?php echo $timeEnterBank; ?></td>
			<td align="right"><?php echo number_format($AmtPay,2); ?></td>
		</tr>  
	<?php
		$old_BankName = $D_BankName;
		$old_BankAccount = $D_BankAccount;
		$old_DatetimeEnterBank = $D_DatetimeEnterBank;
	}
    echo "<tr><td class=\"sum\" align=\"center\"><a href=\"tcq_only_pdf.php?date=$datepicker&D_BankName=$old_BankName&D_BankAccount=$old_BankAccount&D_DatetimeEnterBank=$old_DatetimeEnterBank&condition=$condition\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
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