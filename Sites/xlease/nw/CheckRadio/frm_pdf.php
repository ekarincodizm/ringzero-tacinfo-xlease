<?php
session_start();
include("../../config/config.php");

$nowdate = nowDate();
$check_print = $_POST["check_print"];
$money = $_POST["money"];

//------ หาจำนวนรายการ
if($check_print == "map") // ถ้าเลือกแบบ map
{
	$t = 0;
	$qry2=pg_query("select * from public.\"FOtherpay\" where \"O_DATE\" >= '2012-01-01' and \"O_Type\" = '307' order by \"IDNO\" ");
	$numone = pg_num_rows($qry2);
	while($res12=pg_fetch_array($qry2))
	{
		$O_RECEIPT_check = $res12["O_RECEIPT"];
	
		$qry_check2=pg_query("select * from public.\"tacReceiveTemp\" where \"tacXlsRecID\" = '$O_RECEIPT_check' ");
		$numrow = pg_num_rows($qry_check2);
		if($numrow == 0) // ถ้าเป็นศูนย์แสดงว่าไม่มีข้อมูลเหมือนต้นฉบับ
		{
			$t++;
		}
	}
}


if($check_print == "map_money") // ถ้าเลือกแบบ map และระบุจำนวนเงินด้วย
{
	$t = 0;
	$qry=pg_query("select * from public.\"FOtherpay\" where \"O_DATE\" >= '2012-01-01' and \"O_Type\" = '307' order by \"IDNO\" ");
	$numone = pg_num_rows($qry);
	while($res=pg_fetch_array($qry))
	{
		$IDNO = $res["IDNO"];
		$O_RECEIPT = $res["O_RECEIPT"];
		$O_MONEY = $res["O_MONEY"];
    
		$qry_check=pg_query("select * from public.\"tacReceiveTemp\" where \"tacXlsRecID\" = '$O_RECEIPT' ");
		$numrow = pg_num_rows($qry_check);
		if($numrow == 0) // ถ้าเป็นศูนย์แสดงว่าไม่มีข้อมูลเหมือนต้นฉบับ  ให้แสดงข้อมูลออกมา
		{
			$t++;
		}
		else // ถ้ามีข้อมูล ก็มาเช็คต่อว่า จำนวนเงินมีผลต่างมากกว่าที่เรากำหนดไว้หรือไม่    ถ้ามากกว่า ให้แสดงออกมาด้วย
		{
			while($res_money=pg_fetch_array($qry_check))
			{
				$tacMoney = $res_money["tacMoney"];
				if($O_MONEY - $tacMoney > $money || $tacMoney - $O_MONEY > $money)
				{
					$t++;
				}
			}
		}
	}
}


