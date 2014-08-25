<?php
session_start();
include("../../config/config.php");
$db1="ta_mortgage_datastore";

$datepicker = $_GET['datepicker'];
$condate = $_GET['condate'];
if($condate==1){
	$txtcondate="วันที่ทำรายการ";
	$conditiondate="date(b.\"approveDate\")='$datepicker'";
}else if($condate==2){
	$txtcondate="วันที่รับชำระ";
	$conditiondate="date(a.\"receiveDate\")='$datepicker'";
}

$channel = $_GET['channel'];
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

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

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
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);

$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานรับชำระหนี้อื่นๆ ประจำวันที่ถูกยกเลิก");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name1=iconv('UTF-8','windows-874',"ประจำ$txtcondate ");
$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

$pdf->SetXY(40,25);
$buss_name2=iconv('UTF-8','windows-874',"วันที่ $datepicker ช่องทางการชำระเงิน : ");
$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

$pdf->SetXY(98,25);
$buss_name3=iconv('UTF-8','windows-874',"$txtchannel");
$pdf->MultiCell(290,4,$buss_name3,0,'L',0);

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
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(166,32);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการรับชำระ");
$pdf->MultiCell(75,4,$buss_name,0,'L',0);

$pdf->SetXY(241,32);
$buss_name=iconv('UTF-8','windows-874',"ช่องทาง");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(268,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub = 1;
$qryreceipt=pg_query("SELECT a.\"receiptID\" as receiptid,  a.\"receiveDate\",b.\"approveDate\",a.\"contractID\",c.\"cusFullname\", a.\"debtAmt\" as receiveamount,a.\"byChannel\", a.\"tpDesc\",a.\"tpFullDesc\",a.\"typePayRefValue\",a.\"typePayID\" as typepay
	FROM thcap_v_receipt_otherpay_cancel a
	left join (		select *
					from thcap_temp_receipt_cancel
					where \"approveStatus\" = '1'
			   ) b on a.\"receiptID\"=b.\"receiptID\"
	left join (		select \"receiptID\",\"cusFullname\"
					from \"thcap_v_receipt_details_cancel\"
					GROUP BY \"receiptID\",\"cusFullname\"
			  ) c on a.\"receiptID\"=c.\"receiptID\"
	where $conditiondate $conditionchannel and a.\"contractID\" is not null ");
$i=0;
$sum_amt = 0;
$sum_all = 0;
$old_doerID="";
while($result=pg_fetch_array($qryreceipt)){
    $doerID=$result["doerid"];
	$type=$result["type"];
	$contractID=$result["contractID"];
	$receiptID=$result["receiptid"];
	$receiveDate=$result["receiveDate"];
	$doerStamp=$result["approveDate"]; if($approveDate=="") $approveDate="-";
	$receiveAmount=$result["receiveamount"];
	$cusname=$result["cusFullname"];
	$typePayID=$result["typepay"];
	$typePayRefValue=$result["typePayRefValue"];
	if($type=="1"){
		$detail=$result["tpDesc"].$result["tpFullDesc"]." ".$result["typePayRefValue"];
	}else{
		$detail="ผ่อนชำระตามสัญญากู้";
	}
	
	if($typePayID == "1003"){
		$qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$typePayRefValue' ");
		while($res_due=pg_fetch_array($qry_due)){
			$ptDate=trim($res_due["ptDate"]); // 
			$due = "($ptDate)";
		}
	}else{
		$due = "";
	}
	//ถ้า cusfullname เป็นค่าว่างให้ไปค้นหาชื่อจาก mysql มีโอกาสพบค่าว่างได้เนื่องจากเลขที่ใบเสร็จเก่าอาจยังไม่ได้เก็บชื่อลูกค้าทำให้ไม่พบข้อมูลใน pg
	if($cusname==""){
		$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
		$resname=pg_fetch_array($qryname);
		$cusname=$resname["thcap_fullname"];
	}
	
	$byChannel=$result["byChannel"];
	
	if($byChannel=="" || $byChannel=="0" || $byChannel=="999"){$txtchannel2="ไม่ระบุ";}
	else{
		//นำไปค้นหาในตาราง BankInt
		$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel'");
		$ressearch=pg_fetch_array($qrysearch);
		list($BAccount,$BName)=$ressearch;
		$txtchannel2="$BAccount-$BName";
	}
	
	//show only new page
    if($nub == 27){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
        $pdf->MultiCell(290,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานรับชำระหนี้อื่นๆ ประจำวันที่ถูกยกเลิก");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',15);
		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ประจำ$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"วันที่ $datepicker ช่องทางการชำระเงิน : ");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',15);
		$pdf->SetXY(98,25);
		$buss_name3=iconv('UTF-8','windows-874',"$txtchannel");
		$pdf->MultiCell(290,4,$buss_name3,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(290,4,$buss_name,0,'C',0);

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
		$pdf->MultiCell(40,4,$buss_name,0,'L',0);

		$pdf->SetXY(156,32);
		$buss_name=iconv('UTF-8','windows-874',"ช่องทาง");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(186,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินใบเสร็จ");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',15);
		
        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(290,4,$buss_name,0,'C',0);
	}
	
//show all record
		$pdf->SetFont('AngsanaNew','',12);
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$receiptID");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(30,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$receiveDate");
    $pdf->MultiCell(28,4,$buss_name,0,'C',0);

    $pdf->SetXY(58,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$doerStamp");
    $pdf->MultiCell(28,4,$buss_name,0,'C',0);

    $pdf->SetXY(86,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$contractID");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(116,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$cusname");
    $pdf->MultiCell(50,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(166,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayID - $detail $due");
    $pdf->MultiCell(75,4,$buss_name,0,'L',0);

 	$pdf->SetXY(241,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$txtchannel2");
    $pdf->MultiCell(30,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(271,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($receiveAmount,2));
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);
 
    $cline += 5;
    $nub+=1;
	
    $sum_all+=$receiveAmount;
    $old_doerID=$doerID;
} //end while 

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$cline += 6;
$nub+=1;

    if($nub == 27){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
        $pdf->MultiCell(290,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานรับชำระหนี้อื่นๆ ประจำวันที่ถูกยกเลิก");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',15);
		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ประจำ$txtcondate ");
		$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(40,25);
		$buss_name2=iconv('UTF-8','windows-874',"วันที่ $datepicker ช่องทางการชำระเงิน : ");
		$pdf->MultiCell(290,4,$buss_name2,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',15);
		$pdf->SetXY(98,25);
		$buss_name3=iconv('UTF-8','windows-874',"$txtchannel");
		$pdf->MultiCell(290,4,$buss_name3,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(290,4,$buss_name,0,'C',0);

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
		$pdf->MultiCell(40,4,$buss_name,0,'L',0);

		$pdf->SetXY(156,32);
		$buss_name=iconv('UTF-8','windows-874',"ช่องทาง");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(186,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินใบเสร็จ");
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',15);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(290,4,$buss_name,0,'C',0);
    }

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(190,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงินทั้งหมด ".number_format($sum_all,2));
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->Output();
?>