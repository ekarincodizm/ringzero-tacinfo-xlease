<?php
session_start();
include("../../../config/config.php");

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

//นำค่า array ของประเภทสัญญามาต่อกันเป็นเงื่อนไข เพื่อนำไปค้นหาปีที่ต้องนำมาแสดงในรายงาน
$contypeyear="";
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypechk[$con]!=''){
		if($contypeyear == ""){
			$contypeyear = "\"conType\"='$contypechk[$con]'";
		}else{
			$contypeyear = $contypeyear." OR \"conType\"='$contypechk[$con]'";
		}	
	}
}
if($contypeyear!=""){
	$contypeyear="and ($contypeyear)";
}
$nowdate = nowDateTime();

$id_user = $_SESSION["av_iduser"];
$sql_check_user = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$id_user'");
$userfullname = pg_fetch_result($sql_check_user,0); // ชื่อเต็มผู้พิมพ์รายงาน

//------------------- PDF -------------------//
require('../../../thaipdfclass.php');

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

//หาปีที่เกี่ยวข้องทั้งหมดมาแสดง
$qry_year=pg_query("SELECT distinct(EXTRACT(YEAR FROM \"conDate\")) FROM thcap_contract 
	WHERE (\"conClosedDate\" is NULL OR \"conClosedDate\" > '$datepicker') AND \"conDate\" <= '$datepicker' $contypeyear
	ORDER BY EXTRACT(YEAR FROM \"conDate\")");
while($resyear=pg_fetch_array($qry_year)){
	list($contractyear)=$resyear;
	
	$pdf->AddPage();
	$page = $pdf->PageNo();
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(290,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','B',16);
	$pdf->SetXY(5,16);
	$title=iconv('UTF-8','windows-874',"ลูกหนี้ปี $contractyear");
	$pdf->MultiCell(290,5,$title,0,'C',0);
	
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"รายงานอายุหนี้ AGING สิ้นเพียง ณ วันที่ $datepicker ตามช่วงเดือน ประเภทสัญญา  $contypetxtshow");
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

	$pdf->SetXY(56,32);
	$buss_name=iconv('UTF-8','windows-874',"ไม่ค้างชำระ");
	$pdf->MultiCell(30,6,$buss_name,1,'C',0);$pdf->SetXY(56,32);

	$pdf->SetXY(86,32);
	$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด น้อยกว่า 3 เดือน");
	$pdf->MultiCell(35,6,$buss_name,1,'C',0);

	$pdf->SetXY(121,32);
	$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด 3 เดือน - 6 เดือน");
	$pdf->MultiCell(35,6,$buss_name,1,'C',0);

	$pdf->SetXY(156,32);
	$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด 6 เดือน - 12 เดือน");
	$pdf->MultiCell(40,6,$buss_name,1,'C',0);

	$pdf->SetXY(196,32);
	$buss_name=iconv('UTF-8','windows-874',"เกินกว่า 12 เดือน");
	$pdf->MultiCell(30,6,$buss_name,1,'C',0);

	$pdf->SetXY(226,32);
	$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
	$pdf->MultiCell(35,6,$buss_name,1,'C',0);

	$pdf->SetXY(261,32);
	$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
	$pdf->MultiCell(34,6,$buss_name,1,'C',0);

	//=========================// จบ header ของหน้าแรก
	//วนตามประเภทสัญญาที่เลือก	
	$sump0 = 0;
	$sump1 = 0;	
	$sump2 = 0;
	$sump3 = 0;
	$sump4 = 0;
	$sump5 = 0;
	$sump6 = 0;

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

		$qrymg=pg_query("SELECT \"contractID\",\"conLoanAmt\" FROM thcap_contract WHERE (\"conClosedDate\" is NULL OR \"conClosedDate\" > '$datepicker') AND \"conDate\" <= '$datepicker' AND \"conType\" = '$contypechk[$con]' AND EXTRACT(YEAR FROM \"conDate\")='$contractyear' ORDER BY \"contractID\" ASC");
		$numcontract=pg_num_rows($qrymg);

		$i=0;
		$sumprinciple0 = 0;	
		$sumprinciple1 = 0;
		$sumprinciple2 = 0;
		$sumprinciple3 = 0;
		$sumprinciple4 = 0;
		$sumprinciple5 = 0;
		$sumprinciple6 = 0;
		$chk=0; //สำหรับตรวจสอบว่าสัญญาประเภทนั้นมีข้อมูลหรือไม่
		while($result=pg_fetch_array($qrymg)){
			list($contractID,$conLoanAmt)=$result;
													
			//หาเงินต้นคงเหลือของแต่ละสัญญา ด้วย function thcap_getPrinciple
			$qryprinciple=pg_query("SELECT \"thcap_getPrinciple\"('$contractID','$datepicker')");
			list($principle)=pg_fetch_array($qryprinciple);
			
			if($principle > '0'){ //ไม่ต้องนำค่านั้นมาแสดง
				$chk+=1;
				
				//หาว่าอยู่ระหว่างดำเนินคดีหรือไม่จาก function "thcap_get_all_isSue" ถ้าได้ TRUE แสดงว่า เป็นระหว่างคดี ถ้าได้ FALSE แสดงว่าไม่อยู่
				$qryissue=pg_query("select \"thcap_get_all_isSue\"('$contractID','$datepicker')");
				list($issue)=pg_fetch_array($qryissue);
				if($issue==1){
					$nubmonth='issue';
				}
				//หาว่าปรับโครงสร้างหรือไม่จาก function "thcap_get_all_isRestructure" ถ้าได้ TRUE แสดงว่า เป็นปรับโครงสร้างหนี้ ถ้าได้ FALSE แสดงว่าไม่อยู่
				$qrystructure=pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$datepicker')");
				list($structure)=pg_fetch_array($qrystructure);
				if($structure==1){
					$nubmonth='structure';
				}
				
				if($issue==0 and $structure==0){
					//นำเข้า function เพื่อหาจำนวนเดือนที่ค้าง
					$qrybackduedate=pg_query("SELECT \"thcap_get_all_backmonths\"('$contractID','$datepicker')");
					list($nubmonth)=pg_fetch_array($qrybackduedate);
				}
				
				if($nubmonth=='structure' or ($issue==1 and $structure==1)){ //อยู่ระหว่างปรับโครงสร้างหนี้
					$condition="5";
					$principle5=$principle;
					if($principle5!=""){
						$principle55=number_format($principle5,2);
					}else{
						$principle55="";
					}
				}else if($nubmonth=='issue'){ //อยู่ระหว่างดำเนินคดี 
					$condition="6";
					$principle6=$principle;
					if($principle6!=""){
						$principle66=number_format($principle6,2);
					}else{
						$principle66="";
					}
				}else if($nubmonth == 0){ //ไม่พบวันค้างชำระ
					$condition="0";
					$principle0=$principle;
					if($principle0!=""){
						$principle000=number_format($principle0,2);
					}else{
						$principle000="";
					}
				}else if($nubmonth<3){ //เกินกำหนด น้อยกว่า 3 เดือน
					$condition="1";
					$principle1=$principle;
					if($principle1!=""){
						$principle101=number_format($principle1,2);
					}else{
						$principle101="";
					}
				}else if($nubmonth>=3 and $nubmonth <=6){ //เกินกำหนด 3 เดือน - 6 เดือน
					$condition="2";
					$principle2=$principle;
					if($principle2!=""){
						$principle22=number_format($principle2,2);
					}else{
						$principle22="";
					}
				}else if($nubmonth>6 and $nubmonth <=12){ //เกินกำหนด 6 เดือน - 12 เดือน
					$condition="3";
					$principle3=$principle;
					if($principle3!=""){
						$principle33=number_format($principle3,2);
					}else{
						$principle33="";
					}
				}else if($nubmonth>12){ //เกินกว่า 12 เดือน
					$condition="4";
					$principle4=$principle;
					if($principle4!=""){
						$principle44=number_format($principle4,2);
					}else{
						$principle44="";
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
					$pdf->SetXY(5,16);
					$title=iconv('UTF-8','windows-874',"ลูกหนี้ปี $contractyear");
					$pdf->MultiCell(290,5,$title,0,'C',0);
					
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"รายงานอายุหนี้ AGING สิ้นเพียง ณ วันที่ $datepicker ตามช่วงเดือน ประเภทสัญญา  $contypetxtshow");
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

					$pdf->SetXY(56,32);
					$buss_name=iconv('UTF-8','windows-874',"ไม่ค้างชำระ");
					$pdf->MultiCell(30,6,$buss_name,1,'C',0);$pdf->SetXY(56,32);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด น้อยกว่า 3 เดือน");
					$pdf->MultiCell(35,6,$buss_name,1,'C',0);

					$pdf->SetXY(121,32);
					$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด 3 เดือน - 6 เดือน");
					$pdf->MultiCell(35,6,$buss_name,1,'C',0);

					$pdf->SetXY(156,32);
					$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด 6 เดือน - 12 เดือน");
					$pdf->MultiCell(40,6,$buss_name,1,'C',0);

					$pdf->SetXY(196,32);
					$buss_name=iconv('UTF-8','windows-874',"เกินกว่า 12 เดือน");
					$pdf->MultiCell(30,6,$buss_name,1,'C',0);

					$pdf->SetXY(226,32);
					$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
					$pdf->MultiCell(35,6,$buss_name,1,'C',0);

					$pdf->SetXY(261,32);
					$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
					$pdf->MultiCell(34,6,$buss_name,1,'C',0);
				
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
				
				if($condition=="0"){
					$pdf->SetXY(56,$cline);
					$buss_name=iconv('UTF-8','windows-874',$principle000);
					$pdf->MultiCell(30,6,$buss_name,1,'R',0);
				}else{
					$pdf->SetXY(56,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(30,6,$buss_name,1,'R',0);
					$principle0=0;
				}

				if($condition=="1"){
					$pdf->SetXY(86,$cline);
					$buss_name=iconv('UTF-8','windows-874',$principle101);
					$pdf->MultiCell(35,6,$buss_name,1,'R',0);
				}else{
					$pdf->SetXY(86,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(35,6,$buss_name,1,'R',0);
					$principle1=0;
				}
				
				if($condition=="2"){
					$pdf->SetXY(121,$cline);
					$buss_name=iconv('UTF-8','windows-874',$principle22);
					$pdf->MultiCell(35,6,$buss_name,1,'R',0);
				}else{
					$pdf->SetXY(121,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(35,6,$buss_name,1,'R',0);
					$principle2=0;
				}
				
				if($condition=="3"){
					$pdf->SetXY(156,$cline);
					$buss_name=iconv('UTF-8','windows-874',$principle33);
					$pdf->MultiCell(40,6,$buss_name,1,'R',0);
				}else{
					$pdf->SetXY(156,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(40,6,$buss_name,1,'R',0);
					$principle3=0;
				}
				if($condition=="4"){
					$pdf->SetXY(196,$cline);
					$buss_name=iconv('UTF-8','windows-874',$principle44);
					$pdf->MultiCell(30,6,$buss_name,1,'R',0);
				}else{
					$pdf->SetXY(196,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(30,6,$buss_name,1,'R',0);
					$principle4=0;
				}
				if($condition=="5"){
					$pdf->SetXY(226,$cline);
					$buss_name=iconv('UTF-8','windows-874',$principle55);
					$pdf->MultiCell(35,6,$buss_name,1,'R',0);
				}else{
					$pdf->SetXY(226,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(35,6,$buss_name,1,'R',0);
					$principle5=0;
				}
				if($condition=="6"){
					$pdf->SetXY(261,$cline);
					$buss_name=iconv('UTF-8','windows-874',$principle66);
					$pdf->MultiCell(34,6,$buss_name,1,'R',0);
				}else{
					$pdf->SetXY(261,$cline);
					$buss_name=iconv('UTF-8','windows-874',"");
					$pdf->MultiCell(34,6,$buss_name,1,'R',0);
					$principle6=0;
				}
				 
				$cline += 6;
				$nub+=1;
				
				$allsum+=$principle;
				$sumprinciple0+=$principle0;
				$sumprinciple1+=$principle1;
				$sumprinciple2+=$principle2;
				$sumprinciple3+=$principle3;
				$sumprinciple4+=$principle4;
				$sumprinciple5+=$principle5;
				$sumprinciple6+=$principle6;
				
				$sump0+=$principle0;
				$sump1+=$principle1;
				$sump2+=$principle2;
				$sump3+=$principle3;
				$sump4+=$principle4;
				$sump5+=$principle5;
				$sump6+=$principle6;
				
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
			$pdf->SetXY(5,16);
			$title=iconv('UTF-8','windows-874',"ลูกหนี้ปี $contractyear");
			$pdf->MultiCell(290,5,$title,0,'C',0);
			
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"รายงานอายุหนี้ AGING สิ้นเพียง ณ วันที่ $datepicker ตามช่วงเดือน ประเภทสัญญา  $contypetxtshow");
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

			$pdf->SetXY(56,32);
			$buss_name=iconv('UTF-8','windows-874',"ไม่ค้างชำระ");
			$pdf->MultiCell(30,6,$buss_name,1,'C',0);$pdf->SetXY(56,32);

			$pdf->SetXY(86,32);
			$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด น้อยกว่า 3 เดือน");
			$pdf->MultiCell(35,6,$buss_name,1,'C',0);

			$pdf->SetXY(121,32);
			$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด 3 เดือน - 6 เดือน");
			$pdf->MultiCell(35,6,$buss_name,1,'C',0);

			$pdf->SetXY(156,32);
			$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด 6 เดือน - 12 เดือน");
			$pdf->MultiCell(40,6,$buss_name,1,'C',0);

			$pdf->SetXY(196,32);
			$buss_name=iconv('UTF-8','windows-874',"เกินกว่า 12 เดือน");
			$pdf->MultiCell(30,6,$buss_name,1,'C',0);

			$pdf->SetXY(226,32);
			$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
			$pdf->MultiCell(35,6,$buss_name,1,'C',0);

			$pdf->SetXY(261,32);
			$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
			$pdf->MultiCell(34,6,$buss_name,1,'C',0);
		}

		if($sumprinciple0!=""){$sumprincipleshow0=number_format($sumprinciple0,2);}else{$sumprincipleshow0="-";}
		if($sumprinciple1!=""){$sumprincipleshow1=number_format($sumprinciple1,2);}else{$sumprincipleshow="-";}
		if($sumprinciple2!=""){$sumprincipleshow2=number_format($sumprinciple2,2);}else{$sumprincipleshow2="-";}
		if($sumprinciple3!=""){$sumprincipleshow3=number_format($sumprinciple3,2);}else{$sumprincipleshow3="-";}
		if($sumprinciple4!=""){$sumprincipleshow4=number_format($sumprinciple4,2);}else{$sumprincipleshow4="-";}
		if($sumprinciple5!=""){$sumprincipleshow5=number_format($sumprinciple5,2);}else{$sumprincipleshow5="-";}
		if($sumprinciple6!=""){$sumprincipleshow6=number_format($sumprinciple6,2);}else{$sumprincipleshow6="-";}

		$pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมของสัญญาประเภท $contypechk[$con]");
		$pdf->MultiCell(51,6,$buss_name,1,'C',0);

		$pdf->SetXY(56,$cline);
		$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow0);
		$pdf->MultiCell(30,6,$buss_name,1,'R',0);

		$pdf->SetXY(86,$cline);
		$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow1);
		$pdf->MultiCell(35,6,$buss_name,1,'R',0);
			

		$pdf->SetXY(121,$cline);
		$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow2);
		$pdf->MultiCell(35,6,$buss_name,1,'R',0);

		$pdf->SetXY(156,$cline);
		$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow3);
		$pdf->MultiCell(40,6,$buss_name,1,'R',0);

		$pdf->SetXY(196,$cline);
		$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow4);
		$pdf->MultiCell(30,6,$buss_name,1,'R',0);

		$pdf->SetXY(226,$cline);
		$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow5);
		$pdf->MultiCell(35,6,$buss_name,1,'R',0);

		$pdf->SetXY(261,$cline);
		$buss_name=iconv('UTF-8','windows-874',$sumprincipleshow6);
		$pdf->MultiCell(34,6,$buss_name,1,'R',0);

		
		$sumprincipleshow0 = 0;	
		$sumprincipleshow1 = 0;
		$sumprincipleshow2 = 0;
		$sumprincipleshow3 = 0;
		$sumprincipleshow4 = 0;
		$sumprincipleshow5 = 0;
		$sumprincipleshow6 = 0;

		$cline += 6;
		$nub+=1;	
	}
	$pdf->SetFont('AngsanaNew','B',10);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
	$pdf->MultiCell(51,6,$buss_name,1,'C',0);

	$pdf->SetXY(56,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sump0,2));
	$pdf->MultiCell(30,6,$buss_name,1,'R',0);

	$pdf->SetXY(86,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sump1,2));
	$pdf->MultiCell(35,6,$buss_name,1,'R',0);
		

	$pdf->SetXY(121,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sump2,2));
	$pdf->MultiCell(35,6,$buss_name,1,'R',0);

	$pdf->SetXY(156,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sump3,2));
	$pdf->MultiCell(40,6,$buss_name,1,'R',0);

	$pdf->SetXY(196,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sump4,2));
	$pdf->MultiCell(30,6,$buss_name,1,'R',0);

	$pdf->SetXY(226,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sump5,2));
	$pdf->MultiCell(35,6,$buss_name,1,'R',0);

	$pdf->SetXY(261,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sump6,2));
	$pdf->MultiCell(34,6,$buss_name,1,'R',0);


	$cline += 6;
	$nub+=1;	

	if($allsum>0){ //ป้องกันการ error จากการหารด้วย 0
		$percent0 = number_format($sump0/$allsum*100,2);
		$percent1 = number_format($sump1/$allsum*100,2);
		$percent2 = number_format($sump2/$allsum*100,2);
		$percent3 = number_format($sump3/$allsum*100,2);
		$percent4 = number_format($sump4/$allsum*100,2);
		$percent5 = number_format($sump5/$allsum*100,2);
		$percent6 = number_format($sump6/$allsum*100,2);
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
		$pdf->SetXY(5,16);
		$title=iconv('UTF-8','windows-874',"ลูกหนี้ปี $contractyear");
		$pdf->MultiCell(290,5,$title,0,'C',0);
		
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"รายงานอายุหนี้ AGING สิ้นเพียง ณ วันที่ $datepicker ตามช่วงเดือน ประเภทสัญญา  $contypetxtshow");
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

		$pdf->SetXY(56,32);
		$buss_name=iconv('UTF-8','windows-874',"ไม่ค้างชำระ");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);$pdf->SetXY(56,32);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด น้อยกว่า 3 เดือน");
		$pdf->MultiCell(35,6,$buss_name,1,'C',0);

		$pdf->SetXY(121,32);
		$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด 3 เดือน - 6 เดือน");
		$pdf->MultiCell(35,6,$buss_name,1,'C',0);

		$pdf->SetXY(156,32);
		$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด 6 เดือน - 12 เดือน");
		$pdf->MultiCell(40,6,$buss_name,1,'C',0);

		$pdf->SetXY(196,32);
		$buss_name=iconv('UTF-8','windows-874',"เกินกว่า 12 เดือน");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);

		$pdf->SetXY(226,32);
		$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
		$pdf->MultiCell(35,6,$buss_name,1,'C',0);

		$pdf->SetXY(261,32);
		$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
		$pdf->MultiCell(34,6,$buss_name,1,'C',0);
	}
		
	$pdf->SetXY(5,$cline);	
	$buss_name=iconv('UTF-8','windows-874',"สัดส่วน");
	$pdf->MultiCell(51,6,$buss_name,1,'C',0);

	$pdf->SetXY(56,$cline);
	$buss_name=iconv('UTF-8','windows-874',$percent0."%");
	$pdf->MultiCell(30,6,$buss_name,1,'R',0);

	$pdf->SetXY(86,$cline);
	$buss_name=iconv('UTF-8','windows-874',$percent1."%");
	$pdf->MultiCell(35,6,$buss_name,1,'R',0);
		

	$pdf->SetXY(121,$cline);
	$buss_name=iconv('UTF-8','windows-874',$percent2."%");
	$pdf->MultiCell(35,6,$buss_name,1,'R',0);

	$pdf->SetXY(156,$cline);
	$buss_name=iconv('UTF-8','windows-874',$percent3."%");
	$pdf->MultiCell(40,6,$buss_name,1,'R',0);

	$pdf->SetXY(196,$cline);
	$buss_name=iconv('UTF-8','windows-874',$percent4."%");
	$pdf->MultiCell(30,6,$buss_name,1,'R',0);

	$pdf->SetXY(226,$cline);
	$buss_name=iconv('UTF-8','windows-874',$percent5."%");
	$pdf->MultiCell(35,6,$buss_name,1,'R',0);

	$pdf->SetXY(261,$cline);
	$buss_name=iconv('UTF-8','windows-874',$percent6."%");
	$pdf->MultiCell(34,6,$buss_name,1,'R',0);

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
		$pdf->SetXY(5,16);
		$title=iconv('UTF-8','windows-874',"ลูกหนี้ปี $contractyear");
		$pdf->MultiCell(290,5,$title,0,'C',0);
	
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"รายงานอายุหนี้ AGING สิ้นเพียง ณ วันที่ $datepicker ตามช่วงเดือน ประเภทสัญญา  $contypetxtshow");
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

		$pdf->SetXY(56,32);
		$buss_name=iconv('UTF-8','windows-874',"ไม่ค้างชำระ");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);$pdf->SetXY(56,32);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด น้อยกว่า 3 เดือน");
		$pdf->MultiCell(35,6,$buss_name,1,'C',0);

		$pdf->SetXY(121,32);
		$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด 3 เดือน - 6 เดือน");
		$pdf->MultiCell(35,6,$buss_name,1,'C',0);

		$pdf->SetXY(156,32);
		$buss_name=iconv('UTF-8','windows-874',"เกินกำหนด 6 เดือน - 12 เดือน");
		$pdf->MultiCell(40,6,$buss_name,1,'C',0);

		$pdf->SetXY(196,32);
		$buss_name=iconv('UTF-8','windows-874',"เกินกว่า 12 เดือน");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);

		$pdf->SetXY(226,32);
		$buss_name=iconv('UTF-8','windows-874',"ปรับโครงสร้างหนี้");
		$pdf->MultiCell(35,6,$buss_name,1,'C',0);

		$pdf->SetXY(261,32);
		$buss_name=iconv('UTF-8','windows-874',"อยู่ระหว่างดำเนินคดี");
		$pdf->MultiCell(34,6,$buss_name,1,'C',0);
	}

	$pdf->SetXY(226,$cline);
	$buss_name=iconv('UTF-8','windows-874',"ปี $contractyear ลูกหนี้ทั้งสิ้น");
	$pdf->MultiCell(35,6,$buss_name,1,'C',0);

	$pdf->SetXY(261,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($allsum,2));
	$pdf->MultiCell(34,6,$buss_name,1,'R',0);
	
	//clear data before next year
	unset($percent0);
	unset($percent1);
	unset($percent2);
	unset($percent3);
	unset($percent4);
	unset($percent5);
	unset($percent6);
	unset($allsum);
}

$pdf->Output();
?>