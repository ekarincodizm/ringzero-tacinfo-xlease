<?php
	include("../../config/config.php");

	$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

	//รับค่าตัวแปร เพื่อสร้าง Sql Command สำหรับดึงข้อมูล 
	$money_type = pg_escape_string($_GET["money_type"]); 
	$date_sel = pg_escape_string($_GET["date_sel"]); 
	$qry_cmd =  "SELECT * FROM thcap_temp_money_hold_secure WHERE (moneytype = $money_type ) And (\"dataDate\" = '$date_sel') And (money > 0) ORDER By \"contractID\" ASC  ";
    
	
    $query_list = pg_query($qry_cmd);
    $num_row = pg_num_rows($query_list); 

    if($money_type == 998){
		$money_type_str = 'เงินพัก';
	}elseif($money_type == 997){
		$money_type_str = 'เงินค้ำประกัน';
	}
	$Str_Rpt_Tab = "-(THCAP) รายงานเงินพักรอตัดรายการ เงินค้ำประกันการชำระหนี้ เงินมัดจำ"; // ข้อความสำหรับแสดงบนหัวตาราง
	
	// ------------------- PDF -------------------//
	require('../../thaipdfclass.php');

	class PDF extends ThaiPDF
	{
		function Header()
		{
			$this->SetFont('AngsanaNew','',12);
			$this->SetXY(5,16); 
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
    
	// เริ่ม กำหนดข้อความส่วนหัวกระดาษ
	$pdf->SetFont('AngsanaNew','B',15);
	$pdf->SetXY(10,10);
	$title=iconv('UTF-8','windows-874',"(THCAP) รายงานเงินพักรอตัดรายการ เงินค้ำประกันการชำระหนี้ เงินมัดจำ");
	$pdf->MultiCell(200,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',12); 
	$pdf->SetXY(5,22); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
	$pdf->MultiCell(200,4,$buss_name,0,'R',0);  
	
	$pdf->SetXY(10,15);
	$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);

	$gmm=iconv('UTF-8','windows-874',"$Str_Rpt_Tab");
	$pdf->Text(5,26,$gmm);
	// สิ้นสุด กำหนดข้อความส่วนหัวกระดาษ
	
	// เริ่มสร้างหัวตาราง

	// Create Line-1 For Table
	$pdf->SetXY(30,26); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(135,4,$buss_name,'B','R',0);

	//Define Font For Column Name
	$pdf->SetFont('AngsanaNew','',10); 

	// Create Text Of Column-1
	$pdf->SetXY(30,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(28,5,$buss_name,'1','C',0);

	// Create Text Of Column-2
	$pdf->SetXY(58,30); 
	$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
	$pdf->MultiCell(40,5,$buss_name,'1','C',0);

	// Create Text Of Column-3
	$pdf->SetXY(98,30); 
	$buss_name=iconv('UTF-8','windows-874',"ประเภทเงิน");
	$pdf->MultiCell(20,5,$buss_name,'1','C',0);

	// Create Text Of Column-4
	$pdf->SetXY(118,30); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่ข้อมูล");
	$pdf->MultiCell(17,5,$buss_name,'1','C',0);

	// Create Text Of Column-5
	$pdf->SetXY(135,30); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่สร้างข้อมูล");
	$pdf->MultiCell(30,5,$buss_name,'1','C',0);

	// Create Line-2 For Table
	$pdf->SetXY(30,35); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(135,4,$buss_name,'T','R',0); 
	
	$Y_Value = 35; // ค่าตำแหน่ง Y สำหรับแสดงข้อมูลในแต่ละ Record  
	$idx_row = 1; //เลขประจำแถวเพื่อการควบคุมการแสดงผล
	$row_per_page = 48; // จำนวน Record ที่จะแสดงใน แต่ละหน้า
	
	$Sum_Money_Rpt = 0; // For Save Sum Of Money
	while($Data_Rpt=pg_fetch_array($query_list))
	{
		// เตรียมข้อมูลสำหรับการออกรายงานในแต่ละ Record
		$Contract_ID = $Data_Rpt['contractID']; // เลขที่่สัญญา
		$Money_Rpt = number_format($Data_Rpt['money'],2,'.',','); //จำนวนเงิน
		if($Data_Rpt['moneytype'] == 998){ // กำหนดประเภทเงิน เงินพัก หรือ เงินค้ำประกัน
			$Monty_Type = "เงินพัก";
		}elseif($Data_Rpt['moneytype'] == 997){
			$Monty_Type = "เงินค้ำประกัน";
		}	
		$Date_Of_Data = $Data_Rpt['dataDate']; // วันที่ข้อมูล 
	    $Date_Of_GenDate = $Data_Rpt['genDate']; //วันที่สร้างข้อมูล
	   
		$Sum_Money_Rpt+= $Data_Rpt['money'];
		// ส่วนการแสดงผลที่ รายงาน
		if($idx_row > $row_per_page){ // เ ริ่มส่วน หัวรายงาน
			$idx_row=1;  
			$pdf->AddPage();
			$Y_Value = 35; // ค่าตำแหน่ง Y สำหรับแสดงข้อมูลในแต่ละ Record 
			
			// เริ่ม กำหนดข้อความส่วนหัวกระดาษ
			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(10,10);
			$title=iconv('UTF-8','windows-874',"(THCAP) รายงานเงินพักรอตัดรายการ เงินค้ำประกันการชำระหนี้ เงินมัดจำ");
			$pdf->MultiCell(200,4,$title,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(5,22); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
			$pdf->MultiCell(200,4,$buss_name,0,'R',0);  
	
			$pdf->SetXY(10,15);
			$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$gmm=iconv('UTF-8','windows-874',"$Str_Rpt_Tab");
			$pdf->Text(5,26,$gmm);
			// สิ้นสุด กำหนดข้อความส่วนหัวกระดาษ
			
			// Create Line-1 For Table
			$pdf->SetXY(30,26); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(135,4,$buss_name,'B','R',0);

			//Define Font For Column Name
			$pdf->SetFont('AngsanaNew','',10); 

			// Create Text Of Column-1
			$pdf->SetXY(30,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(28,5,$buss_name,'1','C',0);

			// Create Text Of Column-2
			$pdf->SetXY(58,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
			$pdf->MultiCell(40,5,$buss_name,'1','C',0);

			// Create Text Of Column-3
			$pdf->SetXY(98,30); 
			$buss_name=iconv('UTF-8','windows-874',"ประเภทเงิน");
			$pdf->MultiCell(20,5,$buss_name,'1','C',0);

			// Create Text Of Column-4
			$pdf->SetXY(118,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่ข้อมูล");
			$pdf->MultiCell(17,5,$buss_name,'1','C',0);

			// Create Text Of Column-5
			$pdf->SetXY(135,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่สร้างข้อมูล");
			$pdf->MultiCell(30,5,$buss_name,'1','C',0);

			// Create Line-2 For Table
			$pdf->SetXY(30,35); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(135,4,$buss_name,'T','R',0); 
			
		}
		// แสดงข้อความใน Col ที่ 1 ( เลขที่สัญญา) 
		$pdf->SetXY(30,$Y_Value);
		$buss_name=iconv('UTF-8','windows-874',$Contract_ID);
		$pdf->MultiCell(28,5,$buss_name,'B','C',0); 
		
		// แสดงข้อความใน Col ที่ 2 ( จำนวนเงิน ) 
		$pdf->SetXY(55,$Y_Value);
		$buss_name=iconv('UTF-8','windows-874',$Money_Rpt);
		$pdf->MultiCell(38,5,$buss_name,'B','R',0); 
		
		// แสดงข้อความใน Col ที่ 3 ( ประเภทเงิน เงินพัก หรือ เงินค้ำประกัน ) 
		$pdf->SetXY(93,$Y_Value);
		$buss_name=iconv('UTF-8','windows-874',$Monty_Type);
		$pdf->MultiCell(22,5,$buss_name,'B','R',0); 
		
		//วแสดงข้อความใน Col ที่ 4  วันที่ของข้อมูล
		$pdf->SetXY(115,$Y_Value);
		$buss_name=iconv('UTF-8','windows-874',$Date_Of_Data);
		$pdf->MultiCell(20,5,$buss_name,'B','C',0); 
		
		// แสดงข้อความใน Col ที่ 4  วันที่สร้างข้อมูล
		$pdf->SetXY(135,$Y_Value);
		$buss_name=iconv('UTF-8','windows-874',$Date_Of_GenDate);
		$pdf->MultiCell(30,5,$buss_name,'B','C',0); 
		
		
		$Y_Value+= 5; 
		$idx_row+=1;
		
	  
	} // End Of Loop :: while($Data_Rpt=pg_fetch_array($query_list))
	 
	$Str_Number_Money = " รวมเป็นจำนวนเงิน ".number_format($Sum_Money_Rpt,2,'.',',');
	
	$pdf->SetXY(30,$Y_Value);
	$buss_name=iconv('UTF-8','windows-874',$Str_Number_Money);
	$pdf->MultiCell(63,5,$buss_name,'B','R',0); 
	
	

//ขีดเส้นขั้นรวม 3 เส้นแรก
/*$pdf->SetXY(127,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(167,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(187,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
*/


$pdf->Output();
?>


