<?php
include("../../config/config.php");
$select_Search=pg_escape_string($_GET["date1"]);
$select_bid=pg_escape_string($_GET["bankint"]);
$month1=pg_escape_string($_GET["month1"]);
$year1=pg_escape_string($_GET["year1"]);
$year2=pg_escape_string($_GET["year2"]);
$datepicker=pg_escape_string($_GET["datepicker"]);
$datefrom=pg_escape_string($_GET["datefrom"]);
$dateto=pg_escape_string($_GET["dateto"]);
$cancel=pg_escape_string($_GET["cancel"]);

$id_user=$_SESSION["av_iduser"];

//เดือน-ปี
$monthText = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');

$queryU=pg_query("select \"fullname\" from \"Vfuser\" where id_user = '$id_user'");
$user=pg_fetch_result($queryU,0);

if($select_Search==2){
	$year = (int)$year2;
	if($year>2012){$year = $year-1;}
	else{$year='2555';}
}
else if($select_Search==1){
		/*$year = (int)$year1;
		$month= (int)$month1;		
		if($year>2012){			
			if($month==12){
				$month=1;
				$year = $year-1;
			}else{
			$month= $month-1;
			}
		}
		else{$year='2555';}*/
	$year = (int)$year1;
	if($year>2012){			
		$dateMY=$year1.'-'.$month1.'-'.'01';
		$dateMY= date ("Y-m-d", strtotime("-1 day", strtotime($dateMY)));		
		list($year,$month,$day)=explode("-",$dateMY);		
	}
	else{
		$year='2555';
	}
	
}
else if($select_Search==0){
	$year = 2012;
	$month= 12;
}
else if($select_Search==4){	
	/*$datepickernew = date ("Y-m-d", strtotime("-1 day", strtotime($datepicker)));
	list($year,$month,$day)=explode("-",$datepickernew);
*/	
}
else if($select_Search==3){		
	$datefromnew = date ("Y-m-d", strtotime("-1 day", strtotime($datefrom)));	
	list($year,$month,$day)=explode("-",$datefromnew);	
}

//หาเลขที่บัญชี
$query_BookID=pg_query("select \"accBookID\",\"accBookType\",\"accBookName\" from account.\"all_accBook\"
					where  \"accBookserial\"='$select_bid'");
$res_BookID =pg_fetch_array($query_BookID); 
list($BookID,$BookType,$BookName)=$res_BookID;

