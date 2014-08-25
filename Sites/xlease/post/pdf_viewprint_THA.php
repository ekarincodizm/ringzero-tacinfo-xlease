<?php
session_start();
include("../config/config.php");
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) พิมพ์สัญญาเช่าซื้อ', '$add_date')");
//ACTIONLOG---


$f_idno=$_GET["ID"];
 
$qry_print=pg_query("select to_char(A.\"P_STDATE\",'dd/mm/yyyy') AS stdate, A.\"IDNO\",A.\"CusID\",A.asset_id, A.\"TranIDRef1\", A.\"TranIDRef2\", 
	A.\"P_MONTH\", A.\"P_VAT\", A.\"P_TOTAL\", A.\"P_DOWN\", A.\"P_VatOfDown\", A.\"P_BEGIN\", A.\"P_BEGINX\", A.\"P_FDATE\",
	to_char(A.\"P_FDATE\",'dd/mm/yyyy') AS fdate_thaidate, A.\"P_CLDATE\", A.\"P_ACCLOSE\",B.\"A_FIRNAME\",B.\"A_NAME\",B.\"A_SIRNAME\",B.\"A_PAIR\",
	D.\"A_NO\",D.\"A_SUBNO\",D.\"A_SOI\",D.\"A_RD\",D.\"A_TUM\",D.\"A_AUM\",D.\"A_PRO\",D.\"A_POST\",C.\"CarID\" ,
	C.\"C_CARNAME\", C.\"C_YEAR\", C.\"C_REGIS\",C.\"C_REGIS_BY\", C.\"C_COLOR\", C.\"C_CARNUM\",C.\"C_Milage\", 
	C.\"C_MARNUM\" FROM \"Fp\" A  
LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\" = B. \"CusID\"
LEFT OUTER JOIN \"VCarregistemp\" C ON A.\"IDNO\" = C. \"IDNO\"
LEFT OUTER JOIN \"Fp_Fa1\" D ON A.\"IDNO\" = D. \"IDNO\" and D.\"edittime\"='0' and D.\"CusState\"='0'
where A.\"IDNO\" = '$f_idno'");
$res_p=pg_fetch_array($qry_print);

$cus_id=trim($res_p["CusID"]);

$qry_fn=pg_query("select *,to_char(\"N_OT_DATE\",'dd/mm/yyyy') AS otdate from \"Fn\" where \"CusID\"='$cus_id'  ");
$res_fn=pg_fetch_array($qry_fn); 




$av_no=$res_p["IDNO"];
$av_datestart=$res_p["stdate"];
$cus_name=trim($res_p["A_FIRNAME"])." ".trim($res_p["A_NAME"])."  ".trim($res_p["A_SIRNAME"]);
$cus_mname=trim($res_p["A_PAIR"]);


$cus_nation=trim($res_fn["N_SAN"]);

$cus_age=trim($res_fn["N_AGE"]);
$cus_type=trim($res_fn["N_CARD"]);
$cus_occ=trim($res_fn["N_OCC"]);
$cus_d_id=trim($res_fn["otdate"]);
$cus_id_outby=trim($res_fn["N_BY"]); 

if($cus_type=="บัตรประชาชน" || $cus_type=="ประชาชน"){
	$cus_nid=trim($res_fn["N_IDCARD"]);
}else{
	$cus_nid=trim($res_fn["N_CARDREF"]);
}

if(trim($res_p["A_SOI"])=="")
{
 $s_soi="";
}
else
{
 $s_soi=" ซอย ".trim($res_p["A_SOI"]);
}

if(trim($res_p["A_RD"])=="")
{
 $s_rd="";
}
else
{
 $s_rd=" ถนน ".trim($res_p["A_RD"]);
}



$cus_add=trim($res_p["A_NO"])."  ม.".trim($res_p["A_SUBNO"]).$s_soi." ".$s_rd."  แขวง/ตำบล ".trim($res_p["A_TUM"]);


$cus_pro="เขต/อำเภอ ".trim($res_p["A_AUM"])."  จังหวัด ".trim($res_p["A_PRO"]);

$car_brand =trim($res_p["C_CARNAME"]);
$car_year =trim($res_p["C_YEAR"]);
$car_color =trim($res_p["C_COLOR"]);
$car_regis=trim($res_p["C_REGIS"]);
$car_province=trim($res_p["C_REGIS_BY"]);
$car_number=trim($res_p["C_CARNUM"]);
$car_engine=trim($res_p["C_MARNUM"]);
$car_mi=trim($res_p["C_Milage"]);


$down=$res_p["P_DOWN"];
$se_mon=$res_p["P_MONTH"];
$se_total=$res_p["P_TOTAL"];
$se_vat=$res_p["P_VAT"];

$cost=($se_mon*$se_total)+$down;
$vat_s=7;
$vat_bath=($res_p["P_VAT"]*$res_p["P_TOTAL"])+$res_p["P_VatOfDown"];
$cost_total=$cost+$vat_bath;


$qry_cost=pg_query("select * from conversionnumtothaitext($cost_total)");
$res_cost=pg_fetch_result($qry_cost,0); 



//$cost_paydatesign="300,000.00";
//$cost_paydtl="279,000.00";
//$cost_paydtl_vat="21,000.00";
//$cost_payment="48";
//$cost_paybaht="17,000.00";
//$cost_before_vat="15,810.00";
//$cost_payment_vat="1,190.00";
//$datepay="15";
//$date_first="12 ตุลาคม พ.ศ. 2552";


require('../thaipdfclass.php');

$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(1.5);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();

$arow= 1;
$acol=0;
//$pdf->Image('page1.jpg',0,0,210,290);

$pdf->SetFont('AngsanaNew','B',17);

$pdf->Text(158,76+$arow,$av_no); //เลขที่สัญญา

/*$name_comp="สาขาสำนักงานใหญ่ ตั้งอยู่อาคาร เลขที่ 999 หมู่ 10";
$name_comp_ads="ถนน นวมินทร์ แขวง คลองกุ่ม เขต บึงกุ่ม  จังหวัด กทม T.944-2000";*/

$name_comp="สาขาสำนักงานใหญ่ ตั้งอยู่อาคาร เลขที่ 555";
$name_comp_ads="ถนนนวมินทร์ แขวงคลองกุ่ม เขตบึงกุ่ม  จังหวัด กทม. 10240 โทร 02-744-2222";


$name_cc=iconv('UTF-8','windows-874',$name_comp);
$pdf->Text(116,84+$arow,$name_cc); //ชื่อบริษัท


$name_ads=iconv('UTF-8','windows-874',$name_comp_ads);
$pdf->Text(23,92+$arow,$name_ads); //ที่อยู่




$av_datestart_icon=iconv('UTF-8','windows-874',$av_datestart);
$pdf->Text(170,92+$arow,$av_datestart_icon); //วันที่ทำสัญญา



$cus_name_icon=iconv('UTF-8','windows-874',$cus_name);
$pdf->Text(25,107+$arow,$cus_name_icon); //ผู้ทำสัญญา




$cus_mname_icon=iconv('UTF-8','windows-874',$cus_mname);
$pdf->Text(132,107+$arow,$cus_mname_icon); //คู่สมรสทำสัญญา

$cus_nation_icon=iconv('UTF-8','windows-874',$cus_nation);
$pdf->Text(40,115+$arow,$cus_nation_icon); //สัญชาติ

$cus_age_icon=iconv('UTF-8','windows-874',$cus_age);
$pdf->Text(65,115+$arow,$cus_age_icon); //อายุ


$cus_type_icon=iconv('UTF-8','windows-874',$cus_type);
$pdf->Text(102,115+$arow,$cus_type_icon); //บัตรแสดง

$cus_id_icon=iconv('UTF-8','windows-874',$cus_nid);
$pdf->Text(160,115+$arow,$cus_id_icon); //เลขที่บัตร




$cus_d_id_icon=iconv('UTF-8','windows-874',$cus_d_id);
$pdf->Text(47,122+$arow,$cus_d_id_icon); //วันที่ออกบัตร


$cus_id_outby_icon=iconv('UTF-8','windows-874',$cus_id_outby);
$pdf->Text(95,122+$arow,$cus_id_outby_icon); //ออกบัตรโดย

/*
$pdf->SetXY(20,103+$arow);
$cus_add_icon=iconv('UTF-8','windows-874',$cus_add);
$pdf->MultiCell(170,5,$cus_add_icon,0,'L',0);//ที่อยู่
*/



$car_brand_icon=iconv('UTF-8','windows-874',trim($res_p["A_NO"]));
$pdf->Text(177,122+$arow,$car_brand_icon); //บ้านเลขที่



$car_brand_icon=iconv('UTF-8','windows-874',trim($res_p["A_SUBNO"]));
$pdf->Text(35,130+$arow,$car_brand_icon); //หมู่ที่

$car_brand_icon=iconv('UTF-8','windows-874',trim($res_p["A_SOI"]));
$pdf->Text(54,130+$arow,$car_brand_icon); //ซอย


$car_brand_icon=iconv('UTF-8','windows-874',trim($res_p["A_RD"]));
$pdf->Text(98,130+$arow,$car_brand_icon); //ถนน


$car_brand_icon=iconv('UTF-8','windows-874',trim($res_p["A_TUM"]));
$pdf->Text(162,130+$arow,$car_brand_icon); //แขวง/ตำบล

$car_brand_icon=iconv('UTF-8','windows-874',trim($res_p["A_AUM"]));
$pdf->Text(43,138+$arow,$car_brand_icon); //อำเภอ/เขต


$car_brand_icon=iconv('UTF-8','windows-874',trim($res_p["A_PRO"]));
$pdf->Text(100,138+$arow,$car_brand_icon); //จังหวัด


$car_brand_icon=iconv('UTF-8','windows-874',$cus_occ);
$pdf->Text(156,138+$arow,$car_brand_icon); //อาชีพ


/*
$pdf->SetXY(20,108+$arow);
$cus_pro_icon=iconv('UTF-8','windows-874',$cus_pro);
$pdf->MultiCell(160,5,$cus_pro_icon,0,'L',0);//จังหวัด
*/
//ข้อ 1.
$car_brand_icon=iconv('UTF-8','windows-874',$car_brand);
$pdf->Text(142,153+$arow,$car_brand_icon); //ยี่ห้อรถ


$car_year_icon=iconv('UTF-8','windows-874',$car_year);
$pdf->Text(36,160.5+$arow,$car_year_icon); //รุ่นปี


$car_regis_icon=iconv('UTF-8','windows-874',$car_regis);
$pdf->Text(81,160.5+$arow,$car_regis_icon); //ทะเบียน




$car_province_icon=iconv('UTF-8','windows-874',$car_province);
$pdf->Text(116,160.5+$arow,$car_province_icon); //จังหวัดที่จดทะเบียน




$car_color_icon=iconv('UTF-8','windows-874',$car_color);
$pdf->Text(157,160.5+$arow,$car_color_icon); //สี




$car_number_icon=iconv('UTF-8','windows-874',$car_number);
$pdf->Text(42,168+$arow,$car_number_icon); //เลขตัวถัง

$car_engine_icon=iconv('UTF-8','windows-874',$car_engine);
$pdf->Text(133,168+$arow,$car_engine_icon); //เลขเครื่อง


$car_mi_icon=iconv('UTF-8','windows-874',$car_mi);
$pdf->Text(150,176+$arow,$car_mi_icon); //ไมล์กิโล

$car_option="แอร์ วิทยุเทป ยางอะไหล่";
$car_option_icon=iconv('UTF-8','windows-874',$car_option);
$pdf->Text(50,191+$arow,$car_option_icon); //option


// ข้อ 2
$pdf->SetXY(157,217+$arow);
$cost_icon=iconv('UTF-8','windows-874',$cost);
$pdf->MultiCell(30,5,number_format($cost,2),0,'R',0);//ค่ารถ



$pdf->SetXY(60,225+$arow);
$vat_bath_icon=iconv('UTF-8','windows-874',$vat_bath);
$pdf->MultiCell(22,5,number_format($vat_bath_icon,2),0,'R',0);//vat_baht



$pdf->SetXY(145,225+$arow);
$cost_total_icon=iconv('UTF-8','windows-874',$cost_total);
$pdf->MultiCell(30,5,number_format($cost_total_icon,2),0,'R',0);//vatcost




$car_rescost_icon=iconv('UTF-8','windows-874',$res_cost);
$pdf->Text(30,237.5+$arow,$car_rescost_icon); //text thai cost


/*
$pdf->SetXY(131,160.5+$arow);
$vat_s_icon=iconv('UTF-8','windows-874',$vat_s);
$pdf->MultiCell(10,5,$vat_s_icon,0,'R',0);//txt7
*/


$pdf->SetXY(85,240+$arow);
$cost_paydtl_icon=iconv('UTF-8','windows-874',$down);
$pdf->MultiCell(22,5,number_format($cost_paydtl_icon,2),0,'R',0);//P_DOWN


$pdf->SetXY(165,240+$arow);
$cost_paydtl_vat_icon=iconv('UTF-8','windows-874',$res_p["P_VatOfDown"]);
$pdf->MultiCell(22,5,number_format($cost_paydtl_vat_icon,2),0,'R',0);//vat+cost


$pdf->SetXY(55,248+$arow);
$cost_payment_icon=iconv('UTF-8','windows-874',$res_p["P_VatOfDown"]+$down);
$pdf->MultiCell(22,5,number_format($cost_payment_icon,2),0,'R',0);//total_down+vat




$pdf->SetXY(158,248+$arow);
$cost_payment_icon=iconv('UTF-8','windows-874',$se_total);
$pdf->MultiCell(10,5,$cost_payment_icon,0,'R',0);//$cost_payment


$pdf->SetXY(75,256+$arow);
$cost_before_vat_icon=iconv('UTF-8','windows-874',$res_p["P_MONTH"]);
$pdf->MultiCell(22,5,number_format($cost_before_vat_icon,2),0,'R',0);//แบ่ง รายเดือน



$pdf->SetXY(165,256+$arow);
$cost_payment_vat_icon=iconv('UTF-8','windows-874',$res_p["P_VAT"]);
$pdf->MultiCell(22,5,number_format($cost_payment_vat_icon,2),0,'R',0);//vat รายเดือน


$pdf->SetXY(60,263+$arow);
$cost_paybaht_icon=iconv('UTF-8','windows-874',$res_p["P_MONTH"]+$res_p["P_VAT"]);
$pdf->MultiCell(22,5,number_format($cost_paybaht_icon,2),0,'R',0);//รายเดือน




$pdf->SetXY(111,203.5+$arow);
$fdate=substr($res_p["P_FDATE"],8,2);

$pdf->SetXY(140,263+$arow);
$datepay_icon=iconv('UTF-8','windows-874',$fdate);
$pdf->MultiCell(22,5,$datepay_icon,0,'L',0);//จ่ายทุกวันที่

$date_first_icon=iconv('UTF-8','windows-874',$res_p["fdate_thaidate"]);
$pdf->Text(54,276+$arow,$date_first_icon); //จ่ายงวดแรก


$pdf->Output();

?>
