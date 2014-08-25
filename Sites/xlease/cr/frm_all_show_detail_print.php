<?php
set_time_limit(0);
include("../config/config.php");

if(!empty($_GET['d'])){ $d = pg_escape_string($_GET['d']);}
$nowdate = date("Y/m/d");

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header(){
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

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระทะเบียนรถ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำวันที่ $d");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(164,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"IDCarTax");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(130,30); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทบริการ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(155,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;                           

$qry_name=pg_query("select A.\"IDCarTax\",A.\"IDNO\",\"CusAmt\",\"TypeDep\",\"ApointmentDate\",\"TaxDueDate\",\"TaxValue\",\"BillNumber\",\"TypePay\"  from carregis.\"CarTaxDue\" A LEFT OUTER JOIN carregis.\"DetailCarTax\" B on A.\"IDCarTax\" = B.\"IDCarTax\" 
    where (\"CoPayDate\" = '$d') ORDER BY \"IDNO\" ASC ");

$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDCarTax = $res_name["IDCarTax"];
    $IDNO = $res_name["IDNO"];
    $CusAmt = $res_name["CusAmt"];
    $TypeDep = $res_name["TypeDep"];
    $ApointmentDate = $res_name["ApointmentDate"]; if(empty($ApointmentDate)) $ApointmentDate = "-";
    $TaxDueDate = $res_name["TaxDueDate"];
        $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
        
    $TaxValue = $res_name["TaxValue"];
    $BillNumber = $res_name["BillNumber"];
    $TypePay = $res_name["TypePay"];
        
    $qry_name2=pg_query("select a.\"CarID\" as asset_id,a.\"C_REGIS\",b.\"asset_type\",c.\"full_name\" from \"Carregis_temp\" a
	left join \"Fp\" b on a.\"IDNO\"=b.\"IDNO\"
	left join \"Fa1_FAST\" c on b.\"CusID\"=c.\"CusID\"
	WHERE a.\"IDNO\"='$IDNO' order by \"auto_id\" DESC limit 1 ");
	$num_cartemp=pg_num_rows($qry_name2);
	if($num_cartemp==0){
		//กรณีเป็น Gas 
		$qry_name2=pg_query("SELECT a.\"asset_id\",b.\"car_regis\",a.\"asset_type\",c.\"full_name\" FROM \"Fp\" a
		LEFT JOIN \"FGas\" b ON a.asset_id = b.\"GasID\"
		LEFT JOIN \"Fa1_FAST\" c ON a.\"CusID\" = c.\"CusID\"
		WHERE \"IDNO\"='$IDNO' ");
	}	
	//$qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_name2=pg_fetch_array($qry_name2)){
        $asset_id = $res_name2["asset_id"];
        $full_name = $res_name2["full_name"];
        $asset_type = $res_name2["asset_type"];
        $C_REGIS = $res_name2["C_REGIS"];
        $car_regis = $res_name2["car_regis"];
        if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
    }else{
        $full_name = "ไม่พบข้อมูล";
        $show_regis = "ไม่พบข้อมูล";
    }
  
    $qry_name4=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypePay' ");
    if($res_name4=pg_fetch_array($qry_name4)){
        $TName = $res_name4["TName"];
    }
    
    $summary = $TaxValue;
    $sum_TaxValue+=$TaxValue;
    $sum_CusAmt+=$CusAmt;
    $sum_summary+=$summary;

if($i > 45){ 
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระทะเบียนรถ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำวันที่ $d");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(164,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"IDCarTax");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(130,30); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทบริการ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(155,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10);

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDCarTax);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(55,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(105,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_regis);
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874',$TName);
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(155,$cline); 
$buss_name=iconv('UTF-8','windows-874',$BillNumber);
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(175,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($TaxValue,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$cline+=5; 
$i+=1; 
      
}

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(4,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น $rows รายการ");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(120,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_TaxValue,2));
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>