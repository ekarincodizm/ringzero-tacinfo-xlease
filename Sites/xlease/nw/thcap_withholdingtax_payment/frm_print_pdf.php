<?php
include("../../config/config.php");
$year = pg_escape_string($_GET['year']);
if($year==""){
	$year = date('Y');	
}
$month = pg_escape_string($_GET['month']);
$income_tax = pg_escape_string($_GET['income_tax']);
if($income_tax !=""){
	$income=$income_tax;
	$condition=" AND \"fromChannelRef\"='$income_tax'";
}
else{
	$income="ทั้งหมด";
	$condition="";
}
	
$id_user=$_SESSION["av_iduser"];
$datetime=nowDateTime();
//ผู้พิมพ์
$queryU=pg_query("select \"fullname\" from \"Vfuser\" where id_user = '$id_user'");
$user=pg_fetch_result($queryU,0);

$month1 = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month1[$month];

if($year != "" && $month != ""){
	$showdetaildate = "ประเภท ภงด .".$income."  ประจำเดือน ".$show_month." ปี ".$year;
}else if($year != "" && $month == ""){
	$showdetaildate = "ประเภท ภงด .".$income."  ประจำปี ".$year;
}else{
	$showdetaildate = "แสดงรายการทั้งหมด";
}
// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    
	{
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,15); 
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
$title=iconv('UTF-8','windows-874',"(THCAP) รายงานจ่ายใบภาษีหัก ณ ที่จ่าย");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14); 
$pdf->SetXY(4,21); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์  ".$user);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14); 
$pdf->SetXY(4,27); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$datetime);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,18);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(4,27);
$gmm=iconv('UTF-8','windows-874',$showdetaildate);
$pdf->MultiCell(280,4,$gmm,0,'L',0);

$pdf->SetXY(4,27); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ voucher");
$pdf->MultiCell(20,8,$buss_name,0,'C',0);

$pdf->SetXY(20,32); 
$buss_name=iconv('UTF-8','windows-874',"วันที่มีผล");
$pdf->MultiCell(20,8,$buss_name,0,'C',0);

$pdf->SetXY(30,33); 
$buss_name=iconv('UTF-8','windows-874',"ประเภท");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	$pdf->SetXY(28,36); 
	$buss_name=iconv('UTF-8','windows-874',"ภงด.");
	$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(48,33); 
$buss_name=iconv('UTF-8','windows-874',"ประเภท");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);
	$pdf->SetXY(48,36); 
	$buss_name=iconv('UTF-8','windows-874',"เอกสาร");
	$pdf->MultiCell(15,4,$buss_name,0,'R',0);


$pdf->SetXY(60,33); 
$buss_name=iconv('UTF-8','windows-874',"รหัสอ้างอิงตามประเภท");
$pdf->MultiCell(50,8,$buss_name,0,'C',0);


$pdf->SetXY(106,33); 
$buss_name=iconv('UTF-8','windows-874',"เลขอ้างอิง");
$pdf->MultiCell(33,4,$buss_name,0,'C',0);
	$pdf->SetXY(106,37); 
	$buss_name=iconv('UTF-8','windows-874',"ของรายละเอียด");
	$pdf->MultiCell(33,4,$buss_name,0,'C',0);
	
$pdf->SetXY(126,33); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	$pdf->SetXY(126,37); 
	$buss_name=iconv('UTF-8','windows-874',"ที่จ่ายออก");
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
$pdf->SetXY(154,33); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	$pdf->SetXY(154,37); 
	$buss_name=iconv('UTF-8','windows-874',"ที่จ่ายออก-รับเข้า");
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
			$pdf->SetXY(154,41); 
			$buss_name=iconv('UTF-8','windows-874',"(เฉพาะภาษีมูลค่าเพิ่ม)");
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	

$pdf->SetXY(185,33); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	$pdf->SetXY(185,37); 
	$buss_name=iconv('UTF-8','windows-874',"ที่จ่ายออก-รับเข้า");
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		$pdf->SetXY(185,41); 
		$buss_name=iconv('UTF-8','windows-874',"(ยอดรวมภาษีมูลค่าเพิ่ม)");
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(213,32); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);
	$pdf->SetXY(213,37); 
	$buss_name=iconv('UTF-8','windows-874',"ภาษีหัก ณ ที่จ่าย");
	$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(237,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่อ้างอิง");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	$pdf->SetXY(237,37); 
	$buss_name=iconv('UTF-8','windows-874',"ใบหัก ณ ที่จ่าย");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
$pdf->SetXY(262,33); 
$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,42); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 47;
$i = 1;
$j = 0;  
                        
