<?php
session_start();

$idno=$_POST["f_idno"];

$userid=$_SESSION['uid'];
$id_cusid=$_POST["fcus_id"];

$officeid=$_SESSION["av_officeid"];

$dat=date("Y/m/d");
$datenow=date("Y-m-d");

include("../../config/config.php");



$fs_fir=$_POST["fir_name"];
$fs_name=$_POST["f_name"];
$fs_sirname=$_POST["f_sirname"];

$textName=$fs_fir." ".$fs_name."  ".$fs_surname;

$fs_pair=$_POST["f_pair"];
$fs_san=$_POST["f_san"];
$fs_age=$_POST["f_age"];
$fs_card=$_POST["f_card"];
$fs_cardid=$_POST["f_cardid"];
$fs_datecard=$_POST["f_card_date"];
$fs_card_by=$_POST["f_by"];

$fs_no=$_POST["f_no"];
$fs_subno=$_POST["f_subno"];
$fs_rd=$_POST["f_rd"];
$fs_soi=$_POST["f_soi"];
$fs_tum=$_POST["f_tum"];
$fs_aum=$_POST["f_aum"];
$fs_province=$_POST["f_province"];
$fs_post=$_POST["f_post"];
$fs_occ=$_POST["f_occ"];



 $in_sql="update \"Fa1\" SET  \"A_FIRNAME\"='$fs_fir'  ,\"A_NAME\"='$fs_name',\"A_SIRNAME\"='$fs_sirname',\"A_PAIR\"='$fs_pair',\"A_NO\"='$fs_no', \"A_SUBNO\"='$fs_subno',\"A_SOI\"='$fs_soi',\"A_RD\"='$fs_rd',\"A_TUM\"='$fs_tum',\"A_AUM\"='$fs_aum',\"A_PRO\"='$fs_province',\"A_POST\"='$fs_post'
 where \"CusID\"='$id_cusid'           
		   ";
		  
		  
  
 if($result=pg_query($in_sql))
 {
  $status ="OK Update at Fa1".$in_sql;
 }
 else
 {
  $status ="error Update  Fa1 Re".$in_sql;
 }
 // echo $status;

$fs_stat_add=$_POST["f_extadd"];
if($fs_stat_add==2)
{
 $fs_ext=$_POST["f_ext"];
 $fs_conadd=$fs_ext;
}
else
{
$fs_conadd=trim($fs_no)." ".trim($fs_subno)." ".trim($fs_soi)." ".trim($fs_rd)." ".trim($fs_aum)." ".trim($fs_tum)." ".trim($fs_province)." ".trim($fs_post);
}


 
 
 
$in_fn="Update \"Fn\" SET \"N_SAN\"='$fs_san',\"N_AGE\"='$fs_age',\"N_CARD\"='$fs_card',
\"N_IDCARD\"='$fs_cardid',\"N_OT_DATE\"='$fs_datecard',\"N_BY\"='$fs_card_by',\"N_ContactAdd\"='$fs_conadd',\"N_OCC\"='$fs_occ'
        WHERE \"CusID\"='$id_cusid'  ";
 
 if($result=pg_query($in_fn))
 {
  $statuss ="OK update at Fn".$in_fn;
 }
 else
 {
  $statuss ="error update  Fn Re".$in_fn;
 }	 
//echo "<br>".$statuss;
 
 
 
 
  /*** letter **/
  
   $qry_cc=pg_query($db_connect,"select * from \"ContactCus\" 
                                 WHERE  (\"CusID\"='$id_cusid') and (\"IDNO\"='$idno') ");
  
    $res_cc=pg_fetch_array($qry_cc);
	$cs_cc=$res_cc["CusState"];
    $num_rlet=pg_num_rows($qry_cc);
	
	
	
	if($num_rlet==0)
	{
	
	  $cs_cc=$res_cc["CusState"];
	  
      $qry_lt=pg_query($db_connect,"select * from letter.send_address 
	                              where (\"IDNO\"='$idno') and (\"CusState\"='$cs_cc') and (active=TRUE);");
	  $numr_lt=pg_num_rows($qry_lt);
		if($numr_lt==0)
		{
	
	 
		$gen_ltr=pg_query("select letter.gen_cusletid('$idno')"); //gen letter
	    $res_genltr=pg_fetch_result($gen_ltr,0);
	
	//echo "<br>"."gen id=".$res_genltr;
	
		$ins_send_ads="insert into letter.send_address 	
					   (\"CusLetID\",\"IDNO\",record_date,\"name\",active,userid,dtl_ads,\"CusState\")
					   values
					   ('$res_genltr','$idno','$datenow','$textName',TRUE,'$userid','$fs_conadd',$cs_cc)";
		 
		 if($result=pg_query($db_connect,$ins_send_ads))
		 {
		  $status ="OK".$ins_send_ads;
		 }
		 else
		 {
		  $status ="error insert Re".$ins_send_ads;
		 }
		
		//echo $status;
	
	    }
	    else
	    {			
	      $qry_lt2=pg_query($db_connect,"select * from letter.send_address 
	                              where (\"IDNO\"='$idno') and (\"CusState\"='$cs_cc') and (active=TRUE);");				  
	       $res_idli=pg_fetch_array($qry_lt2);
	 
	       $fs_ltsid=$res_idli["CusLetID"];
	 
	      $in_lt="Update letter.send_address SET dtl_ads='$fs_conadd' WHERE \"CusLetID\"='$fs_ltsid' ";
	    	if($result=pg_query($db_connect,$in_lt))
		   {
		    $statuss ="OK update at Fn".$in_lt;
		    $st="บันทึกข้อมูลเรียบร้อย";
		   }
		   else
		   {
		    $statuss ="error update  Fn Re".$in_lt;
		    $st="เกิดข้อผิดพลาด";
		   }	
		//echo $st; 
    
        }	
      }
	  else
	  {
	  
	    $qry_lt2=pg_query($db_connect,"select * from letter.send_address 
	                              where (\"IDNO\"='$idno') and (\"CusState\"='$cs_cc') and (active=TRUE);");				  
	       $res_idli=pg_fetch_array($qry_lt2);
	 
	       $fs_ltsid=$res_idli["CusLetID"];
	 
	      $in_lt="Update letter.send_address SET dtl_ads='$fs_conadd' WHERE \"CusLetID\"='$fs_ltsid' ";
	    	if($result=pg_query($db_connect,$in_lt))
		   {
		    $statuss ="OK update at Fn".$in_lt;
		    $st="บันทึกข้อมูลเรียบร้อย";
		   }
		   else
		   {
		    $statuss ="error update  Fn Re".$in_lt;
		    $st="เกิดข้อผิดพลาด";
		   }	
	  
	  
	  }
 
 /*************/
 
 
 

echo "<br>";
echo "    บันทึกข้อมูลเรียบร้อยแ้ล้ว ";
echo "<br>";
 
//echo "<meta http-equiv=\"refresh\" content=\"0;URL=../list_menu.php\">";
echo "<meta http-equiv=\"refresh\" content=\"2;URL=frm_edit_cus.php?idnog=$idno\">";

?>