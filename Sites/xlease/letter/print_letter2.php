<?php
session_start();
require('../thaipdfclass.php');
include("../config/config.php");
$cid=pg_escape_string($_GET["cus_lid"]);
$is_status=pg_escape_string($_GET["i_status"]);
if($is_status==0)
{
$qry_print=pg_query("select A.\"IDNO\",B.\"IDNO\",B.\"CusID\",C.* from letter.\"SendDetail\" A 
  left outer join letter.\"VAddressList\" B on B.\"IDNO\" = A.\"IDNO\"
  left outer join \"Fa1\" C on C.\"CusID\"=B.\"CusID\"
  where auto_id='$cid' ");
  
  $res_print=pg_fetch_array($qry_print);

	$r_id=$res_print["auto_id"];
	$name=trim($res_print["A_FIRNAME"])." ".trim($res_print["A_NAME"])." ".trim($res_print["A_SIRNAME"])."\n";
	
	
	    $A_NO=trim($res_print["A_NO"]);
		$A_SUBNO=trim($res_print["A_SUBNO"]);
		$A_SOI="เธเธญเธข ".trim($res_print["A_SOI"]);
		$A_RD=" เธ–เธเธ ".trim($res_print["A_RD"]);
		$A_TUM="เนเธเธงเธ/เธ•เธณเธเธฅ ".trim($res_print["A_TUM"]);
		$A_AUM="เน€เธเธ•/เธญเธณเน€เธ เธญ ".trim($res_print["A_AUM"]);
		$A_PRO="เธ. ".trim($res_print["A_PRO"]);
		
		$A_POST=trim($res_print["A_POST"]);
		$N_ContactAdd=$res_print["N_ContactAdd"];
		
		$address1 = "$A_NO  $A_SUBNO $A_SOI $A_RD $A_TUM";
		$address2 = "$A_AUM $A_PRO";
		$address3 = "$A_POST";
		
		$st_ads=$name.$address1."\n".$address2."\n".$address3;
	
	$ads=$st_ads;
	
	$arti="เธชเนเธ";				 
  
}
else
{
$qry_print=pg_query("select A.*,B.*,C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\" from letter.\"SendDetail\" A
                     LEFT JOIN letter.\"cus_address\" B on A.\"address_id\" = B.\"address_id\"
					 LEFT JOIN \"Fa1\" C on B.\"CusID\" = C.\"CusID\"
					 WHERE A.\"auto_id\"='$cid' ");
	$res_print=pg_fetch_array($qry_print);

	$r_id=$res_print["auto_id"];
	//$name=trim($res_print["A_FIRNAME"]).trim($res_print["A_NAME"])." ".trim($res_print["A_SIRNAME"]);
	$ads=$res_print["address"];
	
	$arti="เธชเนเธ";				 
					 
}
					 


$pdf=new ThaiPDF('P' ,'mm','letter_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

 $pdf->AddPage();
 //$pdf->Image('Letter_head.jpg',0,0,234,108);
  $pdf->SetFont('AngsanaNew','',20);
		
  $col=10;
		
			
		$pdf->SetXY(25,40);
		$av_arti=iconv('UTF-8','windows-874',$arti); //เธเธทเนเธญ
		$pdf->MultiCell(180,8,$av_arti,0,'L',0);
		
		 /*
		$pdf->SetXY(45,48);
		$av_name=iconv('UTF-8','windows-874',$name); //เธเธทเนเธญ
		$pdf->MultiCell(180,8,$av_name,0,'L',0);
		*/
		$pdf->SetXY(45,48);
		$av_ads=iconv('UTF-8','windows-874',$ads); 
		$pdf->MultiCell(180,8,$av_ads,0,'L',0);
	
		
		
$pdf->Output();		
?>