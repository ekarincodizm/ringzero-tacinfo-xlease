<?php
session_start();
include("../../config/config.php");
$current_date=nowDate();
$id_user=$_SESSION["av_iduser"];

//ตรวจสอบ user ที่เข้าใช้งานว่าเป็นระดับ admin หรือไม่
$quryuser=pg_query("select * from \"fuser\" where \"id_user\"='$id_user' and \"isadmin\"='1'");
$numuser=pg_num_rows($quryuser);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ยืนยันนำเช็คเข้าธนาคาร</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function popU(U,N,T) {
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
</style>
    
</head>
<body>
<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>       
			<fieldset><legend><B>(THCAP)ยืนยันนำเช็คเข้าธนาคาร</B></legend>
				<div align="center">
					<div class="ui-widget">
						<form method="post" name="form1" action="process_confirmchqtobank.php">
						<div style="padding-top:10px;text-align:left;"><u><b>หมายเหตุ</b></u>
							<div>- <font color="red"><span style="background-color:#e5cdf9;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ เช็คค้ำประกันหนี้ FACTORING ในกรณีที่ ลูกหนี้ไม่จ่าย จะนำเช็คผู้ขายบิลเข้า ถ้าลูกหนี้จ่ายมาปกติ ก็จะคืนเช็คให้ลูกค้า</font></div>
							<div>- <font color="red">ผลเช็คเด้ง หมายถึง <b>"เช็คคืนรอจัดการ"</b></font></div>
							<div>- <font color="red">รายการเช็คที่บันทึกเข้าระบบแต่ยังไม่บันทึกสัญญาจะไม่สามารถทำรายการได้ จนกว่าจะบันทึกสัญญาเข้าระบบเรียบร้อย</font></div>
						</div>
						<div id="panel">
							<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
							<tr bgcolor="white"><td colspan="12" align="right"><font color="red">ในกรณีที่ยังไม่ทราบผลเช็ค ให้เลือก -ไม่ระบุ - </font></td></tr>
							<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
								<td>เลขที่สัญญา</td>
								<td width="120">ชื่อ-สกุลลูกค้า</td>
								<td>เลขที่เช็ค</td>
								<td>วันที่บนเช็ค</td>
								<td width="120">ธนาคารที่ออกเช็ค</td>
								<td>จ่ายบริษัท</td>
								<td>ยอดเช็ค</td>
								<td>ผู้นำเช็คเข้า</td>
								<td>ธนาคารที่นำเข้า</td>	
								<td>ผลการนำเช็คเข้า</td>
							</tr>
							<?php
							//หาวันที่นำเช็คเข้าธนาคาร
							if($numuser==0){
								$qry_date=pg_query("select \"giveTakerDate\" from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
									left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\" and \"giveTakerDate\" is not null
									WHERE \"revChqStatus\" ='7' and \"giveTakerID\"='$id_user' and a.\"bankRevResult\" is null group by \"giveTakerDate\" order by a.\"giveTakerDate\"");
							}else{
								$qry_date=pg_query("select \"giveTakerDate\" from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
									left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\" 
									WHERE \"revChqStatus\" ='7' and a.\"bankRevResult\" is null and \"giveTakerDate\" is not null
									group by \"giveTakerDate\" order by a.\"giveTakerDate\"");
							}
							$nub=pg_num_rows($qry_date);
							//วนแสดงวันที่ที่นำเช็คเข้า
							while($resdate=pg_fetch_array($qry_date)){
								$giveTakerDate=$resdate["giveTakerDate"];
								
								//แสดงวันที่
								echo "<tr><td colspan=10 bgcolor=#C0C0C0><b>วันที่นำเช็คเข้าธนาคาร : $giveTakerDate</b></td></tr>";
							
								//query ข้อมูลมาแสดงจากวันที่ที่ได้
								if($numuser==0){
									$qry_fr=pg_query("select * from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
										left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
										WHERE \"revChqStatus\" ='7' and date(\"giveTakerDate\") ='$giveTakerDate' and \"giveTakerID\"='$id_user' and a.\"bankRevResult\" is null order by a.\"revChqID\"");
								}else{
									$qry_fr=pg_query("select * from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
										left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
										WHERE \"revChqStatus\" ='7' and date(\"giveTakerDate\") ='$giveTakerDate' and a.\"bankRevResult\" is null order by a.\"revChqID\"");
								}
								
								$i=0;
								
								while($res_fr=pg_fetch_array($qry_fr)){
									$chqKeeperID = $res_fr["chqKeeperID"];
									$revChqID = $res_fr["revChqID"];
									$bankChqNo=$res_fr["bankChqNo"];
									$revChqDate = $res_fr["revChqDate"]; 
									$bankName = $res_fr["bankName"]; 
									$bankOutBranch = $res_fr["bankOutBranch"]; 
									$bankChqToCompID = $res_fr["bankChqToCompID"]; 
									$bankChqAmt = $res_fr["bankChqAmt"]; 
									$revChqStatus=$res_fr["revChqStatus"];
									$bankChqDate=$res_fr["bankChqDate"];									
								    //$giveTakerToBankAcc=$res_fr["giveTakerToBankAcc"]; นำออกเนื่องจากมี BID แล้ว
									$BID=$res_fr["BID"];
									$giveTakerID=$res_fr["giveTakerID"];
									$bankRevResult=$res_fr["bankRevResult"];
									$isInsurChq = $res_fr["isInsurChq"];
									
									//หาเลขที่สัญญา
									$qry_conid=pg_query("select \"revChqToCCID\" from finance.\"thcap_receive_cheque\" a WHERE \"revChqID\" ='$revChqID' ");
									list($contractid) = pg_fetch_array($qry_conid);
									
									//เช็คว่ามีเลขที่สัญญาในระบบ?
									$qry_chk_con = pg_query("select \"contractID\" from thcap_contract where \"contractID\"='$contractid' ");
									$num_chk_con = pg_num_rows($qry_chk_con);
									
									//หาชื่อลูกค้า
									$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractid' and \"CusState\" = '0'");
									list($cusid,$fullname) = pg_fetch_array($qry_cusname);
									
									//หาชื่อธนาคาร
									
									$qry_ourbank = pg_query("SELECT \"BName\",\"BAccount\" FROM \"BankInt\" where \"BID\" = '$BID'");
									list($ourbankname,$BAccount) = pg_fetch_array($qry_ourbank);
									
									
									//หาชื่อผู้นำเข้า
									$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$giveTakerID'");
									list($userfullname) = pg_fetch_array($qry_username);
									
									$i+=1;
									if($i%2==0){
										if($isInsurChq==1){
											echo "<tr bgcolor=\"#e5cdf9\" align=center>";
										}else{
											echo "<tr class=\"odd\" align=center>";
										}
									}else{
										if($isInsurChq==1){
											echo "<tr bgcolor=\"#e5cdf9\" align=center>";
										}else{
											echo "<tr class=\"even\" align=center>";
										}
									}
								?>
									
									<td>
										<a style="cursor:pointer" onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title="ดูตารางผ่อนชำระ">
										<font color="red"><U><?php echo $contractid; ?></U></font></a>
									</td>
									<td align="left">
										<a style="cursor:pointer;" onclick="javascipt:popU('../search_cusco/index.php?cusid=<?php echo $cusid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')" title="ดูข้อมูลลูกค้า">					
										(<font color="red"><U><?php echo $cusid; ?></U></font>)</a>
										<?php echo $fullname; ?>
									</td>
									<td><?php echo $bankChqNo; ?><input type="hidden" name="chqKeeperID[]" value="<?php echo $chqKeeperID;?>"></td>
									<td><?php echo $bankChqDate; ?><input type="hidden" name="revChqID[]" value="<?php echo $revChqID;?>"></td>
									<td align="left"><?php echo $bankName; ?></td>
									<td><?php echo $bankChqToCompID; ?></td>
									<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
									<td><?php echo $userfullname ?></td>
									<td><?php echo "$ourbankname-$BAccount" ?></td>
									<td>								
										<select name="conf[]">
										
											<?php 
												if($bankRevResult == '1'){ $selected1 = 'selected'; } 
												else if($bankRevResult == '2'){ $selected2 = 'selected'; }
												else if($bankRevResult == '3'){ $selected3 = 'selected'; }
												else if($bankRevResult == '4'){ $selected3 = 'selected'; }
												else{ $selected = 'selected';}	
											?>
											<option value="0" <?php echo $selected ?>>ไม่ระบุ</option>
											<?php if ($num_chk_con>0) { ?>
											<option value="1" <?php echo $selected1 ?>>เข้าปกติ</option>
											<option value="2" <?php echo $selected2 ?>>เข้า Too Late</option>
											<option value="3" <?php echo $selected3 ?>>เช็คเด้ง</option>
											<option value="4" <?php echo $selected4 ?>>ยกเลิกนำเช็คเข้า</option>
											<?php } ?>
											<?php 
												unset($selected);
												unset($selected1);
												unset($selected2);
												unset($selected3);
												unset($selected4);
											?>
										</select>
									</td>
								</tr>
								<?php
								} //end whil
							} //end while วันที่
							if($nub == 0){
								echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
							}					
							?>
							</table>
						</div>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
	<tr><td align="center" height="50"><input type="submit" value="บันทึก" onclick="return confirm('ยืนยันการทำรายการ')"><input type="button" value="   ปิด   " onclick="window.close();"></td></tr>
</table>
<div id="limit">
<?php include("frm_histority_limit.php");?>
</div>
<table width="900" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div style="padding-top:100px;"></div>
		<fieldset><legend align="center"><font size="5px;"><b>เช็ครอนำเข้าธนาคาร</b></font></legend>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#BBBBBB" align="center">
				<td>เลขที่สัญญา</td>
				<td>ชื่อ-นามสกุล ลูกค้า</td>
				<td>เลขที่เช็ค</td>
				<td>วันที่บนเช็ค</td>
				<td>ธนาคารที่ออกเช็ค</td>
				<td>สาขา</td>
				<td>จ่ายบริษัท</td>
				<td>ยอดเช็ค(บาท)</td>
			</tr>
			<?php
			$qry_fr=pg_query("select * from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
			left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
			WHERE \"revChqStatus\" ='8' and \"bankChqDate\" <='$current_date' and a.\"bankRevResult\" is null order by a.\"revChqID\" ");
			$nub=pg_num_rows($qry_fr);
			$i=0;
			while($res_fr=pg_fetch_array($qry_fr)){
				$revChqID = $res_fr["revChqID"];
				$bankChqNo=$res_fr["bankChqNo"];
				$bankChqDate = $res_fr["bankChqDate"];							
				$bankName = $res_fr["bankName"]; 
				$bankOutBranch = $res_fr["bankOutBranch"]; 
				$bankChqToCompID = $res_fr["bankChqToCompID"]; 
				$bankChqAmt = $res_fr["bankChqAmt"]; 
				$revChqStatus=$res_fr["revChqStatus"];
				$isInsurChq = $res_fr["isInsurChq"];
				//หาเลขที่สัญญา
				$qry_conid=pg_query("select \"revChqToCCID\" from finance.\"thcap_receive_cheque\" a WHERE \"revChqID\" ='$revChqID' ");
				list($contractid) = pg_fetch_array($qry_conid);
				
				//หาชื่อลูกค้า
				$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractid' and \"CusState\" = '0'");
				list($cusid,$fullname) = pg_fetch_array($qry_cusname);
				
				$i+=1;
				if($i%2==0){
					if($isInsurChq==1){
						echo "<tr bgcolor=\"#e5cdf9\" align=center>";
					}else{
						echo "<tr class=\"#DDDDDD\" align=center>";
					}
				}else{
					if($isInsurChq==1){
						echo "<tr bgcolor=\"#e5cdf9\" align=center>";
					}else{
						echo "<tr class=\"#EEEEEE\" align=center>";
					}
				}
			?>
				<td>
					<a style="cursor:pointer" onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title="ดูตารางผ่อนชำระ">
					<font color="red"><U><?php echo $contractid; ?></U></font></a>
				</td>
				<td align="left">
					<a style="cursor:pointer;" onclick="javascipt:popU('../search_cusco/index.php?cusid=<?php echo $cusid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')" title="ดูข้อมูลลูกค้า">					
					(<font color="red"><U><?php echo $cusid; ?></U></font>)</a>
					<?php echo $fullname; ?>
				</td>
				<td><?php echo $bankChqNo; ?></td>
				<td><?php echo $bankChqDate; ?></td>
				<td align="left"><?php echo $bankName; ?></td>
				<td><?php echo $bankOutBranch; ?></td>
				<td><?php echo $bankChqToCompID; ?></td>
				<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>			
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
		</fieldset>
	</td>
</tr>
</table>
</form>
</body>
</html>