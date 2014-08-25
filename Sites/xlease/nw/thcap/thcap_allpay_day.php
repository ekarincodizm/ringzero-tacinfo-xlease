<?php
include("../../config/config.php");
include("../function/nameMonth.php");
$db1="ta_mortgage_datastore";

// วันที่
$datepicker = pg_escape_string($_POST["datepicker"]);
if($datepicker==""){
	$datepicker=nowDate();
}

// ช่องทางการชำระ
$channel = pg_escape_string($_POST["channel"]);
if($channel==""){
	$conditionchannel="";
}else{
	$conditionchannel="and a.\"byChannel\"='$channel'";
}

$val = pg_escape_string($_POST["val"]);

// ช่วงเวลา
$selectTime = pg_escape_string($_POST["selectTime"]);
if($selectTime == ""){$selectTime = "d";}

// เดือน
$selectMonth = pg_escape_string($_POST["selectMonth"]);
if($selectMonth == ""){$selectMonth = date('m');}

// ปี ของเดือน
$selectYearMonth = pg_escape_string($_POST["selectYearMonth"]);
if($selectYearMonth == ""){$selectYearMonth = date('Y');}

// ปี
$selectYear = pg_escape_string($_POST["selectYear"]);
if($selectYear == ""){$selectYear = date('Y');}

// รายงานตาม
$condate = pg_escape_string($_POST["condate"]);

