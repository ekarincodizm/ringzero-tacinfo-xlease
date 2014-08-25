<?php
session_start();
include("../../config/config.php");
$s=mssql_select_db("Taxiacc") or die("Can't select database");
require('../../thaipdfclass.php');

$id_user = $_GET["id_user"];
$date = $_GET["date"];
$status=$_GET["status"];

$nowdate = Date('d-m-Y');

if($status==1){
	$querysum=pg_query("select sum(\"tacMoney\") as summoney from \"tacReceiveTemp\" where \"makerID\"='$id_user' and \"tacTempDate\"='$date'");
	$txtstatus="รายงานที่รับชำระวันที่";
}else{
	$startDate=$date." 00:00:00";
	$endDate=$date." 23:59:59";
	$querysum=pg_query("select sum(\"tacMoney\") as summoney from \"tacReceiveTemp\" where \"makerID\"='$id_user' and (\"makerStamp\" between '$startDate' and '$endDate')");
	$txtstatus="รายงานเหตุการณ์วันที่";
}
if($ressum=pg_fetch_array($querysum)){
	$summoney=number_format($ressum["summoney"],2);
}

$query_name=pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$id_user'");
if($resname=pg_fetch_array($query_name)){
	$fullname=$id_user."-".$resname["fullname"];
}

$qrysdate=pg_query("select nw_conversiondatetothaitext('$date')");
$selectdate=pg_fetch_result($qrysdate,0);

//------------------- PDF -------------------//
class PDF extends ThaiPDF
{
    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(195,4,$buss_name,0,'R',0);
 
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
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานรับชำระชั่วคราว 1681");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$monthyear=iconv('UTF-8','windows-874',"$txtstatus $selectdate");
$pdf->MultiCell(190,4,$monthyear,0,'C',0);

$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

/*Header of Table*/
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"$fullname  จำนวนเงินที่รับทั้งหมด  $summoney  บาท");
$pdf->MultiCell(100,6,$buss_name,0,'L',0);

$pdf->SetXY(5,35);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);

$pdf->SetXY(30,35);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินรวมในใบเสร็จ");
$pdf->MultiCell(40,6,$buss_name,1,'C',0);

$pdf->SetXY(70,35);
$buss_name=iconv('UTF-8','windows-874',"เลขที่รหัสสัญญาวิทยุ");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);

$pdf->SetXY(100,35);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);

$pdf->SetXY(125,35);
$buss_name=iconv('UTF-8','windows-874',"จำนวนช่วงเดือนที่จ่าย");
$pdf->MultiCell(55,6,$buss_name,1,'C',0);

$pdf->SetXY(180,35);
$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
$pdf->MultiCell(25,6,$buss_name,1,'C',0);


$cline = 41;

if($status==1){
	$query=pg_query("select distinct(\"tacXlsRecID\") as tacxls from \"tacReceiveTemp\" where \"makerID\"='$id_user' and \"tacTempDate\"='$date'");
}else{
	$query=pg_query("select distinct(\"tacXlsRecID\") as tacxls from \"tacReceiveTemp\" where \"makerID\"='$id_user' and (\"makerStamp\" between '$startDate' and '$endDate')");
}

$numrows=pg_num_rows($query);
		
