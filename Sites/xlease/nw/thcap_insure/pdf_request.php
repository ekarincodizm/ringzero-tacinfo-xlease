<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

$auto_id=$_GET["auto_id"];
 
$qrytemp=pg_query("SELECT a.\"ContractID\", b.\"full_name\" as cus1, c.\"full_name\" as cus2, d.\"full_name\" as cus3, e.\"full_name\" as cus4, \"addrCus\", \"startDate\", 
	\"endDate\", \"userBenefit\", \"userNotify\", \"dateNotify\", \"securdeID\", \"checkchipID\", \"statusInsure\",\"addrDeed\"
	FROM thcap_insure_temp a
	left join \"VSearchCus\" b on a.\"CusID1\"=b.\"CusID\"
	left join \"VSearchCus\" c on a.\"CusID2\"=c.\"CusID\"
	left join \"VSearchCus\" d on a.\"CusID3\"=d.\"CusID\"
	left join \"VSearchCus\" e on a.\"CusID4\"=e.\"CusID\"
	where auto_id='$auto_id'");
list($ContractID,$cus1,$cus2,$cus3,$cus4,$addrCus,$startDate,$endDate,$userBenefit,$userNotify,$dateNotify,$securdeID,$checkchipID,$statusInsure,$addrDeed)=pg_fetch_array($qrytemp);


//หาข้อมูลค่าเบี้ยมาแสดง		
	$qrychip=pg_query("SELECT \"refDeedContract\",\"costBuilding\", \"costFurniture\", \"costEngine\", 
	\"costStock\", \"textOther\", \"costOther\", \"insureSpecial\", \"totalChip\", 
	\"numberQ\" FROM thcap_insure_checkchip where auto_id='$checkchipID'");
	list($refDeedContract,$costBuilding, $costFurniture, $costEngine, $costStock, $textOther, $costOther, $insureSpecial, $totalChip, $numberQ)=pg_fetch_array($qrychip);
	$summoney=$costBuilding+$costFurniture+$costEngine+$costStock+$costOther;

	//หาค่า $refDeedContract ได้ดังนี้
	if($statusInsure!="0"){
		$qrysecur=pg_query("select \"securID\",\"addrDeed\" from \"thcap_insure_main\" a
		left join thcap_insure_temp b on a.\"auto_tempID\"=b.\"auto_id\"
		left join \"nw_securities_detail\" c on b.\"securdeID\"=c.\"securdeID\"
		where \"ContractID\"='$ContractID'");
		
		list($refDeedContract,$addrDeed2)=pg_fetch_array($qrysecur);
	}
	
//ดึงรายละเอียดในส่วนของ checker
	$qrychecker=pg_query("SELECT \"securdeID\", feature, feature_other, height, address, 
		wall_brick, wall_wood_brick, wall_wood, wall_other, wall_other_detail, 
		ground_top_con, ground_top_wood, ground_top_parquet, ground_top_ceramic, ground_top_other, ground_top_other_detail, 
		roof_frame_iron, roof_frame_con, roof_frame_wood, roof_frame_unknow, roof_frame_other, roof_frame_other_detail, 
		roof_zine, roof_deck, roof_tile_duo, roof_tile_monern, roof_other, roof_other_detail, 
		quan_cave, quan_unit, quan_room,quan_floor, floor_number, build_inside_area, 
		useful_home, useful_commerce, useful_rent, useful_stored, useful_industry, useful_agriculture, useful_other, useful_other_detail
		FROM nw_securities_detail where \"securID\"='$refDeedContract'");
	list($securdeID, $feature, $feature_other, $height, $address,
		$wall_brick, $wall_wood_brick, $wall_wood, $wall_other, $wall_other_detail, 
		$ground_top_con, $ground_top_wood, $ground_top_parquet, $ground_top_ceramic, $ground_top_other, $ground_top_other_detail, 
		$roof_frame_iron, $roof_frame_con, $roof_frame_wood, $roof_frame_unknow, $roof_frame_other, $roof_frame_other_detail, 
		$roof_zine, $roof_deck, $roof_tile_duo, $roof_tile_monern, $roof_other, $roof_other_detail, 
		$quan_cave, $quan_unit, $quan_room,$quan_floor, $floor_number, $build_inside_area, 
		$useful_home, $useful_commerce, $useful_rent, $useful_stored, $useful_industry, $useful_agriculture, $useful_other, $useful_other_detail)=pg_fetch_array($qrychecker);	
if($statusInsure=="0"){ //กรณีเป็นประกันใหม่
	$qrynum=pg_query("SELECT \"INNUM\" FROM thcap_insure_new where auto_id='$auto_id'");
	list($requestnum)=pg_fetch_array($qrynum);
}else{
	$qrynum=pg_query("SELECT \"RENUM\" FROM thcap_insure_old where auto_id='$auto_id'");
	list($requestnum)=pg_fetch_array($qrynum);
}
$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();


$pdf->SetFont('AngsanaNew','B',20);
$pdf->SetXY(10,8);
$title=iconv('UTF-8','windows-874',"ใบคำขอประกันอัคคีภัย (อย่างย่อ)");
$pdf->MultiCell(190,4,$title,0,'C',0);
	
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,14);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(190,5,$title,1,'R',0);  

$pdf->SetXY(80,14);
$title=iconv('UTF-8','windows-874',"เลขที่รับแจ้ง    $requestnum");
$pdf->MultiCell(100,5,$title,0,'R',0);   

$pdf->SetXY(20,14);
$title=iconv('UTF-8','windows-874',"ต่ออายุ");
$pdf->MultiCell(50,5,$title,0,'C',0); 

