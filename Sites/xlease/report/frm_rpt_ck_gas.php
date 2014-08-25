<?php
session_start();
include("../config/config.php");

$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$nowdate = date("d/m/Y");
$f_idno = $_REQUEST["h_id"];


//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) พิมพ์สรุปสัญญาเช่าซื้อ', '$add_date')");
//ACTIONLOG---

require('../thaipdfclass.php');

$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();

$pdf->SetFont('AngsanaNew','',14);


//$f_idno = "114-06048";
$qry_print=pg_query("select to_char(A.\"P_STDATE\",'dd/mm/yyyy') AS stdate, A.\"IDNO\",A.\"CusID\",A.asset_id,
A.\"P_CustByYear\",A.\"TranIDRef1\", A.\"TranIDRef2\", A.\"P_MONTH\", A.\"P_VAT\", A.\"P_TOTAL\", A.\"P_DOWN\", 
A.\"P_VatOfDown\", A.\"P_BEGIN\", A.\"P_BEGINX\", A.\"P_FDATE\",to_char(A.\"P_FDATE\",'dd/mm/yyyy') AS fdate_thaidate, 
A.\"P_CLDATE\", A.\"P_ACCLOSE\",B.*,C.*
FROM \"Fp\" A  
LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\" = B. \"CusID\"
LEFT OUTER JOIN \"FGas\" C ON A.\"asset_id\" = C. \"GasID\"
LEFT OUTER JOIN \"Fp_Fa1\" D ON A.\"IDNO\" = D. \"IDNO\" and D.\"edittime\"='0' and D.\"CusState\"='0'							 
where A.\"IDNO\" = '$f_idno'");
$res_p=pg_fetch_array($qry_print);

$cus_id=trim($res_p["CusID"]);

$qry_fn=pg_query("select *,to_char(\"N_OT_DATE\",'dd/mm/yyyy') AS otdate from \"Fn\" where \"CusID\"='$cus_id'  ");
$res_fn=pg_fetch_array($qry_fn); 

$CustByYear = $res_p["P_CustByYear"];
$P_BEGIN = $res_p["P_BEGIN"];

$av_no=$res_p["IDNO"];
$av_datestart=$res_p["stdate"];
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
$cus_occ=trim($res_fn["N_OCC"]);
$cus_d_id=trim($res_fn["otdate"]);
$cus_id_outby=trim($res_fn["N_BY"]); 

if(trim($res_p["A_SOI"])==""){
	$s_soi="";
}else{
	$s_soi=" ซอย ".trim($res_p["A_SOI"]);
}

if(trim($res_p["A_RD"])==""){
	$s_rd="";
}else{
	$s_rd=" ถนน ".trim($res_p["A_RD"]);
}

$cus_add=trim($res_p["A_NO"])."  ม.".trim($res_p["A_SUBNO"]).$s_soi." ".$s_rd."  แขวง/ตำบล ".trim($res_p["A_TUM"]);

$cus_pro="เขต/อำเภอ ".trim($res_p["A_AUM"])."  จังหวัด ".trim($res_p["A_PRO"]);


$car_year =trim($res_p["car_year"]); //ปีรถ
$car_regis=trim($res_p["car_regis"]); //ทะเบียน
$car_province=trim($res_p["car_regis_by"]); //ทะเบียนจังหวัด
$car_number=trim($res_p["carnum"]); //เลขตัวถัง
$car_engine=trim($res_p["marnum"]); //เลขเครื่อง
$car_mi=trim($res_p["fc_milage"]); //ไมล์

