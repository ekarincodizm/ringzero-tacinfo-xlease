<?php if($contractID != "") // ถ้ามีการส่งค่ามา // ตารางด้านล่าง
{ ?>

<fieldset>
	<legend><B>ตารางแสดงการชำระค่าซื้อสิทธิเรียกร้อง</B></legend>
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
		<td>ค่างวด</td>
		<td>ยอดที่ต้องชำระ</td>
		<td>ยอดคงเหลือก่อนชำระ</td>
		<td>ยอดคงเหลือหลังชำระ</td>
	</tr>
<?php	
	$qry1=pg_query("select \"typePayRefValue\", \"debtDueDate\", \"receiveDate\", \"delay\", \"receiptID\", \"taxinvoiceID\", \"debtAmt\", \"typePayLeft\"
					from thcap_v_lease_table_current where \"contractID\" = '$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')"); 
	$numrows=pg_num_rows($qry1);
	$i=1;
	
	while($res1=pg_fetch_array($qry1))
	{
		$DueNo = $res1["typePayRefValue"]; // งวดที่  
		$ptDate = $res1["debtDueDate"]; // วันที่ครบกำหนดชำระ 
		$receiveDate = $res1["receiveDate"]; // วันที่จ่าย 
		$delay = $res1["delay"]; // จำนวนวันล่าช้า 
		$receiptID = trim($res1["receiptID"]); //เลขที่ใบเสร็จ
		$taxinvoiceID = trim($res1["taxinvoiceID"]); //เลขที่ใบกำกับภาษี
		$debtall_cut = number_format($res1["debtAmt"],2); //ค่างวดรวม VAT
		$typePayLeft = number_format($res1["debtAmt"],2); // ยอดที่ต้องชำระรวม VAT	
		$totaldebtall_before = number_format($res1["debtAmt"],2); //ยอดคงเหลือก่อนชำระรวม VAT
		$totaldebt_before = number_format($res1["debtAmt"],2); //ยอดคงเหลือก่อนชำระไม่รวม VAT
		$totaldebtall_left = number_format($res1["typePayLeft"],2); //ยอดคงเหลือหลังชำระไม่รวม VAT
		$totaldebt_left = number_format($res1["typePayLeft"],2); //ยอดคงเหลือหลังชำระไม่รวม VAT

					
		if($receiptID!=""){ //แสดงว่าจ่ายแล้ว
			echo "<tr style=\"font-size:11px; background-color:#B3DBAE;\" align=\"center\">";
		}else{
			//ตรวจดูว่าถึงวันที่ต้องชำระหรือยัง
			$nowdate=nowDate();
			if($nowdate>=$ptDate){
				echo "<tr style=\"font-size:11px; background-color:#C6FFC6;\" align=\"center\">";
			}else{
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			}
		}	
	?>
	
		<td><?php echo $DueNo; ?></td>
		<td><?php echo $ptDate; ?></td>
		<td><?php echo $receiveDate; ?></td>
		<td><?php echo $delay; ?></td>
		<td><a style="cursor:pointer; text-decoration:underline;" onclick="javascript:popU('<?php echo $rc_path; ?>/Channel_detail.php?receiptID=<?php echo $receiptID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')"><?php echo $receiptID; ?></a></td>
		<td align="right"><?php echo $debtall_cut; ?></td>
		<td align="right"><?php echo $typePayLeft; ?></td>
		<td align="right"><?php echo $totaldebtall_before; ?></td>
		<td align="right"><?php echo $totaldebtall_left; ?></td>
	</tr>
	<?php
		$i++;
		
	}
	if($numrows==0){
		echo "<tr class=\"even\" align=\"center\"><td colspan=12 height=50><h2>--ไม่พบรายการจ่าย--</h2></td></tr>";
	}
?>
	</table>		
		</div>
	</div>
</fieldset>
<?php
}
?>