$pdf->SetXY(50,14);
$title=iconv('UTF-8','windows-874',"ประกันใหม่");
$pdf->MultiCell(40,5,$title,0,'C',0); 

if($statusInsure=="0"){
	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(52,14.5);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(5,4,$title,1,'C',0); 
	
	$pdf->SetXY(80,14.5);
	$title=iconv('UTF-8','windows-874',"X");
	$pdf->MultiCell(5,4,$title,1,'C',0); 
}else{
	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(52,14.5);
	$title=iconv('UTF-8','windows-874',"X");
	$pdf->MultiCell(5,4,$title,1,'C',0);
	
	$pdf->SetXY(80,14.5);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(5,4,$title,1,'C',0);  
}
$pdf->SetFont('AngsanaNew','',12);
//ข้อ 1
$pdf->SetXY(10,19);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(120,24,$title,1,'C',0); 

	//ชื่อผู้เอาประกันภัย
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(10,20);
	$title=iconv('UTF-8','windows-874',"1. ชื่อผู้เอาประกันภัย");
	$pdf->MultiCell(99,5,$title,0,'L',0);
	
	$pdf->SetXY(35,20);
	$title=iconv('UTF-8','windows-874',"1. $cus1");
	$pdf->MultiCell(99,5,$title,0,'L',0);
	
	if($cus2!=""){
		$pdf->SetXY(35,25);
		$title=iconv('UTF-8','windows-874',"2. $cus2");
		$pdf->MultiCell(99,5,$title,0,'L',0);
	}
	
	if($cus3!=""){
		$pdf->SetXY(83,20);
		$title=iconv('UTF-8','windows-874',"3. $cus3");
		$pdf->MultiCell(99,5,$title,0,'L',0);
	}
	
	if($cus4!=""){
		$pdf->SetXY(83,25);
		$title=iconv('UTF-8','windows-874',"4. $cus4");
		$pdf->MultiCell(99,5,$title,0,'L',0);
	}
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(10,27);
	$title=iconv('UTF-8','windows-874',"..................................................................................................................................................................");
	$pdf->MultiCell(120,4,$title,0,'C',0);
	
	//ที่อยู่
	$pdf->SetXY(10,30);
	$title=iconv('UTF-8','windows-874',"ที่อยู่");
	$pdf->MultiCell(99,5,$title,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','B',10);
	$pdf->SetXY(20,30);
	$title=iconv('UTF-8','windows-874',"$addrCus");
	$pdf->MultiCell(105,4,$title,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(130,19);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(70,24,$title,1,'C',0); 

	//สถานที่ตั้งทรัพย์สินที่เอาประกันภัย
	$pdf->SetXY(130,19);
	$title=iconv('UTF-8','windows-874',"สถานที่ตั้งทรัพย์สินที่เอาประกันภัย");
	$pdf->MultiCell(99,5,$title,0,'L',0);
	
	$pdf->SetXY(132,24);
	$title=iconv('UTF-8','windows-874',"$addrDeed");
	$pdf->MultiCell(65,5,$title,0,'L',0);
	
//ข้อ 2
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,43);
$title=iconv('UTF-8','windows-874',"2. ระยะเวลาประกันภัย      เริ่มวันที่                                       เวลา 16.00   สิ้นสุดวันที่                                       เวลา 16.00 น.");
$pdf->MultiCell(190,5,$title,1,'L',0);  

list($y1,$m1,$d1)=explode("-",$startDate);
$y1=$y1+543;
$startDate=$d1."/".$m1."/".$y1;
list($y2,$m2,$d2)=explode("-",$endDate);
$y2=$y2+543;
$endDate=$d2."/".$m2."/".$y2;

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,43);
$title=iconv('UTF-8','windows-874',"                                                                  $startDate                                                           $endDate");
$pdf->MultiCell(190,5,$title,0,'L',0); 

