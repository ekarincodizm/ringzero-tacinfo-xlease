<?php
include("../../config/config.php");
$vrevTranID = pg_escape_string($_GET['revTranID']);
if($vrevTranID == ""){ echo "<center><h1> ไม่พบรหัสเงินโอน </h1><br><input type=\"button\" value=\" ปิด \" onclick=\"window.close();\" ></center>"; exit(); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>รายละเอียดการใช้เงินโอน</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<div style="margin-top:1px" ></div>
<body>
<table width="80%" border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td  align="center" height="25px">
				<h1><b>รายละเอียดการใช้เงินโอน</b><h1>
			</td>
		</tr>
</table>
<?php
// หาข้อมูลของเงินโอนที่ใช้ ว่าใครใช้ ใช้กับใบเสร็จไหนบ้าง
	$qry_trandetail = pg_query("SELECT \"contractID\",\"bankRevAmt\",\"balanceAmt\",\"namestatus\"	FROM finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\" = '$vrevTranID' AND \"revTranStatus\" in('3','6') ");
	list($vcontractID,$vbankRevAmt,$vbalanceAmt,$namestatus) = pg_fetch_array($qry_trandetail);
	$row_trandetail = pg_num_rows($qry_trandetail);
	if($row_trandetail == ""){ echo "<center><h1> !- ไม่พบข้อมูลการใช้เงินโอน  -!</h1><br><input type=\"button\" value=\" ปิด \" onclick=\"window.close();\" ></center>"; exit(); }
	
	//หาชื่อลูกค้า
	$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$vcontractID'");
	list($vthcap_fullname) = pg_fetch_array($qry_cusname);

?>
<table width="80%" border="0" cellspacing="0" cellpadding="0"  align="center">
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td align="left">เลขที่สัญญา: <font color="red"><a onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $vcontractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800');" style="cursor:pointer;"  ><u><?php echo $vcontractID; ?></u></a></font></td>
					<td align="right">รหัสเงินโอน: <?php echo $vrevTranID; ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td  align="center" height="25px">
			<fieldset style="background-color:#E0EEE0"><legend>ข้อมูลเงินโอน (<?php echo "<font color=\"blue\">$namestatus</font>"; ?>)</legend>
					<table width="80%" border="0" cellspacing="0" cellpadding="0"  align="center">
						<tr>
							<td>ชื่อลูกค้า: <?php echo $vthcap_fullname; ?></td>
							<td></td>
						</tr>
						<tr>
							<td>ยอดเงินโอน: <?php echo number_format($vbankRevAmt,2); ?> บาท</td>
							<td>ยอดคงเหลือ: <?php echo number_format($vbalanceAmt,2); ?> บาท</td>
						</tr>
					</table>			
			</fieldset>
		</td>
	</tr>
</table>
<?php 
//หารายการใบเสร็จที่ใช้เงินโอนนี้
$z = 0;  //ตัวนับข้อมูลใบเสร็จ
$qry_tran_receipt = pg_query("SELECT a.\"receiptID\",b.\"userFullname\",b.\"doerStamp\" FROM finance.\"V_thcap_tranfer_receiptID\" a LEFT JOIN \"thcap_temp_receipt_details\" b on a.\"receiptID\" = b.\"receiptID\" where a.\"revTranID\" = '$vrevTranID'");
$row_tran_receipt = pg_num_rows($qry_tran_receipt);
if($row_tran_receipt > 0){
while($result_tran_receipt = pg_fetch_array($qry_tran_receipt)){ 
	$vreceiptID = $result_tran_receipt['receiptID'];  //รหัสใบเสร็จ 
	$vuserfullname = $result_tran_receipt['userFullname']; //ชื่อผู้ทำรายการใช้เงินโอนในใบเสร็จนี้ / ผู้ตัดใบเสร็จ
	
	$i = 0; //ตัวนับข้อมูลรายการในใบเสร็จ
	$sumdebt = 0; //ผลรวมของการชำระ
	$sumwhtAmt = 0; //ผลรวม ภาษีหัก ณ ที่จ่าย
	$z++; //นับจำนวนใบเสร็จเพื่อไล่สีพื้นหลัง
	
		$qry_reciptdate = pg_query("SELECT \"doerStamp\" FROM thcap_temp_receipt_details where \"receiptID\" = '$vreceiptID' ");
		list($dorestamp)=pg_fetch_array($qry_reciptdate);// วันเวลาที่ออกใบเสร็จ
		
		$qry_reciptondate = pg_query("SELECT \"receiveDate\" FROM thcap_v_receipt_otherpay where \"receiptID\" = '$vreceiptID' ");
		list($vreceiveDate)=pg_fetch_array($qry_reciptondate);// วันเวลาบนใบเสร็จ
		
			if($z%2==0){
				$bgcolor="#E0EEEE";
			}else{
				$bgcolor="#F0FFFF";
			}
?>
	<table width="80%" frame="box" cellspacing="0" cellpadding="0"  align="center" bgcolor="<?php echo $bgcolor ?>">	
		<tr>
			<td width="30%" valign="top"   >
				<table width="99%"  cellspacing="0" cellpadding="1" style="margin-top:1px" align="center">
							<tr>
								<td>รหัสใบเสร็จ:<br> &nbsp&nbsp&nbsp&nbsp- <font color="#0000FF"><a onclick="javascript:popU('../thcap/Channel_detail.php?receiptID=<?php echo $vreceiptID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450');" style="cursor:pointer;"  ><u><?php echo $vreceiptID; ?></u></a></font></td>
							</tr>	
							<tr>
								<td>ผู้ตัดใบเสร็จ:<br> &nbsp&nbsp&nbsp&nbsp- <?php echo $vuserfullname ?></td>
							</tr>
							<tr>
								<td>วัน/เวลาบนใบเสร็จ:<br> &nbsp&nbsp&nbsp&nbsp- <?php echo $vreceiveDate ?></td>
							</tr>
							<tr>
								<td>วัน/เวลาที่ออกใบเสร็จ:<br> &nbsp&nbsp&nbsp&nbsp- <?php echo $dorestamp ?></td>
							</tr>							
				</table>	
			</td>
			<td width="70%">
				<table width="100%" frame="box" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					<tr><td colspan="5" align="center" bgcolor="#528B8B" height="15px" ><font color="white"><b>--- รายการในใบเสร็จ ---</b></font></td></tr>
					<tr bgcolor="#9FB6CD">
						<th width="18%">รายการที่</th>
						<th width="50%">รายละเอียด</th>
						<th>จำนวนเงิน</th>
					</tr>
						<?php
							//หารายการในใบเสร็จว่าจ่ายค่าอะไรบ้าง และจำนวนเงินเท่าไหร่
								$qry_receiptdetail = pg_query("SELECT * FROM thcap_temp_receipt_otherpay where \"receiptID\" = '$vreceiptID'");
								$row_receiptdetail = pg_num_rows($qry_receiptdetail);
								if($row_receiptdetail > 0){
									while($result_receiptdetail = pg_fetch_array($qry_receiptdetail)){
										$i++;
										$txtdetail = $result_receiptdetail["tpDesc"]." ".$result_receiptdetail["tpFullDesc"]." ".$result_receiptdetail["typePayRefValue"]; //รายละเอียดหนี้
										$vdebtAmt = number_format($result_receiptdetail["debtAmt"],2); // จำนวนที่ชำระ
										$sumwhtAmt += $result_receiptdetail["whtAmt"]; // ผลรวมของ ภาษีหัก ณ ที่จ่าย
										$sumdebt += $result_receiptdetail["debtAmt"] - $result_receiptdetail["whtAmt"]; // ผลรวมของจำนวนที่ชำระ ทั้งหมด
										$allsumdebt += $result_receiptdetail["debtAmt"] - $result_receiptdetail["whtAmt"]; //ผลรวมของจำนวนการชำระทั้งหมดเพื่อสรุปผลสุดท้าย
										
										if($i%2==0){
											echo "<tr bgcolor=#EEE8CD onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE8CD';\" align=center>";
										}else{
											echo "<tr bgcolor=#FFF8DC onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF8DC';\" align=center>";
										}
										
										echo "<td>$i</td>";
										echo "<td>$txtdetail</td>";
										echo "<td align=\"right\">$vdebtAmt</td></tr>";
									
									}
									
									if($sumwhtAmt > 0)
									{
										$i++;
										if($i%2==0){
											echo "<tr bgcolor=#EEE8CD onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE8CD';\" align=center>";
										}else{
											echo "<tr bgcolor=#FFF8DC onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF8DC';\" align=center>";
										}
										echo "<td><font color=\"#FF0000\">$i</font></td>";
										echo "<td><font color=\"#FF0000\">ภาษีหัก ณ ที่จ่าย</font></td>";
										echo "<td align=\"right\"><font color=\"#FF0000\">".number_format($sumwhtAmt,2)."</font></td></tr>";
									}
									
								}else{ echo "<TR><td align=\"center\"> ไม่พบข้อมูลรายการในใบเสร็จ  </td></TR>"; }	
						?>
					<tr bgcolor="#CDC8B1">
						<td align="center">รวม <?php echo $i ?> รายการ</td>
						<td></td>
						<td align="right">รวม <?php echo number_format($sumdebt,2); ?></td>	
					</tr>	
				</table>	
			</td>		
		</tr>
	</table>					
<?php }
}else{
	echo "<center>ไม่พบข้อมูลรายการใบเสร็จ</center>";
} ?>						
	<table width="80%" frame="box" cellspacing="0" cellpadding="0"  align="center" bgcolor="#C1CDCD">	
		<tr>
			<td align="left" width="25%">รวมใบเสร็จ: <?php echo $z ?> ใบเสร็จ </td>
			<td width="40%"></td>
			<td align="right">รวมเงินทั้งหมด: <?php echo number_format($allsumdebt,2); ?></td>
		</tr>				
	</table>	
				
			
</body>
</html>