<?php
session_start();
include("../../config/config.php");
include("function_payroll.php");
include("../../core/core_functions.php");
//require_once("../join_payment/extensions/sys_setup.php");

include("../join_payment/extensions/fpdf16/html_table.php");
//require('WriteHTML.php');

//$pdf=new PDF_HTML();
//require('fpdf17/html_table.php');
//fpdf_writehtml
$pdf=new PDF();
//$pdf->AddPage();
//$pdf->SetFont('Arial','',12);

$id_user = $_REQUEST[id_user];

$datepicker = $_GET['datepicker'];
$yy = $_GET['yy'];
$mm = $_GET['mm'];

$yy_l = $_GET['yy'];
$mm_l = $_GET['mm'];
$ty = $_GET['ty'];
//$yy = '2012';
//$mm = '01';

if($ty ==2){				
				$datepicker = $yy."-".$mm."-" ;
			}

$app_date = Date('Y-m-d H:i:s');

$last_date_month = date("t",strtotime("$yy-$mm-01"));

$salary_date = $last_date_month.'/'.$mm.'/'.$yy ;



// สร้าง pdf ตาม ข้อมูล -------------------------------------------------------------------
//$pdf=new PDF('L', 'mm', 'A4');

$qry_fr2=pg_query("SELECT user_id_slip, month, year, user_fullname, user_dep, user_div, 
       user_pos, user_start, bank_name, comp_name, bank_acc_type, salary_type, 
       salary_amt, cost_of_living, diligent, ot, commission, other_income, 
       fare, depreciation, tel_income, total_income, tax, fine_late, 
       social_amt, other_deduct, fine_incomplete, total_deduct, total_net, 
       t_salary_amt, t_cost_of_living, t_diligent, t_ot, t_commission, 
       t_other_income, t_fare, t_tel_income, t_depreciation, t_total_income, 
       t_tax, t_fine_late, t_social_amt, t_other_deduct, t_fine_incomplete, 
       t_total_deduct, t_total_net, pay_type, social_rate, 
       user_id_sys,bank_acc_no,salary_per_day ,salary_note ,bonus,t_total_tax_so 
	   FROM \"hr_payroll_report\" where user_id_sys='$id_user' and month='$mm' and year='$yy' ");

			$nub2=pg_num_rows($qry_fr2); 

			if($nub2>0){
			while($sql_row42=pg_fetch_array($qry_fr2)){
					

					$user_id_slip = $sql_row42['user_id_slip'];
					$user_fullname = $sql_row42['user_fullname'];
					$user_dep = $sql_row42['user_dep'];
					$user_div = $sql_row42['user_div'];
					$user_pos = $sql_row42['user_pos'];
					$user_start = $sql_row42['user_start'];
					$bank_name = $sql_row42['bank_name'];
					$comp_name = $sql_row42['comp_name'];
					$bank_acc_type = $sql_row42['bank_acc_type'];
					$salary_type = $sql_row42['salary_type'];
					$salary_amt = $sql_row42['salary_amt'];
					$cost_of_living = $sql_row42['cost_of_living'];
					$diligent = $sql_row42['diligent'];
					$ot = $sql_row42['ot'];
					$commission = $sql_row42['commission'];
					$other_income = $sql_row42['other_income'];
					$fare = $sql_row42['fare'];
					$depreciation= $sql_row42['depreciation']; 
					

					$tel_income= $sql_row42['tel_income']; 
				    $total_income= $sql_row42['total_income']; 
				    $tax= $sql_row42['tax']; 
				    $fine_late= $sql_row42['fine_late']; 
				    $social_amt= $sql_row42['social_amt']; 

				    $other_deduct= $sql_row42['other_deduct']; 
				    $fine_incomplete= $sql_row42['fine_incomplete']; 
				    $total_deduct= $sql_row42['total_deduct']; 
				    $total_net= $sql_row42['total_net']; 
					
		
					
					
				    $t_salary_amt= $sql_row42['t_salary_amt']; 
					$t_cost_of_living= $sql_row42['t_cost_of_living']; 
				    $t_diligent= $sql_row42['t_diligent']; 
				    $t_ot= $sql_row42['t_ot']; 
				    $t_commission= $sql_row42['t_commission']; 
				    $t_other_income= $sql_row42['t_other_income']; 
				    $t_fare= $sql_row42['t_fare']; 
				    $t_tel_income= $sql_row42['t_tel_income']; 
				    $t_depreciation= $sql_row42['t_depreciation'];
					



					
					
					$t_total_income= $sql_row42['t_total_income']; 
				    $t_tax= $sql_row42['t_tax']; 
				    $t_fine_late= $sql_row42['t_fine_late']; 
				    $t_social_amt= $sql_row42['t_social_amt']; 
				    $t_other_deduct= $sql_row42['t_other_deduct']; 
				    $t_fine_incomplete= $sql_row42['t_fine_incomplete']; 
				    $t_total_deduct= $sql_row42['t_total_deduct']; 
				    $t_total_net= $sql_row42['t_total_net'];
					
					
					



					$pay_type= $sql_row42['pay_type']; 
				   
					$social_rate= $sql_row42['social_rate']; 
				    $user_id_sys= $sql_row42['user_id_sys'];
					
					 $bank_acc_no= $sql_row42['bank_acc_no'];
					  $salary_per_day= $sql_row42['salary_per_day'];
					$salary_note= $sql_row42['salary_note'];
					$bonus= $sql_row42['bonus'];
					$t_total_tax_so= $sql_row42['t_total_tax_so'];
			}

			}
