<?php
session_start();
include("../config/config.php");

 $f_idno=$_GET["ID"];
 
 $qry_print=pg_query("
                      select conversiondatetothaitext(A.\"P_STDATE\") AS datest,A.* ,B.*,C.*,D.*
					  
					  FROM \"Fp\" A  LEFT OUTER JOIN \"ContactCus\" B ON A.\"CusID\" = B. \"CusID\"
					                 LEFT OUTER JOIN \"VCarregistemp\" C ON A.\"IDNO\" = C. \"IDNO\"
									 LEFT OUTER JOIN \"Fa1\" D ON A.\"CusID\" = D. \"CusID\"
									 				 
					  where A.\"IDNO\" = '$f_idno'				 
                       ");

require('../thaipdfclass.php');

$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();


//$pdf->Image('page1.jpg',0,0,210,290);

$pdf->SetFont('AngsanaNew','B',16);

$res_fp=pg_fetch_array($qry_print);

$av_cname=trim($res_fp["A_FIRNAME"]).trim($res_fp["A_NAME"])." ".trim($res_fp["A_SIRNAME"]);

$fp_regis=$res_fp["C_REGIS"];
$fp_reg_by=trim($res_fp["C_REGIS_BY"]);



//$pdf->Text(20,20+$arow,$res_fp["IDNO"]); //เลขที่สัญญา

$av_stdate=iconv('UTF-8','windows-874',$res_fp["datest"]);
$pdf->Text(55,61,$av_stdate); //วันที่ทำสัญญา



 $qry_contactcus=pg_query("
                      select  X.*,Y.*,Z.*,c_datethai(\"N_OT_DATE\") AS c_dateth
					  
					  FROM \"ContactCus\" X  LEFT OUTER JOIN \"Fa1\" Y ON X.\"CusID\" = Y. \"CusID\"
					                 LEFT OUTER JOIN \"Fn\" Z ON Y.\"CusID\" = Z. \"CusID\"
									
									 
									 
					  where X.\"IDNO\"='$f_idno' AND \"CusState\"!=0 order by \"CusState\" ");	
					  
  $crow=5; 
  while($res_cc=pg_fetch_array($qry_contactcus))
  {
     $arow=6.5;
     $acol=10;
	
    $av_fullname=trim($res_cc["A_FIRNAME"]).trim($res_cc["A_NAME"])." ".trim($res_cc["A_SIRNAME"]);
    
	
    $av_names=iconv('UTF-8','windows-874',$av_fullname);
    $pdf->Text(33,56+$arow+$crow,$av_names);
	
	$av_san=iconv('UTF-8','windows-874',$res_cc["N_SAN"]);
    $pdf->Text(140,56+$arow+$crow,$av_san);
	
	
	$av_age=iconv('UTF-8','windows-874',$res_cc["N_AGE"]);
    $pdf->Text(180,56+$arow+$crow,$av_age);
	
	$av_card=iconv('UTF-8','windows-874',$res_cc["N_CARD"]);
	
	$av_cardid=iconv('UTF-8','windows-874',$res_cc["N_IDCARD"]);
	if($res_cc["N_IDCARD"]=="")
	{
		$av_cardid=iconv('UTF-8','windows-874',$res_cc["N_CARDREF"]);
	}
	else
	{
		$av_card=iconv('UTF-8','windows-874',"บัตรประชาชน");
	}
	$pdf->Text(33,62+$arow+$crow,$av_card);
	$pdf->Text(105,62+$arow+$crow,$av_cardid);
	
	$av_card_date=iconv('UTF-8','windows-874',$res_cc["c_dateth"]);
    $pdf->Text(170,62+$arow+$crow,$av_card_date);
	
	$av_cardby=iconv('UTF-8','windows-874',$res_cc["N_BY"]);
    $pdf->Text(30,68+$arow+$crow,$av_cardby);
	
	$av_pair=iconv('UTF-8','windows-874',$res_cc["A_PAIR"]);
    $pdf->Text(115,68+$arow+$crow,$av_pair);
	
	$av_fullAdd=trim($res_cc["A_NO"])." ม.".trim($res_cc["A_SUBNO"])." ซอย ".trim($res_cc["A_SOI"])." ถนน ".trim($res_cc["A_RD"])." แขวง/ตำบล ".trim($res_cc["A_TUM"]);
	
	$av_add=iconv('UTF-8','windows-874',$av_fullAdd);
    $pdf->Text(30,75+$arow+$crow,$av_add);
	
	
	$av_prov="เขต/อำเภอ ".trim($res_cc["A_AUM"])." จังหวัด ".trim($res_cc["A_PRO"]);
	
	$av_pro=iconv('UTF-8','windows-874',$av_prov);
    $pdf->Text(30,80+$arow+$crow,$av_pro);
	
	
	
	$crow=$crow+31;
   
  }
    
  $av_idno=iconv('UTF-8','windows-874',$f_idno);
  $pdf->Text(160,190.5+$arow,$av_idno); //เลขที่ idno
  
  
  $av_stdate2=iconv('UTF-8','windows-874',$res_fp["datest"]);
  $pdf->Text(30,197+$arow,$av_stdate2); //ฉบับลงวันที่
  
  $av_regisc=iconv('UTF-8','windows-874',$fp_regis);
  $pdf->Text(95,197+$arow,$av_regisc); //ทะเบียนรถ
  
  $av_regby=iconv('UTF-8','windows-874',$fp_reg_by);
  $pdf->Text(135,197+$arow,$av_regby); //จังหวัดจดทะเบียน
  
  
  $av_ccname=iconv('UTF-8','windows-874',$av_cname);
  $pdf->Text(53,203+$arow,$av_ccname); //ชื่อ - นามสกุล
   


$pdf->Output();

?>
