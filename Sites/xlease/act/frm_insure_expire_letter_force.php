<?php
session_start();
include("../config/config.php");

$get_company = $_SESSION["session_company_code"];
$get_company_thainame = $_SESSION["session_company_thainame"];
$get_userid = $_SESSION["av_iduser"];
$now_date = nowDate();//ดึง วันที่จาก server
$cid = pg_escape_string($_POST['cid']);
$c_cid = count($cid);
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$d1 = pg_query("select \"conversiondatetothaitext\"('$now_date');");
$now_date_thai = pg_fetch_result($d1,0);

//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', '(TAL) เตือนหมดอายุ ประกัน พรบ.', '$datelog')");
//ACTIONLOG---



//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF{
    
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();

$status = 0;
pg_query("BEGIN WORK");

for($i=0;$i<$c_cid;$i++){
    
    $qry_if=pg_query("select * from \"insure\".\"InsureForce\" WHERE \"InsFIDNO\"='$cid[$i]' ");
    if($res_if=pg_fetch_array($qry_if)){
        $CarID = $res_if["CarID"];
        $CusID = $res_if["CusID"];
        $IDNO = $res_if["IDNO"];
        $InsID = $res_if["InsID"];
        $StartDate = $res_if["StartDate"];
        $EndDate = $res_if["EndDate"];
        $Kind = $res_if["Kind"];
        $Premium = $res_if["Premium"]; $Premium = round($Premium,2);
        /*
        list($n_year,$n_month,$n_day) = split('-',trim($StartDate));
        $ExpireDate = date("Y-m-d", mktime(0, 0, 0, $n_month, $n_day, $n_year+1)); 
        */
        
        $EndDate_lob15 =date("Y-m-d", strtotime("-15 day",strtotime($EndDate)));
        
        $d3 = pg_query("select \"conversiondatetothaitext\"('$EndDate_lob15');");
        $ExpireDate_lob15 = pg_fetch_result($d3,0);
        
        $d2 = pg_query("select \"conversiondatetothaitext\"('$EndDate');");
        $ExpireDate = pg_fetch_result($d2,0);
    }
    
    $qry_if=pg_query("select * from \"ContactCus\" WHERE \"CusID\"='$CusID' ");
    if($res_if=pg_fetch_array($qry_if)){
        $CusState = $res_if["CusState"];
    }
    
    $qry_if=pg_query("select * from \"VCarregistemp\" WHERE \"IDNO\"='$IDNO' ");
    if($res_if=pg_fetch_array($qry_if)){
        $C_REGIS = $res_if["C_REGIS"];
    }
    
    $qry_if=pg_query("select * from \"Fa1\" WHERE \"CusID\"='$CusID' ");
    if($res_if=pg_fetch_array($qry_if)){
        $A_FIRNAME = trim($res_if["A_FIRNAME"]);
        $A_NAME = trim($res_if["A_NAME"]);
        $A_SIRNAME = trim($res_if["A_SIRNAME"]);
        
        $short_name = "$A_NAME  $A_SIRNAME";
        $long_name = "$A_FIRNAME $A_NAME  $A_SIRNAME";
    }
    
    $dtl_ads = "";
    $dtl_ads_data_show = "";
    $qry_if=pg_query("select * from letter.\"send_address\" WHERE \"IDNO\"='$IDNO' AND \"CusState\"='0' AND \"active\"='TRUE' ");
    if($res_if=pg_fetch_array($qry_if)){
        $CusLetID = $res_if["CusLetID"];
        $dtl_ads = $res_if["dtl_ads"];
        $dtl_ads = explode(" ", $dtl_ads);
        $count_arr = count($dtl_ads);
        $hce = ceil($count_arr/2);
        $dtl_ads[$hce] = $dtl_ads[$hce]."\n";
        foreach($dtl_ads as $dtl_ads_data){
            $dtl_ads_data_show .= $dtl_ads_data." ";
        }
    }

// ออกจดหมาย
$pdf->AddPage();
$cline = 3;

$pdf->SetFont('AngsanaNew','',15);

$pdf->Image('../images/head_'.$get_company.'.jpg',20,$cline,'186','29'); //head big
$cline+=35;

$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"วันที่ $now_date_thai");
$pdf->MultiCell(188,6,$title,0,'C',0);
$cline+=12;

$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"เรื่อง  แจ้งต่อประกันภัยรถยนต์ (กรมธรรม์ประกันภัยเดิมสิ้นสุดการคุ้มครอง)");
$pdf->MultiCell(188,6,$buss_name,0,'L',0);
$cline+=6;

