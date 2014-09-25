<?php
include("../../config/config.php");
set_time_limit(120);

$id_user = $_SESSION["av_iduser"];
$nowDateTime = nowDateTime();

// หาชื่อพนักงานที่พิมพ์รายงาน
$query_user_fullname = pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\" = '$id_user' ");
$user_fullname = pg_fetch_result($query_user_fullname,0);

$focusDate = pg_escape_string($_GET["focusDate"]); // วันที่สนใจ
$CusID_array = pg_escape_string($_GET["CusID_array"]); // รหัสลูกค้า
$annuities = pg_escape_string($_GET["annuities"]); // ติดตามค่างวด ถ้าเลือก จะเป็น checked
$PRP = pg_escape_string($_GET["PRP"]); // ค่าประกันภัย+พรบ. ถ้าเลือก จะเป็น checked
$miterTax = pg_escape_string($_GET["miterTax"]); // ค่าภาษี+ตรวจมิเตอร์ ถ้าเลือก จะเป็น checked
$charges = pg_escape_string($_GET["charges"]); // ติดตามค่าใช้จ่ายอื่นๆ ถ้าเลือก จะเป็น checked
$note = pg_escape_string($_GET["note"]); // หมายเหตุ

$CusID_split = split(",", $CusID_array);

$noteShow = str_replace("codeEnter","\r\n",$note);
$noteShow = str_replace("codeSpace"," ",$noteShow);

$focusDateText = substr($focusDate,-2,2)."/".substr($focusDate,5,2)."/".substr($focusDate,0,4); // รูปแบบ วว/ดด/ปปปป (ค.ศ.)

// ------------------- PDF -------------------//
//START PDF
include_once('../../Classes/tcpdf/config/lang/eng.php');
include_once('../../Classes/tcpdf/tcpdf.php');
//include_once('../../Classes/tcpdf/tcpdfLegal.php');

//CUSTOM HEADER and FOOTER
class MYPDF extends TCPDF {
    public function Header(){

    }

