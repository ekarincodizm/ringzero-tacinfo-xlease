<?php
include("../config/config.php");
	require('../thaipdfclass.php');
		
		
		$pdf=new ThaiPDF('P' ,'mm','slip_av');  
		$pdf->SetLeftMargin(0);
		$pdf->SetTopMargin(0);
		$pdf->SetThaiFont();

$pid=pg_escape_string($_GET["pid"]);
$qry_dch=pg_query("select * from \"DetailCheque\" WHERE \"PostID\"='$pid'  ");
while($res_dch=pg_fetch_array($qry_dch))
{
  $a++;
  //echo $res_dch["PostID"]."-".$res_dch["ReceiptNo"]."<br>";

  $recid=$res_dch["ReceiptNo"];



  //$recid=pg_escape_string($_POST["idno_names"]);

		
		$trid=trim($recid);
		
		$srid=substr($trid,2,1);
		
		if($srid=='R')
		{
		  		  
		  
		$qry_con=pg_query("select * from \"Fr\"
						   WHERE \"R_Receipt\" ='$recid' ");
		$resrec=pg_fetch_array($qry_con);
		$rec_id=$resrec["R_Receipt"];
		$rec_date=$resrec["R_Date"];
		$idno=$resrec["IDNO"];
		
		
		
		
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
		$res_band="ยี่ห้อรถ ".$rescus["C_CARNUM"];
		}
		
		$p_month=$rescus["P_MONTH"];
		$p_vat=$rescus["P_VAT"];
		
		
		
		//$sum_rec=$p_month+$p_vat;
		
		//$convthai=pg_query("select conversionnumtothaitext($sum_rec)");
		//$res_conv=pg_fetch_result($convthai,0);
		
		
		
		
		
		$qry_cons=pg_query("select A.*,B.*,C.* from \"Fr\" A 
						   LEFT OUTER JOIN \"DetailCheque\" B ON A.\"R_Receipt\"=B.\"ReceiptNo\"  
						   LEFT OUTER JOIN \"TypePay\" C ON C.\"TypeID\"=B.\"TypePay\" 
						   WHERE  A.\"R_Receipt\" ='$recid' ");
		$nurow=pg_num_rows($qry_cons);
		//$recr=pg_fetch_array($qry_cons);
		
		
		
		
		while($ressum=pg_fetch_array($qry_cons))
		{
		  $res_dc=$ressum["TypeID"];
		  $res_type=$ressum["TName"];
		  $res_vat=$ressum["UseVat"];
		  
		  $r_amt_bf=$ressum["CusAmount"]; 
		  
		  
		  $qduno=pg_query("select * from \"CusPayment\" WHERE \"DueNo\"='$ressum[R_DueNo]' order by \"DueNo\"  ");
		  $resdno=pg_fetch_array($qduno);
		  
		  $tmp_due=$tmp_due." ".$res_ddate=$resdno[DueDate];
		  $tmp_dno=$tmp_dno." ".$res_dno=$resdno[DueNo];
		}  
		
		
		
		if($res_vat=='t')
		{
		  
		  //$resvat="ภาษีมูลค่าเพิ่ม 7% ";
		  //$p_vatth=number_format($p_vat*$nurow,2);
		  $qryvat=pg_query("select amt_before_vat($r_amt_bf)");
		  $re_v=pg_fetch_result($qryvat,0);
		  $res_pvat=$r_amt_bf-$re_v;
		  
		  $r_amt=$re_v;
		  
		  $resvat="ภาษีมูลค่าเพิ่ม 7% ";
		  $p_vatth=$res_pvat;
		  
		}
		else
		{
		  $resvat="";
		  $p_vatth="";
		}
		
		
		$str_due=strlen($tmp_due);
		$str_dno=strlen($tmp_dno);  
		
		$st_dno=substr($tmp_dno,0,3);	
		$end_dno=substr($tmp_dno,str_dno-2,2);	
		$st_due=substr($tmp_due,0,11); 
		$end_due=substr($tmp_due,$str_due-11,11); 
		
		
		if($nurow==1)
		{
		
		   if($res_dc==1)
		  {
		   //$r_dueno="";
		   $r_dueno="ที่ ".trim($resrec["R_DueNo"])." / ".$rescus["P_TOTAL"];
		  }
		  else
		  {
		   $r_dueno=" ";
		   //$r_dueno="ที่ ".trim($resrec["R_DueNo"])." / ".$rescus["P_TOTAL"];
		  
		  }
		  
		  $de_total=" ชำระค่า : ".$res_type." ".$r_dueno;
		  $dtl_pay="";
		  
		}
		else
		{
		  $de_total=" ชำระค่า : ".$res_type."".$st_dno." - ".$end_dno ." / ".$rescus["P_TOTAL"];
		  $dtl_pay= "(".trim($st_due)." - ".trim($end_due).")";
		}
		
		
				   
		 
		
		
		
		$fullname=$rescus["full_name"];
		//echo "<br>".$rec_regis;
		//echo "<br>".$rec_cnumber;
		//echo "<br>".$rec_total;
		
		//echo "<br>".$de_total;
		//echo "<br>".$dtl_pay;
		//echo "<br>".$p_month;
		//echo "<br>".$p_vat;
		//echo "<br>".$res_conv;
		
		//echo "<br>".$tmp_due;
		
	
		$pdf->AddPage();
		$pdf->SetFont('AngsanaNew','',16);
		
		//$pdf->Image('image/paper_recfull.jpg',0,0,210,290);
		
		$col=10;
		
		//Real //
		
		$pdf->Text(163,25.0+$arow,$rec_id); //เลขที่ใบเสร็จ
		
		
		
		$qry_d=pg_query("select conversiondatetothaitext('$rec_date')");
		$res_cdate=pg_fetch_result($qry_d,0);
		$av_daterec=iconv('UTF-8','windows-874',$res_cdate);
		$pdf->Text(150,33.5+$arow,$av_daterec); //วันที่ใบเสร็จ
		
		
		$av_fullname=iconv('UTF-8','windows-874',"ผู้เช่าซื้อ : ".$fullname); 
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
		
		//$av_regis=iconv('UTF-8','windows-874',$rec_regis); 
		//$pdf->Text(10,68+$arow,$av_regis); //ทะเบียน
		
		
		//$av_cnumber=iconv('UTF-8','windows-874',$rec_cnumber); 
		//$pdf->Text(10,73+$arow,$av_cnumber); //เลขตัวถัง
		
		$pdf->Text(10+$col,73+$arow,"_______________________________________________________"); //line
		
		$av_total_con=iconv('UTF-8','windows-874',$de_total); 
		$pdf->Text(10+$col,80+$arow,$av_total_con); //รายการจ่าย
		
		
		$av_pay=iconv('UTF-8','windows-874',$dtl_pay); 
		$pdf->Text(10+$col,85+$arow,$av_pay); //เดือนที่จ่าย
		
		
		$pdf->SetXY(125,83+$arow);
		$av_ttotal=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(50,5,$av_ttotal,0,'L',0);// งวด
		
		$pdf->SetXY(125,89+$arow);
		$av_tvat=iconv('UTF-8','windows-874',$resvat);
		$pdf->MultiCell(50,5,$av_tvat,0,'L',0);// vat
		
		
		
		
		$pdf->SetXY(138,83+$arow);
		$av_total=iconv('UTF-8','windows-874',number_format($r_amt,2));
		$pdf->MultiCell(50,5,$av_total,0,'R',0);//งวด
		
		$pdf->SetXY(138,89+$arow);
		$av_vat=iconv('UTF-8','windows-874',number_format($p_vatth,2));
		$pdf->MultiCell(50,5,$av_vat,0,'R',0);//vat
		
		
		
		$pdf->SetXY(138,104+$arow);
		$av_sum=iconv('UTF-8','windows-874',number_format($r_amt_bf,2));
		$pdf->MultiCell(50,5,$av_sum,0,'R',0);//total
		
		
		
		$trntotal=pg_query("select conversionnumtothaitext($r_amt_bf)");
		$restrn=pg_fetch_result($trntotal,0);
		
		
		$pdf->SetXY(20,104+$arow);
		$av_trnnumber=iconv('UTF-8','windows-874',"=(".$restrn.")=");
		$pdf->MultiCell(150,5,$av_trnnumber,0,'L',0);//แปลงตัวหนังสือไทย
		
		
		}
		else 
		{
		
		
		$qry_con=pg_query("select  A.*, B.* from \"FOtherpay\" A   
						   LEFT OUTER JOIN  \"TypePay\" B on A.\"O_Type\"= B.\"TypeID\"
						   WHERE  A.\"O_RECEIPT\" ='$recid' ");
		$resrec=pg_fetch_array($qry_con);
		$rec_id=$resrec["O_RECEIPT"];
		$rec_date=$resrec["O_DATE"];
		$idno=$resrec["IDNO"];
		$de_total="ชำระค่า ".trim($resrec["TName"]);
		$sumttotal=$resrec["O_MONEY"];
		
		
		
		
		//$r_dueno=$resrec["R_DueNo"];
		
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
		
		
		
		$cus_add=trim($rescus["A_NO"])."  ม.".trim($rescus["A_SUBNO"]).$s_soi." ".$s_rd."  ตำบล/แขวง ".trim($rescus["A_TUM"]);
		
		
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
		$res_band="ยี่ห้อรถ ".$rescus["C_CARNUM"];
		}
		
		$p_month=$rescus["P_MONTH"];
		$p_vat=$rescus["P_VAT"];
		
		
		
		
		
		$fullname=$rescus["full_name"];
		
		
	
	    $pdf->AddPage();
		$pdf->SetFont('AngsanaNew','',16);
		
		//$pdf->Image('image/paper_recfull.jpg',0,0,210,290);
		
		
		$col=10;
		//Real //
		
		$pdf->Text(168,25.0+$arow,$rec_id); //เลขที่ใบเสร็จ
		
		
		
		$qry_d=pg_query("select conversiondatetothaitext('$rec_date')");
		$res_cdate=pg_fetch_result($qry_d,0);
		$av_daterec=iconv('UTF-8','windows-874',$res_cdate);
		$pdf->Text(150,33.5+$arow,$av_daterec); //วันที่ใบเสร็จ
		
		
		$av_fullname=iconv('UTF-8','windows-874',"ผู้เช่าซื้อ :".$fullname); 
		$pdf->Text(10+$col,45+$arow,$av_fullname); //ชื่อ - นามสกุล
		
		
		$pdf->SetXY(9+$col,46+$arow);
		$cus_add_icon=iconv('UTF-8','windows-874',$cus_add);
		$pdf->MultiCell(180,5,$cus_add_icon,0,'L',0);//ที่อยู่
		
		$pdf->SetXY(9+$col,51+$arow);
		$cus_add_pro=iconv('UTF-8','windows-874',"อำเภอ/เขต ".trim($rescus["A_AUM"])."จังหวัด ".trim($rescus["A_PRO"]));
		$pdf->MultiCell(180,5,$cus_add_pro,0,'L',0);//จังหวัด
		
		$av_band=iconv('UTF-8','windows-874',$res_band); 
		$av_cnumber=iconv('UTF-8','windows-874',$rec_cnumber); 
		$av_regis=iconv('UTF-8','windows-874',$rec_regis); 
		
		$pdf->Text(10+$col,59+$arow,"_______________________________________________________"); //line
		
		$pdf->Text(10+$col,66+$arow,$av_band." ".$av_cnumber." ".$av_regis); //ยี่ห้อรถ
		
		
		//$pdf->Text(10+$col,68+$arow,$av_regis); //ทะเบียน
		
		
		
		//$pdf->Text(10,73+$arow,$av_cnumber); //เลขตัวถัง
		
		
		
		$pdf->Text(10+$col,73+$arow,"_______________________________________________________"); //line
		
		
		$av_total_con=iconv('UTF-8','windows-874',$de_total); 
		$pdf->Text(10+$col,80+$arow,$av_total_con); //รายการจ่าย
		
		
		
		
		
		
		
		
		$pdf->SetXY(138,80+$arow);
		$av_bfsum=iconv('UTF-8','windows-874',number_format($sumttotal,2));
		$pdf->MultiCell(50,5,$av_bfsum,0,'R',0);//total
		
		
		
		$pdf->SetXY(138,104+$arow);
		$av_sum=iconv('UTF-8','windows-874',number_format($sumttotal,2));
		$pdf->MultiCell(50,5,$av_sum,0,'R',0);//total
		
		
		
		$trntotal=pg_query("select conversionnumtothaitext($sumttotal)");
		$restrn=pg_fetch_result($trntotal,0);
		
		
		$pdf->SetXY(20,104+$arow);
		$av_trnnumber=iconv('UTF-8','windows-874',"=(".$restrn.")=");
		$pdf->MultiCell(150,5,$av_trnnumber,0,'L',0);//แปลงตัวหนังสือไทย
		
		
		}
		
		

}
$pdf->Output();


?>