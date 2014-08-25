<?php
session_start();



require_once("../../sys_setup.php");
include("../../../../../config/config.php");
include("../../fpdf16/fpdf_writehtml.php");
// สร้าง pdf ตาม ข้อมูล -------------------------------------------------------------------
$pdf=new PDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->AddFont('AngsanaNew','','angsa.php');
$pdf->AddFont('AngsanaNew','B','angsab.php');
$pdf->AddFont('AngsanaNew','I','angsai.php');
$pdf->SetFont('AngsanaNew','',17);


$id = $_GET['id'] ;
	//$expire_date = $_REQUEST[expire_date];
// รับข้อมูลจาก DATABASE
$query = "SELECT * FROM $dbtb_ta_join_payment WHERE id='".$id."' and deleted = '0' ";
$sql_query = pg_query($query);
	
while($sql_row = pg_fetch_array($sql_query))
{			
	$ta_join_payment_id = $sql_row['ta_join_payment_id'];//เลขที่ใบรับเงิน
	$cpro_name = $sql_row['cpro_name']; //ชื่อลูกค้า
	$IDNO = $sql_row['idno']; //เลขที่สัญญา
	$car_license = $sql_row['car_license']; //ทะเบียนรถ discount

	
	$start_pay_date = $sql_row['start_pay_date']; // เริ่มชำระเมื่อ
	$start_pay_date = date_ch_form_c($start_pay_date); //แปลงรูปแบบ วัน เดือน ปี
	
	$expire_date  =$sql_row['expire_date']; 
	$expire_date = date_ch_form_m($expire_date);
	//$expire_date_pay = date("m/Y", $expire_date);
	//$expire_date = $_REQUEST[expire_date];
	$period_date = $sql_row['period_date'];
	$period_date = date_ch_form_m($period_date);
	$user = $sql_row['users']; // ไอดีและชื่อพนักงานที่คีย์ข้อมูล
	//	list($user_id,$firstname,$lastname)=split(" ",$user); //แยกไอดีพนักงานออก
	//$user = $firstname." ".$lastname ;// ชื่อ-นามสกุล พนักงานที่คีย์ข้อมูล
	$pay  = $sql_row['pay']; //รูปแบบการชำระเงิน  เงินสด เงินโอน
	
	$amount_month = $sql_row['amount_month']; // จำนวนเดือนที่จ่าย
	//$amount_month = $_REQUEST[month_pay];
	if($amount_month != 0) $amount_month = "จำนวน: ".$amount_month." เดือน";
	else $amount_month = "ค่าสมัครสมาชิกแบบพิเศษ";
	$ab = $sql_row[amount];
	$amount=number_format($sql_row[amount],2); //จำนวนเงิน
	$vat_percent = $sql_row['vat_percent'];
	
	//$pay = $sql_row['pay']; // ประเภทการชำระ
	//$pay_type = $_REQUEST[pay_type];
	$pay_type = $sql_row['pay_type'];
	if($pay_type == 0) $pay_type = "แบบ: 300 B/M ";
	if($pay_type == 1) $pay_type = "แบบ: 100 B/M ";
	if($pay_type == 2) $pay_type = "แบบ: SP ";
	if($pay=="เงินปรับปรุง"){
	$pay_type = "รายการปรับปรุง";
	}
	$pay_date2 =	$sql_row['pay_date'];  //วันที่ชำระ
	$pay_date = date_ch_form_c($pay_date2); //แปลงรูปแบบ วัน เดือน ปี
	if($ab!=5000){
	$bb =$pay_type.$amount_month.' ตั้งแต่: '.$period_date.' ใช้ได้ถึง: '.$expire_date;
	}else {
	$bb =$pay_type.$amount_month.' ('.$pay.')';
	}
}
/*
$query = "SELECT * FROM $dbtb_ta_join_main  WHERE car_license ='".$car_license."' and deleted = '0' ORDER BY id asc ";

$sql_query = pg_query($query);
	while($sql_row = pg_fetch_array($sql_query))
	{
		$address = $sql_row['address']; //ที่อยู่่
	if($address==''){
 		$address = "                 -------------------------------------"; }
	}*/
$query = "SELECT * FROM $dbtb_ta_join_payment WHERE pay_date='".$pay_date2."' and ta_join_payment_id ='".$ta_join_payment_id."' and car_license='$car_license' and deleted = '0' ORDER BY id asc ";

$sql_query = pg_query($query);
$num_row = pg_num_rows($sql_query);


	$i=0;
