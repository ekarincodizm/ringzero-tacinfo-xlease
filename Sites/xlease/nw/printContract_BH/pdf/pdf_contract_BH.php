<?php
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}
include("../../../config/config.php");

/*-============================================================================-
								   สัญญาเช่าซื้อ	
								ดึงข้อมูลจากตาราง
-============================================================================-*/
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime();
$contractID=$_GET["ID"]; // เลขที่สัญญา

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) พิมพ์สัญญา', '$add_date')");
	//ACTIONLOG---

// หาข้อมูลสัญญา
$qry_contractDetail = pg_query("select * from \"thcap_contract\" where \"contractID\" = '$contractID' ");
while($res_contractDetail = pg_fetch_array($qry_contractDetail))
{
	$av_datestart = $res_contractDetail["conDate"]; // วันที่ทำสัญญา
	$se_total = $res_contractDetail["conTerm"]; // จำนวนงวด
	$fdate = $res_contractDetail["conRepeatDueDay"]; // จ่ายทุกๆวันที่
}
	
// ค้นหารหัสผู้กู้หลัก
$qry_cusMain = pg_query("select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$contractID' and \"CusState\" = '0' ");
$f_idno = pg_fetch_result($qry_cusMain,0);

// ข้อมูลผู้กู้หลัก
$qry_cusDetail = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$f_idno' ");
while($res_cusDetail = pg_fetch_array($qry_cusDetail))
{
	$cus_name = $res_cusDetail["full_name"]; // ชื่อเต็มลูกค้า
	$cusType = $res_cusDetail["type"]; // รหัสประเภทลูกค้า 1:ลูกค้าธรรมดา 2:ลูกค้านิติบุคคล
	
	if($cusType == '1')
	{
		$qry_Fn = pg_query("select * from \"Fn\" where \"CusID\" = '$f_idno' ");
		while($res_Fn = pg_fetch_array($qry_Fn))
		{
			$cus_nation = $res_Fn["N_SAN"]; // สัญชาติ
			$cus_type = $res_Fn["N_CARD"]; // ประเภทบัตรประจำตัว
			$cus_nid = ($res_Fn["N_IDCARD"]); // เลขที่บัตร
		}
		
		if($cus_type=="บัตรประชาชน" || $cus_type=="ประชาชน")
		{
			$cus_no_txt = " ผู้ถือบัตรประชาชนเลขที่ $cus_nid";
		}
		else
		{
			$cus_no_txt = " $cus_type เลขที่ $cus_nid";
		}
	}
	elseif($cusType == '2')
	{
		$qry_corp = pg_query("select * from \"th_corp\" where \"corpID\" = '$f_idno' ");
		while($res_corp = pg_fetch_array($qry_corp))
		{
			$CountryCode = $res_corp["CountryCode"]; // รหัสสัญชาติ
			$cus_nid=trim($res_corp["corp_regis"]); // เลขที่บัตร
			
			// หาชื่อสัญชาติ
			$qry_Country = pg_query("select \"CountryName_THAI\" from \"Country_Code\" where \"CountryCode\" = '$CountryCode' ");
			$cus_nation = pg_fetch_result($qry_Country,0); // สัญชาติ
		}
		
		$cus_no_txt = " เลขทะเบียนนิติบุคคล $cus_nid";
	}
}

//กรณีแสดงสัญชาติ
if($cus_nation==""){
	$cus_nation_txt="";
}else{
	$cus_nation_txt=" สัญชาติ $cus_nation";
}

// หาที่อยู่ลูกค้า
$qry_addr = pg_query("select \"thcap_address\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusID\" = '$f_idno' and \"CusState\" = '0' ");
$cus_addr = pg_fetch_result($qry_addr,0);

//ค้นหาข้อมูลตัวรถ
$qry_carDetail = pg_query("select b.*,c.\"car_color\" as color from \"thcap_contract_asset\" a, \"thcap_asset_biz_detail_10\" b
							left join thcap_asset_biz_detail_10_color c on b.\"car_color\"=c.auto_id
							where a.\"assetDetailID\" = b.\"assetDetailID\"
							and a.\"contractID\" = '$contractID'");
