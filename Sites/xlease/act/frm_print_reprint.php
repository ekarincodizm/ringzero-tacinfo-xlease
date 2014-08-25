<?php

include("../config/config.php");

$h_arti_id = pg_escape_string($_POST['h_arti_id']);
$nowdate = date("Y/m/d");

$qry_in=pg_query("select * from \"insure\".\"InsureForce\" WHERE (\"InsID\"='$h_arti_id')");
if($res_in=pg_fetch_array($qry_in)){
    $InsFIDNO = $res_in["InsFIDNO"];
    $IDNO = $res_in["IDNO"];
    $Code = $res_in["Code"];    $SubCode = substr($Code, 0, 4);
    $Capacity = $res_in["Capacity"];
    $Premium = number_format($res_in["Premium"],2);
    $NetPremium = number_format($res_in["NetPremium"],2);
    $TaxStamp = number_format($res_in["TaxStamp"],2);
    $Vat = number_format($res_in["Vat"],2);
    $start_date = $res_in["StartDate"];
    $end_date = $res_in["EndDate"];
    $Code = $res_in["Code"];
    $Company = $res_in["Company"];
}

    $strYear = date("Y",strtotime($start_date))+543;
    $strMonth = date("m",strtotime($start_date));
    $strDate = date("d",strtotime($start_date));
    
    $endYear = date("Y",strtotime($end_date))+543;
    $endMonth = date("m",strtotime($end_date));
    $endDate = date("d",strtotime($end_date));
    
    $nowYear = date("Y",strtotime($nowdate))+543;
    $nowMonth = date("m",strtotime($nowdate));
    $nowDate = date("d",strtotime($nowdate));

    $thaimonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม ","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน ","ธันวาคม");
    $conv_start_date = $strDate." ".$thaimonth[$strMonth-1]." ".$strYear;
    $conv_end_date = $endDate." ".$thaimonth[$endMonth-1]." ".$endYear;
    $conv_nowdate = $nowDate." ".$thaimonth[$nowMonth-1]." ".$nowYear;


$qry_in2=pg_query("select * from \"insure\".\"RateInsForce\" WHERE (\"IFCode\"='$Code')");
if($res_in2=pg_fetch_array($qry_in2)){
    $BodyType = $res_in2["BodyType"];
    $CapacityUnit = $res_in2["CapacityUnit"];
}
/*
$qry_vc=pg_query("select * from insure.\"VInsForceDetail\" WHERE (\"InsFIDNO\"='$InsFIDNO')");
if($res_vc=pg_fetch_array($qry_vc)){
    $C_COLOR = $res_vc["C_COLOR"];
    $full_name = $res_vc["full_name"];
}
*/
$qry_in3=pg_query("select \"asset_id\" from \"Fp\" WHERE (\"IDNO\"='$IDNO')");
if($res_in3=pg_fetch_array($qry_in3)){
    $asset_id = $res_in3["asset_id"];
    
    $qry_in4=pg_query("select * from \"VCarregistemp\" WHERE (\"IDNO\"='$IDNO')");
    if($res_in4=pg_fetch_array($qry_in4)){
        //$C_CARNAME = $res_in4["C_CARNAME"];
        //$C_REGIS = $res_in4["C_REGIS"];
        $C_REGIS_BY = $res_in4["C_REGIS_BY"];
        //$C_CARNUM = $res_in4["C_CARNUM"];
    }
    
}

$qry_un=pg_query("select * from \"UNContact\" WHERE (\"IDNO\"='$IDNO')");
if($res_un=pg_fetch_array($qry_un)){
    $C_COLOR = $res_un["C_COLOR"];
    $full_name = $res_un["full_name"];
    $C_CARNAME = $res_un["C_CARNAME"];
    $C_REGIS = $res_un["C_REGIS"];
    $C_CARNUM = $res_un["C_CARNUM"];
}

