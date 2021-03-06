<?php
session_start();
include("../../config/config.php");

$idno = $_GET['idno'];
$scusid = $_GET['scusid'];
$nowdate = Date('Y-m-d');

/*$qry_cn=pg_query("select \"IDNO\",\"full_name\",\"asset_type\",\"C_REGIS\",\"car_regis\",\"asset_id\" from \"VContact\"  WHERE (\"IDNO\"='$idno') AND (\"CusID\"='$scusid')");
$res_cn=pg_fetch_array($qry_cn);

    if($res_cn["asset_type"] == 1){
        $regis = $res_cn["C_REGIS"];
    }else{
        $regis = $res_cn["car_regis"];
    }*/
	
	
//---- mysql
$db1="ta_mortgage_datastore";

//ค้นหาชื่อผู้กู้หลักจาก mysql
$qry_namemain=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\"='0'");
$nummain=pg_num_rows($qry_namemain);
if($nummain > 0)
{
	$i=1;
	while($resnamemain=pg_fetch_array($qry_namemain))
	{
		$name1=trim($resnamemain["thcap_fullname"]);
		if($i > 1)
		{
			$name3 = $name3." , ";
		}
		$name3 = $name3.$name1;
		$i++;
	}
}

//ค้นหาชื่อผู้กู้ร่วมจาก mysql
$qry_name=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\" > 0 ");
$numco=pg_num_rows($qry_name);
if($numco > 0)
{
	$i=1;
	while($resco=pg_fetch_array($qry_name))
	{
		$name2=trim($resco["thcap_fullname"]);
		if($i > 1)
		{
			$namemic = $namemic." , ";
		}
		$namemic = $namemic.$name2;
		$i++;
	}
	$namemic = "ผู้กู้ร่วม :".$namemic; 
}
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
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการติดตาม");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(10,19);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา: $idno");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก: $name3 $namemic");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(155,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

$cline = 31;

$qry_fuc=pg_query("select \"userid\", \"FollowDate\", \"FollowDetail\", NULL as \"CusCorpID\"
					from \"thcap_FollowUpContract\" WHERE (\"contractID\"='$idno')
				union
					select \"userid\", \"FollowDate\", \"FollowDetail\", \"CusCorpID\"
					from \"thcap_FollowUpCusCorp\"
					where \"CusCorpID\" in(select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$idno')
				ORDER BY \"FollowDate\" DESC");
$numr=pg_num_rows($qry_fuc);
while($res_fuc=pg_fetch_array($qry_fuc)){
    $g_FollowDate = $res_fuc["FollowDate"];
    $g_FollowDetail = $res_fuc["FollowDetail"];
    $g_FollowDetail = str_replace("\n"," ", $g_FollowDetail);
    $g_FollowDetail = str_replace("<br>"," ", $g_FollowDetail);
	$CusCorpID = $res_fuc["CusCorpID"]; // รหัสลูกค้า (ทั้งบุคคลธรรมดา และนิติบุคคล)
    
    $qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_fuc[userid]')");
    $res_fun=pg_fetch_array($qry_fun);
    $g_fullname = $res_fun["fullname"];
	
	if($CusCorpID != "")
	{
		// หาชื่อลูกค้า
		$qry_cus = pg_query("select \"full_name\" from \"VSearchCusCorp\" WHERE (\"CusID\" = '$CusCorpID')");
		$cusName = pg_fetch_result($qry_cus,0);
		
		$showCus = "(รหัสลูกค้า : $CusCorpID - $cusName)";
	}
	else
	{
		$showCus = "";
	}

if($nub > 60){
    $nub = 0;
    $cline = 31;
    $pdf->AddPage();

    $pdf->SetFont('AngsanaNew','B',18);
    $pdf->SetXY(10,10);
    $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
    $pdf->MultiCell(190,4,$title,0,'C',0);

    $pdf->SetFont('AngsanaNew','',15);
    $pdf->SetXY(10,16);
    $buss_name=iconv('UTF-8','windows-874',"รายละเอียดการติดตาม");
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);
	
	$pdf->SetFont('AngsanaNew','',13);
	$pdf->SetXY(10,20);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา: $idno");
	$pdf->MultiCell(190,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
    $pdf->SetXY(5,26);
    $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'C',0);

    $pdf->SetFont('AngsanaNew','B',13);
    $pdf->SetXY(6,25);
    $buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก: $name3  $namemic");
    $pdf->MultiCell(150,4,$buss_name,0,'L',0);

    $pdf->SetXY(155,25);
    $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
    $pdf->MultiCell(50,4,$buss_name,0,'R',0);
}
    
    $pdf->SetFont('AngsanaNew','B',13);
    $pdf->SetXY(6,$cline);
    $buss_name=iconv('UTF-8','windows-874',"ชื่อเจ้าหน้าที่: $g_fullname $showCus");
    $pdf->MultiCell(160,5,$buss_name,0,'L',0);

    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY(155,$cline);
    $buss_name=iconv('UTF-8','windows-874',"วันที่: $g_FollowDate");
    $pdf->MultiCell(50,5,$buss_name,0,'R',0);

    $buss_name=iconv('UTF-8','windows-874',"$g_FollowDetail");
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