if($selectTime == "d")
{
	if($condate=="1"){
		$conditiondate="b.\"doerStamp\"::date = '$datepicker'";
	}else{
		$conditiondate="a.\"receiveDate\"::date = '$datepicker'";
	}
}
elseif($selectTime == "m")
{
	if($condate=="1"){
		$conditiondate="substr(b.\"doerStamp\"::character varying,6,2) = '$selectMonth' and substr(b.\"doerStamp\"::character varying,1,4) = '$selectYearMonth'";
	}else{
		$conditiondate="substr(a.\"receiveDate\"::character varying,6,2) = '$selectMonth' and substr(a.\"receiveDate\"::character varying,1,4) = '$selectYearMonth'";
	}
}
elseif($selectTime == "y")
{
	if($condate=="1"){
		$conditiondate="substr(b.\"doerStamp\"::character varying,1,4) = '$selectYear'";
	}else{
		$conditiondate="substr(a.\"receiveDate\"::character varying,1,4) = '$selectYear'";
	}
}
else
{
	if($condate=="1"){
		$conditiondate="b.\"doerStamp\"::date = '$datepicker'";
	}else{
		$conditiondate="a.\"receiveDate\"::date = '$datepicker'";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ออกรายงานรับชำระรวม</title>
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
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center"><h2>(THCAP) ออกรายงานรับชำระรวม</h2></div>       
			<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>รายงานรับชำระรวม</B></legend>
				<div align="center">
					<div class="ui-widget">
						<p align="center">
							<table>
								<tr>
									<td align="right"><b>รายงานตาม : </b></td>
									<td align="left">
										<select name="condate">
											<option value="1" <?php if($condate=="1") echo "selected";?>>วันที่ทำรายการ</option>
											<option value="2" <?php if($condate=="2") echo "selected";?>>วันที่รับชำระ</option>
										</select>
									</td>
								</tr>
								<tr>
									<td align="right"><b>ช่องทางการชำระ : </b></td>
									<td align="left">
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
									</td>
								</tr>
								<tr>
									<td align="right" valign="top"><label><b>ช่วงเวลา : </b></label></td>
									<td align="left">
										<input type="radio" name="selectTime" value="d" <?php if($selectTime == "d"){echo "checked";} ?>> วันที่ : <input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">
										
										<br>
										
										<input type="radio" name="selectTime" value="m" <?php if($selectTime == "m"){echo "checked";} ?>> เดือน :
										<select name="selectMonth">
											<?php
												for($i=1; $i<=12; $i++)
												{
													$itext = $i;
													if(strlen($itext) == 1)
													{
														$itext = "0".$itext;
													}
													$mtext = nameMonthTH($itext);
													
													if($itext == $selectMonth){$mselect = "selected";}else{$mselect = "";}
													
													echo "<option value=\"$itext\" $mselect>$mtext</option>";
												}
											?>
										</select>
										<select name="selectYearMonth">
											<?php
												$y = date('Y');
												for($i=0; $i<=10; $i++)
												{
													$yshow = $y - $i;
													
													if($yshow == $selectYearMonth){$yselect = "selected";}else{$yselect = "";}
													
													echo "<option value=\"$yshow\" $yselect>$yshow</option>";
												}
											?>
										</select>
										
										<br>
										
										<input type="radio" name="selectTime" value="y" <?php if($selectTime == "y"){echo "checked";} ?>> ปี : 
										<select name="selectYear">
											<?php
												$y = date('Y');
												for($i=0; $i<=10; $i++)
												{
													$yshow = $y - $i;
													
													if($yshow == $selectYear){$yselect = "selected";}else{$yselect = "";}
													
													echo "<option value=\"$yshow\" $yselect>$yshow</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td align="center" colspan="2">
										<input type="hidden" name="val" value="1"/>
										<input type="submit" id="btn00" value="เริ่มค้น"/>
									</td>
								</tr>
							</table>
						</p>
						<!-- เนื่องจากให้นำ * ออก ในส่วนนี้จึงไม่ควรแสดง
						<div align="center">
							<font color="red"><h3><b>* ใบเสร็จที่มีการจ่ายผ่านมากกว่า 1 ช่องทางกรุณาดู ช่องทางทั้งหมด เพื่อรายละเอียดที่ครบถ้วน</b></h3></font>
						</div>
						-->
						<?php
						if($val=="1"){										
							//ยุบรวมกันเนื่องจากเลขที่สัญญาทุกอันถูกเก็บไว้ใน thcap_v_receipt_otherpay แล้ว
							$qryreceipt=pg_query("select a.\"receiptID\" as receiptid,a.\"receiveDate\",b.\"doerStamp\",b.\"contractID\",b.\"doerID\",a.\"debtAmt\"-a.\"whtAmt\" as debtamt,a.\"whtAmt\", b.\"cusFullname\", 
							a.\"tpDesc\"||a.\"tpFullDesc\"||' '||a.\"typePayRefValue\" as detail,
							 a.\"byChannel\",b.\"doerID\" as doerid, a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"debtID\" as debtid,a.\"nameChannel\",a.\"receiptRemark\",\"byChannelRef\"
							 from thcap_v_receipt_otherpay a 
							 left join (
											select \"receiptID\",\"doerStamp\",\"contractID\",\"doerID\",\"cusFullname\",\"receiptRemark\"
											from thcap_v_receipt_details
											group by \"receiptID\",\"doerStamp\",\"contractID\",\"doerID\",\"cusFullname\",\"receiptRemark\"
											
										) b on a.\"receiptID\"=b.\"receiptID\" 
							 where $conditiondate $conditionchannel order by doerid,receiptid,debtid,a.\"byChannel\" ");
							$numreceipt=pg_num_rows($qryreceipt);
						?>
						<div>
						<tr><td>
						<div align="right" style="padding-top:10px;"><a href="thcap_allpayandCancel_day_pdf.php?datepicker=<?php echo "$datepicker"; ?>&condate=<?php echo "$condate"; ?>&channel=<?php echo "$channel"; ?>&selectTime=<?php echo $selectTime; ?>&selectMonth=<?php echo $selectMonth; ?>&selectYearMonth=<?php echo $selectYearMonth; ?>&selectYear=<?php echo $selectYear; ?>&typeprint=all" target="_blank"><img src="images/icoPrint.png" width="17" height="14" border="0" title="พิมพ์รับชำระและยกเลิก">&nbsp;<span style="font-size:15px; color:#0000FF;">(พิมพ์รวมทั้งหมด)</span></a></div>
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
							<th>หมายเหตุ</th>
						</tr>
						</thead>
						<?php
						$i=0;
						$sum_amt = 0;
						$sum_all = 0;
						$sum_alltotal=0;
						$sum_wht=0;
						$sum_all_wht=0;
						$sum_alltotal_wht=0;
						$old_doerID="";
						$old_receiptID="";
						$nub=0;
						$chk=0; //สำหรับตรวจสอบว่าแต่ละใบเสร็จมีกี่รายการ
						
						while($result=pg_fetch_array($qryreceipt)){
							$nub+=1;
							$doerID=$result["doerid"];
							$contractID=$result["contractID"];
							$receiptID=$result["receiptid"];
							$receiveDate=$result["receiveDate"];
							$doerStamp=$result["doerStamp"]; if($doerStamp=="") $doerStamp="-";
							$receiveAmount=$result["debtamt"];
							$whtAmt=$result["whtAmt"];
							$cusname=$result["cusFullname"];
							$receiptRemark=$result["receiptRemark"];							
							$byChannelRef=$result["byChannelRef"];
							
							
							$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID'");
							$rowqryredstar=pg_num_rows($qryredstar);
							if($rowqryredstar > 1){
								$redstar = "<font color=\"red\">*</font>";
							}else{
								$redstar = "";
							}
							
							
							if($cusname==""){
								$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
								$resname= pg_fetch_array($qryname);
								$cusname=$resname["thcap_fullname"];
							}
	
							$byChannel=$result["byChannel"];
							$detail2=$result["detail"];
							
							//หาว่ามี - อยู่กี่ตัว ถ้า 2 ตัวเป็นเลขที่สัญญาให้แสดงเต็ม แต่ถ้ามี 1 ตัวให้ตัดตัวหลังออก
							$c = strlen($detail2);
							$l = 0;
							for ($pp = 0; $pp < $c; ++$pp){
								if ($detail2[$pp]=="-") ++$l;
							}
							
							if($l==2){
								$detail=$detail2;
							}else{
								list($detail,$detail2)=explode("-",$detail2);
							}
							
							$typePayID=$result["typePayID"];
							$typePayRef=$result["typePayRefValue"];
							
							list($typePayRefValue,$typePayRef2)=explode("-",$typePayRef);
							
							$tpDesc=$result["tpDesc"];
							
							if($detail == "") // ถ้าคำนวนรายละเอียดไม่เจอ
							{
								$detail = $tpDesc;
							}
							
							$txtchannel=$result["nameChannel"];
							
							$qry_hold = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
							list($chkhold) = pg_fetch_array($qry_hold);
							
							$qry_secur = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
							list($chksecur) = pg_fetch_array($qry_secur);

							 if($byChannel=="" || $byChannel=="0"){$txtchannel="ไม่ระบุ";}
			
							if($byChannel==$chkhold || $byChannel==$chksecur){
								$txtchannel=$txtchannel." เลขที่ $byChannelRef";
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
								//if($chk>0){
									echo "<tr bgcolor=#DFFFDF>
									<td colspan=6 align=right><b>รวมเงินในใบเสร็จ </b></td><td align=right><b>".number_format($sum_amt,2)."</b>
									<td colspan=2><b>ภาษีหัก ณ ที่จ่าย  ".number_format($sum_wht,2)."</b></td></tr>";
								//}
								$sum_amt = 0;
								$sum_wht = 0;
								$chk=0;
							}
							
							if(($doerID != $old_doerID) && $nub != 1){
								echo "<tr><td class=\"sum\" align=\"center\"><a href=\"thcap_allpayandCancel_day_pdf.php?datepicker=$datepicker&condate=$condate&channel=$channel&doerID=$old_doerID&selectTime=$selectTime&selectMonth=$selectMonth&selectYearMonth=$selectYearMonth&selectYear=$selectYear&typeprint=normal\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
								<td colspan=5 class=\"sum\" align=right><b>รวมเงินทุกใบเสร็จ</b></td><td align=right class=\"sum\"><b>".number_format($sum_all,2)."</b>
								<td class=\"sum\" colspan=2><b>ภาษีหัก ณ ที่จ่าย ".number_format($sum_all_wht,2)."</b></td></tr>";
								$sum_all=0;
								$sum_all_wht =0;
							}
							
							if($doerID != $old_doerID){
								$query1=pg_query("select * from \"Vfuser\" WHERE \"username\"='$doerID'");
								if($resvc1=pg_fetch_array($query1)){
									$fullname = $resvc1['fullname'];
									$id_user = $resvc1['id_user'];
								}
								$sum_amt = 0;
								$sum_wht = 0;
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
								$qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"::text='$typePayRefValue' ");
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
									<td onclick=\"javascript : popU('Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');\" style=\"cursor:pointer;\"><u>$txtchannel</u></td>
									<td>";
									if($receiptRemark!="" and $receiptRemark!="-" and $receiptRemark!="--"){
										echo"<img src=\"images/open.png\" width=\"16\" height=\"16\" onclick=\"javascript : popU('allpay_result.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350');\" style=\"cursor:pointer;\" title=\"หมายเหตุ\">";
									}
									echo"</td></tr>";																							
							}else{
								echo "
									<td><span onclick=\"javascript : popU('Channel_detail.php?receiptID=$receiptID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');\" style=\"cursor:pointer;\"><u>$receiptID2</u></span></td>
									<td>$receiveDate</td>
									<td>$doerStamp</td>
									<td onclick=\"javascript : popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=750');\" style=\"cursor:pointer;\"><u>$contractID</u></td>
									<td align=left>$cusname</td>
									<td align=left>$typePayID - $detail $due</td>
									<td align=right>".number_format($receiveAmount,2)."</td>
									<td onclick=\"javascript : popU('Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');\" style=\"cursor:pointer;\"><u>$txtchannel</u></td>
									<td>";
									if($receiptRemark!="" and $receiptRemark!="-" and $receiptRemark!="--"){
										echo"<img src=\"images/open.png\" width=\"16\" height=\"16\" onclick=\"javascript : popU('allpay_result.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350');\" style=\"cursor:pointer;\" title=\"หมายเหตุ\">";
									}
									echo"</td></tr>";							
							}
							
							
							$old_typePayID=$typePayID;
							$old_detail=$detail;
							$old_due=$due;
							
							$old_doerID=$doerID;
							$old_receiptID=$receiptID;
							$sum_amt+=$receiveAmount;
							$sum_wht+=$whtAmt;
							$sum_all+=$receiveAmount;
							$sum_all_wht+=$whtAmt;
							$sum_alltotal+=$receiveAmount;
							$sum_alltotal_wht+=$whtAmt;
							$typePayID2=$typePayID;
						}
						if($numreceipt==0){
							echo "<tr><td colspan=9 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
						}else{	
							//if($chk>0){
								echo "<tr bgcolor=#DFFFDF>
								<td colspan=6 align=right><b>รวมเงินในใบเสร็จ</b></td><td align=right ><b>".number_format($sum_amt,2)."</b>
								<td colspan=\"2\"><b>ภาษีหัก ณ ที่จ่าย  ".number_format($sum_wht,2)."</b></td></tr>";
							//}
							
							echo "<tr><td class=\"sum\" align=\"center\"><a href=\"thcap_allpayandCancel_day_pdf.php?datepicker=$datepicker&condate=$condate&channel=$channel&selectTime=$selectTime&selectMonth=$selectMonth&selectYearMonth=$selectYearMonth&selectYear=$selectYear&doerID=$old_doerID&typeprint=normal\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
								<td colspan=5 class=\"sum\" align=right><b>รวมเงินทุกใบเสร็จ</b></td><td align=right class=\"sum\"><b>".number_format($sum_all,2)."</b>
								<td class=\"sum\" colspan=\"2\"><b>ภาษีหัก ณ ที่จ่าย ".number_format($sum_all_wht,2)."</td></tr>";
								
							echo "<tr>
							<td colspan=6 class=\"sumall\" align=right><b>รวมเงินทั้งหมด</b></td><td align=right class=\"sumall\"><b>".number_format($sum_alltotal,2)."</b>
							<td class=\"sumall\" colspan=\"2\"><b>ภาษีหัก ณ ที่จ่าย ".number_format($sum_alltotal_wht,2)."</b></td></tr>";
						}
						?>
						</table>
						<div align="left" style="background-color:#FFFF99;width:200px;"><b><img src="images/open.png" width="16" height="16"> หมายถึง ใบเสร็จที่มีหมายเหตุ<font color=red>*</font></b></div>
						<div align="right"><a href="thcap_allpayandCancel_day_pdf.php?datepicker=<?php echo "$datepicker"; ?>&condate=<?php echo "$condate"; ?>&channel=<?php echo "$channel"; ?>&selectTime=<?php echo $selectTime; ?>&selectMonth=<?php echo $selectMonth; ?>&selectYearMonth=<?php echo $selectYearMonth; ?>&selectYear=<?php echo $selectYear; ?>&typeprint=normal" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานรับชำระทั้งหมด)</span></a></div>
						<?php
						}
						?>
					</div>
				</div>
			</fieldset>
			
			<?php
			
			if($val=="1"){
				$conditiondate2=substr($conditiondate,0,19);
				
				if($conditiondate2=="date(b.\"doerStamp\")"){
					$conditiondate="date(c.\"approveDate\")='$datepicker'";
				}
				
				$qryreceiptcancel=pg_query("select a.\"receiptID\" as receiptid,a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\", d.\"ChannelAmt\" as receiveamount,a.\"byChannel\",b.\"doerID\" as doerid 
				from thcap_v_receipt_otherpay_cancel a 
				left join thcap_v_receipt_details_cancel b on a.\"receiptID\"=b.\"receiptID\" 
				left join thcap_temp_receipt_cancel c on a.\"receiptID\"=c.\"receiptID\" 
				left join thcap_temp_receipt_channel d on a.\"receiptID\"=d.\"receiptID\"
				where $conditiondate $conditionchannel and c.\"approveStatus\"='1' and d.\"byChannel\"<>'999'
				group by a.\"receiptID\",a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\",d.\"ChannelAmt\",a.\"byChannel\",b.\"doerID\" order by doerid,receiptid");
				
				$numreceiptcancel=pg_num_rows($qryreceiptcancel);
			?>
				<br>
				<div style="text-align:left"><h2>ใบเสร็จที่ถูกยกเลิก</h2></div>
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
					$doerID2=$resultcancel["doerid"];
					$contractID2=$resultcancel["contractID"];
					$receiptID2=$resultcancel["receiptid"];
					$receiveDate2=$resultcancel["receiveDate"];
					$doerStamp2=$resultcancel["approveDate"]; if($approveDate=="") $approveDate="-";
					$receiveAmount2=$resultcancel["receiveamount"];
					$cusname2=$resultcancel["cusFullname"];
					$type=$resultcancel["type"];
					$byChannel2=$resultcancel["byChannel"];
					$byChannelRef2=$resultcancel["byChannelRef"];
					
					
					//หา ID รายการที่ยกเลิก
					$qrycancelid=pg_query("SELECT min(\"cancelID\") as cancelid
					FROM thcap_temp_receipt_cancel where \"receiptID\"='$receiptID2' and \"approveStatus\"='1' group by \"receiptID\"");
					list($cancelID2)=pg_fetch_array($qrycancelid);
					
					$qryredstar1 = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID2'");
					$rowqryredstar1=pg_num_rows($qryredstar1);
						if($rowqryredstar1 > 1){
							$redstar1 = "<font color=\"red\">*</font>";
						}else{
							$redstar1 = "";
						}
					
					
					//หาชื่อลูกหนี้
					if($cusname2==""){
					$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID2' and \"CusState\"='0'");
						list($cusname2)= pg_fetch_array($qryname);
					}
					if($byChannel2=="" || $byChannel2=="0" || $byChannel2=="999"){$txtchannel2="ไม่ระบุ";}
					else{
						//นำไปค้นหาในตาราง BankInt
						$qrysearch2=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel2'");
						$ressearch2=pg_fetch_array($qrysearch2);
						list($BAccount,$BName)=$ressearch2;
						$txtchannel2="$BAccount-$BName";
					}
					
					$qry_hold2 = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
					list($chkhold2) = pg_fetch_array($qry_hold2);
							
					$qry_secur2 = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
					list($chksecur2) = pg_fetch_array($qry_secur2);
					
					if($byChannel2==$chkhold2 || $byChannel2==$chksecur2){
						$txtchannel2=$txtchannel2." เลขที่ $byChannelRef2";
					}
							
					$i+=1;
					if($i%2==0){
						echo "<tr bgcolor=\"#F0F0F0\" align=\"center\">";
					}else{
						echo "<tr bgcolor=\"#E8E8E8\" align=\"center\">";
					}
							
					echo "
						<td><span onclick=\"javascript : popU('Channel_detail.php?receiptID=$receiptID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');\" style=\"cursor:pointer;\"><u>$receiptID2</u></span>$redstar1</td>
						<td>$receiveDate2</td>
						<td>$doerStamp2</td>
						<td onclick=\"javascript : popU('../thcap_installments/frm_Index.php?idno=$contractID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=750');\" style=\"cursor:pointer;\"><u>$contractID2</u></td>
						<td align=left>$cusname2</td>
						<td align=right>".number_format($receiveAmount2,2)."</td>
						<td align=left>$txtchannel2</td>";
					if($type=="1"){
						echo "<td><img src=\"images/detail.gif\" width=\"19\" height=\"19\" style=\"cursor:pointer\" onclick=\"javascript:popU('ReceiptOtherCancelDetail.php?receiptID=$receiptID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=500')\"></td>";
					}else{
						echo "<td><img src=\"images/detail.gif\" width=\"19\" height=\"19\" style=\"cursor:pointer\" onclick=\"javascript:popU('ReceiptCancelDetail.php?cancelID=$cancelID2&doerID=$doerID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=500')\"></td>";
					}
					echo "</tr>";
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
				<div align="right"><a href="thcap_allpayandCancel_day_pdf.php?datepicker=<?php echo "$datepicker"; ?>&condate=<?php echo "$condate"; ?>&channel=<?php echo "$channel"; ?>&selectTime=<?php echo $selectTime; ?>&selectMonth=<?php echo $selectMonth; ?>&selectYearMonth=<?php echo $selectYearMonth; ?>&selectYear=<?php echo $selectYear; ?>&typeprint=cancel" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานใบเสร็จที่ถูกยกเลิก)</span></a></div>
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