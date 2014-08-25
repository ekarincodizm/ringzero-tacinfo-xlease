<?php
session_start();
$c_code=$_SESSION["session_company_name"];
$c_thainame=$_SESSION["session_company_thainame"];
$nowdate = date('Y/m/d');
include("../config/config.php");

require('../thaipdfclass.php'); 
class PDF extends ThaiPDF{
	function Header(){
		$this->SetFont('AngsanaNew','',12);
		$this->SetXY(10,16); 
		$buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
		$this->MultiCell(180,4,$buss_name,0,'R',0);
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
$title=iconv('UTF-8','windows-874',"รายงาน Bill payment".$st_m.$st_y);
$pdf->MultiCell(180,4,$title,0,'C',0);
		
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',$c_thainame);
$pdf->MultiCell(180,4,$buss_name,0,'C',0);
		
$pdf->SetXY(0,23);
$buss_name=iconv('UTF-8','windows-874'," วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);
		
$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(4,24.5); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);
		  
if($_POST["sb"]==0){
	//$sql_bk=pg_query("select * from bankofcompany"); //แสดงทุกธนาคาร
	$pdf->SetFont('AngsanaNew','B',15);	
	$pdf->SetXY(6,35); 
	$buss_name=iconv('UTF-8','windows-874',"พบปัญญาในการออกรายงาน      กรุณาเลือกเฉพาะธนาคารที่ต้องการออกรายงาน");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
}else{
	$sql_bk=pg_query("select * from bankofcompany where \"bankno\" = '$_POST[sb]'"); //กรณีเลือกธนาคาร

while($res_bk=pg_fetch_array($sql_bk)){  
	$re_bk=$res_bk["bankno"];
   
    $pdf->SetFont('AngsanaNew','',10);
		
	$pdf->SetXY(6,30+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"ธนาคาร ".$res_bk["bankname"]);
	$pdf->MultiCell(40,4,$buss_name,0,'L',0);
		
	$pdf->SetXY(5,30+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(5,30.5+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'L',0);
		
    $pdf->SetXY(5,34+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"No");
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);
		
	$pdf->SetXY(15,34+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"IDNO");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
				
	$pdf->SetXY(35,34+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"ชื่อ - นามสกุล");
	$pdf->MultiCell(50,4,$buss_name,0,'L',0);
		
	$pdf->SetXY(85,34+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่โอน/เวลา");
	$pdf->MultiCell(30,4,$buss_name,0,'L',0);
		
	$pdf->SetXY(115,34+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"Post asa");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		
	$pdf->SetXY(130,34+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"Post on date");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
				
	$pdf->SetXY(150,34+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"ยอดโอน");
	$pdf->MultiCell(28,4,$buss_name,0,'R',0);
				
	$pdf->SetXY(180,34+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"Post ID");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(5,35+$mline); 
	$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'L',0);		
    
	$io=1;
	$m_amt=0;
		
	$pdf->SetFont('AngsanaNew','',10);
		
	$cline = 41;
	$i = 1+$j;
	$j = 0; 
		
	$srt_sql=pg_query("select A.*,B.* from \"TranPay\" A LEFT OUTER JOIN  bankofcompany B on B.bankno=A.bank_no 
				WHERE (A.tr_date='$_POST[dd]') AND (B.bankno='$re_bk') AND A.\"branch_id\" = '$_POST[branch]'");
	while($res_m=pg_fetch_array($srt_sql)){ 
		if($i > 38){           	
			$pdf->AddPage(); 
			$cline = 37; 
			$i=1; 
		 
			$pdf->SetXY(5,26); 
			$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);
			
			$pdf->SetXY(5,30); 
			$buss_name=iconv('UTF-8','windows-874',"No");
			$pdf->MultiCell(10,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(15,30); 
			$buss_name=iconv('UTF-8','windows-874',"IDNO");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(35,30); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อ - นามสกุล");
			$pdf->MultiCell(50,4,$buss_name,0,'L',0);
				
			$pdf->SetXY(85,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่โอน/เวลา");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);
			
			$pdf->SetXY(115,30); 
			$buss_name=iconv('UTF-8','windows-874',"Post asa");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(130,30); 
			$buss_name=iconv('UTF-8','windows-874',"Post on date");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);
					
			$pdf->SetXY(150,30); 
			$buss_name=iconv('UTF-8','windows-874',"ยอดโอน");
			$pdf->MultiCell(28,4,$buss_name,0,'R',0);
						
			$pdf->SetXY(180,30); 
			$buss_name=iconv('UTF-8','windows-874',"Post ID");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(5,31); 
			$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);
		}
			
		if($res_m["post_on_asa_sys"]=='f'){
			$stp="wait";
		}else{
			$stp="finsh";
		}
			
		if(empty($res_m["post_on_date"])){
			$st_s="wait";
		}else{
			$st_s=$res_m["post_on_date"];
		}		   
	       	   
		$pdf->SetXY(5,$cline+$mline); 
		$buss_name=iconv('UTF-8','windows-874',$io++);
		$pdf->MultiCell(10,4,$buss_name,0,'C',0);
			
		$pdf->SetXY(15,$cline+$mline); 
		$buss_name=iconv('UTF-8','windows-874',$res_m["post_to_idno"]);
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
			
		$pdf->SetXY(35,$cline+$mline); 
		$buss_name=iconv('UTF-8','windows-874',$res_m["ref_name"]);
		$pdf->MultiCell(50,4,$buss_name,0,'L',0);
			
		$pdf->SetXY(85,$cline+$mline); 
		$buss_name=iconv('UTF-8','windows-874',$res_m["tr_date"]." / ".$res_m["tr_time"]);
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);
			
		$pdf->SetXY(115,$cline+$mline); 
		$buss_name=iconv('UTF-8','windows-874',$stp);
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
			
		$pdf->SetXY(130,$cline+$mline); 
		$buss_name=iconv('UTF-8','windows-874',$st_s);
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
						
		$pdf->SetXY(150,$cline+$mline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($res_m["amt"],2));
		$pdf->MultiCell(28,4,$buss_name,0,'R',0);
						
		$pdf->SetXY(180,$cline+$mline); 
		$buss_name=iconv('UTF-8','windows-874',$res_m["PostID"]);
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	      
        $m_amt=$m_amt+$res_m["amt"];		     
        $cline+=6; 
		$i+=1;        
    } //end while
		  	  
	$pdf->SetXY(150,$cline+$mline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($m_amt,2));
	$pdf->MultiCell(28,4,$buss_name,0,'R',0);
	    
	$pdf->SetXY(150,$cline+$mline+0.5); 
	$buss_name=iconv('UTF-8','windows-874',"________________");
    $pdf->MultiCell(28,4,$buss_name,0,'R',0);
	$pdf->SetXY(150,$cline+$mline+0.8); 
	$buss_name=iconv('UTF-8','windows-874',"________________");
    $pdf->MultiCell(28,4,$buss_name,0,'R',0);
		  
	$pdf->SetXY(5,$cline+$mline+2); 
	$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'L',0);

	$mline=$cline-18;
} //else while ธนาคาร
}
$pdf->Output();

?>