$fp_fc_type = $res_p["fc_type"]; // ประเภท รถยนต์/จักรยายนต์
$fp_fc_brand = $res_p["fc_brand"]; //ยี่ห้อ
$fp_fc_model = $res_p["fc_model"]; //รุ่น
$fp_fc_category = $res_p["fc_category"]; //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
$fp_fc_newcar = $res_p["fc_newcar"]; //รถใหม่หรือรถใช้แล้ว	
$fp_fc_gas = $res_p["fc_gas"]; //ระบบแก๊สรถยนต์	
$gas_name = $res_p["gas_name"]; //ยี่ห้อแก๊ส	
$gas_number = $res_p["gas_number"]; //เลขถังแก๊ส	
$gas_type = $res_p["fc_gas"]; //ชนิดแก๊ส	

			if($fp_fc_type != ""){
				//หาประเภท
				$qry_sel_type = pg_query("select \"astypeName\" FROM \"thcap_asset_biz_astype\" WHERE \"astypeID\" = '$fp_fc_type' ");
				list($fp_type) = pg_fetch_array($qry_sel_type);
				//หายี่ห้อ
				$qry_sel_brand = pg_query("select \"brand_name\" FROM \"thcap_asset_biz_brand\" WHERE \"brandID\" = '$fp_fc_brand' ");
				list($fp_band) = pg_fetch_array($qry_sel_brand);
				//หารุ่น
				$qry_sel_model = pg_query("select \"model_name\" FROM \"thcap_asset_biz_model\" WHERE \"modelID\" = '$fp_fc_model' ");
				list($fp_model) = pg_fetch_array($qry_sel_model);
				//แปลงสถานะรถเป็น text
				if($fp_fc_newcar == '1'){
					$newcar = 'รถใหม่';			
				}else if($fp_fc_newcar == '2'){
					$newcar = 'รถใช้แล้ว';
				}
			}else{
				$fp_type = 'รถยนต์';
				$fp_band = '-- ไม่ระบุ --';
				$fp_model = '-- ไม่ระบุ --';
			}

$car_tax_exp=trim($res_p["c_tax_exp"]);
$car_tax_amount=trim($res_p["C_TAX_MON"]);

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

$arow=10;
$acol=0;
$h1 = 10;
$h2 = 20;
$w1 = 5;
$w2 = 10;
$w3 = 65;
$w4 = 50;
$w5 = 40;

$car_color_icon=iconv('UTF-8','windows-874',"หน้า ".$pdf->PageNo());
$pdf->Text(195,10,$car_color_icon); 

$name_cc=iconv('UTF-8','windows-874',"วันที่พิมพ์ : ".$nowdate);
$pdf->Text(10,12,$name_cc);
$name_cc=iconv('UTF-8','windows-874',"รายงานตรวจสอบข้อมูลที่ User มีการคีย์เข้า");
$pdf->Text(10,17,$name_cc); 

/*$name_comp=" สาขาบริษัทที่ทำสัญญา : สำนักงานใหญ่ ตั้งอยู่อาคาร เลขที่ 999 หมู่ 10";
$name_comp_ads="ถนน นวมินทร์ แขวง นวลจันทร์ เขต บึงกุ่ม  จังหวัด กทม T.944-2000";*/

$name_comp="สาขาสำนักงานใหญ่ ตั้งอยู่อาคาร เลขที่ 555";
$name_comp_ads="ถนนนวมินทร์ แขวงคลองกุ่ม เขตบึงกุ่ม  จังหวัด กทม. 10240 โทร 02-744-2222";

$line01=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________");
$pdf->Text(7,10+$arow,$line01); //เส้น

$name_cc=iconv('UTF-8','windows-874',$name_comp);
$pdf->Text(7,17+$arow,$name_cc); //บริษัท


$name_ads=iconv('UTF-8','windows-874',$name_comp_ads);
$pdf->Text(100,17+$arow,$name_ads); //ที่อยู่

$av_datestart_icon=iconv('UTF-8','windows-874',"วันที่ทำสัญญา : ".$av_datestart);
$pdf->Text(10,24+$arow,$av_datestart_icon); //วันที่ทำสัญญา

$av_no=iconv('UTF-8','windows-874',"เลขที่สัญญา : ".$av_no);
$pdf->Text(50+$w3,24+$arow,$av_no); //เลขที่สัญญา

$cus_name_icon=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ : ".$cus_name);
$pdf->Text(10,31+$arow,$cus_name_icon); //ผู้ทำสัญญา

$cus_mname_icon=iconv('UTF-8','windows-874',"ชื่อคู่สมรสของผู้เช่าซื้อ : ".$cus_mname);
$pdf->Text(50+$w3,31+$arow,$cus_mname_icon); //คู่สมรสทำสัญญา

