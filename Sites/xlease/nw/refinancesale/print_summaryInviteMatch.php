<?php
session_start();
include("../../config/config.php");

$condition = $_POST['condition'];
$txtcon = $_POST['txtcon'];
$txtmonth = $_POST['txtmonth'];

if($condition == 1){
	$startDate1 = $_POST['startDate1'];
	$startDate2 = $_POST['startDate2'];
	$startDate3 = $_POST['startDate3'];
	$startDate4 = $_POST['startDate4'];
	
	$endDate1 = $_POST['endDate1'];
	$endDate2 = $_POST['endDate2'];
	$endDate3 = $_POST['endDate3'];
	$endDate4 = $_POST['endDate4'];
}else{
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
}

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
$title=iconv('UTF-8','windows-874',$txtcon);
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',$txtmonth);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetXY(155,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

if($condition == 1){
	$pdf->SetXY(6,35);
	$buss_name=iconv('UTF-8','windows-874',"ที่");
	$pdf->MultiCell(10,18,$buss_name,1,'C',0);

	$pdf->SetXY(16,35);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อ - สกุล");
	$pdf->MultiCell(45,18,$buss_name,1,'C',0);

	$pdf->SetXY(61,35);
	$buss_name=iconv('UTF-8','windows-874',"สัปดาห์ที่  1");
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(61,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(79,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(97,35);
	$buss_name=iconv('UTF-8','windows-874',"สัปดาห์ที่  2");
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(97,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(115,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(133,35);
	$buss_name=iconv('UTF-8','windows-874',"สัปดาห์ที่  3");
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(133,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(151,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(169,35);
	$buss_name=iconv('UTF-8','windows-874',"สัปดาห์ที่  4");
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(169,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(187,41);
	$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$cline = 53;
	$i = 1;
	$nub = 1;
	$qry_user=pg_query("select A.\"id_user\",B.\"fullname\" from refinance.\"user_invite\" A left join \"Vfuser\" B on A.\"id_user\" = B.\"id_user\" where \"status_use\" = 'TRUE' ORDER BY A.\"id_user\"");
	$numr=pg_num_rows($qry_user);
	while($resuser=pg_fetch_array($qry_user)){
		$id_user = $resuser["id_user"];
		$fullname = $resuser["fullname"];
		
		if($nub > 36){
			$nub = 1;
			$cline = 53;
			$pdf->AddPage();

			$pdf->SetXY(6,35);
			$buss_name=iconv('UTF-8','windows-874',"ที่");
			$pdf->MultiCell(10,18,$buss_name,1,'C',0);

			$pdf->SetXY(16,35);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อ - สกุล");
			$pdf->MultiCell(45,18,$buss_name,1,'C',0);

			$pdf->SetXY(61,35);
			$buss_name=iconv('UTF-8','windows-874',"สัปดาห์ที่  1");
			$pdf->MultiCell(36,6,$buss_name,1,'C',0);

			$pdf->SetXY(61,41);
			$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);

			$pdf->SetXY(79,41);
			$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);

			$pdf->SetXY(97,35);
			$buss_name=iconv('UTF-8','windows-874',"สัปดาห์ที่  2");
			$pdf->MultiCell(36,6,$buss_name,1,'C',0);

			$pdf->SetXY(97,41);
			$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);

			$pdf->SetXY(115,41);
			$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);

			$pdf->SetXY(133,35);
			$buss_name=iconv('UTF-8','windows-874',"สัปดาห์ที่  3");
			$pdf->MultiCell(36,6,$buss_name,1,'C',0);

			$pdf->SetXY(133,41);
			$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);

			$pdf->SetXY(151,41);
			$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);

			$pdf->SetXY(169,35);
			$buss_name=iconv('UTF-8','windows-874',"สัปดาห์ที่  4");
			$pdf->MultiCell(36,6,$buss_name,1,'C',0);

			$pdf->SetXY(169,41);
			$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การชักชวน");
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);

			$pdf->SetXY(187,41);
			$buss_name=iconv('UTF-8','windows-874',"    จำนวน     การจับคู่");
			$pdf->MultiCell(18,6,$buss_name,1,'C',0);
			
		}							
		/*---หาจำนวนการชวน 1 สัญญา ถือเป็น 1 การชักชวน---*/
		//สัปดาห์ที่ 1 
		$qry_invite1=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate1' and '$endDate1') group by \"IDNO\""); 
		$num_invite1=pg_num_rows($qry_invite1);
									
		//สัปดาห์ที่ 2
		$qry_invite2=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate2' and '$endDate2') group by \"IDNO\""); 
		$num_invite2=pg_num_rows($qry_invite2);
									
		//สัปดาห์ที่ 3
		$qry_invite3=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate3' and '$endDate3') group by \"IDNO\""); 
		$num_invite3=pg_num_rows($qry_invite3);
									
		//สัปดาห์ที่ 4
		$qry_invite4=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate4' and '$endDate4') group by \"IDNO\""); 
		$num_invite4=pg_num_rows($qry_invite4);
									
		/*------หาจำนวนการจับคู่-----*/
		//สัปดาห์ที่ 1
		$qry_match1=pg_query("SELECT * FROM refinance.\"match_invite\" A
		left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
		where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate1' and '$endDate1')"); 
		$num_match1=pg_num_rows($qry_match1);
									
		//สัปดาห์ที่ 2
		$qry_match2=pg_query("SELECT * FROM refinance.\"match_invite\" A
		left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
		where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate2' and '$endDate2')"); 
		$num_match2=pg_num_rows($qry_match2);
									
		//สัปดาห์ที่ 3
		$qry_match3=pg_query("SELECT * FROM refinance.\"match_invite\" A
		left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
		where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate3' and '$endDate3')"); 
		$num_match3=pg_num_rows($qry_match3);
									
		//สัปดาห์ที่ 4
		$qry_match4=pg_query("SELECT * FROM refinance.\"match_invite\" A
		left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
		where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate4' and '$endDate4')"); 
		$num_match4=pg_num_rows($qry_match4);
									
		$pdf->SetXY(6,$cline);
		$buss_name=iconv('UTF-8','windows-874',$i);
		$pdf->MultiCell(10,6,$buss_name,1,'C',0);

		$pdf->SetXY(16,$cline);
		$buss_name=iconv('UTF-8','windows-874',$fullname);
		$pdf->MultiCell(45,6,$buss_name,1,'L',0);

		//สัปดาห์ที่ 1
		$pdf->SetXY(61,$cline);
		$buss_name=iconv('UTF-8','windows-874',$num_invite1);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		$pdf->SetXY(79,$cline);
		$buss_name=iconv('UTF-8','windows-874',$num_match1);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		//สัปดาห์ที่ 2
		$pdf->SetXY(97,$cline);
		$buss_name=iconv('UTF-8','windows-874',$num_invite2);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		$pdf->SetXY(115,$cline);
		$buss_name=iconv('UTF-8','windows-874',$num_match2);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		//สัปดาห์ที่ 3
		$pdf->SetXY(133,$cline);
		$buss_name=iconv('UTF-8','windows-874',$num_invite3);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		$pdf->SetXY(151,$cline);
		$buss_name=iconv('UTF-8','windows-874',$num_match3);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		//สัปดาห์ที่ 4
		$pdf->SetXY(169,$cline);
		$buss_name=iconv('UTF-8','windows-874',$num_invite4);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		$pdf->SetXY(187,$cline);
		$buss_name=iconv('UTF-8','windows-874',$num_match4);
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);
										

		$cline = $cline +6;
		$suminvite1= $suminvite1 + $num_invite1;
		$summatch1 = $summatch1 + $num_match1;
									
		$suminvite2= $suminvite2 + $num_invite2;
		$summatch2 = $summatch2 + $num_match2;
									
		$suminvite3= $suminvite3 + $num_invite3;
		$summatch3 = $summatch3 + $num_match3;
									
		$suminvite4= $suminvite4 + $num_invite4;
		$summatch4 = $summatch4 + $num_match4;
		
		$i++;
		$nub++;
	}
	$pdf->SetXY(6,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รวม");
	$pdf->MultiCell(55,6,$buss_name,1,'R',0);

	//สัปดาห์ที่ 1
	$pdf->SetXY(61,$cline);
	$buss_name=iconv('UTF-8','windows-874',$suminvite1);
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(79,$cline);
	$buss_name=iconv('UTF-8','windows-874',$summatch1);
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	//สัปดาห์ที่ 2
	$pdf->SetXY(97,$cline);
	$buss_name=iconv('UTF-8','windows-874',$suminvite2);
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(115,$cline);
	$buss_name=iconv('UTF-8','windows-874',$summatch2);
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	//สัปดาห์ที่ 3
	$pdf->SetXY(133,$cline);
	$buss_name=iconv('UTF-8','windows-874',$suminvite3);
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(151,$cline);
	$buss_name=iconv('UTF-8','windows-874',$summatch3);
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	//สัปดาห์ที่ 4
	$pdf->SetXY(169,$cline);
	$buss_name=iconv('UTF-8','windows-874',$suminvite4);
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);

	$pdf->SetXY(187,$cline);
	$buss_name=iconv('UTF-8','windows-874',$summatch4);
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);
}else{
	$pdf->SetXY(40,35);
	$buss_name=iconv('UTF-8','windows-874',"ที่");
	$pdf->MultiCell(10,6,$buss_name,1,'C',0);

	$pdf->SetXY(50,35);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อ - สกุล");
	$pdf->MultiCell(45,6,$buss_name,1,'C',0);

	$pdf->SetXY(95,35);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนการชักชวน");
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(131,35);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนที่จับคู่สำเร็จ");
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);
	
	$cline = 41;
	$i = 1;
	$nub = 1;
	$qry_user=pg_query("select A.\"id_user\",B.\"fullname\" from refinance.\"user_invite\" A
	left join \"Vfuser\" B on A.\"id_user\" = B.\"id_user\" where \"status_use\" = 'TRUE' ORDER BY A.\"id_user\""); 
	
	while($resuser=pg_fetch_array($qry_user)){
		$id_user = $resuser["id_user"];
		$fullname = $resuser["fullname"];
							
		if($nub > 36){
			$nub = 1;
			$cline = 41;
			$pdf->AddPage();

			$pdf->SetXY(40,35);
			$buss_name=iconv('UTF-8','windows-874',"ที่");
			$pdf->MultiCell(10,6,$buss_name,1,'C',0);

			$pdf->SetXY(50,35);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อ - สกุล");
			$pdf->MultiCell(45,6,$buss_name,1,'C',0);

			$pdf->SetXY(95,35);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนกาัรชักชวน");
			$pdf->MultiCell(36,6,$buss_name,1,'C',0);

			$pdf->SetXY(131,35);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนที่จับคู่สำเร็จ");
			$pdf->MultiCell(36,6,$buss_name,1,'C',0);
			
		}
		
		/*---หาจำนวนการชวน 1 สัญญา ถือเป็น 1 การชักชวน---*/
		$qry_invite=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate' and '$endDate') group by \"IDNO\""); 
		$num_invite=pg_num_rows($qry_invite);
							
		/*------หาจำนวนการจับคู่-----*/
		$qry_match=pg_query("SELECT * FROM refinance.\"match_invite\" A
		left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
		where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate' and '$endDate')"); 
		$num_match=pg_num_rows($qry_match);
							
		$pdf->SetXY(40,$cline);
		$buss_name=iconv('UTF-8','windows-874',$i);
		$pdf->MultiCell(10,6,$buss_name,1,'C',0);

		$pdf->SetXY(50,$cline);
		$buss_name=iconv('UTF-8','windows-874',$fullname);
		$pdf->MultiCell(45,6,$buss_name,1,'L',0);

		$pdf->SetXY(95,$cline);
		$buss_name=iconv('UTF-8','windows-874',$num_invite);
		$pdf->MultiCell(36,6,$buss_name,1,'C',0);

		$pdf->SetXY(131,$cline);
		$buss_name=iconv('UTF-8','windows-874',$num_match);
		$pdf->MultiCell(36,6,$buss_name,1,'C',0);
								
		$cline = $cline+6;
		$suminvite= $suminvite + $num_invite;
		$summatch = $summatch + $num_match;
		$i++;
		$nub++;
	}
	$pdf->SetXY(40,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รวม");
	$pdf->MultiCell(55,6,$buss_name,1,'R',0);

	$pdf->SetXY(95,$cline);
	$buss_name=iconv('UTF-8','windows-874',$suminvite);
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(131,$cline);
	$buss_name=iconv('UTF-8','windows-874',$summatch);
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);
}
$pdf->Output();
?>