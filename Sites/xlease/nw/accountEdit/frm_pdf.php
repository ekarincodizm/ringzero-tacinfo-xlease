<?php
include("../../config/config.php");
include("../../core/core_functions.php");
include("../function/nameMonth.php");

$nowdate = nowDateTime();
$id_user=$_SESSION["av_iduser"];
$queryU=pg_query("select \"fullname\" from \"Vfuser\" where id_user = '$id_user'");
$user=pg_fetch_result($queryU,0);//ผู้พิมพ์

$select_date=pg_escape_string($_POST["date1"]);//เลือกวัน
$select_find=pg_escape_string($_POST["find"]);//เลือกการค้นหา
$datepicker=pg_escape_string($_POST["datepicker"]);//ตามวันที่
$month=pg_escape_string($_POST["month"]);//ตามเดือน
$year=pg_escape_string($_POST["year"]);//ตามปี
$datefrom=pg_escape_string($_POST["datefrom"]);
$dateto=pg_escape_string($_POST["dateto"]);//ตามปี
$id_s=pg_escape_string($_POST["id"]);//กรอกข้อมูล
$mfrom_s=pg_escape_string($_POST["mfrom"]);//จำนวนเงินจาก
$mto_s=pg_escape_string($_POST["mto"]);//จำนวนเงินถึง
$selectfind_s=pg_escape_string($_POST["selectfind"]);//ประเภทสมุดรายวันทั่วไป
$by_year=pg_escape_string($_POST["by_year"]);
$cancel=pg_escape_string($_POST["cancel"]);
$selecttype=pg_escape_string($_POST["selecttype"]);

