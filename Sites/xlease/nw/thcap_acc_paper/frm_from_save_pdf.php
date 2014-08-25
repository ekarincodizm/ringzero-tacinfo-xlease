<?php
include("../../config/config.php");

$save_id = pg_escape_string($_GET["save_id"]);

$qry_from_save = pg_query("select save_name, ledger_month, ledger_year from account.thcap_ledger_save_head where save_id = '$save_id' ");
$save_name = pg_result($qry_from_save,0);
$mm = pg_result($qry_from_save,1);
$yy = pg_result($qry_from_save,2);

$chk_all = pg_escape_string($_GET['chk_all']);//กด แสดงทุกสมุดทั้งหมด รวมถึงสมุดที่ไม่ได้ใช้งาน หรือไม่ โดย yes = กด ,no =ไม่กด

$nowdate = nowDateTime();
$id_user=$_SESSION["av_iduser"];
//ผู้พิมพ์
$queryU=pg_query("select \"fullname\" from \"Vfuser\" where id_user = '$id_user'");
$user=pg_fetch_result($queryU,0);

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];
$show_yy = $yy+543;

$dr0 = 0; // รวม เดบิต ของ ยอดยกมา
$cr0 = 0; // รวม เครดิต ของ ยอดยกมา
$dr1 = 0; // รวม เดบิต ของ งบทดลอง
$cr1 = 0; // รวม เครดิต ของ งบทดลอง
$dr2 = 0; // รวม เดบิต ของ งบกำไรขาดทุน
$cr2 = 0; // รวม เครดิต ของ งบกำไรขาดทุน
$dr3 = 0; // รวม เดบิต ของ งบดุล
$cr3 = 0; // รวม เครดิต ของ งบดุล

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(4,8); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(285,4,$buss_name,0,'R',0);
    }
}


$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(4,10);
$title=iconv('UTF-8','windows-874',"กระดาษทำการ");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,16); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ ".$user);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"$save_name(เดือน $mm ปี $yy)");
$pdf->MultiCell(280,4,$buss_name,0,'L',0);

$pdf->SetXY(5,29); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อบัญชี");
$pdf->MultiCell(68,10,$buss_name,1,'C',0);

$pdf->SetXY(73,29); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
$pdf->MultiCell(15,10,$buss_name,1,'C',0);

$pdf->SetXY(88,29); 
$buss_name=iconv('UTF-8','windows-874',"ยอดยกมา");
$pdf->MultiCell(50,5,$buss_name,1,'C',0);

$pdf->SetXY(138,29); 
$buss_name=iconv('UTF-8','windows-874',"งบทดลอง");
$pdf->MultiCell(50,5,$buss_name,1,'C',0);

$pdf->SetXY(188,29); 
$buss_name=iconv('UTF-8','windows-874',"งบกำไรขาดทุน");
$pdf->MultiCell(50,5,$buss_name,1,'C',0);

$pdf->SetXY(238,29); 
$buss_name=iconv('UTF-8','windows-874',"งบดุล");
$pdf->MultiCell(50,5,$buss_name,1,'C',0);

$pdf->SetXY(88,34); 
$buss_name=iconv('UTF-8','windows-874',"เดบิต");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(113,34);
$buss_name=iconv('UTF-8','windows-874',"เครดิต");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(138,34); 
$buss_name=iconv('UTF-8','windows-874',"เดบิต");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(163,34);
$buss_name=iconv('UTF-8','windows-874',"เครดิต");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(188,34); 
$buss_name=iconv('UTF-8','windows-874',"เดบิต");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(213,34);
$buss_name=iconv('UTF-8','windows-874',"เครดิต");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(238,34); 
$buss_name=iconv('UTF-8','windows-874',"เดบิต");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(263,34);
$buss_name=iconv('UTF-8','windows-874',"เครดิต");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$cline = 39;

