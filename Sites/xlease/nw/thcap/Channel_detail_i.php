<?php
session_start();
include("../../config/config.php");
$invoiceID=$_GET['debtInvID'];
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
<title>รายละเอียดใบแจ้งหนี้</title>
</head>
<body>
<?php
//หาใบกำกับภาษี
$qrytax = pg_query("	SELECT a.*,b.\"doerStamp\",c.\"fullname\" as \"doername\",d.select_status,d.select_user 
						FROM \"Vthcap_debt_invoice\" a
						LEFT JOIN \"thcap_temp_invoice_details\" b ON a.\"debtInvID\" = b.\"invoiceID\"
						LEFT JOIN \"Vfuser\" c ON b.\"doerID\" = c.\"id_user\"
						LEFT JOIN thcap_sendinvlist d on a.\"debtInvID\" = d.\"invoiceID\"
						WHERE a.\"debtInvID\" = '$invoiceID'");
$numtax=pg_num_rows($qrytax);
if($numtax>0){ //กรณีพบข้อมูล
	$result = pg_fetch_array($qrytax);
	$contractID = $result["contractID"];
	$debtInvID=$result["debtInvID"];
	$select_status = $result["select_status"];
	$select_user = $result["select_user"];
	$typePayRefDate=$result["typePayRefDate"];;
	
	list($typePayRefDate) = explode(" ", $typePayRefDate); //แยกวันที่และเวลาออกจากกัน (นำวันที่ไปใช้งานอย่างเดียว)
	$default_date="2013-03-01"; //กำหนดวันเริ่มต้นที่ต้องการให้แสดงสถานะ
	echo "<div style=\"text-align:center\"><h2>รายละเอียดใบแจ้งหนี้</h2></div>";	
		if($select_status==1){
				
					$qryname = pg_query("select fullname from \"Vfuser\" where id_user = '$select_user' ");
					$select_name = pg_fetch_result($qryname,0);
					
					$textstatus = "เอกสารนี้ไม่ทันสมัย";
					echo "
						<table cellspacing=1 cellpadding=1 align=right>
						<tr>
							<td vlign=middle bgcolor=#FFFFFF align=right><font color=#FF0000><b>$textstatus</b></font><br>
							<b>โดย:</b>$select_name</td>
						</tr>
						<table>";
				}
	echo "<table width=100% border=0>";
	echo "<tr><td width=50%><b>เลขที่สัญญา : <span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"blue\">
		  <u>$contractID</u></font></span></b></td>
		  <td rowspan=3 vlign=middle>";
		  if($typePayRefDate>=$default_date){
			echo "<table cellspacing=1 cellpadding=1 align=right>";
				
					echo "<tr><td bgcolor=red>
						<table bgcolor=#FFFFFF cellspacing=0 cellpadding=5>
							<tr>";
							//ตรวจสอบว่ามีการส่งจดหมายหรือยัง
							$qrysend=pg_query("select \"sendDate\" from vthcap_letter where \"detailRef\" ='$debtInvID' and \"contractID\" = '$contractID'");
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
				//echo "วันที่ใบแจ้งหนี้น้อยกว่าที่กำหนด คือ วันที่ 1 มีนาคม  2556";
			}
	echo "</td></tr>";
	echo "<tr><td><b>ใบแจ้งหนี้ : <font color=\"red\">".$result["debtInvID"]."</font></b></td></tr>";
	echo "<tr><td><b>วันที่ใบแจ้งหนี้: ".$result['typePayRefDate']."</b></td></tr>";
	echo "</table>";
	
	echo "<div style=\"padding-top:10px\"><b>รายการแจ้งหนี้ที่เกี่ยวข้อง :</b> ( <span style=\"background-color:#E8E8E8;border-style:solid;border-width:1px;\">&nbsp;&nbsp;&nbsp;</span> : คือรายการที่ชำระแล้ว | <span style=\"background-color:#FFFFFF;border-style:solid;border-width:1px\">&nbsp;&nbsp;&nbsp;</span> : คือรายการที่ต้องตรวจสอบว่าชำระหรือยัง)</div> "; 
	echo "<table width=\"100%\" cellSpacing=\"1\" cellPadding=\"2\" bgcolor=\"#EEEED1\" align=\"center\" frame=\"box\">";
	echo "<tr bgcolor=\"#CDCDB4\"><th>รายการ</th><th>รายละเอียด</th><th>จำนวนเงิน</th><th>ภาษีมูลค่าเพิ่ม</th><th>ภาษีหัก ณ ที่จ่าย</th><th>รวม</th></tr>";
	
	//หาว่ารหัสเบี้ยปรับของเลขที่สัญญานี้อะไร
	$qryfine=pg_query("select account.\"thcap_getIntFineType\"('$contractID')");
	list($typefine)=pg_fetch_array($qryfine);
		
	$sumnet = 0; // รวมจำนวนเงินไม่รวม VAT
	$sumvat = 0; // รวมจำนวน VAT
	$sumwht = 0; // รวมภาษีหัก ณ ที่จ่าย
	$sumdebt = 0; //รวมทั้งหมด	
	$no = 1;
	$showfine = 0;
	$qry_conid2 = pg_query("select * from \"Vthcap_debt_invoice\" where \"debtInvID\" = '$invoiceID' order by ranking");	
	while($result2=pg_fetch_array($qry_conid2)){	
		
		$namedetail=trim($result2["namedetail"]); // รายละเอียดการรับชำระ
		$debtNet=trim($result2["debtNet"]); // จำนวนเงินไม่รวม VAT
		$debtVat=trim($result2["debtVat"]); // จำนวน VAT
		$debtWht=trim($result2["debtWht"]); // ภาษีหัก ณ ที่จ่าย
		$debtAmt=$debtNet+$debtVat;	//รวม	
		//$intFineAmt=trim($result2["intFineAmt"]); // เบี้ยปรับที่แสดงบนใบแจ้งหนี้
		$debtDueDate=trim($result2["debtDueDate"]); // กำหนดชำระเงิน
		$debtStatus=trim($result2["debtStatus"]); // สถานะการจ่ายเงิน 1=ยังไม่จ่าย, 2=จ่ายแล้ว
		$typePayID=trim($result2["typePayID"]); // รหัสค่าใช้จ่าย
		
				
		if($debtStatus==2 || $debtStatus==5){
			$color="#E8E8E8";
		}else{
			$color="#FFFFE0";
		}
		
		//ถ้ามีเบี้ยปรับ
		if($typefine==$typePayID){
			$color="#FFFFFF";
			$no=0;
		}
		
		echo "
		<tr bgcolor=$color>
		<td align=\"center\">$no</td>
		<td>$namedetail</td>
		<td align=\"right\">".number_format($debtNet,2)."</td>
		<td align=\"right\">".number_format($debtVat,2)."</td>
		<td align=\"right\">".number_format($debtWht,2)."</td>
		<td align=\"right\">".number_format($debtAmt,2)."</td>
		</tr>
		";

		$sumnet += $debtNet;
		$sumvat += $debtVat;
		$sumwht += $debtWht;
		$sumdebt += $debtAmt;
		
		$no++;
	}
	echo "
			<tr align=\"right\" style=\"font-weight:bold;\">
				<td colspan=\"2\" align=\"center\">รวม</td>
				<td>".number_format($sumnet,2)."</td>
				<td>".number_format($sumvat,2)."</td>
				<td>".number_format($sumwht,2)."</td>
				<td>".number_format($sumdebt,2)."</td>
			</tr><table>
	";
	
	if($result["invoiceRef1"] == ""){ $ref1='-'; }else{$ref1=$result["invoiceRef1"];}
	if($result["invoiceRef2"] == ""){ $ref2='-'; }else{$ref2=$result["invoiceRef2"];}
	echo "<div style=\"padding-top:10px;\"><b>วันที่ครบกำหนดชำระ :</b> ".$debtDueDate."</div>";
	echo "<div ><b>ผู้ออกใบแจ้งหนี้ :</b>".$result["doername"]."</div>";
	echo "<div><b>วันเวลาที่ทำการออกใบแจ้งหนี้ :</b> ".$result["doerStamp"]."</div>";
	echo "<div><b>REF1 :</b> ".$ref1."</div>";
	echo "<div><b>REF2 :</b> ".$ref2."</div>";
	
	
	
}else{
	echo "<div style=\"text-align:center;\"><h2>ไม่พบใบแจ้งหนี้</h2></div>";
}

echo "<div style=\"text-align:center;padding:20px;\"><input type=\"button\" onclick=\"window.close();\" value=\"ปิดหน้านี้\"></div>";

?>
</body>
</html>