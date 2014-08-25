<?php
session_start();
include("../../config/config.php");

 $f_idnos=$_POST["idno_names"];
 
 $f_idno=substr($f_idnos,0,11);
 
 
 $qry_print=pg_query("
                      select A.\"IDNO\",A.\"CusID\",A.asset_id, A.\"TranIDRef1\", A.\"TranIDRef2\", conversiondatetothaitext(A.\"P_STDATE\") AS fp_thaidate, A.\"P_MONTH\", A.\"P_VAT\", A.\"P_TOTAL\", A.\"P_DOWN\", A.\"P_VatOfDown\", A.\"P_BEGIN\", A.\"P_BEGINX\", A.\"P_FDATE\",conversiondatetothaitext(A.\"P_FDATE\") AS fdate_thaidate, A.\"P_CLDATE\", A.\"P_ACCLOSE\",B.\"CusID\", B.\"A_FIRNAME\", B.\"A_NAME\", B.\"A_SIRNAME\",C. \"CarID\" ,C.\"C_CARNAME\", C.\"C_YEAR\", C.\"C_REGIS\",C.\"C_REGIS_BY\", C.\"C_COLOR\", C.\"C_CARNUM\",C.\"C_Milage\", C.\"C_MARNUM\", B.\"A_PAIR\", B.\"A_NO\", B.\"A_SUBNO\", B.\"A_SOI\", B.\"A_RD\", B.\"A_TUM\", B.\"A_AUM\", B.\"A_PRO\" ,D.*,E.*
					  FROM \"Fp\" A  LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\" = B. \"CusID\"
					                 LEFT OUTER JOIN \"VCarregistemp\" C ON A.\"IDNO\" = C.\"IDNO\"
									 LEFT OUTER JOIN \"Fn\" D ON B.\"CusID\"=D.\"CusID\"
									 LEFT OUTER JOIN \"FGas\" E ON A.asset_id =E.\"GasID\"
									 
					  where A.\"IDNO\" = '$f_idno'				 
                       ");
$res_p=pg_fetch_array($qry_print);

$cus_id=trim($res_p["CusID"]);

if($res_p["N_IDCARD"]=="")
{
	$cardid=trim($res_p["N_CARDREF"]);
}
else
{
	$cardid=trim($res_p["N_IDCARD"]);
}

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
	$cus_nid=trim($res_fn["N_IDCARD"]);
}else{
	$cus_nid=trim($res_fn["N_CARDREF"]);
}


$dcard =$res_p["N_OT_DATE"];
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

$cus_d_id=" "."$c_day"." $s_c"." $sthais_year";
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


require('../../thaipdfclass.php');

$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();

$arow=2;
$acol=10;
//$pdf->Image('page1.jpg',0,0,210,290);

$pdf->SetFont('AngsanaNew','B',16);

$pdf->Text(159,69+$arow,$av_no); //เลขที่สัญญา



$av_datestart_icon=iconv('UTF-8','windows-874',$av_datestart);
$pdf->Text(92,85+$arow,$av_datestart_icon); //วันที่ทำสัญญา



$cus_name_icon=iconv('UTF-8','windows-874',$cus_name);
$pdf->Text(90,92+$arow,$cus_name_icon); //ผู้ทำสัญญา


$cus_mname_icon=iconv('UTF-8','windows-874',$cus_mname);
$pdf->Text(40,100+$arow,$cus_mname_icon); //คู่สมรสทำสัญญา


$cus_nation_icon=iconv('UTF-8','windows-874',$cus_nation);
$pdf->Text(35,107+$arow,$cus_nation_icon); //สัญชาติ


$cus_age_icon=iconv('UTF-8','windows-874',$cus_age);
$pdf->Text(58,107+$arow,$cus_age_icon); //อายุ


$cus_type_icon=iconv('UTF-8','windows-874',$cus_type);
$pdf->Text(99,107+$arow,$cus_type_icon); //บัตรแสดง

$cus_id_icon=iconv('UTF-8','windows-874',$cardid);
$pdf->Text(159,107+$arow,$cus_id_icon); //เลขที่บัตร


$cus_d_id_icon=iconv('UTF-8','windows-874',$cus_d_id);
$pdf->Text(42,115+$arow,$cus_d_id_icon); //วันที่ออกบัตร


$cus_id_outby_icon=iconv('UTF-8','windows-874',$cus_id_outby);
$pdf->Text(95,115+$arow,$cus_id_outby_icon); //ออกบัตรโดย

$cus_add_icon=iconv('UTF-8','windows-874',$res_p["A_NO"]);
$pdf->Text(183,117,$cus_add_icon);//ที่อยู่


$cus_add_subno=iconv('UTF-8','windows-874',$res_p["A_SUBNO"]);
$pdf->Text(28,125.5,$cus_add_subno);//หมู่ที่

