<?php
include("../config/config.php");

$nowdate = date("Y/m/d");
$idno = $_GET['idno'];
$ssdate = $_GET['date'];
$status = $_GET['status'];

if($status==2){ //กรณีเป็นการพิมพ์สำหรับศาล
	$add=45;
	$add2=115;
}else{ //กรณีเป็นการพิมพ์ธรรมดา
	$add=0;
	$add2=0;
}
/*
$f_date = $_GET['f_date'];
$stdate = $_GET['stdate'];
$ldate = $_GET['ldate'];
*/

$qry_VCusPayment=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
$res_VCusPayment=pg_fetch_array($qry_VCusPayment);
$stdate=$res_VCusPayment["DueDate"];

$qry_VCusPayment_last=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE (\"IDNO\"='$idno') order by \"DueDate\" desc LIMIT(1)");
$res_VCusPayment_last=pg_fetch_array($qry_VCusPayment_last);
$ldate=$res_VCusPayment_last["DueDate"];

$qry_FpFa1=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$idno'");
$res_FpFa1=pg_fetch_array($qry_FpFa1);
$s_payment_nonvat = $res_FpFa1["P_MONTH"];
$s_payment_vat = $res_FpFa1["P_VAT"];
$s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
$f_date = $res_FpFa1["P_STDATE"];
$fullname = trim($res_FpFa1["A_FIRNAME"])." ".trim($res_FpFa1["A_NAME"])." ".trim($res_FpFa1["A_SIRNAME"]);
$s_fp_ptotal = $res_FpFa1["P_TOTAL"];
$s_LAWERFEE = $res_FpFa1["P_LAWERFEE"];
$s_ACCLOSE = $res_FpFa1["P_ACCLOSE"];
$s_StopVat = $res_FpFa1["P_StopVat"];
$_SESSION["ses_scusid"] = trim($res_FpFa1["CusID"]);

$qry_thaidate=pg_query("select conversiondatetothaitext('$f_date')");
$f_dateth=pg_fetch_result($qry_thaidate,0);

$qry_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$idno'");
$res_VContact=pg_fetch_array($qry_VContact);
$s_year=$res_VContact["C_YEAR"];
$s_expdate = $res_VContact["C_TAX_ExpDate"]; 
$s_ccolor = $res_VContact["C_COLOR"];
$s_ccarname = $res_VContact["C_CARNAME"];
$s_dp_balance = $res_VContact["dp_balance"];
$s_radioid = $res_VContact["RadioID"];

if($res_VContact["C_REGIS"]==""){
    $regis=$res_VContact["car_regis"];
    $r_number="เลขถังแก๊ส  ".$res_VContact["gas_number"];
}else{
    $regis=$res_VContact["C_REGIS"];
    $r_number=$res_VContact["C_CARNUM"];
}
/*
$qry_fp=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$d_idno'");
if( $res_fp=pg_fetch_array($qry_fp) ){
    $name = trim($res_fp["A_FIRNAME"])."".trim($res_fp["A_NAME"])."  ".trim($res_fp["A_SIRNAME"]);
    $fp_pmonth=$res_fp["P_MONTH"];   
    $fp_pvat=$res_fp["P_VAT"];
        $p_sum = $fp_pmonth+$fp_pvat;
    $fp_ptotal=$res_fp["P_TOTAL"];
}

$qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$d_idno'");
if( $res_name=pg_fetch_array($qry_name) ){
    $full_name=$res_name["full_name"];
    $dd_C_REGIS=$res_name["C_REGIS"];
    $dd_C_CARNUM=$res_name["C_CARNUM"];
    $dd_C_COLOR=$res_name["C_COLOR"];
}
*/
//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(276,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"ตารางการชำระเงินลูกค้า");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(10,20);
$buss_name=iconv('UTF-8','windows-874',"คำนวณยอด ถึงวันที่ $ssdate");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$idno."     ชื่อผู้เช่าซื้อ ".$fullname);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(155,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $regis     เลขตัวถัง $r_number     สีรถ $s_ccolor     ปีรถ $s_year    วันที่หมดอายุภาษี   $s_expdate");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($s_payment_nonvat,2)."     ภาษีมูลค่าเพิ่ม ".number_format($s_payment_vat,2)."     รวม ".number_format($s_payment_all,2)."     จำนวนงวด ".$s_fp_ptotal);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(4,29); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5+$add,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดที่");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15+$add,35);  
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

    $pdf->SetXY(15+$add,40); 
    $buss_name=iconv('UTF-8','windows-874',"ครบกำหนด");
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(33+$add,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

    $pdf->SetXY(33+$add,40);  
    $buss_name=iconv('UTF-8','windows-874',"ชำระ");
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);
	
