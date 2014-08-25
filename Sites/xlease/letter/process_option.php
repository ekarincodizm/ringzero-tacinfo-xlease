<?php
set_time_limit (0); 
ini_set("memory_limit","1024M"); 
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
$userid=$_SESSION['uid'];



include("../config/config.php");
$datenow=date("Y-m-d");

$qry_cc=pg_query($db_connect,"select * from \"ContactCus\" ");
while($res_cc=pg_fetch_array($qry_cc))
{
  $c_idno=$res_cc["IDNO"];
  $c_custate=$res_cc["CusState"];
  $c_cusid=$res_cc["CusID"];
  
  
   
  
  $qry_name=pg_query($db_connect,"select \"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"CusID\" 
                                  from \"Fa1\"  
								  WHERE \"CusID\"='$c_cusid' ");
  $res_nn=pg_fetch_array($qry_name);
  
  $c_name=trim($res_nn["A_FIRNAME"])." ".trim($res_nn["A_NAME"])."  ".trim($res_nn["A_SIRNAME"]);
  
  
  $qry_ads=pg_query($db_connect,"select * from \"Fn\" where \"CusID\"='$c_cusid' ");
  $n_ads=pg_fetch_array($qry_ads);
  
  $c_ads=$n_ads["N_ContactAdd"];  
  
  
  								  
  
  
  /* insert to letter.send_address  */
  

  //check cusleter more 1 
    $qry_lt=pg_query($db_connect,"select * from letter.send_address 
	                              where (\"IDNO\"='$c_idno') and (\"CusState\"='$c_custate') and (active=TRUE);");
	$numr_lt=pg_num_rows($qry_lt);
	if($numr_lt==0)
	{
  
  
    $gen_ltr=pg_query("select letter.gen_cusletid('$c_idno')"); //gen letter
	$res_genltr=pg_fetch_result($gen_ltr,0);
	
	//echo "<br>"."gen id=".$res_genltr;
	
		$ins_send_ads="insert into letter.send_address 	
					   (\"CusLetID\",\"IDNO\",record_date,\"name\",active,userid,dtl_ads,\"CusState\")
					   values
					   ('$res_genltr','$c_idno','$datenow','$c_name',TRUE,'$userid','$c_ads',$c_custate)";
		 
		 if($result=pg_query($db_connect,$ins_send_ads))
		 {
		  $status ="OK".$ins_send_ads;
		 }
		 else
		 {
		  $status ="error insert Re".$ins_send_ads;
		 }
		
		echo $status;
    }

          $qry_lt2=pg_query($db_connect,"select * from letter.send_address 
	                              where (\"IDNO\"='$c_idno') and (\"CusState\"='$c_custate') and (active=TRUE);");				  
	 $res_idli=pg_fetch_array($qry_lt2);
	 
	 $fs_ltsid=$res_idli["CusLetID"];
	 
	 $in_lt="Update letter.send_address SET dtl_ads='$c_ads' WHERE \"CusLetID\"='$fs_ltsid' ";
		if($result=pg_query($db_connect,$in_lt))
		 {
		  $statuss ="OK update at Fn".$in_lt;
		  $st="Update มูลเรียบร้อย";
		 }
		 else
		 {
		  $statuss ="error update  Fn Re".$in_lt;
		   $st="เกิดข้อผิดพลาด";
		 }	
		

  /* echo $status." ### ".$st; */
    
}

?>