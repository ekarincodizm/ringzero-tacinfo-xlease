<?php
session_start();
include("../../config/config.php");

$chksh = trim($_POST["chkshPDF"]);
$condate = $_POST["condatePDF"];
$user_id = $_SESSION["av_iduser"];

	//ชื่อผู้ทำรายการ
	$qry_user = pg_query("select fullname from \"Vfuser\" where id_user = '$user_id' ");
	$userName=pg_fetch_result($qry_user,0);
	
if($chksh=="shday"){

	$datepicker = $_POST["datepickerPDF"];
	if($datepicker!=""){
		if($condate==1){
			$txtcondate="วันที่สั่งงาน";	
			$conditiondate="\"AssignDate\"::date='$datepicker'";
		
		}else if($condate==2){
			$txtcondate="วันที่กำหนดส่งงาน";
			$conditiondate="\"DeadlineDate\"::date='$datepicker'";
		}
		$datetext = "วันที่ ".$datepicker;
	}
} else if($chksh=="shmonth"){
	$slbxSelectMonth = $_POST["slbxSelectMonthPDF"];
	$slbxSelectYear = $_POST["slbxSelectYearPDF"];
	if($slbxSelectMonth!="" and $slbxSelectYear!=""){
		if($condate==1){
			if($slbxSelectMonth=="not"){
				$txtcondate="วันที่สั่งงาน";	
				$conditiondate="EXTRACT(YEAR FROM \"AssignDate\")='$slbxSelectYear'";
				$datetext = "เดือน  ทุกเดือน    ปี  ".$slbxSelectYear;
			}else {
				$txtcondate="วันที่สั่งงาน";	
				$conditiondate="EXTRACT(MONTH FROM \"AssignDate\")='$slbxSelectMonth' and EXTRACT(YEAR FROM \"AssignDate\")='$slbxSelectYear'";
				$datetext = "เดือน ".$slbxSelectMonth." ปี  ".$slbxSelectYear;
			}
		}else if($condate==2){
			if($slbxSelectMonth=="not"){
				$txtcondate="วันที่กำหนดส่งงาน";
				$conditiondate="EXTRACT(YEAR FROM \"DeadlineDate\")='$slbxSelectYear'";
				$datetext = "เดือน   ทุกเดือน    ปี ".$slbxSelectYear;
			} else {
				$txtcondate="วันที่กำหนดส่งงาน";
				$conditiondate="EXTRACT(MONTH FROM \"DeadlineDate\")='$slbxSelectMonth' and EXTRACT(YEAR FROM \"DeadlineDate\")='$slbxSelectYear'";
				$datetext = "เดือน ".$slbxSelectMonth." ปี ".$slbxSelectYear;
			}
		}
	}
	
} else if($chksh=="shdateTodate"){
	$startdate = $_POST["startdatePDF"];
	$todate = $_POST["todatePDF"];
	if($startdate!=""and$todate!=""){
		if($condate==1){
			$txtcondate="วันที่สั่งงาน ";	
			$conditiondate="date(\"AssignDate\")>='$startdate' and date(\"AssignDate\")<='$todate'";
		}else if($condate==2){
			$txtcondate="วันที่กำหนดส่งงาน ";
			$conditiondate="date(\"DeadlineDate\")>='$startdate' and date(\"DeadlineDate\")<='$todate'";
		}
	}
	$datetext = "ระหว่าง วันที่".$startdate." ถึง".$todate;
} else {
	$txtcondate = "แสดงรายการทั้งหมด";
}

$nowdate = nowDate();

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
 
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
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการสั่งงานตรวจสอบ");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"$txtcondate $datetext");
$pdf->MultiCell(290,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ $userName");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,31);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(2,34);
$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(25,34);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สั่งงาน");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(50,34);
$buss_name=iconv('UTF-8','windows-874',"ลูกค้า");
$pdf->MultiCell(45,8,$buss_name,0,'C',0);

$pdf->SetXY(100,34);
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(152,34);
$buss_name=iconv('UTF-8','windows-874',"เรื่อง");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(205,34);
$buss_name=iconv('UTF-8','windows-874',"ผู้สั่งงาน");
$pdf->MultiCell(30,8,$buss_name,'B','C',0);

$pdf->SetXY(235,34);
$buss_name=iconv('UTF-8','windows-874',"วันที่รับมอบงาน");
$pdf->MultiCell(30,8,$buss_name,'B','C',0);

$pdf->SetXY(260,34);
$buss_name=iconv('UTF-8','windows-874',"หมายเหตุ");
$pdf->MultiCell(30,8,$buss_name,'B','C',0);

$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,8,$buss_name,'B','C',0);


