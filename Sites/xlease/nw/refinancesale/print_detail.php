<?php
session_start();
include("../../config/config.php");

$dt = $_POST['dt'];

if($dt == "s1" || $dt == ""){	
}else{
	$qry_user=pg_query("select * from \"Vfuser\" WHERE \"id_user\"='$dt'");
	$res_user=pg_fetch_array($qry_user);
	$q_iduser=$res_user["id_user"];
	$q_fullname = $res_user["fullname"];
}


$nowdate = Date('Y-m-d');

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(275,4,$buss_name,0,'R',0);
 
    }
}

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"สรุปรายละเอียดการชักชวนลูกค้า");
$pdf->MultiCell(260,4,$title,0,'C',0);

if($dt == "s1" || $dt == ""){
	$pdf->SetFont('AngsanaNew','B',13);
	$pdf->SetXY(6,25);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(150,4,$buss_name,0,'L',0);
}else{
	$pdf->SetFont('AngsanaNew','B',13);
	$pdf->SetXY(10,25);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อพนักงาน : $q_fullname (รหัสพนักงาน : $q_iduser)");
	$pdf->MultiCell(150,4,$buss_name,0,'L',0);
	
}
$pdf->SetXY(235,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

/* Header of Table*/
$pdf->SetXY(10,35);
$buss_name=iconv('UTF-8','windows-874',"ที่");
$pdf->MultiCell(10,6,$buss_name,1,'C',0);

$pdf->SetXY(20,35);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);

$pdf->SetXY(45,35);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ - สกุล");
$pdf->MultiCell(60,6,$buss_name,1,'C',0);

$pdf->SetXY(105,35);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถยนต์");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);

$pdf->SetXY(130,35);
$buss_name=iconv('UTF-8','windows-874',"ชื่อรุ่น");
$pdf->MultiCell(40,6,$buss_name,1,'C',0);

$pdf->SetXY(170,35);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด (Inc.Vat)");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);

$pdf->SetXY(200,35);
$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);

$pdf->SetXY(225,35);
$buss_name=iconv('UTF-8','windows-874',"ID, ชื่อ -สกุลพนักงาน");
$pdf->MultiCell(60,6,$buss_name,1,'C',0);

$cline = 41;
$i = 1;
$nub = 1;

if($dt == "s1" || $dt == ""){
	$qry_nomatch=pg_query("SELECT \"IDNO\",\"CusID\",\"asset_id\",\"id_user\" FROM refinance.\"invite\" group by \"IDNO\" ,\"CusID\",\"asset_id\",\"id_user\" order by \"id_user\"");
}else{
	$qry_nomatch=pg_query("SELECT \"IDNO\",\"CusID\",\"asset_id\",\"id_user\" FROM refinance.\"invite\" where \"id_user\" = '$dt' group by \"IDNO\" ,\"CusID\",\"asset_id\",\"id_user\" order by \"id_user\"");
}
$nrows=pg_num_rows($qry_nomatch);

while($res=pg_fetch_array($qry_nomatch)){ 
	$IDNO=$res["IDNO"];
	$CusID=$res["CusID"];
	$asset_id=$res["asset_id"];
	$id_user = $res["id_user"];
								
	$qry_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' and \"CusID\" = '$CusID'");
	$res_VContact=pg_fetch_array($qry_VContact);
	$v_idno = $res_VContact["IDNO"];
	$v_cusid = $res_VContact["CusID"];
	$v_fullname = $res_VContact["full_name"];
	$v_ccarname = $res_VContact["C_CARNAME"];
	$v_paymentall = $res_VContact["P_MONTH"] + $res_VContact["P_VAT"];
	$v_ptotal = $res_VContact["P_TOTAL"];
													
	$qry_idno=pg_query("SELECT count(\"IDNO\") AS \"countidno\" FROM \"VCusPayment\" where \"R_Date\" is null and \"IDNO\" = '$v_idno' ");
	if($result_idno=pg_fetch_array($qry_idno)){
		$countidno = $result_idno["countidno"];
	}
													
	$v_ptotaled = $v_ptotal - $countidno;
													
	if($res_VContact["C_REGIS"]==""){
		$regis=$res_VContact["car_regis"];														
	}else{
		$regis=$res_VContact["C_REGIS"];					
	}		
		
	if($nub > 22){
		$nub = 1;
		$cline = 41;
		$pdf->AddPage();
	
		$pdf->SetXY(10,35);
		$buss_name=iconv('UTF-8','windows-874',"ที่");
		$pdf->MultiCell(10,6,$buss_name,1,'C',0);

		$pdf->SetXY(20,35);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(45,35);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อ - สกุล");
		$pdf->MultiCell(60,6,$buss_name,1,'C',0);

		$pdf->SetXY(105,35);
		$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถยนต์");
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(130,35);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อรุ่น");
		$pdf->MultiCell(40,6,$buss_name,1,'C',0);

		$pdf->SetXY(170,35);
		$buss_name=iconv('UTF-8','windows-874',"ค่างวด (Inc.Vat)");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);

		$pdf->SetXY(200,35);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(225,35);
		$buss_name=iconv('UTF-8','windows-874',"ID, ชื่อ -สกุลพนักงาน");
		$pdf->MultiCell(60,6,$buss_name,1,'C',0);
	}							
		
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(10,$cline);
	$buss_name=iconv('UTF-8','windows-874',$i);
	$pdf->MultiCell(10,6,$buss_name,1,'C',0);

	$pdf->SetXY(20,$cline);
	$buss_name=iconv('UTF-8','windows-874',$v_idno);
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);

	$pdf->SetXY(45,$cline);
	$buss_name=iconv('UTF-8','windows-874',$v_fullname);
	$pdf->MultiCell(60,6,$buss_name,1,'L',0);

	$pdf->SetXY(105,$cline);
	$buss_name=iconv('UTF-8','windows-874',$regis);
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);

	$pdf->SetXY(130,$cline);
	$buss_name=iconv('UTF-8','windows-874',$v_ccarname);
	$pdf->MultiCell(40,6,$buss_name,1,'L',0);

	$v_paymentall = number_format($v_paymentall,2);
	$pdf->SetXY(170,$cline);
	$buss_name=iconv('UTF-8','windows-874',$v_paymentall);
	$pdf->MultiCell(30,6,$buss_name,1,'C',0);

	$pdf->SetXY(200,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$v_ptotaled/$v_ptotal");
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);

	$qry_user1=pg_query("select * from \"Vfuser\" WHERE \"id_user\"='$id_user'");
	$res_user1=pg_fetch_array($qry_user1);
	$q_iduser1=$res_user1["id_user"];
	$q_fullname1 = $res_user1["fullname"];
	
	$pdf->SetXY(225,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$q_iduser1, $q_fullname1");
	$pdf->MultiCell(60,6,$buss_name,1,'L',0);
	
	$cline = $cline +6;								
	$i++;
	$nub++;
}
	
$pdf->Output();
?>