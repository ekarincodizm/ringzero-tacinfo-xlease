<?php
include("../../config/config.php");
include("../function/nameMonth.php");
$user_report = $_SESSION["av_iduser"]; //user ที่ทำการออกรายการ
$date_report = nowDateTime(); //วันเวลาที่ออกรายการ 

$option=$_GET["option"];
$contype = $_GET["contype"];

//ค้นหาชื่อผู้ออกรายงาน
$qryname=pg_query("select fullname from \"Vfuser\" where id_user='$user_report'");
list($fullname)=pg_fetch_array($qryname);

$show_month="";
if($option=="day"){
	$datecon=$_GET["datecon"];
	$dateshow = "ประจำวันที่ $datecon";
	$condition="AND date(\"doerStamp\") = '$datecon' ";
}else if($option=="my"){
	$month=$_GET["month"];
	$year=$_GET["year"];
	$show_month = nameMonthTH($month);
	$dateshow = "ประจำเดือน $show_month  ปี ค.ศ.$year";
	
	$condition = "AND EXTRACT(MONTH FROM \"doerStamp\") = '$month' AND EXTRACT(YEAR FROM \"doerStamp\") = '$year' ";
}else if($option=="year"){
	$year=$_GET["year"];
	$dateshow = "ประจำปี ค.ศ. $year";
	$condition = "AND EXTRACT(YEAR FROM \"doerStamp\") = '$year' ";
}

$contypeqry="";
$txtcon="";
$contype = explode("@",$contype);
for($con = 0;$con < sizeof($contype) ; $con++){
	if($contype[$con] != ""){	
		if($contypeqry == "" ){
			if($contype[$con] == "1"){ //กรณีที่อนุมัิติและจ่ายแล้ว
				$contypeqry = "(\"dcNoteStatus\" = '1' AND (\"debtStatus\"='2' OR \"debtStatus\"='5')) ";
				$txtcon="แสดงรายการที่อนุัมัติและลูกค้ามีการจ่ายแล้ว";
			}else if($contype[$con] == "2"){ //กรณีที่อนุมัติและยังไม่จ่าย
				$contypeqry = "(\"dcNoteStatus\" = '1' AND \"debtStatus\"='1') ";
				$txtcon="แสดงรายการที่อนุัมัติและลูกค้ายังไม่ได้จ่าย";
			}else{
				$contypeqry = "\"dcNoteStatus\" = '$contype[$con]' ";
				if($contype[$con]==8){
					$txtcon="แสดงรายการระหว่างรออนุมัติ";
				}else if($contype[$con]==0){
					$txtcon="แสดงรายการที่ไม่อนุมัติ";
				}
			}
		}else{
			if($contype[$con] == "1"){ //กรณีที่อนุมัิติและจ่ายแล้ว
				$contypeqry = $contypeqry."OR (\"dcNoteStatus\" = '1' AND (\"debtStatus\"='2' OR \"debtStatus\"='5')) ";
				$txtcon=$txtcon.", แสดงรายการที่อนุมัติและลูกค้ามีการจ่ายแล้ว";
			}else if($contype[$con] == "2"){ //กรณีที่อนุมัติและยังไม่จ่าย
				$contypeqry = $contypeqry."OR (\"dcNoteStatus\" = '1' AND \"debtStatus\"='1') ";
				$txtcon=$txtcon.", แสดงรายการที่อนุมัติและลูกค้ายังไม่ไ่ด้จ่าย";
			}else{
				$contypeqry = $contypeqry."OR \"dcNoteStatus\" = '$contype[$con]' ";
				if($contype[$con]==8){
					$txtcon=$txtcon.", แสดงรายการระหว่างรออนุมัติ";
				}else if($contype[$con]==0){
					$txtcon=$txtcon.", แสดงรายการที่ไม่อนุมัติ";
				}
			}
		}		
	}
}

if($contypeqry != ""){
	$contypeqry = "AND (".$contypeqry.")";
	$condition = $condition.$contypeqry;
}

// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,18); 
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

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP) รายงานส่วนลด");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14); 
$pdf->SetXY(4,27); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $date_report");
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14); 
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์: $fullname");
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,18);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(4,27);
$gmm=iconv('UTF-8','windows-874',$dateshow);
$pdf->MultiCell(280,4,$gmm,0,'L',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"เงื่อนไขรายงาน : $txtcon");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(5,37); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(30,37); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก/ผู้เช่าซื้อ");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',8);
$pdf->SetXY(55,37); 
$buss_name=iconv('UTF-8','windows-874',"รหัส");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);
	$pdf->SetXY(55,41); 
	$buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(65,37); 
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดหนี้");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(95,37); 
$buss_name=iconv('UTF-8','windows-874',"เลขอ้างอิง");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(120,37); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้แรกเริ่ม");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(145,37); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้เดิมล่าสุด");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(170,37); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้ใหม่");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(195,37); 
$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(220,37); 
$buss_name=iconv('UTF-8','windows-874',"วันเวลา");
$pdf->MultiCell(13,4,$buss_name,0,'C',0);
	$pdf->SetXY(220,41); 
	$buss_name=iconv('UTF-8','windows-874',"ทำรายการ");
	$pdf->MultiCell(13,4,$buss_name,0,'C',0);

$pdf->SetXY(233,37); 
$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติ");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(258,37); 
$buss_name=iconv('UTF-8','windows-874',"วันเวลา");
$pdf->MultiCell(13,4,$buss_name,0,'C',0);
	$pdf->SetXY(258,41); 
	$buss_name=iconv('UTF-8','windows-874',"อนุมัติ");
	$pdf->MultiCell(13,4,$buss_name,0,'C',0);

$pdf->SetXY(271,37); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(4,38); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,8,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 46;
$i = 1;
$j = 0;  
                        
$qry = pg_query("SELECT  * FROM account.thcap_dncn_discount_report where \"dcType\" = '2' $condition ");
$row=pg_num_rows($qry);
	
