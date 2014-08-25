<?php
session_start();
include("../../../config/config.php");
require_once("../../../settings.php");
include("../../function/currency_totext.php"); //function แปลงจำนวนเงินเป็นตัวหนังสือ
$status=0;
pg_query("BEGIN WORK");
$id_user = $_SESSION["av_iduser"];
$contractID = pg_escape_string($_GET['contractID']); //เลขที่สัญญา


$nowdate=nowDateTime();
$nowdate_1=nowDate();
//วันที่ภาษาไทย
$qrydatethai=pg_query("select get_date_thai_format('$nowdate')");
list($nowdatethai)=pg_fetch_array($qrydatethai);

//หาชื่อ ผู้เช่า
$qry_namemain=pg_query("select \"thcap_fullname\"  from \"vthcap_ContactCus_detail\"
where \"contractID\" = '$contractID' and \"CusState\" ='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);	
}
//หาผู้ค้ำประกัน
$qry_name1=pg_query("select \"thcap_fullname\"  from \"vthcap_ContactCus_detail\"
where \"contractID\" = '$contractID' and \"CusState\" = '2'");

//งวดถัดไป
$qrynextDueDate=pg_query("SELECT \"thcap_nextDueDate\"('$contractID','$nowdate_1')");
if($rs_nextDueDate = pg_fetch_array($qrynextDueDate)){
	list($nextDueDate) = $rs_nextDueDate;
}else{
		$status++;
}

//ข้อมูลสัญญา
	$qrycon=pg_query("select \"conDate\",\"conLoanIniRate\" from thcap_contract where \"contractID\"='$contractID'");
	if($rescon=pg_fetch_array($qrycon)){
		$conDate=$rescon["conDate"];
		$conLoanIniRate=$rescon["conLoanIniRate"];
	}
$qryID_NT=pg_query("SELECT \"thcap_gen_documentID\"('$contractID','$nowdate_1','6')");
list($ID_NT)=pg_fetch_array($qryID_NT);
//หา typePayID ของค่าทนาย
	$qrytype=pg_query("select \"tpID\" from account.\"thcap_typePay\"
	where \"tpID\"=substring(account.\"thcap_mg_getMinPayType\"('$contractID'),1,1)||'004'");
	list($tpID)=pg_fetch_array($qrytype);
			
	if($tpID == "")
	{ // ถ้าไม่พบ typePayID ของค่าทนาย ของประเภทสัญญาดังกล่าว
		$status++;
		$error = "ไม่พบรหัสค่าใช้จ่ายของค่าทนาย";
	}
	
	//ตั้งหนี้หนังสือเตือนอัตโนมัติ
	$qrysetdebt=pg_query("SELECT thcap_process_setdebtloan('$contractID','$tpID','$ID_NT','$nowdate','1500',null,'000','0')");
	list($setdebt)=pg_fetch_array($qrysetdebt);
	if($setdebt!='t'){
		$status++;
	}

//------------------- PDF -------------------//
require('../../../thaipdfclass.php');

class PDF extends ThaiPDF {
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$cline = 34;

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"วันที่ $nowdatethai");
$pdf->MultiCell(159,6,$title,0,'C',0);

$cline += 10;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"เรื่อง");
$pdf->MultiCell(15,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"ให้ชำระหนี้ตามสัญญาเช่าซื้อ");
$pdf->MultiCell(159,6,$title,0,'L',0);

$cline += 10;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"เรียน ");
$pdf->MultiCell(15,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"$name3".' (ผู้เช่าซื้อ)');
$pdf->MultiCell(159,6,$title,0,'L',0);
$cline += 5;
//ผู้ค้ำ
$guarantee="";
$numco1=pg_num_rows($qry_name1);
while($resGua=pg_fetch_array($qry_name1)){	
	$name1=trim($resGua["thcap_fullname"]);
	if($guarantee==""){$guarantee=$name1;}
	else{$guarantee.=','.$name1;}
	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(35,$cline);
	$title=iconv('UTF-8','windows-874',"$name1".' (ผู้คำ้ประกัน)');
	$pdf->MultiCell(159,6,$title,0,'L',0);
	$cline += 5;
}

