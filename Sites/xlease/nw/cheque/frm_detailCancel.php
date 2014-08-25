<?php
include("../../config/config.php");
$id = pg_escape_string($_GET['id']);
$chqpay=explode("#",$id);
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){
	$("#submitButton").click(function(){
		$("#submitButton").attr('disabled', true);
		if($("#result").val()==""){
			alert('กรุณาระบุเหตุผลในการยกเลิก');
			$('#result').select();
			$("#submitButton").attr('disabled', false);
			return false;
		}
	});
});
</script>
<form method="post" name="form1" action="process_cheque.php">
<?php
if($id != ""){
	//ตรวจสอบก่อนว่า id นี้กำลังรออนุมัติอยู่หรือไม่เพราะถ้ารออนุมัติอยู่จะไม่สามารถขอยกเลิกใบเสร็จได้อีก เพราะอาจร้องขอซ้ำกันได้
	$qrychkrequest=pg_query("select * from cheque_cancel where CAST(\"chqpayID\" AS character varying)='$chqpay[0]'  and \"cancelStatus\"='2'");
	$numchkrequest=pg_num_rows($qrychkrequest);
	if($numchkrequest > 0){ //แสดงว่ามีรายการกำลังรออนุมัติอยู่
		$status=2;
	}else{
		$qry_check1=pg_query("select \"chqpayID\",a.\"BAccount\",\"chequeNum\",\"cusPay\",\"moneyPay\",\"typeName\" from cheque_pay a
		left join \"BankInt\" b on a.\"BAccount\"=b.\"BAccount\"
		left join cheque_typepay c on a.\"typePay\"=c.\"typePay\"
		WHERE CAST(\"chqpayID\" AS character varying)='$chqpay[0]'");
		$numcheck=pg_num_rows($qry_check1);
		if($rescheck1=pg_fetch_array($qry_check1)){
			list($chqpayID,$BAccount,$chequeNum,$cusPay,$moneyPay,$typeName)=$rescheck1;
		}
		$moneyPay=number_format($moneyPay,2);
		
		if($numcheck>0){
			echo "<table width=\"850\" cellSpacing=\"1\" cellPadding=\"3\" border=\"0\" bgcolor=\"#CECECE\" align=\"center\">
				<tr align=\"center\" style=\"font-weight:bold;color:#FFFFFF\" bgcolor=\"#026F38\">
					<td>เลขที่เช็ค</td>
					<td>เลขที่บัญชี</td>
					<td>ประเภทการจ่าย</td>
					<td>สั่งจ่าย</td>
					<td>จำนวนเงินที่จ่าย (บาท)</td>
				</tr>
			";
			echo "<tr align=center bgcolor=\"#D6FEEA\">
					<td><span onclick=\"javascript:popU('showdetail.php?chqpayID=$chqpayID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=650')\" style=\"cursor: pointer;\" title=\"รายละเอียดการสั่งจ่าย\"><u>$chequeNum</u></span></td>
					<td>$BAccount</td>
					<td>$typeName</td>
					<td align=left>$cusPay</td>
					<td align=right>$moneyPay</td>
					</tr>
					";
			echo "<tr bgcolor=#FFFFFF><td colspan=5><b>เหตุผลที่ยกเลิก</b><br><textarea cols=60 rows=8 name=\"result\" id=\"result\"></textarea></td></tr>";
			echo "</table>";
			
			echo "<div align=center style=\"padding-top:20px;\"><input type=\"hidden\" name=\"chqpayID\" value=\"$chqpay[0]\"><input type=\"hidden\" name=\"method\" value=\"cancelchq\"><input type=\"submit\" value=\"บันทึก\" id=\"submitButton\"><input type=\"reset\" value=\"ยกเลิก\"></div>";
		}else{
			echo "<div align=center><h2>- ไม่พบรายการนี้ กรุณาตรวจสอบอีกครั้ง -</h2></div>";
		}
	}
	if($status==2){
		echo "<div align=center><h2>รายการนี้กำลังรออนุมัติยกเลิกเช็ค<br>=ไม่สามารถทำรายการซ้ำได้=</h2></div>";
	}
}else{ //กรณีไม่กรอกคำค้น
	echo "<center><h2>-กรุณากรอกคำค้นหาก่อนทำรายการ-</h2></center>";
}?>
</form>
