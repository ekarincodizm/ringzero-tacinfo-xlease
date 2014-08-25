<?php
include("../config/config.php");
$recid=pg_escape_string($_GET["rid"]);
$recpid=pg_escape_string($_GET["pid"]);


$qry_con=pg_query("select * from \"Fr\" WHERE \"R_Receipt\" ='$recid' ");
$resrec=pg_fetch_array($qry_con);
$rec_id=$resrec["R_Receipt"];
$rec_date=$resrec["R_Date"];
$idno=$resrec["IDNO"];
$r_dueno=$resrec["R_DueNo"];

$cuslist=pg_query("select A.*,B.* from \"VContact\" A  
                   LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\" = B.\"CusID\" 
                   WHERE A.\"IDNO\" ='$idno' ");

$rescus=pg_fetch_array($cuslist);


if(trim($rescus["A_SOI"])=="")
{
 $s_soi="";
}
else
{
 $s_soi=" ซอย ".trim($rescus["A_SOI"]);
}

if(trim($rescus["A_RD"])=="")
{
 $s_rd="";
}
else
{
 $s_rd=" ถนน ".trim($rescus["A_RD"]);
}



$cus_add=trim($rescus["A_NO"])."  ม.".trim($rescus["A_SUBNO"]).$s_soi." ".$s_rd."  ตำบล/แขวง ".trim($rescus["A_TUM"])."  อำเภอ/เขต ".trim($rescus["A_AUM"]);


if($rescus["C_REGIS"]=="")
{

$rec_regis="ทะเบียน ".$rescus["car_regis"];
$rec_cnumber="เลขถังแก๊ส ".$rescus["gas_number"];
$res_band="ยี่ห้อแก๊ส ".$rescus["gas_name"];


}
else
{

$rec_regis="ทะเบียน ".$rescus["C_REGIS"];
$rec_cnumber="เลขตัวถัง ".$rescus["C_CARNUM"];
$res_band="ยี่ห้อรถ ".$rescus["C_CARNUM"];
}

$p_month=$rescus["P_MONTH"];
$p_vat=$rescus["P_VAT"];



$sum_rec=$p_month+$p_vat;

$convthai=pg_query("select conversionnumtothaitext($sum_rec)");
$res_conv=pg_fetch_result($convthai,0);


$qry_cons=pg_query("select * from \"Fr\" WHERE  \"R_Receipt\" ='$recid' ");
$nurow=pg_num_rows($qry_cons);
while($ressum=pg_fetch_array($qry_cons))
{
  $qduno=pg_query("select * from \"CusPayment\" WHERE \"DueNo\"='$ressum[R_DueNo]' order by \"DueNo\"  ");
  $resdno=pg_fetch_array($qduno);
  
  $tmp_due=$tmp_due." ".$res_ddate=$resdno[DueDate];
  $tmp_dno=$tmp_dno." ".$res_dno=$resdno[DueNo];
  
}

$str_due=strlen($tmp_due);
$str_dno=strlen($tmp_dno);  

$st_dno=substr($tmp_dno,0,2);	
$end_dno=substr($tmp_dno,str_dno-2,2);	
$st_due=substr($tmp_due,0,11); 
$end_due=substr($tmp_due,$str_due-11,11); 

if($nurow==1)
{
  
  $de_total=" งวดที่ ".$r_dueno." / ".$rescus["P_TOTAL"];
  $dtl_pay="";
  
}
else
{
  $de_total=" งวดที่ ".$st_dno." - ".$end_dno ." / ".$rescus["P_TOTAL"];
  $dtl_pay= "(".trim($st_due)." - ".trim($end_due).")";
}


           
			 




$fullname=$rescus["full_name"];
//echo "<br>".$rec_regis;
//echo "<br>".$rec_cnumber;
//echo "<br>".$rec_total;

//echo "<br>".$de_total;
//echo "<br>".$dtl_pay;
//echo "<br>".$p_month;
//echo "<br>".$p_vat;
//echo "<br>".$res_conv;

//echo "<br>".$tmp_due;

require('../thaipdfclass.php');


$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();
$pdf->SetFont('AngsanaNew','B',16);

$pdf->Image('image/paper_recfull.jpg',0,0,210,290);



//Real //

$pdf->Text(168,25.0+$arow,$rec_id); //เลขที่ใบเสร็จ



$qry_d=pg_query("select conversiondatetothaitext('$rec_date')");
$res_cdate=pg_fetch_result($qry_d,0);
$av_daterec=iconv('UTF-8','windows-874',$res_cdate);
$pdf->Text(150,33.5+$arow,$av_daterec); //วันที่ใบเสร็จ


$av_fullname=iconv('UTF-8','windows-874',$fullname); 
$pdf->Text(10,45+$arow,$av_fullname); //ชื่อ - นามสกุล


