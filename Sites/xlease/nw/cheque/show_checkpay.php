<?php
session_start();
include("../../config/config.php");

$condition= pg_escape_string($_GET["con"]);
if($condition=="3"){
	$con="";
}else{
	$con="and \"takeCheque\"='$condition'";
}
?>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
	<td width="100">เช็คเลขที่</td>
	<td width="100">ประเภทการสั่งจ่าย</td>
	<td width="180">สั่งจ่าย</td>
	<td width="80">จำนวนเงิน</td>
	<td width="60">วันที่สั่งจ่าย</td>
	<td width="80">รายละเอียด</td>
	<td width="60">เบิกแล้ว<br><a href="#" onclick="javascript:selectAll('check');"><u>ทั้งหมด</u></a></td>
</tr>

<?php
$summoney=0;
$nubcheck=0;
$qrychq=pg_query("select \"chqpayID\",\"typeName\",\"chequeNum\",\"cusPay\",\"moneyPay\",\"datePay\",\"takeCheque\" from cheque_pay a
left join cheque_typepay b on a.\"typePay\"=b.\"typePay\"
where \"appStatus\"='1' and \"statusPay\"='TRUE' $con order by \"chequeNum\"");
$nub=pg_num_rows($qrychq);
while($reschq=pg_fetch_array($qrychq)){
	list($chqpayID,$typeName,$chequeNum,$cusPay,$moneyPay,$datePay,$takeCheque)=$reschq;
	$i+=1;
	if($i%2==0){
		echo "<tr class=\"odd\" align=center>";
	}else{
		echo "<tr class=\"even\" align=center>";
	}
	?>
	<td><?php echo $chequeNum; ?></td>
	<td><?php echo $typeName; ?></td>
	<td align="left"><?php echo $cusPay; ?></td>
	<td align="right"><?php echo number_format($moneyPay,2); ?></td>
	<td><?php echo $datePay; ?></td>
	<td>
		<img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('showdetail.php?chqpayID=<?php echo $chqpayID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=650')" style="cursor: pointer;">
	</td>
	<td>
		<?php
		if($takeCheque=="1"){
			$nubcheck++;
			
		?>
		<input type="checkbox" name="check[]" value="<?php echo $chqpayID;?>"><input type="hidden" name="method" value="checkpay">
		<?php }else{
			echo "เบิกแล้ว";
		}
		?>
	</td>		
</tr>
<?php
	$summoney+=$moneyPay;
} //end while
if($nub == 0){
	echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
}else{
	echo "<tr bgcolor=\"#F0F0F0\"><td colspan=\"3\" align=\"right\"><b>รวมเงิน</b></td><td align=right>".number_format($summoney,2)."</td><td colspan=\"3\"></td></tr>";
	if($condition=="1" || ($condition=="3" and $nubcheck>0)){
		echo "<tr bgcolor=#FFFFFF height=50 align=center><td colspan=7><input type=\"submit\" value=\" บันทึก \"><input type=\"button\" value=\"   ปิด   \" onclick=\"window.close();\"></td></tr>";
	}
}
?>
</table>
