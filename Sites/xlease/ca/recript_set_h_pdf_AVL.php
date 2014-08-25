<?php
set_time_limit(0);
include("../config/config.php");
require('../thaipdfclass.php');
		
$datepicker = pg_escape_string($_GET['datepicker']);
$yy = pg_escape_string($_GET['yy']);
$mm = pg_escape_string($_GET['mm']);
$ty = pg_escape_string($_GET['ty']);
list($n_year,$n_month,$n_day) = split('-',$datepicker);

if($ty == 1){ // ประจำวัน
    $search_str = substr($n_year,2,2)."R".$n_month.$n_day;
}else{ // ประจำเดือน
    $search_str = substr($yy,2,2)."R".$mm;
}

$pdf=new ThaiPDF('P' ,'mm','slip_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

$qry_in=pg_query("SELECT * FROM \"Fr\" where \"R_Receipt\" LIKE '$search_str%' ORDER BY \"R_Receipt\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $R_Receipt = $res_in["R_Receipt"];

$recid=$R_Receipt;
$trid=trim($recid);

$srid=substr($trid,2,1);
if($srid=="K")
{
    continue;
}
else
{



 
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
     $resvat="ภาษีมูลค่าเพิ่ม 7% ";
     $p_vatth=number_format($vat,2); 
   } 
  
  
  $paydetail = $res_qry["paydetail"];
  $paybyT=$res_qry["payby"];
  
     
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
		}elseif($srid=='N'){
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
	 $s_rd=" ถนน ".trim($rescus["A_RD"]);
	}
		
	$cus_add="ที่อยู่ : ".trim($rescus["A_NO"])."  ม.".trim($rescus["A_SUBNO"]).$s_soi." ".$s_rd."  ตำบล/แขวง ".trim($rescus["A_TUM"]);
	
  if($rescus["C_REGIS"]=="")
	{
	 $rec_regis="ทะเบียน ".$rescus["car_regis"];
	 $rec_cnumber="เลขถังแก๊ส ".$rescus["gas_number"];
	 $res_band="ยี่ห้อแก๊ส ".$rescus["gas_name"];
	}
  else
	{	
	 $rec_regis="ทะเบียน ".$rescus["C_REGIS"];
	 $rec_cnumber="เลขตัวถัง ".$rescus["C_CARNUM"];
	 $res_band="ยี่ห้อรถ ".$rescus["C_CARNAME"];
	}
		
  $p_month=$rescus["P_MONTH"];
  $p_vat=$rescus["P_VAT"];
	
  $fullname=$rescus["full_name"];

// start PDF //

  $pdf->AddPage();
  $pdf->SetFont('AngsanaNew','',16);
		
  $col=10;
		
		$pdf->Text(163,25.0+$arow,$rec_id); //เลขที่ใบเสร็จ
		
		
		
		$qry_d=pg_query("select conversiondatetothaitext('$rec_date')");
		$res_cdate=pg_fetch_result($qry_d,0);
		$av_daterec=iconv('UTF-8','windows-874',$res_cdate);
		$pdf->Text(150,33.5+$arow,$av_daterec); //วันที่ใบเสร็จ
		
		
		$av_fullname=iconv('UTF-8','windows-874',"ผู้เช่าซื้อ : ".$fullname."   "."(".$idno.")"); 
		$pdf->Text(10+$col,48+$arow,$av_fullname); //ชื่อ - นามสกุล
		
		
		$pdf->SetXY(9+$col,50+$arow);
		$cus_add_icon=iconv('UTF-8','windows-874',$cus_add);
		$pdf->MultiCell(180,5,$cus_add_icon,0,'L',0);//ที่อยู่
		
		$pdf->SetXY(9+$col,55+$arow);
		$cus_add_pro=iconv('UTF-8','windows-874',"อำเภอ/เขต ".trim($rescus["A_AUM"])." จังหวัด ".trim($rescus["A_PRO"]));
		$pdf->MultiCell(180,5,$cus_add_pro,0,'L',0);//จังหวัด
		
		$pdf->Text(10+$col,62+$arow,"_______________________________________________________"); //line
		
		$av_band=iconv('UTF-8','windows-874',$res_band." ".$rec_regis." ".$rec_cnumber); 
		$pdf->Text(10+$col,69+$arow,$av_band); //ยี่ห้อรถ

		
		$pdf->Text(10+$col,73+$arow,"_______________________________________________________"); //line
		
		$av_total_con=iconv('UTF-8','windows-874',"ชำระค่า : ".$paydetail); 
		$pdf->Text(10+$col,80+$arow,$av_total_con); //รายการจ่าย
		
		
		$av_pay=iconv('UTF-8','windows-874',"               ".$dtl_pay); 
		$pdf->Text(10+$col,85+$arow,$av_pay); //เดือนที่จ่าย
		
		
	$pdf->SetXY(125,77+$arow);
		$av_ttotal=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(50,5,$av_ttotal,0,'L',0);// งวด
		
		$pdf->SetXY(125,83+$arow);
		$av_tvat=iconv('UTF-8','windows-874',$resvat);
		$pdf->MultiCell(50,5,$av_tvat,0,'L',0);// vat
		
		$pdf->SetXY(125,89+$arow);
		$av_strdis=iconv('UTF-8','windows-874',$str_dis);
		$pdf->MultiCell(50,5,$av_strdis,0,'L',0);// discount
		
		
		
		$pdf->SetXY(138,77+$arow);
		$av_total=iconv('UTF-8','windows-874',number_format($r_amt,2));
		$pdf->MultiCell(50,5,$av_total,0,'R',0);//งวด
		
		$pdf->SetXY(138,83+$arow);
		$av_vat=iconv('UTF-8','windows-874',$p_vatth);
		$pdf->MultiCell(50,5,$av_vat,0,'R',0);//vat
		
		
		$pdf->SetXY(138,89+$arow);
		$av_resdis=iconv('UTF-8','windows-874',$resdis);
		$pdf->MultiCell(50,5,$av_resdis,0,'R',0);//vat
		
		
		$pdf->SetXY(20,92+$arow);
		$av_payby=iconv('UTF-8','windows-874',$paybyT);
		$pdf->MultiCell(150,5,$av_payby,0,'L',0);//ชำระโดย
		
		
		
		$pdf->SetXY(138,104+$arow);
		
		$ms_totalamt=$r_amt_bf-$chk_cl;
		$av_sum=iconv('UTF-8','windows-874',number_format($ms_totalamt,2));
		$pdf->MultiCell(50,5,$av_sum,0,'R',0);//total
		
				
		$trntotal=pg_query("select conversionnumtothaitext($ms_totalamt)");
		$restrn=pg_fetch_result($trntotal,0);
        
		
        
		$pdf->SetXY(20,104+$arow);
		$av_trnnumber=iconv('UTF-8','windows-874',"=(".$restrn.")=");
		$pdf->MultiCell(150,5,$av_trnnumber,0,'L',0);//แปลงตัวหนังสือไทย
		
		  //signature //
		  $pdf->Image('image/signature.jpg',40,113+$arow,23,15);
		  	



}
}
$pdf->Output();
?>