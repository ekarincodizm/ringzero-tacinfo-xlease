<?php
session_start();
include("../config/config.php");

$idno = $_POST['idno'];
$tmoney = $_POST['tmoney'];
$signDate = $_POST['signDate'];
$text_name = $_POST['text_name'];
$text_money = $_POST['text_money'];
$nowdate = Date('Y-m-d');

$nowdate_thai=pg_query("select conversiondatetothaitext('$nowdate')");
$nowdate_thai_show=pg_fetch_result($nowdate_thai,0);

$qry_fp=pg_query("select * from \"Fp\" WHERE \"IDNO\"='$idno';");
if($res_fp=pg_fetch_array($qry_fp)){
    $asset_type = $res_fp["asset_type"];
    $asset_id = $res_fp["asset_id"];
    $P_STDATE = $res_fp["P_STDATE"];
    $CusID = $res_fp["CusID"];
    $P_MONTH = $res_fp["P_MONTH"];
    $P_VAT = $res_fp["P_VAT"];
    $sum_mv = $P_MONTH+$P_VAT;
}

$qry_fa1=pg_query("select * from \"Fa1\" WHERE \"CusID\"='$CusID';");
if($res_fa1=pg_fetch_array($qry_fa1)){
    $A_FIRNAME = trim($res_fa1["A_FIRNAME"]);
    $A_NAME = trim($res_fa1["A_NAME"]);
    $A_SIRNAME = trim($res_fa1["A_SIRNAME"]);
}

if($asset_type == 1){
    $qry_fc=pg_query("select * from \"VCarregistemp\" WHERE \"IDNO\"='$idno';");
    if($res_fc=pg_fetch_array($qry_fc)){
        $C_CARNAME = $res_fc["C_CARNAME"];
        $C_REGIS = $res_fc["C_REGIS"];
    }
}else{
    $qry_fc=pg_query("select * from \"FGas\" WHERE \"GasID\"='$asset_id';");
    if($res_fc=pg_fetch_array($qry_fc)){
        $gas_number = $res_fc["gas_number"];
    }
}


$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  \"R_Receipt\" is null and \"IDNO\"='$idno';");
while($resvc=pg_fetch_array($qry_vcus)){
    $p += 1;
    $DueDate = $resvc["DueDate"];
    $DueNo = $resvc["DueNo"];
    //$CalAmtDelay = $resvc["CalAmtDelay"];

    $s_amt=pg_query("select \"CalAmtDelay\"('$signDate','$DueDate',$sum_mv)"); 
    $res_amt=pg_fetch_result($s_amt,0);
    
    if($p == 1){
        $start_due = $DueDate;
        $st_dueno = $DueNo;
    }
    
    list($n_year,$n_month,$n_day) = split('-',$DueDate);
    list($b_year,$b_month,$b_day) = split('-',$signDate);
    list($a_year,$a_month,$a_day) = split('-',$nowdate);

    $date_1 = mktime(0, 0, 0, $n_month, $n_day, $n_year);
    $date_2 = mktime(0, 0, 0, $b_month, $b_day, $b_year);
    $date_3 = mktime(0, 0, 0, $a_month, $a_day, $a_year);
    
    if($date_1 < $date_2){
            $nub += 1;
            $st_dueno_last = $DueNo;
            $sum_amt += $res_amt;
    }
    
    if($date_1 < $date_3){
            $nubs += 1;
            $st_dueno_lasts = $DueNo;
            $sum_amts += $res_amt;
    }
    
}

$st_dueno_last2 = $st_dueno_lasts+1;
//$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  \"R_Receipt\" is null AND \"V_Receipt\" is null and \"IDNO\"='$idno' AND \"DueNo\"='$st_dueno_last2';");
$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  \"R_Receipt\" is null AND \"IDNO\"='$idno' AND \"DueNo\"='$st_dueno_last2';");
if($resvc=pg_fetch_array($qry_vcus)) {
    $get_DueDate = $resvc["DueDate"];
}

if(!empty($get_DueDate)){
    $get_DueDate_thai=pg_query("select conversiondatetothaitext('$get_DueDate')");
    $get_DueDate_thai_show=pg_fetch_result($get_DueDate_thai,0);
}

$qry_fp=pg_query("select sum(\"CusAmt\") as sumamt from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$idno';");
if($res_fp=pg_fetch_array($qry_fp)){
    $sumamt = $res_fp["sumamt"];
}

