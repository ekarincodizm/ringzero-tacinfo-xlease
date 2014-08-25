<?php
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}

include("../../../config/config.php");
/*-============================================================================-
								  สัญญาค้ำประกัน
								ดึงข้อมูลจากตาราง
-============================================================================-*/
$f_idno=$_GET["ID"];

// ค้นหารหัสผู้กู้หลัก
$qry_cusMain = pg_query("select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$f_idno' and \"CusState\" = '0' ");
$cusMainID = pg_fetch_result($qry_cusMain,0);

// ค้นหาข้อมูลผู้กู้หลัก
$qry_cusNane = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$cusMainID' ");
$str_nameidno = pg_fetch_result($qry_cusNane,0);

//รายละเอียดสัญญา//
$qry_print=pg_query("select * from \"thcap_contract\" where \"contractID\" = '$f_idno' ");
$res_idno=pg_fetch_array($qry_print);

//หาเลขทะเบียน
$qry_carDetail = pg_query("select b.* from \"thcap_contract_asset\" a, \"thcap_asset_biz_detail_10\" b
							where a.\"assetDetailID\" = b.\"assetDetailID\"
							and a.\"contractID\" = '$f_idno'");
while($res_carDetail = pg_fetch_array($qry_carDetail))
{
	if($res_carDetail["regiser_no"] != "")
	{
		$fp_regis= 'เลขทะเบียนรถ '.$res_carDetail["regiser_no"];
	}
	$fp_reg_by='กรุงเทพมหานคร'; // todo ปัจจุบัน fix กทม. ไปก่อนเนื่องจากไม่มีข้อมูล และตอนนี้มีเฉพาะ กทม. (อ้างอิงจากสัญญาผู้เช่าซื้อ)
}


/*-============================================================================-
								  สัญญาค้ำประกัน
								กำหนดรายละเอียด
-============================================================================-*/
	$var1 = $f_idno;	//สัญญาเลขที่
	$var2 = $res_idno["conDate"]; //วันที่ทำสัญญา

	$var9 = $str_nameidno; //ผู้เช่าซื้อ
	
	
	
/*-============================================================================-*/	
	
	
	
// ------------------- PDF -------------------//
require('../../../thaipdfclass.php');

class PDF extends ThaiPDF
{

}


$pdf=new PDF('P','mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true,0);

$Y = 13;	
//เลขที่สัญญา	
$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(48,$Y);
$title=iconv('UTF-8','windows-874',$var1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$Y += 19;
	
//วันที่ทำสัญญา	
$pdf->SetFont('AngsanaNew','B',14);
$qrydate=pg_query("select get_date_thai_format('$var2')");
list($datestart)=pg_fetch_array($qrydate);

$pdf->SetXY(155,$Y);
$title=iconv('UTF-8','windows-874',$datestart);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 10;
$z = 228;	
//ผู้ค้ำ	
$qry_loop_cus = pg_query("select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$f_idno' AND \"CusState\" = '2' ");
while($res_loop_cus=pg_fetch_array($qry_loop_cus))
{	
	$CusID = $res_loop_cus["CusID"]; // รหัสผู้ค้ำ
	
	// ค้นหาข้อมูลผู้ค้ำ
	$qry_cusNane = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$CusID' ");
	while($res_cus = pg_fetch_array($qry_cusNane))
	{
		$av_cname = $res_cus["full_name"]; // ชื่อเต็ม
		$cusType = $res_cus["type"]; // ประเภทลูกค้า
		$IDCARD = $res_cus["IDCARD"]; // เลขที่บัตรประจำตัว
		
		if($cusType == '1')
		{
			$qry_print_dat = pg_query("select * from \"Fn\" where \"CusID\" = '$CusID' ");
			$res_cc=pg_fetch_array($qry_print_dat);
			
			//หาสัญชาติ
			if(trim($res_cc["N_SAN"])==""){
				$s_N_SAN="";
			}else{
				 $s_N_SAN=" สัญชาติ ".trim($res_cc["N_SAN"]);
			}
			
			//บัตรประจำตัว
			if(trim($res_cc["N_CARD"])==""){
				$s_N_CARD="";
			}else{
				 $s_N_CARD=" ผู้ถือ".trim($res_cc["N_CARD"]);
			}
			
			$av_cardid = "เลขที่ ".$IDCARD;
			
			//ข้อมูลประจำตัวผู้ค้ำ
			$cusguarantee_profile = $s_N_SAN.$s_N_CARD.$av_cardid;
		}
		elseif($cusType == '2')
		{
			$qry_corp = pg_query("select * from \"th_corp\" where \"corpID\" = '$CusID' ");
			while($res_corp = pg_fetch_array($qry_corp))
			{
				$CountryCode = $res_corp["CountryCode"]; // รหัสสัญชาติ
				$cus_nid=trim($res_corp["corp_regis"]); // เลขที่บัตร
				
				// หาชื่อสัญชาติ
				$qry_Country = pg_query("select \"CountryName_THAI\" from \"Country_Code\" where \"CountryCode\" = '$CountryCode' ");
				$CountryName_THAI = pg_fetch_result($qry_Country,0); // สัญชาติ
			}
			
			//หาสัญชาติ
			if($CountryName_THAI==""){
				$s_N_SAN="";
			}else{
				 $s_N_SAN=" สัญชาติ  $CountryName_THAI";
			}
			
			//หาเลขทะเบียนนิติบุคคล
			$cusguarantee_profile = "$s_N_SAN เลขทะเบียนนิติบุคคล ".$IDCARD;
		}
	}
	
	// หาที่อยู่ลูกค้า
	$qry_addr = pg_query("select \"thcap_address\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$f_idno' and \"CusID\" = '$CusID' and \"CusState\" = '2' ");
	$cusguarantee_addr = pg_fetch_result($qry_addr,0);
	
	$pdf->SetXY(44,$Y);
	$title=iconv('UTF-8','windows-874',$av_cname.$cusguarantee_profile."\n".'ที่อยู่เลขที่ '.$cusguarantee_addr);
	$pdf->MultiCell(150,5,$title,0,'L',0);

	$Y += 19;
	$z += 15;
}

$Y = 147.5;	
//วันที่ในสัญญา
$pdf->SetXY(93,$Y);
$title=iconv('UTF-8','windows-874',$datestart);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 5;	
// ผู้เช่าซื้อ
$pdf->SetXY(55,$Y);
$title=iconv('UTF-8','windows-874',$var9." ".$fp_regis." ".$fp_reg_by);
$pdf->MultiCell(150,4,$title,0,'L',0);


		
$pdf->Output();	

?>



