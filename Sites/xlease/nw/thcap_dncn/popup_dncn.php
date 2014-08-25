<?php
include("../../config/config.php");

session_start();
$id_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ
$dcNoteID = pg_escape_string($_GET['idapp']);

// รายละเอียดหลัก
$typesql = pg_query("select * from account.thcap_dncn where \"dcNoteID\" = '$dcNoteID' ");
$typequery = pg_fetch_array($typesql);
$dcNoteAmtNET = $typequery["dcNoteAmtNET"];
$dcNoteAmtVAT = $typequery["dcNoteAmtVAT"];
$dcNoteAmtALL = $typequery["dcNoteAmtALL"];
$contractID = $typequery["contractID"];
$dcNoteDate = $typequery["dcNoteDate"];
$dcNoteDescription = $typequery["dcNoteDescription"];
$dcNoteStatus = $typequery["dcNoteStatus"];
$subjectStatus = $typequery["subjectStatus"]; // เรื่องที่ทำรายการ 1 - คืนเงินพัก/เงินค้ำ 2 - ส่วนลด 3-คืนเงินที่ชำระไว้เกิน หรือเงินมัดจำ
$debtID = $typequery["debtID"]; // รหัสหนี้

// ข้อความผลการอนุมัติ
if($dcNoteStatus == "0"){$dcNoteStatus_Text = "ไม่อนุมัติ";}
elseif($dcNoteStatus == "1"){$dcNoteStatus_Text = "อนุมัติ";}
elseif($dcNoteStatus == "9" || $dcNoteStatus == "8"){$dcNoteStatus_Text = "รออนุมัติ";}
else{$dcNoteStatus_Text = "";}

// กำหนดหัว popup
if($subjectStatus == "1" || $subjectStatus == "3")
{
	$headPopup = "รายละเอียดการคืนเงิน";
	
	$qry_refund = pg_query("SELECT  \"contractID\",\"doerStamp\",\"doerID\",\"dcNoteAmtALL\",\"dcNoteID\",\"dcNoteRev\",\"typeChannel\" as \"byChannel\",\"dcNoteDescription\",\"dcNoteDate\",
							\"appvRemask\",\"dcNoteStatus\",\"typeChannelName\",\"byChannelName\",\"returnTranToCus\",\"returnTranToCusName\",\"returnTranToBank\",\"returnTranToBankName\",\"returnTranToAccNo\",
							\"returnChqNo\",\"returnChqCusName\", \"byChannel\" as \"byChannelBankInt\", \"appvName\", \"appvStamp\"
													FROM account.thcap_dncn_payback
													where \"dcNoteID\" = '$dcNoteID'");
	$re_refund = pg_fetch_array($qry_refund);
	
	//ประเภทเงินที่ขอคืน
	$byChannel = $re_refund["byChannel"];
	
	//รายละเอียดประเภทการขอคืน
	$qry_txtchannel = pg_query("SELECT \"tpDesc\" FROM account.\"thcap_typePay\" where  \"tpID\" = '$byChannel' ");
	list($tpDesc) = pg_fetch_array($qry_txtchannel);
	
	//กรณีขอคืนเงินหลังจากปรับปรุงใหม่จะมี column แสดงชื่อรายการที่เลือกว่าคืนเงินพักหรือเงินค้ำ
	if($re_refund["typeChannelName"]!=""){
		$tpDesc=$re_refund["typeChannelName"];
	}
}
elseif($subjectStatus == "2")
{
	$headPopup = "รายละเอียดส่วนลด";
}

// หารายละเอียด
$subtypesql = pg_query("select * from account.thcap_dncn_details where \"dcNoteID\" = '$dcNoteID' ");
$subtypequery = pg_fetch_array($subtypesql);
$doerName = $subtypequery["doerName"];
$doerStamp = $subtypequery["doerStamp"];
$doerRemask = $subtypequery["doerRemask"];
$appvName = $subtypequery["appvName"];
$appvStamp = $subtypequery["appvStamp"];
$appvRemask = $subtypequery["appvRemask"];
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
<title>รายละเอียด</title>
</head>
<body>
<div style="text-align:center"><h2><?php echo $headPopup; ?></h2></div>
<table width="100%">
	<tr>
		<td width="50%"><b>เลขที่สัญญา :  <span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="blue">
		  <u><?php echo $contractID; ?></u></b></font></span>
		</td>
	</tr>
	<tr><td><b>รหัสรายการ : <font color="red"><?php echo $dcNoteID; ?></font></b></td></tr>
	<tr><td><b>วันที่รายการ : <?php echo substr($dcNoteDate,0,19); ?></b></td></tr>
</table>
<div><b>รายละเอียด :</b></div>  
<table width="100%" cellSpacing="1" cellPadding="2" bgcolor="#EEEED1" align="center">
	<tr bgcolor="#CDCDB4">
		<th>รหัส</th>
		<th>จำนวนเงิน</th>
		<th>VAT</th>
		<th>จำนวนเงินรวม VAT</th>
	</tr>
	<tr bgcolor="#FFFFE0">
		<td align="center"><?php echo $dcNoteID;?></td>
		<td align="right"><?php echo number_format($dcNoteAmtNET,2);?></td>
		<td align="right"><?php echo number_format($dcNoteAmtVAT,2);?></td>
		<td align="right"><?php echo number_format($dcNoteAmtALL,2);?></td>
	</tr>
