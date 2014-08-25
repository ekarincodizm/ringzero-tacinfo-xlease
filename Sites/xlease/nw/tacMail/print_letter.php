<?php
session_start();
require('../../thaipdfclass.php');
include("../../config/config.php");
$s=mssql_select_db("Taxiacc") or die("Can't select database");

$cid=$_GET["cus_lid"];
$AddrType = $_GET['AddrType'];
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


	$name=$fullname."\n";

	$st_ads=$address1."\n".$address2."\n".$address3." ".$address4;
	
	$ads=$st_ads;
	
	$arti="ส่ง";		
	
	$com_addr1 = "บริษัท ไทยเอซ คอมมูนิเคชั่น จำกัด";	
	$com_addr2 = "555 อาคารไทยเอซ (ข้างซอยนวมินทร์ 54) ถนนนวมินทร์ ";	
	$com_addr3 = "แขวงคลองกุ่ม เขตบึงกุ่ม กรุงเทพฯ 10240";
	$com_addr4 = "โทร. 0-2744-2222 Fax. 0-2379-1111";		 
  
//}
/*else
{
$qry_print=pg_query("select A.*,B.*,C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\" from letter.\"SendDetail\" A
                     LEFT JOIN letter.\"cus_address\" B on A.\"address_id\" = B.\"address_id\"
					 LEFT JOIN \"Fa1\" C on B.\"CusID\" = C.\"CusID\"
					 WHERE A.\"auto_id\"='$cid' ");
	$res_print=pg_fetch_array($qry_print);

	$r_id=$res_print["auto_id"];
	//$name=trim($res_print["A_FIRNAME"]).trim($res_print["A_NAME"])." ".trim($res_print["A_SIRNAME"]);
	$ads=$res_print["address"];
	
	$arti="ส่ง";				 
					 
}*/
					 


$pdf=new ThaiPDF('P' ,'mm','letter_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

 $pdf->AddPage();
 //$pdf->Image('Letter_head.jpg',0,0,234,108);
 // $pdf->SetFont('AngsanaNew','',19);
		
  $col=10;	
		$pdf->SetFont('AngsanaNew','',19);
		$pdf->SetXY(50,47);
		$av_arti=iconv('UTF-8','windows-874',$arti); //ส่ง
		$pdf->MultiCell(180,8,$av_arti,0,'L',0);
		$pdf->SetFont('AngsanaNew','B',19);
		 
		$pdf->SetXY(75,55);
		$av_name=iconv('UTF-8','windows-874',$name); //ชื่อ
		$pdf->MultiCell(180,8,$av_name,0,'L',0);
		$pdf->SetFont('AngsanaNew','',19);
		$pdf->SetXY(75,63);
		$av_ads=iconv('UTF-8','windows-874',$ads); 
		$pdf->MultiCell(180,8,$av_ads,0,'L',0);
	
		
		
$pdf->Output();		
?>