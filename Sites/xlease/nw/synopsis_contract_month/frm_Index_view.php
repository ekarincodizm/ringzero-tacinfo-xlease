<?php
include('../../config/config.php');

if(!empty($_POST['mm']) AND !empty($_POST['yy'])){
	$month = $_POST['mm'];
	$year = $_POST['yy'];	
}else{
	if(!empty($_GET['mm']) AND !empty($_GET['yy'])){
		$month = $_GET['mm'];
		$year = $_GET['yy'];
	}else{
		$month = date('m');
		$year = date('Y');	
	}	
}


$strSort = $_GET["sort"];
if($strSort == "")
{
	$strSort = "conDate";
}

$strOrder = $_GET["order"];
if($strOrder == "")
{
	$strOrder = "DESC";
}
$sql = pg_query("SELECT * FROM thcap_contract where (EXTRACT(YEAR FROM \"conDate\")='$year') AND (EXTRACT(MONTH FROM \"conDate\")='$month') order by \"$strSort\" $strOrder"); 
$rows = pg_num_rows($sql); 
$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) รายงานสรุปสัญญาใหม่ประจำเดือน</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<center><h2>(THCAP) รายงานสรุปสัญญาใหม่ประจำเดือน</h2></center>
<body >
<table width="90%" align="center" >
	<tr align="center">
		<td width="25%">
		</td>
		<td align="center" width="50%">
			<form action="frm_Index_view.php" method="post">
			<fieldset width="250"><legend>เลือกเดือน/ปี ที่ต้องการ</legend>
					เดือน : <select name="mm" id="mm">
						<?php
						//$cur_month = date('m');
						$thaimonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม ","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน ","ธันวาคม");
						for($i=0; $i<=11; $i++){
							$m = $i+1;
							if($m > 0 && $m < 10){
								$m = "0".$m;
							}

							if($m == $month)
								echo "<option value=\"$m\" selected>$thaimonth[$i]</option>";
							else
								echo "<option value=\"$m\">$thaimonth[$i]</option>";
						}
						?>
						</select>
					ปี : <select name="yy" id="yy">
						<?php
						$cur_year = date('Y');
						for($a=($cur_year-10); $a<=($cur_year+5); $a++){
							if($a == $year)
								echo "<option value=\"$a\" selected>$a</option>";
							else
								echo "<option value=\"$a\">$a</option>";
						}
						?>
						</select>
					<input type="submit" value=" ค้นหา ">	
			</fieldset> 
			</form>
		</td>
		<td width="25%" align="right" valign="bottom">
			<a href="javascript:popU('frm_PDF.php?year=<?php echo $year ?>&month=<?php echo $month ?>')"><u>พิมพ์ PDF</u></a>
		</td>
	</tr>
</table>
<table width="90%" frame="border"  align="center">
	<tr bgcolor="#A8D3FF">
		<th width="7%"><a href='frm_Index_view.php?sort=conDate&order=<?php echo $strNewOrder ?>&yy=<?php echo $year ?>&mm=<?php echo $month ?>'><u>วันที่ทำสัญญา</u></th>
		<th><a href='frm_Index_view.php?sort=contractID&order=<?php echo $strNewOrder ?>&yy=<?php echo $year ?>&mm=<?php echo $month ?>'><u>เลขที่สัญญา</u></th>
		<th width="7%"><a href='frm_Index_view.php?sort=conType&order=<?php echo $strNewOrder ?>&yy=<?php echo $year ?>&mm=<?php echo $month ?>'><u>ประเภทสัญญา</u></th>
		<th><a href='frm_Index_view.php?sort=conLoanAmt&order=<?php echo $strNewOrder ?>&yy=<?php echo $year ?>&mm=<?php echo $month ?>'><u>รูปแบบสัญญา</u></th>
		<th width="12%"><a href='frm_Index_view.php?sort=conLoanAmt&order=<?php echo $strNewOrder ?>&yy=<?php echo $year ?>&mm=<?php echo $month ?>'><u>จำนวนเงิน</u></th>
		<th><a href='frm_Index_view.php?sort=conLoanIniRate&order=<?php echo $strNewOrder ?>&yy=<?php echo $year ?>&mm=<?php echo $month ?>'><u>อัตราดอกเบี้ยตกลง</u></th>
		<th><a href='frm_Index_view.php?sort=conTerm&order=<?php echo $strNewOrder ?>&yy=<?php echo $year ?>&mm=<?php echo $month ?>'><u>จำนวนเดือนที่ผ่อนคือ</u></th>
		<th><a href='frm_Index_view.php?sort=conMinPay&order=<?php echo $strNewOrder ?>&yy=<?php echo $year ?>&mm=<?php echo $month ?>'><u>จำนวนเงินขั้นต่ำที่ต้องจ่าย</u></th>
		<th><a href='frm_Index_view.php?sort=conFirstDue&order=<?php echo $strNewOrder ?>&yy=<?php echo $year ?>&mm=<?php echo $month ?>'><u>วันที่จ่ายครั้งแรก</u></th>
	</tr>
	<?php 
		while($result = pg_fetch_array($sql)){
	?>
	
	<?php 
	//รูปแบบสัญญา 
		if($result['conCredit'] == null && $result['conLoanAmt'] != null){ 
				$prototype = 'สัญญาเงินกู้';		  
		}else{	
				if($result['conCredit'] != null && $result['conLoanAmt'] == null){
					$prototype = 'สัญญาวงเงิน';
				}else{
					if($result['conCredit'] != null && $result['conLoanAmt'] != null){
						$prototype = 'สัญญาวงเงิน/สัญญาเงินกู้';
					}else{
						$prototype = 'ไม่ระบุ';
					}
				}
		  }
	//จำนวนเงิน 
		if($result['conLoanAmt'] != null AND $result['conCredit'] != null){ 
			$money = number_format($result['conLoanAmt'],2)."(".number_format($result['conCredit'],2).")"; 		
		}else{
				if($result['conLoanAmt'] == null AND $result['conCredit'] != null){ 
					$money = "0.00"."(".number_format($result['conCredit'],2).")";
				}else{
					$money = number_format($result['conLoanAmt'],2);
				}	
		} 
	?>
	<tr  bgcolor="#EDF8FE">
		<td align="center"><?php echo $result['conDate']; ?></td>
		<td align="center"><?php echo $result['contractID']; ?></td>
		<td align="center"><?php echo $result['conType']; ?></td>
		<td align="center"><?php echo $prototype; ?></td>	
		<td align="right"><?php echo $money; ?></td>
		<td align="center"><?php echo $result['conLoanIniRate']; ?></td>
		<td align="center"><?php echo $result['conTerm']; ?></td>
		<td align="right"><?php echo number_format($result['conMinPay'],2); ?></td>
		<td align="center"><?php echo $result['conFirstDue']; ?></td>	
	</tr>
	
<?php } ?>  
	<tr  bgcolor="#A8D3FF"><td colspan="9" align="right">ทั้งหมด <?php echo $rows; ?> สัญญา </td></tr>	
  
  
</table>
</body>
</html>