<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

$id_user = $_POST["id_user"];
$SelectChart=$_POST["SelectChart"];
$name = $_POST["name"];
$year = $_POST["year"];
if($SelectChart=="a1"){
	$conmonth="";
	$txtcon="ประจำเดือนมกราคม-ธันวาคม";
}else{
	$month = $_POST["month"];
	$txtcon="ประจำเดือน";
	$txtmonth = $_POST["txtmonth"];
	$conmonth="AND (EXTRACT(MONTH FROM a.\"startDate\")='$month')";
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
$title=iconv('UTF-8','windows-874',"รายงานพนักงานขายแยกตามบุคคล");
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

/*Header of Table*/
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,35);
$buss_name=iconv('UTF-8','windows-874',"ชื่อพนักงาน : $name");
$pdf->MultiCell(100,6,$buss_name,0,'L',0);

$pdf->SetXY(5,41);
$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
$pdf->MultiCell(10,6,$buss_name,1,'C',0);

$pdf->SetXY(15,41);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);

$pdf->SetXY(45,41);
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);

$pdf->SetXY(70,41);
$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
$pdf->MultiCell(45,6,$buss_name,1,'C',0);

$pdf->SetXY(115,41);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ");
$pdf->MultiCell(15,6,$buss_name,1,'C',0);

$pdf->SetXY(130,41);
$buss_name=iconv('UTF-8','windows-874',"สีรถ");
$pdf->MultiCell(20,6,$buss_name,1,'C',0);

$pdf->SetXY(150,41);
$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);

$pdf->SetXY(175,41);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);

$cline = 47;

$query=pg_query("select a.\"IDNO\",b.\"P_BEGIN\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\",d.\"C_REGIS\",d.\"C_COLOR\",b.\"P_STDATE\",b.\"P_ACCLOSE\" , 
		b.\"P_CLDATE\"
from \"nw_startDateFp\" a
left join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
left join \"Fa1\" c on b.\"CusID\" = c.\"CusID\"
left join \"VCarregistemp\" d on b.\"IDNO\" = d.\"IDNO\" where a.\"id_user\"='$id_user' AND EXTRACT(YEAR FROM a.\"startDate\")='$year' $conmonth order by a.\"IDNO\"");
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
	$IDNO=$result["IDNO"];
	$P_BEGIN=$result["P_BEGIN"]; //ยอดสินเชื่อ
	$P_BEGIN2=number_format($P_BEGIN,2);
	$cusname=trim($result["A_FIRNAME"]).trim($result["A_NAME"])." ".trim($result["A_SIRNAME"]); //รายชื่อลูกค้า
	$C_REGIS =$result["C_REGIS"]; //ทะเบียนรถ
	$C_COLOR =$result["C_COLOR"]; //สีรถ		
	$P_STDATE =$result["P_STDATE"]; //วันที่ทำสัญญา
	$P_ACCLOSE = $result["P_ACCLOSE"];
	$P_CLDATE = $result["P_CLDATE"];

	if($nub > 36){
		$nub = 0;
		$cline = 31;
		$pdf->AddPage();
		
		/*Header of Table*/
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อพนักงาน : $name");
		$pdf->MultiCell(100,6,$buss_name,0,'L',0);

		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
		$pdf->MultiCell(10,6,$buss_name,1,'C',0);

		$pdf->SetXY(15,$cline);
		$buss_name=iconv('UTF-8','windows-874',"IDNO");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);

		$pdf->SetXY(45,$cline);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(70,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
		$pdf->MultiCell(45,6,$buss_name,1,'C',0);

		$pdf->SetXY(115,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ");
		$pdf->MultiCell(15,6,$buss_name,1,'C',0);

		$pdf->SetXY(130,$cline);
		$buss_name=iconv('UTF-8','windows-874',"สีรถ");
		$pdf->MultiCell(20,6,$buss_name,1,'C',0);

		$pdf->SetXY(150,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ");
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(175,$cline);
		$buss_name=iconv('UTF-8','windows-874',"สถานะ");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);
		
		$cline = $cline +6;	

	}
	
	//--- หาสถานะการค้างชำระ
	// list($state,$codestate) = behindhand($IDNO,$P_ACCLOSE,$P_CLDATE,$P_STDATE);
			
	$qry_behind = pg_query("SELECT xls_get_backduenum('$IDNO',1)");
			list($state) = pg_fetch_array($qry_behind);
			$qry_behind = pg_query("SELECT xls_get_backduenum('$IDNO')");
			list($codestate) = pg_fetch_array($qry_behind);
			
			if($codestate == '00'){ $sumclose++ ;}
			else if($codestate == '1'){ $summaryone++ ; }
			else if($codestate == '2'){ $sumtwo++ ; }
			else if($codestate >= '3'){ $sumthree++ ; }
			else{ $sumnormal++ ; }
	
	$pdf->SetFont('AngsanaNew','',12);
	
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',$i);
	$pdf->MultiCell(10,6,$buss_name,1,'C',0);
	
	$pdf->SetXY(15,$cline);
	$buss_name=iconv('UTF-8','windows-874',$IDNO);
	$pdf->MultiCell(30,6,$buss_name,1,'C',0);
	
	$pdf->SetXY(45,$cline);
	$buss_name=iconv('UTF-8','windows-874',$P_STDATE);
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);

	$pdf->SetXY(70,$cline);
	$buss_name=iconv('UTF-8','windows-874',$cusname);
	$pdf->MultiCell(45,6,$buss_name,1,'L',0);

	$pdf->SetXY(115,$cline);
	$buss_name=iconv('UTF-8','windows-874',$C_REGIS);
	$pdf->MultiCell(15,6,$buss_name,1,'L',0);

	$pdf->SetXY(130,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$C_COLOR");
	$pdf->MultiCell(20,6,$buss_name,1,'L',0);
	
	$pdf->SetXY(150,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$P_BEGIN2");
	$pdf->MultiCell(25,6,$buss_name,1,'R',0);
	
	$pdf->SetXY(175,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$state");
	$pdf->MultiCell(30,6,$buss_name,1,'R',0);
	
	$cline = $cline +6;								
	$sumbegin = $sumbegin+$P_BEGIN;
	$i++;
	$nub++;
}
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมยอดสินเชื่อ");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumbegin,2));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

if($nub > 30){
		$nub = 0;
		$cline = 31;
		$pdf->AddPage();

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาทั้งหมด");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($numrows));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะเป็นปกติ");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumnormal));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะค้าง  1 งวด");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($summaryone));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะค้าง  2 งวด");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumtwo));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะค้าง  3 งวดขึ้นไป");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumthree));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่ปิดบัญชีแล้ว");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumclose));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

}else{

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาทั้งหมด");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($numrows));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะเป็นปกติ");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumnormal));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะค้าง  1 งวด");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($summaryone));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะค้าง  2 งวด");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumtwo));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่มีสถานะค้าง  3 งวดขึ้นไป");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumthree));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

$cline = $cline +6;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่ปิดบัญชีแล้ว");
$pdf->MultiCell(145,6,$buss_name,1,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumclose));
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,6,$buss_name,'TBR','R',0);

}

$pdf->Output();

?>