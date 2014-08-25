<!--รายการรับชำระทั้งหมด-->
<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#F0F0F0"  align="center">
    <tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="center" valign="middle">
        <td>วันที่ชำระ</td>
		<td>วันที่ตั้งหนี้</td>
        <td>เลขที่ใบเสร็จ</td>
        <td>รหัสประเภท<br>ค่าใช้จ่าย</td>
        <td>คำอธิบายรายการ</td>
        <td>เลขอ้างอิง</td>
        <td>ยอดเงิน</td>
		<td>ช่องทางการจ่าย</td>
		<td>Ref ช่องทางการจ่าย</td>
		<td>หมายเหตุ</td>
    </tr>
<?php
//กำหนด path ที่จะแสดงใบเสร็จ
$pathreceipt = redirect($_SERVER['PHP_SELF'],'nw/thcap'); 
if($searchdate=='now'){ //กรณีให้แสดงเฉพาะรายการปัจจุบัน
	$condition="and date(\"receiveDate\")=current_date";
}else{ //กรณีแสดงรายการทั้งหมด
	$condition="";
}
$qry_vcus=pg_query("select * from \"thcap_v_receipt_otherpay\" WHERE  \"contractID\"='$contractID' $condition ORDER BY \"receiveDate\", \"receiptID\", \"typePayID\" ");
$rows = pg_num_rows($qry_vcus);
if($rows > 0){
while($resvc=pg_fetch_array($qry_vcus)){

			$contractID = $resvc["contractID"];
        
			$bychannel = $resvc["byChannel"];
			$sqlchannel997 = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
			list($rechannel997) = pg_fetch_array($sqlchannel997);
			$sqlchannel998 = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
			list($rechannel998) = pg_fetch_array($sqlchannel998);
         
			if($bychannel == $rechannel997 || $bychannel == $rechannel998){$color99x = "#FF9933"; }else{ $color99x = ""; }
			
			$typePayID = $resvc["typePayID"];
			$sqlchannel997 = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID')");
			list($rechannel997) = pg_fetch_array($sqlchannel997);
			$sqlchannel998 = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID')");
			list($rechannel998) = pg_fetch_array($sqlchannel998);
			if($typePayID == $rechannel997 || $typePayID == $rechannel998){$typePayIDcolor99x = "#FF9933"; }else{ $typePayIDcolor99x = ""; }
			
			$i+=1;
			
			if($typePayID == $minPayType)
			{				
					echo "<tr style=\"font-size:11px\" bgcolor=\"#DDDDDD\">";				
			}
			else
			{				
					if($i%2==0){
						echo "<tr class=\"odd\">";
					}else{
						echo "<tr class=\"even\">";
					}	
			}
			$receiptfindchan = $resvc["receiptID"];
			$qry_channel = pg_query("SELECT \"byChannelRef\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptfindchan' ");
			list($channelref) = pg_fetch_array($qry_channel);
			
			
			$Channelshow = "<a onclick=\"javascript:popU('$pathreceipt/frm_byway_transpay_detail.php?receiptID=$receiptfindchan&bychannel=$bychannel','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=350')\" style=\"cursor:pointer;\" ><u>$channelref</u></a>";

			//หาหมายเหตุของเลขที่ใบเสร็จ
			$qryresult=pg_query("select \"receiptRemark\" from thcap_v_receipt_details where \"receiptID\"='$receiptfindchan'");
			list($receiptRemark)=pg_fetch_array($qryresult);
			
?>     
        <td align="center"><?php echo $resvc["receiveDate"]; ?></td>
        <td align="center"><?php echo $resvc["typePayRefDate"]; ?></td>
        <td align="center" style="color:#0000FF;"><span onclick="javascript:popU('<?php echo $pathreceipt;?>/Channel_detail.php?receiptID=<?php echo $resvc["receiptID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $resvc["receiptID"]; ?></u></span></td>
        <td align="center" bgcolor="<?php echo $typePayIDcolor99x ?>"><?php echo $typePayID; ?></td>
        <td align="center" bgcolor="<?php echo $typePayIDcolor99x ?>"><?php echo $resvc["tpDesc"]." ".$resvc["tpFullDesc"]; ?></td>
		<td align="center"><?php echo $resvc["typePayRefValue"]; ?></td>
        <td align="right"><?php echo number_format($resvc["debtAmt"],2); ?></td>
		<td align="center" bgcolor="<?php echo $color99x ?>"><?php echo $resvc["nameChannel"]; ?></td>
		<td align="center"><?php echo $Channelshow; ?></td>
		<td align="center">
			<?php
			$img=$pathreceipt.'/images/open.png';
			if($receiptRemark!="" and $receiptRemark!="-" and $receiptRemark!="--"){			
				echo"<img src=\"$img\" width=\"16\" height=\"16\" onclick=\"javascript : popU('$pathreceipt/allpay_result.php?receiptID=$receiptfindchan','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350');\" style=\"cursor:pointer;\" title=\"หมายเหตุ\">";
			}else{
				echo "-";
			}
			?>
		</td>
    </tr>
        
<?php
    }
}else{
?>
    <tr>
        <td align="center" colspan="18">ไม่พบข้อมูล</td>
    </tr>
<?php
}
?>
</table>

