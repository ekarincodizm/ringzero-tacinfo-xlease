<?php
session_start();
include("../../config/config.php");
include("../../core/core_functions.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$contractID = $_POST["idno_text"];
if($contractID == ""){$contractID = $_GET["idno"];}
$vfocusdate = nowDate();

//ถ้ามียอดปิดบัญชี
$signDate = $_GET["signDate"]; //วันที่เลือก
$damage = $_GET["damage"]; //รวมค่าเสียหายปิดบัญชีก่อนกำหนด ถ้าเลือกจะเป็น on
$costclose = $_GET["costclose"]; //รวมค่าบริการปิดบัญชี ถ้าเลือกจะเป็น on
//---------

//----- ส่วนของหัวกระดาษ
$sql_head=pg_query("select * from \"vthcap_contract_creditline\" where \"contractID\" = '$contractID'");
while($resultH=pg_fetch_array($sql_head))
{
	$conIntRate = $resultH["conLoanIniRate"]; // INT.ปกติ (ดอกเบี้ยเริ่มแรก)
	$conMaxRate = $resultH["conLoanMaxRate"]; // INT.ผิดนัด (ดอกเบี้ยสูงสุด)
	$conDate = $resultH["conDate"]; //วันทำสัญญา
	$conCredit = $resultH["conCredit"]; //วงเงินสินเชื่อ
}
//-----


//หาชื่อผู้กู้หลัก (บางกรณีมีได้หลายคน)
$qry_cus0=pg_query("select * from \"vthcap_ContactCus_detail\"
where  \"contractID\" = '$contractID' and \"CusState\" = '0'");
$numcus0=pg_num_rows($qry_cus0);
$i=1;
$name3="";
			
while($resadd=pg_fetch_array($qry_cus0)){
	$cus0=trim($resadd["thcap_fullname"]);
	if($numcus0==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		$name3=$cus0;
	}else{
		if($i==$numcus0){
			$name3=$name3.$cus0;
		}else{
			$name3=$name3.$cus0.", ";
		}
	}
	$i++;
}

//หาชื่อผู้กู้ร่วม
$qry_cus1=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\" = '$contractID' and \"CusState\" = '1'");
$numcus1=pg_num_rows($qry_cus1);
$i=1;
$nameco="";
while($resco=pg_fetch_array($qry_cus1)){
	$cus1=trim($resco["thcap_fullname"]);
	if($numcus1==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		$nameco=$cus1;
	}else{
		if($i==$numcus1){
			$nameco=$nameco.$cus1;
		}else{
			$nameco=$nameco.$cus1.", ";
		}
	}
	$i++;
}
			
//หาผู้ค้ำประกัน
$qry_cus2=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\" = '$contractID' and \"CusState\" = '2'");
$numcus2=pg_num_rows($qry_cus2);
$i=1;
$namecus2="";
while($resGua=pg_fetch_array($qry_cus2)){
	$cus2=trim($resGua["thcap_fullname"]);
	if($numcus2==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		$namecus2=$cus2;
	}else{
		if($i==$numcus2){
			$namecus2=$namecus2.$cus2;
		}else{
			$namecus2=$namecus2.$cus2.", ";
		}
	}
	$i++;
}

if($nameco == ""){$nameco = "ไม่มีผู้กู้ร่วม";}
if($namecus2 == ""){$namecus2 = "ไม่มีผู้ค้ำ";}

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) แสดงวงเงินและหนี้");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,22);
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

//----- หัวเลขที่สัญญา
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,27);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

// $pdf->SetFont('AngsanaNew','',12);
// $pdf->SetXY(31,27);
// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(45,27);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(65,27);
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(90,27);
$buss_name=iconv('UTF-8','windows-874',"วงเงินสินเชื่อ");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

//--

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"$contractID");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

// $pdf->SetFont('AngsanaNew','',12);
// $pdf->SetXY(32,30);
// $buss_name=iconv('UTF-8','windows-874',"$conIntRate %");
// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(40,30);
$buss_name=iconv('UTF-8','windows-874',"$conMaxRate %($conIntRate %)");
$pdf->MultiCell(23,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(67,30);
$buss_name=iconv('UTF-8','windows-874',"$conDate");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(91,30);
$buss_name=iconv('UTF-8','windows-874',number_format($conCredit,2));
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

//----- จบหัวเลขที่สัญญา

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,30.5);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,B,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,35);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(28,4,$buss_name,0,'L',0);

