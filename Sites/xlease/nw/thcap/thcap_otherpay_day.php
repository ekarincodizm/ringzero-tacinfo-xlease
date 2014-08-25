<?php
include("../../config/config.php");
$db1="ta_mortgage_datastore";
$datepicker=$_POST["datepicker"];
if($datepicker==""){
	$datepicker=nowDate();
}
$condate=$_POST["condate"];
if($condate=="1"){
	$conditiondate="date(e.\"doerStamp\")='$datepicker'";
}else{
	$conditiondate="date(c.\"receiveDate\")='$datepicker'";
}
$channel=$_POST["channel"];
if($channel==""){
	$conditionchannel="";
}else{
	$conditionchannel="and c.\"byChannel\"='$channel'";
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
			<div style="text-align:center"><h2>(THCAP) รายงานรับชำระหนี้อื่นๆ ประจำวัน</h2></div>       
			<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>รายงานรับชำระหนี้อื่นๆ ประจำวัน</B></legend>
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
							$qryreceipt=pg_query("select a.\"receiptID\",c.\"receiveDate\",e.\"doerStamp\",b.\"contractID\",e.\"doerID\",
							e.\"cusFullname\",concat(d.\"tpDesc\"|| ' ' || d.\"tpFullDesc\" || ' ' || b.\"typePayRefValue\") as detail,
							b.\"typePayID\",b.\"typePayRefValue\",d.\"tpDesc\",
							c.\"ChannelAmt\" as \"debtAmt\",c.\"receiveDate\",c.\"byChannel\" from thcap_v_receipt_otherpay a
							left join thcap_temp_otherpay_debt b on a.\"debtID\"=b.\"debtID\"
							left join thcap_temp_receipt_channel c on a.\"receiptID\"=c.\"receiptID\"
							left join account.\"thcap_typePay\" d on b.\"typePayID\"=d.\"tpID\"
							left join thcap_v_receipt_details e on a.\"receiptID\"=e.\"receiptID\"
							where $conditiondate $conditionchannel and b.\"contractID\" is not null  order by e.\"doerID\",a.\"receiptID\",a.\"debtID\",c.\"byChannel\"
							");
							//and c.\"byChannel\" <> '999'
							$numreceipt=pg_num_rows($qryreceipt);
						?>
						<div>
						<div align="right"><a href="thcap_other_day_pdf.php?datepicker=<?php echo "$datepicker"; ?>&condate=<?php echo "$condate"; ?>&channel=<?php echo "$channel"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานทั้งหมด)</span></a></div>
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
						<thead>
						<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
							<th>เลขที่ใบเสร็จ</th>
							<th>วันที่รับชำระ</th>
							<th>วันที่ทำรายการ</th>
							<th>เลขที่สัญญา</th>
							<th>ชื่อ-นามสกุลลูกค้า</th>
							<th width="160">รายละเอียดการรับชำระ</th>
							<th>จำนวนเงินใบเสร็จ</th>
							<th>ช่องทาง</th>
						</tr>
						</thead>
						<?php
						$i=0;
						$sum_amt = 0;
						$sum_all = 0;
						$sum_alltotal=0;
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
							$receiveAmount=$result["debtAmt"];
							$cusname=$result["cusFullname"];
							
							if($cusname==""){
								$qryname = pg_query("select \"thcap_fullname\" from  \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
								$resname=pg_fetch_array($qryname);
								$cusname=$resname["thcap_fullname"];
							}
	
							$byChannel=$result["byChannel"];
							$detail2=$result["detail"];
							list($detail,$detail2)=explode("-",$detail2);
							$typePayID=$result["typePayID"];
							$typePayRef=$result["typePayRefValue"];
							
							list($typePayRefValue,$typePayRef2)=explode("-",$typePayRef);
							$tpDesc=$result["tpDesc"];
							
							if($detail == "") // ถ้าคำนวนรายละเอียดไม่เจอ
							{
								$detail = $tpDesc;
							}
							
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
				
							if($receiptID!=$old_receiptID){
								$i+=1;
							}else{
								$chk++;
							}
							
							if($i%2==0){
								$color="class=\"odd\"";
							}else{
								$color="class=\"even\"";
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
									echo "<tr $color2>
									<td colspan=6 align=right><b>รวมเงินทุกรายการ</b></td><td align=right><b>".number_format($sum_amt,2)."</b>
									<td align=right></td></tr>";
								}
								$sum_amt = 0;
								$chk=0;
							}
							
							if(($doerID != $old_doerID) && $nub != 1){
								echo "<tr><td class=\"sum\" align=\"center\"><a href=\"thcap_other_day_user_pdf.php?datepicker=$datepicker&condate=$condate&channel=$channel&doerID=$old_doerID\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
								<td colspan=5 class=\"sum\" align=right><b>รวมเงินทุกใบเสร็จ</b></td><td align=right class=\"sum\"><b>".number_format($sum_all,2)."</b>
								<td class=\"sum\" align=right></td></tr>";
								$sum_all=0;
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
							
							
							echo "<tr $color align=\"center\" valign=top>";

							if($receiptID==$old_receiptID){
								$receiptID2="";
							}else{
								$receiptID2=$receiptID;
							}
							
							if($typePayID == "1003")
							{
								$qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$typePayRefValue' ");
								while($res_due=pg_fetch_array($qry_due))
								{
									$ptDate=trim($res_due["ptDate"]); // วันดิว
									$due = "($ptDate)";
								}
							}
							else
							{
								$due = "";
							}
							
							 if($receiptID==$old_receiptID){
								if($old_typePayID==$typePayID and $old_detail==$detail and $old_due==$due){			
									$typetype="";
								}else{
									$typetype="$typePayID - $detail $due";
								}
								
								echo "
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td align=left></td>
									<td align=left>$typetype</td>
									<td align=right>".number_format($receiveAmount,2)."</td>
									<td>$txtchannel</td>
									</tr>
								";
							 }else{
								echo "
									<td>$receiptID2</td>
									<td>$receiveDate</td>
									<td>$doerStamp</td>
									<td>$contractID</td>
									<td align=left>$cusname</td>
									<td align=left>$typePayID - $detail $due</td>
									<td align=right>".number_format($receiveAmount,2)."</td>
									<td>$txtchannel</td>
									</tr>
								";
							}
							$old_typePayID=$typePayID;
							$old_detail=$detail;
							$old_due=$due;
							
							$old_doerID=$doerID;
							$old_receiptID=$receiptID;
							$sum_amt+=$receiveAmount;
							$sum_all+=$receiveAmount;
							$sum_alltotal+=$receiveAmount;
						}
						if($numreceipt==0){
							echo "<tr><td colspan=8 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
						}else{	
							if($chk>0){
								echo "<tr $color>
								<td colspan=6 align=right><b>รวมเงินทุกรายการ</b></td><td align=right ><b>".number_format($sum_amt,2)."</b></td>
								<td align=right></td></tr>";
							}
							
							echo "<tr><td class=\"sum\" align=\"center\"><a href=\"thcap_other_day_user_pdf.php?datepicker=$datepicker&condate=$condate&channel=$channel&doerID=$old_doerID\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
								<td colspan=5 class=\"sum\" align=right><b>รวมเงินทุกใบเสร็จ</b></td><td align=right class=\"sum\"><b>".number_format($sum_all,2)."</b>
								<td class=\"sum\" align=right></td></tr>";
								
							echo "<tr>
							<td colspan=6 class=\"sumall\" align=right><b>รวมเงินทั้งหมด</b></td><td align=right class=\"sumall\"><b>".number_format($sum_alltotal,2)."</b>
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
				if($conditiondate2=="date(e.\"doerStamp\")"){
					$conditiondate="date(b.\"approveDate\")='$datepicker'";
				}
				
				$qryreceiptcancel=pg_query("select 1 as type,a.\"receiptID\",c.\"receiveDate\",b.\"approveDate\",b.\"contractID\",d.\"cusFullname\",
				sum(a.\"debtAmt\") as receiveamount,c.\"byChannel\",null as doerid,null as cancelID
					from thcap_v_receipt_otherpay_cancel a
					left join thcap_temp_receipt_cancel b on a.\"receiptID\"=b.\"receiptID\"
					left join thcap_temp_receipt_channel c on a.\"receiptID\"=c.\"receiptID\"
					left join (		select \"cusFullname\",\"receiptID\"			
									from \"thcap_v_receipt_details_cancel\"
									GROUP BY \"cusFullname\",\"receiptID\"
								) d on a.\"receiptID\"=d.\"receiptID\"
					where $conditiondate $conditionchannel and c.\"byChannel\" <> '999' and a.\"contractID\" is not null and b.\"approveStatus\"='1'
					group by a.\"receiptID\",c.\"receiveDate\",b.\"approveDate\",b.\"contractID\",d.\"cusFullname\",c.\"byChannel\"
					order by a.\"receiptID\""); 
					
				$numreceiptcancel=pg_num_rows($qryreceiptcancel);
			?>
				<br>
				<div style="text-align:left"><h2>ใบเสร็จที่ถูกยกเลิก</h2></div>
				<div align="right"><a href=" thcap_otherpay_day_cancel_pdf.php?datepicker=<?php echo "$datepicker"; ?>&condate=<?php echo "$condate"; ?>&channel=<?php echo "$channel"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานใบเสร็จที่ถูกยกเลิก)</span></a></div>
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
					$receiveAmount2=$resultcancel["receiveamount"];
					$cusname2=$resultcancel["cusFullname"];
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
						<td align=left>$cusname2</td>
						<td align=right>$receiveAmount2</td>
						<td align=left>$txtchannel2</td>
						<td><img src=\"images/detail.gif\" width=\"19\" height=\"19\" style=\"cursor:pointer\" onclick=\"javascript:popU('ReceiptOtherCancelDetail.php?receiptID=$receiptID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=500')\"></td>
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