while($res_carDetail = pg_fetch_array($qry_carDetail))
{
	$assetDetailID = $res_carDetail["assetDetailID"]; // รหัสสินทรัพย์
	$car_regis = $res_carDetail["regiser_no"]; // ทะเบียนรถ
	$car_year = $res_carDetail["year_regis"]; // ปีที่จดทะเบียน
	$car_number = $res_carDetail["motorcycle_no"]; // เลขที่ตัวถัง
	$car_color = $res_carDetail["color"]; // สีรถ
	$car_mi = $res_carDetail["car_mileage"]; // เลขไมล์
	$fp_fc_category = $res_carDetail["car_type"]; // ชนิดรถ
}

$qry_assetDetail = pg_query("select * from \"thcap_asset_biz_detail\" where \"assetDetailID\" = '$assetDetailID' ");
while($res_assetDetail = pg_fetch_array($qry_assetDetail))
{
	$asset_brand = $res_assetDetail["brand"]; // รหัสยี่ห้อ
	$asset_model = $res_assetDetail["model"]; // รหัสรุ่น
	$car_engine = $res_assetDetail["productCode"]; // เลขเครื่องยนต์
}
	
// หายี่ห้อ
$qry_assetBrand = pg_query("select \"brand_name\" from \"thcap_asset_biz_brand\" where \"brandID\" = '$asset_brand' ");
$fp_band = pg_fetch_result($qry_assetBrand,0);

// หารุ่น
$qry_assetModel = pg_query("select \"model_name\" from \"thcap_asset_biz_model\" where \"modelID\" = '$asset_model' ");
$fp_model = pg_fetch_result($qry_assetModel,0);


IF($car_brand == "''" OR $car_brand == " " OR $car_brand == "-" OR $car_brand == "-" OR $car_brand == ""){ $car_brand = "--ไม่ระบุ--"; }
IF($car_year == "''" OR $car_year == " " OR $car_year == "-" OR $car_year == "-" OR $car_year == ""){ $car_year = "--ไม่ระบุ--"; }

IF($car_color == "''" OR $car_color == " " OR $car_color == "-" OR $car_color == "-" OR $car_color == ""){ $car_color = "--ไม่ระบุ--"; }
IF($car_regis == "''" OR $car_regis == " " OR $car_regis == "-" OR $car_regis == "-" OR $car_regis == ""){ $car_regis = "--ไม่ระบุ--"; }
$car_province = "กรุงเทพมหานคร"; // todo ปัจจุบัน fix กทม. ไปก่อนเนื่องจากไม่มีข้อมูล และตอนนี้มีเฉพาะ กทม.
IF($car_province == "''" OR $car_province == " " OR $car_province == "-" OR $car_province == "-" OR $car_province == ""){ $car_province = "--ไม่ระบุ--"; }

IF($fp_fc_category == "''" OR $fp_fc_category == " " OR $fp_fc_category == "-" OR $fp_fc_category == "-" OR $fp_fc_category == ""){ $fp_fc_category = "--ไม่ระบุ--"; }
$newcar = "รถใหม่"; // todo ปัจจุบัน fix ไว้ว่าเป็นรถใหม่ไปก่อน
IF($newcar == "''" OR $newcar == " " OR $newcar == "-" OR $newcar == "-" OR $newcar == ""){ $newcar = "--ไม่ระบุ--"; }
IF($car_number == "''" OR $car_number == " " OR $car_number == "-" OR $car_number == "-" OR $car_number == ""){ $car_number = "--ไม่ระบุ--"; }
IF($car_engine == "''" OR $car_engine == " " OR $car_engine == "-" OR $car_engine == "-" OR $car_engine == ""){ $car_engine = "--ไม่ระบุ--"; }

IF($car_mi == "''" OR $car_mi == " " OR $car_mi == "-" OR $car_mi == "-" OR $car_mi == ""){ $car_mi = "--ไม่ระบุ--"; }


