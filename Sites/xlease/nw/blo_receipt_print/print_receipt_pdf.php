<?php
session_start();
include("../../config/config.php");
pg_query("BEGIN WORK");
$status=0;
$type = pg_escape_string($_GET["type"]);
$receiptID=pg_escape_string($_GET["receiptid"]);//receiptID
$reason=pg_escape_string($_GET["reason"]);//reason

$nowdate = nowDateTime();
$id_user=$_SESSION["av_iduser"];
$qry_count=pg_query("select * from \"blo_receipt\" where \"receiptID\" = '$receiptID' ");
$num=pg_num_rows($qry_count);
if($num>0){
	//ผู้พิมพ์
	$queryU=pg_query("select \"fullname\" from \"Vfuser\" where id_user = '$id_user'");
	$user=pg_fetch_result($queryU,0);

	if($type == "0"){
		$typeprint = '{"1","2"}';
	}else{
		$typeprint = "{".$type."}";
	}
	//save blo_receipt_reprint
	$query1 =	"INSERT INTO \"blo_receipt_reprint\" (	
										\"receiptid\",
										\"reprint_reason\",
										\"doerID\",
										\"doerStamp\",
										type_reprint
										) 
							VALUES(
							           '$receiptID',
									   '$reason',
									   '$id_user',
									   '$$nowdate',
									   '$typeprint')";

	if(!$res_inss=pg_query($query1)){
		$status++;
	}


$typeprintin =$type ;
if($typeprintin == '1'){
	$typeprint = '1';
	$chk = 'real';
}else if($typeprintin == '2'){
	$typeprint = '2';
}else{
	$typeprint = '1';
}
//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,8); 
    }
}


$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
//select ข้อมูล
$qry_data=pg_query("select *,\"receiptStamp\"::date as \"receiptdate\" from \"blo_receipt\" where \"receiptID\" = '$receiptID'  ");
if($result=pg_fetch_array($qry_data))
{
		$cusname=$result["CusFullName"];//ชื่อลูกค้า
		$cusadd=$result["CusFullAddress"];//ที่อยู่
		
		$cusadd_arr =explode(" ",$cusadd);
		$irow=0;
		$itxt=0;
		$cusadd_str_1="";
		$cusadd_str_2="";
		while($itxt < count($cusadd_arr)){
			$nostr=strlen($cusadd_str_1)+strlen($cusadd_arr[$itxt]);
			if(($nostr < 220) and ($irow==0)){
				$cusadd_str_1 .=" ".$cusadd_arr[$itxt];
			}
			else{
				$irow=1;
				$cusadd_str_2 .=" ".$cusadd_arr[$itxt];
			}
			$itxt=$itxt+1;
		}
		
		$receiptstamp=$result["receiptdate"];
		$conid=$result["contractID"];
		$costsID=$result['costsID'];
		$netAmt=$result["netAmt"];
		$vatAmt=$result["vatAmt"];
		$costsAmt=$result["costsAmt"];
		$whtAmt=$result["whtAmt"];
}
else{ $status++;}
$costsID_str1 = str_replace("{","",$costsID);
$costsID_str2 = str_replace("}","",$costsID_str1);
$costsID=explode(",",$costsID_str2);

$netAmt= str_replace("{","",$netAmt);
$netAmt = str_replace("}","",$netAmt);
$netAmt=explode(",",$netAmt);


$vatAmt= str_replace("{","",$vatAmt);
$vatAmt = str_replace("}","",$vatAmt);
$vatAmt=explode(",",$vatAmt);

$costsAmt= str_replace("{","",$costsAmt);
$costsAmt = str_replace("}","",$costsAmt);
$costsAmt=explode(",",$costsAmt);

$whtAmt= str_replace("{","",$whtAmt);
$whtAmt = str_replace("}","",$whtAmt);
$whtAmt=explode(",",$whtAmt);