if($salary_amt==0)$salary_amt=" - ";
else $salary_amt= number_format($salary_amt,2);

if($cost_of_living==0)$cost_of_living=" - ";
else $cost_of_living= number_format($cost_of_living,2);

if($diligent==0)$diligent=" - ";
else $diligent= number_format($diligent,2);

if($ot==0)$ot=" - ";
else $ot= number_format($ot,2);

if($commission==0)$commission=" - ";
else $commission= number_format($commission,2);

if($other_income==0)$other_income=" - ";
else $other_income= number_format($other_income,2);

if($fare==0)$fare=" - ";
else $fare= number_format($fare,2);
if($bank_acc_no=="")$bank_acc_no=" - ";


if($depreciation==0)$depreciation=" - ";
else $depreciation= number_format($depreciation,2);		

							if($tel_income==0)$tel_income=" - ";
else $tel_income= number_format($tel_income,2);

if($total_income==0)$total_income=" - ";
else $total_income= number_format($total_income,2);

if($tax==0)$tax=" - ";
else $tax= number_format($tax,2);

if($fine_late==0)$fine_late=" - ";
else $fine_late= number_format($fine_late,2);

if($social_amt==0)$social_amt=" - ";
else $social_amt= number_format($social_amt,2);


if($total_net==0)$total_net=" - ";
else $total_net= number_format($total_net,2);	

	if($t_salary_amt==0)$t_salary_amt=" - ";
else $t_salary_amt= number_format($t_salary_amt,2);

if($t_cost_of_living==0)$t_cost_of_living=" - ";
else $t_cost_of_living= number_format($t_cost_of_living,2);

if($t_diligent==0)$t_diligent=" - ";
else $t_diligent= number_format($t_diligent,2);

if($t_ot==0)$t_ot=" - ";
else $t_ot= number_format($t_ot,2);

if($t_commission==0)$t_commission=" - ";
else $t_commission= number_format($t_commission,2);

if($t_other_income==0)$t_other_income=" - ";
else $t_other_income= number_format($t_other_income,2);

if($t_fare==0)$t_fare=" - ";
else $t_fare= number_format($t_fare,2);					
					
		if($t_tel_income==0)$t_tel_income=" - ";
