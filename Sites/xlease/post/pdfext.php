<?php
include("../config/config.php");
$frow=5;


/*
 $f_idno=$_GET["ID"];
 
 //list name IDNO 
 $qry_name=pg_query("select A.\"IDNO\",A.\"CusID\",B.* 
                     from \"Fp\" A 
					 LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\"=B.\"CusID\"
					 WHERE A.\"IDNO\"='$f_idno' 
					 
					");
  $res_name=pg_fetch_array($qry_name);
  $str_nameidno=trim($res_name["A_FIRNAME"])." ".trim($res_name["A_NAME"])." ".trim($res_name["A_SIRNAME"]);
 
 
 
 
 
 
  //for detail //
  $qry_print=pg_query("
                      select to_char(A.\"P_STDATE\",'dd/mm/yyyy') AS datest,A.*,C.*,D.*
					  FROM \"Fp\" A  LEFT OUTER JOIN \"Fc\" C ON A.asset_id = C. \"CarID\"
									 LEFT OUTER JOIN \"Fa1\" D ON A.\"CusID\" = D. \"CusID\"
					  where A.\"IDNO\" = '$f_idno'				 
  
  
                       ");
 
 
 
 
 
 
  $qry_print2=pg_query("
                      select * from \"ContactCus\" where (\"IDNO\" = '$f_idno') AND (\"CusState\"!=0) order by \"CusState\"
					  
					  				 
                       ");
					   
  $qry_print_dat=pg_query("
                      select * from \"ContactCus\" where (\"IDNO\" = '$f_idno') AND (\"CusState\"!=0) order by \"CusState\"
					  
					  				 
                       ");					   
 
 
 

*/


require('../thaipdfclass.php');

$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();


//$pdf->Image('page1.jpg',0,0,210,290);

$pdf->SetFont('AngsanaNew','B',16);

//$num_grt=pg_num_rows($qry_print2);


// result number record
//$num_txt=iconv('UTF-8','windows-874',$num_grt);
//$pdf->Text(33,56,$num_txt);
	




$fp_regis=$res_fp["C_REGIS"];
$fp_reg_by=trim($res_fp["C_REGIS_BY"]);



$name_comp="สาขาจรัญสนิทวงศ์ ตั้งอยู่อาคาร เลขที่ 667";
$name_comp_ads="ถนนจรัญสนิทวงศ์ แขวงอรุณอมรินทร์ เขตบางกอกน้อย จังหวัดกรุงเทพฯ10700";


$name_cc=iconv('UTF-8','windows-874',$name_comp);
$pdf->Text(132,43+$frow,$name_cc); //ชื่อบริษัท


$name_ads=iconv('UTF-8','windows-874',$name_comp_ads);
$pdf->Text(25,50+$frow,$name_ads); //ที่อยู่



//$res_idno=pg_fetch_array($qry_print);
$av_stdate=iconv('UTF-8','windows-874',"23/05/2011");
$pdf->Text(172,50+$frow,$av_stdate); //วันที่ทำสัญญา

$arow=10;
$crow=5;

