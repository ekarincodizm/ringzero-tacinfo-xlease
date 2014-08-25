<?php
include("../../config/config.php");
include("../function/nameMonth.php");

$option = pg_escape_string($_GET['option']);
$acctype = pg_escape_string($_GET['acctype']);

if($option==1){//เมื่อเลือก วันที่นำเงินเข้าธนาคาร
	$datepicker = pg_escape_string($_GET['datepicker']);
	$condition = "AND date(\"bankRevStamp\")='$datepicker' ";
	$txthead="$datepicker";
}else if($option==2){//เมื่อเลือก เดือน-ปี ที่นำเงินเข้าธนาคาร
	$yy = pg_escape_string($_GET["yy"]);
	$mm = pg_escape_string($_GET["mm"]);
	$month=nameMonthTH($mm);
	$condition = "AND EXTRACT(MONTH FROM \"bankRevStamp\") = '$mm' AND EXTRACT(YEAR FROM \"bankRevStamp\") = '$yy' ";
	$txthead="$month $yy";
}else if($option==3){//เมื่อเลือก ปี ที่นำเงินเข้าธนาคาร
	$yy = pg_escape_string($_GET["yy"]);
	$condition = "AND  EXTRACT(YEAR FROM \"bankRevStamp\") = '$yy' ";
	$txthead="$yy";
}

$acctypeloop = explode("@",$acctype);
$bankname="";
for($loop = 0;$loop<sizeof($acctypeloop);$loop++){
	if($acctypeloop[$loop] != "" ){
		$qry_acc = pg_query("select * from \"BankInt\" where \"isTranPay\" = 1 and \"BID\" = '$acctypeloop[$loop]'");
		while($re_acc = pg_fetch_array($qry_acc)){
			$BAccount2 = $re_acc['BAccount'];
			$BName2 = $re_acc['BName'];
			$bankname="$BAccount2-$BName2";
		}

		if(sizeof($acctypeloop)==1){ //กรณีมีแค่ 1 ธนาคารที่เลือก
			$txtbank=$bankname;
		}else{
			if($loop==sizeof($acctypeloop)-1){
				$txtbank=$txtbank.$bankname;
			}else{
				$txtbank=$txtbank.$bankname.", ";
			}
		}
	}
}


$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
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
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานเงินโอน");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,25);
$buss_name1=iconv('UTF-8','windows-874',"วันที่นำเงินเข้าธนาคาร : $txthead");
$pdf->MultiCell(65,4,$buss_name1,0,'L',0);

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(70,25);
$buss_name2=iconv('UTF-8','windows-874',"บัญชี : ");
$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(82,25);
$buss_name3=iconv('UTF-8','windows-874',"$txtbank");
$pdf->MultiCell(290,4,$buss_name3,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,20);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"รหัสเงินโอน");
$pdf->MultiCell(20,8,$buss_name,0,'C',0);

$pdf->SetXY(24,32);
$buss_name=iconv('UTF-8','windows-874',"ประเภท");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);
	$pdf->SetXY(24,36);
	$buss_name=iconv('UTF-8','windows-874',"การนำเข้า");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(38,32);
$buss_name=iconv('UTF-8','windows-874',"สถานะการอนุมัติ");
$pdf->MultiCell(35,8,$buss_name,0,'C',0);

$pdf->SetXY(67,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(95,32);
$buss_name=iconv('UTF-8','windows-874',"สาขา");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);
	$pdf->SetXY(95,36);
	$buss_name=iconv('UTF-8','windows-874',"ที่โอน");
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(103,32);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่นำเงิน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	$pdf->SetXY(103,36);
	$buss_name=iconv('UTF-8','windows-874',"เข้าธนาคาร");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