$qry_if=pg_query("select \"CollectCus\" from insure.\"InsureForce\" WHERE \"IDNO\"='$idno' AND \"CusPayReady\"='false';");
if($res_if=pg_fetch_array($qry_if)){
    $CollectCus1 = $res_if["CollectCus"];
}

$qry_uif=pg_query("select \"CollectCus\" from insure.\"InsureUnforce\" WHERE \"IDNO\"='$idno' AND \"CusPayReady\"='false';");
if($res_uif=pg_fetch_array($qry_uif)){
    $CollectCus2 = $res_uif["CollectCus"];
}

$qry_uif=pg_query("select \"NTID\" from \"NTHead\" WHERE \"IDNO\"='$idno' AND \"CusState\"='0';");
if($res_uif=pg_fetch_array($qry_uif)){
    $NTID = $res_uif["NTID"];
}

$stdate_thai=pg_query("select conversiondatetothaitext('$P_STDATE')");
$stdate_thai_show=pg_fetch_result($stdate_thai,0);

$start_due_thai=pg_query("select conversiondatetothaitext('$start_due')");
$start_due_thai_show=pg_fetch_result($start_due_thai,0);

$aaaa = split(' ',trim($start_due_thai_show));

$start_due_thai_show_list = $aaaa[1]." ".$aaaa[2]." ".$aaaa[3];
/*
$aaaa = split(' ',$start_due_thai_show);

$start_due_thai_show_list = $aaaa[2]." ".$aaaa[3]." ".$aaaa[4];
*/
//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF {
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$cline = 50;

$pdf->Image('../images/color_av3.jpg','0','0','215','48');

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"วันที่ $nowdate_thai_show");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 10;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"หนังสือเลขที่ $NTID");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"เรื่อง เตือนให้ชำระค่าเช่าซื้อ (เตือนครั้งสุดท้าย)");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"เรียน $A_FIRNAME $A_NAME  $A_SIRNAME");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"อ้างถึง สัญญาเช่าซื้อเลขที่ $idno ลงวันที่ $stdate_thai_show");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 8;


$pdf->SetXY(15,$cline);
if($asset_type == 1){
    $title=iconv('UTF-8','windows-874',"          ตามที่ท่านได้ทำสัญญาเช่าซื้อรถยนต์ยี่ห้อ $C_CARNAME เลขทะเบียน $C_REGIS ไปจากบริษัทฯ ซึ่งตามสัญญาเช่าซื้อ ฉบับดังกล่าว ท่านสัญญาว่าจะชำระค่าเช่าซื้อติดต่อกันทุกเดือนไปจนกว่าจะครบตามสัญญา แต่ปรากฎว่า บัดนี้ท่านผิดนัดไม่ชำระ ค่าเช่าซื้อติดต่อกัน ตั้งแต่งวดเดือน $start_due_thai_show_list จนถึงปัจจุบันรวม $nubs งวด การที่ท่านชำระค่าเช่าซื้อไม่ตรงกำหนดตาม สัญญานั้น เป็นการกระทำผิดสัญญาเช่าซื้อข้อ 3 โดยทางบริษัทฯ ได้บอกกล่าวเตือนให้ชำระ ค่าเช่าซื้อที่ค้างหลายครั้งหลายหนแล้ว แต่ท่านก็เพิกเฉย เป็นเหตุให้ บริษัทฯ ได้รับความเสียหายขาดประโยชน์จากการให้เช่าซื้อ ซึ่งผู้เช่าซื้อต้องรับผิดเสียดอกเบิ้ยล่าช้า, ค่าเร่งรัดพร้อมทั้งค่าใช้จ่ายในการติดตามทวงถามค่าเช่าซื้อ ทั้งนี้เป็นไปตามสัญญาข้อ 3 และ ข้อ 14 จึงขอให้ท่านชำระหนี้ที่ค้างตามรายการดังต่อไปนี้");
}else{
    $title=iconv('UTF-8','windows-874',"          ตามที่ท่านได้ทำสัญญาเช่าซื้อชุดอุปกรณ์ก๊าซรถยนต์ เลขตัวถังก๊าซ $gas_number ไปจากบริษัทฯ ซึ่งตามสัญญาเช่าซื้อฉบับ ดังกล่าว ท่านสัญญาว่าจะชำระค่าเช่าซื้อติดต่อกันทุกเดือน ไปจนกว่าจะครบตามสัญญา แต่ปรากฎว่าบัดนี้ท่านผิดนัดไม่ชำระค่า เช่าซื้อติดต่อกันตั้งแต่งวดเดือน $start_due_thai_show_list จนถึงปัจจุบันรวม $nubs งวด การที่ท่านชำระค่าเช่าซื้อไม่ตรงกำหนดตามสัญญา ท่านสัญญาว่าจะชำระค่าเช่าซื้อติดต่อกันทุกเดือน ไปจนกว่าจะครบตามสัญญา แต่ปรากฎว่าค้างหลายครั้งหลายหนแล้ว แต่ท่าน ก็เพิกเฉย เป็นเหตุให้ บริษัทฯ ได้รับความเสียหายขาดประโยชน์ จากการให้เช่าซื้อ ซึ่งผู้เช่าซื้อท่านต้องรับผิดชอบเสียเบี้ยปรับ ดอกเบิ้ยล่าช้า, ค่าเร่งรัด, พร้อมทั้งค่าใช้จ่ายในการติดตามทวงถามค่าเช่าซื้อ ทั้งนี้เป็นไปตามสัญญา ข้อ 2, ข้อ 4, และ ข้อ 15 จึงขอให้ท่านชำระหนี้ที่ค้างตามรายการดังต่อไปนี้");
}
$pdf->MultiCell(185,6,$title,0,'L',0);

