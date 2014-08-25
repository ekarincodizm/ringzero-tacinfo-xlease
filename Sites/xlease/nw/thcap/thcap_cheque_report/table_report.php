<?php
include("../../../config/config.php");
$strings = $_GET["condition"];
$option = $_GET["option"];
$datecon = $_GET["datecon"];
$opstatus = $_GET["opstatus"];
if($datecon == ""){
	$datecon = nowDate();
}

if($option == 'day'){
	$condition = " date(a.\"$strings\") = '$datecon' ";
}else IF($option == 'year'){
	$yy = $_GET["yy"];
	$condition = " EXTRACT(YEAR FROM a.\"$strings\") = '$yy' ";
}else{
	$yy = $_GET["yy"];
	$mm = $_GET["mm"];
	$condition = " EXTRACT(MONTH FROM a.\"$strings\") = '$mm' AND EXTRACT(YEAR FROM a.\"$strings\") = '$yy' ";
}

if($opstatus != ""){
	$conditionstatus = "AND \"namestatus\" = '$opstatus'";
		
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ..........</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body >
<form name="frm" method="post">
	<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center" >	
	   <tr>
			<td>
				
				<div style="float:left;">
					<u><b>หมายเหตุ</b></u><font color="red">
						<span style="background-color:#e5cdf9;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ เช็คค้ำประกันหนี้ FACTORING ในกรณีที่ ลูกหนี้ไม่จ่าย จะนำเช็คผู้ขายบิลเข้า ถ้าลูกหนี้จ่ายมาปกติ ก็จะคืนเช็คให้ลูกค้า 					
					</font>
				</div>
				<div style="float:right;">
					<img src="../thcap_capital_interest_lastweek/images/pdf.png" height="20px"><a href="javascript:popU('frm_pdf.php?condition=<?php echo $strings; ?>&option=<?php echo $option; ?>&datecon=<?php echo $datecon; ?>&yy=<?php echo $yy; ?>&mm=<?php echo $mm; ?>&opstatus=<?php echo $opstatus; ?>')"><b><u>พิมพ์ (PDF)</u></b></a>
					<img src="../thcap_capital_interest_lastweek/images/excel.png" height="20px"><a href="javascript:popU('frm_excel.php?condition=<?php echo $strings; ?>&option=<?php echo $option; ?>&datecon=<?php echo $datecon; ?>&yy=<?php echo $yy; ?>&mm=<?php echo $mm; ?>&opstatus=<?php echo $opstatus; ?>')"><b><u>พิมพ์(Excel)</u></b></a>				
				</div>
				<div style="clear:both;"></div>
				<div style="float:left;">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<font color="blue">
						<span style="background-color:#DDDDDD;">&nbsp;&nbsp;&nbsp;</span> รายการสีเทา คือ เช็คถูกนำไปใช้แล้ว
						<span style="background-color:#ffcccc;">&nbsp;&nbsp;&nbsp;</span> รายการสีแดงอ่อน คือ ไม่ได้รับเช็ค
						<span style="background-color:#98FB98;">&nbsp;&nbsp;&nbsp;</span> รายการสีเขียวอ่อน คือ นำเข้าธนาคารแล้วรอยืนยันผล
					</font>
				</div>
			</td>
	   </tr>
	   <tr>
			<td align="center">
					<table align="center" frame="box" width="100%">
							<div style="padding-top:5px;"></div>	
							<tr bgcolor="#CDC0B0">
								<th>เลขที่สัญญา</th>
								<th>ชื่อ-นามสกุล ลูกค้า</th>
								<th>รหัสรายการเช็ค</th>
								<th>เลขที่เช็ค</th>
								<th>วันที่บนเช็ค</th>
								<th>ธนาคารที่ออกเช็ค</th>
								<th>จ่ายบริษัท</th>
								<th>ยอดเช็ค(บาท)</th>
								<th>ผู้นำเช็คเข้าธนาคาร</th>	
								<th>ธนาคารที่นำเข้า</th>	
								<th>วันนำเช็คเข้าธนาคาร</th>
								<th>วันที่เงินเข้าธนาคาร</th>
								<th>สถานะ</th>		
							</tr>
			
								
					<?php		
						
						$qry_selcol = pg_query("SELECT a.*,b.* FROM \"finance\".\"V_thcap_receive_cheque_chqManage\" a 
												left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
												where $condition $conditionstatus");
						$row_Selcol = pg_num_rows($qry_selcol);
							if($row_Selcol > 0){
								$sumbankChqAmt=0;
								while($re_selcol = pg_fetch_array($qry_selcol)){
									$revChqToCCID = $re_selcol["revChqToCCID"];
									$chqKeeperID = $re_selcol["chqKeeperID"];
									$revChqID = $re_selcol["revChqID"];
									$bankChqNo=$re_selcol["bankChqNo"];
									$revChqDate = $re_selcol["revChqDate"]; 
									$bankName = $re_selcol["bankName"]; 
									$bankOutBranch = $re_selcol["bankOutBranch"]; 
									$bankChqToCompID = $re_selcol["bankChqToCompID"]; 
									$bankChqAmt = $re_selcol["bankChqAmt"]; 
									$revChqStatus=$re_selcol["revChqStatus"];
									$bankChqDate=$re_selcol["bankChqDate"];
									//$giveTakerToBankAcc=$re_selcol["giveTakerToBankAcc"];
									$giveTakerID=$re_selcol["giveTakerID"];
									$bankRevResult=$re_selcol["bankRevResult"];
									$chqstampdate=$re_selcol["giveTakerDate"];
									$status=$re_selcol["namestatus"];
									$BID=$re_selcol["BID"];
									$isInsurChq = $re_selcol["isInsurChq"];
									
									//ตรวจสอบว่ารออนุมัติคืนลูกค้าอยู่หรือไม่
									// $qrychkapp=pg_query("select * from finance.thcap_receive_cheque_return where \"statusChq\"='2' and \"revChqID\"='$revChqID'");
									// $numchkapp=pg_num_rows($qrychkapp);
									// if($numchkapp>0){
										// $status="อยู่ระหว่างรอขอคืนลูกค้า";
									// }
									
									//หาชื่อลูกค้า
									$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$revChqToCCID' and \"CusState\" = '0'");
									list($cusid,$fullname) = pg_fetch_array($qry_cusname);									
									
									//หาชื่อผู้นำเข้า
									$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$giveTakerID'");
									list($userfullname) = pg_fetch_array($qry_username);
									
									
									//หาชื่อธนาคาร
									if($BID!=""){
										$qry_ourbank = pg_query("SELECT \"BName\",\"BAccount\" FROM \"BankInt\" where \"BID\" = '$BID'");
										list($ourbankname,$BAccount) = pg_fetch_array($qry_ourbank);
									}
									
									//หาวันที่เงินเข้าธนาคาร โดยวันที่นำมาจากตาราง finance.thcap_receive_transfer column "bankRevStamp"
									$qrydate=pg_query("SELECT date(\"bankRevStamp\") FROM finance.thcap_receive_transfer WHERE \"revChqID\"='$revChqID' AND \"revTranStatus\" in ('1','6')");
									list($bankRevStamp)=pg_fetch_array($qrydate);
									if($bankRevStamp==""){
										$bankRevStamp="-";
									}
									
									if($userfullname == ""){ $userfullname = '-'; }
									if($ourbankname == ""){ $ourbankname = '-'; }
									if($BAccount == ""){ $BAccount = '-'; }
									if($chqstampdate == ""){ $chqstampdate = '-'; }															
									
									$i++;
										
										if($isInsurChq==1){
											echo "<tr bgcolor=#e5cdf9 onmouseover=\"javascript:this.bgColor = '#EECBAD';\" onmouseout=\"javascript:this.bgColor = '#e5cdf9';\" align=center>";
										}else{
										
											if($revChqStatus==1){
												echo "<tr bgcolor=#DDDDDD onmouseover=\"javascript:this.bgColor = '#EECBAD';\" onmouseout=\"javascript:this.bgColor = '#DDDDDD';\" align=center>";										
											}else if($revChqStatus==4){
												echo "<tr bgcolor=#ffcccc onmouseover=\"javascript:this.bgColor = '#EECBAD';\" onmouseout=\"javascript:this.bgColor = '#ffcccc';\" align=center>";																					
											}else if($revChqStatus==7){
												echo "<tr bgcolor=#98FB98 onmouseover=\"javascript:this.bgColor = '#EECBAD';\" onmouseout=\"javascript:this.bgColor = '#98FB98';\" align=center>";																					
											}else{
												if($i%2==0){
													echo "<tr bgcolor=#EEDFCC onmouseover=\"javascript:this.bgColor = '#EECBAD';\" onmouseout=\"javascript:this.bgColor = '#EEDFCC';\" align=center>";										
												}else{
													echo "<tr bgcolor=#FFFAFA onmouseover=\"javascript:this.bgColor = '#EECBAD';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=center>";												
												} 
											}	
										}
									
					?>
									
											<td>
												<span onclick="javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=<?php echo $revChqToCCID ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;">
												<font color="red"><u><?php echo $revChqToCCID ?></u></font></span>
											</td>
											<td align="left">
												<a style="cursor:pointer;" onclick="javascipt:popU('../../search_cusco/index.php?cusid=<?php echo $cusid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')" title="ดูข้อมูลลูกค้า">					
												(<font color="red"><U><?php echo $cusid; ?></U></font>)</a>
												<?php echo $fullname; ?>
											</td>
											<td><span onclick="javascript:popU('../Channel_detail_chq.php?revChqID=<?php echo $revChqID ?>&chqKeeperID=<?php echo $chqKeeperID ?>&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=550')" style="cursor:pointer;">
												<font color="red"><u><?php echo $revChqID ?></u></font></span></td>
											<td><?php echo $bankChqNo; ?></td>
											<td><?php echo $bankChqDate; ?></td>
											<td align="left"><?php echo $bankName; ?></td>
											<td><?php echo $bankChqToCompID; ?></td>
											<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
											<td><?php echo $userfullname ?></td>
											<td><?php echo $ourbankname."-".$BAccount ?></td>
											<td><?php echo $chqstampdate ?></td>
											<td><?php echo $bankRevStamp;  ?></td>
											<td><?php echo $status ?></td>
										</tr>
								<?php 
								$sumbankChqAmt=$sumbankChqAmt+$bankChqAmt;
								unset($ourbankname);
								unset($BAccount);
								} 
								?>
						<tr bgcolor="#DDDAB2">
							<td colspan="6">
								รวม: <?php echo $row_Selcol ;?> รายการ								
							</td>
							<td align="right" bgcolor="#FFC1C1"><b>ยอดรวม</b></td>
							<td align="right" bgcolor="#FFC1C1"><b><?php echo number_format($sumbankChqAmt,2);?></b></td>
							<td colspan="5"></td>
						</tr>
					<?php }else{  echo "<tr bgcolor=\"#FFF\"><td align=\"center\" colspan=\"13\"><h2> ไม่พบรายการรับเช็ค </h2></td></tr>"; }?>	
					</table>
				</td>
			</tr>
	</table>
</form>
</body>