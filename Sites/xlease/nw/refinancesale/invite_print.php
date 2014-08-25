<?php
session_start();
include("../../config/config.php");

$idno = $_GET['idno'];
$scusid = $_GET['scusid'];
$nowdate = Date('Y-m-d');

$qry_cn=pg_query("select \"IDNO\",\"full_name\",\"asset_type\",\"C_REGIS\",\"car_regis\",\"asset_id\" from \"VContact\"  WHERE (\"IDNO\"='$idno') AND (\"CusID\"='$scusid')");
$res_cn=pg_fetch_array($qry_cn);

    if($res_cn["asset_type"] == 1){
        $regis = $res_cn["C_REGIS"];
    }else{
        $regis = $res_cn["car_regis"];
    }

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

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการติดต่อ");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"ติดต่อ ชื่อ: $res_cn[full_name]    เลขที่สัญญา: $res_cn[IDNO]   ทะเบียนรถ: $regis");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetXY(155,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

$cline = 31;

$qry_fuc=pg_query("select * from refinance.\"invite\" WHERE (\"IDNO\"='$idno') AND (\"CusID\"='$scusid') ORDER BY \"inviteDate\" DESC");
$numr=pg_num_rows($qry_fuc);
while($res_fuc=pg_fetch_array($qry_fuc)){
    $g_FollowDate = $res_fuc["inviteDate"];
    $g_FollowDetail = $res_fuc["invite_detail"];
    $g_FollowDetail = str_replace("\n"," ", $g_FollowDetail);
    $g_FollowDetail = str_replace("<br>"," ", $g_FollowDetail);
    
    $qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_fuc[id_user]')");
    $res_fun=pg_fetch_array($qry_fun);
    $g_fullname = $res_fun["fullname"];

if($nub > 60){
    $nub = 0;
    $cline = 31;
    $pdf->AddPage();

    $pdf->SetFont('AngsanaNew','B',18);
    $pdf->SetXY(10,10);
    $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
    $pdf->MultiCell(190,4,$title,0,'C',0);

    $pdf->SetFont('AngsanaNew','',15);
    $pdf->SetXY(10,16);
    $buss_name=iconv('UTF-8','windows-874',"รายละเอียดการติดต่อ");
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);

    $pdf->SetXY(5,26);
    $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'C',0);

    $pdf->SetFont('AngsanaNew','B',13);
    $pdf->SetXY(6,25);
    $buss_name=iconv('UTF-8','windows-874',"ติดต่อ ชื่อ: $res_cn[full_name]    เลขที่สัญญา: $res_cn[IDNO]   ทะเบียนรถ: $regis");
    $pdf->MultiCell(150,4,$buss_name,0,'L',0);

    $pdf->SetXY(155,25);
    $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
    $pdf->MultiCell(50,4,$buss_name,0,'R',0);
}
    
    $pdf->SetFont('AngsanaNew','B',13);
    $pdf->SetXY(6,$cline);
    $buss_name=iconv('UTF-8','windows-874',"ชื่อเจ้าหน้าที่: $g_fullname");
    $pdf->MultiCell(70,5,$buss_name,0,'L',0);

    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY(155,$cline);
    $buss_name=iconv('UTF-8','windows-874',"วันที่ชักชวนลูกค้า: $g_FollowDate");
    $pdf->MultiCell(50,5,$buss_name,0,'R',0);

    $buss_name=iconv('UTF-8','windows-874',"$g_FollowDetail");
    $GetStringWidth = $pdf->GetStringWidth($buss_name);
    $GetStringWidth = ceil( ($GetStringWidth/190) );
    $pdf->SetXY(10,$cline+5);
    $pdf->MultiCell(190,5,$buss_name,0,'L',0);

    $GetStringWidth=$GetStringWidth*5;
    $cline+=(15+$GetStringWidth);
    $nub+=$GetStringWidth;
    
    $pdf->SetXY(5,$cline-6);
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,5,$buss_name,0,'C',0);
}

$pdf->Output();
?>