$nowdate = nowDateTime();
$n=0;			
			if($select_bid!="")
			{	
				$n++;
				$sumnub++;
				$condition="";
				if($cancel=="off"){
						$condition=" and (\"abh_correcting_entries_abh_autoid\" IS NULL AND  \"abh_is_correcting_entries\" <> '1' ) " ;
				}	
				 if($select_Search=='1'){  //ตามเดือน ปี
				
					$query="select date(a.\"ledger_stamp\") as \"abh_stamp\",b.\"abh_id\" as \"abh_id\",d.\"accBookName\" as \"accBookName\",b.\"abh_detail\" as \"abh_detail\",a.\"abd_booktype\" as \"abd_bookType\",a.\"abd_amount\" as \"abd_amount\",ledger_balance,a.\"abd_accbookid\" as \"accBookID\"
					from account.\"thcap_ledger_detail\" a  
					left join account.\"all_accBookHead\" b  on a.\"abh_autoid\"=b.\"abh_autoid\"	
					left join account.\"all_accBook\" d  on  a.\"accBookserial\"=d.\"accBookserial\"
					where a.\"is_ledgerstatus\"='0' and a.\"accBookserial\"='$select_bid' and  EXTRACT(YEAR FROM a.\"ledger_stamp\") = '$year1' 
					and  EXTRACT(MONTH FROM a.\"ledger_stamp\") = '$month1'  $condition ";
					
					//วันที่ ยอดมา
					$tiemto=$year1."-".$month1."-01";
					
					$show_month = $monthText[$month1];
					$show_yy = $year1+543;
					$selecttiem=" ประจำเดือน  ".$show_month." ปี  ".$show_yy;
				}
				else if($select_Search=='2'){ //ตามปี				
					$query="select date(a.\"ledger_stamp\") as \"abh_stamp\",b.\"abh_id\" as \"abh_id\",d.\"accBookName\" as \"accBookName\",b.\"abh_detail\" as \"abh_detail\",a.\"abd_booktype\" as \"abd_bookType\",a.\"abd_amount\" as \"abd_amount\",ledger_balance,a.\"abd_accbookid\" as \"accBookID\"
					from account.\"thcap_ledger_detail\" a  
					left join account.\"all_accBookHead\" b  on a.\"abh_autoid\"=b.\"abh_autoid\"	
					left join account.\"all_accBook\" d  on  a.\"accBookserial\"=d.\"accBookserial\"
					where a.\"is_ledgerstatus\"='0' and a.\"accBookserial\"='$select_bid' and  EXTRACT(YEAR FROM a.\"ledger_stamp\") = '$year2' 
					$condition ";
					//ปี ยอดมา					
					$tiemto=$year2."-"."01-01";
					
					$show_yy = $year2+543;
					$selecttiem=" ประจำปี  ".$show_yy;
				}
				else if($select_Search=='0'){
					$query="select date(a.\"ledger_stamp\") as \"abh_stamp\",b.\"abh_id\" as \"abh_id\",d.\"accBookName\" as \"accBookName\",b.\"abh_detail\" as \"abh_detail\",a.\"abd_booktype\" as \"abd_bookType\",a.\"abd_amount\" as \"abd_amount\",ledger_balance
					from account.\"thcap_ledger_detail\" a  
					left join account.\"all_accBookHead\" b  on a.\"abh_autoid\"=b.\"abh_autoid\"	
					left join account.\"all_accBook\" d  on  a.\"accBookserial\"=d.\"accBookserial\"
					where a.\"is_ledgerstatus\"='0' and a.\"accBookserial\"='$select_bid' $condition ";
					//วันที่ ยอดมา
					$tiemto="2013"."-"."01"."-01";
				
				}
				else if($select_Search=='4'){
					list($year4,$month4,$day4)=explode("-",$datepicker);					
					$query="select date(a.\"ledger_stamp\") as \"abh_stamp\",b.\"abh_id\" as \"abh_id\",d.\"accBookName\" as \"accBookName\",b.\"abh_detail\" as \"abh_detail\",a.\"abd_booktype\" as \"abd_bookType\",a.\"abd_amount\" as \"abd_amount\",ledger_balance
					from account.\"thcap_ledger_detail\" a  
					left join account.\"all_accBookHead\" b  on a.\"abh_autoid\"=b.\"abh_autoid\"	
					left join account.\"all_accBook\" d  on  a.\"accBookserial\"=d.\"accBookserial\"
					where a.\"is_ledgerstatus\"='0' and a.\"accBookserial\"='$select_bid' 
					and  EXTRACT(YEAR FROM a.\"ledger_stamp\") = '$year4' and  EXTRACT(DAY FROM a.\"ledger_stamp\") = '$day4'
					and  EXTRACT(MONTH FROM a.\"ledger_stamp\") = '$month4' 
					$condition ";
					//วันที่ ยอดมา
					//$tiemto=$datepickernew;	
					$tiemto=$datepicker;		
				}
				else if($select_Search=='3'){				
					list($year3,$month3,$day3)=explode("-",$datefrom);					
					$query="select date(a.\"ledger_stamp\") as \"abh_stamp\",b.\"abh_id\" as \"abh_id\",d.\"accBookName\" as \"accBookName\",b.\"abh_detail\" as \"abh_detail\",a.\"abd_booktype\" as \"abd_bookType\",a.\"abd_amount\" as \"abd_amount\",ledger_balance
					from account.\"thcap_ledger_detail\" a  
					left join account.\"all_accBookHead\" b  on a.\"abh_autoid\"=b.\"abh_autoid\"	
					left join account.\"all_accBook\" d  on  a.\"accBookserial\"=d.\"accBookserial\"
					where a.\"is_ledgerstatus\"='0' and a.\"accBookserial\"='$select_bid' 
					and  a.\"ledger_stamp\"::date between '$datefrom' and '$dateto'
					$condition ";
					//วันที่ ยอดมา
					$tiemto=$datefrom;					
				}
				$query.=" order by a.\"auto_id\" asc ";				
				$query=pg_query($query);
				
			}

// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,10); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(280,4,$buss_name,0,'R',0);
    }
 
}


$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true,0);

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']. "สำนักงานใหญ่ (0001)");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,16); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ ".$user);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(10,20); 
$buss_name=iconv('UTF-8','windows-874',"สำหรับรายการบัญชี".$selecttiem);
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13); 
$pdf->SetXY(10,25); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่  ".$BookID.' '.$BookName);
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บัญชีแยกประเภท มีรายละเอียด มียอดยกมา");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"$dateTitle");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,25); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"สาขา");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"เอกสาร #");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(70,30); 
$buss_name=iconv('UTF-8','windows-874',"รายการ");
$pdf->MultiCell(120,4,$buss_name,0,'C',0);