$cline += 5;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"อ้างถึง");
$pdf->MultiCell(15,6,$title,0,'L',0);

$qrydatethai_conDate=pg_query("select get_date_thai_format('$conDate')");
list($nowdatethai_conDate)=pg_fetch_array($qrydatethai_conDate);

$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"หนังสือสัญญาเช่าซื้อ  เลขที่  $contractID  ฉบับลงวันที่ $nowdatethai_conDate");
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 15;

$pdf->SetXY(30,$cline);
$title=iconv('UTF-8','windows-874',"ตามที่ ท่านได้ทำสัญญาเช่าซื้อตามที่อ้างถึงกับ   ");
$pdf->MultiCell(70,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(90,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท  ไทยเอซ  แคปปิตอล  จำกัด  ");
$pdf->MultiCell(52,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(138,$cline);
$title=iconv('UTF-8','windows-874',"นั้น  บัดนี้  ท่านได้ผิดนัดการชำระ");
$pdf->MultiCell(90,6,$title,0,'L',0);

$count_c=0;
$qryPay=pg_query("SELECT  \"typePayRefValue\",\"debtDueDate\",\"typePayAmt\"
        FROM
            vthcap_otherpay_debt_current
        WHERE
            \"debtStatus\" = 1 AND
            \"debtIsOther\" !='1' AND           
			\"debtDueDate\" < '$nextDueDate' AND -- น้อยกว่า วันครบกำหนดชำระถัดไป ถัดไป
            \"contractID\" = '$contractID' -- ของสัญญานั้นๆ");
$numPay=pg_num_rows($qryPay);
$sum_typePayAmt=0;

while($resPay=pg_fetch_array($qryPay)){
	$typePayRefValue = $resPay['typePayRefValue'];
	$debtDueDate = $resPay['debtDueDate'];
	$typePayAmt = $resPay['typePayAmt'];	
	$sum_typePayAmt +=$typePayAmt;
	$count_c++;
	if($count_c==1){
		//วันที่ภาษาไทย
		$qrydebtDueDate=pg_query("select get_date_thai_format('$debtDueDate')");
		list($s_debtDueDate)=pg_fetch_array($qrydebtDueDate);
		$arrayfirst_unpaid=$typePayRefValue.",".$debtDueDate;
		$Pay_1="หนี้เช่าซื้องวดที่  $typePayRefValue  ถึงงวดที่   ";
		$Pay_2="คืองวดประจำวันที่  $s_debtDueDate";
	}
	else if($count_c==$numPay){
		//วันที่ภาษาไทย
		$qrydebtDueDate=pg_query("select get_date_thai_format('$debtDueDate')");
		list($s_debtDueDate)=pg_fetch_array($qrydebtDueDate);
		$arrayend_unpaid=$typePayRefValue.",".$debtDueDate;
		$Pay_1.=" $typePayRefValue ";
		$Pay_2.="ถึงงวดวันที่  $s_debtDueDate";
	
	}
}
$Pay_2.="รวม  $numPay งวด  เป็น";
$pay_amt=$sum_typePayAmt;
$cline += 5;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',$Pay_1.$Pay_2);
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 5;
$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"เงินจำนวน ". number_format($sum_typePayAmt,2)." บาท  การกระทำดังกล่าว  ถือว่าท่านทำผิดสัญญาเช่าซื้อทำให้บริษัทได้รับความเสียหาย  บริษัทได้บอกกล่าว");
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 5;
$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ท่านชำระหลายครั้งแล้วแต่ท่าน ไม่ชำระแต่อย่างใด  ");
$pdf->MultiCell(180,6,$title,0,'L',0);
	
$txt_T="โดยหนังสือฉบับนี้  ข้าพเจ้าในฐานะทนายความผู้รับมอบอำนาจจากบริษัท จึงขอให้ท่านชำระหนี้เช่าซื้อจำนวน  $numPay งวด";	
$cline += 5;	
$pdf->SetXY(40,$cline);
$title=iconv('UTF-8','windows-874',$txt_T);
$pdf->MultiCell(159,6,$title,0,'L',0);	

//ค่าอื่น ๆ
$qrydetail=pg_query("SELECT \"typePayID\" , SUM(\"typePayAmt\") as \"typePayAmt\"
        FROM
            thcap_v_otherpay_debt_realother_current --ค่าที่ไม่รวม ค่างวด
        WHERE
            \"debtStatus\" = 1 AND
			((\"debtDueDate\" IS NULL) OR ((\"debtDueDate\" IS NOT NULL) AND (\"debtDueDate\" <= '$nowdate_1'))) AND
            \"contractID\" = '$contractID' AND -- ของสัญญานั้นๆ
			\"typePayID\" !='$tpID'
            GROUP BY \"typePayID\"");
$numPaydetail=pg_num_rows($qrydetail);
$txt_paydetail="เดือน จำนวน ".number_format($sum_typePayAmt,2)." บาท  ";
$arrayunpaid_detailall="";
$arrayunpaid_detailall="{"." ค่างวด".",".$sum_typePayAmt."}";
if($numPaydetail>0){
	
	while($resPaydetail=pg_fetch_array($qrydetail)){
		$typePayID = $resPaydetail['typePayID'];
		$typePayAmt = $resPaydetail['typePayAmt'];
		$sum_typePayAmt=$sum_typePayAmt+$typePayAmt ;
		//หาชื่อหนี้	
		$qrynametype=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typePayID'");
		list($tpDesc)=pg_fetch_array($qrynametype);
		$txt_paydetail.= $tpDesc.'  '. number_format($typePayAmt,2).'  '." บาท    ";
		if($arrayunpaid_detailall==""){$arrayunpaid_detailall="{".$tpDesc.",".$typePayAmt."}";}
		else{$arrayunpaid_detailall.=","."{".$tpDesc.",".$typePayAmt."}";	}
	}
}
//ค่าทนาย
$qrydetail_advocacy=pg_query("SELECT \"typePayID\" , SUM(\"typePayAmt\") as \"typePayAmt\"
        FROM
            thcap_v_otherpay_debt_realother_current --ค่าที่ไม่รวม ค่างวด
        WHERE
            \"debtStatus\" = 1 AND
            \"contractID\" = '$contractID' AND -- ของสัญญานั้นๆ
			\"typePayID\" ='$tpID' AND 
			\"typePayRefValue\"='$ID_NT'
            GROUP BY \"typePayID\"");
$numPay_advocacy=pg_num_rows($qrydetail_advocacy);			
if($numPay_advocacy==1){
	while($resPaydetail=pg_fetch_array($qrydetail_advocacy)){
		$typePayID = $resPaydetail['typePayID'];
		$typePayAmt = $resPaydetail['typePayAmt'];
		$sum_typePayAmt=$sum_typePayAmt+$typePayAmt ;
		//หาชื่อหนี้	
		$qrynametype=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typePayID'");
		list($tpDesc)=pg_fetch_array($qrynametype);
		$txt_paydetail.= $tpDesc.'  '. number_format($typePayAmt,2).'  '." บาท    ";
		$arrayunpaid_detailall.=","."{".$tpDesc.",".$typePayAmt."}";
	}
}
else{
	$status++;
}		
//เบี้ยปรับ  45 วันจากปัจจุบัน
$daylease_fine=date('Y-m-d',strtotime("+45 day"));
$qr_get_lease_fine=pg_query("select \"thcap_get_lease_fine\"('$contractID','$daylease_fine')");
if($rs_get_lease_fine = pg_fetch_array($qr_get_lease_fine)){	
	list($lease_fine) = $rs_get_lease_fine;
	$arrayunpaid_detailall.=","."{"."ค่าเบี้ยปรับล่าช้า".",".$lease_fine."}";
}else{
		$status++;
}

$txt_paydetail.="ค่าเบี้ยปรับล่าช้า". number_format($lease_fine,2).'  '." บาท    ";
$sum_typePayAmt +=$lease_fine;
$unpaid_detailall_amt=$sum_typePayAmt;
$txt_s2= " รวมเป็นเงิน  ". number_format($sum_typePayAmt,2) ." บาท ";



//วันที่จะครบกำหนดชำระถัดไป
$qrynextDueDate=pg_query("SELECT \"thcap_nextDueDate\"('$contractID','$nextDueDate')");
list($nextDueDate)=pg_fetch_array($qrynextDueDate);

$qrydatethai_conDatenext=pg_query("select get_date_thai_format('$nextDueDate')");
list($nowdatethai_conDatenext)=pg_fetch_array($qrydatethai_conDatenext);						   


$qryPay_t=pg_query("SELECT  \"typePayRefValue\",\"typePayAmt\"
        FROM
            vthcap_otherpay_debt_current
        WHERE
            \"debtStatus\" = 1 AND
            \"debtIsOther\" !='1' AND           
			\"debtDueDate\" = '$nextDueDate' AND 
            \"contractID\" = '$contractID' -- ของสัญญานั้นๆ");
$numPaydetail=pg_num_rows($qryPay_t);
if($numPaydetail>0){	
	$resPaydetail=pg_fetch_array($qryPay_t);
	$typePayRefValue = $resPaydetail['typePayRefValue'];
	$nextDueAmt = $resPaydetail['typePayAmt'];
	$arraypay_next=$typePayRefValue.",".$nextDueDate;
}
$sum_typePayAmt +=$nextDueAmt;
$txt_s3= "  พร้อมด้วยดอกเบี้ย ตามตามสัญญาเช่าซื้อ  อัตราร้อยละ  $conLoanIniRate ต่อปี  ของต้นเงินค้างชำระจนถึงวันที่ท่านชำระ  ทั้งนี้  ข้าพเจ้าขอให้ท่านชำระเงินจำนวนดังกล่าวให้แก่บริษัท  ณ.ที่ทำการบริษัทหรือโอนเงินเข้าธนาคารกสิกรไทย จำกัด  (มหาชน) สาขาโลตัส  สุขาภิบาล 1  บัญชีออมทรัพย์ เลขที่บัญชี  773 2 26116 2   ชื่อบัญชี  บริษัท  ไทยเอซ  แคปปิตอล  จำกัด  ภายใน 15  วัน  นับแต่วันที่ท่านได้รับหรือถือว่าได้รับหนังสือนี้โดยชอบ  ในกรณีที่ระยะเวลาที่กำหนดชำระตามที่กล่าวหากค่างวด งวดที่ $typePayRefValue ประจำวันที่ $nowdatethai_conDatenext อยู่ในระยะเวลาบอกกล่าวนี้ ท่านยังต้องชำระเพิ่มอีก 1 งวด เป็นเงินอีก ".number_format($nextDueAmt,2)."รวมเป็นเงินทั้งสิ้น ".number_format($sum_typePayAmt,2)." บาท หากท่านเพิกเฉย  หรือชำระ  แต่ชำระไม่ครบถ้วนตามจำนวนดังกล่าว  จนพ้นกำหนดระยะเวลาที่กำหนดนี้  ให้ถือว่าสัญญาเช่าซื้อเป็นอันสิ้นสุดลงทันที  นับถัดจากวันครบระยะเวลาให้ชำระหนี้ตามหนังสือเตือนฉบับนี้  การชำระหนี้บางส่วนในระหว่างบอกกล่าวนี้  แม้ว่าบริษัทได้รับชำระหนี้บางส่วนไว้ก็ตาม  ไม่ถือว่าบริษัทยอมผ่อนผันการชำระ";


$cline += 5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',$txt_paydetail.$txt_s1.$txt_s2.$txt_s3);

$pdf->MultiCell(159,6,$title,0,'L',0);
$len=strlen($txt_paydetail.$txt_s1.$txt_s2.$txt_s3);
$cn=($len/159);

$cline = $cline+($cn*4)-1;
$pdf->SetXY(40,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"ผลของการสิ้นสุดสัญญาเช่าซื้อ  และไม่ชำระหนี้ให้ครบถ้วนตามหนังสือนี้  ท่านต้องชำระหนี้ตามสัญญาเช่าซื้อตามที่ ");
$pdf->MultiCell(180,6,$title,0,'L',0);


$cline = $cline+5;
$pdf->SetXY(20,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"อ้างถึงทั้งหมดในคราวเดียวทันที  (ปิดบัญชี)  พร้อมด้วยค่าใช้จ่าย  ค่าดอกเบี้ย  ค่าติดตาม  ค่าเสียหาย  ค่าทนายความ  ค่าฤชา");
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline = $cline+5;
$pdf->SetXY(20,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"ธรรมเนียม  เพิ่มขึ้นอีกมากมายโดยไม่จำเป็น  ข้าพเจ้าหวังว่าจะได้รับความร่วมมือจากท่านด้วยดี  ขอขอบคุณ");
$pdf->MultiCell(180,6,$title,0,'L',0);


$cline += 5;
$pdf->SetXY(40,$cline);
$title=iconv('UTF-8','windows-874',"จึงเรียนมาเพื่อทราบและดำเนินการ");
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 10;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ขอแสดงความนับถือ");
$pdf->MultiCell(180,6,$title,0,'C',0);

$cline += 20;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ทนายความผู้รับมอบอำนาจ");
$pdf->MultiCell(180,6,$title,0,'C',0);


$pdf->SetXY(20,260);	
$title=iconv('UTF-8','windows-874',"ติดต่อฝ่ายกฎหมาย");
$pdf->MultiCell(159,6,$title,0,'L',0);

$pdf->SetXY(20,265);
$title=iconv('UTF-8','windows-874',"คุณณัฐธยาน์  02-7442325  วันจันทร์ -  เสาร์  เวลา  08.30  – 17.00 น.");
$pdf->MultiCell(180,6,$title,0,'L',0);

//บันทึกข้อมูล
$ins="INSERT INTO \"thcap_history_nt\"(
	\"NT_ID\", \"contractID\", \"NT_Date\", \"NT_number\", \"NT_docversion\", \"NT_isprint\",\"NT_doerid\")
	VALUES ('$ID_NT','$contractID','$nowdate','1', '1','1','$id_user')";
	if($resin=pg_query($ins)){
		$ntid = pg_fetch_result($resin,0); // NT
	}else{
		$status++;
}
//บันทึกข้อมูลใน pdf
$arrayfirst_unpaid="{".$arrayfirst_unpaid."}";
$arrayend_unpaid="{".$arrayend_unpaid."}";
$arrayunpaid_detailall="{".$arrayunpaid_detailall."}";
$arraypay_next="{".$arraypay_next."}";
$ins="INSERT INTO thcap_pdf_nt( \"NT_ID\", \"contractID\", \"NT_Date\", \"Cus_main\", \"Guarantee\", 
        \"arrayfirst_unpaid\", \"arrayend_unpaid\", \"pay_amt\", \"arrayunpaid_detailall\", 
        \"unpaid_detailall_amt\",\"interest\",\"arraypay_next\",\"amountpay_next\",\"amountpay_all\")
		
	VALUES ('$ID_NT','$contractID','$nowdate','$name3','$guarantee',
		'$arrayfirst_unpaid','$arrayend_unpaid','$pay_amt','$arrayunpaid_detailall',
		'$unpaid_detailall_amt','$conLoanIniRate','$arraypay_next','$nextDueAmt','$sum_typePayAmt')";
	if($resin=pg_query($ins)){
		$ntid = pg_fetch_result($resin,0); // NT
	}else{
		$status++;
}
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(20,24);
$title=iconv('UTF-8','windows-874',"ที่ $ID_NT");
$pdf->MultiCell(150,6,$title,0,'L',0);
if($status==0){ 
	$pdf->Output();
	pg_query("COMMIT");
}
else{ pg_query("ROLLBACK");}

?>