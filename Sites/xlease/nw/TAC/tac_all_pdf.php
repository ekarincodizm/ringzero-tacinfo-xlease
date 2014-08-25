<?php
set_time_limit(0);
include("../../config/config.php");
require('../../thaipdfclass.php');
		
$datepicker = $_GET['datepicker'];
$yy = $_GET['yy'];
$mm = $_GET['mm'];
$ty = $_GET['ty'];
list($n_year,$n_month,$n_day) = split('-',$datepicker);

    $search_str = substr($n_year,2,2)."R".$n_month.$n_day;
        
$pdf=new ThaiPDF('P' ,'mm','slip_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();


//$qry_in=pg_query("SELECT * FROM \"Fr\" where \"R_Receipt\" LIKE '$search_str%' ORDER BY \"R_Receipt\" ASC ");
$qry_in=pg_query("select * from \"FOtherpay\" WHERE \"O_PRNDATE\"='$datepicker' AND (\"O_Type\"='165' OR \"O_Type\"='307') AND \"Cancel\"='FALSE' AND (\"PayType\"='TCQ' OR \"PayType\"='TTR') ORDER BY \"PayType\",\"O_DATE\" ASC");
while($res_in=pg_fetch_array($qry_in)){
    $IDNO = $res_in["IDNO"];
	$O_DATE = $res_in["O_DATE"];
	$O_RECEIPT = $res_in["O_RECEIPT"];
	$O_MONEY = $res_in["O_MONEY"];
	$O_Type = $res_in["O_Type"];
	$O_BANK = $res_in["O_BANK"];
	$O_PRNDATE = $res_in["O_PRNDATE"];
	$PayType = $res_in["PayType"];
	$Cancel = $res_in["Cancel"];
	$O_memo = $res_in["O_memo"];
	$RefAnyID = $res_in["RefAnyID"];

$recid=$O_RECEIPT;
$trid=trim($recid);


//-----------------
	
$qry=pg_query("select * from print_any_receipt('$recid')");
 $res_qry=pg_fetch_array($qry);
  $IDNO = $res_qry["idno"];
  
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
  
//-----------------

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
				     WHERE A.\"IDNO\" ='$IDNO' ");
	$num_cuslist=pg_num_rows($cuslist);				 
					 
					 //11111111111111111111111111111111
					 
					$qry_dch=pg_query("select * from \"FTACCheque\" WHERE \"refreceipt\"='$recid' ");
	$num_dch=pg_num_rows($qry_dch);
	
	$qry_dtr=pg_query("select * from \"FTACTran\" WHERE \"refreceipt\"='$recid' ");
	$num_dtr=pg_num_rows($qry_dtr);

	
	if($num_cuslist==0){
		if($num_dch >0){
			$cuslist=pg_query("select a.\"COID\",\"RadioNum\",a.\"carregis\",a.\"fullname\" AS full_name,
				\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"AmtPay\" from \"FTACCheque\" a
				left join \"RadioContract\" d on a.\"COID\"=d.\"COID\"
				left join \"GroupCus_Active\" b on d.\"RadioRelationID\"=b.\"GroupCusID\" and b.\"CusState\"='0'
				left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\" 
				where  a.\"COID\"='$IDNO' and \"refreceipt\"='$recid'");
		}else{
			if($num_dtr==0){
				$cuslist=pg_query("select \"COID\",\"RadioNum\",\"RadioCar\" as carregis,(((btrim(c.\"A_FIRNAME\"::text) || ' '::text) || btrim(c.\"A_NAME\"::text)) || ' '::text) || btrim(c.\"A_SIRNAME\"::text) AS full_name,
					\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"AmtPay\" from \"RadioContract\" a
				left join \"GroupCus_Active\" b on a.\"RadioRelationID\"=b.\"GroupCusID\" and b.\"CusState\"='0'
				left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\" 
				left join \"FCash\" d on a.\"COID\"=d.\"IDNO\"
				where a.\"COID\"='$IDNO' and \"refreceipt\"='$recid'");
			}else{
				$cuslist=pg_query("select a.\"COID\",\"RadioNum\",a.\"carregis\",a.\"fullname\" AS full_name,
					\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"AmtPay\" from \"FTACTran\" a
					left join \"RadioContract\" d on a.\"COID\"=d.\"COID\"
					left join \"GroupCus_Active\" b on d.\"RadioRelationID\"=b.\"GroupCusID\" and b.\"CusState\"='0'
					left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\" 
					where  a.\"COID\"='$IDNO' and \"refreceipt\"='$recid'");
			}
		}
	}
					 
					 //111111111111111111111111111
					 
					 
					 
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
	
	//11111111111111111111111111111111
	/*
	$qry_dch=pg_query("select * from \"FTACCheque\" WHERE \"refreceipt\"='$recid' ");
	$num_dch=pg_num_rows($qry_dch);
	
	$qry_dtr=pg_query("select * from \"FTACTran\" WHERE \"refreceipt\"='$recid' ");
	$num_dtr=pg_num_rows($qry_dtr);
	
	if($num_cuslist==0){
		if($num_dch >0){
			$cuslist=pg_query("select a.\"COID\",\"RadioNum\",a.\"carregis\",a.\"fullname\" AS full_name,
				\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"AmtPay\" from \"FTACCheque\" a
				left join \"RadioContract\" d on a.\"COID\"=d.\"COID\"
				left join \"GroupCus_Active\" b on d.\"RadioRelationID\"=b.\"GroupCusID\" and b.\"CusState\"='0'
				left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\" 
				where  a.\"COID\"='$IDNO' and \"refreceipt\"='$recid'");
		}else{
			if($num_dtr==0){
				$cuslist=pg_query("select \"COID\",\"RadioNum\",\"RadioCar\" as carregis,(((btrim(c.\"A_FIRNAME\"::text) || ' '::text) || btrim(c.\"A_NAME\"::text)) || ' '::text) || btrim(c.\"A_SIRNAME\"::text) AS full_name,
					\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"AmtPay\" from \"RadioContract\" a
				left join \"GroupCus_Active\" b on a.\"RadioRelationID\"=b.\"GroupCusID\" and b.\"CusState\"='0'
				left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\" 
				left join \"FCash\" d on a.\"COID\"=d.\"IDNO\"
				where a.\"COID\"='$IDNO' and \"refreceipt\"='$recid'");
			}else{
				$cuslist=pg_query("select a.\"COID\",\"RadioNum\",a.\"carregis\",a.\"fullname\" AS full_name,
					\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"AmtPay\" from \"FTACTran\" a
					left join \"RadioContract\" d on a.\"COID\"=d.\"COID\"
					left join \"GroupCus_Active\" b on d.\"RadioRelationID\"=b.\"GroupCusID\" and b.\"CusState\"='0'
					left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\" 
					where  a.\"COID\"='$IDNO' and \"refreceipt\"='$recid'");
			}
		}
	}*/
					 
					 //111111111111111111111111111

$fullname=$rescus["full_name"];

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
	
	
	
//0000000000000000000000000000000

if($PayType=="TCQ")
{
	$sql_tcq=pg_query("select * from \"FTACCheque\" WHERE \"COID\" = '$IDNO'");
	$row_tcq = pg_num_rows($sql_tcq);
	if($row_tcq>0)
	{
		while($TCQ=pg_fetch_array($sql_tcq))
		{
			$fullname = $TCQ["fullname"];
			$rec_regis = $TCQ["carregis"];
		}
	}
}

if($PayType=="TTR")
{
	$sql_ttr=pg_query("select * from \"FTACTran\" WHERE \"COID\" = '$IDNO'");
	$row_ttr = pg_num_rows($sql_ttr);
	if($row_ttr>0)
	{
		while($TTR=pg_fetch_array($sql_ttr))
		{
			$fullname = $TTR["fullname"];
			$rec_regis = $TTR["carregis"];
		}
	}
}
	
	
// start PDF //
$pdf->AddPage();
$pdf->SetFont('AngsanaNew','',14);

$col=10;

$pdf->Text(163,35.5+$arow,$O_RECEIPT); //เลขที่ใบเสร็จ

$qry_d=pg_query("select conversiondatetothaitext('$O_DATE')");
$res_cdate=pg_fetch_result($qry_d,0);
$av_daterec=iconv('UTF-8','windows-874',$res_cdate);
$pdf->Text(96,35.5+$arow,$av_daterec); //วันที่ใบเสร็จ

$av_fullname=iconv('UTF-8','windows-874',$fullname); 
$pdf->Text(20+$col,41+$arow,$av_fullname); //ชื่อ - นามสกุล

$av_fullname=iconv('UTF-8','windows-874',$IDNO); 
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
		  $pdf->Image('images/ampai.jpg',40,124+$arow,18,5);

}
$pdf->Output();
?>