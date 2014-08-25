<?php
include("../config/config.php");
require('../thaipdfclass.php');
include("../nw/join_cal/function_join.php");		

$pdf=new ThaiPDF('P' ,'mm','slip_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

//$recid=pg_escape_string($_POST["idno_names"]);
$recid=pg_escape_string($_GET["id"]);
$trid=trim($recid);
$IDNO2=pg_escape_string($_GET["idno"]);

$srid=substr($trid,2,1);
if($srid=="K")
{
  echo "รับเงินรับฝากแล้ว";
}
else
{



 
 $qry=pg_query("select \"idno\", \"discount\", \"money\", \"vat\", \"paydetail\", \"payby\", \"pd2\"
				from print_any_receipt('$recid')");
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
			$qry_con=pg_query("select \"R_Receipt\", \"R_Date\", \"Cancel\", \"CusFullname\" from \"Fr\" WHERE \"R_Receipt\" ='$recid'");
			$resrec=pg_fetch_array($qry_con);
				$rec_id=$resrec["R_Receipt"];
				$rec_date=$resrec["R_Date"];
				$Cancel=$resrec["Cancel"];
				$CusFullname=$resrec["CusFullname"];
		}elseif($srid=='N'){
			$qry_con=pg_query("select \"O_RECEIPT\", \"O_DATE\", \"Cancel\" from \"FOtherpay\" WHERE \"O_RECEIPT\" ='$recid'");
			$resrec=pg_fetch_array($qry_con);
				$rec_id=$resrec["O_RECEIPT"];
				$rec_date=$resrec["O_DATE"];
				$Cancel=$resrec["Cancel"];
				//$CusFullname=$resrec["CusFullname"];
		}elseif($srid=='V'){
			$qry_con=pg_query("select \"V_Receipt\",\"V_Date\",\"Cancel\" from \"FVat\" WHERE \"V_Receipt\" ='$recid'");
			$resrec=pg_fetch_array($qry_con);
				$rec_id=$resrec["V_Receipt"];
				$rec_date=$resrec["V_Date"];
				$Cancel=$resrec["Cancel"];
		}
		
	$cuslist=pg_query("select A.\"CusID\", B.\"CusID\"  from \"VContact\" A  
				     LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\" = B.\"CusID\" 
				     WHERE A.\"IDNO\" ='$idno' ");
	$num_cuslist=pg_num_rows($cuslist);
   
	$qry_dch=pg_query("select \"refreceipt\" from \"FTACCheque\" WHERE \"refreceipt\" = '$recid' ");
	$num_dch=pg_num_rows($qry_dch);
	
	$qry_dtr=pg_query("select \"refreceipt\" from \"FTACTran\" WHERE \"refreceipt\" = '$recid' ");
	$num_dtr=pg_num_rows($qry_dtr);
	
	if($num_cuslist==0){
		if($num_dch >0){
			$cuslist=pg_query("select a.\"COID\",\"RadioNum\",a.\"carregis\",a.\"fullname\" AS full_name,
				\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"AmtPay\" from \"FTACCheque\" a
				left join \"RadioContract\" d on a.\"COID\"=d.\"COID\"
				left join \"GroupCus_Active\" b on d.\"RadioRelationID\"=b.\"GroupCusID\" and b.\"CusState\"='0'
				left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\" 
				where  a.\"COID\"='$IDNO2' and \"refreceipt\"='$recid'");
		}else{
			if($num_dtr==0){
				$cuslist=pg_query("select \"COID\",\"RadioNum\",\"RadioCar\" as carregis,(((btrim(c.\"A_FIRNAME\"::text) || ' '::text) || btrim(c.\"A_NAME\"::text)) || ' '::text) || btrim(c.\"A_SIRNAME\"::text) AS full_name,
					\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"AmtPay\" from \"RadioContract\" a
				left join \"GroupCus_Active\" b on a.\"RadioRelationID\"=b.\"GroupCusID\" and b.\"CusState\"='0'
				left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\" 
				left join \"FCash\" d on a.\"COID\"=d.\"IDNO\"
				where a.\"COID\"='$IDNO2' and \"refreceipt\"='$recid'");
			}else{
				$cuslist=pg_query("select a.\"COID\",\"RadioNum\",a.\"carregis\",a.\"fullname\" AS full_name,
					\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"AmtPay\" from \"FTACTran\" a
					left join \"RadioContract\" d on a.\"COID\"=d.\"COID\"
					left join \"GroupCus_Active\" b on d.\"RadioRelationID\"=b.\"GroupCusID\" and b.\"CusState\"='0'
					left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\" 
					where  a.\"COID\"='$IDNO2' and \"refreceipt\"='$recid'");
			}
		}
		$num_cusradio=pg_num_rows($cuslist);
	}else{
		if($num_dch >0){
			$cuslist2=pg_query("select a.\"carregis\",a.\"fullname\" from \"FTACCheque\" a
			where  a.\"COID\"='$IDNO2' and \"refreceipt\"='$recid'");
			$rescus2=pg_fetch_array($cuslist2);
				$carregis=$rescus2["carregis"];
				$fullname=$rescus2["fullname"];
		}else if($num_dtr >0){
			$cuslist2=pg_query("select a.\"carregis\",a.\"fullname\" from \"FTACTran\" a
			where  a.\"COID\"='$IDNO2' and \"refreceipt\"='$recid'");
			$rescus2=pg_fetch_array($cuslist2);
				$carregis=$rescus2["carregis"];
				$fullname=$rescus2["fullname"];
		}
	}
	
	$rescus=pg_fetch_array($cuslist);
	
	// หารหัสลูกค้าคนล่าสุด
	$qry_lastCus = pg_query("select \"cusid\" from \"VJoinMain\" where \"idno\" = '$idno' ");
	$lastCusID = pg_fetch_result($qry_lastCus,0);
	
	if($lastCusID != "")
	{
		$cuslistLast = pg_query("select \"A_SOI\", \"A_RD\", \"A_NO\", \"A_SUBNO\", \"A_TUM\"
								from \"Fa1\" WHERE \"CusID\" = '$lastCusID' ");
		$num_cuslistLast = pg_num_rows($cuslistLast);
		$rescusLast = pg_fetch_array($cuslistLast);
	}
	
	if($num_cuslistLast == 1)
	{
		if(trim($rescusLast["A_SOI"])=="")
		{
		 $s_soi="";
		}
		else
		{
		 $s_soi=" ซอย ".trim($rescusLast["A_SOI"]);
		}
		if(trim($rescusLast["A_RD"])==""){
			$s_rd="";
		}else{
			$s_rd="ถนน ".trim($rescusLast["A_RD"]);
		}
			
		$cus_add=trim($rescusLast["A_NO"])."  ม.".trim($rescusLast["A_SUBNO"])." ".$s_soi;
		$cus_add2=$s_rd." ตำบล/แขวง ".trim($rescusLast["A_TUM"]);
		
		// หาชื่อเต็ม
		$qry_fullnameLast = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$lastCusID' ");
		$fullname = pg_fetch_result($qry_fullnameLast,0);
	}
	else
	{
		if(trim($rescus["A_SOI"])=="")
		{
		 $s_soi="";
		}
		else
		{
		 $s_soi=" ซอย ".trim($rescus["A_SOI"]);
		}
		if(trim($rescus["A_RD"])==""){
			$s_rd="";
		}else{
			$s_rd="ถนน ".trim($rescus["A_RD"]);
		}
			
		$cus_add=trim($rescus["A_NO"])."  ม.".trim($rescus["A_SUBNO"])." ".$s_soi;
		$cus_add2=$s_rd." ตำบล/แขวง ".trim($rescus["A_TUM"]);
		
		if($fullname ==""){
			$fullname=$rescus["full_name"];
		}else{
			$fullname=$fullname;
		}
	}
	
	if($CusFullname != "") // ถ้ามีข้อมูลในตารางใบเสร็จ ให้ใช้ข้อมูลจากตารางดังกล่าว
	{
		$fullname = $CusFullname;
	}
	
	$c_year=$rescus["C_YEAR"];
	
	
	if($carregis ==""){
		if($rescus["C_REGIS"]==""){
			$rec_regis=$rescus["car_regis"];
			$rec_cnumber=$rescus["gas_number"];
			$res_band=$rescus["gas_name"];
		}else{	
			$rec_regis=$rescus["C_REGIS"];
			$rec_cnumber=$rescus["C_CARNUM"];
			$res_band=$rescus["C_CARNAME"];
		}
	}else{
		$rec_regis=$carregis;
		if($rescus["C_REGIS"]==""){
			$rec_cnumber=$rescus["gas_number"];
			$res_band=$rescus["gas_name"];
		}else {	
			$rec_cnumber=$rescus["C_CARNUM"];
			$res_band=$rescus["C_CARNAME"];
		}
	}
	
	if($num_cusradio > 0){
		$rec_regis=$rescus["carregis"];
		$res_band=$rescus["RadioNum"];
		$c_year="-";
		$rec_cnumber="- ลูกค้านอก TR -";
		$idno=$IDNO2;
		$r_amt=$rescus["AmtPay"];
		$r_amt_bf=$rescus["AmtPay"];
		$chk_cl=0;
	}
		
	$p_month=$rescus["P_MONTH"];
	$p_vat=$rescus["P_VAT"];

 /*
  $exp_tax=$rescus["C_TAX_ExpDate"];
  $qrtdateExp=pg_query("select * from c_datethai('$exp_tax')");
  $expdate_tax=pg_fetch_result($qrtdateExp,0);
 */

// start PDF //

  $pdf->AddPage();
  $pdf->SetFont('AngsanaNew','',14);
  
	if($Cancel == 't'){
		$pdf->Image("image/cancel.png",60,20,100);  // พิมพ์ยกเลิกกรณีที่เป็นใบเสร็จที่ยกเลิกแล้ว
	}
  $col=10;
		
		$pdf->Text(163,35.5+$arow,$rec_id); //เลขที่ใบเสร็จ
		
		
		
		$qry_d=pg_query("select conversiondatetothaitext('$rec_date')");
		$res_cdate=pg_fetch_result($qry_d,0);
		$av_daterec=iconv('UTF-8','windows-874',$res_cdate);
		$pdf->Text(96,35.5+$arow,$av_daterec); //วันที่ใบเสร็จ
		
		$ext_join = receipt_d($IDNO2,$trid) ;
		
		if($ext_join[3][0]!="")$fullname=$ext_join[3][0];
		$av_fullname=iconv('UTF-8','windows-874',$fullname); 
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