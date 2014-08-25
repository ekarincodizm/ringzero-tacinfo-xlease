<?php
include("../config/config.php");

$yy = pg_escape_string($_GET['yy']);
//$nowdate = date("Y/m/d");

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

$qry_name=pg_query("SELECT \"acclosedate\" FROM account.\"VSOYEndYear\" where EXTRACT(YEAR FROM \"acclosedate\")='$yy' ");
if($res_name=pg_fetch_array($qry_name)){
    $acclosedate = $res_name["acclosedate"];
}

$trndate=pg_query("select conversiondatetothaitext('$acclosedate')");  
$restrn=pg_fetch_result($trndate,0);

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
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
$pdf->SetXY(10,10);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,18);
$title=iconv('UTF-8','windows-874',"ตารางแยกอายุลูกหนี้ (สาขาจรัญสนิทวงศ์)");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetXY(10,25);
$title=iconv('UTF-8','windows-874',"ณ $restrn");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,35); 
$buss_name=iconv('UTF-8','windows-874',"อายุหนี้");
$pdf->MultiCell(40,12,$buss_name,1,'C',0);

$pdf->SetXY(50,35); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนราย");
$pdf->MultiCell(20,12,$buss_name,1,'C',0);

$pdf->SetXY(70,35); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่าลูกหนี้คงเหลือ");
$pdf->MultiCell(60,6,$buss_name,1,'C',0);

$pdf->SetXY(70,41); 
$buss_name=iconv('UTF-8','windows-874',"บาท");
$pdf->MultiCell(40,6,$buss_name,1,'C',0);

$pdf->SetXY(110,41); 
$buss_name=iconv('UTF-8','windows-874',"%");
$pdf->MultiCell(20,6,$buss_name,1,'C',0);

$pdf->SetXY(130,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผลคงเหลือ");
$pdf->MultiCell(30,12,$buss_name,1,'C',0);

$pdf->SetXY(160,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้สุทธิ");
$pdf->MultiCell(20,12,$buss_name,1,'C',0);

$pdf->SetXY(180,35); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่าลูกหนี้สุทธิ
หลังหักหลักประกัน");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);

$pdf->SetXY(205,35); 
$buss_name=iconv('UTF-8','windows-874',"อัตราสำรอง");
$pdf->MultiCell(20,12,$buss_name,1,'C',0);

$pdf->SetXY(225,35); 
$buss_name=iconv('UTF-8','windows-874',"ค่าเผื่อหนี้สงสัยจะสูญ");
$pdf->MultiCell(60,6,$buss_name,1,'C',0);

$pdf->SetXY(225,41); 
$buss_name=iconv('UTF-8','windows-874',"หนี้สงสัยจะสูญ");
$pdf->MultiCell(40,6,$buss_name,1,'C',0);

$pdf->SetXY(265,41); 
$buss_name=iconv('UTF-8','windows-874',"%");
$pdf->MultiCell(20,6,$buss_name,1,'C',0);

//-----------------------------------------------------------------//

$s_qry_name=pg_query("select A.*,B.* from account.\"effsoyaddcom\" A 
LEFT OUTER JOIN account.\"VSOYEndYear\" B ON A.\"idno\"=B.\"idno\" 
where EXTRACT(YEAR FROM B.\"acclosedate\")='$yy' ");
$s_rows = pg_num_rows($s_qry_name);
while($s_res_name=pg_fetch_array($s_qry_name)){
    $s_urtotal= $s_res_name["urtotal"];
    $s_aroutstanding= $s_res_name["aroutstanding"];
    $s_aroutafterguarantee = $s_res_name["aroutafterguarantee"];
    $s_writeoffrate = $s_res_name["writeoffrate"];  
    $s_backupwriteoff = $s_res_name["backupwriteoff"];
    $s_rlremain = $s_res_name["rlremain"];
    $s_rlall = $s_res_name["rlall"];
    
    $s_sum_urtotalall += $s_urtotal;
    $s_sum_rlall += $s_rlall;
    $s_sum_rlremain += $s_rlremain;
    $s_sum_aroutstanding += $s_aroutstanding;
    $s_sum_urtotal += $s_aroutstanding-$s_urtotal;
    $s_sum_aroutafterguarantee += $s_aroutafterguarantee;
    $s_sum_backupwriteoff += $s_backupwriteoff;
}


$cline = 47;
$cw = 8;

$qry_name=pg_query("select A.*,B.* from account.\"effsoyaddcom\" A 
LEFT OUTER JOIN account.\"VSOYEndYear\" B ON A.\"idno\"=B.\"idno\"
WHERE EXTRACT(YEAR FROM B.\"acclosedate\")='$yy' and A.\"overdue\" = '0' ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $urtotal= $res_name["urtotal"];
    $aroutstanding= $res_name["aroutstanding"];
    $aroutafterguarantee = $res_name["aroutafterguarantee"];
    $writeoffrate = $res_name["writeoffrate"];  
    $backupwriteoff = $res_name["backupwriteoff"];
    $rlremain = $res_name["rlremain"];
    $rlall = $res_name["rlall"];
    
    $sum_urtotalall += $urtotal;
    $sum_rlall += $rlall;
    $sum_rlremain += $rlremain;
    $sum_aroutstanding += $aroutstanding;
    $sum_urtotal += $aroutstanding-$urtotal;
    $sum_aroutafterguarantee += $aroutafterguarantee;
    $sum_backupwriteoff += $backupwriteoff;
    
    $sum_aroutstanding_percent = ($sum_aroutstanding*100)/$s_sum_aroutstanding;
    $sum_backupwriteoff_percent = ($sum_backupwriteoff*100)/$s_sum_backupwriteoff;
}


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,$cline); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ไม่ค้างชำระ");
$pdf->MultiCell(40,$cw,$buss_name,1,'L',0);

$pdf->SetXY(50,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$rows");
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(70,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutstanding,2));
$pdf->MultiCell(40,$cw,$buss_name,1,'R',0);

$pdf->SetXY(110,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutstanding_percent,2));
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_urtotalall,2));
$pdf->MultiCell(30,$cw,$buss_name,1,'R',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_urtotal,2));
$pdf->MultiCell(20,$cw,$buss_name,1,'R',0);

