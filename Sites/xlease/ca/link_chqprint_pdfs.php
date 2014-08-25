<?php
include("../config/config.php");
$recid=pg_escape_string($_GET["pid"]);

//$pdf->Text(163,25.0,$recid);

//$pdf->SetXY(160,19.5);
//$pdf->Cell(0,5,$recid,0,1);//postID


require('fpdf.php');


class PDF  extends FPDF
{
//Page header
	function Header()
		{
		if($this->HAdd == 1){
    			//Select Arial bold 15
				$this->SetXY(163,19);
    			$Show = $this->HTitle;
				if($this->HShowPNo == 1)$Show = $Show . '' . $this->PageNo();
				$this->SetFont('CordiaNew','B',14);
    			//Framed title
    			$this->Cell(0,5,$Show,0,0,$this->HAlign);
    			//Line break
   			 	$this->Ln(24);
			 }
		}
		
		//Page footer
		function Footer()
		
		/*{
			//Position at 1.5 cm from bottom
			$this->SetY(-50);
			//Arial italic 8
			
			$this->SetFont('AngsanaNew','',12);
			//Page number
			$this->Cell(0,10,$recid,0,0,'R');
		}
		*/
		{
			if($this->VAdd == 1){
			 	$Show = $this->VTitle;
			 	if($this->VShowPNo == 1)$Show = $Show . '' . $this->PageNo();
				//Go to 1.5 cm from bottom
    			$this->SetY(-116);
    			//Select Arial italic 8
    			$this->SetFont('CordiaNew','B',14);
				//Print current and total page numbers
   		 		$this->Cell(0,12,$Show,0,0,$this->VAlign);
			}
		}


       

}



 
	$pdf=new PDF('P' ,'mm','slip_av');  
	$pdf->AliasNbPages();
	$pdf->SetThaiFont();

   // $pdf->Image('image/bg_slip_thaiace.jpg',40,113+$arow,23,15);

	
	


$recid=pg_escape_string($_GET["pid"]);

//$pdf->Text(163,25.0,$recid);

//$pdf->SetXY(160,19.5);
//$pdf->Cell(0,5,$recid,0,1);//postID

$a=0;
$a_row=10;


$d_ncq=pg_query("select A.*,B.* 
                   from \"FCheque\" A
				   LEFT OUTER JOIN \"BankCheque\" B on B.\"BankCode\"=A.\"BankName\"
				   where \"PostID\" ='$recid' ");
$res_dd=pg_fetch_array($d_ncq);
$qry_d=pg_query("select conversiondatetothaitext('$res_dd[ReceiptDate]')");
$res_cdate=pg_fetch_result($qry_d,0);
$av_rdate=iconv('UTF-8','windows-874',$res_cdate);



$qry_ncq=pg_query("select A.*,B.* 
                   from \"FCheque\" A
				   LEFT OUTER JOIN \"BankCheque\" B on B.\"BankCode\"=A.\"BankName\"
				   where \"PostID\" ='$recid' ");


$num_cq=pg_num_rows($qry_ncq);


    
	$pdf->SetHeader($recid."    " , 0, 'R', 1);
    
	$pdf->SetFooter($av_rdate."    " , 0, 'R', 1);

	$pdf->SetLeftMargin(25);
    $pdf->AddPage();

    $pdf->Image('image/ch_thaiace.jpg',20,5+$arow,176,15);

	$pdf->SetAutoPageBreak(true,45);
	$pdf->SetFont('AngsanaNew','',16);




$a=0;

$pdf->SetFont('AngsanaNew','',12);
$av_rdate=iconv('UTF-8','windows-874',"รายการรับเช็ค");
$pdf->Text(30,40,$av_rdate); //Type่

 while($rescq=pg_fetch_array($qry_ncq))
 {
 $a++;
 $c_no=trim($rescq["ChequeNo"]);//เลขที่เช็ค
 $c_bank=$rescq["BankName"]; //ชื่อย่อธนาคาร
 $c_fullbank=$rescq["BankName"]; //ชื่อย่อธนาคาร
 $c_bbrh=$rescq["BankBranch"]; //สาขา
 $c_dateoncq=$rescq["DateOnCheque"]; //วันที่บนเช็ค
 $cs_cusam=$rescq["AmtOnCheque"];
 


 $sum_amtcq=$sum_amtcq+$cs_cusam;
 
 $pdf->SetFont('AngsanaNew','',12);
 //$pdf->Text(163,35.0+$a_row,$c_no); 
 

 
 $av_cno=iconv('UTF-8','windows-874',$a.":"." เลขที่เช็ค :".$c_no." ".$c_fullbank." สาขา :".$c_bbrh." วันที่บนเช็ค :".$c_dateoncq); 
 $pdf->Cell(0,5,$av_cno,0,1);//total
 //$pdf->Cell(0,50,$av_cno,0,1);//total

 
 
 $qry_dtl=pg_query("select A.*,B.*,C.* from \"DetailCheque\" A 
	                    LEFT OUTER JOIN \"VContact\" B on A.\"IDNO\"=B.\"IDNO\"
						LEFT OUTER JOIN \"TypePay\" C on A.\"TypePay\"=C.\"TypeID\"
	
	 					where (A.\"PostID\" ='$recid') AND (A.\"ChequeNo\"='$c_no') ");
	 $n=0;
	 while($resdt=pg_fetch_array($qry_dtl))
	 {
	   $n++;
	   if($resdt["C_REGIS"]=="")
		{
		
		$rec_regis=$resdt["car_regis"]; 
		$rec_cnumber=$resdt["gas_number"];
		$res_band=$resdt["gas_name"];
		}
		else
		{
		
		$rec_regis=$resdt["C_REGIS"];
		$rec_cnumber=$resdt["C_CARNUM"];
		$res_band=$resdt["C_CARNAME"];
		}
 
		$c_idno=$resdt["IDNO"];//เลข idno
		$c_fullname=trim($resdt["full_name"]); //ชื่อ
		$c_regis=$rec_regis; //ทะเบียน
		$c_typepay=$resdt["TName"]; //จ่ายค่า
		$c_cusamt=number_format($resdt["CusAmount"],2); //ยอดเช็ค
		
	
	 
	 $av_cidno=iconv('UTF-8','windows-874',$c_idno." ".$c_fullname." ทะเบียน ".$c_regis." ชำระค่า :".$c_typepay." ยอดเงิน :".$c_cusamt);
	 
	 $pdf->Cell(0,5,"  ".$av_cidno,0,1);//total
	 
	 //$pdf->SetXY(138,50+$arow);
	 //$av_sum=iconv('UTF-8','windows-874',number_format($resdt["CusAmount"],2));
	 //$pdf->MultiCell(50,5,$av_sum,0,'R',0);//total
		
  	}
 

 $a_row=$a_row+10; 
  //  $pdf->SetXY(138,80+$arow);

//	$pdf->MultiCell(50,5,$av_sum,0,'R',0);//total

}
  $pdf->SetFont('AngsanaNew','',14);
 $pdf->SetXY(138,80);
  $av_sum=iconv('UTF-8','windows-874',number_format($sum_amtcq,2));	 
  //$pdf->Text(0,5,"  ".$av_sum,0,1);
  $pdf->Text(170,105,$av_sum);
  
  $trntotal=pg_query("select conversionnumtothaitext($sum_amtcq)");
  $restrn=pg_fetch_result($trntotal,0);
  $av_trn=iconv('UTF-8','windows-874',$restrn);
   $pdf->Text(50,105,$av_trn);
   
      //signature //
         $pdf->Image('image/signature.jpg',40,113+$arow,23,15);
  
$pdf->Output();
?>