// หาราคาเช่าซื้อไม่รวมเงินดาวน์ ไม่รวม VAT
$qry_sumCost = pg_query("select sum(\"debtNet\") as \"sumDebtNet\" from \"thcap_temp_otherpay_debt\"
					where \"contractID\" = '$contractID' and \"typePayID\" = account.\"thcap_mg_getMinPayType\"(\"contractID\")");
$cost = pg_fetch_result($qry_sumCost,0);

// หาวันที่ครบกำหนดชำระงวดแรก
$qry_oneDue = pg_query("select \"debtDueDate\" from \"thcap_temp_otherpay_debt\"
						where \"contractID\" = '$contractID'
						and \"typePayID\" = account.\"thcap_mg_getMinPayType\"(\"contractID\") and \"typePayRefValue\" = '1'");
$fdate_thaidate = pg_fetch_result($qry_oneDue,0);

//หาที่อยู่ในการติดต่อและส่งเอกสาร
$qry_oneDue = pg_query("select \"sentaddress\" from \"thcap_lease_contract\" where \"contractID\" = '$contractID' ");
$con_addr = pg_fetch_result($qry_oneDue,0);

/*-============================================================================-
								   สัญญาเช่าซื้อ	
								กำหนดรายละเอียด
-============================================================================-*/
	$var1 = $contractID;	//เลขที่สัญญา
	$var2 = $av_datestart;       //วันที่ทำสัญญา
	$var3 = $corpType.$cus_name.$cus_nation_txt.$cus_no_txt."\n".'ที่อยู่เลขที่ '.$cus_addr;   //ชื่อผู้ทำสัญญา
	$address = "ที่อยู่ในการติดต่อและส่งเอกสาร\n".'เลขที่ '.$con_addr;   //ที่อยู่ในการติดต่อและส่งเอกสาร
	$var4 = "จักรยานยนต์";    //รถ ปัจจุบัน fix จักรยานยนต์
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
	
	$qrydate=pg_query("select get_date_thai_format('$fdate_thaidate')");
	list($fdate_thaidate)=pg_fetch_array($qrydate);
	
	$var17 = $fdate_thaidate;  //เริ่มชำระงวดแรก
	$var18 = $se_total;  //แบ่งชำระเป็น
	$var19 = $fdate;  // ชำระทุกวันที่
	$var26 = ''; //ข้อ 3
	
	// ข้อมูลการผ่อนชำระ
	$rowData = 0; // อยู่บรรทัดที่เท่าไหร่ในกระดาษ
	$qry_contractData = pg_query("select * from \"thcap_temp_otherpay_debt\"
								where \"contractID\" = '$contractID'
								and \"typePayID\" = account.\"thcap_mg_getMinPayType\"(\"contractID\")
								order by \"typePayRefValue\"::integer");
	$row_contractData = pg_num_rows($qry_contractData); // จำนวนงวดทั้งหมด
	while($res_p = pg_fetch_array($qry_contractData))
	{
		$typePayRefValue = $res_p["typePayRefValue"]; // งวดที่
		$debtNet = $res_p["debtNet"]; // จำนวนเงินก่อน vat
		$debtVat = $res_p["debtVat"]; // ภาษีมูลค่าเพิ่ม
		$typePayAmt = $res_p["typePayAmt"]; // รวมชำระสุทธิงวดละ
		
		if($typePayRefValue == '1')
		{
			$typePayRefValue_Old = $typePayRefValue; // เริ่มงวดที่
			$debtNet_Old = $debtNet; // จำนวนเงินของงวดก่อนหน้านี้
			$typePayRefValue_OldOld = $typePayRefValue; // เริ่มงวดที่
		}
		else
		{
			if($debtNet_Old != $debtNet)
			{ // ถ้าจำนวนเงินไม่เท่ากัน
				$rowData++; // บรรทัดต่อไป
				if($rowData == 1)
				{
					//งวดที่ block 1
					if($typePayRefValue_OldOld == $typePayRefValue_Old)
					{
						$var20_0 = $typePayRefValue_OldOld;
					}
					else
					{
						$var20_0 = $typePayRefValue_OldOld."-".$typePayRefValue_Old;
					}
					$var20_1 = number_format($debtNet_Old,2);  	//ค่างวด block 1
					$var20_2 = number_format($debtVat_Old,2);		//ภาษีมูลค่าเพิ่ม block 1
					$var20_3 = number_format($typePayAmt_Old,2);  	//รวมชำระสุทธิงวดละ block 1
				}
				elseif($rowData == 2)
				{
					//งวดที่ block 2
					if($typePayRefValue_OldOld == $typePayRefValue_Old)
					{
						$var21_0 = $typePayRefValue_OldOld;
					}
					else
					{
						$var21_0 = $typePayRefValue_OldOld."-".$typePayRefValue_Old;
					}
					$var21_1 = number_format($debtNet_Old,2);  	//ค่างวด block 2
					$var21_2 = number_format($debtVat_Old,2);		//ภาษีมูลค่าเพิ่ม block 2
					$var21_3 = number_format($typePayAmt_Old,2);  	//รวมชำระสุทธิงวดละ block 2
				}
				elseif($rowData == 3)
				{
					//งวดที่ block 3
					if($typePayRefValue_OldOld == $typePayRefValue_Old)
					{
						$var22_0 = $typePayRefValue_OldOld;
					}
					else
					{
						$var22_0 = $typePayRefValue_OldOld."-".$typePayRefValue_Old;
					}
					$var22_1 = number_format($debtNet_Old,2);  	//ค่างวด block 3
					$var22_2 = number_format($debtVat_Old,2);		//ภาษีมูลค่าเพิ่ม block 3
					$var22_3 = number_format($typePayAmt_Old,2);  	//รวมชำระสุทธิงวดละ block 3
				}
				elseif($rowData == 4)
				{
					//งวดที่ block 4
					if($typePayRefValue_OldOld == $typePayRefValue_Old)
					{
						$var23_0 = $typePayRefValue_OldOld;
					}
					else
					{
						$var23_0 = $typePayRefValue_OldOld."-".$typePayRefValue_Old;
					}
					$var23_1 = number_format($debtNet_Old,2);  	//ค่างวด block 4
					$var23_2 = number_format($debtVat_Old,2);		//ภาษีมูลค่าเพิ่ม block 4
					$var23_3 = number_format($typePayAmt_Old,2);  	//รวมชำระสุทธิงวดละ block 4
				}
				elseif($rowData == 5)
				{
					//งวดที่ block 5
					if($typePayRefValue_OldOld == $typePayRefValue_Old)
					{
						$var24_0 = $typePayRefValue_OldOld;
					}
					else
					{
						$var24_0 = $typePayRefValue_OldOld."-".$typePayRefValue_Old;
					}
					$var24_1 = number_format($debtNet_Old,2);  	//ค่างวด block 5
					$var24_2 = number_format($debtVat_Old,2);		//ภาษีมูลค่าเพิ่ม block 5
					$var24_3 = number_format($typePayAmt_Old,2);  	//รวมชำระสุทธิงวดละ block 5
				}
				
				$typePayRefValue_OldOld = $typePayRefValue; // เริ่มงวดที่
			}
		}
		
		$debtNet_Old = $debtNet; // จำนวนเงินของงวดก่อนหน้านี้
		$debtVat_Old = $debtVat; // ภาษีมูลค่าเพิ่ม ก่อนหน้านี้
		$typePayAmt_Old = $typePayAmt; // ก่อนหน้านี้
		$typePayRefValue_Old = $typePayRefValue; // เลขงวดก่อนหน้านี้
	}
	
	if($var20_0 == "" && $row_contractData > 0)
	{ // ถ้าเท่ากันทุกงวด
		$var20_0 = '01-'.$se_total;  	//งวดที่ block 1
		$var20_1 = number_format($debtNet,2);  	//ค่างวด block 1
		$var20_2 = number_format($debtVat,2);		//ภาษีมูลค่าเพิ่ม block 1
		$var20_3 = number_format($typePayAmt,2);  	//รวมชำระสุทธิงวดละ block 1
		
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
	}
	elseif($var20_0 != "" && $row_contractData > 0)
	{
		if($var21_0 == "")
		{
			//งวดที่ block 2
			if($typePayRefValue_OldOld == $typePayRefValue_Old)
			{
				$var21_0 = $typePayRefValue_OldOld;
			}
			else
			{
				$var21_0 = $typePayRefValue_OldOld."-".$typePayRefValue_Old;
			}
			$var21_1 = number_format($debtNet_Old,2);  	//ค่างวด block 2
			$var21_2 = number_format($debtVat_Old,2);		//ภาษีมูลค่าเพิ่ม block 2
			$var21_3 = number_format($typePayAmt_Old,2);  	//รวมชำระสุทธิงวดละ block 2
		}
		elseif($var22_0 == "")
		{
			//งวดที่ block 3
			if($typePayRefValue_OldOld == $typePayRefValue_Old)
			{
				$var22_0 = $typePayRefValue_OldOld;
			}
			else
			{
				$var22_0 = $typePayRefValue_OldOld."-".$typePayRefValue_Old;
			}
			$var22_1 = number_format($debtNet_Old,2);  	//ค่างวด block 3
			$var22_2 = number_format($debtVat_Old,2);		//ภาษีมูลค่าเพิ่ม block 3
			$var22_3 = number_format($typePayAmt_Old,2);  	//รวมชำระสุทธิงวดละ block 3
		}
		elseif($var23_0 == "")
		{
			//งวดที่ block 4
			if($typePayRefValue_OldOld == $typePayRefValue_Old)
			{
				$var23_0 = $typePayRefValue_OldOld;
			}
			else
			{
				$var23_0 = $typePayRefValue_OldOld."-".$typePayRefValue_Old;
			}
			$var23_1 = number_format($debtNet_Old,2);  	//ค่างวด block 4
			$var23_2 = number_format($debtVat_Old,2);		//ภาษีมูลค่าเพิ่ม block 4
			$var23_3 = number_format($typePayAmt_Old,2);  	//รวมชำระสุทธิงวดละ block 4
		}
		elseif($var24_0 == "")
		{
			//งวดที่ block 5
			if($typePayRefValue_OldOld == $typePayRefValue_Old)
			{
				$var24_0 = $typePayRefValue_OldOld;
			}
			else
			{
				$var24_0 = $typePayRefValue_OldOld."-".$typePayRefValue_Old;
			}
			$var24_1 = number_format($debtNet_Old,2);  	//ค่างวด block 5
			$var24_2 = number_format($debtVat_Old,2);		//ภาษีมูลค่าเพิ่ม block 5
			$var24_3 = number_format($typePayAmt_Old,2);  	//รวมชำระสุทธิงวดละ block 5
		}
	}
	
	//$var25 = $cus_name;   //ชื่อผู้ทำสัญญา ( ลายเซ็น)
	
	
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

