<?php
session_start();
require('../thaipdfclass.php');
include("../config/config.php");
$cid=pg_escape_string($_GET["cus_lid"]);
$nowdate = pg_escape_string($_GET["nowdate"]);
$post = pg_escape_string($_GET["post"]);

list($year, $month, $day) = split('[/.-]', $nowdate);
$year = $year + 543;

if($month == '01'){
	$month = "มกราคม";
}else if($month == '02'){
	$month = "กุมภาพันธ์";
}else if($month == '03'){
	$month = "มีนาคม";
}else if($month == '04'){
	$month = "เมษายน";
}else if($month == '05'){
	$month = "พฤษภาคม";
}else if($month == '06'){
	$month = "มิถุนายน";
}else if($month == '07'){
	$month = "กรกฎาคม";
}else if($month == '08'){
	$month = "สิงหาคม";
}else if($month == '09'){
	$month = "กันยายน";
}else if($month == '10'){
	$month = "ตุลาคม";
}else if($month == '11'){
	$month = "พฤศจิกายน";
}else if($month == '12'){
	$month = "ธันวาคม";
}

$qry_print1=pg_query("select \"detail\",\"IDNO\",\"type_send\" from letter.\"SendDetail\" WHERE auto_id='$cid'");
$res_print1=pg_fetch_array($qry_print1);


$headerid=explode(",",$res_print1["detail"]); //ประเภทหัวเรื่องการส่ง
$type_send=$res_print1["type_send"]; //ประเภทการส่ง เช่น E เป็น EMS
$headsize = sizeof($headerid);
if($headsize > 1){
	for($h = 0;$h<$headsize;$h++){
		$qry_header = pg_query("SELECT \"type_name\" FROM letter.type_letter where auto_id = '$headerid[$h]'");
		$res_header = pg_fetch_array($qry_header);
		if($header != ""){			
				$header = $header.",";			
		}	
			$header = $header.$res_header["type_name"];
			
	}	
}else{
		$qry_header = pg_query("SELECT \"type_name\" FROM letter.type_letter where auto_id = '$headerid[0]'");
		$res_header = pg_fetch_array($qry_header);
		$header = $res_header["type_name"];		
}






$qry_print=pg_query("select \"auto_id\",\"coname\",\"address\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\" from letter.\"SendDetail\" A
                     LEFT JOIN letter.\"cus_address\" B on A.\"address_id\" = B.\"address_id\"
					 LEFT JOIN \"Fa1\" C on B.\"CusID\" = C.\"CusID\"
					 WHERE A.\"auto_id\"='$cid' ");

  
  $res_print=pg_fetch_array($qry_print);
	$r_id=$res_print["auto_id"];
	$a_firname=trim($res_print["A_FIRNAME"]);
	if($a_firname=="นาย" || $a_firname=="นาง" || $a_firname=="นางสาว" || $a_firname=="น.ส."){
		$txtfirname="คุณ";
	}else{
		$txtfirname=$res_print["A_FIRNAME"];
	}
	if(trim($res_print["coname"])==""){
		$name=trim($txtfirname).trim($res_print["A_NAME"])." ".trim($res_print["A_SIRNAME"])."\n";
	}else{
		$name=trim($res_print["coname"]);
	}
	$ads2=trim($res_print["address"]);
	
	    $A_NO=trim($res_print["A_NO"]);
		$A_SUBNO=trim($res_print["A_SUBNO"]);
		$A_SOI="ซอย ".trim($res_print["A_SOI"]);
		$A_RD=" ถนน ".trim($res_print["A_RD"]);
		$A_TUM="แขวง/ตำบล ".trim($res_print["A_TUM"]);
		$A_AUM="เขต/อำเภอ ".trim($res_print["A_AUM"]);
		$A_PRO= trim($res_print["A_PRO"]);
		
		$A_POST=trim($res_print["A_POST"]);
		//$N_ContactAdd=$res_print["N_ContactAdd"];
		
		$address1 = "$A_NO  $A_SUBNO $A_SOI $A_RD $A_TUM";
		$address2 = "$A_AUM $A_PRO";
		$address3 = "$A_POST";
		
		$st_ads=$name.$address1."\n".$address2."\n".$address3;
	
	$ads=$st_ads;

$pdf=new ThaiPDF('P' ,'mm','letter_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

 $pdf->AddPage();
  $pdf->SetFont('AngsanaNew','B',12);

		//IDNO
		$pdf->SetXY(55,4);
		$ta_name=iconv('UTF-8','windows-874',$res_print1["IDNO"]); 
		$pdf->MultiCell(50,5,$ta_name,0,'R',0);
		
		//header
		$pdf->SetXY(16,7);
		$ta_name=iconv('UTF-8','windows-874',$header); 
		$pdf->MultiCell(88,5,$ta_name,0,'R',0);
	 $pdf->SetFont('AngsanaNew','',14);	
		//name
		$pdf->SetXY(16,12);
		$ta_name=iconv('UTF-8','windows-874',$name); 
		$pdf->MultiCell(100,8,$ta_name,0,'L',0);
		
		//address
		$pdf->SetXY(16,20);
		$ta_ads2=iconv('UTF-8','windows-874',$ads2); 
		$pdf->MultiCell(100,6,$ta_ads2,0,'L',0);
	
		//post
		$pdf->SetXY(55,38);
		$ta_post=iconv('UTF-8','windows-874',$post); 
		$pdf->MultiCell(95,8,$ta_post,0,'L',0);
		
		//day send
		$pdf->SetXY(20,44);
		$ta_day=iconv('UTF-8','windows-874',$day); 
		$pdf->MultiCell(180,8,$ta_day,0,'L',0);
		
		//month
		$pdf->SetXY(45,44);
		$ta_month=iconv('UTF-8','windows-874',$month); 
		$pdf->MultiCell(180,8,$ta_month,0,'L',0);
		
		 //year
		$pdf->SetXY(85,44);
		$ta_year=iconv('UTF-8','windows-874',$year); 
		$pdf->MultiCell(180,8,$ta_year,0,'L',0);
		
		//ref
		$query_ref=pg_query("select \"ems_num\",\"reg_num\" from letter.\"regis_send\" where auto_id='$cid'");
		$num_ref=pg_num_rows($query_ref);
		$resultref=pg_fetch_array($query_ref);
		if($type_send=="E"){
			$numref=$resultref["ems_num"];//เลขที่ ems
		}
		else if($type_send=="A"){
			$numref=$resultref["reg_num"];
		}
		if($num_ref!=0){
			$pdf->SetFont('AngsanaNew','',15);
			$pdf->SetXY(113,57);
			$ta_ref=iconv('UTF-8','windows-874',$numref); 
			$pdf->MultiCell(180,8,$ta_ref,0,'L',0);
		}
		
		
		
		
$pdf->Output();		
?>