//ดึงข้อมูล มาแสดง ตามเงื่อนไข ที่ได้ทำการเลือก
	if($year != "" && $month != ""){		
		$qry_in=pg_query("SELECT * FROM \"v_thcap_withholdingtax_payment\"
		WHERE EXTRACT(MONTH FROM \"voucherDate\") = '$month' AND EXTRACT(YEAR FROM \"voucherDate\") = '$year'  $condition");
		$selectMonth = $month;		
	}else if($year != "" && $month == ""){
		$qry_in=pg_query("SELECT * FROM \"v_thcap_withholdingtax_payment\"
		WHERE EXTRACT(YEAR FROM \"voucherDate\") = '$year'  $condition");
		$selectMonth = "not";
	}else{
		$qry_in=pg_query("SELECT * FROM \"v_thcap_withholdingtax_payment\" ");
		$selectMonth = "not";	
	}
$icount=0;
while($res_in=pg_fetch_array($qry_in)){		
   

if($i > 25){ 
    $pdf->AddPage(); 
    $cline = 47; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP) รายงานจ่ายใบภาษีหัก ณ ที่จ่าย");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14); 
$pdf->SetXY(4,21); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์  ".$user);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14); 
$pdf->SetXY(4,27); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$datetime);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,18);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(4,27);
$gmm=iconv('UTF-8','windows-874',$showdetaildate);
$pdf->MultiCell(280,4,$gmm,0,'L',0);

$pdf->SetXY(4,27); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ voucher");
$pdf->MultiCell(20,8,$buss_name,0,'C',0);

$pdf->SetXY(20,32); 
$buss_name=iconv('UTF-8','windows-874',"วันที่มีผล");
$pdf->MultiCell(20,8,$buss_name,0,'C',0);

$pdf->SetXY(30,33); 
$buss_name=iconv('UTF-8','windows-874',"ประเภท");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	$pdf->SetXY(28,36); 
	$buss_name=iconv('UTF-8','windows-874',"ภงด.");
	$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(48,33); 
$buss_name=iconv('UTF-8','windows-874',"ประเภท");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);
	$pdf->SetXY(48,36); 
	$buss_name=iconv('UTF-8','windows-874',"เอกสาร");
	$pdf->MultiCell(15,4,$buss_name,0,'R',0);


$pdf->SetXY(60,33); 
$buss_name=iconv('UTF-8','windows-874',"รหัสอ้างอิงตามประเภท");
$pdf->MultiCell(50,8,$buss_name,0,'C',0);


$pdf->SetXY(106,33); 
$buss_name=iconv('UTF-8','windows-874',"เลขอ้างอิง");
$pdf->MultiCell(33,4,$buss_name,0,'C',0);
	$pdf->SetXY(106,37); 
	$buss_name=iconv('UTF-8','windows-874',"ของรายละเอียด");
	$pdf->MultiCell(33,4,$buss_name,0,'C',0);
	
$pdf->SetXY(126,33); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	$pdf->SetXY(126,37); 
	$buss_name=iconv('UTF-8','windows-874',"ที่จ่ายออก");
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
$pdf->SetXY(154,33); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	$pdf->SetXY(154,37); 
	$buss_name=iconv('UTF-8','windows-874',"ที่จ่ายออก-รับเข้า");
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
			$pdf->SetXY(154,41); 
			$buss_name=iconv('UTF-8','windows-874',"(เฉพาะภาษีมูลค่าเพิ่ม)");
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	

$pdf->SetXY(185,33); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	$pdf->SetXY(185,37); 
	$buss_name=iconv('UTF-8','windows-874',"ที่จ่ายออก-รับเข้า");
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		$pdf->SetXY(185,41); 
		$buss_name=iconv('UTF-8','windows-874',"(ยอดรวมภาษีมูลค่าเพิ่ม)");
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(213,32); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);
	$pdf->SetXY(213,37); 
	$buss_name=iconv('UTF-8','windows-874',"ภาษีหัก ณ ที่จ่าย");
	$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(237,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่อ้างอิง");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	$pdf->SetXY(237,37); 
	$buss_name=iconv('UTF-8','windows-874',"ใบหัก ณ ที่จ่าย");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
$pdf->SetXY(262,33); 
$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,42); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);
	
}


$pdf->SetFont('AngsanaNew','',10);

