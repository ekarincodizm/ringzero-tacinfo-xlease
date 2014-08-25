<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

$id_user = $_SESSION["av_iduser"];
$month = $_POST["month"];
$year = $_POST["year"];

$startDate = $year."-".$month."-01"." "."00:00:00"; 
	
if($month == "04" || $month == "06" || $month == "09" || $month == "11"){
	$endDate = $year."-".$month."-30"." "."23:59:59"; 
	if($month == "04"){
		$txtmonth = "เมษายน";
	}else if($month == "06"){
		$txtmonth = "มิถุนายน";
	}else if($month == "09"){
		$txtmonth = "กันยายน";
	}else if($month == "11"){
		$txtmonth = "พฤศจิกายน";
	}
}else if($month == "02" and ($year%4 == 0)){
	$endDate = $year."-".$month."-29"." "."23:59:59"; 
	$txtmonth = "กุมภาพันธ์";
}else if($month == "02" and ($year%4 != 0)){
	$endDate = $year."-".$month."-28"." "."23:59:59"; 
	$txtmonth = "กุมภาพันธ์";
}else{
	$endDate = $year."-".$month."-31"." "."23:59:59"; 
	if($month == "01"){
		$txtmonth = "มกราคม";
	}else if($month == "03"){
		$txtmonth = "มีนาคม";
	}else if($month == "05"){
		$txtmonth = "พฤษภาคม";
	}else if($month == "07"){
		$txtmonth = "กรกฎาคม";
	}else if($month == "08"){
		$txtmonth = "สิงหาคม";
	}else if($month == "10"){
		$txtmonth = "ตุลาคม";
	}else if($month == "12"){
		$txtmonth = "ธันวาคม";
	}
}

$nowdate = Date('Y-m-d');

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
$title=iconv('UTF-8','windows-874',"สรุปรายละเอียดการจับคู่");
$pdf->MultiCell(190,4,$title,0,'C',0);

if($year == "" || $month == ""){
	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(10,16);
	$monthyear=iconv('UTF-8','windows-874',"แสดงรายการทั้งหมด");
	$pdf->MultiCell(190,4,$monthyear,0,'C',0);
}else{
	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(10,16);
	$monthyear=iconv('UTF-8','windows-874',"เดือน$txtmonth  ค.ศ.$year");
	$pdf->MultiCell(190,4,$monthyear,0,'C',0);
}
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetXY(155,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

/*Header of Table*/
$pdf->SetXY(30,35);
$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
$pdf->MultiCell(20,6,$buss_name,1,'C',0);

$pdf->SetXY(50,35);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญาเก่า");
$pdf->MultiCell(36,6,$buss_name,1,'C',0);

$pdf->SetXY(86,35);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญาใหม่");
$pdf->MultiCell(36,6,$buss_name,1,'C',0);

$pdf->SetXY(122,35);
$buss_name=iconv('UTF-8','windows-874',"ID, ชื่อ - สกุลพนักงานที่จับคู่");
$pdf->MultiCell(60,6,$buss_name,1,'C',0);



$cline = 41;
$i = 1;
//ดึงค่างวดสูงสุด ต่ำสุด และ limit ล่าสุดขึ้นมาเพื่อนำมาตรวจสอบตามเงื่อนไข
if($year != ""){
	$qry_nomatch=pg_query("SELECT A.\"IDNO\" AS \"idno_new\",B.\"IDNO\" AS \"idno_old\",B.\"id_user\",C.\"fullname\" FROM refinance.\"match_invite\" A
	left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\" 
	left join \"Vfuser\" C on B.\"id_user\" = C.\"id_user\"
	where (A.\"matchDate\" between '$startDate' and '$endDate') order by B.\"id_user\"");
}else{
	$qry_nomatch=pg_query("SELECT A.\"IDNO\" AS \"idno_new\",B.\"IDNO\" AS \"idno_old\",B.\"id_user\",C.\"fullname\" FROM refinance.\"match_invite\" A
	left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\" 
	left join \"Vfuser\" C on B.\"id_user\" = C.\"id_user\"
	order by B.\"id_user\"");
}
$nrows=pg_num_rows($qry_nomatch);

while($res=pg_fetch_array($qry_nomatch)){
    $idno_new=$res["idno_new"];
	$idno_old=$res["idno_old"];
	$id_user=$res["id_user"];
	$fullname = $res["fullname"];

	if($nub > 36){
		$nub = 0;
		$cline = 41;
		$pdf->AddPage();
		
		/*Header of Table*/
		$pdf->SetXY(30,35);
		$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
		$pdf->MultiCell(20,6,$buss_name,1,'C',0);

		$pdf->SetXY(50,35);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญาเก่า");
		$pdf->MultiCell(36,6,$buss_name,1,'C',0);

		$pdf->SetXY(86,35);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญาใหม่");
		$pdf->MultiCell(36,6,$buss_name,1,'C',0);

		$pdf->SetXY(122,35);
		$buss_name=iconv('UTF-8','windows-874',"ID, ชื่อ - สกุลพนักงานที่จับคู่");
		$pdf->MultiCell(60,6,$buss_name,1,'C',0);
	}
   
	$pdf->SetXY(30,$cline);
	$buss_name=iconv('UTF-8','windows-874',$i);
	$pdf->MultiCell(20,6,$buss_name,1,'C',0);

	$pdf->SetXY(50,$cline);
	$buss_name=iconv('UTF-8','windows-874',$idno_old);
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(86,$cline);
	$buss_name=iconv('UTF-8','windows-874',$idno_new);
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(122,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$id_user, $fullname");
	$pdf->MultiCell(60,6,$buss_name,1,'L',0);
	
	$cline = $cline +6;								
	$i++;
	$nub++;
    

}

$pdf->Output();
?>