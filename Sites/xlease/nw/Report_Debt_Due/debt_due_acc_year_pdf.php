<?php
set_time_limit(0);
include("../../config/config.php");
$nowdate = date('Y-m-d H:m:s');
$datepicker = $_GET["datepicker"];
$contype = $_GET['contype']; //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง

// ============================================================================================
//นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อแสดงประเภทสัญญาที่แสดงบนหัวรายงาน
// ============================================================================================
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypetxtshow == ""){
		$contypetxtshow = $contypechk[$con];
	}else{
		$contypetxtshow = $contypetxtshow.",".$contypechk[$con];
	}	
}

// ============================================================================================
// นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อนำไปหาปีลูกหนี้เฉพาะ ประเภทที่เลือก	
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
//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
 
    }
 
}

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (ทางบัญชี)");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker ประเภทสัญญา  $contypetxtshow");
$pdf->MultiCell(290,4,$buss_name,0,'L',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
$pdf->MultiCell(10,8,$buss_name,1,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,8,$buss_name,1,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
$pdf->MultiCell(45,8,$buss_name,1,'C',0);

$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี");
$pdf->MultiCell(50,4,$buss_name,1,'C',0);
	$pdf->SetXY(90,34); 
	$buss_name=iconv('UTF-8','windows-874',"คงค้างชำระ");
	$pdf->MultiCell(25,4,$buss_name,1,'C',0);
	$pdf->SetXY(115,34); 
	$buss_name=iconv('UTF-8','windows-874',"ยังไม่ครบกำหนด");
	$pdf->MultiCell(25,4,$buss_name,1,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี");
$pdf->MultiCell(30,4,$buss_name,1,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระเกิน 5 ปี ขึ้นไป");
$pdf->MultiCell(30,4,$buss_name,1,'C',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
$pdf->MultiCell(30,8,$buss_name,1,'C',0);

$pdf->SetXY(230,30); 
$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
$pdf->MultiCell(30,8,$buss_name,1,'C',0);

$pdf->SetXY(260,30); 
$buss_name=iconv('UTF-8','windows-874',"รวมหนี้คงเหลือทั้งสัญญา");
$pdf->MultiCell(35,8,$buss_name,1,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$cline = 38;
$nub = 1;
$p=1;

// ============================================================================================
// หาีว่ามีสัญญาของลูกหนี้ปีไหนบ้างที่มีรายการรับชำระ ในเดือนปี หรือ เฉพาะปี ที่ผู้ใช้งานต้องการออกรายงาน 
// ============================================================================================
$qry_year=pg_query("
		SELECT 
			DISTINCT(EXTRACT(YEAR FROM \"conDate\")) as \"conyear\" 
		FROM
			thcap_contract 
		WHERE
			\"conDate\"<='$datepicker' AND
			\"thcap_get_all_isSold\"(\"contractID\", '$datepicker') IS NULL AND
			\"thcap_checkcontractcloseddate\"(\"contractID\", '$datepicker') IS NULL $contypeyear
		ORDER BY \"conyear\" ASC
");

// ==========================================================================================
// กำหนดค่าเริ่มต้น ของผลรวม
// ==========================================================================================
$sumall_Overdue = 0;
$sumall_ptMinPay_1 = 0;
$sumall_ptMinPay_2 = 0;
$sumall_ptMinPay_3 = 0;
$sumall_restructure = 0;
$sumall_sue = 0;
$sumall_money_function = 0;

$page=0;

while($resyear=pg_fetch_array($qry_year)){
	list($contractyear)=$resyear;
	$page++;
	if($page>1){
		$pdf->AddPage(); $cline = 38; $nub=1; 

		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (ทางบัญชี) ");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,23);
		$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker ประเภทสัญญา  $contypetxtshow");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);

		$pdf->SetXY(5,23);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30); 
		$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
		$pdf->MultiCell(10,8,$buss_name,1,'C',0);

		$pdf->SetXY(15,30); 
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,8,$buss_name,1,'C',0);

		$pdf->SetXY(45,30); 
		$buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
		$pdf->MultiCell(45,8,$buss_name,1,'C',0);

		$pdf->SetXY(90,30); 
		$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี");
		$pdf->MultiCell(50,4,$buss_name,1,'C',0);
			$pdf->SetXY(90,34); 
			$buss_name=iconv('UTF-8','windows-874',"คงค้างชำระ");
			$pdf->MultiCell(25,4,$buss_name,1,'C',0);
			$pdf->SetXY(115,34); 
			$buss_name=iconv('UTF-8','windows-874',"ยังไม่ครบกำหนด");
			$pdf->MultiCell(25,4,$buss_name,1,'C',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(140,30); 
		$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี");
		$pdf->MultiCell(30,4,$buss_name,1,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(170,30); 
		$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระเกิน 5 ปี ขึ้นไป");
		$pdf->MultiCell(30,4,$buss_name,1,'C',0);

		$pdf->SetXY(200,30); 
		$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
		$pdf->MultiCell(30,8,$buss_name,1,'C',0);

		$pdf->SetXY(230,30); 
		$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
		$pdf->MultiCell(30,8,$buss_name,1,'C',0);

		$pdf->SetXY(260,30); 
		$buss_name=iconv('UTF-8','windows-874',"รวมหนี้คงเหลือทั้งสัญญา");
		$pdf->MultiCell(35,8,$buss_name,1,'C',0);
	}
	
	// ==========================================================================================
	//แสดงปีอยู่ด้านบน
	// ==========================================================================================
	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"ปี $contractyear");
	$pdf->MultiCell(290,5,$buss_name,1,'C',0);
	$cline += 5;
	$nub+=1;
	
	// ==========================================================================================
	// กำหนดค่าเริ่มต้น ของผลรวม ของแต่ละปี
	// ==========================================================================================
	$sumyear_Overdue=0;
	$sumyear_ptMinPay_1=0;
	$sumyear_ptMinPay_2=0;
	$sumyear_ptMinPay_3=0;
	$sumyear_restructure = 0;
	$sumyear_sue = 0;
	$sumyear_money_function=0;
		
	// ==========================================================================================
	// หาวันสำหรับใช้ในเงื่อนไขการแบ่งจำนวนเงินที่ครบกำหนดชำระลงช้องต่างๆ
	// ==========================================================================================
	$nextday = date("Y-m-d", strtotime("+1 day", strtotime($datepicker))); // วันต่อไป
	$nextyear = date("Y-m-d", strtotime("+1 year", strtotime($datepicker))); // ปีต่อไป
	$next_oneyear_oneday = date("Y-m-d", strtotime("+1 day", strtotime($nextyear))); // ถัดไป 1 ปี 1 วัน
	$nextfiveyear = date("Y-m-d", strtotime("+5 year", strtotime($datepicker))); // 5 ปีต่อไป
	$next_fiveyear_oneday = date("Y-m-d", strtotime("+1 day", strtotime($nextfiveyear))); // ถัดไป 5 ปี 1 วัน

	// ==========================================================================================
	// วนแสดงข้อมูลตามประเภทสัญญา
	// ==========================================================================================
	for($con = 0;$con < sizeof($contypechk) ; $con++){
		$sum_Overdue = 0;
		$sum_ptMinPay_1 = 0;
		$sum_ptMinPay_2 = 0;
		$sum_ptMinPay_3 = 0;
		$sum_restructure = 0;
		$sum_sue = 0;
		$sum_money_function = 0;
		
		if($nub > 28)
		{ 
			$pdf->AddPage(); $cline = 38; $nub=1; 

			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (ทางบัญชี)");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,23);
			$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker ประเภทสัญญา  $contypetxtshow");
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,23);
			$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,30); 
			$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
			$pdf->MultiCell(10,8,$buss_name,1,'C',0);

			$pdf->SetXY(15,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,8,$buss_name,1,'C',0);

			$pdf->SetXY(45,30); 
			$buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
			$pdf->MultiCell(45,8,$buss_name,1,'C',0);

			$pdf->SetXY(90,30); 
			$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี");
			$pdf->MultiCell(50,4,$buss_name,1,'C',0);
				$pdf->SetXY(90,34); 
				$buss_name=iconv('UTF-8','windows-874',"คงค้างชำระ");
				$pdf->MultiCell(25,4,$buss_name,1,'C',0);
				$pdf->SetXY(115,34); 
				$buss_name=iconv('UTF-8','windows-874',"ยังไม่ครบกำหนด");
				$pdf->MultiCell(25,4,$buss_name,1,'C',0);

			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(140,30); 
			$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี");
			$pdf->MultiCell(30,4,$buss_name,1,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(170,30); 
			$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระเกิน 5 ปี ขึ้นไป");
			$pdf->MultiCell(30,4,$buss_name,1,'C',0);

			$pdf->SetXY(200,30); 
			$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
			$pdf->MultiCell(30,8,$buss_name,1,'C',0);

			$pdf->SetXY(230,30); 
			$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
			$pdf->MultiCell(30,8,$buss_name,1,'C',0);

			$pdf->SetXY(260,30); 
			$buss_name=iconv('UTF-8','windows-874',"รวมหนี้คงเหลือทั้งสัญญา");
			$pdf->MultiCell(35,8,$buss_name,1,'C',0);
		}
		
		//แสดงประเภทอยู่ด้านบนข้อมูล	
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา $contypechk[$con]");
		$pdf->MultiCell(290,5,$buss_name,1,'L',0);
		$cline += 5;
		$nub+=1;
		
		// ==========================================================================================
		// นำทุกสัญญาขึ้นมา โดยให้ check ว่าวันที่เลือกดังกล่าวปิดบัญชีแล้วหรือไม่ด้วย ให้แสดงเฉพาะสัญญาที่ยังไม่ปิดบัญชี และเป็นสัญญาประเภทที่เลือกและวนถึง
		// ==========================================================================================
		$qry_debt_due = pg_query("SELECT \"contractID\"
					FROM 
						public.\"thcap_contract\" 
					WHERE
						\"conType\" = '$contypechk[$con]' AND
						\"conDate\" <= '$datepicker' AND
						EXTRACT(YEAR FROM \"conDate\")='$contractyear' AND
						\"thcap_checkcontractcloseddate\"(\"contractID\", '$datepicker') IS NULL 
					ORDER BY \"contractID\"
		");
		$row_debt_due = pg_num_rows($qry_debt_due);
		
		// ==========================================================================================
		// กรณีไม่พบข้อมูลที่จะแสดงรายงาน
		// ==========================================================================================
		if($row_debt_due == 0)
		{
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"-----ไม่พบข้อมูล-----");
			$pdf->MultiCell(290,5,$buss_name,1,'C',0);
			$cline += 5;
			$nub+=1;	
		}
		else
		{	
			// ==========================================================================================
			// กรณีพบข้อมูลที่จะแสดงรายงาน
			// ==========================================================================================
			$i = 0;
			while($res = pg_fetch_array($qry_debt_due))
			{	
				$i++;
				$contractID = $res["contractID"];
				
				// ==========================================================================================
				// ล้างค่าของรายการ
				// ==========================================================================================
				$money_function = 0;
				$Overdue = 0;
				$ptMinPay_1 = 0;
				$ptMinPay_2 = 0;
				$ptMinPay_3 = 0;
				$amtrestructure = 0;
				$amtsue = 0;
				
				// ==========================================================================================
				// หาเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก
				// ==========================================================================================
				$inter=pg_query("SELECT \"thcap_amountown\"('$contractID','$datepicker')");
				$resin=pg_fetch_array($inter);
				list($money_function)=$resin;
										
				// ==========================================================================================
				// ถ้า amountown น้อยกว่าหรือเท่ากับ 0 ให้ข้าม loop นี้ไปเลย ให้ไป loop ต่อไปทันที
				// ==========================================================================================
				if($money_function <= 0.00){ $i--; continue; }
					
				// ==========================================================================================
				// ค้นหาชื่อผู้กู้หลัก
				// ==========================================================================================
				$qry_namemain=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
				if($resnamemain=pg_fetch_array($qry_namemain)){
					$name3=trim($resnamemain["thcap_fullname"]);
				}
				else{
					$name3 = ""; // ถ้าไม่พบชื่อลูกค้า ให้เป็นค่าว่าง
				}
				
				// ==========================================================================================
				//หาว่าอยู่ระหว่างดำเนินคดีหรือไม่จาก function "thcap_get_all_isSue" ถ้าได้ TRUE แสดงว่า เป็นระหว่างคดี ถ้าได้ FALSE แสดงว่าไม่อยู่
				// ==========================================================================================
				$qryissue=pg_query("select \"thcap_get_all_isSue\"('$contractID','$datepicker')");
				list($issue)=pg_fetch_array($qryissue);

										
				// ==========================================================================================
				//หาว่าปรับโครงสร้างหรือไม่จาก function "thcap_get_all_isRestructure" ถ้าได้ TRUE แสดงว่า เป็นปรับโครงสร้างหนี้ ถ้าได้ FALSE แสดงว่าไม่อยู่
				// ==========================================================================================
				$qrystructure=pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$datepicker')");
				list($isrestructure)=pg_fetch_array($qrystructure);
										
										
				// ==========================================================================================
				//ตรวจสอบเงื่อนไขว่าเงินอยู่ในช่องใด
				// ==========================================================================================
				if($issue==1 and $isrestructure==1) { // อยู่ระหว่างปรับโครงสร้างหนี้
					$amtrestructure = $money_function;
					
				} else if($issue==1) { // อยู่ระหว่างฟ้อง
					$amtsue = $money_function;
					
				} else { // ลูกหนี้ปกติ
					// ==========================================================================================
					// ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (คงค้างชำระ)
					// ==========================================================================================
					$sql_str_func = pg_query("select \"thcap_get_all_backamt\"('$contractID', '$datepicker',2)");
					$str_func = pg_fetch_array($sql_str_func);
					list($Overdue) = $str_func;

					// ==========================================================================================
					// ตรวจสอบประเภทสัญญาและกำหนด QUERY ที่จะใช้หายอดหนี้ที่จะครบกำหนดชำระ
					// ==========================================================================================
					$sql_tpye_func = pg_query("select \"thcap_get_creditType\"('$contractID')");
					$type_func = pg_fetch_array($sql_tpye_func);
					list($credittype) = $type_func;
					if ($credittype == "LOAN" OR $credittype == "JOINT_VENTURE" OR $credittype == "PERSONAL_LOAN") {
						$queryfind = "select sum(\"ptMinPay\") as \"ptMinPay\" from account.\"thcap_mg_payTerm\"";
					} else if (
								$credittype == "HIRE_PURCHASE" OR $credittype == "LEASING" OR $credittype == "GUARANTEED_INVESTMENT" OR
								$credittype == "FACTORING" OR $credittype == "SALE_ON_CONSIGNMENT" OR $credittype == "PROMISSORY_NOTE") {
						$queryfind = "select sum(\"debtnet\") as \"ptMinPay\" from account.\"thcap_acc_filease_realize_eff_present\"";
					}
					
					// ==========================================================================================
					// ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ)
					// ==========================================================================================
					$qry_ptMinPay_1 = pg_query("$queryfind where \"contractID\" = '$contractID' and \"ptDate\" >= '$nextday' and \"ptDate\" <= '$nextyear' ");
					while($res_ptMinPay_1 = pg_fetch_array($qry_ptMinPay_1))
					{
						$ptMinPay_1 = $res_ptMinPay_1["ptMinPay"];
					}
							
					// ==========================================================================================
					// ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี
					// ==========================================================================================
					$qry_ptMinPay_2 = pg_query("$queryfind where \"contractID\" = '$contractID' and \"ptDate\" >= '$next_oneyear_oneday' and \"ptDate\" <= '$nextfiveyear' ");
					while($res_ptMinPay_2 = pg_fetch_array($qry_ptMinPay_2))
					{
						$ptMinPay_2 = $res_ptMinPay_2["ptMinPay"];
					}
							
					// ==========================================================================================
					// ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 5 ปี ขึ้นไป
					// ==========================================================================================
					$qry_ptMinPay_3 = pg_query("$queryfind where \"contractID\" = '$contractID' and\"ptDate\" >= '$next_fiveyear_oneday' ");
					while($res_ptMinPay_3 = pg_fetch_array($qry_ptMinPay_3))
					{
						$ptMinPay_3 = $res_ptMinPay_3["ptMinPay"];
					}
					
					// ********************************* คำนวณให้ลงรายการยอดจะครบกำหนดอย่างถูกต้อง *********************************

					// ==========================================================================================
					// กำหนดค่าให้รายการที่ไม่มีค่า = 0 (ที่ต้องกำหนดใหม่เนื่องจาก ไปเอาจาก base มา ได้ค่าเป็น null)
					// ==========================================================================================
					if($Overdue=="") 		$Overdue = 0.00;
					if($ptMinPay_1=="") 	$ptMinPay_1 = 0.00;
					if($ptMinPay_2=="") 	$ptMinPay_2 = 0.00;
					if($ptMinPay_3=="") 	$ptMinPay_3 = 0.00;
					if($amtrestructure=="")	$amtrestructure = 0.00;
					if($amtsue=="") 		$amtsue = 0.00;
					if($money_function=="")	$money_function = 0.00;
												
					// ==========================================================================================
					// นำข้อมูลเข้าช่องโดยสำหรับ LOAN นี้ที่ต้องจ่ายต่อปีเท่าเดิม แต่จ่ายล่วงหน้ามีผลหมดเร็วขึ้น แต่สำหรับ HIRE_PURCHASE / LEASING / GUARANTEED_INVESTMENT / FACTORING / SALE_ON_CONSIGNMENT / PROMISSORY_NOTE หนี้คงที่
					// ==========================================================================================
					if ($money_function > $Overdue + $ptMinPay_1 + $ptMinPay_2 && $ptMinPay_1 > 0 && $ptMinPay_2 > 0) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ เกิน 5 ปี
						if ($ptMinPay_3 == 0.00) { // ถ้างวด 3 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบใน ช่วง 2
							$ptMinPay_2 = $money_function - $Overdue  - $ptMinPay_1;
						} else {
							$ptMinPay_3 = $money_function - $Overdue - $ptMinPay_1 - $ptMinPay_2;
						}
					} else if ($money_function > $Overdue + $ptMinPay_1 && $ptMinPay_1 > 0) {  // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ แต่เกิน 1 ปี แต่ไม่ถึง 5 ปี
						if ($ptMinPay_2 == 0.00) { // ถ้างวด 2 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบในงวด ช่วง 1
							$ptMinPay_1 = $money_function - $Overdue;
						} else {
							$ptMinPay_2 = $money_function - $Overdue - $ptMinPay_1;
						}
							$ptMinPay_3 = 0.00;
					} else if ($money_function > $Overdue) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ แต่ไม่เกิน 1 ปี
						if ($ptMinPay_1 == 0.00) { // ถ้างวด 1 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบในงวด ช่วง Overdue
							$Overdue = $money_function;
						} else {
							$ptMinPay_1 = $money_function - $Overdue;
						}
							$ptMinPay_2 = 0.00;
							$ptMinPay_3 = 0.00;
					} else if ($money_function <= $Overdue) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ น้อยกว่าที่ค้างชำระ
							$Overdue = $money_function;
							$ptMinPay_1 = 0.00;
							$ptMinPay_2 = 0.00;
							$ptMinPay_3 = 0.00;
					}
				}

				// ==========================================================================================
				// รวมจำนวนเงินที่จะนำไปแสดง
				// ==========================================================================================
				$sum_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ประเภทสัญญา]
				$sumyear_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ปี]
				$sumall_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ทั้งหมด]
											
				$sum_Overdue += $Overdue; // รวม Overdue ของทั้งประเภทสัญญา
				$sumyear_Overdue += $Overdue; // รวม Overdue ของทั้งหมด
				$sumall_Overdue += $Overdue; // รวม Overdue ของทั้งหมด
											
				$sum_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ประเภทสัญญา]
				$sumyear_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ปี]
				$sumall_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ทั้งหมด]

				$sum_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ประเภทสัญญา]
				$sumyear_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ปี]
				$sumall_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ทั้งหมด]
											
				$sum_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ประเภทสัญญา]
				$sumyear_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ปี]
				$sumall_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ทั้งหมด]
											
				$sum_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ประเภทสัญญา]
				$sumyear_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ปี]
				$sumall_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ทั้งหมด]

				$sum_sue += $amtsue; // รวมฟ้อง [ประเภทสัญญา]
				$sumyear_sue += $amtsue; // รวมฟ้อง [ปี]
				$sumall_sue += $amtsue; // รวมฟ้อง [ทั้งหมด]

				// ==========================================================================================
				// Process ในการตรวจสอบค่า หากมีค่าไม่สอดคล้องในการแสดง ให้เป็น -999
				// ==========================================================================================
				// สาเหตุที่ใช้ postgres ในการรวมค่าเนื่องจาก เมื่อมี Operation เยอะๆ จะเกิด Bug เศษส่วนไกลๆ ทำให้ ไม่ลงตัว
				$pgcal=pg_query("select 
				CASE WHEN ('$Overdue'::numeric(15,2) + '$ptMinPay_1'::numeric(15,2) + '$ptMinPay_2'::numeric(15,2) + '$ptMinPay_3'::numeric(15,2) + '$amtrestructure'::numeric(15,2) + '$amtsue'::numeric(15,2))<>'$money_function'::numeric(15,2) THEN '1' ELSE '0' END as money_function,
				CASE WHEN ('$sum_Overdue'::numeric(15,2) + '$sum_ptMinPay_1'::numeric(15,2) + '$sum_ptMinPay_2'::numeric(15,2) + '$sum_ptMinPay_3'::numeric(15,2) + '$sum_restructure'::numeric(15,2) + '$sum_sue'::numeric(15,2))<>'$sum_money_function'::numeric THEN '1' ELSE '0' END as sum_money_function,
				CASE WHEN ('$sumall_Overdue'::numeric(15,2) + '$sumall_ptMinPay_1'::numeric(15,2) + '$sumall_ptMinPay_2'::numeric(15,2) + '$sumall_ptMinPay_3'::numeric(15,2) + '$sumall_restructure'::numeric(15,2) + '$sumall_sue'::numeric(15,2))<>'$sumall_money_function'::numeric(15,2) THEN '1' ELSE '0' END as sumall_money_function");
				list($cmoney_function,$csum_money_function,$csumyear_money_function,$csumall_money_function)=pg_fetch_array($pgcal);

				if($cmoney_function=='1'){
					$money_function = -999;
				}

				if($csum_money_function=='1'){
					$sum_money_function = -999;
				}

				if($csumyear_money_function=='1'){
					$sumyear_money_function = -999;
				}

				if($csumall_money_function=='1'){
					$sumall_money_function = -999;
				}

				if($nub > 28)
				{ 
					$pdf->AddPage(); $cline = 38; $nub=1; 

					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (ทางบัญชี)");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,23);
					$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker ประเภทสัญญา  $contypetxtshow");
					$pdf->MultiCell(290,4,$buss_name,0,'L',0);

					$pdf->SetXY(5,23);
					$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,30); 
					$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
					$pdf->MultiCell(10,8,$buss_name,1,'C',0);

					$pdf->SetXY(15,30); 
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,8,$buss_name,1,'C',0);

					$pdf->SetXY(45,30); 
					$buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
					$pdf->MultiCell(45,8,$buss_name,1,'C',0);

					$pdf->SetXY(90,30); 
					$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี");
					$pdf->MultiCell(50,4,$buss_name,1,'C',0);
						$pdf->SetXY(90,34); 
						$buss_name=iconv('UTF-8','windows-874',"คงค้างชำระ");
						$pdf->MultiCell(25,4,$buss_name,1,'C',0);
						$pdf->SetXY(115,34); 
						$buss_name=iconv('UTF-8','windows-874',"ยังไม่ครบกำหนด");
						$pdf->MultiCell(25,4,$buss_name,1,'C',0);

					$pdf->SetFont('AngsanaNew','',10);
					$pdf->SetXY(140,30); 
					$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี");
					$pdf->MultiCell(30,4,$buss_name,1,'C',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(170,30); 
					$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระเกิน 5 ปี ขึ้นไป");
					$pdf->MultiCell(30,4,$buss_name,1,'C',0);

					$pdf->SetXY(200,30); 
					$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
					$pdf->MultiCell(30,8,$buss_name,1,'C',0);

					$pdf->SetXY(230,30); 
					$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
					$pdf->MultiCell(30,8,$buss_name,1,'C',0);

					$pdf->SetXY(260,30); 
					$buss_name=iconv('UTF-8','windows-874',"รวมหนี้คงเหลือทั้งสัญญา");
					$pdf->MultiCell(35,8,$buss_name,1,'C',0);
				}

				$pdf->SetFont('AngsanaNew','',12); 
				$pdf->SetXY(5,$cline); 
				$buss_name=iconv('UTF-8','windows-874',$p);
				$pdf->MultiCell(10,5,$buss_name,1,'C',0);

				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(15,$cline); 
				$buss_name=iconv('UTF-8','windows-874',"$contractID");
				$pdf->MultiCell(30,5,$buss_name,1,'C',0);

				$pdf->SetXY(45,$cline); 
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(45,5,$buss_name,1,'L',0);
				$pdf->SetXY(45,$cline); 
				$buss_name=iconv('UTF-8','windows-874',"$name3");
				$pdf->MultiCell(60,5,$buss_name,0,'L',0);
				
					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(90,$cline); 
					$buss_name=iconv('UTF-8','windows-874',number_format($Overdue,2));
					$pdf->MultiCell(25,5,$buss_name,1,'R',0);
					
					$pdf->SetXY(115,$cline); 
					$buss_name=iconv('UTF-8','windows-874',number_format($ptMinPay_1,2));
					$pdf->MultiCell(25,5,$buss_name,1,'R',0);

				$pdf->SetXY(140,$cline); 
				$buss_name=iconv('UTF-8','windows-874',number_format($ptMinPay_2,2));
				$pdf->MultiCell(30,5,$buss_name,1,'R',0);

				$pdf->SetXY(170,$cline); 
				$buss_name=iconv('UTF-8','windows-874',number_format($ptMinPay_3,2));
				$pdf->MultiCell(30,5,$buss_name,1,'R',0);
				
				$pdf->SetXY(200,$cline); 
				$buss_name=iconv('UTF-8','windows-874',number_format($amtrestructure,2));
				$pdf->MultiCell(30,5,$buss_name,1,'R',0);
				
				$pdf->SetXY(230,$cline); 
				$buss_name=iconv('UTF-8','windows-874',number_format($amtsue,2));
				$pdf->MultiCell(30,5,$buss_name,1,'R',0);

				$pdf->SetXY(260,$cline); 
				$buss_name=iconv('UTF-8','windows-874',number_format($money_function,2));
				$pdf->MultiCell(35,5,$buss_name,1,'R',0);

				$cline+=5;	
				$nub+=1;			
				$p++;
			}
			
			if($nub > 28)
			{ 
				$pdf->AddPage(); $cline = 38; $nub=1; 

				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (ทางบัญชี)");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,23);
				$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker ประเภทสัญญา  $contypetxtshow");
				$pdf->MultiCell(290,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,23);
				$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,30); 
				$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
				$pdf->MultiCell(10,8,$buss_name,1,'C',0);

				$pdf->SetXY(15,30); 
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,8,$buss_name,1,'C',0);

				$pdf->SetXY(45,30); 
				$buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
				$pdf->MultiCell(45,8,$buss_name,1,'C',0);

				$pdf->SetXY(90,30); 
				$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี");
				$pdf->MultiCell(50,4,$buss_name,1,'C',0);
					$pdf->SetXY(90,34); 
					$buss_name=iconv('UTF-8','windows-874',"คงค้างชำระ");
					$pdf->MultiCell(25,4,$buss_name,1,'C',0);
					$pdf->SetXY(115,34); 
					$buss_name=iconv('UTF-8','windows-874',"ยังไม่ครบกำหนด");
					$pdf->MultiCell(25,4,$buss_name,1,'C',0);

				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(140,30); 
				$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี");
				$pdf->MultiCell(30,4,$buss_name,1,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(170,30); 
				$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระเกิน 5 ปี ขึ้นไป");
				$pdf->MultiCell(30,4,$buss_name,1,'C',0);

				$pdf->SetXY(200,30); 
				$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
				$pdf->MultiCell(30,8,$buss_name,1,'C',0);

				$pdf->SetXY(230,30); 
				$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
				$pdf->MultiCell(30,8,$buss_name,1,'C',0);

				$pdf->SetXY(260,30); 
				$buss_name=iconv('UTF-8','windows-874',"รวมหนี้คงเหลือทั้งสัญญา");
				$pdf->MultiCell(35,8,$buss_name,1,'C',0);
			}
			
			$pdf->SetFont('AngsanaNew','B',12); 
			$pdf->SetXY(5,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"รวมประเภทสัญญา $contypechk[$con]");
			$pdf->MultiCell(85,5,$buss_name,1,'L',0);

			$pdf->SetXY(90,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_Overdue,2));
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);

			$pdf->SetXY(115,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_ptMinPay_1,2));
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);

			$pdf->SetXY(140,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_ptMinPay_2,2));
			$pdf->MultiCell(30,5,$buss_name,1,'R',0);

			$pdf->SetXY(170,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_ptMinPay_3,2));
			$pdf->MultiCell(30,5,$buss_name,1,'R',0);

			$pdf->SetXY(200,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_restructure,2));
			$pdf->MultiCell(30,5,$buss_name,1,'R',0);

			$pdf->SetXY(230,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_sue,2));
			$pdf->MultiCell(30,5,$buss_name,1,'R',0);
			
			$pdf->SetXY(260,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_money_function,2));
			$pdf->MultiCell(35,5,$buss_name,1,'R',0);
			$cline+=5;
			$nub+=1;
		}
		$p=1; //กำหนดลำดับใหม่เืมื่อวนประเภทสัญญาใหม่
	}
	if($nub > 28)
	{ 
		$pdf->AddPage(); $cline = 38; $nub=1; 

		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (ทางบัญชี)");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,23);
		$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker ประเภทสัญญา  $contypetxtshow");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);

		$pdf->SetXY(5,23);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30); 
		$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
		$pdf->MultiCell(10,8,$buss_name,1,'C',0);

		$pdf->SetXY(15,30); 
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,8,$buss_name,1,'C',0);

		$pdf->SetXY(45,30); 
		$buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
		$pdf->MultiCell(45,8,$buss_name,1,'C',0);

		$pdf->SetXY(90,30); 
		$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี");
		$pdf->MultiCell(50,4,$buss_name,1,'C',0);
			$pdf->SetXY(90,34); 
			$buss_name=iconv('UTF-8','windows-874',"คงค้างชำระ");
			$pdf->MultiCell(25,4,$buss_name,1,'C',0);
			$pdf->SetXY(115,34); 
			$buss_name=iconv('UTF-8','windows-874',"ยังไม่ครบกำหนด");
			$pdf->MultiCell(25,4,$buss_name,1,'C',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(140,30); 
		$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี");
		$pdf->MultiCell(30,4,$buss_name,1,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(170,30); 
		$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระเกิน 5 ปี ขึ้นไป");
		$pdf->MultiCell(30,4,$buss_name,1,'C',0);

		$pdf->SetXY(200,30); 
		$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
		$pdf->MultiCell(30,8,$buss_name,1,'C',0);

		$pdf->SetXY(230,30); 
		$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
		$pdf->MultiCell(30,8,$buss_name,1,'C',0);

		$pdf->SetXY(260,30); 
		$buss_name=iconv('UTF-8','windows-874',"รวมหนี้คงเหลือทั้งสัญญา");
		$pdf->MultiCell(35,8,$buss_name,1,'C',0);
	}
	
	// ==========================================================================================
	// แสดงข้อมูลผลรวมรวมปีทุกประเภทสัญญา
	// ==========================================================================================
	$pdf->SetFont('AngsanaNew','B',12); 
	$pdf->SetXY(5,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"รวมลูกหนี้ปี $contractyear");
	$pdf->MultiCell(85,5,$buss_name,1,'L',0);

	$pdf->SetXY(90,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($sumyear_Overdue,2));
	$pdf->MultiCell(25,5,$buss_name,1,'R',0);

	$pdf->SetXY(115,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($sumyear_ptMinPay_1,2));
	$pdf->MultiCell(25,5,$buss_name,1,'R',0);

	$pdf->SetXY(140,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($sumyear_ptMinPay_2,2));
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);

	$pdf->SetXY(170,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($sumyear_ptMinPay_3,2));
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);

	$pdf->SetXY(200,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($sumyear_restructure,2));
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);

	$pdf->SetXY(230,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($sumyear_sue,2));
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);

	$pdf->SetXY(260,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($sumyear_money_function,2));
	$pdf->MultiCell(35,5,$buss_name,1,'R',0);
	$cline+=5;
	$nub+=1;
}
// ==========================================================================================
// แสดงข้อมูลผลรวมทั้งหมด
// ==========================================================================================
if($nub > 28)
{ 
	$pdf->AddPage(); $cline = 38; $nub=1; 

	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
	$pdf->MultiCell(290,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (ทางบัญชี)");
	$pdf->MultiCell(290,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,23);
	$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker ประเภทสัญญา  $contypetxtshow");
	$pdf->MultiCell(290,4,$buss_name,0,'L',0);

	$pdf->SetXY(5,23);
	$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
	$pdf->MultiCell(290,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,30); 
	$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
	$pdf->MultiCell(10,8,$buss_name,1,'C',0);

	$pdf->SetXY(15,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(30,8,$buss_name,1,'C',0);

	$pdf->SetXY(45,30); 
	$buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
	$pdf->MultiCell(45,8,$buss_name,1,'C',0);

	$pdf->SetXY(90,30); 
	$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี");
	$pdf->MultiCell(50,4,$buss_name,1,'C',0);
		$pdf->SetXY(90,34); 
		$buss_name=iconv('UTF-8','windows-874',"คงค้างชำระ");
		$pdf->MultiCell(25,4,$buss_name,1,'C',0);
		$pdf->SetXY(115,34); 
		$buss_name=iconv('UTF-8','windows-874',"ยังไม่ครบกำหนด");
		$pdf->MultiCell(25,4,$buss_name,1,'C',0);

	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(140,30); 
	$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี");
	$pdf->MultiCell(30,4,$buss_name,1,'C',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(170,30); 
	$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ที่จะครบกำหนด\nชำระเกิน 5 ปี ขึ้นไป");
	$pdf->MultiCell(30,4,$buss_name,1,'C',0);

	$pdf->SetXY(200,30); 
	$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
	$pdf->MultiCell(30,8,$buss_name,1,'C',0);

	$pdf->SetXY(230,30); 
	$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
	$pdf->MultiCell(30,8,$buss_name,1,'C',0);

	$pdf->SetXY(260,30); 
	$buss_name=iconv('UTF-8','windows-874',"รวมหนี้คงเหลือทั้งสัญญา");
	$pdf->MultiCell(35,8,$buss_name,1,'C',0);
}
$pdf->SetFont('AngsanaNew','B',12); 
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(85,5,$buss_name,1,'L',0);

$pdf->SetXY(90,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sumall_Overdue,2));
$pdf->MultiCell(25,5,$buss_name,1,'R',0);

$pdf->SetXY(115,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sumall_ptMinPay_1,2));
$pdf->MultiCell(25,5,$buss_name,1,'R',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sumall_ptMinPay_2,2));
$pdf->MultiCell(30,5,$buss_name,1,'R',0);

$pdf->SetXY(170,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sumall_ptMinPay_3,2));
$pdf->MultiCell(30,5,$buss_name,1,'R',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sumall_restructure,2));
$pdf->MultiCell(30,5,$buss_name,1,'R',0);

$pdf->SetXY(230,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sumall_sue,2));
$pdf->MultiCell(30,5,$buss_name,1,'R',0);

$pdf->SetXY(260,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sumall_money_function,2));
$pdf->MultiCell(35,5,$buss_name,1,'R',0);

$pdf->Output();
?>