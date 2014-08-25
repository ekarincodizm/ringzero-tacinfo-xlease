<?php
	include("../../config/config.php");
	
	$year = $_POST['slbxSelectYear'];
	$month = $_POST['slbxSelectMonth'];
	$selectMonth = "";

	if($year != "" && $month != ""){		
		$sql=pg_query("SELECT \"receiptID\", \"receiveDate\", \"whtRef\", \"sumdebtAmt\", \"sumWht\", 
		\"receiveUser\", \"recUser\", \"statusReceiptID\",\"thcap_receiptIDToContractID\"(\"receiptID\") AS \"contractID\",thcap_fullname
		FROM vthcap_wht 
		LEFT JOIN \"vthcap_ContactCus_detail\" b ON b.\"contractID\"=\"thcap_receiptIDToContractID\"(\"receiptID\")
		WHERE EXTRACT(MONTH FROM \"receiveDate\") = '$month' AND EXTRACT(YEAR FROM \"receiveDate\") = '$year' AND \"CusState\"=0");
		$selectMonth = $month;
	}else if($year != "" && $month == ""){
		$sql=pg_query("SELECT \"receiptID\", \"receiveDate\", \"whtRef\", \"sumdebtAmt\", \"sumWht\", 
		\"receiveUser\", \"recUser\", \"statusReceiptID\",\"thcap_receiptIDToContractID\"(\"receiptID\") AS \"contractID\",thcap_fullname
		FROM vthcap_wht 
		left join \"vthcap_ContactCus_detail\" b ON b.\"contractID\"=\"thcap_receiptIDToContractID\"(\"receiptID\")
		WHERE EXTRACT(YEAR FROM \"receiveDate\") = '$year' AND \"CusState\"=0");
		$selectMonth = "not";
	}else{
		$sql=pg_query("SELECT \"receiptID\", \"receiveDate\", \"whtRef\", \"sumdebtAmt\", \"sumWht\", 
		\"receiveUser\", \"recUser\", \"statusReceiptID\",\"thcap_receiptIDToContractID\"(\"receiptID\") AS \"contractID\",thcap_fullname
		FROM vthcap_wht 
		LEFT JOIN \"vthcap_ContactCus_detail\" b ON b.\"contractID\"=\"thcap_receiptIDToContractID\"(\"receiptID\")
		WHERE \"CusState\"=0");
		$selectMonth = "not";	
	}
	$rows = pg_num_rows($sql);
	
	
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) รายงานรับใบภาษีหัก ณ ที่จ่าย</title>
<link href="styles.css" rel="stylesheet" type="text/css">
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



<table width="1000" border="0" cellspacing="0" cellpadding="0"  align="center" bgcolor="#FFFFFF">
	<tr>
		<td><p></td>
	</tr>
	<tr>
		<td align="center"><b><h2>(THCAP) รายงานรับใบภาษีหัก ณ ที่จ่าย</h2></b></td>
	</tr>
	<tr>
		<td align="center"><hr width="550"></td>
	</tr>
	<tr>
		<td align="center">
		
		<form action="" name="frm_index_view.php" id="frm_select_month" method="post">		
			<table width="950" cellspacing="0" border="0" cellpadding="2"  align="center">
				<tr>
					<td width="25%"></td>	
					<td width="50%" align="right">
						<table width="340" frame="box" cellspacing="0" cellpadding="2"  align="center" bgcolor="#F0F0F0">
								<tr>								
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
									<?php $datenow = date('Y');
										
										$yearback = $datenow -30;														
									 for($t=$yearback;$t<=$datenow;$t++){													  
											if($t == $datenow){ ?> 
												<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
									<?php	}else{ ?>
												<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
									<?php  
											}
										} 
									?>	
									</select>								
									<input type="submit" value="เรียกดู"></td>		
								</tr>
						</table>
					</td>
					<td align="right">
						<img src="images/print.gif" height="20px"><a href="javascript:popU('frm_print_pdf.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>')"><b><u>พิมพ์ (PDF)</u></b></a>
						<img src="../thcap/thcap_capital_interest_lastweek/images/excel.png" height="20px"><a href="javascript:popU('frm_print_excel.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>')"><b><u>พิมพ์(Excel)</u></b></a>
					</td>
				</tr>									
			</table>
		</form>			
		</td>
	</tr>	
<?php if($rows != 0 || $rows != null){?>	
	<tr>
		<td>
			<table width="1100" frame="box" cellspacing="1" cellpadding="0"  align="center">			
						<tr bgcolor="#79BCFF" height="25px" align="center">
							<td>เลขที่สัญญา</td>
							<td>ผู้กู้หลัก</td>
							<td>เลขที่ใบเสร็จ</td>
							<td>วันที่รับชำระ</td>
							<td>จำนวนเงิน</td>
							<td>เลขที่ใบ<br>ภาษีหัก ณ ที่จ่าย 	</td>							
							<td>จำนวนเงิน<br>ภาษีหัก ณ ที่จ่าย</td>
							<td>สถานะ</td>
							<td>เพิ่มเติม</td>
						</tr>
						<?php 
						
						$i=0;
						
						while($resuilt = pg_fetch_array($sql)){
						$i+=1;
						if($i%2==0){
							echo "<tr class=\"odd\" height=25>";
						}else{
							echo "<tr class=\"even\" height=25>";
						} 

						?>
							<td align="center"><u><a onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $resuilt['contractID']; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><?php echo $resuilt['contractID']; ?></a></u></td>
							<td align="left"><?php echo $resuilt['thcap_fullname']; ?></td>
							<td align="center"><?php echo $resuilt['receiptID']; ?></td>
							<td align="center"><?php echo $resuilt['receiveDate']; ?></td>
							<td align="right"><?php echo number_format($resuilt['sumdebtAmt'],2); ?></td>
							<td align="center"><?php echo $resuilt['whtRef']; ?></td>
							<td align="right"><?php echo number_format($resuilt['sumWht'],2); ?></td>
							<?php  if($resuilt['recUser'] == ""){ $status='ยังไม่ได้รับ'; }else{ $status='ได้รับแล้ว'; } ?>
							<td align="center"><?php echo $status; ?></td>	
							<td align="center"><img src="images/detail.gif" style="cursor:pointer;" onclick="javascript:popU('frm_show_data.php?receiptID=<?php echo $resuilt['receiptID'];; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=350');"></td>	
						</tr>	
						
						
		<?php				
					$sum1 = $sum1+$resuilt['sumdebtAmt'];
					$sum2 = $sum2+$resuilt['sumWht'];
		}
							

	   ?>
						<tr bgcolor="#79BCAA" height="25px" >
							<td colspan="4"></td>
							<td align="right">รวม :    <?php echo number_format($sum1,2); ?></td>
							<td align="right"></td>
							<td align="right">รวม :    <?php echo number_format($sum2,2); ?></td>
							<td></td>
							<td></td>							
						</tr>		
			</table>
		</td>
	</tr>
<?php }else{ 
			echo "<tr><td><hr width=\"450\"></td></tr>";
			echo "<tr><td><h2><center> ไม่พบข้อมูลของเดือนและปีที่เลือกครับ </center></h2></td></tr>"; } ?>		
	<tr>
		<td><br></td>
	</tr>	
</table>   


</body>
</html>