while($res=pg_fetch_array($qry)){
	$conid = $res["contractID"];	//เลขที่สัญญา		
	$maincus_fullname = $res["maincus_fullname"]; //-- หาชื่อผู้กู้หลัก
	$typePayID = $res["typePayID"]; // รหัสประเภทค่าใช้จ่าย
	$tpdetail = $res["tpdetail"]; // รายละเอียดประเภทค่าใช้จ่าย
	$typePayRefValue = $res["typePayRefValue"];// หา Ref
	$netstart=number_format($res["netstart"],2)."(".number_format($res["vatstart"],2).")"; //จำนวนหนี้แรกเริ่ม
	$netbefore=number_format($res["netbefore"],2)."(".number_format($res["vatbefore"],2).")"; //จำนวนหนี้เดิืมล่าสุด
	$netnow=number_format($res["netnow"],2)."(".number_format($res["vatnow"],2).")"; //จำนวนหนี้ใหม่
	$doer_fullname=$res["doerName"]; //ชื่อผู้ขอ
	$doerStamp = $res["doerStamp"]; //วันที่ขอ
	$appv_fullname=$res["appvName"]; //ชื่อผู้อนุมัติ
	$appvStamp=$res["appvStamp"]; //วันเวลาที่อนุมัติ	
	$status = $res["statusname"];//สถานะการอนุมัติ
	
	if($debtStatus == 5)
	{
		$status = "อนุัมัติและลดหนี้เป็น 0.00";
	}
	
	if($i > 17){ 
		$pdf->AddPage(); 
		$cline = 46; 
		$i=1; 

		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(10,10);
		$title=iconv('UTF-8','windows-874',"(THCAP) รายงานส่วนลด");
		$pdf->MultiCell(280,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14); 
		$pdf->SetXY(4,27); 
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $date_report");
		$pdf->MultiCell(285,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14); 
		$pdf->SetXY(4,32); 
		$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์: $fullname");
		$pdf->MultiCell(285,4,$buss_name,0,'R',0);

		$pdf->SetXY(10,18);
		$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
		$pdf->MultiCell(280,4,$buss_name,0,'C',0);

		$pdf->SetXY(4,27);
		$gmm=iconv('UTF-8','windows-874',$dateshow);
		$pdf->MultiCell(280,4,$gmm,0,'L',0);

		$pdf->SetXY(4,32); 
		$buss_name=iconv('UTF-8','windows-874',"เงื่อนไขรายงาน : $txtcon");
		$pdf->MultiCell(285,4,$buss_name,'B','L',0);

		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(5,37); 
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(30,37); 
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก/ผู้เช่าซื้อ");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',8);
		$pdf->SetXY(55,37); 
		$buss_name=iconv('UTF-8','windows-874',"รหัส");
		$pdf->MultiCell(10,4,$buss_name,0,'C',0);
			$pdf->SetXY(55,41); 
			$buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
			$pdf->MultiCell(10,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(65,37); 
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดหนี้");
		$pdf->MultiCell(30,8,$buss_name,0,'C',0);

		$pdf->SetXY(95,37); 
		$buss_name=iconv('UTF-8','windows-874',"เลขอ้างอิง");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(120,37); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้แรกเริ่ม");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(145,37); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้เดิมล่าสุด");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(170,37); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้ใหม่");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(195,37); 
		$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(220,37); 
		$buss_name=iconv('UTF-8','windows-874',"วันเวลา");
		$pdf->MultiCell(13,4,$buss_name,0,'C',0);
			$pdf->SetXY(220,41); 
			$buss_name=iconv('UTF-8','windows-874',"ทำรายการ");
			$pdf->MultiCell(13,4,$buss_name,0,'C',0);

		$pdf->SetXY(233,37); 
		$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติ");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(258,37); 
		$buss_name=iconv('UTF-8','windows-874',"วันเวลา");
		$pdf->MultiCell(13,4,$buss_name,0,'C',0);
			$pdf->SetXY(258,41); 
			$buss_name=iconv('UTF-8','windows-874',"อนุมัติ");
			$pdf->MultiCell(13,4,$buss_name,0,'C',0);

		$pdf->SetXY(271,37); 
		$buss_name=iconv('UTF-8','windows-874',"สถานะ");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(4,38); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(285,8,$buss_name,'B','L',0);
	}

	$pdf->SetFont('AngsanaNew','',9);
	$pdf->SetXY(5,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$conid);
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(30,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$maincus_fullname);
	$pdf->MultiCell(25,4,$buss_name,0,'L',0);

	$pdf->SetXY(55,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$typePayID);
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

	$pdf->SetXY(65,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$tpdetail);
	$pdf->MultiCell(30,4,$buss_name,0,'L',0);

	$pdf->SetXY(95,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$typePayRefValue);
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(120,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$netstart);
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(145,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$netbefore);
	$pdf->MultiCell(25,4,$buss_name,0,'R',0);

	$pdf->SetXY(170,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$netnow);
	$pdf->MultiCell(25,4,$buss_name,0,'R',0);

	$pdf->SetXY(195,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$doer_fullname);
	$pdf->MultiCell(25,4,$buss_name,0,'L',0);

	$pdf->SetXY(220,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$doerStamp);
	$pdf->MultiCell(13,4,$buss_name,0,'C',0);

	$pdf->SetXY(233,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$appv_fullname);
	$pdf->MultiCell(25,4,$buss_name,0,'L',0);

	$pdf->SetXY(258,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$appvStamp);
	$pdf->MultiCell(13,4,$buss_name,0,'C',0);

	$pdf->SetXY(271,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$status);
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(4,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(285,8,$buss_name,'B','L',0);
	 
	// -----------

	$cline+=8; 
	$i+=1;  
	}  
$pdf->Output();
?>