$pdf->SetXY(125,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(20,8,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(143,32);
$buss_name=iconv('UTF-8','windows-874',"ผู้ตรวจสอบด้านบัญชี");
$pdf->MultiCell(40,8,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(178,32);
$buss_name=iconv('UTF-8','windows-874',"ผลตรวจ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);
	$pdf->SetXY(178,36);
	$buss_name=iconv('UTF-8','windows-874',"ฝ่ายบัญชี");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(192,32);
$buss_name=iconv('UTF-8','windows-874',"ผู้ตรวจสอบด้านการเงิน");
$pdf->MultiCell(40,8,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(227,32);
$buss_name=iconv('UTF-8','windows-874',"ผลตรวจ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);
	$pdf->SetXY(227,36);
	$buss_name=iconv('UTF-8','windows-874',"ฝ่ายการเงิน");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);
	
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(240,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(270,32);
$buss_name=iconv('UTF-8','windows-874',"รหัสเช็ค");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(5,38);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);

//=========================// จบ header ของหน้าแรก


$nub = 1;
$cline = 42;

for($loop = 0;$loop<sizeof($acctypeloop);$loop++){
	
	if($acctypeloop[$loop] != "" ){

		$qry_acc = pg_query("select * from \"BankInt\" where \"isTranPay\" = 1 and \"BID\" = '$acctypeloop[$loop]'");
		if($re_acc = pg_fetch_array($qry_acc)){
			$BAccount = $re_acc['BAccount'];
			$BName = $re_acc['BName'];
			$bankname2="$BAccount-$BName";
		}
		
		//แสดงกรุ๊ปธนาคาร
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$bankname2");
		$pdf->MultiCell(295,5,$buss_name,0,'L',0);
		
		$cline += 5;
		$nub+=1;
		
		if($nub == 25){
			$nub = 1;
			$cline = 42;
			$pdf->AddPage();
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',15);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานเงินโอน");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,25);
			$buss_name1=iconv('UTF-8','windows-874',"วันที่นำเงินเข้าธนาคาร : $txthead");
			$pdf->MultiCell(65,4,$buss_name1,0,'L',0);


			$pdf->SetXY(70,25);
			$buss_name2=iconv('UTF-8','windows-874',"บัญชี : ");
			$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

			$pdf->SetFont('AngsanaNew','B',13);
			$pdf->SetXY(80,25);
			$buss_name3=iconv('UTF-8','windows-874',"$txtbank");
			$pdf->MultiCell(290,4,$buss_name3,0,'L',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,20);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"รหัสเงินโอน");
			$pdf->MultiCell(20,8,$buss_name,0,'C',0);

			$pdf->SetXY(24,32);
			$buss_name=iconv('UTF-8','windows-874',"ประเภท");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);
				$pdf->SetXY(24,36);
				$buss_name=iconv('UTF-8','windows-874',"การนำเข้า");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(38,32);
			$buss_name=iconv('UTF-8','windows-874',"สถานะการอนุมัติ");
			$pdf->MultiCell(35,8,$buss_name,0,'C',0);

			$pdf->SetXY(67,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
			$pdf->MultiCell(30,8,$buss_name,0,'C',0);

			$pdf->SetXY(95,32);
			$buss_name=iconv('UTF-8','windows-874',"สาขา");
			$pdf->MultiCell(10,4,$buss_name,0,'C',0);
				$pdf->SetXY(95,36);
				$buss_name=iconv('UTF-8','windows-874',"ที่โอน");
				$pdf->MultiCell(10,4,$buss_name,0,'C',0);

			$pdf->SetXY(103,32);
			$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่นำเงิน");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
				$pdf->SetXY(103,36);
				$buss_name=iconv('UTF-8','windows-874',"เข้าธนาคาร");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);
				
			$pdf->SetXY(125,32);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
			$pdf->MultiCell(20,8,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(143,32);
			$buss_name=iconv('UTF-8','windows-874',"ผู้ตรวจสอบด้านบัญชี");
			$pdf->MultiCell(40,8,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(178,32);
			$buss_name=iconv('UTF-8','windows-874',"ผลตรวจ");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);
				$pdf->SetXY(178,36);
				$buss_name=iconv('UTF-8','windows-874',"ฝ่ายบัญชี");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(192,32);
			$buss_name=iconv('UTF-8','windows-874',"ผู้ตรวจสอบด้านการเงิน");
			$pdf->MultiCell(40,8,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(227,32);
			$buss_name=iconv('UTF-8','windows-874',"ผลตรวจ");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);
				$pdf->SetXY(227,36);
				$buss_name=iconv('UTF-8','windows-874',"ฝ่ายการเงิน");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);
				
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(240,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,8,$buss_name,0,'C',0);

			$pdf->SetXY(270,32);
			$buss_name=iconv('UTF-8','windows-874',"รหัสเช็ค");
			$pdf->MultiCell(25,8,$buss_name,0,'C',0);

			$pdf->SetXY(5,38);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		
		//ขึ้นบรรทัดใหม่
		$query=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"bankRevAccID\" = '$acctypeloop[$loop]' $condition ORDER BY \"revTranID\" ASC");
		$nubrows = pg_num_rows($query);
		
		if($nubrows==0){
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"------ไม่พบข้อมูล------");
			$pdf->MultiCell(295,4,$buss_name,0,'L',0);
			
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
			
			$cline += 5;
			$nub+=1;
		}else{
			while($resvc=pg_fetch_array($query)){
				$revTranID = $resvc['revTranID'];
				$cnID = $resvc['cnID'];
				$revTranStatus = $resvc['revTranStatus'];
				$appvXStatus = $resvc['appvXStatus'];
				$appvYStatus = $resvc['appvYStatus'];
				$txtstatus=$resvc['namestatus'];
				$BAccount = $resvc['BAccount'];
				$bankRevBranch = trim($resvc['bankRevBranch']);
				$bankRevStamp = trim($resvc['bankRevStamp']);
				$bankRevAmt = trim($resvc['bankRevAmt']);
				$doerStamp = $resvc['doerStamp'];				
				$fullnameX = $resvc['fullnameX'];
				$fullnameY = $resvc['fullnameY'];
				$contractID = $resvc['contractID']; // เลขที่สัญญา
				$revChqID = $resvc['revChqID']; // รหัสเช็ค
				
				if($fullnameX == ""){ $fullnameX = "-"; }
				if($fullnameY == ""){ $fullnameY = "-"; }
				
				if($appvXStatus==""){
					$appvXStatus=9;
				}else{
					$appvXStatus=$appvXStatus;
				}
				
				if($appvXStatus==9){
					$txtx="รออนุมัติ";
				}else if($appvXStatus==0){
					$txtx="ไม่อนุมัติ";
				}else if($appvXStatus==1){
					$txtx="อนุมัติ";
				}
				
				$appvYStatus = $resvc['appvYStatus'];
				if($appvYStatus==""){
					$appvYStatus=9;
				}else{
					$appvYStatus=$appvYStatus;
				}
				if($appvYStatus=="9"){
					$txty="รออนุมัติ";
				}else if($appvYStatus==0){
					$txty="ไม่อนุมัติ";
				}else if($appvYStatus==1){
					$txty="อนุมัติ";
				}
				$tranActionID = $resvc['tranActionID'];
				
				if($nub == 25){
					$nub = 1;
					$cline = 42;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานเงินโอน");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"วันที่นำเงินเข้าธนาคาร : $txthead");
					$pdf->MultiCell(65,4,$buss_name1,0,'L',0);


					$pdf->SetXY(70,25);
					$buss_name2=iconv('UTF-8','windows-874',"บัญชี : ");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',13);
					$pdf->SetXY(80,25);
					$buss_name3=iconv('UTF-8','windows-874',"$txtbank");
					$pdf->MultiCell(290,4,$buss_name3,0,'L',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,20);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"รหัสเงินโอน");
					$pdf->MultiCell(20,8,$buss_name,0,'C',0);

					$pdf->SetXY(24,32);
					$buss_name=iconv('UTF-8','windows-874',"ประเภท");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);
						$pdf->SetXY(24,36);
						$buss_name=iconv('UTF-8','windows-874',"การนำเข้า");
						$pdf->MultiCell(15,4,$buss_name,0,'C',0);

					$pdf->SetXY(38,32);
					$buss_name=iconv('UTF-8','windows-874',"สถานะการอนุมัติ");
					$pdf->MultiCell(35,8,$buss_name,0,'C',0);

					$pdf->SetXY(67,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
					$pdf->MultiCell(30,8,$buss_name,0,'C',0);

					$pdf->SetXY(95,32);
					$buss_name=iconv('UTF-8','windows-874',"สาขา");
					$pdf->MultiCell(10,4,$buss_name,0,'C',0);
						$pdf->SetXY(95,36);
						$buss_name=iconv('UTF-8','windows-874',"ที่โอน");
						$pdf->MultiCell(10,4,$buss_name,0,'C',0);

					$pdf->SetXY(103,32);
					$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่นำเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);
						$pdf->SetXY(103,36);
						$buss_name=iconv('UTF-8','windows-874',"เข้าธนาคาร");
						$pdf->MultiCell(25,4,$buss_name,0,'C',0);
						
					$pdf->SetXY(125,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(20,8,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(143,32);
					$buss_name=iconv('UTF-8','windows-874',"ผู้ตรวจสอบด้านบัญชี");
					$pdf->MultiCell(40,8,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(178,32);
					$buss_name=iconv('UTF-8','windows-874',"ผลตรวจ");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);
						$pdf->SetXY(178,36);
						$buss_name=iconv('UTF-8','windows-874',"ฝ่ายบัญชี");
						$pdf->MultiCell(15,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(192,32);
					$buss_name=iconv('UTF-8','windows-874',"ผู้ตรวจสอบด้านการเงิน");
					$pdf->MultiCell(40,8,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(227,32);
					$buss_name=iconv('UTF-8','windows-874',"ผลตรวจ");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);
						$pdf->SetXY(227,36);
						$buss_name=iconv('UTF-8','windows-874',"ฝ่ายการเงิน");
						$pdf->MultiCell(15,4,$buss_name,0,'C',0);
						
					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(240,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,8,$buss_name,0,'C',0);

					$pdf->SetXY(270,32);
					$buss_name=iconv('UTF-8','windows-874',"รหัสเช็ค");
					$pdf->MultiCell(25,8,$buss_name,0,'C',0);

					$pdf->SetXY(5,38);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(290,4,$buss_name,B,'C',0);
				}
				
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,$cline);
				$buss_name=iconv('UTF-8','windows-874',"$revTranID");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetXY(24,$cline);
				$buss_name=iconv('UTF-8','windows-874',"$cnID");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);

				$pdf->SetXY(38,$cline);
				$buss_name=iconv('UTF-8','windows-874',"$txtstatus");
				$pdf->MultiCell(35,4,$buss_name,0,'L',0);

				$pdf->SetXY(67,$cline);
				$buss_name=iconv('UTF-8','windows-874',"$BAccount");
				$pdf->MultiCell(30,4,$buss_name,0,'L',0);
				
				$pdf->SetXY(95,$cline);
				$buss_name=iconv('UTF-8','windows-874',"$bankRevBranch");
				$pdf->MultiCell(10,4,$buss_name,0,'C',0);
				
				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(103,$cline);
				$buss_name=iconv('UTF-8','windows-874',"$bankRevStamp");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(125,$cline);
				$buss_name=iconv('UTF-8','windows-874',number_format($bankRevAmt,2));
				$pdf->MultiCell(20,4,$buss_name,0,'R',0);
								
				$pdf->SetXY(143,$cline);
				$buss_name=iconv('UTF-8','windows-874',$fullnameX);
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);
				
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(178,$cline);
				$buss_name=iconv('UTF-8','windows-874',$txtx);
				$pdf->MultiCell(15,4,$buss_name,0,'L',0);
				
				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(192,$cline);
				$buss_name=iconv('UTF-8','windows-874',$fullnameY);
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);
				
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(227,$cline);
				$buss_name=iconv('UTF-8','windows-874',$txty);
				$pdf->MultiCell(15,4,$buss_name,0,'L',0);
				
				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(240,$cline);
				$buss_name=iconv('UTF-8','windows-874',$contractID);
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);
				
				$pdf->SetFont('AngsanaNew','',12);			
				$pdf->SetXY(270,$cline);
				$buss_name=iconv('UTF-8','windows-874',$revChqID);
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);
				
				$cline += 5;
				$nub+=1;
			
				$sumbankRevAmt += $bankRevAmt;
			}
			if($nub == 25){
				$nub = 1;
				$cline = 42;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานเงินโอน");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,25);
				$buss_name1=iconv('UTF-8','windows-874',"วันที่นำเงินเข้าธนาคาร : $txthead");
				$pdf->MultiCell(65,4,$buss_name1,0,'L',0);


				$pdf->SetXY(70,25);
				$buss_name2=iconv('UTF-8','windows-874',"บัญชี : ");
				$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

				$pdf->SetFont('AngsanaNew','B',13);
				$pdf->SetXY(80,25);
				$buss_name3=iconv('UTF-8','windows-874',"$txtbank");
				$pdf->MultiCell(290,4,$buss_name3,0,'L',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,20);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"รหัสเงินโอน");
				$pdf->MultiCell(20,8,$buss_name,0,'C',0);

				$pdf->SetXY(24,32);
				$buss_name=iconv('UTF-8','windows-874',"ประเภท");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);
					$pdf->SetXY(24,36);
					$buss_name=iconv('UTF-8','windows-874',"การนำเข้า");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);

				$pdf->SetXY(38,32);
				$buss_name=iconv('UTF-8','windows-874',"สถานะการอนุมัติ");
				$pdf->MultiCell(35,8,$buss_name,0,'C',0);

				$pdf->SetXY(67,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
				$pdf->MultiCell(30,8,$buss_name,0,'C',0);

				$pdf->SetXY(95,32);
				$buss_name=iconv('UTF-8','windows-874',"สาขา");
				$pdf->MultiCell(10,4,$buss_name,0,'C',0);
					$pdf->SetXY(95,36);
					$buss_name=iconv('UTF-8','windows-874',"ที่โอน");
					$pdf->MultiCell(10,4,$buss_name,0,'C',0);

				$pdf->SetXY(103,32);
				$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่นำเงิน");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);
					$pdf->SetXY(103,36);
					$buss_name=iconv('UTF-8','windows-874',"เข้าธนาคาร");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);
					
				$pdf->SetXY(125,32);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
				$pdf->MultiCell(20,8,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(143,32);
				$buss_name=iconv('UTF-8','windows-874',"ผู้ตรวจสอบด้านบัญชี");
				$pdf->MultiCell(40,8,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(178,32);
				$buss_name=iconv('UTF-8','windows-874',"ผลตรวจ");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);
					$pdf->SetXY(178,36);
					$buss_name=iconv('UTF-8','windows-874',"ฝ่ายบัญชี");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(192,32);
				$buss_name=iconv('UTF-8','windows-874',"ผู้ตรวจสอบด้านการเงิน");
				$pdf->MultiCell(40,8,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(227,32);
				$buss_name=iconv('UTF-8','windows-874',"ผลตรวจ");
				$pdf->MultiCell(15,4,$buss_name,0,'C',0);
					$pdf->SetXY(227,36);
					$buss_name=iconv('UTF-8','windows-874',"ฝ่ายการเงิน");
					$pdf->MultiCell(15,4,$buss_name,0,'C',0);
					
				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(240,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,8,$buss_name,0,'C',0);

				$pdf->SetXY(270,32);
				$buss_name=iconv('UTF-8','windows-874',"รหัสเช็ค");
				$pdf->MultiCell(25,8,$buss_name,0,'C',0);

				$pdf->SetXY(5,38);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(290,4,$buss_name,B,'C',0);
			}
			
			$sumbankall += $sumbankRevAmt;	
			$sumbankRevAmt = number_format($sumbankRevAmt,2);
			
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(95,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รวม");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(120,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$sumbankRevAmt");
			$pdf->MultiCell(25,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);

			unset($sumbankRevAmt);
			
			$cline += 5;
			$nub+=1;
		}
		
	}
}
if($nub == 25){
	$nub = 1;
	$cline = 42;
	$pdf->AddPage();
			
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(290,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานเงินโอน");
	$pdf->MultiCell(290,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,25);
	$buss_name1=iconv('UTF-8','windows-874',"วันที่นำเงินเข้าธนาคาร : $txthead");
	$pdf->MultiCell(65,4,$buss_name1,0,'L',0);


	$pdf->SetXY(70,25);
	$buss_name2=iconv('UTF-8','windows-874',"บัญชี : ");
	$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

	$pdf->SetFont('AngsanaNew','B',13);
	$pdf->SetXY(80,25);
	$buss_name3=iconv('UTF-8','windows-874',"$txtbank");
	$pdf->MultiCell(290,4,$buss_name3,0,'L',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,20);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(290,4,$buss_name,0,'R',0);

	$pdf->SetXY(5,26);
	$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(290,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,32);
	$buss_name=iconv('UTF-8','windows-874',"รหัสเงินโอน");
	$pdf->MultiCell(20,8,$buss_name,0,'C',0);

	$pdf->SetXY(24,32);
	$buss_name=iconv('UTF-8','windows-874',"ประเภท");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		$pdf->SetXY(24,36);
		$buss_name=iconv('UTF-8','windows-874',"การนำเข้า");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(38,32);
	$buss_name=iconv('UTF-8','windows-874',"สถานะการอนุมัติ");
	$pdf->MultiCell(35,8,$buss_name,0,'C',0);

	$pdf->SetXY(67,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
	$pdf->MultiCell(30,8,$buss_name,0,'C',0);

	$pdf->SetXY(95,32);
	$buss_name=iconv('UTF-8','windows-874',"สาขา");
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);
		$pdf->SetXY(95,36);
		$buss_name=iconv('UTF-8','windows-874',"ที่โอน");
		$pdf->MultiCell(10,4,$buss_name,0,'C',0);

	$pdf->SetXY(103,32);
	$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่นำเงิน");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
		$pdf->SetXY(103,36);
		$buss_name=iconv('UTF-8','windows-874',"เข้าธนาคาร");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
		
	$pdf->SetXY(125,32);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
	$pdf->MultiCell(20,8,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(143,32);
	$buss_name=iconv('UTF-8','windows-874',"ผู้ตรวจสอบด้านบัญชี");
	$pdf->MultiCell(40,8,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(178,32);
	$buss_name=iconv('UTF-8','windows-874',"ผลตรวจ");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		$pdf->SetXY(178,36);
		$buss_name=iconv('UTF-8','windows-874',"ฝ่ายบัญชี");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(192,32);
	$buss_name=iconv('UTF-8','windows-874',"ผู้ตรวจสอบด้านการเงิน");
	$pdf->MultiCell(40,8,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(227,32);
	$buss_name=iconv('UTF-8','windows-874',"ผลตรวจ");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		$pdf->SetXY(227,36);
		$buss_name=iconv('UTF-8','windows-874',"ฝ่ายการเงิน");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(240,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(30,8,$buss_name,0,'C',0);

	$pdf->SetXY(270,32);
	$buss_name=iconv('UTF-8','windows-874',"รหัสเช็ค");
	$pdf->MultiCell(25,8,$buss_name,0,'C',0);
	$pdf->SetXY(5,38);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(290,4,$buss_name,B,'C',0);
}
$sumbankall = number_format($sumbankall,2);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(95,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งหมด");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			
$pdf->SetXY(120,$cline);
$buss_name=iconv('UTF-8','windows-874',"$sumbankall");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,B,'C',0);

$pdf->Output();
?>