$cline +=45;

$sum_mv = ceil($sum_mv);
$sum_amt = ceil($sum_amt);
$sumamt = ceil($sumamt);
$CollectCus1 = ceil($CollectCus1);
$CollectCus2 = ceil($CollectCus2);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',"ค่างวดที่ $st_dueno - $st_dueno_lasts : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2) ." เป็นเงิน");
$pdf->MultiCell(185,6,$title,0,'L',0);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',number_format($sum_mv*$nubs,2) ." บาท");
$pdf->MultiCell(175,6,$title,0,'R',0);
$sumall += ($sum_mv*$nubs);
$cline += 6;

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',"ค่าทนายรวมค่าดอกเบี้ยล่าช้า");
$pdf->MultiCell(185,6,$title,0,'L',0);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',number_format($tmoney+$sum_amt,2) ." บาท");
$pdf->MultiCell(175,6,$title,0,'R',0);
$sumall += ($tmoney+$sum_amt);
$cline += 6;

if($asset_type == 1){
    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',"ค่ามิเตอร์+ปรับ");
    $pdf->MultiCell(185,6,$title,0,'L',0);

    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',number_format(($sumamt+1000),2) ." บาท");
    $pdf->MultiCell(175,6,$title,0,'R',0);
    $sumall += ($sumamt+1000);
    $cline += 6;
}

if($CollectCus1 > 0){
    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',"ค่า พรบ."); // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $pdf->MultiCell(185,6,$title,0,'L',0);

    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',number_format($CollectCus1,2) ." บาท");
    $pdf->MultiCell(175,6,$title,0,'R',0);
    $sumall += $CollectCus1;
    $cline += 6;
}

if($CollectCus2 > 0){
    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',"ค่าประกันภัย"); // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $pdf->MultiCell(185,6,$title,0,'L',0);

    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',number_format($CollectCus2,2) ." บาท");
    $pdf->MultiCell(175,6,$title,0,'R',0);
    $sumall += $CollectCus2;
    $cline += 6;
}

if(isset($text_name)){
    for($i=0;$i<count($text_name);$i++){
        $pdf->SetXY(23,$cline);
        $title=iconv('UTF-8','windows-874',"$text_name[$i]");
        $pdf->MultiCell(185,6,$title,0,'L',0);
        
        $text_money[$i] = ceil($text_money[$i]);
        
        $pdf->SetXY(23,$cline);
        $title=iconv('UTF-8','windows-874',number_format($text_money[$i],2) ." บาท");
        $pdf->MultiCell(175,6,$title,0,'R',0);
        $sumall += $text_money[$i];
        $cline += 6;
    }
}


$sumall = ceil($sumall);

$pdf->SetXY(23,$cline-5);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',"รวมเป็นเงินทั้งสิ้น");
$pdf->MultiCell(185,6,$title,0,'L',0);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',number_format($sumall,2) ." บาท");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetXY(23,$cline+1);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetXY(23,$cline+1.5);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$cline += 6;

