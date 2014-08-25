<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

$condition = $_POST["condition"];
$sortby = $_POST["sortby"];
$month = $_POST["month"];
$monthshow = $_POST["monthshow"];
$year = $_POST["year"];
$year2 = $_POST["year"] + 543;
$typepay = $_POST["typepay"];
$concom = $_POST["concom"];
$money = $_POST["money"];

$nowdate = Date('Y-m-d');
$nowd=substr($nowdate,8,2);
$nowm=substr($nowdate,5,2);
$nowy=substr($nowdate,0,4);
$nowy=$nowy+543;

$nowdate= $nowd."-".$nowm."-".$nowy;
//------------------- PDF -------------------//
class PDF extends ThaiPDF
{
    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(195,4,$buss_name,0,'R',0);
 
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
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานรายการย่อยผิดปกติ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
if($year == ""){
$monthyear=iconv('UTF-8','windows-874',"แสดงข้อมูลที่ค้างทั้งหมดจนถึงปัจจุบัน");
}else{
$monthyear=iconv('UTF-8','windows-874',"ข้อมูลที่ค้างประจำเดือน$monthshow  พ.ศ.$year2");
}
$pdf->MultiCell(190,4,$monthyear,0,'C',0);

$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$qry_inf=pg_query("select * from \"TypePay\" where \"TypeID\"='$typepay'");
$res_inf=pg_fetch_array($qry_inf);
$TName=$res_inf["TName"];

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"( $TName $concom $money )");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetXY(155,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);



/*Header of Table*/
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,35);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,6,$buss_name,1,'C',0);

$pdf->SetXY(25,35);
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(55,6,$buss_name,1,'C',0);

$pdf->SetXY(80,35);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถยนต์");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);

$pdf->SetXY(105,35);
$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
$pdf->MultiCell(50,6,$buss_name,1,'C',0);

$pdf->SetXY(155,35);
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
$pdf->MultiCell(20,6,$buss_name,1,'C',0);

$pdf->SetXY(175,35);
$buss_name=iconv('UTF-8','windows-874',"หนี้ประจำเดือน/ปี");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);


$cline = 41;

