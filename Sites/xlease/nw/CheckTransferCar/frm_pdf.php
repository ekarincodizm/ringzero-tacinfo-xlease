<?php
session_start();
include("../../config/config.php");

$host="172.16.2.5";
$dbmysqlname="ta_tal_1r4_dev";
$dbuser="ta_auto";
$dbpass="ta_auto";
$connect_db = mysql_connect($host,$dbuser,$dbpass)or die ("Cannot connect to MySQL Database");

// เชื่อมต่อกับฐานข้อมูลของ DATABASE MySql
mysql_select_db($dbmysqlname,$connect_db);
mysql_query("SET NAMES 'UTF8'");

$nowdate = nowDate();


//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบทะเบียนรถที่ผิดพลาด");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
//$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถที่ผิดพลาด จำนวน : $t ทะเบียน");
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(90,33);
$buss_name=iconv('UTF-8','windows-874',"หมายเลขทะเบียนรถ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;
$a=0;







$irow = 0;
$sql_origin = mysql_query("SELECT distinct car_license , ta_join_pm_id , contract_id , cpro_id , cpro_name FROM ta_join_main_bin order by car_license");
$numrow_origin = mysql_num_rows($sql_origin);
while($res=mysql_fetch_array($sql_origin)) // ค้นหาข้อมูลทั้งหมดในตาราง  ta_join_main_bin
{
	$ta_join_pm_id = $res["ta_join_pm_id"];
	$car_license = $res["car_license"];
	$contract_id = $res["contract_id"];
	$cpro_id = $res["cpro_id"];
	$cpro_name = $res["cpro_name"];

	$sql_main = mysql_query("SELECT * FROM ta_join_main
							where car_license = '$car_license' ");
	$numrow_main = mysql_num_rows($sql_main);
	if($numrow_main == 0) // ถ้าตาราง ta_join_main_bin มีข้อมูล แต่ตาราง ta_join_main ไม่มีข้อมูลให้แสดงออกมา
	{
		if($nub == 46)
			{
				$nub = 1;
				$cline = 40;
				$pdf->AddPage();
        
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
				$pdf->MultiCell(200,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบทะเบียนรถที่ผิดพลาด");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				//$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถที่ผิดพลาด จำนวน : $t ทะเบียน");
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(50,4,$buss_name,0,'L',0);
	
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(200,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	
				$pdf->SetXY(90,33);
				$buss_name=iconv('UTF-8','windows-874',"หมายเลขทะเบียนรถ");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,34);
				$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			}
	
			$pdf->SetFont('AngsanaNew','',12);

			$pdf->SetXY(90,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$car_license");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
			$cline += 5;
			$nub+=1;
			$a += 1;
	}
}


$sql_three = mysql_query("SELECT distinct car_license , ta_join_payment_id , contract_id , cpro_id , cpro_name FROM ta_join_payment order by car_license");
while($res_3 = mysql_fetch_array($sql_three)) // ค้นหาข้อมูลทั้งหมดในตาราง  ta_join_payment
{
	$ta_join_pm_id_three = $res_3["ta_join_payment_id"];
	$car_license_three = $res_3["car_license"];
	$contract_id_three = $res_3["contract_id"];
	$cpro_id_three = $res_3["cpro_id"];
	$cpro_name_three = $res_3["cpro_name"];
	
	$sql_one = mysql_query("SELECT * FROM ta_join_main_bin
							where car_license = '$car_license_three' ");
	$numrow_one = mysql_num_rows($sql_one);
	if($numrow_one > 0) // เช็คก่อนว่าในตาราง ta_join_payment มีข้อมูลเหมือนในตาราง ta_join_main_bin ถ้ามีแสดงว่าเคยทดสอบไปแล้วไม่ต้องทดสอบอีก
	{}
	else // ถ้าไม่มีให้ไปทดสอบความถูกต้องที่ตาราง ta_join_main
	{
		$sql_main = mysql_query("SELECT * FROM ta_join_main
							where car_license = '$car_license_three' ");
		$numrow_main = mysql_num_rows($sql_main);
		if($numrow_main == 0) // ถ้าตาราง ta_join_payment มีข้อมูล แต่ตาราง ta_join_main ไม่มีข้อมูลให้แสดงออกมา
		{
			if($nub == 46)
			{
				$nub = 1;
				$cline = 40;
				$pdf->AddPage();
        
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
				$pdf->MultiCell(200,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบทะเบียนรถที่ผิดพลาด");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				//$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถที่ผิดพลาด จำนวน : $t ทะเบียน");
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(50,4,$buss_name,0,'L',0);
	
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(200,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	
				$pdf->SetXY(90,33);
				$buss_name=iconv('UTF-8','windows-874',"หมายเลขทะเบียนรถ");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,34);
				$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			}
	
			$pdf->SetFont('AngsanaNew','',12);

			$pdf->SetXY(90,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$car_license");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
			$cline += 5;
			$nub+=1;
			$a += 1;
		}
	}
}


$sql_four = mysql_query("SELECT distinct car_license , ta_join_payment_id , contract_id , cpro_id , cpro_name FROM ta_join_payment_bin order by car_license");
while($res_4 = mysql_fetch_array($sql_four)) // ค้นหาข้อมูลทั้งหมดในตาราง  ta_join_payment_bin
{
	$ta_join_pm_id_four = $res_4["ta_join_payment_id"];
	$car_license_four = $res_4["car_license"];
	$contract_id_four = $res_4["contract_id"];
	$cpro_id_four = $res_4["cpro_id"];
	$cpro_name_four = $res_4["cpro_name"];
	
	$sql_one = mysql_query("SELECT * FROM ta_join_main_bin
							where car_license = '$car_license_four' ");
	$numrow_one = mysql_num_rows($sql_one);
	if($numrow_one > 0) // เช็คก่อนว่าในตาราง ta_join_payment_bin มีข้อมูลเหมือนในตาราง ta_join_main_bin ถ้ามีแสดงว่าเคยทดสอบไปแล้วไม่ต้องทดสอบอีก
	{}
	else // ถ้าไม่มีให้ไปทดสอบความถูกต้องที่ตาราง ta_join_main
	{
		$sql_two = mysql_query("SELECT * FROM ta_join_payment
							where car_license = '$car_license_four' ");
		$numrow_two = mysql_num_rows($sql_two);
		if($numrow_two > 0) // เช็คก่อนว่าในตาราง ta_join_payment_bin มีข้อมูลเหมือนในตาราง ta_join_payment ถ้ามีแสดงว่าเคยทดสอบไปแล้วไม่ต้องทดสอบอีก
		{}
		else
		{
			$sql_main = mysql_query("SELECT * FROM ta_join_main
									where car_license = '$car_license_four' ");
			$numrow_main = mysql_num_rows($sql_main);
			if($numrow_main == 0) // ถ้าตาราง ta_join_payment มีข้อมูล แต่ตาราง ta_join_main ไม่มีข้อมูลให้แสดงออกมา
			{
				if($nub == 46)
			{
				$nub = 1;
				$cline = 40;
				$pdf->AddPage();
        
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
				$pdf->MultiCell(200,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบทะเบียนรถที่ผิดพลาด");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				//$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถที่ผิดพลาด จำนวน : $t ทะเบียน");
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(50,4,$buss_name,0,'L',0);
	
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(200,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetXY(30,33);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);
	
				$pdf->SetXY(90,33);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
				$pdf->SetXY(140,33);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,34);
				$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			}
	
			$pdf->SetFont('AngsanaNew','',12);

			$pdf->SetXY(90,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$car_license");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
			$cline += 5;
			$nub+=1;
			$a += 1;
			}
		}
	}
}

if($num_row > 0){
    $pdf->SetFont('AngsanaNew','B',13);

    $pdf->SetXY(5,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
    $cline += 6;
    $nub+=1;
}

$pdf->Output();
?>