$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"เรียน  คุณ $short_name");
$pdf->MultiCell(188,6,$buss_name,0,'L',0);
$cline+=12;

if($get_company == "AVL"){
    $txt_str = "11";
}elseif($get_company == "THA"){
    $txt_str = "12";
}

$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"          ตามที่ท่านได้ทำสัญญาเช่าซื้อรถยนต์ ยี่ห้อ TOYOTA หมายเลขทะเบียน $C_REGIS กทม. กับ บริษัท $get_company_thainame
(สาขาจรัญสนิทวงศ์) ขณะนี้กรมธรรม์ประกันภัยรถยนต์ดังกล่าว จะสิ้นสุดการคุ้มครองลงในวันที่ $ExpireDate
เพื่อเป็นการป้องกันความเสียหาย อันอาจเกิดขึ้นกับทรัพย์สินของท่าน และเพื่อให้รถยนต์ที่เช่าซื้อ มีการคุ้มครองตามเงื่อนไข
ของสัญญาเช่าซื้อ ข้อที่ $txt_str ระบุรายละเอียดการทำประกันภัยรถยนต์ทุกปีตลอดระยะเวลาการเช่าซื้อ ทางบริษัท $get_company_thainame
จึงใคร่ขอให้ท่านติดต่อกลับ เกี่ยวกับเรื่องประกันภัยรถยนต์ก่อนวันที่ $ExpireDate_lob15 นี้ เพราะต้องเตรียมเอกสารยื่นที่
ขนส่ง เพื่อต่อทะเบียนรถ (ป้ายภาษี) ของท่าน");
$pdf->MultiCell(188,6,$buss_name,0,'L',0);
$cline+=36;

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"*** แจ้งต่อประกันตามวันที่กำหนด แต่นัดชำระเงินไม่เกินวันสิ้นสุดความคุ้มครองประกันเดิม ***");
$pdf->MultiCell(188,6,$buss_name,0,'L',0);
$cline+=12;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"          หากท่านต้องการข้อมูลเพิ่มเติม หรือ มีการโอนเงินมา กรุณาติดต่อกลับ ฝ่ายประกันภัยรถยนต์ 
บริษัท $get_company_thainame (สาขาจรัญสนิทวงศ์) โทร 02-8825533 ต่อ 3");
$pdf->MultiCell(188,6,$buss_name,0,'L',0);
$cline+=18;

$pdf->SetXY(20,$cline);
$buss_name=iconv('UTF-8','windows-874',"                              ขอแสดงความนับถือ");
$pdf->MultiCell(188,6,$buss_name,0,'C',0);
$cline+=75;

$pdf->SetXY(0,$cline-13);
$buss_name=iconv('UTF-8','windows-874',"- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ");
$pdf->MultiCell(209,6,$buss_name,0,'L',0);

$pdf->Image('../images/head_'.$get_company.'_small.jpg',0,$cline-8,'149','23'); //head small
$cline+=30;

$pdf->SetXY(50,$cline);
$buss_name=iconv('UTF-8','windows-874',"ส่ง
$long_name
$dtl_ads_data_show");
$pdf->MultiCell(150,6,$buss_name,0,'L',0);

//Insert
//$rs1 = pg_query("select letter.\"gen_cusletid\"('$IDNO');");
//$gen_cusletid = pg_fetch_result($rs1,0);

$rs2 = pg_query("select letter.\"gen_sendid\"('$now_date');");
$gen_sendid = pg_fetch_result($rs2,0);

/*
$in_sql="insert into \"letter\".\"send_address\" (\"CusLetID\",\"IDNO\",\"record_date\",\"name\",\"active\",\"userid\",\"dtl_ads\",\"CusState\") values ('$gen_cusletid','$IDNO','$now_date','$long_name','TRUE','$get_userid','$dtl_ads_data_show','$CusState');";
if($result1 = pg_query($in_sql)){

}else{
    $status += 1;
}
*/

$in_sql2="insert into \"letter\".\"send_detail\" (\"send_date\",\"sendID\",\"CusLetID\",\"detail\",\"userid\") values ('$now_date','$gen_sendid','$CusLetID','4','$get_userid');";
if($result2 = pg_query($in_sql2)){

}else{
    $status += 1;
}
//End Insert

}

if($status != 0){
    pg_query("ROLLBACK");
    echo "<div align=center>เกิดข้อผิดผลาด ไม่สามารถบันทึกข้อมูลได้</div>";
}else{
    pg_query("COMMIT");
    $pdf->Output();
}
?>