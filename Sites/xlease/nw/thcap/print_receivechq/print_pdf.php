<?php
include("../../../config/config.php");
require('../../../thaipdfclass.php');
$recnum=$_GET["recnum"]; //ใบรับเช็ค

//หาข้อมูลเพิ่มเติมสำหรับนำมาแสดง
$qrydata=pg_query("SELECT distinct(\"revChqNum\"),\"revChqToCCID\",\"thcap_fullname\",\"thcap_address\",date(\"revChqDate\"),\"receiverFullName\" FROM finance.\"V_thcap_receive_cheque_chqManage\" a 
left join \"vthcap_ContactCus_detail\" b on a.\"revChqToCCID\"=b.\"contractID\" and \"CusState\"='0'
where  \"revChqNum\" ='$recnum' and \"CusState\"='0'");
list($revChqNum,$contractID,$cusname,$address,$revChqDate,$receiverName)=pg_fetch_array($qrydata);

class PDF extends ThaiPDF
{ 
	function Header(){	
		$this->SetFont('AngsanaNew','B',14);
		$this->SetXY(98,5); 
		$buss_name=iconv('UTF-8','windows-874',"(เอกสารนี้มีทั้งสิ้น tp หน้า หน้า ".$this->PageNo()."/tp)");
		$this->MultiCell(100,4,$buss_name,0,'L',0);
		
		$this->SetXY(98,155); 
		$buss_name=iconv('UTF-8','windows-874',"(เอกสารนี้มีทั้งสิ้น tp หน้า หน้า ".$this->PageNo()."/tp)");
		$this->MultiCell(100,4,$buss_name,0,'L',0);
	}
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$col=19;
//๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑ต้นฉบับ
	//ใบรับเช็ค
	$pdf->SetFont('AngsanaNew','B',14);  
	$pdf->SetXY(175,$col);
	$txtrecnum=iconv('UTF-8','windows-874',$recnum);
	$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
	
	$col+=6;
	//วันที่รับเช็ค 
	$pdf->SetXY(165,$col);
	$txtrecnum=iconv('UTF-8','windows-874',$revChqDate);
	$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
	
	$col+=6;
	//ชื่อลูกค้า
	$pdf->SetXY(30,$col);
	$txtrecnum=iconv('UTF-8','windows-874',$cusname);
	$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
	
	//เลขที่สัญญา
	$pdf->SetXY(130,$col);
	$txtrecnum=iconv('UTF-8','windows-874',$contractID);
	$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
	
	$col+=8;
	//ที่อยู่
	$pdf->SetXY(30,$col);
	$txtrecnum=iconv('UTF-8','windows-874',$address);
	$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
	
	//ลงชื่อผู้รับเช็ค
	$pdf->SetXY(20,135);
	$txtrecnum=iconv('UTF-8','windows-874',$receiverName);
	$pdf->MultiCell(60,3,$txtrecnum,0,'C',0); 
	
	//ลงชื่อลูกค้า
	$pdf->SetXY(130,135);
	$txtrecnum=iconv('UTF-8','windows-874',$cusname);
	$pdf->MultiCell(65,3,$txtrecnum,0,'C',0); 
	
	

$col+=126;	
//๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑สำเนา
	//ใบรับเช็ค
	$pdf->SetFont('AngsanaNew','B',14);  
	$pdf->SetXY(175,$col);
	$txtrecnum=iconv('UTF-8','windows-874',$recnum);
	$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
	
	$col+=6;
	//วันที่รับเช็ค 
	$pdf->SetXY(165,$col);
	$txtrecnum=iconv('UTF-8','windows-874',$revChqDate);
	$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
	
	$col+=6;
	//ชื่อลูกค้า
	$pdf->SetXY(30,$col);
	$txtrecnum=iconv('UTF-8','windows-874',$cusname);
	$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
	
	//เลขที่สัญญา
	$pdf->SetXY(130,$col);
	$txtrecnum=iconv('UTF-8','windows-874',$contractID);
	$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
	