$pdf->Text(7,34+$arow,$line01); //เส้น

//ประวัติและที่อยู่จริง
$his=iconv('UTF-8','windows-874',"ประวัติและที่อยู่จริงของผู้เช่าซื้อ ");
$pdf->Text(7,41+$arow,$his); 
$cus_nation_icon=iconv('UTF-8','windows-874',"สัญชาติ : ".$cus_nation);
$pdf->Text(10,48+$arow,$cus_nation_icon); //สัญชาติ

$cus_age_icon=iconv('UTF-8','windows-874',"อายุ : ".$cus_age);
$pdf->Text(70,48+$arow,$cus_age_icon); //อายุ

$cus_type_icon=iconv('UTF-8','windows-874',"บัตรแสดงตัว : ".$cus_type);
$pdf->Text(50+$w3,48+$arow,$cus_type_icon); //บัตรแสดง

$cus_id_icon=iconv('UTF-8','windows-874',"เลขที่บัตร : ".$cus_nid);
$pdf->Text(50+$w3+$w4,48+$arow,$cus_id_icon); //เลขที่บัตร

$cus_d_id_icon=iconv('UTF-8','windows-874',"วันที่ออกบัตร : ".$cus_d_id);
$pdf->Text(10,55+$arow,$cus_d_id_icon); //วันที่ออกบัตร

$cus_id_outby_icon=iconv('UTF-8','windows-874',"ออกบัตรโดย : ".$cus_id_outby);
$pdf->Text(70,55+$arow,$cus_id_outby_icon); //ออกบัตรโดย

$car_brand_icon=iconv('UTF-8','windows-874',"อาชีพ : ".$cus_occ);
$pdf->Text(50+$w3+$w4,55+$arow,$car_brand_icon); //อาชีพ

$car_brand_icon=iconv('UTF-8','windows-874',"บ้านเลขที่ : ".trim($res_p["A_NO"]));
$pdf->Text(10,62+$arow,$car_brand_icon); //บ้านเลขที่

$car_brand_icon=iconv('UTF-8','windows-874',"หมู่ที่ : ".trim($res_p["A_SUBNO"]));
$pdf->Text(70,62+$arow,$car_brand_icon); //หมู่ที่

$car_brand_icon=iconv('UTF-8','windows-874',"ซอย : ".trim($res_p["A_SOI"]));
$pdf->Text(50+$w3,62+$arow,$car_brand_icon); //ซอย

$car_brand_icon=iconv('UTF-8','windows-874',"ถนน : ".trim($res_p["A_RD"]));
$pdf->Text(50+$w3+$w4,62+$arow,$car_brand_icon); //ถนน

$car_brand_icon=iconv('UTF-8','windows-874',"ตำบล : ".trim($res_p["A_TUM"]));
$pdf->Text(10,69+$arow,$car_brand_icon); //แขวง/ตำบล

$car_brand_icon=iconv('UTF-8','windows-874',"อำเภอ : ".trim($res_p["A_AUM"]));
$pdf->Text(70,69+$arow,$car_brand_icon); //อำเภอ/เขต

$car_brand_icon=iconv('UTF-8','windows-874',"จังหวัด : ".trim($res_p["A_PRO"]));
$pdf->Text(50+$w3,69+$arow,$car_brand_icon); //จังหวัด

$pdf->Text(7,72+$arow,$line01); 

//รายละเอียดรถยนต์
$car_detail=iconv('UTF-8','windows-874',"รายละเอียดรถยนต์ ");
$pdf->Text(7,79+$arow,$car_detail); 

$car_brand_icon=iconv('UTF-8','windows-874',"ยี่ห้อ : ".$fp_band);
$pdf->Text(10,86+$arow,$car_brand_icon); //ยี่ห้อรถ

$car_year_icon=iconv('UTF-8','windows-874',"รุ่น : ".$fp_model);
$pdf->Text(70,86+$arow,$car_year_icon); //รุ่น

$car_regis_icon=iconv('UTF-8','windows-874',"ทะเบียน : ".$car_regis);
$pdf->Text(50+$w3,86+$arow,$car_regis_icon); //ทะเบียน