if(!empty($get_DueDate)){

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',"หากชำระล่าช้ากว่า วันที่ $get_DueDate_thai_show จะต้องชำระค่างวดเพิ่มอีก 1 งวด");
$pdf->MultiCell(185,6,$title,0,'L',0);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',number_format($sum_mv,2) ." บาท");
$pdf->MultiCell(175,6,$title,0,'R',0);
$sumall += $sum_mv;
$cline += 6;

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',"รวมเป็นเงินทั้งสิ้น");
$pdf->MultiCell(185,6,$title,0,'L',0);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',number_format($sumall,2) ." บาท");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetXY(23,$cline-5);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetXY(23,$cline+1);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetXY(23,$cline+1.5);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$cline += 10;
}else{
    $cline += 4;
}

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(15,$cline);
if($asset_type == 1){
$title=iconv('UTF-8','windows-874',"          โดยหนังสือฉบับนี้ ข้าพเจ้าในฐานะหัวหน้าฝ่ายกฎหมายผู้รับมอบอำนาจจาก บริษัทฯ จึงขอให้ท่านชำระหนี้จำนวนดังกล่าว ภายในกำหนด 30 วัน นับตั้งแต่วันที่ท่านได้รับหนังสือฉบับนี้ หรือถือว่าได้รับหนังสือโดยชอบด้วยกฏหมายหากพ้นกำหนดนี้แล้ว ท่านยังเพิกเฉยอยู่อีก หรือชำระเพียงบางส่วน หรือชำระไม่ครบถ้วนทันงวดตามสัญญา หรือไม่ชำระภายในกำหนดดังกล่าวข้างต้น ให้ถือว่าหนังสือฉบับนี้เป็นการแสดงเจตนาบอกเลิกสัญญา ซึ่งมีผลให้สัญญาเช่าซื้อเป็นอันยกเลิกโดยปริยาย นับถัดจากวันที่ครบกำหนดให้ชำระข้างต้นโดยไม่ต้องบอกกล่าวอีก");
}else{
$title=iconv('UTF-8','windows-874',"          โดยหนังสือฉบับนี้ ข้าพเจ้าในฐานะผู้รับมอบอำนาจจาก บริษัทฯ จึงขอให้ท่านชำระหนี้จำนวนดังกล่าวให้ครบถ้วนทันงวด ภายในกำหนด 30 วัน นับตั้งแต่วันที่ท่านได้รับหนังสือฉบับนี้ หรือถือว่าได้รับหนังสือโดยชอบด้วยกฏหมาย หากพ้นกำหนด ดังกล่าวแล้วท่านยังเพิกเฉย หรือชำระเพียงบางส่วน หรือชำระไม่ครบถ้วนทันงวดทั้งหมดดังกล่าวข้างต้น หรือไม่ชำระภายใน กำหนดข้างต้น ให้ถือว่าหนังสือฉบับนี้เป็นการแสดงเจตนาบอกเลิกสัญญา ซึ่งมีผลให้สัญญาเช่าซื้อเป็นอันยกเลิกโดยปริยาย ซึ่งจะทำให้ท่านเสียค่าใช้จ่ายเพิ่มขึ้นอีกมากโดยไม่จำเป็น นับถัดจากวันที่ครบกำหนดดังกล่าวข้างต้น โดยไม่ต้องบอกกล่าวอีก");
}
$pdf->MultiCell(185,6,$title,0,'L',0);

$cline += 40;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"          จึงเรียนมาเพื่อทราบ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"ขอแสดงความนับถือ");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 15;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"(นาย มาชัย  บัณฑิตขจร)");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 6;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"หัวหน้าฝ่ายกฎหมาย");
$pdf->MultiCell(190,6,$title,0,'C',0);

//================================= New Page ======================================//