if($condition==1){ //แสดงข้อมูลที่ค้างทั้งหมด
	$query_main=pg_query("select \"IDNO\",\"IDCarTax\",\"nameone\",\"CusAmt\",\"TaxDueDate\",\"A_FIRNAME\",\"full_name\",\"carregis\",\"gasregis\" from \"VNwBillcar\" 
	where \"TypePay\"='$typepay' group by \"IDNO\",\"IDCarTax\",\"nameone\",\"CusAmt\",\"TaxDueDate\",\"A_FIRNAME\",\"full_name\",\"carregis\",\"gasregis\" having sum(\"TaxValue\") $concom '$money' order by \"$sortby\" $sort");
}else{ //แสดงข้อมูลที่ค้างประจำเดือน/ปี
	$query_main=pg_query("select \"IDNO\",\"IDCarTax\",\"nameone\",\"CusAmt\",\"TaxDueDate\",\"A_FIRNAME\",\"full_name\",\"carregis\",\"gasregis\" from \"VNwBillcar\" 
	where (EXTRACT(MONTH FROM \"TaxDueDate\")='$month' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$year') and \"TypePay\"='$typepay' 
	group by \"IDNO\",\"IDCarTax\",\"nameone\",\"CusAmt\",\"TaxDueDate\",\"A_FIRNAME\",\"full_name\",\"carregis\",\"gasregis\" having sum(\"TaxValue\") $concom '$money' order by \"$sortby\" $sort");
}
$TaxValueallsum=0;
$nub=0;
while($result_main=pg_fetch_array($query_main)){
	$IDNO=$result_main["IDNO"]; //เลขที่สัญญา
	$IDCarTax=$result_main["IDCarTax"];
	$nameone=$result_main["nameone"]; //ประเภทค่าใช้จ่ายของรายการหลัก
	$CusAmt=$result_main["CusAmt"]; //ยอดชำระค่าใช้จ่ายหลัก
	$TaxDueDate=$result_main["TaxDueDate"]; //วันที่ตั้งหนี้
	$yearshow=substr($TaxDueDate,0,4) + 543;
	$monthshow=substr($TaxDueDate,5,2);
	if($monthshow == "01"){
		$monthshow2="มกราคม";
	}elseif($monthshow == "02"){
		$monthshow2="กุมภาพันธ์";
	}elseif($monthshow == "03"){
		$monthshow2="มีนาคม";
	}elseif($monthshow == "04"){
		$monthshow2="เมษายน";
	}elseif($monthshow == "05"){
		$monthshow2="พฤษภาคม";
	}elseif($monthshow == "06"){
		$monthshow2="มิถุนายน";
	}elseif($monthshow == "07"){
		$monthshow2="กรกฎาคม";
	}elseif($monthshow == "08"){
		$monthshow2="สิงหาคม";
	}elseif($monthshow == "09"){
		$monthshow2="กันยายน";
	}elseif($monthshow == "10"){
		$monthshow2="ตุลาคม";
	}elseif($monthshow == "11"){
		$monthshow2="พฤศจิกายน";
	}elseif($monthshow == "12"){
		$monthshow2="ธันวาคม";
	}
	$cusname=trim($result_main["A_FIRNAME"]).$result_main["full_name"]; //ชื่อลูกค้า
	$carregis=$result_main["carregis"]; //ทะเบียนรถ
	$gasregis=$result_main["gasregis"]; //ทะเบียนรถแกส
		
	if($carregis==""){
		$car_regis=$gasregis;
	}else{
		$car_regis=$carregis;
	}
	
	if($nub > 43){
		$nub = 0;
		$cline = 29;
		$pdf->AddPage();
		
		/*Header of Table*/
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(5,23);
		$buss_name=iconv('UTF-8','windows-874',"IDNO");
		$pdf->MultiCell(20,6,$buss_name,1,'C',0);
		
		$pdf->SetXY(25,23);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
		$pdf->MultiCell(55,6,$buss_name,1,'C',0);

		$pdf->SetXY(80,23);
		$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถยนต์");
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(105,23);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
		$pdf->MultiCell(50,6,$buss_name,1,'C',0);

		$pdf->SetXY(155,23);
		$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
		$pdf->MultiCell(20,6,$buss_name,1,'C',0);

		$pdf->SetXY(175,23);
		$buss_name=iconv('UTF-8','windows-874',"หนี้ประจำเดือน/ปี");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);
	}
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',$IDNO);
	$pdf->MultiCell(20,5,$buss_name,1,'C',0);
	
	$pdf->SetXY(25,$cline);
	$buss_name=iconv('UTF-8','windows-874',$cusname);
	$pdf->MultiCell(55,5,$buss_name,1,'L',0);

	$pdf->SetXY(80,$cline);
	$buss_name=iconv('UTF-8','windows-874',$car_regis);
	$pdf->MultiCell(25,5,$buss_name,1,'C',0);

	$pdf->SetXY(105,$cline);
	$buss_name=iconv('UTF-8','windows-874',$nameone);
	$pdf->MultiCell(50,5,$buss_name,1,'L',0);

	$pdf->SetXY(155,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($CusAmt,2));
	$pdf->MultiCell(20,5,$buss_name,1,'R',0);

	$pdf->SetXY(175,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$monthshow2/$yearshow");
	$pdf->MultiCell(30,5,$buss_name,1,'C',0);
	$pdf->SetXY(20,$cline);
	
	$query=pg_query("select * from \"VNwBillcar\" where \"IDCarTax\"='$IDCarTax'");
	$TaxValuesum=0;
	$cline=$cline+5;
	$nub++;
	while($result=pg_fetch_array($query)){
		$nametwo=$result["nametwo"]; //ประเภทค่าใช้จ่ายของรายการย่อย
		$TaxValue=$result["TaxValue"]; //ยอดชำระของรายการย่อย
		$CoPayDate=$result["CoPayDate"]; //วันที่ของรายการย่อย
		$yearshow=substr($CoPayDate,0,4) + 543;
		$monthshow=substr($CoPayDate,5,2);
		$dayshow=substr($CoPayDate,8,2);
		$dateshow="$dayshow-$monthshow-$yearshow";
		
		if($nub > 47){
			$nub = 0;
			$cline = 29;
			$pdf->AddPage();
			
			/*Header of Table*/
			$pdf->SetFont('AngsanaNew','B',14);
			$pdf->SetXY(5,23);
			$buss_name=iconv('UTF-8','windows-874',"IDNO");
			$pdf->MultiCell(20,6,$buss_name,1,'C',0);
			
			$pdf->SetXY(25,23);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
			$pdf->MultiCell(55,6,$buss_name,1,'C',0);

			$pdf->SetXY(80,23);
			$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถยนต์");
			$pdf->MultiCell(25,6,$buss_name,1,'C',0);

			$pdf->SetXY(105,23);
			$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
			$pdf->MultiCell(50,6,$buss_name,1,'C',0);

			$pdf->SetXY(155,23);
			$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
			$pdf->MultiCell(20,6,$buss_name,1,'C',0);

			$pdf->SetXY(175,23);
			$buss_name=iconv('UTF-8','windows-874',"หนี้ประจำเดือน/ปี");
			$pdf->MultiCell(30,6,$buss_name,1,'C',0);
		}
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',$nametwo);
		$pdf->MultiCell(150,5,$buss_name,1,'R',0);

		$pdf->SetXY(155,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($TaxValue,2));
		$pdf->MultiCell(20,5,$buss_name,1,'R',0);

		$pdf->SetXY(175,$cline);
		$buss_name=iconv('UTF-8','windows-874',$dateshow);
		$pdf->MultiCell(30,5,$buss_name,1,'C',0);
		
		$cline = $cline +5;
		$nub++;
		$TaxValuesum=$TaxValuesum+$TaxValue; //รวมแต่ละรายการ
	}	//end while รายการย่อย
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รวม");
	$pdf->MultiCell(150,5,$buss_name,1,'R',0);
	
	$pdf->SetXY(155,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($TaxValuesum,2));
	$pdf->MultiCell(20,5,$buss_name,1,'R',0);
	
	$pdf->SetXY(175,$cline);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);
	
	$cline = $cline +5;								
	$TaxValueallsum=$TaxValueallsum+$TaxValuesum; //รวมทั้งหมด
	$nub++;
}

$pdf->SetXY(4,$cline-3.3);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(205,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงินทุกรายการ");
$pdf->MultiCell(150,5,$buss_name,1,'R',0);

$pdf->SetXY(155,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($TaxValueallsum,2));
$pdf->MultiCell(20,5,$buss_name,1,'R',0);
	
$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,5,$buss_name,1,'R',0);
	
$pdf->Output();
?>