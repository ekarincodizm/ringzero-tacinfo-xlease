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
$costclose = $_GET["costclose"]; //รวมค่าบริการปิดบัญชี ถ้าเลือกจะเป็น on
//---------

//----- ส่วนของหัวกระดาษ
$sql_head=pg_query("select * from public.\"thcap_lease_contract\" where \"contractID\" = '$contractID' ");
while($resultH=pg_fetch_array($sql_head))
{
	$conLoanIniRate = $resultH["conLoanIniRate"];
	$conLoanMaxRate = $resultH["conLoanMaxRate"];
	$conDate = $resultH["conDate"];
	$conStartDate = $resultH["conStartDate"];
	$conRepeatDueDay = $resultH["conRepeatDueDay"];
	$conLoanAmt = $resultH["conFinanceAmount"];
	$conTerm = $resultH["conTerm"];
	$conMinPay = $resultH["conMinPay"];
}
//-----

//ค้นหาผู้เช่า/ผู้เช่าซื้อจาก
$qry_namemain=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\"='$contractID' and \"CusState\"='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
}

//ค้นหาชื่อผู้เช่าร่วม/ผู้เช่าซื้อร่วมจาก
$qry_name=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\"='$contractID' and \"CusState\" =1");
$numco=pg_num_rows($qry_name);
$i=1;
$nameco="";
while($resco=pg_fetch_array($qry_name))
{
	$name2=trim($resco["thcap_fullname"]);
	if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		$nameco=$name2;
	}else{
		if($i==$numco){
			$nameco=$nameco.$name2;
		}else{
			$nameco=$nameco.$name2.", ";
		}
	}
	$i++;
}
if($nameco == ""){$nameco = "ไม่มีผู้เช่าร่วม/ผู้เช่าซื้อร่วม";}
//จบการค้นหาชื่อผู้เช่าร่วม/ผู้เช่าซื้อร่วม


//หาเงินค้ำประกัน
	$sqlguan = pg_query("SELECT \"contractBalance\" FROM vthcap_contract_money where \"moneyType\" = account.\"thcap_getSecureMoneyType\"('$contractID','1')::smallint and \"contractID\" = '$contractID'");
	list($moneyguan) = pg_fetch_array($sqlguan);
//เงินพักรอตัดรายการ
	$sqlcut = pg_query("SELECT \"contractBalance\" FROM vthcap_contract_money where \"moneyType\" = account.\"thcap_getHoldMoneyType\"('$contractID','1')::smallint and \"contractID\" = '$contractID'");
	list($moneycut) = pg_fetch_array($sqlcut);

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

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,22);
$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
$pdf->MultiCell(290,4,$buss_name,0,'L',0);

//----- หัวเลขที่สัญญา
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(40,28);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(65,28);
$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(90,28);
$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(125,28);
$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(160,28);
$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(195,28);
$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(230,28);
$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

//--
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"$contractID");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(40,32);
$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(65,32);
$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(90,32);
$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(125,32);
$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(160,32);
$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
$pdf->MultiCell(35,4,$buss_name,0,'C',0);


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(195,32);
$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(230,32);
$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
$pdf->MultiCell(35,4,$buss_name,0,'C',0);
//----- จบหัวเลขที่สัญญา

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,39);
$buss_name=iconv('UTF-8','windows-874',"DueNo");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(18,39);
$buss_name=iconv('UTF-8','windows-874',"DueDate");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);
	$pdf->SetXY(14,43);
	$buss_name=iconv('UTF-8','windows-874',"(วันครบกำหนด)");
	$pdf->MultiCell(22,4,$buss_name,0,'C',0);

$pdf->SetXY(40,39);
$buss_name=iconv('UTF-8','windows-874',"วันที่จ่าย");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(60,39);
$buss_name=iconv('UTF-8','windows-874',"จำนวน");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);
	$pdf->SetXY(60,43);
	$buss_name=iconv('UTF-8','windows-874',"วันล่าช้า");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(75,39);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(100,39);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบกำกับภาษี");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(125,39);