$sum_costsAmt=0;
for($p_ja=$typeprint;$p_ja<=2;$p_ja++){ //ใส่ไว้เพื่อให้ print รายงานออกมา 2 ชุด
	$sum_costsAmt=0;
	if($chk == "real" && $p_ja == "2" ){break; exit;}
	$pdf->AddPage();

if($p_ja!="1"){
	$pdf->Image("images/12.png",60,100,100);  //สำเนา
}
else{
	$pdf->Image("images/11.png",60,100,100);  //ต้นชบับ
}
$pdf->SetFont('AngsanaNew','',20); 
$title=iconv('UTF-8','windows-874',"ใบเสร็จรับเงิน/ใบกำกับภาษี");
$pdf->SetXY(80,35);
$pdf->MultiCell(110,10,$title,0,'L',0); 
/*******************************เลขที่*/
$pdf->SetFont('AngsanaNew','',14);  
$str_receiptID=iconv('UTF-8','windows-874',"เลขที่");
$pdf->SetXY(90,40);
$pdf->MultiCell(50,15,$str_receiptID,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(140,40);
$pdf->MultiCell(60,15,$receiptID,0,'L',0); 

/*******************************วันที่*/
$pdf->SetFont('AngsanaNew','',15);
$receiveDate=iconv('UTF-8','windows-874',"วันที่");
$pdf->SetXY(90,45);
$pdf->MultiCell(50,20,$receiveDate,0,'R',0); 

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(140,45);
$pdf->MultiCell(60,20,$receiptstamp,0,'L',0); 

/*******************************สัญญาเลขที่*/
$pdf->SetFont('AngsanaNew','',15);
$receiveDate=iconv('UTF-8','windows-874',"สัญญาเลขที่");
$pdf->SetXY(90,50);
$pdf->MultiCell(50,25,$receiveDate,0,'R',0); 

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(140,50);
$pdf->MultiCell(60,25,$conid,0,'L',0); 

/*******************************ได้รับเงินจาก*/
$pdf->SetFont('AngsanaNew','',15);
$receiveDate=iconv('UTF-8','windows-874',"ได้รับเงินจาก");
$pdf->SetXY(10,52);
$pdf->MultiCell(25,34,$receiveDate); 

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(30,45);
$cusname_txt=iconv('UTF-8','windows-874',$cusname);
$pdf->MultiCell(200,46,$cusname_txt,0,'L',0); 


$pdf->SetFont('AngsanaNew','',15);
$receiveDate=iconv('UTF-8','windows-874',".................................................................................................................................................");
$pdf->SetXY(30,52);
$pdf->MultiCell(150,34,$receiveDate); 

/*******************************ที่อยู่*/
$pdf->SetFont('AngsanaNew','',15);
$receiveadd=iconv('UTF-8','windows-874',"ที่อยู่");
$pdf->SetXY(10,58);
$pdf->MultiCell(25,40,$receiveadd);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(30,58);
$cusadd_str=iconv('UTF-8','windows-874',$cusadd_str_1);
$pdf->MultiCell(150,38,$cusadd_str,0,'L',0); 

$pdf->SetFont('AngsanaNew','',15);
$receiveDate=iconv('UTF-8','windows-874',"......................................................................................................................................................................");
$pdf->SetXY(30,58);
$pdf->MultiCell(150,40,$receiveDate); 

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(30,66);
$cusadd_str=iconv('UTF-8','windows-874',$cusadd_str_2);
$pdf->MultiCell(150,38,$cusadd_str,0,'L',0);

$pdf->SetFont('AngsanaNew','',15);
$receive_dot=iconv('UTF-8','windows-874',"......................................................................................................................................................................");
$pdf->SetXY(30,66);
$pdf->MultiCell(150,40,$receive_dot); 


$pdf->SetXY(5,90);
$is_acid=iconv('UTF-8','windows-874',"ลำดับที่" );
$pdf->MultiCell(13,8,$is_acid,1,'C',0);  
  
$pdf->SetXY(18,90);
$is_acid=iconv('UTF-8','windows-874',"รายการ" );
$pdf->MultiCell(97,8,$is_acid,1,'C',0); 

$pdf->SetXY(115,90);
$is_acid=iconv('UTF-8','windows-874',"จำนวนเงิน" );
$pdf->MultiCell(30,8,$is_acid,1,'C',0); 

$pdf->SetXY(145,90);
$is_acid=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม" );
$pdf->MultiCell(30,8,$is_acid,1,'C',0); 

$pdf->SetXY(175,90);
$is_acid=iconv('UTF-8','windows-874',"รวมเงิน" );
$pdf->MultiCell(30,8,$is_acid,1,'C',0); 
$crow=0;
$icount=0;



while($icount<12){
	$costsname="";
	$ii=0;	
	$pdf->SetXY(5,98+$crow);
	$i_acid=iconv('UTF-8','windows-874',$icount+1);
	$pdf->MultiCell(13,8,$i_acid,1,'C',0);
	
	if($icount < count($costsID)){
			
		while(($ii !=$costsID[$icount])and($ii < 13)){
			$ii=$ii+1;
		}
		if($ii==$costsID[$icount]){
			$str_sql="select \"costsName\" from \"blo_costs\" where \"costsID\" ='$ii'";
			$query_chkcost=pg_query($str_sql);		
			if($result1=pg_fetch_array($query_chkcost)){
				$str_costsname=$result1["costsName"];
				$str_netAmt=number_format($netAmt[$icount],2);
				$str_vatAmt=number_format($vatAmt[$icount],2);
				$str_costsAmt=number_format($costsAmt[$icount],2);

				if($whtAmt[$icount] > 0){
					$str_whtAmt=" (หัก ณ ที่จ่าย:".number_format($whtAmt[$icount],2)." บาท )";
				}	
				$sum_costsAmt = $sum_costsAmt + $costsAmt[$icount] ;
			}
			else{
				    $status++;
			}

		}	
		
	}
	else{
		$str_costsname="";
		$str_netAmt="";
		$str_vatAmt="";
		$str_costsAmt="";
		$str_whtAmt="";
	}
	$pdf->SetFont('AngsanaNew','',13);
	$pdf->SetXY(18,98+$crow);
	$i_acid=iconv('UTF-8','windows-874',$str_costsname.$str_whtAmt);
	$pdf->MultiCell(97,8,$i_acid,1,'L',0);
	
	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(115,98+$crow);
	$i_acid=iconv('UTF-8','windows-874',$str_netAmt);
	$pdf->MultiCell(30,8,$i_acid,1,'R',0);
	
	$pdf->SetXY(145,98+$crow);
	$i_acid=iconv('UTF-8','windows-874',$str_vatAmt);
	$pdf->MultiCell(30,8,$i_acid,1,'R',0);
	
	$pdf->SetXY(175,98+$crow);
	$i_acid=iconv('UTF-8','windows-874',$str_costsAmt);
	$pdf->MultiCell(30,8,$i_acid,1,'R',0);
	
	
	
	$crow=$crow+8;
	$icount=$icount+1;
}
$pdf->SetFont('AngsanaNew','B',14);  
$sumall=iconv('UTF-8','windows-874',"รวมเงิน");
$pdf->SetXY(145,98+$crow);
$pdf->MultiCell(30,8,$sumall,0,'C',0); 

$pdf->SetXY(175,98+$crow);
$i_acid=iconv('UTF-8','windows-874','');
$pdf->MultiCell(30,9,$i_acid,1,'R',0);

$pdf->SetXY(175,98+$crow);
$i_acid=iconv('UTF-8','windows-874',number_format($sum_costsAmt,2));
$pdf->MultiCell(30,8,$i_acid,1,'R',0);



$crow=$crow+8;
	
$pdf->SetFont('AngsanaNew','',14);  
$sumall=iconv('UTF-8','windows-874',"ข้าพเจ้า");
$pdf->SetXY(15,98+$crow);
$pdf->MultiCell(150,15,$sumall,0,'L',0); 

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(30,98+$crow);
$cusname_txt=iconv('UTF-8','windows-874',$cusname);
$pdf->MultiCell(150,13,$cusname_txt,0,'L',0); 

$pdf->SetFont('AngsanaNew','',14);  
$sumall=iconv('UTF-8','windows-874',".............................................................................................................................................................");
$pdf->SetXY(25,98+$crow);
$pdf->MultiCell(130,15,$sumall,0,'L',0);


$pdf->SetFont('AngsanaNew','',14);  
$sumall=iconv('UTF-8','windows-874',"ตกลงชำระค่าใช้จ่าย ข้างต้นให้กับ");
$pdf->SetXY(155,98+$crow);
$pdf->MultiCell(50,15,$sumall,0,'L',0); 

$crow=$crow+8;

$pdf->SetFont('AngsanaNew','',14);  
$sumall=iconv('UTF-8','windows-874',"บริษัท สำนักกฎหมาย กรุงเทพ จำกัด โดยยินยอมให้ บริษัท ไทยเอช แคปปิตอล จำกัด หัก ค่าใช้จ่ายข้างต้น ออกจาก ยอดเงินกู้ที่ได้รับ ได้");
$pdf->SetXY(15,98+$crow);
$pdf->MultiCell(180,15,$sumall,0,'L',0); 

$crow=$crow+8;
$crow=$crow+20;

$pdf->SetFont('AngsanaNew','',14);  
$sumall=iconv('UTF-8','windows-874',"ผู้รับชำระ..................................................................................");
$pdf->SetXY(15,98+$crow);
$pdf->MultiCell(150,15,$sumall,0,'L',0); 

$pdf->SetFont('AngsanaNew','',14);  
$sumall=iconv('UTF-8','windows-874',"ผู้ชำระ...............................................................................");
$pdf->SetXY(125,98+$crow);
$pdf->MultiCell(150,15,$sumall,0,'L',0);

$crow=$crow+8;
$pdf->SetFont('AngsanaNew','',14);  
$sumall=iconv('UTF-8','windows-874',"(................................................................................)");
$pdf->SetXY(15,98+$crow);
$pdf->MultiCell(90,15,$sumall,0,'C',0); 

$pdf->SetFont('AngsanaNew','',14);  
$sumall_txt=iconv('UTF-8','windows-874',$user);
$pdf->SetXY(15,98+$crow-1);
$pdf->MultiCell(90,15,$sumall_txt,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);  
$sumall=iconv('UTF-8','windows-874',"(................................................................................)");
$pdf->SetXY(120,98+$crow);
$pdf->MultiCell(90,15,$sumall,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);  
$sumall_txt=iconv('UTF-8','windows-874',$cusname);
$pdf->SetXY(120,98+$crow-1);
$pdf->MultiCell(90,15,$sumall_txt,0,'C',0);

}

$pdf->Output();
}
if($status==0)
{
	//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(BLO) พิมพ์ใบเสร็จรับเงิน', '$nowdate')");
	//ACTIONLOG---
	pg_query("COMMIT");	

}
else
{
	pg_query("ROLLBACK");

}

?>