while($sql_row = pg_fetch_array($sql_query))
{
	/*
			$cash[$i] 		 = $sql_row['amount_cash']; //เงินสด
			if($cash[$i] !=0 ){
	 			$cash[$i];
			}
			$transfer[$i]	 = $sql_row['amount_transfer']; // เงินโอน
			if($transfer[$i] !=0){
	 			$transfer[$i]; 
			}
			$cheque[$i] 	 = $sql_row['amount_cheque']; // เช็ค
			if($cheque[$i] !=0 ){
				$cheque[$i];
			}
			$cs_cheque[$i]   = $sql_row['amount_cs_cheque']; // แคชเชียร์เช็ค
			if($cs_cheque[$i] !=0 ){
				$cs_cheque[$i]; 		
			}
			$update_m[$i]    = $sql_row['amount_update_m']; // เงินปรับปรุง
			if($update_m[$i] !=0 ){
				$update_m[$i]; 
			}
			*/
			$discount    = $sql_row['amount_discount']; // เงินปรับปรุง
			$status_tax_wh     = $sql_row['status_tax_wh']; // ภาษีหัก ณ ที่จ่าย  สถานะภาษีหัก ณ ที่จ่าย	
			$ta_join_payment_id2[$i] = $sql_row['ta_join_payment_id'];
//$pay2[$i] = $sql_row['pay'];
//$pay_type2[$i] = $sql_row['pay_type'];
	//if($pay_type2[$i] == 0) $pay_type2[$i] = "แบบ: 300 B/M ";
	//if($pay_type2[$i] == 1) $pay_type2[$i] = "แบบ: 100 B/M ";
	//if($pay_type2[$i] == 2) $pay_type2[$i] = "แบบ: SP ";
	//if($pay2[$i]=="เงินปรับปรุง"){
	//$pay_type2[$i] = "รายการปรับปรุง";
	//}
			$expire_date2[$i]  =$sql_row['expire_date']; 
			$expire_date2[$i] = date_ch_form_m($expire_date2[$i]);
	//$expire_date_pay = date("m/Y", $expire_date);
	//$expire_date = $_REQUEST[expire_date];
			$amount3[$i] = $sql_row[amount];
			$amount2[$i]=number_format($sql_row[amount],2); //จำนวนเงิน
			$period_date2[$i] = $sql_row['period_date'];
			$period_date2[$i] = date_ch_form_m($period_date2[$i]);
			$amount_month2[$i] = $sql_row['amount_month'];
			if($amount_month2[$i] != 0) $amount_month2[$i] = "จำนวน: ".$amount_month2[$i]." เดือน";
			else $amount_month2[$i] = "ค่าสมัครสมาชิกแบบพิเศษ";
			if($amount3[$i]!=5000){
				$cc[$i] = $pay_type2[$i].$amount_month2[$i].' ตั้งแต่ '.$period_date2[$i].' ใช้ได้ถึง: '.$expire_date2[$i] ;
			}else {
				$cc[$i] = $pay_type2[$i].$amount_month2[$i] ;
			}


	$i++;
}

