<?php
session_start();
require('../../thaipdfclass.php');
include("../../config/config.php");
$cid=pg_escape_string($_GET["cus_lid"]);
$nowdate = date("Y-m-d");
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

$pdf=new ThaiPDF('P' ,'mm','letter_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$qry_print=pg_query("select \"contractID\",\"cusName\",\"addressCon\",\"regisnumber\" from vthcap_letter WHERE auto_id='$cid' and regisnumber is not null
group by \"contractID\",\"cusName\",\"addressCon\",\"regisnumber\"");
while($res_print=pg_fetch_array($qry_print)){
	$name=trim($res_print["cusName"]); //ลูกค้าที่รับจดหมายกรณีเลือกที่อยู่ในสัญญา
	$ads2=trim($res_print["addressCon"]); //ที่อยู่ในสัญญา	
	$regisnumber=trim($res_print["regisnumber"]); //ที่อยู่ในสัญญา	

	$nub = 0;
    $show_type = "";
    //ค้นหาว่าส่งรายการอะไรบ้าง
	$qrydetail=pg_query("select detail,\"detailRef\" from \"thcap_letter_detailRef\" where \"sendID\"='$cid'");
	while($resdetail=pg_fetch_array($qrydetail)){
		if($resdetail['detailRef']==""){
			$ref="";
		}else{
			$ref="($resdetail[detailRef])";
		}
		//หาชื่อของรายการที่ส่ง
		$qry_name3=pg_query("select \"sendName\" from \"thcap_letter_head\" WHERE \"auto_id\"='$resdetail[detail]'");
        if($res_name3=pg_fetch_array($qry_name3)){
			$type_name=$res_name3["sendName"];
			$nub += 1;
			
			if($nub == 1){
				$show_type .= "$type_name $ref";
			}else{
				$show_type .= ", $type_name $ref";
			}
        }
	}

 $pdf->AddPage();
 $pdf->SetFont('AngsanaNew','B',11);
		
		//contractID
		$pdf->SetXY(55,4);
		$ta_name=iconv('UTF-8','windows-874',$res_print["contractID"]); 
		$pdf->MultiCell(50,4,$ta_name,0,'R',0);
		
		//header
		$pdf->SetXY(21,7);
		$ta_name=iconv('UTF-8','windows-874',$show_type); 
		$pdf->MultiCell(83,6,$ta_name,0,'R',0);
		$pdf->SetFont('AngsanaNew','',14);	
		
		//name
		$pdf->SetXY(21,14);
		$ta_name=iconv('UTF-8','windows-874',$name); 
		$pdf->MultiCell(100,4,$ta_name,0,'L',0);
		
		$pdf->SetFont('AngsanaNew','B',12);
		//address
		$pdf->SetXY(21,20);
		$ta_ads2=iconv('UTF-8','windows-874',$ads2); 
		$pdf->MultiCell(100,6,$ta_ads2,0,'L',0);
	
		//post
		$pdf->SetXY(55,38);
		$ta_post=iconv('UTF-8','windows-874',$post); 
		$pdf->MultiCell(95,8,$ta_post,0,'L',0);
		
		//day send
		$pdf->SetXY(21,44);
		$ta_day=iconv('UTF-8','windows-874',$day); 
		$pdf->MultiCell(180,8,$ta_day,0,'L',0);
		
		//month
		$pdf->SetXY(44,44);
		$ta_month=iconv('UTF-8','windows-874',$month); 
		$pdf->MultiCell(180,8,$ta_month,0,'L',0);
		
		 //year
		$pdf->SetXY(86,44);
		$ta_year=iconv('UTF-8','windows-874',$year); 
		$pdf->MultiCell(180,8,$ta_year,0,'L',0);
		
		//ref
		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(118,57);
		$ta_ref=iconv('UTF-8','windows-874',$regisnumber); 
		$pdf->MultiCell(180,8,$ta_ref,0,'L',0);
}		
				
$pdf->Output();		
?>