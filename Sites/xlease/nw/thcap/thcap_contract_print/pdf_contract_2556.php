<?php
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../../config/config.php");
include("../../../core/core_functions.php");

/*-============================================================================-
								   สัญญาเช่าซื้อ	
								รับข้อมูลจาก Session
-============================================================================-*/
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$f_idno=$_GET["contractID"];

$test=GetAndUnsetSession('test');
 echo $test;
 
//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) พิมพ์สัญญาเช่าซื้อ', '$add_date')");
//ACTIONLOG---

//รับค่าสัญญาเ่ช่าซื้อ
$av_no = GetAndUnsetSession('contractID');
$av_datestart = GetAndUnsetSession('av_datestart');
$cus_name = GetAndUnsetSession('cus_name');
$cus_nid = GetAndUnsetSession('cus_nid');
$cus_addr = GetAndUnsetSession('cus_addr');
$fp_type = GetAndUnsetSession('fp_type');
$fp_band = GetAndUnsetSession('fp_band');
$fp_model = GetAndUnsetSession('fp_model');
$car_year = GetAndUnsetSession('car_year');
$car_regis = GetAndUnsetSession('car_regis');
$car_province = GetAndUnsetSession('car_province');
$fp_fc_category = GetAndUnsetSession('fp_fc_category');
$newcar = GetAndUnsetSession('newcar');
$oldcar = GetAndUnsetSession('oldcar');
$car_number = GetAndUnsetSession('car_number');
$car_engine = GetAndUnsetSession('car_engine');
$car_mi = GetAndUnsetSession('car_mi');
$car_color = GetAndUnsetSession('car_color');
$cost = GetAndUnsetSession('cost');
$fdate_thaidate = GetAndUnsetSession('fdate_thaidate');
$se_total = GetAndUnsetSession('se_total');
$fdate = GetAndUnsetSession('fdate');

//รับค่าเงือนไขการชำระเงิน
$InCashAmt = GetAndUnsetSession('CashAmt');
$InCashVat = GetAndUnsetSession('CashVat');
$InCashSum = GetAndUnsetSession('CashSum');
			
$InPaymentAmt = GetAndUnsetSession('PaymentAmt');
$InPaymentVat = GetAndUnsetSession('PaymentVat');
$InPaymentSum = GetAndUnsetSession('PaymentSum');
			
$InDownAmt = GetAndUnsetSession('DownAmt');
$InDownVat = GetAndUnsetSession('DownVat');
$InDownSum = GetAndUnsetSession('DownSum');
			
$InOtherAmt = GetAndUnsetSession('OtherAmt');
$InOtherVat = GetAndUnsetSession('OtherVat');
$InOtherSum = GetAndUnsetSession('OtherSum');
			
$InInvestCastAmt = GetAndUnsetSession('InvestCastAmt');
$InInvestCastVat = GetAndUnsetSession('InvestCastVat');
$InInvestCastSum = GetAndUnsetSession('InvestCastSum');
			
$InconTerm = GetAndUnsetSession('conTerm');
$InconMinPay = GetAndUnsetSession('conMinPay');
$IninterestRate = GetAndUnsetSession('interestRate');

$InLeasingInterestAmt = GetAndUnsetSession('LeasingInterestAmt');
$InLeasingInterestVat = GetAndUnsetSession('LeasingInterestVat');
$InLeasingInterestSum = GetAndUnsetSession('LeasingInterestSum');
			
$InNetLeasingAmt = GetAndUnsetSession('NetLeasingAmt');
$InNetLeasingVat = GetAndUnsetSession('NetLeasingVat');
$InNetLeasingSum = GetAndUnsetSession('NetLeasingSum');
			
$InLeasingByPeriodSum = GetAndUnsetSession('LeasingByPeriodSum');
			