    public function Footer(){
		$this->SetFont('AngsanaUPC', '', 12);// Set font
		//$this->Line(10, 200, 340, 200);
		$this->MultiCell(50, 0, 'วันที่พิมพ์ '.date('Y-m-d'), 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(155, 5, 'หน้า '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 'R', 0, 0, '', '', true); // แนวตั้ง P
		//$this->MultiCell(245, 5, 'หน้า '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 'R', 0, 0, '', '', true); // แนวนอน L
    }
}

// หัวกระดาษ
$hp = '
<table cellpadding="2" cellspacing="0" border="0" width="100%" style="font-size:36px;">
	<tr style="font-weight:bold;text-align:center;font-size:50px;" >
		<td align="center"><b>รายงานติดตามชำระลูกค้ารายบุคคล(อู่)</b></td>
	</tr>
	<tr>
		<td align="right">ผู้พิมพ์รายงาน :  '.$user_fullname.'</td>
	</tr>
</table>';

// ------------------- ข้อมูล -------------------//
$save_data = "";

// ------------------- PDF -------------------//
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // A4
//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false); // กระดาษยาว

// remove default header/footer
$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(true);

//set margins
$pdf->SetMargins(10, 5, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// set font
$pdf->SetFont('AngsanaUPC', '', 14); //AngsanaUPC  CordiaUPC

// ถ้าเลือกให้แสดง
if($annuities == "checked" || $PRP == "checked" || $miterTax == "checked" || $charges == "checked")
{
	// ถ้าเลือกให้แสดง ติดตามค่างวด
	if($annuities == "checked")
	{
		$save_data = "";
		$save_data .= $hp;
		
		$t1 = 0; // จำนวนรายการทั้งหมด รวมทุกคน ในตาราง ค่างวด
		for($c=1; $c<=count($CusID_split); $c++)
		{
			$CusID = $CusID_split[$c-1];
			
			if($c > 1){$save_data .= '<br/><br/><br/>';} // เว้นระยะห่างของตารางแต่ละคน
			
			// หาชื่อลูกค้า
			$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
			$cusName = pg_fetch_result($qry_cusName,0);
		
			$save_data .= '<table cellpadding="2" cellspacing="0" border="1" width="100%" bgcolor="#000000">
				<tr bgcolor="#FFFFFF" style="font-size:50px;">
					<td colspan="6"><b>ค่างวด ถึงวันที่ '.$focusDateText.' ของ '.$cusName.'</b></td>
				</tr>
				<tr bgcolor="#79BCFF" align="center" style="font-size:50px;">
					<th>ลำดับ</th>
					<th>ทะเบียน</th>
					<th>เลขที่สัญญา</th>
					<th>ครบชำระ</th>
					<th>งวดที่</th>
					<th>งวดละ</th>
				</tr>';

				$qry_contract = pg_query("select \"IDNO\", \"DueNo\", \"DueDate\"
								from  \"VCusPayment\"
								WHERE \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
								AND \"R_Receipt\" is null AND \"DueDate\" <= '$focusDate'
								AND \"IDNO\" IN(select distinct \"IDNO\" from \"Fp\" where \"P_ACCLOSE\" = FALSE)
								ORDER BY \"IDNO\", \"DueDate\" ");
				$i = 0;
				$sum_P_MONTH = 0;
				$IDNO_old = "";
				$C_REGIS_old = "";
				while($res_contract = pg_fetch_array($qry_contract))
				{
					$i++;
					$t1++;
					
					$IDNO = $res_contract["IDNO"];
					$DueNo = $res_contract["DueNo"];
					$DueDate = $res_contract["DueDate"];
					
					$DueDateText = substr($DueDate,-2,2)."/".substr($DueDate,5,2);
					
					// หาข้อมูลอื่นๆ
					$qry_other = pg_query("SELECT \"C_REGIS\", \"P_MONTH\" + \"P_VAT\", \"C_CARNAME\", \"P_TOTAL\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
					$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
					$P_MONTH = pg_fetch_result($qry_other,1); // งวดล่ะ
					$C_CARNAME = pg_fetch_result($qry_other,2); // ประเภทรภ
					$P_TOTAL = pg_fetch_result($qry_other,3); // จำนนวนงวด
					
					if($C_REGIS == "" || $C_REGIS == "-")
					{
						$C_REGIS = $C_CARNAME;
					}
					
					if($IDNO == $IDNO_old && $C_REGIS == $C_REGIS_old)
					{
						$i--;
						$i_text = "";
						$IDNO_text = "";
						$C_REGIS_text = "";
					}
					else
					{
						$i_text = $i;
						$IDNO_text = $IDNO;
						$C_REGIS_text = $C_REGIS;
					}
					
					// ถ้ารายการใดไม่ต้องการพิมพ์ ให้ข้ามไป
					if(pg_escape_string($_GET["P1R$t1"]) != "on")
					{
						if($IDNO != $IDNO_old || $C_REGIS != $C_REGIS_old)
						{
							$i--;
						}
						
						continue;
					}
					
					$save_data .= '<tr bgcolor="#FFFFFF" style="font-size:48px;">
					<td align="center">'.$i_text.'</td>
					<td align="center">'.$C_REGIS_text.'</td>
					<td align="center">'.$IDNO_text.'</td>
					<td align="center">'.$DueDateText.'</td>
					<td align="center">'.$DueNo.'/'.$P_TOTAL.'</td>
					<td align="right">'.number_format($P_MONTH,2).'</td>
					</tr>';
					
					$sum_P_MONTH += $P_MONTH;
					
					$IDNO_old = $IDNO;
					$C_REGIS_old = $C_REGIS;
				}
				
				if($i > 0)
				{
					$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
					<td colspan="5" align="right"><b>รวม</b></td>
					<td colspan="5" align="right"><b>'.number_format($sum_P_MONTH,2).'</b></td>
					</tr>';
				}
				else
				{
					$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
					<td colspan="6" align="center"><b>-- ไม่พบยอดค่าชำระ --</b></td>
					</tr>';
				}

			$save_data .= '</table>';

			$dataArray[1][$c][1] = $CusID;
			$dataArray[1][$c][2] = $cusName;
			$dataArray[1][$c][3] = $sum_P_MONTH;
		}

		$pdf->AddPage('P');
		$pdf->writeHTML($save_data, true, false, true, false, '');
	}
	
	// ถ้าเลือกให้แสดง ค่าประกันภัย+พรบ.
	if($PRP == "checked")
	{
		$save_data = "";
		$save_data .= $hp;
		
		$t2 = 0; // จำนวนรายการทั้งหมด รวมทุกคน ในตาราง ค่าประกันภัย+พรบ.
		for($c=1; $c<=count($CusID_split); $c++)
		{
			$CusID = $CusID_split[$c-1];
			
			if($c > 1){$save_data .= '<br/><br/><br/>';} // เว้นระยะห่างของตารางแต่ละคน
			
			// หาชื่อลูกค้า
			$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
			$cusName = pg_fetch_result($qry_cusName,0);
		
			$save_data .= '<table cellpadding="2" cellspacing="0" border="1" width="100%" bgcolor="#000000">
				<tr bgcolor="#FFFFFF" style="font-size:50px">
					<td colspan="7"><b>ค่าประกันภัย+พรบ. ถึงวันที่ '.$focusDateText.' ของ '.$cusName.'</b></td>
				</tr>
				<tr bgcolor="#79BCFF" align="center" style="font-size:50px">
					<th width="7%">ลำดับ</th>
					<th width="14%">วันที่ครบรอบ</th>
					<th width="14%">ภาษี+พรบ.</th>
					<th width="13%">ทะเบียน</th>
					<th width="18%">เลขที่สัญญา</th>
					<th width="15%">ยอดเงิน</th>
					<th width="19%">หมายเหตุ</th>
				</tr>';

				$qry_contract = pg_query("SELECT \"IDNO\",
											'ค่าประกันภัย+พรบ.' AS \"TName\",
											\"StartDate\",
											sum(\"outstanding\") AS \"outstanding\",
											sum(\"whereFrom\") AS \"whereFrom\",
											CASE WHEN sum(\"whereFrom\") = 1 THEN 'ไม่รวมประกันภัย'
											ELSE
												CASE WHEN sum(\"whereFrom\") = 2 THEN 'ไม่รวมพรบ.'
													ELSE NULL
												END
											END AS \"note\"
										FROM
										(
											-- ประกันภัยภาคบังคับ(พรบ.)
											SELECT \"IDNO\", 'พรบ.' AS \"TName\", \"StartDate\", ceil(\"outstanding\") AS \"outstanding\", 1::integer AS \"whereFrom\"
											FROM insure.\"VInsForceDetail\"
											WHERE \"outstanding\" >= '0.01'
											AND \"StartDate\" <= '$focusDate'
											AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
											
											UNION

											-- ประกันภัยภาคสมัครใจ
											SELECT \"IDNO\", 'ประกันภัยรถ' AS \"TName\", \"StartDate\", ceil(\"outstanding\") AS \"outstanding\", 2::integer AS \"whereFrom\"
											FROM insure.\"VInsUnforceDetail\"
											WHERE \"outstanding\" >= '0.01'
											AND \"StartDate\" <= '$focusDate'
											AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')

											ORDER BY 1, 4
										) AS \"tableTemp\"
										GROUP BY 1, 3
										ORDER BY 1, 3 ");
				$i = 0;
				$sum_CusAmt = 0;
				$IDNO_old = "";
				$C_REGIS_old = "";
				while($res_contract = pg_fetch_array($qry_contract))
				{
					$i++;
					$t2++;
					
					$IDNO = $res_contract["IDNO"]; // เลขที่สัญญา
					$TName = $res_contract["TName"]; // ชื่อค่าใช้จ่าย
					$StartDate = $res_contract["StartDate"]; // วันที่เริ่มประกันภัย หรือ วันที่เริ่มพรบ.
					$CusAmt = $res_contract["outstanding"]; // จำนวนเงิน
					$whereFrom = $res_contract["whereFrom"]; // 1 เฉพาะรายการ พรบ. / 2 เฉพาะรายการ ประกันภัยรถ / 3 รวมกันทั้ง 2 รายการ
							$note = $res_contract["note"]; // หมายเหตุ
					
					// หาข้อมูลอื่นๆ
					$qry_other = pg_query("SELECT \"C_REGIS\", \"C_CARNAME\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
					$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
					$C_CARNAME = pg_fetch_result($qry_other,1); // ประเภทรภ
					
					if($C_REGIS == "" || $C_REGIS == "-")
					{
						$C_REGIS = $C_CARNAME;
					}
					
					// วันที่ รูปแบบ วว/ดด/ปปป
					$StartDateText = substr($StartDate,-2,2)."/".substr($StartDate,5,2)."/".substr($StartDate,0,4);
					
					// วันที่คุ้มครอง
					if($whereFrom != 1) // ถ้าไม่ได้เป็น พรบ. เพียงอย่างเดียว
					{
						$StartDate_K = $StartDateText;
					}
					else
					{
						$StartDate_K = "";
					}
					
					// ภาษี+พรบ.
					if($whereFrom != 2) // ถ้าไม่ได้เป็น ประกันภัย เพียงอย่างเดียว
					{
						$StartDate_P = $StartDateText;
					}
					else
					{
						$StartDate_P = "";
					}
					
					if($IDNO == $IDNO_old && $C_REGIS == $C_REGIS_old)
					{
						$i--;
						$i_text = "";
						$IDNO_text = "";
						$C_REGIS_text = "";
					}
					else
					{
						$i_text = $i;
						$IDNO_text = $IDNO;
						$C_REGIS_text = $C_REGIS;
					}
					
					// ถ้ารายการใดไม่ต้องการพิมพ์ ให้ข้ามไป
					if(pg_escape_string($_GET["P2R$t2"]) != "on")
					{
						if($IDNO != $IDNO_old || $C_REGIS != $C_REGIS_old)
						{
							$i--;
						}
						
						continue;
					}
					
					$save_data .= '<tr bgcolor="#FFFFFF" style="font-size:48px;">
					<td align="center">'.$i_text.'</td>
					<td align="center">'.$StartDate_K.'</td>
					<td align="center">'.$StartDate_P.'</td>
					<td align="center">'.$C_REGIS_text.'</td>
					<td align="center">'.$IDNO_text.'</td>
					<td align="right">'.number_format($CusAmt,2).'</td>
					<td align="center">'.$note.'</td>
					</tr>';
					
					$sum_CusAmt += $CusAmt;
					
					$IDNO_old = $IDNO;
					$C_REGIS_old = $C_REGIS;
				}
				
				if($i > 0)
				{
					$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
					<td colspan="5" align="right"><b>รวม</b></td>
					<td align="right"><b>'.number_format($sum_CusAmt,2).'</b></td>
					<td></td>
					</tr>';
				}
				else
				{
					$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
					<td colspan="7" align="center"><b>-- ไม่พบยอดค่าชำระ --</b></td>
					</tr>';
				}

			$save_data .= '</table>';

			$dataArray[2][$c][1] = $CusID;
			$dataArray[2][$c][2] = $cusName;
			$dataArray[2][$c][3] = $sum_CusAmt;
		}
		
		$pdf->AddPage('P');
		$pdf->writeHTML($save_data, true, false, true, false, '');
	}
	
	// ถ้าเลือกให้แสดง ค่าภาษี+ตรวจมิเตอร์
	if($miterTax == "checked")
	{
		$save_data = "";
		$save_data .= $hp;
		
		$t3 = 0; // จำนวนรายการทั้งหมด รวมทุกคน ในตาราง ค่าภาษี+ตรวจมิเตอร์
		for($c=1; $c<=count($CusID_split); $c++)
		{
			$CusID = $CusID_split[$c-1];
			
			if($c > 1){$save_data .= '<br/><br/><br/>';} // เว้นระยะห่างของตารางแต่ละคน
			
			// หาชื่อลูกค้า
			$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
			$cusName = pg_fetch_result($qry_cusName,0);
		
			$save_data .= '<table cellpadding="2" cellspacing="0" border="1" width="100%" bgcolor="#000000">
				<tr bgcolor="#FFFFFF" style="font-size:50px">
					<td colspan="6"><b>ค่าภาษี+ตรวจมิเตอร์ ถึงวันที่ '.$focusDateText.' ของ '.$cusName.'</b></td>
				</tr>
				<tr bgcolor="#79BCFF" align="center" style="font-size:50px">
					<th width="10%">ลำดับ</th>
					<th width="15%">ทะเบียน</th>
					<th width="20%">เลขที่สัญญา</th>
					<th width="25%">ค่าใช้จ่าย</th>
					<th width="15%">วันที่ครบรอบ</th>
					<th width="15%">ยอดเงิน</th>
				</tr>';

				$qry_contract = pg_query("SELECT a.\"IDNO\", a.\"TypeDep\", b.\"TName\", a.\"TaxDueDate\", a.\"CusAmt\"
										FROM carregis.\"CarTaxDue\" a, \"TypePay\" b
										WHERE a.\"TypeDep\" = b.\"TypeID\"
										AND a.\"cuspaid\" = false AND a.\"CusAmt\" > 0
										AND a.\"TaxDueDate\" <= '$focusDate'
										AND a.\"TypeDep\" IN('101', '105')
										AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')

										ORDER BY 1, 4 ");
				$i = 0;
				$sum_CusAmt = 0;
				$IDNO_old = "";
				$C_REGIS_old = "";
				while($res_contract = pg_fetch_array($qry_contract))
				{
					$i++;
					$t3++;
					
					$IDNO = $res_contract["IDNO"]; // เลขที่สัญญา
					$TName = $res_contract["TName"]; // ชื่อค่าใช้จ่าย
					$TaxDueDate = $res_contract["TaxDueDate"]; // วันครอบกำหนดตรวจมิเตอร์หรือภาษีรถ
					$CusAmt = $res_contract["CusAmt"]; // จำนวนเงิน
					
					// หาข้อมูลอื่นๆ
					$qry_other = pg_query("SELECT \"C_REGIS\", \"C_CARNAME\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
					$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
					$C_CARNAME = pg_fetch_result($qry_other,1); // ประเภทรภ
					
					if($C_REGIS == "" || $C_REGIS == "-")
					{
						$C_REGIS = $C_CARNAME;
					}
					
					// ถ้าเป็น "ตรวจมิเตอร์" ไม่ต้องแสดงวันที่ครบกำหนด
					if($TName == "ตรวจมิเตอร์")
					{
						$TaxDueDateText = "";
					}
					else
					{
						$TaxDueDateText = substr($TaxDueDate,-2,2)."/".substr($TaxDueDate,5,2)."/".substr($TaxDueDate,0,4);
					}
					
					if($IDNO == $IDNO_old && $C_REGIS == $C_REGIS_old)
					{
						$i--;
						$i_text = "";
						$IDNO_text = "";
						$C_REGIS_text = "";
					}
					else
					{
						$i_text = $i;
						$IDNO_text = $IDNO;
						$C_REGIS_text = $C_REGIS;
					}
					
					// ถ้ารายการใดไม่ต้องการพิมพ์ ให้ข้ามไป
					if(pg_escape_string($_GET["P3R$t3"]) != "on")
					{
						if($IDNO != $IDNO_old || $C_REGIS != $C_REGIS_old)
						{
							$i--;
						}
						
						continue;
					}
					
					$save_data .= '<tr bgcolor="#FFFFFF" style="font-size:48px;">
					<td align="center">'.$i_text.'</td>
					<td align="center">'.$C_REGIS_text.'</td>
					<td align="center">'.$IDNO_text.'</td>
					<td align="center">'.$TName.'</td>
					<td align="center">'.$TaxDueDateText.'</td>
					<td align="right">'.number_format($CusAmt,2).'</td>
					</tr>';
					
					$sum_CusAmt += $CusAmt;
					
					$IDNO_old = $IDNO;
					$C_REGIS_old = $C_REGIS;
				}
				
				if($i > 0)
				{
					$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
					<td colspan="5" align="right"><b>รวม</b></td>
					<td colspan="5" align="right"><b>'.number_format($sum_CusAmt,2).'</b></td>
					</tr>';
				}
				else
				{
					$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
					<td colspan="6" align="center"><b>-- ไม่พบยอดค่าชำระ --</b></td>
					</tr>';
				}

			$save_data .= '</table>';

			$dataArray[3][$c][1] = $CusID;
			$dataArray[3][$c][2] = $cusName;
			$dataArray[3][$c][3] = $sum_CusAmt;
		}
		
		$pdf->AddPage('P');
		$pdf->writeHTML($save_data, true, false, true, false, '');
	}

	// ถ้าเลือกให้แสดง ติดตามค่าใช้จ่ายอื่นๆ
	if($charges == "checked")
	{
		$save_data = "";
		$save_data .= $hp;
		
		$t4 = 0; // จำนวนรายการทั้งหมด รวมทุกคน ในตาราง ค่าใช้จ่ายอื่นๆ
		for($c=1; $c<=count($CusID_split); $c++)
		{
			$CusID = $CusID_split[$c-1];
			
			if($c > 1){$save_data .= '<br/><br/><br/>';} // เว้นระยะห่างของตารางแต่ละคน
			
			// หาชื่อลูกค้า
			$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
			$cusName = pg_fetch_result($qry_cusName,0);
		
			$save_data .= '<table cellpadding="2" cellspacing="0" border="1" width="100%" bgcolor="#000000">
				<tr bgcolor="#FFFFFF" style="font-size:50px">
					<td colspan="6"><b>ค่าใช้จ่ายอื่นๆ ถึงวันที่ '.$focusDateText.' ของ '.$cusName.'</b></td>
				</tr>
				<tr bgcolor="#79BCFF" align="center" style="font-size:50px">
					<th width="10%">ลำดับ</th>
					<th width="15%">ทะเบียน</th>
					<th width="20%">เลขที่สัญญา</th>
					<th width="25%">ค่าใช้จ่าย</th>
					<th width="15%">วันที่ครบรอบ</th>
					<th width="15%">ยอดเงิน</th>
				</tr>';

				$qry_contract = pg_query("SELECT a.\"IDNO\", a.\"TypeDep\", b.\"TName\", a.\"TaxDueDate\", a.\"CusAmt\"
										FROM carregis.\"CarTaxDue\" a, \"TypePay\" b
										WHERE a.\"TypeDep\" = b.\"TypeID\"
										AND a.\"cuspaid\" = false AND a.\"CusAmt\" > 0
										AND a.\"TaxDueDate\" <= '$focusDate'
										AND a.\"TypeDep\" NOT IN('101', '105')
										AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
										
										UNION

										SELECT \"IDNO\", NULL, 'ประกันภัยคุ้มครองหนี้', \"StartDate\", \"outstanding\"
										FROM insure.\"VInsLiveDetail\"
										WHERE \"outstanding\" >= '0.01'
										AND \"StartDate\" <= '$focusDate'
										AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')

										ORDER BY 1, 4 ");
				$i = 0;
				$sum_CusAmt = 0;
				$IDNO_old = "";
				$C_REGIS_old = "";
				while($res_contract = pg_fetch_array($qry_contract))
				{
					$i++;
					$t4++;
					
					$IDNO = $res_contract["IDNO"]; // เลขที่สัญญา
					$TName = $res_contract["TName"]; // ชื่อค่าใช้จ่าย
					$TaxDueDate = $res_contract["TaxDueDate"]; // วันครอบกำหนดตรวจมิเตอร์หรือภาษีรถ
					$CusAmt = $res_contract["CusAmt"]; // จำนวนเงิน
					
					// หาข้อมูลอื่นๆ
					$qry_other = pg_query("SELECT \"C_REGIS\", \"C_CARNAME\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
					$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
					$C_CARNAME = pg_fetch_result($qry_other,1); // ประเภทรภ
					
					if($C_REGIS == "" || $C_REGIS == "-")
					{
						$C_REGIS = $C_CARNAME;
					}
					
					// ถ้าเป็น "ตรวจมิเตอร์" ไม่ต้องแสดงวันที่ครบกำหนด
					if($TName == "ตรวจมิเตอร์")
					{
						$TaxDueDateText = "";
					}
					else
					{
						$TaxDueDateText = substr($TaxDueDate,-2,2)."/".substr($TaxDueDate,5,2)."/".substr($TaxDueDate,0,4);
					}
					
					if($IDNO == $IDNO_old && $C_REGIS == $C_REGIS_old)
					{
						$i--;
						$i_text = "";
						$IDNO_text = "";
						$C_REGIS_text = "";
					}
					else
					{
						$i_text = $i;
						$IDNO_text = $IDNO;
						$C_REGIS_text = $C_REGIS;
					}
					
					// ถ้ารายการใดไม่ต้องการพิมพ์ ให้ข้ามไป
					if(pg_escape_string($_GET["P4R$t4"]) != "on")
					{
						if($IDNO != $IDNO_old || $C_REGIS != $C_REGIS_old)
						{
							$i--;
						}
						
						continue;
					}
					
					$save_data .= '<tr bgcolor="#FFFFFF" style="font-size:48px;">
					<td align="center">'.$i_text.'</td>
					<td align="center">'.$C_REGIS_text.'</td>
					<td align="center">'.$IDNO_text.'</td>
					<td align="center">'.$TName.'</td>
					<td align="center">'.$TaxDueDateText.'</td>
					<td align="right">'.number_format($CusAmt,2).'</td>
					</tr>';
					
					$sum_CusAmt += $CusAmt;
					
					$IDNO_old = $IDNO;
					$C_REGIS_old = $C_REGIS;
				}
				
				if($i > 0)
				{
					$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
					<td colspan="5" align="right"><b>รวม</b></td>
					<td colspan="5" align="right"><b>'.number_format($sum_CusAmt,2).'</b></td>
					</tr>';
				}
				else
				{
					$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
					<td colspan="6" align="center"><b>-- ไม่พบยอดค่าชำระ --</b></td>
					</tr>';
				}

			$save_data .= '</table>';

			$dataArray[4][$c][1] = $CusID;
			$dataArray[4][$c][2] = $cusName;
			$dataArray[4][$c][3] = $sum_CusAmt;
		}
		
		$pdf->AddPage('P');
		$pdf->writeHTML($save_data, true, false, true, false, '');
	}
	
	// แสดงตารางผลรวมทั้งหมด
	if($annuities == "checked" || $PRP == "checked" || $miterTax == "checked" || $charges == "checked")
	{
		$columnData = 0; // จำนวน column ของข้อมูลที่เลือก
		if($annuities == "checked"){$columnData++;}
		if($PRP == "checked"){$columnData++;}
		if($miterTax == "checked"){$columnData++;}
		if($charges == "checked"){$columnData++;}
		
		// จำนวน column ที่แสดง
		if($columnData > 1){$columnSum = $columnData + 2;}
		else{$columnSum = $columnData + 1;}
		
		//---------- รวมทั้งหมด ----------//
		$save_data = "";
		$save_data .= $hp;
		$save_data .= '<table cellpadding="2" cellspacing="0" border="1" width="100%" bgcolor="#000000">
				<tr bgcolor="#FFFFFF" style="font-size:50px">
					<td colspan="'.$columnSum.'"><b>ยอดรวม ถึงวันที่ '.$focusDateText.'</b></td>
				</tr>
				<tr bgcolor="#79BCFF" align="center" style="font-size:45px">
					<th>ชื่อ</th>';
					if($annuities == "checked"){$save_data .= '<th>ค่างวด</th>';}
					if($PRP == "checked"){$save_data .= '<th>ค่าประกันภัย+พรบ.</th>';}
					if($miterTax == "checked"){$save_data .= '<th>ค่าภาษี+ตรวจมิเตอร์</th>';}
					if($charges == "checked"){$save_data .= '<th>ค่าใช้จ่ายอื่นๆ</th>';}
					if($columnData > 1){$save_data .= '<th>รวม</th>';} // ถ้าเลือกมากกว่า 1 หัวข้อ ให้แสดงช่องรวมด้านหลังด้วย
		$save_data .= '</tr>';
		
		$sumI = 0; // รวมค่างวดทั้งหมด
		$sumP = 0; // รวม ค่าประกันภัย+พรบ. ทั้งหมด
		$sumM = 0; // รวม ค่าภาษี+ตรวจมิเตอร์ ทั้งหมด
		$sumO = 0; // รวมค่าใช้จ่ายอื่นๆทั้งหมด
		$SumAll = 0; // รวมทั้งหมด
		for($d=1; $d<=count($CusID_split); $d++)
		{
			// ชื่อ
			if($dataArray[1][$d][2] != ""){$cusNameSum = $dataArray[1][$d][2];}
			elseif($dataArray[2][$d][2] != ""){$cusNameSum = $dataArray[2][$d][2];}
			elseif($dataArray[3][$d][2] != ""){$cusNameSum = $dataArray[3][$d][2];}
			elseif($dataArray[4][$d][2] != ""){$cusNameSum = $dataArray[4][$d][2];}
			
			// รวมของแต่ละคน
			$cusSumAll = $dataArray[1][$d][3] + $dataArray[2][$d][3] + $dataArray[3][$d][3] + $dataArray[4][$d][3];

			$save_data .= '<tr bgcolor="#FFFFFF" style="font-size:48px;">
				<td align="left">'.$cusNameSum.'</td>';
				if($annuities == "checked"){$save_data .= '<td align="right">'.number_format($dataArray[1][$d][3],2).'</td>';}
				if($PRP == "checked"){$save_data .= '<td align="right">'.number_format($dataArray[2][$d][3],2).'</td>';}
				if($miterTax == "checked"){$save_data .= '<td align="right">'.number_format($dataArray[3][$d][3],2).'</td>';}
				if($charges == "checked"){$save_data .= '<td align="right">'.number_format($dataArray[4][$d][3],2).'</td>';}
				if($columnData > 1){$save_data .= '<td align="right">'.number_format($cusSumAll,2).'</td>';}
			$save_data .= '</tr>';
			
			$sumI += $dataArray[1][$d][3]; // รวมค่างวดทั้งหมด
			$sumP += $dataArray[2][$d][3]; // รวมค่าประกันภัย+พรบ. ทั้งหมด
			$sumM += $dataArray[3][$d][3]; // รวมค่าภาษี+ตรวจมิเตอร์ ทั้งหมด
			$sumO += $dataArray[4][$d][3]; // รวมค่าใช้จ่ายอื่นๆทั้งหมด
			$SumAll += $cusSumAll; // รวมทั้งหมด
		}
		
		$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
				<td align="right"><b>รวมทั้งหมด</b></td>';
				if($annuities == "checked"){$save_data .= '<td align="right"><b>'.number_format($sumI,2).'</b></td>';}
				if($PRP == "checked"){$save_data .= '<td align="right"><b>'.number_format($sumP,2).'</b></td>';}
				if($miterTax == "checked"){$save_data .= '<td align="right"><b>'.number_format($sumM,2).'</b></td>';}
				if($charges == "checked"){$save_data .= '<td align="right"><b>'.number_format($sumO,2).'</b></td>';}
				if($columnData > 1){$save_data .= '<td align="right"><b>'.number_format($SumAll,2).'</b></td>';}
		$save_data .= '</tr>
			
			<tr bgcolor="#FFFFFF" style="font-size:48px;">
			<td align="left" colspan="'.$columnSum.'"><b>หมายเหตุ : </b>'.$noteShow.'</td>
			</tr>';
		
		$save_data .= '</table>';

		$pdf->AddPage('P');
		$pdf->writeHTML($save_data, true, false, true, false, '');
		//---------- จบ รวมทั้งหมด ----------//
	}
}

// ------------------- จบ PDF -------------------//
$pdf->Output();
?>