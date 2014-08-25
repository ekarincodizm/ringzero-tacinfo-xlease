<?php
include("../../config/config.php");
$id = $_GET['id'];
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<?php
$condition=0;
if($id != ""){
	//ตรวจสอบก่อนว่า id นี้กำลังรออนุมัติอยู่หรือไม่เพราะถ้ารออนุมัติอยู่จะไม่สามารถขอยกเลิกใบเสร็จได้อีก เพราะอาจร้องขอซ้ำกันได้
	// $qrychkrequest=pg_query("select * from thcap_temp_receipt_cancel where (\"contractID\"='$id' or \"receiptID\"='$id') and \"approveStatus\"='2'");
	// $numchkrequest=pg_num_rows($qrychkrequest);
	// if($numchkrequest > 0){ //แสดงว่ามีรายการกำลังรออนุมัติอยู่
		//ตรวจสอบว่ารายการที่รออยู่เป็นใบเสร็จค่างวดหรือค่าอื่นๆ
		// if($numchkrequest==1){ 
		
		// }
		// $status=2;
	//}else{
		echo "<table width=\"850\" cellSpacing=\"1\" cellPadding=\"3\" border=\"0\" bgcolor=\"#D7F0FD\" align=\"center\">
		<tr align=\"center\" style=\"font-weight:bold;\" bgcolor=\"#BCE6FC\">
			<td>เลขที่สัญญา</td>
			<td>เลขที่ใบเสร็จ</td>
			<td>วันที่จ่าย</td>
			<td>จำนวนเงินที่จ่าย</td>
			<td>ช่องทางการจ่าย</td>
			<td>ยกเลิกใบเสร็จ</td>
		</tr>
		";
		
		$qry_con=pg_query("select \"contractID\",\"receiptID\",\"receiveDate\",sum(\"debtAmt\") as amt,\"nameChannel\" from thcap_v_receipt_otherpay where \"receiptID\"='$id' or \"contractID\"='$id'
		group by \"contractID\",\"receiptID\",\"receiveDate\",\"nameChannel\" order by \"receiveDate\"");
		$numrow=pg_num_rows($qry_con);
			while($result=pg_fetch_array($qry_con))
			{
				$contractID=trim($result["contractID"]);
				$receiptID=trim($result["receiptID"]);
				$receiveDate=trim($result["receiveDate"]);
				$receiveAmount=trim($result["amt"]);
				$txtby=trim($result["nameChannel"]);
				
				//หาว่าเลขที่ใบเสร็จที่ยกเลิกจ่ายค่าอะไร
				$qryother=pg_query("select \"typePayID\" from thcap_v_receipt_otherpay where \"receiptID\"='$receiptID' group by \"typePayID\"");
				list($typePayID)=pg_fetch_array($qryother);
				
				//หาุ typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
				$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID')");
				list($typeID) = pg_fetch_array($select);
				
				echo "<tr align=center bgcolor=\"#EAF9FF\">
				<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td>
				<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&contractID=$contractID&typepdf=2\" target=\"_blank\"><u>$receiptID</u></a></td>
				<td>$receiveDate</td>
				<td align=right>$receiveAmount</td>
				<td>$txtby</td>
				";
				// typepdf=2 หมายถึงค่าอื่นๆ
				//ถ้าประเภทการจ่ายเหมือนกันแสดงว่าเป็นเงินต้น ให้ popup หน้าของเงินต้น
				if($typePayID==$typeID){
					echo "<td><img src=\"images/delete.gif\" width=\"16\" height=\"16\" onclick=\"javascript:popU('ReceiptCancelConfirm.php?contractID=$contractID&receiptID=$receiptID&statusshow=1&typePayID=$typePayID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer;\"></td>";
				}else{
					echo "<td><img src=\"images/delete.gif\" width=\"16\" height=\"16\" onclick=\"javascript:popU('ReceiptOtherCancelConfirm.php?contractID=$contractID&receiptID=$receiptID&statusshow=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer;\"></td>";
				}
				echo"</tr>";
			}

		if($numrow==0){
			echo "<tr align=center height=30 bgcolor=\"#EAF9FF\"><td colspan=6><h2>-ไม่พบข้อมูล-</h2></td></tr>";
		}

		echo "</table>";
	//}
	// if($status==2){
		// echo "<div align=center><h2>รายการนี้กำลังรออนุมัติยกเลิกใบเสร็จ<br>=ไม่สามารถทำรายการซ้ำได้=</h2></div>";
	// }
}else{ //กรณีไม่กรอกคำค้น
	echo "<center><h2>-กรุณากรอกคำค้นหาก่อนทำรายการ-</h2></center>";
}?>