$car_province_icon=iconv('UTF-8','windows-874',"จังหวัด : ".$car_province);
$pdf->Text(50+$w3+$w4,86+$arow,$car_province_icon); //จังหวัดที่จดทะเบียน

$car_color_icon=iconv('UTF-8','windows-874',"ระบบแก๊สรถยนต์ : ".$fp_fc_gas);
$pdf->Text(10,93+$arow,$car_color_icon); //สี

$car_number_icon=iconv('UTF-8','windows-874',"เลขตัวถัง : ".$car_number);
$pdf->Text(70,93+$arow,$car_number_icon); //เลขตัวถัง

$car_engine_icon=iconv('UTF-8','windows-874',"เลขเครื่อง : ".$car_engine);
$pdf->Text(50+$w3,93+$arow,$car_engine_icon); //เลขเครื่อง

$car_mi_icon=iconv('UTF-8','windows-874',"เลขกิโล : ".$car_mi);
$pdf->Text(50+$w3+$w4,93+$arow,$car_mi_icon); //เลขกิโล


$car_year_icon=iconv('UTF-8','windows-874',"ปี : ".$car_year);
$pdf->Text(10,100+$arow,$car_year_icon); //ปี

$car_year_icon=iconv('UTF-8','windows-874',"ชนิดรถ : ".$fp_fc_category);
$pdf->Text(70,100+$arow,$car_year_icon); //ชนิดรถ

$car_regis_icon=iconv('UTF-8','windows-874',"เป็นรถ : ".$newcar);
$pdf->Text(50+$w3,100+$arow,$car_regis_icon); //ทะเบียน

$car_province_icon=iconv('UTF-8','windows-874',"ประเภทรถ : ".$fp_type);
$pdf->Text(50+$w3+$w4,100+$arow,$car_province_icon); //ประเภทรถ

$car_year_icon=iconv('UTF-8','windows-874',"ยี่ห้อแก๊ส : ".$gas_name);
$pdf->Text(10,107+$arow,$car_year_icon); //ยี่ห้อแก๊ส

$car_year_icon=iconv('UTF-8','windows-874',"เลขถังแก๊ส : ".$gas_number);
$pdf->Text(70,107+$arow,$car_year_icon); //เลขถังแก๊ส

$car_regis_icon=iconv('UTF-8','windows-874',"ระบบแก๊ส : ".$gas_type);
$pdf->Text(50+$w3,107+$arow,$car_regis_icon); //ระบบแก๊ส


$car_option="แอร์,วิทยุ,เทป,ล้อแม็กซ์,ยางอะไหล่";
$car_option_icon=iconv('UTF-8','windows-874',"อุปกรณ์ติดรถ : ".$car_option);
$pdf->Text(10,114+$arow,$car_option_icon); //option

$pdf->Text(7,117+$arow,$line01); 

//รายละเอียดสินเชื่อ
$car_detail=iconv('UTF-8','windows-874',"รายละเอียดสินเชื่อ");
$pdf->Text(7,124+$arow,$car_detail); 

$cost_icon=iconv('UTF-8','windows-874',"มูลค่าเช่าซื้อ : ".number_format($cost,2));

$pdf->Text(10,131+$arow,$cost_icon); 

$vat_bath_icon=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม : ".number_format($vat_bath,2));

$pdf->Text(70,131+$arow,$vat_bath_icon); 

$vat_bath_icon=iconv('UTF-8','windows-874',"รวมเป็นเงิน : ".number_format($cost_total,2));

$pdf->Text(50+$w3,131+$arow,$vat_bath_icon); 

$res_th_icon=iconv('UTF-8','windows-874',"หรือยอดรวมเป็นเเงิน : ".$res_cost);

$pdf->Text(10,138+$arow,$res_th_icon); 

$sl_icon=iconv('UTF-8','windows-874',"เงินต้น : ".number_format($P_BEGIN,2));

$pdf->Text(50+$w3+$w4,138+$arow,$sl_icon); 

$cost_paydtl_icon=iconv('UTF-8','windows-874',"DownPayment : ".number_format($down,2));
$pdf->Text(10,145+$arow,$cost_paydtl_icon); 

