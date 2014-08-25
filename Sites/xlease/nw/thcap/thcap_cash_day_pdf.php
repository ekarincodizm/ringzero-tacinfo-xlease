<?php
session_start();
include("../../config/config.php");
$db1="ta_mortgage_datastore";

$datepicker = $_GET['datepicker'];
$condate = $_GET['condate'];
if($condate==1){
	$txtcondate="วันที่ทำรายการ";
	$conditiondate="date(a.\"doerStamp\")='$datepicker'";
}else if($condate==2){
	$txtcondate="วันที่รับชำระ";
	$conditiondate="date(c.\"receiveDate\")='$datepicker'";
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
	$conditionchannel="and c.\"byChannel\"='$channel'";
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
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานรับชำระประจำวัน");
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

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(268,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub = 1;

$qryreceipt=pg_query("select b.\"receiptID\",b.\"receiveDate\",a.\"doerStamp\",b.\"contractID\",c.\"ChannelAmt\" as \"receiveAmount\", 
	c.\"byChannel\",a.\"doerID\",a.\"cusFullname\" from thcap_v_receipt_details a
	left join \"thcap_temp_int_201201\" b on a.\"receiptID\"=b.\"receiptID\"
	left join \"thcap_temp_receipt_channel\" c on a.\"receiptID\"=c.\"receiptID\" 
	where $conditiondate $conditionchannel and b.\"contractID\" is not null order by a.\"doerID\",a.\"receiptID\",c.\"byChannel\"");
$i=0;
$sum_amt = 0;
$sum_all = 0;
$sum_alltotal=0;
$old_doerID="";
$old_receiptID="";
$chk=0;
while($result=pg_fetch_array($qryreceipt)){
    $doerID=$result["doerID"];
	$contractID=$result["contractID"];
	$receiptID=$result["receiptID"];
	$receiveDate=$result["receiveDate"];
	$doerStamp=$result["doerStamp"]; if($doerStamp=="") $doerStamp="-";
	$receiveAmount=$result["receiveAmount"];
	$cusname=$result["cusFullname"];
	if($cusname==""){
		$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
		$resname=pg_fetch_array($qryname);
		$cusname=$resname["thcap_fullname"];
	}
	
	$byChannel=$result["byChannel"];
	
	$qrytype=pg_query("SELECT \"tpID\",\"tpDesc\" FROM account.\"thcap_typePay\" where \"tpID\"='1000'");
	list($tpID,$detail)=pg_fetch_array($qrytype);
	
	if($byChannel=="" || $byChannel=="0"){$txtchannel2="ไม่ระบุ";}
	else{
		if($byChannel=="999"){
			$txtchannel2="ภาษีหัก ณ ที่จ่าย";
		}else{
			//นำไปค้นหาในตาราง BankInt
			$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel'");
			$ressearch=pg_fetch_array($qrysearch);
			list($BAccount,$BName)=$ressearch;
			$txtchannel2="$BAccount-$BName";
		}
	}
	$pdf->SetFont('AngsanaNew','B',13);
	if($receiptID==$old_receiptID){
		$chk++;
	}
	//กรณีที่ไม่ใช่ใบเสร็จเดียวกัน
    if(($receiptID != $old_receiptID) && $nub != 1){ //and $nub < 45
        if($chk>0){
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รวมเงินในใบเสร็จ ".number_format($sum_amt,2));
			$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"_______________________________________");
			$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
			if($nub == 27){ 
				$cline += 14;
				$nub=27;
			}else{
				$cline += 7;
				$nub+=1;	
			}	
		}
		$sum_amt = 0;
		$chk=0;
    }

	//กรณีที่ไม่ใช่ชื่อคนที่ 1 ให้รวมเงิน
    if(($doerID != $old_doerID) && ($nub != 1 )){ //and $nub < 45
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ทุกใบเสร็จ ".number_format($sum_all,2));
		$pdf->MultiCell(286,4,$buss_name,0,'R',0);
			
		$pdf->SetXY(5,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(286,4,$buss_name,0,'R',0);
        
        $sum_amt = 0;
		
		if($nub == 27){ 
			$cline += 14;
			$nub=27;
		}else{
			$cline += 7;
			$nub+=1;	
		}		
    }
    
    //กรณีไม่ใช่ชื่อคนเดียวกัน ให้แสดงชื่อผู้รับเงินคนต่อไป
	if($doerID != $old_doerID and $nub != 27){
        $query1=pg_query("select * from \"Vfuser\" WHERE \"username\"='$doerID'");
		if($resvc1=pg_fetch_array($query1)){
			$fullname = $resvc1['fullname'];
			$id_user = $resvc1['id_user'];
		}
		
        $pdf->SetFont('AngsanaNew','B',12);
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"ผู้รับเงิน $fullname ($id_user)");
        $pdf->MultiCell(100,4,$buss_name,0,'L',0);
        
		$nub+=1;
		$cline += 5;

    }
    
	$sum_amt+=$receiveAmount;
	$sum_all+=$receiveAmount;
	$sum_alltotal+=$receiveAmount;
    
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
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานรับชำระประจำวัน");
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

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(268,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินใบเสร็จ");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
    
		if($doerID != $old_doerID){
			$query1=pg_query("select * from \"Vfuser\" WHERE \"username\"='$doerID'");
			if($resvc1=pg_fetch_array($query1)){
				$fullname = $resvc1['fullname'];
				$id_user = $resvc1['id_user'];
			}
			
			$pdf->SetFont('AngsanaNew','B',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ผู้รับเงิน $fullname ($id_user)");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
			$nub+=1;
			$cline += 5;
		}
	}
	
//show all record
	if($receiptID==$old_receiptID){
		if($byChannel=="999"){			
			$typetype="";
		}else{
			$typetype="$tpID - $detail";
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
		$pdf->MultiCell(50,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(166,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$typetype");
		$pdf->MultiCell(75,4,$buss_name,0,'L',0);

		$pdf->SetXY(241,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$txtchannel2");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(271,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($receiveAmount,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	}else{
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
		$buss_name=iconv('UTF-8','windows-874',"$detail");
		$pdf->MultiCell(75,4,$buss_name,0,'L',0);

		$pdf->SetXY(241,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$txtchannel2");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(271,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($receiveAmount,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
	}
 
    $cline += 5;
    $nub+=1;
    
    $old_doerID=$doerID;
	$old_typePayID=$typePayID;
	$old_detail=$detail;
	$old_due=$due;
	$old_receiptID=$receiptID;
} //end while 

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_amt,2));
$pdf->MultiCell(286,4,$buss_name,0,'R',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'R',0);

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
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานรับชำระประจำวัน");
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

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(268,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินใบเสร็จ");
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

$pdf->Output();
?>