<?php
session_start();
include("../config/config.php");
require('../thaipdfclass.php');

$frow=3;

$f_idno=$_GET["ID"];
 
//หาชื่อผู้กู้หลัก
$qry_name=pg_query("select A.\"IDNO\",A.\"CusID\",B.* 
FROM \"Fp\" A 
LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\"=B.\"CusID\"
WHERE A.\"IDNO\"='$f_idno'");
$res_name=pg_fetch_array($qry_name);
$str_nameidno=trim($res_name["A_FIRNAME"])." ".trim($res_name["A_NAME"])." ".trim($res_name["A_SIRNAME"]);
 
//รายละเอียดสัญญา//
$qry_print=pg_query("select to_char(A.\"P_STDATE\",'dd/mm/yyyy') AS datest,A.*,C.*,D.*
FROM \"Fp\" A  LEFT OUTER JOIN \"VCarregistemp\" C ON A.\"IDNO\" = C. \"IDNO\"
LEFT OUTER JOIN \"Fa1\" D ON A.\"CusID\" = D. \"CusID\"
where A.\"IDNO\" = '$f_idno'");
 

 
$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();


$pdf->SetFont('AngsanaNew','B',16);





/*$name_comp="สาขาสำนักงานใหญ่ ตั้งอยู่อาคาร เลขที่ 999 หมู่ 10";
$name_comp_ads="ถนน นวมินทร์ แขวง  คลองกุ่ม เขต บึงกุ่ม  จังหวัด กทม. T.944-2000";*/

$name_comp="สาขาสำนักงานใหญ่ ตั้งอยู่อาคาร เลขที่ 555";
$name_comp_ads="ถนนนวมินทร์ แขวงคลองกุ่ม เขตบึงกุ่ม  จังหวัด กทม. 10240 โทร 02-744-2222";


$name_cc=iconv('UTF-8','windows-874',$name_comp);
$pdf->Text(128,43+$frow,$name_cc); //ชื่อบริษัท


$name_ads=iconv('UTF-8','windows-874',$name_comp_ads);
$pdf->Text(25,50+$frow,$name_ads); //ที่อยู่



$res_idno=pg_fetch_array($qry_print);
$av_stdate=iconv('UTF-8','windows-874',$res_idno["datest"]);
$pdf->Text(172,50+$frow,$av_stdate); //วันที่ทำสัญญา


//หาผู้ค้ำ
$qry_print_dat=pg_query("select b.\"A_FIRNAME\",b.\"A_NAME\",b.\"A_SIRNAME\",b.\"A_PAIR\",c.\"N_OT_DATE\" AS otdate,
b.\"A_NO\",b.\"A_SUBNO\",b.\"A_SOI\",b.\"A_RD\",b.\"A_TUM\",b.\"A_AUM\",b.\"A_PRO\",b.\"A_POST\",c.*
from \"Fp_Fa1\" a
left join \"Fa1\" b on a.\"CusID\"=b.\"CusID\" 
left join \"Fn\" c on b.\"CusID\"=c.\"CusID\"
where a.\"IDNO\" = '$f_idno' AND a.\"CusState\"<>0  and \"edittime\"=0
order by a.\"CusState\"");	
				   
$num_grt=pg_num_rows($qry_print_dat);
 
