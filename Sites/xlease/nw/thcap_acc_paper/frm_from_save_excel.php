<?php
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");

$save_id = pg_escape_string($_GET["save_id"]);

$qry_from_save = pg_query("select save_name, ledger_month, ledger_year from account.thcap_ledger_save_head where save_id = '$save_id' ");
$save_name = pg_result($qry_from_save,0);
$mm = pg_result($qry_from_save,1);
$yy = pg_result($qry_from_save,2);

$chk_all = pg_escape_string($_GET['chk_all']);//กด แสดงทุกสมุดทั้งหมด รวมถึงสมุดที่ไม่ได้ใช้งาน หรือไม่ โดย yes = กด ,no =ไม่กด

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];
$show_yy = $yy+543;

$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', '(THCAP) บัญชีกระดาษทำการ');
$objPHPExcel->getActiveSheet()->SetCellValue('A2', $save_name.' (เดือน '.$mm.' ปี '.$yy.')');
if($chk_all == "yes"){$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'แสดงสมุดทั้งหมด รวมถึงสมุดที่ไม่ได้ใช้งาน ');}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:A6');
$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'ชื่อบัญชี');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B5:B6');
$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'เลขที่บัญชี');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C5:D5');
$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'ยอดยกมา');
$objPHPExcel->getActiveSheet()->SetCellValue('C6', 'เดบิต');
$objPHPExcel->getActiveSheet()->SetCellValue('D6', 'เครดิต');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E5:F5');
$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'งบทดลอง');
$objPHPExcel->getActiveSheet()->SetCellValue('E6', 'เดบิต');
$objPHPExcel->getActiveSheet()->SetCellValue('F6', 'เครดิต');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G5:H5');
$objPHPExcel->getActiveSheet()->SetCellValue('G5', 'งบกำไรขาดทุน');
$objPHPExcel->getActiveSheet()->SetCellValue('G6', 'เดบิต');
$objPHPExcel->getActiveSheet()->SetCellValue('H6', 'เครดิต');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I5:J5');
$objPHPExcel->getActiveSheet()->SetCellValue('I5', 'งบดุล');
$objPHPExcel->getActiveSheet()->SetCellValue('I6', 'เดบิต');
$objPHPExcel->getActiveSheet()->SetCellValue('J6', 'เครดิต');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J6')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->applyFromArray(array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->applyFromArray(array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('G5')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('I5')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('C6')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('D6')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('E6')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('F6')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('G6')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('H6')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('I6')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('J6')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));

$qry = pg_query("SELECT \"accBookserial\",\"accBookID\",\"accBookName\",\"accBookUnit\",\"accBookType\" FROM account.\"all_accBook\" ORDER BY \"accBookID\" ASC");

//หาวัน สิ้นเดือน
$set_mm = (int)$mm;	
$sql_day =  pg_query("SELECT \"gen_numdaysinmonth\"($set_mm,$yy)");
$sql_day =pg_fetch_array($sql_day); 
$yy0=$yy-1;
list($cday)=$sql_day;