if($check_print == "order") // ถ้าเลือกแบบ ผิดปกติ
{
	$t = 0;
	$qry=pg_query("select \"tacReceiveTemp\".\"tacID\" , \"FOtherpay\".\"O_RECEIPT\" , \"tacReceiveTemp\".\"tacMoney\" , \"VCarregistemp\".\"C_REGIS\"
				from public.\"tacReceiveTemp\" , public.\"FOtherpay\" , public.\"Fp\" , public.\"VCarregistemp\"
				where \"tacReceiveTemp\".\"tacXlsRecID\" = \"FOtherpay\".\"O_RECEIPT\"
				and \"FOtherpay\".\"IDNO\" = \"Fp\".\"IDNO\"
				and \"Fp\".\"IDNO\" = \"VCarregistemp\".\"IDNO\" ");
	$numone = pg_num_rows($qry);
	while($res=pg_fetch_array($qry))
	{
		$tacID = $res["tacID"];
		$O_RECEIPT = $res["O_RECEIPT"];
		$tacMoney = $res["tacMoney"];
		$C_REGIS = trim($res["C_REGIS"]);
	
		$query_ONID=mssql_query("select * from TacCusDtl where CusID='$tacID'");
		$num_ONID=mssql_num_rows($query_ONID);
		
		if($num_ONID != 0)
		{
			while($res_test=mssql_fetch_array($query_ONID))
			{
				$CarRegis_check = trim(iconv('WINDOWS-874','UTF-8',$res_test["CarRegis"]));
			}
	
			if($C_REGIS != $CarRegis_check) // ถ้าเป็นศูนย์แสดงว่าไม่มีข้อมูลเหมือนต้นฉบับ
			{
				$t++;
			}
		}
	}
}
//------ จบการหาจำนวนรายการ


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
if($check_print == "map"){$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบการ map");}
elseif($check_print == "map_money"){$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบการ map");}
elseif($check_print == "order"){$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบรายการผิดปกติ");}
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
if($check_print == "map"){$buss_name=iconv('UTF-8','windows-874',"ใบเสร็จที่มีไม่เท่ากัน จำนวน : $t รายการ");}
elseif($check_print == "map_money"){$buss_name=iconv('UTF-8','windows-874',"ใบเสร็จที่มีไม่เท่ากัน และ ใบเสร็จที่มีจำนวนเงินต่างกันมากกว่า $money บาท จำนวน : $t รายการ");}
elseif($check_print == "order"){$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถที่ไม่ตรงกัน จำนวน : $t รายการ");}
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

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

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;
$a=0;

if($check_print == "map") // ถ้าเลือกแบบ map
{
	$qry=pg_query("select * from public.\"FOtherpay\" where \"O_DATE\" >= '2012-01-01' and \"O_Type\" = '307' order by \"IDNO\" ");
	$numone = pg_num_rows($qry);

	while($res=pg_fetch_array($qry))
	{
		$IDNO = $res["IDNO"];
		$O_RECEIPT = $res["O_RECEIPT"];
		$O_MONEY = $res["O_MONEY"];
    
		$qry_check=pg_query("select * from public.\"tacReceiveTemp\" where \"tacXlsRecID\" = '$O_RECEIPT' ");
		$num_row = pg_num_rows($qry_check);
		if($num_row == 0) // ถ้าเป็นศูนย์แสดงว่าไม่มีข้อมูลเหมือนต้นฉบับ
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
				$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบการ map");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"ใบเสร็จที่มีไม่เท่ากัน จำนวน : $t รายการ");
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
			$pdf->SetXY(30,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$IDNO");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetXY(90,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(128,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
			$pdf->MultiCell(40,4,$buss_name,0,'R',0);

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
elseif($check_print == "order") // ถ้าเลือกแบบ ผิดปกติ
{
	$qry=pg_query("select \"tacReceiveTemp\".\"tacID\" , \"FOtherpay\".\"O_RECEIPT\" , \"tacReceiveTemp\".\"tacMoney\" , \"VCarregistemp\".\"C_REGIS\"
				from public.\"tacReceiveTemp\" , public.\"FOtherpay\" , public.\"Fp\" , public.\"VCarregistemp\"
				where \"tacReceiveTemp\".\"tacXlsRecID\" = \"FOtherpay\".\"O_RECEIPT\"
				and \"FOtherpay\".\"IDNO\" = \"Fp\".\"IDNO\"
				and \"Fp\".\"IDNO\" = \"VCarregistemp\".\"IDNO\" ");
	$numone = pg_num_rows($qry);
	while($res=pg_fetch_array($qry))
	{
		$tacID = $res["tacID"];
		$O_RECEIPT = $res["O_RECEIPT"];
		$tacMoney = $res["tacMoney"];
		$C_REGIS = trim($res["C_REGIS"]);
	
		$query_ONID=mssql_query("select * from TacCusDtl where CusID='$tacID'");
		$num_ONID=mssql_num_rows($query_ONID);
		
		if($num_ONID != 0)
		{
			while($res_test=mssql_fetch_array($query_ONID))
			{
				$CarRegis_check = trim(iconv('WINDOWS-874','UTF-8',$res_test["CarRegis"]));
			}
	
			if($C_REGIS != $CarRegis_check) // ถ้าเป็นศูนย์แสดงว่าไม่มีข้อมูลเหมือนต้นฉบับ
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
					$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบรายการผิดปกติ");
					$pdf->MultiCell(200,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถที่ไม่ตรงกัน จำนวน : $t รายการ");
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
				$pdf->SetXY(30,$cline);
				$buss_name=iconv('UTF-8','windows-874',"$tacID");
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);

				$pdf->SetXY(90,$cline);
				$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(128,$cline);
				$buss_name=iconv('UTF-8','windows-874',number_format($tacMoney,2));
				$pdf->MultiCell(40,4,$buss_name,0,'R',0);
	
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
elseif($check_print == "map_money") // ถ้าเลือกแบบ map และกำหนดจำนวนเงินด้วย
{
	$qry=pg_query("select * from public.\"FOtherpay\" where \"O_DATE\" >= '2012-01-01' and \"O_Type\" = '307' order by \"IDNO\" ");
	$numone = pg_num_rows($qry);

	while($res=pg_fetch_array($qry))
	{
		$IDNO = $res["IDNO"];
		$O_RECEIPT = $res["O_RECEIPT"];
		$O_MONEY = $res["O_MONEY"];
    
		$qry_check=pg_query("select * from public.\"tacReceiveTemp\" where \"tacXlsRecID\" = '$O_RECEIPT' ");
		$num_row = pg_num_rows($qry_check);
		if($num_row == 0) // ถ้าเป็นศูนย์แสดงว่าไม่มีข้อมูลเหมือนต้นฉบับ
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
				$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบการ map");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"ใบเสร็จที่มีไม่เท่ากัน และ ใบเสร็จที่มีจำนวนเงินต่างกันมากกว่า $money บาท จำนวน : $t รายการ");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);
	
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
			$pdf->SetXY(30,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$IDNO");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetXY(90,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(128,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
			$pdf->MultiCell(40,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
			$cline += 5;
			$nub+=1;
			$a += 1;
		}
		else // ถ้ามีข้อมูล ก็มาเช็คต่อว่า จำนวนเงินมีผลต่างมากกว่าที่เรากำหนดไว้หรือไม่    ถ้ามากกว่า ให้แสดงออกมาด้วย
		{
			while($res_money=pg_fetch_array($qry_check))
			{
				$tacMoney = $res_money["tacMoney"];
				if($O_MONEY - $tacMoney > $money || $tacMoney - $O_MONEY > $money)
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
						$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบการ map");
						$pdf->MultiCell(200,4,$buss_name,0,'C',0);

						$pdf->SetFont('AngsanaNew','',12);
						$pdf->SetXY(5,25);
						$buss_name=iconv('UTF-8','windows-874',"ใบเสร็จที่มีไม่เท่ากัน และ ใบเสร็จที่มีจำนวนเงินต่างกันมากกว่า $money บาท จำนวน : $t รายการ");
						$pdf->MultiCell(200,4,$buss_name,0,'L',0);
	
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
					$pdf->SetXY(30,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$IDNO");
					$pdf->MultiCell(40,4,$buss_name,0,'C',0);

					$pdf->SetXY(90,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(128,$cline);
					$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
					$pdf->MultiCell(40,4,$buss_name,0,'R',0);

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