$qry_fa1=pg_query("select * from \"ContactCus\" WHERE \"IDNO\"='$idno' AND \"CusState\" > 0 ORDER BY \"CusState\" ASC;");
while($res_fa1=pg_fetch_array($qry_fa1)){
    $get_CusID = trim($res_fa1["CusID"]);
    $get_CusState = trim($res_fa1["CusState"]);

$qry_fa1=pg_query("select * from \"Fa1\" WHERE \"CusID\"='$get_CusID';");
if($res_fa1=pg_fetch_array($qry_fa1)){
    $get_A_FIRNAME = trim($res_fa1["A_FIRNAME"]);
    $get_A_NAME = trim($res_fa1["A_NAME"]);
    $get_A_SIRNAME = trim($res_fa1["A_SIRNAME"]);
}    
    
$qry_uif=pg_query("select \"NTID\" from \"NTHead\" WHERE \"IDNO\"='$idno' AND \"CusState\"='$get_CusState';");
if($res_uif=pg_fetch_array($qry_uif)){
    $NTID = $res_uif["NTID"];
}

$pdf->AddPage();

$sumall = 0;
$cline = 50;

$pdf->Image('../images/color_av3.jpg','0','0','215','48');

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"วันที่ $nowdate_thai_show");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 10;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"หนังสือเลขที่ $NTID");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"เรื่อง ขอให้ชำระหนี้แทนผู้เช่าซื้อโดยด่วน (เตือนครั้งสุดท้าย)");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"เรียน $get_A_FIRNAME $get_A_NAME  $get_A_SIRNAME");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"อ้างถึง สัญญาเช่าซื้อเลขที่ $idno และสัญญาค้ำประกันทำขึ้นเมื่อวันที่ $stdate_thai_show");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 8;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"          ตามที่ท่านได้ทำสัญญาค้ำประกันหนี้ให้ $A_FIRNAME $A_NAME  $A_SIRNAME ไว้แก่ บริษัท ไทยเอซ ลิสซิ่ง จำกัด ณ วันที่ 
$stdate_thai_show ซึ่งตามสัญญาเช่าซื้อฉบับดังกล่าว ผู้เช่าซื้อสัญญาว่าจะชำระค่าเช่าซื้อติดต่อกันทุกเดือน ไปจนกว่าจะครบ ตามสัญญาแต่ปรากฎว่า บัดนี้ผู้เช่าซื้อผิดนัด ไม่ชำระค่าเช่าซื้อติดต่อกันตั้งแต่งวดเดือน $start_due_thai_show_list จนถึงปัจจุบันรวม $nubs งวดเดือน การที่ผู้เช่าซื้อ ชำระค่าเช่าซื้อที่ค้างหลายครั้งหลายหนแล้ว แต่ผู้เช่าซื้อก็เพิกเฉยเป็นเหตุให้บริษัทฯ ได้รับความเสียหายขาด ประโยชน์จากการให้เช่าซื้อ ซึ่งผู้เช่าซื้อต้องรับผิดเสียค่าเร่งรัด, ค่าดอกเบี้ยล่าช้า พร้อมทั้งค่าใช้จ่ายในการติดตามทวงถามค่าเช่าซื้อ ทั้งนี้เป็นไปตามสัญญาข้อ 3 และข้อ 4 จึงขอให้ท่านชำระหนี้ที่ค้างตามรายการดังต่อไปนี้");
$pdf->MultiCell(185,6,$title,0,'L',0);

$cline += 42;

$sum_mv = ceil($sum_mv);
$sum_amt = ceil($sum_amt);
$sumamt = ceil($sumamt);
$CollectCus1 = ceil($CollectCus1);
$CollectCus2 = ceil($CollectCus2);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',"ค่างวดที่ $st_dueno - $st_dueno_lasts : จำนวน $nubs งวดๆละ ". number_format($sum_mv,2) ." เป็นเงิน");
$pdf->MultiCell(190,6,$title,0,'L',0);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',number_format($sum_mv*$nubs,2) ." บาท");
$pdf->MultiCell(175,6,$title,0,'R',0);
$sumall += ($sum_mv*$nubs);
$cline += 6;

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',"ค่าทนายรวมค่าดอกเบี้ยล่าช้า");
$pdf->MultiCell(190,6,$title,0,'L',0);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',number_format($tmoney+$sum_amt,2) ." บาท");
$pdf->MultiCell(175,6,$title,0,'R',0);
$sumall += ($tmoney+$sum_amt);
$cline += 6;

if($asset_type == 1){
    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',"ค่ามิเตอร์+ปรับ");
    $pdf->MultiCell(190,6,$title,0,'L',0);

    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',number_format(($sumamt+1000),2) ." บาท");
    $pdf->MultiCell(175,6,$title,0,'R',0);
    $sumall += ($sumamt+1000);
    $cline += 6;
}

