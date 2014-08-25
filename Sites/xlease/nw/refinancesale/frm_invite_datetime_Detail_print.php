<?php
session_start();
include("../../config/config.php");
set_time_limit(0);
$nowdate = nowDate();
$empid = $_GET['empid'];
$date = $_GET['date'];
$idnochk = $_GET['IDNO'];
if($empid == null && $date == null && $idnochk == null){

	$qry_fuc=pg_query("select * from refinance.\"invite\" ORDER BY \"inviteDate\" DESC");
	$checkall = 'all';
}
else if($empid != null && $date == null && $idnochk == null){

	$qry_fuc=pg_query("select * from refinance.\"invite\" WHERE (\"id_user\"='$empid') ORDER BY \"inviteDate\" DESC");
}else if($empid == null && $date != null && $idnochk == null){
	$qry_fuc=pg_query("select * from refinance.\"invite\" WHERE date(\"inviteDate\")='$date' ORDER BY \"inviteDate\" DESC");
}else if($empid != null && $date != null && $idnochk == null){
	$qry_fuc=pg_query("select * from refinance.\"invite\" WHERE date(\"inviteDate\")='$date' and (\"id_user\"='$empid') ORDER BY \"inviteDate\" DESC");
}else if($empid == null && $date == null && $idnochk != null){
	$qry_fuc=pg_query("select * from refinance.\"invite\" WHERE \"IDNO\" = '$idnochk' ORDER BY \"inviteDate\" DESC");
	$checkall = 'all';
}


$qry_cn=pg_query("select * from \"Vfuser\"  WHERE (\"id_user\"='$empid')");
$res_cn=pg_fetch_array($qry_cn);

   
// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

$X = 10;

class PDF extends ThaiPDF
{
    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY($X,18); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(275,4,$buss_name,0,'R',0);
 
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
$pdf->SetXY($X,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(275,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY($X,18);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการชักชวนลูกค้าของพนักงาน");
$pdf->MultiCell(275,4,$buss_name,0,'C',0);

$pdf->SetXY($X,26);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(275,4,$buss_name,'B','C',0);

if($checkall == 'all'){
$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY($X,25);
$buss_name=iconv('UTF-8','windows-874'," ข้อมูลทั้งหมดของการชักชวนของพนักงานทุกคน");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);
}else{
$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY($X,25);
$buss_name=iconv('UTF-8','windows-874',"รหัสพนักงาน  : $res_cn[id_user]    ชื่อพนักงาน  : $res_cn[fullname] ");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);
}

$pdf->SetXY($X,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(265,4,$buss_name,0,'R',0);

$cline = 33;



$numr=pg_num_rows($qry_fuc);
while($res_fuc=pg_fetch_array($qry_fuc)){
    $IDNO = $res_fuc["IDNO"];
	$CusID = $res_fuc["CusID"];
	$inviteDate = $res_fuc["inviteDate"];
	$invite_detail = $res_fuc["invite_detail"];
    
    
    $qry_fun=pg_query("select full_name from \"UNContact\" WHERE \"IDNO\"='$IDNO'");
    $res_fun=pg_fetch_array($qry_fun);
    $g_fullname = $res_fun["full_name"];

if($nub > 30){
    $nub = 0;
    $cline = 33;
    $pdf->AddPage();
	
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY($X,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
	$pdf->MultiCell(275,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY($X,18);
	$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการสนทนา");
	$pdf->MultiCell(275,4,$buss_name,0,'C',0);

	$pdf->SetXY($X,26);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(275,4,$buss_name,'B','C',0);

	if($checkall == 'all'){
	$pdf->SetFont('AngsanaNew','B',13);
	$pdf->SetXY($X,25);
	$buss_name=iconv('UTF-8','windows-874'," ข้อมูลทั้งหมดของการชักชวนของพนักงานทุกคน");
	$pdf->MultiCell(150,4,$buss_name,0,'L',0);
	}else{
	$pdf->SetFont('AngsanaNew','B',13);
	$pdf->SetXY($X,25);
	$buss_name=iconv('UTF-8','windows-874',"รหัสพนักงาน  : $res_cn[id_user]    ชื่อพนักงาน  : $res_cn[fullname] ");
	$pdf->MultiCell(150,4,$buss_name,0,'L',0);
	}


	$pdf->SetXY($X,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
	$pdf->MultiCell(265,4,$buss_name,0,'R',0);
   
}
    
    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY($X,$cline);
    $buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา : $IDNO");
    $pdf->MultiCell(70,5,$buss_name,0,'L',0);

    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY($X+55,$cline);
	$g_fullname = str_replace("\n","",$g_fullname);
    $buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า : $g_fullname");
    $pdf->MultiCell(65,5,$buss_name,0,'L',0);
	
	 $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY($X+100,$cline);
    $buss_name=iconv('UTF-8','windows-874',"วันที่สนทนา : $inviteDate");
    $pdf->MultiCell(65,5,$buss_name,0,'R',0);
	
	if($checkall == 'all'){
		$empid2 = $res_fuc["id_user"];
		$qry_cn2=pg_query("select * from \"Vfuser\"  WHERE (\"id_user\"='$empid2')");
		$res_cn2=pg_fetch_array($qry_cn2);
	
	 $pdf->SetXY($X+200,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อพนักงาน  : $res_cn2[fullname]");
		$pdf->MultiCell(65,5,$buss_name,0,'L',0);
	}
	
	
	$invite_detail = str_replace("\n","",$invite_detail);
    $buss_name=iconv('UTF-8','windows-874',"รายละเอียด  :   $invite_detail");
    $GetStringWidth = $pdf->GetStringWidth($buss_name);
	
    $GetStringWidth = ceil( ($GetStringWidth/275) );
    $pdf->SetXY($X,$cline+7);
    $pdf->MultiCell(275,5,$buss_name,0,'L',0);

    $GetStringWidth=$GetStringWidth*5;
    $cline+=(15+$GetStringWidth);
    $nub+=$GetStringWidth;
    
    $pdf->SetXY($X,$cline-7);
    $buss_name=iconv('UTF-8','windows-874',"");
    $pdf->MultiCell(275,5,$buss_name,'B','C',0);
}

$pdf->Output();
?>