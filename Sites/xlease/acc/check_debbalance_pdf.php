<?php
set_time_limit(0);
include("../config/config.php");

$nowdate = nowDate();//ดึง วันที่จาก server
$date = pg_escape_string($_GET['date']);
$yy = pg_escape_string($_GET['yy']);

$yy_plus = $yy+543;
$st_date_del_1year =date("Y-m-d", strtotime("-1 year",strtotime($date)));
$st_date_positive_1day =date("Y-m-d", strtotime("+1 day",strtotime($st_date_del_1year)));

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',13);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(190,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$border = 0;

$pdf->SetFont('AngsanaNew','B',17);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานตรวจสอบลูกหนี้คงเหลือ");
$pdf->MultiCell(190,4,$title,$border,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,$border,'C',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"แสดงข้อมูลของ วันที่ $st_date_del_1year ลูกหนี้ปี $yy_plus");
$pdf->MultiCell(190,4,$buss_name,$border,'L',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,$border,'R',0);

$pdf->SetXY(10,26);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,$border,'C',0);

$pdf->SetXY(10,32);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,$border,'C',0);

$pdf->SetXY(35,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(70,4,$buss_name,$border,'C',0);

$pdf->SetXY(105,32);
$buss_name=iconv('UTF-8','windows-874',"ยอดปีเก่า");
$pdf->MultiCell(25,4,$buss_name,$border,'C',0);

$pdf->SetXY(130,32);
$buss_name=iconv('UTF-8','windows-874',"ยอดในปีนี้");
$pdf->MultiCell(25,4,$buss_name,$border,'C',0);

$pdf->SetXY(155,32);
$buss_name=iconv('UTF-8','windows-874',"ยอดยกไป");
$pdf->MultiCell(25,4,$buss_name,$border,'C',0);

$pdf->SetXY(180,32);
$buss_name=iconv('UTF-8','windows-874',"หมายเหตุ");
$pdf->MultiCell(20,4,$buss_name,$border,'C',0);

$pdf->SetXY(10,33);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

//Data
$cline = 38;

$qry=pg_query("SELECT * FROM account.\"debtbalance\" WHERE \"acclosedate\" = '$st_date_del_1year' AND \"custyear\"='$yy' ORDER BY \"idno\",\"acclosedate\" ");
$qry_num = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $j++;
    
    if($j==40){
        $pdf->AddPage();
        $j=0;
        $cline = 38;
        
        $pdf->SetFont('AngsanaNew','B',17);
        $pdf->SetXY(10,10);
        $title=iconv('UTF-8','windows-874',"รายงานตรวจสอบลูกหนี้คงเหลือ");
        $pdf->MultiCell(190,4,$title,$border,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(10,16);
        $buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
        $pdf->MultiCell(190,4,$buss_name,$border,'C',0);

        $pdf->SetXY(10,25);
        $buss_name=iconv('UTF-8','windows-874',"แสดงข้อมูลของ วันที่ $st_date_del_1year ลูกหนี้ปี $yy_plus");
        $pdf->MultiCell(190,4,$buss_name,$border,'L',0);

        $pdf->SetXY(10,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(190,4,$buss_name,$border,'R',0);

        $pdf->SetXY(10,26);
        $buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
        $pdf->MultiCell(190,4,$buss_name,$border,'C',0);

        $pdf->SetXY(10,32);
        $buss_name=iconv('UTF-8','windows-874',"IDNO");
        $pdf->MultiCell(25,4,$buss_name,$border,'C',0);

        $pdf->SetXY(35,32);
        $buss_name=iconv('UTF-8','windows-874',"ชื่อ");
        $pdf->MultiCell(70,4,$buss_name,$border,'C',0);

        $pdf->SetXY(105,32);
        $buss_name=iconv('UTF-8','windows-874',"ยอดปีเก่า");
        $pdf->MultiCell(25,4,$buss_name,$border,'C',0);

        $pdf->SetXY(130,32);
        $buss_name=iconv('UTF-8','windows-874',"ยอดในปีนี้");
        $pdf->MultiCell(25,4,$buss_name,$border,'C',0);

        $pdf->SetXY(155,32);
        $buss_name=iconv('UTF-8','windows-874',"ยอดยกไป");
        $pdf->MultiCell(25,4,$buss_name,$border,'C',0);

        $pdf->SetXY(180,32);
        $buss_name=iconv('UTF-8','windows-874',"หมายเหตุ");
        $pdf->MultiCell(20,4,$buss_name,$border,'C',0);

        $pdf->SetXY(10,33);
        $buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
        $pdf->MultiCell(190,4,$buss_name,0,'C',0);
    }
    
    $acclosedate = $res["acclosedate"];
    $idno = $res["idno"];
    $cusid = $res["cusid"];
    $custyear = $res["custyear"];
    $monthly = $res["monthly"];
    $totaldue = $res["totaldue"];
    $notpaid = $res["notpaid"];
    $vatpayready = $res["vatpayready"];

    $full_name = "";
    $sql_fname = pg_query("SELECT \"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\" FROM \"Fa1\" WHERE \"CusID\"='$cusid' ");
    if($rs_fname = pg_fetch_array($sql_fname)){
        $full_name = trim($rs_fname['A_FIRNAME'])." ".trim($rs_fname['A_NAME'])." ".trim($rs_fname['A_SIRNAME']);
    }

    $fr = pg_query("SELECT COUNT(\"R_DueNo\") as countfr FROM \"Fr\" WHERE \"IDNO\"='$idno' AND \"Cancel\" = 'FALSE' AND (\"R_Date\" BETWEEN '$st_date_positive_1day' AND '$date') AND \"CustYear\"='$yy' ");
    if($rs_fr = pg_fetch_array($fr)){
        $countfr = $rs_fr['countfr'];
    }
    
    $x = $notpaid-$countfr;
    
    $m1 = $monthly*$notpaid;
    $m2 = $monthly*$countfr;
    $m3 = $m1-$m2;
    
    $s1 += $m1;
    $s2 += $m2;
    $s3 += $m3;
    
    $pdf->SetXY(10,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$idno");
    $pdf->MultiCell(25,4,$buss_name,$border,'C',0);

    $pdf->SetXY(35,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$full_name");
    $pdf->MultiCell(70,4,$buss_name,$border,'L',0);
    
    $pdf->SetXY(105,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($m1,2));
    $pdf->MultiCell(25,4,$buss_name,$border,'R',0);

    $pdf->SetXY(130,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($m2,2));
    $pdf->MultiCell(25,4,$buss_name,$border,'R',0);

    $pdf->SetXY(155,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($m3,2));
    $pdf->MultiCell(25,4,$buss_name,$border,'R',0);

    $pdf->SetXY(180,$cline);
    
    if($x == 0){
        //ปิด บัญชี
        $buss_name=iconv('UTF-8','windows-874',"AccClose");
    }elseif($x > 0){
        //ปกติ
        $buss_name=iconv('UTF-8','windows-874',"");
    }elseif($x < 0){
        //ผิดปกติ
        $buss_name=iconv('UTF-8','windows-874',"Error");
    }
    
    $pdf->MultiCell(20,4,$buss_name,$border,'C',0);
    
    $cline+=6;
}


    if($j==40){
        $pdf->AddPage();
        $j=0;
        $cline = 38;
        
        $pdf->SetFont('AngsanaNew','B',17);
        $pdf->SetXY(10,10);
        $title=iconv('UTF-8','windows-874',"รายงานตรวจสอบลูกหนี้คงเหลือ");
        $pdf->MultiCell(190,4,$title,$border,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(10,16);
        $buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
        $pdf->MultiCell(190,4,$buss_name,$border,'C',0);

        $pdf->SetXY(10,25);
        $buss_name=iconv('UTF-8','windows-874',"แสดงข้อมูลของ วันที่ $st_date_del_1year ลูกหนี้ปี $yy_plus");
        $pdf->MultiCell(190,4,$buss_name,$border,'L',0);

        $pdf->SetXY(10,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(190,4,$buss_name,$border,'R',0);

        $pdf->SetXY(10,26);
        $buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
        $pdf->MultiCell(190,4,$buss_name,$border,'C',0);

        $pdf->SetXY(10,32);
        $buss_name=iconv('UTF-8','windows-874',"IDNO");
        $pdf->MultiCell(25,4,$buss_name,$border,'C',0);

        $pdf->SetXY(35,32);
        $buss_name=iconv('UTF-8','windows-874',"ชื่อ");
        $pdf->MultiCell(70,4,$buss_name,$border,'C',0);

        $pdf->SetXY(105,32);
        $buss_name=iconv('UTF-8','windows-874',"ยอดปีเก่า");
        $pdf->MultiCell(25,4,$buss_name,$border,'C',0);

        $pdf->SetXY(130,32);
        $buss_name=iconv('UTF-8','windows-874',"ยอดในปีนี้");
        $pdf->MultiCell(25,4,$buss_name,$border,'C',0);

        $pdf->SetXY(155,32);
        $buss_name=iconv('UTF-8','windows-874',"ยอดยกไป");
        $pdf->MultiCell(25,4,$buss_name,$border,'C',0);

        $pdf->SetXY(180,32);
        $buss_name=iconv('UTF-8','windows-874',"หมายเหตุ");
        $pdf->MultiCell(20,4,$buss_name,$border,'C',0);

        $pdf->SetXY(10,33);
        $buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
        $pdf->MultiCell(190,4,$buss_name,0,'C',0);
    }


$pdf->SetXY(10,$cline-5);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,$border,'C',0);

$cline+=1;

$pdf->SetXY(35,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $qry_num รายการ");
$pdf->MultiCell(70,4,$buss_name,$border,'R',0);

$pdf->SetXY(105,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s1,2));
$pdf->MultiCell(25,4,$buss_name,$border,'R',0);

$pdf->SetXY(130,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s2,2));
$pdf->MultiCell(25,4,$buss_name,$border,'R',0);

$pdf->SetXY(155,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s3,2));
$pdf->MultiCell(25,4,$buss_name,$border,'R',0);

$pdf->SetXY(10,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,$border,'C',0);


$pdf->Output();
?>