$pdf->SetXY(180,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutafterguarantee,2));
$pdf->MultiCell(25,$cw,$buss_name,1,'R',0);

$pdf->SetXY(205,$cline); 
$buss_name=iconv('UTF-8','windows-874',$writeoffrate);
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(225,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_backupwriteoff,2));
$pdf->MultiCell(40,$cw,$buss_name,1,'R',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_backupwriteoff_percent,2));
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);


$cline = 47+8;
$cw = 8;
for($i=1;$i<=12;$i++){

$sum_urtotalall = 0;
$sum_urtotal = 0;
$writeoffrate = 0;
$sum_rlall = 0;
$sum_rlremain = 0;
$sum_aroutstanding = 0;
$sum_aroutafterguarantee = 0;
$sum_backupwriteoff = 0;
$sum_rlall_percent = 0;
$sum_backupwriteoff_percent = 0;
$sum_aroutstanding_percent = 0;
 
$qry_name=pg_query("select A.*,B.* from account.\"effsoyaddcom\" A 
LEFT OUTER JOIN account.\"VSOYEndYear\" B ON A.\"idno\"=B.\"idno\"
WHERE EXTRACT(YEAR FROM B.\"acclosedate\")='$yy' and A.\"overdue\" = '$i' ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $urtotal= $res_name["urtotal"];
    $aroutstanding= $res_name["aroutstanding"];
    $aroutafterguarantee = $res_name["aroutafterguarantee"];
    $writeoffrate = $res_name["writeoffrate"];  
    $backupwriteoff = $res_name["backupwriteoff"];
    $rlremain = $res_name["rlremain"];
    $rlall = $res_name["rlall"];
    
    $sum_urtotalall += $urtotal;
    $sum_rlall += $rlall;
    $sum_rlremain += $rlremain;
    $sum_aroutstanding += $aroutstanding;
    $sum_urtotal += $aroutstanding-$urtotal;
    $sum_aroutafterguarantee += $aroutafterguarantee;
    $sum_backupwriteoff += $backupwriteoff;
    
    $sum_aroutstanding_percent = ($sum_aroutstanding*100)/$s_sum_aroutstanding;
    $sum_backupwriteoff_percent = ($sum_backupwriteoff*100)/$s_sum_backupwriteoff;
}


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,$cline); 
$buss_name=iconv('UTF-8','windows-874',"ค้างชำระ $i เดือน");
$pdf->MultiCell(40,$cw,$buss_name,1,'L',0);

$pdf->SetXY(50,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$rows");
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(70,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutstanding,2));
$pdf->MultiCell(40,$cw,$buss_name,1,'R',0);

$pdf->SetXY(110,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutstanding_percent,2));
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_urtotalall,2));
$pdf->MultiCell(30,$cw,$buss_name,1,'R',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_urtotal,2));
$pdf->MultiCell(20,$cw,$buss_name,1,'R',0);

$pdf->SetXY(180,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutafterguarantee,2));
$pdf->MultiCell(25,$cw,$buss_name,1,'R',0);

$pdf->SetXY(205,$cline); 
$buss_name=iconv('UTF-8','windows-874',$writeoffrate);
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(225,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_backupwriteoff,2));
$pdf->MultiCell(40,$cw,$buss_name,1,'R',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_backupwriteoff_percent,2));
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$cline+=8;
}