$pdf->SetXY(195,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเดบิต");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเครดิต");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
  
$pdf->SetXY(265,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

//เส้นยาว
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

//------ หายอดยกมา
	if($year=='2555'){}
	else{
	if($select_Search=='1'){	//เดือน-ปี 
		$abh_sum= pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
		and EXTRACT(YEAR FROM \"ledger_stamp\") =$year and EXTRACT(MONTH FROM \"ledger_stamp\")= '$month' and \"is_ledgerstatus\"='1'");
	}
	else if($select_Search=='2'){	//ปี 
		$sql_day =  pg_query("SELECT \"gen_numdaysinmonth\"('12',$year)");
		$sql_day =pg_fetch_array($sql_day); 
		list($cday)=$sql_day;
		$abh_sum= pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
		and EXTRACT(YEAR FROM \"ledger_stamp\") =$year and EXTRACT(MONTH FROM \"ledger_stamp\")= '12' and EXTRACT(DAY FROM \"ledger_stamp\")= '$cday' and \"is_ledgerstatus\"='1'");
	}
	else if($select_Search=='0'){//ทุกช่วงเวลา
		$abh_sum= pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
		and EXTRACT(YEAR FROM \"ledger_stamp\") =$year and EXTRACT(MONTH FROM \"ledger_stamp\")= '$month' and \"is_ledgerstatus\"='1'");
	}
	else if($select_Search=='4'){//วันที่
		$abh_sum= pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
		AND auto_id in (SELECT MAX(auto_id)  from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
		AND \"ledger_stamp\"::date <'$datepicker')");
	}
	else if($select_Search=='3'){//ตามช่วง
		$abh_sum= pg_query("SELECT \"ledger_balance\" from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
		and EXTRACT(YEAR FROM \"ledger_stamp\") =$year and EXTRACT(DAY FROM \"ledger_stamp\") =$day 
		and EXTRACT(MONTH FROM \"ledger_stamp\")= '$month' and \"is_ledgerstatus\"='1'");
	}
	$abh_sumb =pg_fetch_array($abh_sum); 
	list($abh_balance)=$abh_sumb;
	$abh_balance1=$abh_balance;
	}

	if($abh_balance<0){
		$abh_balance_replace = str_replace("-", "",$abh_balance);
	}
	else{
		$abh_balance_replace=$abh_balance;
	}

	if($select_Search=='4')
	{// ถ้าเลือกแบบวันที่
		// ตรวจสอบก่อนว่า ปีและเดือนที่สนใจ มีการ gen ข้อมูลอยู่แล้วหรือยัง
		$qry_chk_date_gen = pg_query("select * from account.\"thcap_ledger_detail\" where EXTRACT(YEAR FROM \"ledger_stamp\") = '$focus_year' AND EXTRACT(MONTH FROM \"ledger_stamp\") = '$focus_month' ");
		$row_chk_date_gen = pg_num_rows($qry_chk_date_gen);
		if($row_chk_date_gen == 0){$abh_balance1 = "";}
	}
	
	if($abh_balance1 != "")
	{
		$pdf->SetFont('AngsanaNew','',10);
		
		//บัญชีเลขที่
		$pdf->SetXY(25,37); 
		$buss_name=iconv('UTF-8','windows-874',"บัญชีเลขที่");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(40,37); 
		$buss_name=iconv('UTF-8','windows-874',$accBookID."   ".$accBookName);
		$pdf->MultiCell(100,4,$buss_name,0,'L',0);

		$pdf->SetXY(10,42); 
		$buss_name=iconv('UTF-8','windows-874',$tiemto);
		$pdf->MultiCell(25,4,$buss_name,0,'L',0);

		$pdf->SetXY(60,42); 
		$buss_name=iconv('UTF-8','windows-874',"ยอดยกมา");
		$pdf->MultiCell(15,4,$buss_name,0,'L',0);

		if($abh_balance < 1){  //ถ้า ติดลบ และ 0
			$netcredit=$abh_balance_replace;							
			$pdf->SetXY(230,42);
			$buss_name=iconv('UTF-8','windows-874',number_format($abh_balance_replace,2,'.',','));
			$pdf->MultiCell(30,4,$buss_name,0,'R',0); } 
		else {         //ถ้า มากกว่า 0
			$netdebit=$abh_balance_replace;
			$pdf->SetXY(195,42);
			$buss_name=iconv('UTF-8','windows-874',number_format($abh_balance_replace,2,'.',','));
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		}	


		//ยอดคงเหลือ
		$pdf->SetXY(265,42);
		$buss_name=iconv('UTF-8','windows-874',number_format($abh_balance,2,'.',','));
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);
	}
//----- จบการหายอดยกมา

$pdf->SetFont('AngsanaNew','',10);
$cline = 47;
$i = 1;
$j = 0;  
$n=0;
$abh_sumDebit=0;
$abh_sumCredit=0;
while($resvc=pg_fetch_array($query)){
					$n++;
					$abh_stamp = $resvc["abh_stamp"]; 
					$abh_id = $resvc['abh_id']; 
					$accBookName = $resvc['accBookName']; 
					$abh_detail = $resvc['abh_detail'];
					$abd_bookType = $resvc['abd_bookType']; 
					$abd_amount = $resvc['abd_amount']; 
					$accBookID = $resvc['accBookID']; 
					$ledger_balance = $resvc['ledger_balance'];
					$abh_detail=str_replace("\r\n","",$abh_detail);
					
if($i > 29){ 
    $pdf->AddPage(); 
    $cline = 47; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']. "สำนักงานใหญ่ (0001)");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,16); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ ".$user);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(10,20); 
$buss_name=iconv('UTF-8','windows-874',"สำหรับรายการบัญชี".$selecttiem);
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13); 
$pdf->SetXY(10,25); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่  ".$BookID.' '.$BookName);
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บัญชีแยกประเภท มีรายละเอียด มียอดยกมา");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"$dateTitle");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,25); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"สาขา");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"เอกสาร #");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(70,30); 
$buss_name=iconv('UTF-8','windows-874',"รายการ");
$pdf->MultiCell(120,4,$buss_name,0,'C',0);

$pdf->SetXY(195,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเดบิต");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเครดิต");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
  
$pdf->SetXY(265,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

//เส้นยาว
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

}

// --------------data table
$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(10,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resvc['abh_stamp']);
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',"0001");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(35,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resvc['abh_id']);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',11);
$pdf->SetXY(60,$cline); 
$buss_name=iconv('UTF-8','windows-874',$abh_detail);
$pdf->MultiCell(140,4,$buss_name,0,'L',0);

if($abd_bookType=='1'){
	$netdebit=$netdebit+$abd_amount;
	$abh_sumDebit +=$abd_amount;	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(195,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($abd_amount,2,'.',','));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	$pdf->SetXY(230,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
}
else{
	$netcredit=$netcredit+$abd_amount ;
	$abh_sumCredit +=$abd_amount;	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(195,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	$pdf->SetXY(230,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($abd_amount,2,'.',','));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
}
$abh_sumBalance +=$ledger_balance;
$pdf->SetXY(265,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($ledger_balance,2,'.',','));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

// -----------

$cline+=5; 
$i+=1;       
}

//----- หาข้อมูลยอดยกไป
	if($n == 0 && $abh_balance1 != "")
	{ // ถ้าไม่มีรายการย่อย แต่มียอดยกมา
		$ledger_balance = $abh_balance1; // ให้ยอดยกไป เท่ากับ ยอกยกมา
		
		// หาวันที่สำหรับยอดยกไป
		if($select_Search=='1'){	//เดือนปี 	
			$qry_stamp= pg_query("SELECT max(\"ledger_stamp\")::date from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
			and EXTRACT(YEAR FROM \"ledger_stamp\") =$year1 and EXTRACT(MONTH FROM \"ledger_stamp\")= '$month1' and \"is_ledgerstatus\"='1'");
		}
		else if($select_Search=='2'){	//ปี 
			$sql_day =  pg_query("SELECT \"gen_numdaysinmonth\"('12',$year)");
			$sql_day =pg_fetch_array($sql_day); 
			list($cday)=$sql_day;
			$qry_stamp= pg_query("SELECT max(\"ledger_stamp\")::date from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
			and EXTRACT(YEAR FROM \"ledger_stamp\") =$year2 and \"is_ledgerstatus\"='1'");
		}
		else if($select_Search=='0'){//ทุกช่วงเวลา
			$qry_stamp= pg_query("SELECT max(\"ledger_stamp\")::date from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
			and \"is_ledgerstatus\"='1'");
		}
		else if($select_Search=='4'){//วันที่
			$qry_stamp= pg_query("SELECT max(\"ledger_stamp\")::date from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
			AND auto_id in (SELECT MAX(auto_id)  from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
			AND \"ledger_stamp\"::date <'$datepicker')");
		}
		else if($select_Search=='3'){//ตามช่วง
			$qry_stamp= pg_query("SELECT max(\"ledger_stamp\")::date from account.\"thcap_ledger_detail\" where \"accBookserial\" ='$select_bid'
			and \"ledger_stamp\" >= '$datefrom' and \"ledger_stamp\" <= '$dateto' and \"is_ledgerstatus\"='1'");
		}
		
		$fetch_stamp = pg_fetch_array($qry_stamp);
		list($abh_stamp) = $fetch_stamp;
		if($abh_stamp==""){
			if($select_Search=='2'){
				$abh_stamp=$tiemto=$year2."-"."12-31";
			}else if($select_Search=='1'){
				$abh_stamp=$tiemto=$year1."-".$month1."-31";
			}
		}
	}
//----- จบการหาข้อมูลยอดยกไป

//หาวันที่ ยอดยกไป 
if(($select_Search=='1') or(($select_Search=='2')))
	{
		$timestamp = strtotime($abh_stamp); 
		$m= date('m', $timestamp);
		$timestamp = strtotime($abh_stamp); 
		$y = date('Y', $timestamp);
		$sql_countday =  pg_query("SELECT \"gen_numdaysinmonth\"($m,$y)");
		$sql_countday =pg_fetch_array($sql_countday); 
		list($countday)=$sql_countday;
		$tiemsend=$y."-".$m."-".$countday;
	}
	else if($select_Search=='0'){
		$tiemsend=$abh_stamp;
	}
	else if($select_Search=='4'){
		$tiemsend=$datepicker;}
	else if($select_Search=='3'){
	$tiemsend=$dateto;}
//---------
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',$tiemsend);
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(60,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดยกไป");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);
//ยอดยกไป
if($ledger_balance<0){
	$ledger_balance_replace = str_replace("-", "",$ledger_balance);
}else{
	$ledger_balance_replace = $ledger_balance;
}					
if($ledger_balance > 0) { 
	$netcredit= $netcredit + $ledger_balance_replace;						
	$pdf->SetXY(230,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($ledger_balance_replace,2,'.',','));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
 } else {  
	$netdebit= $netdebit + $ledger_balance_replace;
	$pdf->SetXY(195,$cline); 
	$buss_name=iconv('UTF-8','windows-874',number_format($ledger_balance_replace,2,'.',','));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);						
 }

$cline+=5; 
//

$pdf->SetXY(4,$cline-4.0); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(220,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(70,4,$buss_name,0,'R',0);
//
$pdf->SetXY(60,$cline+1.7); 
$buss_name=iconv('UTF-8','windows-874',"ยอดประจำงวด");
$pdf->MultiCell(55,4,$buss_name,0,'L',0);

$pdf->SetXY(195,$cline+1.5); 
$s_down=iconv('UTF-8','windows-874',number_format($abh_sumDebit,2));
$pdf->MultiCell(30,4,$s_down,0,'R',0);

$pdf->SetXY(230,$cline+1.5); 
$s_down=iconv('UTF-8','windows-874',number_format($abh_sumCredit,2));
$pdf->MultiCell(30,4,$s_down,0,'R',0);

$abh_sumBalance=$abh_sumDebit - $abh_sumCredit;

$pdf->SetXY(265,$cline+1.5); 
$s_down=iconv('UTF-8','windows-874',number_format($abh_sumBalance,2));
$pdf->MultiCell(25,4,$s_down,0,'R',0);

$cline+=8; 

$pdf->SetXY(4,$cline-4.0); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(220,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(70,4,$buss_name,0,'R',0);

//
$pdf->SetXY(60,$cline+1.7); 
$buss_name=iconv('UTF-8','windows-874',"ยอดรวม");
$pdf->MultiCell(55,4,$buss_name,0,'L',0);

$pdf->SetXY(195,$cline+1.5); 
$s_down=iconv('UTF-8','windows-874',number_format($netdebit,2));
$pdf->MultiCell(30,4,$s_down,0,'R',0);

$pdf->SetXY(230,$cline+1.5); 
$s_down=iconv('UTF-8','windows-874',number_format($netcredit,2));
$pdf->MultiCell(30,4,$s_down,0,'R',0);

$pdf->SetXY(4,$cline+5.0); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->Output();
?>