$sum = 0;
$sum_up = 0;
$set_mm = (int)$mm;	
$sql_day =  pg_query("SELECT \"gen_numdaysinmonth\"($set_mm,$yy)");
$sql_day =pg_fetch_array($sql_day); 
$yy0=$yy-1;
list($cday)=$sql_day;
$qry = pg_query("SELECT \"accBookserial\",\"accBookID\",\"accBookName\",\"accBookUnit\",\"accBookType\" FROM account.\"all_accBook\" ORDER BY \"accBookID\" ASC");
while($res=pg_fetch_array($qry)){
	$Acserial= $res['accBookserial'];
    $AcID = $res['accBookID'];
    $AcName = $res['accBookName'];
	$accBookUnit = $res['accBookUnit'];
	$accBookType = $res['accBookType']; // ประเภทสมุดบัญชี
	
	$abh_sum=  pg_query("SELECT \"ledger_balance\", \"income_statement\", \"balance_sheet\" from account.\"thcap_ledger_save_detail\" where  \"accBookserial\" ='$Acserial' and \"is_ledgerstatus\" = '1' 
	and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy' and EXTRACT(MONTH FROM \"ledger_stamp\")= '$set_mm' and EXTRACT(DAY FROM \"ledger_stamp\")= '$cday'
	and save_id = '$save_id' ");
	$abh_sum0 =pg_fetch_array($abh_sum); 
	list($abh_sum1, $income_statement, $balance_sheet)=$abh_sum0;
	
	//มีรายการในนอกเหนือหารสรุปแต่ละเดือน
	$abh_list_detail=  pg_query("SELECT count(\"auto_id\") from account.\"thcap_ledger_save_detail\" where  \"accBookserial\" ='$Acserial' and save_id = '$save_id'
	and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy' and \"is_ledgerstatus\"='0'");
	$res_list_detail =pg_fetch_array($abh_list_detail); 
	list($rows_list_detail)=$res_list_detail;	
	
	//ยอดยกมา
	$abh_balance=  pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_save_detail\" where \"accBookserial\" ='$Acserial'
	and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy0' and EXTRACT(MONTH FROM \"ledger_stamp\")= '12' and save_id = '$save_id'
	and \"is_ledgerstatus\"='1'");	
	
	$abh_balance0 =pg_fetch_array($abh_balance); 
	list($abh_balance1)=$abh_balance0;
   
    /*****ถ้า ไม่ได้ติกให้แสดงทุกสมุด และ สรุปของบัญชีในเดือนเป็น 0 และยกยกมาเป็น 0 จะไม่แสดง******/
	if(($chk_all=="no") and (($abh_sum1==0) or ($abh_sum1=="")) and (($abh_balance1==0) or ($abh_balance1==""))
	and (($rows_list_detail==0))){}
    else
	{
		if($nub >= 28){
			$nub = 0;
			$cline = 39;
			$pdf->AddPage();

			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(4,10);
			$title=iconv('UTF-8','windows-874',"กระดาษทำการ");
			$pdf->MultiCell(285,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(4,16);
			$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
			$pdf->MultiCell(285,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,23); 
			$buss_name=iconv('UTF-8','windows-874',"$save_name(เดือน $mm ปี $yy)");
			$pdf->MultiCell(280,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,29); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อบัญชี");
			$pdf->MultiCell(68,10,$buss_name,1,'C',0);

			$pdf->SetXY(73,29); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
			$pdf->MultiCell(15,10,$buss_name,1,'C',0);

			$pdf->SetXY(88,29); 
			$buss_name=iconv('UTF-8','windows-874',"ยอดยกมา");
			$pdf->MultiCell(50,5,$buss_name,1,'C',0);

			$pdf->SetXY(138,29); 
			$buss_name=iconv('UTF-8','windows-874',"งบทดลอง");
			$pdf->MultiCell(50,5,$buss_name,1,'C',0);

			$pdf->SetXY(188,29); 
			$buss_name=iconv('UTF-8','windows-874',"งบกำไรขาดทุน");
			$pdf->MultiCell(50,5,$buss_name,1,'C',0);

			$pdf->SetXY(238,29); 
			$buss_name=iconv('UTF-8','windows-874',"งบดุล");
			$pdf->MultiCell(50,5,$buss_name,1,'C',0);

			$pdf->SetXY(88,34); 
			$buss_name=iconv('UTF-8','windows-874',"เดบิต");
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);

			$pdf->SetXY(113,34);
			$buss_name=iconv('UTF-8','windows-874',"เครดิต");
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);

			$pdf->SetXY(138,34); 
			$buss_name=iconv('UTF-8','windows-874',"เดบิต");
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);

			$pdf->SetXY(163,34);
			$buss_name=iconv('UTF-8','windows-874',"เครดิต");
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);

			$pdf->SetXY(188,34); 
			$buss_name=iconv('UTF-8','windows-874',"เดบิต");
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);

			$pdf->SetXY(213,34);
			$buss_name=iconv('UTF-8','windows-874',"เครดิต");
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);

			$pdf->SetXY(238,34); 
			$buss_name=iconv('UTF-8','windows-874',"เดบิต");
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);

			$pdf->SetXY(263,34);
			$buss_name=iconv('UTF-8','windows-874',"เครดิต");
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);
		}
		if($accBookUnit=='0'){
			$pdf->SetFont('AngsanaNew','B',8);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$AcName");		
			$pdf->MultiCell(68,5,$buss_name,1,'L',0);
		}
		else{
			$pdf->SetFont('AngsanaNew','',8);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$AcName");
			$pdf->MultiCell(68,5,$buss_name,1,'L',0);
		}	
		
		if($accBookUnit=='0'){
			$sql_abh_balance_unit=  pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_save_detail\" where \"accBookserial\" ='$Acserial'
			and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy0' and EXTRACT(MONTH FROM \"ledger_stamp\")= '12' and save_id = '$save_id'
			and \"is_ledgerstatus\"='2'");
			$sql_abh_balance_unit =pg_fetch_array($sql_abh_balance_unit); 
			list($abh_balance_unit)=$sql_abh_balance_unit;
			if($abh_balance_unit ==''){$abh_balance_unit =0.00;}
			$sum += $abh_balance_unit;
			//$abh_balance_unit= '('.number_format($abh_balance_unit,2).')';
			
			$sql_abh_balance_unit_sum=  pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_save_detail\" where  \"accBookserial\" ='$Acserial' and \"is_ledgerstatus\" ='2' 
			and EXTRACT(YEAR FROM \"ledger_stamp\") = '$yy' and EXTRACT(MONTH FROM \"ledger_stamp\")= '$set_mm' and EXTRACT(DAY FROM \"ledger_stamp\")= '$cday'
			and save_id = '$save_id' ");
			$sql_abh_balance_unit_sum =pg_fetch_array($sql_abh_balance_unit_sum); 
			list($abh_balance_unit_sum)=$sql_abh_balance_unit_sum;
			if($abh_balance_unit_sum ==''){$abh_balance_unit_sum =0;}
			 $sum_up += $abh_balance_unit_sum;	
			//$abh_balance_unit_sum= '('.number_format($abh_balance_unit_sum,2).')';   
			
		}else{
		$abh_balance_unit='';
		$abh_balance_unit_sum='';
		}
		
		// รหัสบัญชี
		$pdf->SetXY(73,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$AcID");
		$pdf->MultiCell(15,5,$buss_name,1,'C',0);
		
		// ยอดยกมา
		if($abh_balance1 == ""){$abh_balance1 = 0;}
		if($accBookUnit=="0")
		{
			if($abh_balance_unit_sum != "" && $abh_balance_unit_sum < 0)
			{
				$abh_balance_unit_sum *= -1;
				$abh_balance_unit_sum = '('.number_format($abh_balance_unit_sum,2).')';
			}
			else
			{
				$abh_balance_unit_sum = '('.number_format($abh_balance_unit_sum,2).')';
			}
		}
		else
		{
			$abh_balance_unit_sum = "";
		}
		
		if($abh_balance1 >= 0)
		{
			$abh_balance2 = $abh_balance1;
			$pdf->SetXY(88,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($abh_balance2,2).$abh_balance_unit_sum);
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
			
			$dr0 += $abh_balance2;
			
			$pdf->SetXY(113,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		}
		else
		{
			$abh_balance2 = $abh_balance1 * -1;
			$pdf->SetXY(113,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($abh_balance2,2).$abh_balance_unit_sum);
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
			
			$cr0 += $abh_balance2;
			
			$pdf->SetXY(88,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		}
		
		// งบทดลอง
		if($abh_sum1 == ""){$abh_sum1 = 0;}
		if($accBookUnit=="0")
		{
			if($abh_balance_unit_sum != "" && $abh_balance_unit_sum < 0)
			{
				$abh_balance_unit_sum *= -1;
				$abh_balance_unit_sum = '('.number_format($abh_balance_unit_sum,2).')';
			}
			else
			{
				$abh_balance_unit_sum = '('.number_format($abh_balance_unit_sum,2).')';
			}
		}
		else
		{
			$abh_balance_unit_sum = "";
		}
		
		if($abh_sum1 >= 0)
		{
			$abh_sum2 = $abh_sum1;
			$pdf->SetXY(138,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($abh_sum2,2).$abh_balance_unit_sum);
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
			
			$dr1 += $abh_sum2;
			
			$pdf->SetXY(163,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		}
		else
		{
			$abh_sum2 = $abh_sum1 * -1;
			$pdf->SetXY(163,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($abh_sum2,2).$abh_balance_unit_sum);
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
			
			$cr1 += $abh_sum2;
			
			$pdf->SetXY(138,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		}
		
		// งบกำไรขาดทุน
		if($income_statement != "")
		{
			if($abh_sum1 >= 0)
			{
				$income_statement2 = $income_statement;
				$pdf->SetXY(188,$cline);
				$buss_name=iconv('UTF-8','windows-874',number_format($income_statement2,2));
				$pdf->MultiCell(25,5,$buss_name,1,'R',0);
				
				$dr2 += $income_statement2;
				
				$pdf->SetXY(213,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(25,5,$buss_name,1,'R',0);
			}
			else
			{
				$income_statement2 = $income_statement * -1;
				$pdf->SetXY(213,$cline);
				$buss_name=iconv('UTF-8','windows-874',number_format($income_statement2,2));
				$pdf->MultiCell(25,5,$buss_name,1,'R',0);
				
				$cr2 += $income_statement2;
				
				$pdf->SetXY(188,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(25,5,$buss_name,1,'R',0);
			}
		}
		elseif($income_statement == "" && $balance_sheet == "")
		{
			$pdf->SetXY(188,$cline);
			$buss_name=iconv('UTF-8','windows-874','ไม่พบข้อมูล');
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);
			
			$pdf->SetXY(213,$cline);
			$buss_name=iconv('UTF-8','windows-874','ไม่พบข้อมูล');
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);
		}
		else
		{
			$pdf->SetXY(188,$cline);
			$buss_name=iconv('UTF-8','windows-874','');
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
			
			$pdf->SetXY(213,$cline);
			$buss_name=iconv('UTF-8','windows-874','');
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		}
		
		// งบดุล
		if($balance_sheet != "")
		{
			if($abh_sum1 >= 0)
			{
				$balance_sheet2 = $balance_sheet;
				$pdf->SetXY(238,$cline);
				$buss_name=iconv('UTF-8','windows-874',number_format($balance_sheet2,2));
				$pdf->MultiCell(25,5,$buss_name,1,'R',0);
				
				$dr3 += $balance_sheet2;
				
				$pdf->SetXY(263,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(25,5,$buss_name,1,'R',0);
			}
			else
			{
				$balance_sheet2 = $balance_sheet * -1;
				$pdf->SetXY(263,$cline);
				$buss_name=iconv('UTF-8','windows-874',number_format($balance_sheet2,2));
				$pdf->MultiCell(25,5,$buss_name,1,'R',0);
				
				$cr3 += $balance_sheet2;
				
				$pdf->SetXY(238,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(25,5,$buss_name,1,'R',0);
			}
		}
		elseif($income_statement == "" && $balance_sheet == "")
		{
			$pdf->SetXY(238,$cline);
			$buss_name=iconv('UTF-8','windows-874','ไม่พบข้อมูล');
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);
			
			$pdf->SetXY(263,$cline);
			$buss_name=iconv('UTF-8','windows-874','ไม่พบข้อมูล');
			$pdf->MultiCell(25,5,$buss_name,1,'C',0);
		}
		else
		{
			$pdf->SetXY(238,$cline);
			$buss_name=iconv('UTF-8','windows-874','');
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
			
			$pdf->SetXY(263,$cline);
			$buss_name=iconv('UTF-8','windows-874','');
			$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		}
		
		$cline+=5;
		$nub++;
	}
}

	$pdf->SetFont('AngsanaNew','B',8);

	$pdf->SetXY(5,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(68,5,$buss_name,1,'C',0);

	$pdf->SetXY(73,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(15,5,$buss_name,1,'C',0);

    $pdf->SetXY(88,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($dr0,2));
    $pdf->MultiCell(25,5,$buss_name,1,'R',0);
	
	$pdf->SetXY(113,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($cr0,2));
    $pdf->MultiCell(25,5,$buss_name,1,'R',0);

    $pdf->SetXY(138,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($dr1,2));
    $pdf->MultiCell(25,5,$buss_name,1,'R',0);
	
	$pdf->SetXY(163,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($cr1,2));
    $pdf->MultiCell(25,5,$buss_name,1,'R',0);
	
	$pdf->SetXY(188,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($dr2,2));
    $pdf->MultiCell(25,5,$buss_name,1,'R',0);
	
	$pdf->SetXY(213,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($cr2,2));
    $pdf->MultiCell(25,5,$buss_name,1,'R',0);
	
	$pdf->SetXY(238,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($dr3,2));
    $pdf->MultiCell(25,5,$buss_name,1,'R',0);
	
	$pdf->SetXY(263,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($cr3,2));
    $pdf->MultiCell(25,5,$buss_name,1,'R',0);
	
	$cline+=5;
    $nub++;
	
	$pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"กำไรสุทธิ");
    $pdf->MultiCell(68,5,$buss_name,1,'L',0);
	
	$pdf->SetXY(73,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(15,5,$buss_name,1,'C',0);
	
	$pdf->SetXY(88,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(25,5,$buss_name,1,'C',0);

	$pdf->SetXY(113,$cline);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(25,5,$buss_name,1,'C',0);

	$pdf->SetXY(138,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(25,5,$buss_name,1,'C',0);

	$pdf->SetXY(163,$cline);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(25,5,$buss_name,1,'C',0);
	
	// หา กำไรสุทธิของ งบกำไรขาดทุน
	if($dr2 > $cr2)
	{
		$pdf->SetXY(188,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(213,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($dr2-$cr2,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	elseif($cr2 > $dr2)
	{
		$pdf->SetXY(188,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($cr2-$dr2,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(213,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	else
	{
		$pdf->SetXY(188,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format(0,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(213,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format(0,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	
	// หา กำไรสุทธิของ งบดุล
	if($dr3 > $cr3)
	{
		$pdf->SetXY(238,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(263,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($dr3-$cr3,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	elseif($cr2 > $dr2)
	{
		$pdf->SetXY(238,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($cr3-$dr3,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(263,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	else
	{
		$pdf->SetXY(238,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format(0,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(263,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format(0,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	
	$cline+=5;
    $nub++;
	
	$pdf->SetXY(5,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(68,5,$buss_name,1,'C',0);

	$pdf->SetXY(73,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(15,5,$buss_name,1,'C',0);
	
	$pdf->SetXY(88,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(25,5,$buss_name,1,'C',0);

	$pdf->SetXY(113,$cline);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(25,5,$buss_name,1,'C',0);

	$pdf->SetXY(138,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(25,5,$buss_name,1,'C',0);

	$pdf->SetXY(163,$cline);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(25,5,$buss_name,1,'C',0);
	
	// หา ผลรวม งบกำไรขาดทุน
	if($dr2 > $cr2)
	{
		$pdf->SetXY(188,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($dr2,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(213,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($cr2+($dr2-$cr2),2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	elseif($cr2 > $dr2)
	{
		$pdf->SetXY(188,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($dr2+($cr2-$dr2),2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(213,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($cr2,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	else
	{
		$pdf->SetXY(188,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($dr2,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(213,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($cr2,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	
	// หา ผลรวม งบดุล
	if($dr3 > $cr3)
	{
		$pdf->SetXY(238,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($dr3,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(263,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($cr3+($dr3-$cr3),2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	elseif($cr2 > $dr2)
	{
		$pdf->SetXY(238,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($dr3+($cr3-$dr3),2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(263,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($cr3,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}
	else
	{
		$pdf->SetXY(238,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($dr3,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
		
		$pdf->SetXY(263,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($cr3,2));
		$pdf->MultiCell(25,5,$buss_name,1,'R',0);
	}

$pdf->Output();
?>