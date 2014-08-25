<?php
session_start();
 include("../config/config.php");

 $f_idno=pg_escape_string($_POST["idno_names"]);
 
 $qry_print=pg_query("
                      select A.\"IDNO\",A.\"CusID\",A.\"CarID\", A.\"TranIDRef1\", A.\"TranIDRef2\", A.\"P_STDATE\", A.\"P_MONTH\", A.\"P_VAT\", A.\"P_TOTAL\", A.\"P_DOWN\", A.\"P_VatOfDown\", A.\"P_BEGIN\", A.\"P_BEGINX\", A.\"P_FDATE\", A.\"P_CLDATE\", A.\"P_ACCLOSE\",B.\"CusID\", B.\"A_FIRNAME\", B.\"A_NAME\", B.\"A_SIRNAME\", C. \"CarID\" ,C.\"C_CARNAME\", C.\"C_YEAR\", C.\"C_REGIS\", C.\"C_COLOR\", C.\"C_CARNUM\", C.\"C_MARNUM\", B.\"A_PAIR\", B.\"A_NO\", B.\"A_SUBNO\", B.\"A_SOI\", B.\"A_RD\", B.\"A_TUM\", B.\"A_AUM\", B.\"A_PRO\" 
					  FROM \"Fp\" A  LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\" = B. \"CusID\"
					                 LEFT OUTER JOIN \"VCarregistemp\" C ON A.\"IDNO\" = C. \"IDNO\"
									 
					  where A.\"IDNO\" = '$f_idno'				 
                       ");
$res_p=pg_fetch_array($qry_print);



$av_no="AV00000001";
$av_datestart="17 ตุลาคม  พ.ศ. 2552";
$cus_name="นาย ทรงผล  บัวบาน";
$cus_mname="นาง ทรงศรี  บัวบาน";
$cus_nation="ไทย";
$cus_age="34";
$cus_type="บัตรประชาชน";
$cus_id="1234567891234";
$cus_d_id="17 ตุลาคม พ.ศ.2551";
$cus_id_outby="นายทะเบียน เขตดุสิต";
$cus_add="1030 ม.สุขสันต์หรรษา แขวงอรุณอมรินทร์ เขตบางกอกน้อย กรุงเทพ ฯ 10700";

$car_brand ="TOYOTA";
$car_year ="COROLLA ALTIS E 2009";
$car_color ="สีเหลือง";
$car_regis="ทษ 2552";
$car_province="กรุงเทพมหานคร";
$car_number="MCR8411125112521451";
$car_engine="3ZZ-FE01125118121";
$car_mi="56";

$cost="715,000.00";
$vat_s="7";
$vat_bath="50,050.00"
$cost_paydatesign="300,000.00";
$cost_paydtl="279,000.00";
$cost_paydtl_vat="21,000.00";
$cost_payment="48";
$cost_paybaht="17,000.00";
$cost_before_vat="15,810.00";
$cost_payment_vat="1,190.00";
$datepay="15";
$date_first="12 ตุลาคม พ.ศ. 2552";


require('thaipdfclass.php');

$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();
$pdf->Image('page1.jpg',0,0,210,290);
$pdf->SetFont('AngsanaNew','B',16);
$pdf->Text(143,51,$av_no); //เลขที่สัญญา

$av_datestart_icon=iconv('UTF-8','windows-874',$av_datestart);
$pdf->Text(60,77,$av_datestart_icon); //วันที่ทำสัญญา


$cus_name_icon=iconv('UTF-8','windows-874',$cus_name);
$pdf->Text(69,83,$cus_name_icon); //ผู้ทำสัญญา

$cus_mname_icon=iconv('UTF-8','windows-874',$cus_mname);
$pdf->Text(40,89,$cus_mname_icon); //คู่สมรสทำสัญญา

$cus_nation_icon=iconv('UTF-8','windows-874',$cus_nation);
$pdf->Text(32,95,$cus_nation_icon); //สัญชาติ

$cus_age_icon=iconv('UTF-8','windows-874',$cus_age);
$pdf->Text(62,95,$cus_age_icon); //อายุ


$cus_type_icon=iconv('UTF-8','windows-874',$cus_type);
$pdf->Text(106,95,$cus_type_icon); //บัตรแสดง

$cus_id_icon=iconv('UTF-8','windows-874',$cus_id);
$pdf->Text(154,95,$cus_id_icon); //เลขที่บัตร

$cus_d_id_icon=iconv('UTF-8','windows-874',$cus_d_id);
$pdf->Text(40,101,$cus_d_id_icon); //วันที่ออกบัตร


$cus_id_outby_icon=iconv('UTF-8','windows-874',$cus_id_outby);
$pdf->Text(100,101,$cus_id_outby_icon); //ออกบัตรโดย


$pdf->SetXY(25,103);
$cus_add_icon=iconv('UTF-8','windows-874',$cus_add);
$pdf->MultiCell(100,5,$cus_add_icon,0,'L',0);//ที่อยู่

$car_brand_icon=iconv('UTF-8','windows-874',$car_brand);
$pdf->Text(25,157,$car_brand_icon); //ยี่ห้อรถ

 
$car_year_icon=iconv('UTF-8','windows-874',$car_year);
$pdf->Text(25,164,$car_year_icon); //รุ่นปี



$car_color_icon=iconv('UTF-8','windows-874',$car_color);
$pdf->Text(25,170,$car_color_icon); //สี



$car_regis_icon=iconv('UTF-8','windows-874',$car_regis);
$pdf->Text(75,170,$car_regis_icon); //ทะเบียน


$car_province_icon=iconv('UTF-8','windows-874',$car_province);
$pdf->Text(46,176,$car_province_icon); //จังหวัดที่จดทะเบียน


$car_number_icon=iconv('UTF-8','windows-874',$car_number);
$pdf->Text(32,182,$car_number_icon); //เลขตัวถัง

$car_engine_icon=iconv('UTF-8','windows-874',$car_engine);
$pdf->Text(38,188.5,$car_engine_icon); //เลขเครื่อง


$car_mi_icon=iconv('UTF-8','windows-874',$car_mi);
$pdf->Text(70,194.5,$car_mi_icon); //เลขเครื่อง



$pdf->SetXY(150,153);
$cost_icon=iconv('UTF-8','windows-874',$cost);
$pdf->MultiCell(40,5,$cost,0,'R',0);//ค่ารถ


$pdf->SetXY(150,159.5);
$vat_bath_icon=iconv('UTF-8','windows-874',$vat_bath);
$pdf->MultiCell(40,5,$vat_bath_icon,0,'R',0);//vat_baht



$pdf->SetXY(138,159.5);
$vat_s_icon=iconv('UTF-8','windows-874',$vat_s);
$pdf->MultiCell(10,5,$vat_s_icon,0,'R',0);//vatcost



$pdf->SetXY(150,166);
$cost_vat_icon=iconv('UTF-8','windows-874',$cost_vat);
$pdf->MultiCell(40,5,$cost_vat_icon,0,'R',0);//vat+cost




$pdf->SetXY(150,172);
$cost_paydatesign_icon=iconv('UTF-8','windows-874',$cost_paydatesign);
$pdf->MultiCell(40,5,$cost_paydatesign_icon,0,'R',0);//vat+cost


$pdf->SetXY(120,178);
$cost_paydtl_icon=iconv('UTF-8','windows-874',$cost_paydtl);
$pdf->MultiCell(30,5,$cost_paydtl_icon,0,'R',0);//vatcost


$pdf->SetXY(150,178);
$cost_paydtl_vat_icon=iconv('UTF-8','windows-874',$cost_paydtl_vat);
$pdf->MultiCell(40,5,$cost_paydtl_vat_icon,0,'R',0);//vat+cost

$pdf->SetXY(150,184);
$cost_payment_icon=iconv('UTF-8','windows-874',$cost_payment);
$pdf->MultiCell(40,5,$cost_payment_icon,0,'R',0);//$cost_payment

$pdf->SetXY(150,190);
$cost_paybaht_icon=iconv('UTF-8','windows-874',$cost_paybaht);
$pdf->MultiCell(40,5,$cost_paybaht_icon,0,'R',0);//รายเดือน

$pdf->SetXY(120,196.5);
$cost_before_vat_icon=iconv('UTF-8','windows-874',$cost_before_vat);
$pdf->MultiCell(30,5,$cost_before_vat_icon,0,'R',0);//แบ่ง รายเดือน

$pdf->SetXY(150,196.5);
$cost_payment_vat_icon=iconv('UTF-8','windows-874',$cost_payment_vat);
$pdf->MultiCell(40,5,$cost_payment_vat_icon,0,'R',0);//vat รายเดือน

$pdf->SetXY(120,202.5);
$datepay_icon=iconv('UTF-8','windows-874',$datepay);
$pdf->MultiCell(30,5,$datepay_icon,0,'R',0);//จ่ายทุกวันที่

$pdf->SetXY(135,209);
$date_first_icon=iconv('UTF-8','windows-874',$date_first);
$pdf->MultiCell(60,5,$date_first_icon,0,'L',0);//จ่ายทุกวันที่




$pdf->Output();

?>