$icount+=1;
//กรณีที่ เลขที่ voucher เหมือนกัน จะแสดงสี เหมือนกัน
if($icount==1){
	$voucherID_old=$res_in['voucherID'];
	$pdf->SetXY(4,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$res_in['voucherID']);
	$pdf->MultiCell(25,4,$buss_name,0,'L',0);
}
else{
	if($voucherID_old==$res_in['voucherID']){}
	else{
	$voucherID_old=$res_in['voucherID'];
	$pdf->SetXY(4,$cline); 
	$buss_name=iconv('UTF-8','windows-874',$res_in['voucherID']);
	$pdf->MultiCell(25,4,$buss_name,0,'L',0);
	}
}


$pdf->SetXY(20,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['voucherDate']);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(37,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['fromChannelRef']);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(48,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['voucherRefType']);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		
		$RefValue_arr="";
		$name="";
		$res_in['voucherRefValue']=str_replace("\n", " ", $res_in['voucherRefValue']);
		if($res_in['voucherRefValue']==""){$irow=1;$name[1]="";}
		else{
			
			
			$RefValue_arr =explode(" ",$res_in['voucherRefValue']);
			$irow=1;
			$itxt=0;
			while($itxt < count($RefValue_arr)){
				$nostr="";
				if($RefValue_arr[$itxt]==""){}
				else{
					$nostr=strlenth($RefValue_arr[$itxt])+ (strlenth($name[$irow])) ;				
					if(($nostr < 30) and ($nostr!="") ){
					$name[$irow] .=" ".$RefValue_arr[$itxt];
					}
					else{
						$irow++;
						$name[$irow] .=" ".$RefValue_arr[$itxt];
				
					}
				}
				$itxt=$itxt+1;
			}

		}
/*$pdf->SetXY(63,$cline); 
$buss_name=iconv('UTF-8','windows-874',$RefValue_str_1);
if($RefValue_str_2 !=""){	
	$pdf->SetXY(63,$cline+5); 
	$buss_name=iconv('UTF-8','windows-874',$RefValue_str_2);
}*/
$count_c=1;
$cline_t=$cline-5;
$i=$i-1;
$ncount=0;
while($count_c <= $irow){
	if(($name[$count_c]=='')){	
	}
	else{
	$ncount++;
	$cline_t=$cline_t+5;
	$i=$i+1;
	if($i > 25){ 
		$pdf->AddPage(); 
		$cline = 47; 
		$i=1; 
		$cline_t=$cline ;
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(10,10);
		$title=iconv('UTF-8','windows-874',"(THCAP) รายงานจ่ายใบภาษีหัก ณ ที่จ่าย");
		$pdf->MultiCell(280,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14); 
		$pdf->SetXY(4,21); 
		$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์  ".$user);
		$pdf->MultiCell(285,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14); 
		$pdf->SetXY(4,27); 
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$datetime);
		$pdf->MultiCell(285,4,$buss_name,0,'R',0);

		$pdf->SetXY(10,18);
		$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
		$pdf->MultiCell(280,4,$buss_name,0,'C',0);

		$pdf->SetXY(4,27);
		$gmm=iconv('UTF-8','windows-874',$showdetaildate);
		$pdf->MultiCell(280,4,$gmm,0,'L',0);

		$pdf->SetXY(4,27); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(285,4,$buss_name,'B','L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(4,32); 
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ voucher");
		$pdf->MultiCell(20,8,$buss_name,0,'C',0);

		$pdf->SetXY(20,32); 
		$buss_name=iconv('UTF-8','windows-874',"วันที่มีผล");
		$pdf->MultiCell(20,8,$buss_name,0,'C',0);

		$pdf->SetXY(30,33); 
		$buss_name=iconv('UTF-8','windows-874',"ประเภท");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			$pdf->SetXY(28,36); 
			$buss_name=iconv('UTF-8','windows-874',"ภงด.");
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);

		$pdf->SetXY(48,33); 
		$buss_name=iconv('UTF-8','windows-874',"ประเภท");
		$pdf->MultiCell(15,4,$buss_name,0,'R',0);
			$pdf->SetXY(48,36); 
			$buss_name=iconv('UTF-8','windows-874',"เอกสาร");
			$pdf->MultiCell(15,4,$buss_name,0,'R',0);


		$pdf->SetXY(60,33); 
		$buss_name=iconv('UTF-8','windows-874',"รหัสอ้างอิงตามประเภท");
		$pdf->MultiCell(50,8,$buss_name,0,'C',0);


		$pdf->SetXY(106,33); 
		$buss_name=iconv('UTF-8','windows-874',"เลขอ้างอิง");
		$pdf->MultiCell(33,4,$buss_name,0,'C',0);
			$pdf->SetXY(106,37); 
			$buss_name=iconv('UTF-8','windows-874',"ของรายละเอียด");
			$pdf->MultiCell(33,4,$buss_name,0,'C',0);
	
		$pdf->SetXY(126,33); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
			$pdf->SetXY(126,37); 
			$buss_name=iconv('UTF-8','windows-874',"ที่จ่ายออก");
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
		$pdf->SetXY(154,33); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
			$pdf->SetXY(154,37); 
			$buss_name=iconv('UTF-8','windows-874',"ที่จ่ายออก-รับเข้า");
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);
					$pdf->SetXY(154,41); 
					$buss_name=iconv('UTF-8','windows-874',"(เฉพาะภาษีมูลค่าเพิ่ม)");
					$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	

		$pdf->SetXY(185,33); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
			$pdf->SetXY(185,37); 
			$buss_name=iconv('UTF-8','windows-874',"ที่จ่ายออก-รับเข้า");
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);
				$pdf->SetXY(185,41); 
				$buss_name=iconv('UTF-8','windows-874',"(ยอดรวมภาษีมูลค่าเพิ่ม)");
				$pdf->MultiCell(30,4,$buss_name,0,'R',0);

		$pdf->SetXY(213,32); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);
			$pdf->SetXY(213,37); 
			$buss_name=iconv('UTF-8','windows-874',"ภาษีหัก ณ ที่จ่าย");
			$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetXY(237,32); 
		$buss_name=iconv('UTF-8','windows-874',"เลขที่อ้างอิง");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			$pdf->SetXY(237,37); 
			$buss_name=iconv('UTF-8','windows-874',"ใบหัก ณ ที่จ่าย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
		$pdf->SetXY(262,33); 
		$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ");
		$pdf->MultiCell(30,8,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(4,42); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(285,4,$buss_name,'B','L',0);
		$pdf->SetFont('AngsanaNew','',10);
	}
	$pdf->SetXY(63,$cline_t); 
	$buss_name=iconv('UTF-8','windows-874',$name[$count_c]);
	$pdf->MultiCell(48,4,$buss_name,0,'L',0);
	}
	$count_c++;
}



