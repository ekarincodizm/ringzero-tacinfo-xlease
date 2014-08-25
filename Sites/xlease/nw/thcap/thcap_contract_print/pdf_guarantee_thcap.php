<?php
session_start();
$id_user = $_SESSION["av_iduser"];

include("../../../config/config.php");
require('../../../thaipdfclass.php');

/*-============================================================================-
								  สัญญาค้ำประกัน
								ดึงข้อมูลจากตาราง
-============================================================================-*/
$contractID=$_GET["contractID"];
//หาชื่อผู้กู้หลัก
$qry_name_main=pg_query("	SELECT a.*,b.\"N_CARD\",b.\"N_SAN\",b.\"N_AGE\"
						FROM \"vthcap_ContactCus_detail\" a
						LEFT JOIN \"Fn\" b ON a.\"CusID\" = b.\"CusID\"
						WHERE \"contractID\" = '$contractID' 
						AND \"CusState\" = '0'
				   ");
$res_name_main = pg_fetch_array($qry_name_main);
$fullnamemain = trim($res_name_main["thcap_fullname"]);
$CusIDmain = trim($res_name_main["CusID"]);
	IF($res_name_main["type"] == '1'){ //บุคคลธรรมดา
		if($res_name_main["N_IDCARD"] != ""){
			$main_profile = ' อายุ '.$res_name_main["N_AGE"].' สัญชาติ '.$res_name_main["N_SAN"].' ถือบัตร '.$res_name_main["N_CARD"].' เลขบัตร '.$res_name_main["N_IDCARD"];
		}else{
			$main_profile = ' อายุ '.$res_name_main["N_AGE"].' สัญชาติ '.$res_name_main["N_SAN"].' ถือบัตร '.$res_name_main["N_CARD"].' เลขบัตร '.$res_name_main["N_CARDREF"];
		}	
	}else{//นิติบุคคล
		
		if($res_name_main["N_IDCARD"] != ""){
			$main_profile = 'เลขทะเบียนนิติบุคคล '.$res_name_main["N_IDCARD"];
		}else{
			$main_profile = 'เลขทะเบียนนิติบุคคล '.$res_name_main["N_CARDREF"];
		}	
	}


//รายละเอียดสัญญา//
$qry_con=pg_query("	
						SELECT a.*,b.\"conDate\"
						FROM \"vthcap_ContactCus_detail\" a	
						LEFT JOIN \"thcap_contract\" b ON a.\"contractID\" = b.\"contractID\"
						WHERE a.\"contractID\" = '$contractID'
					");
$res_con=pg_fetch_array($qry_con);

//หาผู้ค้ำ
$qry_name_sec=pg_query("	
						SELECT *
						FROM \"vthcap_ContactCus_detail\" a	
						LEFT JOIN \"Fa1\" b ON a.\"CusID\" = b.\"CusID\"
						LEFT JOIN \"Fn\" c ON a.\"CusID\" = c.\"CusID\"
						WHERE a.\"contractID\" = '$contractID' 
						AND a.\"CusState\" = '2'							
						");	
							

/*-============================================================================-
								  สัญญาค้ำประกัน
								กำหนดรายละเอียด
-============================================================================-*/
	$var1 = $contractID;	//สัญญาเลขที่
	$var2 = date("d/m/Y"); //วันที่ทำสัญญา
	$var8 = $res_con["conDate"]; //วันที่ในสัญญา
	$var9 = $fullnamemain; //ผู้เช่าซื้อ
	
	
	
/*-============================================================================-*/	
	
	
	
// ------------------- PDF -------------------//


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
$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(48,$Y);
$title=iconv('UTF-8','windows-874',$var1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 20;	
//วันที่ทำสัญญา	
$pdf->SetXY(155,$Y);
$title=iconv('UTF-8','windows-874',$var2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y = 41;
$z = 228;	
//ผู้ค้ำ	
while($res_cc=pg_fetch_array($qry_name_sec)){

	IF($res_cc["type"] == '1'){ //บุคคลธรรมดา
		//หาสัญชาติ
		if(trim($res_cc["N_SAN"])==""){
			$s_N_SAN="";
		}else{
			 $s_N_SAN=" สัญชาติ ".trim($res_cc["N_SAN"]);
		}
		//หาอายุ
		if(trim($res_cc["N_AGE"])==""){
			$s_N_AGE="";
		}else{
			 $s_N_AGE=" อายุ ".trim($res_cc["N_AGE"]." ปี");
		}
		//บัตรประจำตัว
		if(trim($res_cc["N_CARD"])==""){
			$s_N_CARD="";
		}else{
			 $s_N_CARD=" บัตรประจำตัว ".trim($res_cc["N_CARD"]);
		}
		//หาเลขบัตร
		$ncard=trim($res_cc["N_CARD"]);
		$av_cardid = 'เลขที่ ';
		if($ncard=="บัตรประชาชน" || $ncard=="ประชาชน"){
			$av_cardid +=$res_cc["N_IDCARD"];
		}else{
			$av_cardid +=$res_cc["N_CARDREF"];
		}
		//หาวันที่ออกบัตร
		if(trim($res_cc["otdate"])==""){
			$s_otdate="";
		}else{
			 $s_otdate=" วันที่ออกบัตร ".trim($res_cc["otdate"]);
		}
		
		
		//ข้อมูลประจำตัวผู้ค้ำ
		$cusguarantee_profile = $s_N_SAN.$s_N_AGE.$s_N_CARD." ".$av_cardid.$s_otdate;
	}else{ //นิติบุคคล

		//หาเลขบัตร
		$ncard=trim($res_cc["N_CARD"]);
		$av_cardid = 'เลขทะเบียนนิติบุคคล ';
		if($res_cc["N_CARD"] == ""){
			$av_cardid +=$res_cc["N_IDCARD"];
		}else{
			$av_cardid +=$res_cc["N_CARDREF"];
		}
	
		
		//ข้อมูลประจำตัวผู้ค้ำ
		$cusguarantee_profile = $av_cardid;
	}
	
	//ที่อยู่ผู้ค้ำประกัน
	$cusguarantee_addr = $res_cc["thcap_address"];
	
	//ชื่อผู้ค้ำ
		$av_cname = $res_cc["thcap_fullname"];
		
	
	$pdf->SetXY(44,$Y);
	$title=iconv('UTF-8','windows-874',$av_cname."\n".$cusguarantee_profile."\nที่อยู่ ".$cusguarantee_addr);
	$pdf->MultiCell(150,5,$title,0,'L',0);

	$Y += 19;
	
	//ลงชื่อผู้ค้ำคนที่ 

	$pdf->SetXY(119,$z);
	$title=iconv('UTF-8','windows-874',$av_cname);
	$pdf->MultiCell(60,5,$title,0,'C',0);
	
	$z += 15;
}	


$Y = 147.5;	
//วันที่ในสัญญา
$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var8);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 6;	
//ผู้เช่าซื้อ
$pdf->SetXY(55,$Y);
$title=iconv('UTF-8','windows-874',$var9." ".$main_profile);
$pdf->MultiCell(150,4,$title,0,'L',0);


		
$pdf->Output();	

?>



