<?php
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");
/*-============================================================================-
								  สัญญาค้ำประกัน
								ดึงข้อมูลจากตาราง
-============================================================================-*/
$f_idno=$_GET["ID"];
//หาชื่อผู้กู้หลัก
$qry_name=pg_query("SELECT A.\"IDNO\",A.\"CusID\",B.* 
					FROM \"Fp\" A 
					LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\"=B.\"CusID\"
					WHERE A.\"IDNO\"='$f_idno'
				   ");
$res_name=pg_fetch_array($qry_name);
$str_nameidno=trim($res_name["A_FIRNAME"])." ".trim($res_name["A_NAME"])." ".trim($res_name["A_SIRNAME"]);


//รายละเอียดสัญญา//
$qry_print=pg_query("SELECT to_char(A.\"P_STDATE\",'dd/mm/yyyy') AS datest,A.*,C.*,D.*
					FROM \"Fp\" A  LEFT OUTER JOIN \"VCarregistemp\" C ON A.\"IDNO\" = C. \"IDNO\"
					LEFT OUTER JOIN \"Fa1\" D ON A.\"CusID\" = D. \"CusID\"
					where A.\"IDNO\" = '$f_idno'
					");
$res_idno=pg_fetch_array($qry_print);

//หาเลขทะเบียน
if($res_idno["C_REGIS"] != ""){
	$fp_regis= 'เลขทะเบียนรถ '.$res_idno["C_REGIS"];
}

//หาจังหวัดทะเบียน
if($res_idno["C_REGIS_BY"] != ""){
	if(trim($res_idno["C_REGIS_BY"]) == "กรุงเทพ" OR trim($res_idno["C_REGIS_BY"]) == "กรุงเทพฯ" OR trim($res_idno["C_REGIS_BY"]) == "กทม" OR trim($res_idno["C_REGIS_BY"]) == "กทม." OR trim($res_idno["C_REGIS_BY"]) == "กรุงเทพมหานคร"){
		$fp_reg_by= ' กรุงเทพมหานคร';
	}else{
		$fp_reg_by= ' จังหวัด'.trim($res_idno["C_REGIS_BY"]);
	}	
}

							

/*-============================================================================-
								  สัญญาค้ำประกัน
								กำหนดรายละเอียด
-============================================================================-*/
	$var1 = $f_idno;	//สัญญาเลขที่
	$var2 = $res_idno["datest"]; //วันที่ทำสัญญา

	$var8 = $res_idno["datest"]; //วันที่ในสัญญา
	$var9 = $str_nameidno; //ผู้เช่าซื้อ
		
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
$pdf->SetAutoPageBreak(true,0);

$Y = 13;	
//เลขที่สัญญา	
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(48,$Y);
$title=iconv('UTF-8','windows-874',$var1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 19;	
//วันที่ทำสัญญา	
$pdf->SetXY(155,$Y);
$title=iconv('UTF-8','windows-874',$var2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 10;
$z = 228;	

//หาข้อมูลผู้ค้ำ	
$nub=0;
$qry_loop_cus = pg_query("select b.\"A_FIRNAME\",b.\"A_NAME\",b.\"A_SIRNAME\",b.\"A_PAIR\",c.\"N_OT_DATE\" AS otdate,c.*,
concat(COALESCE(concat(' บ้านเลขที่ ', btrim(B.\"A_NO\")), ''), '', COALESCE(
CASE
    WHEN trim(a.\"A_SUBNO\") IS NULL OR trim(a.\"A_SUBNO\") = '-' OR trim(a.\"A_SUBNO\") = '--' OR trim(a.\"A_SUBNO\") = '  ' OR replace(trim(a.\"A_SUBNO\"),' ','') = ''  THEN ''
    ELSE concat(' หมู่ ', btrim(a.\"A_SUBNO\"))
    END, ''), '', COALESCE(
CASE
	WHEN trim( a.\"A_BUILDING\") IS NULL OR trim( a.\"A_BUILDING\") = '-' OR trim( a.\"A_BUILDING\") = '--' OR trim( a.\"A_BUILDING\") = ' ' OR replace(trim(a.\"A_BUILDING\"),' ','') = '' THEN ''
	ELSE concat(' อาคาร', btrim(a.\"A_BUILDING\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( a.\"A_ROOM\") IS NULL OR trim( a.\"A_ROOM\") = '-' OR trim( a.\"A_ROOM\") = '--' OR trim( a.\"A_ROOM\") = ' ' OR replace(trim(a.\"A_ROOM\"),' ','') = '' THEN ''
	ELSE concat(' ห้อง', btrim(a.\"A_ROOM\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( a.\"A_FLOOR\") IS NULL OR trim( a.\"A_FLOOR\") = '-' OR trim( a.\"A_FLOOR\") = '--' OR trim( a.\"A_FLOOR\") = ' ' OR replace(trim(a.\"A_FLOOR\"),' ','') = '' THEN ''
	ELSE concat(' ชั้น', btrim(a.\"A_FLOOR\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( a.\"A_BAN\") IS NULL OR trim( a.\"A_BAN\") = '-' OR trim( a.\"A_BAN\") = '--' OR trim( a.\"A_BAN\") = ' ' OR replace(trim(a.\"A_BAN\"),' ','') = '' THEN ''
	ELSE concat(' หมู่บ้าน', btrim(a.\"A_BAN\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( a.\"A_SOI\") IS NULL OR trim( a.\"A_SOI\") = '-' OR trim(a.\"A_SOI\") = '--' OR trim( a.\"A_SOI\") = ' ' OR replace(trim(a.\"A_SOI\"),' ','') = '' THEN ''
	ELSE concat(' ซอย', btrim(a.\"A_SOI\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( a.\"A_RD\") IS NULL OR trim( a.\"A_RD\") = '-' OR trim( a.\"A_RD\") = '--' OR trim( a.\"A_RD\") = ' ' OR replace(trim(a.\"A_RD\"),' ','') = '' THEN ''
	ELSE concat(' ถนน', btrim(a.\"A_RD\"))
	END, ''), '', COALESCE(
CASE
	WHEN trim( a.\"A_TUM\") IS NULL OR trim( a.\"A_TUM\") = '-' OR trim( a.\"A_TUM\") = '--' OR trim( a.\"A_TUM\") = ' ' OR replace(trim(a.\"A_TUM\"),' ','') = '' THEN ''
	ELSE 
		CASE
		WHEN trim(a.\"A_PRO\") LIKE 'กรุงเทพ%' OR trim(a.\"A_PRO\") LIKE 'กรุงเทพฯ%' OR trim(a.\"A_PRO\") LIKE 'กรุงเทพมหานคร%'  OR trim(a.\"A_PRO\") LIKE 'กทม%' OR trim(a.\"A_PRO\") LIKE 'กทม.%'   THEN concat(' แขวง', btrim(a.\"A_TUM\"))
		ELSE concat(' ตำบล', btrim(a.\"A_TUM\"))
		END
	END, ''), '', COALESCE(
CASE
	WHEN trim( a.\"A_AUM\") IS NULL OR trim( a.\"A_AUM\") = '-' OR trim( a.\"A_AUM\") = '--' OR trim( a.\"A_AUM\") = ' ' OR replace(trim(a.\"A_AUM\"),' ','') = '' THEN ''
	ELSE 
		CASE
		WHEN trim(a.\"A_PRO\") LIKE 'กรุงเทพ%' OR trim(a.\"A_PRO\") LIKE 'กรุงเทพฯ%' OR trim(a.\"A_PRO\") LIKE 'กรุงเทพมหานคร%'  OR trim(a.\"A_PRO\") LIKE 'กทม%' OR trim(a.\"A_PRO\") LIKE 'กทม.%'  THEN concat(' เขต', btrim(a.\"A_AUM\"), ' ')
		ELSE concat(' อำเภอ', btrim(a.\"A_AUM\"), ' ')
		END
	END, ''), '', COALESCE(
CASE
	WHEN trim( a.\"A_PRO\") IS NULL OR trim( a.\"A_PRO\") = ' ' THEN ''
	ELSE 
		CASE
		WHEN trim(a.\"A_PRO\") LIKE 'กรุงเทพ%' OR trim(a.\"A_PRO\") LIKE 'กรุงเทพฯ%' OR trim(a.\"A_PRO\") LIKE 'กรุงเทพมหานคร%'  OR trim(a.\"A_PRO\") LIKE 'กทม%' OR trim(a.\"A_PRO\") LIKE 'กทม.%'   THEN 'กรุงเทพมหานคร'
		ELSE concat('จังหวัด', btrim(a.\"A_PRO\"))
		END
	END, ''), ' ', COALESCE(
CASE
	WHEN trim( a.\"A_POST\") IS NULL OR trim( a.\"A_POST\") = '-' OR trim( a.\"A_POST\") = '--' OR trim( a.\"A_POST\") = '0' OR trim( a.\"A_POST\") = ' ' THEN ''
	ELSE btrim(a.\"A_POST\")
	END, ''), '', '') AS address
from \"Fp_Fa1\" a
left join \"Fa1\" b on a.\"CusID\"=b.\"CusID\"
LEFT JOIN \"Fn\" c on b.\"CusID\"=c.\"CusID\"
where \"IDNO\" = '$f_idno' AND \"CusState\" <> 0 AND \"edittime\" = 0 order by \"CusState\""); 
while($res_loop_cus=pg_fetch_array($qry_loop_cus)){	
	$nub+=1;

	if($nub>5){
		$Y = 147.5;	
		//วันที่ในสัญญา
		$pdf->SetXY(95,$Y);
		$title=iconv('UTF-8','windows-874',$var8);
		$pdf->MultiCell(70,4,$title,0,'L',0);

		$Y += 6;	
		// ผู้เช่าซื้อ
		$pdf->SetXY(55,$Y);
		$title=iconv('UTF-8','windows-874',$var9." ".$fp_regis." ".$fp_reg_by);
		$pdf->MultiCell(150,4,$title,0,'L',0);
		$pdf->AddPage();
		
		$Y = 13;	
		//เลขที่สัญญา	
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(48,$Y);
		$title=iconv('UTF-8','windows-874',$var1);
		$pdf->MultiCell(70,4,$title,0,'L',0);

		$Y += 19;	
		//วันที่ทำสัญญา	
		$pdf->SetXY(155,$Y);
		$title=iconv('UTF-8','windows-874',$var2);
		$pdf->MultiCell(70,4,$title,0,'L',0);

		$Y += 10;
		$z = 228;	
	}
	//หาสัญชาติ
	if(trim($res_loop_cus["N_SAN"])==""){
		$s_N_SAN="";
	}else{
		 $s_N_SAN=" สัญชาติ ".trim($res_loop_cus["N_SAN"]);
	}
	//หาอายุ
	if(trim($res_loop_cus["N_AGE"])==""){
		$s_N_AGE="";
	}else{
		 $s_N_AGE=" อายุ ".trim($res_loop_cus["N_AGE"]." ปี");
	}
	//บัตรประจำตัว
	if(trim($res_loop_cus["N_CARD"])==""){
		$s_N_CARD="";
	}else{
		 $s_N_CARD=" ผู้ถือ".trim($res_loop_cus["N_CARD"]);
	}
	//หาเลขบัตร
	$ncard=trim($res_loop_cus["N_CARD"]);
	$av_cardid = 'เลขที่ ';
	if($ncard=="บัตรประชาชน" || $ncard=="ประชาชน"){
		$av_cardid = $av_cardid." ".$res_loop_cus["N_IDCARD"];
	}else{
		$av_cardid = $av_cardid." ".$res_loop_cus["N_CARDREF"];
	}

	//ข้อมูลประจำตัวผู้ค้ำ
	$cusguarantee_profile = $s_N_SAN.$s_N_CARD.$av_cardid;
	//ที่อยู่ผู้ค้ำประกัน
	$cusguarantee_addr = $res_loop_cus["address"];
	
	//ชื่อผู้ค้ำ
		$av_cname=trim($res_loop_cus["A_FIRNAME"])." ".trim($res_loop_cus["A_NAME"])." ".trim($res_loop_cus["A_SIRNAME"]);	
						
		
	$pdf->SetXY(44,$Y);
	$title=iconv('UTF-8','windows-874',$av_cname.$cusguarantee_profile."\n".$cusguarantee_addr);
	$pdf->MultiCell(150,5,$title,0,'L',0);

	$Y += 19;
	$z += 15;
}

$Y = 147.5;	
//วันที่ในสัญญา
$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var8);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 6;	
// ผู้เช่าซื้อ
$pdf->SetXY(55,$Y);
$title=iconv('UTF-8','windows-874',$var9." ".$fp_regis." ".$fp_reg_by);
$pdf->MultiCell(150,4,$title,0,'L',0);
	
$pdf->Output();	

?>



