<?php
include("../../config/config.php");
$db1="ta_mortgage_datastore";

$datepicker = pg_escape_string($_GET["datepicker"]); // วันที่
$condate = pg_escape_string($_GET["condate"]); // รายงานตาม
$channel = pg_escape_string($_GET["channel"]); // ช่องทางการชำระ
$selectTime = pg_escape_string($_GET["selectTime"]); // ช่วงเวลา
$selectMonth = pg_escape_string($_GET["selectMonth"]); // เดือน
$selectYearMonth = pg_escape_string($_GET["selectYearMonth"]); // ปี ของเดือน
$selectYear = pg_escape_string($_GET["selectYear"]); // ปี

function chk_null($data,$type="text")
{
	$re_data = "";
	if($data=="")
	{
		$re_data = "<ไม่มีข้อมูล>";
	}
	else
	{
		if($type=="money")
		{
			$re_data = number_format($data,2,".",",");
		}
		else
		{
			$re_data = $data;
		}
	}
	
	return $re_data;
}

if($condate==1){
	$txtcondate="วันที่ทำรายการ";
}else if($condate==2){
	$txtcondate="วันที่รับชำระ";
}

if($selectTime == "d")
{
	$txtshowdate = "ประจำวันที่ $datepicker";
	if($condate=="1"){
		$conditiondate="b.\"doerStamp\"::date = '$datepicker'";
	}else{
		$conditiondate="a.\"receiveDate\"::date = '$datepicker'";
	}
}
elseif($selectTime == "m")
{
	$txtshowdate = "ประจำเดือน $selectMonth ปี $selectYearMonth";
	if($condate=="1"){
		$conditiondate="substr(b.\"doerStamp\"::character varying,6,2) = '$selectMonth' and substr(b.\"doerStamp\"::character varying,1,4) = '$selectYearMonth'";
	}else{
		$conditiondate="substr(a.\"receiveDate\"::character varying,6,2) = '$selectMonth' and substr(a.\"receiveDate\"::character varying,1,4) = '$selectYearMonth'";
	}
}
elseif($selectTime == "y")
{
	$txtshowdate = "ประจำปี $selectYear";
	if($condate=="1"){
		$conditiondate="substr(b.\"doerStamp\"::character varying,1,4) = '$selectYear'";
	}else{
		$conditiondate="substr(a.\"receiveDate\"::character varying,1,4) = '$selectYear'";
	}
}
else
{
	if($condate=="1"){
		$conditiondate="b.\"doerStamp\"::date = '$datepicker'";
	}else{
		$conditiondate="a.\"receiveDate\"::date = '$datepicker'";
	}
}

$channel = $_GET['channel'];
if($channel=="") {
	$txtchan="ทุกช่องทาง";
	$conditionchannel="";
}else{
	//นำไปค้นหาในตาราง BankInt
	$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$channel'");
	$ressearch=pg_fetch_array($qrysearch);
	list($BAccount,$BName)=$ressearch;
	$txtchan="$BAccount-$BName";
	$conditionchannel="and a.\"byChannel\"='$channel'";
}

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
//สถานะการสั่งพิมพ์ว่าพิมพ์ทั้งหมดหรือเฉพาะบางส่วน   แบ่งเป็น all = พิมพ์ทั้งหมด /  normal = ส่วนปกติด้านบน / cancel = ส่วนยกเลิกด้านล่าง
$typeprint = $_GET["typeprint"];
$doerIDget = $_GET["doerID"];
	IF($typeprint == 'all'){ 
		$normal = 'yes';
		$cancel = 'yes';
	}ELSE IF($typeprint == 'normal'){
		$normal = 'yes';
		$cancel = 'no';
		if($doerIDget != ""){
			$personprint = "and b.\"doerID\"='$doerIDget' ";
		}
	}ELSE IF($typeprint == 'cancel'){
		$normal = 'no';
		$cancel = 'yes';
	}


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

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->SetAutoPageBreak(true,0);