$arow=3;
$crow=3;
$nn=1;
while($res_cc=pg_fetch_array($qry_print_dat))
{

	$av_cname=trim($res_cc["A_FIRNAME"])." ".trim($res_cc["A_NAME"])." ".trim($res_cc["A_SIRNAME"]);
 
 /* Detail */

     $av_names=iconv('UTF-8','windows-874',$av_cname);
     $pdf->Text(40,55+$arow+$crow,$av_names); //ชื่อผู้ทำสัญญา

     $av_san=iconv('UTF-8','windows-874',$res_cc["N_SAN"]);
     $pdf->Text(130,55+$arow+$crow,$av_san);//สัญชาติ
	 
	 $av_age=iconv('UTF-8','windows-874',$res_cc["N_AGE"]);
     $pdf->Text(160,55+$arow+$crow,$av_age); //อายุ
	 
	 $ncard=trim($res_cc["N_CARD"]);
	 $av_card=iconv('UTF-8','windows-874',$ncard);
	 
	if($ncard=="บัตรประชาชน" || $ncard=="ประชาชน"){
		$av_cardid=iconv('UTF-8','windows-874',$res_cc["N_IDCARD"]);
	}else{
		$av_cardid=iconv('UTF-8','windows-874',$res_cc["N_CARDREF"]);
	}
	$pdf->Text(30,62+$arow+$crow,$av_card);//บัตรแสดง
    $pdf->Text(87,62+$arow+$crow,$av_cardid); //เลขที่บัตร
	 
	 
	
	 //$fn_cdate=pg_query("select c_datethai('$res_cc[N_OT_DATE]')");
	 //$res_fndate=pg_fetch_result($fn_cdate,0);
	 $av_card_date=iconv('UTF-8','windows-874',$res_cc["otdate"]); 
     $pdf->Text(150,62+$arow+$crow,$av_card_date); //วันที่ออกบัีตร
	 
	 
	 $av_cardby=iconv('UTF-8','windows-874',$res_cc["N_BY"]);
     $pdf->Text(30,70+$arow+$crow,$av_cardby); //ออกให้โดย
	
	  
  	 $av_pair=iconv('UTF-8','windows-874',$res_cc["A_PAIR"]);
     $pdf->Text(102,70+$arow+$crow,$av_pair); //คู่สมรส
	 
	 
	 $av_add=iconv('UTF-8','windows-874',trim($res_cc["A_NO"]));
     $pdf->Text(183,70+$arow+$crow,$av_add); //บ้านเลขที่
	 
	 
	  $av_subno=iconv('UTF-8','windows-874',trim($res_cc["A_SUBNO"]));
      $pdf->Text(30,77.5+$arow+$crow,$av_subno); //หมู่
	  
	  $av_soi=iconv('UTF-8','windows-874',trim($res_cc["A_SOI"]));
      $pdf->Text(50,77.5+$arow+$crow,$av_soi); //ซอย
	  
	  $av_rd=iconv('UTF-8','windows-874',trim($res_cc["A_RD"]));
      $pdf->Text(105,77.5+$arow+$crow,$av_rd); //ถนน
	  
	  $av_tam=iconv('UTF-8','windows-874',trim($res_cc["A_TUM"]));
      $pdf->Text(169,77.5+$arow+$crow,$av_tam); //ตำบล
	  
	  
	  $av_aum=iconv('UTF-8','windows-874',trim($res_cc["A_AUM"]));
      $pdf->Text(40,85+$arow+$crow,$av_aum); //อำเภอ
	  
	  $av_pro=iconv('UTF-8','windows-874',trim($res_cc["A_PRO"]));
      $pdf->Text(105,85+$arow+$crow,$av_pro); //จังหวัด
	  
	  
	  $av_occ=iconv('UTF-8','windows-874',trim($res_cc["N_OCC"]));
      $pdf->Text(160,85+$arow+$crow,$av_occ); //อาชีพ
	  
	  
      
	 
	 	 
     $crow=$crow+38.5;
	 
  $fp_regis=$res_idno["C_REGIS"];
  $fp_reg_by=trim($res_idno["C_REGIS_BY"]);
  
   
  
  

  $av_idno=iconv('UTF-8','windows-874',$f_idno);
  $pdf->Text(32,224.6+$frow,$av_idno); //เลขที่ idno

 
 
  $fn_cdate2=pg_query("select c_datethai('$res_idno[P_STDATE]')");
  $res_fndate2=pg_fetch_result($fn_cdate2,0);
  
  

  $av_stdate2=iconv('UTF-8','windows-874',$res_fndate2);
  $pdf->Text(83,224.6+$frow,$av_stdate); //ฉบับลงวันที่
  
  $av_regisc=iconv('UTF-8','windows-874',$fp_regis);
  $pdf->Text(135,224.6+$frow,$av_regisc); //ทะเบียนรถ
  
  $av_regby=iconv('UTF-8','windows-874',$fp_reg_by);
  $pdf->Text(170,224.6+$frow,$av_regby); //จังหวัดจดทะเบียน
  
  $av_ccnames=iconv('UTF-8','windows-874',$str_nameidno);
  $pdf->Text(93,232.5+$frow,$av_ccnames); //ชื่อ - นามสกุล
  
	 if($nn%4==0 && $nn!=$num_grt){
		$pdf->AddPage();

		$pdf->Text(132,43+$frow,$name_cc); //ชื่อบริษัท

		$pdf->Text(25,50+$frow,$name_ads); //ที่อยู่

		$pdf->Text(172,50+$frow,$av_stdate); //วันที่ทำสัญญา
		$arow=3;
		$crow=3; 
	 }
	 
$nn++;
}

$pdf->Output();



?>
