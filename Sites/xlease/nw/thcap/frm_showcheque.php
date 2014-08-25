<?php
session_start();
include("../../config/config.php");

$revChqID=pg_escape_string($_GET["revChqID"]);
$bankChqNo=pg_escape_string($_GET["bankChqNo"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) แสดงรายละเอียดเช็ค</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>  
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div align="center" ><h1>(THCAP)รายละเอียดเช็ค</h1></div>
<table width="90%" border="0" cellspacing="5" cellpadding="0" align="center">
	<tr>
		<td>       
			<fieldset><legend><B>รายละเอียดเช็ค - เลขที่เช็ค : <?php echo $bankChqNo ?></B></legend>
				<div align="center">
					<div class="ui-widget">
						<div id="panel">
							<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
							<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
								<td>เลขที่สัญญา</td>
								<td>ชื่อลูกค้า</td>
								<td>ประเภทการนำเข้า</td>
								<td>สถานะเช็ค</td>
								<td>วันที่รับเช็ค</td>
								<td>วันที่สั่งจ่าย</td>
								<td>ธนาคารที่ออกเช็ค</td>
								<td>สาขา</td>
								<td>จ่ายบริษัท</td>
								<td>ยอดเช็ค</td>
							</tr>
							<?php
								$qry_fr=pg_query("select a.\"cnID\",a.\"revChqStatus\",a.\"revChqDate\"::date ,a.\"revChqToCCID\",a.\"bankOutBranch\",a.\"bankChqToCompID\",
								a.\"bankChqAmt\",a.\"bankChqDate\",b.\"bankName\" 
												from finance.thcap_receive_cheque a 
												left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
												WHERE \"revChqID\" ='$revChqID'");
								$i=0;
								while($res_fr=pg_fetch_array($qry_fr)){
									$cnID = $res_fr['cnID'];
									$revChqStatus = $res_fr['revChqStatus'];
									$revChqDate = $res_fr['revChqDate']; 
									$revChqToCCID = $res_fr['revChqToCCID']; 
									$bankName = $res_fr['bankName']; 
									$bankOutBranch = $res_fr['bankOutBranch']; 
									$bankChqToCompID = $res_fr['bankChqToCompID']; 
									$bankChqAmt = $res_fr['bankChqAmt']; 
									$bankChqDate = $res_fr['bankChqDate']; //วันที่สั่งจ่าย
								 
									
									//สถานะเช็ค
									switch($revChqStatus){
										case 1 :
											$revChqStatusName = "ACTIVE";
											break;
										case 2 :
											$revChqStatusName = "เช็คคืนรอจัดการ";
											break;
										case 3 :
											$revChqStatusName = "คืนเช็คให้ลูกค้า";
											break;
										case 4 :
											$revChqStatusName = "ไม่ได้รับเช็ค / เช็คหายไม่ทราบสาเหตุ";
											break;
										case 6 :
											$revChqStatusName = "เช็คถูกเข้าธนาคารแล้วลงวันที่<br>รอตรวจสอบผลเช็ค";
											break;
										case 7 :
											$revChqStatusName = "เช็คถูกนำไปเข้า";
											break;
										case 8 :
											$revChqStatusName = "เช็คที่รับถูกส่งให้ผู้เก็บเช็ค";
											break;
										case 9 :
											$revChqStatusName = "พนักงานรับเช็ค";
											break;
										case 10 :
											$revChqStatusName = "รออนุมัติคืนเช็คให้ลูกค้า";
											break;
									}
									
									//หาชื่อลูกค้า
									$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$revChqToCCID' and \"CusState\" = '0'");
									list($cusid,$fullname) = pg_fetch_array($qry_cusname);
									
									$i+=1;
									if($i%2==0){
										echo "<tr class=\"odd\" align=center>";
									}else{
										echo "<tr class=\"even\" align=center>";
									}
								?>
									
									<td>
										<a style="cursor:pointer" onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $revChqToCCID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title="ดูตารางผ่อนชำระ">
										<font color="red"><U><?php echo $revChqToCCID; ?></U></font></a>
									</td>
									<td align="left">
										<a style="cursor:pointer;" onclick="javascipt:popU('../search_cusco/index.php?cusid=<?php echo $cusid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')" title="ดูข้อมูลลูกค้า">					
										(<font color="red"><U><?php echo $cusid; ?></U></font>)</a>
										<?php echo $fullname; ?>
									</td>
									<td><?php echo $cnID; ?></td>
									<td><?php echo $revChqStatusName; ?></td>
									<td><?php echo $revChqDate ; ?></td>
									<td><?php echo $bankChqDate; ?></td>
									<td ><?php echo $bankName; ?></td>
									<td ><?php echo $bankOutBranch; ?></td>
									<td><?php echo $bankChqToCompID; ?></td>
									<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
								</tr>
								<?php
								} //end whil					
								?>
							</table>
							
					
							<tr>
						</div>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
	<tr>
		<td>
			<fieldset><legend><B>ประวัติการเดินเช็ค - รหัสรายการเช็ค : <?php echo $revChqID; ?></B></legend>
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
							<tr style="font-weight:bold;color:#FFFFFF" valign="top" bgcolor="#8B8378" align="center">
								<td>ลำดับที่</td>
								<td>ผู้เก็บเช็ค</td>
								<td>ผู้นำเช็คเข้าธนาคาร</td>
								<td>วันที่มอบเช็ค<br>ให้ผู้นำเข้า</td>
								<td>ครั้งที่นำเข้าธนาคาร</td>
								<td>วันที่ธนาคาร<br>ลงรับเช็ค</td>
								<td>ธนาคารที่นำเข้า</td>
								<td>ผลการลงรับเช็ค<br>ของธนาคาร</td>
								<td>ผู้ทำรายการยืนยันผล</td>
								<td>วันที่ทำรายการ<br>ยืนยันผล</td>	
								
										
							</tr>
							<?php
								$query_history_chq = pg_query(" select \"keeperID\",\"giveTakerID\",\"giveTakerDate\",\"chqSubmitTimes\",\"bankRevDate\",\"BID\",\"bankRevResult\",\"bankRevResult\",
									\"replyByTakerID\",\"replyByTakerStamp\"::date 
									from finance.thcap_receive_cheque_keeper  
									WHERE \"revChqID\" ='$revChqID' 
									order by \"chqSubmitTimes\" ");
								$nub = 0;	
								while($res_his=pg_fetch_array($query_history_chq)){
								$nub++;
									$keeperID = $res_his['keeperID']; //รหัสผู้เก็บเช็ค
									$giveTakerID = $res_his['giveTakerID'];
									$giveTakerDate = $res_his['giveTakerDate'];
									$chqSubmitTimes = $res_his['chqSubmitTimes'];
									$bankRevDate = $res_his['bankRevDate'];
									$BID = $res_his['BID'];
									$bankRevResult = $res_his['bankRevResult'];
									$replyByTakerID = $res_his['replyByTakerID'];
									$replyByTakerStamp = $res_his['replyByTakerStamp'];
									
									//หาชื่อธนาคาร
									
									$qry_ourbank = pg_query("SELECT \"BName\",\"BAccount\" FROM \"BankInt\" where \"BID\"::text = '$BID'");
									list($ourbankname,$BAccount) = pg_fetch_array($qry_ourbank);
									
									//หาชื่อผู้เก็บเช็ค
									$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$keeperID'");
									list($keeperfullname) = pg_fetch_array($qry_username);
									
									//หาชื่อผู้นำเข้า
									$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$giveTakerID'");
									list($givefullname) = pg_fetch_array($qry_username);
									
									//หาชื่อผู้ยืนยัน
									$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$replyByTakerID'");
									list($replyfullname) = pg_fetch_array($qry_username);
									
									//สถานะเช็ค
									switch($bankRevResult){
										case 1 :
											$RevResult = " ปกติ";
											break;
										case 2 :
											$RevResult = "too late";
											break;
										case 3 :
											$RevResult= "เช็คเด้ง";
											break;
										case 4 :
											$RevResult = "ยกเลิกนำเช็คเข้าธนาคาร";
											break;
									}
									
									echo "<tr align=\"center\">";
										echo "<td>$nub</td>";
										echo "<td>$keeperfullname</td>";
										echo "<td>$givefullname</td>";
										echo "<td>$giveTakerDate</td>";
										echo "<td>$chqSubmitTimes</td>";
										echo "<td>$bankRevDate</td>";
										echo "<td>$ourbankname-$BAccount</td>";
										echo "<td>$RevResult</td>";
										echo "<td>$replyfullname</td>";
										echo "<td>$replyByTakerStamp</td>";
									echo "</tr>";
								} //end while
							?>
								
							</table>
			</fieldset>
		</td>
	</tr>
	<tr><td align="center" height="50"><input type="button" value="   ปิด   " onclick="window.close();"></td></tr>
</table>


</body>
</html>