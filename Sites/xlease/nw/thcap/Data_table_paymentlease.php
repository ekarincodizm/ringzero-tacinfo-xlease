<?php if($contractID != "") // ถ้ามีการส่งค่ามา // ตารางด้านล่าง
{ ?>

<fieldset>
	<legend><B>ตารางแสดงการชำระค่าเช่า</B></legend>
	<div align="center">
		<div id="panel2" align="left">
		
<?php
include("../../core/core_functions.php");
$rc_path = redirect($_SERVER['PHP_SELF'],'nw/thcap');
?>
	<table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#E0E0E0"  align="center">
	<tr bgcolor="#79BCFF" style="font-size:11px"  align="center" valign="middle">
        <td>DueNo</td>
        <td>DueDate<br />(วันครบกำหนด)</td>
        <td>วันที่จ่าย</td>
		<td>จำนวน<br>วันล่าช้า</td>
        <td>เลขที่ใบเสร็จ</td>
        <td>เลขที่ใบกำกับภาษี</td>
		<td>ค่างวดรวม VAT</td>
		<td>ยอดที่ต้องชำระ<br>รวม VAT</td>
		<td>ยอดคงเหลือก่อนชำระ<br>รวม VAT</td>
		<td>ยอดคงเหลือก่อนชำระ<br>ไม่รวม VAT</td>
		<td>ยอดคงเหลือหลังชำระ<br>รวม VAT</td>
		<td>ยอดคงเหลือหลังชำระ<br>ไม่รวม VAT</td>
	</tr>
<?php
	//หายอดทั้งหมดที่ต้องชำระรวม VAT
	$qrysum=pg_query("select sum(\"typePayAmt\"),\"contractID\" from public.\"thcap_v_lease_table_current\" where \"contractID\" = '$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID') group by \"contractID\""); 
	list($sumamtvat,$contact1)=pg_fetch_array($qrysum);
	
	//หายอด ทั้งหมดที่ต้องจ่ายไม่รวม VAT
	$qrysum2=pg_query("select sum(\"debtNet\"),\"contractID\" from public.\"thcap_v_lease_table_current\" where \"contractID\" = '$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID') group by \"contractID\""); 
	list($sumamtnovat,$contact2)=pg_fetch_array($qrysum2);
	
	$qry1=pg_query("select \"typePayAmt\", \"debtStatus\", \"debtDueDate\", \"debtAmt\", \"debtNet\", \"netAmt\", \"typePayRefValue\", \"receiveDate\", \"delay\",
						\"receiptID\", \"taxinvoiceID\", \"typePayLeft\"
					from public.\"thcap_v_lease_table_current\" where \"contractID\" = '$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')"); 
	$numrows=pg_num_rows($qry1);
	$i=1;
	
	while($res1=pg_fetch_array($qry1))
	{
		$alldebt=trim($res1["typePayAmt"]); //ยอดที่ต้องชำระ
		$debtStatus=trim($res1["debtStatus"]);
		$debtDueDate=trim($res1["debtDueDate"]);
		$debtAmt=trim($res1["debtAmt"]); //ยอดทั้งหมดที่จ่าย
		$alldebtNet=trim($res1["debtNet"]); //ยอดทั้งหมดที่จ่ายไม่รวม VAT	
		
		if($debtStatus==2){ //แสดงว่าจ่ายแล้ว
			echo "<tr style=\"font-size:11px; background-color:#B3DBAE;\" align=\"center\">";
			$alldebt=trim($res1["debtAmt"]); //กรณีที่ชำระแล้วให้เอายอดนี้มาแสดง	
			$alldebtNet=trim($res1["netAmt"]); //ยอดทั้งหมดที่จ่ายไม่รวม VAT
					
		}else{
			//ตรวจดูว่าถึงวันที่ต้องชำระหรือยัง
			$nowdate=date('Y-m-d');
			if($nowdate>=$debtDueDate){
				echo "<tr style=\"font-size:11px; background-color:#C6FFC6;\" align=\"center\">";
			}else{
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			}
		}
		
		//ยอดคงเหลือก่อนชำระถ้าเป็นงวดแรก จะต้องเหลือเต็มจำนวน
		$sumall_beforevat=$sumamtvat;
		$sumall_beforenovat=$sumamtnovat;

		
		
		$sumamtvat=trim($sumamtvat)-trim($alldebt); //ยอดค้างชำระรวม VAT
		
		$sumamtnovat=trim($sumamtnovat)-trim($alldebtNet); //ยอดค้างชำระรวม VAT	
	?>
	
		<td><?php echo trim($res1["typePayRefValue"]); ?></td>
		<td><?php echo trim($res1["debtDueDate"]); ?></td>
		<td><?php echo trim($res1["receiveDate"]); ?></td>
		<td><?php echo trim($res1["delay"]); ?></td>
		<td><a style="cursor:pointer; text-decoration:underline;" onclick="javascript:popU('<?php echo $rc_path; ?>/Channel_detail.php?receiptID=<?php echo trim($res1["receiptID"]); ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')"><?php echo trim($res1["receiptID"]); ?></a></td>
		<td><a style="cursor:pointer; text-decoration:underline;" onclick="javascript:popU('<?php echo $rc_path; ?>/Channel_detail_v.php?receiptID=<?php echo trim($res1["taxinvoiceID"]); ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')"><?php echo trim($res1["taxinvoiceID"]); ?></a></td>
		<td align="right"><?php echo number_format($alldebt,2); ?></td>
		<td align="right"><?php echo number_format($res1["typePayLeft"],2); ?></td>
		<td align="right"><?php echo number_format($sumall_beforevat,2); ?></td>
		<td align="right"><?php echo number_format($sumall_beforenovat,2); ?></td>
		<td align="right"><?php echo number_format($sumamtvat,2); ?></td>
		<td align="right"><?php echo number_format($sumamtnovat,2); ?></td>
	</tr>
	<?php
		$i++;
		
	}
	if($numrows==0){
		echo "<tr class=\"even\" align=\"center\"><td colspan=11 height=50><h2>--ไม่พบรายการจ่าย--</h2></td></tr>";
	}
?>
	</table>		
		</div>
	</div>
</fieldset>
<?php
}
?>