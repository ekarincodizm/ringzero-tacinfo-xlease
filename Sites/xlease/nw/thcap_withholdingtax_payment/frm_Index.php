<?php
	include("../../config/config.php");	
	$year = pg_escape_string($_POST['slbxSelectYear']);
	if($year==""){
		$year = date('Y');	
	}
	$month = pg_escape_string($_POST['slbxSelectMonth']);
	$income_tax = pg_escape_string($_POST['Selectincome_tax']);
	if($income_tax !=""){
		$condition=" AND \"fromChannelRef\"='$income_tax'";
	}
	else{
		$condition="";
	}
	
	//ดึงข้อมูล มาแสดง ตามเงื่อนไข ที่ได้ทำการเลือก
	if($year != "" && $month != ""){		
		$sql=pg_query("SELECT * FROM \"v_thcap_withholdingtax_payment\"
		WHERE EXTRACT(MONTH FROM \"voucherDate\") = '$month' AND EXTRACT(YEAR FROM \"voucherDate\") = '$year' $condition");
		$selectMonth = $month;		
	}else if($year != "" && $month == ""){
		$sql=pg_query("SELECT * FROM \"v_thcap_withholdingtax_payment\"
		WHERE EXTRACT(YEAR FROM \"voucherDate\") = '$year' $condition ");
		$selectMonth = "not";
	}else{
		$sql=pg_query("SELECT * FROM \"v_thcap_withholdingtax_payment\" ");
		$selectMonth = "not";	
	}
	$rows = pg_num_rows($sql);
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) รายงานจ่ายใบภาษีหัก ณ ที่จ่าย</title>
<link href="styles.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
/*function chk(){

	if(document.getElementById("Selectincome_tax").value ==""){
		alert("กรุณาเลือกประเภท ภงด");
		return false;
	}
	else{
		return true;
	}

}*/
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>
<body bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="0" cellpadding="0"  align="center" bgcolor="#FFFFFF">
	<tr>
		<td><p></td>
	</tr>
	<tr>
		<td align="center"><b><h2>(THCAP) รายงานจ่ายใบภาษีหัก ณ ที่จ่าย</h2></b></td>
	</tr>
	<tr>
		<td align="center"><hr width="550"></td>
	</tr>
	<tr>
		<td align="center">
		
		<form action="" name="frm_Index.php" id="frm_select_month" method="post">		
			<table width="100%" cellspacing="0" border="0" cellpadding="2"  align="center">
				<tr>	
				<td width="25%"></td>					
					<td width="50%" align="right">
						<table width="600" frame="box" cellspacing="0" cellpadding="2"  align="center" bgcolor="#F0F0F0" >
								<tr>
									<td align="right">เลือกประเภท ภงด : 									
										<select id="Selectincome_tax" name="Selectincome_tax" >
										<?php
											$qryspecial=pg_query("select  distinct (\"fromChannelRef\") as \"income_tax\"  
											from \"thcap_temp_voucher_channel\" where \"fromChannel\" = '-999' order by \"fromChannelRef\" ");
											$numspec=pg_num_rows($qryspecial);
											echo "<option value=\"\">ทั้งหมด</option>";		
											while($resspec=pg_fetch_array($qryspecial)){
													list($income)=$resspec;
													if($income==$income_tax){
														echo "<option value=\"$income\" selected=\"selected\">$income</option>	";
													}
													else{
														echo "<option value=\"$income\" >$income</option>";
													}													
											}			
										?>											
										</select></td>	
									<td align="right">เลือกเดือน : 									
									<select id="slbxSelectMonth" name="slbxSelectMonth" >
										<option value=""<?php if($selectMonth=="not"){echo "selected";} ?> style="background-Color:#FFFCCC" >แสดงทั้งหมด</option>
										<option value="01"<?php if($selectMonth=='01'){echo "selected";} ?>>มกราคม</option>
										<option value="02"<?php if($selectMonth=='02'){echo "selected";} ?>>กุมภาพันธ์</option>
										<option value="03"<?php if($selectMonth=='03'){echo "selected";} ?>>มีนาคม</option>
										<option value="04"<?php if($selectMonth=='04'){echo "selected";} ?>>เมษายน</option>
										<option value="05"<?php if($selectMonth=='05'){echo "selected";} ?>>พฤษภาคม</option>
										<option value="06"<?php if($selectMonth=='06'){echo "selected";} ?>>มิถุนายน</option>
										<option value="07"<?php if($selectMonth=='07'){echo "selected";} ?>>กรกฎาคม</option>
										<option value="08"<?php if($selectMonth=='08'){echo "selected";} ?>>สิงหาคม</option>
										<option value="09"<?php if($selectMonth=='09'){echo "selected";} ?>>กันยายน</option>
										<option value="10"<?php if($selectMonth=='10'){echo "selected";} ?>>ตุลาคม</option>
										<option value="11"<?php if($selectMonth=='11'){echo "selected";} ?>>พฤศจิกายน</option>
										<option value="12"<?php if($selectMonth=='12'){echo "selected";} ?>>ธันวาคม</option>
										
									</select></td>	
									<td> ปี : 
									<select id="slbxSelectYear" name="slbxSelectYear">
									<?php 
									$datenow = date('Y');									
									$yearback = $datenow-30;														
									 for($t=$yearback;$t<=$datenow;$t++){
										if($year!=""){//กรณีที่มีการเลือกข้อมูลแล้ว ต้องการให้ข้อมูลที่เลือกแสดงค่าที่เลือก ใน เดือนปี
											if($t == $year){ ?> 
											<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
									<?php	}else{ ?>
												<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
									<?php  
											}
										}
										else{									 
											if($t == $datenow){ ?> 
												<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
									<?php	}else{ ?>
												<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
									<?php  
											}
										}
}										
									?>	
									</select>								
									<input type="submit" value="เรียกดู" ></td>		
								</tr>
						</table>
					</td>
					<td align="right">
						<img src="images/print.gif" height="20px"><a href="javascript:popU('frm_print_pdf.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&income_tax=<?php echo $income_tax; ?>')"><b><u>พิมพ์ (PDF)</u></b></a>
						<img src="../thcap/thcap_capital_interest_lastweek/images/excel.png" height="20px"><a href="javascript:popU('frm_print_excel.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&income_tax=<?php echo $income_tax; ?>')"><b><u>พิมพ์(Excel)</u></b></a>
					</td>					
				</tr>									
			</table>
		</form>			
		</td>
	</tr>	
<?php if($rows != 0 || $rows != null){?>	
	<tr>
		<td>
			<table width="100%" frame="box" cellspacing="1" cellpadding="0"  align="center">			
						<tr bgcolor="#79BCFF" height="25px" align="center">
							<td>เลขที่ voucher</td>
							<td width="6%">วันที่มีผล</td>
							<td>ประเภท ภงด.</td>	
							<td>ประเภทเอกสาร</td>							
							<td>รหัสอ้างอิงตามประเภท</td>
							<td>เลขอ้างอิง<br>ของรายละเอียด</td>	
							<td>จำนวนเงิน<br>ที่จ่ายออก</td>							
							<td>จำนวนเงินที่<br>จ่ายออก-รับเข้า<br>(เฉพาะภาษีมูลค่าเพิ่ม)</td>
							<td>จำนวนเงินที่<br>จ่ายออก-รับเข้า<br>(ยอดรวมภาษีมูลค่าเพิ่ม)</td>							
							<td>จำนวนเงิน<br>ภาษีหัก ณ ที่จ่าย</td>
							<td>เลขที่อ้างอิงใบหัก ณ ที่จ่าย</td>
							<td>ผู้ทำรายการ</td>
							<td>ราย<br>ละเอียด </td>
						</tr>
						<?php 
						$i=0;
						while($resuilt = pg_fetch_array($sql)){
							
						$i+=1;
						//กรณีที่ เลขที่ voucher เหมือนกัน จะแสดงสี เหมือนกัน
						if($i==1){
								$voucherID_old=$resuilt['voucherID'];
								$color=1;
						}
						else{
								if($voucherID_old==$resuilt['voucherID']){}
								else{
									$voucherID_old=$resuilt['voucherID'];
									if($color==2)
									{	$color=1;}
									else
									{  	$color=2;}
									}
						}
						if($color==1){
							echo "<tr class=\"odd\" height=25>";
						}else{
							echo "<tr class=\"even\" height=25>";
						} 

						?>
							<td align="center"><u><a onclick="javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=<?php echo $resuilt['voucherID']; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=800')" style="cursor:pointer;"><font color="#0000FF" ><?php echo $resuilt['voucherID']; ?></a></u></td>							
							<td align="center"><?php echo $resuilt['voucherDate']; ?></td>
							<td align="center"><?php echo $resuilt['fromChannelRef']; ?></td>
							<td align="center"><?php echo $resuilt['voucherRefType']; ?></td>
							<td align="center"><?php echo $resuilt['voucherRefValue']; ?></td>
							<td align="center"><?php echo $resuilt['voucherThisDetailsRef']; ?></td>
							<td align="right"><?php echo number_format($resuilt['netAmt'],2); ?></td>
							<td align="right"><?php echo number_format($resuilt['vatAmt'],2); ?></td>
							<td align="right"><?php echo number_format($resuilt['sumAmt'],2); ?></td>							
							<td align="right"><?php echo number_format($resuilt['whtAmt'],2); ?></td>
							<td align="center"><?php echo  $resuilt['whtRef']; ?></td>							
							<td align="center"><?php echo $resuilt['doerFull']; ?></td>
							<td align="center"><img src="images/detail.gif" style="cursor:pointer;" onclick="javascript:popU('frm_detail_voucherID.php?voucherID=<?php echo $resuilt['voucherID']; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=350');"></td>
							
						</tr>	
						
						
		<?php		//หาผลรวมแต่ละรายการ		
					$netAmt = $netAmt+$resuilt['netAmt'];
					$vatAmt = $vatAmt+$resuilt['vatAmt'];
					$sumAmt = $sumAmt+$resuilt['sumAmt'];
					$whtAmt = $whtAmt+$resuilt['whtAmt'];
					
		}
	   ?>
						<tr bgcolor="#79BCAA" height="25px" >
							<td colspan="6" align="center">รวม </td>
							<td align="right"><?php echo number_format($netAmt,2); ?></td>
							<td align="right"><?php echo number_format($vatAmt,2); ?></td>
							<td align="right"><?php echo number_format($sumAmt,2); ?></td>
							<td align="right"><?php echo number_format($whtAmt,2); ?></td>
							
							<td colspan="3"></td>			
						</tr>		
			</table>
		</td>
	</tr>
<?php }else{ 
			echo "<tr><td><hr width=\"450\"></td></tr>";
			echo "<tr><td><h2><center> ไม่พบข้อมูลของเดือนและปีที่เลือก</center></h2></td></tr>"; } ?>		
	<tr>
		<td><br></td>
	</tr>	
</table> 
</body>
</html>