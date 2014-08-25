<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');


$year = $_POST["year"];
$SelectChart=$_POST["SelectChart"];

if($SelectChart=="a1"){
	$conmonth="";
	$txtcon="ประจำเดือนมกราคม-ธันวาคม";
}else{
	$month = $_POST["month"];
	$txtcon="ประจำเดือน";
	$txtmonth = $_POST["txtmonth"];
	$conmonth="AND EXTRACT(MONTH FROM \"nw_startDateFp\".\"startDate\")='$month'";
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
$title=iconv('UTF-8','windows-874',"รายงานพนักงานขายแบบรวม");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$monthyear=iconv('UTF-8','windows-874',"$txtcon$txtmonth ค.ศ. $year");
$pdf->MultiCell(190,4,$monthyear,0,'C',0);

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

$pdf->SetXY(10,35);
$buss_name=iconv('UTF-8','windows-874',"รหัสพนักงาน");
$pdf->MultiCell(20,6,$buss_name,1,'C',0);

$pdf->SetXY(30,35);
$buss_name=iconv('UTF-8','windows-874',"ชื่อพนักงาน");
$pdf->MultiCell(50,6,$buss_name,1,'C',0);

$pdf->SetXY(80,35);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญาที่ทำ");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);

$pdf->SetXY(110,35);
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);

$pdf->SetXY(135,35);
$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);

$pdf->SetXY(165,35);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);

$cline = 41;

