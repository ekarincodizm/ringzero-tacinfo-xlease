<?php
session_start();

include("../config/config.php");
$nowdate = Date('Y-m-d');

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(190,4,$buss_name,0,'R',0);
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
$title=iconv('UTF-8','windows-874',"Pass Chqeue");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(118,23);
$pdf->SetFont('AngsanaNew','B',12);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); $pdf->SetFont('AngsanaNew','',12);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"Cheque No.");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(50,30); 
$buss_name=iconv('UTF-8','windows-874',"Bank Name");
$pdf->MultiCell(35,4,$buss_name,0,'L',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"Bank Branch");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(115,30); 
$buss_name=iconv('UTF-8','windows-874',"date on cheque");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"dateEnter Bank");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"AmtOnCheque");
$pdf->MultiCell(25,5,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$pdf->SetFont('AngsanaNew','',12);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);
$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;
   
 /* data  */
 
 
  
 // echo "T"."<br>";
 //print_r($chose_listss=pg_escape_string($_POST["chose_lists"]));
    
	

  $dateqq=pg_escape_string($_GET["qdate"]);


  $qrybb=pg_query("select DISTINCT  \"AccBankEnter\" from \"FCheque\" 
                  where \"DateEnterBank\"='$dateqq'
				  ");
  while($resbb=pg_fetch_array($qrybb))
  {
  
   $abb=$resbb["AccBankEnter"];
  

  $qry_bk=pg_query("select * from bankofcompany where accno='$abb'");
  while($res_bk=pg_fetch_array($qry_bk))
  {
    $accbk=$res_bk["accno"];
	
    $accs=$res_bk["bankname"];
	$accbr=$res_bk["bankbranch"];

       $qry_chq=pg_query("select A.*,B.*,C.* from \"FCheque\"  A
                     LEFT OUTER JOIN \"BankCheque\" B ON A.\"BankName\"=B.\"BankCode\"
					 LEFT OUTER JOIN bankofcompany C ON A.\"AccBankEnter\"=C.accno
                     WHERE (\"DateEnterBank\"='$dateqq') AND (A.\"AccBankEnter\"='$accbk') AND (A.\"Accept\"=TRUE) ");
					 		 
		  while($res_chq=pg_fetch_array($qry_chq))
		  {		
		   
			
		   
			$n++; 			
			$cq_no=$res_chq["ChequeNo"];
			$p_id=$res_chq["PostID"];
			$ch_date=$res_chq["DateOnCheque"];
            $b_name=$res_chq["BankName"];
			$b_br=$res_chq["BankBranch"];
             $d_entbank=$res_chq["DateEnterBank"]; 
			 $amt_onch=$res_chq["AmtOnCheque"];
 /*                 
$qry_fr=pg_query("select \"IDNO\",COUNT(\"DueNo\") as \"SumDueNo\",MAX(\"daydelay\") as \"daydelay\" from \"VRemainPayment\" GROUP BY \"IDNO\" ORDER BY \"SumDueNo\" DESC,\"IDNO\" ASC ");
while($res_fr=pg_fetch_array($qry_fr)){
    
    $IDNO = $res_fr["IDNO"];
    $SumDueNo = $res_fr["SumDueNo"];
    $DueDate = $res_fr["DueDate"];
    $V_Receipt = $res_fr["V_Receipt"];
    $V_Date = $res_fr["V_Date"];
    $daydelay = $res_fr["daydelay"];
    
    $qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $full_name = $res_vc["full_name"];
        $C_COLOR = $res_vc["C_COLOR"];
        $asset_type = $res_vc["asset_type"];
        $C_REGIS = $res_vc["C_REGIS"];
        $car_regis = $res_vc["car_regis"];
        if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
    }
*/
if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"Pass Cheque");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(118,23);
$pdf->SetFont('AngsanaNew','B',12);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);
$pdf->SetXY(4,24); 
$pdf->SetFont('AngsanaNew','',12);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"Cheque No.");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(50,30); 
$buss_name=iconv('UTF-8','windows-874',"Bank Name");
$pdf->MultiCell(35,4,$buss_name,0,'L',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"Bank Branch");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(115,30); 
$buss_name=iconv('UTF-8','windows-874',"date on cheque");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"dateEnter Bank");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"AmtOnCheque");
$pdf->MultiCell(25,5,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$pdf->SetFont('AngsanaNew','',12);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',14); 

$pdf->SetXY(15,$cline); 
$buss_name=iconv('UTF-8','windows-874',$n);
$pdf->MultiCell(10,5,$buss_name,0,'C',0);

$pdf->SetXY(30,$cline); 
$buss_name=iconv('UTF-8','windows-874',$cq_no);
$pdf->MultiCell(20,5,$buss_name,0,'L',0);

$pdf->SetXY(50,$cline); 
$buss_name=iconv('UTF-8','windows-874',$b_name);
$pdf->MultiCell(35,5,$buss_name,0,'L',0);

$pdf->SetXY(85,$cline); 
$buss_name=iconv('UTF-8','windows-874',$b_br);
$pdf->MultiCell(30,5,$buss_name,0,'L',0);

$pdf->SetXY(115,$cline); 
$buss_name=iconv('UTF-8','windows-874',$ch_date);
$pdf->MultiCell(20,5,$buss_name,0,'C',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',$d_entbank);
$pdf->MultiCell(20,5,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($amt_onch,2));
$pdf->MultiCell(25,5,$buss_name,0,'R',0);

$cline+=5; 
$i+=1;     

$sumamt=$sumamt+$res_chq["AmtOnCheque"];
  
}

  
  }
  }
$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);


$pdf->SetXY(145,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"รวมยอดเงิน");
$pdf->MultiCell(20,5,$buss_name,0,'R',0);


$pdf->SetXY(170,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',number_format($sumamt,2));
$pdf->MultiCell(25,5,$buss_name,0,'R',0);


$pdf->Output();
?>