//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',12);
$cline = 43;
$nub = 0;
$count = 0;
	if($conditiondate!=""){
		$sql_main = pg_query("select * from assign_work_detail where \"WorkStatus\" = '1' and $conditiondate order by \"DoerStamp\" DESC");
	}else {
		$sql_main = pg_query("select * from assign_work_detail where \"WorkStatus\" = '1' order by \"DoerStamp\" DESC");
	}
	$numrow_main = pg_num_rows($sql_main);
	
	while($res=pg_fetch_array($sql_main)){
	$nub += 1;
	$count +=1;
		$AssignNo = $res["AssignNo"];
		$AssignDate = substr($res["AssignDate"],0,10);
		$Institution = $res["Institution"];
		$str = substr($res["Subject"],1,count($res["Subject"])-2);
		$Subject = explode(",",$str);
		$Place = $res["Place"];
		$CusID = $res["CusID"];
		$DebtorID = $res["DebtorID"];
		$DebtorName = $res["DebtorName"];
		$PhoneNo = $res["PhoneNo"];
		$DeadlineDate = substr($res["DeadlineDate"],0,10);
		$AssignName = $res["AssignName"];
		$Note = $res["Note"];
		
		//หาชื่อเรื่อง
		for($i=0;$i<sizeof($Subject);$i++){
			if($Subject[$i]==1){
				$subname = "รับเช็ค";
			} else if($Subject[$i]==2){
				$subname = "เอกสารรับกลับ";
			}else if($Subject[$i]==3){
				$subname = "ตรวจรับ/นับสินค้าบริการ";
			}else {
				$subname = "ไม่ระบุเรื่อง";
			}
	
			if($i==0){
				$allSubname = $subname;
			}else{
				$allSubname = $allSubname." , ".$subname;
			}
		}	
		//ชื่อผู้ทำรายการ
		$qry_Cusname = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID'");
		$CusName=pg_fetch_result($qry_Cusname,0);
		
if($nub >= 19){
    $nub = 0;
    $cline = 43;
    $pdf->AddPage();
        
    $pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP)รายงานการสั่งงานตรวจสอบ-วางบิลเก็บช็ค");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"$txtcondate $datetext");
$pdf->MultiCell(290,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ $userName");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,31);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(2,34);
$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(25,34);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สั่งงาน");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(50,34);
$buss_name=iconv('UTF-8','windows-874',"ลูกค้า");
$pdf->MultiCell(45,8,$buss_name,0,'C',0);

$pdf->SetXY(100,34);
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(152,34);
$buss_name=iconv('UTF-8','windows-874',"เรื่อง");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(205,34);
$buss_name=iconv('UTF-8','windows-874',"ผู้สั่งงาน");
$pdf->MultiCell(30,8,$buss_name,'B','C',0);

$pdf->SetXY(235,34);
$buss_name=iconv('UTF-8','windows-874',"วันที่รับมอบงาน");
$pdf->MultiCell(30,8,$buss_name,'B','C',0);

$pdf->SetXY(260,34);
$buss_name=iconv('UTF-8','windows-874',"หมายเหตุ");
$pdf->MultiCell(30,8,$buss_name,'B','C',0);

$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,8,$buss_name,'B','C',0);
	}
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(2,$cline);
$buss_name=iconv('UTF-8','windows-874',$count);
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline);
$buss_name=iconv('UTF-8','windows-874',$AssignNo);
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(53,$cline);
$buss_name=iconv('UTF-8','windows-874',$CusName);
$pdf->MultiCell(50,8,$buss_name,0,'L',0);

$pdf->SetXY(97,$cline);
$buss_name=iconv('UTF-8','windows-874',$DebtorName);
$pdf->MultiCell(50,8,$buss_name,0,'L',0);

$pdf->SetXY(127,$cline);
$buss_name=iconv('UTF-8','windows-874',$allSubname);
$pdf->MultiCell(80,8,$buss_name,0,'C',0);

$pdf->SetXY(205,$cline);
$buss_name=iconv('UTF-8','windows-874',$AssignName);
$pdf->MultiCell(30,8,$buss_name,'B','C',0);

$pdf->SetXY(235,$cline);
$buss_name=iconv('UTF-8','windows-874',$AssignDate);
$pdf->MultiCell(30,8,$buss_name,'B','C',0);

$pdf->SetXY(260,$cline);
$buss_name=iconv('UTF-8','windows-874',$Note);
$pdf->MultiCell(30,8,$buss_name,'B','C',0);

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,8,$buss_name,'B','C',0);

$cline+=7;
	}//endwhile
$cline+=2;
$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(2,$cline);
$buss_name=iconv('UTF-8','windows-874',"รายการทั้งหมด");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline);
$buss_name=iconv('UTF-8','windows-874',$count);
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(35,$cline);
$buss_name=iconv('UTF-8','windows-874',"รายการ");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$cline+=2;
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,8,$buss_name,'B','C',0);
$cline+=2;
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,8,$buss_name,'B','C',0);
$pdf->Output();
?>