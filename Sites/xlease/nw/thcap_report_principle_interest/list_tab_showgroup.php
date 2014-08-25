<?php
require_once("../../config/config.php");

// ============================================================================================
// รับค่าที่ผู้ใช้งานเลือกจากหน้าหลัก
// ============================================================================================
$tab_id = $_GET['tabid']; //ปีที่ต้องการ
$checkoption = $_GET["op1"];
$contype = $_GET['contype']; //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง

// ============================================================================================
// นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อรอการส่งค่าแบบ GET	
// ============================================================================================
$contypeyear="";
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypechk[$con]!=''){
		if($contypeyear == ""){
			$contypeyear = "\"conType\"='$contypechk[$con]'";
		}else{
			$contypeyear = $contypeyear." OR \"conType\"='$contypechk[$con]'";
		}	
	}
}
if($contypeyear!=""){
	$contypeyear="and ($contypeyear)";
}

IF($checkoption == 'my'){
	$checked1 = "checked";
	$selectMonth = $_GET["month"]; // เดือนที่เลือก
	$selectYear = $_GET["year"]; // ปีที่เลือก
	// where สำหรับหาปี
	$where = " (EXTRACT(MONTH FROM b.\"receiveDate\") = '$selectMonth' and EXTRACT(YEAR FROM b.\"receiveDate\") = '$selectYear') OR
				EXTRACT(MONTH FROM c.\"receiveDate\") = '$selectMonth' and EXTRACT(YEAR FROM c.\"receiveDate\") = '$selectYear'";
	// where สำหรับหารายการรับชำระ
	$wherelist = " EXTRACT(MONTH FROM a.\"receiveDate\") = '$selectMonth' and EXTRACT(YEAR FROM a.\"receiveDate\") = '$selectYear'";

}else if($checkoption == 'y'){
	$checked2 = "checked";
	$selectYear = $_GET["year"]; // ปีที่เลือก
	// where สำหรับหาปี
	$where = " EXTRACT(YEAR FROM b.\"receiveDate\") = '$selectYear' OR 
				EXTRACT(YEAR FROM c.\"receiveDate\") = '$selectYear'";
	// where สำหรับหารายการรับชำระ
	$wherelist = " EXTRACT(YEAR FROM a.\"receiveDate\") = '$selectYear'";
}
	
echo "
<table frame=\"box\" width=\"100%\" align=\"center\" border=\"0\" cellSpacing=\"1\" cellPadding=\"1\" bgcolor=\"#EEEED1\">
	<tr align=\"center\" bgcolor=\"#79BCFF\">
		<th>วันที่รับชำระ</th>
		<th>เลขที่สัญญา</th>
		<th>เลขที่ใบเสร็จ</th>
		<th>จำนวนเงินที่รับชำระ</th>
		<th>เงินต้นรับชำระ</th>
		<th>ดอกเบี้ยรับชำระ</th>
	</tr>
";	
	if($tab_id==0){	
		$sumall=0;
		$sumPriciple=0;
		$sumInterest=0;
		
		// ============================================================================================
		// หาีว่ามีสัญญาของลูกหนี้ปีไหนบ้างที่มีรายการรับชำระ ในเดือนปี หรือ เฉพาะปี ที่ผู้ใช้งานต้องการออกรายงาน 
		// ============================================================================================
		$qry_year=pg_query("
				SELECT DISTINCT(EXTRACT(YEAR FROM \"conDate\")) as \"conyear\" FROM thcap_contract a
				left join thcap_temp_int_201201 b on a.\"contractID\" = b.\"contractID\" AND b.\"isReceiveReal\" = '1'
				left join account.thcap_acc_filease_realize_eff_present c on a.\"contractID\" = c.\"contractID\"
				where $where ORDER BY \"conyear\" ASC
		");
		
		while($resyear=pg_fetch_array($qry_year)){
			list($contractyear)=$resyear;
			
			echo "<tr bgcolor=\"#7AC5CD\" align=\"center\" height=\"30\"><td colspan=7><b>-- ลูกหนี้ปี $contractyear --</b></td></tr>";
			
			//วนตามประเภทสัญญาที่เลือก
			$sumAmountAllcon = 0; // จำนวนเงินที่รับชำระ รวมทั้งหมด ของปีนั้นๆ
			$sumPricipleAllcon = 0; // เงินต้นรับชำระ รวมทั้งหมด ของปีนั้นๆ
			$sumInterestAllcon = 0; // ดอกเบี้ยรับชำระ รวมทั้งหมด ของปีนั้นๆ
			for($con = 0;$con < sizeof($contypechk) ; $con++){
				if($contypechk[$con] != ""){ //หากมีประเภทสัญญาถูกส่งมา
					//แสดงประเภทอยู่ด้านบนข้อมูล
					echo "<tr bgcolor=\"#FFE4B5\"><td colspan=\"8\"><b>ประเภทสัญญา $contypechk[$con]</b></td></tr>";

					$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contypechk[$con]') ");
					$chk_con_type = pg_fetch_result($qry_chk_con_type,0);					
					
					// ============================================================================================
					// ตรวจสอบข้อมูลแยกตามประเภทสัญญา
					// ============================================================================================
					if($chk_con_type == "LOAN" || $chk_con_type == "JOINT_VENTURE" || $chk_con_type == "PERSONAL_LOAN"){
						
						$qry_main=pg_query("SELECT distinct DATE(a.\"receiveDate\") \"DATEE\",a.\"contractID\",a.\"receiptID\",a.\"receiveAmount\",a.\"receivePriciple\",a.\"receiveInterest\"
											FROM \"thcap_temp_int_201201\" a
											LEFT JOIN thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
											where $wherelist and \"isReceiveReal\" = '1' and b.\"contractYear\"='$contractyear' and \"conType\" = '$contypechk[$con]' order by a.\"receiptID\" ");
						$row_main = pg_num_rows($qry_main);
						if($row_main > 0)
						{
							$i = 0;
							$sumAmountAll = 0; // จำนวนเงินที่รับชำระ รวมตามประเภท
							$sumPricipleAll = 0; // เงินต้นรับชำระ รวมตามประเภท
							$sumInterestAll = 0; // ดอกเบี้ยรับชำระ รวมตามประเภท
							
							$relpaths = redirect($_SERVER['PHP_SELF'],'nw/thcap');
							
							while($res = pg_fetch_array($qry_main))
							{
								$i++;
								$receiveDate = $res["DATEE"]; // วันที่รับชำระ
								$contractID = $res["contractID"]; // เลขที่สัญญา
								$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
								$receiveAmount = $res["receiveAmount"]; // จำนวนเงินที่รับชำระ
								$receivePriciple = $res["receivePriciple"]; // เงินต้นรับชำระ
								$receiveInterest = $res["receiveInterest"]; // ดอกเบี้ยรับชำระ
								
								// รวมจำนวนเงินของลูกหนี้ทั้งปี
								$sumAmountAll += $receiveAmount;
								$sumPricipleAll += $receivePriciple;
								$sumInterestAll += $receiveInterest;
								
								// จำนวนรวมของทั้งประเภทสัญญา
								$sumAmountAllcon+= $receiveAmount;
								$sumPricipleAllcon += $receivePriciple;
								$sumInterestAllcon += $receiveInterest;
								
								// จำนวนรวมเงินทั้งหมด
								$sumall += $receiveAmount;
								$sumPriciple += $receivePriciple;
								$sumInterest += $receiveInterest;
								
								if($i%2==0){
									echo "<tr class=\"odd\">";
								}else{
									echo "<tr class=\"even\">";
								}
								
								echo "<td align=\"center\">$receiveDate</td>";
								echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td>";
								echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')\" style=\"cursor: pointer;\"><font color=\"red\"><u>$receiptID</u></font></span></td>";
								echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>";
								echo "<td align=\"right\">".number_format($receivePriciple,2)."</td>";
								echo "<td align=\"right\">".number_format($receiveInterest,2)."</td>";
								echo "</tr>";
							}
							echo "<tr bgcolor=\"#FFCCCC\">";
							echo "<td align=\"right\" colspan=\"3\"><b>รวมเงินสัญญาประเภท $contypechk[$con]</b></td>";
							echo "<td align=\"right\"><b>".number_format($sumAmountAll,2)."</b></td>";
							echo "<td align=\"right\"><b>".number_format($sumPricipleAll,2)."</b></td>";
							echo "<td align=\"right\"><b>".number_format($sumInterestAll,2)."</b></td>";
							echo "</tr>";
						}
						else
						{
							echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"6\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
						}
					}else if($chk_con_type == "HIRE_PURCHASE" OR $chk_con_type == "LEASING"){
						// ตรวจสอบข้อมูลเงินต้นดอกเบี้ยรับของสัญญาประเภท HIRE_PURCHASE หรือ LEASING
						$qry_main = pg_query("
							SELECT distinct DATE(\"receiveDate\") \"DATEE\",a.\"contractID\",\"receiptID\",\"debt_cut\",\"priciple_cut\",\"interest_cut\",\"conType\"
							FROM \"account\".\"thcap_acc_filease_realize_eff_present\" a
							LEFT JOIN \"thcap_lease_contract\" b on a.\"contractID\"=b.\"contractID\"
							WHERE  $wherelist AND b.\"contractYear\"='$contractyear' AND \"conType\"='$contypechk[$con]' order by \"receiptID\" 
						");
						$row_main = pg_num_rows($qry_main);
						if($row_main > 0){
							$i = 0;
							$sumAmountAll = 0; // จำนวนเงินที่รับชำระ รวมตามประเภท
							$sumPricipleAll = 0; // เงินต้นรับชำระ รวมตามประเภท
							$sumInterestAll = 0; // ดอกเบี้ยรับชำระ รวมตามประเภท
									
							$relpaths = redirect($_SERVER['PHP_SELF'],'nw/thcap');
									
							while($res = pg_fetch_array($qry_main))
							{
								$i++;
								$receiveDate = $res["DATEE"]; // วันที่รับชำระ
								$contractID = $res["contractID"]; // เลขที่สัญญา
								$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
								$debt_cut = $res["debt_cut"]; // จำนวนเงินที่รับชำระ
								$priciple_cut = $res["priciple_cut"]; // เงินต้นรับชำระ
								$interest_cut = $res["interest_cut"]; // ดอกเบี้ยรับชำระ
								
								// จำนวนรวมของทั้งประเภทสัญญา
								$sumAmountAll += $debt_cut;
								$sumPricipleAll += $priciple_cut;
								$sumInterestAll += $interest_cut;
								
								// รวมจำนวนเงินของลูกหนี้ทั้งปี
								$sumAmountAllcon += $debt_cut;
								$sumPricipleAllcon += $priciple_cut;
								$sumInterestAllcon += $interest_cut;
								
								// จำนวนรวมเงินทั้งหมด
								$sumall += $debt_cut;
								$sumPriciple += $priciple_cut;
								$sumInterest += $interest_cut;
										
								if($i%2==0){
									echo "<tr class=\"odd\">";
								}else{
									echo "<tr class=\"even\">";
								}
										
								echo "<td align=\"center\">$receiveDate</td>";
								echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td>";
								echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')\" style=\"cursor: pointer;\"><font color=\"red\"><u>$receiptID</u></font></span></td>";
								echo "<td align=\"right\">".number_format($debt_cut,2)."</td>";
								echo "<td align=\"right\">".number_format($priciple_cut,2)."</td>";
								echo "<td align=\"right\">".number_format($interest_cut,2)."</td>";
								echo "</tr>";
							}
							
							echo "<tr bgcolor=\"#FFBBBB\">";
							echo "<td align=\"right\" colspan=\"3\">รวมเงินสัญญาประเภท $contypechk[$con]</td>";
							echo "<td align=\"right\">".number_format($sumAmountAll,2)."</td>";
							echo "<td align=\"right\">".number_format($sumPricipleAll,2)."</td>";
							echo "<td align=\"right\">".number_format($sumInterestAll,2)."</td>";
							echo "</tr>";
						}
						else{
							echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"6\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
						}
					}
				}
			}
			echo "<tr bgcolor=\"#FFBBBB\">";
			echo "<td align=\"right\" colspan=\"3\"><b>รวมจำนวนเงิน ของลูกหนี้ปี $contractyear</b></td>";
			echo "<td align=\"right\"><b>".number_format($sumAmountAllcon,2)."</b></td>";
			echo "<td align=\"right\"><b>".number_format($sumPricipleAllcon,2)."</b></td>";
			echo "<td align=\"right\"><b>".number_format($sumInterestAllcon,2)."</b></td>";
			echo "</tr>";
		}
		echo "<tr bgcolor=\"#7AC5CD\">";
		echo "<td align=\"right\" colspan=\"3\"><b>รวมทั้งหมด</b></td>";
		echo "<td align=\"right\"><b>".number_format($sumall,2)."</b></td>";
		echo "<td align=\"right\"><b>".number_format($sumPriciple,2)."</b></td>";
		echo "<td align=\"right\"><b>".number_format($sumInterest,2)."</b></td>";
		echo "</tr>";
	}else{
		$sumAmountAllcon = 0; // จำนวนเงินที่รับชำระ รวมทั้งหมด
		$sumPricipleAllcon = 0; // เงินต้นรับชำระ รวมทั้งหมด
		$sumInterestAllcon = 0; // ดอกเบี้ยรับชำระ รวมทั้งหมด		
		//วนตามประเภทสัญญาที่เลือก	
		for($con = 0;$con < sizeof($contypechk) ; $con++){
			if($contypechk[$con] != ""){ //หากมีประเภทสัญญาถูกส่งมา
				//แสดงประเภทอยู่ด้านบนข้อมูล
				echo "<tr bgcolor=\"#FFE4B5\"><td colspan=\"8\"><b>ประเภทสัญญา $contypechk[$con]</b></td></tr>";
				
				$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contypechk[$con]') ");
				$chk_con_type = pg_fetch_result($qry_chk_con_type,0);					
					
				// ============================================================================================
				// ตรวจสอบข้อมูลแยกตามประเภทสัญญา
				// ============================================================================================
				if($chk_con_type == "LOAN" || $chk_con_type == "JOINT_VENTURE" || $chk_con_type == "PERSONAL_LOAN"){
					
					$qry_main=pg_query("
						SELECT distinct DATE(a.\"receiveDate\") \"DATEE\",a.\"contractID\",a.\"receiptID\",a.\"receiveAmount\",a.\"receivePriciple\",a.\"receiveInterest\"
						FROM \"thcap_temp_int_201201\" a
						LEFT JOIN thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
						where $wherelist and \"isReceiveReal\" = '1' and b.\"contractYear\"='$tab_id' and \"conType\" = '$contypechk[$con]' order by a.\"receiptID\" 
					");
					$row_main = pg_num_rows($qry_main);
					if($row_main > 0)
					{
						$i = 0;
						$sumAmountAll = 0; // จำนวนเงินที่รับชำระ รวมตามประเภท
						$sumPricipleAll = 0; // เงินต้นรับชำระ รวมตามประเภท
						$sumInterestAll = 0; // ดอกเบี้ยรับชำระ รวมตามประเภท
						
						$relpaths = redirect($_SERVER['PHP_SELF'],'nw/thcap');
						
						while($res = pg_fetch_array($qry_main))
						{
							$i++;
							$receiveDate = $res["DATEE"]; // วันที่รับชำระ
							$contractID = $res["contractID"]; // เลขที่สัญญา
							$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
							$receiveAmount = $res["receiveAmount"]; // จำนวนเงินที่รับชำระ
							$receivePriciple = $res["receivePriciple"]; // เงินต้นรับชำระ
							$receiveInterest = $res["receiveInterest"]; // ดอกเบี้ยรับชำระ
							
							// จำนวนเงินรวมทั้งหมด
							$sumAmountAllcon+= $receiveAmount;
							$sumPricipleAllcon += $receivePriciple;
							$sumInterestAllcon += $receiveInterest;
							
							// จำนวนเงินรวมของแต่ละประเภทสัญญา
							$sumAmountAll += $receiveAmount;
							$sumPricipleAll += $receivePriciple;
							$sumInterestAll += $receiveInterest;											

							if($i%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
							}
							
							echo "<td align=\"center\">$receiveDate</td>";
							echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td>";
							echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')\" style=\"cursor: pointer;\"><font color=\"red\"><u>$receiptID</u></font></span></td>";
							echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>";
							echo "<td align=\"right\">".number_format($receivePriciple,2)."</td>";
							echo "<td align=\"right\">".number_format($receiveInterest,2)."</td>";
							echo "</tr>";
						}
						echo "<tr bgcolor=\"#FFCCCC\">";
						echo "<td align=\"right\" colspan=\"3\"><b>รวมเงินสัญญาประเภท $contypechk[$con]</b></td>";
						echo "<td align=\"right\"><b>".number_format($sumAmountAll,2)."</b></td>";
						echo "<td align=\"right\"><b>".number_format($sumPricipleAll,2)."</b></td>";
						echo "<td align=\"right\"><b>".number_format($sumInterestAll,2)."</b></td>";
						echo "</tr>";
					}
					else
					{
						echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"6\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
					}
				}else if($chk_con_type == "HIRE_PURCHASE" OR $chk_con_type == "LEASING"){
					// ตรวจสอบข้อมูลเงินต้นดอกเบี้ยรับของสัญญาประเภท HIRE_PURCHASE หรือ LEASING
					$qry_main = pg_query("
						SELECT distinct DATE(\"receiveDate\") \"DATEE\",a.\"contractID\",\"receiptID\",\"debt_cut\",\"priciple_cut\",\"interest_cut\",\"conType\"
						FROM \"account\".\"thcap_acc_filease_realize_eff_present\" a
						LEFT JOIN \"thcap_lease_contract\" b on a.\"contractID\"=b.\"contractID\"
						WHERE  $wherelist AND b.\"contractYear\"='$tab_id' AND \"conType\"='$contypechk[$con]' order by \"receiptID\" 
					");
					$row_main = pg_num_rows($qry_main);
					if($row_main > 0){
						$i = 0;
						$sumAmountAll = 0; // จำนวนเงินที่รับชำระ รวมตามประเภท
						$sumPricipleAll = 0; // เงินต้นรับชำระ รวมตามประเภท
						$sumInterestAll = 0; // ดอกเบี้ยรับชำระ รวมตามประเภท
									
						$relpaths = redirect($_SERVER['PHP_SELF'],'nw/thcap');
									
						while($res = pg_fetch_array($qry_main))
						{
							$i++;
							$receiveDate = $res["DATEE"]; // วันที่รับชำระ
							$contractID = $res["contractID"]; // เลขที่สัญญา
							$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
							$debt_cut = $res["debt_cut"]; // จำนวนเงินที่รับชำระ
							$priciple_cut = $res["priciple_cut"]; // เงินต้นรับชำระ
							$interest_cut = $res["interest_cut"]; // ดอกเบี้ยรับชำระ
								
							// รวมจำนวนเงินของลูกหนี้ทั้งปี
							$sumAmountAllcon += $debt_cut;
							$sumPricipleAllcon += $priciple_cut;
							$sumInterestAllcon += $interest_cut;
							
							// จำนวนรวมของทั้งประเภทสัญญา
							$sumAmountAll += $debt_cut;
							$sumPricipleAll += $priciple_cut;
							$sumInterestAll += $interest_cut;
										
							if($i%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
							}
						
							echo "<td align=\"center\">$receiveDate</td>";
							echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td>";
							echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')\" style=\"cursor: pointer;\"><font color=\"red\"><u>$receiptID</u></font></span></td>";
							echo "<td align=\"right\">".number_format($debt_cut,2)."</td>";
							echo "<td align=\"right\">".number_format($priciple_cut,2)."</td>";
							echo "<td align=\"right\">".number_format($interest_cut,2)."</td>";
							echo "</tr>";
						}
							
						echo "<tr bgcolor=\"#FFBBBB\">";
						echo "<td align=\"right\" colspan=\"3\">รวมเงินสัญญาประเภท $contypechk[$con]</td>";
						echo "<td align=\"right\">".number_format($sumAmountAll,2)."</td>";
						echo "<td align=\"right\">".number_format($sumPricipleAll,2)."</td>";
						echo "<td align=\"right\">".number_format($sumInterestAll,2)."</td>";
						echo "</tr>";
					}
					else{
						echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"6\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
					}
				}
			}
		}
		echo "<tr bgcolor=\"#7AC5CD\">";
		echo "<td align=\"right\" colspan=\"3\"><b>รวมทั้งหมด</b></td>";
		echo "<td align=\"right\"><b>".number_format($sumAmountAllcon,2)."</b></td>";
		echo "<td align=\"right\"><b>".number_format($sumPricipleAllcon,2)."</b></td>";
		echo "<td align=\"right\"><b>".number_format($sumInterestAllcon,2)."</b></td>";
		echo "</tr>";
	}
echo "</table>";
?>