$buss_name=iconv('UTF-8','windows-874',"ค่างวดรวม VAT");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(155,39);
$buss_name=iconv('UTF-8','windows-874',"ยอดที่ต้องชำระรวม VAT");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(185,39);
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือก่อนชำระ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	$pdf->SetXY(185,43);
	$buss_name=iconv('UTF-8','windows-874',"รวม VAT");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(210,39);
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือก่อนชำระ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	$pdf->SetXY(210,43);
	$buss_name=iconv('UTF-8','windows-874',"ไม่รวม VAT");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(235,39);
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือหลังชำระ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	$pdf->SetXY(235,43);
	$buss_name=iconv('UTF-8','windows-874',"รวม VAT");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(265,39);
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือหลังชำระ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	$pdf->SetXY(265,43);
	$buss_name=iconv('UTF-8','windows-874',"ไม่รวม VAT");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,44);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 46;
$nub = 1;
$a=0;

$qry=pg_query("select * from account.thcap_acc_filease_realize_eff_present where \"contractID\" = '$contractID'"); 
$num_row = pg_num_rows($qry);
$i = 1;
while($result=pg_fetch_array($qry))
{
	$DueNo = $res1["DueNo"]; // งวดที่  
	$ptDate = $res1["ptDate"]; // วันที่ครบกำหนดชำระ 
	$receiveDate = $res1["receiveDate"]; // วันที่จ่าย 
	$delay = $res1["delay"]; // จำนวนวันล่าช้า 
	$receiptID = trim($res1["receiptID"]); //เลขที่ใบเสร็จ
	$taxinvoiceID = trim($res1["taxinvoiceID"]); //เลขที่ใบกำกับภาษี
	$debtall_cut = number_format($res1["debtall_cut"],2); //ค่างวดรวม VAT
	$typePayLeft = number_format($res1["typePayLeft"],2); // ยอดที่ต้องชำระรวม VAT	
	$totaldebtall_before = number_format($res1["totaldebtall_before"],2); //ยอดคงเหลือก่อนชำระรวม VAT
	$totaldebt_before = number_format($res1["totaldebt_before"],2); //ยอดคงเหลือก่อนชำระไม่รวม VAT
	$totaldebtall_left = number_format($res1["totaldebtall_left"],2); //ยอดคงเหลือหลังชำระไม่รวม VAT
	$totaldebt_left = number_format($res1["totaldebt_left"],2); //ยอดคงเหลือหลังชำระไม่รวม VAT
		
	if($nub == 25)
	{
		$nub = 1;
		$cline = 46;
		$pdf->AddPage();
		
		
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
		$pdf->MultiCell(190,4,$buss_name,0,'L',0);
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,22);
		$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);

		//----- หัวเลขที่สัญญา
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,28);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(40,28);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(65,28);
		$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(90,28);
		$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(125,28);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(160,28);
		$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(195,28);
		$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(230,28);
		$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		//--
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"$contractID");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(40,32);
		$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(65,32);
		$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(90,32);
		$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(125,32);
		$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(160,32);
		$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);


		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(195,32);
		$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(230,32);
		$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);
		//----- จบหัวเลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,39);
		$buss_name=iconv('UTF-8','windows-874',"DueNo");
		$pdf->MultiCell(15,4,$buss_name,0,'L',0);

		$pdf->SetXY(18,39);
		$buss_name=iconv('UTF-8','windows-874',"DueDate");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
			$pdf->SetXY(14,43);
			$buss_name=iconv('UTF-8','windows-874',"(วันครบกำหนด)");
			$pdf->MultiCell(22,4,$buss_name,0,'C',0);

		$pdf->SetXY(40,39);
		$buss_name=iconv('UTF-8','windows-874',"วันที่จ่าย");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(60,39);
		$buss_name=iconv('UTF-8','windows-874',"จำนวน");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
			$pdf->SetXY(60,43);
			$buss_name=iconv('UTF-8','windows-874',"วันล่าช้า");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(75,39);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(100,39);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบกำกับภาษี");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(125,39);
		$buss_name=iconv('UTF-8','windows-874',"ค่างวดรวม VAT");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(155,39);
		$buss_name=iconv('UTF-8','windows-874',"ยอดที่ต้องชำระรวม VAT");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(185,39);
		$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือก่อนชำระ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			$pdf->SetXY(185,43);
			$buss_name=iconv('UTF-8','windows-874',"รวม VAT");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(210,39);
		$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือก่อนชำระ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			$pdf->SetXY(210,43);
			$buss_name=iconv('UTF-8','windows-874',"ไม่รวม VAT");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(235,39);
		$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือหลังชำระ");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
			$pdf->SetXY(235,43);
			$buss_name=iconv('UTF-8','windows-874',"รวม VAT");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(265,39);
		$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือหลังชำระ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			$pdf->SetXY(265,43);
			$buss_name=iconv('UTF-8','windows-874',"ไม่รวม VAT");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,44);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'C',0);
	}
	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',$DueNo);
	$pdf->MultiCell(15,4,$buss_name,0,'L',0);

	$pdf->SetXY(16,$cline);
	$buss_name=iconv('UTF-8','windows-874',$ptDate);
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(34,$cline);
	$buss_name=iconv('UTF-8','windows-874',$receiveDate);
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	$pdf->SetXY(60,$cline);
	$buss_name=iconv('UTF-8','windows-874',$delay);
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(75,$cline);
	$buss_name=iconv('UTF-8','windows-874',$receiptID);
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(100,$cline);
	$buss_name=iconv('UTF-8','windows-874',$taxinvoiceID);
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(125,$cline);
	$buss_name=iconv('UTF-8','windows-874',$debtall_cut);
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);

	$pdf->SetXY(155,$cline);
	$buss_name=iconv('UTF-8','windows-874',$typePayLeft);
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);

	$pdf->SetXY(185,$cline);
	$buss_name=iconv('UTF-8','windows-874',$totaldebtall_before);
	$pdf->MultiCell(25,4,$buss_name,0,'R',0);

	$pdf->SetXY(210,$cline);
	$buss_name=iconv('UTF-8','windows-874',$totaldebt_before);
	$pdf->MultiCell(25,4,$buss_name,0,'R',0);

	$pdf->SetXY(235,$cline);
	$buss_name=iconv('UTF-8','windows-874',$totaldebtall_left);
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);

	$pdf->SetXY(265,$cline);
	$buss_name=iconv('UTF-8','windows-874',$totaldebt_left);
	$pdf->MultiCell(25,4,$buss_name,0,'R',0);
    
	$cline += 5;
	$nub+=1;
	$a += 1;
	$i++;
}


