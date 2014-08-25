<?php include("../../config/config.php");
$av_iduser = $_SESSION["av_iduser"];
?>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="color:#FFF;" valign="top" bgcolor="#FFFFFF" align="center" >	
	<td width="100"></td>
	<td width="70"></td>
	<td width="70"></td>
	<td width="100"></td>
	<td width="200"></td>
	<td width="270"></td>
	<td width="100"></td>
	<td width="68"></font></td>
</tr>
<?php

$condate=$_GET["condate"];
$iduser=$_GET["iduser"];
$month=$_GET["month"];
$year=$_GET["year"];
$color2=$_GET["color2"];
$divname=$_GET["divname"];
$name2=$_GET["divname2"];
$byChannel=$_GET["byChannel"];
$iduserold="";
$receiptID1="";
if($condate=="1"){
	$conditiondate="EXTRACT(MONTH FROM \"receiveStamp\")='$month' and EXTRACT(YEAR FROM \"receiveStamp\")='$year'";
}else{
	$conditiondate="EXTRACT(MONTH FROM a.\"receiveDate\")='$month' and EXTRACT(YEAR FROM a.\"receiveDate\")='$year'";
}
//เพิ่มเงื่อนไขในการค้นหา ช่องทาง:
//ถ้า เป็น-ทุกช่องทาง- ค้นหาทุกช่องทาง
$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$av_iduser' ");
$leveluser = pg_fetch_array($query_leveluser);
$emplevel=$leveluser["emplevel"];

