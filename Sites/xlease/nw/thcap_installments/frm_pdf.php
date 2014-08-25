<?php
session_start();
include("../../config/config.php");
include("../../core/core_functions.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$contractID = pg_escape_string($_POST["idno_text"]);
if($contractID == ""){$contractID = pg_escape_string($_GET["idno"]);}
$vfocusdate = nowDate();

//ถ้ามียอดปิดบัญชี
$signDate = pg_escape_string($_GET["signDate"]); //วันที่เลือก
$damage = pg_escape_string($_GET["damage"]); //รวมค่าเสียหายปิดบัญชีก่อนกำหนด ถ้าเลือกจะเป็น on
$costclose = pg_escape_string($_GET["costclose"]); //รวมค่าบริการปิดบัญชี ถ้าเลือกจะเป็น on
//---------

//----- ส่วนของหัวกระดาษ
$sql_head=pg_query("select \"conLoanIniRate\", \"conLoanMaxRate\", \"conDate\", \"conStartDate\", \"conRepeatDueDay\", \"conLoanAmt\", \"conTerm\", \"conMinPay\"
					from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
while($resultH=pg_fetch_array($sql_head))
{
	$conLoanIniRate = $resultH["conLoanIniRate"];
	$conLoanMaxRate = $resultH["conLoanMaxRate"];
	$conDate = $resultH["conDate"];
	$conStartDate = $resultH["conStartDate"];
	$conRepeatDueDay = $resultH["conRepeatDueDay"];
	$conLoanAmt = $resultH["conLoanAmt"];
	$conTerm = $resultH["conTerm"];
	$conMinPay = $resultH["conMinPay"];
}
//-----

$db1="ta_mortgage_datastore";

//ค้นหาชื่อผู้กู้หลักจาก mysql
$qry_namemain=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where \"contractID\"='$contractID' and \"CusState\"='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
}

//ค้นหาชื่อผู้กู้ร่วมจาก mysql
$qry_name=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
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
if($nameco == ""){$nameco = "ไม่มีผู้กู้ร่วม";}
//จบการค้นหาชื่อผู้กู้ร่วมจาก mysql


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

// $pdf->SetFont('AngsanaNew','',12);
// $pdf->SetXY(31,22);
// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(125,28);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(160,28);
$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

// $pdf->SetFont('AngsanaNew','',12);
// $pdf->SetXY(32,32);
// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,39);
$buss_name=iconv('UTF-8','windows-874',"จ่ายครั้งที่");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(20,39);
$buss_name=iconv('UTF-8','windows-874',"วันที่จ่าย");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(50,39);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(65,39);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินจ่าย");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(105,39);
$buss_name=iconv('UTF-8','windows-874',"จำนวนวัน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(125,39);
$buss_name=iconv('UTF-8','windows-874',"จำนวนดอกเบี้ยที่ต้องจ่าย");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(165,39);
$buss_name=iconv('UTF-8','windows-874',"จำนวนหักเงินต้น");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(205,36);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้นคงเหลือ ณ วันจ่าย");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(235,36);
$buss_name=iconv('UTF-8','windows-874',"จำนวนดอกเบี้ยคงเหลือ ณ วันจ่าย");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(265,39);
$buss_name=iconv('UTF-8','windows-874',"ช่องทางการจ่าย");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,40);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 46;
$nub = 1;
$a=0;

$qry=pg_query("select \"receiveDate\", \"interestRate\", \"receiveAmount\", \"receiveInterest\", \"receivePriciple\", \"LeftPrinciple\", \"LeftInterest\", \"receiptID\"
			from public.\"thcap_temp_int_201201\" where \"contractID\" = '$contractID' and \"isReceiveReal\" = '1' order by \"receiveDate\" ASC , \"LeftPrinciple\" DESC ");
$num_row = pg_num_rows($qry);
$i = 1;
while($result=pg_fetch_array($qry))
{
	$receiveDate[$i] = $result["receiveDate"];
	$interestRate = $result["interestRate"];
	$receiveAmount = $result["receiveAmount"];
	$receiveInterest = $result["receiveInterest"];
	$receivePriciple = $result["receivePriciple"];
	$LeftPrinciple = $result["LeftPrinciple"];
	$LeftInterest = $result["LeftInterest"];
	$receiptID  = $result["receiptID"];
	$Last_LeftInterest = $LeftInterest; // ยอดดอกเบี้ยคงเหลือล่าสุด
	
	//หาช่องทางการจ่าย
	$sqlchannel = pg_query("SELECT \"byChannel\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID' order by \"ChannelAmt\" DESC ");
	$rechannel = pg_fetch_array($sqlchannel);
	$byChannel  = $rechannel["byChannel"];
	
	if($byChannel=="" || $byChannel=="0" || $byChannel=="999"){$byChannel="ไม่ระบุ";}
	else{
		//นำไปค้นหาในตาราง BankInt
		$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel'");
		$ressearch=pg_fetch_array($qrysearch);
		list($BAccount,$BName)=$ressearch;
		$byChannel="$BAccount-$BName";
	}
	
	if($i == 1){$day = core_time_datediff($conStartDate, $receiveDate[$i]);}
	else{$day = core_time_datediff($receiveDate[$i-1], $receiveDate[$i]);}
	
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

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(31,22);
		// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(125,28);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(160,28);
		$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

		// $pdf->SetFont('AngsanaNew','',12);
		// $pdf->SetXY(32,32);
		// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
		// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,39);
		$buss_name=iconv('UTF-8','windows-874',"จ่ายครั้งที่");
		$pdf->MultiCell(15,4,$buss_name,0,'L',0);

		$pdf->SetXY(20,39);
		$buss_name=iconv('UTF-8','windows-874',"วันที่จ่าย");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(50,39);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(65,39);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินจ่าย");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(105,39);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนวัน");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(125,39);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนดอกเบี้ยที่ต้องจ่าย");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(165,39);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนหักเงินต้น");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(205,36);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้นคงเหลือ ณ วันจ่าย");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(235,36);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนดอกเบี้ยคงเหลือ ณ วันจ่าย");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(265,39);
		$buss_name=iconv('UTF-8','windows-874',"ช่องทางการจ่าย");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,40);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'C',0);
	}
	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$i");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(20,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$receiveDate[$i]");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	$pdf->SetXY(50,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$interestRate %");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(65,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($receiveAmount,2));
	$pdf->MultiCell(40,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(105,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$day");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(125,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($receiveInterest,2));
	$pdf->MultiCell(40,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(165,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($receivePriciple,2));
	$pdf->MultiCell(40,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(205,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($LeftPrinciple,2));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(235,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($LeftInterest,2));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(265,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$byChannel");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	/*
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,$cline+1);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	*/
    
	$cline += 5;
	$nub+=1;
	$a += 1;
	$i++;
}


// หาค่าจาก function ใน postgres
	$backAmt = pg_query("select \"thcap_backAmt\"('$contractID','$vfocusdate')");
	$backAmt = pg_fetch_result($backAmt,0);
	
	$backDueDate = pg_query("select \"thcap_backDueDate\"('$contractID','$vfocusdate')");
	$backDueDate = pg_fetch_result($backDueDate,0);
// จบการหาค่าจาก function ใน postgres


//if($num_row > 0){
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
	
	$qry_other = pg_query("select \"typePayID\", \"typePayRefValue\", \"typePayRefDate\", \"typePayAmt\", \"doerID\", \"doerStamp\", \"debtID\"
							from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\"='$contractID' and \"debtStatus\"='1' order by \"typePayRefDate\" ");
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

				// $pdf->SetFont('AngsanaNew','',12);
				// $pdf->SetXY(31,22);
				// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
				// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(125,28);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(160,28);
				$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

				// $pdf->SetFont('AngsanaNew','',12);
				// $pdf->SetXY(32,32);
				// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
				// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			
			$qry_type=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
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
				$doerusername=pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\"='$doerID'");
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

				// $pdf->SetFont('AngsanaNew','',12);
				// $pdf->SetXY(31,22);
				// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
				// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(125,28);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(160,28);
				$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

				// $pdf->SetFont('AngsanaNew','',12);
				// $pdf->SetXY(32,32);
				// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
				// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(290,4,$buss_name,B,'C',0);

			
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,39);
				$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(35,39);
				$buss_name=iconv('UTF-8','windows-874',"รายการ");
				$pdf->MultiCell(60,4,$buss_name,0,'C',0);

				$pdf->SetXY(95,39);
				$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิงของค่าใช้จ่าย");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(125,39);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);

				$pdf->SetXY(165,39);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);
				
				$pdf->SetXY(195,39);
				$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
				$pdf->MultiCell(50,4,$buss_name,0,'C',0);

				$pdf->SetXY(245,39);
				$buss_name=iconv('UTF-8','windows-874',"วันเวลาตั้งหนี้");
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,40);
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(31,22);
			// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(32,32);
			// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
			
		$cline += 6;
		
		// แยกวันที่ออกมา
		
		$dd = substr($signDate,8,2);
		$mm = substr($signDate,5,2);
		$yy = substr($signDate,0,4);
		
		// คำนวนเงิน
		
		// ==================================================================================
		// หาเงินต้น ดอกเบี้ยคงเหลือ ล่าสุด
		// ==================================================================================
				
		$sql_money_one = pg_query("	SELECT MAX(\"serial\") as \"maxserial\" 
									FROM public.\"thcap_temp_int_201201\" 
									WHERE 
										\"contractID\" = '$contractID' AND
										\"isReceiveReal\" != '0'
		");
		while($resultone=pg_fetch_array($sql_money_one))
		{
			$maxserial = $resultone["maxserial"];
		}
				
		if($maxserial != "")
		{
			$sql_money_two = pg_query("	SELECT \"LeftPrinciple\", \"LeftInterest\"
										FROM
											public.\"thcap_temp_int_201201\" 
										WHERE
											\"serial\" = '$maxserial'
			");
			while($resulttwo=pg_fetch_array($sql_money_two))
			{
				$LastLeftPrinciple = $resulttwo["LeftPrinciple"];
				$Last_LeftInterest = $resulttwo["LeftInterest"];	
			}	
		} else{
			$sql_money_three = pg_query("select \"conLoanAmt\" from public.\"thcap_contract\" where \"contractID\" = '$contractID' ");
			while($resultthree=pg_fetch_array($sql_money_three))
			{
				$LastLeftPrinciple = $resultthree["conLoanAmt"];
				$Last_LeftInterest = 0;
			}
		}

		//-------------------------------หาดอกเบี้ย
		$inter=pg_query("SELECT \"thcap_cal_InterestToDateFromLastPay\"('$contractID','$signDate')");
		$resin=pg_fetch_array($inter);
		list($money_function)=$resin;
		//-------------------------------จบการหาดอกเบี้ย
			
		$money_function = $money_function + $Last_LeftInterest;
			
		// ==================================================================================
		// ยอดหนี้ค้างชำระอื่นๆ
		// ==================================================================================
		$sql_other = pg_query("select \"tpID\" from account.\"thcap_typePay\" where \"isSubsti\" <> '1' ");
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
		$sql_other2 = pg_query("select \"tpID\" from account.\"thcap_typePay\" where \"isSubsti\" = '1' ");
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

		$sum_close_account = $LastLeftPrinciple + $costclose + $damage + $money_function + $plusone + $plustwo + $cloce_fine; // เงินรวม
		
		// จบการคำนวนเงิน
		
		/*$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);*/

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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(31,22);
			// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(32,32);
			// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"---------- คำนวณยอดปิดบัญชี ณ วันที่ $dd/$mm/$yy ----------");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);
		
		$nub +=1;
		/*$cline += 6;
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);*/
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(31,22);
			// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(32,32);
			// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$pdf->SetXY(5,32);
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(31,22);
			// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(32,32);
			// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		$cline += 5;
		
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ยอดดอกเบี้ย");
		$pdf->MultiCell(60,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(65,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($money_function,2));
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(31,22);
			// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(32,32);
			// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		$cline += 5;
		
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ยอดเงินเสียหายปิดบัญชีก่อนกำหนด");
		$pdf->MultiCell(60,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(65,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($damage,2));
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(31,22);
			// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(32,32);
			// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$pdf->SetXY(5,32);
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(31,22);
			// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(32,32);
			// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$pdf->SetXY(5,32);
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(31,22);
			// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(32,32);
			// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$pdf->SetXY(5,32);
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

				// $pdf->SetFont('AngsanaNew','',12);
				// $pdf->SetXY(31,22);
				// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
				// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(125,28);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(160,28);
				$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

				// $pdf->SetFont('AngsanaNew','',12);
				// $pdf->SetXY(32,32);
				// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
				// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
				$pdf->SetXY(5,32);
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
		
		/*
		$cline += 9;
		$nub+=3;
		
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(1,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($LastLeftPrinciple,2));
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetXY(15,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($money_function,2));
		$pdf->MultiCell(37,4,$buss_name,0,'R',0);

		$pdf->SetXY(57,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($damage,2));
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);

		$pdf->SetXY(90,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($plusone,2));
		$pdf->MultiCell(40,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(135,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($plustwo,2));
		$pdf->MultiCell(28,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(165,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($costclose,2));
		$pdf->MultiCell(38,4,$buss_name,0,'R',0);
		*/
			
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(31,22);
			// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(32,32);
			// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$pdf->SetXY(5,32);
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(31,22);
			// $buss_name=iconv('UTF-8','windows-874',"INT. ปกติ");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินต้น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(125,28);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเดือนผ่อนชำระคืน");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(160,28);
			$buss_name=iconv('UTF-8','windows-874',"ยอดจ่ายขั้นต่ำ/เดือน");
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

			// $pdf->SetFont('AngsanaNew','',12);
			// $pdf->SetXY(32,32);
			// $buss_name=iconv('UTF-8','windows-874',"$conLoanIniRate %");
			// $pdf->MultiCell(20,4,$buss_name,0,'L',0);

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
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
	$a += 1;
	$i++;
	
	
	
//}

$pdf->Output();
?>