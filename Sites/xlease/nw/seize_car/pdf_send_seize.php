<?php
session_start();
include("../../config/config.php");

$IDNO = $_POST['IDNO'];
$NTID = $_POST['NTID'];
if($IDNO == "" and $NTID == ""){
	$IDNO = $_GET['IDNO'];
	$NTID = $_GET['NTID'];
}
$nowdate = Date('Y-m-d');

/* ============================================= */
$query=pg_query("select a.\"IDNO\",b.\"full_name\",a.\"P_STDATE\",b.\"C_CARNAME\",b.\"C_REGIS\",b.\"C_REGIS_BY\",
b.\"C_CARNUM\",c.\"gas_name\",c.\"gas_number\",c.\"car_regis\",c.\"car_regis_by\",c.\"carnum\" from \"Fp\" a
left join \"VCarregistemp\" b on a.\"IDNO\" = b.\"IDNO\"
left join \"FGas\" c on a.\"asset_id\" = c.\"GasID\"
where a.\"IDNO\" = '$IDNO'");
if($res = pg_fetch_array($query)){
	$IDNO = $res["IDNO"];
	$fullname = trim($res["full_name"]);
	$P_STDATE = $res["P_STDATE"]; //วันทำสัญญา
	$CARNAME = $res["C_CARNAME"]; //ยี่ห้อรถยนต์
	
	//ถังแก๊ส
	if($CARNAME == ""){
		$C_CARNAME = $res["gas_name"]." <b>เลขถังแก๊ส </b> ". $res["gas_number"]; //ยี่ห้อถังแก๊ส
		$C_REGIS = $res["car_regis"]; //ทะเบียนรถ
		$CAR_REGIS_BY = $res["car_regis_by"]; //จังหวัด
		$C_CARNUM = $res["carnum"]; //หมายเลขตัวถัง
	}else{
		$C_CARNAME = $res["C_CARNAME"]; //ยี่ห้อรถยนต์
		$C_REGIS = $res["C_REGIS"]; //ทะเบียน
		$C_CARNUM = $res["C_CARNUM"]; //หมายเลขตัวถัง
		$CAR_REGIS_BY = $res["C_REGIS_BY"]; //จังหวัด
	}
	
}

$query_seize=pg_query("select b.\"fullname\" as \"authorizeuser\",c.\"fullname\" as \"seizeuser\",d.\"fullname\" as \"witness1\",e.\"fullname\" as \"witness2\",f.\"organize_name\",proxy_datesend from \"nw_seize_car\" a
	left join \"Vfuser\" b on a.\"authorize_user\" = b.\"id_user\"
	left join \"Vfuser\" c on a.\"seize_user\" = c.\"id_user\" 
	left join \"Vfuser\" d on a.\"witness_user1\" = d.\"id_user\" 
	left join \"Vfuser\" e on a.\"witness_user2\" = e.\"id_user\" 
	left join \"nw_organize\" f on a.\"organizeID\" = f.\"organizeID\" 
	where a.\"IDNO\" = '$IDNO' and a.\"NTID\" = '$NTID'");
	if($res_seize = pg_fetch_array($query_seize)){
		$authorizeuser=$res_seize["authorizeuser"]; //ผู้มอบอำนาจ
		$seizeuser=$res_seize["seizeuser"]; //ผู้รับมอบอำนาจ
		$witness1=$res_seize["witness1"]; //พยานคนที่ 1
		$witness2=$res_seize["witness2"]; //พยานคนที่ 2
		$organize_name=$res_seize["organize_name"]; //ตัวแทนบริษัท
		$proxy_datesend=$res_seize["proxy_datesend"];//วันที่ส่งหนังสือมอบอำนาจ
	}
/* ============================================= */

$nowdate_thai=pg_query("select nw_conversiondatetothaitext('$proxy_datesend')");
$nowdate_thai_show=pg_fetch_result($nowdate_thai,0);

$idnodate_thai=pg_query("select nw_conversiondatetothaitext('$P_STDATE')");
$idnodate_thai_show=pg_fetch_result($idnodate_thai,0);
//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF {
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$cline = 54;

