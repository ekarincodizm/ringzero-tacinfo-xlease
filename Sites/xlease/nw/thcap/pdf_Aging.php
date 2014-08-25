<?php
session_start();
include("../../config/config.php");

$datepicker = $_GET['datepicker'];
$contype = $_GET['contype']; //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง
//นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อแสดงประเภทสัญญาที่แสดงบนหัวรายงาน
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypetxtshow == ""){
		$contypetxtshow = $contypechk[$con];
	}else{
		$contypetxtshow = $contypetxtshow.",".$contypechk[$con];
	}	
}
$nowdate = nowDateTime();

$id_user = $_SESSION["av_iduser"];
$sql_check_user = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$id_user'");
$userfullname = pg_fetch_result($sql_check_user,0); // ชื่อเต็มผู้พิมพ์รายงาน

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

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"รายงานอายุหนี้ AGING สิ้นเพียง ณ วันที่ $datepicker ตามช่วงวัน ประเภทสัญญา  $contypetxtshow");
$pdf->MultiCell(290,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,20);
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์รายงาน : $userfullname");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"ที่");
$pdf->MultiCell(8,6,$buss_name,1,'C',0);

$pdf->SetXY(13,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(43,6,$buss_name,1,'C',0);

// $pdf->SetXY(36,32);
// $buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
// $pdf->MultiCell(38,6,$buss_name,1,'C',0);

$pdf->SetXY(56,32);
$buss_name=iconv('UTF-8','windows-874',"ไม่ค้างชำระ");
$pdf->MultiCell(18,6,$buss_name,1,'C',0);

$pdf->SetXY(74,32);
$buss_name=iconv('UTF-8','windows-874',"01-30");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(91,32);
$buss_name=iconv('UTF-8','windows-874',"31-60");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(108,32);
$buss_name=iconv('UTF-8','windows-874',"61-90");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(125,32);
$buss_name=iconv('UTF-8','windows-874',"91-120");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(142,32);
$buss_name=iconv('UTF-8','windows-874',"121-150");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(159,32);
$buss_name=iconv('UTF-8','windows-874',"151-180");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(176,32);
$buss_name=iconv('UTF-8','windows-874',"181-210");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(193,32);
$buss_name=iconv('UTF-8','windows-874',"211-240");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(210,32);
$buss_name=iconv('UTF-8','windows-874',"241-270");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(227,32);
$buss_name=iconv('UTF-8','windows-874',"271-300");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(244,32);
$buss_name=iconv('UTF-8','windows-874',"301-330");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(261,32);
$buss_name=iconv('UTF-8','windows-874',"331-360");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(278,32);
$buss_name=iconv('UTF-8','windows-874',"เกินกว่า 360");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

//=========================// จบ header ของหน้าแรก
//วนตามประเภทสัญญาที่เลือก	
$sump1 = 0;	
$sump2 = 0;
$sump3 = 0;
$sump4 = 0;
$sump5 = 0;
$sump6 = 0;
$sump7 = 0;
$sump8 = 0;
$sump9 = 0;
$sump10 = 0;
$sump11 = 0;
$sump12 = 0;
$sump13 = 0;
$sump14 = 0;

$cline = 38;
$nub = 1;
for($con = 0;$con < sizeof($contypechk) ; $con++){
	//แสดงประเภทอยู่ด้านบนข้อมูล	
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$contypechk[$con]");
	$pdf->MultiCell(290,5,$buss_name,1,'L',0);
	$cline += 5;
	$nub+=1;

	$qrymg=pg_query("SELECT \"contractID\",\"conLoanAmt\" FROM thcap_contract WHERE (\"conClosedDate\" is NULL OR \"conClosedDate\" > '$datepicker') AND \"conDate\" <= '$datepicker' AND \"conType\" = '$contypechk[$con]' ORDER BY \"contractID\" ASC");
	$numcontract=pg_num_rows($qrymg);

	$i=0;
	$sumprinciple0 = 0;
	$sumprinciple = 0;
	$sumprinciple2 = 0;
	$sumprinciple3 = 0;
	$sumprinciple4 = 0;
	$sumprinciple5 = 0;
	$sumprinciple6 = 0;
	$sumprinciple7 = 0;
	$sumprinciple8 = 0;
	$sumprinciple9 = 0;
	$sumprinciple10 = 0;
	$sumprinciple11 = 0;
	$sumprinciple12 = 0;
	$sumprinciple13 = 0;
	$chk=0; //สำหรับตรวจสอบว่าสัญญาประเภทนั้นมีข้อมูลหรือไม่
	while($result=pg_fetch_array($qrymg)){
		list($contractID,$conLoanAmt)=$result;
		
		// ชื่อประเภทสินเชื่อแบบเต็ม
		$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contractID') ");
		list($contype) = pg_fetch_array($qry_chk_con_type);
		
		//หาชื่อลูกหนี้
		$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and  \"CusState\" ='0'");
		list($cusname)=pg_fetch_array($qryname);
								
		//หาเงินต้นคงเหลือของแต่ละสัญญา ด้วย function thcap_getPrinciple
		$qryprinciple=pg_query("SELECT \"thcap_getPrinciple\"('$contractID','$datepicker')");
		list($principle)=pg_fetch_array($qryprinciple);
		
		if($principle > '0'){ //ไม่ต้องนำค่านั้นมาแสดง
			$chk+=1;
			//นำเข้า function เพื่อหาวันที่ค้าง
			if($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN'){
				$qrybackduedate=pg_query("SELECT ('$datepicker'-\"thcap_backDueDate\"('$contractID','$datepicker'))+1");
				list($backduedate)=pg_fetch_array($qrybackduedate);
				$nubdate = $backduedate;
			}else if($contype=='LEASING' OR $contype=='HIRE_PURCHASE'){
				$backduedate=1; //จะไม่เข้าเงื่อนไข $backduedate=="" เนื่องจากของสัญญาประเภทนี้ไม่ได้หาค่า $backduedate
				$qrybackdueday=pg_query("SELECT \"thcap_get_lease_backdays\"('$contractID','$datepicker','1')");
				list($nubdate)=pg_fetch_array($qrybackdueday);
			}
			if($backduedate==""){
				$condition="00";
				$principle0=$principle;
				if($principle0!=""){
					$principle000=number_format($principle0,2);
				}else{
					$principle000="";
				}
			}else{																
				if($nubdate == 0){
					$condition="00";
					$principle0=$principle;
					if($principle0!=""){
						$principle000=number_format($principle0,2);
					}else{
						$principle000="";
					}
				}else if($nubdate>=1 and $nubdate <31){
					$condition="01-30";
					$principle1=$principle;
					if($principle1!=""){
						$principle101=number_format($principle1,2);
					}else{
						$principle101="";
					}
				}else if($nubdate>30 and $nubdate <61){
					$condition="31-60";
					$principle2=$principle;
					if($principle2!=""){
						$principle22=number_format($principle2,2);
					}else{
						$principle22="";
					}
				}else if($nubdate>60 and $nubdate <91){
					$condition="61-90";
					$principle3=$principle;
					if($principle3!=""){
						$principle33=number_format($principle3,2);
					}else{
						$principle33="";
					}
				}else if($nubdate>90 and $nubdate <121){
					$condition="91-120";
					$principle4=$principle;
					if($principle4!=""){
						$principle44=number_format($principle4,2);
					}else{
						$principle44="";
					}
				}else if($nubdate>120 and $nubdate <151){
					$condition="121-150";
					$principle5=$principle;
					if($principle5!=""){
						$principle55=number_format($principle5,2);
					}else{
						$principle55="";
					}
				}else if($nubdate>150 and $nubdate <181){
					$condition="151-180";
					$principle6=$principle;
					if($principle6!=""){
						$principle66=number_format($principle6,2);
					}else{
						$principle66="";
					}
				}else if($nubdate>180 and $nubdate <211){
					$condition="181-210";
					$principle7=$principle;
					if($principle7!=""){
						$principle77=number_format($principle7,2);
					}else{
						$principle77="";
					}
				}else if($nubdate>210 and $nubdate <241){
					$condition="211-240";
					$principle8=$principle;
					if($principle8!=""){
						$principle88=number_format($principle8,2);
					}else{
						$principle88="";
					}
				}else if($nubdate>240 and $nubdate <271){
					$condition="241-270";
					$principle9=$principle;	
					if($principle9!=""){
						$principle99=number_format($principle9,2);
					}else{
						$principle99="";
					}
				}else if($nubdate>270 and $nubdate <301){
					$condition="271-300";
					$principle10=$principle;
					if($principle10!=""){
						$principle100=number_format($principle10,2);
					}else{
						$principle100="";
					}									
				}else if($nubdate>300 and $nubdate <331){
					$condition="301-330";
					$principle11=$principle;
					if($principle11!=""){
						$principle111=number_format($principle11,2);
					}else{
						$principle111="";
					}									
				}else if($nubdate>330 and $nubdate <361){
					$condition="331-360";
					$principle12=$principle;	
					if($principle12!=""){
						$principle122=number_format($principle12,2);
					}else{
						$principle122="";
					}
				}else if($nubdate>360){
					$condition="361";
					$principle13=$principle;
					if($principle13!=""){
						$principle133=number_format($principle13,2);
					}else{
						$principle133="";
					}									
				}
			}
			
			
			//show only new page
			if($nub >= 25){
				$nub = 1;
				$cline = 38;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',16);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"รายงานอายุหนี้ AGING สิ้นเพียง ณ วันที่ $datepicker ตามช่วงวัน ประเภทสัญญา  $contypetxtshow");
				$pdf->MultiCell(290,4,$buss_name,0,'L',0);
				
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,20);
				$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์รายงาน : $userfullname");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"ที่");
				$pdf->MultiCell(8,6,$buss_name,1,'C',0);

				$pdf->SetXY(13,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(43,6,$buss_name,1,'C',0);

				// $pdf->SetXY(36,32);
				// $buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
				// $pdf->MultiCell(38,6,$buss_name,1,'C',0);

				$pdf->SetXY(56,32);
				$buss_name=iconv('UTF-8','windows-874',"ไม่ค้างชำระ");
				$pdf->MultiCell(18,6,$buss_name,1,'C',0);
				
				$pdf->SetXY(74,32);
				$buss_name=iconv('UTF-8','windows-874',"01-30");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(91,32);
				$buss_name=iconv('UTF-8','windows-874',"31-60");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(108,32);
				$buss_name=iconv('UTF-8','windows-874',"61-90");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(125,32);
				$buss_name=iconv('UTF-8','windows-874',"91-120");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(142,32);
				$buss_name=iconv('UTF-8','windows-874',"121-150");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(159,32);
				$buss_name=iconv('UTF-8','windows-874',"151-180");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(176,32);
				$buss_name=iconv('UTF-8','windows-874',"181-210");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(193,32);
				$buss_name=iconv('UTF-8','windows-874',"211-240");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(210,32);
				$buss_name=iconv('UTF-8','windows-874',"241-270");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(227,32);
				$buss_name=iconv('UTF-8','windows-874',"271-300");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(244,32);
				$buss_name=iconv('UTF-8','windows-874',"301-330");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(261,32);
				$buss_name=iconv('UTF-8','windows-874',"331-360");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);

				$pdf->SetXY(278,32);
				$buss_name=iconv('UTF-8','windows-874',"เกินกว่า 360");
				$pdf->MultiCell(17,6,$buss_name,1,'C',0);
			
			}
			
		//show all record
			$i+=1;
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$i");
			$pdf->MultiCell(8,6,$buss_name,1,'C',0);

			$pdf->SetXY(13,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(43,6,$buss_name,1,'C',0);

			// $pdf->SetXY(36,$cline);
			// $buss_name=iconv('UTF-8','windows-874',"$cusname");
			// $pdf->MultiCell(38,6,$buss_name,1,'L',0);
			
			if($condition=="00"){
				$pdf->SetXY(56,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle000);
				$pdf->MultiCell(18,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(56,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(18,6,$buss_name,1,'R',0);
				$principle0=0;
			}

			if($condition=="01-30"){
				$pdf->SetXY(74,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle101);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(74,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle1=0;
			}
			
			if($condition=="31-60"){
				$pdf->SetXY(91,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle22);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(91,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle2=0;
			}
			
			if($condition=="61-90"){
				$pdf->SetXY(108,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle33);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(108,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle3=0;
			}
			if($condition=="91-120"){
				$pdf->SetXY(125,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle44);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(125,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle4=0;
			}
			if($condition=="121-150"){
				$pdf->SetXY(142,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle55);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(142,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle5=0;
			}
			if($condition=="151-180"){
				$pdf->SetXY(159,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle66);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(159,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle6=0;
			}
			if($condition=="181-210"){
				$pdf->SetXY(176,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle77);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(176,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle7=0;
			}
			if($condition=="211-240"){
				$pdf->SetXY(193,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle88);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(193,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle8=0;
			}
			if($condition=="241-270"){
				$pdf->SetXY(210,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle99);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(210,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle9=0;
			}
			if($condition=="271-300"){
				$pdf->SetXY(227,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle100);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(227,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle10=0;
			}
			if($condition=="301-330"){
				$pdf->SetXY(244,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle111);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(244,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle11=0;
			}
			if($condition=="331-360"){
				$pdf->SetXY(261,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle112);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(261,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle12=0;
			}
			if($condition=="361"){
				$pdf->SetXY(278,$cline);
				$buss_name=iconv('UTF-8','windows-874',$principle133);
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
			}else{
				$pdf->SetXY(278,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(17,6,$buss_name,1,'R',0);
				$principle13=0;
			}
			 
			$cline += 6;
			$nub+=1;
			
			$allsum+=$principle;
			$sumprinciple0+=$principle0;
			$sumprinciple+=$principle1;
			$sumprinciple2+=$principle2;
			$sumprinciple3+=$principle3;
			$sumprinciple4+=$principle4;
			$sumprinciple5+=$principle5;
			$sumprinciple6+=$principle6;
			$sumprinciple7+=$principle7;
			$sumprinciple8+=$principle8;
			$sumprinciple9+=$principle9;
			$sumprinciple10+=$principle10;
			$sumprinciple11+=$principle11;
			$sumprinciple12+=$principle12;
			$sumprinciple13+=$principle13;
			
			$sump1+=$principle0;
			$sump2+=$principle1;
			$sump3+=$principle2;
			$sump4+=$principle3;
			$sump5+=$principle4;
			$sump6+=$principle5;
			$sump7+=$principle6;
			$sump8+=$principle7;
			$sump9+=$principle8;
			$sump10+=$principle9;
			$sump11+=$principle10;
			$sump12+=$principle11;
			$sump13+=$principle12;
			$sump14+=$principle13;
			
			unset($condition);
			unset($principle);
			unset($nubdate);
		} //end if
	} //end while 

    if($nub >= 25){
        $nub = 1;
        $cline = 38;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',16);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"รายงานอายุหนี้ AGING สิ้นเพียง ณ วันที่ $datepicker ตามช่วงวัน ประเภทสัญญา  $contypetxtshow");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,20);
		$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์รายงาน : $userfullname");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"ที่");
		$pdf->MultiCell(8,6,$buss_name,1,'C',0);

		$pdf->SetXY(13,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(43,6,$buss_name,1,'C',0);

		// $pdf->SetXY(36,32);
		// $buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
		// $pdf->MultiCell(38,6,$buss_name,1,'C',0);
		
		$pdf->SetXY(56,32);
		$buss_name=iconv('UTF-8','windows-874',"ไม่ค้างชำระ");
		$pdf->MultiCell(18,6,$buss_name,1,'C',0);

		$pdf->SetXY(74,32);
		$buss_name=iconv('UTF-8','windows-874',"01-30");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(91,32);
		$buss_name=iconv('UTF-8','windows-874',"31-60");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(108,32);
		$buss_name=iconv('UTF-8','windows-874',"61-90");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(125,32);
		$buss_name=iconv('UTF-8','windows-874',"91-120");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(142,32);
		$buss_name=iconv('UTF-8','windows-874',"121-150");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(159,32);
		$buss_name=iconv('UTF-8','windows-874',"151-180");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(176,32);
		$buss_name=iconv('UTF-8','windows-874',"181-210");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(193,32);
		$buss_name=iconv('UTF-8','windows-874',"211-240");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(210,32);
		$buss_name=iconv('UTF-8','windows-874',"241-270");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(227,32);
		$buss_name=iconv('UTF-8','windows-874',"271-300");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(244,32);
		$buss_name=iconv('UTF-8','windows-874',"301-330");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(261,32);
		$buss_name=iconv('UTF-8','windows-874',"331-360");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);

		$pdf->SetXY(278,32);
		$buss_name=iconv('UTF-8','windows-874',"เกินกว่า 360");
		$pdf->MultiCell(17,6,$buss_name,1,'C',0);
    }

	if($sumprinciple0!=""){$sumprincipleshow0=number_format($sumprinciple0,2);}else{$sumprincipleshow0="-";}
	if($sumprinciple!=""){$sumprincipleshow=number_format($sumprinciple,2);}else{$sumprincipleshow="-";}
	if($sumprinciple2!=""){$sumprincipleshow2=number_format($sumprinciple2,2);}else{$sumprincipleshow2="-";}
	if($sumprinciple3!=""){$sumprincipleshow3=number_format($sumprinciple3,2);}else{$sumprincipleshow3="-";}
	if($sumprinciple4!=""){$sumprincipleshow4=number_format($sumprinciple4,2);}else{$sumprincipleshow4="-";}
	if($sumprinciple5!=""){$sumprincipleshow5=number_format($sumprinciple5,2);}else{$sumprincipleshow5="-";}
	if($sumprinciple6!=""){$sumprincipleshow6=number_format($sumprinciple6,2);}else{$sumprincipleshow6="-";}
	if($sumprinciple7!=""){$sumprincipleshow7=number_format($sumprinciple7,2);}else{$sumprincipleshow7="-";}
	if($sumprinciple8!=""){$sumprincipleshow8=number_format($sumprinciple8,2);}else{$sumprincipleshow8="-";}
	if($sumprinciple9!=""){$sumprincipleshow9=number_format($sumprinciple9,2);}else{$sumprincipleshow9="-";}
	if($sumprinciple10!=""){$sumprincipleshow10=number_format($sumprinciple10,2);}else{$sumprincipleshow10="-";}
	if($sumprinciple11!=""){$sumprincipleshow11=number_format($sumprinciple11,2);}else{$sumprincipleshow11="-";}
	if($sumprinciple12!=""){$sumprincipleshow12=number_format($sumprinciple12,2);}else{$sumprincipleshow12="-";}
	if($sumprinciple13!=""){$sumprincipleshow13=number_format($sumprinciple13,2);}else{$sumprincipleshow13="-";}

	$pdf->SetFont('AngsanaNew','B',10);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รวมของสัญญาประเภท $contypechk[$con]");
	$pdf->MultiCell(51,6,$buss_name,1,'C',0);

	$pdf->SetXY(56,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow0);
	$pdf->MultiCell(18,6,$buss_name,1,'R',0);

	$pdf->SetXY(74,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);
		

	$pdf->SetXY(91,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow2);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(108,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow3);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(125,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow4);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(142,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow5);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(159,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow6);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(176,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow7);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(193,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow8);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(210,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow9);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(227,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow10);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(244,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow11);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(261,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow12);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);

	$pdf->SetXY(278,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow13);
	$pdf->MultiCell(17,6,$buss_name,1,'R',0);
	
	$sumprincipleshow0 = 0;	
	$sumprincipleshow = 0;
	$sumprincipleshow2 = 0;
	$sumprincipleshow3 = 0;
	$sumprincipleshow4 = 0;
	$sumprincipleshow5 = 0;
	$sumprincipleshow6 = 0;
	$sumprincipleshow7 = 0;
	$sumprincipleshow8 = 0;
	$sumprincipleshow9 = 0;
	$sumprincipleshow10 = 0;
	$sumprincipleshow11 = 0;
	$sumprincipleshow12 = 0;
	$sumprincipleshow13 = 0;

	$cline += 6;
	$nub+=1;	
}
$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(51,6,$buss_name,1,'C',0);

$pdf->SetXY(56,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump1,2));
$pdf->MultiCell(18,6,$buss_name,1,'R',0);

$pdf->SetXY(74,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump2,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);
	

$pdf->SetXY(91,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump3,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(108,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump4,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(125,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump5,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(142,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump6,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(159,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump7,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(176,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump8,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(193,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump9,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(210,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump10,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(227,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump11,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(244,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump12,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(261,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump13,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(278,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sump14,2));
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$cline += 6;
$nub+=1;	

if($allsum>0){ //ป้องกันการ error จากการหารด้วย 0
	$percent0 = number_format($sump1/$allsum*100,2);
	$percent1 = number_format($sump2/$allsum*100,2);
	$percent2 = number_format($sump3/$allsum*100,2);
	$percent3 = number_format($sump4/$allsum*100,2);
	$percent4 = number_format($sump5/$allsum*100,2);
	$percent5 = number_format($sump6/$allsum*100,2);
	$percent6 = number_format($sump7/$allsum*100,2);
	$percent7 = number_format($sump8/$allsum*100,2);
	$percent8 = number_format($sump9/$allsum*100,2);
	$percent9 = number_format($sump10/$allsum*100,2);
	$percent10 = number_format($sump11/$allsum*100,2);
	$percent11 = number_format($sump12/$allsum*100,2);
	$percent12 = number_format($sump13/$allsum*100,2);
	$percent13 = number_format($sump14/$allsum*100,2);
}

if($nub >= 25){
	$nub = 1;
	$cline = 38;
	$pdf->AddPage();
	
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(290,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',16);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"รายงานอายุหนี้ AGING สิ้นเพียง ณ วันที่ $datepicker ตามช่วงวัน ประเภทสัญญา  $contypetxtshow");
	$pdf->MultiCell(290,4,$buss_name,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,20);
	$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์รายงาน : $userfullname");
	$pdf->MultiCell(290,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
	$pdf->MultiCell(290,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,32);
	$buss_name=iconv('UTF-8','windows-874',"ที่");
	$pdf->MultiCell(8,6,$buss_name,1,'C',0);

	$pdf->SetXY(13,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(43,6,$buss_name,1,'C',0);

	// $pdf->SetXY(36,32);
	// $buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
	// $pdf->MultiCell(38,6,$buss_name,1,'C',0);

	$pdf->SetXY(56,32);
	$buss_name=iconv('UTF-8','windows-874',"ไม่ค้างชำระ");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);
	
	$pdf->SetXY(74,32);
	$buss_name=iconv('UTF-8','windows-874',"01-30");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(91,32);
	$buss_name=iconv('UTF-8','windows-874',"31-60");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(108,32);
	$buss_name=iconv('UTF-8','windows-874',"61-90");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(125,32);
	$buss_name=iconv('UTF-8','windows-874',"91-120");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(142,32);
	$buss_name=iconv('UTF-8','windows-874',"121-150");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(159,32);
	$buss_name=iconv('UTF-8','windows-874',"151-180");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(176,32);
	$buss_name=iconv('UTF-8','windows-874',"181-210");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(193,32);
	$buss_name=iconv('UTF-8','windows-874',"211-240");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(210,32);
	$buss_name=iconv('UTF-8','windows-874',"241-270");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(227,32);
	$buss_name=iconv('UTF-8','windows-874',"271-300");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(244,32);
	$buss_name=iconv('UTF-8','windows-874',"301-330");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(261,32);
	$buss_name=iconv('UTF-8','windows-874',"331-360");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(278,32);
	$buss_name=iconv('UTF-8','windows-874',"เกินกว่า 360");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);
}
	
$pdf->SetXY(5,$cline);	
$buss_name=iconv('UTF-8','windows-874',"สัดส่วน");
$pdf->MultiCell(51,6,$buss_name,1,'C',0);

$pdf->SetXY(56,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent0."%");
$pdf->MultiCell(18,6,$buss_name,1,'R',0);

$pdf->SetXY(74,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent1."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);
	

$pdf->SetXY(91,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent2."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(108,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent3."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(125,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent4."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(142,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent5."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(159,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent6."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(176,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent7."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(193,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent8."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(210,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent9."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(227,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent10."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(244,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent11."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(261,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent12."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$pdf->SetXY(278,$cline);
$buss_name=iconv('UTF-8','windows-874',$percent13."%");
$pdf->MultiCell(17,6,$buss_name,1,'R',0);

$cline += 6;
$nub+=1;

if($nub >= 25){
	$nub = 1;
	$cline = 38;
	$pdf->AddPage();
	
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(290,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',16);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"รายงานอายุหนี้ AGING สิ้นเพียง ณ วันที่ $datepicker ตามช่วงวัน ประเภทสัญญา  $contypetxtshow");
	$pdf->MultiCell(290,4,$buss_name,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,20);
	$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์รายงาน : $userfullname");
	$pdf->MultiCell(290,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
	$pdf->MultiCell(290,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,32);
	$buss_name=iconv('UTF-8','windows-874',"ที่");
	$pdf->MultiCell(8,6,$buss_name,1,'C',0);

	$pdf->SetXY(13,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(43,6,$buss_name,1,'C',0);

	// $pdf->SetXY(36,32);
	// $buss_name=iconv('UTF-8','windows-874',"รายชื่อลูกหนี้");
	// $pdf->MultiCell(38,6,$buss_name,1,'C',0);

	$pdf->SetXY(56,32);
	$buss_name=iconv('UTF-8','windows-874',"ไม่ค้างชำระ");
	$pdf->MultiCell(18,6,$buss_name,1,'C',0);
	
	$pdf->SetXY(74,32);
	$buss_name=iconv('UTF-8','windows-874',"01-30");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(91,32);
	$buss_name=iconv('UTF-8','windows-874',"31-60");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(108,32);
	$buss_name=iconv('UTF-8','windows-874',"61-90");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(125,32);
	$buss_name=iconv('UTF-8','windows-874',"91-120");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(142,32);
	$buss_name=iconv('UTF-8','windows-874',"121-150");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(159,32);
	$buss_name=iconv('UTF-8','windows-874',"151-180");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(176,32);
	$buss_name=iconv('UTF-8','windows-874',"181-210");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(193,32);
	$buss_name=iconv('UTF-8','windows-874',"211-240");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(210,32);
	$buss_name=iconv('UTF-8','windows-874',"241-270");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(227,32);
	$buss_name=iconv('UTF-8','windows-874',"271-300");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(244,32);
	$buss_name=iconv('UTF-8','windows-874',"301-330");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(261,32);
	$buss_name=iconv('UTF-8','windows-874',"331-360");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);

	$pdf->SetXY(278,32);
	$buss_name=iconv('UTF-8','windows-874',"เกินกว่า 360");
	$pdf->MultiCell(17,6,$buss_name,1,'C',0);
}

$pdf->SetXY(244,$cline);
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ทั้งสิ้น");
$pdf->MultiCell(17,6,$buss_name,1,'C',0);

$pdf->SetXY(261,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($allsum,2));
$pdf->MultiCell(34,6,$buss_name,1,'R',0);

$pdf->Output();
?>