$InconFirstDue = GetAndUnsetSession('conFirstDue');
$InconRepeatDueDay = GetAndUnsetSession('conRepeatDueDay');
/*-============================================================================-
								   สัญญาเช่าซื้อ	
								กำหนดรายละเอียด
-============================================================================-*/
$var1 = $av_no;	//เลขที่สัญญา
$var2 = $av_datestart;       //วันที่ทำสัญญา
$var3 = $cus_name;   //ชื่อผู้ทำสัญญา
$vcus_no_txt = $cus_nid; // บัตรประจำตัวประชาชนหมายเลข / ใบรับรองจดทะเบียน
$vcus_addr = $cus_addr; // ที่อยู่
$var4 = $fp_type;    //รถ
$var5 = $fp_band;   //ยี่ห้อ
$var6 = $fp_model;  //รุ่น
$var7 = $car_year;  //ปีจดทะเบียน
$var8 = $car_regis; //เลขทะเบียนรถ
$var9 = $car_province;  //จังหวัด
$var10 = $fp_fc_category;  //ชนิดรถ
$var11 = $newcar; //เป็นรถ
$var12 = $car_number; //เลขตัวถัง
$var13 = $car_engine; //เลขเครื่องยนต์
$var14 = $car_mi; //ระยะทางที่ปรากฎในเรือนมิเตอร์รถ
$var15 = $car_color; //สีตัวรถ
$var16 = number_format($cost,2); //ราคาเช่าซื้อไม่รวม VAT ไม่รวมเงินดาวน์
$var17 = $fdate_thaidate;  //เริ่มชำระงวดแรก
$var18 = $se_total;  //แบ่งชำระเป็น
$var19 = $fdate;  // ชำระทุกวันที่
$var20 = $oldcar; // รถใช้แล้ว


$costTEXT = NumberToText($cost);
	
/*-============================================================================-*/	

//============================= หน้าที่ 2 เงือนไขการชำระเงิน  ==========================================



//(1)

$CashSum = $InCashSum;//ราคาเงินสด (รวมเป็นเงิน)
$CashAmt = $InCashAmt;//ราคาเงินสด (จำนวน)
$CashVat = $InCashVat;//ราคาเงินสด (VAT)


//(2)

$PaymentAmt = $InPaymentAmt; //ราคาเงินจอง (จำนวน)
$PaymentVat = $InPaymentVat; //ราคาเงินจอง (VAT)
$PaymentSum = $InPaymentSum; //ราคาเงินจอง (รวมเป็นเงิน)

//(3)

$DownAmt = $InDownAmt; //ราคาเงินดาวน์ (จำนวน)
$DownVat = $InDownVat; //ราคาเงินดาวน์ (VAT)
$DownSum = $InDownSum; //ราคาเงินดาวน์ (รวมเป็นเงิน)

//(4)

$OtherAmt = $InOtherAmt; //ราคาค่าใช้จ่ายอื่นๆ ที่ผู้เช่าซื้อขอลงทุนเพิ่ม(จำนวน)
$OtherVat = $InOtherVat; //ราคาค่าใช้จ่ายอื่นๆ ที่ผู้เช่าซื้อขอลงทุนเพิ่ม(VAT)
$OtherSum = $InOtherSum; //ราคาค่าใช้จ่ายอื่นๆ ที่ผู้เช่าซื้อขอลงทุนเพิ่ม(รวมเป็นเงิน)

//(5)
$InvestCastSum = $InInvestCastSum; //เงินลงทุน(รวมเป็นเงิน)
$InvestCastAmt = $InInvestCastAmt; //เงินลงทุน(จำนวน)
$InvestCastVat = $InInvestCastVat; //เงินลงทุน(VAT)

//(6)
$LeasingPeriod = $InconTerm;//จำนวนงวด
$LeasingPeriodAmt = ""; //ระยะเวลาเช่าซื้อทั้งหมด(จำนวน)
$LeasingPeriodVat = ""; //ระยะเวลาเช่าซื้อทั้งหมด((VAT)
$LeasingPeriodSum = ""; //ระยะเวลาเช่าซื้อทั้งหมด((รวมเป็นเงิน)
//(7)
$DuringPeriodPrice = $InconMinPay;//งวดละ
$DuringPeriodAmt = ""; //ระยะเวลาต่องวด(จำนวน)
$DuringPeriodVat = ""; //ระยะเวลาต่องวด((VAT)
$DuringPeriodSum = ""; //ระยะเวลาต่องวด((รวมเป็นเงิน)

//(8)
$InterestRateByMonth = $InconTerm;	//ต่อเดือน
$InterestRateByYear= $InconTerm;	//ต่อปี
$InterestRateAmt = ""; 			//อัตราดอกเบี้ย(จำนวน)
$InterestRateVat = ""; 			//ระยะเวลาต่องวด((VAT)
$InterestRateSum = ""; 				//ระยะเวลาต่องวด((รวมเป็นเงิน)

