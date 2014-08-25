<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
include("../../config/config.php");
require('../../thaipdfclass.php');
		

$pdf=new ThaiPDF('P' ,'mm','slip_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

$pid=$_GET["pid"];



$qry_dch=pg_query("select * from \"FCash\" WHERE \"PostID\"='$pid' ");
while($res_dch=pg_fetch_array($qry_dch))
{
  $a++;
  $recid=trim($res_dch["refreceipt"]);
  
  
  
  
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
		


  $cuslist=pg_query("select * from \"RadioContract\" a
		left join \"GroupCus_Active\" b on a.\"RadioRelationID\"=b.\"GroupCusID\" and b.\"CusState\"='0'
		left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\"
		where a.\"COID\"='$idno' ");
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
	$c_year="-";
	

	$rec_regis=$rescus["RadioCar"];
	$res_band=$rescus["RadioNum"];
	$rec_cnumber="- ลูกค้านอก TR -";
		
  $p_month=$rescus["P_MONTH"];
  $p_vat=$rescus["P_VAT"];

  $exp_tax=$rescus["C_TAX_ExpDate"];
  
  if($exp_tax=="")
  {
  $expdate_tax="";
  }
  else
  {
  
  $qrtdateExp=pg_query("select * from c_datethai('$exp_tax')");
  $expdate_tax=pg_fetch_result($qrtdateExp,0);
  }
	
  $fullname=trim($rescus["A_FIRNAME"]).trim($rescus["A_NAME"])." ".trim($rescus["A_SIRNAME"]);

// start PDF //
    $srid=substr($recid,2,1);
	if($srid=="K")
	{
	  
	  
	}
	else
	{


  

  $pdf->AddPage();
  $pdf->SetFont('AngsanaNew','',14);
		
  $col=10;
		
		$pdf->Text(163,35.5+$arow,$rec_id); //เลขที่ใบเสร็จ
		
		
		
		$qry_d=pg_query("select conversiondatetothaitext('$rec_date')");
		$res_cdate=pg_fetch_result($qry_d,0);
		$av_daterec=iconv('UTF-8','windows-874',$res_cdate);
		$pdf->Text(96,35.5+$arow,$av_daterec); //วันที่ใบเสร็จ
		
		
		$av_fullname=iconv('UTF-8','windows-874',$fullname); 
		$pdf->Text(20+$col,41+$arow,$av_fullname); //ชื่อ - นามสกุล
		
		
		$av_fullname=iconv('UTF-8','windows-874',$idno); 
		$pdf->Text(153+$col,42.5+$arow,$av_fullname); //IDNO
		
		
		$pdf->SetXY(19+$col,43+$arow);
		$cus_add_icon=iconv('UTF-8','windows-874',$cus_add);
		$pdf->MultiCell(175,5,$cus_add_icon,0,'L',0);//ที่อยู่
		
		
		
		$pdf->SetXY(19+$col,48+$arow);
		$cus_add_icon=iconv('UTF-8','windows-874',$cus_add2);
		$pdf->MultiCell(175,5,$cus_add_icon,0,'L',0);//ถนน
		
		
		$pdf->SetXY(19+$col,53+$arow);
		$cus_add_pro=iconv('UTF-8','windows-874',"อำเภอ/เขต ".trim($rescus["A_AUM"])." จังหวัด ".trim($rescus["A_PRO"]));
		$pdf->MultiCell(175,5,$cus_add_pro,0,'L',0);//จังหวัด
		
		
		$av_fullname=iconv('UTF-8','windows-874',$res_band); 
		$pdf->Text(145+$col,48+$arow,$av_fullname); //ยี่ห้อรถ
		
		$av_fullname=iconv('UTF-8','windows-874',$rec_regis); 
		$pdf->Text(145+$col,54.5+$arow,$av_fullname); //ทะเบียน
		
		$av_fullname=iconv('UTF-8','windows-874',$c_year); 
		$pdf->Text(175+$col,54.5+$arow,$av_fullname); //ปี
		
		$av_fullname=iconv('UTF-8','windows-874',$rec_cnumber); 
		$pdf->Text(145+$col,61+$arow,$av_fullname); //ตัวถัง
			
			
			
			
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
   }
$pdf->Output();
?>