<?php
include("../../../config/config.php");

$type = pg_escape_string($_GET["type"]);
$contype = pg_escape_string($_GET["contype"]);
$contypepdf = pg_escape_string($_GET["contype"]); //สำหรับส่งไปหน้า พิมพ์ PDF หรือ Excel
$find_date = pg_escape_string($_GET["find_date"]);// conDate  =วันที่ทำสัญญา  or conStartDate=วันที่เริ่มกู้/รับของ

//ตรวจสอบ ว่า ค้นหาจาก:
if ($find_date=='conDate'){// วันที่ทำสัญญา
	$conditionfind=" \"conDate\" ";
	$find="conDate";
	
}
else if($find_date=='conStartDate'){//วันที่เริ่มกู้/รับของ
	$conditionfind=" \"conStartDate\" ";
	$find="conStartDate";
}
//จบการตรวจสอบ ว่า ค้นหาจาก:

if($contype == ""){ echo "<center><h1>เลือกประเภทสินเชื่อที่ต้องการด้วยครับ !!</h1></center>"; exit(); }

if($type == 'sm'){
	$mm = pg_escape_string($_GET["month"]);
	$yy = pg_escape_string($_GET["year"]);	
	$condition = " EXTRACT(MONTH FROM $conditionfind) = '$mm' AND EXTRACT(YEAR FROM $conditionfind) = '$yy' ";
}else if($type == 'sy'){
	$yy = pg_escape_string($_GET["year"]);
	$condition = " EXTRACT(YEAR FROM $conditionfind) = '$yy' ";
}else{
	echo "<center><h1>ERROR ! เกิดความผิดพลาดในการแสดงผล</h1></center>";
	exit();
}

//นำประเภทของสัญญาที่เลือกมาตัด @ แยกประเภทออกจากกัน เพื่อใช้เป็นเงื่อนไขในการค้นหา
$contype = explode("@",$contype);
for($con = 0;$con < sizeof($contype) ; $con++){
	if($contype[$con] != ""){	
		if($contypeqry == ""){
			$contypeqry = "\"conType\" = '$contype[$con]' ";
		}else{
			$contypeqry = $contypeqry."OR \"conType\" = '$contype[$con]' ";
		}		
	}
}
// เติม AND ไปข้างหน้า ใช้ในกรณีที่มีเงื่อนไขอื่นด้วยนอกจากเงื่อนไขอย่างเดียว
if($contypeqry != ""){
	$contypeqry = "AND (".$contypeqry.")";
	$condition = $condition.$contypeqry;
}

//ตัวแปลเก็บ field เพื่อใช้เรียงข้อมูล
$strSort = $_GET["sort"];
if($strSort == ""){
	$strSort = "contractID";
}
//เรียงจากน้อยไปมากหรือมากไปน้อย
$strOrder = $_GET["order"];
if($strOrder == ""){
	$strOrder = "ASC";
}
//คำสั่ง query ข้อมูลหาว่ามีข้อมูลหรือไม่
$qry_connew1 = pg_query("SELECT \"contractID\" FROM thcap_contract where $condition");
$rows_connew1 = pg_num_rows($qry_connew1);

