<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

include("../config/config.php");

$qry_cc=pg_query($db_connect,"select * from \"ContactCus\" ");
while($res_cc=pg_fetch_array($qry_cc))
{
  $c_idno=$res_cc["IDNO"];
  $c_custate=$res_cc["IDNO"];
  $c_cusid=$res_cc["CusID"];
  
  
   
  
  $qry_name=pg_query($db_connect,"select \"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"CusID\" 
                                  from \"Fa1\"
								  WHERE \"CusID\"='$c_cusid' ");
  $res_nn=pg_fetch_array($qry_name);
  
  $n_name=$res_nn["A_FIRNAME"]." ".$res_nn["A_NAME"]."  ".$res_nn["A_SIRNAME"];
  
  
   $qry_ads=pg_query($db_connect,"select * from ");  								  
  
  
  /* insert to letter.send_address  */
  

  
  
  
  

}

?>