<?php
include("../config/config.php");

$f_mon=pg_escape_string($_GET["mmon"]);
$f_year=pg_escape_string($_GET["myear"]);




$nowdate = date('Y/m/d');



//------------------- PDF -------------------//
require('../thaipdfclass.php');


class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(192,4,$buss_name,0,'R',0);
 
    }
 
}


$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();


$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,10);
$buss_name=iconv('UTF-8','windows-874',"รายงานยอดอากรสัญญาชำระประจำเดือน");
$pdf->MultiCell(180,4,$buss_name,0,'C',0);


$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(10,16);
$title=iconv('UTF-8','windows-874',"บริษัท".$_SESSION['session_company_thainame']);
$pdf->MultiCell(180,4,$title,0,'C',0);




$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน ".$f_mon." / ".$f_year);
$pdf->MultiCell(180,4,$buss_name,0,'L',0);

/*
$pdf->SetXY(0,23);
$buss_name=iconv('UTF-8','windows-874'," วันที่พิมพ์ $nowdate");
$pdf->MultiCell(202,4,$buss_name,0,'R',0);
*/

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(32,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);


$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"ราคาเช่าซื้อ ไม่รวม VAT");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(130,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่าอากรเช่าซื้อ");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่าอากรผู้ค้ำ");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);


$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;

    $gri=0;

   $sql_acid=pg_query("select * from \"VContact\" where (EXTRACT(YEAR FROM \"P_STDATE\")='$f_year') AND (EXTRACT(MONTH FROM \"P_STDATE\")='$f_mon') AND (\"P_TOTAL\"!=0) ORDER BY \"IDNO\"");
  while($res_acb=pg_fetch_array($sql_acid))
  {
     $r_count++;
	 $r_idno=$res_acb["IDNO"];
	 $r_name=$res_acb["full_name"];
	 $r_pmonth_ptotal=$res_acb["P_MONTH"]*$res_acb["P_TOTAL"];
     $r_cost=$r_pmonth_ptotal/1000;
	 $rk_cost=ceil($r_cost);
	 
	 
	 $sum_pt=$sum_pt+$r_pmonth_ptotal;
	 $sum_c=$sum_c+$rk_cost;
	
	/*
	$qry_m=pg_query($sql_acc);
    
    while($res_m=pg_fetch_array($qry_m))
   	{
	    $qri++;
	    $ss=$res_m["acb_id"];
		$sName=$res_m["acb_detail"];
	    $sql_ls=pg_query("select * from account.\"VAccountBook\" WHERE acb_id='$ss'");
		while($res_ls=pg_fetch_array($sql_ls))
	    {
		
		$j+=1;
    	$aa+=1;
    
        $a_date=$res_ls["acb_date"];
        $a_type=$res_ls["AcID"];
		$a_name=$res_ls["AcName"];
		$a_dr=$res_ls["AmtDr"];
		$a_cr=$res_ls["AmtCr"];
		
       
       */
		
	
    
        
if($i > 45)
{ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,10);
$buss_name=iconv('UTF-8','windows-874',"รายงานยอดอากรสัญญาชำระประจำเดือน");
$pdf->MultiCell(180,4,$buss_name,0,'C',0);


$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(10,16);
$title=iconv('UTF-8','windows-874',"บริษัท".$_SESSION['session_company_thainame']);
$pdf->MultiCell(180,4,$title,0,'C',0);




$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน ".$f_mon." / ".$f_year);
$pdf->MultiCell(180,4,$buss_name,0,'L',0);

/*
$pdf->SetXY(0,23);
$buss_name=iconv('UTF-8','windows-874'," วันที่พิมพ์ $nowdate");
$pdf->MultiCell(202,4,$buss_name,0,'R',0);
*/

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(32,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);


$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"ราคาเช่าซื้อ ไม่รวม VAT");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(130,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่าอากรเช่าซื้อ");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่าอากรผู้ค้ำ");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);


$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);
}

$pdf->SetFont('AngsanaNew','',13); 
   

$pdf->SetXY(5,$cline); 

/*
 $trn_date=pg_query("select * from c_date_number('$as_date')");
 $a_date=pg_fetch_result($trn_date,0); */

$buss_name=iconv('UTF-8','windows-874',$r_idno);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(32,$cline); 
$buss_name=iconv('UTF-8','windows-874',$r_name);
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(90,$cline); 
$buss_name=iconv('UTF-8','windows-874',$r_pmonth_ptotal);
$pdf->MultiCell(30,4,number_format($buss_name,2),0,'R',0);

$pdf->SetXY(125,$cline); 
$buss_name=iconv('UTF-8','windows-874',$rk_cost);
$pdf->MultiCell(30,4,number_format($buss_name,2),0,'R',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',10);
$pdf->MultiCell(30,4,number_format($buss_name,2),0,'R',0);

if($prn_line==1)
{
$pdf->SetXY(7,$cline+1.5); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);
}
else
{

}



$cline+=5; 

$i+=1;       

}

$arr_sName = explode("\n",$sName);
$count_arr_sName = count($arr_sName);
$nub = 0;
foreach($arr_sName AS $v){
    if(!empty($v)){
        $nub++;
        $pdf->SetXY(50,$cline);
        if($nub==1)
            $buss_name=iconv('UTF-8','windows-874',$ss." ".$v);
        else
            $buss_name=iconv('UTF-8','windows-874',$v);
        $pdf->MultiCell(100,4,$buss_name,0,'L',0);

        $cline+=5;
        $i+=1;
    }
}

$cline-=5;
/*
$pdf->SetXY(50,$cline); 
$buss_name=iconv('UTF-8','windows-874',$ss." ".$sName);
$pdf->MultiCell(70,4,$buss_name,0,'L',0);
*/
$pdf->SetXY(5,$cline+1); 

/*
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

*/
$cline+=5; 



$i+=1; 



$pdf->SetFont('AngsanaNew','',12);

/*
$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);
*/
$pdf->SetFont('AngsanaNew','B',13);


$pdf->SetXY(5,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $r_count รายการ ");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);        
        

$pdf->SetXY(90,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',$sum_pt);
$pdf->MultiCell(30,4,number_format($buss_name,2),0,'R',0);  

$pdf->SetXY(125,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',$sum_c);
$pdf->MultiCell(30,4,number_format($buss_name,2),0,'R',0);

$pdf->SetXY(160,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',10*$r_count);
$pdf->MultiCell(30,4,number_format($buss_name,2),0,'R',0);


$pdf->SetXY(90,$cline-4); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);  

$pdf->SetXY(125,$cline-4); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(160,$cline-4); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);


$pdf->SetXY(90,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);  

$pdf->SetXY(125,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(160,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(90,$cline+2.5); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);  

$pdf->SetXY(125,$cline+2.5); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(160,$cline+2.5); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);



$pdf->SetXY(30,$cline+10); 
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระค่าอากรสัญญาทั้งหมด : ");
$pdf->MultiCell(60,4,$buss_name,0,'R',0);  

$ss_sum=(10*$r_count)+$sum_c;


$pdf->SetXY(90,$cline+10); 
$buss_name=iconv('UTF-8','windows-874',$ss_sum);
$pdf->MultiCell(30,4,number_format($buss_name,2),0,'R',0);


$pdf->SetXY(90,$cline+11); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0); 

$pdf->SetXY(90,$cline+11.5); 
$buss_name=iconv('UTF-8','windows-874',"_____________");
$pdf->MultiCell(30,4,$buss_name,0,'R',0); 



$pdf->Output();
?>