$nub=0;
$sumtacMoney=0;
while($result=pg_fetch_array($query)){
	$tacxls=$result["tacxls"];
	
	if($status==1){
		$querydetail=pg_query("select \"tacID\",\"tacTempDate\",sum(\"tacMoney\") as summoney,min(\"tacMonth\") as monthmin,max(\"tacMonth\") as monthmax
		from \"tacReceiveTemp\" where \"makerID\"='$id_user' and \"tacTempDate\"='$date' and \"tacXlsRecID\"='$tacxls'
		group by \"tacID\",\"tacTempDate\"");
	}else{
		$querydetail=pg_query("select \"tacID\",\"tacTempDate\",sum(\"tacMoney\") as summoney,min(\"tacMonth\") as monthmin,max(\"tacMonth\") as monthmax
		from \"tacReceiveTemp\" where \"makerID\"='$id_user' and (\"makerStamp\" between '$startDate' and '$endDate') and \"tacXlsRecID\"='$tacxls'
		group by \"tacID\",\"tacTempDate\"");
	}
	while($resdetail=pg_fetch_array($querydetail)){
		$tacID=$resdetail["tacID"];
		$tacTempDate1=$resdetail["tacTempDate"];
		$ytemp=substr($tacTempDate1,0,4);
		$ytemp=$ytemp+543;
		$mtemp=substr($tacTempDate1,5,2);
		$dtemp=substr($tacTempDate1,8,2);
		$tacTempDate=$dtemp."-".$mtemp."-".$ytemp;
		
		$tacMoney1=$resdetail["summoney"];
		$tacMoney=number_format($tacMoney1,2);
		$tacMonthMin=$resdetail["monthmin"];
		$ymin=substr($tacMonthMin,0,4);
		$dmin1=substr($tacMonthMin,5,2);
		
		$sql=mssql_query("select CarRegis from TacCusDtl 
		where CusID='$tacID'",$conn); 
		if($res = mssql_fetch_array($sql)){
			$CarRegis=trim(iconv('WINDOWS-874','UTF-8',$res["CarRegis"]));
		}
		
		if($dmin1=="01"){
			$dmin="มกราคม";
		}else if($dmin1=="02"){
			$dmin="กุมภาพันธ์";
		}else if($dmin1=="03"){
			$dmin="มีนาคม";
		}else if($dmin1=="04"){
			$dmin="เมษายน";
		}else if($dmin1=="05"){
			$dmin="พฤษภาคม";
		}else if($dmin1=="06"){
			$dmin="มิถุนายน";
		}else if($dmin1=="07"){
			$dmin="กรกฎาคม";
		}else if($dmin1=="08"){
			$dmin="สิงหาคม";
		}else if($dmin1=="09"){
			$dmin="กันยายน";
		}else if($dmin1=="10"){
			$dmin="ตุลาคม";
		}else if($dmin1=="11"){
			$dmin="พฤศจิกายน";
		}else if($dmin1=="12"){
			$dmin="ธันวาคม";
		}
		$tacMonthMin=$dmin." ".$ymin;
			
		$tacMoneyMax=$resdetail["monthmax"];
		$ymax=substr($tacMoneyMax,0,4);
		$dmax1=substr($tacMoneyMax,5,2);
		if($dmax1=="01"){
			$dmax="มกราคม";
		}else if($dmax1=="02"){
			$dmax="กุมภาพันธ์";
		}else if($dmax1=="03"){
			$dmax="มีนาคม";
		}else if($dmax1=="04"){
			$dmax="เมษายน";
		}else if($dmax1=="05"){
			$dmax="พฤษภาคม";
		}else if($dmax1=="06"){
			$dmax="มิถุนายน";
		}else if($dmax1=="07"){
			$dmax="กรกฎาคม";
		}else if($dmax1=="08"){
			$dmax="สิงหาคม";
		}else if($dmax1=="09"){
			$dmax="กันยายน";
		}else if($dmax1=="10"){
			$dmax="ตุลาคม";
		}else if($dmax1=="11"){
			$dmax="พฤศจิกายน";
		}else if($dmax1=="12"){
			$dmax="ธันวาคม";
		}
		
		$tacMoneyMax=$dmax." ".$ymax;
		
		if($nub > 36){
			$nub = 0;
			$cline = 31;
			$pdf->AddPage();
			
			/*Header of Table*/
			$pdf->SetFont('AngsanaNew','B',14);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
			$pdf->MultiCell(25,6,$buss_name,1,'C',0);

			$pdf->SetXY(30,25);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินรวมในใบเสร็จ");
			$pdf->MultiCell(40,6,$buss_name,1,'C',0);

			$pdf->SetXY(70,25);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่รหัสสัญญาวิทยุ");
			$pdf->MultiCell(30,6,$buss_name,1,'C',0);

			$pdf->SetXY(100,25);
			$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ");
			$pdf->MultiCell(25,6,$buss_name,1,'C',0);

			$pdf->SetXY(125,25);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนช่วงเดือนที่จ่าย");
			$pdf->MultiCell(55,6,$buss_name,1,'C',0);
			
			$pdf->SetXY(180,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
			$pdf->MultiCell(25,6,$buss_name,1,'C',0);
		}
		
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',$tacxls);
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);
		
		$pdf->SetXY(30,$cline);
		$buss_name=iconv('UTF-8','windows-874',$tacMoney);
		$pdf->MultiCell(40,6,$buss_name,1,'R',0);

		$pdf->SetXY(70,$cline);
		$buss_name=iconv('UTF-8','windows-874',$tacID);
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);

		$pdf->SetXY(100,$cline);
		$buss_name=iconv('UTF-8','windows-874',$CarRegis);
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(125,$cline);
		$buss_name=iconv('UTF-8','windows-874',$tacMonthMin."-".$tacMoneyMax);
		$pdf->MultiCell(55,6,$buss_name,1,'C',0);
		
		$pdf->SetXY(180,$cline);
		$buss_name=iconv('UTF-8','windows-874',$tacTempDate);
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);
		
		$sumtacMoney=$sumtacMoney+$tacMoney1;
		$cline = $cline +6;								
		$nub++;
	}	
}
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน");
$pdf->MultiCell(25,6,$buss_name,1,'R',0);

$pdf->SetXY(30,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumtacMoney,2));
$pdf->MultiCell(40,6,$buss_name,1,'R',0);

$pdf->SetXY(70,$cline);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(135,6,$buss_name,1,'R',0);
	
$pdf->Output();
?>