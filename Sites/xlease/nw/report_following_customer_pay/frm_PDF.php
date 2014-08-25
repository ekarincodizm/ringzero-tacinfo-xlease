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
$charges = pg_escape_string($_GET["charges"]); // ติดตามค่าใช้จ่ายอื่นๆ ถ้าเลือก จะเป็น checked
$note = pg_escape_string($_GET["note"]); // หมายเหตุ

$CusID_split = split(",", $CusID_array);

$noteShow = str_replace("codeEnter","\r\n",$note);
$noteShow = str_replace("codeSpace"," ",$noteShow);

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

// ถ้าเลือกให้แสดง ติดตามค่างวด หรือ ติดตามค่าใช้จ่ายอื่นๆ
if($annuities == "checked" || $charges == "checked")
{
	// ถ้าเลือกให้แสดง ติดตามค่างวด
	if($annuities == "checked")
	{
		$save_data = "";
		$save_data .= $hp;
		
		for($c=1; $c<=count($CusID_split); $c++)
		{
			$CusID = $CusID_split[$c-1];
			
			// หาชื่อลูกค้า
			$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
			$cusName = pg_fetch_result($qry_cusName,0);
		
			$save_data .= '<table cellpadding="2" cellspacing="0" border="1" width="100%" bgcolor="#000000">
				<tr bgcolor="#FFFFFF" style="font-size:50px;">
					<td colspan="6"><b>ค่างวด ถึงวันที่ '.$focusDate.' ของ '.$cusName.'</b></td>
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
								ORDER BY \"IDNO\", \"DueDate\" ");
				$i = 0;
				$sum_P_MONTH = 0;
				while($res_contract = pg_fetch_array($qry_contract))
				{
					$i++;
					
					$IDNO = $res_contract["IDNO"];
					$DueNo = $res_contract["DueNo"];
					$DueDate = $res_contract["DueDate"];
					
					// หาข้อมูลอื่นๆ
					$qry_other = pg_query("SELECT \"C_REGIS\", \"P_MONTH\", \"C_CARNAME\", \"P_TOTAL\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
					$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
					$P_MONTH = pg_fetch_result($qry_other,1); // งวดล่ะ
					$C_CARNAME = pg_fetch_result($qry_other,2); // ประเภทรภ
					$P_TOTAL = pg_fetch_result($qry_other,3); // จำนนวนงวด
					
					if($C_REGIS == "" || $C_REGIS == "-")
					{
						$C_REGIS = $C_CARNAME;
					}
					
					$save_data .= '<tr bgcolor="#FFFFFF" style="font-size:45px;">
					<td align="center">'.$i.'</td>
					<td align="center">'.$C_REGIS.'</td>
					<td align="center">'.$IDNO.'</td>
					<td align="center">'.$DueDate.'</td>
					<td align="center">'.$DueNo.'/'.$P_TOTAL.'</td>
					<td align="right">'.number_format($P_MONTH,2).'</td>
					</tr>';
					
					$sum_P_MONTH += $P_MONTH;
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
			$save_data .= '<br/><br/><br/>';

			$dataArray[1][$c][1] = $CusID;
			$dataArray[1][$c][2] = $cusName;
			$dataArray[1][$c][3] = $sum_P_MONTH;
		}

		$pdf->AddPage('P');

		$pdf->writeHTML($save_data, true, false, true, false, '');
		
		//----- รวมค่างวด-----//
		$save_data = "";
		$save_data .= $hp;
		$save_data .= '<table cellpadding="2" cellspacing="0" border="1" width="100%" bgcolor="#000000">
			<tr bgcolor="#FFFFFF" style="font-size:50px">
				<td colspan="2"><b>ยอดรวม ค่างวด ถึงวันที่ '.$focusDate.'</b></td>
			</tr>
			<tr bgcolor="#79BCFF" align="center" style="font-size:50px">
				<th>ชื่อ</th>
				<th>จำนวนเงิน</th>
			</tr>';

			$sum_annuities = 0;
			for($d=1; $d<=count($CusID_split); $d++)
			{
				$save_data .= '<tr bgcolor="#FFFFFF" style="font-size:45px;">
				<td align="left">'.$dataArray[1][$d][2].'</td>
				<td align="right">'.number_format($dataArray[1][$d][3],2).'</td>
				</tr>';
				
				$sum_annuities += $dataArray[1][$d][3];
			}
			
			$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
			<td align="right"><b>รวม</b></td>
			<td align="right"><b>'.number_format($sum_annuities,2).'</b></td>
			</tr>
			
			<tr bgcolor="#FFFFFF" style="font-size:45px;">
			<td align="left" colspan="2"><b>หมายเหตุ : </b>'.$noteShow.'</td>
			</tr>
		</table>';

		$pdf->AddPage('P');

		$pdf->writeHTML($save_data, true, false, true, false, '');
	}

	// ถ้าเลือกให้แสดง ติดตามค่าใช้จ่ายอื่นๆ
	if($charges == "checked")
	{
		$save_data = "";
		$save_data .= $hp;
		
		for($c=1; $c<=count($CusID_split); $c++)
		{
			$CusID = $CusID_split[$c-1];
			
			// หาชื่อลูกค้า
			$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
			$cusName = pg_fetch_result($qry_cusName,0);
		
			$save_data .= '<table cellpadding="2" cellspacing="0" border="1" width="100%" bgcolor="#000000">
				<tr bgcolor="#FFFFFF" style="font-size:50px">
					<td colspan="6"><b>ค่าใช้จ่ายอื่นๆ ของ '.$cusName.'</b></td>
				</tr>
				<tr bgcolor="#79BCFF" align="center" style="font-size:50px">
					<th>ลำดับ</th>
					<th>ทะเบียน</th>
					<th>เลขที่สัญญา</th>
					<th>รอบภาษี+มิเตอร์</th>
					<th>ค่าใช้จ่าย</th>
					<th>ยอดเงิน</th>
				</tr>';

				$qry_contract = pg_query("SELECT a.\"IDNO\", a.\"TypeDep\", b.\"TName\", a.\"TaxDueDate\", a.\"CusAmt\"
										FROM carregis.\"CarTaxDue\" a, \"TypePay\" b
										WHERE a.\"TypeDep\" = b.\"TypeID\"
										AND a.\"cuspaid\" = false AND a.\"CusAmt\" > 0
										AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
										
										UNION

										SELECT \"IDNO\", NULL, 'ประกันภัยภาคบังคับ (พรบ.)', \"StartDate\", \"outstanding\"
										FROM insure.\"VInsForceDetail\"
										WHERE \"outstanding\" >= '0.01'
										AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
										
										UNION

										SELECT \"IDNO\", NULL, 'ประกันภัยภาคสมัครใจ', \"StartDate\", \"outstanding\"
										FROM insure.\"VInsUnforceDetail\"
										WHERE \"outstanding\" >= '0.01'
										AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')

										UNION

										SELECT \"IDNO\", NULL, 'ประกันภัยคุ้มครองหนี้', \"StartDate\", \"outstanding\"
										FROM insure.\"VInsLiveDetail\"
										WHERE \"outstanding\" >= '0.01'
										AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')

										ORDER BY 1 ");
				$i = 0;
				$sum_CusAmt = 0;
				while($res_contract = pg_fetch_array($qry_contract))
				{
					$i++;
					
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
					
					$save_data .= '<tr bgcolor="#FFFFFF" style="font-size:45px;">
					<td align="center">'.$i.'</td>
					<td align="center">'.$C_REGIS.'</td>
					<td align="center">'.$IDNO.'</td>
					<td align="center">'.$TaxDueDate.'</td>
					<td align="center">'.$TName.'</td>
					<td align="right">'.number_format($CusAmt,2).'</td>
					</tr>';
					
					$sum_CusAmt += $CusAmt;
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
			$save_data .= '<br/><br/><br/>';

			$dataArray[2][$c][1] = $CusID;
			$dataArray[2][$c][2] = $cusName;
			$dataArray[2][$c][3] = $sum_CusAmt;
		}
		
		$pdf->AddPage('P');

		$pdf->writeHTML($save_data, true, false, true, false, '');
		
		//----- รวมค่าใช้จ่ายอื่นๆ -----//
		$save_data = "";
		$save_data .= $hp;
		$save_data .= '<table cellpadding="2" cellspacing="0" border="1" width="100%" bgcolor="#000000">
			<tr bgcolor="#FFFFFF" style="font-size:50px">
				<td colspan="2"><b>ยอดรวม ค่าใช้จ่ายอื่นๆ</b></td>
			</tr>
			<tr bgcolor="#79BCFF" align="center" style="font-size:50px">
				<th>ชื่อ</th>
				<th>จำนวนเงิน</th>
			</tr>';

			$sum_annuities = 0;
			for($d=1; $d<=count($CusID_split); $d++)
			{
				$save_data .= '<tr bgcolor="#FFFFFF" style="font-size:45px;">
				<td align="left">'.$dataArray[2][$d][2].'</td>
				<td align="right">'.number_format($dataArray[2][$d][3],2).'</td>
				</tr>';
				
				$sum_charges += $dataArray[2][$d][3];
			}
			
			$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
			<td align="right"><b>รวม</b></td>
			<td align="right"><b>'.number_format($sum_charges,2).'</b></td>
			</tr>
			
			<tr bgcolor="#FFFFFF" style="font-size:45px;">
			<td align="left" colspan="2"><b>หมายเหตุ : </b>'.$noteShow.'</td>
			</tr>
		</table>';

		$pdf->AddPage('P');

		$pdf->writeHTML($save_data, true, false, true, false, '');
	}
	
	//---------- รวมทั้งหมด ----------//
		$save_data = "";
		$save_data .= $hp;
		$save_data .= '<table cellpadding="2" cellspacing="0" border="1" width="100%" bgcolor="#000000">
				<tr bgcolor="#FFFFFF" style="font-size:50px">
					<td colspan="4"><b>ยอดรวม ค่างวด ถึงวันที่ '.$focusDate.' และ ค่าใช้จ่ายอื่นๆ ทั้งหมด</b></td>
				</tr>
				<tr bgcolor="#79BCFF" align="center" style="font-size:50px">
					<th>ชื่อ</th>
					<th>ค่างวด</th>
					<th>ค่าใช้จ่ายอื่นๆ</th>
					<th>รวม</th>
				</tr>';
		$sumI = 0; // รวมค่างวดทั้งหมด
		$sumO = 0; // รวมค่าใช้จ่ายอื่นๆทั้งหมด
		$SumAll = 0; // รวมทั้งหมด
		for($d=1; $d<=count($CusID_split); $d++)
		{
			// ชื่อ
			if($dataArray[1][$d][2] != ""){$cusNameSum = $dataArray[1][$d][2];}
			else{$cusNameSum = $dataArray[2][$d][2];}
			
			// รวมของแต่ละคน
			$cusSumAll = $dataArray[1][$d][3] + $dataArray[2][$d][3];

			$save_data .= '<tr bgcolor="#FFFFFF" style="font-size:45px;">
				<td align="left">'.$cusNameSum.'</td>
				<td align="right">'.number_format($dataArray[1][$d][3],2).'</td>
				<td align="right">'.number_format($dataArray[2][$d][3],2).'</td>
				<td align="right">'.number_format($cusSumAll,2).'</td>
			</tr>';
			
			$sumI += $dataArray[1][$d][3]; // รวมค่างวดทั้งหมด
			$sumO += $dataArray[2][$d][3]; // รวมค่าใช้จ่ายอื่นๆทั้งหมด
			$SumAll += $cusSumAll; // รวมทั้งหมด
		}
		
		$save_data .= '<tr bgcolor="#FFCCCC" style="font-size:50px">
				<td align="right"><b>รวมทั้งหมด</b></td>
				<td align="right"><b>'.number_format($sumI,2).'</b></td>
				<td align="right"><b>'.number_format($sumO,2).'</b></td>
				<td align="right"><b>'.number_format($SumAll,2).'</b></td>
			</tr>
			
			<tr bgcolor="#FFFFFF" style="font-size:45px;">
			<td align="left" colspan="4"><b>หมายเหตุ : </b>'.$noteShow.'</td>
			</tr>
		</table>';

		$pdf->AddPage('P');

		$pdf->writeHTML($save_data, true, false, true, false, '');
	//---------- จบ รวมทั้งหมด ----------//
}

// ------------------- จบ PDF -------------------//
$pdf->Output();
?>