if($num_row==1){
	$amount= $amount3[0];
$amount = str_replace(',','',$amount);
$net =(($amount*100)/(100+$vat_percent));
	if($status_tax_wh !=0){
		$aa=$status_tax_wh/100 ;
		$tax_wh=($net*$aa);
	}
$amount_dis =$amount-$discount;
$keep_cash=$amount_dis-$tax_wh;
$keep_cash = number_format($keep_cash,2);
$tax_wh = number_format($tax_wh,2); 
$vat=$amount-$net;
$net = number_format($net,2);
$vat = number_format($vat,2);
/*
$status= $status[0]+$status[1];
$cash= $cash[0]+$cash[1]; 
$transfer= $transfer[0]+$transfer[1];
$cheque= $cheque[0]+$cheque[1];
$cs_cheque= $cs_cheque[0]+$cs_cheque[1];
$update_m= $update_m[0]+$update_m[1];*/
//$discount= $discount[0]+$discount[1];


}
if($num_row==2){
$amount= $amount3[0]+$amount3[1];
$amount = number_format($amount,2);
$amount = str_replace(',','',$amount);
$net =(($amount*100)/(100+$vat_percent));
$vat=$amount-$net;
	if($status_tax_wh !=0){
		$aa=$status_tax_wh/100 ;
		$tax_wh=($net*$aa);
	}
$amount_dis =$amount-$discount;
$keep_cash=$amount_dis-$tax_wh;
$keep_cash = number_format($keep_cash,2);
$tax_wh = number_format($tax_wh,2);
$net = number_format($net,2);
$vat = number_format($vat,2);
/*
$status= $status[0]+$status[1];
$cash= $cash[0]+$cash[1]; 
$transfer= $transfer[0]+$transfer[1];
$cheque= $cheque[0]+$cheque[1];
$cs_cheque= $cs_cheque[0]+$cs_cheque[1];
$update_m= $update_m[0]+$update_m[1];*/
//$discount= $discount[0]+$discount[1];



}
if($num_row==3){
$amount= $amount3[0]+$amount3[1]+$amount3[2];
$amount = number_format($amount,2);
$amount = str_replace(',','',$amount);
$net =(($amount*100)/(100+$vat_percent));
$vat=$amount-$net;
	if($status_tax_wh !=0){
		$aa=$status_tax_wh/100 ;
		$tax_wh=($net*$aa);
	}
	$amount_dis =$amount-$discount;
	//echo $amount_dis;
$keep_cash=$amount_dis-$tax_wh; 
$keep_cash = number_format($keep_cash,2);
$tax_wh = number_format($tax_wh,2);
$net = number_format($net,2);
$vat = number_format($vat,2);
$amount = number_format($amount,2);
//echo 'รวม'.$amount.'<br>';echo '7%'.$vat.'<br>';echo 'net'.$net.'<br>';echo 'keep'.$keep_cash.'<br>';echo 'tax_wh'.$tax_wh.'<br>';
//$status= $status[0]+$status[1]+$status[2];
//$cash= $cash[0]+$cash[1]+$cash[2]; 
//$transfer= $transfer[0]+$transfer[1]+$transfer[2];
//$cheque= $cheque[0]+$cheque[1]+$cheque[2];
//$cs_cheque= $cs_cheque[0]+$cs_cheque[1]+$cs_cheque[2];
//$update_m= $update_m[0]+$update_m[1]+$update_m[2];	
//$discount= $discount[0]+$discount[1]+$discount[2];

} 
	if($discount==0 ){
			$discount =''; 
			if($status_tax_wh ==0){	
	
					$tax_title='    ';
					$receipt_cash=$amount;
					}
				else{	
				    
	          		$show_tax_wh = $tax_wh." ".'('.$keep_cash.')' ;
					$tax_title='* หักภาษี ณ ที่จ่าย $status_tax_wh % จำนวน ' ;
					$receipt_cash=$keep_cash;
				 }	

	}else{
			$discount_title='ส่วนลดพิเศษ';
		//	$discount=$discount;
			$amount = str_replace(',','',$amount);
			$amount = ($amount-$discount);
			$net =(($amount*100)/(100+$vat_percent));
			$vat=$amount-$net;
			//ส่วนการคิดเงิน 3% หากมีส่วนลด
				if($status_tax_wh !=0){
					$aa=$status_tax_wh/100 ;
					$tax_wh=($net*$aa);
				}
				
			$keep_cash=$amount-$tax_wh;
			$tax_wh = number_format($tax_wh,2);
			
			$net = number_format($net,2);
			$vat = number_format($vat,2);
			$amount = number_format($amount,2);
				if($status_tax_wh ==0){	
	
					$tax_title='    ';
					$receipt_cash=$amount;
					}
				else{
					
	          		$show_tax_wh = $tax_wh." ".'('.number_format($keep_cash,2).')' ;
					
					$tax_title='* หักภาษี ณ ที่จ่าย $status_tax_wh % จำนวน ' ;
					$receipt_cash=number_format($keep_cash,2);
				}	
				//echo 'ส่วนลดพิเศษ รวม'.$amount.'<br>';echo '7%'.$vat.'<br>';echo 'net'.$net.'<br>';echo 'keep'.$keep_cash.'<br>';echo 'tax_wh'.$tax_wh.'<br>';
			}
	
$text =  '
    