if($Company == 'SMK'){
    
//------------------- PDF SMK -------------------//
require('../thaipdfclass.php');

$pdf=new ThaiPDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();

$pdf->SetFont('AngsanaNew','',14);

$top_date=iconv('UTF-8','windows-874',"$conv_start_date");  //วันที่ 
$pdf->Text(135,29,$top_date);

if($C_COLOR == "เหลือง" || $C_COLOR == "ฟ้า"){
$buss_name1=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]."");  //ชื่อบริษัท 
$pdf->Text(70,53,$buss_name1);
$buss_name2=iconv('UTF-8','windows-874',"999 หมู่ที่ 10 ถ.นวมินทร์ แขวงคลองกุ่ม"); 
$pdf->Text(70,58,$buss_name2);
$buss_name3=iconv('UTF-8','windows-874',"เขตบึงกุ่ม กรุงเทพฯ 10230"); 
$pdf->Text(70,63,$buss_name3);
}else{
$buss_name1=iconv('UTF-8','windows-874',"$full_name");  //ชื่อลูกค้า
$pdf->Text(70,53,$buss_name1);
$buss_name2=iconv('UTF-8','windows-874',"999 หมู่ที่ 10 ถ.นวมินทร์ แขวงคลองกุ่ม"); 
$pdf->Text(70,58,$buss_name2);
$buss_name3=iconv('UTF-8','windows-874',"เขตบึงกุ่ม กรุงเทพฯ 10230"); 
$pdf->Text(70,63,$buss_name3);
}

$start_date=iconv('UTF-8','windows-874',$conv_start_date);  //วันที่ 
$pdf->Text(68,74,$start_date);
$end_date=iconv('UTF-8','windows-874',$conv_end_date); 
$pdf->Text(122,74,$end_date);

$car_code=iconv('UTF-8','windows-874',$SubCode);  //รายการ 
$pdf->Text(4,101,$car_code);

$pdf->SetXY(20,97.5);
$car_name=iconv('UTF-8','windows-874',$C_CARNAME);
$pdf->MultiCell(37,4,$car_name,0,'L',0);

$car_bc=iconv('UTF-8','windows-874',$C_REGIS . " " . $C_REGIS_BY);
$pdf->Text(56,101,$car_bc);
$car_mr=iconv('UTF-8','windows-874',$C_CARNUM);
$pdf->Text(91,101,$car_mr);
$car_num_body=iconv('UTF-8','windows-874',$BodyType);
$pdf->Text(140,101,$car_num_body);
$car_cc=iconv('UTF-8','windows-874',$Capacity." ".$CapacityUnit);
$pdf->Text(171,101,$car_cc);

$b_money=iconv('UTF-8','windows-874',$Premium);  //เบี้ย 
$pdf->Text(8,163,$b_money);
$b_discount=iconv('UTF-8','windows-874',"");  
$pdf->Text(17,163,$b_discount);
$b_net=iconv('UTF-8','windows-874',$NetPremium);  
$pdf->Text(85,163,$b_net);
$b_stm=iconv('UTF-8','windows-874',$TaxStamp);  
$pdf->Text(116,163,$b_stm);
$b_vat=iconv('UTF-8','windows-874',$Vat);  
$pdf->Text(140,163,$b_vat);
$b_all=iconv('UTF-8','windows-874',$Premium); 
$pdf->Text(172,163,$b_all);

$use=iconv('UTF-8','windows-874',"ใช้เป็นรถส่วนบุคคล หรือ รับจ้าง หรือ ให้เช่า");   //Fix
$pdf->Text(55,173,$use);

$cur_date=iconv('UTF-8','windows-874',$conv_start_date);   //วันทำสัญญา
$pdf->Text(38,191,$cur_date);
$cur_date=iconv('UTF-8','windows-874',$conv_start_date); 
$pdf->Text(140,191,$cur_date);

$user_bc=iconv('UTF-8','windows-874',$C_REGIS);   //User
$pdf->Text(81,247,$user_bc);
$user_cn=iconv('UTF-8','windows-874',$C_CARNUM);
$pdf->Text(150,247,$user_cn);

$user_date_st=iconv('UTF-8','windows-874',$conv_start_date);
$pdf->Text(30,260,$user_date_st);
$user_date_end=iconv('UTF-8','windows-874',$conv_end_date);
$pdf->Text(115,260,$user_date_end);

