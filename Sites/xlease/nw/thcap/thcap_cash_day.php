<?php
include("../../config/config.php");
$db1="ta_mortgage_datastore";

$datepicker=$_POST["datepicker"];
if($datepicker==""){
	$datepicker=nowDate();
}
$condate=$_POST["condate"];
if($condate=="1"){
	$conditiondate="date(a.\"doerStamp\")='$datepicker'";
}else{
	$conditiondate="date(b.\"receiveDate\")='$datepicker'";
}
$channel=$_POST["channel"];
if($channel==""){
	$conditionchannel="";
}else{
	$conditionchannel="and b.\"byChannel\"='$channel'";
}
$val=$_POST["val"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
    
</head>
<body id="mm">
<form method="post" name="form1" action="#">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center"><h2>(THCAP) รายงานรับชำระประจำวัน</h2></div>       
			<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>รายงานรับชำระประจำวัน</B></legend>
				<div align="center">
					<div class="ui-widget">
						<p align="center">
							<b>รายงานตาม</b>
							<select name="condate">
								<option value="1" <?php if($condate=="1") echo "selected";?>>วันที่ทำรายการ</option>
								<option value="2" <?php if($condate=="2") echo "selected";?>>วันที่รับชำระ</option>
							</select>
							<label><b>วันที่</b></label>
							<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">
							<b>ช่องทางการชำระ</b>
							<select name="channel">
								<option value="" <?php if($channel=="") echo "selected";?>>ทั้งหมด</option>
								<?php
									//ดึงข้อมูลจากฐานข้อมูล
									$qrychannel=pg_query("select \"BID\",\"BAccount\",\"BName\" from \"BankInt\" where \"BCompany\"='THCAP' and \"isChannel\"='1' order by \"BID\"");
									while($reschn=pg_fetch_array($qrychannel)){
										list($BID,$BAccount,$BName)=$reschn;
										?>
										<option value="<?php echo $BID;?>" <?php if($channel==$BID) echo "selected";?>><?php echo "$BAccount-$BName";?></option>
										<?php
									}
								?>
							</select>
							<input type="hidden" name="val" value="1"/>
							<input type="submit" id="btn00" value="เริ่มค้น"/>
						</p>
						<?php
						if($val=="1"){
							$qryreceipt=pg_query("select b.\"receiptID\",b.\"receiveDate\",a.\"doerStamp\",b.\"contractID\",c.\"ChannelAmt\" as \"receiveAmount\", 
							c.\"byChannel\",a.\"doerID\",a.\"cusFullname\" from thcap_v_receipt_details a
							left join \"thcap_temp_int_201201\" b on a.\"receiptID\"=b.\"receiptID\"
							left join \"thcap_temp_receipt_channel\" c on a.\"receiptID\"=c.\"receiptID\" 
							where $conditiondate $conditionchannel and b.\"contractID\" is not null order by a.\"doerID\",a.\"receiptID\",c.\"byChannel\"");
							$numreceipt=pg_num_rows($qryreceipt);						
						?>
						<div>
						<div align="right"><a href="thcap_cash_day_pdf.php?datepicker=<?php echo "$datepicker"; ?>&condate=<?php echo "$condate"; ?>&channel=<?php echo "$channel"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานทั้งหมด)</span></a></div>
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
						<thead>
						<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
							<th>เลขที่ใบเสร็จ</th>
							<th>วันที่รับชำระ</th>
							<th>วันที่ทำรายการ</th>
							<th>เลขที่สัญญา</th>
							<th>ชื่อ-นามสกุลลูกค้า</th>
							<th>จำนวนเงินใบเสร็จ</th>
							<th>ช่องทาง</th>
						</tr>
						</thead>
						<?php
						$i=0;
						$sum_amt = 0;
						$sum_all = 0;
						$old_doerID="";
						$old_receiptID="";
						$nub=0;
						$chk=0; //สำหรับตรวจสอบว่าแต่ละใบเสร็จมีกี่รายการ
						while($result=pg_fetch_array($qryreceipt)){
							$nub+=1;
							$doerID=$result["doerID"];
							$contractID=$result["contractID"];
							$receiptID=$result["receiptID"];
							$receiveDate=$result["receiveDate"];
							$doerStamp=$result["doerStamp"]; if($doerStamp=="") $doerStamp="-";
							$receiveAmount=$result["receiveAmount"];
							$cusname=$result["cusFullname"];
											
							//ถ้า cusfullname เป็นค่าว่างให้ไปค้นหาชื่อจาก mysql มีโอกาสพบค่าว่างได้เนื่องจากเลขที่ใบเสร็จเก่าอาจยังไม่ได้เก็บชื่อลูกค้าทำให้ไม่พบข้อมูลใน pg
							if($cusname==""){
								$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
								$resname=pg_fetch_array($qryname);
								$cusname=$resname["thcap_fullname"];
							}
							$byChannel=$result["byChannel"];
							
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
								}
							}
							
							//ถ้าเลขใบเสร็จไม่เหมือนกัน ให้แสดงรวมเงินในบรรทัดสุดท้าย
							if(($receiptID != $old_receiptID) && $nub != 1){
								if($i%2==0){
									$color2="class=\"even\"";
								}else{
									$color2="class=\"odd\"";
								}
								
								//กรณีรวมเงินแต่ละเลขที่ใบเสร็จ
								if($chk>0){
									echo "<tr>
									<td colspan=5 align=right class=\"sum\" style=\"background-color:#DFFFDF\">รวมเงินในใบเสร็จ</td><td align=right class=\"sum\" style=\"background-color:#DFFFDF\">".number_format($sum_amt,2)."</td>
									<td align=right style=\"background-color:#DFFFDF\"></td></tr>";
								}
								$sum_amt = 0;
								$chk=0;
							}
							
							if(($doerID != $old_doerID) && $nub != 1){
								echo "<tr><td class=\"sum\" align=\"center\"><a href=\"thcap_cash_day_user_pdf.php?datepicker=$datepicker&condate=$condate&channel=$channel&doerID=$old_doerID\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
								<td colspan=4 class=\"sum\" align=right><b>รวมเงินทุกใบเสร็จ</b></td><td align=right class=\"sum\"><b>".number_format($sum_all,2)."</b>
								<td class=\"sum\" align=right></td></tr>";
								$sum_all = 0;
							}
							
							if($doerID != $old_doerID){
								$query1=pg_query("select * from \"Vfuser\" WHERE \"username\"='$doerID'");
								if($resvc1=pg_fetch_array($query1)){
									$fullname = $resvc1['fullname'];
									$id_user = $resvc1['id_user'];
								}
								$sum_amt = 0;
								echo "<tr><td colspan=7><b>ผู้รับเงิน : $fullname ($id_user)</b></td></tr>";
							}
							
							if($receiptID!=$old_receiptID){
								$i+=1;
							}else{
								$chk++;				
							}
							
							if($i%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							
							 if($receiptID==$old_receiptID){
								echo "
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td align=left></td>
									<td align=right>".number_format($receiveAmount,2)."</td>
									<td align=\"left\">$txtchannel</td>
									</tr>
								";
							}else{
								echo "
									<td>$receiptID</td>
									<td>$receiveDate</td>
									<td>$doerStamp</td>
									<td>$contractID</td>
									<td align=left>$cusname</td>
									<td align=right>".number_format($receiveAmount,2)."</td>
									<td align=\"left\">$txtchannel</td>
									</tr>
								";
							}
							
							$old_receiptID=$receiptID;
							$old_doerID=$doerID;
							$sum_amt+=$receiveAmount;
							$sum_all+=$receiveAmount;
							$sum_alltotal+=$receiveAmount;
						}
						if($numreceipt==0){
							echo "<tr><td colspan=7 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
						}else{	
							if($chk>0){
								echo "<tr>
								<td colspan=5 align=right class=\"sum\" style=\"background-color:#DFFFDF\">รวมเงินในใบเสร็จ</td><td align=right class=\"sum\" style=\"background-color:#DFFFDF\">".number_format($sum_amt,2)."</td>
								<td align=right style=\"background-color:#DFFFDF\"></td></tr>";
							}
							
							echo "<tr><td class=\"sum\" align=\"center\"><a href=\"thcap_cash_day_user_pdf.php?datepicker=$datepicker&condate=$condate&channel=$channel&doerID=$old_doerID\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
							<td colspan=4 class=\"sum\" align=right><b>รวมเงินทุกใบเสร็จ</b></td><td align=right class=\"sum\"><b>".number_format($sum_all,2)."</b>
							<td class=\"sum\" align=right></td></tr>";
							
							echo "<tr>
							<td colspan=5 class=\"sumall\" align=right><b>รวมเงินทั้งหมด</b></td><td align=right class=\"sumall\"><b>".number_format($sum_alltotal,2)."</b>
							<td class=\"sumall\" align=right></td></tr>";
						}
						?>
						</table>
						<?php
						}
						?>
					</div>
				</div>
			</fieldset>
			
			<?php
			if($val=="1"){
				$conditiondate2=substr($conditiondate,0,19);
				if($conditiondate2=="date(a.\"doerStamp\")"){
					$conditiondate="date(a.\"approveDate\")='$datepicker'";
				}
				
				$qryreceiptcancel=pg_query("select b.\"receiptID\",b.\"receiveDate\",a.\"approveDate\",b.\"contractID\",d.\"cusFullname\",b.\"receiveAmount\",b.\"byChannel\",d.\"doerID\" as doerid,a.\"cancelID\"
					from thcap_temp_cancel_int b
					inner join thcap_temp_receipt_cancel a on b.\"receiptID\"=a.\"receiptID\"
					left join \"thcap_v_receipt_details\" d on a.\"receiptID\"=d.\"receiptID\"
					where $conditiondate $conditionchannel and \"approveStatus\"='1'  order by doerid,b.\"receiptID\""); 
				
				$numreceiptcancel=pg_num_rows($qryreceiptcancel);
				
			?>
				<br>
				<div style="text-align:left"><h2>ใบเสร็จที่ถูกยกเลิก</h2></div>
				<div align="right"><a href=" thcap_cash_day_cancel_pdf.php?datepicker=<?php echo "$datepicker"; ?>&condate=<?php echo "$condate"; ?>&channel=<?php echo "$channel"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานใบเสร็จที่ถูกยกเลิก)</span></a></div>
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#DFDFDF">
				<tr style="font-weight:bold;" valign="top" bgcolor="#CCCCCC" align="center">
					<th>เลขที่ใบเสร็จ</th>
					<th>วันที่รับชำระ</th>
					<th>วันที่ทำรายการ</th>
					<th>เลขที่สัญญา</th>
					<th>ชื่อ-นามสกุลลูกค้า</th>
					<th>จำนวนเงินใบเสร็จ</th>
					<th>ช่องทาง</th>
					<th>รายละเอียด</th>
				</tr>
				<?php	
				$i=0;
				$sum_all2 = 0;
				while($resultcancel=pg_fetch_array($qryreceiptcancel)){
					$nub+=1;
					$doerID2=$resultcancel["doerID"];
					$cancelID2=$resultcancel["cancelID"];
					$contractID2=$resultcancel["contractID"];
					$receiptID2=$resultcancel["receiptID"];
					$receiveDate2=$resultcancel["receiveDate"];
					$doerStamp2=$resultcancel["approveDate"]; if($approveDate=="") $approveDate="-";
					$receiveAmount2=$resultcancel["receiveAmount"];
					$cusname2=$resultcancel["cusFullname"];
					
					//ถ้า cusfullname เป็นค่าว่างให้ไปค้นหาชื่อจาก mysql มีโอกาสพบค่าว่างได้เนื่องจากเลขที่ใบเสร็จเก่าอาจยังไม่ได้เก็บชื่อลูกค้าทำให้ไม่พบข้อมูลใน pg
					if($cusname2==""){
						$qryname2 = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID2' and \"CusState\"='0'");
						$resname2=pg_fetch_array($qryname2);
						$cusname2=$resname2["thcap_fullname"];
					}
	
					$byChannel2=$resultcancel["byChannel"];
					
					if($byChannel2=="" || $byChannel2=="0" || $byChannel2=="999"){$txtchannel2="ไม่ระบุ";}
					else{
						//นำไปค้นหาในตาราง BankInt
						$qrysearch2=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel2'");
						$ressearch2=pg_fetch_array($qrysearch2);
						list($BAccount,$BName)=$ressearch2;
						$txtchannel2="$BAccount-$BName";
					}
							
					$i+=1;
					if($i%2==0){
						echo "<tr bgcolor=\"#F0F0F0\" align=\"center\">";
					}else{
						echo "<tr bgcolor=\"#E8E8E8\" align=\"center\">";
					}
							
					echo "
						<td>$receiptID2</td>
						<td>$receiveDate2</td>
						<td>$doerStamp2</td>
						<td>$contractID2</td>
						<td align=\"left\">$cusname2</td>
						<td align=right>".number_format($receiveAmount2,2)."</td>
						<td align=\"left\">$txtchannel2</td>
						<td><img src=\"images/detail.gif\" width=\"19\" height=\"19\" style=\"cursor:pointer\" onclick=\"javascript:popU('ReceiptCancelDetail.php?cancelID=$cancelID2&doerID=$doerID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')\"></td>
						</tr>
					";
					$sum_all2+=$receiveAmount2;
				}
				if($numreceiptcancel==0){
					echo "<tr><td colspan=8 bgcolor=\"#E8E8E8\" align=center height=50><b>-ไม่พบรายการที่ถูกยกเลิก-</b></td></tr>";
				}else{	
					echo "<tr>
					<td colspan=5 bgcolor=\"#CCCCCC\" align=right><b>รวมเงินทั้งหมด</b></td><td align=right bgcolor=\"#CCCCCC\"><b>".number_format($sum_all2,2)."</b>
					<td colspan=2 bgcolor=\"#CCCCCC\" align=right></td></tr>";
				}
				?>
				</table>
			</div>
			<?php
			}
			?>
        </td>
    </tr>
</table>
</form>
</body>
</html>