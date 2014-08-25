<?php
session_start();
include("../../config/config.php");
$taxinvoiceID=pg_escape_string($_GET['receiptID']);
$id_user = $_SESSION["av_iduser"]; //ผู้ใช้ที่ทำรายการ ณ ขณะนั้น
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<title>รายละเอียดใบกำกับภาษี</title>
</head>
<body>
<?php
$cancel=0;
//ตรวจสอบว่าี้ผู้ใช้งานสามารถเห็นปุ่มขอยกเลิกใบกำกับภา็็้้้้้้้็็้ษีได้หรือไม่
$qrychk=pg_query("select \"id_user\" from \"f_usermenu\" where id_user='$id_user' and id_menu='TM57' and status='TRUE'");
$numchk=pg_num_rows($qrychk);
$numchk=1;
if($numchk>0){$cancel++; } 
$qrystscancel=pg_query("select \"taxinvoiceID\" from thcap_v_taxinvoice_otherpay_cancel WHERE \"taxinvoiceID\" = '$taxinvoiceID'");
$numstscancel=pg_num_rows($qrystscancel); 


		
if($numstscancel<=0 and $numchk>0){ 
	//ตรวจสอบว่าใบกำกับภา็็้้้้้้้็็้ษีนั้นรออนุมัติยกเลิำกอยู่หรือไม่
		$qrycheck=pg_query("select \"taxinvoiceID\",\"approveStatus\",\"requestUser\"  from \"thcap_temp_taxinvoice_cancel\"  where (\"approveStatus\" = '2' or \"approveStatus\" = '1')   and \"taxinvoiceID\" = '$taxinvoiceID'");
		$numcheck=pg_num_rows($qrycheck);
		$statusApp = pg_fetch_result($qrycheck,1);
		$reqUser = pg_fetch_result($qrycheck,2);
		if($numcheck>0){ 
			if($statusApp==2){ 
				$textSt = "เอกสารนี้อยู่ระหว่างรออนุมัติยกเลิก";
				$qryname = pg_query("select fullname from \"Vfuser\" where id_user = '$reqUser' ");
				$select_name = pg_fetch_result($qryname,0);
			} 
			$cancel=0; //ใบกำกับภา็็้้้้้้้็็้ษีนั้นรออนุมัติยกเลิำกอยู่ หรือ ได้อนุมัติยกเลิกแล้ว
		}else{
			$cancel++; 
		}	
}else{
	$hidden = "hidden";
	$cancel=0; 
}
//หาใบกำกับภาษี
$qrytax=pg_query("SELECT * FROM thcap_temp_taxinvoice_details WHERE \"taxinvoiceID\"='$taxinvoiceID'");
$numtax=pg_num_rows($qrytax);
if($numtax>0){ //กรณีพบข้อมูล
	if($restax=pg_fetch_array($qrytax)){
		$receiptID=$restax["receiptRef"];
		$replacetaxinvID=trim($restax["replacetaxinvID"]);
		$taxpointDate_tax=$restax["taxpointDate"];
		list($taxpointDate_tax) = explode(" ", $taxpointDate_tax); //แยกวันที่และเวลาออกจากกัน (นำวันที่ไปใช้งานอย่างเดียว)
		$default_date="2013-03-01"; //กำหนดวันเริ่มต้นที่ต้องการให้แสดงสถานะ	
		$qry_receiptcancel = pg_query("SELECT * FROM thcap_v_receipt_otherpay_cancel where \"receiptID\" = '$receiptID'");
		$rows_receiptcancel = pg_num_rows($qry_receiptcancel);
		IF($rows_receiptcancel > 0){
			$receipttxtcancel = "  (<font color=\"red\"><b> ถูกยกเลิก </b></font>)";
		}
		
		$abh_id = trim($restax["abh_id"]); // เลขที่รายการบันทึกบัญชี
		if($abh_id == "" && $receiptID != "")
		{ // ถ้าไม่มี เลขที่รายการบันทึกบัญชี ให้ไปหาในใบเสร็จ ในกรณีที่มีการอ้างอิง
			$qry_abh_id = pg_query("select \"abh_id\" from thcap_temp_receipt_details where \"receiptID\" = '$receiptID' ");
			$abh_id = pg_result($qry_abh_id,0);
		}
		
		// หารหัส abh_autoid
		if($abh_id != "")
		{
			$qry_abh_autoid = pg_query("select \"abh_autoid\" from account.\"all_accBookHead\" where \"abh_id\" = '$abh_id' ");
			$abh_autoid = pg_result($qry_abh_autoid,0);
		}
	}
	$qry_canceltax = pg_query("select * from thcap_v_taxinvoice_otherpay_cancel WHERE \"taxinvoiceID\" = '$taxinvoiceID'");
	$iscanceltax = pg_num_rows($qry_canceltax);
	IF($iscanceltax > 0){
		$hidden = "";
		$canceltxt = "<font color=\"red\"><b> เอกสารนี้ถูกยกเลิก </b></font>";
		$qryAppv_user = pg_query("select \"approveUser\",\"requestUser\" from \"thcap_temp_taxinvoice_cancel\"  where (\"approveStatus\" = '1')   and \"taxinvoiceID\" = '$taxinvoiceID'");
		$app_id = pg_fetch_result($qryAppv_user,0);
		$req_id = pg_fetch_result($qryAppv_user,1);
		
		$qryname = pg_query("select fullname from \"Vfuser\" where id_user = '$app_id' ");
		$select_name = pg_fetch_result($qryname,0);
		
		$qryname = pg_query("select fullname from \"Vfuser\" where id_user = '$req_id' ");
		$req_name = pg_fetch_result($qryname,0);
		
		$qry_conid2=pg_query("select \"typePayID\",\"tpDesc\",\"tpFullDesc\",\"typePayRefValue\",\"debtID\"
,\"netAmt\",\"vatAmt\",\"debtAmt\",\"whtAmt\" from thcap_v_taxinvoice_otherpay_cancel WHERE \"taxinvoiceID\" = '$taxinvoiceID'");
		
		$qry_conid_show = pg_query("select distinct(\"contractID\") from thcap_v_taxinvoice_otherpay_cancel WHERE \"taxinvoiceID\" = '$taxinvoiceID'");
	}else{
		$qry_conid2=pg_query("select \"typePayID\",\"tpDesc\",\"tpFullDesc\",\"typePayRefValue\",\"debtID\"
,\"netAmt\",\"vatAmt\",\"debtAmt\",\"whtAmt\"  from thcap_v_taxinvoice_otherpay WHERE \"taxinvoiceID\" = '$taxinvoiceID' ");
		
		$qry_conid_show = pg_query("select distinct(\"contractID\") from thcap_v_taxinvoice_otherpay WHERE \"taxinvoiceID\" = '$taxinvoiceID'");
	}
	list($contractID) = pg_fetch_array($qry_conid_show);
	echo "<div style=\"text-align:center\"><h2>รายละเอียดใบกำกับภาษี</h2></div>";
	if($cancel>0){
		echo "<div style=\"text-align:right\">";
		echo "<input type=\"button\"  value=\"ขอยกเลิกใบกำกับภาษีนี้\" onclick=\"javascript:popU('../thcap_cancel_tax/TaxOtherCancelConfirm.php?contractID=$contractID&taxinvoiceID=$taxinvoiceID&statusshow=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" />";
		echo "</div>";
	} else {
			echo "<table cellspacing=1 cellpadding=1 align=right $hidden>
				<tr>
					<td vlign=middle align=right>"; if($textSt==""){echo "<img src=\"images/del.png\"/>";} else {echo "<img src=\"images/sandclock.png\"/>";} echo " <font color=#FF0000><b>";if($textSt==""){echo $canceltxt;} else {echo $textSt;} echo "</b></font><br>";
					if($textSt==""){echo "<b>ผู้ขอยกเลิก: </b>$req_name<br><b>ผู้อนุมัติ: </b>$select_name</td>";} else {echo "<b>โดย: </b>$select_name</td>";} echo "		
				</tr>
			</table>";
	}
	echo "<table width=100% border=0>";
	if($restax['taxpointDate'] >= '2013-01-01')
	{
		if($abh_id == "")
		{
			echo "<tr><td><b>การบันทึกบัญชีของใบกำกับภาษีนี้ : <font color=\"red\">รายการนี้ยังไม่บันทึกบัญชี</font></b></td></tr>";
		}
		else
		{
			echo "<tr><td><b>การบันทึกบัญชีของใบกำกับภาษีนี้ : </b> <img src=\"images/detail.gif\" onclick=\"javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" style=\"cursor:pointer;\"></td></tr>";
		}
	}
	echo "<tr><td width=50%><b>เลขที่สัญญา : <span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"blue\">
		  <u>$contractID</u></font></span></b></td>";
	echo "<td rowspan=4>";
		  if($taxpointDate_tax>=$default_date){
			echo "<table cellspacing=1 cellpadding=1 align=right>
					<tr><td bgcolor=red>
						<table bgcolor=#FFFFFF cellspacing=0 cellpadding=5>
							<tr>";
							//ตรวจสอบว่ามีการส่งจดหมายหรือยัง
							$qrysend=pg_query("select \"sendDate\" from vthcap_letter where \"detailRef\" ='$taxinvoiceID' and \"contractID\" = '$contractID'");
							$numsend=pg_num_rows($qrysend);
							if($numsend>0){
								$txtsend="จัดส่งแล้ว";
								list($sendDate)=pg_fetch_array($qrysend);
							}else{
								$txtsend="ยังไม่จัดส่ง";
							}
							echo "<td vlign=middle bgcolor=#FFFFFF><strong>สถานะการส่ง&nbsp;:</strong>&nbsp;$txtsend";
							if($txtsend=="จัดส่งแล้ว"){echo "<br><strong>วันที่จัดส่ง&nbsp;:</strong>&nbsp;$sendDate</td>";}
			echo "</tr></table></td></tr></table>";
			}else{
				//echo "วันที่ใบกำกับภาษีน้อยกว่าที่กำหนด คือ วันที่ 1 มีนาคม  2556";
			}
	echo"</td>";		
	echo "</tr>";
	echo "<tr><td><b>ใบกำกับภาษี : <font color=\"red\">$taxinvoiceID</font></b></td></tr>";
	echo "<tr><td><b>อ้างอิงใบเสร็จรับเงินเลขที่ : <a onclick=\"javascript:popU('Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor: pointer;\"><u>$receiptID</u></a></b>$receipttxtcancel</tr>";
	echo "<tr><td><b>วันที่ใบกำกับภาษี : ".$restax['taxpointDate']."</b></td></tr>";
	echo "</table>";
	echo "<div style=\"padding-top:10px\"><b>การจ่ายที่เกี่ยวข้อง :</b></div> "; 
	echo "<table width=\"100%\" cellSpacing=\"1\" cellPadding=\"2\" bgcolor=\"#EEEED1\" align=\"center\">";
	echo "<tr bgcolor=\"#CDCDB4\"><th>รหัส</th><th>รายละเอียด</th><th>จำนวนเงิน</th><th>VAT</th><th>จำนวนเงินรวม VAT</th><th>ภาษีหัก ณ ที่จ่าย</th></tr>";
	
	$sumnet=0;
	$sumvat=0;
	$sumdebt=0;
	$sumwht=0;
	
	while($result2=pg_fetch_array($qry_conid2)){	
		$typePayID = $result2["typePayID"]; 
		$tpDesc=trim($result2["tpDesc"]);
		$tpFullDesc=trim($result2["tpFullDesc"]);
		$typePayRefValue=trim($result2["typePayRefValue"]);
		$debtID = $result2["debtID"]; // รหัสหนี้
		$netAmt = $result2["netAmt"]; // ค่าใช้จ่ายนั้นๆ ก่อนภาษีมูลค่าเพิ่ม
		$vatAmt = $result2["vatAmt"]; // ภาษีมูลค่าเพิ่ม
		$debtAmt = $result2["debtAmt"]; // netAmt+vatAmt
		$whtAmt = $result2["whtAmt"];
		$fulldesc = "$tpDesc $tpFullDesc $typePayRefValue"; //รายละเอียดการรับชำระ

		$sum_netAmt += $netAmt; //รวมจำนวนเงิน
		$sum_vatAmt += $vatAmt; //รวมภาษีมูลค่าเพิ่ม
		$sum_whtAmt += $whtAmt; //รวมภาษีหัก ณ ที่จ่าย
		$sum_debtAmt += $debtAmt; //รวม
		
		echo "
		<tr bgcolor=\"#FFFFE0\">
		<td align=\"center\">$typePayID</td>
		<td>$fulldesc</td>
		<td align=\"right\">".number_format($netAmt,2)."</td>
		<td align=\"right\">".number_format($vatAmt,2)."</td>
		<td align=\"right\">".number_format($debtAmt,2)."</td>
		<td align=\"right\">".number_format($whtAmt,2)."</td>
		</tr>
		";
		
		$sumnet=$sumnet+$netAmt;
		$sumvat=$sumvat+$vatAmt;
		$sumdebt=$sumdebt+$debtAmt;
		$sumwht=$sumwht+$whtAmt;
	}
	echo "
	<tr align=\"right\" style=\"font-weight:bold;\">
		<td colspan=\"2\" align=\"center\">รวม</td>
		<td>".number_format($sumnet,2)."</td>
		<td>".number_format($sumvat,2)."</td>
		<td>".number_format($sumdebt,2)."</td>
		<td>".number_format($sumwht,2)."</td>
	</tr><table><br>
	";
	
	$sqlchannel = pg_query("SELECT \"byChannel\",\"ChannelAmt\",\"byChannelRef\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID'");		
	$rowchannel = pg_num_rows($sqlchannel);
	
	echo "<table width=\"100%\" cellSpacing=\"1\" cellPadding=\"3\" frame=\"box\" bgcolor=\"#E8E8E8\" align=\"center\">";
	if($rowchannel > 0){
		echo "<tr>
			<td height=\"25\" colspan=\"4\" align=\"center\" ><b> มีช่องทางการจ่ายดังนี้ </b>
			<hr width=\"450\"></td></tr>";
			
		$num = 0;
		while($rechannel = pg_fetch_array($sqlchannel)){
			$byChannel  = $rechannel["byChannel"];
			$ChannelAmt  = $rechannel["ChannelAmt"];
			$byChannelRef  = $rechannel["byChannelRef"];
			
			$num++;
			if($byChannel=="" || $byChannel=="0"){$txtchannel="ไม่ระบุ";}
			else{
				if($byChannel=="999"){
					$txtchannel="ภาษีหัก ณ ที่จ่าย";
				}else{
					//นำไปค้นหาในตาราง BankInt
					$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel'");
					$ressearch=pg_fetch_array($qrysearch);
					list($BAccount,$BName)=$ressearch;
					$txtchannel="$BAccount-$BName";	
					
					$qry_hold2 = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('','1')");
					list($chkhold2) = pg_fetch_array($qry_hold2);
							
					$qry_secur2 = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('','1')");
					list($chksecur2) = pg_fetch_array($qry_secur2);
					
					if($byChannel==$chksecur2 || $byChannel==$chkhold2){
						$txtchannel=$txtchannel." เลขที่ $byChannelRef";
					}
				}
			}
			echo  "<tr><td height=\"25\" width=\"35%\" align=\"right\" valign=\"top\"><b>ช่องทางที่ $num : </td><td width=\"25%\">$txtchannel</b></td>";	
			echo  "<td height=\"25\" align=\"right\" width=\"15%\" valign=\"top\"><b>จำนวนเงิน  : </b></td><td align=\"left\" valign=\"top\">".number_format($ChannelAmt,2)." <b>บาท</b></td></tr>";		
			$sumamt=$sumamt+$ChannelAmt;
		}
		echo "<tr><td colspan=4><hr width=\"450\"></td></tr>";
		echo  "<tr><td height=\"25\" width=\"35%\" align=\"right\"></td><td width=\"25%\"></td>";	
		echo  "<td height=\"25\" align=\"right\" width=\"15%\"><b>รวมรับชำระ  : </b></td><td align=\"left\">".number_format($sumamt,2)." <b>บาท</b></td></tr>";
	}
	echo "</table><br>";
	
	if($replacetaxinvID != ""){
		echo "<div style=\"padding-top:10px;\"><b>ออกแทนใบกำกับภาษีที่ยกเลิก :</b>$replacetaxinvID</div>";
	}
	echo "<div style=\"padding-top:10px;\"><b>สาขาที่ออกใบกำกับภาษีคือ :</b>".$restax["branchName"]."</div>";
	echo "<div style=\"padding-top:10px;\"><b>ผู้ออกใบกำกับภาษี :</b>".$restax["userFullname"]."</div>";
	echo "<div><b>วันเวลาที่ทำการออกใบกำกับภาษี :</b> ".$restax["doerStamp"]."</div>";
	
}else{
	echo "<div style=\"text-align:center;\"><h2>ไม่พบใบกำกับภาษี</h2></div>";
}
echo "<div style=\"text-align:center;padding:20px;\"><input type=\"button\" onclick=\"window.close();\" value=\"ปิดหน้านี้\"></div>";
?>
</body>
</html>