if($CollectCus1 > 0){
    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',"ค่า พรบ."); // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $pdf->MultiCell(190,6,$title,0,'L',0);

    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',number_format($CollectCus1,2) ." บาท");
    $pdf->MultiCell(175,6,$title,0,'R',0);
    $sumall += $CollectCus1;
    $cline += 6;
}

if($CollectCus2 > 0){
    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',"ค่าประกันภัย"); // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $pdf->MultiCell(190,6,$title,0,'L',0);

    $pdf->SetXY(23,$cline);
    $title=iconv('UTF-8','windows-874',number_format($CollectCus2,2) ." บาท");
    $pdf->MultiCell(175,6,$title,0,'R',0);
    $sumall += $CollectCus2;
    $cline += 6;
}

if(isset($text_name)){
    for($i=0;$i<count($text_name);$i++){
        $pdf->SetXY(23,$cline);
        $title=iconv('UTF-8','windows-874',"$text_name[$i]");
        $pdf->MultiCell(190,6,$title,0,'L',0);
        
        $text_money[$i] = ceil($text_money[$i]);
        $pdf->SetXY(23,$cline);
        $title=iconv('UTF-8','windows-874',number_format($text_money[$i],2) ." บาท");
        $pdf->MultiCell(175,6,$title,0,'R',0);
        $sumall += $text_money[$i];
        $cline += 6;
    }
}

$sumall = ceil($sumall);

$pdf->SetXY(23,$cline-5);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',"รวมเป็นเงินทั้งสิ้น");
$pdf->MultiCell(190,6,$title,0,'L',0);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',number_format($sumall,2) ." บาท");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetXY(23,$cline+1);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetXY(23,$cline+1.5);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$cline += 6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',"หากชำระล่าช้ากว่า วันที่ $get_DueDate_thai_show จะต้องชำระค่างวดเพิ่มอีก 1 งวด");
$pdf->MultiCell(190,6,$title,0,'L',0);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',number_format($sum_mv,2) ." บาท");
$pdf->MultiCell(175,6,$title,0,'R',0);
$sumall += $sum_mv;
$cline += 6;

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',"รวมเป็นเงินทั้งสิ้น");
$pdf->MultiCell(190,6,$title,0,'L',0);

$pdf->SetXY(23,$cline);
$title=iconv('UTF-8','windows-874',number_format($sumall,2) ." บาท");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetXY(23,$cline-5);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetXY(23,$cline+1);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$pdf->SetXY(23,$cline+1.5);
$title=iconv('UTF-8','windows-874',"___________________");
$pdf->MultiCell(175,6,$title,0,'R',0);

$cline += 10;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"          โดยหนังสือฉบับนี้ ข้าพเจ้าในฐานะหัวหน้าฝ่ายกฎหมายผู้รับมอบอำนาจจาก บริษัทฯ จึงขอให้ท่านซึ่งเป็นผู้ค้ำประกันให้กับ ผู้เช่าซื้อ ในฐานะ อย่างลูกหนี้ร่วมชำระหนี้จำนวนดังกล่าว ภายในกำหนด 30 วัน นับตั้งแต่วันที่ท่านได้รับหนังสือฉบับนี้ หรือถือว่า ได้รับหนังสือโดยชอบด้วยกฏหมาย หากพ้นกำหนดนี้แล้ว ท่านยังเพิกเฉยอยู่อีก หรือชำระเพียงบางส่วน หรือชำระไม่ครบถ้วนทัน งวดตามสัญญา หรือไม่ชำระภายในกำหนดดังกล่าวข้างต้น ให้ถือว่าหนังสือฉบับนี้เป็นการแสดงเจตนาบอกเลิกสัญญา ซึ่งมีผล ให้สัญญาเช่าซื้อเป็นอันยกเลิก นับถัดจากวันที่ ครบกำหนดดังกล่าวข้างต้นโดยไม่ต้องบอกกล่าวอีก");
$pdf->MultiCell(185,6,$title,0,'L',0);

$cline += 40;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"          จึงเรียนมาเพื่อทราบ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"ขอแสดงความนับถือ");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 15;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"(นาย มาชัย  บัณฑิตขจร)");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 6;

$pdf->SetXY(15,$cline);
$title=iconv('UTF-8','windows-874',"หัวหน้าฝ่ายกฎหมาย");
$pdf->MultiCell(190,6,$title,0,'C',0);


}

$pdf->Output();
?>