//หากมีการสั่งพิมพ์ส่วนปกติ
IF($normal == 'yes'){
$pdf->AddPage();
$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


$pdf->SetXY(40,25);
$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
$pdf->MultiCell(290,4,$buss_name2,0,'L',0);



$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
$pdf->MultiCell(28,4,$buss_name,0,'C',0);

$pdf->SetXY(58,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
$pdf->MultiCell(28,4,$buss_name,0,'C',0);

$pdf->SetXY(86,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(116,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
$pdf->MultiCell(45,4,$buss_name,0,'L',0);

$pdf->SetXY(161,32);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
$pdf->MultiCell(105,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(266,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',10);
$cline = 39;
$nub = 1;
$person = 1;
//$chk=0; //สำหรับตรวจสอบว่าแต่ละใบเสร็จมีกี่รายการ

//ดึงข้อมูลขึ้นมาแสดงตามเงื่อนไขที่เลือก
$qryreceipt=pg_query("select a.\"receiptID\" as receiptid,a.\"receiveDate\",b.\"doerStamp\",b.\"contractID\",b.\"doerID\",a.\"debtAmt\"- a.\"whtAmt\" as \"debtAmt\", b.\"cusFullname\", 
	concat(a.\"tpDesc\"|| ' ' || a.\"tpFullDesc\" || ' ' || a.\"typePayRefValue\") as detail,
	a.\"byChannel\",b.\"doerID\" as doerid, a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"debtID\" as debtid,a.\"nameChannel\" ,\"byChannelRef\"
	from thcap_v_receipt_otherpay a 
	left join thcap_v_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
	where $conditiondate $conditionchannel $personprint order by doerid,receiptid,debtid,a.\"byChannel\"");
$i=0;
$sum_amt = 0;
$sum_all = 0;
$sum_alltotal=0;
$old_doerID="";
$old_receiptID="";
while($result=pg_fetch_array($qryreceipt)){
    $doerID=$result["doerid"];
	$contractID=$result["contractID"];
	$receiptID=$result["receiptid"];
	$receiveDate=$result["receiveDate"];
	$doerStamp=$result["doerStamp"]; if($doerStamp=="") $doerStamp="-";
	$receiveAmount=$result["debtAmt"];
	$cusname=$result["cusFullname"];
	$byChannel=$result["byChannel"];
	$detail2=$result["detail"];
	$typePayID=$result["typePayID"];
	$typePayRef=$result["typePayRefValue"];
	
	if($cusname==""){
		$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
		$resname=pg_fetch_array($qryname);
		$cusname=$resname["thcap_fullname"];
	}
	
	//หาว่ามี - อยู่กี่ตัว ถ้า 2 ตัวเป็นเลขที่สัญญาให้แสดงเต็ม แต่ถ้ามี 1 ตัวให้ตัดตัวหลังออก
	$c = strlen($detail2);
	$l = 0;
	for ($pp = 0; $pp < $c; ++$pp){
		if ($detail2[$pp]=="-") ++$l;
	}						
	if($l==2){
		$detail=$detail2;
	}else{
		list($detail,$detail2)=explode("-",$detail2);
	}
	
	list($typePayRefValue,$typePayRef2)=explode("-",$typePayRef);
	$tpDesc=$result["tpDesc"];
	
	if($detail == "") // ถ้าคำนวนรายละเอียดไม่เจอ
	{
		$detail = $tpDesc;
	}
	
	if($typePayID == "1003"){
		$qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"::text='$typePayRefValue' ");
		while($res_due=pg_fetch_array($qry_due)){
			$ptDate=trim($res_due["ptDate"]); // 
			$due = "($ptDate)";
		}
	}else{
		$due = "";
	}
	
	$txtchannel=$result["nameChannel"];
	if($byChannel=="" || $byChannel=="0"){$txtchannel2="ไม่ระบุ";}
	
	
	
	$pdf->SetFont('AngsanaNew','B',10);
	//กรณีที่ไม่ใช่ใบเสร็จเดียวกัน
    if(($receiptID != $old_receiptID) && $nub != 1){ 
		$sum_amt = 0;
    }
	
	
    
	//show only new page

    if($nub == 35){ //กรณีวนครบ 35 แถว ให้ขึ้นแถวใหม่
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(116,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);

		$pdf->SetXY(161,32);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
		$pdf->MultiCell(105,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(266,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
	
	}
	
	//แสดงช่องทาง โดยจะแสดงก็ต่อเมื่อใบเสร็จคนละใบ
	if($receiptID!=$old_receiptID and $old_receiptID!=""){ 
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,$cline-3);
		$buss_name=iconv('UTF-8','windows-874',"--------------------------------------------------------------------------------------------------------");
		$pdf->MultiCell(286,4,$buss_name,0,'R',0);
		$nub++;
		
		//แสดงช่องทางการชำระทั้งหมด
		$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$old_receiptID' order by \"ChannelAmt\" DESC");
		$sumamt=0;
		while($resstar=pg_fetch_array($qryredstar)){
			$chan=$resstar["byChannel"];
			$amt=$resstar["ChannelAmt"];
			$byChannelRef=$resstar["byChannelRef"];
			
			$qry_hold = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
			list($chkhold) = pg_fetch_array($qry_hold);
									
			$qry_secur = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
			list($chksecur) = pg_fetch_array($qry_secur);
					
			 if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
				$nub = 1;
				$cline = 39;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,25);
				$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
				$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


				$pdf->SetXY(40,25);
				$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
				$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

				

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(290,4,$buss_name,B,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(30,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(58,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(86,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(116,32);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
				$pdf->MultiCell(45,4,$buss_name,0,'L',0);

				$pdf->SetXY(161,32);
				$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
				$pdf->MultiCell(105,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(266,32);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
				$pdf->MultiCell(25,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,33);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(290,4,$buss_name,B,'C',0);
			}
			
			
			
			if($chan=="999"){
				$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
				$sum_all+=$amt;
				$sum_alltotal+=$amt;
				$sum_amt+=$amt;
			}else{
				//นำไปค้นหาในตาราง BankInt
				$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
				$ressearch=pg_fetch_array($qrysearch);
				list($BAccount,$BName)=$ressearch;
				$txtchannel3="ช่องทาง : $BAccount-$BName";
				
				if($chan==$chkhold || $chan==$chksecur){
					$txtchannel3="ช่องทาง : $BAccount-$BName เลขที่ $byChannelRef";
				}
			}
			
			
			
			if($chan != $oldchan){
				$chantxt[] = $chan;			
			}
			
			
			
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(200,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$txtchannel3");
			$pdf->MultiCell(80,4,$buss_name,0,'L',0);
					
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(271,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($amt,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
					
			$cline += 5;
			$nub+=1;
					
			$sumamt=$sumamt+$amt;
			
		}
		if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
			$nub = 1;
			$cline = 39;
			$pdf->AddPage();
				
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',15);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,25);
			$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
			$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


			$pdf->SetXY(40,25);
			$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
			$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

			

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(30,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(58,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(86,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(116,32);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
			$pdf->MultiCell(45,4,$buss_name,0,'L',0);

			$pdf->SetXY(161,32);
			$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
			$pdf->MultiCell(105,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(266,32);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
			$pdf->MultiCell(25,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,4,$buss_name,B,'C',0);
		}
		
		//รวมตอนท้ายของช่องทาง
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,$cline-3);
		$buss_name=iconv('UTF-8','windows-874',"--------------------------------------------------------------------------------------------------------");
		$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมเงินในใบเสร็จ ".number_format($sumamt,2));
		$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
		$pdf->SetXY(193,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(97,4,$buss_name,B,'R',0);
			
		$cline += 5;
		$nub+=1;
		
		
				
	} //end กรณีแสดงช่องทาง
	
	if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
		$nub = 1;
		$cline = 39;
		$pdf->AddPage();
				
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(116,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);

		$pdf->SetXY(161,32);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
		$pdf->MultiCell(105,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(266,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
	}
	
	//กรณีผู้ทำรายการคนละคน ให้สรุปเงินของผู้นั้นก่อนแสดงข้อมูลของคนใหม่
    if(($doerID != $old_doerID) && $nub != 1){ 
	
		if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,$cline);
					$buss_name=iconv('UTF-8','windows-874','********************************************************************************************');
					$pdf->MultiCell(286,4,$buss_name,0,'R',0);
							
					$cline += 3;
					$nub+=1;
	
	
		//รวมจำนวนเงินของแต่ละช่องทาง ท้ายสุดของคนๆนั้น
		$qrychansum=pg_query("select a.\"receiptID\" as receiptid from thcap_v_receipt_otherpay a left join thcap_v_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
								where $conditiondate $conditionchannel AND b.\"doerID\" = '$old_doerID' ");
		$ar = 1;
		while($rechanel = pg_fetch_array($qrychansum)){
			$re_chan = $rechanel["receiptid"];
			$qrychanname = pg_query("SELECT \"byChannel\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan'");
			while($rechanname=pg_fetch_array($qrychanname)){
				if($ar == 1){
					$chansum[]=$rechanname["byChannel"];					
				}else{		
					if(!in_array($rechanname["byChannel"],$chansum)){	
						$chansum[]=$rechanname["byChannel"];
					}
				}
				$ar++;						
			}					
		}

		for($yy=0;$yy<sizeof($chansum);$yy++){
		
				if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
		
				$chansumall = 0;
					
				$qrychansum=pg_query("select distinct(a.\"receiptID\") as receiptid from thcap_v_receipt_otherpay a left join thcap_v_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
								where $conditiondate $conditionchannel AND b.\"doerID\" = '$old_doerID' ");
				while($rechanel = pg_fetch_array($qrychansum)){
					$chanamtsum = 0;
					$re_chan = $rechanel["receiptid"];
					
				
				
					$qrychanname = pg_query("SELECT \"ChannelAmt\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan' AND \"byChannel\" = '$chansum[$yy]'");
					list($chanamtsum)=pg_fetch_array($qrychanname);	
					$row_chanamt = pg_num_rows($qrychanname);
					if($row_chanamt > 0){
							 $chansumall += $chanamtsum;
					}
					
				}
				
				
					if($chansum[$yy]=="999"){
						$txtchannelsum="รวมช่องทาง : ภาษีหัก ณ ที่จ่าย";
					}else{
						//นำไปค้นหาในตาราง BankInt
						$qrysearchsum=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chansum[$yy]'");
						list($BAccount,$BName)=pg_fetch_array($qrysearchsum);					
						$txtchannelsum="รวมช่องทาง : $BAccount-$BName";
					}

					
					$pdf->SetFont('AngsanaNew','',10);
					$pdf->SetXY(225,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$txtchannelsum");
					$pdf->MultiCell(50,4,$buss_name,0,'L',0);
							
					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(271,$cline);
					$buss_name=iconv('UTF-8','windows-874',number_format($chansumall,2));
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
							
					$cline += 5;
					$nub+=1;
									
		}
		unset($chansum);
		
			if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,$cline);
					$buss_name=iconv('UTF-8','windows-874','********************************************************************************************');
					$pdf->MultiCell(286,4,$buss_name,0,'R',0);
							
					$cline += 3;
					$nub+=1;
		
				if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
		
	
		$pdf->SetFont('AngsanaNew','B',12);
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"รวมเงินทุกใบเสร็จ ".number_format($sum_all,2));
        $pdf->MultiCell(286,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(5,$cline+1);
        $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(286,4,$buss_name,0,'R',0);
        
        $sum_all = 0;

		$cline += 5;
		$nub+=1;	
				
    }
    
    //กรณีไม่ใช่ชื่อคนเดียวกัน ให้แสดงชื่อผู้รับเงินคนต่อไป
	if($doerID != $old_doerID and $nub != 35){
        $query1=pg_query("select * from \"Vfuser\" WHERE \"username\"='$doerID'");
		if($resvc1=pg_fetch_array($query1)){
			$fullname = $resvc1['fullname'];
			$id_user = $resvc1['id_user'];
		}
		
		if($person > 1){ //กรณีวนครบ 35 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
		}

		
		
		
		
		$pdf->SetFont('AngsanaNew','B',12);
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"ผู้รับเงิน $fullname ($id_user)");
        $pdf->MultiCell(100,4,$buss_name,0,'L',0);
        
		$nub+=1;
		$cline += 5;
		$person++;

    }
    
	$sum_amt+=$receiveAmount;
	$sum_all+=$receiveAmount;
	$sum_alltotal+=$receiveAmount;

//แสดงข้อมูล
	if($receiptID==$old_receiptID){
		$receiptID2="";
	}else{
		$receiptID2=$receiptID;
	}
	
	if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
	
	
	if($receiptID==$old_receiptID){
		if($old_typePayID==$typePayID and $old_detail==$detail and $old_due==$due){			
			$typetype="";
		}else{
			$typetype="$typePayID - $detail $due";
		}
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(116,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);
		
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(161,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$typetype");
		$pdf->MultiCell(85,4,$buss_name,0,'L',0);

		$pdf->SetXY(243,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$txtchannel2");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(271,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($receiveAmount,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	}else{
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$receiptID2");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$receiveDate");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$doerStamp");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$contractID");
		$pdf->MultiCell(35,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(116,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$cusname");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);
		
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(161,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$typePayID - $detail $due");
		$pdf->MultiCell(85,4,$buss_name,0,'L',0);

		
		$pdf->SetXY(243,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$txtchannel2");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(271,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($receiveAmount,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		
	}
	
    $cline += 5;
    $nub+=1;
	  
    $old_doerID=$doerID;
	$old_receiptID=$receiptID;
	$typePayID2=$typePayID;
	
	$old_typePayID=$typePayID;
	$old_detail=$detail;
	$old_due=$due;
} //end while 

if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}


//กรณีแสดงช่องทาง ในตอนท้าย
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,$cline-3);
$buss_name=iconv('UTF-8','windows-874',"--------------------------------------------------------------------------------------------------------");
$pdf->MultiCell(286,4,$buss_name,0,'R',0);
		
//แสดงช่องทางการชำระทั้งหมด
$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID' order by \"ChannelAmt\" DESC");
$sumamt=0;
while($resstar=pg_fetch_array($qryredstar)){
	$chan=$resstar["byChannel"];
	$amt=$resstar["ChannelAmt"];
	$byChannelRef=$resstar["byChannelRef"];
			
	$qry_hold = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
	list($chkhold) = pg_fetch_array($qry_hold);
									
	$qry_secur = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
	list($chksecur) = pg_fetch_array($qry_secur);
					
	if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
		$nub = 1;
		$cline = 39;
		$pdf->AddPage();
				
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(116,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);

		$pdf->SetXY(161,32);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
		$pdf->MultiCell(105,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(266,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
	}
			
	if($chan=="999"){
		$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
		$sum_all+=$amt;
		$sum_alltotal+=$amt;
	}else{
	//นำไปค้นหาในตาราง BankInt
		$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
		$ressearch=pg_fetch_array($qrysearch);
		list($BAccount,$BName)=$ressearch;
		$txtchannel3="ช่องทาง : $BAccount-$BName";
		
		if($chan==$chkhold || $chan==$chksecur){
			$txtchannel3="ช่องทาง : $BAccount-$BName เลขที่ $byChannelRef";
		}
	}
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(200,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$txtchannel3");
	$pdf->MultiCell(80,4,$buss_name,0,'L',0);
					
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(271,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($amt,2));
	$pdf->MultiCell(20,4,$buss_name,0,'R',0);
					
	$cline += 5;
	$nub+=1;
					
	$sumamt=$sumamt+$amt;
}
if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
	$nub = 1;
	$cline = 39;
	$pdf->AddPage();
				
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(290,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
	$pdf->MultiCell(290,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,25);
	$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
	$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


	$pdf->SetXY(40,25);
	$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
	$pdf->MultiCell(290,4,$buss_name2,0,'L',0);
	

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(290,4,$buss_name,0,'R',0);

	$pdf->SetXY(5,26);
	$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(290,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(30,32);
	$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
	$pdf->MultiCell(28,4,$buss_name,0,'C',0);

	$pdf->SetXY(58,32);
	$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
	$pdf->MultiCell(28,4,$buss_name,0,'C',0);

	$pdf->SetXY(86,32);
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	$pdf->SetXY(116,32);
	$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
	$pdf->MultiCell(45,4,$buss_name,0,'L',0);

	$pdf->SetXY(161,32);
	$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
	$pdf->MultiCell(105,4,$buss_name,0,'L',0);

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(266,32);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
	$pdf->MultiCell(25,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,33);
	$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(290,4,$buss_name,0,'C',0);
}
		
//รวมตอนท้ายของช่องทาง
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,$cline-3);
$buss_name=iconv('UTF-8','windows-874',"--------------------------------------------------------------------------------------------------------");
$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงินในใบเสร็จ ".number_format($sumamt,2));
$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
$pdf->SetXY(193,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(97,4,$buss_name,B,'R',0);
			
$cline += 5;
$nub+=1;	
//end กรณีแสดงช่องทาง

		if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,$cline);
					$buss_name=iconv('UTF-8','windows-874','********************************************************************************************');
					$pdf->MultiCell(286,4,$buss_name,0,'R',0);
							
					$cline += 3;
					$nub+=1;


		//รวมจำนวนเงินของแต่ละช่องทาง ท้ายสุดของคนๆนั้น
		$qrychansum=pg_query("select a.\"receiptID\" as receiptid from thcap_v_receipt_otherpay a left join thcap_v_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
								where $conditiondate $conditionchannel AND b.\"doerID\" = '$old_doerID' ");
		$ar = 1;
		while($rechanel = pg_fetch_array($qrychansum)){
			$re_chan = $rechanel["receiptid"];
			$qrychanname = pg_query("SELECT \"byChannel\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan'");
			while($rechanname=pg_fetch_array($qrychanname)){
				if($ar == 1){
					$chansum[]=$rechanname["byChannel"];					
				}else{		
					if(!in_array($rechanname["byChannel"],$chansum)){	
						$chansum[]=$rechanname["byChannel"];
					}
				}
				$ar++;						
			}					
		}

		for($yy=0;$yy<sizeof($chansum);$yy++){
		
				if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
		
				$chansumall = 0;
					
				$qrychansum=pg_query("select distinct(a.\"receiptID\") as receiptid from thcap_v_receipt_otherpay a left join thcap_v_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
								where $conditiondate $conditionchannel AND b.\"doerID\" = '$old_doerID' ");
				while($rechanel = pg_fetch_array($qrychansum)){
					$chanamtsum = 0;
					$re_chan = $rechanel["receiptid"];
					
				
				
					$qrychanname = pg_query("SELECT \"ChannelAmt\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan' AND \"byChannel\" = '$chansum[$yy]'");
					list($chanamtsum)=pg_fetch_array($qrychanname);	
					$row_chanamt = pg_num_rows($qrychanname);
					if($row_chanamt > 0){
							 $chansumall += $chanamtsum;
					}
					
				}
				
				
					if($chansum[$yy]=="999"){
						$txtchannelsum="รวมช่องทาง : ภาษีหัก ณ ที่จ่าย";
					}else{
						//นำไปค้นหาในตาราง BankInt
						$qrysearchsum=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chansum[$yy]'");
						list($BAccount,$BName)=pg_fetch_array($qrysearchsum);					
						$txtchannelsum="รวมช่องทาง : $BAccount-$BName";
					}

					
					$pdf->SetFont('AngsanaNew','',10);
					$pdf->SetXY(225,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$txtchannelsum");
					$pdf->MultiCell(50,4,$buss_name,0,'L',0);
							
					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(271,$cline);
					$buss_name=iconv('UTF-8','windows-874',number_format($chansumall,2));
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
							
					$cline += 5;
					$nub+=1;
									
		}
		unset($chansum);



	if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,$cline);
					$buss_name=iconv('UTF-8','windows-874','********************************************************************************************');
					$pdf->MultiCell(286,4,$buss_name,0,'R',0);
							
					$cline += 3;
					$nub+=1;




	if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}

					
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงินทุกใบเสร็จ ".number_format($sum_all,2));
$pdf->MultiCell(286,4,$buss_name,0,'R',0);
        
$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);

$cline += 5;
$nub+=1;


//รวมจำนวนเงินของแต่ละช่องทาง ท้ายสุดของคนๆนั้น
		$qrychansum=pg_query("select a.\"receiptID\" as receiptid from thcap_v_receipt_otherpay a left join thcap_v_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
								where $conditiondate $conditionchannel $personprint");
		$ar = 1;
		while($rechanel = pg_fetch_array($qrychansum)){
			$re_chan = $rechanel["receiptid"];
			$qrychanname = pg_query("SELECT \"byChannel\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan'");
			while($rechanname=pg_fetch_array($qrychanname)){
				if($ar == 1){
					$chansum[]=$rechanname["byChannel"];					
				}else{		
					if(!in_array($rechanname["byChannel"],$chansum)){	
						$chansum[]=$rechanname["byChannel"];
					}
				}
				$ar++;						
			}					
		}

		for($yy=0;$yy<sizeof($chansum);$yy++){
		
				if($nub == 35){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
		
				$chansumall = 0;
					
				$qrychansum=pg_query("select distinct(a.\"receiptID\") as receiptid from thcap_v_receipt_otherpay a left join thcap_v_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
								where $conditiondate $conditionchannel $personprint");
				while($rechanel = pg_fetch_array($qrychansum)){
					$chanamtsum = 0;
					$re_chan = $rechanel["receiptid"];
					
				
				
					$qrychanname = pg_query("SELECT \"ChannelAmt\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan' AND \"byChannel\" = '$chansum[$yy]'");
					list($chanamtsum)=pg_fetch_array($qrychanname);	
					$row_chanamt = pg_num_rows($qrychanname);
					if($row_chanamt > 0){
							 $chansumall += $chanamtsum;
					}
					
				}
				
				
					if($chansum[$yy]=="999"){
						$txtchannelsum="รวมทั้งหมดช่องทาง : ภาษีหัก ณ ที่จ่าย";
					}else{
						//นำไปค้นหาในตาราง BankInt
						$qrysearchsum=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chansum[$yy]'");
						list($BAccount,$BName)=pg_fetch_array($qrysearchsum);					
						$txtchannelsum="รวมทั้งหมดช่องทาง : $BAccount-$BName";
					}

					
					$pdf->SetFont('AngsanaNew','',10);
					$pdf->SetXY(215,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$txtchannelsum");
					$pdf->MultiCell(60,4,$buss_name,0,'L',0);
							
					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(271,$cline);
					$buss_name=iconv('UTF-8','windows-874',number_format($chansumall,2));
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
							
					$cline += 5;
					$nub+=1;
									
		}
		unset($chansum);



    if($nub == 35){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(116,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);

		$pdf->SetXY(161,32);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
		$pdf->MultiCell(105,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(266,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
    }

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงินทั้งหมด  ".number_format($sum_alltotal,2));
$pdf->MultiCell(286,4,$buss_name,0,'R',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+2);
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);

}//ปิดการพิมพ์ส่วนปกติ

//หากสั่งให้พิมพ์ส่วนของยกเลิก
if($cancel == 'yes'){
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//###################ในส่วนของยกเลิกใบเสร็จ#####################################

//เพื่อไม่ให้สับสนให้กำหนดตัวแปรใหม่
if($condate==1){
	$txtcondate="วันที่ทำรายการ";
	$conditiondate="date(c.\"approveDate\")='$datepicker'";
}else if($condate==2){
	$txtcondate="วันที่รับชำระ";
	$conditiondate="date(a.\"receiveDate\")='$datepicker'";
}

if($channel=="") {
	$txtchannel="ทุกช่องทาง";
	$conditionchannel="";
}else{
	//นำไปค้นหาในตาราง BankInt
	$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$channel'");
	$ressearch=pg_fetch_array($qrysearch);
	list($BAccount,$BName)=$ressearch;
	$txtchannel="$BAccount-$BName";
	$conditionchannel="and a.\"byChannel\"='$channel'";
}

$nub = 1;
$cline = 39;
$person = 1;
$pdf->AddPage();

$pdf->SetFont('AngsanaNew','B',25);
$pdf->SetXY(50,10);
$title=iconv('UTF-8','windows-874',"ยกเลิก");
$pdf->MultiCell(18,8,$title,B,'C',0);
        
$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานใบเสร็จรวมที่ถูกยกเลิกประจำวัน");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

$pdf->SetXY(40,25);
$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
$pdf->MultiCell(290,4,$buss_name2,0,'L',0);



$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
$pdf->MultiCell(28,4,$buss_name,0,'C',0);

$pdf->SetXY(58,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
$pdf->MultiCell(28,4,$buss_name,0,'C',0);

$pdf->SetXY(86,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(116,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
$pdf->MultiCell(45,4,$buss_name,0,'L',0);

$pdf->SetXY(161,32);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
$pdf->MultiCell(105,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(266,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub = 1;

//ยุบมาให้ใช้อันเดียว
$qryreceipt=pg_query("select a.\"receiptID\" as receiptid,a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\", (a.\"debtAmt\" - a.\"whtAmt\") as receiveamount,a.\"byChannel\",b.\"doerID\" as doerid,
a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\" as \"requestUser\",f.\"fullname\" as \"approveUser\",c.\"result\"
	from thcap_v_receipt_otherpay_cancel a 
	left join thcap_v_receipt_details_cancel b on a.\"receiptID\"=b.\"receiptID\" 
	left join thcap_temp_receipt_cancel c on a.\"receiptID\"=c.\"receiptID\" 
	left join thcap_temp_receipt_channel d on a.\"receiptID\"=d.\"receiptID\"
	left join \"Vfuser\" e on c.\"requestUser\"=e.\"id_user\"
	left join \"Vfuser\" f on c.\"approveUser\"=f.\"id_user\"
	where $conditiondate $conditionchannel and c.\"approveStatus\"='1' and d.\"byChannel\"<>'999'
	group by a.\"receiptID\",a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\",a.\"whtAmt\",a.\"debtAmt\",a.\"byChannel\",b.\"doerID\",
a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\",f.\"fullname\",c.\"result\" order by doerid,receiptid");
$numreceipt=pg_num_rows($qryreceipt);

$i=0;
$sum_amt = 0;
$sum_all = 0;
$sum_alltotal=0;
$old_doerID="";
$old_receiptID="";

$old_requestUser="";
$old_approveUser="";
$old_result="";
if($numreceipt>0){
	while($resultss=pg_fetch_array($qryreceipt)){
		$doerID=$resultss["doerid"];
		$contractID=$resultss["contractID"];
		$receiptID=$resultss["receiptid"];
		$receiveDate=$resultss["receiveDate"];
		$approveDate=$resultss["approveDate"];
		$doerStamp=substr($approveDate,0,19);
		$receiveAmount=$resultss["receiveamount"];
		$cusname=$resultss["cusFullname"];
		$byChannel=$resultss["byChannel"];
		$typePayID=$resultss["typePayID"];
		$typePayRef=$resultss["typePayRefValue"];
		$detail2=$resultss["tpDesc"].$resultss["tpFullDesc"]." ".$resultss["typePayRefValue"];
		
		$requestUser=$resultss["requestUser"];
		$approveUser=$resultss["approveUser"];
		$result=$resultss["result"];
		
		if($cusname==""){
			$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
			$resname=pg_fetch_array($qryname);
			$cusname=$resname["thcap_fullname"];
		}
		
		//หาว่ามี - อยู่กี่ตัว ถ้า 2 ตัวเป็นเลขที่สัญญาให้แสดงเต็ม แต่ถ้ามี 1 ตัวให้ตัดตัวหลังออก
		$c = strlen($detail2);
		$l = 0;
		for ($pp = 0; $pp < $c; ++$pp){
			if ($detail2[$pp]=="-") ++$l;
		}						
		if($l==2){
			$detail=$detail2;
		}else{
			list($detail,$detail2)=explode("-",$detail2);
		}
		
		list($typePayRefValue,$typePayRef2)=explode("-",$typePayRef);
		$tpDesc=$result["tpDesc"];
		
		if($detail == "") // ถ้าคำนวนรายละเอียดไม่เจอ
		{
			$detail = $tpDesc;
		}
		
		if($typePayID == "1003"){
			$qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"::text='$typePayRefValue' ");
			while($res_due=pg_fetch_array($qry_due)){
				$ptDate=trim($res_due["ptDate"]); // 
				$due = "($ptDate)";
			}
		}else{
			$due = "";
		}
		
		$txtchannel=$result["nameChannel"];
		if($byChannel=="" || $byChannel=="0"){$txtchannel2="ไม่ระบุ";}
		
		$pdf->SetFont('AngsanaNew','B',10);
		//กรณีที่ไม่ใช่ใบเสร็จเดียวกัน
		if(($receiptID != $old_receiptID) && $nub != 1){ 
			$sum_amt = 0;
		}
		
		
		
		//show only new page

		if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
			$nub = 1;
			$cline = 39;
			$pdf->AddPage();
			
			$pdf->SetFont('AngsanaNew','B',25);
			$pdf->SetXY(50,10);
			$title=iconv('UTF-8','windows-874',"ยกเลิก");
			$pdf->MultiCell(18,8,$title,B,'C',0);
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',15);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานใบเสร็จรวมที่ถูกยกเลิกประจำวัน");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,25);
			$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
			$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


			$pdf->SetXY(40,25);
			$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
			$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

			

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(30,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(58,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(86,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(116,32);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
			$pdf->MultiCell(45,4,$buss_name,0,'L',0);

			$pdf->SetXY(161,32);
			$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
			$pdf->MultiCell(105,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(266,32);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
			$pdf->MultiCell(25,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);
		
		}
		
		//กรณีแสดงช่องทาง
		if($receiptID!=$old_receiptID and $old_receiptID!=""){ //กรณีไม่ใช่ใบเสร็จเดียวกันให้สรุปก่อนขึ้นใบเสร็จใบใหม่
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,$cline-3);
			$buss_name=iconv('UTF-8','windows-874',"--------------------------------------------------------------------------------------------------------");
			$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
			//แสดงช่องทางการชำระทั้งหมด
			$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$old_receiptID' order by \"ChannelAmt\" DESC");
			$sumamt=0;
			while($resstar=pg_fetch_array($qryredstar)){
				$chan=$resstar["byChannel"];
				$amt=$resstar["ChannelAmt"];
				$byChannelRef=$resstar["byChannelRef"];
			
				$qry_hold = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
				list($chkhold) = pg_fetch_array($qry_hold);
									
				$qry_secur = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
				list($chksecur) = pg_fetch_array($qry_secur);
						
				if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',25);
					$pdf->SetXY(50,10);
					$title=iconv('UTF-8','windows-874',"ยกเลิก");
					$pdf->MultiCell(18,8,$title,B,'C',0);
					
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานใบเสร็จรวมที่ถูกยกเลิกประจำวัน");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
				
				
				if($chan=="999"){
					$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
					$sum_all+=$amt;
					$sum_alltotal+=$amt;
				}else{
					//นำไปค้นหาในตาราง BankInt
					$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
					$ressearch=pg_fetch_array($qrysearch);
					list($BAccount,$BName)=$ressearch;
					$txtchannel3="ช่องทาง : $BAccount-$BName";
					
					if($chan==$chkhold || $chan==$chksecur){
						$txtchannel3="ช่องทาง : $BAccount-$BName เลขที่ $byChannelRef";
					}
				}
				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(200,$cline);
				$buss_name=iconv('UTF-8','windows-874',"$txtchannel3");
				$pdf->MultiCell(80,4,$buss_name,0,'L',0);
						
				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(271,$cline);
				$buss_name=iconv('UTF-8','windows-874',number_format($amt,2));
				$pdf->MultiCell(20,4,$buss_name,0,'R',0);
						
				$cline += 5;
				$nub+=1;
						
				$sumamt=$sumamt+$amt;
			}
			
			$sum_all+=$sumamt;
			$sum_alltotal+=$sumamt;
			
			if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
				$nub = 1;
				$cline = 39;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',25);
				$pdf->SetXY(50,10);
				$title=iconv('UTF-8','windows-874',"ยกเลิก");
				$pdf->MultiCell(18,8,$title,B,'C',0);
					
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานใบเสร็จรวมที่ถูกยกเลิกประจำวัน");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,25);
				$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
				$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


				$pdf->SetXY(40,25);
				$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
				$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

				

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(30,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(58,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(86,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(116,32);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
				$pdf->MultiCell(45,4,$buss_name,0,'L',0);

				$pdf->SetXY(161,32);
				$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
				$pdf->MultiCell(105,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(266,32);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
				$pdf->MultiCell(25,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,33);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);
			}
			
			//หาว่าใครเป็นผู้ขอยกเลิก ผู้อนุมัติ และหมายเหตุที่ขอยกเลิก
			$qryuser=pg_query("select b.\"fullname\" as \"requestUser\",c.\"fullname\" as \"approveUser\",a.\"result\" from \"thcap_temp_receipt_cancel\" a 
			left join \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
			left join \"Vfuser\" c on a.\"approveUser\"=c.\"id_user\"
			where \"receiptID\"='$old_receiptID' and \"approveStatus\"='1'");
			if($resuser=pg_fetch_array($qryuser)){
				$requestUser=$resuser["requestUser"];
				$approveUser=$resuser["approveUser"];
				$result=str_replace("\r\n"," ",str_replace("�","",chk_null($resuser['result'])));
			}
			
			//รวมตอนท้ายของช่องทาง
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,$cline-3);
			$buss_name=iconv('UTF-8','windows-874',"--------------------------------------------------------------------------------------------------------");
			$pdf->MultiCell(286,4,$buss_name,0,'R',0);
				
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รวมเงินในใบเสร็จ ".number_format($sumamt,2));
			$pdf->MultiCell(286,4,$buss_name,0,'R',0);
				
			$pdf->SetXY(193,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(97,4,$buss_name,B,'R',0);
				
			$cline += 6;
			$nub+=1;
			
			if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
				$nub = 1;
				$cline = 39;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',25);
				$pdf->SetXY(50,10);
				$title=iconv('UTF-8','windows-874',"ยกเลิก");
				$pdf->MultiCell(18,8,$title,B,'C',0);
									
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,25);
				$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
				$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

				$pdf->SetXY(40,25);
				$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
				$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

				

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(30,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(58,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(86,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(116,32);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
				$pdf->MultiCell(45,4,$buss_name,0,'L',0);

				$pdf->SetXY(161,32);
				$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
				$pdf->MultiCell(105,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(266,32);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
				$pdf->MultiCell(25,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,33);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(290,4,$buss_name,B,'C',0);
			}
			
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(192,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ผู้ขอยกเลิก  : $requestUser");
			$pdf->MultiCell(90,5,$buss_name,0,'L',0);
					
			$cline += 5;
			$nub+=1;
	
			if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
				$nub = 1;
				$cline = 39;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',25);
				$pdf->SetXY(50,10);
				$title=iconv('UTF-8','windows-874',"ยกเลิก");
				$pdf->MultiCell(18,8,$title,B,'C',0);
									
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,25);
				$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
				$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

				$pdf->SetXY(40,25);
				$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
				$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

				

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(30,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(58,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(86,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(116,32);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
				$pdf->MultiCell(45,4,$buss_name,0,'L',0);

				$pdf->SetXY(161,32);
				$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
				$pdf->MultiCell(105,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(266,32);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
				$pdf->MultiCell(25,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,33);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(290,4,$buss_name,B,'C',0);
			}
	
			$pdf->SetXY(192,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติ  : $approveUser");
			$pdf->MultiCell(90,5,$buss_name,0,'L',0);
					
			$cline += 5;
			$nub+=1;
	
			if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
				$nub = 1;
				$cline = 39;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',25);
				$pdf->SetXY(50,10);
				$title=iconv('UTF-8','windows-874',"ยกเลิก");
				$pdf->MultiCell(18,8,$title,B,'C',0);
									
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,25);
				$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
				$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

				$pdf->SetXY(40,25);
				$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
				$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

				

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(30,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(58,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(86,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(116,32);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
				$pdf->MultiCell(45,4,$buss_name,0,'L',0);

				$pdf->SetXY(161,32);
				$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
				$pdf->MultiCell(105,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(266,32);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
				$pdf->MultiCell(25,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,33);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);
			}
	
			$pdf->SetXY(192,$cline);
			$buss_name=iconv('UTF-8','windows-874',"หมายเหตุที่ขอยกเลิก  : $result");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
					
			$cline += 8;
			$nub+=1;	
		} //end กรณีแสดงช่องทาง
		
		
		//กรณีที่ไม่ใช่ชื่อคนที่ 1 ให้รวมเงิน
		if(($doerID != $old_doerID) && $nub != 1){ 
		
			if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
				$nub = 1;
				$cline = 39;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',25);
				$pdf->SetXY(50,10);
				$title=iconv('UTF-8','windows-874',"ยกเลิก");
				$pdf->MultiCell(18,8,$title,B,'C',0);
						
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,25);
				$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
				$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


				$pdf->SetXY(40,25);
				$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
				$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

				

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(30,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(58,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(86,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(116,32);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
				$pdf->MultiCell(45,4,$buss_name,0,'L',0);

				$pdf->SetXY(161,32);
				$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
				$pdf->MultiCell(105,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(266,32);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
				$pdf->MultiCell(25,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,33);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);
			}

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874','********************************************************************************************');
			$pdf->MultiCell(286,4,$buss_name,0,'R',0);
					
			$cline += 3;
			$nub+=1;
		
		
		
		
		//รวมจำนวนเงินของแต่ละช่องทาง ท้ายสุดของคนๆนั้น
		$qrychansum=pg_query("select a.\"receiptID\" as receiptid,a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\", (a.\"debtAmt\" - a.\"whtAmt\") as receiveamount,a.\"byChannel\",b.\"doerID\" as doerid,
		a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\" as \"requestUser\",f.\"fullname\" as \"approveUser\"
		from thcap_v_receipt_otherpay_cancel a 
		left join thcap_v_receipt_details_cancel b on a.\"receiptID\"=b.\"receiptID\" 
		left join thcap_temp_receipt_cancel c on a.\"receiptID\"=c.\"receiptID\" 
		left join thcap_temp_receipt_channel d on a.\"receiptID\"=d.\"receiptID\"
		left join \"Vfuser\" e on c.\"requestUser\"=e.\"id_user\"
		left join \"Vfuser\" f on c.\"approveUser\"=f.\"id_user\"
		where $conditiondate $conditionchannel AND b.\"doerID\" = '$old_doerID' and c.\"approveStatus\"='1' and d.\"byChannel\"<>'999'
		group by a.\"receiptID\",a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\",a.\"whtAmt\",a.\"debtAmt\",a.\"byChannel\",b.\"doerID\",
		a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\",f.\"fullname\" order by doerid,receiptid ");
		$ar = 1;
		while($rechanel = pg_fetch_array($qrychansum)){
			$re_chan = $rechanel["receiptid"];
			$qrychanname = pg_query("SELECT \"byChannel\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan'");
			while($rechanname=pg_fetch_array($qrychanname)){
				if($ar == 1){
					$chansum[]=$rechanname["byChannel"];					
				}else{		
					if(!in_array($rechanname["byChannel"],$chansum)){	
						$chansum[]=$rechanname["byChannel"];
					}
				}
				$ar++;						
			}					
		}

		for($yy=0;$yy<sizeof($chansum);$yy++){
		
			if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
				$nub = 1;
				$cline = 39;
				$pdf->AddPage();
				
				$pdf->SetFont('AngsanaNew','B',25);
				$pdf->SetXY(50,10);
				$title=iconv('UTF-8','windows-874',"ยกเลิก");
				$pdf->MultiCell(18,8,$title,B,'C',0);
						
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
				$pdf->MultiCell(290,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',15);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,25);
				$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
				$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


				$pdf->SetXY(40,25);
				$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
				$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

				

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,25);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
				$pdf->MultiCell(290,4,$buss_name,0,'R',0);

				$pdf->SetXY(5,26);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY(5,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
				$pdf->MultiCell(25,4,$buss_name,0,'C',0);

				$pdf->SetXY(30,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(58,32);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(86,32);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(116,32);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
				$pdf->MultiCell(45,4,$buss_name,0,'L',0);

				$pdf->SetXY(161,32);
				$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
				$pdf->MultiCell(105,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','B',12);
				$pdf->SetXY(266,32);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
				$pdf->MultiCell(25,4,$buss_name,0,'R',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,33);
				$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
				$pdf->MultiCell(290,4,$buss_name,0,'C',0);
			}
		
			$chansumall = 0;
					
			$qrychansum=pg_query("select a.\"receiptID\" as receiptid,a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\", (a.\"debtAmt\" - a.\"whtAmt\") as receiveamount,a.\"byChannel\",b.\"doerID\" as doerid,
			a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\" as \"requestUser\",f.\"fullname\" as \"approveUser\"
				from thcap_v_receipt_otherpay_cancel a 
				left join thcap_v_receipt_details_cancel b on a.\"receiptID\"=b.\"receiptID\" 
				left join thcap_temp_receipt_cancel c on a.\"receiptID\"=c.\"receiptID\" 
				left join thcap_temp_receipt_channel d on a.\"receiptID\"=d.\"receiptID\"
				left join \"Vfuser\" e on c.\"requestUser\"=e.\"id_user\"
				left join \"Vfuser\" f on c.\"approveUser\"=f.\"id_user\"
				where $conditiondate $conditionchannel AND b.\"doerID\" = '$old_doerID' and c.\"approveStatus\"='1' and d.\"byChannel\"<>'999'
				group by a.\"receiptID\",a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\",a.\"whtAmt\",a.\"debtAmt\",a.\"byChannel\",b.\"doerID\",
			a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\",f.\"fullname\"	order by doerid,receiptid ");
			while($rechanel = pg_fetch_array($qrychansum)){
				$chanamtsum = 0;
				$re_chan = $rechanel["receiptid"];
				
				$qrychanname = pg_query("SELECT \"ChannelAmt\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan' AND \"byChannel\" = '$chansum[$yy]'");
				list($chanamtsum)=pg_fetch_array($qrychanname);	
				$row_chanamt = pg_num_rows($qrychanname);
				if($row_chanamt > 0){
						 $chansumall += $chanamtsum;
				}
				
			}
					
			if($chansum[$yy]=="999"){
				$txtchannelsum="รวมช่องทาง : ภาษีหัก ณ ที่จ่าย";
			}else{
				//นำไปค้นหาในตาราง BankInt
				$qrysearchsum=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chansum[$yy]'");
				list($BAccount,$BName)=pg_fetch_array($qrysearchsum);					
				$txtchannelsum="รวมช่องทาง : $BAccount-$BName";
			}

					
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(225,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$txtchannelsum");
			$pdf->MultiCell(50,4,$buss_name,0,'L',0);
					
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(271,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($chansumall,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
					
			$cline += 5;
			$nub+=1;
									
		}
		unset($chansum);
		
		if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',25);
					$pdf->SetXY(50,10);
					$title=iconv('UTF-8','windows-874',"ยกเลิก");
					$pdf->MultiCell(18,8,$title,B,'C',0);
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,$cline);
					$buss_name=iconv('UTF-8','windows-874','********************************************************************************************');
					$pdf->MultiCell(286,4,$buss_name,0,'R',0);
							
					$cline += 3;
					$nub+=1;
		
		
		
		if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
			$nub = 1;
			$cline = 39;
			$pdf->AddPage();
			
			$pdf->SetFont('AngsanaNew','B',25);
			$pdf->SetXY(50,10);
			$title=iconv('UTF-8','windows-874',"ยกเลิก");
			$pdf->MultiCell(18,8,$title,B,'C',0);
					
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',15);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานใบเสร็จรวมที่ถูกยกเลิกประจำวัน");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,25);
			$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
			$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


			$pdf->SetXY(40,25);
			$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
			$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

			

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(30,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(58,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(86,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(116,32);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
			$pdf->MultiCell(45,4,$buss_name,0,'L',0);

			$pdf->SetXY(161,32);
			$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
			$pdf->MultiCell(105,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(266,32);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
			$pdf->MultiCell(25,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);
		}
		
		
		
		
		
		
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รวมเงินทุกใบเสร็จ ".number_format($sum_all,2));
			$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
			$sum_all = 0;
			
			$cline += 5;
			$nub+=1;		
		}
		
		if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',25);
					$pdf->SetXY(50,10);
					$title=iconv('UTF-8','windows-874',"ยกเลิก");
					$pdf->MultiCell(18,8,$title,B,'C',0);
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
		
		//กรณีไม่ใช่ชื่อคนเดียวกัน ให้แสดงชื่อผู้รับเงินคนต่อไป
		if($doerID != $old_doerID and $nub != 30){
			$query1=pg_query("select * from \"Vfuser\" WHERE \"username\"='$doerID'");
			if($resvc1=pg_fetch_array($query1)){
				$fullname = $resvc1['fullname'];
				$id_user = $resvc1['id_user'];
			}
			if($person > 1){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',25);
					$pdf->SetXY(50,10);
					$title=iconv('UTF-8','windows-874',"ยกเลิก");
					$pdf->MultiCell(18,8,$title,B,'C',0);
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
			
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ผู้รับเงิน $fullname ($id_user)");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
			$nub+=1;
			$cline += 5;
			$person++;

		}
		
		
		if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',25);
					$pdf->SetXY(50,10);
					$title=iconv('UTF-8','windows-874',"ยกเลิก");
					$pdf->MultiCell(18,8,$title,B,'C',0);
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
				
		
		$sum_amt+=$receiveAmount;
		
	//แสดงข้อมูล
		if($receiptID==$old_receiptID){ //กรณีเลขที่ใบเสร็จเดียวกันให้แสดงแค่ครั้งเดียว
			$receiptID2="";
		}else{
			$receiptID2=$receiptID;
		}
		
		if($receiptID==$old_receiptID){ //กรณีเลขที่ใบเสร็จเดียวกัน
			if($old_typePayID==$typePayID and $old_detail==$detail and $old_due==$due){			
				$typetype="";
			}else{
				$typetype="$typePayID - $detail $due";
			}
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(30,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(58,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(86,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(116,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(45,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(161,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$typetype");
			$pdf->MultiCell(85,4,$buss_name,0,'L',0);

			$pdf->SetXY(243,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$txtchannel2");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(271,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($receiveAmount,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		}else{ //ถ้าไม่ใช่ใบเสร็จเดียวกันให้แสดงข้อมูลแบบสมบูรณ์
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$receiptID2");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(30,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$receiveDate");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(58,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$doerStamp");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(86,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$contractID");
			$pdf->MultiCell(35,4,$buss_name,0,'L',0);
			
			$pdf->SetXY(116,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$cusname");
			$pdf->MultiCell(45,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(161,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$typePayID - $detail $due");
			$pdf->MultiCell(85,4,$buss_name,0,'L',0);

			
			$pdf->SetXY(243,$cline);
			$buss_name=iconv('UTF-8','windows-874',"$txtchannel2");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(271,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($receiveAmount,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
		}
		
		$cline += 5;
		$nub+=1;
		  
		$old_doerID=$doerID;
		$old_receiptID=$receiptID;
		$typePayID2=$typePayID;
		
		$old_typePayID=$typePayID;
		$old_detail=$detail;
		$old_due=$due;
		
		unset($requestUser);
		unset($approveUser);
		unset($result);
	} //end while 

	//กรณีแสดงช่องทาง ในตอนท้าย
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,$cline-3);
	$buss_name=iconv('UTF-8','windows-874',"--------------------------------------------------------------------------------------------------------");
	$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
	//แสดงช่องทางการชำระทั้งหมด
	$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID' order by \"ChannelAmt\" DESC");
	$sumamt=0;
	while($resstar=pg_fetch_array($qryredstar)){
		$chan=$resstar["byChannel"];
		$amt=$resstar["ChannelAmt"];
		$byChannelRef=$resstar["byChannelRef"];
			
		$qry_hold = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
		list($chkhold) = pg_fetch_array($qry_hold);
									
		$qry_secur = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
		list($chksecur) = pg_fetch_array($qry_secur);
						
		if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
			$nub = 1;
			$cline = 39;
			$pdf->AddPage();
			
			$pdf->SetFont('AngsanaNew','B',25);
			$pdf->SetXY(50,10);
			$title=iconv('UTF-8','windows-874',"ยกเลิก");
			$pdf->MultiCell(18,8,$title,B,'C',0);
					
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',15);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานใบเสร็จรวมที่ถูกยกเลิกประจำวัน");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,25);
			$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
			$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


			$pdf->SetXY(40,25);
			$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
			$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

			

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(30,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(58,32);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(86,32);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(116,32);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
			$pdf->MultiCell(45,4,$buss_name,0,'L',0);

			$pdf->SetXY(161,32);
			$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
			$pdf->MultiCell(105,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(266,32);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
			$pdf->MultiCell(25,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);
		}
				
		if($chan=="999"){
			$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
			$sum_all+=$amt;
			$sum_alltotal+=$amt;
			$sum_amt+=$amt;
		}else{
		//นำไปค้นหาในตาราง BankInt
			$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
			$ressearch=pg_fetch_array($qrysearch);
			list($BAccount,$BName)=$ressearch;
			$txtchannel3="ช่องทาง : $BAccount-$BName";
			
			if($chan==$chkhold || $chan==$chksecur){
				$txtchannel3="ช่องทาง : $BAccount-$BName เลขที่ $byChannelRef";
			}
		}
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(200,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$txtchannel3");
		$pdf->MultiCell(80,4,$buss_name,0,'L',0);
						
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(271,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($amt,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
						
		$cline += 5;
		$nub+=1;
						
		$sumamt=$sumamt+$amt;
	}
	
	$sum_all+=$sumamt;
	$sum_alltotal+=$sumamt;
	
	if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
		$nub = 1;
		$cline = 39;
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',25);
		$pdf->SetXY(50,10);
		$title=iconv('UTF-8','windows-874',"ยกเลิก");
		$pdf->MultiCell(18,8,$title,B,'C',0);
					
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานใบเสร็จรวมที่ถูกยกเลิกประจำวัน");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(116,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);

		$pdf->SetXY(161,32);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
		$pdf->MultiCell(105,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(266,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
	}
			
	//รวมตอนท้ายของช่องทาง
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,$cline-3);
	$buss_name=iconv('UTF-8','windows-874',"--------------------------------------------------------------------------------------------------------");
	$pdf->MultiCell(286,4,$buss_name,0,'R',0);
				
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รวมเงินในใบเสร็จ ".number_format($sumamt,2));
	$pdf->MultiCell(286,4,$buss_name,0,'R',0);
				
	$pdf->SetXY(193,$cline+1);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(97,4,$buss_name,B,'R',0);
				
	$cline += 6;
	$nub+=1;	
	
	//หาว่าใครเป็นผู้ขอยกเลิก ผู้อนุมัติ และหมายเหตุที่ขอยกเลิก
	$qryuser=pg_query("select b.\"fullname\" as \"requestUser\",c.\"fullname\" as \"approveUser\",a.\"result\" from \"thcap_temp_receipt_cancel\" a 
	left join \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
	left join \"Vfuser\" c on a.\"approveUser\"=c.\"id_user\"
	where \"receiptID\"='$receiptID' and \"approveStatus\"='1'");
	if($resuser=pg_fetch_array($qryuser)){
		$requestUser=$resuser["requestUser"];
		$approveUser=$resuser["approveUser"];
		$result=str_replace("\r\n"," ",str_replace("�","",chk_null($resuser['result'])));
	}
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(192,$cline);
	$buss_name=iconv('UTF-8','windows-874',"ผู้ขอยกเลิก  : $requestUser");
	$pdf->MultiCell(90,5,$buss_name,0,'L',0);
			
	$cline += 5;
	$nub+=1;
	
	if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
		$nub = 1;
		$cline = 39;
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',25);
		$pdf->SetXY(50,10);
		$title=iconv('UTF-8','windows-874',"ยกเลิก");
		$pdf->MultiCell(18,8,$title,B,'C',0);
							
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(116,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);

		$pdf->SetXY(161,32);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
		$pdf->MultiCell(105,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(266,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
	}
	
	$pdf->SetXY(192,$cline);
	$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติ  : $approveUser");
	$pdf->MultiCell(90,5,$buss_name,0,'L',0);
			
	$cline += 5;
	$nub+=1;
	
	if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
		$nub = 1;
		$cline = 39;
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',25);
		$pdf->SetXY(50,10);
		$title=iconv('UTF-8','windows-874',"ยกเลิก");
		$pdf->MultiCell(18,8,$title,B,'C',0);
							
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(116,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);

		$pdf->SetXY(161,32);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
		$pdf->MultiCell(105,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(266,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
	}
	
	$pdf->SetXY(192,$cline);
	$buss_name=iconv('UTF-8','windows-874',"หมายเหตุที่ขอยกเลิก  : $result");
	$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
	$cline += 8;
	$nub+=1;
	//end กรณีแสดงช่องทาง
	
	if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
		$nub = 1;
		$cline = 39;
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',25);
		$pdf->SetXY(50,10);
		$title=iconv('UTF-8','windows-874',"ยกเลิก");
		$pdf->MultiCell(18,8,$title,B,'C',0);
							
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(116,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);

		$pdf->SetXY(161,32);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
		$pdf->MultiCell(105,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(266,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
	}

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874','********************************************************************************************');
	$pdf->MultiCell(286,4,$buss_name,0,'R',0);
							
	$cline += 3;
	$nub+=1;
	
	//รวมจำนวนเงินของแต่ละช่องทาง ท้ายสุดของคนๆนั้น
		$qrychansum=pg_query("select a.\"receiptID\" as receiptid,a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\", (a.\"debtAmt\" - a.\"whtAmt\") as receiveamount,a.\"byChannel\",b.\"doerID\" as doerid,
a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\" as \"requestUser\",f.\"fullname\" as \"approveUser\"
	from thcap_v_receipt_otherpay_cancel a 
	left join thcap_v_receipt_details_cancel b on a.\"receiptID\"=b.\"receiptID\" 
	left join thcap_temp_receipt_cancel c on a.\"receiptID\"=c.\"receiptID\" 
	left join thcap_temp_receipt_channel d on a.\"receiptID\"=d.\"receiptID\"
	left join \"Vfuser\" e on c.\"requestUser\"=e.\"id_user\"
	left join \"Vfuser\" f on c.\"approveUser\"=f.\"id_user\"
	where $conditiondate $conditionchannel AND b.\"doerID\" = '$old_doerID' and c.\"approveStatus\"='1' and d.\"byChannel\"<>'999'
	group by a.\"receiptID\",a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\",a.\"whtAmt\",a.\"debtAmt\",a.\"byChannel\",b.\"doerID\",
a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\",f.\"fullname\"	order by doerid,receiptid  ");
		$ar = 1;
		while($rechanel = pg_fetch_array($qrychansum)){
			$re_chan = $rechanel["receiptid"];
			$qrychanname = pg_query("SELECT \"byChannel\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan'");
			while($rechanname=pg_fetch_array($qrychanname)){
				if($ar == 1){
					$chansum[]=$rechanname["byChannel"];					
				}else{		
					if(!in_array($rechanname["byChannel"],$chansum)){	
						$chansum[]=$rechanname["byChannel"];
					}
				}
				$ar++;						
			}					
		}

		for($yy=0;$yy<sizeof($chansum);$yy++){
		
				if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',25);
					$pdf->SetXY(50,10);
					$title=iconv('UTF-8','windows-874',"ยกเลิก");
					$pdf->MultiCell(18,8,$title,B,'C',0);
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
		
				$chansumall = 0;
					
				$qrychansum=pg_query("select a.\"receiptID\" as receiptid,a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\", (a.\"debtAmt\" - a.\"whtAmt\") as receiveamount,a.\"byChannel\",b.\"doerID\" as doerid,
a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\" as \"requestUser\",f.\"fullname\" as \"approveUser\"
	from thcap_v_receipt_otherpay_cancel a 
	left join thcap_v_receipt_details_cancel b on a.\"receiptID\"=b.\"receiptID\" 
	left join thcap_temp_receipt_cancel c on a.\"receiptID\"=c.\"receiptID\" 
	left join thcap_temp_receipt_channel d on a.\"receiptID\"=d.\"receiptID\"
	left join \"Vfuser\" e on c.\"requestUser\"=e.\"id_user\"
	left join \"Vfuser\" f on c.\"approveUser\"=f.\"id_user\"
	where $conditiondate $conditionchannel AND b.\"doerID\" = '$old_doerID' and c.\"approveStatus\"='1' and d.\"byChannel\"<>'999'
	group by a.\"receiptID\",a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\",a.\"whtAmt\",a.\"debtAmt\",a.\"byChannel\",b.\"doerID\",
a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\",f.\"fullname\"	order by doerid,receiptid  ");
				while($rechanel = pg_fetch_array($qrychansum)){
					$chanamtsum = 0;
					$re_chan = $rechanel["receiptid"];
					
				
				
					$qrychanname = pg_query("SELECT \"ChannelAmt\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan' AND \"byChannel\" = '$chansum[$yy]'");
					list($chanamtsum)=pg_fetch_array($qrychanname);	
					$row_chanamt = pg_num_rows($qrychanname);
					if($row_chanamt > 0){
							 $chansumall += $chanamtsum;
					}
					
				}
				
				
					if($chansum[$yy]=="999"){
						$txtchannelsum="รวมช่องทาง : ภาษีหัก ณ ที่จ่าย";
					}else{
						//นำไปค้นหาในตาราง BankInt
						$qrysearchsum=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chansum[$yy]'");
						list($BAccount,$BName)=pg_fetch_array($qrysearchsum);					
						$txtchannelsum="รวมช่องทาง : $BAccount-$BName";
					}

					
					$pdf->SetFont('AngsanaNew','',10);
					$pdf->SetXY(225,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$txtchannelsum");
					$pdf->MultiCell(50,4,$buss_name,0,'L',0);
							
					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(271,$cline);
					$buss_name=iconv('UTF-8','windows-874',number_format($chansumall,2));
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
							
					$cline += 5;
					$nub+=1;
									
		}
		unset($chansum);
		
		if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',25);
					$pdf->SetXY(50,10);
					$title=iconv('UTF-8','windows-874',"ยกเลิก");
					$pdf->MultiCell(18,8,$title,B,'C',0);
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,$cline);
					$buss_name=iconv('UTF-8','windows-874','********************************************************************************************');
					$pdf->MultiCell(286,4,$buss_name,0,'R',0);
							
					$cline += 3;
					$nub+=1;
		
		if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',25);
					$pdf->SetXY(50,10);
					$title=iconv('UTF-8','windows-874',"ยกเลิก");
					$pdf->MultiCell(18,8,$title,B,'C',0);
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
				
				
		

	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รวมเงินทุกใบเสร็จ ".number_format($sum_all,2));
	$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
	$pdf->SetXY(5,$cline+1);
	$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(286,4,$buss_name,0,'C',0);

	$cline += 6;
	$nub+=1;
	
	
	
	
	//รวมจำนวนเงินของแต่ละช่องทาง ท้ายสุดของคนๆนั้น
		$qrychansum=pg_query("select a.\"receiptID\" as receiptid,a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\", (a.\"debtAmt\" - a.\"whtAmt\") as receiveamount,a.\"byChannel\",b.\"doerID\" as doerid,
a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\" as \"requestUser\",f.\"fullname\" as \"approveUser\"
	from thcap_v_receipt_otherpay_cancel a 
	left join thcap_v_receipt_details_cancel b on a.\"receiptID\"=b.\"receiptID\" 
	left join thcap_temp_receipt_cancel c on a.\"receiptID\"=c.\"receiptID\" 
	left join thcap_temp_receipt_channel d on a.\"receiptID\"=d.\"receiptID\"
	left join \"Vfuser\" e on c.\"requestUser\"=e.\"id_user\"
	left join \"Vfuser\" f on c.\"approveUser\"=f.\"id_user\"
	where $conditiondate $conditionchannel and c.\"approveStatus\"='1' and d.\"byChannel\"<>'999'
	group by a.\"receiptID\",a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\",a.\"whtAmt\",a.\"debtAmt\",a.\"byChannel\",b.\"doerID\",
a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\",f.\"fullname\"	order by doerid,receiptid");
		$ar = 1;
		while($rechanel = pg_fetch_array($qrychansum)){
			$re_chan = $rechanel["receiptid"];
			$qrychanname = pg_query("SELECT \"byChannel\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan'");
			while($rechanname=pg_fetch_array($qrychanname)){
				if($ar == 1){
					$chansum[]=$rechanname["byChannel"];					
				}else{		
					if(!in_array($rechanname["byChannel"],$chansum)){	
						$chansum[]=$rechanname["byChannel"];
					}
				}
				$ar++;						
			}					
		}

		for($yy=0;$yy<sizeof($chansum);$yy++){
		
				if($nub == 30){ //กรณีวนครบ 26 แถว ให้ขึ้นแถวใหม่
					$nub = 1;
					$cline = 39;
					$pdf->AddPage();
					
					$pdf->SetFont('AngsanaNew','B',25);
					$pdf->SetXY(50,10);
					$title=iconv('UTF-8','windows-874',"ยกเลิก");
					$pdf->MultiCell(18,8,$title,B,'C',0);
							
					$pdf->SetFont('AngsanaNew','B',18);
					$pdf->SetXY(5,10);
					$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
					$pdf->MultiCell(290,4,$title,0,'C',0);

					$pdf->SetFont('AngsanaNew','',15);
					$pdf->SetXY(5,16);
					$buss_name=iconv('UTF-8','windows-874',"(THCAP) ออกรายงานรับชำระรวม");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetXY(5,25);
					$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
					$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


					$pdf->SetXY(40,25);
					$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
					$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

					

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,25);
					$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
					$pdf->MultiCell(290,4,$buss_name,0,'R',0);

					$pdf->SetXY(5,26);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);

					$pdf->SetFont('AngsanaNew','',14);
					$pdf->SetXY(5,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
					$pdf->MultiCell(25,4,$buss_name,0,'C',0);

					$pdf->SetXY(30,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(58,32);
					$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
					$pdf->MultiCell(28,4,$buss_name,0,'C',0);

					$pdf->SetXY(86,32);
					$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
					$pdf->MultiCell(30,4,$buss_name,0,'C',0);

					$pdf->SetXY(116,32);
					$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);

					$pdf->SetXY(161,32);
					$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
					$pdf->MultiCell(105,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','B',12);
					$pdf->SetXY(266,32);
					$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
					$pdf->MultiCell(25,4,$buss_name,0,'R',0);

					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(5,33);
					$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
					$pdf->MultiCell(290,4,$buss_name,0,'C',0);
				}
		
				$chansumall = 0;
					
				$qrychansum=pg_query("select a.\"receiptID\" as receiptid,a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\", (a.\"debtAmt\" - a.\"whtAmt\") as receiveamount,a.\"byChannel\",b.\"doerID\" as doerid,
				a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\" as \"requestUser\",f.\"fullname\" as \"approveUser\"
					from thcap_v_receipt_otherpay_cancel a 
					left join thcap_v_receipt_details_cancel b on a.\"receiptID\"=b.\"receiptID\" 
					left join thcap_temp_receipt_cancel c on a.\"receiptID\"=c.\"receiptID\" 
					left join thcap_temp_receipt_channel d on a.\"receiptID\"=d.\"receiptID\"
					left join \"Vfuser\" e on c.\"requestUser\"=e.\"id_user\"
					left join \"Vfuser\" f on c.\"approveUser\"=f.\"id_user\"
					where $conditiondate $conditionchannel and c.\"approveStatus\"='1' and d.\"byChannel\"<>'999'
					group by a.\"receiptID\",a.\"receiveDate\",c.\"approveDate\",a.\"contractID\",b.\"cusFullname\",a.\"whtAmt\",a.\"debtAmt\",a.\"byChannel\",b.\"doerID\",
				a.\"typePayID\",a.\"typePayRefValue\",a.\"tpDesc\",a.\"tpFullDesc\",e.\"fullname\",f.\"fullname\"	order by doerid,receiptid ");
				while($rechanel = pg_fetch_array($qrychansum)){
					$chanamtsum = 0;
					$re_chan = $rechanel["receiptid"];
					
				
				
					$qrychanname = pg_query("SELECT \"ChannelAmt\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$re_chan' AND \"byChannel\" = '$chansum[$yy]'");
					list($chanamtsum)=pg_fetch_array($qrychanname);	
					$row_chanamt = pg_num_rows($qrychanname);
					if($row_chanamt > 0){
							 $chansumall += $chanamtsum;
					}
					
				}
				
				
					if($chansum[$yy]=="999"){
						$txtchannelsum="รวมทั้งหมดช่องทาง : ภาษีหัก ณ ที่จ่าย";
					}else{
						//นำไปค้นหาในตาราง BankInt
						$qrysearchsum=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chansum[$yy]'");
						list($BAccount,$BName)=pg_fetch_array($qrysearchsum);					
						$txtchannelsum="รวมทั้งหมดช่องทาง : $BAccount-$BName";
					}

					
					$pdf->SetFont('AngsanaNew','',10);
					$pdf->SetXY(215,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$txtchannelsum");
					$pdf->MultiCell(60,4,$buss_name,0,'L',0);
							
					$pdf->SetFont('AngsanaNew','',12);
					$pdf->SetXY(271,$cline);
					$buss_name=iconv('UTF-8','windows-874',number_format($chansumall,2));
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
							
					$cline += 5;
					$nub+=1;
									
		}
		unset($chansum);
	
	
	
	
	
	
	
	
	

	if($nub == 30){
		$nub = 1;
		$cline = 39;
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',25);
		$pdf->SetXY(50,10);
		$title=iconv('UTF-8','windows-874',"ยกเลิก");
		$pdf->MultiCell(18,8,$title,B,'C',0);
			
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานใบเสร็จรวมที่ถูกยกเลิกประจำวัน");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ตาม$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);


		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"$txtshowdate ช่องทางการชำระเงิน : $txtchan");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(58,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(116,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
		$pdf->MultiCell(45,4,$buss_name,0,'L',0);

		$pdf->SetXY(161,32);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
		$pdf->MultiCell(105,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(266,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
	}
}
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงินทั้งหมด  ".number_format($sum_alltotal,2));
$pdf->MultiCell(286,4,$buss_name,0,'R',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+2);
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);

} //ปิดการพิมพ์ส่วนของยกเลิก

$pdf->Output();
?>