</table>
<br>
<div align="left">
	<table cellSpacing="1" cellPadding="3" border="0" frame="box">
		<?php
		// ช่องทางการคืนเงิน
		if($subjectStatus == "1" || $subjectStatus == "3")
		{
			echo "<tr bgcolor=#FFFACD><td align=\"right\">ช่องทางการคืนเงิน : </td><td>&nbsp;  $re_refund[byChannelName]</td></tr>";
		}
		
		//ถ้าตรวจสอบพบว่ามีรหัสเจ้าของบัญชีแสดงว่าเป็นการคืนแบบโอนให้แสดงข้อมูลส่วนนี้ด้วย
		if(($subjectStatus == "1" || $subjectStatus == "3") && $re_refund["returnTranToCus"]!=""){
			echo "<tr bgcolor=#FFFACD><td align=\"right\">เจ้าของบัญชี : </td><td>&nbsp;  $re_refund[returnTranToCusName]</td></tr>";
			echo "<tr bgcolor=#FFFACD><td align=\"right\">รหัสธนาคาร : </td><td>&nbsp;  $re_refund[returnTranToBank]#$re_refund[returnTranToBankName]</td></tr>";
			echo "<tr bgcolor=#FFFACD><td align=\"right\">เลขที่บัญชีปลายทาง : </td><td>&nbsp;  $re_refund[returnTranToAccNo]</td></tr>";
			echo "<tr bgcolor=#FFFACD><td align=\"right\">ประเภทเงินที่ขอคืน : </td><td>&nbsp;  $tpDesc</td></tr>";
		}
		
		//ถ้าตรวจสอบพบว่ามีเลขที่เช็คแสดงว่าเป็นการคืนแบบเช็คให้แสดงข้อมูลส่วนนี้ด้วย
		if(($subjectStatus == "1" || $subjectStatus == "3") && $re_refund["returnChqNo"]!="")
		{
			// หาข้อมูลธนาคารที่ออกเช็ค
			$qry_bank = pg_query("select \"BAccount\"||'-'||\"BName\" as \"res_bank\" from \"BankInt\" where \"BID\" = '$re_refund[byChannelBankInt]' ");
			$res_bank = pg_result($qry_bank,0);
			
			echo "<tr bgcolor=#FFFACD><td align=\"right\">เช็คธนาคาร : </td><td>&nbsp;  $res_bank</td></tr>";
			echo "<tr bgcolor=#FFFACD><td align=\"right\">ออกเช็คให้กับ : </td><td>&nbsp;  $re_refund[returnChqCusName]</td></tr>";
			echo "<tr bgcolor=#FFFACD><td align=\"right\">ประเภทเงินที่ขอคืน : </td><td>&nbsp;  $tpDesc</td></tr>";
		}
		
		// รายละเอียดหนี้
		if($subjectStatus == "2")
		{
			// หารหัสประเภทค่าใช้จ่าย
			$qry_typePayID = pg_query("select \"typePayID\" from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debtID' ");
			$typePayID = pg_fetch_result($qry_typePayID,0);
				
			// รายละเอียดประเภทค่าใช้จ่าย
			$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
			while($res_type=pg_fetch_array($qry_type))
			{
				$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
				$tpFullDesc=trim($res_type["tpFullDesc"]); // รายละเอียดแบบเต็ม
			}
			
			// หาค่าอ้างอิง
			$qry_typePayRefValue = pg_query("select \"typePayRefValue\" from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debtID' ");
			$typePayRefValue = pg_fetch_result($qry_typePayRefValue,0);
			
			// หาประเภทสัญญา
			$qry_type_contract = pg_query("select \"thcap_get_creditType\"('$contractID') ");
			$res_type_contract = pg_fetch_result($qry_type_contract,0);
			
			if($res_type_contract == "HIRE_PURCHASE" || $res_type_contract == "LEASING")
			{
				// หา รหัสของค่างวด
				$qry_getMinPayType = pg_query("select account.\"thcap_mg_getMinPayType\"('$contractID') ");
				$res_getMinPayType = pg_fetch_result($qry_getMinPayType,0);
				
				// ถ้าเป็นค่างวดของ HP
				if($typePayID == $res_getMinPayType)
				{
					$tpDesc = "$tpDesc $tpFullDesc $typePayRefValue";
				}
			}
			
			echo "<tr bgcolor=#FFFACD><td align=\"right\">รายการหนี้ที่ลด : </td><td>&nbsp;  $tpDesc</td></tr>";
			echo "<tr bgcolor=#FFFACD><td align=\"right\">ค่าอ้างอิง : </td><td>&nbsp;  $tpFullDesc $typePayRefValue</td></tr>";
		}
		?>
		<tr>
			<td align="right"><b>ผู้ทำรายการ : </b></td>
			<td align="left"><?php echo $doerName;?></td>
		</tr>
		<tr>
			<td align="right"><b>วันที่ทำรายการ : </b></td>
			<td align="left"><?php echo $doerStamp;?></td>
		</tr>
		<tr>
			<td align="right" valign="top"><b>รายละเอียด : </b></td>
			<td align="left"><textarea readOnly><?php echo $doerStamp;?></textarea></td>
		</tr>
		<tr>
			<td align="right"><b>ผลการอนุมัติ : </b></td>
			<td align="left"><?php echo $dcNoteStatus_Text;?></td>
		</tr>
		<tr>
			<td align="right"><b>ผู้อนุมัติ : </b></td>
			<td align="left"><?php echo $appvName;?></td>
		</tr>
		<tr>
			<td align="right"><b>วันที่เวลาที่อนุมัติ : </b></td>
			<td align="left"><?php echo $appvStamp;?></td>
		</tr>
		<tr>
			<td align="right" valign="top"><b>เหตุผล : </b></td>
			<td align="left"><textarea readOnly><?php echo $appvRemask;?></textarea></td>
		</tr>
	</table>
</div>
<br>
<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>
</body>
</html>