<?php
include("../../config/config.php");
require('../../thaipdfclass.php');
	

$pdf=new ThaiPDF('P' ,'mm','slip_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

//$recid=$_POST["idno_names"];
$recid=$_GET["id"];
$trid=trim($recid);
$IDNO2=$_GET["idno"];

	$qry_fr2=pg_query("SELECT user_id_slip, month, year, user_fullname, user_dep, user_div, 
       user_pos, user_start, bank_name, comp_name, bank_acc_type, salary_type, 
       salary_amt, cost_of_living, diligent, ot, commission, other_income, 
       fare, depreciation, tel_income, total_income, tax, fine_late, 
       social_amt, other_deduct, fine_incomplete, total_deduct, total_net, 
       t_salary_amt, t_cost_of_living, t_diligent, t_ot, t_commission, 
       t_other_income, t_fare, t_tel_income, t_depreciation, t_total_income, 
       t_tax, t_fine_late, t_social_amt, t_other_deduct, t_fine_incomplete, 
       t_total_deduct, t_total_net, pay_type, social_rate, 
       user_id_sys,bank_acc_no,salary_per_day ,salary_note 
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
			}

			}

// start PDF //

  $pdf->AddPage();
  $pdf->SetFont('AngsanaNew','',14);
		
  $col=10;
		
		$pdf->Text(163,35.5+$arow,$rec_id); //เลขที่ใบเสร็จ
		
		
		
		//$qry_d=pg_query("select conversiondatetothaitext('$rec_date')");
		//$res_cdate=pg_fetch_result($qry_d,0);
		$av_daterec=iconv('UTF-8','windows-874',$comp_name);
		$pdf->Text(96,35.5+$arow,$av_daterec); //ชื่อบริษัท
		
		$ext_join = receipt_d($IDNO2,$trid) ;
		
		
		$av_fullname=iconv('UTF-8','windows-874',$user_fullname); 
		$pdf->Text(20+$col,41+$arow,$av_fullname); //ชื่อ - นามสกุล
		
		
		$av_fullname=iconv('UTF-8','windows-874',$IDNO2); 
		$pdf->Text(153+$col,42.5+$arow,$av_fullname); //IDNO
		
		if($ext_join[3][1]==""){//ไม่ใช่ค่าเข้าร่วม
		$pdf->SetXY(19+$col,43+$arow);
		$cus_add_icon=iconv('UTF-8','windows-874',$cus_add);
		$pdf->MultiCell(175,5,$cus_add_icon,0,'L',0);//ที่อยู่
		
		
		
		$pdf->SetXY(19+$col,48+$arow);
		$cus_add_icon=iconv('UTF-8','windows-874',$cus_add2);
		$pdf->MultiCell(175,5,$cus_add_icon,0,'L',0);//ถนน
		
		
		$pdf->SetXY(19+$col,53+$arow);
		$cus_add_pro=iconv('UTF-8','windows-874',"อำเภอ/เขต ".trim($rescus["A_AUM"])." จังหวัด ".trim($rescus["A_PRO"]));
		$pdf->MultiCell(175,5,$cus_add_pro,0,'L',0);//จังหวัด
		
			}else{ //ที่อยู่ค่าเข้าร่วม
			$pdf->SetXY(19+$col,43+$arow);
		$cus_add_icon=iconv('UTF-8','windows-874',$ext_join[3][1]);
		$pdf->MultiCell(65,5,$cus_add_icon,0,'L',0);//ที่อยู่	
			
		}
		
		$av_fullname=iconv('UTF-8','windows-874',$res_band); 
		$pdf->Text(145+$col,48+$arow,$av_fullname); //ยี่ห้อรถ
		
		$av_fullname=iconv('UTF-8','windows-874',$rec_regis); 
		$pdf->Text(145+$col,54.5+$arow,$av_fullname); //ทะเบียน
		
		$av_fullname=iconv('UTF-8','windows-874',$c_year); 
		$pdf->Text(175+$col,54.5+$arow,$av_fullname); //ปี
		
		$av_fullname=iconv('UTF-8','windows-874',$rec_cnumber); 
		$pdf->Text(145+$col,61+$arow,$av_fullname); //ตัวถัง
		
		
		
		//$pdf->Text(10+$col,62+$arow,"_______________________________________________________"); //line
		
		//$av_band=iconv('UTF-8','windows-874',$res_band." ".$rec_regis." ".$rec_cnumber); 
		//$pdf->Text(10+$col,69+$arow,$av_band); //ยี่ห้อรถ

		
		//$pdf->Text(10+$col,73+$arow,"_______________________________________________________"); //line
	
	
		$pdf->SetFont('AngsanaNew','',12);	
			
				if($ext_join[0][1]!=""){
		$av_total_con=iconv('UTF-8','windows-874',$ext_join[0][0]); 
		$pdf->Text(105+$col,80+$arow,$av_total_con); //รายการจ่าย
		
				
		$pdf->SetXY(138,76+$arow);
		$av_total=iconv('UTF-8','windows-874',number_format($ext_join[0][1],2));
		$pdf->MultiCell(50,5,$av_total,0,'R',0);//ค่างวด
		
				}

					if($ext_join[1][1]!=""){
					$pdf->SetFont('AngsanaNew','',12);	
						
		$av_total_con=iconv('UTF-8','windows-874',$ext_join[1][0]); 
		$pdf->Text(105+$col,85+$arow,$av_total_con); //รายการจ่าย
		
				
		$pdf->SetXY(138,81+$arow);
		$av_total=iconv('UTF-8','windows-874',number_format($ext_join[1][1],2));
		$pdf->MultiCell(50,5,$av_total,0,'R',0);//ค่างวด
		
				}
				
					if($ext_join[2][1]!=""){
						$pdf->SetFont('AngsanaNew','',12);
						
		$av_total_con=iconv('UTF-8','windows-874',$ext_join[2][0]); 
		$pdf->Text(105+$col,90+$arow,$av_total_con); //รายการจ่าย
		
				
		$pdf->SetXY(138,86+$arow);
		$av_total=iconv('UTF-8','windows-874',number_format($ext_join[2][1],2));
		$pdf->MultiCell(50,5,$av_total,0,'R',0);//ค่างวด
		
				}
		
		$pdf->SetFont('AngsanaNew','',14);
		
	
		$av_total_con=iconv('UTF-8','windows-874',$paydetail); 
		$pdf->Text(115+$col,75+$arow,$av_total_con); //รายการจ่าย
		
		
		$av_pay=iconv('UTF-8','windows-874',$dtl_pay); 
		$pdf->Text(115+$col,80+$arow,$av_pay); //เดือนที่จ่าย
		
		
		$pdf->SetXY(125,77+$arow);
		$av_ttotal=iconv('UTF-8','windows-874'," ");
		$pdf->MultiCell(50,5,$av_ttotal,0,'L',0);// งวด
		
		$pdf->SetXY(110,89+$arow);
		$av_tvat=iconv('UTF-8','windows-874',$resvat);
		$pdf->MultiCell(50,5,$av_tvat,0,'L',0);// strvat
		
		
		
		
		if($ext_join[0][1]==""){	//ถ้าไม่ใช่ค่าเข้าร่วม
		$pdf->SetXY(138,83.5+$arow);
		$av_total=iconv('UTF-8','windows-874',number_format($r_amt,2));
		$pdf->MultiCell(50,5,$av_total,0,'R',0);//ค่างวด
		}
		
		
		$pdf->SetXY(65,78+$arow);
		$av_total=iconv('UTF-8','windows-874',$expdate_tax);
		$pdf->MultiCell(50,5,$av_total,0,'L',0);//วันค่อภาษี
		
		
		
		
		$pdf->SetXY(138,89+$arow);
		$av_vat=iconv('UTF-8','windows-874',$p_vatth);
		$pdf->MultiCell(50,5,$av_vat,0,'R',0);//vat
		
		
		$pdf->SetXY(138,97+$arow);
		$av_resdis=iconv('UTF-8','windows-874',$resdis);
		$pdf->MultiCell(50,5,$av_resdis,0,'R',0);//vat
		
		
		//$pdf->SetXY(138,93+$arow);
		//$av_strdis=iconv('UTF-8','windows-874',$str_dis);
		//$pdf->MultiCell(50,5,$av_strdis,0,'R',0);// discount
		
		
		
		$pdf->SetXY(26,114.9+$arow);
		$av_payby=iconv('UTF-8','windows-874',$paybyT);
		$pdf->MultiCell(150,5,$av_payby,0,'L',0);//ชำระโดย
		
		
		
		$pdf->SetXY(138,103+$arow);
		
		$ms_totalamt=$r_amt_bf-$chk_cl;
		$av_sum=iconv('UTF-8','windows-874',number_format($ms_totalamt,2));
		$pdf->MultiCell(50,5,$av_sum,0,'R',0);//total
		
				
		$trntotal=pg_query("select conversionnumtothaitext($ms_totalamt)");
		$restrn=pg_fetch_result($trntotal,0);
        
		
        
		$pdf->SetXY(115,109.5+$arow);
		$av_trnnumber=iconv('UTF-8','windows-874',"=(".$restrn.")=");
		$pdf->MultiCell(150,5,$av_trnnumber,0,'L',0);//แปลงตัวหนังสือไทย
		
		  //signature //
		  $pdf->Image('image/ampai.jpg',40,124+$arow,18,5);
		  	
/*
if (!file_exists($_SESSION["session_path_save_pdf"].$rec_id.".pdf")) { //check file exists
$pdf->Output($_SESSION["session_path_save_pdf"].$rec_id.".pdf", "F"); // save pdf
}
*/
$pdf->Output(); //open pdf
}
?>