$pdf->SetXY(51+$add,35);  
$buss_name=iconv('UTF-8','windows-874',"วัน");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(61+$add,35);  
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(79+$add,35); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(99+$add,35); 
$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(117+$add,35); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(135+$add,35); 
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(155+$add,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างรวม VAT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

if($status==1){ //กรณีเป็นการพิมพ์ธรรมดา
	$pdf->SetXY(180,35); 
	$buss_name=iconv('UTF-8','windows-874',"ยอดค้างไม่รวม VAT");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	$pdf->SetXY(210,35); 
	$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(230,35); 
	$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(250,35); 
	$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(250,40); 
		$buss_name=iconv('UTF-8','windows-874',"สะสม");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(270,35); 
	$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);                   

		$pdf->SetXY(270,40); 
		$buss_name=iconv('UTF-8','windows-874',"ค้างรับ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
}

$pdf->SetXY(4,41); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);   

$cline = 46;

$qry_before=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Date\" is not null)"); //หารายการที่ชำระแล้ว
while($resbf=pg_fetch_array($qry_before)){
    $nub_show += 1;
    if($nub_show == 1){
        $pm_vat = $s_payment_all*$s_fp_ptotal;
        $pm_non_vat = $s_payment_nonvat*$s_fp_ptotal;
    }
    
    $pm_vat_lob = $pm_vat-$s_payment_all;
    $pm_non_vat_lob = $pm_non_vat-$s_payment_nonvat;

	$inub_page += 1;
	
	if($inub_page == 25){
		$pdf->AddPage();
		$cline = 46;
		$inub_page = 1;
    
		$pdf->SetFont('AngsanaNew','B',15);
		$pdf->SetXY(10,10);
		$title=iconv('UTF-8','windows-874',"ตารางการชำระเงินลูกค้า");
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(10,16);
		$buss_name=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(10,20);
		$buss_name=iconv('UTF-8','windows-874',"คำนวณยอด ถึงวันที่ $ssdate ");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,23);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(280,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,23);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$idno."     ชื่อผู้เช่าซื้อ ".$fullname);
		$pdf->MultiCell(100,4,$buss_name,0,'L',0);

		$pdf->SetXY(155,28);
		$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $regis     เลขตัวถัง $r_number     สีรถ $s_ccolor     ปีรถ $s_year    วันที่หมดอายุภาษี   $s_expdate");
		$pdf->MultiCell(150,4,$buss_name,0,'L',0);

		$pdf->SetXY(5,28);
		$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($s_payment_nonvat,2)."     ภาษีมูลค่าเพิ่ม ".number_format($s_payment_vat,2)."     รวม ".number_format($s_payment_all,2)."     จำนวนงวด ".$s_fp_ptotal);
		$pdf->MultiCell(100,4,$buss_name,0,'L',0);

		$pdf->SetXY(4,29); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'C',0);

		$pdf->SetXY(5+$add,35); 
		$buss_name=iconv('UTF-8','windows-874',"งวดที่");
		$pdf->MultiCell(10,4,$buss_name,0,'C',0);

		$pdf->SetXY(15+$add,35); 
		$buss_name=iconv('UTF-8','windows-874',"วันที่");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

			$pdf->SetXY(15+$add,40); 
			$buss_name=iconv('UTF-8','windows-874',"ครบกำหนด");
			$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(33+$add,35); 
		$buss_name=iconv('UTF-8','windows-874',"วันที่");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

			$pdf->SetXY(33+$add,40); 
			$buss_name=iconv('UTF-8','windows-874',"ชำระ");
			$pdf->MultiCell(18,4,$buss_name,0,'C',0);
			
		$pdf->SetXY(51+$add,35); 
		$buss_name=iconv('UTF-8','windows-874',"วัน");
		$pdf->MultiCell(10,4,$buss_name,0,'C',0);

		$pdf->SetXY(61+$add,35); 
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(79+$add,35); 
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(99+$add,35); 
		$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(117+$add,35); 
		$buss_name=iconv('UTF-8','windows-874',"VAT");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(135+$add,35); 
		$buss_name=iconv('UTF-8','windows-874',"รวม");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(155+$add,35);  
		$buss_name=iconv('UTF-8','windows-874',"ยอดค้างรวม VAT");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		if($status==1){ //กรณีเป็นการพิมพ์ธรรมดา
			$pdf->SetXY(180,35); 
			$buss_name=iconv('UTF-8','windows-874',"ยอดค้างไม่รวม VAT");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(210,35); 
			$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(230,35); 
			$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(250,35); 
			$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetXY(250,40); 
				$buss_name=iconv('UTF-8','windows-874',"สะสม");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(270,35); 
			$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);                   

				$pdf->SetXY(270,40); 
				$buss_name=iconv('UTF-8','windows-874',"ค้างรับ");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);
		}
		$pdf->SetXY(4,41); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,B,'C',0);   
	}

	$pdf->SetXY(5+$add,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$resbf[DueNo]); //งวดที่
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

	$pdf->SetXY(15+$add,$cline);  
	$buss_name=iconv('UTF-8','windows-874',$resbf[DueDate]); //วันที่ครบกำหนด
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(33+$add,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$resbf[R_Date]); //วันที่ชำระ
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);  

	$pdf->SetXY(51+$add,$cline);
	$buss_name=iconv('UTF-8','windows-874',$resbf["daydelay"]); //วัน
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

	$pdf->SetXY(61+$add,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($resbf["CalAmtDelay"],2)); //ดอกเบี้ย
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);   

	$pdf->SetXY(79+$add,$cline);
	$buss_name=iconv('UTF-8','windows-874',$resbf[R_Receipt]); //เลขที่ใบเสร็จ
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(99+$add,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($s_payment_nonvat,2)); //ค่างวด
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(117+$add,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($s_payment_vat,2)); //VAT
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(135+$add,$cline);
	$totalvat = $s_payment_nonvat + $s_payment_vat;
	$buss_name=iconv('UTF-8','windows-874',number_format($totalvat,2)); //รวม
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	if($nub_show == 1){
		$pdf->SetXY(155+$add,40);
		$buss_name=iconv('UTF-8','windows-874',number_format($pm_vat,2)); //ยอดค้างรวม VAT
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	}
	
	
	$pdf->SetXY(155+$add,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($pm_vat_lob,2)); //ยอดค้างรวม VAT
	$pdf->MultiCell(25,4,$buss_name,0,'C',0); 
	
	if($status==1){ //กรณีพิมพ์ปกติ
		if($nub_show == 1){
			$pdf->SetXY(180,40);
			$buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat,2));
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		}

		$pdf->SetXY(180,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat_lob,2));
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(210,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($resbf[Priciple],2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
					
		$pdf->SetXY(230,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($resbf[Interest],2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(250,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($resbf[AccuInt],2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(270,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($resbf[WaitIncome],2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	}
	
	$line_nub += 1;
	if($line_nub==3){
		$pdf->SetXY(5+$add,$cline+1); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290-$add2,4,$buss_name,B,'C',0);
		$line_nub = 0;
	} //end if
                
    $last_DueDate = $resbf["DueDate"];
    $pm_vat = $pm_vat_lob;
    $pm_non_vat = $pm_non_vat_lob;
	$sumamt+=$resbf["CalAmtDelay"];
    $cline += 5;
}

if($nub_show == 0){
    $last_DueDate == $ssdate;
    $pm_vat = $s_payment_all*$s_fp_ptotal;
    $pm_non_vat = $s_payment_nonvat*$s_fp_ptotal;
}

//หาวันที่ถัดจาก DueDate ที่คำนวณ
$qry_amt=@pg_query("select * ,'$ssdate'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$idno')  AND (\"DueDate\" BETWEEN '$stdate' AND '$ssdate') "); //รายการที่คำนวณ
while($res_amt=@pg_fetch_array($qry_amt)){
    $nub_show += 1;
	$pm_vat_lob = $pm_vat-$s_payment_all;
    $pm_non_vat_lob = $pm_non_vat-$s_payment_nonvat;
	
	$s_amt=pg_query("select \"CalAmtDelay\"('$ssdate','$res_amt[DueDate]',$s_payment_all)"); 
    $res_s=pg_fetch_result($s_amt,0);
	
	$inub_page += 1;
    
	if($inub_page == 25){
        $pdf->AddPage();
        $cline = 46;
        $inub_page = 1;
        
    $pdf->SetFont('AngsanaNew','B',15);
    $pdf->SetXY(10,10);
    $title=iconv('UTF-8','windows-874',"ตารางการชำระเงินลูกค้า");
    $pdf->MultiCell(290,4,$title,0,'C',0);

    $pdf->SetFont('AngsanaNew','',12);
    $pdf->SetXY(10,16);
    $buss_name=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
    $pdf->MultiCell(290,4,$buss_name,0,'C',0);

    $pdf->SetXY(10,20);
    $buss_name=iconv('UTF-8','windows-874',"คำนวณยอด ถึงวันที่ $ssdate");
    $pdf->MultiCell(290,4,$buss_name,0,'C',0);

    $pdf->SetXY(5,23);
    $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
    $pdf->MultiCell(280,4,$buss_name,0,'R',0);

    $pdf->SetXY(5,23);
    $buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$idno."     ชื่อผู้เช่าซื้อ ".$fullname);
    $pdf->MultiCell(100,4,$buss_name,0,'L',0);

    $pdf->SetXY(155,28);
    $buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $regis     เลขตัวถัง $r_number     สีรถ $s_ccolor     ปีรถ $s_year    วันที่หมดอายุภาษี   $s_expdate");
    $pdf->MultiCell(150,4,$buss_name,0,'L',0);

    $pdf->SetXY(5,28);
    $buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($s_payment_nonvat,2)."     ภาษีมูลค่าเพิ่ม ".number_format($s_payment_vat,2)."     รวม ".number_format($s_payment_all,2)."     จำนวนงวด ".$s_fp_ptotal);
    $pdf->MultiCell(100,4,$buss_name,0,'L',0);

    $pdf->SetXY(4,29); 
    $buss_name=iconv('UTF-8','windows-874',"");
    $pdf->MultiCell(290,4,$buss_name,B,'C',0);

    $pdf->SetXY(5+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"งวดที่");
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

	$pdf->SetXY(15+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(15+$add,40); 
		$buss_name=iconv('UTF-8','windows-874',"ครบกำหนด");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(33+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(33+$add,40); 
		$buss_name=iconv('UTF-8','windows-874',"ชำระ");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);
		
	$pdf->SetXY(51+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"วัน");
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

	$pdf->SetXY(61+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(79+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(99+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(117+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"VAT");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(135+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"รวม");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(155+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"ยอดค้างรวม VAT");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
	if($status==1){ //กรณีเป็นการพิมพ์ธรรมดา
		$pdf->SetXY(180,35); 
		$buss_name=iconv('UTF-8','windows-874',"ยอดค้างไม่รวม VAT");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(210,35); 
		$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(230,35); 
		$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(250,35); 
		$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(250,40); 
			$buss_name=iconv('UTF-8','windows-874',"สะสม");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(270,35); 
		$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);                   

			$pdf->SetXY(270,40); 
			$buss_name=iconv('UTF-8','windows-874',"ค้างรับ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	}
    $pdf->SetXY(4,41); 
    $buss_name=iconv('UTF-8','windows-874',"");
    $pdf->MultiCell(290,4,$buss_name,B,'C',0);   

    }

    $pdf->SetXY(5+$add,$cline); 
    $buss_name=iconv('UTF-8','windows-874',$res_amt[DueNo]);
    $pdf->MultiCell(10,4,$buss_name,0,'C',0);

    $pdf->SetXY(15+$add,$cline); 
    $buss_name=iconv('UTF-8','windows-874',$res_amt[DueDate]);
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);

    $pdf->SetXY(33+$add,$cline); 
    $buss_name=iconv('UTF-8','windows-874',$ssdate);
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);
    
	$pdf->SetXY(51+$add,$cline);
	$buss_name=iconv('UTF-8','windows-874',$res_amt["dateA"]); //วัน
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

	$pdf->SetXY(61+$add,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($res_s,2)); //ดอกเบี้ย
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);
	
    $pdf->SetXY(79+$add,$cline);
    $buss_name=iconv('UTF-8','windows-874',$res_amt[R_Receipt]); //เลขที่ใบเสร็จ
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(99+$add,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_payment_nonvat,2)); //ค่างวด
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);

    $pdf->SetXY(117+$add,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_payment_vat,2)); //VAT
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(135+$add,$cline);
	$totalvat = $s_payment_nonvat + $s_payment_vat;
	$buss_name=iconv('UTF-8','windows-874',number_format($totalvat,2)); //รวม
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
    
    if($nub_show == 1){
        $pdf->SetXY(155+$add,40);
        $buss_name=iconv('UTF-8','windows-874',number_format($pm_vat,2));
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);
    }

    $pdf->SetXY(155+$add,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_vat_lob,2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
    
	if($status==1){ //กรณีเป็นการพิมพ์ธรรมดา
		if($nub_show == 1){
			$pdf->SetXY(180,40);
			$buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat,2));
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		}
					
		$pdf->SetXY(180,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format(abs($pm_non_vat_lob),2));
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(210,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format(abs($res_amt[Priciple]),2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
					
		$pdf->SetXY(230,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[Interest],2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(250,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[AccuInt],2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(270,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[WaitIncome],2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	}
    $line_nub += 1;
    if($line_nub==3){
        $pdf->SetXY(5+$add,$cline+1); 
        $buss_name=iconv('UTF-8','windows-874',"");
        $pdf->MultiCell(290-$add2,4,$buss_name,0,'C',0);
    $line_nub = 0;
    }
    
    $pm_vat = $pm_vat_lob;
    $pm_non_vat = $pm_non_vat_lob;
	$sumamt2+=$res_s;
    $last_DueDate = $res_amt["DueDate"];
    $cline += 5;
} //end while

$DateUpdate =date("Y-m-d", strtotime("+1 day",strtotime($last_DueDate)));// วันถัดจาก Due ล่าสุด
$qry_l=@pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"DueDate\" BETWEEN '$DateUpdate' AND '$ldate')");
while($resl=@pg_fetch_array($qry_l)){
    $nub_show += 1;
    $pm_vat_lob = $pm_vat-$s_payment_all;
    $pm_non_vat_lob = $pm_non_vat-$s_payment_nonvat;
    
    $inub_page += 1;
    
	if($inub_page == 25){
        $pdf->AddPage();
        $cline = 46;
        $inub_page = 1;
        
    $pdf->SetFont('AngsanaNew','B',15);
    $pdf->SetXY(10,10);
    $title=iconv('UTF-8','windows-874',"ตารางการชำระเงินลูกค้า");
    $pdf->MultiCell(290,4,$title,0,'C',0);

    $pdf->SetFont('AngsanaNew','',12);
    $pdf->SetXY(10,16);
    $buss_name=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
    $pdf->MultiCell(290,4,$buss_name,0,'C',0);

    $pdf->SetXY(10,20);
    $buss_name=iconv('UTF-8','windows-874',"คำนวณยอด ถึงวันที่ $ssdate");
    $pdf->MultiCell(290,4,$buss_name,0,'C',0);

    $pdf->SetXY(5,23);
    $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
    $pdf->MultiCell(280,4,$buss_name,0,'R',0);

    $pdf->SetXY(5,23);
    $buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$idno."     ชื่อผู้เช่าซื้อ ".$fullname);
    $pdf->MultiCell(100,4,$buss_name,0,'L',0);

    $pdf->SetXY(155,28);
    $buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $regis     เลขตัวถัง $r_number     สีรถ $s_ccolor     ปีรถ $s_year    วันที่หมดอายุภาษี   $s_expdate");
    $pdf->MultiCell(150,4,$buss_name,0,'L',0);

    $pdf->SetXY(5,28);
    $buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($s_payment_nonvat,2)."     ภาษีมูลค่าเพิ่ม ".number_format($s_payment_vat,2)."     รวม ".number_format($s_payment_all,2)."     จำนวนงวด ".$s_fp_ptotal);
    $pdf->MultiCell(100,4,$buss_name,0,'L',0);

    $pdf->SetXY(4,29); 
    $buss_name=iconv('UTF-8','windows-874',"");
    $pdf->MultiCell(290,4,$buss_name,B,'C',0);

    $pdf->SetXY(5+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"งวดที่");
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

	$pdf->SetXY(15+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(15+$add,40); 
		$buss_name=iconv('UTF-8','windows-874',"ครบกำหนด");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(33+$add,35);  
	$buss_name=iconv('UTF-8','windows-874',"วันที่");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(33+$add,40); 
		$buss_name=iconv('UTF-8','windows-874',"ชำระ");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);
		
	$pdf->SetXY(51+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"วัน");
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

	$pdf->SetXY(61+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ย");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(79+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(99+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(117+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"VAT");
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);

	$pdf->SetXY(135+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"รวม");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(155+$add,35); 
	$buss_name=iconv('UTF-8','windows-874',"ยอดค้างรวม VAT");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	if($status==1){ //กรณีเป็นการพิมพ์ธรรมดา
		$pdf->SetXY(180,35); 
		$buss_name=iconv('UTF-8','windows-874',"ยอดค้างไม่รวม VAT");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(210,35); 
		$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(230,35); 
		$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(250,35); 
		$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(250,40); 
			$buss_name=iconv('UTF-8','windows-874',"สะสม");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(270,35); 
		$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);                   

			$pdf->SetXY(270,40); 
			$buss_name=iconv('UTF-8','windows-874',"ค้างรับ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	}
    $pdf->SetXY(4,41); 
    $buss_name=iconv('UTF-8','windows-874',"");
    $pdf->MultiCell(290,4,$buss_name,B,'C',0);   

    }

    $pdf->SetXY(5+$add,$cline); 
    $buss_name=iconv('UTF-8','windows-874',$resl[DueNo]);
    $pdf->MultiCell(10,4,$buss_name,0,'C',0);

    $pdf->SetXY(15+$add,$cline); 
    $buss_name=iconv('UTF-8','windows-874',$resl[DueDate]);
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);

    $pdf->SetXY(33+$add,$cline); 
    $buss_name=iconv('UTF-8','windows-874',$resl[R_Date]);
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);
    
	$pdf->SetXY(51+$add,$cline);
	$buss_name=iconv('UTF-8','windows-874',$resl["daydelay"]); //วัน
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

	$pdf->SetXY(61+$add,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($resl["CalAmtDelay"],2)); //ดอกเบี้ย
	$pdf->MultiCell(18,4,$buss_name,0,'C',0);
	
    $pdf->SetXY(79+$add,$cline);
    $buss_name=iconv('UTF-8','windows-874',$resl[R_Receipt]); //เลขที่ใบเสร็จ
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(99+$add,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_payment_nonvat,2)); //ค่างวด
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);

    $pdf->SetXY(117+$add,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_payment_vat,2)); //VAT
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(135+$add,$cline);
	$totalvat = $s_payment_nonvat + $s_payment_vat;
	$buss_name=iconv('UTF-8','windows-874',number_format($totalvat,2)); //รวม
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
    
    if($nub_show == 1){
        $pdf->SetXY(155+$add,40);
        $buss_name=iconv('UTF-8','windows-874',number_format($pm_vat,2));
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);
    }

    $pdf->SetXY(155+$add,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_vat_lob,2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
    
	if($status==1){ //กรณีพิมพ์ข้อมูลธรรมดา
		if($nub_show == 1){
			$pdf->SetXY(180,40);
			$buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat,2));
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		}
					
		$pdf->SetXY(180,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format(abs($pm_non_vat_lob),2));
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(210,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format(abs($resl[Priciple]),2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
					
		$pdf->SetXY(230,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($resl[Interest],2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(250,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($resl[AccuInt],2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(270,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($resl[WaitIncome],2));
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	}
    $line_nub += 1;
    if($line_nub==3){
        $pdf->SetXY(4,$cline+1); 
        $buss_name=iconv('UTF-8','windows-874',"");
        $pdf->MultiCell(290,4,$buss_name,B,'C',0);
    $line_nub = 0;
    }
    
    $pm_vat = $pm_vat_lob;
    $pm_non_vat = $pm_non_vat_lob;
	$sumamt2+=$resbf["CalAmtDelay"];
    $cline += 5;
}//end while
$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$idno' AND \"Cancel\"='FALSE' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $otherpay_amt = $re_mny["sum_money_otherpay"];
}

$sumamt3 = $sumamt + $sumamt2;

$pdf->SetXY(61,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"********** ดอกเบี้ยล่าช้ารวม               ".number_format($sumamt3,2)."      บาท                คงเหลือ:           ".number_format($sumamt3-$otherpay_amt,2)."         บาท");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

/*
$qry_amt=pg_query("select * ,'$d_date'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$d_idno')  AND (\"DueDate\" BETWEEN '$stdate' AND '$d_date') "); 
while($res_amt=pg_fetch_array($qry_amt)){

$pm_vat_lob = $pm_vat-$fp_pmonth-$fp_pvat;
$pm_non_vat_lob = $pm_non_vat-$fp_pmonth;

$inub_page += 1;
if($inub_page == 25){
    $pdf->AddPage();
    $cline = 46;
    $inub_page = 1;
    
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"ตารางการชำระเงินลูกค้า");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(10,20);
$buss_name=iconv('UTF-8','windows-874',"คำนวณยอด ถึงวันที่ $d_date ");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$d_idno."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($fp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($fp_pvat,2)."     รวม ".number_format($p_sum,2)."     จำนวนงวด ".$fp_ptotal);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(4,29); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดที่");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(15,40); 
    $buss_name=iconv('UTF-8','windows-874',"ครบกำหนด");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(40,40); 
    $buss_name=iconv('UTF-8','windows-874',"ชำระ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(65,35); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,35); 
$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างรวม VAT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(160,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างไม่รวม VAT");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,35); 
$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(215,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(240,40); 
    $buss_name=iconv('UTF-8','windows-874',"สะสม");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);                   

    $pdf->SetXY(265,40); 
    $buss_name=iconv('UTF-8','windows-874',"ค้างรับ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,41); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);   

}  

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',$res_amt[DueNo]);
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_amt[DueDate]);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,$cline); 
$buss_name=iconv('UTF-8','windows-874',$d_date);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
$pdf->SetXY(65,$cline);
$buss_name=iconv('UTF-8','windows-874',$res_amt[R_Receipt]);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($fp_pmonth,2));
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($fp_pvat,2));
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

if($nub_show == 1){
    $pdf->SetXY(135,40);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_vat,2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
}

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($pm_vat_lob,2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
if($nub_show == 1){
    $pdf->SetXY(160,40);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat,2));
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
}

$pdf->SetXY(160,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat_lob,2));
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[Priciple],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
$pdf->SetXY(215,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[Interest],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[AccuInt],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[WaitIncome],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$line_nub += 1;
if($line_nub==3){
$pdf->SetXY(4,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);
$line_nub = 0;
}

    $pm_vat = $pm_vat_lob;
    $pm_non_vat = $pm_non_vat_lob;
    $ddddd = $res_amt["DueDate"];
    $cline += 5;
}

$DateUpdate =date("Y-m-d", strtotime("+1 day",strtotime($ddddd)));

$qry_l=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$d_idno')  AND (\"DueDate\" BETWEEN '$DateUpdate' AND '$ldate')  ");

$n_rowl=pg_num_rows($qry_l);
$n_l=$n_rowl;
for($i=0;$i<($n_l);$i++){
    $resl=pg_fetch_array($qry_l);
    
$pm_vat_lob = $pm_vat-$fp_pmonth-$fp_pvat;
$pm_non_vat_lob = $pm_non_vat-$fp_pmonth;

$inub_page += 1;
if($inub_page == 25){
    $pdf->AddPage();
    $cline = 46;
    $inub_page = 1;
    
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"ตารางการชำระเงินลูกค้า");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(10,20);
$buss_name=iconv('UTF-8','windows-874',"คำนวณยอด ถึงวันที่ $d_date ");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$d_idno."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($fp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($fp_pvat,2)."     รวม ".number_format($p_sum,2)."     จำนวนงวด ".$fp_ptotal);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(4,29); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดที่");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(15,40); 
    $buss_name=iconv('UTF-8','windows-874',"ครบกำหนด");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(40,40); 
    $buss_name=iconv('UTF-8','windows-874',"ชำระ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(65,35); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,35); 
$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างรวม VAT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(160,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างไม่รวม VAT");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,35); 
$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(215,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(240,40); 
    $buss_name=iconv('UTF-8','windows-874',"สะสม");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);                   

    $pdf->SetXY(265,40); 
    $buss_name=iconv('UTF-8','windows-874',"ค้างรับ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,41); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);   

}        

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',$resl[DueNo]);
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resl[DueDate]);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resl[R_Date]);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
$pdf->SetXY(65,$cline);
$buss_name=iconv('UTF-8','windows-874',$resl[R_Receipt]);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($fp_pmonth,2));
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($fp_pvat,2));
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

if($nub_show == 1){
    $pdf->SetXY(135,40);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_vat,2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
}

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($pm_vat_lob,2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
if($nub_show == 1){
    $pdf->SetXY(160,40);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat,2));
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
}

$pdf->SetXY(160,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat_lob,2));
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($resl[Priciple],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
$pdf->SetXY(215,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resl[Interest],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resl[AccuInt],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resl[WaitIncome],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$line_nub += 1;
if($line_nub==3){
$pdf->SetXY(4,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);
$line_nub = 0;
}

$pm_vat = $pm_vat_lob;
$pm_non_vat = $pm_non_vat_lob;
$cline += 5;
}

*/

$pdf->Output();
?>