$pdf->SetFont('AngsanaNew','',18);
$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"หนังสือมอบอำนาจ");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 8;
$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(33,$cline);
$title=iconv('UTF-8','windows-874',"วันที่ $nowdate_thai_show");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 12;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"                ข้าพเจ้า  $organize_name โดย");
$pdf->MultiCell(75,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(100,$cline);
$title=iconv('UTF-8','windows-874',"$authorizeuser");
$pdf->MultiCell(40,6,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(140,$cline);
if($CARNAME == ""){
	$title=iconv('UTF-8','windows-874',"เป็นเจ้าของกรรมสิทธิ์ถังแก๊ส");
}else{
	$title=iconv('UTF-8','windows-874',"เป็นเจ้าของกรรมสิทธิ์รถยนต์ยี่ห้อ");
}
$pdf->MultiCell(80,6,$title,0,'L',0);
 
$cline += 6;
$pdf->SetXY(25,$cline);
$pdf->SetFont('AngsanaNew','',13);
$title=iconv('UTF-8','windows-874',"$C_CARNAME  หมายเลขทะเบียน");
$pdf->MultiCell(60,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(82,$cline);
$title=iconv('UTF-8','windows-874',"$C_REGIS  $CAR_REGIS_BY");
$pdf->MultiCell(50,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(125,$cline);
$title=iconv('UTF-8','windows-874',"หมายเลขตัวถัง  $C_CARNUM  ขอมอบ");
$pdf->MultiCell(85,6,$title,0,'L',0);

$cline += 6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"อำนาจให้");
$pdf->MultiCell(16,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(39,$cline);
$title=iconv('UTF-8','windows-874',"$seizeuser");
$pdf->MultiCell(50,6,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(89,$cline);
if($CARNAME == ""){
	$title=iconv('UTF-8','windows-874',"เป็นตัวแทน  $organize_name  ในการติดตามเพื่อยึดถังแก๊ส");
}else{
	$title=iconv('UTF-8','windows-874',"เป็นตัวแทน  $organize_name  ในการติดตามเพื่อยึดรถยนต์");
}
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;
$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(25,$cline);
if($CARNAME == ""){
	$title=iconv('UTF-8','windows-874',"เอาคืนเข้าครอบครอง หรือรับถังแก๊สดังกล่าวคืนจาก");
}else{
	$title=iconv('UTF-8','windows-874',"เอาคืนเข้าครอบครอง หรือรับรถยนต์คันดังกล่าวคืนจาก");
}
$pdf->MultiCell(80,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(103,$cline);
$title=iconv('UTF-8','windows-874',"$fullname");
$pdf->MultiCell(45,6,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(145,$cline);
$title=iconv('UTF-8','windows-874',"หรือผู้ครอบครองใดๆ  หรือจาก");
$pdf->MultiCell(140,6,$title,0,'L',0);

$cline += 6;
$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(25,$cline);
if($CARNAME == ""){
	$title=iconv('UTF-8','windows-874',"เจ้าพนักงานตำรวจ  หรือ  พนักงานสอบสวนแทนบริษัทได้  ตามสัญญาเช่าซื้อถังแก๊สเลขที่");
}else{
	$title=iconv('UTF-8','windows-874',"เจ้าพนักงานตำรวจ  หรือ  พนักงานสอบสวนแทนบริษัทได้  ตามสัญญาเช่าซื้อรถยนต์เลขที่");
}
$pdf->MultiCell(128,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(152,$cline);
$title=iconv('UTF-8','windows-874',"$IDNO");
$pdf->MultiCell(25,6,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(175,$cline);
$title=iconv('UTF-8','windows-874',"ฉบับลงวันที่");
$pdf->MultiCell(20,6,$title,0,'L',0);

$cline += 6;
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"$idnodate_thai_show");
$pdf->MultiCell(35,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(56,$cline);
$title=iconv('UTF-8','windows-874',"ผู้เช่าซื้อได้ทำผิดสัญญาเช่าซื้อข้อ 2  ข้อ 4  และ  ข้อ 15 คือผิดนัดไม่ชำระค่าเช่าซื้อ  ให้เป็นไปตาม");
$pdf->MultiCell(162,6,$title,0,'L',0);

$cline += 6;
$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(25,$cline);
if($CARNAME == ""){
	$title=iconv('UTF-8','windows-874',"สัญญา  และตามหนังสือบอกกล่าวจนเป็นเหตุให้สัญญาเช่าซื้อได้สิ้นสุดกันไปแล้ว กับให้มีอำนาจในการเจรจาแจ้งความ กล่าวโทษร้องทุกข์กับพนักงานสอบสวน  เพื่อดำเนินคดีกับผู้กระทำความผิดใดๆ  ต่อถังแก๊ส ดังกล่าวแทนบริษัทได้  จนเสร็จการ  แต่ทั้งนี้ต้องไม่กระทำการใดๆ  อันเป็นความผิดทางอาญาเพื่อให้บรรลุวัตถุประสงค์ขั้นต้น");
}else{
	$title=iconv('UTF-8','windows-874',"สัญญา  และตามหนังสือบอกกล่าวจนเป็นเหตุให้สัญญาเช่าซื้อได้สิ้นสุดกันไปแล้ว กับให้มีอำนาจในการเจรจาแจ้งความ กล่าวโทษร้องทุกข์กับพนักงานสอบสวน  เพื่อดำเนินคดีกับผู้กระทำความผิดใดๆ  ต่อรถยนต์ คันดังกล่าวแทนบริษัทได้  จนเสร็จการ  แต่ทั้งนี้ต้องไม่กระทำการใดๆ  อันเป็นความผิดทางอาญาเพื่อให้บรรลุวัตถุประสงค์ขั้นต้น");
}
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 12;
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(170,$cline);
$title=iconv('UTF-8','windows-874',"แต่ไม่มีอำนาจ ");
$pdf->MultiCell(25,6,$title,0,'L',0);

$cline += 6;
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"รับเงิน  หรือ  ตั๋วเงินใดๆ  ทั้งสิ้น");
$pdf->MultiCell(48,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(73,$cline);
$title=iconv('UTF-8','windows-874',"แทนบริษัท");
$pdf->MultiCell(20,6,$title,0,'L',0);

/*if($CARNAME == ""){
$title=iconv('UTF-8','windows-874',"                ข้าพเจ้า  บริษัท  ไทยเอซ  สิสซิ่ง  จำกัด  โดย  $authorizeuser  เป็นเจ้าของกรรมสิทธิ์ถังแก๊ส $C_CARNAME  หมายเลขทะเบียน  $C_REGIS  $CAR_REGIS_BY  หมายเลขตัวถัง  $C_CARNUM  ขอมอบอำนาจให้  $seizeuser  เป็นตัวแทน  $organize_name  ในการติดตามเพื่อยึดถังแก๊สเอาคืนเข้าครอบครอง  หรือ  รับถังแก๊สคันดังกล่าวคืนจาก  $fullname  หรือผู้ครอบครองใดๆ  หรือ  จากเจ้าพนักงานตำรวจ  หรือ  พนักงานสอบสวนแทนบริษัทได้  ตามสัญญาเช่าซื้อเลขที่  $IDNO  ฉบับลงวันที่  $nowdate_thai_show  ผู้เช่าซื้อได้ทำผิดสัญญาเช่าซื้อข้อ 2 ข้อ 4 และ ข้อ 15 คือผิดนัดไม่ชำระค่าเช่าซื้อให้เป็นไปตามสัญญา  และ  ตามหนังสือบอกกล่าว  จนเป็นเหตุให้สัญญาเช่าซื้อได้สิ้นสุดกันไปแล้ว  กับให้มีอำนาจในการเจรจา  แจ้งความ  กล่าวโทษ  ร้องทุกข์กับพนักงานสอบสวน  เพื่อดำเนินคดีกับผู้กระทำความผิดใดๆ  ต่อถังแก๊สดังกล่าวแทนบริษัทได้จนเสร็จการ  แต่ทั้งนี้ต้องไม่กระทำการใดๆ  อันเป็นความผิดทางอาญา  เพื่อให้บรรลุวัตถุประสงค์ขั้นต้น แต่ไม่มีอำนาจรับเงิน หรือ ตั๋วเงินใดๆ ทั้งสิ้น แทนบริษัท");
}else{
$title=iconv('UTF-8','windows-874',"                ข้าพเจ้า  บริษัท  ไทยเอซ  สิสซิ่ง  จำกัด  โดย  $authorizeuser  เป็นเจ้าของกรรมสิทธิ์รถยนต์ยี่ห้อ  $C_CARNAME  หมายเลขทะเบียน  $C_REGIS  $CAR_REGIS_BY  หมายเลขตัวถัง  $C_CARNUM  ขอมอบอำนาจให้  $seizeuser  เป็นตัวแทน  $organize_name  ในการติดตามเพื่อยึดรถยนต์เอาคืนเข้าครอบครองหรือรับรถยนต์คันดังกล่าวคืนจาก  $fullname  หรือผู้ครอบครองใดๆ  หรือจากเจ้าพนักงานตำรวจ  หรือพนักงานสอบสวนแทนบริษัทได้  ตามสัญญาเช่าซื้อรถยนต์เลขที่  $IDNO  ฉบับลงวันที่  $nowdate_thai_show  ผู้เช่าซื้อได้ทำผิดสัญญาเช่าซื้อข้อ 2 ข้อ 4 และข้อ 15 คือผิดนัดไม่ชำระค่าเช่าซื้อให้เป็นไปตามสัญญา  และตามหนังสือบอกกล่าว  จนเป็นเหตุให้สัญญาเช่าซื้อได้สิ้นสุดกันไปแล้ว  กับให้มีอำนาจในการเจรจา  แจ้งความ  กล่าวโทษ  ร้องทุกข์กับพนักงานสอบสวน  เพื่อดำเนินคดีกับผู้กระทำความผิดใดๆ  ต่อรถยนต์คันดังกล่าวแทนบริษัทได้จนเสร็จการ  แต่ทั้งนี้ต้องไม่กระทำการใดๆ  อันเป็นความผิดทางอาญา  เพื่อให้บรรลุวัตถุประสงค์ขั้นต้น แต่ไม่มีอำนาจรับเงิน หรือ ตั๋วเงินใดๆ ทั้งสิ้น แทนบริษัท");
}
$pdf->MultiCell(185,6,$title,0,'L',0);
*/


$cline +=6;


$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"                การใดที่ผู้รับมอบอำนาจได้กระทำไปตามหนังสือมอบอำนาจนี้เท่านั้น  ให้ถือเสมือนหนึ่งว่า  ข้าพเจ้าบริษัท             ไทยเอซ ลิสซิ่ง จำกัด ได้กระทำไปด้วยตนเองทุกประการ และ มีผลผูกพันบริษัท เพื่อเป็นหลักฐานในการนี้ จึงได้ลง  ลายมือชื่อ และ ประทับตราสำคัญของบริษัทฯ  ไว้เป็นหลักฐานต่อหน้าพยาน");
$pdf->MultiCell(175,6,$title,0,'L',0);

$cline += 30;

$pdf->SetXY(75,$cline);
$title=iconv('UTF-8','windows-874',"ลงชื่อ......................................................ผู้มอบอำนาจ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"($authorizeuser)");
$pdf->MultiCell(160,6,$title,0,'C',0);

$cline += 10;

$pdf->SetXY(75,$cline);
$title=iconv('UTF-8','windows-874',"ลงชื่อ......................................................ผู้รับมอบอำนาจ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"($seizeuser)");
$pdf->MultiCell(165,6,$title,0,'C',0);

$cline += 10;

$pdf->SetXY(30,$cline);
$title=iconv('UTF-8','windows-874',"ลงชื่อ............................................พยาน");
$pdf->MultiCell(190,6,$title,0,'L',0);

$pdf->SetXY(126,$cline);
$title=iconv('UTF-8','windows-874',"ลงชื่อ............................................พยาน");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(28,$cline);
$title=iconv('UTF-8','windows-874',"($witness1)");
$pdf->MultiCell(60,6,$title,0,'C',0);

$pdf->SetXY(125,$cline);
$title=iconv('UTF-8','windows-874',"($witness2)");
$pdf->MultiCell(60,6,$title,0,'C',0);

$cline += 15;
$pdf->SetXY(25,$cline);
if($CARNAME == ""){
	$title=iconv('UTF-8','windows-874',"                ข้าพเจ้า.......................................................ยินดีคืนถังแก๊สดังกล่าวข้างต้น  ให้กับบริษัทฯ ");
}else{
	$title=iconv('UTF-8','windows-874',"                ข้าพเจ้า.......................................................ยินดีคืนรถยนต์คันดังกล่าวข้างต้น  ให้กับบริษัทฯ  โดยภายในรถยนต์  ไม่มีทรัพย์สินใดๆ  ทั้งสิ้น");
}
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 20;
$pdf->SetXY(75,$cline);
if($CARNAME == ""){
	$title=iconv('UTF-8','windows-874',"ลงชื่อ......................................................ผู้มอบถังแก๊ส");
}else{
	$title=iconv('UTF-8','windows-874',"ลงชื่อ......................................................ผู้มอบรถยนต์");
}
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(28,$cline);
$title=iconv('UTF-8','windows-874',"(...................................................)");
$pdf->MultiCell(160,6,$title,0,'C',0);

$cline += 6;

$pdf->SetXY(75,$cline);
$title=iconv('UTF-8','windows-874',"วันที่........................................เวลา...............น.");
$pdf->MultiCell(190,6,$title,0,'L',0);

$pdf->Output();
?>