$page = $pdf->PageNo();	

$Y = 12.8;	
//เลขที่สัญญา	
$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(48,$Y);
$title=iconv('UTF-8','windows-874',$var1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 19.5;	
//วันที่ทำสัญญา	
$pdf->SetFont('AngsanaNew','B',14);
$qrydate=pg_query("select get_date_thai_format('$var2')");
list($datestart)=pg_fetch_array($qrydate);

$pdf->SetXY(165,$Y);
$title=iconv('UTF-8','windows-874',$datestart);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 12;	
//ผู้เช่าซื้อ	
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$var3);
$pdf->MultiCell(160,5,$title,0,'C',0);

$Y += 15;
//ที่อยู่ในการติดต่อและส่งเอกสาร	
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$address);
$pdf->MultiCell(160,5,$title,0,'C',0);

$Y = 100.5;	
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

$Y += 8;
//ระยะไมล์
$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var14);
$pdf->MultiCell(70,4,$title,0,'L',0);

//สีตัวรถ
$pdf->SetXY(160,$Y);
$title=iconv('UTF-8','windows-874',$var15);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 18;
//ราคาไม่รวม VAT
$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var16);
$pdf->MultiCell(70,4,$title,0,'L',0);

//วันที่เริ่มชำระ
$pdf->SetXY(170,$Y);
$title=iconv('UTF-8','windows-874',$var17);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 8;
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