//ข้อ 3
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,48);
$title=iconv('UTF-8','windows-874',"3. จำนวนเงินเอาประกันภัยตามกรมธรรม์ฉบับนี้");
$pdf->MultiCell(190,5,$title,1,'L',0); 
	//ฝั่งซ้าย
	$pdf->SetXY(10,53);
	$title=iconv('UTF-8','windows-874',"เบี้ยประกันภัย");
	$pdf->MultiCell(95,5,$title,1,'C',0); 
	
	$pdf->SetXY(10,58);
	$title=iconv('UTF-8','windows-874',"อัตรา");
	$pdf->MultiCell(25,5,$title,1,'C',0); 
		$pdf->SetXY(10,63);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(25,5,$title,1,'C',0);
	
	$pdf->SetXY(35,58);
	$title=iconv('UTF-8','windows-874',"อัตราเพิ่ม");
	$pdf->MultiCell(20,5,$title,1,'C',0); 
		$pdf->SetXY(35,63);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(20,5,$title,1,'C',0);
	
	$pdf->SetXY(55,58);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(25,10,$title,1,'C',0); 
	
	$pdf->SetXY(80,58);
	$title=iconv('UTF-8','windows-874',"จำนวนเงิน");
	$pdf->MultiCell(25,5,$title,1,'C',0); 
		$pdf->SetXY(80,63);
		$title=iconv('UTF-8','windows-874',"บาท");
		$pdf->MultiCell(25,5,$title,1,'R',0);
		
	$pdf->SetXY(10,68);
	$title=iconv('UTF-8','windows-874',"เบี้ยประกันภัยสุทธิ                     บาท");
	$pdf->MultiCell(47.5,5,$title,1,'L',0); 
	
	$pdf->SetXY(57.5,68);
	$title=iconv('UTF-8','windows-874',"อากรแสตมป์                            บาท");
	$pdf->MultiCell(47.5,5,$title,1,'L',0);
	
	//ฝั่งขวา
	$pdf->SetXY(105,53);
	$title=iconv('UTF-8','windows-874',"ส่วนลดอุปกรณ์ดับเพลิง");
	$pdf->MultiCell(35,5,$title,1,'C',0);
		$pdf->SetXY(105,58);
		$title=iconv('UTF-8','windows-874',"อัตรา");
		$pdf->MultiCell(35,5,$title,1,'C',0);
		
		$pdf->SetXY(105,63);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(35,5,$title,1,'C',0);

	$pdf->SetXY(140,53);
	$title=iconv('UTF-8','windows-874',"เบี้ยประกันภัยเพิ่มพิเศษ");
	$pdf->MultiCell(60,5,$title,1,'C',0);
		$pdf->SetXY(140,58);
		$title=iconv('UTF-8','windows-874',"อัตรา");
		$pdf->MultiCell(20,5,$title,1,'C',0);
			$pdf->SetXY(140,58);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,5,$title,1,'C',0);
		
		$pdf->SetXY(160,58);
		$title=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(40,5,$title,1,'C',0);
		
		$pdf->SetXY(160,63);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(40,5,$title,1,'C',0);
		
		$pdf->SetXY(105,68);
		$title=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม                            บาท");
		$pdf->MultiCell(47.5,5,$title,1,'C',0);
		
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(152.5,68);
		$title=iconv('UTF-8','windows-874',"รวม");
		$pdf->MultiCell(47.5,5,$title,1,'L',0);
		
		$pdf->SetXY(152.5,68);
		$title=iconv('UTF-8','windows-874',number_format($totalChip,2));
		$pdf->MultiCell(47.5,5,$title,0,'R',0);

//ข้อ 4
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,73);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(190,65,$title,1,'C',0);

$pdf->SetXY(10,73);
$title=iconv('UTF-8','windows-874',"4. จำนวนเงินเอาประกันภัยทั้งสิ้น");
$pdf->MultiCell(100,5,$title,0,'L',0);

$pdf->SetXY(105,73);
$title=iconv('UTF-8','windows-874',"งวดนี้");
$pdf->MultiCell(50,5,$title,0,'C',0);
$pdf->SetXY(105,73);
$title=iconv('UTF-8','windows-874',"_____");
$pdf->MultiCell(50,5,$title,0,'C',0);