$cost_paydtl_vat_icon=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม : ".number_format($res_p["P_VatOfDown"],2));
$pdf->Text(70,145+$arow,$cost_paydtl_vat_icon); 

$cost_payment_icon=iconv('UTF-8','windows-874',"รวมเป็นเงิน : ".number_format($res_p["P_VatOfDown"]+$down,2));
$pdf->Text(50+$w3,145+$arow,$cost_payment_icon); 

$cost_payment_icon=iconv('UTF-8','windows-874',"จำนวนงวดชำระ : ".$se_total);
$pdf->Text(10,152+$arow,$cost_payment_icon); 

$cost_before_vat_icon=iconv('UTF-8','windows-874',"งวดเดือนละ : ".number_format($res_p["P_MONTH"],2));
$pdf->Text(70,152+$arow,$cost_before_vat_icon); 

$cost_payment_vat_icon=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม : ".number_format($res_p["P_VAT"],2));
$pdf->Text(50+$w3,152+$arow,$cost_payment_vat_icon); 

$cost_paybaht_icon=iconv('UTF-8','windows-874',"รวมเป็นเงิน : ".number_format($res_p["P_MONTH"]+$res_p["P_VAT"],2));
$pdf->Text(50+$w3+$w4,152+$arow,$cost_paybaht_icon); 

$fdate=substr($res_p["P_FDATE"],8,2);

$datepay_icon=iconv('UTF-8','windows-874',"ชำระภายในวันที่ : ".$fdate);
$pdf->Text(10,159+$arow,$datepay_icon); 

$date_first_icon=iconv('UTF-8','windows-874',"ชำระงวดแรกวันที่ : ".$res_p["fdate_thaidate"]);
$pdf->Text(70,159+$arow,$date_first_icon); 

//----------------------

$datepay_icon=iconv('UTF-8','windows-874',"ประเภทเช่าซื้อ: --".$cus_type1);
$pdf->Text(10,166+$arow,$datepay_icon); 

$date_first_icon=iconv('UTF-8','windows-874',"สถานะผู้เช่าซื้อ : --".$cus_st);
$pdf->Text(70,166+$arow,$date_first_icon); 

$cost_payment_vat_icon=iconv('UTF-8','windows-874',"ประเภทชำระเงิน : --".$type_p);
$pdf->Text(50+$w3,166+$arow,$cost_payment_vat_icon); 

$car_detail=iconv('UTF-8','windows-874',"  NHP = ลูกค้าเช่าซื้อปกติ , 10HP = ลูกค้าเช่าซื้อชำระทุก 10 วัน  , PLED = ลูกค้าจำนำ");
$pdf->Text(20,173+$arow,$car_detail); 

$car_detail=iconv('UTF-8','windows-874',"  IND = ลูกค้าบุคคลธรรมดา , COM = ลูกค้านิติบุคคล  , TR = ลูกค้าโอนเงินเพื่อชำระ , OC = ลูกค้ามาชำระเอง");
$pdf->Text(20,180+$arow,$car_detail); 

$datepay_icon=iconv('UTF-8','windows-874',"เป็นลูกค้าประจำปี : ".$CustByYear);
$pdf->Text(10,187+$arow,$datepay_icon); 

$date_first_icon=iconv('UTF-8','windows-874',"ผู้ควบคุมดูแลลูกค้า : --");
$pdf->Text(70,187+$arow,$date_first_icon); 

$pdf->Text(7,190+$arow,$line01); 

//รายละเอียดประกันภัย
$qry_if=pg_query("select *,to_char(\"ConfirmDate\",'dd/mm/yyyy') AS c_d from \"insure\".\"InsureUnforce\" WHERE \"IDNO\" = '$f_idno' AND \"Cancel\"='FALSE' ");
$rows = pg_num_rows($qry_if);
while($res_if=pg_fetch_array($qry_if)){
   // $InsUFIDNO = $res_if["InsUFIDNO"];
    $IDNO = $res_if["IDNO"];
    $InsID = $res_if["InsID"]; 
    $Invest = $res_if["Invest"];
    $Premium = $res_if["Premium"]; $Premium = round($Premium,2);
	$Net_Premium = $res_if["NetPremium"]; $Net_Premium = round($Net_Premium,2);
    $Company = $res_if["Company"];
    $c_d = $res_if["c_d"];  
}

