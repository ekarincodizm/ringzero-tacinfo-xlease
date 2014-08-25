<?php
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();


include("../config/config.php");
require('../thaipdfclass.php');
include("../nw/join_cal/function_join.php");			
		

$pdf=new ThaiPDF('P' ,'mm','slip_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

$pid=$_GET["pid"];

$qry_dch=pg_query("select * from \"DetailTranpay\" WHERE \"PostID\"='$pid' ");
while($res_dch=pg_fetch_array($qry_dch))
{
  $a++;
  $recid=trim($res_dch["ReceiptNo"]);
  $sidno=$res_dch["IDNO"];


  $qry=pg_query("select * from print_any_receipt('$recid')");
  $res_qry=pg_fetch_array($qry);
  $idno = $res_qry["idno"];
  
   //chk_for close Fp
  $chk_cl=$res_qry["discount"];
  if($chk_cl==0)
  {
     $resdis="";
	 $str_dis="";
  }
  else
  {
    $resdis=number_format($chk_cl,2);
	$str_dis="ส่วนลด ";
  }
  
  //
  
  
  
  
  
  $money = $res_qry["money"]; 
  if($money == "")
   { 
     $money = "";
	 $r_amt=$money; 
   }
   else
   {
     $r_amt=$money;
   }
  
  $vat = $res_qry["vat"]; 
  if($vat=="0")
   { 
	 $resvat="";
	 $p_vatth=""; 
   }
  else
   {
     $resvat="ค่าภาษีมูลค่าเพิ่ม 7% ";
     $p_vatth=number_format($vat,2); 
   } 
  
  
  $paydetail = $res_qry["paydetail"];
  
  $paybyT=substr($res_qry["payby"],22,300);
  
  
  
     
   $dtl_pays = $res_qry["pd2"];
   if($dtl_pays=="")
   {
   $dtl_pay="";
   $r_amt_bf =$money+$vat;	  
   }
   else
   {	
	
	  $reslen=strlen($dtl_pays);
	  
	  if($reslen > 11)
	  {
	   $dash=" - ";
	  $start_p=substr($dtl_pays,6,4);
	  $vst_p=substr($dtl_pays,0,6);
	  $resst_p=$start_p+543;
	  
	  $vend_p=substr($dtl_pays,11,6);
	  $end_p=substr($dtl_pays,17,4);
	  $resend_p=$end_p+543;
	  
	  $dtl_pay="(".$vst_p.$resst_p.$dash.$vend_p.$resend_p.")" ;
	   
	  }
	  else
	  {
	   $dash="";
	   $start_p=substr($dtl_pays,6,4);
	   $vst_p=substr($dtl_pays,0,6);
	   $resst_p=$start_p+543;
	   $dtl_pay="(".$vst_p.$resst_p.")";
	  }
	  
	 
	  
	  $payby = $res_qry["payby"];
	  $r_amt_bf =$money+$vat;
	 
   }
  
		 $srid=substr($recid,2,1);
		if($srid=='R'){
			$qry_con=pg_query("select \"R_Receipt\",\"R_Date\" from \"Fr\" WHERE \"R_Receipt\" ='$recid'");
			$resrec=pg_fetch_array($qry_con);
				$rec_id=$resrec["R_Receipt"];
				$rec_date=$resrec["R_Date"];
		}elseif($srid=='N' || $srid=='K'){
			$qry_con=pg_query("select \"O_RECEIPT\",\"O_DATE\" from \"FOtherpay\" WHERE \"O_RECEIPT\" ='$recid'");
			$resrec=pg_fetch_array($qry_con);
				$rec_id=$resrec["O_RECEIPT"];
				$rec_date=$resrec["O_DATE"];
		}elseif($srid=='V'){
			$qry_con=pg_query("select \"V_Receipt\",\"V_Date\" from \"FVat\" WHERE \"V_Receipt\" ='$recid'");
			$resrec=pg_fetch_array($qry_con);
				$rec_id=$resrec["V_Receipt"];
				$rec_date=$resrec["V_Date"];
		}
		


  $cuslist=pg_query("select A.*,B.* from \"VContact\" A  
				     LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\" = B.\"CusID\" 
				     WHERE A.\"IDNO\" ='$idno' ");
  $rescus=pg_fetch_array($cuslist);
  if(trim($rescus["A_SOI"])=="")
	{
	 $s_soi="";
	}
	else
	{
	 $s_soi=" ซอย ".trim($rescus["A_SOI"]);
	}
  if(trim($rescus["A_RD"])=="")
	{
	 $s_rd="";
	}
	else
	{
	 $s_rd="ถนน ".trim($rescus["A_RD"]);
	}
		
	$cus_add=trim($rescus["A_NO"])."  ม.".trim($rescus["A_SUBNO"])." ".$s_soi;
	$cus_add2=$s_rd." ตำบล/แขวง ".trim($rescus["A_TUM"]);
	$c_year=$rescus["C_YEAR"];
	
	
  if($rescus["C_REGIS"]=="")
	{
	 $rec_regis=$rescus["car_regis"];
	 $rec_cnumber=$rescus["gas_number"];
	 $res_band=$rescus["gas_name"];
	}
  else
	{	
	 $rec_regis=$rescus["C_REGIS"];
	 $rec_cnumber=$rescus["C_CARNUM"];
	 $res_band=$rescus["C_CARNAME"];
	}
		
  $p_month=$rescus["P_MONTH"];
  $p_vat=$rescus["P_VAT"];

 /*
  $exp_tax=$rescus["C_TAX_ExpDate"];
  $qrtdateExp=pg_query("select * from c_datethai('$exp_tax')");
  $expdate_tax=pg_fetch_result($qrtdateExp,0);
 */
	
  $fullname=$rescus["full_name"];

// start PDF //

  $pdf->AddPage();
  $pdf->SetFont('AngsanaNew','',14);
		
  $col=10;
		
		$pdf->Text(163,35.5+$arow,$rec_id); //เลขที่ใบเสร็จ
		
		$qry_d=pg_query("select conversiondatetothaitext('$rec_date')");
		$res_cdate=pg_fetch_result($qry_d,0);
		$av_daterec=iconv('UTF-8','windows-874',$res_cdate);
		$pdf->Text(96,35.5+$arow,$av_daterec); //วันที่ใบเสร็จ
		
		$ext_join = receipt_d($idno,$recid) ; // Function เช็คค่าเข้าร่วม
		if($ext_join[3][0]!="")$fullname=$ext_join[3][0];
		$av_fullname=iconv('UTF-8','windows-874',$fullname); 
		$pdf->Text(20+$col,41+$arow,$av_fullname); //ชื่อ - นามสกุล
		
		
		$av_fullname=iconv('UTF-8','windows-874',$idno); 
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
		
		
		
		
		
		$pdf->SetXY(138,83.5+$arow);
		$av_total=iconv('UTF-8','windows-874',number_format($r_amt,2));
		$pdf->MultiCell(50,5,$av_total,0,'R',0);//ค่างวด
		
		
		
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
		  	
  }		
/*
if (!file_exists($_SESSION["session_path_save_pdf"].$rec_id.".pdf")) { //check file exists
$pdf->Output($_SESSION["session_path_save_pdf"].$rec_id.".pdf", "F"); // save pdf
}
*/
$pdf->Output(); //open pdf
?>