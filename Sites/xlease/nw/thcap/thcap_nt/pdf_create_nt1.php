<?php
include("../../config/config.php");
$db1="ta_mortgage_datastore";

$nowdate = Date('Y-m-d');
$nowtime=Date('H:i:s');

$val=$_GET['val'];
$order=$_GET['order'];
$sort=$_GET['sort'];
//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
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
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP) Create NT");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

if($val=="1"){
	$txtval="แสดงรายการเฉพาะที่ค้าง";
}else{
	$txtval="แสดงรายการทั้งหมด";
}

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',$txtval);
$pdf->MultiCell(290,4,$buss_name,0,'L',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
$pdf->MultiCell(55,4,$buss_name,0,'L',0);

$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่ค้างชำระ");
$pdf->MultiCell(32,4,$buss_name,0,'C',0);

$pdf->SetXY(122,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มผิดนัด");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(147,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่ค้างชำระ");
$pdf->MultiCell(33,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(210,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ครบกำหนด");
$pdf->MultiCell(28,4,$buss_name,0,'C',0);

	$pdf->SetXY(210,34); 
	$buss_name=iconv('UTF-8','windows-874',"ชำระถัดไป");
	$pdf->MultiCell(28,4,$buss_name,0,'C',0);


$pdf->SetXY(238,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่ครบ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	$pdf->SetXY(238,34); 
	$buss_name=iconv('UTF-8','windows-874',"กำหนดชำระถัดไป");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(268,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ NT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,34); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$cline = 40;

$qry_fr=pg_query("select \"contractID\" from \"thcap_mg_contract\" where \"conStatus\"<>'11' order by \"contractID\"");
$nub=0;
$i=0;
while($res_fr=pg_fetch_array($qry_fr)){
	list($contractID)=$res_fr;
				  					   
	//หาวันที่เริ่มผิดนัดชำระ
	$qrybackduedate=pg_query("SELECT \"thcap_backDueDate\"('$contractID','$nowdate')");
	list($backduedate)=pg_fetch_array($qrybackduedate);
					   
	//##########หาจำนวนวันที่ค้างชำระ 
	if($backduedate=="" || $backduedate=="0.00"){
	$nubdate="";
	}else{
		$y1=substr($nowdate,0,4);
		$m1=substr($nowdate,5,2);
		$d1=substr($nowdate,8,2);
															
		$y2=substr($backduedate,0,4);
		$m2=substr($backduedate,5,2);
		$d2=substr($backduedate,8,2);
															
		//หาจำนวนวันที่รับชำระจาก นำ "วันปัจจุบันที่ user เลือก" - function thcap_backDueDate()
		$result_1 = mktime(0, 0, 0, $m1, $d1, $y1); //นำวันเดือนปี 1 มาแปลงเป็นรูปแบบ Unix timestamp
		$result_2 = mktime(0, 0, 0, $m2, $d2, $y2); //นำวันเดือนปี 2 มาแปลงเป็นรูปแบบ Unix timestamp

		$result_date = $result_1 - $result_2; //นำวันที่มาลบกัน

		$nubdate = $result_date / (60 * 60 * 24); //แปลงค่าเวลารูปแบบ Unix timestamp ให้เป็นจำนวนวัน 
		$nubdate=ceil($nubdate); //ทำให้เป็นจำนวนเต็ม
	}
	//##########สิ้นสุดหาจำนวนวันที่ค้างชำระ 
	
	if(($nubdate != 0 and $val=="1") || $val=="2"){						
		//หาจำนวนเงินที่ค้างชำระ
		$qrybackAmt=pg_query("SELECT \"thcap_backAmt\"('$contractID','$nowdate')");
		list($backAmt)=pg_fetch_array($qrybackAmt);
						   
		//วันที่จะครบกำหนดชำระถัดไป
		$qrynextDueDate=pg_query("SELECT \"thcap_nextDueDate\"('$contractID','$nowdate')");
		list($nextDueDate)=pg_fetch_array($qrynextDueDate);
							   
		//จำนวนเงินที่จะครบกำหนดชำระถัดไป
		$qrynextDueAmt=pg_query("SELECT \"thcap_nextDueAmt\"('$contractID','$nowdate')");
		list($nextDueAmt)=pg_fetch_array($qrynextDueAmt);
		
		//หาเงินต้นคงเหลือ
		$qryleftprint=pg_query("select \"LeftPrinciple\" from \"thcap_temp_int_201201\" where \"contractID\"='$contractID' order by \"lastReceiveDate\" DESC limit 1");
		list($LeftPrinciple)=pg_fetch_array($qryleftprint);
		
		if($sort=="" || $sort=="3"){
			$x[$i]=$nubdate; //sort ตามจำนวนวันที่ค้างชำระ
			$y[$i]=$nubdate."#".$contractID;
		}else if($sort=="1"){ 
			$x[$i]=$contractID; //sort ตามเลขที่สัญญา
			$y[$i]=$contractID."#".$contractID;
		}else if($sort=="2"){
			$x[$i]=$cusname; //sort ตามชื่อผู้กู้หลัก
			$y[$i]=$cusname."#".$contractID;
		}else if($sort=="4"){
			$x[$i]=$backduedate; //sort ตามวันที่เริ่มผิดนัด
			$y[$i]=$backduedate."#".$contractID;
		}else if($sort=="5"){
			$x[$i]=$backAmt; //sort ตามจำนวนเงินที่ค้าง
			$y[$i]=$backAmt."#".$contractID;
		}else if($sort=="6"){
			$x[$i]=$nextDueDate; //sort ตามวันที่จะครบกำหนด
			$y[$i]=$nextDueDate."#".$contractID;
		}else if($sort=="7"){
			$x[$i]=$nextDueAmt; //sort ตามจำนวนเงินที่จะครบกำหนด
			$y[$i]=$nextDueAmt."#".$contractID;
		}else if($sort=="8"){
			$x[$i]=$nubdate; //sort ตามสถานะ NT
			$y[$i]=$nubdate."#".$contractID;
		}else if($sort=="9"){
			$x[$i]=$LeftPrinciple; //sort ตามสถานะ NT
			$y[$i]=$LeftPrinciple."#".$contractID;
		}		
		$i++;	
	} //end if
} // end while รอบที่ 1

//นำ array ที่ได้มา sort
if($order=="asc"){
	$condition="desc"; //เรียงจากมากไปน้อย
}else{
	$condition="asc"; //เรียงจากน้อยไปมาก
}

$a=sizeof($x);
if($condition=="asc"){ //เรียงจากน้อยไปมาก
	for($i=0;$i<$a;$i++){
		for($j=$i+1;$j<$a;$j++){
			if($x[$i]>$x[$j]){
				$temp=$x[$j];
				$x[$j]=$x[$i];
				$x[$i]=$temp;
									
				$tempy=$y[$j];
				$y[$j]=$y[$i];
				$y[$i]=$tempy;
			}
		}
	}
}else{ //เรียงจากมากไปน้อย
	for($i=0;$i<$a;$i++){
		for($j=$i+1;$j<$a;$j++){
			if($x[$i]<$x[$j]){
				$temp=$x[$j];
				$x[$j]=$x[$i];
				$x[$i]=$temp;
									
				$tempy=$y[$j];
				$y[$j]=$y[$i];
				$y[$i]=$tempy;
			}
		}
	}
}

for($j=0;$j<$a;$j++){
		list($valchk,$contact)=explode("#",$y[$j]);
		/*
		หลังจากได้ contractID แล้วก็ให้ทำตามขั้นตอนปกติคือการแสดงค่า แต่การแสดงค่านั้นต้องนำ contractID ที่ได้มาหาค่าอีกรอบ
		เนื่องจากในตอนแรกนั้นเราไม่ได้ส่งค่าอื่นๆตามมาใน array ด้วย
		*/
							
		//หาชื่อผู้กู้หลัก
		$qryname =pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contact' and \"CusState\"='0'");
		list($cusname)=pg_fetch_array($qryname);
									
		//หาวันที่เริ่มผิดนัดชำระ
		$qrybackduedate=pg_query("SELECT \"thcap_backDueDate\"('$contact','$nowdate')");
		list($backduedate)=pg_fetch_array($qrybackduedate);
								
		//##########หาจำนวนวันที่ค้างชำระ 
		if($backduedate==""){
			$nubdate="";
		}else{
			$y1=substr($nowdate,0,4);
			$m1=substr($nowdate,5,2);
			$d1=substr($nowdate,8,2);
																	
			$y2=substr($backduedate,0,4);
			$m2=substr($backduedate,5,2);
			$d2=substr($backduedate,8,2);
																	
			//หาจำนวนวันที่รับชำระจาก นำ "วันปัจจุบันที่ user เลือก" - function thcap_backDueDate()
			$result_1 = mktime(0, 0, 0, $m1, $d1, $y1); //นำวันเดือนปี 1 มาแปลงเป็นรูปแบบ Unix timestamp
			$result_2 = mktime(0, 0, 0, $m2, $d2, $y2); //นำวันเดือนปี 2 มาแปลงเป็นรูปแบบ Unix timestamp

			$result_date = $result_1 - $result_2; //นำวันที่มาลบกัน

			$nubdate = $result_date / (60 * 60 * 24); //แปลงค่าเวลารูปแบบ Unix timestamp ให้เป็นจำนวนวัน 
			$nubdate=ceil($nubdate); //ทำให้เป็นจำนวนเต็ม
		}
								
		//หาจำนวนเงินที่ค้างชำระ
		$qrybackAmt=pg_query("SELECT \"thcap_backAmt\"('$contact','$nowdate')");
		list($backAmt)=pg_fetch_array($qrybackAmt);
							   
		//วันที่จะครบกำหนดชำระถัดไป
		$qrynextDueDate=pg_query("SELECT \"thcap_nextDueDate\"('$contact','$nowdate')");
		list($nextDueDate)=pg_fetch_array($qrynextDueDate);
								   
		//จำนวนเงินที่จะครบกำหนดชำระถัดไป
		$qrynextDueAmt=pg_query("SELECT \"thcap_nextDueAmt\"('$contact','$nowdate')");
		list($nextDueAmt)=pg_fetch_array($qrynextDueAmt);
		
		//หาเงินต้นคงเหลือ
		$qryleftprint=pg_query("select \"LeftPrinciple\" from \"thcap_temp_int_201201\" where \"contractID\"='$contact' order by \"lastReceiveDate\" DESC limit 1");
		list($LeftPrinciple)=pg_fetch_array($qryleftprint);
		
		if($nub > 25){ 
			$pdf->AddPage(); 
			$cline = 40; 
			$nub=0; 

			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(10,10);
			$title=iconv('UTF-8','windows-874',"(THCAP) Create NT");
			$pdf->MultiCell(290,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',16);
			$pdf->SetXY(10,16);
			$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			if($val=="1"){
				$txtval="แสดงรายการเฉพาะที่ค้าง";
			}else{
				$txtval="แสดงรายการทั้งหมด";
			}

			$pdf->SetXY(5,23);
			$buss_name=iconv('UTF-8','windows-874',$txtval);
			$pdf->MultiCell(290,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,23);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);

			$pdf->SetXY(4,24); 
			$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(5,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(35,30); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(55,4,$buss_name,0,'L',0);

			$pdf->SetXY(90,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่ค้างชำระ");
			$pdf->MultiCell(32,4,$buss_name,0,'C',0);

			$pdf->SetXY(122,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มผิดนัด");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(147,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่ค้างชำระ");
			$pdf->MultiCell(33,4,$buss_name,0,'C',0);

			$pdf->SetXY(180,30); 
			$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(210,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่ครบกำหนด");
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

				$pdf->SetXY(210,34); 
				$buss_name=iconv('UTF-8','windows-874',"ชำระถัดไป");
				$pdf->MultiCell(28,4,$buss_name,0,'C',0);


			$pdf->SetXY(238,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่ครบ");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

				$pdf->SetXY(238,34); 
				$buss_name=iconv('UTF-8','windows-874',"กำหนดชำระถัดไป");
				$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(268,30); 
			$buss_name=iconv('UTF-8','windows-874',"สถานะ NT");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(4,34); 
			$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);
		}

		if(($val=="1" and $backAmt > 0) || $val=="2"){
			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline); 
			$buss_name=iconv('UTF-8','windows-874',$contact);
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(35,$cline); 
			$buss_name=iconv('UTF-8','windows-874',$cusname);
			$pdf->MultiCell(55,4,$buss_name,0,'L',0);

			$pdf->SetXY(90,$cline); 
			$buss_name=iconv('UTF-8','windows-874',$nubdate);
			$pdf->MultiCell(32,4,$buss_name,0,'C',0);

			$pdf->SetXY(122,$cline); 
			$buss_name=iconv('UTF-8','windows-874',$backduedate);
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(147,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($backAmt,2));
			$pdf->MultiCell(33,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(180,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($LeftPrinciple,2));
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);

			$pdf->SetXY(210,$cline); 
			$buss_name=iconv('UTF-8','windows-874',$nextDueDate);
			$pdf->MultiCell(28,4,$buss_name,0,'C',0);

			$pdf->SetXY(238,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($nextDueAmt,2));
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);

			$pdf->SetXY(268,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"รออยู่");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$cline+=5; 
			$nub+=1;   
		} 
} //end if

$pdf->SetXY(4,$cline-4); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$nub+=1;  
	
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด  $i รายการ");
$pdf->MultiCell(286,4,$buss_name,0,'L',0);

$pdf->SetXY(4,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(4,$cline+2);
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->Output();
?>