if($emplevel<=1){
	if($byChannel==""){
		$qryreceipt=pg_query("select a.\"receiptID\" as \"receiptID\",\"id_user\",\"fullname\" from \"thcap_checkReceiptID\" a
		left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\" where a.\"receiptID\" not in(select \"receiptID\" 
		from \"thcap_checkReceiptID\" where \"doerID\" is not null and \"doerID2\" is not null)
		and	$conditiondate  and  a.\"receiveUser\"='$iduser' order by \"receiveUser\",a.\"receiptID\"");
	}
	else{
		$qryreceipt=pg_query("select a.\"receiptID\" as \"receiptID\",\"id_user\",\"fullname\" from \"thcap_checkReceiptID\" a
		left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\"
		left join \"thcap_temp_receipt_channel\" c   on a.\"receiptID\"=c.\"receiptID\" where a.\"receiptID\" not in(select \"receiptID\" 
		from \"thcap_checkReceiptID\" where \"doerID\" is not null and \"doerID2\" is not null)
		and $conditiondate and  a.\"receiveUser\"='$iduser' and  c.\"byChannel\"='$byChannel' order by \"receiveUser\",a.\"receiptID\"");
	}

}
else{
	if($byChannel==""){
		$qryreceipt=pg_query("select a.\"receiptID\" as \"receiptID\",\"id_user\",\"fullname\",a.\"doerID\" from \"thcap_checkReceiptID\" a
		left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\" where \"receiptID\" not in(select \"receiptID\" 
		from \"thcap_checkReceiptID\" where  \"doerID\" ='$av_iduser' or \"doerID2\" ='$av_iduser' or (\"doerID\" is not null and \"doerID2\" is not null)) and  a.\"receiveUser\"='$iduser' and $conditiondate order by \"receiveUser\",a.\"receiptID\"");
	}
	else{
		$qryreceipt=pg_query("select a.\"receiptID\" as \"receiptID\",\"id_user\",\"fullname\" from \"thcap_checkReceiptID\" a
		left join \"Vfuser\" b on a.\"receiveUser\"=b.\"id_user\"
		left join \"thcap_temp_receipt_channel\" c   on a.\"receiptID\"=c.\"receiptID\" where a.\"receiptID\" not in(select \"receiptID\" 
		from \"thcap_checkReceiptID\" where  \"doerID\" ='$av_iduser' or \"doerID2\" ='$av_iduser' or (\"doerID\" is not null and \"doerID2\" is not null)) and  a.\"receiveUser\"='$iduser' and $conditiondate and  c.\"byChannel\"='$byChannel' order by \"receiveUser\",a.\"receiptID\"");
	}
}
while($resreceipt=pg_fetch_array($qryreceipt))
	{
		$receiptID=$resreceipt["receiptID"];
		$iduser=$resreceipt["id_user"]; //รหัสผู้ทำรายการรับชำระ
		$username=$resreceipt["fullname"]; //ชื่อผู้ทำรายการรับชำระ
		//กรณีคนละเลขที่ใบเสร็จให้แสดงช่องทางการชำระเงิน
		if($receiptID1!=""){
			echo "<tr bgcolor=\"#FFF\"><td colspan=5></td><td colspan=2>";
				echo "<table width=\"100%\" bgcolor=\"$color2\" style=\"border-style: dashed; border-width: 1px; border-color:#8B7765; margin-bottom:3px\">";

			$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID1'  order by \"ChannelAmt\" DESC");
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
			echo "<tr><td align=\"right\"><b>สรุปเงินรวมทุกช่องทาง</b></td><td width=\"100\" align=\"right\" bgcolor=$color2><b>".number_format($sumchan,2)."</b></td></tr>";	
			echo "</table>";
		echo "</td><td></td></tr>";
		}
		//แสดงผู้ทำรายการ ถ้าชื่อซ้ำกันจะไม่ให้แสดงซ้ำ 
			
	//หารายการที่ชำระทั้งหมดของใบเสร็จที่ได้
	$receiptID_old="";
	$sumAmount=0; //จำนวนเงินรวมในใบเสร็จ
	$qrydata=pg_query("select \"receiptID\",\"receiveDate\",\"doerStamp\",\"contractID\",\"debtAmt\"-\"whtAmt\" as debtamt,\"whtAmt\", \"cusFullname\", 
	\"tpDesc\"||\"tpFullDesc\"||' '||\"typePayRefValue\" as detail,\"typePayID\",\"typePayRefValue\",\"tpDesc\",\"debtID\" as debtid,
	\"byChannelRef\",\"status\" from thcap_v_receipt_otherpay_all 
	where \"receiptID\"='$receiptID'  order by \"typePayID\"");
	while($result=pg_fetch_array($qrydata))
	{
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
			$color="#D5EFFD";
			$color2="#8EE5EE";
		}else{
			$color="#99FFCC";
			$color2="#99FF99";
		}
		//$color2="#E0EEE0"; //แสดงช่องทาง
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
			$check=""; //ไม่ต้องแสดง checkbox ให้เลือกซ้ำ
			
			if($color=="#D5EFFD"){
				$color="#99FFCC";
				$color2="#99FF99";
			}else{
				$color="#D5EFFD";
				$color2="#8EE5EE";
			}
			if($statusrec=='2'){
				$color="#CCCCCC";
				$color2="#CCCCCC";
			}
			$i--;
		}else{
			$check="<input type=\"checkbox\" name=\"$divname\" value=\"$receiptID\" onChange=Changbox('$divname','$name2')>";
		}

		echo "<tr align=center bgcolor=$color>
			<td>
			<span onclick=\"javascript : popU('../thcap/Channel_detail.php?receiptID=$receiptID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');\" style=\"cursor:pointer;\"><u>$receiptID2</u>
			</span> ";

			if($receiptID2!=""){
				//หาหมายเหตุของเลขที่ใบเสร็จ
				$qryresult=pg_query("select \"receiptRemark\" from thcap_v_receipt_details where \"receiptID\"='$receiptID2'");
				list($receiptRemark)=pg_fetch_array($qryresult);
				if($receiptRemark==""){
					echo "<img src=\"images/add.png\" width=\"19\" height=\"19\" onclick=\"javascript:popU('../thcap/allpay_result.php?receiptID=$receiptID2&method=add','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\">";
				}
			  else{
				echo "<img src=\"images/open.png\" width=\"19\" height=\"19\" onclick=\"javascript:popU('../thcap/allpay_result.php?receiptID=$receiptID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\">";
				}
			}
			echo "</td>
			<td>$receiveDate</td>
			<td>$doerStamp</td>
			<td onclick=\"javascript : popU('../thcap_installments/frm_Index.php?idno=$contractID&fromp=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=750');\" style=\"cursor:pointer;\"><u>$contractID</u></td>
			<td align=left>$cusname</td>
			<td align=left>$typePayID - $detail $due</td>
			<td align=right>".number_format($receiveAmount,2)."</td>
			<td>$check</td>
			</tr>";	
			$receiptID_old=$receiptID1;
		echo "<tr bgcolor=$color><td align=right colspan=6><b>สรุปเงินรวมในใบเสร็จ :</b></td><td align=right><b>".number_format($sumAmount,2)."</b></td><td></td></tr>";	

	} //end while แสดงรายการในใบเสร็จ	
	$receiptID1=$receiptID;
	$iduserold=$iduser;
	//แสดงจำนวนเงินรวมในใบเสร็จ
}
//แสดงช่องทางการรับชำระของใบเสร็จใบสุดท้าย
echo "<tr bgcolor=\"#FFF\"><td colspan=5></td><td colspan=2>";
	echo "<table width=\"100%\" bgcolor=\"$color2\" style=\"border-style: dashed; border-width: 1px; border-color:#8B7765; margin-bottom:3px\">";
	
	$sumchan=0; //รวมทุกช่องทาง
	$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID' order by \"ChannelAmt\" DESC");
	while($resstar=pg_fetch_array($qryredstar)){
		$chan=$resstar["byChannel"];
		$amt=$resstar["ChannelAmt"];
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
	echo "<tr><td align=\"right\"><b>สรุปเงินรวมทุกช่องทาง</b></td><td width=\"100\" align=\"right\" bgcolor=$color2><b>".number_format($sumchan,2)."</b></td></tr>";	
	echo "</table>";
	echo "</td><td></td></tr>";
	
	?>
	<script type="text/javascript">

function Changbox(name,divname2){	
	var ele=$('input[name='+name+']');  
		var num=0;
		for (i=0; i< ele.length; i++)
		{
			if($(ele[i]).is(':checked')){
				num+=1;
			}
		}			
		if(num==ele.length)
		{ 
			document.getElementById(divname2).checked = true;	
			}
		else{
			document.getElementById(divname2).checked = false;
			}			
}
</script>
</table>