//(9)
$LeasingInterestSum = $InLeasingInterestSum; //ดอกเบี้ยเช่าซื้อทั้งหมด((รวมเป็นเงิน)
$LeasingInterestAmt = $InLeasingInterestAmt; //ดอกเบี้ยเช่าซื้อทั้งหมด(จำนวน)
$LeasingInterestVat = $InLeasingInterestVat; //ดอกเบี้ยเช่าซื้อทั้งหมด((VAT)

//(10)
$NetLeasingSum = $InNetLeasingSum; //ค่าเช่าซื้อทั้งหมด((รวมเป็นเงิน)
$NetLeasingAmt = $InNetLeasingAmt; //ค่าเช่าซื้อทั้งหมด(จำนวน)
$NetLeasingVat = $InNetLeasingVat; //ค่าเช่าซื้อทั้งหมด((VAT)

//(11)
$LeasingByPeriodSum = $InLeasingByPeriodSum; //ค่าเช่าซื้อชำระเป็นงวดๆ((รวมเป็นเงิน)
$LeasingByPeriodAmt= ""; 
$LeasingByPeriodVat = ""; 


$PayFdate =$InconFirstDue; //เริ่มชำระงวดแรกวันที่
$PayNdate =$InconRepeatDueDay;//เริ่มชำระงวดต่อไปวันที่
	
// ------------------- PDF -------------------//
require('../../../thaipdfclass.php');

class PDF extends ThaiPDF
{

}

