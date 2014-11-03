<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
include("../function/nameMonth.php");

$receiveDate=$_GET["receiveDate"];
$chkanalyze=$_GET["chkanalyze"]; //สถานะว่าเป็นแจกแจงหรือไม่
$typepay=$_GET["typepay"]; //ประเภทที่ต้องการให้แสดง
$typepay2=$_GET["typepay"];
$txtmonth=nameMonthTH($month);

//หาชื่อประเภท
$qrytype=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typepay'");
$restype=pg_fetch_array($qrytype);
list($txtdesc)=$restype;

if($chkanalyze=="1"){
	if($typepay==""){
		$txttype="(ประเภทค่าใช้จ่าย : ทุกประเภท)";
	}else{
		$txttype="(ประเภทค่าใช้จ่าย : $txtdesc)";
	}
}else{
	$txttype="(ประเภทค่าใช้จ่าย : ทุกประเภท)";
}

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',10);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(200,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,25);
$buss_name1=iconv('UTF-8','windows-874',"ประจำเดือน $txtmonth $year $txttype");
$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
$pdf->MultiCell(18,4,$buss_name,0,'L',0);

$pdf->SetXY(20,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(42,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(77,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(102,32);
$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(142,32);
$buss_name=iconv('UTF-8','windows-874',"รายได้");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(157,32);
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(172,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

	
$pdf->SetXY(5,38);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก
$pdf->SetFont('AngsanaNew','',13);
$cline = 42; 
$nub = 1;
if($chkanalyze=="1"){ //กรณีเป็นแบบแจกแจง						
	if($typepay==""){ //กรณีเลือกแสดงทั้งหมด
		$qryreceipt=pg_query("select \"contractID\",a.\"receiptID\",date(\"receiveDate\") as \"receiveDate\",\"tpDesc\" as \"tpdesc\",
		\"netAmt\" as \"netamt\",\"vatAmt\" as \"vatamt\",\"debtAmt\" as \"debtamt\",\"debtID\" as \"debtid\",\"nameChannel\" as \"channel\",
		\"cusFullname\",\"typePayID\"
		from thcap_v_receipt_otherpay a
		left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\"
		where date(\"receiveDate\")='$receiveDate' order by \"receiveDate\",a.\"receiptID\"");							
		$showtype="0";
	}else{
		//ดึงข้อมูลใน thcap_v_receipt_otherpay มาแสดง
		$qryreceipt=pg_query("select \"contractID\",a.\"receiptID\",date(\"receiveDate\") as \"receiveDate\",\"tpDesc\" as \"tpdesc\",
		\"netAmt\" as \"netamt\",\"vatAmt\" as \"vatamt\",\"debtAmt\" as \"debtamt\",\"debtID\" as \"debtid\",\"nameChannel\" as \"channel\",
		\"cusFullname\" ,\"typePayID\"
		from thcap_v_receipt_otherpay a
		left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\"
		where date(\"receiveDate\")='$receiveDate' and \"typePayID\"='$typepay'
		order by \"receiveDate\",a.\"receiptID\"");	
		$numqry2=pg_num_rows($qryreceipt);
																		
		//กรณีเลือก type ที่ไม่มีอยู่ใน thcap_v_receipt_otherpay แสดงว่าอาจเลือก ชำระเงินต้น หรือ ดอกเบี้ย
		if($numqry2==0){
			$qryreceipt=pg_query("select a.\"contractID\",a.\"receiptID\",date(a.\"receiveDate\") as \"receiveDate\",\"receivePriciple\",\"receiveInterest\",\"receiveAmount\",\"nameChannel\" as \"channel\",\"cusFullname\"
			FROM thcap_temp_int_201201 a
			left join thcap_v_receipt_otherpay b on a.\"receiptID\"=b.\"receiptID\"
			left join thcap_temp_receipt_details c on a.\"receiptID\"=c.\"receiptID\"
			where date(a.\"receiveDate\")='$receiveDate' and a.\"receiptID\" is not null order by a.\"receiveDate\",a.\"receiptID\"");								
			$numqry=pg_num_rows($qryreceipt);
			if($numqry>0){
				$showtype="0";
				$chk=1;
			}
		}else{		
			$showtype="0";
		}
	}
}else{
	$qryreceipt=pg_query("select \"contractID\",a.\"receiptID\",date(\"receiveDate\") as \"receiveDate\",\"tpDesc\" as \"tpdesc\",
	\"netAmt\" as \"netamt\",\"vatAmt\" as \"vatamt\",\"debtAmt\" as \"debtamt\",\"debtID\" as \"debtid\",\"nameChannel\" as \"channel\",
	\"cusFullname\" ,\"typePayID\"
	from thcap_v_receipt_otherpay a
	left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\"
	where date(\"receiveDate\")='$receiveDate' order by \"receiveDate\",a.\"receiptID\"");							
	$showtype="0";
}


$i=0;
$p=0;
$sum_amt = 0;
$sum_all = 0;
$sum_allnet = 0;
$sum_allvat = 0;
$sumnet = 0;
$sumvat = 0;
$sum_alltotalnet=0;
$sum_alltotalvat=0;
$sum_alltotal=0;
$old_doerID="";
$old_receiptID="";
$old_receiveDate="";
$old_cusname="";
while($result=pg_fetch_array($qryreceipt)){
    $receiveDate=$result["receiveDate"];
	$receiptID=$result["receiptID"];
	$cusfullname=$result["cusfullname"];
	$contractID=$result["contractID"];
	$tpdesc=$result["tpdesc"]; 
	$netamt=$result["netamt"];
	$netamt2=number_format($netamt,2);
	$vatamt=$result["vatamt"];
	$vatamt2=number_format($vatamt,2);
	$debtAmt=$result["debtamt"];
	$debtAmt2=number_format($debtAmt,2);
	$debtid=$result["debtid"];
	$channel=$result["channel"];
	
	if($typepay==""){ //กรณีเลือกทั้งหมด
		$typePayID=$result["typePayID"]; //ประเภทการจ่ายของแต่ละสัญญา
	}
							
	$receivePriciple=$result["receivePriciple"];
	$receiveInterest=$result["receiveInterest"];
	$receiveAmount=$result["receiveAmount"];
	
	//ถ้า cusfullname เป็นค่าว่างให้ไปค้นหาชื่อจาก mysql มีโอกาสพบค่าว่างได้เนื่องจากเลขที่ใบเสร็จเก่าอาจยังไม่ได้เก็บชื่อลูกค้าทำให้ไม่พบข้อมูลใน pg
	$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
	$resname= pg_fetch_array($qryname);
	if($cusfullname==""){
		$cusfullname=$resname["thcap_fullname"];
	}else{
		$cusfullname=$cusfullname;
	}
	
	//ตรวจสอบว่าเป็นสัญญาประเภทใด
	$contype=pg_creditType($contractID);
							
	//หารหัสผ่อนชำระตามสัญญากู้ว่าใช้รหัสอะไร
	$paytype=pg_getminpaytype($contractID); //หารหัสผ่อนชำระตามสัญญากู้ เช่น 1000
	
	//ถ้าเป็นสัญญาประเภท LOAN แสดงว่ามี type เงินต้นและดอกเบี้ย
	if($contype=='LOAN' || $contype=='PERSONAL_LOAN'){ 
		$paytype_a=pg_getprincipletype($contractID);//หารหัสผ่อนชำระตามสัญญากู้-คืนเงินต้น เช่น 1001
		$paytype_b=pg_getinteresttype($contractID);//หารหัสผ่อนชำระตามสัญญากู้-ดอกเบี้ย เช่น 1002
	}
							
	if($typepay==$paytype_a){ //กรณีเป็นชำระคืนเงินต้น
		//หาชื่อประเภทการจ่าย
		$qrytypename=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typepay'");
		list($tpdescname)=pg_fetch_array($qrytypename);
		$tpdesc=$tpdescname;
		
		$netamt2=number_format($receivePriciple,2);
		$debtAmt2=number_format($receivePriciple,2);
		if($receivePriciple=='0'){ 
			$showtype=2; //ถ้าค่าเท่ากับ 0 จะไม่แสดงข้อมูลนั้น
		}else{
			$showtype=1; //กำหนดให้แสดงข้อมูลนั้น
		}
	}else if($typepay==$paytype_b){ //กรณีดอกเบี้ย
		$qrytypename=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typepay'");
		list($tpdescname)=pg_fetch_array($qrytypename);
		$tpdesc=$tpdescname;
								
		$netamt2=number_format($receiveInterest,2);
		$debtAmt2=number_format($receiveInterest,2);
		if($receiveInterest=='0'){
			$showtype=2; //กำหนดไม่ให้แสดง record นี้
		}else{
			$showtype=1; //กำหนดให้แสดง record นี้
		}
	}
if($showtype!="2"){
	//กรณีที่เลือกชำระเงินต้นและดอกเบี้ย แต่ type ไม่ตรงกับที่เลือก ก็ไม่ให้แสดงค่าด้วย
	if($chk=="1" and $typepay!="" and $typepay!=$paytype and $typepay!=$paytype_a and $typepay!=$paytype_b and $paytype_a!="" and $paytype_b!=""){
		$showtype=2; //กำหนดไม่ให้แสดง record นี้
	}
	
	if($receiptID!=$old_receiptID){
		$i+=1;
	}else{
		$p++;
	}
	
	
   $pdf->SetFont('AngsanaNew','B',10);
   
    //กรณีแสดงช่องทาง
	if($receiptID!=$old_receiptID and $old_receiptID!=""){ 
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(130,$cline-3);
		$buss_name=iconv('UTF-8','windows-874',"----------------------------------------------------------------------");
		$pdf->MultiCell(78,4,$buss_name,0,'R',0);
		
		//แสดงช่องทางการชำระทั้งหมด
		$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$old_receiptID' order by \"ChannelAmt\" DESC");
		$sumamt=0;
		while($resstar=pg_fetch_array($qryredstar)){
			$chan=$resstar["byChannel"];
			$amt=$resstar["ChannelAmt"];
					
			if($nub == 44){
				$nub = 1;
				$cline = 42;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',16);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(200,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);


				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(5,25);
				$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
				$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(200,4,$buss_name,0,'R',0);

				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',10);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
				$pdf->MultiCell(18,4,$buss_name,0,'L',0);

				$pdf->SetXY(20,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);


				$pdf->SetXY(42,32);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetXY(77,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(102,32);
				$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);

				$pdf->SetXY(142,32);
				$buss_name=iconv('UTF-8','windows-874',"รายได้");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);

				$pdf->SetXY(157,32);
				$buss_name=iconv('UTF-8','windows-874',"VAT");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);

				$pdf->SetXY(172,32);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);
					
				$pdf->SetXY(5,38);
				$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);	
			}
			
			if($chan=="999"){
				$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
			}else{
				//นำไปค้นหาในตาราง BankInt
				$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
				$ressearch=pg_fetch_array($qrysearch);
				list($BAccount,$BName)=$ressearch;
				$txtchannel3="ช่องทาง : $BAccount-$BName";
			}
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(142,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$txtchannel3");
			$pdf->MultiCell(50,4,$buss_name,0,'L',0);
					
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(172,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($amt,2));
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);
					
			$cline += 5;
			$nub+=1;					
		}
			
		if($nub == 44){
			$nub = 1;
			$cline = 42;
			$pdf->AddPage();
			
			$pdf->SetFont('AngsanaNew','B',16);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,25);
			$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
			$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(200,4,$buss_name,0,'R',0);

			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
			$pdf->MultiCell(18,4,$buss_name,0,'L',0);

			$pdf->SetXY(20,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(42,32);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetXY(77,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(102,32);
			$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetXY(142,32);
			$buss_name=iconv('UTF-8','windows-874',"รายได้");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(157,32);
			$buss_name=iconv('UTF-8','windows-874',"VAT");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(172,32);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
				
			$pdf->SetXY(5,38);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			
		}
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(130,$cline-3);
		$buss_name=iconv('UTF-8','windows-874',"----------------------------------------------------------------------");
		$pdf->MultiCell(78,4,$buss_name,0,'R',0);
		
		//****************หารวมเงินใบเสร็จ							
		$qrysumtotal=pg_query("select a.\"receiptID\",sum(\"netAmt\"),sum(\"vatAmt\"),sum(\"debtAmt\")
		from thcap_v_receipt_otherpay a 
		left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
		where date(\"receiveDate\")='$receiveDate'  and a.\"receiptID\" = '$old_receiptID'	group by a.\"receiptID\"");
		list($receipt2,$sumnetamt,$sumvatamt,$sumall_amt)=pg_fetch_array($qrysumtotal);
								
		if($typepay==$paytype_a || $typepay==$paytype_b){
			$sumnet=$sumnet;
			$sumvat=$sumvat;
			$sum_amt=$sum_amt;
		}else{
			$sumnet=$sumnetamt;
			$sumvat=$sumvatamt;
			$sum_amt=$sumall_amt;
		}
		//***********จบหาเงินรวมใบเสร็จ
								
		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมเงินใบเสร็จนี้");
		$pdf->MultiCell(137,4,$buss_name,0,'R',0);
			
		$pdf->SetXY(142,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumnet,2));
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(157,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumvat,2));
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(172,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sum_amt,2));
		$pdf->MultiCell(35,4,$buss_name,0,'R',0);
			
		$pdf->SetXY(5,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________");
		$pdf->MultiCell(203,4,$buss_name,0,'R',0);
		$p=0;
		$cline+=5;
		$nub+=1;
	}
	if($nub == 44){
		$nub = 1;
		$cline = 42;
		$pdf->AddPage();
			
		$pdf->SetFont('AngsanaNew','B',16);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);


		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
		$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(18,4,$buss_name,0,'L',0);

		$pdf->SetXY(20,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(42,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetXY(77,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(102,32);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(142,32);
		$buss_name=iconv('UTF-8','windows-874',"รายได้");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(157,32);
		$buss_name=iconv('UTF-8','windows-874',"VAT");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(172,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				
		$pdf->SetXY(5,38);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			
	}	
	if(($receiveDate != $old_receiveDate) && $nub != 1){
		if($sum_all>0){
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ยอดรวมต่อวัน ");
			$pdf->MultiCell(137,4,$buss_name,0,'R',0);
			
			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(142,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_allnet,2));
			$pdf->MultiCell(15,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(157,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_allvat,2));
			$pdf->MultiCell(15,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(172,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_all,2));
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'R',0);
			
			$sum_allnet=0;
			$sum_allvat=0;
			$sum_all=0;
			$cline+=5;
			$nub+=1;
		}
	}
	if($receiptID==$old_receiptID){
		$receiptID2="";
	}else{
		$receiptID2=$receiptID;
		$sumnet = 0;
		$sumvat = 0;
		$sum_amt = 0;
	}
	if($old_receiveDate==$receiveDate){
		$receiveDate2="";
	}else{
		$receiveDate2=$receiveDate;
	}
							
	if($old_cusname==$cusfullname and $receiptID==$old_receiptID){
		$cusfullname2="";
	}else{
		$cusfullname2=$cusfullname;
	}
	
	if($showtype==1){ //กรณีเลือกประเภทเป็นชำระคืนเงินต้นหรือดอกเบี้ย
		if($nub == 44){
			$nub = 1;
			$cline = 42;
			$pdf->AddPage();
			
			$pdf->SetFont('AngsanaNew','B',16);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,25);
			$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
			$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(200,4,$buss_name,0,'R',0);

			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
			$pdf->MultiCell(18,4,$buss_name,0,'L',0);

			$pdf->SetXY(20,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);


			$pdf->SetXY(42,32);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetXY(77,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(102,32);
			$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetXY(142,32);
			$buss_name=iconv('UTF-8','windows-874',"รายได้");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(157,32);
			$buss_name=iconv('UTF-8','windows-874',"VAT");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(172,32);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
				
			$pdf->SetXY(5,38);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			
		}
		
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$receiveDate2");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(20,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$receiptID2");
		$pdf->MultiCell(22,4,$buss_name,0,'C',0);

		$pdf->SetXY(42,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$cusfullname2");
		$pdf->MultiCell(35,4,$buss_name,0,'L',0);

		$pdf->SetXY(70,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$contractID");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(102,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$tpdesc");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(142,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$netamt2");
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetXY(157,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$vatamt2");
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(172,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$debtAmt2");
		$pdf->MultiCell(35,4,$buss_name,0,'R',0);
		
		$nub+=1;
		if($typepay==$paytype_a){
			$cline += 5;
		}	
	
		$old_doerID=$doerID;
		$old_receiptID=$receiptID;
		$old_receiveDate=$receiveDate;
		$old_cusname=$cusfullname;
		$sum_amt+=$debtAmt;
		$sumnet+=$netamt;
		$sumvat+=$vatamt;
		if($typepay==$paytype_b){
			$sum_amt+=$receiveAmount;
			$sumnet+=$receiveAmount;
			$sumvat+=$vatamt;
			$sum_all+=$receiveInterest;
			$sum_allnet+=$receiveInterest;
			$sum_alltotalnet+=$receiveInterest;
			$sum_alltotal+=$receiveInterest;
		}else if($typepay==$paytype_a){
			$sum_amt+=$receiveAmount;
			$sumnet+=$receiveAmount;
			$sumvat+=$vatamt;
			$sum_all+=$receivePriciple;
			$sum_allnet+=$receivePriciple;
			$sum_alltotalnet+=$receivePriciple;
			$sum_alltotal+=$receivePriciple;
		}else{
			$sum_allnet+=$netamt;
			$sum_all+=$debtAmt;
			$sum_alltotalnet+=$netamt;
			$sum_alltotal+=$debtAmt;
		}
		$sum_allvat+=$vatamt;								
		$sum_alltotalvat+=$vatamt;
	}
	
	if($showtype=="0"){ //กำหนดให้แสดง record นี้กรณีไม่ได้เลือกชำระเงินต้นหรือดอกเบี้ย					
		if($nub == 44){
			$nub = 1;
			$cline = 42;
			$pdf->AddPage();
			
			$pdf->SetFont('AngsanaNew','B',16);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,25);
			$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
			$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(200,4,$buss_name,0,'R',0);

			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
			$pdf->MultiCell(18,4,$buss_name,0,'L',0);

			$pdf->SetXY(20,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);


			$pdf->SetXY(42,32);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetXY(77,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(102,32);
			$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetXY(142,32);
			$buss_name=iconv('UTF-8','windows-874',"รายได้");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(157,32);
			$buss_name=iconv('UTF-8','windows-874',"VAT");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(172,32);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
				
			$pdf->SetXY(5,38);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			
		}
		
		//กรณีเป็นแบบแจกแจง และเป็นการชำระเงินต้น
		if($chkanalyze=="1" and (($typepay==$paytype || $typePayID==$paytype) || $typepay==$paytype_a || $typepay==$paytype_b)){
			$qry_2012=pg_query("select \"receivePriciple\",\"receiveInterest\",\"receiveAmount\" FROM thcap_temp_int_201201 where \"receiptID\"='$receiptID'");
			list($receivePriciple,$receiveInterest,$receiveAmount)=pg_fetch_array($qry_2012);
					
			$netamt2=number_format($receivePriciple,2);
			$debtAmt2=number_format($receivePriciple,2);
			if($receivePriciple=='0'){
				$show=1; //กรณีเงินต้นเป็น 0 จะไม่แสดงข้อมูล
			}else{
				$show=0; //เงินต้นมีค่า จะแสดงข้อมูล
			}
		}else{  
			$show=0; //กรณีค่าอื่นๆ ให้แสดงข้อมูลปกติ
		}
		if($show==0 and $typepay!=$paytype_b){ //แสดงข้อมูลตามทั่วไป ยกเว้นที่เลือกประเภท "ดอกเบี้ย" และถ้าเงินต้นเป็น 0 จะไม่แสดง record ส่วนนี้ด้วย	
			if(($paytype==$typePayID || $paytype==$typepay2) and $chkanalyze=="1"){ //กรณีเป็นการแจกแจง และเลือกแสดงผ่อนชำระให้แจกแจงเงินต้นด้วย
				$qrytypename=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$paytype_a'");
				list($tpdescname)=pg_fetch_array($qrytypename);
				$tpdesc=$tpdescname;
			}
			
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$receiveDate2");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(20,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$receiptID2");
			$pdf->MultiCell(22,4,$buss_name,0,'C',0);

			$pdf->SetXY(42,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$cusfullname2");
			$pdf->MultiCell(35,4,$buss_name,0,'L',0);

			$pdf->SetXY(70,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(102,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$tpdesc");
			$pdf->MultiCell(45,4,$buss_name,0,'L',0);
			
			$pdf->SetXY(142,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$netamt2");
			$pdf->MultiCell(15,4,$buss_name,0,'R',0);

			$pdf->SetXY(157,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$vatamt2");
			$pdf->MultiCell(15,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(172,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$debtAmt2");
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);
			
			$nub+=1;
			if($typepay==$paytype_a){
				$cline += 5;
			}
		}
		//ค่าดอกเบี้ย แสดงค่านี้ก็ต่อเมื่อเลือกแบบแจกแจง เลือกแสดงทั้งหมด,ผ่อนชำระตามสัญญากู้ และ ดอกเบี้ย
		if($chkanalyze=="1" and $debtid=="" and ($typepay=="" || $typepay==$paytype || $typepay==$paytype_b)){ 
			//หาชื่อเต็มของ type ดอกเบี้ย
			$qrytypename=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$paytype_b'");
			list($tpdescname)=pg_fetch_array($qrytypename);
			$tpdesc=$tpdescname;
			
			if($receiveInterest > 0){ //ถ้าดอกเบี้ยมีค่า  (ถ้าไม่มีค่าจะไม่แสดงแถวนี้)
				if($nub == 44){
					$nub = 1;
					$cline = 42;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',16);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(200,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
					$pdf->MultiCell(200,4,$buss_name,0,'C',0);


					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
					$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

					$pdf->SetFont('AngsanaNew','',10);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(200,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(200,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','B',10);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(18,4,$buss_name,0,'L',0);

					$pdf->SetXY(20,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);


					$pdf->SetXY(42,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
					$pdf->MultiCell(35,4,$buss_name,0,'C',0);

					$pdf->SetXY(77,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(102,32);
					$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
					$pdf->MultiCell(40,4,$buss_name,0,'C',0);

					$pdf->SetXY(142,32);
					$buss_name=iconv('UTF-8','windows-874',"รายได้");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);

					$pdf->SetXY(157,32);
					$buss_name=iconv('UTF-8','windows-874',"VAT");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);

					$pdf->SetXY(172,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
					$pdf->MultiCell(35,4,$buss_name,0,'C',0);
						
					$pdf->SetXY(5,38);
					$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(200,4,$buss_name,0,'C',0);	
				}
				
				if($show==0 and ($typepay=="" || $typepay==$paytype)){ //กรณีเงินต้นมีค่า และเลือกประเภท  "ทั้งหมด" และ "ผ่อนชำระตามสัญญากู้"
					$cline += 5;
					$pdf->SetFont('AngsanaNew','',10);
					$pdf->SetXY(5,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);

					$pdf->SetXY(20,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(22,4,$buss_name,0,'C',0);

					$pdf->SetXY(42,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(35,4,$buss_name,0,'L',0);

					$pdf->SetXY(77,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);
					
					$pdf->SetXY(102,$cline);
					$buss_name=iconv('UTF-8','windows-874',$tpdesc);
					$pdf->MultiCell(40,4,$buss_name,0,'L',0);
					
					$pdf->SetXY(142,$cline);
					$buss_name=iconv('UTF-8','windows-874',number_format($receiveInterest,2));
					$pdf->MultiCell(15,4,$buss_name,0,'R',0);

					$pdf->SetXY(157,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$vatamt2");
					$pdf->MultiCell(15,4,$buss_name,0,'R',0);
					
					$pdf->SetXY(172,$cline);
					$buss_name=iconv('UTF-8','windows-874',number_format($receiveInterest,2));
					$pdf->MultiCell(35,4,$buss_name,0,'R',0);

				}else{
					$pdf->SetFont('AngsanaNew','',10);
					$pdf->SetXY(5,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$receiveDate2");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);

					$pdf->SetXY(20,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$receiptID2");
					$pdf->MultiCell(22,4,$buss_name,0,'C',0);

					$pdf->SetXY(42,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$cusfullname2");
					$pdf->MultiCell(35,4,$buss_name,0,'L',0);

					$pdf->SetXY(70,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$contractID");
					$pdf->MultiCell(35,4,$buss_name,0,'C',0);
					
					$pdf->SetXY(102,$cline);
					$buss_name=iconv('UTF-8','windows-874',$tpdesc);
					$pdf->MultiCell(40,4,$buss_name,0,'L',0);
					
					$pdf->SetXY(142,$cline);
					$buss_name=iconv('UTF-8','windows-874',number_format($receiveInterest,2));
					$pdf->MultiCell(15,4,$buss_name,0,'R',0);

					$pdf->SetXY(157,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$vatamt2");
					$pdf->MultiCell(15,4,$buss_name,0,'R',0);
					
					$pdf->SetXY(172,$cline);
					$buss_name=iconv('UTF-8','windows-874',number_format($receiveInterest,2));
					$pdf->MultiCell(35,4,$buss_name,0,'R',0);
				}
				$nub+=1;
			}
		
			if($nub == 44){
				$nub = 1;
				$cline = 42;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',16);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(200,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);


				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(5,25);
				$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
				$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(200,4,$buss_name,0,'R',0);

				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',10);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
				$pdf->MultiCell(18,4,$buss_name,0,'L',0);

				$pdf->SetXY(20,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);


				$pdf->SetXY(42,32);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
				$pdf->MultiCell(35,4,$buss_name,0,'C',0);

				$pdf->SetXY(77,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(102,32);
				$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);

				$pdf->SetXY(142,32);
				$buss_name=iconv('UTF-8','windows-874',"รายได้");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);

				$pdf->SetXY(157,32);
				$buss_name=iconv('UTF-8','windows-874',"VAT");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);

				$pdf->SetXY(172,32);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);

				$pdf->SetXY(187,32);
				$buss_name=iconv('UTF-8','windows-874',"ประเภท");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);
					$pdf->SetXY(187,36);
					$buss_name=iconv('UTF-8','windows-874',"การรับชำระ");
					$pdf->MultiCell(20,4,$buss_name,0,'C',0);
					
				$pdf->SetXY(5,38);
				$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			}
		
			$sumnetdeb=number_format(($receivePriciple+$receiveInterest),2);
		}
		$old_doerID=$doerID;
		$old_receiptID=$receiptID;
		$old_receiveDate=$receiveDate;
		$old_cusname=$cusfullname;
		$sum_amt+=$debtAmt;
		$sumnet+=$netamt;
		$sumvat+=$vatamt;
		if($typepay==$paytype_b){
			$sum_amt+=$receiveAmount;
			$sumnet+=$receiveAmount;
			$sumvat+=$vatamt;
			$sum_all+=$receiveInterest;
			$sum_allnet+=$receiveInterest;
			$sum_alltotalnet+=$receiveInterest;
			$sum_alltotal+=$receiveInterest;
		}else if($typepay==$paytype_a){
			$sum_amt+=$receiveAmount;
			$sumnet+=$receiveAmount;
			$sumvat+=$vatamt;
			$sum_all+=$receivePriciple;
			$sum_allnet+=$receivePriciple;
			$sum_alltotalnet+=$receivePriciple;
			$sum_alltotal+=$receivePriciple;
		}else{
			$sum_allnet+=$netamt;
			$sum_all+=$debtAmt;
			$sum_alltotalnet+=$netamt;
			$sum_alltotal+=$debtAmt;
		}
		$sum_allvat+=$vatamt;
		$sum_alltotalvat+=$vatamt;
	} //จบเงื่อนไข showtype==0
	if($showtype==1){
		$showtype=2;
	}

	if($typepay!=$paytype_a and $typepay!=$paytype_b){
		$cline += 5;
    }
	
	if($receiveInterest >0 and $typepay==$paytype_b){
		$cline += 5;
	}
} //end if	
}//end while
	
$pdf->SetFont('AngsanaNew','',10);
if($p>0){
	if($nub == 44){
        $nub = 1;
        $cline = 42;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',16);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
        $pdf->MultiCell(200,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);


		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
		$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(18,4,$buss_name,0,'L',0);

		$pdf->SetXY(20,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);


		$pdf->SetXY(42,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetXY(77,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(102,32);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(142,32);
		$buss_name=iconv('UTF-8','windows-874',"รายได้");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(157,32);
		$buss_name=iconv('UTF-8','windows-874',"VAT");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(172,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			
		$pdf->SetXY(5,38);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	}
	//แสดงช่องทางการชำระทั้งหมด
	$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$old_receiptID' order by \"ChannelAmt\" DESC");
	$sumamt=0;
	while($resstar=pg_fetch_array($qryredstar)){
		$chan=$resstar["byChannel"];
		$amt=$resstar["ChannelAmt"];														
									
		if($chan=="999"){
			$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
		}else{
			//นำไปค้นหาในตาราง BankInt
			$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
			$ressearch=pg_fetch_array($qrysearch);
			list($BAccount,$BName)=$ressearch;
			$txtchannel3="ช่องทาง : $BAccount-$BName";
		}

		$pdf->SetXY(5,$cline);
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(172,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($amt,2));
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);
		$cline += 6;
		
		//****************หารวมเงินใบเสร็จ
		$qrysumtotal=pg_query("select a.\"receiptID\",sum(\"netAmt\"),sum(\"vatAmt\"),sum(\"debtAmt\")
		from thcap_v_receipt_otherpay a 
		left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
		where EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year' and a.\"receiptID\" = '$old_receiptID'	group by a.\"receiptID\"");
		list($receipt2,$sumnetamt,$sumvatamt,$sumall_amt)=pg_fetch_array($qrysumtotal);
								
		if($typepay==$paytype_a || $typepay==$paytype_b){
			$sumnet=$sumnet;
			$sumvat=$sumvat;
			$sum_amt=$sum_amt;
		}else{
			$sumnet=$sumnetamt;
			$sumvat=$sumvatamt;
			$sum_amt=$sumall_amt;
		}
		//***********จบหาเงินรวมใบเสร็จ	
		
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมเงินใบเสร็จ  $receiptID");
		$pdf->MultiCell(137,4,$buss_name,0,'R',0);

		$pdf->SetXY(142,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumnet,2));
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetXY(157,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumvat,2));
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetXY(172,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sum_amt,2));
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________");
		$pdf->MultiCell(182,4,$buss_name,0,'R',0);

		$cline += 6;
		$nub+=1;
	}
}
if($nub == 44){
	$nub = 1;
	$cline = 42;
	$pdf->AddPage();
			
	$pdf->SetFont('AngsanaNew','B',16);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(200,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,25);
	$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
	$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(200,4,$buss_name,0,'R',0);

	$pdf->SetXY(5,26);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',10);
	$pdf->SetXY(5,32);
	$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
	$pdf->MultiCell(18,4,$buss_name,0,'L',0);

	$pdf->SetXY(20,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);


	$pdf->SetXY(42,32);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
	$pdf->MultiCell(35,4,$buss_name,0,'C',0);

	$pdf->SetXY(77,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(102,32);
	$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
	$pdf->MultiCell(40,4,$buss_name,0,'C',0);

	$pdf->SetXY(142,32);
	$buss_name=iconv('UTF-8','windows-874',"รายได้");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(157,32);
	$buss_name=iconv('UTF-8','windows-874',"VAT");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(172,32);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(187,32);
	$buss_name=iconv('UTF-8','windows-874',"ประเภท");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
		$pdf->SetXY(187,36);
		$buss_name=iconv('UTF-8','windows-874',"การรับชำระ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
				
	$pdf->SetXY(5,38);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
}
if($sum_all>0){
	//แสดงช่องทางการชำระทั้งหมด
	$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$old_receiptID' order by \"ChannelAmt\" DESC");
	$sumamt=0;
	while($resstar=pg_fetch_array($qryredstar)){
		$chan=$resstar["byChannel"];
		$amt=$resstar["ChannelAmt"];														
									
		if($chan=="999"){
			$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
		}else{
		//นำไปค้นหาในตาราง BankInt
			$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
			$ressearch=pg_fetch_array($qrysearch);
			list($BAccount,$BName)=$ressearch;
			$txtchannel3="ช่องทาง : $BAccount-$BName";
		}
		$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(142,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$txtchannel3");
			$pdf->MultiCell(50,4,$buss_name,0,'L',0);
					
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(172,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($amt,2));
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);
					
			$cline += 5;
			$nub+=1;					
		}
			
		if($nub == 44){
			$nub = 1;
			$cline = 42;
			$pdf->AddPage();
			
			$pdf->SetFont('AngsanaNew','B',16);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);


			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,25);
			$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
			$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(200,4,$buss_name,0,'R',0);

			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
			$pdf->MultiCell(18,4,$buss_name,0,'L',0);

			$pdf->SetXY(20,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(42,32);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetXY(77,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(102,32);
			$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetXY(142,32);
			$buss_name=iconv('UTF-8','windows-874',"รายได้");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(157,32);
			$buss_name=iconv('UTF-8','windows-874',"VAT");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(172,32);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
				
			$pdf->SetXY(5,38);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			
		}
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(130,$cline-3);
		$buss_name=iconv('UTF-8','windows-874',"----------------------------------------------------------------------");
		$pdf->MultiCell(78,4,$buss_name,0,'R',0);
		
		//****************หารวมเงินใบเสร็จ							
		$qrysumtotal=pg_query("select a.\"receiptID\",sum(\"netAmt\"),sum(\"vatAmt\"),sum(\"debtAmt\")
		from thcap_v_receipt_otherpay a 
		left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
		where date(\"receiveDate\")='$receiveDate' and a.\"receiptID\" = '$old_receiptID'	group by a.\"receiptID\"");
		list($receipt2,$sumnetamt,$sumvatamt,$sumall_amt)=pg_fetch_array($qrysumtotal);
								
		if($typepay==$paytype_a || $typepay==$paytype_b){
			$sumnet=$sumnet;
			$sumvat=$sumvat;
			$sum_amt=$sum_amt;
		}else{
			$sumnet=$sumnetamt;
			$sumvat=$sumvatamt;
			$sum_amt=$sumall_amt;
		}
		//***********จบหาเงินรวมใบเสร็จ
		
		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมเงินใบเสร็จนี้");
		$pdf->MultiCell(137,4,$buss_name,0,'R',0);
			
		$pdf->SetXY(142,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumnet,2));
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(157,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumvat,2));
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(172,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sum_amt,2));
		$pdf->MultiCell(35,4,$buss_name,0,'R',0);
			
		$pdf->SetXY(5,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________");
		$pdf->MultiCell(203,4,$buss_name,0,'R',0);

	$cline += 6;
	$nub+=1;
	
	if($nub == 44){
		$nub = 1;
		$cline = 42;
		$pdf->AddPage();
			
		$pdf->SetFont('AngsanaNew','B',16);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);


		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
		$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(18,4,$buss_name,0,'L',0);

		$pdf->SetXY(20,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(42,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetXY(77,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(102,32);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(142,32);
		$buss_name=iconv('UTF-8','windows-874',"รายได้");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(157,32);
		$buss_name=iconv('UTF-8','windows-874',"VAT");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(172,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(5,38);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	}
	
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรวมต่อวัน  ");
	$pdf->MultiCell(137,4,$buss_name,0,'R',0);
			
	$pdf->SetFont('AngsanaNew','B',10);
	$pdf->SetXY(142,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sum_allnet,2));
	$pdf->MultiCell(15,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',10);
	$pdf->SetXY(157,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sum_allvat,2));
	$pdf->MultiCell(15,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',10);
	$pdf->SetXY(172,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sum_all,2));
	$pdf->MultiCell(35,4,$buss_name,0,'R',0);
			
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(203,4,$buss_name,0,'R',0);

	$cline += 6;
	$nub+=1;
}
if($nub == 44){
	$nub = 1;
	$cline = 42;
	$pdf->AddPage();
			
	$pdf->SetFont('AngsanaNew','B',16);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(200,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,25);
	$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
	$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(200,4,$buss_name,0,'R',0);

	$pdf->SetXY(5,26);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',10);
	$pdf->SetXY(5,32);
	$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
	$pdf->MultiCell(18,4,$buss_name,0,'L',0);

	$pdf->SetXY(20,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(42,32);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
	$pdf->MultiCell(35,4,$buss_name,0,'C',0);

	$pdf->SetXY(77,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(102,32);
	$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
	$pdf->MultiCell(40,4,$buss_name,0,'C',0);

	$pdf->SetXY(142,32);
	$buss_name=iconv('UTF-8','windows-874',"รายได้");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(157,32);
	$buss_name=iconv('UTF-8','windows-874',"VAT");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(172,32);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
	$pdf->MultiCell(35,4,$buss_name,0,'C',0);
		
	$pdf->SetXY(5,38);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
}

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดรวมทั้งเดือน ");
$pdf->MultiCell(137,4,$buss_name,0,'R',0);

$pdf->SetXY(137,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sum_alltotalnet,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(157,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sum_alltotalvat,2));
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(172,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sum_alltotal,2));
$pdf->MultiCell(35,4,$buss_name,0,'R',0);
			
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(203,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+0.5);
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(203,4,$buss_name,0,'C',0);

$cline += 6;
$nub+=1;

if($nub == 44){
	$nub = 1;
	$cline = 42;
	$pdf->AddPage();
        
	$pdf->SetFont('AngsanaNew','B',16);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(200,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,25);
	$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
	$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(200,4,$buss_name,0,'R',0);

	$pdf->SetXY(5,26);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',10);
	$pdf->SetXY(5,32);
	$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
	$pdf->MultiCell(18,4,$buss_name,0,'L',0);

	$pdf->SetXY(20,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(42,32);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
	$pdf->MultiCell(35,4,$buss_name,0,'C',0);

	$pdf->SetXY(77,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(102,32);
	$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
	$pdf->MultiCell(40,4,$buss_name,0,'C',0);

	$pdf->SetXY(142,32);
	$buss_name=iconv('UTF-8','windows-874',"รายได้");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(157,32);
	$buss_name=iconv('UTF-8','windows-874',"VAT");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(172,32);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,38);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
}
	
//หาสรุปของแต่ละช่องทางการจ่าย
if($typepay2==""){ //กรณีเลือกแสดงทั้งหมด
	$qryamt=pg_query("select x.\"byChannel\",sum(x.\"debtAmt\"),(select sum(\"ChannelAmt\") from thcap_temp_receipt_channel a
	where a.\"byChannel\"='999' and a.\"receiptID\" in (select \"receiptID\" from thcap_v_receipt_otherpay where \"byChannel\"=x.\"byChannel\" and date(\"receiveDate\")='$receiveDate' group by \"receiptID\")) 
	FROM thcap_v_receipt_otherpay x
	where date(\"receiveDate\")='$receiveDate' group by  x.\"byChannel\"");							
}else{
	$qryamt=pg_query("select x.\"byChannel\",sum(x.\"debtAmt\"),(select sum(\"ChannelAmt\") from thcap_temp_receipt_channel a
	where a.\"byChannel\"='999' and a.\"receiptID\" in (select \"receiptID\" from thcap_v_receipt_otherpay where \"byChannel\"=x.\"byChannel\" and date(\"receiveDate\")='$receiveDate' and \"typePayID\"='$typepay' group by \"receiptID\")) 
	FROM thcap_v_receipt_otherpay x
	where date(\"receiveDate\")='$receiveDate' and \"typePayID\"='$typepay' group by  \"byChannel\"");							
	$numchkamt=pg_num_rows($qryamt);
							
	if($numchkamt==0){ //แสดงว่าอาจเลือกแสดง type เป็น ชำระเงินต้น หรือ ดอกเบี้ย ทำให้ไม่มีในตาราง otherpay
		$type_a=pg_getprincipletype($typepay2);//หารหัสผ่อนชำระตามสัญญากู้-คืนเงินต้น เช่น 1001
		$type_b=pg_getinteresttype($typepay2);//หารหัสผ่อนชำระตามสัญญากู้-ดอกเบี้ย เช่น 1002
								
		if($type_b==$typepay2){ //กรณีเป็นดอกเบี้ย
			$qryamt=pg_query("select \"byChannel\",sum(\"receiveInterest\") ,
			(select sum(\"ChannelAmt\") from thcap_temp_receipt_channel x
			where x.\"byChannel\"='999' and x.\"receiptID\" IN (select x.\"receiptID\" from thcap_temp_int_201201 x
			inner join \"thcap_temp_receipt_channel\" y on x.\"receiptID\"=y.\"receiptID\"
			where date(x.\"receiveDate\")='$receiveDate' and \"byChannel\" <> '999' 
			and account.\"thcap_mg_getInterestType\"(x.\"contractID\")='$type_b'))			
			
			FROM thcap_temp_int_201201 a
			inner join \"thcap_temp_receipt_channel\" b on a.\"receiptID\"=b.\"receiptID\"
			where date(a.\"receiveDate\")='$receiveDate' and \"byChannel\" <> '999' 
			and account.\"thcap_mg_getInterestType\"(a.\"contractID\")='$type_b'
			group by  \"byChannel\"");																
		}else if($type_a==$typepay2){ //กรณีเป็นเงินต้น
			$qryamt=pg_query("select \"byChannel\",sum(\"receiveInterest\") ,
			(select sum(\"ChannelAmt\") from thcap_temp_receipt_channel x
			where x.\"byChannel\"='999' and x.\"receiptID\" IN (select x.\"receiptID\" from thcap_temp_int_201201 x
			inner join \"thcap_temp_receipt_channel\" y on x.\"receiptID\"=y.\"receiptID\"
			where date(x.\"receiveDate\")='$receiveDate' and \"byChannel\" <> '999' 
			and account.\"thcap_mg_getInterestType\"(x.\"contractID\")='$type_a'))			

			FROM thcap_temp_int_201201 a
			inner join \"thcap_temp_receipt_channel\" b on a.\"receiptID\"=b.\"receiptID\"
			where date(a.\"receiveDate\")='$receiveDate' and \"byChannel\" <> '999' 
			and account.\"thcap_mg_getPrincipleType\"(a.\"contractID\")='$type_a'
			group by  \"byChannel\"");															
		}
	}
}
	
while($resamt=pg_fetch_array($qryamt)){
	$cline += 6;
	$nub+=1;
	list($allchannel,$allamt,$taxamt)=$resamt;
	$allamt-=$taxamt;
	
	if($taxamt>0){
		$texttaxamt="ภาษีหัก ณ ที่จ่าย ".number_format($taxamt,2)." บาท";
	}else{
		$texttaxamt="";
	}
		
	//นำ channel ที่ได้ไปค้นว่าเป็นการจ่ายแบบไหน
	$qrysearchbnk=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$allchannel'");
	$ressearchbnk=pg_fetch_array($qrysearchbnk);
	list($BAccount2,$BName2)=$ressearchbnk;
	if($BAccount2 == "" and $BName2==""){
		$txtchannel2="ไม่ระบุ";
	}else{
		$txtchannel2="$BAccount2-$BName2";
	}
	if($nub == 44){
		$nub = 1;
		$cline = 42;
		$pdf->AddPage();
			
		$pdf->SetFont('AngsanaNew','B',16);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
		$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(18,4,$buss_name,0,'L',0);

		$pdf->SetXY(20,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(42,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetXY(77,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(102,32);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(142,32);
		$buss_name=iconv('UTF-8','windows-874',"รายได้");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(157,32);
		$buss_name=iconv('UTF-8','windows-874',"VAT");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(172,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,38);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	}
	$allamtchannel=$allamtchannel+$allamt;
	$alltaxamt+=$taxamt;
		
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รับเงินจาก     $txtchannel2     รวม  ".number_format($allamt,2)."  บาท $texttaxamt");
	$pdf->MultiCell(180,4,$buss_name,0,'L',0);
}
$allchantax=$allamtchannel+$alltaxamt;
$cline += 6;
$nub+=1;

if($nub == 44){
	$nub = 1;
	$cline = 42;
	$pdf->AddPage();
		
	$pdf->SetFont('AngsanaNew','B',16);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(200,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) สมุดรายวันรับเงิน");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,25);
	$buss_name1=iconv('UTF-8','windows-874',"วันที่รับชำระ $receiveDate");
	$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(200,4,$buss_name,0,'R',0);

	$pdf->SetXY(5,26);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',10);
	$pdf->SetXY(5,32);
	$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
	$pdf->MultiCell(18,4,$buss_name,0,'L',0);

	$pdf->SetXY(20,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จรับเงิน");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(42,32);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
	$pdf->MultiCell(35,4,$buss_name,0,'C',0);

	$pdf->SetXY(77,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(102,32);
	$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
	$pdf->MultiCell(40,4,$buss_name,0,'C',0);

	$pdf->SetXY(142,32);
	$buss_name=iconv('UTF-8','windows-874',"รายได้");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(157,32);
	$buss_name=iconv('UTF-8','windows-874',"VAT");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(172,32);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,38);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
}

if($alltaxamt>0){
	$texttallaxamt="รวมภาษีหัก ณ ที่จ่าย ".number_format($alltaxamt,2)." บาท รวมทั้งหมด ".number_format($allchantax,2)." บาท";
}else{
	$texttallaxamt="";
}
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมทุกช่องทาง ".number_format($allamtchannel,2)." บาท $texttallaxamt");
$pdf->MultiCell(180,4,$buss_name,0,'L',0);
$pdf->Output();
?>