$sum_urtotalall = 0;
$sum_urtotal = 0;
$writeoffrate = 0;
$sum_rlall = 0;
$sum_rlremain = 0;
$sum_aroutstanding = 0;
$sum_aroutafterguarantee = 0;
$sum_backupwriteoff = 0;
$sum_rlall_percent = 0;
$sum_backupwriteoff_percent = 0;
$sum_aroutstanding_percent = 0;

$qry_name=pg_query("select A.*,B.* from account.\"effsoyaddcom\" A 
LEFT OUTER JOIN account.\"VSOYEndYear\" B ON A.\"idno\"=B.\"idno\"
WHERE EXTRACT(YEAR FROM B.\"acclosedate\")='$yy' and A.\"overdue\" > '12' ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $urtotal= $res_name["urtotal"];
    $aroutstanding= $res_name["aroutstanding"];
    $aroutafterguarantee = $res_name["aroutafterguarantee"];
    $writeoffrate = $res_name["writeoffrate"];  
    $backupwriteoff = $res_name["backupwriteoff"];
    $rlremain = $res_name["rlremain"];
    $rlall = $res_name["rlall"];
    
    $sum_urtotalall += $urtotal;
    $sum_rlall += $rlall;
    $sum_rlremain += $rlremain;
    $sum_aroutstanding += $aroutstanding;
    $sum_urtotal += $aroutstanding-$urtotal;
    $sum_aroutafterguarantee += $aroutafterguarantee;
    $sum_backupwriteoff += $backupwriteoff;
    
    $sum_aroutstanding_percent = ($sum_aroutstanding*100)/$s_sum_aroutstanding;
    $sum_backupwriteoff_percent = ($sum_backupwriteoff*100)/$s_sum_backupwriteoff;
}


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,$cline); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้รอเรียกร้อง");
$pdf->MultiCell(40,$cw,$buss_name,1,'L',0);

$pdf->SetXY(50,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$rows");
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(70,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutstanding,2));
$pdf->MultiCell(40,$cw,$buss_name,1,'R',0);

$pdf->SetXY(110,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutstanding_percent,2));
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_urtotalall,2));
$pdf->MultiCell(30,$cw,$buss_name,1,'R',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_urtotal,2));
$pdf->MultiCell(20,$cw,$buss_name,1,'R',0);

$pdf->SetXY(180,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutafterguarantee,2));
$pdf->MultiCell(25,$cw,$buss_name,1,'R',0);

$pdf->SetXY(205,$cline); 
$buss_name=iconv('UTF-8','windows-874',$writeoffrate);
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(225,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_backupwriteoff,2));
$pdf->MultiCell(40,$cw,$buss_name,1,'R',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_backupwriteoff_percent,2));
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

//----------------------------------------------------------//

$cline += 8;
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,$cline); 
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(40,$cw,$buss_name,1,'C',0);

$pdf->SetXY(50,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$s_rows");
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(70,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_sum_aroutstanding,2));
$pdf->MultiCell(40,$cw,$buss_name,1,'R',0);

$pdf->SetXY(110,$cline); 
$buss_name=iconv('UTF-8','windows-874',"100");
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_sum_urtotalall,2));
$pdf->MultiCell(30,$cw,$buss_name,1,'R',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_sum_urtotal,2));
$pdf->MultiCell(20,$cw,$buss_name,1,'R',0);

$pdf->SetXY(180,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_sum_aroutafterguarantee,2));
$pdf->MultiCell(25,$cw,$buss_name,1,'R',0);

$pdf->SetXY(205,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$pdf->SetXY(225,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_sum_backupwriteoff,2));
$pdf->MultiCell(40,$cw,$buss_name,1,'R',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',"100");
$pdf->MultiCell(20,$cw,$buss_name,1,'C',0);

$cline += 16;
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,$cline); 
$buss_name=iconv('UTF-8','windows-874',"ในรายงานไม่รวม รอร้องเรียน");
$pdf->MultiCell(40,$cw,$buss_name,0,'L',0);

$pdf->SetXY(70,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_sum_aroutstanding-$sum_aroutstanding,2));
$pdf->MultiCell(40,$cw,$buss_name,0,'R',0);

$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_sum_urtotalall-$sum_urtotalall,2));
$pdf->MultiCell(30,$cw,$buss_name,0,'R',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_sum_urtotal-$sum_urtotal,2));
$pdf->MultiCell(20,$cw,$buss_name,0,'R',0);

$pdf->SetXY(225,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_sum_backupwriteoff-$sum_backupwriteoff,2));
$pdf->MultiCell(40,$cw,$buss_name,0,'R',0);

$pdf->Output();
?>