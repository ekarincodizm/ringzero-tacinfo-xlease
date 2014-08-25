<?php 
if($contractID != ""){ // ถ้ามีการส่งค่ามา  // header
?>
<script type="text/javascript">
function popUPO(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<fieldset>	
	<legend><B>FA - รายละเอียดบิลขาย</B></legend>
		<div align="center">
			<div id="panel4" align="left" >
				<table  width="100%" cellspacing="1" cellpadding="1" >
					<tr bgcolor="#CDC0B0">
						<th align="center">บิลที่ผูกกับสัญญา</th>
						<th align="center">เลขที่บิล</th>
						<th align="center">ให้แก่</th>
						<th align="center">ยอดบิล</th>
						<th align="center">วันที่นัดรับเช็ค</th>
						<th align="center">ยอดนัดรับ</th>
						<th align="center">รายละเอียด</th>
					</tr>	
<?php
	//หาบิล
	$qry_BillFA = pg_query("SELECT a.\"contractID\", ta_array_list(a.\"arrayFaBill\") AS \"prebillID\", ta_array_get(a.\"arrayFaBill\", ta_array_list(a.\"arrayFaBill\"))::numeric(15,2) AS \"InvoiceAmt\"
							FROM thcap_contract_fa_bill a
							WHERE a.\"contractID\" = '$contractID'");
	$numrows_BillFA = pg_num_rows($qry_BillFA);
			
	if($numrows_BillFA > 0) // ถ้ามีการระบุวงเงินที่จะใช้
	{
				$b = 0;
				$total_bill=0;
				$total_money=0;
				while($res_BillFA = pg_fetch_array($qry_BillFA))
				{
					$b++;
					
					$prebillID = $res_BillFA["prebillID"]; // รหัสบิล
					$InvoiceAmt = $res_BillFA["InvoiceAmt"]; // ยอดนัดรับ
					
					$qry_chkBillFA = pg_query("select \"numberInvoice\", \"userSalebill\", \"userDebtor\", \"totalTaxInvoice\", \"dateAssign\", \"prebillIDMaster\"
												from \"thcap_fa_prebill\" where \"prebillID\" = '$prebillID' ");
					while($res_chkBillFA = pg_fetch_array($qry_chkBillFA))
					{
						$numberInvoice = $res_chkBillFA["numberInvoice"]; // เลขที่ใบแจ้งหนี้
						$userSalebill = $res_chkBillFA["userSalebill"]; // รหัสลูกค้าผู้ขายบิล
						$userDebtor = $res_chkBillFA["userDebtor"]; // รหัสลูกหนี้ในบิล
						$totalTaxInvoice = $res_chkBillFA["totalTaxInvoice"]; // ยอดบิล
						$dateAssign = $res_chkBillFA["dateAssign"]; // วันที่นัดรับเช็ค
						$prebillIDMaster = $res_chkBillFA["prebillIDMaster"];
					}
					
					$qry_searchSalebillName = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$userSalebill' ");
					$nameSalebill = pg_fetch_result($qry_searchSalebillName,0); // ชื่อลูกค้าผู้ขายบิล
					
					$qry_searchDebtorName = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$userDebtor' ");
					$nameDebtor = pg_fetch_result($qry_searchDebtorName,0); // ชื่อลูกหนี้ในบิล
					
					if($InvoiceAmt == ""){$InvoiceAmtText = "";} else{$InvoiceAmtText = number_format($InvoiceAmt,2);$total_money=$total_money+$InvoiceAmt;}
					if($totalTaxInvoice == ""){$totalTaxInvoiceText = "";}else{$totalTaxInvoiceText = number_format($totalTaxInvoice,2);$total_bill=$total_bill+$totalTaxInvoice;}
					
					$textpopup = "<a onclick=\"javascript:popU('../thcap_fa/fa_bill_detail.php?prebillIDMaster=$prebillIDMaster','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td></tr>";
					
					if($b%2==0){
								echo "<tr bgcolor=\"#EEDFCC\">";
							}else{
								echo "<tr bgcolor=\"#FFEFDB\">";
							}

					echo "
								<td align=\"center\">บิลที่ $b</td>
								<td align=\"left\">$numberInvoice</td>
								<td align=\"left\">$nameDebtor</td>
								<td align=\"right\">$totalTaxInvoiceText</td>
								<td align=\"center\">$dateAssign</td>
								<td align=\"right\">$InvoiceAmtText</td>
								<td align=\"center\">$textpopup</td>
						  </tr>
						";
				}
				echo "<tr><td colspan=3 align=right><strong>รวม&nbsp;&nbsp;&nbsp;</strong></td><td align=right><strong>".number_format($total_bill,2)."</strong></td><td align=right><strong>รวม&nbsp;&nbsp;&nbsp;</strong></td><td align=right><strong>".number_format($total_money,2)."</strong></td></tr>";
	}else{ 
?>
	
			<tr bgcolor="#EEDFCC">
				<td align="center" colspan="7">ไม่มีรายการบิล</td>
			</tr>
<?php
	}	
?>
				</table>
			</div>
		</div>
	</fieldset>	
<?php				
	}
?>