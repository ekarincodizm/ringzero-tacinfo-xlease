<?php
session_start();
include("../../config/config.php");

$txthead = $_POST['txthead'];
$txtmonth1 = $_POST['txtmonth1'];
$txtmonth2 = $_POST['txtmonth2'];
$condition = $_POST['condition'];

if($condition == 2){
	list($txt1,$txt2) = split('<br>',$txtmonth1);
	$txtmonth1 = "                       ".$txt1."                       ".$txt2;
	
	list($txt3,$txt4) = split('<br>',$txtmonth2);
	$txtmonth2 = "                       ".$txt3."                       ".$txt4;
}



$startDate1 = $_POST['startDate1'];
$startDate2 = $_POST['startDate2'];
$endDate1 = $_POST['endDate1'];
$endDate2 = $_POST['endDate2'];


$nowdate = Date('Y-m-d');

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
$title=iconv('UTF-8','windows-874',$txthead);
$pdf->MultiCell(190,4,$title,0,'C',0);


$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetXY(155,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

if($condition != 2){
	$pdf->SetXY(40,35);
	$buss_name=iconv('UTF-8','windows-874',"ที่");
	$pdf->MultiCell(10,18,$buss_name,1,'C',0);

	$pdf->SetXY(50,35);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อ - สกุล");
	$pdf->MultiCell(45,18,$buss_name,1,'C',0);

	$pdf->SetXY(95,35);
	$buss_name=iconv('UTF-8','windows-874',$txtmonth1);
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(95,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(113,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(131,35);
	$buss_name=iconv('UTF-8','windows-874',$txtmonth2);
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(131,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(149,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);
}else{
	$pdf->SetXY(30,35);
	$buss_name=iconv('UTF-8','windows-874',"ที่");
	$pdf->MultiCell(10,18,$buss_name,1,'C',0);

	$pdf->SetXY(40,35);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อ - สกุล");
	$pdf->MultiCell(45,18,$buss_name,1,'C',0);

	$pdf->SetXY(85,35);
	$buss_name=iconv('UTF-8','windows-874',$txtmonth1);
	$pdf->MultiCell(50,6,$buss_name,1,'C',0);

	$pdf->SetXY(85,47);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนการชักชวน");
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);

	$pdf->SetXY(110,47);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนการจับคู่");
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);

	$pdf->SetXY(135,35);
	$buss_name=iconv('UTF-8','windows-874',$txtmonth2);
	$pdf->MultiCell(50,6,$buss_name,1,'C',0);

	$pdf->SetXY(135,47);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนการชักชวน");
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);

	$pdf->SetXY(160,47);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนการจับคู่");
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);
}
	$cline = 53;
	$i = 1;
	$nub = 1;
	$qry_user=pg_query("select A.\"id_user\",B.\"fullname\" from refinance.\"user_invite\" A
	left join \"Vfuser\" B on A.\"id_user\" = B.\"id_user\" where \"status_use\" = 'TRUE' ORDER BY A.\"id_user\""); 
	
	$numr=pg_num_rows($qry_user);
	while($resuser=pg_fetch_array($qry_user)){
		$id_user = $resuser["id_user"];
		$fullname = $resuser["fullname"];
		
		if($nub > 36){
			$nub = 1;
			$cline = 53;
			$pdf->AddPage();

			if($condition != 2){
				$pdf->SetXY(40,35);
				$buss_name=iconv('UTF-8','windows-874',"ที่");
				$pdf->MultiCell(10,18,$buss_name,1,'C',0);

				$pdf->SetXY(50,35);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อ - สกุล");
				$pdf->MultiCell(45,18,$buss_name,1,'C',0);

				$pdf->SetXY(95,35);
				$buss_name=iconv('UTF-8','windows-874',$txtmonth1);
				$pdf->MultiCell(36,6,$buss_name,1,'C',0);

				$pdf->SetXY(95,41);
				$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
				$pdf->MultiCell(18,6,$buss_name,1,'C',0);

				$pdf->SetXY(113,41);
				$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
				$pdf->MultiCell(18,6,$buss_name,1,'C',0);

				$pdf->SetXY(131,35);
				$buss_name=iconv('UTF-8','windows-874',$txtmonth2);
				$pdf->MultiCell(36,6,$buss_name,1,'C',0);

				$pdf->SetXY(131,41);
				$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
				$pdf->MultiCell(18,6,$buss_name,1,'C',0);

				$pdf->SetXY(149,41);
				$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
				$pdf->MultiCell(18,6,$buss_name,1,'C',0);
			}else{
				$pdf->SetXY(30,35);
				$buss_name=iconv('UTF-8','windows-874',"ที่");
				$pdf->MultiCell(10,18,$buss_name,1,'C',0);

				$pdf->SetXY(40,35);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อ - สกุล");
				$pdf->MultiCell(45,18,$buss_name,1,'C',0);

				$pdf->SetXY(85,35);
				$buss_name=iconv('UTF-8','windows-874',$txtmonth1);
				$pdf->MultiCell(50,6,$buss_name,1,'C',0);

				$pdf->SetXY(85,47);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนการชักชวน");
				$pdf->MultiCell(25,6,$buss_name,1,'C',0);

				$pdf->SetXY(110,47);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนการจับคู่");
				$pdf->MultiCell(25,6,$buss_name,1,'C',0);

				$pdf->SetXY(135,35);
				$buss_name=iconv('UTF-8','windows-874',$txtmonth2);
				$pdf->MultiCell(50,6,$buss_name,1,'C',0);

				$pdf->SetXY(135,47);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนการชักชวน");
				$pdf->MultiCell(25,6,$buss_name,1,'C',0);

				$pdf->SetXY(160,47);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนการจับคู่");
				$pdf->MultiCell(25,6,$buss_name,1,'C',0);
			}
		}							
		/*---หาจำนวนการชวน 1 สัญญา ถือเป็น 1 การชักชวน ของค่าแรก---*/
		$qry_invite1=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate1' and '$endDate1') group by \"IDNO\""); 
		$num_invite1=pg_num_rows($qry_invite1);
								
		/*---หาจำนวนการชวน 1 สัญญา ถือเป็น 1 การชักชวน ของค่าที่สอง---*/
		$qry_invite2=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate2' and '$endDate2') group by \"IDNO\""); 
		$num_invite2=pg_num_rows($qry_invite2);
								
		/*------หาจำนวนการจับคู่ ของค่าแรก-----*/
		$qry_match1=pg_query("SELECT * FROM refinance.\"match_invite\" A
		left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
		where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate1' and '$endDate1')"); 
		$num_match1=pg_num_rows($qry_match1);
								
		/*------หาจำนวนการจับคู่ ของค่าที่สอง-----*/
		$qry_match2=pg_query("SELECT * FROM refinance.\"match_invite\" A
		left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
		where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate2' and '$endDate2')"); 
		$num_match2=pg_num_rows($qry_match2);
									
		if($condition != 2){
			$pdf->SetXY(40,$cline);
			$buss_name=iconv('UTF-8','windows-874',$i);
			$pdf->MultiCell(10,6,$buss_name,1,'C',0);

			$pdf->SetXY(50,$cline);
			$buss_name=iconv('UTF-8','windows-874',$fullname);
			$pdf->MultiCell(45,6,$buss_name,1,'L',0);

			//สัปดาห์ที่ 1
			$pdf->SetXY(95,$cline);
			$buss_name=iconv('UTF-8','windows-874',$num_invite1);
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);

			$pdf->SetXY(113,$cline);
			$buss_name=iconv('UTF-8','windows-874',$num_match1);
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);

			//สัปดาห์ที่ 2
			$pdf->SetXY(131,$cline);
			$buss_name=iconv('UTF-8','windows-874',$num_invite2);
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);

			$pdf->SetXY(149,$cline);
			$buss_name=iconv('UTF-8','windows-874',$num_match2);
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);
		}else{
			$pdf->SetXY(30,$cline);
			$buss_name=iconv('UTF-8','windows-874',$i);
			$pdf->MultiCell(10,6,$buss_name,1,'C',0);

			$pdf->SetXY(40,$cline);
			$buss_name=iconv('UTF-8','windows-874',$fullname);
			$pdf->MultiCell(45,6,$buss_name,1,'L',0);

			$pdf->SetXY(85,$cline);
			$buss_name=iconv('UTF-8','windows-874',$num_invite1);
			$pdf->MultiCell(25,6,$buss_name,1,'C',0);

			$pdf->SetXY(110,$cline);
			$buss_name=iconv('UTF-8','windows-874',$num_match1);
			$pdf->MultiCell(25,6,$buss_name,1,'C',0);

			$pdf->SetXY(135,$cline);
			$buss_name=iconv('UTF-8','windows-874',$num_invite2);
			$pdf->MultiCell(25,6,$buss_name,1,'C',0);

			$pdf->SetXY(160,$cline);
			$buss_name=iconv('UTF-8','windows-874',$num_match2);
			$pdf->MultiCell(25,6,$buss_name,1,'C',0);	
		}
										

		$cline = $cline +6;
		$suminvite1= $suminvite1 + $num_invite1;
		$summatch1 = $summatch1 + $num_match1;
									
		$suminvite2= $suminvite2 + $num_invite2;
		$summatch2 = $summatch2 + $num_match2;
										
		$i++;
		$nub++;
	}
	if($condition != 2){
		$pdf->SetXY(40,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวม");
		$pdf->MultiCell(55,6,$buss_name,1,'R',0);

		//สัปดาห์ที่ 1
		$pdf->SetXY(95,$cline);
		$buss_name=iconv('UTF-8','windows-874',$suminvite1);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		$pdf->SetXY(113,$cline);
		$buss_name=iconv('UTF-8','windows-874',$summatch1);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		//สัปดาห์ที่ 2
		$pdf->SetXY(131,$cline);
		$buss_name=iconv('UTF-8','windows-874',$suminvite2);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		$pdf->SetXY(149,$cline);
		$buss_name=iconv('UTF-8','windows-874',$summatch2);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);
	}else{
		$pdf->SetXY(30,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวม");
		$pdf->MultiCell(55,6,$buss_name,1,'R',0);

		$pdf->SetXY(85,$cline);
		$buss_name=iconv('UTF-8','windows-874',$suminvite1);
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(110,$cline);
		$buss_name=iconv('UTF-8','windows-874',$summatch1);
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(135,$cline);
		$buss_name=iconv('UTF-8','windows-874',$suminvite2);
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(160,$cline);
		$buss_name=iconv('UTF-8','windows-874',$summatch2);
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);
	}
$pdf->Output();
?>