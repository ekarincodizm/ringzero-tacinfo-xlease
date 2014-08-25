<?php
session_start();
include("../../config/config.php");
include("../../core/core_functions.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$idno = pg_escape_string($_POST["idno"]);

//-- หา minPayType
if($idno != "")
{
	$qry_minPayType = pg_query("select account.\"thcap_mg_getMinPayType\"('$idno')");
	$res_minPayType = pg_fetch_array($qry_minPayType);
	list($minPayType) = $res_minPayType;
}
//-- จบการหา minPayType

$db1="ta_mortgage_datastore";

//ค้นหาชื่อผู้กู้หลัก
$qry_namemain=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where  \"contractID\"='$idno' and \"CusState\" ='0'");
$nummain=pg_num_rows($qry_namemain);
if($nummain > 0)
{
	$i=1;
	while($resnamemain=pg_fetch_array($qry_namemain))
	{
		$name1=trim($resnamemain["thcap_fullname"]);
		if($i > 1)
		{
			$name3 = $name3." , ";
		}
		$name3 = $name3.$name1;
		$i++;
	}
}

//ค้นหาชื่อผู้กู้ร่วม
$qry_name=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\" > 0");
$numco=pg_num_rows($qry_name);
$i=1;
$nameco="";
while($resco=pg_fetch_array($qry_name)){
	$name2=trim($resco["thcap_fullname"]);
	if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		$nameco=$name2;
	}else{
		if($i==$numco){
			$nameco=$nameco.$name2;
		}else{
			$nameco=$nameco.$name2.", ";
		}
	}
$i++;
}

if($numco>0){
$nameco = "| ผู้กู้ร่วม :".$nameco;
}

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายการรับชำระทั้งหมด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,20);
$buss_name=iconv('UTF-8','windows-874',"$idno");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',9);
$pdf->SetXY(5,20);
$buss_name=iconv('UTF-8','windows-874',"*** หมายถึง รายการที่ชำระคืนเงินต้น-ดอกเบี้ย");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
if($nameco != ""){$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก : $name3  $nameco");}
else{$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก : $name3");}
$pdf->MultiCell(290,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(35,33);
$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(60,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(90,33);
$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(120,33);
$buss_name=iconv('UTF-8','windows-874',"คำอธิบายรายการ");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(160,33);
$buss_name=iconv('UTF-8','windows-874',"เลขอ้างอิง");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,33);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(215,33);
$buss_name=iconv('UTF-8','windows-874',"ช่องทางการจ่าย");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(252,33);
$buss_name=iconv('UTF-8','windows-874',"Ref ช่องทางการจ่าย");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;
$a=0;

$qry_vcus=pg_query("select \"receiveDate\", \"typePayRefDate\", \"receiptID\", \"typePayID\", \"tpDesc\", \"typePayRefValue\", \"debtAmt\", \"byChannel\", \"nameChannel\"
					from \"thcap_v_receipt_otherpay\" WHERE  \"contractID\"='$idno' ORDER BY \"receiveDate\", \"receiptID\", \"typePayID\" ");
$rows = pg_num_rows($qry_vcus);
if($rows > 0){
	while($resvc=pg_fetch_array($qry_vcus))
	{
        $receiveDate = $resvc["receiveDate"];
        $typePayRefDate = $resvc["typePayRefDate"];
        $receiptID = $resvc["receiptID"];
        $typePayID = $resvc["typePayID"];
        $tpDesc = $resvc["tpDesc"];
        $typePayRefValue = $resvc["typePayRefValue"];
		$debtAmt = $resvc["debtAmt"];
		$byChannel = $resvc["byChannel"];
		$nameChannel = $resvc["nameChannel"];
	
		$sqlchannel997 = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$byChannel','1')");
		list($rechannel997) = pg_fetch_array($sqlchannel997);
		$sqlchannel998 = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$byChannel','1')");
		list($rechannel998) = pg_fetch_array($sqlchannel998);
		if($byChannel == $rechannel997 || $byChannel == $rechannel998){
			$nameChannel = $nameChannel."*";		
		}
		
		//หา Ref ช่องทางการจ่าย
		$qry_channel = pg_query("SELECT \"byChannelRef\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID' ");
		list($channelref) = pg_fetch_array($qry_channel);

	
		if($nub == 25)
		{
			$nub = 1;
			$cline = 40;
			$pdf->AddPage();
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"รายการรับชำระทั้งหมด");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',14);
			$pdf->SetXY(5,20);
			$buss_name=iconv('UTF-8','windows-874',"$idno");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',9);
			$pdf->SetXY(5,20);
			$buss_name=iconv('UTF-8','windows-874',"*** หมายถึง รายการที่ชำระคืนเงินต้น-ดอกเบี้ย");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			if($nameco != ""){$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก : $name3  $nameco");}
			else{$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก : $name3");}
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(35,33);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(60,33);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(90,33);
			$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(120,33);
			$buss_name=iconv('UTF-8','windows-874',"คำอธิบายรายการ");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetXY(160,33);
			$buss_name=iconv('UTF-8','windows-874',"เลขอ้างอิง");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(190,33);
			$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(215,33);
			$buss_name=iconv('UTF-8','windows-874',"ช่องทางการจ่าย");
			$pdf->MultiCell(50,4,$buss_name,0,'C',0);

			$pdf->SetXY(252,33);
			$buss_name=iconv('UTF-8','windows-874',"Ref ช่องทางการจ่าย");
			$pdf->MultiCell(50,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,34);
			$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);
		}
		
		if($typePayID == $minPayType)
		{
			$receiptID = $receiptID." ***";
		}
	
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$receiveDate");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(35,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$typePayRefDate");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(60,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$receiptID");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(90,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$typePayID");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(120,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$tpDesc");
		$pdf->MultiCell(70,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(160,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$typePayRefValue");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(185,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($debtAmt,2));
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(215,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$nameChannel");
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(265,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$channelref");
		$pdf->MultiCell(50,4,$buss_name,0,'L',0);
		
		$cline += 5;
		$nub+=1;
		$a += 1;
		$i++;
	}
}

if($rows > 0){
    $pdf->SetFont('AngsanaNew','B',14);

    $pdf->SetXY(5,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(290,4,$buss_name,0,'C',0);
    
    $cline += 6;
    $nub+=1;
}

$pdf->Output();
?>