$pdf->SetXY(113,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['voucherThisDetailsRef']);//
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(126,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_in['netAmt'].$i,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(154,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_in['vatAmt'],2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(185,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_in['sumAmt'],2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(213,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_in['whtAmt'],2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(237,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['whtRef']);
$pdf->MultiCell(25,4,$buss_name,0,'C',0); 

$pdf->SetXY(259,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['doerFull']);
$pdf->MultiCell(30,4,$buss_name,0,'R',0);



// -----------
if($ncount==0){
	$cline_t=$cline+5;
	$i=$i+1;
}
else{
$cline=$cline_t;
}
$cline+=5;

/*if($i > 25){ 
		$pdf->AddPage(); 
		$cline = 47; 
		$i=1; 
}*/
//else{$i+=1; }
$i+=1;

$netAmt = $netAmt+$res_in['netAmt'];
$vatAmt = $vatAmt+$res_in['vatAmt'];  
$sumAmt = $sumAmt+$res_in['sumAmt'];
$whtAmt = $whtAmt+$res_in['whtAmt'];  
}  

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$cline += 7;

$pdf->SetFont('AngsanaNew','B',14);
$cline -= 2;
$pdf->SetXY(114,$cline); 
$buss_name=iconv('UTF-8','windows-874','รวม');
$pdf->MultiCell(30,4,$buss_name,0,'C',0);


$pdf->SetXY(126,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($netAmt,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(154,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($vatAmt,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(185,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sumAmt,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(215,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($whtAmt,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$cline += 3;
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->Output();

function strlenth($string)
{
$array = getMBStrSplit($string);
	$count = 0;
	
	foreach($array as $value)
	{
		$ascii = ord(iconv("UTF-8", "TIS-620", $value ));
		
		if( !( $ascii == 209 ||  ($ascii >= 212 && $ascii <= 218 ) || ($ascii >= 231 && $ascii <= 238 )) )
		{
			$count += 1;
		}
	}
	return $count;
}
function getMBStrSplit($string, $split_length = 1){
	mb_internal_encoding('UTF-8');
	mb_regex_encoding('UTF-8'); 
	
	$split_length = ($split_length <= 0) ? 1 : $split_length;
	$mb_strlen = mb_strlen($string, 'utf-8');
	$array = array();
	$i = 0; 
	
	while($i < $mb_strlen)
	{
		$array[] = mb_substr($string, $i, $split_length);
		$i = $i+$split_length;
	}
	
	return $array;
}
?>