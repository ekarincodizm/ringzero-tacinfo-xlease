<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");



$type = $_GET['type'];

	if($type == 'a1'){
		$type1 = conLoanAmt;
		$type2 = conFinanceAmount;
	}else if($type == 'a2'){
		$type1 = "";
	}else if($type == 'a3'){
		$type1 = "";
	}else{
		echo "<script>alert(' ไม่มีข้อมูล ')</script>";
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
		exit();
	}

$contype = $_GET['contypee'];
$contype = explode("@",$contype);
sizeof($contype);

for($con = 0;$con < sizeof($contype) ; $con++){

	if($contype[$con] != ""){	
		if($contypeqry == ""){
			$contypeqry = "\"conType\" = '$contype[$con]' ";
		}else{
			$contypeqry = $contypeqry."OR \"conType\" = '$contype[$con]' ";
		}		
	}

}

if($contypeqry != ""){
	$contypeqry = "AND (".$contypeqry.")";
}
?>
<?PHP 
$year5 = $_GET['Ystart'];	
$m5 = $_GET['Mstart'];

$year = $_GET['Ystart'];	
$m1 = $_GET['Mstart'];
$playback = $_GET['report'];

	if($playback != ''){		
		$stop = $playback;	
	}else{
		echo "<script>alert(' ไม่มีข้อมูล ')</script>";
		exit();
}


$strSQL = "SELECT \"gen_numDaysInMonth\"($m1,$year) ";
$objQuery = pg_query($strSQL);
$re1= pg_fetch_array($objQuery);
list($day)=$re1;

$date = $year."-".$m1."-"."01";
$datedes = $year."-".$m1."-".$day;
$roww = 0;

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>- รายงานสถิติยอดสินเขื่อ -</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
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

<body>