/*
while($res_fp=pg_fetch_array($qry_print_dat))
{

 
 $rescus_id=$res_fp["CusID"];
 
 $qry_sname=pg_query("select A,*,B.*,to_char(B.\"N_OT_DATE\",'dd/mm/yyyy') AS otdate
 
                       from \"Fa1\" A 
                      LEFT OUTER JOIN  \"Fn\" B ON A.\"CusID\" = B.\"CusID\"
					  WHERE  A.\"CusID\"='$rescus_id' ");
 $res_cc=pg_fetch_array($qry_sname);
 
 $av_cname=trim($res_cc["A_FIRNAME"])." ".trim($res_cc["A_NAME"])." ".trim($res_cc["A_SIRNAME"]);
 
 */
 
 /* Detail */

     $av_names=iconv('UTF-8','windows-874',"นางสาว โสภา เนียมฝอย");
     $pdf->Text(40,57+$arow+$crow,$av_names);

     $av_san=iconv('UTF-8','windows-874',"ไทย");
     $pdf->Text(130,57+$arow+$crow,$av_san);//สัญชาติ
	 
	 $av_age=iconv('UTF-8','windows-874',"39");
     $pdf->Text(160,57+$arow+$crow,$av_age); //อายุ
	 
	 
	 $av_card=iconv('UTF-8','windows-874',"บัตรประชาชน");
     $pdf->Text(30,65+$arow+$crow,$av_card);//บัตรแสดง
	 
	 
	 $av_cardid=iconv('UTF-8','windows-874',"3 7204 00138 69 5");
     $pdf->Text(87,65+$arow+$crow,$av_cardid); //เลขที่บัตร
	 
	 
	
	 //$fn_cdate=pg_query("select c_datethai('$res_cc[N_OT_DATE]')");
	 //$res_fndate=pg_fetch_result($fn_cdate,0);
	 $av_card_date=iconv('UTF-8','windows-874',"01/08/2006"); 
     $pdf->Text(150,65+$arow+$crow,$av_card_date); //วันที่ออกบัีตร
	 
	 
	 $av_cardby=iconv('UTF-8','windows-874',"อำเภอ โพทะเล");
     $pdf->Text(30,72+$arow+$crow,$av_cardby); //ออกให้โดย
	
	  
  	 $av_pair=iconv('UTF-8','windows-874'," - ");
     $pdf->Text(102,72+$arow+$crow,$av_pair); //คู่สมรส
	 
	 
	 $av_add=iconv('UTF-8','windows-874',"111");
     $pdf->Text(183,72+$arow+$crow,$av_add); //บ้านเลขที่
	 
	 
	  $av_subno=iconv('UTF-8','windows-874',"6");
      $pdf->Text(30,80.5+$arow+$crow,$av_subno); //หมู่
	  
	  $av_soi=iconv('UTF-8','windows-874'," - ");
      $pdf->Text(50,80.5+$arow+$crow,$av_soi); //ซอย
	  
	  $av_rd=iconv('UTF-8','windows-874'," - ");
      $pdf->Text(105,80.5+$arow+$crow,$av_rd); //ถนน
	  
	  $av_tam=iconv('UTF-8','windows-874',"ท้ายน้ำ");
      $pdf->Text(169,80.5+$arow+$crow,$av_tam); //ตำบล
	  
	  
	  $av_aum=iconv('UTF-8','windows-874',"โพทะเล");
      $pdf->Text(40,88+$arow+$crow,$av_aum); //อำเภอ
	  
	  $av_pro=iconv('UTF-8','windows-874',"พิจิตร");
      $pdf->Text(105,88+$arow+$crow,$av_pro); //จังหวัด
	  
	  
	  $av_occ=iconv('UTF-8','windows-874',"ค้าขาย");
      $pdf->Text(160,88+$arow+$crow,$av_occ); //อาชีพ
	  
	  
      
	 
	 	 
     $crow=$crow+39;







//$pdf->Text(20,20+$arow,$res_fp["IDNO"]); //เลขที่สัญญา



