<?php
include("../config/config.php");
$get_company = $_SESSION["session_company_code"];
$get_company_thainame = $_SESSION["session_company_thainame"];
$get_userid = $_SESSION["av_iduser"];
$now_date = nowDate();//ดึง วันที่จาก server

$idno = pg_escape_string($_GET['idno']);
$dataarr = pg_escape_string($_GET['dataarr']);
$fee = pg_escape_string($_GET['fee']);
$datepicker = pg_escape_string($_GET['datepicker']);

$txt_detail = "";
$arr = explode("|",$dataarr);
foreach($arr as $v){
    $krr = explode(":",$v);
    if(empty($krr[0])) continue;
    $qry_nn=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$krr[0]'");
    if($res_nn=pg_fetch_array($qry_nn)){
        $TName = $res_nn["TName"];
    }
    $txt_detail .= "          - $TName ยอดเงิน ".number_format($krr[1],2)." บาท\n";
    $sum += $krr[1];
}

$sum += $fee;

if($fee != 0 && !empty($fee)){
    $txt_detail .= "          - ค่าธรรมเนียม ยอดเงิน ".number_format($fee,2)." บาท\n";
}
$txt_detail .= "          รวมเป็นเงินต้องชำระ ".number_format($sum,2)." บาท";

pg_query("BEGIN WORK");
$status = 0;

$qry_if=pg_query("select \"CusLetID\" from letter.\"send_address\" WHERE \"IDNO\"='$idno' AND \"CusState\"='0' AND \"active\"='TRUE' ");
if($res_if=pg_fetch_array($qry_if)){
    $CusLetID = $res_if["CusLetID"];
}

$rs = pg_query("select letter.\"gen_sendid\"('$now_date');");
$gen_sendid = pg_fetch_result($rs,0);
if(empty($gen_sendid)){
    $status++;
}

$in_sql="insert into \"letter\".\"send_detail\" (\"send_date\",\"sendID\",\"CusLetID\",\"detail\",\"userid\") values ('$now_date','$gen_sendid','$CusLetID','8','$get_userid');";
if(!$result = pg_query($in_sql)){
    $status++;
}
/*
$qry_sdt=pg_query("select \"send_date\" from letter.\"send_detail\" WHERE \"CusLetID\"='$CusLetID' AND \"detail\"='4' ORDER BY \"send_date\" DESC");
if($res_sdt=pg_fetch_array($qry_sdt)){
    $send_date = $res_sdt["send_date"];
}
*/
$dddd2 = pg_query("select \"conversiondatetothaitext\"('$datepicker');");
$send_date = pg_fetch_result($dddd2,0);

$to_date =date("Y-m-d", strtotime("+7 day",strtotime($now_date))); //บวกวันที่เพิ่ม 7 วัน
$dddd1 = pg_query("select \"conversiondatetothaitext\"('$to_date');");
$to_date = pg_fetch_result($dddd1,0);

if($status == 0){
    pg_query("COMMIT");
    //pg_query("ROLLBACK");
}else{
    pg_query("ROLLBACK");
}

//-------------------//------------------- PDF -------------------//-------------------//
require('../thaipdfclass.php');

$d1 = pg_query("select \"conversiondatetothaitext\"('$now_date');");
$now_date_thai = pg_fetch_result($d1,0);

    
$qry_un=pg_query("select * from \"UNContact\" WHERE \"IDNO\"='$idno' ");
if($res_un=pg_fetch_array($qry_un)){
    $full_name = $res_un["full_name"];
    $C_CARNAME = $res_un["C_CARNAME"];
    $C_REGIS = $res_un["C_REGIS"];
}

$result_fullname=pg_query("SELECT fullname FROM \"Vfuser\" WHERE id_user='$get_userid' ");
if($res_fullname = pg_fetch_array($result_fullname)){
    $fullname=$res_fullname["fullname"];
}

class PDF extends ThaiPDF{
    
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();
$cline = 3;

$pdf->SetFont('AngsanaNew','',15);

$pdf->Image('../images/head_'.$get_company.'.jpg',20,$cline,'186','29');
$cline+=35;

$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"วันที่ $now_date_thai");
$pdf->MultiCell(188,6,$title,0,'C',0);
$cline+=12;

$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"เรื่อง แจ้งเตือนการชำระค่าประกันภัยรถยนต์ (โดยด่วน)");
$pdf->MultiCell(188,6,$buss_name,0,'L',0);
$cline+=6;

$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"เรียน $full_name");
$pdf->MultiCell(188,6,$buss_name,0,'L',0);
$cline+=12;

$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"          ตามที่ท่านได้เช่าซื้อรถยนต์ยี่ห้อ $C_CARNAME หมายเลขทะเบียน $C_REGIS กทม.
กับ บริษัท $get_company_thainame ตามสัญญาเลขที่ $idno และได้ติดต่อตกลงกับบริษัท เพื่อต่ออายุประกันภัยรถยนต์
ตามรายละเอียดหนังสือที่แจ้งให้ท่านทราบเมื่อวันที่ $send_date แล้วนั้น บัดนี้ได้เลยกำหนดการที่นัดชำระ
ไว้กับทาง บริษัทเป็นเวลาพอสมควร ทางบริษัทจึงขอให้ท่านติดต่อชำระค่าเบิ้ยประกันที่ค้าง ตามรายละเอียดดังนี้
$txt_detail

ดังนั้น บริษัทขอให้ท่านชำระยอดเงินดังกล่าวภายในวันที่ $to_date มิฉะนั้นบริษัทจะทำตามเงื่อนไขของ
สัญญาเช่าซื้อรถยนต์ โดยจะนำเงินค่างวดรถของท่านที่จะชำระในงวดต่อไปมาหักชำระหนี้ดังกล่าว จนกว่าจะครบตามจำนวน
ค่าเบี้ยประกันที่ค้าง
");
$pdf->MultiCell(188,6,$buss_name,0,'L',0);

$arr_nub = explode("\n",$buss_name);
$count_nubline = count($arr_nub);
$cline+=($count_nubline*6);

$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทางบริษัทต้องขออภัยมา ณ ที่นี้ หากท่านได้ชำระค่าประกันภัยจำนวนดังกล่าว ก่อนที่ได้รับหนังสือฉบับนี้
จึงเรียนมาเพื่อทราบ");
$pdf->MultiCell(188,6,$buss_name,0,'L',0);
$cline+=30;

$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"ขอแสดงความนับถือ
($fullname)
เจ้าหน้าที่ฝ่ายประกันภัยรถยนต์");
$pdf->MultiCell(188,6,$buss_name,0,'C',0);

$pdf->Output();
?>