$pdf=new PDF('P','mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();
$pdf->SetAutoPageBreak(-3);
$page = $pdf->PageNo();	

$Y = 34;	
//เลขที่สัญญา	
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(132,$Y);
$title=iconv('UTF-8','windows-874',$var1);
$pdf->MultiCell(55,4,$title,0,'C',0);

$Y += 13;	
//วันที่ทำสัญญา	
$pdf->SetXY(131,$Y);
$title=iconv('UTF-8','windows-874',$var2);
$pdf->MultiCell(44,4,$title,0,'C',0);

$Y += 17;	
//ผู้เช่าซื้อ	
$pdf->SetXY(108,$Y);
$title=iconv('UTF-8','windows-874',$var3);
$pdf->MultiCell(80,5,$title,0,'L',0);

$Y += 9;
//บัตรประจำตัวประชาชนหมายเลข / ใบรับรองจดทะเบียน	
$pdf->SetXY(128,$Y);
$title=iconv('UTF-8','windows-874',$vcus_no_txt);
$pdf->MultiCell(61,5,$title,0,'L',0);

$Y += 9;
//ที่อยู่
$pdf->SetXY(44,$Y);
$title=iconv('UTF-8','windows-874',$vcus_addr);
$pdf->MultiCell(143,5,$title,0,'L',0);

$Y = 131;	
//รถ	
$pdf->SetXY(34,$Y);
$title=iconv('UTF-8','windows-874',$var4);
$pdf->MultiCell(39,4,$title,0,'L',0);
//ยี่ห้อ
$pdf->SetXY(85,$Y);
$title=iconv('UTF-8','windows-874',$var5);
$pdf->MultiCell(41,4,$title,0,'L',0);
//แบบ
$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874','');
$pdf->MultiCell(45,4,$title,0,'L',0);

$Y += 8;
//สีตัวรถ
$pdf->SetXY(34,$Y);
$title=iconv('UTF-8','windows-874',$var15);
$pdf->MultiCell(70,4,$title,0,'L',0);
//รุ่น
$pdf->SetXY(115,$Y);
$title=iconv('UTF-8','windows-874',$var6);
$pdf->MultiCell(69,4,$title,0,'L',0);

$Y += 8;
//ชนิดรถ
$pdf->SetXY(49,$Y);
$title=iconv('UTF-8','windows-874',$var10);
$pdf->MultiCell(135,4,$title,0,'L',0);

$Y += 8;
//เลขทะเบียนรถ
$pdf->SetXY(60,$Y);
$title=iconv('UTF-8','windows-874',$var8);
$pdf->MultiCell(45,4,$title,0,'L',0);
//จังหวัด
$pdf->SetXY(142,$Y);
$title=iconv('UTF-8','windows-874',$var9);
$pdf->MultiCell(42,4,$title,0,'L',0);
//เป็นรถ
/*$pdf->SetXY(183,$Y);
$title=iconv('UTF-8','windows-874',$var11);
$pdf->MultiCell(70,4,$title,0,'L',0);*/

$Y += 8;
//เลขตัวถัง
$pdf->SetXY(56,$Y);
$title=iconv('UTF-8','windows-874',$var12);
$pdf->MultiCell(127,4,$title,0,'L',0);

$Y += 8;
//เลขเครื่องยนต์
$pdf->SetXY(57,$Y);
$title=iconv('UTF-8','windows-874',$var13);
$pdf->MultiCell(126,4,$title,0,'L',0);

$Y += 6;
//ระยะไมล์
$pdf->SetXY(127,$Y);
$title=iconv('UTF-8','windows-874',$var14);
$pdf->MultiCell(30,4,$title,0,'C',0);

$pdf->SetXY(38,$Y);
$title=iconv('UTF-8','windows-874',$var11);
$pdf->MultiCell(30,4,$title,0,'C',0);

$pdf->SetXY(62,$Y);
$title=iconv('UTF-8','windows-874',$var20);
$pdf->MultiCell(30,4,$title,0,'C',0);

$Y = 267;
//ราคาไม่รวม VAT
$pdf->SetXY(138,$Y);
$title=iconv('UTF-8','windows-874',$var16);
$pdf->MultiCell(40,4,$title,0,'R',0);

$Y = 276;
//จำนวนเงินแบบตัวอักษร
$pdf->SetXY(30,$Y);
$title=iconv('UTF-8','windows-874',$costTEXT);
$pdf->MultiCell(120,4,$title,0,'L',0);

$pdf->AddPage();
$x0=50;
$x1=92;
$x2=115;
$x3=142;
//(1)
$p=57;
$pdf->SetXY($x0,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',number_format($CashAmt,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',number_format($CashVat,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',number_format($CashSum,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

//(2)
$p+=8;
$pdf->SetXY($x0,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',number_format($PaymentAmt,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',number_format($PaymentVat,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',number_format($PaymentSum,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

//(3)
$p+=8;
$pdf->SetXY($x0,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',number_format($DownAmt,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',number_format($DownVat,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',number_format($DownSum,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

//(4)
$p+=8;
$pdf->SetXY($x0,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',number_format($OtherAmt,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',number_format($OtherVat,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',number_format($OtherSum,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

//(5)
$p+=8;
$pdf->SetXY($x0,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',number_format($InvestCastAmt,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',number_format($InvestCastVat,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',number_format($InvestCastSum,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

//(6)
$p+=8;
$pdf->SetXY(42,$p-1);
$title=iconv('UTF-8','windows-874',$LeasingPeriod);
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

//(7)
$p+=8;
$pdf->SetXY(55,$p-1);
$title=iconv('UTF-8','windows-874',number_format($DuringPeriodPrice,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

//(8)
$p+=8;
$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(22,$p-1);
$title=iconv('UTF-8','windows-874',number_format($InterestRateByMonth,4,'.',''));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY(53,$p-1);
$title=iconv('UTF-8','windows-874',number_format($InterestRateByYear,4,'.',''));
$pdf->MultiCell(42,4,$title,0,'R',0);
$pdf->SetFont('AngsanaNew','B',14);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,0,'R',0);

//(9)
$p+=8;
$pdf->SetXY(42,$p);
$title=iconv('UTF-8','windows-874',$varI_4);
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',number_format($LeasingInterestAmt,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',number_format($LeasingInterestVat,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',number_format($LeasingInterestSum,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

//(10)
$p+=8;
$pdf->SetXY(42,$p);
$title=iconv('UTF-8','windows-874',$varJ_4);
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',number_format($NetLeasingAmt,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',number_format($NetLeasingVat,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',number_format($NetLeasingSum,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

//(11)
$p+=8;
$pdf->SetXY(42,$p);
$title=iconv('UTF-8','windows-874',$varK_4);
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x1,$p);
$title=iconv('UTF-8','windows-874',number_format($LeasingByPeriodAmt,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x2,$p);
$title=iconv('UTF-8','windows-874',number_format($LeasingByPeriodVat,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->SetXY($x3,$p);
$title=iconv('UTF-8','windows-874',number_format($LeasingByPeriodSum,2,'.',','));
$pdf->MultiCell(42,4,$title,0,'R',0);

//เริ่มชำระงวดแรก
$p+=8;
$pdf->SetXY($x3-2,$p-1);
$title=iconv('UTF-8','windows-874',$PayFdate);
$pdf->MultiCell(42,4,$title,0,'R',0);
//เริ่มชำระงวดถัดไป
$p+=8;
$pdf->SetXY(65,$p-1);
$title=iconv('UTF-8','windows-874',$PayNdate);
$pdf->MultiCell(42,4,$title,0,'R',0);

$pdf->Output();	

?>