/*

 $qry_contactcus=pg_query("
                      select  X.*,Y.*,Z.*,c_datethai(\"N_OT_DATE\") AS c_dateth
					  
					  FROM \"ContactCus\" X  LEFT OUTER JOIN \"Fa1\" Y ON X.\"CusID\" = Y. \"CusID\"
					                 LEFT OUTER JOIN \"Fn\" Z ON Y.\"CusID\" = Z. \"CusID\"
									
									 
									 
					  where X.\"IDNO\"='$f_idno' AND \"CusState\"!=0 order by \"CusState\" ");	
					  
  $crow=5; 
  while($res_cc=pg_fetch_array($qry_contactcus))
  {
     $arow=1.5;
     $acol=10;
	
	
	
	
	/*
	
    $av_fullname=trim($res_cc["A_FIRNAME"])." ".trim($res_cc["A_NAME"])." ".trim($res_cc["A_SIRNAME"]);
    
	
    $av_names=iconv('UTF-8','windows-874',$av_fullname);
    $pdf->Text(33,56+$arow+$crow,$av_names);
	
	$av_san=iconv('UTF-8','windows-874',$res_cc["N_SAN"]);
    $pdf->Text(140,56+$arow+$crow,$av_san);
	
	
	$av_age=iconv('UTF-8','windows-874',$res_cc["N_AGE"]);
    $pdf->Text(180,56+$arow+$crow,$av_age);
	
	$av_card=iconv('UTF-8','windows-874',$res_cc["N_CARD"]);
    $pdf->Text(33,62+$arow+$crow,$av_card);
	
	$av_cardid=iconv('UTF-8','windows-874',$res_cc["N_IDCARD"]);
    $pdf->Text(105,62+$arow+$crow,$av_cardid);
	
	$av_card_date=iconv('UTF-8','windows-874',$res_cc["c_dateth"]);
    $pdf->Text(170,62+$arow+$crow,$av_card_date);
	
	$av_cardby=iconv('UTF-8','windows-874',$res_cc["N_BY"]);
    $pdf->Text(30,68+$arow+$crow,$av_cardby);
	
	$av_pair=iconv('UTF-8','windows-874',$res_cc["A_PAIR"]);
    $pdf->Text(115,68+$arow+$crow,$av_pair);
	
	$av_fullAdd=trim($res_cc["A_NO"])." ม.".trim($res_cc["A_SUBNO"])." ซอย ".trim($res_cc["A_SOI"])." ถนน ".trim($res_cc["A_RD"])." แขวง/ตำบล ".trim($res_cc["A_TUM"]);
	
	$av_add=iconv('UTF-8','windows-874',$av_fullAdd);
    $pdf->Text(30,75+$arow+$crow,$av_add);
	
	
	$av_prov="เขต/อำเภอ ".trim($res_cc["A_AUM"])." จังหวัด ".trim($res_cc["A_PRO"]);
	
	$av_pro=iconv('UTF-8','windows-874',$av_prov);
    $pdf->Text(30,80+$arow+$crow,$av_pro);
	
	
	
	$crow=$crow+31;
   
  }
    
  $av_idno=iconv('UTF-8','windows-874',$f_idno);
  $pdf->Text(160,190.5,$av_idno); //เลขที่ idno
  
  
  $av_stdate2=iconv('UTF-8','windows-874',$res_fp["datest"]);
  $pdf->Text(30,197,$av_stdate2); //ฉบับลงวันที่
  
  $av_regisc=iconv('UTF-8','windows-874',$fp_regis);
  $pdf->Text(95,197,$av_regisc); //ทะเบียนรถ
  
  $av_regby=iconv('UTF-8','windows-874',$fp_reg_by);
  $pdf->Text(135,197,$av_regby); //จังหวัดจดทะเบียน
  
  
  $av_ccname=iconv('UTF-8','windows-874',$av_cname);
  $pdf->Text(53,203,$av_ccname); //ชื่อ - นามสกุล
   

*/


  $fp_regis="ทศ 3022";
  $fp_reg_by="กรุงเทพมหานคร";
  
   
  
  

  $av_idno=iconv('UTF-8','windows-874',"223-05011");
  $pdf->Text(32,226+$frow,$av_idno); //เลขที่ idno

 
 
 // $fn_cdate2=pg_query("select c_datethai('$res_idno[P_STDATE]')");
 // $res_fndate2=pg_fetch_result($fn_cdate2,0);
  
  

  $av_stdate2=iconv('UTF-8','windows-874',"12/05/2010");
  $pdf->Text(83,226+$frow,$av_stdate2); //ฉบับลงวันที่
  
  $av_regisc=iconv('UTF-8','windows-874',$fp_regis);
  $pdf->Text(135,226+$frow,$av_regisc); //ทะเบียนรถ
  
  $av_regby=iconv('UTF-8','windows-874',$fp_reg_by);
  $pdf->Text(170,226+$frow,$av_regby); //จังหวัดจดทะเบียน
  
  $av_ccnames=iconv('UTF-8','windows-874',$str_nameidno);
  $pdf->Text(93,233.5+$frow,$av_ccnames); //ชื่อ - นามสกุล
  
   
$pdf->Output();



?>