	$col+=8;
	//ที่อยู่
	$pdf->SetXY(30,$col);
	$txtrecnum=iconv('UTF-8','windows-874',$address);
	$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
	
	//ลงชื่อผู้รับเช็ค
	$pdf->SetXY(20,273);
	$txtrecnum=iconv('UTF-8','windows-874',$receiverName);
	$pdf->MultiCell(60,3,$txtrecnum,0,'C',0); 
	
	//ลงชื่อลูกค้า
	$pdf->SetXY(130,273);
	$txtrecnum=iconv('UTF-8','windows-874',$cusname);
	$pdf->MultiCell(65,3,$txtrecnum,0,'C',0);
	
//########################################แสดงข้อมูล
$col1=62;
$col2=208;
$nub=1;
	$sumamt=0;
	$qry=pg_query("select \"bankChqNo\",\"bankChqDate\",\"bankName\",\"bankChqAmt\" from finance.\"V_thcap_receive_cheque_chqManage\" a
	left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
	where \"revChqNum\" ='$recnum' order by \"bankChqNo\"");
	while($res=pg_fetch_array($qry)){
		list($bankChqNo,$bankChqDate,$bankName,$bankChqAmt)=$res;
		if($nub>6){
			//จำนวนเงินรวมต้นฉบับ
			$pdf->SetXY(165,$col1+8);
			$txtrecnum=iconv('UTF-8','windows-874',number_format($sumamt,2));
			$pdf->MultiCell(40,5,$txtrecnum,0,'R',0);
			
			//จำนวนเงินรวมต้นฉบับ
			$pdf->SetXY(165,$col2+8);
			$txtrecnum=iconv('UTF-8','windows-874',number_format($sumamt,2));
			$pdf->MultiCell(40,5,$txtrecnum,0,'R',0);
			
			$pdf->AddPage();
			$col=19;
			$nub=1;
			//๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑ต้นฉบับ
			//ใบรับเช็ค
			$pdf->SetFont('AngsanaNew','B',14);  
			$pdf->SetXY(175,$col);
			$txtrecnum=iconv('UTF-8','windows-874',$recnum);
			$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
			
			$col+=6;
			//วันที่รับเช็ค 
			$pdf->SetXY(165,$col);
			$txtrecnum=iconv('UTF-8','windows-874',$revChqDate);
			$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
			
			$col+=6;
			//ชื่อลูกค้า
			$pdf->SetXY(30,$col);
			$txtrecnum=iconv('UTF-8','windows-874',$cusname);
			$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
			
			//เลขที่สัญญา
			$pdf->SetXY(130,$col);
			$txtrecnum=iconv('UTF-8','windows-874',$contractID);
			$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
			
			$col+=8;
			//ที่อยู่
			$pdf->SetXY(30,$col);
			$txtrecnum=iconv('UTF-8','windows-874',$address);
			$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
			
			//ลงชื่อผู้รับเช็ค
			$pdf->SetXY(20,135);
			$txtrecnum=iconv('UTF-8','windows-874',$receiverName);
			$pdf->MultiCell(60,3,$txtrecnum,0,'C',0); 
			
			//ลงชื่อลูกค้า
			$pdf->SetXY(130,135);
			$txtrecnum=iconv('UTF-8','windows-874',$cusname);
			$pdf->MultiCell(65,3,$txtrecnum,0,'C',0);

			$col+=126;	
			//๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑สำเนา
			//ใบรับเช็ค
			$pdf->SetFont('AngsanaNew','B',14);  
			$pdf->SetXY(175,$col);
			$txtrecnum=iconv('UTF-8','windows-874',$recnum);
			$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
			
			$col+=6;
			//วันที่รับเช็ค 
			$pdf->SetXY(165,$col);
			$txtrecnum=iconv('UTF-8','windows-874',$revChqDate);
			$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
			
			$col+=6;
			//ชื่อลูกค้า
			$pdf->SetXY(30,$col);
			$txtrecnum=iconv('UTF-8','windows-874',$cusname);
			$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
			
			//เลขที่สัญญา
			$pdf->SetXY(130,$col);
			$txtrecnum=iconv('UTF-8','windows-874',$contractID);
			$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
			
			$col+=8;
			//ที่อยู่
			$pdf->SetXY(30,$col);
			$txtrecnum=iconv('UTF-8','windows-874',$address);
			$pdf->MultiCell(190,3,$txtrecnum,0,'L',0); 
			
			//ลงชื่อผู้รับเช็ค
			$pdf->SetXY(20,273);
			$txtrecnum=iconv('UTF-8','windows-874',$receiverName);
			$pdf->MultiCell(60,3,$txtrecnum,0,'C',0); 
			
			//ลงชื่อลูกค้า
			$pdf->SetXY(130,273);
			$txtrecnum=iconv('UTF-8','windows-874',$cusname);
			$pdf->MultiCell(65,3,$txtrecnum,0,'C',0);
			
			$col1=62;
			$col2=208;
			$sumamt=0;
		}
		
		//๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑ต้นฉบับ
		//เลขที่เช็ค
		$pdf->SetXY(15,$col1);
		$txtrecnum=iconv('UTF-8','windows-874',$bankChqNo);
		$pdf->MultiCell(45,5,$txtrecnum,0,'L',0); 
		
		//วันที่สั่งจ่าย
		$pdf->SetXY(60,$col1);
		$txtrecnum=iconv('UTF-8','windows-874',$bankChqDate);
		$pdf->MultiCell(30,5,$txtrecnum,0,'L',0); 
		
		//ธนาคาร
		$pdf->SetXY(90,$col1);
		$txtrecnum=iconv('UTF-8','windows-874',"   $bankName");
		$pdf->MultiCell(75,5,$txtrecnum,0,'L',0); 
		
		//จำนวนเงิน
		$pdf->SetXY(165,$col1);
		$txtrecnum=iconv('UTF-8','windows-874',number_format($bankChqAmt,2));
		$pdf->MultiCell(40,5,$txtrecnum,0,'R',0);


		//๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒สำเนา	
		//เลขที่เช็ค
		$pdf->SetXY(15,$col2);
		$txtrecnum=iconv('UTF-8','windows-874',$bankChqNo);
		$pdf->MultiCell(45,5,$txtrecnum,0,'L',0); 
		
		//วันที่สั่งจ่าย
		$pdf->SetXY(60,$col2);
		$txtrecnum=iconv('UTF-8','windows-874',$bankChqDate);
		$pdf->MultiCell(30,5,$txtrecnum,0,'L',0); 
		
		//ธนาคาร
		$pdf->SetXY(90,$col2);
		$txtrecnum=iconv('UTF-8','windows-874',"   $bankName");
		$pdf->MultiCell(75,5,$txtrecnum,0,'L',0); 
		
		//จำนวนเงิน
		$pdf->SetXY(165,$col2);
		$txtrecnum=iconv('UTF-8','windows-874',number_format($bankChqAmt,2));
		$pdf->MultiCell(40,5,$txtrecnum,0,'R',0);
		
		$sumamt+=$bankChqAmt;
		$col1+=6;
		$col2+=6;
		$nub+=1;
	}
	if($nub<7){
		//จำนวนเงินรวมต้นฉบับ
		$pdf->SetXY(165,106);
		$txtrecnum=iconv('UTF-8','windows-874',number_format($sumamt,2));
		$pdf->MultiCell(40,5,$txtrecnum,0,'R',0);
		
		//จำนวนเงินรวมต้นฉบับ
		$pdf->SetXY(165,252);
		$txtrecnum=iconv('UTF-8','windows-874',number_format($sumamt,2));
		$pdf->MultiCell(40,5,$txtrecnum,0,'R',0);
	}

$pdf->Output(); //open pdf
?>
