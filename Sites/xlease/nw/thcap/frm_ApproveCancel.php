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
    <title><?php echo $_SESSION['session_company_name']; ?></title>
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
		<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">(THCAP) อนุมัติยกเลิกใบเสร็จ</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>เลขที่ใบเสร็จ</td>
				<td>วันที่จ่าย</td>
				<td>จำนวนเงินที่จ่าย</td>
				<td>ผู้ขอยกเลิก</td>
				<td>วันเวลาที่ทำการขอยกเลิก</td>
				<td>ช่องทางการจ่าย</td>
				<td>Ref ช่องทางการจ่าย</td>
				<td>รายละเอียด</td>
			</tr>
			<?php
			$qry_app=pg_query("select a.\"cancelID\",a.\"contractID\",a.\"receiptID\",a.\"requestUser\",a.\"requestDate\",a.\"result\" 
			from  thcap_temp_receipt_cancel a  
			where  a.\"approveStatus\"='2' 
			order by a.\"requestDate\"");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$cancelID=$res_app["cancelID"];
				$contract=$res_app["contractID"];
				$receipt=$res_app["receiptID"];
				$requestUser=$res_app["requestUser"];
				$requestDate=$res_app["requestDate"];
				$result=$res_app["result"];
				if($result==""){
					$result="";
				}else{
					$result="and a.\"result\"='$result'";
				}
				
				//หาว่าใบเสร็จนี้ถูกลบหรือยังจากตาราง  thcap_v_receipt_otherpay
				$qrychk=pg_query("select * from thcap_v_receipt_otherpay where \"receiptID\"='$receipt'");
				$nubchk=pg_num_rows($qrychk);
					
				//หาข้อมูลที่เหลือออกมาแสดง 
				if($nubchk>0){ //แสดงว่ายังไม่ถูกลบ
					$qry_receipt=pg_query("		SELECT a.\"cancelID\",a.\"contractID\",a.\"receiptID\",\"fullname\",\"requestDate\",e.\"receiveDate\",f.\"receiveAmount\",e.\"nameChannel\",e.\"debtID\",e.\"typePayID\",e.\"byChannelRef\",e.\"byChannel\"
												FROM \"thcap_temp_receipt_cancel\" a
												LEFT JOIN \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
												LEFT JOIN \"thcap_v_receipt_otherpay\" e on a.\"receiptID\"=e.\"receiptID\"
												LEFT JOIN (		select aa1.\"receiptID\",sum(aa1.\"debtAmt\") as \"receiveAmount\"
																from \"thcap_v_receipt_otherpay\" aa1
																left join \"thcap_temp_receipt_cancel\" bb1 on aa1.\"receiptID\" = bb1.\"receiptID\"
																where bb1.\"cancelID\" = '$cancelID'
																group by aa1.\"receiptID\"
														  ) f on f.\"receiptID\" = a.\"receiptID\"
												WHERE a.\"cancelID\"='$cancelID'
												ORDER BY \"requestDate\"
											");
				}else{
					$qry_receipt=pg_query("		SELECT a.\"cancelID\",a.\"contractID\",a.\"receiptID\",\"fullname\",\"requestDate\",e.\"receiveDate\",f.\"receiveAmount\",e.\"nameChannel\",e.\"debtID\",e.\"typePayID\",e.\"byChannelRef\",e.\"byChannel\"
												FROM \"thcap_temp_receipt_cancel\" a
												LEFT JOIN \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
												LEFT JOIN \"thcap_v_receipt_otherpay_cancel\" e on a.\"receiptID\"=e.\"receiptID\"
												LEFT JOIN (		select aa1.\"receiptID\",sum(aa1.\"debtAmt\") as \"receiveAmount\"
																from \"thcap_v_receipt_otherpay_cancel\" aa1
																left join \"thcap_temp_receipt_cancel\" bb1 on aa1.\"receiptID\" = bb1.\"receiptID\"
																where bb1.\"cancelID\" = '$cancelID'
																group by aa1.\"receiptID\"
														  ) f on f.\"receiptID\" = a.\"receiptID\"
												WHERE a.\"cancelID\"='$cancelID'
												ORDER BY \"requestDate\"
					
										  ");
				}
				
				$res_receipt=pg_fetch_array($qry_receipt);
				$cancelID=$res_receipt["cancelID"];
				$contractID=$res_receipt["contractID"];
				$receiptID=$res_receipt["receiptID"];
				$receiveDate=$res_receipt["receiveDate"];
				$receiveAmount=$res_receipt["receiveAmount"];
				$fullname=$res_receipt["fullname"];
				$byChannel=$res_receipt["nameChannel"];			
				$debtID=$res_receipt["debtID"];
				$typePayID=$res_receipt["typePayID"];
				$byChannelRef=$res_receipt["byChannelRef"];
				$byChannelsend=$res_receipt["byChannel"];
				$byChannelshow = "<a onclick=\"javascript:popU('frm_byway_transpay_detail.php?receiptID=$receiptID&bychannel=$byChannelsend','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=350')\" style=\"cursor:pointer;\" ><u>$byChannelRef</u></a>";
				
				if($byChannel==""){$txtchannel="ไม่ระบุ";}
				else{
					$txtchannel="$byChannel";
				}
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				
				//หา typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
				$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID')");
				list($typeID) = pg_fetch_array($select);
				
				//นำ receiptID ที่ได้ ไปค้นหาในตารางผ่อนชำระค่าอื่นๆว่ามีหรือไม่ ถ้ามีแสดงว่าใบเสร็จนั้นเป็นของค่าใช้จ่ายอื่นๆ  --ใช้ไม่ได้แล้ว เนื่องจากเงินพักกับเงินค้ำ มี \"debtID\" เป็นค่าว่างเหมือนค่างวด
				//$qrychkrec=pg_query("select \"receiptID\" from thcap_v_receipt_otherpay where \"receiptID\"='$receiptID' and \"debtID\" is not null group by \"receiptID\"");				
				//$numchkrec=pg_num_rows($qrychkrec); //if > 0 แสดงว่าเป็นค่าใช้จ่ายอื่นๆ
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<?php
				/*if($typePayID==$typeID){ //ถ้าเท่ากันแสดงว่าเป็นใบเสร็จค่างวด
					//ตรวจสอบอีกครั้งว่าเป็นค่างวดที่อยู่ในตาราง 201201 หรือไม่ 
					$qrychkreceiptID=pg_query("select * from thcap_temp_int_201201 where \"receiptID\"='$receiptID'");
					$numchkreceiptID=pg_num_rows($qrychkreceiptID);
					if($numchkreceiptID==0){ //แสดงว่าเป็นค่างวดที่แสดงใบเสร็จแบบค่าอื่นๆ
						echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=2&contractID=$contractID\" target=\"_blank\"><u>$receiptID</u></a></td>"; // typepdf=2 หมายถึงค่าอื่นๆ					
					}else{
						echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=1&contractID=$contractID\" target=\"_blank\"><u>$receiptID</u></a></td>"; // typepdf=1 หมายถึงค่างวด	
					}
					
				}else{
					echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=2&contractID=$contractID\" target=\"_blank\"><u>$receiptID</u></a></td>"; // typepdf=2 หมายถึงค่าอื่นๆ
				}	*/
				?>
				<td align="center" style="color:#0000FF;"><span onclick="javascript:popU('Channel_detail.php?receiptID=<?php echo $receiptID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $receiptID; ?></u></span></td>
				<td align="left"><?php echo $receiveDate; ?></td>
				<td><?php echo $receiveAmount; ?></td>
				<td><?php echo $fullname; ?></td>
				<td><?php echo $requestDate; ?></td>			
				<td><?php echo $txtchannel; ?></td>
				<td><?php echo $byChannelshow; ?></td>
				<td>
				<?php
				if($typePayID==$typeID)
				{
				?>
					<span onclick="javascript:popU('ReceiptCancelConfirm.php?contractID=<?php echo $contractID?>&receiptID=<?php echo $receiptID?>&cancelID=<?php echo $cancelID;?>&statusshow=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor:pointer;"><u>ตรวจสอบ</u></span>
				<?php
				}
				else 
				{
				?>
					<span onclick="javascript:popU('ReceiptOtherCancelConfirm.php?contractID=<?php echo $contractID?>&receiptID=<?php echo $receiptID?>&cancelID=<?php echo $cancelID;?>&statusshow=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor:pointer;"><u>ตรวจสอบ</u></span>
				<?php
				}
				?>
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>
<?php
include("frm_appvcancel_history_limit.php");
include("frm_autocancel_history_limit.php");
?>
</body>
</html>