$strcodition="ค้นหาตาม ::";
$condition="";
if($cancel=="off"){
	$condition="and (\"abh_correcting_entries_abh_autoid\"  IS NULL  AND \"abh_is_correcting_entries\" <> '1') ";
}
//เงื่อนไขการค้นหา
if($select_find=='0'){	//เลือกทั้งหมด
	$strcodition.=" ทุกเงื่อนไข";
	$find_s="select abh_autoid from account.\"all_accBookHead\"";
}
else if($select_find=='1'){ //เลขที่
	$strcodition.=" เลขที่  $id_s";
	$find_s="select abh_autoid from account.\"all_accBookHead\"
			where abh_id like '%$id_s%'";
}
else if($select_find=='2'){//ประเภทสมุดรายวันทั่วไป
	if($selectfind_s=='all'){
	$strcodition.=" ทุกประเภทสมุดรายวันทั่วไป";
	$find_s="select abh_autoid from account.\"all_accBookHead\"";}
	else{
	$strcodition.=" ประเภทสมุดรายวันทั่วไป  :$selectfind_s ";
	$find_s="select abh_autoid from account.\"all_accBookHead\"
			where \"GJ_typeID\" like '%$selectfind_s%'";
	}
}
else if($select_find=='3'){//คำอธิบาย
	$strcodition.=" คำอธิบาย  :$id_s";
	$find_s="select abh_autoid from account.\"all_accBookHead\"
			where \"abh_detail\" like '%$id_s%'";
}
else if($select_find=='4'){//จำนวนเงิน
	if($mfrom_s!=''){$chkmfrom=number_format($mfrom_s ,2);}
	if($mto_s!=''){$chkmto=number_format($mto_s ,2);}
	
	$strcodition.=" จำนวนเงิน  : ตั้งแต่  $chkmfrom  ถึง  $chkmto บาท";
	$find_s="select distinct a.\"abd_autoidabh\"
	from account.\"all_accBookDetail\" a
	where (select sum(b.\"abd_amount\") from account.\"all_accBookDetail\" b where b.\"abd_autoidabh\" = a.\"abd_autoidabh\" and b.\"abd_bookType\" = '1') between '$mfrom_s' and $mto_s
	or (select sum(b.\"abd_amount\") from account.\"all_accBookDetail\" b where b.\"abd_autoidabh\" = a.\"abd_autoidabh\" and b.\"abd_bookType\" = '2')  between '$mfrom_s' and $mto_s
	union
	select distinct \"abd_autoidabh\" from account.\"all_accBookDetail\" where \"abd_amount\" between '$mfrom_s' and $mto_s
	";
}
else if($select_find=='5'){//ประเภทใบสำคัญ
	$strcodition.=" ประเภทใบสำคัญ  : $selecttype";
	$find_s="select abh_autoid from account.\"all_accBookHead\"
			where \"abh_type\" = '$selecttype'";
}
//กรอองข้อมูลวันที่

if($select_date=='0'){ //	ทั้งหมด 
	$date_s="";
	$strcodition.=" ทุกช่วงเวลา";
}
else if($select_date=='1'){//ตามวันที่
	$strcodition.=" ประจำวันที่ :$datepicker";
	$date_s=" and abh_stamp::date = '$datepicker' and abh_status = '1'";
}
else if($select_date=='2'){//ตามเดือน
	$strcodition.=" ประจำเดือน: $month  ปี  $year";
	$date_s=" and EXTRACT(MONTH FROM \"abh_stamp\") = '$month' AND EXTRACT(YEAR FROM \"abh_stamp\") = '$year'  and abh_status = '1'";
}
else if($select_date=='3'){//ตามช่วง
	$strcodition.=" ตั้งแต่ วันที่: $datefrom  ถึง $dateto";
	$date_s=" and abh_stamp::date between '$datefrom' and '$dateto'";
	}
else if($select_date=='4'){//ตามปี
	$strcodition.=" ประจำ ปี: $by_year";
	$date_s=" and EXTRACT(YEAR FROM \"abh_stamp\") = '$by_year'  and abh_status = '1' ";
	
	}
$qry_in=pg_query("select \"abh_autoid\",\"abh_id\",\"abh_detail\", \"abh_stamp\"::date as \"abh_stamp_dateOnly\" from account.\"all_accBookHead\"
												where abh_autoid in(  $find_s  										
												) and \"abh_status\" = '1' $date_s	$condition
												order by \"abh_stamp\"::date, \"abh_refid\" ASC");
// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header()
	{
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,10); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(200,4,$buss_name,0,'R',0);
    }
}


$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP) รายงานบัญชีสมุดรายวันทั่วไป");
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',11); 
$pdf->SetXY(3,22); 
$buss_name=iconv('UTF-8','windows-874',$strcodition);
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(5,16); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ ".$user);
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(5,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"$txtheader $show_month $show_yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(3,30); 
$buss_name=iconv('UTF-8','windows-874',"เอกสาร #");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(42,30); 
$buss_name=iconv('UTF-8','windows-874',"รหัสบัญชี/แผนก");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(65,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อบัญชี [รายละเอียด]");
$pdf->MultiCell(90,4,$buss_name,0,'L',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเดบิต");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเครดิต");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;

$abd_amount_dr_all = 0;
$abd_amount_cr_all = 0;

while($res=pg_fetch_array($qry_in))
{
	$abh_autoid = $res["abh_autoid"];
	$abh_id = $res["abh_id"];
	$abh_stamp_dateOnly = $res["abh_stamp_dateOnly"];
	$abh_detail = $res["abh_detail"];
	
	$sp_dtl=str_replace("\n"," ",$abh_detail);

	if($cline > 260)
	{
		$pdf->AddPage(); 
		$cline = 37; 
		$i=1; 

		$pdf->SetFont('AngsanaNew','B',15);
		$pdf->SetXY(10,10);
		$title=iconv('UTF-8','windows-874',"(THCAP) รายงานบัญชีสมุดรายวันทั่วไป");
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',11); 
		$pdf->SetXY(3,22); 
		$buss_name=iconv('UTF-8','windows-874',$strcodition);
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12); 
		$pdf->SetXY(5,16); 
		$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ ".$user);
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12); 
		$pdf->SetXY(5,22); 
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetXY(10,15);
		$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$gmm=iconv('UTF-8','windows-874',"$txtheader $show_month $show_yy");
		$pdf->Text(5,26,$gmm);

		$pdf->SetXY(4,24); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,'B','L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(3,30); 
		$buss_name=iconv('UTF-8','windows-874',"เอกสาร #");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetXY(25,30); 
		$buss_name=iconv('UTF-8','windows-874',"วันที่");
		$pdf->MultiCell(15,4,$buss_name,0,'L',0);

		$pdf->SetXY(42,30); 
		$buss_name=iconv('UTF-8','windows-874',"รหัสบัญชี/แผนก");
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetXY(65,30); 
		$buss_name=iconv('UTF-8','windows-874',"ชื่อบัญชี [รายละเอียด]");
		$pdf->MultiCell(90,4,$buss_name,0,'L',0);

		$pdf->SetXY(160,30); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเดบิต");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);

		$pdf->SetXY(185,30); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเครดิต");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(4,32); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,'B','L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$cline = 37;
		$i = 1;
		$j = 0;
	}

	// -----------

	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(3,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$abh_id);
	$pdf->MultiCell(20,4,$buss_name,0,'L',0);

	$pdf->SetXY(25,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$abh_stamp_dateOnly);
	$pdf->MultiCell(15,4,$buss_name,0,'L',0);

	$pdf->SetXY(42,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$sp_dtl);
	$pdf->MultiCell(160,4,$buss_name,0,'L',0);

	$pdf->SetFont('AngsanaNew','',11);
	$pdf->SetXY(74,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$typePayID);
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(89,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$tpDesc);
	$pdf->MultiCell(38,4,$buss_name,0,'L',0);
	// -----------

	$cline+=5; 
	$i+=1;     

	// วนลูปย่อย
	$qry_sub_qry = pg_query("select * from account.\"all_accBookDetail\" where abd_autoidabh = '$abh_autoid' order by \"abd_bookType\" ");
	$abd_amount_dr = 0;
	$abd_amount_cr = 0;
	
	while($subres = pg_fetch_array($qry_sub_qry))
	{
		$abd_autoid = $subres["abd_autoid"];
		$accBookserial = $subres["accBookserial"];
		$abd_bookType = $subres["abd_bookType"];
		$abd_amount = $subres["abd_amount"];
		
		if($cline > 260)
		{
			$pdf->AddPage(); 
			$cline = 37; 
			$i=1; 

			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(10,10);
			$title=iconv('UTF-8','windows-874',"(THCAP) รายงานบัญชีสมุดรายวันทั่วไป");
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(5,22); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
			$pdf->MultiCell(200,4,$buss_name,0,'R',0);

			$pdf->SetXY(10,15);
			$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$gmm=iconv('UTF-8','windows-874',"$txtheader $show_month $show_yy");
			$pdf->Text(5,26,$gmm);

			$pdf->SetXY(4,24); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(3,30); 
			$buss_name=iconv('UTF-8','windows-874',"เอกสาร #");
			$pdf->MultiCell(20,4,$buss_name,0,'L',0);

			$pdf->SetXY(25,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่");
			$pdf->MultiCell(15,4,$buss_name,0,'L',0);

			$pdf->SetXY(42,30); 
			$buss_name=iconv('UTF-8','windows-874',"รหัสบัญชี/แผนก");
			$pdf->MultiCell(20,4,$buss_name,0,'L',0);

			$pdf->SetXY(65,30); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อบัญชี [รายละเอียด]");
			$pdf->MultiCell(90,4,$buss_name,0,'L',0);

			$pdf->SetXY(160,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเดบิต");
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);

			$pdf->SetXY(185,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเครดิต");
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(4,32); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$cline = 37;
			$i = 1;
			$j = 0;
		}
		
		// หารหัสและชื่อสมุดบัญชี
		$qry_accNane = pg_query("select \"accBookID\", \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$accBookserial' ");
		$accBookID = pg_fetch_result($qry_accNane,0);
		$accBookName = pg_fetch_result($qry_accNane,1);
		
		if($abd_bookType == 1)
		{
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(42,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$accBookID/-");
			$pdf->MultiCell(20,4,$buss_name,0,'L',0);

			$pdf->SetXY(65,$cline); 
			$buss_name=iconv('UTF-8','windows-874',$accBookName);
			$pdf->MultiCell(90,4,$buss_name,0,'L',0);

			$pdf->SetXY(160,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($abd_amount,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			$abd_amount_dr += $abd_amount;
			$abd_amount_dr_all += $abd_amount;
		}
		else
		{
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(44,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$accBookID/-");
			$pdf->MultiCell(18,4,$buss_name,0,'L',0);

			$pdf->SetXY(65,$cline); 
			$buss_name=iconv('UTF-8','windows-874',$accBookName);
			$pdf->MultiCell(90,4,$buss_name,0,'L',0);

			$pdf->SetXY(185,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($abd_amount,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			$abd_amount_cr += $abd_amount;
			$abd_amount_cr_all += $abd_amount;
		}
		
		$cline+=5; 
		$i+=1; 
	}
	
	// รวม
	$pdf->SetXY(3,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
	$pdf->MultiCell(202,4,$buss_name,0,'C',0);
	
	$cline+=5; 
	$i+=1;
	
	$pdf->SetXY(3,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"*** รวม ***");
	$pdf->MultiCell(20,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(160,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($abd_amount_dr,2));
	$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(185,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($abd_amount_cr,2));
	$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	
	$cline+=5; 
	$i+=1;
	
	$pdf->SetXY(3,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"========================================================================================================================================================");
	$pdf->MultiCell(202,4,$buss_name,0,'C',0);
	
	$cline+=5; 
	$i+=1;
}

$pdf->SetFont('AngsanaNew','B',10);

$pdf->SetXY(3,$cline+1.7); 
$buss_name=iconv('UTF-8','windows-874',"*** รวมทั้งสิ้น ***");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

// ผลรวมจำนวนเงิน
$pdf->SetXY(160,$cline+1.7); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($abd_amount_dr_all,2));
$pdf->MultiCell(20,4,$s_P_BEGINX,0,'R',0);

$pdf->SetXY(185,$cline+1.7); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($abd_amount_cr_all,2));
$pdf->MultiCell(20,4,$s_P_BEGINX,0,'R',0);

$pdf->SetXY(3,$cline+5); 
$buss_name=iconv('UTF-8','windows-874',"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
$pdf->MultiCell(202,4,$buss_name,0,'C',0);

$pdf->SetXY(3,$cline+10); 
$buss_name=iconv('UTF-8','windows-874',"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
$pdf->MultiCell(202,4,$buss_name,0,'C',0);

$pdf->Output();
?>