$j = 7;
while($res=pg_fetch_array($qry))
{
	$Acserial= $res['accBookserial'];
    $AcID = $res['accBookID'];
    $AcName = $res['accBookName'];
	$accBookUnit = $res['accBookUnit'];
	$accBookType = $res['accBookType']; // ประเภทสมุดบัญชี
	
	$abh_sum=  pg_query("SELECT \"ledger_balance\", \"income_statement\", \"balance_sheet\" from account.\"thcap_ledger_save_detail\" where  \"accBookserial\" ='$Acserial' and \"is_ledgerstatus\" = '1' 
	and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy' and EXTRACT(MONTH FROM \"ledger_stamp\")= '$set_mm' and EXTRACT(DAY FROM \"ledger_stamp\")= '$cday'
	and save_id = '$save_id' ");
	$abh_sum0 =pg_fetch_array($abh_sum); 
	list($abh_sum1, $income_statement, $balance_sheet)=$abh_sum0;
	
	//มีรายการในนอกเหนือหารสรุปแต่ละเดือน
	$abh_list_detail=  pg_query("SELECT count(\"auto_id\") from account.\"thcap_ledger_save_detail\" where  \"accBookserial\" ='$Acserial' and save_id = '$save_id'
	and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy' and \"is_ledgerstatus\"='0'");
	$res_list_detail =pg_fetch_array($abh_list_detail); 
	list($rows_list_detail)=$res_list_detail;	
	
	//ยอดยกมา
	$abh_balance=  pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_save_detail\" where \"accBookserial\" ='$Acserial'
	and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy0' and EXTRACT(MONTH FROM \"ledger_stamp\")= '12' and save_id = '$save_id'
	and \"is_ledgerstatus\"='1'");	
	
	$abh_balance0 =pg_fetch_array($abh_balance); 
	list($abh_balance1)=$abh_balance0;
	
	/*****ถ้า ไม่ได้ติกให้แสดงทุกสมุด และ สรุปของบัญชีในเดือนเป็น 0 และยกยกมาเป็น 0 จะไม่แสดง******/
	if(($chk_all=="no") and (($abh_sum1==0) or ($abh_sum1=="")) and (($abh_balance1==0) or ($abh_balance1==""))
	and (($rows_list_detail==0))){}
    else
	{
		$i = $j - 1;		
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $AcName);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $AcID);
		
		// ยอดยกมา
		if($abh_balance1 == ""){$abh_balance1 = 0;}
		
		if($accBookUnit=='0')
		{
			$sql_abh_balance_unit=  pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_save_detail\" where \"accBookserial\" ='$Acserial'
			and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy0' and EXTRACT(MONTH FROM \"ledger_stamp\")= '12' and save_id = '$save_id'
			and \"is_ledgerstatus\"='2'");
			$sql_abh_balance_unit =pg_fetch_array($sql_abh_balance_unit); 
			list($abh_balance_unit)=$sql_abh_balance_unit;
			if($abh_balance_unit ==''){$abh_balance_unit =0.00;}
			$sum += $abh_balance_unit;
			
			if($abh_balance_unit != "" && $abh_balance_unit < 0)
			{
				$abh_balance_unit *= -1;
				$abh_balance_unit = '('.number_format($abh_balance_unit,2).')';
			}
			else
			{
				$abh_balance_unit = '('.number_format($abh_balance_unit,2).')';
			}
		}
		else
		{
			$abh_balance_unit = "";
		}
		
		if($abh_balance1 >= 0)
		{
			$abh_balance2 = $abh_balance1;
			$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			if($accBookUnit=='0')
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, number_format($abh_balance2,2).$abh_balance_unit);
			}
			else
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $abh_balance2);
			}
			$dr0 += $abh_balance2;
		}
		else
		{
			$abh_balance2 = $abh_balance1 * -1;
			$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			if($accBookUnit=='0')
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, number_format($abh_balance2,2).$abh_balance_unit);
			}
			else
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $abh_balance2);
			}
			$cr0 += $abh_balance2;
		}
		//----------------------
		
		// งบทดลอง
		if($abh_sum1 == ""){$abh_sum1 = 0;}
		if($accBookUnit=='0')
		{
			$sql_abh_balance_unit_sum=  pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_save_detail\" where  \"accBookserial\" ='$Acserial' and \"is_ledgerstatus\" ='2' 
			and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy' and EXTRACT(MONTH FROM \"ledger_stamp\")= '$set_mm' and EXTRACT(DAY FROM \"ledger_stamp\")= '$cday'
			and save_id = '$save_id' ");
			$sql_abh_balance_unit_sum =pg_fetch_array($sql_abh_balance_unit_sum); 
			list($abh_balance_unit_sum)=$sql_abh_balance_unit_sum;
			if($abh_balance_unit_sum ==''){$abh_balance_unit_sum =0;}
			$sum_up += $abh_balance_unit_sum;
			
			if($abh_balance_unit_sum != "" && $abh_balance_unit_sum < 0)
			{
				$abh_balance_unit_sum *= -1;
				$abh_balance_unit_sum = '('.number_format($abh_balance_unit_sum,2).')';
			}
			else
			{
				$abh_balance_unit_sum = '('.number_format($abh_balance_unit_sum,2).')';
			}
		}
		else
		{
			$abh_balance_unit_sum = "";
		}
		
		if($abh_sum1 >= 0)
		{
			$abh_sum2 = $abh_sum1;
			$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			if($accBookUnit=='0')
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, number_format($abh_sum2,2).$abh_balance_unit_sum);
			}
			else
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $abh_sum2);
			}
			$dr1 += $abh_sum2;
		}
		else
		{
			$abh_sum2 = $abh_sum1 * -1;
			$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			if($accBookUnit=='0')
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, number_format($abh_sum2,2).$abh_balance_unit_sum);
			}
			else
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $abh_sum2);
			}
			$cr1 += $abh_sum2;
		}
		//----------------------
		
		// งบกำไรขาดทุน
		if($income_statement != "")
		{
			if($abh_sum1 >= 0)
			{
				$income_statement2 = $income_statement;
				$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $income_statement2);
				$dr2 += $income_statement2;
			}
			else
			{
				$income_statement2 = $income_statement * -1;
				$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $income_statement2);
				$cr2 += $income_statement2;
			}
		}
		elseif($income_statement == "" && $balance_sheet == "")
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, 'ไม่พบข้อมูล');
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, 'ไม่พบข้อมูล');
		}
		//----------------------
		
		// งบดุล
		if($balance_sheet != "")
		{
			if($abh_sum1 >= 0)
			{
				$balance_sheet2 = $balance_sheet;
				$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $balance_sheet2);
				$dr3 += $balance_sheet2;
			}
			else
			{
				$balance_sheet2 = $balance_sheet * -1;
				$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $balance_sheet2);
				$cr3 += $balance_sheet2;
			}
		}
		elseif($income_statement == "" && $balance_sheet == "")
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, 'ไม่พบข้อมูล');
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, 'ไม่พบข้อมูล');
		}
		//----------------------
		
		$j++;
	}
}

$i = $j - 1;
$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, $dr0);
$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, $cr0);
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, $dr1);
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $cr1);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $dr2);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $cr2);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $dr3);
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $cr3);

$j++;

$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->setCellValue('A'.$j, 'กำไรสุทธิ');

if($dr2 > $cr2)
{
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $dr2-$cr2);
}
elseif($cr2 > $dr2)
{
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $cr2-$dr2);
}
else
{
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, 0);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, 0);
}

if($dr3 > $cr3)
{
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $dr3-$cr3);
}
elseif($cr3 > $dr3)
{
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $cr3-$dr3);
}
else
{
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, 0);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, 0);
}

$j++;

$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

if($dr2 > $cr2)
{
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $dr2);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $cr2+($dr2-$cr2));
}
elseif($cr2 > $dr2)
{
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $dr2+($cr2-$dr2));
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $cr2);
}
else
{
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $dr2);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $cr2);
}

if($dr3 > $cr3)
{
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $dr3);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $cr3+($dr3-$cr3));
}
elseif($cr3 > $dr3)
{
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $dr3+($cr3-$dr3));
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $cr3);
}
else
{
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, $dr3);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $cr3);
}

$header = "(THCAP) บัญชีกระดาษทำการ";
$datwshow = 'เดือน '.$mm.' ปี '.$yy;

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle($datwshow);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$header.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>