else $t_tel_income= number_format($t_tel_income,2);

if($t_depreciation==0)$t_depreciation=" - ";
else $t_depreciation= number_format($t_depreciation,2);
	
$other_deduct = ($other_deduct+$fine_incomplete); 
if($other_deduct==0)$other_deduct=" - ";
else $other_deduct= number_format($other_deduct,2);



$t_other_deduct = ($t_other_deduct+$t_fine_incomplete); 
if($t_other_deduct==0)$t_other_deduct=" - ";
else $t_other_deduct= number_format($t_other_deduct,2);

if($t_total_net==0)$t_total_net=" - ";
else $t_total_net= number_format($t_total_net,2);

if($t_total_income==0)$t_total_income=" - ";
else $t_total_income= number_format($t_total_income,2);

if($t_tax==0)$t_tax=" - ";
else $t_tax= number_format($t_tax,2);

if($t_fine_late==0)$t_fine_late=" - ";
else $t_fine_late= number_format($t_fine_late,2);

if($t_social_amt==0)$t_social_amt=" - ";
else $t_social_amt= number_format($t_social_amt,2);

if($t_total_deduct==0)$t_total_deduct=" - ";
else $t_total_deduct= number_format($t_total_deduct,2);

if($total_deduct==0)$total_deduct=" - ";
else $total_deduct= number_format($total_deduct,2);

if($t_total_tax_so==0)$t_total_tax_so=" - ";
else $t_total_tax_so= number_format($t_total_tax_so,2);

if($bonus==0)$bonus=" - ";
else $bonus= number_format($bonus,2);
$total_net_text =  NumberToText($total_net);


if($pay_type=='1'){ $pay_by = "โอนเข้าธนาคาร" ;}
else if($pay_type=='0'){ $pay_by = "เงินสด" ;}

if($bank_acc_type=='1'){ $bank_acc_type = "ออมทรัพย์" ;} else if($bank_acc_type=='2'){$bank_acc_type =  "กระแสรายวัน" ;}else if($bank_acc_type=='3'){$bank_acc_type = "เงินฝากประจำ" ;} 

$pdf->AddPage();
$pdf->AddFont('AngsanaNew','','angsa.php');
$pdf->AddFont('AngsanaNew','B','angsab.php');
$pdf->AddFont('AngsanaNew','I','angsai.php');
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetLeftMargin(4.5);

	
$text =  '

<BR><BR>
<table width="780" border="0"  cellpadding="0" cellspacing="0"><tr>
<td height="30" width="65">&nbsp;</td><td height="30" width="430" colspan="2" align="left">'.$comp_name.'</td>
<td height="30" width="200">&nbsp;</td><td height="30" width="35">'.$user_dep.'</td></tr>
<tr>
<td height="30" width="65">&nbsp;</td>
<td height="30" width="430">'.$user_fullname.'</td>
<td height="30" width="100">'.$user_id_slip.'</td>
<td height="30" width="100">&nbsp;</td>
<td height="30" width="35">'.$salary_date.'</td>
</tr>
<tr>
<td height="3"  colspan="5"  width="780">&nbsp;</td>
</tr>
</table>
<table width="780" border="1" >
<tr>';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','B',12);	
		$text= '<td height="30"  width="780"  colspan="6" align=CENTER><FONT SIZE="4">รายการ รับ</FONT></td>
</tr>';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','',12);	
		$text= '<tr>
