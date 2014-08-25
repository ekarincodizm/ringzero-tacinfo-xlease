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
		echo "<table width=\"850\" cellSpacing=\"1\" cellPadding=\"3\" border=\"0\" bgcolor=\"#D7F0FD\" align=\"center\">
		<tr align=\"center\" style=\"font-weight:bold;\" bgcolor=\"#BCE6FC\">
			<td>เลขที่สัญญา</td>
			<td>ชื่อผู้กู้หลัก</td>
			<td>เลขที่ใบกำกับภาษี</td>
			<td>วันที่ใบกำกับภาษี</td>
			<td>จำนวนเงิน</td>
			<td>ยกเลิกใบกำกับภาษี</td>
		</tr>
		";
		
		$qry_con=pg_query("select \"contractID\",\"taxinvoiceID\",\"taxpointDate\",sum(\"debtAmt\") as amt from thcap_v_taxinvoice_otherpay where (\"taxinvoiceID\"='$id' or \"contractID\"='$id')
		and \"taxinvoiceID\" not in(select \"taxinvoiceID\" from \"thcap_temp_taxinvoice_cancel\" where \"approveStatus\" = '2')
		group by \"contractID\",\"taxinvoiceID\",\"taxpointDate\" order by \"taxpointDate\"");
		$numrow=pg_num_rows($qry_con);
			while($result=pg_fetch_array($qry_con))
			{
				$contractID=trim($result["contractID"]);
				$taxinvoiceID=trim($result["taxinvoiceID"]);
				$taxpointDate=trim($result["taxpointDate"]);
				$receiveAmount=trim($result["amt"]);
				
				//หาว่าเลขที่ใบเสร็จที่ยกเลิกจ่ายค่าอะไร
				$qryother=pg_query("select \"typePayID\" from thcap_v_taxinvoice_otherpay where \"taxinvoiceID\"='$taxinvoiceID' group by \"typePayID\"");
				list($typePayID)=pg_fetch_array($qryother);
				
				// หาชื่อผู้กู้หลัก
				$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = 0");
				list($full_namecus) = pg_fetch_array($qry_cusname);
				
				//หาุ typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
				$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID')");
				list($typeID) = pg_fetch_array($select);
				
				echo "<tr align=center bgcolor=\"#EAF9FF\">
				<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td>
				<td>$full_namecus</td>
				<td style=\"color:#0000FF;\"><span onclick=\"javascript:popU('../thcap/Channel_detail_v.php?receiptID=$taxinvoiceID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor: pointer;\"><u>$taxinvoiceID</u></span></td>
				<td>$taxpointDate</td>
				<td align=right>$receiveAmount</td>
				";
				// typepdf=2 หมายถึงค่าอื่นๆ
				
				echo "<td><img src=\"images/delete.gif\" width=\"16\" height=\"16\" onclick=\"javascript:popU('TaxOtherCancelConfirm.php?contractID=$contractID&taxinvoiceID=$taxinvoiceID&statusshow=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer;\"></td>";
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