$query=pg_query("select \"nw_startDateFp\".\"id_user\" , \"nw_startDateFp\".\"IDNO\" , \"Fp\".\"P_STDATE\" , \"Fp\".\"P_BEGIN\" ,\"Fp\".\"P_ACCLOSE\" , 
		\"Fp\".\"P_CLDATE\", \"Vfuser\".\"fullname\"
						from \"nw_startDateFp\" , \"Vfuser\" , \"Fp\"
						where \"nw_startDateFp\".\"id_user\" = \"Vfuser\".\"id_user\"
							and \"nw_startDateFp\".\"IDNO\" = \"Fp\".\"IDNO\"
							and (EXTRACT(YEAR FROM \"nw_startDateFp\".\"startDate\")='$year' $conmonth)
						order by \"nw_startDateFp\".\"id_user\" , \"nw_startDateFp\".\"IDNO\" ");
$numrows=pg_num_rows($query);
		
$sumbegin=0;
$i=1;
$nub=0;
$sumclose = 0;
$summaryone = 0;
$sumtwo = 0;
$sumthree = 0;
$summormal = 0;
while($result=pg_fetch_array($query)){
	$id_user=$result["id_user"];
	$fullname=$result["fullname"];
	$IDNO=$result["IDNO"];
	$P_STDATE=$result["P_STDATE"];
	$beginx =$result["P_BEGIN"];
	$sumbeginx=number_format($beginx,2);
	$P_ACCLOSE = trim($result["P_ACCLOSE"]);
	$P_CLDATE = trim($result["P_CLDATE"]);

	if($nub > 36){
		$nub = 0;
		$cline = 41;
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(10,10);
		$title=iconv('UTF-8','windows-874',"รายงานพนักงานขายแบบรวม");
		$pdf->MultiCell(190,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(10,16);
		$monthyear=iconv('UTF-8','windows-874',"$txtcon$txtmonth ค.ศ. $year");
		$pdf->MultiCell(190,4,$monthyear,0,'C',0);

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

		$pdf->SetXY(10,35);
		$buss_name=iconv('UTF-8','windows-874',"รหัสพนักงาน");
		$pdf->MultiCell(20,6,$buss_name,1,'C',0);

		$pdf->SetXY(30,35);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อพนักงาน");
		$pdf->MultiCell(50,6,$buss_name,1,'C',0);

		$pdf->SetXY(80,35);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญาที่ทำ");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);

		$pdf->SetXY(110,35);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(135,35);
		$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);

		$pdf->SetXY(165,35);
		$buss_name=iconv('UTF-8','windows-874',"สถานะ");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);
	}
	$pdf->SetFont('AngsanaNew','',14);
	
	//------- เช็ีคว่าใช่คนเดิมหรือไม่
	$checkIDone = $id_user;
	if($i==1)
	{
		$checkIDtwo = $checkIDone;
	}
	else
	{
		if($checkIDone != $checkIDtwo)
		{
			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(10,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รวมยอดสินเชื่อ");
			$pdf->MultiCell(125,6,$buss_name,1,'R',0);

			$pdf->SetXY(135,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($sumone,2));
			$pdf->MultiCell(30,6,$buss_name,'LTB','R',0);
			
			$pdf->SetXY(165,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

			$checkIDtwo = $checkIDone;
			$sumone = 0;
			$cline+=6;
			$nub++;
		}
	}
	//------- จบการเช็คคนเดิม
	
	//--- หาสถานะการค้างชำระ
	
			$qry_behind = pg_query("SELECT xls_get_backduenum('$IDNO',1)");
			list($state) = pg_fetch_array($qry_behind);
			$qry_behind = pg_query("SELECT xls_get_backduenum('$IDNO')");
			list($codestate) = pg_fetch_array($qry_behind);
			
			if($codestate == '00'){ $sumclose++ ;}
			else if($codestate == '1'){ $summaryone++ ; }
			else if($codestate == '2'){ $sumtwo++ ; }
			else if($codestate >= '3'){ $sumthree++ ; }
			else{ $sumnormal++ ; }
	
	
	
	$pdf->SetXY(10,$cline);
	$buss_name=iconv('UTF-8','windows-874',$id_user);
	$pdf->MultiCell(20,6,$buss_name,1,'C',0);
	
	$pdf->SetXY(30,$cline);
	$buss_name=iconv('UTF-8','windows-874',$fullname);
	$pdf->MultiCell(50,6,$buss_name,1,'L',0);
	
	$pdf->SetXY(80,$cline);
	$buss_name=iconv('UTF-8','windows-874',$IDNO);
	$pdf->MultiCell(30,6,$buss_name,1,'C',0);

	$pdf->SetXY(110,$cline);
	$buss_name=iconv('UTF-8','windows-874',$P_STDATE);
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);
	
	$pdf->SetXY(135,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($beginx,2));
	$pdf->MultiCell(30,6,$buss_name,1,'R',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(165,$cline);
	$buss_name=iconv('UTF-8','windows-874',$state);
	$pdf->MultiCell(30,6,$buss_name,1,'C',0);
	
	
	
	
	$cline = $cline +6;
	$sumone = $sumone+$beginx;
	$sumbegin = $sumbegin+$beginx;
	$i++;
	$nub++;
	
	if($i == $numrows+1)
	{
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(10,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมยอดสินเชื่อ");
		$pdf->MultiCell(125,6,$buss_name,1,'R',0);
		
		$pdf->SetXY(135,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumone,2));
		$pdf->MultiCell(30,6,$buss_name,1,'R',0);
		
		$pdf->SetXY(165,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);
		
		$cline = $cline +6;
		$i++;
		$nub++;
	}
}
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมยอดสินเชื่อทั้งหมด");
$pdf->MultiCell(125,6,$buss_name,1,'R',0);

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumbegin,2));
$pdf->MultiCell(30,6,$buss_name,1,'R',0);

$pdf->SetXY(165,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาทั้งหมด");
$pdf->MultiCell(125,6,$buss_name,1,'R',0);

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($numrows));
$pdf->MultiCell(30,6,$buss_name,1,'R',0);

$pdf->SetXY(165,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะเป็นปกติ");
$pdf->MultiCell(125,6,$buss_name,1,'R',0);

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumnormal));
$pdf->MultiCell(30,6,$buss_name,1,'R',0);

$pdf->SetXY(165,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะค้าง  1 งวด");
$pdf->MultiCell(125,6,$buss_name,1,'R',0);

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($summaryone));
$pdf->MultiCell(30,6,$buss_name,1,'R',0);

$pdf->SetXY(165,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะค้าง  2 งวด");
$pdf->MultiCell(125,6,$buss_name,1,'R',0);

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumtwo));
$pdf->MultiCell(30,6,$buss_name,1,'R',0);

$pdf->SetXY(165,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะค้าง  3 งวดขึ้นไป");
$pdf->MultiCell(125,6,$buss_name,1,'R',0);

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumthree));
$pdf->MultiCell(30,6,$buss_name,1,'R',0);

$pdf->SetXY(165,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่ปิดบัญชีแล้ว");
$pdf->MultiCell(125,6,$buss_name,1,'R',0);

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumclose));
$pdf->MultiCell(30,6,$buss_name,1,'R',0);

$pdf->SetXY(165,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

	
$pdf->Output();
?>