$cus_add_soi=iconv('UTF-8','windows-874',$res_p["A_SOI"]);
$pdf->Text(55,125.5,$cus_add_soi);//ซอย

$cus_add_rd=iconv('UTF-8','windows-874',$res_p["A_RD"]);
$pdf->Text(104,125.5,$cus_add_rd);//ถนน

$cus_add_tum=iconv('UTF-8','windows-874',$res_p["A_TUM"]);
$pdf->Text(165,125.5,$cus_add_tum);//ตำบล

$cus_add_aum=iconv('UTF-8','windows-874',$res_p["A_AUM"]);
$pdf->Text(37,133,$cus_add_aum);//อำเภอ

$cus_add_pro=iconv('UTF-8','windows-874',$res_p["A_PRO"]);
$pdf->Text(100,133,$cus_add_pro);//จังหวัด

$cus_add_occ=iconv('UTF-8','windows-874',$cus_occ);
$pdf->Text(165,133,$cus_add_occ);//อาชีพ


$cus_gas_name=iconv('UTF-8','windows-874',trim($res_p["gas_name"]));
$pdf->Text(173,147.5,$cus_gas_name);//ยี่ห้อแก๊ส


$car_c_year=iconv('UTF-8','windows-874',trim($res_p["car_year"]));
$pdf->Text(37,155.5,$car_c_year);//ปีรถ


$car_c_regis=iconv('UTF-8','windows-874',trim($res_p["car_regis"]));
$pdf->Text(85,155.5,$car_c_regis);//ทะเบียน

$car_c_regisby=iconv('UTF-8','windows-874',trim($res_p["car_regis_by"]));
$pdf->Text(130,155.5,$car_c_regisby);//จังหวัดจดทะเบียน


$car_c_gasname=iconv('UTF-8','windows-874',trim($res_p["gas_number"]));
$pdf->Text(172,155.5,$car_c_gasname);//เลขถังก๊าซ

$car_c_num=iconv('UTF-8','windows-874',trim($res_p["carnum"]));
$pdf->Text(40,162.5,$car_c_num);//เลขตัวถังรถ

$car_c_mar=iconv('UTF-8','windows-874',trim($res_p["marnum"]));
$pdf->Text(117,162.5,$car_c_mar);//เลขเครื่อง



$gas_cost=iconv('UTF-8','windows-874',number_format($cost,2));
$pdf->Text(165,228.5,$gas_cost);//ราคาเช่าซื้อ




$gas_vatcost=iconv('UTF-8','windows-874',number_format($vat_bath,2));
$pdf->Text(59,236,$gas_vatcost);//vat ทั้งหมด

$gas_totalcost=iconv('UTF-8','windows-874',number_format($cost_total,2));
$pdf->Text(139,236,$gas_totalcost);//ยอดทั้งหมด

$bt_cost=pg_query("select conversionnumtothaitext($cost_total)");
$res_bt=pg_fetch_result($bt_cost,0);
$gas_totalcost=iconv('UTF-8','windows-874',$res_bt);
$pdf->Text(25,243.5,$gas_totalcost);//ยอดทั้งหมด แปลไทย

$gas_down=iconv('UTF-8','windows-874',number_format($res_p["P_DOWN"],2));
$pdf->Text(80,252,$gas_down);//ดาวน์

$gas_vatdown=iconv('UTF-8','windows-874',number_format($res_p["P_VatOfDown"],2));
$pdf->Text(159,252,$gas_vatdown);//vat ดาวน์

$gas_totaldown=iconv('UTF-8','windows-874',number_format($res_p["P_DOWN"]+$res_p["P_VatOfDown"],2));
$pdf->Text(50,258.5,$gas_totaldown);//ดาวน์

$gas_total=iconv('UTF-8','windows-874',$res_p["P_TOTAL"]);
$pdf->Text(155,258.5,$gas_total);//vat ดาวน์

$gas_mon=iconv('UTF-8','windows-874',number_format($res_p["P_MONTH"],2));
$pdf->Text(66,265.5,$gas_mon);//งวด ถอด vat


$gas_vat=iconv('UTF-8','windows-874',number_format($res_p["P_VAT"],2));
$pdf->Text(155,265.5,$gas_vat);//vat งวด


$gas_mon=iconv('UTF-8','windows-874',number_format($res_p["P_MONTH"]+$res_p["P_VAT"],2));
$pdf->Text(55,273.5,$gas_mon);//รวม งวดผ่อน



$gas_fdate=iconv('UTF-8','windows-874',substr($res_p["P_FDATE"],8,2));
$pdf->Text(130,273.5,$gas_fdate);//วันที่งวดแรก

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

$gas_fsdate=iconv('UTF-8','windows-874',$textdate);
$pdf->Text(41,281.5,$gas_fsdate);//รวม งวดผ่อน


$pdf->Output();

?>