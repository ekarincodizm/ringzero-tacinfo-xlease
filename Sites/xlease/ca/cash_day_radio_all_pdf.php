<?php
session_start();
include("../config/config.php");

$type = pg_escape_string($_GET['type']);
$datepicker = pg_escape_string($_GET['date']);
$nowdate = nowDate();//ดึง วันที่จาก server

//------------------- PDF -------------------//
require('../thaipdfclass.php');

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
$buss_name=iconv('UTF-8','windows-874',"รายงานค่าวิทยุทั้งหมด");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่ใบเสร็จ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(50,32);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(80,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(110,32);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(125,32);
$buss_name=iconv('UTF-8','windows-874',"TypePay");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(145,32);
$buss_name=iconv('UTF-8','windows-874',"TName");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(160,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,32);
$buss_name=iconv('UTF-8','windows-874',"PayType");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub = 1;

if($type == "all" || $type == 1){

$query=pg_query("select * from \"FOtherpay\" WHERE \"O_PRNDATE\"='$datepicker' AND (\"O_Type\"='165' OR \"O_Type\"='307') AND \"Cancel\"='FALSE' ORDER BY \"PayType\",\"O_DATE\" ASC");
//$query=pg_query("select * from \"FOtherpay\" WHERE \"O_PRNDATE\"='$datepicker' AND \"O_Type\"='109' AND \"PayType\"='OC' ORDER BY \"PayType\",\"O_DATE\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $IDNO = $resvc['IDNO'];
    $O_DATE = $resvc['O_DATE'];
    $O_RECEIPT = $resvc['O_RECEIPT'];
    $O_MONEY = $resvc['O_MONEY'];
    $O_Type = $resvc['O_Type'];
    $O_PRNDATE = $resvc['O_PRNDATE'];
    $PayType = $resvc['PayType'];
    
    $TName="";
    $query_type=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$O_Type'");
    if($res_type=pg_fetch_array($query_type)){
        $TName = $res_type['TName'];
    }

    $full_name="";
    $C_REGIS="";
    $car_regis="";
    $regis="";
    $query_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO'");
    if($res_VContact=pg_fetch_array($query_VContact)){
        $full_name = $res_VContact['full_name'];
        $asset_type = $res_VContact['asset_type'];
        $C_REGIS = $res_VContact['C_REGIS'];
        $car_regis = $res_VContact['car_regis'];
        if($asset_type == 1){
            $regis = $C_REGIS;
        }else{
            $regis = $car_regis;
        }
    }
	
	if($PayType=="TCQ")
	{
		$query_new=pg_query("select * from public.\"FTACCheque\" WHERE \"COID\"='$IDNO' and \"refreceipt\"='$O_RECEIPT' order by \"auto_id\" ");
		while($result_new=pg_fetch_array($query_new))
		{
				$vtid=$result_new["vtid"];
				$full_name=$result_new["fullname"];
				$regis=$result_new["carregis"];
		}
	}
	
	if($PayType=="TTR")
	{
		$query_new=pg_query("select * from public.\"FTACTran\" WHERE \"COID\"='$IDNO' and \"refreceipt\"='$O_RECEIPT' order by \"auto_id\" ");
		while($result_new=pg_fetch_array($query_new))
		{
				$vtid=$result_new["vtid"];
				$full_name=$result_new["fullname"];
				$regis=$result_new["carregis"];
		}
	}
    
    $sum_amt+=$O_MONEY;
    $sum_amt_all+=$O_MONEY;
    
    if($nub == 46){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
        $pdf->MultiCell(200,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,16);
        $buss_name=iconv('UTF-8','windows-874',"รายงานค่าวิทยุทั้งหมด");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
        $pdf->MultiCell(50,4,$buss_name,0,'L',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,32);
        $buss_name=iconv('UTF-8','windows-874',"วันที่ใบเสร็จ");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(25,32);
        $buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(50,32);
        $buss_name=iconv('UTF-8','windows-874',"IDNO");
        $pdf->MultiCell(30,4,$buss_name,0,'C',0);

        $pdf->SetXY(80,32);
        $buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
        $pdf->MultiCell(50,4,$buss_name,0,'C',0);

        $pdf->SetXY(110,32);
        $buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
        $pdf->MultiCell(15,4,$buss_name,0,'C',0);

        $pdf->SetXY(125,32);
        $buss_name=iconv('UTF-8','windows-874',"TypePay");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(145,32);
        $buss_name=iconv('UTF-8','windows-874',"TName");
        $pdf->MultiCell(15,4,$buss_name,0,'C',0);

        $pdf->SetXY(160,32);
        $buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(180,32);
        $buss_name=iconv('UTF-8','windows-874',"PayType");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }

    $pdf->SetFont('AngsanaNew','',13);
    
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"$O_DATE");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline);
$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(50,$cline);
$buss_name=iconv('UTF-8','windows-874',"$IDNO");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(80,$cline);
$buss_name=iconv('UTF-8','windows-874',"$full_name");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(110,$cline);
$buss_name=iconv('UTF-8','windows-874',"$regis");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(125,$cline);
$buss_name=iconv('UTF-8','windows-874',"$O_Type");
$pdf->MultiCell(10,4,$buss_name,0,'L',0);

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',"$TName");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(165,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(185,$cline);
$buss_name=iconv('UTF-8','windows-874',"$PayType");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);
    
    $cline += 5;
    $nub+=1;
}