$pdf->SetXY(33,35);
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(51,35);
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(69,35);
$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(99,35);
$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(114,35);
$buss_name=iconv('UTF-8','windows-874',"อัตรดอกเบี้ยปัจจุบัน(%)");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(139,35);
$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(164,35);
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มค้างชำระ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(184,35);
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างชำระปัจจุบัน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,36);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,B,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;
$a=0;

$qry=pg_query("select a.\"contractID\",\"creditLine\",\"conDate\",\"conStartDate\",\"conLoanAmt\",\"conTerm\",\"conMinPay\"
	from \"vthcap_contract_creditRef_active\" a
	left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" 
	where \"contractCredit\"='$contractID'");
$num_row = pg_num_rows($qry);
$i = 1;
while($result=pg_fetch_array($qry))
{
	$contractID2 = $result["contractID"]; //สัญญาที่ใช้วงเงินนั้นๆอยู่
	$creditLine = $result["creditLine"]; //ใช้วงเงินอยู่เท่าไหร่
	$conDate = $result["conDate"]; //วันที่ทำสัญญา
	$conStartDate = $result["conStartDate"]; //วันที่เริ่มกู้
	$conLoanAmt = $result["conLoanAmt"]; //ยอดกู้
	$conTerm = $result["conTerm"]; //จำนวนงวด
	$conMinPay = $result["conMinPay"]; //ยอดจ่ายขั้นต่ำ/ต่อเดือน
	$interestRate = $result["interestRate"]; //อัตราดอกเบี้ยปัจจุบัน
					
	//ยอดค้างชำระปัจจุบัน
	$backAmt = pg_query("select \"thcap_backAmt\"('$contractID','$vfocusdate')");
	$backAmt = pg_fetch_result($backAmt,0);
					
	//วันที่เริ่มค้างชำระ
	$backDueDate = pg_query("select \"thcap_backDueDate\"('$contractID','$vfocusdate')");
	$backDueDate = pg_fetch_result($backDueDate,0);
					
	//อัตรดอกเบี้ยปัจจุบัน
	$qryintcur=pg_query("select \"conIntCurRate\" from \"thcap_mg_contract_current\" where \"contractID\"='$contractID'");
	$resintcur=pg_fetch_array($qryintcur);
	$conIntCurRate=$resintcur["conIntCurRate"];
	
	if($nub == 45)
	{
		$nub = 1;
		$cline = 40;
		$pdf->AddPage();
		
		
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) แสดงวงเงินและหนี้");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,22);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		//----- หัวเลขที่สัญญา
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(10,27);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(31,27);
		// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(45,27);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(65,27);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(90,27);
		$buss_name=iconv('UTF-8','windows-874',"วงเงินสินเชื่อ");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		//--

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"$contractID");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(32,30);
		// $buss_name=iconv('UTF-8','windows-874',"$conIntRate %");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(40,30);
		$buss_name=iconv('UTF-8','windows-874',"$conMaxRate %($conIntRate %)");
		$pdf->MultiCell(23,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(67,30);
		$buss_name=iconv('UTF-8','windows-874',"$conDate");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(91,30);
		$buss_name=iconv('UTF-8','windows-874',number_format($conCredit,2));
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		//----- จบหัวเลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,30.5);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,B,'C',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,35);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(28,4,$buss_name,0,'L',0);

		$pdf->SetXY(33,35);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(51,35);
		$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(69,35);
		$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(99,35);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(114,35);
		$buss_name=iconv('UTF-8','windows-874',"อัตรดอกเบี้ยปัจจุบัน(%)");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(139,35);
		$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(164,35);
		$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มค้างชำระ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(184,35);
		$buss_name=iconv('UTF-8','windows-874',"ยอดค้างชำระปัจจุบัน");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,36);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,B,'C',0);
	}
	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$contractID2");
	$pdf->MultiCell(28,4,$buss_name,0,'L',0);

	$pdf->SetXY(33,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$conDate");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(51,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(69,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	$pdf->SetXY(99,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$conTerm");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(114,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($conIntCurRate,2));
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(139,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(164,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$backDueDate");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(184,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($backAmt,2));
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
  
	$cline += 5;
	$nub+=1;
	$a += 1;
	$i++;
}


//--------หนี้อื่นๆที่ค้างชำระ  	
$pdf->SetFont('AngsanaNew','',10);
		
$qry_other = pg_query("select \"typePayID\",\"typePayRefValue\",\"typePayRefDate\",\"typePayAmt\",\"doerID\",\"doerStamp\",\"debtID\",a.\"contractID\"
	from \"vthcap_contract_creditRef_active\" a
	left join \"thcap_v_otherpay_debt_realother\" b on a.\"contractID\"=b.\"contractID\" 
	where \"contractCredit\"='$contractID' and \"debtStatus\"='1' 
	union
	select \"typePayID\",\"typePayRefValue\",\"typePayRefDate\",\"typePayAmt\",\"doerID\",\"doerStamp\",\"debtID\",\"contractID\"
	from \"thcap_v_otherpay_debt_realother\" 
	where \"contractID\"='$contractID' and \"debtStatus\"='1' order by \"doerStamp\" ASC");
$row_other = pg_num_rows($qry_other);
if($row_other > 0)
{
	if($nub == 45)
	{
		$nub = 1;
		$cline = 40;
		$pdf->AddPage();
						
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) แสดงวงเงินและหนี้");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,22);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		//----- หัวเลขที่สัญญา
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(10,27);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(31,27);
		// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(45,27);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(65,27);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(90,27);
		$buss_name=iconv('UTF-8','windows-874',"วงเงินสินเชื่อ");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		//--

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"$contractID");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(32,30);
		// $buss_name=iconv('UTF-8','windows-874',"$conIntRate %");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(40,30);
		$buss_name=iconv('UTF-8','windows-874',"$conMaxRate %($conIntRate %)");
		$pdf->MultiCell(23,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(67,30);
		$buss_name=iconv('UTF-8','windows-874',"$conDate");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(91,30);
		$buss_name=iconv('UTF-8','windows-874',number_format($conCredit,2));
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		//----- จบหัวเลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,30.5);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,B,'C',0);

		}
			
		
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,B,'C',0);
			
			$pdf->SetXY(5,$cline+6);
			$buss_name=iconv('UTF-8','windows-874',"----- หนี้อื่นๆที่ค้างชำระ -----");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			
			$cline += 6;
			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,B,'C',0);
			
			$cline += 6;
			
			
		if($nub < 46)
		{
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(35,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รหัสประเภท");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);
				$pdf->SetXY(35,$cline);
				$buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
				$pdf->MultiCell(15,10,$buss_name,0,'C',0);

			$pdf->SetXY(50,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รายการ");
			$pdf->MultiCell(37,4,$buss_name,0,'C',0);

			$pdf->SetXY(87,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิงของค่าใช้จ่าย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(112,$cline);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
			$pdf->MultiCell(18,4,$buss_name,0,'C',0);

			$pdf->SetXY(130,$cline);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(155,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetXY(190,$cline);
			$buss_name=iconv('UTF-8','windows-874',"วันเวลาตั้งหนี้");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+3);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,B,'C',0);
			
			$cline += 6;
			$nub+=3;
		}
		
		while($res_name=pg_fetch_array($qry_other))
		{
			$typePayID=trim($res_name["typePayID"]); // รหัสประเภทค่าใช้จ่าย
			$typePayRefValue=trim($res_name["typePayRefValue"]);
			$typePayRefDate=trim($res_name["typePayRefDate"]);
			$typePayAmt=trim($res_name["typePayAmt"]);
			$doerID=trim($res_name["doerID"]); 
			$doerStamp=trim($res_name["doerStamp"]);
			$debtID=trim($res_name["debtID"]);
			$contractID2=trim($res_name["contractID"]);
								
			$doerStamp = substr($doerStamp,0,19); // ทำให้อยู่ในรูปแบบวันเวลาที่สวยงาม
								
			if($doerID == "000")
			{
				$doerName = "อัตโนมัติโดยระบบ";
			}
			else
			{
				$doerusername=pg_query("select * from public.\"Vfuser\" where \"id_user\"='$doerID'");
				while($res_username=pg_fetch_array($doerusername))
				{
					$doerName=$res_username["fullname"];
				}
			}
							
			$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
			if($res_type=pg_fetch_array($qry_type))
			{
				$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
			}
			
			if($nub == 45)
			{
				$nub = 1;
				$cline = 40;
				$pdf->AddPage();
				
				
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(200,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) แสดงวงเงินและหนี้");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,22);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				//----- หัวเลขที่สัญญา
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(10,27);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,4,$buss_name,0,'L',0);

				// $pdf->SetFont('AngsanaNew','',12);
				// $pdf->SetXY(31,27);
				// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
				// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(45,27);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
				$pdf->MultiCell(20,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(65,27);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
				$pdf->MultiCell(25,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(90,27);
				$buss_name=iconv('UTF-8','windows-874',"วงเงินสินเชื่อ");
				$pdf->MultiCell(20,4,$buss_name,0,'L',0);

				//--

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,30);
				$buss_name=iconv('UTF-8','windows-874',"$contractID");
				$pdf->MultiCell(30,4,$buss_name,0,'L',0);

				// $pdf->SetFont('AngsanaNew','',12);
				// $pdf->SetXY(32,30);
				// $buss_name=iconv('UTF-8','windows-874',"$conIntRate %");
				// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(40,30);
				$buss_name=iconv('UTF-8','windows-874',"$conMaxRate %($conIntRate %)");
				$pdf->MultiCell(23,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(67,30);
				$buss_name=iconv('UTF-8','windows-874',"$conDate");
				$pdf->MultiCell(20,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(91,30);
				$buss_name=iconv('UTF-8','windows-874',number_format($conCredit,2));
				$pdf->MultiCell(20,4,$buss_name,0,'L',0);

				//----- จบหัวเลขที่สัญญา

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,30);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(200,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,30.5);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(200,4,$buss_name,B,'C',0);
			
				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(5,$cline);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);
				
				$pdf->SetXY(35,$cline);
				$buss_name=iconv('UTF-8','windows-874',"รหัสประเภท");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);
					$pdf->SetXY(35,$cline);
					$buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
					$pdf->MultiCell(15,10,$buss_name,0,'C',0);

				$pdf->SetXY(50,$cline);
				$buss_name=iconv('UTF-8','windows-874',"รายการ");
				$pdf->MultiCell(37,4,$buss_name,0,'C',0);

				$pdf->SetXY(87,$cline);
				$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิงของค่าใช้จ่าย");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(112,$cline);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
				$pdf->MultiCell(18,4,$buss_name,0,'C',0);

				$pdf->SetXY(130,$cline);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);
				
				$pdf->SetXY(150,$cline);
				$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetXY(185,$cline);
				$buss_name=iconv('UTF-8','windows-874',"วันเวลาตั้งหนี้");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,$cline+3);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(200,4,$buss_name,B,'C',0);
			}
			
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$contractID2");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);
			
			$pdf->SetXY(35,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$typePayID");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(50,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$tpDesc");
			$pdf->MultiCell(37,4,$buss_name,0,'L',0);

			$pdf->SetXY(87,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$typePayRefValue $due");
			$pdf->MultiCell(25,4,$buss_name,0,'L',0);

			$pdf->SetXY(112,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$typePayRefDate");
			$pdf->MultiCell(18,4,$buss_name,0,'C',0);

			$pdf->SetXY(130,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($typePayAmt,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(150,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$doerName");
			$pdf->MultiCell(35,4,$buss_name,0,'L',0);

			$pdf->SetXY(185,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$doerStamp");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			
			$cline += 5;
			$nub+=1;
			$a += 1;
			$i++;
		}
	}
	
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	if($nub == 45)
	{
		$nub = 1;
		$cline = 40;
		$pdf->AddPage();
				
				
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) แสดงวงเงินและหนี้");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,22);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		//----- หัวเลขที่สัญญา
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(10,27);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(31,27);
		// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(45,27);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(65,27);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(90,27);
		$buss_name=iconv('UTF-8','windows-874',"วงเงินสินเชื่อ");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		//--

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"$contractID");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(32,30);
		// $buss_name=iconv('UTF-8','windows-874',"$conIntRate %");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(40,30);
		$buss_name=iconv('UTF-8','windows-874',"$conMaxRate %($conIntRate %)");
		$pdf->MultiCell(23,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(67,30);
		$buss_name=iconv('UTF-8','windows-874',"$conDate");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(91,30);
		$buss_name=iconv('UTF-8','windows-874',number_format($conCredit,2));
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		//----- จบหัวเลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,30.5);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,B,'C',0);
			
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
				
		$pdf->SetXY(35,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รหัสประเภท");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
			$pdf->SetXY(35,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
			$pdf->MultiCell(15,10,$buss_name,0,'C',0);

		$pdf->SetXY(50,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รายการ");
		$pdf->MultiCell(37,4,$buss_name,0,'C',0);

		$pdf->SetXY(87,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิงของค่าใช้จ่าย");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(112,$cline);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(130,$cline);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
				
		$pdf->SetXY(150,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetXY(185,$cline);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาตั้งหนี้");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline+3);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,B,'C',0);
	}
	
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"หมายเหตุ");
	$pdf->MultiCell(200,4,$buss_name,0,'L',0);
	
	$cline += 5;
	$nub+=1;

	if($nub == 45)
	{
		$nub = 1;
		$cline = 40;
		$pdf->AddPage();
				
				
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) แสดงวงเงินและหนี้");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,22);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		//----- หัวเลขที่สัญญา
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(10,27);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(31,27);
		// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(45,27);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(65,27);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(90,27);
		$buss_name=iconv('UTF-8','windows-874',"วงเงินสินเชื่อ");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		//--

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"$contractID");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(32,30);
		// $buss_name=iconv('UTF-8','windows-874',"$conIntRate %");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(40,30);
		$buss_name=iconv('UTF-8','windows-874',"$conMaxRate %($conIntRate %)");
		$pdf->MultiCell(23,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(67,30);
		$buss_name=iconv('UTF-8','windows-874',"$conDate");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(91,30);
		$buss_name=iconv('UTF-8','windows-874',number_format($conCredit,2));
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		//----- จบหัวเลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,30.5);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,B,'C',0);
			
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
				
		$pdf->SetXY(35,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รหัสประเภท");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
			$pdf->SetXY(35,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
			$pdf->MultiCell(15,10,$buss_name,0,'C',0);

		$pdf->SetXY(50,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รายการ");
		$pdf->MultiCell(37,4,$buss_name,0,'C',0);

		$pdf->SetXY(87,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิงของค่าใช้จ่าย");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(112,$cline);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(130,$cline);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
				
		$pdf->SetXY(150,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetXY(185,$cline);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาตั้งหนี้");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline+3);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,B,'C',0);
	}
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
	$pdf->MultiCell(200,4,$buss_name,0,'L',0);
	
	$cline += 5;
	$nub+=1;
	if($nub == 45)
	{
		$nub = 1;
		$cline = 40;
		$pdf->AddPage();
				
				
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) แสดงวงเงินและหนี้");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,22);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		//----- หัวเลขที่สัญญา
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(10,27);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(31,27);
		// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(45,27);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(65,27);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(90,27);
		$buss_name=iconv('UTF-8','windows-874',"วงเงินสินเชื่อ");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		//--

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"$contractID");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(32,30);
		// $buss_name=iconv('UTF-8','windows-874',"$conIntRate %");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(40,30);
		$buss_name=iconv('UTF-8','windows-874',"$conMaxRate %($conIntRate %)");
		$pdf->MultiCell(23,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(67,30);
		$buss_name=iconv('UTF-8','windows-874',"$conDate");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(91,30);
		$buss_name=iconv('UTF-8','windows-874',number_format($conCredit,2));
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		//----- จบหัวเลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,30.5);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,B,'C',0);
			
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
				
		$pdf->SetXY(35,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รหัสประเภท");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
			$pdf->SetXY(35,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
			$pdf->MultiCell(15,10,$buss_name,0,'C',0);

		$pdf->SetXY(50,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รายการ");
		$pdf->MultiCell(37,4,$buss_name,0,'C',0);

		$pdf->SetXY(87,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิงของค่าใช้จ่าย");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(112,$cline);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(130,$cline);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
				
		$pdf->SetXY(150,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetXY(185,$cline);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาตั้งหนี้");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline+3);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	}	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"ผู้ค้ำ : $namecus2");
	$pdf->MultiCell(200,4,$buss_name,0,'L',0);


$pdf->Output();
?>