$pdf->SetXY(135,73);
$title=iconv('UTF-8','windows-874',"งวดก่อน");
$pdf->MultiCell(50,5,$title,0,'C',0);
$pdf->SetXY(135,73);
$title=iconv('UTF-8','windows-874',"_______");
$pdf->MultiCell(50,5,$title,0,'C',0);

	//สิ่งปลูกสร้าง
	if($costBuilding!=""){
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(20,78);
		$title=iconv('UTF-8','windows-874',"X");
		$pdf->MultiCell(5,4,$title,1,'C',0);
	}else{
		$pdf->SetXY(20,78);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(5,4,$title,1,'C',0);
	}
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(25,78);
	$title=iconv('UTF-8','windows-874',"- สิ่งปลูกสร้าง (รากฐานฯไม่รวม)");
	$pdf->MultiCell(100,5,$title,0,'L',0); 
	
	if($costBuilding>0){
		$pdf->SetFont('AngsanaNew','B',12);
	}else{
		$pdf->SetFont('AngsanaNew','',12);
	}
	$pdf->SetXY(105,78);
	$title=iconv('UTF-8','windows-874',number_format($costBuilding,2));
	$pdf->MultiCell(30,5,$title,0,'R',0);
	
	if($numberQ==""){
		$numberQ="ไม่มี";
	}else{
		$numberQ=$numberQ;
	}
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(160,78);
	$title=iconv('UTF-8','windows-874',"เลขคิว   $numberQ");
	$pdf->MultiCell(100,5,$title,0,'L',0);
	
	//เฟอร์นิเจอร์
	if($costFurniture!=""){
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(20,83);
		$title=iconv('UTF-8','windows-874',"X");
		$pdf->MultiCell(5,4,$title,1,'C',0);
	}else{
		$pdf->SetXY(20,83);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(5,4,$title,1,'C',0);
	}
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(25,83);
	$title=iconv('UTF-8','windows-874',"- เฟอร์นิเจอร์ เครื่องตกแต่งติดตั้งตรึงตรา และของใช้ต่างๆ");
	$pdf->MultiCell(100,5,$title,0,'L',0); 
	
	if($costFurniture>0){
		$pdf->SetFont('AngsanaNew','B',12);
	}else{
		$pdf->SetFont('AngsanaNew','',12);
	}
	$pdf->SetXY(105,83);
	$title=iconv('UTF-8','windows-874',number_format($costFurniture,2));
	$pdf->MultiCell(30,5,$title,0,'R',0);
	
	//เครื่องจักร
	if($costEngine!=""){
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(20,88);
		$title=iconv('UTF-8','windows-874',"X");
		$pdf->MultiCell(5,4,$title,1,'C',0);
	}else{
		$pdf->SetXY(20,88);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(5,4,$title,1,'C',0);
	}
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(25,88);
	$title=iconv('UTF-8','windows-874',"- เครื่องจักร");
	$pdf->MultiCell(100,5,$title,0,'L',0); 
	
	if($costEngine>0){
		$pdf->SetFont('AngsanaNew','B',12);
	}else{
		$pdf->SetFont('AngsanaNew','',12);
	}
	$pdf->SetXY(105,88);
	$title=iconv('UTF-8','windows-874',number_format($costEngine,2));
	$pdf->MultiCell(30,5,$title,0,'R',0);
	
	//สต๊อกสินค้า
	if($costStock!=""){
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(20,93);
		$title=iconv('UTF-8','windows-874',"X");
		$pdf->MultiCell(5,4,$title,1,'C',0);
	}else{
		$pdf->SetXY(20,93);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(5,4,$title,1,'C',0);
	}
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(25,93);
	$title=iconv('UTF-8','windows-874',"- สต๊อกสินค้า");
	$pdf->MultiCell(100,5,$title,0,'L',0); 
	
	if($costStock>0){
		$pdf->SetFont('AngsanaNew','B',12);
	}else{
		$pdf->SetFont('AngsanaNew','',12);
	}
	$pdf->SetXY(105,93);
	$title=iconv('UTF-8','windows-874',number_format($costStock,2));
	$pdf->MultiCell(30,5,$title,0,'R',0);
	
	//อื่นๆ
	if($costOther!=""){
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(20,98);
		$title=iconv('UTF-8','windows-874',"X");
		$pdf->MultiCell(5,4,$title,1,'C',0);
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(25,98);
		$title=iconv('UTF-8','windows-874',"- $textOther");
		$pdf->MultiCell(100,5,$title,0,'L',0); 
	}else{
		$pdf->SetXY(20,98);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(5,4,$title,1,'C',0);
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(25,98);
		$title=iconv('UTF-8','windows-874',"- อื่นๆ...");
		$pdf->MultiCell(100,5,$title,0,'L',0); 
	}
	
	if($costOther>0){
		$pdf->SetFont('AngsanaNew','B',12);
	}else{
		$pdf->SetFont('AngsanaNew','',12);
	}
	$pdf->SetXY(105,98);
	$title=iconv('UTF-8','windows-874',number_format($costOther,2));
	$pdf->MultiCell(30,5,$title,0,'R',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(25,103);
	$title=iconv('UTF-8','windows-874',"รวมทุนประกันภัยทั้งสิ้น");
	$pdf->MultiCell(80,5,$title,0,'R',0); 
	
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(105,103);
	$title=iconv('UTF-8','windows-874',number_format($summoney,2));
	$pdf->MultiCell(30,5,$title,0,'R',0);
	
	//ภัยเพิ่มพิเศษ
	if($insureSpecial!=""){
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(25,108);
		$title=iconv('UTF-8','windows-874',"ภัยเพิ่มพิเศษ");
		$pdf->MultiCell(25,5,$title,0,'L',0);
		
		$pdf->SetXY(45,108);
		$title=iconv('UTF-8','windows-874',$insureSpecial);
		$pdf->MultiCell(100,5,$title,0,'L',0);
	}
	
	//ผู้รับผลประโยชน์
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(25,128);
	$title=iconv('UTF-8','windows-874',"ผู้รับผลประโยชน์");
	$pdf->MultiCell(25,5,$title,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(50,128);
	$title=iconv('UTF-8','windows-874',$userBenefit);
	$pdf->MultiCell(100,5,$title,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(25,133);
	$title=iconv('UTF-8','windows-874',"เงื่อนไขพิเศษ................................................................................................");
	$pdf->MultiCell(100,5,$title,0,'L',0);

//ข้อ 5
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,138);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(190,10,$title,1,'C',0);

	$pdf->SetXY(10,138);
	$title=iconv('UTF-8','windows-874',"5. จำนวนเงินเอาประกันภัยและบริษัทประกันร่วม");
	$pdf->MultiCell(100,5,$title,0,'L',0);

	$pdf->SetXY(105,138);
	$title=iconv('UTF-8','windows-874',"งวดนี้");
	$pdf->MultiCell(50,5,$title,0,'C',0);
	$pdf->SetXY(105,138);
	$title=iconv('UTF-8','windows-874',"_____");
	$pdf->MultiCell(50,5,$title,0,'C',0);

	$pdf->SetXY(155,138);
	$title=iconv('UTF-8','windows-874',"งวดก่อน");
	$pdf->MultiCell(50,5,$title,0,'C',0);
	$pdf->SetXY(155,138);
	$title=iconv('UTF-8','windows-874',"_______");
	$pdf->MultiCell(50,5,$title,0,'C',0);

	$pdf->SetXY(30,143);
	$title=iconv('UTF-8','windows-874',"........................................................................................................................................................................................................................................");
	$pdf->MultiCell(190,5,$title,0,'L',0);

	

//ข้อ 6
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,148);
$title=iconv('UTF-8','windows-874',"6. รายละเอียดของสิ่งปลูกสร้างที่เอาประกันและหรือที่เก็บหรือติดตั้งทรัพย์สินที่เอาประกันภัย");
$pdf->MultiCell(190,5,$title,1,'L',0);

	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(140,148.5);
	$title=iconv('UTF-8','windows-874',"X");
	$pdf->MultiCell(5,4,$title,1,'C',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(146,148);
	$title=iconv('UTF-8','windows-874',"เป็นเจ้าของ");
	$pdf->MultiCell(20,5,$title,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(170,148.5);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(5,4,$title,1,'C',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(175,148);
	$title=iconv('UTF-8','windows-874',"เป็นผู้เช่า");
	$pdf->MultiCell(20,5,$title,0,'L',0);
	
	//ข้อมูลของ checker
	$pdf->SetXY(10,153);
	$title=iconv('UTF-8','windows-874',"จำนวนชั้น");
	$pdf->MultiCell(33,5,$title,1,'C',0);
	
	$pdf->SetXY(43,153);
	$title=iconv('UTF-8','windows-874',"ฝาผนังด้านนอกเป็น");
	$pdf->MultiCell(31,5,$title,1,'C',0);
	
	$pdf->SetXY(74,153);
	$title=iconv('UTF-8','windows-874',"พื้นชั้นบนเป็น");
	$pdf->MultiCell(31,5,$title,1,'C',0);
	
	$pdf->SetXY(105,153);
	$title=iconv('UTF-8','windows-874',"โครงหลังคาเป็น");
	$pdf->MultiCell(33,5,$title,1,'C',0);
	
	$pdf->SetXY(138,153);
	$title=iconv('UTF-8','windows-874',"หลังคาเป็น");
	$pdf->MultiCell(33,5,$title,1,'C',0);
	
	$pdf->SetXY(171,153);
	$title=iconv('UTF-8','windows-874',"จำนวนคูหา/หลัง");
	$pdf->MultiCell(29,5,$title,1,'C',0);

if($useful_home=="1"){
	$txtuse="ที่อยู่อาศัย";
}else if($useful_commerce=="1"){
	$txtuse="พาณิชยกรรม";
}else if($useful_rent=="1"){
	$txtuse="ให้เช่า";
}else if($useful_stored=="1"){
	$txtuse="เก็บไว้เฉยๆ";
}else if($useful_industry=="1"){
	$txtuse="อุตสาหกรรม";
}else if($useful_agriculture=="1"){
	$txtuse="เกษตรกรรม";
}else if($useful_other=="1"){
	$txtuse=$useful_other_detail;
}

list($before,$behide)=explode(".",$height);
if($behide=="00"){
	$height2=$before;
}else{
	$height2=$height;
}
if($feature=="1"){
	$txtfeature="ตึกแถว $height2 ชั้น";
}else if($feature=="2"){
	$txtfeature="ทาวน์เฮ้าส์ $height2 ชั้น";
}else if($feature=="3"){
	$txtfeature="บ้านเดี่ยวตึก $height2 ชั้น";
}else if($feature=="4"){
	$txtfeature="บ้านแฝด $height2 ชั้น";
}else if($feature=="5"){
	$txtfeature="อาคารพาณิชย์ $height2 ชั้น";
}else{
	$txtfeature="$feature_other $height2 ชั้น";
}
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(10,158);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(33,26,$title,1,'C',0);
	$pdf->SetXY(10,158);
	$title=iconv('UTF-8','windows-874',$txtfeature);
	$pdf->MultiCell(33,5,$title,0,'C',0);
	
	//กรณีฝาผนังด้านนอก
	$pdf->SetXY(43,158);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(31,26,$title,1,'C',0);
		
		$pdf->SetFont('AngsanaNew','B',14);
		if($wall_brick=="1"){
			$pdf->SetXY(44,159);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(44,159);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(50,159);
		$title=iconv('UTF-8','windows-874',"ก่ออิฐฯ");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//ก่ออิฐฯ/ไม้
		$pdf->SetFont('AngsanaNew','B',14);
		if($wall_wood_brick=="1"){
			$pdf->SetXY(44,164);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(44,164);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(50,164);
		$title=iconv('UTF-8','windows-874',"ก่ออิฐฯ/ไม้");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//ไม้
		$pdf->SetFont('AngsanaNew','B',14);
		if($wall_wood=="1"){
			$pdf->SetXY(44,169);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(44,169);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(50,169);
		$title=iconv('UTF-8','windows-874',"ไม้");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//อื่นๆ
		$pdf->SetFont('AngsanaNew','B',14);
		if($wall_other=="1"){
			$pdf->SetXY(44,174);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(50,174);
			$title=iconv('UTF-8','windows-874',"$wall_other_detail");
			$pdf->MultiCell(20,5,$title,0,'L',0);
		}else{
			$pdf->SetXY(44,174);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(50,174);
			$title=iconv('UTF-8','windows-874',"อื่นๆ...");
			$pdf->MultiCell(20,4,$title,0,'L',0);
		}	
	//พื้นชั้นบนเป็น
	$pdf->SetXY(74,158);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(31,26,$title,1,'C',0);	
		$pdf->SetFont('AngsanaNew','B',14);
		if($ground_top_con=="1"){
			$pdf->SetXY(75,159);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(75,159);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(81,159);
		$title=iconv('UTF-8','windows-874',"คอนกรีต");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//ไม้
		$pdf->SetFont('AngsanaNew','B',14);
		if($ground_top_wood=="1"){
			$pdf->SetXY(75,164);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(75,164);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(80,164);
		$title=iconv('UTF-8','windows-874',"ไม้");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//ปาเก้
		$pdf->SetFont('AngsanaNew','B',14);
		if($ground_top_parquet=="1"){
			$pdf->SetXY(75,169);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(75,169);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(80,169);
		$title=iconv('UTF-8','windows-874',"ปาเก้");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//เซรามิค
		$pdf->SetFont('AngsanaNew','B',14);
		if($ground_top_ceramic=="1"){
			$pdf->SetXY(75,174);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(75,174);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(80,174);
		$title=iconv('UTF-8','windows-874',"เซรามิค");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//อื่นๆ
		$pdf->SetFont('AngsanaNew','B',14);
		if($ground_top_other=="1"){
			$pdf->SetXY(75,179);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(80,179);
			$title=iconv('UTF-8','windows-874',"$ground_top_other_detail");
			$pdf->MultiCell(20,5,$title,0,'L',0);
		}else{
			$pdf->SetXY(75,179);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(80,179);
			$title=iconv('UTF-8','windows-874',"อื่นๆ...");
			$pdf->MultiCell(20,5,$title,0,'L',0);
		}	
	//โครงหลังคาเป็น
	$pdf->SetXY(105,158);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(33,26,$title,1,'C',0);
	$pdf->SetFont('AngsanaNew','B',14);
		if($roof_frame_iron=="1"){
			$pdf->SetXY(106,159);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(106,159);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(112,159);
		$title=iconv('UTF-8','windows-874',"เหล็ก");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//คอนกรีต
		$pdf->SetFont('AngsanaNew','B',14);
		if($roof_frame_con=="1"){
			$pdf->SetXY(106,164);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(106,164);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(112,164);
		$title=iconv('UTF-8','windows-874',"คอนกรีต");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//ไม้
		$pdf->SetFont('AngsanaNew','B',14);
		if($roof_frame_wood=="1"){
			$pdf->SetXY(106,169);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(106,169);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(112,169);
		$title=iconv('UTF-8','windows-874',"ไม้");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//ไม่สามารถตรวจสอบได้
		$pdf->SetFont('AngsanaNew','B',14);
		if($roof_frame_unknow=="1"){
			$pdf->SetXY(106,174);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(106,174);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(112,174);
		$title=iconv('UTF-8','windows-874',"ตรวจสอบไม่ได้");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//อื่นๆ
		$pdf->SetFont('AngsanaNew','B',14);
		if($roof_frame_other=="1"){
			$pdf->SetXY(106,179);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(112,179);
			$title=iconv('UTF-8','windows-874',"$roof_frame_other_detail");
			$pdf->MultiCell(20,5,$title,0,'L',0);
		}else{
			$pdf->SetXY(106,179);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(112,179);
			$title=iconv('UTF-8','windows-874',"อื่นๆ...");
			$pdf->MultiCell(20,5,$title,0,'L',0);
		}	
	
	//หลังคาเป็น
	$pdf->SetXY(138,158);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(33,26,$title,1,'C',0);
		$pdf->SetFont('AngsanaNew','B',14);
		if($roof_zine=="1"){
			$pdf->SetXY(139,159);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(139,159);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(145,159);
		$title=iconv('UTF-8','windows-874',"สังกะสี");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//ดาดฟ้า
		$pdf->SetFont('AngsanaNew','B',14);
		if($roof_deck=="1"){
			$pdf->SetXY(139,164);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(139,164);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(145,164);
		$title=iconv('UTF-8','windows-874',"ดาดฟ้า");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//กระเบื้องลอนคู่
		$pdf->SetFont('AngsanaNew','B',14);
		if($roof_tile_duo=="1"){
			$pdf->SetXY(139,169);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(139,169);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(145,169);
		$title=iconv('UTF-8','windows-874',"กระเบื้องลอนคู่");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//กระเบื้องโมเนียร์
		$pdf->SetFont('AngsanaNew','B',14);
		if($roof_tile_monern=="1"){
			$pdf->SetXY(139,174);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}else{
			$pdf->SetXY(139,174);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
		}	
		$pdf->SetFont('AngsanaNew','',11);
		$pdf->SetXY(145,174);
		$title=iconv('UTF-8','windows-874',"กระเบื้องโมเนียร์");
		$pdf->MultiCell(20,5,$title,0,'L',0);
		
		//อื่นๆ
		$pdf->SetFont('AngsanaNew','B',14);
		if($roof_other=="1"){
			$pdf->SetXY(139,179);
			$title=iconv('UTF-8','windows-874',"X");
			$pdf->MultiCell(5,4,$title,1,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(145,179);
			$title=iconv('UTF-8','windows-874',"$roof_other_detail");
			$pdf->MultiCell(20,5,$title,0,'L',0);
		}else{
			$pdf->SetXY(139,179);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(5,4,$title,1,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(145,179);
			$title=iconv('UTF-8','windows-874',"อื่นๆ...");
			$pdf->MultiCell(20,5,$title,0,'L',0);
		}	
	
	//จำนวนคูหาXหลัง
	$pdf->SetXY(171,158);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(29,26,$title,1,'C',0);
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(172,159);
			$title=iconv('UTF-8','windows-874',"..........................คูหา");
			$pdf->MultiCell(33,5,$title,0,'L',0);
			
			$pdf->SetXY(172,158);
			$title=iconv('UTF-8','windows-874',"$quan_cave");
			$pdf->MultiCell(20,5,$title,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(172,164);
			$title=iconv('UTF-8','windows-874',"..........................หลัง");
			$pdf->MultiCell(33,5,$title,0,'L',0);
			
			$pdf->SetXY(172,163);
			$title=iconv('UTF-8','windows-874',$quan_unit);
			$pdf->MultiCell(20,5,$title,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(172,169);
			$title=iconv('UTF-8','windows-874',"..........................ห้อง");
			$pdf->MultiCell(33,5,$title,0,'L',0);
			
			$pdf->SetXY(172,168);
			$title=iconv('UTF-8','windows-874',$quan_room);
			$pdf->MultiCell(20,5,$title,0,'C',0);
	
	$pdf->SetXY(10,184);
	$title=iconv('UTF-8','windows-874',"พื้นที่ภายในอาคาร");
	$pdf->MultiCell(33,5,$title,1,'C',0);
	
	$pdf->SetXY(43,184);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(31,10,$title,1,'C',0);
	
	$pdf->SetXY(43,184);
	$title=iconv('UTF-8','windows-874',"รหัสโครงสร้าง");
	$pdf->MultiCell(31,5,$title,0,'C',0);
	
	$pdf->SetXY(74,184);
	$title=iconv('UTF-8','windows-874',"ฝาผนัง");
	$pdf->MultiCell(31,5,$title,1,'C',0);
	
	$pdf->SetXY(105,184);
	$title=iconv('UTF-8','windows-874',"เสา/กำแพงรับแรง");
	$pdf->MultiCell(33,5,$title,1,'C',0);
	
	$pdf->SetXY(138,184);
	$title=iconv('UTF-8','windows-874',"คาน");
	$pdf->MultiCell(33,5,$title,1,'C',0);
	
	$pdf->SetXY(171,184);
	$title=iconv('UTF-8','windows-874',"พื้น");
	$pdf->MultiCell(29,5,$title,1,'C',0);
		
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(10,189);
		$title=iconv('UTF-8','windows-874',$build_inside_area." ตรม.");
		$pdf->MultiCell(33,5,$title,1,'C',0);
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(74,189);
		$title=iconv('UTF-8','windows-874',"รหัส");
		$pdf->MultiCell(13,5,$title,1,'C',0);
			$pdf->SetXY(87,189);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(18,5,$title,1,'C',0);
		
		$pdf->SetXY(105,189);
		$title=iconv('UTF-8','windows-874',"รหัส");
		$pdf->MultiCell(13,5,$title,1,'C',0);
			$pdf->SetXY(118,189);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,5,$title,1,'C',0);
		
		$pdf->SetXY(138,189);
		$title=iconv('UTF-8','windows-874',"รหัส");
		$pdf->MultiCell(13,5,$title,1,'C',0);
			$pdf->SetXY(151,189);
			$title=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,5,$title,1,'C',0);
		
		$pdf->SetXY(171,189);
		$title=iconv('UTF-8','windows-874',"รหัส");
		$pdf->MultiCell(29,5,$title,1,'C',0);
//ข้อ 7
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,194);
$title=iconv('UTF-8','windows-874',"7. สถานที่ใช้เป็น                                                                                             รหัส : ภัยตัวเอง                     ภัยนอก                     ชั้นของสิ่งปลูกสร้าง");
$pdf->MultiCell(190,5,$title,1,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(40,194);
$title=iconv('UTF-8','windows-874',"$txtuse");
$pdf->MultiCell(190,5,$title,0,'L',0);

//ข้อ 8
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,199);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(190,16,$title,1,'L',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(10,199);
	$title=iconv('UTF-8','windows-874',"8. กรมธรรม์ประกันภัยนี้มีเอกสารแนบท้าย");
	$pdf->MultiCell(190,5,$title,0,'L',0);

	$pdf->SetXY(60,199);
	$title=iconv('UTF-8','windows-874',"อค........................................................................................................................");
	$pdf->MultiCell(190,5,$title,0,'L',0);

	$pdf->SetXY(60,204);
	$title=iconv('UTF-8','windows-874',"อค./ทส.................................................................................................................");
	$pdf->MultiCell(190,5,$title,0,'L',0);

	$pdf->SetXY(60,209);
	$title=iconv('UTF-8','windows-874',"ข้อรับรองที่...........................................................................................................");
	$pdf->MultiCell(190,5,$title,0,'L',0);
	

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,215);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(50,24,$title,1,'L',0);
	$pdf->SetXY(15,217);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(5,3,$title,1,'C',0);
	
	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(15,216.5);
	$title=iconv('UTF-8','windows-874',"X");
	$pdf->MultiCell(5,3,$title,0,'C',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(22,216);
	$title=iconv('UTF-8','windows-874',"ตัวแทน");
	$pdf->MultiCell(30,5,$title,0,'L',0);
	$pdf->SetXY(22,216);
	$title=iconv('UTF-8','windows-874',"______");
	$pdf->MultiCell(30,5,$title,0,'L',0);
	
	$pdf->SetXY(11,221);
	$title=iconv('UTF-8','windows-874',"รหัส   1014000");
	$pdf->MultiCell(50,6,$title,0,'L',0);
	
	$pdf->SetXY(11,227);
	$title=iconv('UTF-8','windows-874',"ชื่อ บริษัท ไทยเอซ ลิสซิ่ง จำกัด");
	$pdf->MultiCell(50,6,$title,0,'L',0);
	
	$pdf->SetXY(11,233);
	$title=iconv('UTF-8','windows-874',"โทร 02-744-2222 ต่อ 2354");
	$pdf->MultiCell(50,6,$title,0,'L',0);
	
$pdf->SetXY(60,215);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(90,24,$title,1,'L',0);
	$pdf->SetXY(61,217);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(3,3,$title,1,'C',0);
	
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(66,216);
	$title=iconv('UTF-8','windows-874',"นายหน้าประกันภัย");
	$pdf->MultiCell(100,5,$title,0,'L',0);
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(66,216);
	$title=iconv('UTF-8','windows-874',"_______________");
	$pdf->MultiCell(100,5,$title,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(61,222);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(3,3,$title,1,'C',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(66,221);
	$title=iconv('UTF-8','windows-874',"บริษัท กรุงศรีอยุธยาโบรกเกอร์ จำกัด         ใบอนุญาตเลขที่  316/2535");
	$pdf->MultiCell(100,6,$title,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(61,228);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(3,3,$title,1,'C',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(66,227);
	$title=iconv('UTF-8','windows-874',"บริษัท กรุงทองโบรกเกอร์  จำกัด                ใบอนุญาตเลขที่  206/2526");
	$pdf->MultiCell(100,6,$title,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(61,234);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(3,3,$title,1,'C',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(66,233);
	$title=iconv('UTF-8','windows-874',"บริษัท พูนทวีโบรกเกอร์  จำกัด                   ใบอนุญาตเลขที่  122/2519");
	$pdf->MultiCell(100,6,$title,0,'L',0);

$pdf->SetXY(150,215);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(50,24,$title,1,'L',0);
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(165,216);
	$title=iconv('UTF-8','windows-874',"กรมธรรม์ส่ง");
	$pdf->MultiCell(20,5,$title,0,'L',0);
	$pdf->SetXY(165,216);
	$title=iconv('UTF-8','windows-874',"____________");
	$pdf->MultiCell(20,5,$title,0,'C',0);
	
	$pdf->SetXY(151,222);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(5,3,$title,1,'C',0);
	
	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(151,221.5);
	$title=iconv('UTF-8','windows-874',"X");
	$pdf->MultiCell(5,3,$title,0,'C',0);
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(157,221);
	$title=iconv('UTF-8','windows-874',"ตัวแทน");
	$pdf->MultiCell(20,5,$title,0,'L',0);
	
	$pdf->SetXY(151,228);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(5,3,$title,1,'C',0);
	
	$pdf->SetXY(157,227);
	$title=iconv('UTF-8','windows-874',"มารับเอง");
	$pdf->MultiCell(20,5,$title,0,'L',0);
	
	$pdf->SetXY(151,234);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(5,3,$title,1,'C',0);
	
	$pdf->SetXY(157,233);
	$title=iconv('UTF-8','windows-874',"บริษัทเก็บที่....................................");
	$pdf->MultiCell(50,5,$title,0,'L',0);
	
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,242);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(50,24,$title,1,'L',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(10,242);
	$title=iconv('UTF-8','windows-874',"ชื่อผู้แจ้ง / ลูกค้า");
	$pdf->MultiCell(50,5,$title,0,'C',0);
	
	$pdf->SetXY(11,247);
	$title=iconv('UTF-8','windows-874',"$userNotify");
	$pdf->MultiCell(50,5,$title,0,'L',0);
	
	list($yn,$mn,$dn)=explode("-",$dateNotify);
	$yn=$yn+543;
	$dateNotify=$dn."/".$mn."/".$yn;
	$pdf->SetXY(11,252);
	$title=iconv('UTF-8','windows-874',"วันที่  $dateNotify");
	$pdf->MultiCell(50,5,$title,0,'L',0);
	
	$pdf->SetXY(11,257);
	$title=iconv('UTF-8','windows-874',"โทร  02-744-2222 ต่อ 2354");
	$pdf->MultiCell(50,4,$title,0,'L',0);

$pdf->SetXY(80,242);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(50,24,$title,1,'L',0);
	$pdf->SetXY(80,242);
	$title=iconv('UTF-8','windows-874',"ผู้รับแจ้งประกัน");
	$pdf->MultiCell(50,5,$title,0,'C',0);

$pdf->SetXY(150,242);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(50,24,$title,1,'L',0);
	$pdf->SetXY(150,242);
	$title=iconv('UTF-8','windows-874',"ผู้อนุมัติ");
	$pdf->MultiCell(50,5,$title,0,'C',0);
	
	$pdf->SetXY(150,255);
	$title=iconv('UTF-8','windows-874',"....................................................................");
	$pdf->MultiCell(50,5,$title,0,'C',0);
	
	$pdf->SetXY(150,260);
	$title=iconv('UTF-8','windows-874',"วันที่........../........../..........");
	$pdf->MultiCell(50,5,$title,0,'C',0);

$pdf->SetXY(10,268);
$title=iconv('UTF-8','windows-874',"กรณีที่ให้ส่ง ปณ. ส่งที่.........................................................................................................................................................................................................................................");
$pdf->MultiCell(190,4,$title,0,'L',0);

$pdf->SetXY(10,272);
$title=iconv('UTF-8','windows-874',".............................................................................................................................................................................................................................................................................");
$pdf->MultiCell(190,4,$title,0,'L',0);	

//ค้นหารายชื่อเพิ่มเติมว่ามีหรือไม่
$i=5;
$qryadd=pg_query("select \"full_name\" from thcap_insure_cus a
left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\" where \"tempID\"='$auto_id'");
$numadd=pg_num_rows($qryadd);
if($numadd>0){
	$pdf->AddPage(); 
	
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"รายชื่อผู้เอาประกันภัย (เพิ่มเติม)");
	$pdf->MultiCell(200,6,$title,0,'C',0);
	
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(55,20);
	$title=iconv('UTF-8','windows-874',"เลขที่รับแจ้ง    $requestnum");
	$pdf->MultiCell(100,6,$title,0,'R',0); 

	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(55,26);
	$title=iconv('UTF-8','windows-874',"ลำดับที่");
	$pdf->MultiCell(20,6,$title,1,'C',0);
	
	$pdf->SetXY(75,26);
	$title=iconv('UTF-8','windows-874',"ชื่อ-นามสกุล");
	$pdf->MultiCell(80,6,$title,1,'C',0);
	$p=1;
	$cline=32;
	
	while($resadd=pg_fetch_array($qryadd)){
		list($full_name)=$resadd;
		
		$pdf->SetFont('AngsanaNew','',16);
		$pdf->SetXY(55,$cline);
		$title=iconv('UTF-8','windows-874',$p);
		$pdf->MultiCell(20,6,$title,1,'C',0);
		
		$pdf->SetXY(75,$cline);
		$title=iconv('UTF-8','windows-874',$full_name);
		$pdf->MultiCell(80,6,$title,1,'L',0);
		
		$p++;
		$cline+=6;
	}
}
$pdf->Output();
?>