<td height="30" width="130"   align=CENTER>เงินเดือน</td>
<td height="30" width="130"   align=CENTER>ค่าครองชีพ</td>
<td height="30" width="130"   align=CENTER>เบี้ยขยัน</td>
<td height="30" width="130"   align=CENTER>คอมมิชชั่น </td>
<td height="30" width="130"   align=CENTER>ล่วงเวลา (OT)</td>
<td height="30" width="130" align=CENTER>&nbsp;</td>
</tr>
<tr>
<td height="30" width="130" align=RIGHT>'.$salary_amt.'</td>
<td height="30" width="130" align=RIGHT>'.$cost_of_living.'</td>
<td height="30" width="130" align=RIGHT>'.$diligent.'</td>
<td height="30" width="130" align=RIGHT>'.$commission.'</td>
<td height="30" width="130" align=RIGHT>'.$ot.'</td>
<td height="30" width="130" align=RIGHT>&nbsp;</td>
</tr>
<tr>
<td height="30" width="130" align=CENTER>ค่าพาหนะ</td>
<td height="30" width="130" align=CENTER>ค่าเสื่อมพาหนะ</td>
<td height="30" width="130" align=CENTER>ค่าโทรศัพท์</td>
<td height="30" width="130" align=CENTER>ค่าอื่นๆ</td>
<td height="30" width="130" align=CENTER>โบนัส</td>
<td height="30" width="130" align=CENTER>รับรวม</td>
</tr>
<tr>
<td height="30" width="130" align=RIGHT>'.$fare.'</td>
<td height="30" width="130" align=RIGHT>'.$depreciation.'</td>
<td height="30" width="130" align=RIGHT>'.$tel_income.'</td>
<td height="30" width="130" align=RIGHT>'.$other_income.'</td>
<td height="30" width="130" align=RIGHT>'.$bonus.'</td>
<td height="30" width="130" align=RIGHT>'.$total_income.'</td>
</tr>
<tr>';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','B',12);	
		$text= '<td height="30" width="780" colspan="6" align=CENTER>รายการ หัก</td>
</tr>';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','',12);	
		$text= '<tr>
<td height="30" width="130" align=CENTER>ภาษี</td>
<td height="30" width="130" align=CENTER>ประกันสังคม</td>
<td height="30" width="130" align=CENTER>ค่าปรับมาสาย</td>
<td height="30" width="130" align=CENTER>ค่าหักอื่นๆ</td>
<td height="30" width="130" align=CENTER>&nbsp;</td>
<td height="30" width="130" align=CENTER>หักรวม</td>
</tr>
<tr>
<td height="30" width="130" align=RIGHT>'.$tax.'</td>
<td height="30" width="130" align=RIGHT>'.$social_amt.'</td>
<td height="30" width="130" align=RIGHT>'.$fine_late.'</td>
<td height="30" width="130" align=RIGHT>'.$other_deduct.'</td>
<td height="30" width="130" align=CENTER>&nbsp;</td>
<td height="30" width="130" align=RIGHT>'.$total_deduct.'</td>
</tr>
<tr>
<td height="30" width="520" colspan="4">';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','I',12);$text = '('.$total_net_text.')</td>';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','B',12);	
		$text= '<td height="30" width="130" align=CENTER>รับ สุทธิ เดือนนี้</td>
<td height="30" width="130" align=RIGHT>'.$total_net.'</td>
</tr>
<tr>
<td height="30" width="780" colspan="6" align=CENTER>รายการ สะสม ถึงปัจจุบัน</td>
</tr>';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','',12);	
		$text= '<tr>
