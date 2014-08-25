<?php
session_start();
require('../thaipdfclass.php');
include("../config/config.php");
$cid=pg_escape_string($_GET["cus_lid"]);
$qry_print=pg_query("select \"coname\",\"auto_id\",\"address\" ,C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\" from letter.\"SendDetail\" A
                     LEFT JOIN letter.\"cus_address\" B on A.\"address_id\" = B.\"address_id\"
					 LEFT JOIN \"Fa1\" C on B.\"CusID\" = C.\"CusID\"
					 WHERE A.\"auto_id\"='$cid' ");

  
  $res_print=pg_fetch_array($qry_print);
	$coname=trim($res_print["coname"]);
	$a_firname=trim($res_print["A_FIRNAME"]);
	if($a_firname=="นาย" || $a_firname=="นาง" || $a_firname=="นางสาว" || $a_firname=="น.ส."){
		$txtfirname="คุณ";
	}else{
		$txtfirname=$res_print["A_FIRNAME"];
	}
	$r_id=$res_print["auto_id"];
	if($coname==""){
		$name=trim($txtfirname).trim($res_print["A_NAME"])." ".trim($res_print["A_SIRNAME"])."\n";
	}else{
		$name=$coname;
	}
	$ads2=$res_print["address"];
				
	$arti="ส่ง";				 
  				
$pdf=new ThaiPDF('P' ,'mm','letter_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

 $pdf->AddPage();
  $pdf->SetFont('AngsanaNew','',20);
		
  $col=10;
		
			
		$pdf->SetXY(25,40);
		$av_arti=iconv('UTF-8','windows-874',$arti); //ชื่อ
		$pdf->MultiCell(180,8,$av_arti,0,'L',0);
		
		 
		$pdf->SetXY(50,48);
		$av_name=iconv('UTF-8','windows-874',$name); //ชื่อ
		$pdf->MultiCell(100,8,$av_name,0,'L',0);
		
		$pdf->SetXY(50,56);
		$av_ads=iconv('UTF-8','windows-874',$ads2); 
		$pdf->MultiCell(120,8,$av_ads,0,'L',0);
	
		
		
$pdf->Output();		
?>