if($num_row > 0){
    $pdf->SetFont('AngsanaNew','B',13);
    $pdf->SetXY(100,$cline);
    $buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_amt,2));
    $pdf->MultiCell(85,4,$buss_name,0,'R',0);

    $pdf->SetXY(5,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
    $cline += 6;
    $nub+=1;
}

}//end check type [all,1]

if($type == "all" || $type == 2){

//====================================//
$num_row = 0;
$sum_amt = 0;
$query=pg_query("select * from \"FOtherpay\" WHERE \"O_PRNDATE\"='$datepicker' AND \"O_Type\"='109' AND \"PayType\"<>'OC' ORDER BY \"PayType\",\"O_DATE\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $IDNO = $resvc['IDNO'];
    $O_DATE = $resvc['O_DATE'];
    $O_RECEIPT = $resvc['O_RECEIPT'];
    $O_MONEY = $resvc['O_MONEY'];
    $O_Type = $resvc['O_Type'];
    $O_PRNDATE = $resvc['O_PRNDATE'];
    $PayType = $resvc['PayType'];
    
    $TName="";
    $query_type=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$O_Type'");
    if($res_type=pg_fetch_array($query_type)){
        $TName = $res_type['TName'];
    }

    $full_name="";
    $C_REGIS="";
    $car_regis="";
    $regis="";
    $query_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO'");
    if($res_VContact=pg_fetch_array($query_VContact)){
        $full_name = $res_VContact['full_name'];
        $asset_type = $res_VContact['asset_type'];
        $C_REGIS = $res_VContact['C_REGIS'];
        $car_regis = $res_VContact['car_regis'];
        if($asset_type == 1){
            $regis = $C_REGIS;
        }else{
            $regis = $car_regis;
        }
    }
	
	if($PayType=="TCQ")
	{
		$query_new=pg_query("select * from public.\"FTACCheque\" WHERE \"COID\"='$IDNO' and \"refreceipt\"='$O_RECEIPT' order by \"auto_id\" ");
		while($result_new=pg_fetch_array($query_new))
		{
				$vtid=$result_new["vtid"];
				$full_name=$result_new["fullname"];
				$regis=$result_new["carregis"];
		}
	}
	
	if($PayType=="TTR")
	{
		$query_new=pg_query("select * from public.\"FTACTran\" WHERE \"COID\"='$IDNO' and \"refreceipt\"='$O_RECEIPT' order by \"auto_id\" ");
		while($result_new=pg_fetch_array($query_new))
		{
				$vtid=$result_new["vtid"];
				$full_name=$result_new["fullname"];
				$regis=$result_new["carregis"];
		}
	}
    
    $sum_amt+=$O_MONEY;
    $sum_amt_all+=$O_MONEY;
    
    if($nub == 46){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
        $pdf->MultiCell(200,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,16);
        $buss_name=iconv('UTF-8','windows-874',"รายงานค่าวิทยุทั้งหมด");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
        $pdf->MultiCell(50,4,$buss_name,0,'L',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,32);
        $buss_name=iconv('UTF-8','windows-874',"วันที่ใบเสร็จ");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(25,32);
        $buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(50,32);
        $buss_name=iconv('UTF-8','windows-874',"IDNO");
        $pdf->MultiCell(30,4,$buss_name,0,'C',0);

        $pdf->SetXY(80,32);
        $buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
        $pdf->MultiCell(50,4,$buss_name,0,'C',0);

        $pdf->SetXY(110,32);
        $buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
        $pdf->MultiCell(15,4,$buss_name,0,'C',0);

        $pdf->SetXY(125,32);
        $buss_name=iconv('UTF-8','windows-874',"TypePay");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(145,32);
        $buss_name=iconv('UTF-8','windows-874',"TName");
        $pdf->MultiCell(15,4,$buss_name,0,'C',0);

        $pdf->SetXY(160,32);
        $buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(180,32);
        $buss_name=iconv('UTF-8','windows-874',"PayType");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }

    $pdf->SetFont('AngsanaNew','',13);
    
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"$O_DATE");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline);
$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(50,$cline);
$buss_name=iconv('UTF-8','windows-874',"$IDNO");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(80,$cline);
$buss_name=iconv('UTF-8','windows-874',"$full_name");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(110,$cline);
$buss_name=iconv('UTF-8','windows-874',"$regis");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(125,$cline);
$buss_name=iconv('UTF-8','windows-874',"$O_Type");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(145,$cline);
$buss_name=iconv('UTF-8','windows-874',"$TName");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(160,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(180,$cline);
$buss_name=iconv('UTF-8','windows-874',"$PayType");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);
    
    $cline += 5;
    $nub+=1;
}


if($num_row > 0){
    $pdf->SetFont('AngsanaNew','B',13);
    $pdf->SetXY(100,$cline);
    $buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_amt,2));
    $pdf->MultiCell(80,4,$buss_name,0,'R',0);

    $pdf->SetXY(5,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'C',0);
}

}//end check type [all,2]

$pdf->Output();
?>