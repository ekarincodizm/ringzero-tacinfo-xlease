<?php
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");

/*-============================================================================-
								   สัญญาเช่าซื้อ	
								ดึงข้อมูลจากตาราง
-============================================================================-*/
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$f_idno=$_GET["ID"];

//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) พิมพ์สัญญาเช่าซื้อ', '$add_date')");
//ACTIONLOG---



//ตรวจสอบว่าเป็นสัญญาแก๊สหรือธรรมดา
$qry_chk_gas = pg_query("select * FROM \"Fp\" WHERE \"IDNO\" = '$f_idno' AND \"asset_id\" IN (select \"CarID\" FROM \"Fc\") ");
$row_chk_gas = pg_num_rows($qry_chk_gas);

if($row_chk_gas == 0){ //กรณีเป็นสัญญาแก๊ส
	$quevar = "C.\"GasID\" AS \"CarID\", C.\"car_year\" AS \"C_YEAR\", C.\"car_regis\" AS \"C_REGIS\",C.\"car_regis_by\" AS \"C_REGIS_BY\", C.\"carnum\" AS \"C_CARNUM\",C.\"fc_milage\" AS \"C_Milage\", C.\"marnum\" AS \"C_MARNUM\" ";
	$tb1 = 'FGas';
	$PK1 = 'asset_id';
	$PK2 = 'GasID';
}else{
	$quevar = "C.\"CarID\",C.\"C_CARNAME\", C.\"C_YEAR\", C.\"C_REGIS\",C.\"C_REGIS_BY\", C.\"C_COLOR\", C.\"C_CARNUM\",C.\"C_Milage\",C.\"C_MARNUM\"";
		
	$tb1 = 'VCarregistemp'; //ตารางข้อมูลรถปัจจุบันของเลขที่สัญญา
	$PK1 = 'IDNO';
	$PK2 = 'IDNO';
}