//เปลี่ยนการเรียงข้อมูล
$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
</head>
<body>
	<table width="95%"  cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td>
				<div style="padding-top:10px;text-align:left;"><u><b>หมายเหตุ</b></u><font color="red"> <span style="background-color:#CCCCCC;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> รายการสีเทา คือ สัญญาที่ปิดบัญชีแล้ว</font></div>								
			</td>
			<td align="right">
				<!--<img src="../thcap_capital_interest_lastweek/images/excel.png" height="20px"><a href="javascript:popU('')"><b><u>พิมพ์รายงาน (Excel)</u></b></a>	-->
				<img src="../thcap_capital_interest_lastweek/images/pdf.png" height="20px"><a href="javascript:popU('frm_pdf.php?type=<?php echo $type ?>&contype=<?php echo $contypepdf ?>&month=<?php echo $mm ?>&year=<?php echo $yy ?>&sort=<?php echo $strSort ?>&order=<?php echo $strOrder ?>&find_date=<?php echo $find_date ?>')"><b><u>พิมพ์รายงาน (PDF)</u></b></a></div>
			</td>
		</tr>
	</table>

	<table width="95%" frame="box" cellspacing="2" cellpadding="1" align="center">		
		<tr bgcolor="#CDB38B">
			<th><a onclick="sorttb('contractID','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>เลขที่สัญญา</u></a></th>
			<th>ผู้กู้หลัก</th>
			<th><a onclick="sorttb('conType','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>ประเภทสัญญา</th>
			<th width="5%"><a onclick="sorttb('conDate','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>วันที่ทำสัญญา</th>
			<th width="5%"><a onclick="sorttb('conStartDate','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>วันที่เริ่มกู้/รับของ</th>
			<th width="7%"><a onclick="sorttb('conCredit','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>วงเงิน</th>
			<th width="7%"><a onclick="sorttb('conLoanAmt','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>ยอดกู้</th>
			<th width="7%"><a onclick="sorttb('conFinAmtExtVat','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>ยอดลงทุน/ยอดจัด (ก่อนภาษีมูลค่าเพิ่ม)</th>	
			<th width="7%">เงินดาวน์ (ก่อนภาษีมูลค่าเพิ่ม)</th>				
			<th width="7%">ค่าซาก (ก่อนภาษีมูลค่าเพิ่ม)</th>			
			<th><a onclick="sorttb('','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>อัตราดอกเบี้ย</th>
			<th><a onclick="sorttb('','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>จำนวนเดือน</th>
			<th><a onclick="sorttb('conMinPay','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>ค่างวด</th>
			<th width="5%"><a onclick="sorttb('','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>วันที่เริ่มจ่าย</th>
			<th width="5%"><a onclick="sorttb('conEndDate','<?php echo $strNewOrder ?>','<?php echo $find ?>');" style="cursor:pointer;"><u>วันที่สิ้นสุดสัญญา</th>
			<th>% ค่าเสียหายปิด</th>			
		</tr>
		<?php 			
			
				
				$numrows = 0;
				if($rows_connew1 == 0){
					echo "<tr><td colspan=\"16\" align=\"center\"><h2> ไม่พบข้อมูลการเปิดสัญญา </h2><td></tr>";
					
				}else{
				
			for($con = 0;$con < sizeof($contype) ; $con++){
				if($contype[$con] != ""){
				
					$listconCreditsum = 0; //ยอดรวม วงเงิน
					$listconLoanAmtsum = 0; //ยอดรวม ยอดกู้
					$listconFinAmtExtVatsum = 0; //ยอดรวม ยอดลงทุน/ยอดจัด (ก่อนภาษีมูลค่าเพิ่ม)
					$listconMinPaysum = 0; //ยอดรวม ค่างวด
					$listconResidualValue = 0;//ยอดค่าซาก
					$listdebtNet = 0;//ยอดเงินดาวน์ (ก่อนภาษีมูลค่าเพิ่ม)
						
				
					//แสดงประเภท
					echo "<tr><td align=\"left\" colspan=\"16\" bgcolor=\"#CDB79E\"><b>&nbsp$contype[$con]</b></td></tr>";
							//คำสั่ง query ข้อมูล
							$qry_connew = pg_query("SELECT \"contractID\",\"conType\",\"conDate\",\"conStartDate\",\"conCredit\",\"conLoanAmt\",\"conFinAmtExtVat\",
							\"conLoanIniRate\",\"conTerm\",\"conMinPay\",\"conFirstDue\",\"conEndDate\",\"conClosedFee\"
							FROM thcap_contract where \"conType\" = '$contype[$con]' AND $condition order by \"$strSort\" $strOrder ");	
							
							$rows_connew = pg_num_rows($qry_connew);
							if($rows_connew > 0){ //หากประเภทนี้มีข้อมูล
									while($re_connew = pg_fetch_array($qry_connew)){
										
										//หาชื่อผู้กู้หลัก
										$contractID = $re_connew["contractID"];
										$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '0'");
										list($thcap_fullname) = pg_fetch_array($qry_cusname);
										
										//หาวันที่ปิดบัญชี
										$dateclosesql = pg_query("SELECT thcap_checkcontractcloseddate('$contractID')");
										$dateclosere = pg_fetch_array($dateclosesql);
										$dateclose = $dateclosere['thcap_checkcontractcloseddate'];
										
										//ค่าซาก (ก่อนภาษีมูลค่าเพิ่ม)										
										$conResidualValuesql = pg_query("SELECT thcap_get_all_residuevalue('$contractID','1')");
										$conResidual= pg_fetch_array($conResidualValuesql);
										$conResidualValue = $conResidual['thcap_get_all_residuevalue'];
										if($conResidualValue !=""){ $conResidualValue= number_format($conResidualValue,2);}
										//เงินดาวน์ (ก่อนภาษีมูลค่าเพิ่ม)
										$qry_debtNet = pg_query("select SUM(\"debtNet\") as \"debtNet\" from \"thcap_temp_otherpay_debt\"  
										where \"typePayID\" LIKE '%996'  and \"contractID\"='$contractID'");
										$re_debtNet = pg_fetch_array($qry_debtNet);
										$debtNet = $re_debtNet["debtNet"];
										if($debtNet !=""){ $debtNet= number_format($debtNet,2);}
										
										
									IF($dateclose != ""){ //หากสัญญาถูกปิดแล้วจะเป็นสีเทา
										echo "<tr bgcolor=#CCCCCC onmouseover=\"javascript:this.bgColor = '#87CEEB';\" onmouseout=\"javascript:this.bgColor = '#CCCCCC';\" align=center>";	
										$bgcolortd = '#CCCCCC';
									
									}else{
									
										if($numrows%2==0){
											echo "<tr bgcolor=#EEE9BF onmouseover=\"javascript:this.bgColor = '#87CEEB';\" onmouseout=\"javascript:this.bgColor = '#EEE9BF';\" align=center>";
										}else{
											echo "<tr bgcolor=#FFFACD onmouseover=\"javascript:this.bgColor = '#87CEEB';\" onmouseout=\"javascript:this.bgColor = '#FFFACD';\" align=center>";
										}
										$bgcolortd = '#BCD2EE';
										$numrows++;
									}	
						?>				
										<td align="left"><span onclick="javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
											<font color="red"><u><?php echo $re_connew["contractID"]; ?></u></font></span>
										</td>
										<td align="left"><?php echo $thcap_fullname; ?></td>
										<td><?php echo $re_connew["conType"]; ?></td>
										<td><?php echo $re_connew["conDate"]; ?></td>
										<td><?php echo $re_connew["conStartDate"]; ?></td>
										<td align="right" bgcolor="<?php echo $bgcolortd; ?>"><?php echo number_format($re_connew["conCredit"],2); ?></td>
										<td align="right" bgcolor="<?php echo $bgcolortd; ?>"><?php echo number_format($re_connew["conLoanAmt"],2); ?></td>
										<td align="right" bgcolor="<?php echo $bgcolortd; ?>"><?php echo number_format($re_connew["conFinAmtExtVat"],2); ?></td>
										<td align="right" bgcolor="<?php echo $bgcolortd; ?>"><?php echo  $debtNet;?></td><!--เงินดาวน์ (ก่อนภาษีมูลค่าเพิ่ม)-->
										<td align="right" bgcolor="<?php echo $bgcolortd; ?>"><?php echo  $conResidualValue;?></td><!--ค่าซาก (ก่อนภาษีมูลค่าเพิ่ม)-->
										<td><?php echo $re_connew["conLoanIniRate"]; ?></td>
										<td><?php echo $re_connew["conTerm"]; ?></td>
										<td align="right"><?php echo number_format($re_connew["conMinPay"],2); ?></td>
										<td><?php echo $re_connew["conFirstDue"]; ?></td>
										<td><?php echo $re_connew["conEndDate"]; ?></td>
										<td align="right"><?php echo number_format($re_connew["conClosedFee"],2); ?></td>
							</tr>
						<?php	
									
									
									//รวมของแต่ละประเภทสัญญา
									$listconCreditsum += $re_connew["conCredit"]; //ยอดรวม วงเงิน
									$listconLoanAmtsum += $re_connew["conLoanAmt"]; //ยอดรวม ยอดกู้
									$listconFinAmtExtVatsum += $re_connew["conFinAmtExtVat"]; //ยอดรวม ยอดลงทุน/ยอดจัด (ก่อนภาษีมูลค่าเพิ่ม)
									$listconMinPaysum += $re_connew["conMinPay"]; //ยอดรวม ค่างวด
									if($conResidualValue !=""){ $listconResidualValue += $conResidual['thcap_get_all_residuevalue'];}//ค่าซาก
									if($debtNet !=""){ $listdebtNet += $re_debtNet["debtNet"];}
									
						
						
									//รวมผลรวมทั้งหมด								
									$conCreditsum += $re_connew["conCredit"]; //ยอดรวม วงเงิน
									$conLoanAmtsum += $re_connew["conLoanAmt"]; //ยอดรวม ยอดกู้
									$conFinAmtExtVatsum += $re_connew["conFinAmtExtVat"]; //ยอดรวม ยอดลงทุน/ยอดจัด (ก่อนภาษีมูลค่าเพิ่ม)
									$conMinPaysum += $re_connew["conMinPay"]; //ยอดรวม ค่างวด
									if($conResidualValue !=""){ $conResidualValuesum += $conResidual['thcap_get_all_residuevalue'];}//ค่าซาก
									if($debtNet !=""){ $debtNetsum += $re_debtNet["debtNet"];}
									
									unset($thcap_fullname); //ทำลายตัวแปรเก็บชื่อผู้กู้หลัก  เพื่อป้องกันการแสดงซ้ำซ้อนของข้อมูล
								 }
								 if($listconResidualValue ==""){$listconResidualValue=""; }
								 else {$listconResidualValue=number_format($listconResidualValue,2);}
								 
								 if($listdebtNet ==""){$listdebtNet=""; }
								 else {$listdebtNet = number_format($listdebtNet,2);}
								
								
						?>		 
								<tr bgcolor="#EECFA1">
									<td>ประเภท  <?php echo $contype[$con]; ?>: <?php echo $rows_connew; ?> สัญญา</td>
									<td align="right" colspan="4"><b>รวม</b></td>
									<td align="right" colspan="" bgcolor="#A2B5CD"><?php echo number_format($listconCreditsum,2); ?></td>
									<td align="right" colspan="" bgcolor="#A2B5CD"><?php echo number_format($listconLoanAmtsum,2); ?></td>
									<td align="right" colspan="" bgcolor="#A2B5CD"><?php echo number_format($listconFinAmtExtVatsum,2); ?></td>
									<td align="right" colspan="" bgcolor="#A2B5CD"><?php echo $listdebtNet; ?></td>
									<td align="right" colspan="" bgcolor="#A2B5CD"><?php echo $listconResidualValue; ?></td>
									<td colspan="2"></td>
									<td align="right" colspan=""><?php echo number_format($listconMinPaysum,2); ?></td>
									<td colspan="4"></td>
								</tr> 
								 
						<?php		 
						 }else{
							echo "<tr><td align=\"center\" colspan=\"15\">-- ไม่มีข้อมูล --</td></tr>";
						 }
				}	
			}	 if($conResidualValuesum ==""){ $conResidualValuesum="";}
				 else{$conResidualValuesum=number_format($conResidualValuesum,2); }
				 
				 if($debtNetsum ==""){ $debtNetsum="";}
				 else{$debtNetsum=number_format($debtNetsum,2); }
						
						?>	
					<tr bgcolor="#EECFA1"><td align="left" colspan="15" bgcolor="#FF9999"><b>&nbsp ผลรวม</b></td></tr>			
					<tr bgcolor="#FFE4E1">
						<td>ทั้งหมด <?php echo $rows_connew1; ?> สัญญา</td>
						<td align="right" colspan="4"><b>รวม</b></td>
						<td align="right" colspan="" bgcolor="#A2B5CD"><?php echo number_format($conCreditsum,2); ?></td>
						<td align="right" colspan="" bgcolor="#A2B5CD"><?php echo number_format($conLoanAmtsum,2); ?></td>
						<td align="right" colspan="" bgcolor="#A2B5CD"><?php echo number_format($conFinAmtExtVatsum,2); ?></td>
						<td align="right" colspan="" bgcolor="#A2B5CD"><?php echo $debtNetsum; ?></td>		
						<td align="right" colspan="" bgcolor="#A2B5CD"><?php echo $conResidualValuesum; ?></td>						
						<td colspan="2"></td>
						<td align="right" colspan=""><?php echo number_format($conMinPaysum,2); ?></td>
						<td colspan="4"></td>
					</tr>
		<?php	}?>
		
		<tr>
		</tr>
	</table>
</body>
</html>
