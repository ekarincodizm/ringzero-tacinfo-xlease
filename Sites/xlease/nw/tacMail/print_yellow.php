<?php
session_start();
require('../../thaipdfclass.php');
include("../../config/config.php");
$cid=$_GET["cus_lid"];
$AddrType = $_GET['AddrType'];
$DocID = $_GET['DocID'];

$nowdate = date("Y-m-d");

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
$qry_name=mssql_query("select a.CusID,b.RadioID,(replace(a.PreName,' ','')+replace(a.Name,' ','')+' '+replace(a.SurName,' ','')) as fullname ,a.* from TacCusDtl as a
left join RadioDoc as b on a.CusID=b.CusID 
where a.CusID = '$cid' ");
$numrows = mssql_num_rows($qry_name);
while($res_name=mssql_fetch_array($qry_name)){
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_name["CusID"])); 
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res_name["RadioID"])); if(empty($RadioID)) $RadioID="ไม่พบข้อมูล";
    $fullname=trim(iconv('WINDOWS-874','UTF-8',$res_name["fullname"]));
	
	if($AddrType=="1"){
	if(trim($res_name["Add1No"])!="" && trim($res_name["Add1No"])!="-" && trim($res_name["Add1No"])!="--" && trim($res_name["Add1No"])!="---")$Add1No =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1No"])); 
	if(trim($res_name["Add1SubNo"])!="" && trim($res_name["Add1SubNo"])!="-" && trim($res_name["Add1SubNo"])!="--" && trim($res_name["Add1SubNo"])!="---")$Add1SubNo ="หมู่ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1SubNo"])); 
	if(trim($res_name["Add1Soi"])!="" && trim($res_name["Add1Soi"])!="-" && trim($res_name["Add1Soi"])!="--" && trim($res_name["Add1Soi"])!="---")$Add1Soi ="ซอย ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1Soi"])); 
	if(trim($res_name["Add1Rd"])!="" && trim($res_name["Add1Rd"])!="-" && trim($res_name["Add1Rd"])!="--" && trim($res_name["Add1Rd"])!="---")$Add1Rd ="ถนน ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1Rd"]));
	if(trim($res_name["Add1Tum"])!="" && trim($res_name["Add1Tum"])!="-" && trim($res_name["Add1Tum"])!="--" && trim($res_name["Add1Tum"])!="---")$Add1Tum ="แขวง/ตำบล ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1Tum"]));
	if(trim($res_name["Add1Aum"])!="" && trim($res_name["Add1Aum"])!="-" && trim($res_name["Add1Aum"])!="--" && trim($res_name["Add1Aum"])!="---")$Add1Aum  ="เขต/อำเภอ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1Aum"]));
	if(trim($res_name["Add1Prov"])!="" && trim($res_name["Add1Prov"])!="-" && trim($res_name["Add1Prov"])!="--" && trim($res_name["Add1Prov"])!="---")$Add1Prov ="จ. ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1Prov"]));
	if(trim($res_name["Add1AreaCode"])!="" && trim($res_name["Add1AreaCode"])!="-" && trim($res_name["Add1AreaCode"])!="--" && trim($res_name["Add1AreaCode"])!="---")
	$Add1AreaCode =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1AreaCode"]));
	
	$address1 = "$Add1No  $Add1SubNo $Add1Soi $Add1Rd ";
	$address2 = "$Add1Tum $Add1Aum ";
	$address3 = "$Add1Prov";
	$address4 = "$Add1AreaCode";
	}
	else if($AddrType=="2"){
	if(trim($res_name["Add2No"])!="" && trim($res_name["Add2No"])!="-" && trim($res_name["Add2No"])!="--" && trim($res_name["Add2No"])!="---")$Add2No =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2No"])); 
	if(trim($res_name["Add2SubNo"])!="" && trim($res_name["Add2SubNo"])!="-" && trim($res_name["Add2SubNo"])!="--" && trim($res_name["Add2SubNo"])!="---")$Add2SubNo ="หมู่ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2SubNo"])); 
	if(trim($res_name["Add2Soi"])!="" && trim($res_name["Add2Soi"])!="-" && trim($res_name["Add2Soi"])!="--" && trim($res_name["Add2Soi"])!="---")$Add2Soi ="ซอย ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2Soi"])); 
	if(trim($res_name["Add2Rd"])!="" && trim($res_name["Add2Rd"])!="-" && trim($res_name["Add2Rd"])!="--" && trim($res_name["Add2Rd"])!="---")$Add2Rd ="ถนน ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2Rd"]));
	if(trim($res_name["Add2Tum"])!="" && trim($res_name["Add2Tum"])!="-" && trim($res_name["Add2Tum"])!="--" && trim($res_name["Add2Tum"])!="---")$Add2Tum ="แขวง/ตำบล ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2Tum"]));
	if(trim($res_name["Add2Aum"])!="" && trim($res_name["Add2Aum"])!="-" && trim($res_name["Add2Aum"])!="--" && trim($res_name["Add2Aum"])!="---")$Add2Aum  ="เขต/อำเภอ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2Aum"]));
	if(trim($res_name["Add2Prov"])!="" && trim($res_name["Add2Prov"])!="-" && trim($res_name["Add2Prov"])!="--" && trim($res_name["Add2Prov"])!="---")$Add2Prov ="จ. ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2Prov"]));
	if(trim($res_name["Add2AreaCode"])!="" && trim($res_name["Add2AreaCode"])!="-" && trim($res_name["Add2AreaCode"])!="--" && trim($res_name["Add2AreaCode"])!="---")
	$Add2AreaCode =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2AreaCode"]));
	
	$address1 = "$Add2No  $Add2SubNo $Add2Soi $Add2Rd ";
	$address2 = "$Add2Tum $Add2Aum ";
	$address3 = "$Add2Prov";
	$address4 = "$Add2AreaCode";
	}
	else if($AddrType=="3"){
	if(trim($res_name["Add3No"])!="" && trim($res_name["Add3No"])!="-" && trim($res_name["Add3No"])!="--" && trim($res_name["Add3No"])!="---")$Add3No =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3No"])); 
	if(trim($res_name["Add3SubNo"])!="" && trim($res_name["Add3SubNo"])!="-" && trim($res_name["Add3SubNo"])!="--" && trim($res_name["Add3SubNo"])!="---")$Add3SubNo ="หมู่ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3SubNo"])); 
	if(trim($res_name["Add3Soi"])!="" && trim($res_name["Add3Soi"])!="-" && trim($res_name["Add3Soi"])!="--" && trim($res_name["Add3Soi"])!="---")$Add3Soi ="ซอย ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Soi"])); 
	if(trim($res_name["Add3Rd"])!="" && trim($res_name["Add3Rd"])!="-" && trim($res_name["Add3Rd"])!="--" && trim($res_name["Add3Rd"])!="---")$Add3Rd ="ถนน ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Rd"]));
	if(trim($res_name["Add3Tum"])!="" && trim($res_name["Add3Tum"])!="-" && trim($res_name["Add3Tum"])!="--" && trim($res_name["Add3Tum"])!="---")$Add3Tum ="แขวง/ตำบล ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Tum"]));
	if(trim($res_name["Add3Aum"])!="" && trim($res_name["Add3Aum"])!="-" && trim($res_name["Add3Aum"])!="--" && trim($res_name["Add3Aum"])!="---")$Add3Aum  ="เขต/อำเภอ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Aum"]));
	if(trim($res_name["Add3Prov"])!="" && trim($res_name["Add3Prov"])!="-" && trim($res_name["Add3Prov"])!="--" && trim($res_name["Add3Prov"])!="---")$Add3Prov ="จ. ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Prov"]));
	if(trim($res_name["Add3AreaCode"])!="" && trim($res_name["Add3AreaCode"])!="-" && trim($res_name["Add3AreaCode"])!="--" && trim($res_name["Add3AreaCode"])!="---")
	$Add3AreaCode =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3AreaCode"]));
	
	$address1 = "$Add3No  $Add3SubNo $Add3Soi $Add3Rd ";
	$address2 = "$Add3Tum $Add3Aum ";
	$address3 = "$Add3Prov";
	$address4 = "$Add3AreaCode";
	}
	   
		
	}


	$name=$fullname." ($DocID)\n";

	//$st_ads=$address1."\n".$address2."\n".$address3;
	
	//$ads=$st_ads;
	
	$arti="ส่ง";	
	$post = "คลองจั่น";
	
