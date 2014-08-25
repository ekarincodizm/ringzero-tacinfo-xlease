<?php
session_start();
require('../thaipdfclass.php');
include("../config/config.php");
$idno=pg_escape_string($_GET["IDNO"]);

$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) พิมพ์ Bill Payment', '$currentdate')");
//ACTIONLOG---


$qry_name=pg_query("select A.\"IDNO\",A.\"C_REGIS\",A.\"TranIDRef1\",A.\"TranIDRef2\",
(((btrim(B.\"A_FIRNAME\"::text) ::text) || btrim(B.\"A_NAME\"::text)) || ' '::text) || btrim(B.\"A_SIRNAME\"::text) AS fullname 
from \"VContact\" A left join \"Fa1\" B on A.\"CusID\" = B.\"CusID\" WHERE A.\"IDNO\" ='$idno'");
$res_name=pg_fetch_array($qry_name);
$IDNO=$res_name["IDNO"];
$name=$res_name["fullname"];
$c_regis = $res_name["C_REGIS"];
$ref1 = $res_name["TranIDRef1"];
$ref2 = $res_name["TranIDRef2"];	 
  
$pdf=new ThaiPDF('P' ,'mm','letter_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

 $pdf->AddPage();
 //$pdf->Image('Letter_head.jpg',0,0,234,108);
  $pdf->SetFont('AngsanaNew','',14);
				
		$pdf->SetXY(140,11);
		$ta_ref1=iconv('UTF-8','windows-874',$ref1); //ชื่อ
		$pdf->MultiCell(100,8,$ta_ref1,0,'L',0);
		
		$pdf->SetXY(150,19);
		$ta_ref2=iconv('UTF-8','windows-874',$ref2); //ชื่อ
		$pdf->MultiCell(100,8,$ta_ref2,0,'L',0);
		
		$pdf->SetXY(60,44);
		$ta_name=iconv('UTF-8','windows-874',"เลขที่สัญญา : ".$IDNO); //เลขที่สัญญา
		$pdf->MultiCell(60,4,$ta_name,0,'L',0);
		
		$pdf->SetXY(60,47);
		$ta_name=iconv('UTF-8','windows-874',$name); //ชื่อ
		$pdf->MultiCell(60,8,$ta_name,0,'L',0);
		
		$pdf->SetXY(110,47);
		$ta_regis=iconv('UTF-8','windows-874',$c_regis); 
		$pdf->MultiCell(50,8,$ta_regis,0,'L',0);
			
$pdf->Output();		
?>