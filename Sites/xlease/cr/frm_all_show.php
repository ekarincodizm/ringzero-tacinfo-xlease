<?php
set_time_limit(0);
ini_set('memory_limit','1024M');
include("../config/config.php");
$date_check = pg_escape_string($_POST['date_check']);

if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}

$onlydonthave=pg_escape_string($_POST['onlydonthave']);
$zero=pg_escape_string($_POST['zero']);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION['session_company_name']; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
function MM_openbrWindow(theURL,winName,features) { 
	window.open(theURL,winName,features);
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<?php include("menu.php"); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
<tr>
	<td> 
		<fieldset><legend><b>แสดงรายการ ลูกค้า</b></legend>
		<form method="post" action="" name="f_list" id="f_list">
			<div align="left">
				<b>เลือกเดือน</b>
				<select name="mm">
				<?php
				if(empty($mm)){
					$nowmonth = date("m");
				}else{
					$nowmonth = $mm;
				}
				$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
				for($i=0; $i<12; $i++){
					$a+=1;
					if($a > 0 AND $a <10) $a = "0".$a;
					if($nowmonth != $a){
						echo "<option value=\"$a\">$month[$i]</option>";
					}else{
						echo "<option value=\"$a\" selected>$month[$i]</option>";
					}
					
				}
				?>    
				</select>
				<b>ปี</b> 
				<select name="yy">
				<?php
				if(empty($yy)){
					$nowyear = date("Y");
				}else{
					$nowyear = $yy;
				}
				$year_a = $nowyear + 5; 
				$year_b =  $nowyear - 5;

				$s_b = $year_b+543;

				while($year_b <= $year_a){
					if($nowyear != $year_b){
						echo "<option value=\"$year_b\">$s_b</option>";
					}else{
						echo "<option value=\"$year_b\" selected>$s_b</option>";
					}
					$year_b += 1;
					$s_b +=1;
				}
				?>
				</select>
				<input type="checkbox" name="onlydonthave" value="1" <?php if($onlydonthave==1){ echo "checked";}?>>แสดงเฉพาะรายการที่ไม่พบใบเสร็จ <input type="checkbox" name="zero" value="1" <?php if($zero==1){ echo "checked";}?>>แสดงเฉพาะรายการที่ตั้งหนี้เป็น <b>0</b> บาท <input type="submit" name="submit" value="ค้นหา">
			</div>
		</form>
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0">
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
			<td align="center">IDNO</td>
			<td align="center">IDCarTax</td>
			<td align="center">ชื่อ</td>
			<td align="center">ทะเบียน</td>
			<td align="center">ประเภทบริการ</td>
			<td align="center">เลขที่ใบเสร็จ</td>
			<td align="center">วันที่</td>
			<td align="center">ยอดชำระ</td>
			<td align="center" bgcolor="#FFBBFF">วันที่ชำระ</td>
			<td align="center" bgcolor="#FFBBFF">เลขที่ใบเสร็จ</td>
			<td align="center" bgcolor="#FFBBFF">จำนวนเงิน</td>
			<td align="center" bgcolor="#FFBBFF">สถานะการชำระ</td>
		</tr>
   
		<?php
		if( isset($mm) ){
			$qry_name=pg_query("select \"IDCarTax\",\"IDNO\",\"CusAmt\",\"TypeDep\",\"ApointmentDate\",\"TaxDueDate\",\"BookIn\",\"cuspaid\"
			from carregis.\"CarTaxDue\" where (EXTRACT(MONTH FROM \"TaxDueDate\")='$mm' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$yy') ORDER BY \"IDNO\" ASC ");
			$rows = pg_num_rows($qry_name);

			while($res_name=pg_fetch_array($qry_name)){
				$IDCarTax = $res_name["IDCarTax"];
				$IDNO = $res_name["IDNO"];
				$CusAmt = $res_name["CusAmt"];
				$TypeDep = $res_name["TypeDep"];
				$ApointmentDate = $res_name["ApointmentDate"]; if(empty($ApointmentDate)) $ApointmentDate = "-";
				$TaxDueDate = $res_name["TaxDueDate"];
				$TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
				$BookIn = $res_name["BookIn"];
				$cuspaid=$res_name["cuspaid"];   //สถานะการจ่ายเงิน
				
				$qry_name8=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypeDep' ");
				if($res_name8=pg_fetch_array($qry_name8)){
					$TDName = $res_name8["TName"];
				}
			
				
				$O_DATE = "";
				$O_RECEIPT = "";
				$O_MONEY = "";
				$PayType = "";
				$qry_vcus=pg_query("select \"O_DATE\",\"O_RECEIPT\",\"O_MONEY\",\"O_Type\",\"PayType\" from \"FOtherpay\" WHERE  \"RefAnyID\"='$IDCarTax'");
				if($resvc=pg_fetch_array($qry_vcus)){
					$O_DATE = $resvc["O_DATE"];
					$O_RECEIPT = $resvc["O_RECEIPT"];
					$O_MONEY = $resvc["O_MONEY"];
					$O_Type = $resvc["O_Type"];
					$PayType = $resvc["PayType"];	
				}
				
				$qry_name2=pg_query("select a.\"CarID\" as asset_id,a.\"C_REGIS\",b.\"asset_type\",c.\"full_name\" from \"Carregis_temp\" a
				left join \"Fp\" b on a.\"IDNO\"=b.\"IDNO\"
				left join \"Fa1_FAST\" c on b.\"CusID\"=c.\"CusID\"
				WHERE a.\"IDNO\"='$IDNO' order by \"auto_id\" DESC limit 1 ");
				$num_cartemp=pg_num_rows($qry_name2);
				if($num_cartemp==0){
					//กรณีเป็น Gas 
					$qry_name2=pg_query("SELECT a.\"asset_id\",b.\"car_regis\",a.\"asset_type\",c.\"full_name\" FROM \"Fp\" a
					LEFT JOIN \"FGas\" b ON a.asset_id = b.\"GasID\"
					LEFT JOIN \"Fa1_FAST\" c ON a.\"CusID\" = c.\"CusID\"
					WHERE \"IDNO\"='$IDNO' ");
				}
				
				//$qry_name2=pg_query("select * from \"VContacttest\" WHERE \"IDNO\"='$IDNO' ");
				if($res_name2=pg_fetch_array($qry_name2)){
					$asset_id = $res_name2["asset_id"];
					$full_name = $res_name2["full_name"];
					$asset_type = $res_name2["asset_type"];
					$C_REGIS = $res_name2["C_REGIS"];
					$car_regis = $res_name2["car_regis"];
					if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
				}else{
					$full_name = "ไม่พบข้อมูล";
					$show_regis = "ไม่พบข้อมูล";
				}
		 
				//นำใบเสร็จที่ได้มา map ย้อนหลังไม่เกิน 4 เดือน (กรณีชำระเงินก่อนสร้างรายการ)
				if($cuspaid == 'f' and $O_RECEIPT == ""){ 
					//หาใบเสร็จย้อนหลัง 4 เดือน
					$qryday=pg_query("SELECT \"gen_numDaysInMonth\"($mm,$yy)");
					list($numday)=pg_fetch_array($qryday);
					
					$endDate=$yy."-".$mm."-".$numday;
					
					$startDate=date("Y-m-d",strtotime("-4 month",strtotime($endDate)));
					
					//หาว่ามีการจ่ายเงินหรือยัง ถ้ามีแล้วให้ map ใบเสร็จ
					$query_map=pg_query("select \"IDNO\" , \"O_RECEIPT\"  from \"FOtherpay\" where \"IDNO\"='$IDNO' and \"O_Type\"='$TypeDep' and (\"O_DATE\" between '$startDate' and '$endDate')");
					$numrows_map=pg_num_rows($query_map);
					if($numrows_map==0){
						$txtmap=1;
					}else{
						$txtmap=2;
					}
				}else{
					$txtmap=3; 
				}
				$CusAmt2=number_format($CusAmt,2);
				if(($onlydonthave==1 and numrows_map==0 and $zero!=1 and $cuspaid == 'f' and $O_RECEIPT == "") ||
				   ($onlydonthave!=1 and $zero!=1) ||
				   ($onlydonthave==1 and numrows_map==0 and $zero==1 and $cuspaid == 'f' and $O_RECEIPT == "" and $CusAmt2=="0.00") ||
				   ($onlydonthave!=1 and $zero==1 and $CusAmt2=="0.00")){
					
					if($BookIn != 't'){
						echo "<tr style=\"background-color:#79BCFF\">";
					}else{
						echo "<tr style=\"background-color:#CEFFCE\">";
					}
					?>

					<td align="center"><span onclick="javascript:popU('../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="แสดงตารางผ่อนชำระ"><U><b><?php echo $IDNO; ?></b></U></span></td>
					<td align="center"><span onclick="javascript:popU('../post/frm_otherpay.php?idno=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="แสดงตารางผ่อนชำระ"><U><b><?php echo $IDCarTax; ?></b></U></span></td>
					<td><b><?php echo $full_name; ?></b></td>
					<td><b><?php echo $show_regis; ?></b></td>
					<td><b><?php echo $TDName; ?></b></td>
					<td colspan="2"></td>
					<td align="right"><b><?php echo number_format($CusAmt,2); ?></b></td>
					<td align="center" bgcolor="#FFEAFF"><b><?php echo "$O_DATE"; ?></b></td>
					<td align="center" bgcolor="#FFEAFF">
						<?php
							if($txtmap==1){
								echo "ไม่พบใบเสร็จ";
							}else if($txtmap==2){
							?>
								<input type="button" name="map" value="MAP ใบเสร็จ" onClick="MM_openbrWindow('nw_mapReceipt.php?IDNO=<?php echo $IDNO;?>&O_Type=<?php echo $TypeDep;?>&startDate=<?php echo $startDate;?>&endDate=<?php echo $endDate;?>&IDCarTax=<?php echo $IDCarTax;?>','','scrollbars=yes,width=800,height=600, left = 0, top = 0')">
							<?php
							}else{
								echo "<b>$O_RECEIPT</b>"; 
							}
						?>
					</td>
					<td align="right" bgcolor="#FFEAFF"><b><?php echo number_format($O_MONEY,2); ?></b></td>
					<td align="left" bgcolor="#FFEAFF"><b><?php echo "$PayType"; ?></b></td>  
					</tr>

					<?php
					$BillNumber = "";   
					$in = 0;
					$qry_detail=pg_query("select \"TaxValue\",\"BillNumber\",\"TypePay\",\"CoPayDate\" from carregis.\"DetailCarTax\" where (\"IDCarTax\" = '$IDCarTax') ");
					$rows_dt = pg_num_rows($qry_detail);
					while($res_detail=pg_fetch_array($qry_detail)){
						$TaxValue = $res_detail["TaxValue"];
						$BillNumber = $res_detail["BillNumber"];
						$TypePay = $res_detail["TypePay"];
						$CoPayDate = $res_detail["CoPayDate"];
						
						if(!empty($TypePay)){
							$qry_name4=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypePay' ");
							if($res_name4=pg_fetch_array($qry_name4)){
								$TName = $res_name4["TName"];
							}
						}

						$s_TaxValue+=$TaxValue;
						$sum_TaxValue+=$TaxValue;
						
						$in+=1;
						if($in%2==0){
							echo "<tr class=\"odd\" valign=\"top\">";
						}else{
							echo "<tr class=\"even\" valign=\"top\">";
						}
						?>

						<td colspan="4"></td>
						<td align="left"><?php echo "$TName"; ?></td>
						<td align="left"><?php echo "$BillNumber"; ?></td>
						<td align="center"><?php echo "$CoPayDate"; ?></td>
						<td align="right"><?php echo number_format($TaxValue,2); ?></td>
						<?php if($in%2==0){ ?>
						<td colspan="4" bgcolor="#FFFBFF"></td>
						<?php }else{ ?>
						<td colspan="4" bgcolor="#FFEAFF"></td>
						<?php } ?>
						</tr>

						<?php 
						if($rows_dt==$in){ 
						?>
						<tr bgcolor="#FFFFEA" style="font-size:11px; font-weight:bold;">
							<td align="right" colspan="7">รวมเงิน</td>
							<td align="right"><?php echo number_format($s_TaxValue,2); ?></td>
							<td colspan="4"></td>
						</tr>

						<?php 
						$s_TaxValue = 0;
						}
						?>

					<?php            
					}//end while
					$sum_O_MONEY+=$O_MONEY;	
				} //end if เงื่อนไขการเลือก checkbox
			}//end while
		}//if date_check

		if($rows > 0){
		?>
			<tr bgcolor="#FFFFC0" style="font-size:11px; font-weight:bold;">
				<td align="right" colspan="7">รวมยอดเงิน <u>ทั้งหมด</u></td>
				<td align="right"><?php echo number_format($sum_TaxValue,2); ?></td>
				<td colspan="2"></td>
				<td align="right"><?php echo number_format($sum_O_MONEY,2); ?></td>
				<td></td>
			</tr>
			<tr bgcolor="#ffffff" style="font-size:11px;">
				<td align="right" colspan="20"><a href="frm_all_show_print.php?mm=<?php echo "$mm"; ?>&yy=<?php echo "$yy"; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์</b></a></td>
			</tr>                                                                      
		<?php } ?>
		</table>
		</fieldset>
    </td>
</tr>
</table>
</body>
</html>