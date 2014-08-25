<?php
set_time_limit(0);
include("../config/config.php");

$mm = $_GET['mm'];
$yy = $_GET['yy'];

$nowyear = date("Y")+543;
$nowdate = date("d-m-")."$nowyear";

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(280,4,$buss_name,0,'R',0);
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
$title=iconv('UTF-8','windows-874',"รายงานภาษีขาย");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(300,4,$buss_name,0,'L',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"งวดที่/รหัส");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(50,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบกำกับ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(75,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(100,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อทรัพย์สิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
  
$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(190,30); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่า");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);
 
$pdf->SetXY(210,30); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(230,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดรวม");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(250,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);
    
$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(300,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;  
                        
$qry_in=pg_query("SELECT * FROM \"FVat\" where EXTRACT(MONTH FROM \"V_Date\")='$mm' AND EXTRACT(YEAR FROM \"V_Date\")='$yy' AND \"Cancel\"='FALSE' ORDER BY \"V_Receipt\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $V_Date = $res_in["V_Date"];
    $V_DueNo = $res_in["V_DueNo"];
    $V_Receipt = $res_in["V_Receipt"];
    $IDNO = $res_in["IDNO"];
    $VatValue = $res_in["VatValue"]; $VatValue = round($VatValue,2);
    $V_memo = $res_in["V_memo"];

    $qry_fp=pg_query("SELECT \"CusID\",\"asset_type\",\"asset_id\",\"P_MONTH\" FROM \"Fp\" where \"IDNO\"='$IDNO'");
    if($res_fp=pg_fetch_array($qry_fp)){
            $CusID = $res_fp["CusID"];
            $asset_type = $res_fp["asset_type"];
            $asset_id = $res_fp["asset_id"];
            $P_MONTH = $res_fp["P_MONTH"];
    }
    
    $R_Date = "";
    $R_Receipt = "";
    if(empty($V_memo) || $V_memo == ""){
        $qry_fr=pg_query("SELECT \"R_Date\",\"R_Receipt\" FROM \"Fr\" where \"R_DueNo\"='$V_DueNo' AND \"IDNO\"='$IDNO' AND \"Cancel\"='FALSE'");
    }else{
        $qry_fr=pg_query("SELECT \"R_Date\",\"R_Receipt\" FROM \"Fr\" where \"R_DueNo\"='$V_DueNo' AND \"IDNO\"='$IDNO' AND \"R_Receipt\"='$V_memo' AND \"Cancel\"='FALSE'");
    }

    if($res_fr=pg_fetch_array($qry_fr)){
        $R_Date = $res_fr["R_Date"];
        $R_Receipt = $res_fr["R_Receipt"];
        $rs4=pg_query("select \"money_for_reportvat\"('$R_Receipt','$IDNO')");
        $money=pg_fetch_result($rs4,0);
    }else{
        $money = $P_MONTH;
    }
    $money = round($money,2);

    $rs1=pg_query("select \"customer_name\"('$CusID')");
    $full_name=pg_fetch_result($rs1,0);
    
    $rs2=pg_query("select \"asset_name\"('$asset_type','$asset_id')");
    $asset_name=pg_fetch_result($rs2,0);
    
    $rs3=pg_query("select \"asset_regis\"('$asset_type','$asset_id')");
    $asset_regis=pg_fetch_result($rs3,0);
    
    $sum_money = $money+$sum_money;
    $sum_VatValue = $VatValue+$sum_VatValue;
    $sum_amt = ($money+$VatValue)+$sum_amt;

if($i > 30){ 
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1; 

	$pdf->SetFont('AngsanaNew','B',15);
	$pdf->SetXY(10,10);
	$title=iconv('UTF-8','windows-874',"รายงานภาษีขาย");
	$pdf->MultiCell(280,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',12); 
	$pdf->SetXY(4,22); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
	$pdf->MultiCell(285,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(10,15);
	$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
	$pdf->MultiCell(280,4,$buss_name,0,'C',0);

	$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
	$pdf->Text(5,26,$gmm);

	$pdf->SetXY(4,24); 
	$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(300,4,$buss_name,0,'L',0);

	$pdf->SetXY(5,30); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(25,30); 
	$buss_name=iconv('UTF-8','windows-874',"งวดที่/รหัส");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(50,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบกำกับ");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(75,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(100,30); 
	$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
	$pdf->MultiCell(40,4,$buss_name,0,'C',0);

	$pdf->SetXY(140,30); 
	$buss_name=iconv('UTF-8','windows-874',"ชื่อทรัพย์สิน");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	  
	$pdf->SetXY(170,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขทะเบียน");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(190,30); 
	$buss_name=iconv('UTF-8','windows-874',"มูลค่า");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	 
	$pdf->SetXY(210,30); 
	$buss_name=iconv('UTF-8','windows-874',"VAT");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(230,30); 
	$buss_name=iconv('UTF-8','windows-874',"ยอดรวม");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(250,30);
	$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
		
	$pdf->SetXY(270,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(4,32); 
	$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(300,4,$buss_name,0,'L',0);

}

//-----------

$qry_date_number2=@pg_query("select \"c_date_number\"('$R_Date')");
$res_date_number2=@pg_fetch_result($qry_date_number2,0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$V_Date");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',$V_DueNo);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(50,$cline); 
$buss_name=iconv('UTF-8','windows-874',$V_Receipt);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(75,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(100,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',$asset_name);
$pdf->MultiCell(35,4,$buss_name,0,'L',0);
  
$pdf->SetXY(175,$cline); 
$buss_name=iconv('UTF-8','windows-874',$asset_regis);
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(190,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($money,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
 
$pdf->SetXY(210,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($VatValue,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(230,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($money+$VatValue,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(250,$cline);
$buss_name=iconv('UTF-8','windows-874',$res_date_number2);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);
    
$pdf->SetXY(270,$cline); 
$buss_name=iconv('UTF-8','windows-874',$R_Receipt);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

//-----------

$cline+=5; 
$i+=1;       
}  
if($i > 30){ 
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1; 

	$pdf->SetFont('AngsanaNew','B',15);
	$pdf->SetXY(10,10);
	$title=iconv('UTF-8','windows-874',"รายงานภาษีขาย");
	$pdf->MultiCell(280,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',12); 
	$pdf->SetXY(4,22); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
	$pdf->MultiCell(285,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(10,15);
	$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
	$pdf->MultiCell(280,4,$buss_name,0,'C',0);

	$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
	$pdf->Text(5,26,$gmm);

	$pdf->SetXY(4,24); 
	$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(300,4,$buss_name,0,'L',0);

	$pdf->SetXY(5,30); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(25,30); 
	$buss_name=iconv('UTF-8','windows-874',"งวดที่/รหัส");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(50,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบกำกับ");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(75,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(100,30); 
	$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
	$pdf->MultiCell(40,4,$buss_name,0,'C',0);

	$pdf->SetXY(140,30); 
	$buss_name=iconv('UTF-8','windows-874',"ชื่อทรัพย์สิน");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	  
	$pdf->SetXY(170,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขทะเบียน");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(190,30); 
	$buss_name=iconv('UTF-8','windows-874',"มูลค่า");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	 
	$pdf->SetXY(210,30); 
	$buss_name=iconv('UTF-8','windows-874',"VAT");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(230,30); 
	$buss_name=iconv('UTF-8','windows-874',"ยอดรวม");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(250,30);
	$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
		
	$pdf->SetXY(270,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(4,32); 
	$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(300,4,$buss_name,0,'L',0);

}
$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(195,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"___________          ____________          _____________");
$pdf->MultiCell(70,4,$buss_name,0,'L',0);

$pdf->SetXY(180,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(193,$cline+1); 
$s_down=iconv('UTF-8','windows-874',number_format($sum_money,2));
$pdf->MultiCell(17,4,$s_down,0,'R',0);

$pdf->SetXY(213,$cline+1); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($sum_VatValue,2));
$pdf->MultiCell(17,4,$s_P_BEGINX,0,'R',0);
  
$pdf->SetXY(233,$cline+1); 
$s_intall=iconv('UTF-8','windows-874',number_format($sum_amt,2));
$pdf->MultiCell(17,4,$s_intall,0,'R',0);

$pdf->SetXY(195,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"___________          ____________          _____________");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(195,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"___________          ____________          _____________");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->Output();
?>