<table width="1100" frame="box" align="center">
<?php if($type != 'a2'){ ?>
		<tr bgcolor="#CDBA96">
			<th width="100">เดือน-ปี</th>
			<th width="150">เลขที่สัญญา</th>
			<th width="210">ผู้กู้หลัก</th>
			<th width="100">วันที่ทำสัญญา</th>
			<th width="100">วันสิ้นสุดสัญญา</th>
			<th width="120">ระยะเวลาสัญญา</th>
			<th width="100">อัตราดอกเบี้ย</th>
			<th width="200">จำนวนเงินกู้</th>
		</tr>
		
 <?php }
  
  for($i=1;$i<=$stop;$i++){
		
		if($type=='a1'){

		$strSQL = "	
					SELECT 	\"$type1\" as \"conLoanAmt\",\"conDate\",\"contractID\",\"conLoanIniRate\",\"conEndDate\" 
					FROM 	\"thcap_mg_contract\" 
					WHERE 	\"conDate\" Between '$date' AND  '$datedes' $contypeqry
					
					UNION
					
					SELECT 	\"$type2\" as \"conLoanAmt\",\"conDate\",\"contractID\",\"conLoanIniRate\",\"conEndDate\" 
					FROM 	\"thcap_lease_contract\" 
					WHERE 	\"conDate\" Between '$date' AND  '$datedes' $contypeqry
					
					ORDER BY \"contractID\"
				  ";
		$objQuery = pg_query($strSQL);
		$nrows = pg_num_rows($objQuery);
		$text = 'รายการทั้งหมดของเดือน';
		$textm ='รวมยอดสินเชื่อของเดือน';
		$bath = 'บาท';
		
		$amttextm ='รวมยอดสินเชื่อทั้งหมด';
		$amttext = 'รายการทั้งหมด';
		$roww = $roww + $nrows;
		if($nrows == 0){
					//continue;
	
				}
			
		
		}else if($type == 'a2'){
		$strSQL = "	
					SELECT 	\"contractID\",\"conType\" 
					FROM 	\"thcap_mg_contract\" 
					WHERE	\"conDate\" Between '$date' AND  '$datedes' $contypeqry
					
					UNION
					
					SELECT 	\"contractID\",\"conType\" 
					FROM 	\"thcap_lease_contract\" 
					WHERE	\"conDate\" Between '$date' AND  '$datedes' $contypeqry
					
					ORDER BY \"contractID\"
				   ";
		$objQuery = pg_query($strSQL);
		$nrows = pg_num_rows($objQuery);
		
		$text = 'จำนวนสัญญาทั้งหมด';
		$roww = $roww + $nrows;	
				if($nrows == 0){
					//continue;	
				}
				
		}else if($type == 'a3'){
		$strSQL = "
					SELECT \"contractID\",\"conLoanAmt\" as \"conLoanAmt\",\"conDate\",\"conLoanIniRate\",\"conEndDate\" 
					FROM \"thcap_mg_contract\" 
					WHERE \"conDate\" Between '$date' AND  '$datedes' $contypeqry
					
					UNION
					
					SELECT \"contractID\",\"conFinanceAmount\" as \"conLoanAmt\",\"conDate\",\"conLoanIniRate\",\"conEndDate\" 
					FROM \"thcap_lease_contract\" 
					WHERE \"conDate\" Between '$date' AND  '$datedes' $contypeqry
					
					ORDER BY \"contractID\"
					
				  ";
		$objQuery = pg_query($strSQL);
		$nrows = pg_num_rows($objQuery);
		
		$text = 'รายการทั้งหมดของเดือน';
		$textm ='รวมยอดสินเชื่อของเดือน';
		$bath = 'บาท';
		
		$amttextm ='รวมยอดสินเชื่อทั้งหมด';
		$amttext = 'รายการทั้งหมด';
		$roww = $roww + $nrows;
				if($nrows == 0){
					//continue;
					
				}
			
		}
		
		list($year,$m2,$day)=explode('-',$date);
		if($m2=="01"){
				$txtmonth="มกราคม";
			}else if($m2=="02"){
				$txtmonth="กุมภาพันธ์";
			}else if($m2=="03"){
				$txtmonth="มีนาคม";
			}else if($m2=="04"){
				$txtmonth="เมษายน";
			}else if($m2=="05"){
				$txtmonth="พฤษภาคม";
			}else if($m2=="06"){
				$txtmonth="มิถุนายน";
			}else if($m2=="07"){
				$txtmonth="กรกฎาคม";
			}else if($m2=="08"){
				$txtmonth="สิงหาคม";
			}else if($m2=="09"){
				$txtmonth="กันยายน";
			}else if($m2=="10"){
				$txtmonth="ตุลาคม";
			}else if($m2=="11"){
				$txtmonth="พฤศจิกายน";
			}else if($m2=="12"){
				$txtmonth="ธันวาคม";
			}	
?>					
<?PHP	
			if($type=='a1'){
					$sum = 0;
					$sm = 0;
					while($results = pg_fetch_array($objQuery)){
					$conid =  $results["contractID"];
					
					$maincussql = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$conid' and \"CusState\" = '0' ");
					$remaincus = pg_fetch_array($maincussql);
					
					
					
					if($sm%2==0){
						echo "<tr bgcolor=#EEE9BF onmouseover=\"javascript:this.bgColor = '#87CEEB';\" onmouseout=\"javascript:this.bgColor = '#EEE9BF';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFFACD onmouseover=\"javascript:this.bgColor = '#87CEEB';\" onmouseout=\"javascript:this.bgColor = '#FFFACD';\" align=center>";
					}
					
					if($sm == 0){
						echo "<td align=\"center\">$txtmonth  $year</td>";
					}else{
						echo "<td></td>";
					} ?>	
							<td align="center">
							<span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u>
							<?php echo $conid ;?></u></span></td>
							<td align="left"><?php echo $remaincus["thcap_fullname"];?></div></td>
							<td align="center"><?php echo $results["conDate"];?></div></td>
							<?php $conenddate = $results["conEndDate"];
								if($conenddate == ""){ $conenddate = "-";} ?>
							<td align="center"><?php echo $conenddate;?></td>
							<?php
							$qry_times	= pg_query("SELECT \"thcap_getLoanLength\"('$conid')");	
							list($times) = pg_fetch_array($qry_times);
							if($times == ""){$times = "-";};		
							?>							
							<td align="right"><?php echo $times; ?></td>
							<td align="right"><?php echo $results["conLoanIniRate"];?></div>%</td>
							<td align="Right"><?php echo number_format($results["conLoanAmt"],2);?></td>
						
							</tr>
<?php
												
							
							$sum = $sum + $results["conLoanAmt"];
							$sum1 = number_format($sum,2);
						
							
							$amtsum += $results["conLoanAmt"];
							$amtsum1 = number_format($amtsum,2);
							$sm++;
							$conid = "";
					}
			}else if($type == 'a2'){
							
					$contypenum = $_GET['contypee'];
					$contypenum = explode("@",$contypenum);
					for($con = 0;$con<sizeof($contypenum);$con++){
						if($contypenum[$con] != ""){
							$contypenumrow = 0;
								$strSQL1 = "
												SELECT 	\"contractID\",\"conType\" 
												FROM 	\"thcap_mg_contract\" 
												WHERE	(\"conDate\" Between '$date' AND  '$datedes') AND \"conType\" = '$contypenum[$con]'
												
												UNION
												
												SELECT 	\"contractID\",\"conType\" 
												FROM 	\"thcap_lease_contract\" 
												WHERE	(\"conDate\" Between '$date' AND  '$datedes') AND \"conType\" = '$contypenum[$con]'
												
												ORDER BY \"contractID\"
								
											";
								$objQuery1 = pg_query($strSQL1);
								$rowcon1 = pg_num_rows($objQuery1);
							if($rowcon1 > 0){ $rowcon1 = "<font color=\"black\">".$rowcon1."</font>"; }
							
							 $sum1 = $sum1.$contypenum[$con].": ".$rowcon1." ";
						}	
					}
				$textm = "แบ่งรายการเป็น "	;
				$amtsum1 = null;
				
				
				if($i == 1){ $datedescontype = $datedes;}
				 $datecontype = $date;
				 
				
			}else if($type == 'a3'){			
				$sum = 0;
				$sm = 0;
					while($results = pg_fetch_array($objQuery)){
						$conid =  $results["contractID"];
						
						$maincussql = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$conid' and \"CusState\" = '0'  ");
						$remaincus = pg_fetch_array($maincussql);
						
						
						if($sm%2==0){
							echo "<tr bgcolor=#EEE9BF onmouseover=\"javascript:this.bgColor = '#87CEEB';\" onmouseout=\"javascript:this.bgColor = '#EEE9BF';\" align=center>";
						}else{
							echo "<tr bgcolor=#FFFACD onmouseover=\"javascript:this.bgColor = '#87CEEB';\" onmouseout=\"javascript:this.bgColor = '#FFFACD';\" align=center>";
						}				
						
						if($sm == 0){
							echo "<td align=\"center\">$txtmonth  $year</td>";
						}else{
							echo "<td></td>";
						} ?>
							<td align="center">
							<span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u>
							<?php echo $conid ;?></u></span></td>
							<td align="left"><?php echo $remaincus["thcap_fullname"];?></td>
							<td align="center"><?php echo $results["conDate"];?></td>
							<?php
								$conenddate = $results["conEndDate"];
								if($conenddate = ""){$conenddate = "-";};
							?>
							<td align="center"><?php echo $conenddate;?></td>
							<?php
							$qry_times	= pg_query("SELECT \"thcap_getLoanLength\"('$conid')");	
							list($times) = pg_fetch_array($qry_times);
							if($times == ""){$times = "-";};							
							?>
							<td align="right"><?php echo $times; ?></td>
							<td align="right"><?php echo $results["conLoanIniRate"];?>%</td>
							<td align="right"><?php echo number_format($results["conLoanAmt"],2);?></td>
							
							</tr>
<?php							
							$sum = $sum + $results["conLoanAmt"];
							
							$amtsum += $results["conLoanAmt"];
							$amtsum1 = number_format($amtsum,2);
							$conid = "";
							$sm++;		
					}
					$sum1 = number_format($sum,2);
					if($nrows == 0){}else{
					$sum2 = $sum/$nrows;	
					}
			}
			
					if($nrows == 0){
						
						if($type == 'a2'){
							$sum2 = null;
							//$sum1 = null;
						}else{
							$sum2 = '0';
							$sum1 = '0';
						}						
					}
?>
			<tr>
				<td bgcolor="#B9D3EE" colspan="8" align="right"><?php echo $txtmonth." ".$year." "; ?>ทั้งหมด <font color="red"><b><?php echo $nrows; ?></b></font> รายการ
					<?php echo $textm; ?> <font color="red"><b><?php echo $sum1 ?></b></font>  <?php echo $bath; ?>
				</td>
			</tr>
<?php
			if($type== 'a3'){
?>			
					
	
					<tr>
					<td bgcolor="#87CEFA" colspan="8" align="right">ยอดสินเชื่อเฉลี่ยต่อสัญญา <font color="red"><b><?php echo number_format($sum2,2); ?></b></font>
					<?php echo $bath; ?>
					</td>
					</tr>
<?php			
			}
			
				$date = date("Y-m-d", strtotime("-1 month", strtotime($date)));
				list($year2,$m2,$d2) = explode("-",$date);
				$strSQL = "SELECT \"gen_numDaysInMonth\"($m2,$year2) ";
				$objQuery = pg_query($strSQL);
				$re1= pg_fetch_array($objQuery);
				list($day2)=$re1;				
				$datedes = $year2."-".$m2."-".$day2;		
				$amtnrows += $nrows;
				
		$sum1 = "";
		$sum2 = "";	
				
}
?>
				
					
</table>
<?php
if(empty($roww) || $roww == 0){

		//echo "<hr width=850>";
		//echo "<center><h1>ไม่พบข้อมูล</h1></center>";
		//echo "<input type=\"button\" name=\"close\" value=\"Back\" style=\"width:150px; height:30px;\" onclick=\"parent.location.href='index.php'\">";
		exit();

}



if($type == 'a2'){			
	$contypenum = $_GET['contypee'];
	$contypenum = explode("@",$contypenum);
	for($con = 0;$con<sizeof($contypenum);$con++){
		if($contypenum[$con] != ""){
			$contypenumrow = 0;
		 $strSQL1 = "
						SELECT 	\"contractID\",\"conType\" 
						FROM 	\"thcap_mg_contract\" 
						WHERE	(\"conDate\" Between '$datecontype' AND  '$datedescontype') AND \"conType\" = '$contypenum[$con]'
						
						UNION
						
						SELECT 	\"contractID\",\"conType\" 
						FROM 	\"thcap_lease_contract\" 
						WHERE	(\"conDate\" Between '$datecontype' AND  '$datedescontype') AND \"conType\" = '$contypenum[$con]'
						
						ORDER BY \"contractID\"
		
		
		           ";
		$objQuery1 = pg_query($strSQL1);
		$rowcon1 = pg_num_rows($objQuery1);
		$amtsum1 = $amtsum1.$contypenum[$con].":".$rowcon1." ";
		}	
	}
	$amttextm = "แบ่งรายการเป็น ";			
}
?>

 </div>
 
<table width="1100" border="0" align="center">
	<tr>
		<td bgcolor="#FF6666" colspan="2"><div align="right"><font size="4px;"><?php echo $amttext; ?> รวม <?php echo $amtnrows; ?> รายการ
						<?php echo $amttextm; ?> <?php echo $amtsum1; ?> <?php echo $bath; ?></font></div>
		</td>
	</tr>
<?php
					if($type== 'a3'){
					
					$sum3 = $amtsum/$amtnrows;
					
?>			
	<tr>
		<td bgcolor="#FA8072" colspan="4" align="right"><font size="3px;"> 
			ยอดสินเชื่อเฉลี่ยต่อสัญญา <?php echo number_format($sum3,2); ?> <?php echo $bath; ?></font>
		</td>
	</tr>
<?php			
					}
?>
	<!--<tr>
		<td  align="left">
			<input type="button" name="close" value="Chart" style="width:200px; height:30px;" onclick="javascript:popU('credit_chart.php?month=<?php echo $m5?>&year=<?php echo $year5?>&playback=<?php echo $playback?>&type=<?php echo $type?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')"></td>
		</td>
	</tr>-->
</table>



</body>
</html>