$arow =$arow+28;
$car_detail=iconv('UTF-8','windows-874',"รายละเอียดประกันภัย");
$pdf->Text(7,169+$arow,$car_detail); 

$car_brand_icon=iconv('UTF-8','windows-874',"ทำประกันกับบริษัท : --".$b_do);
$pdf->Text(10,176+$arow,$car_brand_icon); 

$car_year_icon=iconv('UTF-8','windows-874',"บริษัทประกันภัย : ".$Company);
$pdf->Text(70,176+$arow,$car_year_icon); 

$car_regis_icon=iconv('UTF-8','windows-874',"เลขที่กรมธรรม์ : ".$InsID);
$pdf->Text(50+$w3,176+$arow,$car_regis_icon); 

$car_province_icon=iconv('UTF-8','windows-874',"วันที่ทำประกัน : ".$c_d);
$pdf->Text(50+$w3+$w4,176+$arow,$car_province_icon); 

$car_color_icon=iconv('UTF-8','windows-874',"ทุนประกัน : ".number_format($Invest,2));
$pdf->Text(10,183+$arow,$car_color_icon); 

$car_number_icon=iconv('UTF-8','windows-874',"เบี้ยประกัน : ".number_format($Premium,2));
$pdf->Text(70,183+$arow,$car_number_icon); 
$car_engine_icon=iconv('UTF-8','windows-874',"เบี้ยประกันสุทธิ : ".number_format($Net_Premium,2));
$pdf->Text(50+$w3,183+$arow,$car_engine_icon); 
$pdf->Text(7,186+$arow,$line01); 

//รายละเอียด พรบ.
$qry_if=pg_query("select *,to_char(\"DoDate\",'dd/mm/yyyy') AS d_d,to_char(\"StartDate\",'dd/mm/yyyy') AS s_d from \"insure\".\"InsureForce\" WHERE \"IDNO\" = '$f_idno' AND \"Cancel\"='FALSE' ");
$rows = pg_num_rows($qry_if);
while($res_if=pg_fetch_array($qry_if)){
   // $InsUFIDNO = $res_if["InsUFIDNO"];
    $IDNO = $res_if["IDNO"];
    $InsID = $res_if["InsID"];
	$InsMark = $res_if["InsMark"];
    $StartDate = $res_if["s_d"];
	$Code = $res_if["Code"]; 
    $Premium = $res_if["Premium"]; $Premium = round($Premium,2);
	$Net_Premium = $res_if["NetPremium"]; $Net_Premium = round($Net_Premium,2);
    $Company = $res_if["Company"];
    $d_d = $res_if["d_d"];  
}

$car_detail=iconv('UTF-8','windows-874',"รายละเอียด พรบ.");
$pdf->Text(7,193+$arow,$car_detail); 

$car_brand_icon=iconv('UTF-8','windows-874',"ทำ พรบ. กับบริษัท : --".$do_prb);
$pdf->Text(10,200+$arow,$car_brand_icon); 

$car_year_icon=iconv('UTF-8','windows-874',"บริษัทประกันภัย : ".$Company);
$pdf->Text(70,200+$arow,$car_year_icon); 

$car_regis_icon=iconv('UTF-8','windows-874',"เลขที่กรมธรรม์ : ".$InsID);
$pdf->Text(50+$w3,200+$arow,$car_regis_icon); 

$car_color_icon=iconv('UTF-8','windows-874',"เลขเครื่องหมาย : ".$InsMark );
$pdf->Text(10,207+$arow,$car_color_icon); 

$car_number_icon=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ: --".$InsMark1 );
$pdf->Text(70,207+$arow,$car_number_icon); 
$car_engine_icon=iconv('UTF-8','windows-874',"วันเริ่มคุ้มครอง: ".$StartDate);
$pdf->Text(50+$w3,207+$arow,$car_engine_icon); 

