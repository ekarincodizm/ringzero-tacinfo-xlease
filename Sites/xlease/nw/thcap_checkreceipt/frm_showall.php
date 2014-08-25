<?php include("../../config/config.php");
$av_iduser=$_SESSION["av_iduser"];
?>
<div><span style="background-color:#CCCCCC;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><b> ใบเสร็จที่ยกเลิกแล้ว</b></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="color:#FFF;" valign="top" bgcolor="#8B7D7B" align="center">
	<th>เลขที่ใบเสร็จ</th>
	<th>วันที่รับชำระ</th>
	<th>วันที่ทำรายการ</th>
	<th>เลขที่สัญญา</th>
	<th width="200">ชื่อลูกค้า</th>
	<th>รายละเอียดการรับชำระ</th>
	<th width="100">จำนวนเงิน</th>
</tr>

<?php
$iduserold="";
$receiptID1="";
$sumamtuser=0; //เงินรวมทั้งหมดที่ user แต่ละคนรับชำระ
$sumamtall=0; //เงินรวมทั้งหมดทุก user
$i=0;
//ตรวจสอบ levelของผู้ใช้งาน
$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$av_iduser' ");
$leveluser = pg_fetch_array($query_leveluser);
$emplevel=$leveluser["emplevel"];
if($emplevel<=1){ //ผู้ใช้งาน มี emplevel<=1 จะมองเ็ห็น เลขที่สัญญานั้น แม้ว่า จะตรวจสอบไปแล้ว
	$qryreceipt=pg_query("select \"receiptID\",\"id_user\",\"fullname\" from \"thcap_checkReceiptID\" a
	left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\" where \"receiptID\" not in(select \"receiptID\" 
	from \"thcap_checkReceiptID\" where \"doerID\" is not null and \"doerID2\" is not null) order by \"receiveUser\",\"receiptID\"");
}
else{
	$qryreceipt=pg_query("select \"receiptID\",\"id_user\",\"fullname\" from \"thcap_checkReceiptID\" a
	left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\" where \"receiptID\" not in(select \"receiptID\" 
	from \"thcap_checkReceiptID\" where  \"doerID\" ='$av_iduser' or \"doerID2\" ='$av_iduser' or (\"doerID\" is not null and \"doerID2\" is not null)) order by \"receiveUser\",\"receiptID\"");
}
//ค้นหาใบเสร็จทุกใบที่อยู่ในช่วง   query เดิม
/*$qryreceipt=pg_query("select \"receiptID\",\"id_user\",\"fullname\" from \"thcap_checkReceiptID\" a
left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\"
where \"checkstatus\"='0' order by \"receiveUser\",\"receiptID\"");*/
while($resreceipt=pg_fetch_array($qryreceipt)){
	$receiptID=$resreceipt["receiptID"]; //เลขที่ใบเสร็จ
	$iduser=$resreceipt["id_user"]; //รหัสผู้ทำรายการรับชำระ
	$username=$resreceipt["fullname"]; //ชื่อผู้ทำรายการรับชำระ
	
	//กรณีคนละเลขที่ใบเสร็จให้แสดงช่องทางการชำระเงิน
	if($receiptID1!=""){
		echo "<tr bgcolor=\"#FFF\"><td colspan=5></td><td colspan=2>";
			echo "<table width=\"100%\" bgcolor=\"$color2\" style=\"border-style: dashed; border-width: 1px; border-color:#8B7765; margin-bottom:3px\">";

			$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID1' order by \"ChannelAmt\" DESC");
			$sumchan=0;
			while($resstar=pg_fetch_array($qryredstar)){
				$chan=$resstar["byChannel"];
				$amt=$resstar["ChannelAmt"];
				$sumchan+=$amt;
				$byChannelRef=$resstar["byChannelRef"];
				
				$qry_hold = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
				list($chkhold) = pg_fetch_array($qry_hold);
										
				$qry_secur = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
				list($chksecur) = pg_fetch_array($qry_secur);
				
				if($chan=="999"){
					$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
				}else{
					//นำไปค้นหาในตาราง BankInt
					$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
					$ressearch=pg_fetch_array($qrysearch);
					list($BAccount,$BName)=$ressearch;
					$txtchannel3="ช่องทาง : $BAccount-$BName";
					
					if($chan==$chkhold || $chan==$chksecur || $chan=='990' || $chan=='991'){
						$txtchannel3="ช่องทาง : $BAccount-$BName เลขที่ $byChannelRef";
					}
				}
				echo "<tr><td align=\"right\"><b>$txtchannel3</b></td><td width=\"100\" align=\"right\"><b>".number_format($amt,2)."</b></td></tr>";	
			}
			//สรุปเงินรวมทุกช่องทาง
			echo "<tr><td align=\"right\"><b>สรุปเงินรวมทุกช่องทาง</b></td><td width=\"100\" align=\"right\" bgcolor=#E6E6FA><b>".number_format($sumchan,2)."</b></td></tr>";	
			echo "</table>";
		echo "</td></tr>";
	}
		
	//แสดงผู้ทำรายการ ถ้าชื่อซ้ำกันจะไม่ให้แสดงซ้ำ 
	if($iduserold!=$iduser || $iduserold==""){
		//แสดงจำนวนเงินของ user ก่อนหน้า
		if($iduserold!=""){
			echo "<tr bgcolor=\"#E6E6FA\"><td colspan=6 align=right><b>จำนวนเงินรวมทุกใบเสร็จ : </b></td><td align=right><b>".number_format($sumamtuser,2)."</b></td></tr>";
			$sumamtuser=0; //เริ่มนับจำนวนเงินรวมใหม่
		}
		
		//แสดงรายชื่อ user ถัดไป
		echo "<tr bgcolor=\"#CDB7B5\"><td colspan=7><b>ผู้รับเงิน : $username ($iduser)</b></td></tr>";
	}
	
	//หารายการที่ชำระทั้งหมดของใบเสร็จที่ได้
	$receiptID_old="";
	$sumAmount=0; //จำนวนเงินรวมในใบเสร็จ
	$qrydata=pg_query("select \"receiptID\",\"receiveDate\",\"doerStamp\",\"contractID\",\"debtAmt\"-\"whtAmt\" as debtamt,\"whtAmt\", \"cusFullname\", 
	\"tpDesc\"||\"tpFullDesc\"||' '||\"typePayRefValue\" as detail,\"typePayID\",\"typePayRefValue\",\"tpDesc\",\"debtID\" as debtid,
	\"byChannelRef\",\"status\" from thcap_v_receipt_otherpay_all 
	where \"receiptID\"='$receiptID' order by \"typePayID\"");
	while($result=pg_fetch_array($qrydata)){
		$receiptID1=$result["receiptID"]; //เลขที่ใบเสร็จของรายการนี้
		$receiptID2=$result["receiptID"]; //เลขที่ใบเสร็จ ที่เป็นตัวแปรตาม ถ้าใบเสร็จซ้ำกัน ตัวแปรนี้จะเป็นค่าว่าง
		$receiveDate=$result["receiveDate"]; //วันที่รับชำระ
		$doerStamp=$result["doerStamp"]; //วันที่ทำรายการรับชำระ
		$contractID=$result["contractID"]; //เลขที่สัญญา
		$cusname=$result["cusFullname"]; //ชื่อลูกค้า
		$receiveAmount=$result["debtamt"]; //จำนวนเงินที่ชำระ
		$sumAmount+=$receiveAmount; //จำนวนเงินรวมในใบเสร็จ
		$sumamtuser+=$receiveAmount; //เงินรวมทั้งหมดของ user แต่ละคนที่รับชำระ
		$sumamtall+=$receiveAmount; //เงินรวมทั้งหมดของทุก user ที่รับชำระ
		$whtAmt=$result["whtAmt"]; //ภาษีหัก ณ ที่จ่าย
		$typePayID=$result["typePayID"]; //รหัสรายการที่จ่าย
		$detail=$result["detail"]; //รายละเอียดรายการที่จ่าย
		$statusrec=$result["status"]; //สถานะใบเสร็จ 1=ไม่ยกเลิก 2=ยกเลิกแล้ว
		
		$i++;
		if($i%2==0){
			$color="#EED5D2";
		}else{
			$color="#FFE4E1";		
		}
		$color2="#E6E6FA"; //แสดงช่องทาง
		if($statusrec=='2'){
			$color="#CCCCCC";
			$color2="#CCCCCC";
		}
		
		//ถ้าเลขที่ใบเสร็จเดียวกัน ให้แสดงแค่ครั้งเดียว
		if($receiptID_old==$receiptID1){
			$receiptID2=""; //ไม่ต้องแสดงเลขที่ใบเสร็จซ้ำ
			$receiveDate=""; //ไม่ต้องแสดงวันที่รับชำระซ้ำ
			$doerStamp=""; //วันต้องแสดงวันที่ทำรายการรับชำระซ้ำ
			$contractID=""; //ไม่ต้องแสดงเลขที่สัญญาซ้ำ
			$cusname=""; //ไม่ต้องแสดงชื่อลูกค้าซ้ำ
			
			if($color=="#EED5D2"){
				$color="#FFE4E1";
			}else{
				$color="#EED5D2";
			}
			
			if($statusrec=='2'){
				$color="#CCCCCC";
				$color2="#CCCCCC";
			}
			$i--;
		}

		echo "<tr align=center bgcolor=$color>
			<td><span onclick=\"javascript : popU('../thcap/Channel_detail.php?receiptID=$receiptID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');\" style=\"cursor:pointer;\"><u>$receiptID2</u></span></td>
			<td>$receiveDate</td>
			<td>$doerStamp</td>
			<td onclick=\"javascript : popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=750');\" style=\"cursor:pointer;\"><u>$contractID</u></td>
			<td align=left>$cusname</td>
			<td align=left>$typePayID - $detail $due</td>
			<td align=right>".number_format($receiveAmount,2)."</td>
			</tr>";	
		$receiptID_old=$receiptID1;
	} //end while แสดงรายการในใบเสร็จ
	//แสดงจำนวนเงินรวมในใบเสร็จ
	echo "<tr bgcolor=$color><td align=right colspan=6><b>สรุปเงินรวมในใบเสร็จ :</b></td><td align=right><b>".number_format($sumAmount,2)."</b></td></tr>";
	$receiptID1=$receiptID;
	$iduserold=$iduser;
}
//แสดงช่องทางการรับชำระของใบเสร็จใบสุดท้าย
echo "<tr bgcolor=\"#FFF\"><td colspan=5></td><td colspan=2>";
	echo "<table width=\"100%\" bgcolor=\"$color2\" style=\"border-style: dashed; border-width: 1px; border-color:#8B7765; margin-bottom:3px\">";
	$sumchan=0; //สรุปรวมทุกช่องทาง
	$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID' order by \"ChannelAmt\" DESC");
	while($resstar=pg_fetch_array($qryredstar)){
		$chan=$resstar["byChannel"];
		$amt=$resstar["ChannelAmt"];
		$sumchan+=$amt;
		$byChannelRef=$resstar["byChannelRef"];
		
		$qry_hold = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
		list($chkhold) = pg_fetch_array($qry_hold);
								
		$qry_secur = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
		list($chksecur) = pg_fetch_array($qry_secur);
		
		if($chan=="999"){
			$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
		}else{
			//นำไปค้นหาในตาราง BankInt
			$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
			$ressearch=pg_fetch_array($qrysearch);
			list($BAccount,$BName)=$ressearch;
			$txtchannel3="ช่องทาง : $BAccount-$BName";
			
			if($chan==$chkhold || $chan==$chksecur || $chan=='990' || $chan=='991'){
				$txtchannel3="ช่องทาง : $BAccount-$BName เลขที่ $byChannelRef";
			}
		}
		echo "<tr><td align=\"right\"><b>$txtchannel3</b></td><td width=\"100\" align=\"right\"><b>".number_format($amt,2)."</b></td></tr>";	
	}
	//สรุปเงินรวมทุกช่องทาง
	echo "<tr><td align=\"right\"><b>สรุปเงินรวมทุกช่องทาง</b></td><td width=\"100\" align=\"right\" bgcolor=#E6E6FA><b>".number_format($sumchan,2)."</b></td></tr>";	
	echo "</table>";
echo "</td></tr>";
echo "<tr bgcolor=\"$color2\"><td colspan=6 align=right><b>จำนวนเงินรวมทุกใบเสร็จ : </b></td><td align=right><b>".number_format($sumamtuser,2)."</b></td></tr>";
echo "<tr bgcolor=\"#FFCCCC\"><td colspan=6 align=right><b>จำนวนเงินรวมทั้งหมด : </b></td><td align=right><b>".number_format($sumamtall,2)."</b></td></tr>";

?>
</table>
