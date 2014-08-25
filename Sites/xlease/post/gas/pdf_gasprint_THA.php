<?php
session_start();
include("../../config/config.php");

 $f_idno=$_POST["h_id"];
 
 //$f_idno=substr($f_idnos,0,11);
 
 
 $qry_print=pg_query("
                      select A.\"IDNO\",A.\"CusID\",A.asset_id, A.\"TranIDRef1\", A.\"TranIDRef2\", to_char(A.\"P_STDATE\",'dd/mm/yyyy') AS fp_thaidate, A.\"P_MONTH\", A.\"P_VAT\",
					  A.\"P_TOTAL\", A.\"P_DOWN\", A.\"P_VatOfDown\", A.\"P_BEGIN\", A.\"P_BEGINX\", A.\"P_FDATE\",to_char(A.\"P_FDATE\",'dd/mm/yyyy') AS fdate_thaidate, A.\"P_CLDATE\", A.\"P_ACCLOSE\",B.\"CusID\", B.\"A_FIRNAME\", B.\"A_NAME\", B.\"A_SIRNAME\",C. \"CarID\" ,C.\"C_CARNAME\", C.\"C_YEAR\", C.\"C_REGIS\",C.\"C_REGIS_BY\", C.\"C_COLOR\", C.\"C_CARNUM\",C.\"C_Milage\", C.\"C_MARNUM\", B.\"A_PAIR\",
					  F.\"A_NO\", F.\"A_SUBNO\", F.\"A_SOI\", F.\"A_RD\", F.\"A_TUM\", F.\"A_AUM\", F.\"A_PRO\" ,D.*, to_char(D.\"N_OT_DATE\",'dd/mm/yyyy') as ndate,E.*
					  FROM \"Fp\" A  LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\" = B. \"CusID\"
					                 LEFT OUTER JOIN \"VCarregistemp\" C ON A.\"IDNO\" = C.\"IDNO\"
									 LEFT OUTER JOIN \"Fn\" D ON B.\"CusID\"=D.\"CusID\"
									 LEFT OUTER JOIN \"FGas\" E ON A.asset_id =E.\"GasID\"
									 LEFT OUTER JOIN \"Fp_Fa1\" F ON A.\"IDNO\" =F.\"IDNO\" AND F.\"CusState\"='0' AND F.\"edittime\"='0'
									 
					  where A.\"IDNO\" = '$f_idno'				 
                       ");
$res_p=pg_fetch_array($qry_print);

$cus_id=trim($res_p["CusID"]);

$fpdate=$res_p["fdate_thaidate"];

$qry_fn=pg_query("select * from \"Fn\" where \"CusID\"='$cus_id'  ");
$res_fn=pg_fetch_array($qry_fn); 




$av_no=$res_p["IDNO"];
$av_datestart=$res_p["fp_thaidate"];
$cus_name=trim($res_p["A_FIRNAME"])." ".trim($res_p["A_NAME"])."  ".trim($res_p["A_SIRNAME"]);
$cus_mname=trim($res_p["A_PAIR"]);


$cus_nation=trim($res_fn["N_SAN"]);

$cus_age=trim($res_fn["N_AGE"]);
$cus_type=trim($res_fn["N_CARD"]);

if($cus_type=="บัตรประชาชน" || $cus_type=="ประชาชน"){
	$cardid=trim($res_fn["N_IDCARD"]);
}else{
	$cardid=trim($res_fn["N_CARDREF"]);
}


$dcard =$res_p["N_OT_DATE"];


$nn_date=$res_p["ndate"];

$c_day = substr($dcard,8,2);
$c_month = substr($dcard,5,2);
$c_year = substr($dcard,0,4);


if($c_month == '1') { $s_c= "มกราคม"; } else
if($c_month == '2') {  $s_c= "กุมภาพันธ์"; } else
if($c_month == '3') { $s_c= "มีนาคม"; } else
if($c_month == '4') {  $s_c= "เมษายน"; } else
if($c_month == '5') {  $s_c= "พฤษภาคม"; } else
if($c_month == '6') {  $s_c= "มิถุนายน"; } else
if($c_month == '7') {  $s_c= "กรกฏาคม"; } else
if($c_month == '8') {  $s_c= "สิงหาคม"; } else
if($c_month == '9') {  $s_c= "กันยายน"; } else
if($c_month == '10') {  $s_c= "ตุลาคม"; } else
if($c_month == '11') {  $s_c= "พฤศจิกายน"; } else {  $s_c= "ธันวาคม"; }

$sthais_year=$c_year+543;

$cus_d_id=$nn_date;
//$cus_d_id=trim($res_p["otdate"]);



$cus_id_outby=trim($res_fn["N_BY"]); 
$cus_add=trim($res_p["A_NO"]);
$cus_occ=trim($res_p["N_OCC"]);

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
$cost_paydatesign="300,000.00";
$cost_paydtl="279,000.00";
$cost_paydtl_vat="21,000.00";
$cost_payment="48";
$cost_paybaht="17,000.00";
$cost_before_vat="15,810.00";
$cost_payment_vat="1,190.00";
$datepay="15";
$date_first="12 ตุลาคม พ.ศ. 2552";


$name_comp="สาขาสำนักงานใหญ่  ตั้งอยู่อาคาร  เลขที่  555";
$name_comp_ads="ถนนนวมินทร์  แขวงคลองกุ่ม  เขตบึงกุ่ม  กรุงเทพฯ  10240 โทร 02-744-2222";


require('../../thaipdfclass.php');

$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();

$arow=5;
$acol=0;
//$pdf->Image('page1.jpg',0,0,210,290);

$pdf->SetFont('AngsanaNew','B',17);

$pdf->Text(159,69.5+$arow,$av_no); //เลขที่สัญญา


$name_cc=iconv('UTF-8','windows-874',$name_comp);
$pdf->Text(120,77+$arow,$name_cc); //ชื่อบริษัท


$name_ads=iconv('UTF-8','windows-874',$name_comp_ads);
$pdf->Text(20,85+$arow,$name_ads); //ที่อยู่




$av_datestart_icon=iconv('UTF-8','windows-874',$av_datestart);
$pdf->Text(175,85+$arow,$av_datestart_icon); //วันที่ทำสัญญา



$cus_name_icon=iconv('UTF-8','windows-874',$cus_name);
$pdf->Text(25,100+$arow,$cus_name_icon); //ผู้ทำสัญญา


$cus_mname_icon=iconv('UTF-8','windows-874',$cus_mname);
$pdf->Text(130,100+$arow,$cus_mname_icon); //คู่สมรสทำสัญญา


$cus_nation_icon=iconv('UTF-8','windows-874',$cus_nation);
$pdf->Text(35,107+$arow,$cus_nation_icon); //สัญชาติ


$cus_age_icon=iconv('UTF-8','windows-874',$cus_age);
$pdf->Text(65,107+$arow,$cus_age_icon); //อายุ


$cus_type_icon=iconv('UTF-8','windows-874',$cus_type);
$pdf->Text(110,107+$arow,$cus_type_icon); //บัตรแสดง

$cus_id_icon=iconv('UTF-8','windows-874',$cardid);
$pdf->Text(160,107+$arow,$cus_id_icon); //เลขที่บัตร


$cus_d_id_icon=iconv('UTF-8','windows-874',$nn_date);
$pdf->Text(45,115+$arow,$cus_d_id_icon); //วันที่ออกบัตร


$cus_id_outby_icon=iconv('UTF-8','windows-874',$cus_id_outby);
$pdf->Text(100,115+$arow,$cus_id_outby_icon); //ออกบัตรโดย

$cus_add_icon=iconv('UTF-8','windows-874',$res_p["A_NO"]);
$pdf->Text(180,115+$arow,$cus_add_icon);//ที่อยู่


$cus_add_subno=iconv('UTF-8','windows-874',$res_p["A_SUBNO"]);
$pdf->Text(32,123+$arow,$cus_add_subno);//หมู่ที่

$cus_add_soi=iconv('UTF-8','windows-874',$res_p["A_SOI"]);
$pdf->Text(60,123+$arow,$cus_add_soi);//ซอย

$cus_add_rd=iconv('UTF-8','windows-874',$res_p["A_RD"]);
$pdf->Text(110,123+$arow,$cus_add_rd);//ถนน

$cus_add_tum=iconv('UTF-8','windows-874',$res_p["A_TUM"]);
$pdf->Text(165,123+$arow,$cus_add_tum);//ตำบล

$cus_add_aum=iconv('UTF-8','windows-874',$res_p["A_AUM"]);
$pdf->Text(40,130+$arow,$cus_add_aum);//อำเภอ

$cus_add_pro=iconv('UTF-8','windows-874',$res_p["A_PRO"]);
$pdf->Text(105,130+$arow,$cus_add_pro);//จังหวัด

$cus_add_occ=iconv('UTF-8','windows-874',$cus_occ);
$pdf->Text(170,130+$arow,$cus_add_occ);//อาชีพ


$cus_gas_name=iconv('UTF-8','windows-874',trim($res_p["gas_name"]));
$pdf->Text(160,147+$arow,$cus_gas_name);//ยี่ห้อแก๊ส


$car_c_year=iconv('UTF-8','windows-874',trim($res_p["car_year"]));
$pdf->Text(35,153+$arow,$car_c_year);//ปีรถ


$car_c_regis=iconv('UTF-8','windows-874',trim($res_p["car_regis"]));
$pdf->Text(85,153+$arow,$car_c_regis);//ทะเบียน

if(trim($res_p["car_regis_by"])=='กรุงเทพมหานคร'){
	$carby='กรุงเทพฯ';
}else{
	$carby=$res_p["car_regis_by"];
}
$car_c_regisby=iconv('UTF-8','windows-874',$carby);
$pdf->Text(120,153+$arow,$car_c_regisby);//จังหวัดจดทะเบียน


$car_c_gasname=iconv('UTF-8','windows-874',trim($res_p["gas_number"]));
$pdf->Text(165,153+$arow,$car_c_gasname);//เลขถังก๊าซ

$car_c_num=iconv('UTF-8','windows-874',trim($res_p["carnum"]));
$pdf->Text(40,162+$arow,$car_c_num);//เลขตัวถังรถ

$car_c_mar=iconv('UTF-8','windows-874',trim($res_p["marnum"]));
$pdf->Text(130,162+$arow,$car_c_mar);//เลขเครื่อง

$gas_cost=iconv('UTF-8','windows-874',number_format($cost,2));
$pdf->Text(165,217+$arow,$gas_cost);//ราคาเช่าซื้อ

$gas_vatcost=iconv('UTF-8','windows-874',number_format($vat_bath,2));
$pdf->Text(60,224+$arow,$gas_vatcost);//vat ทั้งหมด

$gas_totalcost=iconv('UTF-8','windows-874',number_format($cost_total,2));
$pdf->Text(165,224+$arow,$gas_totalcost);//ยอดทั้งหมด

$bt_cost=pg_query("select conversionnumtothaitext($cost_total)");
$res_bt=pg_fetch_result($bt_cost,0);
$gas_totalcost=iconv('UTF-8','windows-874',$res_bt);
$pdf->Text(40,232+$arow,$gas_totalcost);//ยอดทั้งหมด แปลไทย

$gas_down=iconv('UTF-8','windows-874',number_format($res_p["P_DOWN"],2));
$pdf->Text(80,239+$arow,$gas_down);//ดาวน์

$gas_vatdown=iconv('UTF-8','windows-874',number_format($res_p["P_VatOfDown"],2));
$pdf->Text(170,239+$arow,$gas_vatdown);//vat ดาวน์

$gas_totaldown=iconv('UTF-8','windows-874',number_format($res_p["P_DOWN"]+$res_p["P_VatOfDown"],2));
$pdf->Text(50,247+$arow,$gas_totaldown);//ดาวน์

$gas_total=iconv('UTF-8','windows-874',$res_p["P_TOTAL"]);
$pdf->Text(160,247+$arow,$gas_total);//จำนวนงวด

$gas_mon=iconv('UTF-8','windows-874',number_format($res_p["P_MONTH"],2));
$pdf->Text(70,254+$arow,$gas_mon);//งวด ถอด vat

$gas_vat=iconv('UTF-8','windows-874',number_format($res_p["P_VAT"],2));
$pdf->Text(160,254+$arow,$gas_vat);//vat งวด

$gas_mon=iconv('UTF-8','windows-874',number_format($res_p["P_MONTH"]+$res_p["P_VAT"],2));
$pdf->Text(55,262+$arow,$gas_mon);//รวม งวดผ่อน

$gas_fdate=iconv('UTF-8','windows-874',substr($res_p["P_FDATE"],8,2));
$pdf->Text(140,262+$arow,$gas_fdate);//วันที่งวดแรก

$update = $res_p["P_FDATE"];
$s_day = substr($update,8,2);
$s_month = substr($update,5,2);
$s_year = substr($update,0,4);


if($s_month == '1') { $s_m= "มกราคม"; } else
if($s_month == '2') {  $s_m= "กุมพาพันธ์"; } else
if($s_month == '3') { $s_m= "มีนาคม"; } else
if($s_month == '4') {  $s_m= "เมษายน"; } else
if($s_month == '5') {  $s_m= "พฤษภาคม"; } else
if($s_month == '6') {  $s_m= "มิถุนายน"; } else
if($s_month == '7') {  $s_m= "กรกฏาคม"; } else
if($s_month == '8') {  $s_m= "สิงหาคม"; } else
if($s_month == '9') {  $s_m= "กันยายน"; } else
if($s_month == '10') {  $s_m= "ตุลาคม"; } else
if($s_month == '11') {  $s_m= "พฤศจิกายน"; } else {  $s_m= "ธันวาคม"; }

$sthai_year =$s_year+543;

$textdate=" "."$s_day"." $s_m"." $sthai_year ";

$y_en=substr($res_p["P_FDATE"],0,4);
$y_thai=$y_en+543;

$gas_fsdate=iconv('UTF-8','windows-874',$fpdate);
$pdf->Text(50,269+$arow,$gas_fsdate);//รวม งวดผ่อน


$pdf->Output();

?>