$car_province_icon=iconv('UTF-8','windows-874',"วันที่ออก พรบ. : ".$d_d);
$pdf->Text(50+$w3+$w4,207+$arow,$car_province_icon); 

$car_color_icon=iconv('UTF-8','windows-874',"รหัส พรบ. : ".$Code);
$pdf->Text(10,214+$arow,$car_color_icon); 

$car_number_icon=iconv('UTF-8','windows-874',"ค่าเบี้ย พรบ.: ".number_format($Premium,2));
$pdf->Text(70,214+$arow,$car_number_icon); 
$car_engine_icon=iconv('UTF-8','windows-874',"ค่าเบี้ย พรบ. สุทธิ: ".number_format($Net_Premium,2));
$pdf->Text(50+$w3,214+$arow,$car_engine_icon); 

$pdf->Text(7,217+$arow,$line01); 


//ผู้ค้ำ
$qry_print_dat=pg_query("select * from \"ContactCus\" where (\"IDNO\" = '$f_idno') AND (\"CusState\"!=0) order by \"CusState\"");						   
$numnum = pg_num_rows($qry_print_dat);
if($numnum==0){
	$gua=iconv('UTF-8','windows-874',"ไม่มีข้อมูลรายละเอียดผู้ค้ำ");
	$pdf->Text(7,224+$arow,$gua); 
}else{
	$pdf->AddPage();
	$name_cc=iconv('UTF-8','windows-874',"วันที่พิมพ์ : ".$nowdate);
	$pdf->Text(10,12,$name_cc);
	$name_cc=iconv('UTF-8','windows-874',"รายงานตรวจสอบข้อมูลที่ User มีการคีย์เข้า");
	$pdf->Text(10,17,$name_cc); 
	
	$nn=1;
	$arow=10;
	$pdf->Text(7,10+$arow,$line01); //เส้น
	$car_color_icon=iconv('UTF-8','windows-874',"หน้า ".$pdf->PageNo());
	$pdf->Text(195,10,$car_color_icon); 
	while($res_fp=pg_fetch_array($qry_print_dat))
	{
		$rescus_id=$res_fp["CusID"];
 
		$qry_sname=pg_query("select B.*,to_char(B.\"N_OT_DATE\",'dd/mm/yyyy') AS otdate,
		A.\"A_FIRNAME\",A.\"A_NAME\",A.\"A_SIRNAME\",A.\"A_PAIR\",
		C.\"A_NO\",C.\"A_SUBNO\",C.\"A_SOI\",C.\"A_RD\",C.\"A_TUM\",C.\"A_AUM\",C.\"A_PRO\",C.\"A_POST\"
		from \"Fa1\" A 
		LEFT OUTER JOIN  \"Fn\" B ON A.\"CusID\" = B.\"CusID\"
		LEFT OUTER JOIN \"Fp_Fa1\" C ON A.\"CusID\" = C. \"CusID\" and C.\"edittime\"='0' and C.\"IDNO\"='$f_idno'
		WHERE  A.\"CusID\"='$rescus_id' ");
		
		$res_cc=pg_fetch_array($qry_sname);
 
		$av_cname=trim($res_cc["A_FIRNAME"])." ".trim($res_cc["A_NAME"])." ".trim($res_cc["A_SIRNAME"]);
 
		// Detail  
		$gua=iconv('UTF-8','windows-874',"รายละเอียดผู้ค้ำประกันอันดับ  ".$nn);
		$pdf->Text(7,17+$arow,$gua); 

		$av_names=iconv('UTF-8','windows-874',"ชื่อ : ".$av_cname);
		$pdf->Text(10,24+$arow+$crow,$av_names);

		$av_pair=iconv('UTF-8','windows-874',"ชื่อคู่สมรส : ".$res_cc["A_PAIR"]);
		$pdf->Text(50+$w3,24+$arow+$crow,$av_pair); //คู่สมรส

		$cus_nation_icon=iconv('UTF-8','windows-874',"สัญชาติ : ".$res_cc["N_SAN"]);
		$pdf->Text(10,31+$arow,$cus_nation_icon); //สัญชาติ

		$cus_age_icon=iconv('UTF-8','windows-874',"อายุ : ".$res_cc["N_AGE"]);
		$pdf->Text(70,31+$arow,$cus_age_icon); //อายุ

		$cus_type_icon=iconv('UTF-8','windows-874',"บัตรแสดงตัว : ".$res_cc["N_CARD"]);
		$pdf->Text(50+$w3,31+$arow,$cus_type_icon); //บัตรแสดง
		
		if($res_cc["N_CARD"]=="บัตรประชาชน" || $res_cc["N_CARD"]=="ประชาชน"){
			$idcard=$res_cc["N_IDCARD"];
		}else{
			$idcard=$res_cc["N_CARDREF"];
		}
		$cus_id_icon=iconv('UTF-8','windows-874',"เลขที่บัตร : ".$idcard);
		$pdf->Text(50+$w3+$w4,31+$arow,$cus_id_icon); //เลขที่บัตร

		$cus_d_id_icon=iconv('UTF-8','windows-874',"วันที่ออกบัตร : ".$res_cc["otdate"]);
		$pdf->Text(10,38+$arow,$cus_d_id_icon); //วันที่ออกบัตร

		$cus_id_outby_icon=iconv('UTF-8','windows-874',"ออกบัตรโดย : ".$res_cc["N_BY"]);
		$pdf->Text(70,38+$arow,$cus_id_outby_icon); //ออกบัตรโดย

		$car_brand_icon=iconv('UTF-8','windows-874',"อาชีพ : ".trim($res_cc["N_OCC"]));
		$pdf->Text(50+$w3+$w4,38+$arow,$car_brand_icon); //อาชีพ

		$car_brand_icon=iconv('UTF-8','windows-874',"บ้านเลขที่ : ".trim($res_cc["A_NO"]));
		$pdf->Text(10,45+$arow,$car_brand_icon); //บ้านเลขที่

		$car_brand_icon=iconv('UTF-8','windows-874',"หมู่ที่ : ".trim($res_cc["A_SUBNO"]));
		$pdf->Text(70,45+$arow,$car_brand_icon); //หมู่ที่

		$car_brand_icon=iconv('UTF-8','windows-874',"ซอย : ".trim($res_cc["A_SOI"]));
		$pdf->Text(50+$w3,45+$arow,$car_brand_icon); //ซอย


		$car_brand_icon=iconv('UTF-8','windows-874',"ถนน : ".trim($res_cc["A_RD"]));
		$pdf->Text(50+$w3+$w4,45+$arow,$car_brand_icon); //ถนน

		$car_brand_icon=iconv('UTF-8','windows-874',"ตำบล : ".trim($res_cc["A_TUM"]));
		$pdf->Text(10,52+$arow,$car_brand_icon); //แขวง/ตำบล

		$car_brand_icon=iconv('UTF-8','windows-874',"อำเภอ : ".trim($res_cc["A_AUM"]));
		$pdf->Text(70,52+$arow,$car_brand_icon); //อำเภอ/เขต

		$car_brand_icon=iconv('UTF-8','windows-874',"จังหวัด : ".trim($res_cc["A_PRO"]));
		$pdf->Text(50+$w3,52+$arow,$car_brand_icon); //จังหวัด
	 
		$pdf->Text(7,55+$arow,$line01); 

		$arow= $arow+45; 

		if($nn%6==0 &&  $nn!=$numnum){
			$arow=10;
			$pdf->AddPage();
	
			$name_cc=iconv('UTF-8','windows-874',"วันที่พิมพ์ : ".$nowdate);
			$pdf->Text(10,12,$name_cc);
			$name_cc=iconv('UTF-8','windows-874',"รายงานตรวจสอบข้อมูลที่ User มีการคีย์เข้า");
			$pdf->Text(10,17,$name_cc); 
			$pdf->Text(7,10+$arow,$line01); //เส้น
			$car_color_icon=iconv('UTF-8','windows-874',"หน้า ".$pdf->PageNo());
			$pdf->Text(195,10,$car_color_icon); 
		}
		$nn++;
	}//end while		 
}  //end if

$pdf->Output();
?>