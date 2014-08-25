<?php
session_start();

include("../../config/config.php");
require('../../thaipdfclass.php');

$comp_name=$_SESSION["session_company_thainame"]; 

$p_year=pg_escape_string($_GET["myear"]);
$p_type=pg_escape_string($_GET["mtype"]);
$idd=pg_escape_string($_GET['aid']);
$booktype=pg_escape_string($_GET['booktype']);



$pdf=new ThaiPDF('P' ,'mm','slip_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();


$qry_m=pg_query("select * from account.\"all_accBookHead\" where  \"abh_autoid\" = '$idd'");
while($res_m=pg_fetch_array($qry_m))
{
    $a_id=$res_m["abh_id"];
	$as_date=$res_m["abh_stamp"];
	
	$trn_date=pg_query("select * from c_date_number('$as_date')");
	$a_date=pg_fetch_result($trn_date,0);
	 
	$pdf->AddPage();
    $pdf->SetFont('AngsanaNew','B',22);
	$pdf->SetXY(15,10);
	$head_comp=iconv('UTF-8','windows-874',"บริษัท ".$comp_name);
	$pdf->MultiCell(180,6,$head_comp,0,'C',0);
	$pdf->SetFont('AngsanaNew','B',20);
	$pdf->SetXY(15,18);
    
    if( substr($a_id,0,2) == "AJ" ){
        $head_imp=iconv('UTF-8','windows-874',"ใบสำคัญปรับปรุง");
    }else{
        $head_imp=iconv('UTF-8','windows-874',$booktype);
    }
	
	$pdf->MultiCell(180,6,$head_imp,0,'C',0);

	
	$pdf->SetFont('AngsanaNew','',16);
	$str_number="เลขที่ ";
	$str_date=iconv('UTF-8','windows-874',"วันที่  ");
	//$str_number=date("Y-m-d",strtotime($as_date));
	$icon_daterec=iconv('UTF-8','windows-874',$str_number);
	$pdf->Text(15,26.0+$arow,$str_date.date("Y-m-d",strtotime($as_date))); //วันที่ใบเสร็จ
	$pdf->Text(155,26.0+$arow,$icon_daterec.$a_id); //เลขที่ใบเสร็จ

       $pdf->SetXY(15,32);
	   $is_acid=iconv('UTF-8','windows-874'," รายการ" );
	   $pdf->MultiCell(100,8,$is_acid,1,'C',0);
	  
	   $pdf->SetXY(115,32);//Dr
	   $is_dr=iconv('UTF-8','windows-874',Dr);
	   $pdf->MultiCell(35,8,$is_dr,1,'C',0);
	   
	   $pdf->SetXY(150,32);//Cr
	   $is_cr=iconv('UTF-8','windows-874',Cr);
	   $pdf->MultiCell(35,8,$is_cr,1,'C',0);
	   $crow=$crow+8;


    
	$crow=0;
	$qry_vacc=pg_query("select *  from account.\"V_all_AccountBook\" where abh_id='$a_id' ");
	while($res_vacc=pg_fetch_array($qry_vacc))
	{
	     
		$v_acname=$res_vacc["accBookName"];
		$v_acid=" [ ".$res_vacc["abd_accBookID"]." ]"." ".$v_acname;
		$vs_dt=$res_vacc["abh_detail"];
		$abd_bookType = $res_vacc["abd_bookType"]; // ประเภท 1 Dr 2 Cr
		$abd_amount = $res_vacc["abd_amount"];
	   
		if($abd_bookType == 1)
		{
			$v_dr = number_format($abd_amount,2);
			$v_cr = "0.00";
			$sum_dr=$sum_dr+$abd_amount;
		}
		elseif($abd_bookType == 2)
		{
			$v_dr = "0.00";
			$v_cr = number_format($abd_amount,2);
			$sum_cr=$sum_cr+$abd_amount;
		}
		else
		{
			$v_dr = "";
			$v_cr = "";
		}
	   
	   
	    $exp_dtl=str_replace("\n","#",$vs_dt);
	    $sep_dtl=explode("#",$exp_dtl);
	    
		$sp_dtl=str_replace("\n"," ",$vs_dt);
	   
	   $pdf->SetXY(15,40+$crow);
	   $i_acid=iconv('UTF-8','windows-874',$v_acid);
	   $pdf->MultiCell(100,8,$i_acid,1,'L',0);
	  
	   $pdf->SetXY(115,40+$crow);//Dr
	   $i_dr=iconv('UTF-8','windows-874',$v_dr);
	   $pdf->MultiCell(35,8,$i_dr,1,'R',0);
	   
	   $pdf->SetXY(150,40+$crow);//Cr
	   $i_cr=iconv('UTF-8','windows-874',$v_cr);
	   $pdf->MultiCell(35,8,$i_cr,1,'R',0);
	   $crow=$crow+8;	   
	   
	  
	    $total_str=count($sep_dtl);
	 	 
	 
		for($is=$total_str-1;$is<$total_str;$is++)
		{
		  $res_i=$sep_dtl[$is];
		} 
	   $v_dt=iconv('UTF-8','windows-874',$a_id."     ".$res_i);

	} 
		
		$pdf->SetXY(15,40+$crow);
		$i_acid=iconv('UTF-8','windows-874',"ยอดรวม");
		$pdf->MultiCell(100,8,$i_acid,1,'C',0);
	  
	   $pdf->SetXY(115,40+$crow);//Dr
	   $i_dr=iconv('UTF-8','windows-874',number_format($sum_dr,2));
	   $pdf->MultiCell(35,8,$i_dr,1,'R',0);
	   
	   $pdf->SetXY(150,40+$crow);//Cr
	   $i_cr=iconv('UTF-8','windows-874',number_format($sum_cr,2));
	   $pdf->MultiCell(35,8,$i_cr,1,'R',0);
	   $crow=$crow+8;
	   
	   
	   $pdf->SetXY(15,40+$crow);//Detail
	   $i_dt=iconv('UTF-8','windows-874',"  ".$sp_dtl);
	   $pdf->MultiCell(170,8,$i_dt,1,'L',0);
	   
	   
	   $pdf->SetXY(15,105);//Detail
	   $i_pre=iconv('UTF-8','windows-874',"ผู้เตรียม ___________________ ");
	   $pdf->MultiCell(55,10,$i_pre,0,'L',0);
	   
	   $pdf->SetXY(72,105);//Detail
	   $i_pre=iconv('UTF-8','windows-874',"ผู้ตรวจสอบ _________________ ");
	   $pdf->MultiCell(55,10,$i_pre,0,'L',0);
	   
	   $pdf->SetXY(130,105);//Detail
	   $i_pre=iconv('UTF-8','windows-874',"ผู้อนุมัติจ่าย _________________");
	   $pdf->MultiCell(55,10,$i_pre,0,'L',0);
	   
	 
  }		
$pdf->Output(); 		
/*
$pdf=new ThaiPDF('P' ,'mm','slip_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

*/


/*
  $pdf->AddPage();
  $pdf->SetFont('AngsanaNew','',16);
		
  $col=10;
		
		$pdf->Text(163,25.0+$arow,$rec_id); //เลขที่ใบเสร็จ

$pdf->Output();
*/

?>