<table border="0">
	<tr>
		<td height="80" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td width="65" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="250" scope="col">'.$pay_date.'</td>
		<td width="400" scope="col">'.$ta_join_payment_id.'</td>
	</tr>
	<tr>
		<td width="165" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="300" align=left scope="col">'.$car_license.'</td>
		<td width="250" align=left scope="col">'.$cpro_name.' '.'('.$IDNO.')'.'</td>

	</tr>
	<tr>
		<td width="200" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$start_pay_date.'</td>
		<td width="60" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="500" align=left scope="col">';
		$pdf->WriteHTML($text);
		$pdf->SetFont('AngsanaNew','',12); 
		$text = $address;
		$pdf->WriteHTML($text);
		$pdf->SetFont('AngsanaNew','',17); 
		$text = '</td>
	</tr>
	<tr>
		<td height="15" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td width="585" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col"></td>
	</tr>
	<tr>
		<td height="30" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>';
	
	if($num_row==2){
	$text .='
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$cc[0].'</font></td>
		<td width="125" align=left scope="col">'.$amount2[0].'</td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$cc[1].'</font></td>
		<td width="125" align=left scope="col">'.$amount2[1].'</td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="125" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td height="115" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	';
	}else if($num_row==3){
	$text .='
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$cc[0].'</font></td>
		<td width="125" align=left scope="col">'.$amount2[0].'</td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$cc[1].'</font></td>
		<td width="125" align=left scope="col">'.$amount2[1].'</td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$cc[2].'</font></td>
		<td width="125" align=left scope="col">'.$amount2[2].'</td>
	</tr>
	<tr>
		<td height="115" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	';
	
	}else if($num_row<=1){
		$text .='
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$bb.'</font></td>
		<td width="125" align=left scope="col">'.$amount2[0].'</td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="125" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="125" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td height="115" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	';
	}
	$text .='
    <tr>
	 	<td width="50" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$discount_title.'</td>
		<td width="480" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$discount.'</td>
	</tr>
	 <tr>';
	 	$pdf->WriteHTML($text);
		$pdf->SetFont('AngsanaNew','',12); 
		$text = '
	 	<td width="60" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$tax_title." ".$show_tax_wh .'</td>';
		$pdf->WriteHTML($text);
		$pdf->SetFont('AngsanaNew','',17); 
		$text = '
	</tr>
     <tr>
	 	<td width="625" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$net.'</td>
	</tr>
	 <tr>
	 	<td width="640" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="120" align=left scope="col">'.$vat.'</td>
	</tr>
     <tr>
	 	<td width="625" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$amount.'</td>
	</tr>
    <tr>
	<tr>
		<td height="5" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>';
	 	
		$pdf->WriteHTML($text);
		$pdf->SetFont('AngsanaNew','',12); 
		$text = '<td width="200" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">เงินสด '.$receipt_cash.'</td>';
		$pdf->WriteHTML($text);
		$pdf->SetFont('AngsanaNew','',17); 
		$text = '
	</tr>
	<tr>
		<td height="20" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td height="20" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td width="90" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="550" align=left scope="col">'.$user.'</td>
	</tr>
	
	
	<tr>
		<td height="10" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	

	<tr>
		<td height="165" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td width="65" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="250" scope="col">'.$pay_date.'</td>
		<td width="400" scope="col">'.$ta_join_payment_id.'</td>
	</tr>
	<tr>
		<td width="165" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="300" align=left scope="col">'.$car_license.'</td>
		<td width="250" align=left scope="col">'.$cpro_name.' '.'('.$IDNO.')'.'</td>

	</tr>
	<tr>
		<td width="200" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$start_pay_date.'</td>
		<td width="60" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="500" align=left scope="col">';
		$pdf->WriteHTML($text);
		$pdf->SetFont('AngsanaNew','',12); 
		$text = $address;
		$pdf->WriteHTML($text);
		$pdf->SetFont('AngsanaNew','',17); 
		$text = '</td>
	</tr>
	<tr>
		<td height="15" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td width="585" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col"></td>
	</tr>
	<tr>
		<td height="30" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	';
	
	if($num_row==2){
	$text .='
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$cc[0].'</font></td>
		<td width="125" align=left scope="col">'.$amount2[0].'</td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$cc[1].'</font></td>
		<td width="125" align=left scope="col">'.$amount2[1].'</td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="125" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td height="115" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	';
	}else if($num_row==3){
	$text .='
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$cc[0].'</font></td>
		<td width="100" align=left scope="col">'.$amount2[0].'</td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$cc[1].'</font></td>
		<td width="100" align=left scope="col">'.$amount2[1].'</td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$cc[2].'</font></td>
		<td width="100" align=left scope="col">'.$amount2[2].'</td>
	</tr>
	<tr>
		<td height="110" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	';
	
	}else if($num_row<=1){
		$text .='
<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font size=8>'.$bb.'</font></td>
		<td width="125" align=left scope="col">'.$amount2[0].'</td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="125" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td width="75" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="575" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="125" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	<tr>
		<td height="110" align=left scope="col"><font color=#FFFFFF>.</font></td>
	</tr>
	';
	}
	$text .='
    <tr>
	 	<td width="50" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$discount_title.'</td>
		<td width="480" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$discount.'</td>
	</tr>
    <tr>';
	 	$pdf->WriteHTML($text);
		$pdf->SetFont('AngsanaNew','',12); 
		$text = '
	 	<td width="60" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$tax_title." ".$show_tax_wh.'</td>';
		$pdf->WriteHTML($text);
		$pdf->SetFont('AngsanaNew','',17); 
		$text = '
	</tr>
	 <tr>
	 	<td width="640" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$net.'</td>
	</tr>
	 <tr>
	 	<td width="640" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="120" align=left scope="col">'.$vat.'</td>
	</tr>
     <tr>
	 	<td width="625" align=left scope="col"><font color=#FFFFFF>.</font></td>
		<td width="100" align=left scope="col">'.$amount.'</td>
	</tr>

</table>



';
//echo $text;

$pdf->WriteHTML($text);
/*
if (!file_exists($_SESSION["session_path_save_pdf"].$rec_id.".pdf")) { //check file exists
$pdf->Output($_SESSION["session_path_save_pdf"].$rec_id.".pdf", "F"); // save pdf
}
*/
$pdf->Output(); //open pdf
?>