$pdf->SetXY(9,46+$arow);
$cus_add_icon=iconv('UTF-8','windows-874',$cus_add);
$pdf->MultiCell(120,5,$cus_add_icon,0,'L',0);//ที่อยู่

$pdf->SetXY(9,51+$arow);
$cus_add_pro=iconv('UTF-8','windows-874',"จังหวัด ".trim($rescus["A_PRO"]));
$pdf->MultiCell(120,5,$cus_add_pro,0,'L',0);//จังหวัด

$av_band=iconv('UTF-8','windows-874',$res_band); 
$pdf->Text(10,63+$arow,$av_band); //ยี่ห้อรถ

$av_regis=iconv('UTF-8','windows-874',$rec_regis); 
$pdf->Text(10,68+$arow,$av_regis); //ทะเบียน


$av_cnumber=iconv('UTF-8','windows-874',$rec_cnumber); 
$pdf->Text(10,73+$arow,$av_cnumber); //เลขตัวถัง



$av_total_con=iconv('UTF-8','windows-874',$de_total); 
$pdf->Text(10,80+$arow,$av_total_con); //รายการจ่าย


$av_pay=iconv('UTF-8','windows-874',$dtl_pay); 
$pdf->Text(10,85+$arow,$av_pay); //เดือนที่จ่าย


$pdf->SetXY(140,93+$arow);
$av_ttotal=iconv('UTF-8','windows-874',"ค่างวดก่อนภาษี");
$pdf->MultiCell(50,5,$av_ttotal,0,'L',0);// งวด

$pdf->SetXY(140,99+$arow);
$av_tvat=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม ");
$pdf->MultiCell(50,5,$av_tvat,0,'L',0);// vat




$pdf->SetXY(150,93+$arow);
$av_total=iconv('UTF-8','windows-874',number_format($p_month*$nurow,2));
$pdf->MultiCell(50,5,$av_total,0,'R',0);//จังหวัด

$pdf->SetXY(150,99+$arow);
$av_vat=iconv('UTF-8','windows-874',number_format($p_vat*$nurow,2));
$pdf->MultiCell(50,5,$av_vat,0,'R',0);//จังหวัด




$sumttotal=($p_vat*$nurow)+($p_month*$nurow);


$pdf->SetXY(150,109+$arow);
$av_sum=iconv('UTF-8','windows-874',number_format($sumttotal,2));
$pdf->MultiCell(50,5,$av_sum,0,'R',0);//total



$trntotal=pg_query("select conversionnumtothaitext($sumttotal)");
$restrn=pg_fetch_result($trntotal,0);


$pdf->SetXY(20,109+$arow);
$av_trnnumber=iconv('UTF-8','windows-874',"=(".$restrn.")=");
$pdf->MultiCell(150,5,$av_trnnumber,0,'L',0);//แปลงตัวหนังสือไทย


//end real //


//copy //
$pdf->Text(168,179.0+$arow,$rec_id); //เลขที่ใบเสร็จ

$pdf->Text(150,188.1+$arow,$av_daterec); //วันที่ใบเสร็จ

$pdf->Text(10,200+$arow,$av_fullname); //ชื่อ - นามสกุล


$pdf->SetXY(10,201+$arow);
$pdf->MultiCell(120,5,$cus_add_icon,0,'L',0);//ที่อยู่
$pdf->SetXY(10,206+$arow);
$pdf->MultiCell(120,5,$cus_add_pro,0,'L',0);//จังหวัด


$pdf->Text(10,218+$arow,$av_band); //ยี่ห้อรถ


$pdf->Text(10,223+$arow,$av_regis."  ".$av_cnumber); //ทะเบียน


$pdf->Text(10,235+$arow,$av_total_con); //รายการจ่าย



$pdf->Text(10,235+$arow,$av_pay); //เดือนที่จ่าย


$pdf->SetXY(140,235+$arow);
$pdf->MultiCell(50,5,$av_ttotal,0,'L',0);// งวด

$pdf->SetXY(140,243+$arow);
$pdf->MultiCell(50,5,$av_tvat,0,'L',0);// vat




$pdf->SetXY(150,234+$arow);
$pdf->MultiCell(50,5,$av_total,0,'R',0);//

$pdf->SetXY(150,242+$arow);
$pdf->MultiCell(50,5,$av_vat,0,'R',0);//จ


$pdf->SetXY(150,255+$arow);
$pdf->MultiCell(50,5,$av_sum,0,'R',0);//total





$pdf->SetXY(20,255+$arow);
$pdf->MultiCell(150,5,$av_trnnumber,0,'L',0);//แปลงตัวหนังสือไทย


// end copy //

$pdf->Output();


?>