// หาค่าจาก function ใน postgres
	$qrybackamt = pg_query("select \"thcap_get_all_backamt\"('$contractID')");
	list($backAmt) = pg_fetch_array($qrybackamt);
	
	$qrybackDueDate = pg_query("select \"thcap_get_all_backdate\"('$contractID')");
	list($backDueDate) = pg_fetch_array($qrybackDueDate);
// จบการหาค่าจาก function ใน postgres


    $pdf->SetFont('AngsanaNew','B',13);

    $pdf->SetXY(5,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"");
    $pdf->MultiCell(290,4,$buss_name,B,'C',0);
    
    $cline += 6;
    $nub+=1;
	
	$pdf->SetFont('AngsanaNew','',12);
	
	$pdf->SetXY(5,$cline+1);
	$buss_name=iconv('UTF-8','windows-874',"ยอดค้างชำระปัจจุบัน : ".number_format($backAmt,2));
	$pdf->MultiCell(40,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(70,$cline+1);
	$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มค้างชำระ : $backDueDate");
	$pdf->MultiCell(40,4,$buss_name,0,'L',0);
	
	$cline += 5;
	$nub+=1;
	$a += 1;
	$i++;
	
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN หนี้อื่นๆที่ค้างชำระ
	
	$qry_other = pg_query("select * from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\"='$contractID' and \"debtStatus\"='1' order by \"typePayRefDate\" ");
	$row_other = pg_num_rows($qry_other);
	if($row_other > 0)
	{
	
		if($nub == 25)
			{
				$nub = 1;
				$cline = 46;
				$pdf->AddPage();
				
				
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
				$pdf->MultiCell(100,4,$buss_name,0,'L',0);
				
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,22);
				$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
				$pdf->MultiCell(290,4,$buss_name,0,'L',0);

				//----- หัวเลขที่สัญญา
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,28);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(40,28);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(65,28);
				$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(90,28);
				$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(125,28);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(160,28);
				$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(195,28);
				$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(230,28);
				$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				//--
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"$contractID");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(40,32);
				$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(65,32);
				$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(90,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(125,32);
				$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(160,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);


				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(195,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(230,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);
				//----- จบหัวเลขที่สัญญา

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			}
			
		//$cline += 6;
		
			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
			
			$pdf->SetXY(5,$cline+6);
			$buss_name=iconv('UTF-8','windows-874',"----- หนี้อื่นๆที่ค้างชำระ -----");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);
			
			$cline += 6;
			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
			
			$cline += 6;
			
			
		if($nub < 25)
		{
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(35,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รายการ");
			$pdf->MultiCell(60,4,$buss_name,0,'C',0);

			$pdf->SetXY(95,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิงของค่าใช้จ่าย");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(125,$cline);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetXY(165,$cline);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(195,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
			$pdf->MultiCell(50,4,$buss_name,0,'C',0);

			$pdf->SetXY(245,$cline);
			$buss_name=iconv('UTF-8','windows-874',"วันเวลาตั้งหนี้");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
			
			$cline += 6;
			$nub+=1;
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
			
			$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
			while($res_type=pg_fetch_array($qry_type))
			{
				$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
			}
			
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
			
			if($nub == 25)
			{
				$nub = 1;
				$cline = 46;
				$pdf->AddPage();
				
				
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
				$pdf->MultiCell(100,4,$buss_name,0,'L',0);
				
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,22);
				$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
				$pdf->MultiCell(290,4,$buss_name,0,'L',0);

				//----- หัวเลขที่สัญญา
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,28);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(40,28);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(65,28);
				$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(90,28);
				$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(125,28);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(160,28);
				$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(195,28);
				$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(230,28);
				$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				//--
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"$contractID");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(40,32);
				$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(65,32);
				$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(90,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(125,32);
				$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(160,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);


				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(195,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(230,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);
				//----- จบหัวเลขที่สัญญา

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,33);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(290,4,$buss_name,B,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,39);
				$buss_name=iconv('UTF-8','windows-874',"DueNo");
				$pdf->MultiCell(15,4,$buss_name,0,'L',0);

				$pdf->SetXY(18,39);
				$buss_name=iconv('UTF-8','windows-874',"DueDate");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);
					$pdf->SetXY(14,43);
					$buss_name=iconv('UTF-8','windows-874',"(วันครบกำหนด)");
					$pdf->MultiCell(22,4,$buss_name,0,'C',0);

				$pdf->SetXY(40,39);
				$buss_name=iconv('UTF-8','windows-874',"วันที่จ่าย");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetXY(60,39);
				$buss_name=iconv('UTF-8','windows-874',"จำนวน");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);
					$pdf->SetXY(60,43);
					$buss_name=iconv('UTF-8','windows-874',"วันล่าช้า");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);

				$pdf->SetXY(75,39);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(100,39);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบกำกับภาษี");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(125,39);
				$buss_name=iconv('UTF-8','windows-874',"ค่างวดรวม VAT");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(155,39);
				$buss_name=iconv('UTF-8','windows-874',"ยอดที่ต้องชำระรวม VAT");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(185,39);
				$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือก่อนชำระ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);
					$pdf->SetXY(185,43);
					$buss_name=iconv('UTF-8','windows-874',"รวม VAT");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(210,39);
				$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือก่อนชำระ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);
					$pdf->SetXY(210,43);
					$buss_name=iconv('UTF-8','windows-874',"ไม่รวม VAT");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(235,39);
				$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือหลังชำระ");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);
					$pdf->SetXY(235,43);
					$buss_name=iconv('UTF-8','windows-874',"รวม VAT");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(265,39);
				$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือหลังชำระ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);
					$pdf->SetXY(265,43);
					$buss_name=iconv('UTF-8','windows-874',"ไม่รวม VAT");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,44);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(290,4,$buss_name,B,'C',0);
			}
			
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$typePayID");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(35,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$tpDesc");
			$pdf->MultiCell(60,4,$buss_name,0,'L',0);

			$pdf->SetXY(95,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$typePayRefValue");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(125,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$typePayRefDate");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(165,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($typePayAmt,2));
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(195,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$doerName");
			$pdf->MultiCell(50,4,$buss_name,0,'L',0);
			
			$pdf->SetXY(245,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$doerStamp");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);
			
			$cline += 5;
			$nub+=1;
			$a += 1;
			$i++;
		}
	}
	
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	
	
	
	
	//GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG ยอดปิดบัญชี
	
	if($signDate != "")
	{
	
		if($nub == 25)
		{
			$nub = 1;
			$cline = 46;
			$pdf->AddPage();
			
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,22);
			$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			//----- หัวเลขที่สัญญา
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,28);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,28);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,28);
			$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			//--
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,32);
			$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,32);
			$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,32);
			$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			//----- จบหัวเลขที่สัญญา

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,44);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
			
		$cline += 6;
				
		// คำนวนเงิน
		
		// ==================================================================================
		// หายอดปิดสัญญาลงทุน 
		// ==================================================================================
		$qrymoneyclose = pg_query("SELECT \"thcap_cal_close_guaranteed_investment\"('$contractID','$signDate')");
		list($LastLeftPrinciple)=pg_fetch_array($qrymoneyclose);
			
		// ==================================================================================
		// ยอดหนี้ค้างชำระอื่นๆ
		// ==================================================================================
		$sql_other = pg_query("select * from account.\"thcap_typePay\" where \"isSubsti\" <> '1' ");
		while($resultother=pg_fetch_array($sql_other))
		{
			$tpID_other = $resultother["tpID"];
			$sql_Sother = pg_query("select sum(\"typePayAmt\") as \"sumone\" from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\" = '$contractID' and \"typePayID\" = '$tpID_other' and \"debtStatus\" = '1' ");
			while($resultSother=pg_fetch_array($sql_Sother))
			{
				$sumone = $resultSother["sumone"];	
				$plusone += $sumone;
			}
			$sumone = 0;
		}
		
		// ==================================================================================
		// ยอดรับจ่ายแทนค่าประกันภัย-อื่นๆ
		// ==================================================================================
		$sql_other2 = pg_query("select * from account.\"thcap_typePay\" where \"isSubsti\" = '1' ");
		while($resultother2=pg_fetch_array($sql_other2))
		{
			$tpID_other2 = $resultother2["tpID"];
			$sql_Sother2 = pg_query("select sum(\"typePayAmt\") as \"sumone\" from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\" = '$contractID' and \"typePayID\" = '$tpID_other2' and \"debtStatus\" = '1' ");
			while($resultSother2=pg_fetch_array($sql_Sother2))
			{
				$sumone = $resultSother2["sumone"];
				$plustwo += $sumone;
			}
			$sumone = 0;
		}
		
		// ==================================================================================
		// หาค่าเบี้ยปรับ ณ วันที่เลือก
		// ==================================================================================
			$qr_ct = pg_query("select \"thcap_get_creditType\"('$contractID') as credit_type");
			if($qr_ct)
			{
				$rs_ct = pg_fetch_array($qr_ct);
				$credit_type = $rs_ct['credit_type'];
			}
			
			if($credit_type=="HIRE_PURCHASE" || $credit_type=="LEASING" || $credit_type=="GUARANTEED_INVESTMENT" || $credit_type=="FACTORING")
			{
				$qr_get_cloce_fine = pg_query("select \"thcap_get_lease_fine\"('$contractID','$signDate')");
				$cloce_fine = pg_fetch_result($qr_get_cloce_fine,0);
			}
			else
			{
				$qr_get_cloce_fine = pg_query("select \"thcap_get_loan_fine\"('$contractID','$signDate')");
				$cloce_fine = pg_fetch_result($qr_get_cloce_fine,0);
			}
			if($cloce_fine == ""){$cloce_fine = "0.00";}
		// ==================================================================================

		$sum_close_account = $LastLeftPrinciple + $costclose + $plusone + $plustwo + $cloce_fine; // เงินรวม
		
		// จบการคำนวนเงิน
		
		if($nub == 25)
		{
			$nub = 1;
			$cline = 46;
			$pdf->AddPage();
			
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,22);
			$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			//----- หัวเลขที่สัญญา
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,28);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,28);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,28);
			$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			//--
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,32);
			$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,32);
			$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,32);
			$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			//----- จบหัวเลขที่สัญญา

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"---------- คำนวณยอดปิดบัญชี ณ วันที่ $signDate ----------");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);
		
		$nub +=1;
		if($nub == 25)
		{
			$nub = 1;
			$cline = 46;
			$pdf->AddPage();
			
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,22);
			$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			//----- หัวเลขที่สัญญา
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,28);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,28);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,28);
			$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			//--
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,32);
			$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,32);
			$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,32);
			$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			//----- จบหัวเลขที่สัญญา

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		
		$cline += 5;

		$pdf->SetFont('AngsanaNew','',11);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ยอดเงินต้น");
		$pdf->MultiCell(60,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(65,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($LastLeftPrinciple,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(85,$cline);
		$buss_name=iconv('UTF-8','windows-874',"บาท");
		$pdf->MultiCell(10,4,$buss_name,0,'L',0);
		
		//-----
		$nub+=1;
		if($nub == 25)
		{
			$nub = 1;
			$cline = 46;
			$pdf->AddPage();
			
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,22);
			$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			//----- หัวเลขที่สัญญา
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,28);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,28);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,28);
			$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			//--
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,32);
			$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,32);
			$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,32);
			$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			//----- จบหัวเลขที่สัญญา

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		$cline += 5;

		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ยอดหนี้ค้างชำระอื่นๆ");
		$pdf->MultiCell(60,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(65,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($plusone,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(85,$cline);
		$buss_name=iconv('UTF-8','windows-874',"บาท");
		$pdf->MultiCell(10,4,$buss_name,0,'L',0);
		
		//-----
		$nub+=1;
		if($nub == 25)
		{
			$nub = 1;
			$cline = 46;
			$pdf->AddPage();
			
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,22);
			$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			//----- หัวเลขที่สัญญา
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,28);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,28);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,28);
			$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			//--
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,32);
			$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,32);
			$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,32);
			$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			//----- จบหัวเลขที่สัญญา

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		$cline += 5;

		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ยอดรับจ่ายแทนค่าประกันภัย-อื่นๆ");
		$pdf->MultiCell(60,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(65,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($plustwo,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(85,$cline);
		$buss_name=iconv('UTF-8','windows-874',"บาท");
		$pdf->MultiCell(10,4,$buss_name,0,'L',0);
		
		//-----
		$nub+=1;
		if($nub == 25)
		{
			$nub = 1;
			$cline = 46;
			$pdf->AddPage();
			
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,22);
			$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			//----- หัวเลขที่สัญญา
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,28);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,28);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,28);
			$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			//--
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,32);
			$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,32);
			$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,32);
			$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			//----- จบหัวเลขที่สัญญา

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		$cline += 5;
		
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ยอดเงินบริการปิดบัญชี");
		$pdf->MultiCell(60,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(65,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($costclose,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(85,$cline);
		$buss_name=iconv('UTF-8','windows-874',"บาท");
		$pdf->MultiCell(10,4,$buss_name,0,'L',0);
		
		//-----
		if($cloce_fine > 0.00)
		{
			$nub+=1;
			if($nub == 25)
			{
				$nub = 1;
				$cline = 46;
				$pdf->AddPage();
				
				
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
				$pdf->MultiCell(100,4,$buss_name,0,'L',0);
				
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,22);
				$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
				$pdf->MultiCell(290,4,$buss_name,0,'L',0);

				//----- หัวเลขที่สัญญา
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,28);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(40,28);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(65,28);
				$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(90,28);
				$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(125,28);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(160,28);
				$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(195,28);
				$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(230,28);
				$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				//--
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"$contractID");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(40,32);
				$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(65,32);
				$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(90,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(125,32);
				$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(160,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);


				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(195,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(230,32);
				$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);
				//----- จบหัวเลขที่สัญญา

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,33);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(290,4,$buss_name,B,'C',0);
			}
			$cline += 5;
			
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ยอดเบี้ยปรับ");
			$pdf->MultiCell(60,4,$buss_name,0,'L',0);
			
			$pdf->SetXY(65,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($cloce_fine,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(85,$cline);
			$buss_name=iconv('UTF-8','windows-874',"บาท");
			$pdf->MultiCell(10,4,$buss_name,0,'L',0);
		}
		
		//-----

		$pdf->SetXY(5,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'L',0);
			
		$cline += 5;
		$nub+=1;
		if($nub == 25)
		{
			$nub = 1;
			$cline = 46;
			$pdf->AddPage();
			
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,22);
			$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			//----- หัวเลขที่สัญญา
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,28);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,28);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,28);
			$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			//--
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,32);
			$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,32);
			$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,32);
			$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			//----- จบหัวเลขที่สัญญา

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		$a += 1;
		$i++;
		
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ยอดรวมเงินที่ต้องชำระทั้งสิ้น");
		$pdf->MultiCell(60,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(65,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sum_close_account,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(85,$cline);
		$buss_name=iconv('UTF-8','windows-874',"บาท");
		$pdf->MultiCell(10,4,$buss_name,0,'L',0);
		
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'L',0);
		
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,$cline+2);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'L',0);
		
		$cline += 7;
		$nub+=1;
		$a += 1;
		$i++;
	}
	
	//GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG
	
	
	$cline += 5;
	$nub+=1;
	if($nub == 25)
		{
			$nub = 1;
			$cline = 46;
			$pdf->AddPage();
			
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) ตารางแสดงการผ่อนชำระ");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก : $name3");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,22);
			$buss_name=iconv('UTF-8','windows-874',"ผู้กู้ร่วม : $nameco");
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			//----- หัวเลขที่สัญญา
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,28);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,28);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,28);
			$buss_name=iconv('UTF-8','windows-874',"วันเริ่มต้นสัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ค่าเช่า");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินค้ำประกันสัญญา");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,28);
			$buss_name=iconv('UTF-8','windows-874',"เงินพักรอตัดรายการ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			//--
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(40,32);
			$buss_name=iconv('UTF-8','windows-874',"$conLoanMaxRate %($conLoanIniRate %)");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(65,32);
			$buss_name=iconv('UTF-8','windows-874',"$conStartDate");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(90,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conLoanAmt,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,32);
			$buss_name=iconv('UTF-8','windows-874',"$conTerm เดือน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($conMinPay,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneyguan,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(230,32);
			$buss_name=iconv('UTF-8','windows-874',number_format($moneycut,2));
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			//----- จบหัวเลขที่สัญญา

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
	$a += 1;
	$i++;
$pdf->Output();
?>