//รายละเอียดบุคคล
$qry_print=pg_query("SELECT to_char(A.\"P_STDATE\",'dd/mm/yyyy') AS stdate, A.\"IDNO\",A.\"CusID\",A.asset_id, A.\"TranIDRef1\", A.\"TranIDRef2\", 
A.\"P_MONTH\", A.\"P_VAT\", A.\"P_TOTAL\", A.\"P_DOWN\", A.\"P_VatOfDown\", A.\"P_BEGIN\", A.\"P_BEGINX\", A.\"P_FDATE\",
to_char(A.\"P_FDATE\",'dd/mm/yyyy') AS fdate_thaidate, A.\"P_CLDATE\", A.\"P_ACCLOSE\",B.\"A_FIRNAME\",B.\"A_NAME\",B.\"A_SIRNAME\",B.\"A_PAIR\",
$quevar,C.\"fc_type\",C.\"fc_brand\",C.\"fc_model\",C.\"fc_category\",C.\"fc_newcar\",	
concat(COALESCE(concat(' บ้านเลขที่ ', btrim(D.\"A_NO\")), ''), '', COALESCE(
CASE
    WHEN trim(D.\"A_SUBNO\") IS NULL OR trim(D.\"A_SUBNO\") = '-' OR trim(D.\"A_SUBNO\") = '--' OR trim(D.\"A_SUBNO\") = '  ' OR replace(trim(D.\"A_SUBNO\"),' ','') = ''  THEN ''
    ELSE concat(' หมู่ ', btrim(D.\"A_SUBNO\"))
    END, ''), '', COALESCE(
CASE
	WHEN trim( D.\"A_BUILDING\") IS NULL OR trim( D.\"A_BUILDING\") = '-' OR trim( D.\"A_BUILDING\") = '--' OR trim( D.\"A_BUILDING\") = ' ' OR replace(trim(D.\"A_BUILDING\"),' ','') = '' THEN ''
	ELSE concat(' อาคาร', btrim(D.\"A_BUILDING\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( D.\"A_ROOM\") IS NULL OR trim( D.\"A_ROOM\") = '-' OR trim( D.\"A_ROOM\") = '--' OR trim( D.\"A_ROOM\") = ' ' OR replace(trim(D.\"A_ROOM\"),' ','') = '' THEN ''
	ELSE concat(' ห้อง', btrim(D.\"A_ROOM\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( D.\"A_FLOOR\") IS NULL OR trim( D.\"A_FLOOR\") = '-' OR trim( D.\"A_FLOOR\") = '--' OR trim( D.\"A_FLOOR\") = ' ' OR replace(trim(D.\"A_FLOOR\"),' ','') = '' THEN ''
	ELSE concat(' ชั้น', btrim(D.\"A_FLOOR\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( D.\"A_BAN\") IS NULL OR trim( D.\"A_BAN\") = '-' OR trim( D.\"A_BAN\") = '--' OR trim( D.\"A_BAN\") = ' ' OR replace(trim(D.\"A_BAN\"),' ','') = '' THEN ''
	ELSE concat(' หมู่บ้าน', btrim(D.\"A_BAN\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( D.\"A_SOI\") IS NULL OR trim( D.\"A_SOI\") = '-' OR trim(D.\"A_SOI\") = '--' OR trim( D.\"A_SOI\") = ' ' OR replace(trim(D.\"A_SOI\"),' ','') = '' THEN ''
	ELSE concat(' ซอย', btrim(D.\"A_SOI\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( D.\"A_RD\") IS NULL OR trim( D.\"A_RD\") = '-' OR trim( D.\"A_RD\") = '--' OR trim( D.\"A_RD\") = ' ' OR replace(trim(D.\"A_RD\"),' ','') = '' THEN ''
	ELSE concat(' ถนน', btrim(D.\"A_RD\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( D.\"A_TUM\") IS NULL OR trim( D.\"A_TUM\") = '-' OR trim( D.\"A_TUM\") = '--' OR trim( D.\"A_TUM\") = ' ' OR replace(trim(D.\"A_TUM\"),' ','') = '' THEN ''
	ELSE 
		CASE
		WHEN trim(D.\"A_PRO\") LIKE 'กรุงเทพ%' OR trim(D.\"A_PRO\") LIKE 'กรุงเทพฯ%' OR trim(D.\"A_PRO\") LIKE 'กรุงเทพมหานคร%'  OR trim(D.\"A_PRO\") LIKE 'กทม%' OR trim(D.\"A_PRO\") LIKE 'กทม.%'   THEN concat(' แขวง', btrim(D.\"A_TUM\"))
		ELSE concat(' ตำบล', btrim(D.\"A_TUM\"))
		END
	END, ''), '', COALESCE(
CASE
	WHEN trim( D.\"A_AUM\") IS NULL OR trim( D.\"A_AUM\") = '-' OR trim( D.\"A_AUM\") = '--' OR trim( D.\"A_AUM\") = ' ' OR replace(trim(D.\"A_AUM\"),' ','') = '' THEN ''
	ELSE 
		CASE
		WHEN trim(D.\"A_PRO\") LIKE 'กรุงเทพ%' OR trim(D.\"A_PRO\") LIKE 'กรุงเทพฯ%' OR trim(D.\"A_PRO\") LIKE 'กรุงเทพมหานคร%'  OR trim(D.\"A_PRO\") LIKE 'กทม%' OR trim(D.\"A_PRO\") LIKE 'กทม.%'  THEN concat(' เขต', btrim(D.\"A_AUM\"), ' ')
		ELSE concat(' อำเภอ', btrim(D.\"A_AUM\"), ' ')
		END
	END, ''), '', COALESCE(
CASE
	WHEN trim( D.\"A_PRO\") IS NULL OR trim( D.\"A_PRO\") = ' ' THEN ''
	ELSE 
		CASE
		WHEN trim(D.\"A_PRO\") LIKE 'กรุงเทพ%' OR trim(D.\"A_PRO\") LIKE 'กรุงเทพฯ%' OR trim(D.\"A_PRO\") LIKE 'กรุงเทพมหานคร%'  OR trim(D.\"A_PRO\") LIKE 'กทม%' OR trim(D.\"A_PRO\") LIKE 'กทม.%'   THEN 'กรุงเทพมหานคร'
		ELSE concat('จังหวัด', btrim(D.\"A_PRO\"))
		END
	END, ''), ' ', COALESCE(
CASE
	WHEN trim( D.\"A_POST\") IS NULL OR trim( D.\"A_POST\") = '-' OR trim( D.\"A_POST\") = '--' OR trim( D.\"A_POST\") = '0' OR trim( D.\"A_POST\") = ' ' THEN ''
	ELSE btrim(D.\"A_POST\")
	END, ''), '', '') AS address
	
	
FROM \"Fp\" A  
LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\" = B. \"CusID\"
LEFT OUTER JOIN \"$tb1\" C ON A.\"$PK1\" = C. \"$PK2\"
LEFT OUTER JOIN \"Fp_Fa1\" D ON A.\"IDNO\" = D. \"IDNO\" and D.\"edittime\"='0' and D.\"CusState\"='0'
WHERE A.\"IDNO\" = '$f_idno'");

$res_p=pg_fetch_array($qry_print);
$cus_id=trim($res_p["CusID"]);
$av_no=$res_p["IDNO"];
$av_datestart=$res_p["stdate"];
$cus_name=trim($res_p["A_FIRNAME"])." ".trim($res_p["A_NAME"])."  ".trim($res_p["A_SIRNAME"]);
$cus_mname=trim($res_p["A_PAIR"]);

$qry_fn=pg_query("select *,to_char(\"N_OT_DATE\",'dd/mm/yyyy') AS otdate from \"Fn\" where \"CusID\"='$cus_id'  ");
$res_fn=pg_fetch_array($qry_fn); 
$cus_nation=trim($res_fn["N_SAN"]);
$cus_age=trim($res_fn["N_AGE"]);
$cus_type=trim($res_fn["N_CARD"]);
$cus_occ=trim($res_fn["N_OCC"]);
$cus_d_id=trim($res_fn["otdate"]);
$cus_id_outby=trim($res_fn["N_BY"]); 
if($cus_type=="บัตรประชาชน" || $cus_type=="ประชาชน"){
	$cus_nid=trim($res_fn["N_IDCARD"]);
	$cus_no_txt = " ผู้ถือบัตรประชาชนเลขที่ $cus_nid";
}else{
	$cus_nid=trim($res_fn["N_CARDREF"]);
	$cus_no_txt = " $cus_type เลขที่ $cus_nid";
}
	
//กรณีแสดงสัญชาติ
if($cus_nation==""){
	$cus_nation_txt="";
}else{
	$cus_nation_txt=" สัญชาติ $cus_nation";
}


$cus_addr = trim($res_p["address"]); //ที่อยู่ปัจจุบันของลูกค้า
$car_brand =trim($res_p["C_CARNAME"]);
IF($car_brand == "''" OR $car_brand == " " OR $car_brand == "-" OR $car_brand == "-" OR $car_brand == ""){ $car_brand = "--ไม่ระบุ--"; }
$car_year =trim($res_p["C_YEAR"]);
IF($car_year == "''" OR $car_year == " " OR $car_year == "-" OR $car_year == "-" OR $car_year == ""){ $car_year = "--ไม่ระบุ--"; }
$car_color =trim($res_p["C_COLOR"]);
IF($car_color == "''" OR $car_color == " " OR $car_color == "-" OR $car_color == "-" OR $car_color == ""){ $car_color = "--ไม่ระบุ--"; }
$car_regis=trim($res_p["C_REGIS"]);
IF($car_regis == "''" OR $car_regis == " " OR $car_regis == "-" OR $car_regis == "-" OR $car_regis == ""){ $car_regis = "--ไม่ระบุ--"; }
$car_province=trim($res_p["C_REGIS_BY"]);
IF($car_province == "''" OR $car_province == " " OR $car_province == "-" OR $car_province == "-" OR $car_province == ""){ $car_province = "--ไม่ระบุ--"; }
$car_number=trim($res_p["C_CARNUM"]);
IF($car_number == "''" OR $car_number == " " OR $car_number == "-" OR $car_number == "-" OR $car_number == ""){ $car_number = "--ไม่ระบุ--"; }
$car_engine=trim($res_p["C_MARNUM"]);
IF($car_engine == "''" OR $car_engine == " " OR $car_engine == "-" OR $car_engine == "-" OR $car_engine == ""){ $car_engine = "--ไม่ระบุ--"; }
$car_mi=trim($res_p["C_Milage"]);
IF($car_mi == "''" OR $car_mi == " " OR $car_mi == "-" OR $car_mi == "-" OR $car_mi == ""){ $car_mi = "--ไม่ระบุ--"; }
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

$fdate=substr($res_p["P_FDATE"],8,2);


$fp_fc_type = $res_p["fc_type"]; // ประเภท รถยนต์/จักรยายนต์
$fp_fc_brand = $res_p["fc_brand"]; //ยี่ห้อ
$fp_fc_model = $res_p["fc_model"]; //รุ่น
$fp_fc_category = $res_p["fc_category"]; //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
$fp_fc_newcar = $res_p["fc_newcar"]; //รถใหม่หรือรถใช้แล้ว	

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
	$fp_type = "--ไม่ระบุ--";
	$fp_band = $car_brand;
	$fp_model = "--ไม่ระบุ--";
	$newcar = "--ไม่ระบุ--";
	$fp_fc_category  = "--ไม่ระบุ--";
}


/*-============================================================================-
								   สัญญาเช่าซื้อ	
								กำหนดรายละเอียด
-============================================================================-*/
$var1 = $av_no;	//เลขที่สัญญา
$var2 = $av_datestart;       //วันที่ทำสัญญา
$var3 = $corpType.$cus_name.$cus_nation_txt.$cus_no_txt."\n".$cus_addr;   //ชื่อผู้ทำสัญญา
$var4 = $fp_type;    //รถ
$var5 = $fp_band;   //ยี่ห้อ
$var6 = $fp_model;  //รุ่น
$var7 = $car_year;  //ปีจดทะเบียน
$var8 = $car_regis; //เลขทะเบียนรถ
$var9 = $car_province;  //จังหวัด
$var10 = $fp_fc_category;  //ชนิดรถ
$var11 = $newcar; //เป็นรถ
$var12 = $car_number; //เลขตัวถัง
$var13 = $car_engine; //เลขเครื่องยนต์
$var14 = $car_mi; //ระยะทางที่ปรากฎในเรือนมิเตอร์รถ
$var15 = $car_color; //สีตัวรถ
$var16 = number_format($cost,2); //ราคาเช่าซื้อไม่รวม VAT ไม่รวมเงินดาวน์
$var17 = $res_p["fdate_thaidate"];  //เริ่มชำระงวดแรก
$var18 = $se_total;  //แบ่งชำระเป็น
$var19 = $fdate;  // ชำระทุกวันที่
$var26 = ''; //ข้อ 3
$var20_0 = '01-'.$se_total;  	//งวดที่ block 1
$var20_1 = number_format($res_p["P_MONTH"],2);  	//ค่างวด block 1
$var20_2 = number_format($res_p["P_VAT"],2);		//ภาษีมูลค่าเพิ่ม block 1
$var20_3 = number_format($res_p["P_MONTH"]+$res_p["P_VAT"],2);  	//รวมชำระสุทธิงวดละ block 1
	
$var21_0 = '';  	//งวดที่ block 2
$var21_1 = '';	//ค่างวด block 2
$var21_2 = '';		//ภาษีมูลค่าเพิ่ม block 2
$var21_3 = '';  	//รวมชำระสุทธิงวดละ block 2

$var22_0 = ''; 	//งวดที่ block 3
$var22_1 = '';	//ค่างวด block 3
$var22_2 = '';		//ภาษีมูลค่าเพิ่ม block 3
$var22_3 = '';  	//รวมชำระสุทธิงวดละ block 3

$var23_0 = ''; 	//งวดที่ block 4
$var23_1 = '';	//ค่างวด block 4
$var23_2 = '';		//ภาษีมูลค่าเพิ่ม block 4
$var23_3 = '';  	//รวมชำระสุทธิงวดละ block 4

$var24_0 = ''; 	//งวดที่ block 5
$var24_1 = '';	//ค่างวด block 5
$var24_2 = '';		//ภาษีมูลค่าเพิ่ม block 5
$var24_3 = '';  	//รวมชำระสุทธิงวดละ block 5

//$var25 = $cus_name;   //ชื่อผู้ทำสัญญา ( ลายเซ็น)
	
	
/*-============================================================================-*/	
	
	
	
	
	
	
	
// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

}


$pdf=new PDF('P','mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();	

$Y = 12.5;	
//เลขที่สัญญา	
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(48,$Y);
$title=iconv('UTF-8','windows-874',$var1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 19;	
//วันที่ทำสัญญา	
$pdf->SetXY(165,$Y);
$title=iconv('UTF-8','windows-874',$var2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 19;	
//ผู้เช่าซื้อ	
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$var3);
$pdf->MultiCell(160,5,$title,0,'C',0);

$Y = 99;	
//รถ	
$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',$var4);
$pdf->MultiCell(70,4,$title,0,'L',0);
//ยี่ห้อ
$pdf->SetXY(77,$Y);
$title=iconv('UTF-8','windows-874',$var5);
$pdf->MultiCell(70,4,$title,0,'L',0);
//รุ่น
$pdf->SetXY(122,$Y);
$title=iconv('UTF-8','windows-874',$var6);
$pdf->MultiCell(70,4,$title,0,'L',0);
//ปีจดทะเบียน
$pdf->SetXY(180,$Y);
$title=iconv('UTF-8','windows-874',$var7);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 8;
//เลขทะเบียนรถ
$pdf->SetXY(55,$Y);
$title=iconv('UTF-8','windows-874',$var8);
$pdf->MultiCell(70,4,$title,0,'L',0);
//จังหวัด
$pdf->SetXY(90,$Y);
$title=iconv('UTF-8','windows-874',$var9);
$pdf->MultiCell(70,4,$title,0,'L',0);
//ชนิดรถ
$pdf->SetXY(130,$Y);
$title=iconv('UTF-8','windows-874',$var10);
$pdf->MultiCell(70,4,$title,0,'L',0);
//เป็นรถ
$pdf->SetXY(183,$Y);
$title=iconv('UTF-8','windows-874',$var11);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//เลขตัวถัง
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var12);
$pdf->MultiCell(70,4,$title,0,'L',0);

//เลขเครื่องยนต์
$pdf->SetXY(135,$Y);
$title=iconv('UTF-8','windows-874',$var13);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//ระยะไมล์
$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var14);
$pdf->MultiCell(70,4,$title,0,'L',0);

//สีตัวรถ
$pdf->SetXY(160,$Y);
$title=iconv('UTF-8','windows-874',$var15);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 17;
//ราคาไม่รวม VAT
$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var16);
$pdf->MultiCell(70,4,$title,0,'L',0);

//วันที่เริ่มชำระ
$pdf->SetXY(170,$Y);
$title=iconv('UTF-8','windows-874',$var17);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//แบ่งชำระเป็น
$pdf->SetXY(52,$Y);
$title=iconv('UTF-8','windows-874',$var18);
$pdf->MultiCell(70,4,$title,0,'L',0);
//ชำทุกวันที่ 
$pdf->SetXY(110,$Y);
$title=iconv('UTF-8','windows-874',$var19);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 6;
//ส่วนที่ 2 ข้อ 3
$pdf->SetXY(33,$Y);
$title=iconv('UTF-8','windows-874',$var26);
$pdf->MultiCell(155,4,$title,'B','L',0);

$Y += 20;
//ตารางค่างวดช่องที่ 1
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var20_0);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(100,$Y);
$title=iconv('UTF-8','windows-874',$var20_1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874',$var20_2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(170,$Y);
$title=iconv('UTF-8','windows-874',$var20_3);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//ตารางค่างวดช่องที่ 2
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var21_0);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(100,$Y);
$title=iconv('UTF-8','windows-874',$var21_1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874',$var21_2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(170,$Y);
$title=iconv('UTF-8','windows-874',$var21_3);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//ตารางค่างวดช่องที่ 3
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var22_0);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(100,$Y);
$title=iconv('UTF-8','windows-874',$var22_1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874',$var22_2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(170,$Y);
$title=iconv('UTF-8','windows-874',$var22_3);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//ตารางค่างวดช่องที่ 4
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var23_0);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(100,$Y);
$title=iconv('UTF-8','windows-874',$var23_1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874',$var23_2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(170,$Y);
$title=iconv('UTF-8','windows-874',$var23_3);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//ตารางค่างวดช่องที่ 5
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var24_0);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(100,$Y);
$title=iconv('UTF-8','windows-874',$var24_1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874',$var24_2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(170,$Y);
$title=iconv('UTF-8','windows-874',$var24_3);
$pdf->MultiCell(70,4,$title,0,'L',0);
	
$Y += 35;	
//ลงชื่อผู้เช่าซื้อ
$pdf->SetXY(70,$Y);
$title=iconv('UTF-8','windows-874',$var25);
$pdf->MultiCell(80,4,$title,0,'C',0);
	
$pdf->Output();	

?>