<td height="30" width="130" align=CENTER>เงินเดือนสะสม</td>
<td height="30" width="130" align=CENTER>ค่าครองชีพสะสม</td>
<td height="30" width="130" align=CENTER>เบี้ยขยันสะสม</td>
<td height="30" width="130" align=CENTER>คอมมิชชั่นสะสม</td>
<td height="30" width="130" align=CENTER>ล่วงเวลา (OT) สะสม</td>
<td height="30" width="130" align=CENTER>&nbsp;</td>
</tr>
<tr>
<td height="30" width="130" align=RIGHT>'.$t_salary_amt.'</td>
<td height="30" width="130" align=RIGHT>'.$t_cost_of_living.'</td>
<td height="30" width="130" align=RIGHT>'.$t_diligent.'</td>
<td height="30" width="130" align=RIGHT>'.$t_commission.'</td>
<td height="30" width="130" align=RIGHT>'.$t_ot.'</td>
<td height="30" width="130" align=RIGHT>&nbsp;</td>
</tr>
<tr>
<td height="30" width="130" align=CENTER>ค่าพาหนะสะสม</td>
<td height="30" width="130" align=CENTER>ค่าเสื่อมพาหนะสะสม</td>
<td height="30" width="130" align=CENTER>ค่าโทรศัพท์สะสม </td>
<td height="30" width="130" align=CENTER>ค่าอื่นๆสะสม</td>
<td height="30" width="130" align=CENTER>&nbsp;</td>
<td height="30" width="130" align=CENTER>รับรวมสะสม</td>
</tr>
<tr>
<td height="30" width="130" align=RIGHT>'.$t_fare.'</td>
<td height="30" width="130" align=RIGHT>'.$t_depreciation.'</td>
<td height="30" width="130" align=RIGHT>'.$t_tel_income.'</td>
<td height="30" width="130" align=RIGHT>'.$t_other_income.'</td>
<td height="30" width="130" align=RIGHT>&nbsp;</td>
<td height="30" width="130" align=RIGHT>'.$t_total_income.'</td>
</tr>
<tr>
<td height="30" width="130" align=CENTER>ภาษีสะสม</td>
<td height="30" width="130" align=CENTER>ประกันสังคมสะสม</td>
<td height="30" width="130" align=CENTER>&nbsp;</td>
<td height="30" width="130" align=CENTER>&nbsp;</td>
<td height="30" width="130" align=CENTER>&nbsp;</td>
<td height="30" width="130" align=CENTER>ภาษีและประกันสังคมสะสม</td>
</tr>
<tr>
<td height="30" width="130" align=RIGHT>'.$tax.'</td>
<td height="30" width="130" align=RIGHT>'.$t_social_amt.'</td>
<td height="30" width="130" align=RIGHT>&nbsp;</td>
<td height="30" width="130" align=RIGHT>&nbsp;</td>
<td height="30" width="130" align=CENTER>&nbsp;</td>
<td height="30" width="130" align=RIGHT>'.$t_total_tax_so.'</td>
</tr>
<tr>
<td height="30" width="130" align=CENTER>วันรับเข้าทำงาน</td>
<td height="30" width="130" align=CENTER>ธนาคาร</td>
<td height="30" width="130" align=CENTER>ประเภทบัญชี</td>
<td height="30" width="130" align=CENTER>เลขที่บัญชี</td>
<td height="30" width="130" align=CENTER>ชำระโดย</td>';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','B',12);	
		$text= '<td height="30" width="130" align=CENTER>รับสุทธิสะสม ถึงวันนี้</td>';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','',12);	
		$text= '</tr>
<tr>
<td height="30" width="130" align=CENTER>'.date_ch_form_c2($user_start).'</td>
<td height="30" width="130" align=CENTER>'.$bank_name.'</td>
<td height="30" width="130" align=CENTER>'.$bank_acc_type.'</td>
<td height="30" width="130" align=CENTER>'.$bank_acc_no.'</td>
<td height="30" width="130" align=CENTER>'.$pay_by.'</td>';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','B',12);	
		$text= '<td height="30" width="130" align=RIGHT>'.$t_total_net.'</td>
		';$pdf->WriteHTML($text);	
		$pdf->SetFont('AngsanaNew','',12);	
		$text= '</tr>
<tr>
<td height="30" width="130" align=CENTER></td>
<td height="30" width="130" align=CENTER></td>
<td height="30" width="130" align=CENTER></td>
<td height="30" width="130" align=CENTER></td>
<td height="30" width="130" align=CENTER></td>
<td height="30" width="130" align=CENTER></td>
</tr>
</table>';
//echo 11;
//$pdf->WriteHTML($text);	
$pdf->writeHTML($text, true, false, true, false, '');							
$pdf->Output();
?>