$pdf=new ThaiPDF('P' ,'mm','letter_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

 $pdf->AddPage();
 $pdf->SetFont('AngsanaNew','',14);
		$h =-5;
		$l = -1 ;
		//name
		$pdf->SetXY(17+$l,12+$h);
		$ta_name=iconv('UTF-8','windows-874',$name); 
		$pdf->MultiCell(180,8,$ta_name,0,'L',0);
		
		//address
		$pdf->SetXY(17+$l,19+$h);
		$ta_ads2=iconv('UTF-8','windows-874',$address1); 
		$pdf->MultiCell(100,8,$ta_ads2,0,'L',0);
		
		//address
		$pdf->SetXY(17+$l,26+$h);
		$ta_ads2=iconv('UTF-8','windows-874',$address2); 
		$pdf->MultiCell(100,8,$ta_ads2,0,'L',0);
		
		//address
		$pdf->SetXY(17+$l,32+$h);
		$ta_ads2=iconv('UTF-8','windows-874',$address3); 
		$pdf->MultiCell(100,8,$ta_ads2,0,'L',0);
		
		//post
		$pdf->SetXY(80+$l,32+$h);
		$ta_ads2=iconv('UTF-8','windows-874',$address4[0]); 
		$pdf->MultiCell(100,8,$ta_ads2,0,'L',0);
		
		//post
		$pdf->SetXY(85+$l,32+$h);
		$ta_ads2=iconv('UTF-8','windows-874',$address4[1]); 
		$pdf->MultiCell(100,8,$ta_ads2,0,'L',0);
		
		//post
		$pdf->SetXY(90+$l,32+$h);
		$ta_ads2=iconv('UTF-8','windows-874',$address4[2]); 
		$pdf->MultiCell(100,8,$ta_ads2,0,'L',0);
		
		//post
		$pdf->SetXY(95+$l,32+$h);
		$ta_ads2=iconv('UTF-8','windows-874',$address4[3]); 
		$pdf->MultiCell(100,8,$ta_ads2,0,'L',0);
		
		//post
		$pdf->SetXY(100+$l,32+$h);
		$ta_ads2=iconv('UTF-8','windows-874',$address4[4]); 
		$pdf->MultiCell(100,8,$ta_ads2,0,'L',0);
	
		//post
		$pdf->SetXY(50+$l,38+$h);
		$ta_post=iconv('UTF-8','windows-874',$post); 
		$pdf->MultiCell(95,8,$ta_post,0,'L',0);
		
		//day send
		$pdf->SetXY(17+$l,45+$h);
		$ta_day=iconv('UTF-8','windows-874',$day); 
		$pdf->MultiCell(180,8,$ta_day,0,'L',0);
		
		//month
		$pdf->SetXY(40+$l,45+$h);
		$ta_month=iconv('UTF-8','windows-874',$month); 
		$pdf->MultiCell(180,8,$ta_month,0,'L',0);
		
		 //year
		$pdf->SetXY(85+$l,45+$h);
		$ta_year=iconv('UTF-8','windows-874',$year); 
		$pdf->MultiCell(180,8,$ta_year,0,'L',0);
		
		
		
		
		
$pdf->Output();		
?>