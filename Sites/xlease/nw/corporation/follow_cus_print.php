<?php
session_start();
include("../../config/config.php");


$nowdate = Date('Y-m-d');
$corpID_get=pg_escape_string($_GET['corpID']);

$corpID= trim($corpID_get);
$sqlsel2 = pg_query("SELECT corp_regis,\"corpName_THA\" FROM th_corp where \"corpID\"::character varying = '$corpID' ");
$re2 = pg_fetch_array($sqlsel2);
$corp_regis= $re2['corp_regis'];


//---- End mysql


//------------------- PDF -------------------//
require('../../thaipdfclass.php');

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
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการติดตาม");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,19);
$buss_name=iconv('UTF-8','windows-874',"เลขทะเบียนนิติบุคคล: $corp_regis");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้านิติบุคคล : ".$re2['corpName_THA']);
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetXY(155,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);



$qry_fuc = pg_query("SELECT id_user, fol_detail, fol_date  FROM th_corp_follow_cus where \"corpID\" = '$corpID'");
$cline = 31;
while($res_fuc=@pg_fetch_array($qry_fuc)){


if($nub > 60){
    $nub = 0;
    $cline = 31;
    $pdf->AddPage();

	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(10,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
	$pdf->MultiCell(190,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(10,15);
	$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการติดตาม");
	$pdf->MultiCell(190,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(10,19);
	$buss_name=iconv('UTF-8','windows-874',"เลขทะเบียนนิติบุคคล: $corp_regis");
	$pdf->MultiCell(190,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(5,26);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',13);
	$pdf->SetXY(6,25);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้านิติบุคคล : ".$re2['corpName_THA']);
	$pdf->MultiCell(150,4,$buss_name,0,'L',0);

	$pdf->SetXY(155,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
	$pdf->MultiCell(50,4,$buss_name,0,'R',0);
	
	
}



	$qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_fuc[id_user]')");
	$res_fun=pg_fetch_array($qry_fun);

    
    $pdf->SetFont('AngsanaNew','',12);
    $pdf->SetXY(6,$cline);
    $buss_name=iconv('UTF-8','windows-874',"ชื่อเจ้าหน้าที่: ".$res_fun['fullname']);
    $pdf->MultiCell(70,5,$buss_name,0,'L',0);

    
    $pdf->SetXY(155,$cline);
    $buss_name=iconv('UTF-8','windows-874',"วันที่: ".$res_fuc['fol_date']);
    $pdf->MultiCell(50,5,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','',13);
    $buss_name=iconv('UTF-8','windows-874',str_replace("\n","",$res_fuc['fol_detail']));
    $GetStringWidth = $pdf->GetStringWidth($buss_name);
    $GetStringWidth = ceil( ($GetStringWidth/190) );
    $pdf->SetXY(10,$cline+5);
    $pdf->MultiCell(190,5,$buss_name,0,'L',0);

    $GetStringWidth=$GetStringWidth*5;
    $cline+=(15+$GetStringWidth);
    //$nub+=$GetStringWidth;
	$nub += 6;
    
    $pdf->SetXY(5,$cline-6);
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,5,$buss_name,0,'C',0);

}

$pdf->Output();
?>