$pdf->Output(); 
   
}else{
    
//------------------- PDF CPY ------------------//
require('../thaipdfclass.php');

$pdf=new ThaiPDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();

$pdf->SetFont('AngsanaNew','',14);

$top_date=iconv('UTF-8','windows-874',"$conv_start_date");  //วันที่ 
$pdf->Text(135,29,$top_date);

if($C_COLOR == "เหลือง" || $C_COLOR == "ฟ้า"){
$buss_name1=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]."");  //ชื่อบริษัท
$pdf->Text(70,54,$buss_name1);
$buss_name2=iconv('UTF-8','windows-874',"999 หมู่ที่ 10 ถ.นวมินทร์ แขวงคลองกุ่ม"); 
$pdf->Text(70,59,$buss_name2);
$buss_name3=iconv('UTF-8','windows-874',"เขตบึงกุ่ม กรุงเทพฯ 10230"); 
$pdf->Text(70,64,$buss_name3);
}else{
$buss_name1=iconv('UTF-8','windows-874',"$full_name");  //ชื่อลูกค้า
$pdf->Text(70,54,$buss_name1);
$buss_name2=iconv('UTF-8','windows-874',"999 หมู่ที่ 10 ถ.นวมินทร์ แขวงคลองกุ่ม"); 
$pdf->Text(70,59,$buss_name2);
$buss_name3=iconv('UTF-8','windows-874',"เขตบึงกุ่ม กรุงเทพฯ 10230"); 
$pdf->Text(70,64,$buss_name3);
}

$start_date=iconv('UTF-8','windows-874',$conv_start_date);  //วันที่ 
$pdf->Text(68,72,$start_date);
$end_date=iconv('UTF-8','windows-874',$conv_end_date); 
$pdf->Text(122,72,$end_date);

$car_code=iconv('UTF-8','windows-874',$SubCode);  //รายการ 
$pdf->Text(4,98,$car_code);

$pdf->SetFont('AngsanaNew','',11);  
$pdf->SetXY(20,94.5);
$car_name=iconv('UTF-8','windows-874',$C_CARNAME);
$pdf->MultiCell(40,4,$car_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',14);
$car_bc=iconv('UTF-8','windows-874',$C_REGIS . " " . $C_REGIS_BY);
$pdf->Text(60,98,$car_bc);
$car_mr=iconv('UTF-8','windows-874',$C_CARNUM);
$pdf->Text(91,98,$car_mr);
$car_num_body=iconv('UTF-8','windows-874',$BodyType);
$pdf->Text(142,98,$car_num_body);
$car_cc=iconv('UTF-8','windows-874',$Capacity." ".$CapacityUnit);
$pdf->Text(175,98,$car_cc);

$b_money=iconv('UTF-8','windows-874',$Premium);  //เบี้ย 
$pdf->Text(8,162,$b_money);
$b_discount=iconv('UTF-8','windows-874',"");  
$pdf->Text(17,162,$b_discount);
$b_net=iconv('UTF-8','windows-874',$NetPremium);  
$pdf->Text(85,162,$b_net);
$b_stm=iconv('UTF-8','windows-874',$TaxStamp);  
$pdf->Text(116,162,$b_stm);
$b_vat=iconv('UTF-8','windows-874',$Vat);  
$pdf->Text(140,162,$b_vat);
$b_all=iconv('UTF-8','windows-874',$Premium); 
$pdf->Text(172,162,$b_all);

$use=iconv('UTF-8','windows-874',"ใช้เป็นรถส่วนบุคคล หรือ รับจ้าง หรือ ให้เช่า");   //Fix
$pdf->Text(55,170,$use);

$cur_date=iconv('UTF-8','windows-874',$conv_start_date);   //วันทำสัญญา
$pdf->Text(40,188,$cur_date);
$cur_date=iconv('UTF-8','windows-874',$conv_start_date); 
$pdf->Text(140,188,$cur_date);

$user_bc=iconv('UTF-8','windows-874',$C_REGIS);   //User
$pdf->Text(81,245,$user_bc);
$user_cn=iconv('UTF-8','windows-874',$C_CARNUM);
$pdf->Text(150,245,$user_cn);
$user_date_st=iconv('UTF-8','windows-874',$conv_start_date);
$pdf->Text(30,258,$user_date_st);
$user_date_end=iconv('UTF-8','windows-874',$conv_end_date);
$pdf->Text(115,258,$user_date_end);

$pdf->Output();

}
?>