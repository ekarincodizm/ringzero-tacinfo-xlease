<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติยกเลิกใบกำกับภาษี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>(THCAP) อนุมัติยกเลิกใบกำกับภาษี</h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">(THCAP) อนุมัติยกเลิกใบกำกับภาษี</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>เลขที่ใบกำกับภาษี</td>
				<td>วันที่ใบกับกำภาษี</td>
				<td>จำนวนเงิน</td>
				<td>ผู้ขอยกเลิก</td>
				<td>วันเวลาที่ทำการขอยกเลิก</td>
				<td>รายละเอียด</td>
			</tr>
			<?php
			$qry_app=pg_query("select \"cancelTaxID\", \"contractID\", \"taxinvoiceID\", \"requestUser\", \"requestDate\", \"result\" 
								from  \"thcap_temp_taxinvoice_cancel\"
								where  \"approveStatus\" = '2' 
								order by \"requestDate\" ");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$cancelID=$res_app["cancelTaxID"];
				$contract=$res_app["contractID"];
				$receipt=$res_app["taxinvoiceID"];
				$requestUser=$res_app["requestUser"];
				$requestDate=$res_app["requestDate"];
				$result=$res_app["result"];
				if($result==""){
					$result="";
				}else{
					$result="and a.\"result\"='$result'";
				}
				
				//หาว่าใบเสร็จนี้ถูกลบหรือยังจากตาราง  thcap_v_receipt_otherpay
				$qrychk=pg_query("select * from thcap_v_taxinvoice_otherpay where \"taxinvoiceID\"='$receipt'");
				$nubchk=pg_num_rows($qrychk);
					
				//หาข้อมูลที่เหลือออกมาแสดง 
				if($nubchk>0){ //แสดงว่ายังไม่ถูกลบ
					$qry_receipt=pg_query("	SELECT a.\"cancelTaxID\",a.\"contractID\",a.\"taxinvoiceID\",\"fullname\",\"requestDate\",e.\"taxpointDate\",f.\"receiveAmount\",e.\"debtID\",e.\"typePayID\"
											FROM \"thcap_temp_taxinvoice_cancel\" a
											LEFT JOIN \"Vfuser\" b on a.\"requestUser\" = b.\"id_user\"
											LEFT JOIN \"thcap_v_taxinvoice_otherpay\" e on a.\"taxinvoiceID\" = e.\"taxinvoiceID\"
											LEFT JOIN (	select aa1.\"taxinvoiceID\",sum(aa1.\"debtAmt\") as \"receiveAmount\"
														from \"thcap_v_taxinvoice_otherpay\" aa1
														left join \"thcap_temp_taxinvoice_cancel\" bb1 on aa1.\"taxinvoiceID\" = bb1.\"taxinvoiceID\"
														where bb1.\"cancelTaxID\" = '$cancelID'
														group by aa1.\"taxinvoiceID\"
													  ) f on f.\"taxinvoiceID\" = a.\"taxinvoiceID\"
											WHERE a.\"cancelTaxID\" = '$cancelID'
											ORDER BY \"requestDate\" ");
				}else{
					$qry_receipt=pg_query("	SELECT a.\"cancelTaxID\",a.\"contractID\",a.\"taxinvoiceID\",\"fullname\",\"requestDate\",e.\"taxpointDate\",f.\"receiveAmount\",e.\"debtID\",e.\"typePayID\"
											FROM \"thcap_temp_taxinvoice_cancel\" a
											LEFT JOIN \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
											LEFT JOIN \"thcap_v_taxinvoice_otherpay_cancel\" e on a.\"taxinvoiceID\"=e.\"taxinvoiceID\"
											LEFT JOIN (select aa1.\"taxinvoiceID\",sum(aa1.\"debtAmt\") as \"receiveAmount\"
													from \"thcap_v_taxinvoice_otherpay_cancel\" aa1
													left join \"thcap_temp_taxinvoice_cancel\" bb1 on aa1.\"taxinvoiceID\" = bb1.\"taxinvoiceID\"
													where bb1.\"cancelTaxID\" = '$cancelID'
													group by aa1.\"taxinvoiceID\"
												) f on f.\"taxinvoiceID\" = a.\"taxinvoiceID\"
											WHERE a.\"cancelTaxID\"='$cancelID'
											ORDER BY \"requestDate\" ");
				}
				
				$res_receipt=pg_fetch_array($qry_receipt);
				$cancelID=$res_receipt["cancelTaxID"];
				$contractID=$res_receipt["contractID"];
				$taxinvoiceID=$res_receipt["taxinvoiceID"];
				$receiveDate=$res_receipt["taxpointDate"];
				$receiveAmount=$res_receipt["receiveAmount"];
				$fullname=$res_receipt["fullname"];
				$byChannel=$res_receipt["nameChannel"];			
				$debtID=$res_receipt["debtID"];
				$typePayID=$res_receipt["typePayID"];
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				
				//หา typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
				$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID')");
				list($typeID) = pg_fetch_array($select);
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td align="center" style="color:#0000FF;"><span onclick="javascript:popU('../thcap/Channel_detail_v.php?receiptID=<?php echo $taxinvoiceID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $taxinvoiceID; ?></u></span></td>
				<td align="left"><?php echo $receiveDate; ?></td>
				<td><?php echo $receiveAmount; ?></td>
				<td><?php echo $fullname; ?></td>
				<td><?php echo $requestDate; ?></td>
				<td>
					<span onclick="javascript:popU('ReceiptOtherCancelConfirm.php?contractID=<?php echo $contractID?>&taxinvoiceID=<?php echo $taxinvoiceID?>&cancelID=<?php echo $cancelID;?>&statusshow=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor:pointer;"><u>ตรวจสอบ</u></span>
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>
<?php
include("frm_appvcancel_history_limit.php");
?>
</body>
</html>