<?php
set_time_limit (0);
ini_set("memory_limit","128M"); 
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>list oldfp</title>
</head>

<body>


  <?php
   $sql_idno=pg_query("select  \"IDNO\" from \"Fp\"  ");
   while($res_id=pg_fetch_array($sql_idno))
   {
      
	  //echo $res_id["IDNO"]."<br>";
	  
	  
	  $sql_oid2=mssql_query("select IDNO,OLD_IDNO from Fa1 WHERE OLD_IDNO='$res_id[IDNO]'",$conn);
	  $num_row=mssql_num_rows($sql_oid2);
	  
	  if($num_row==0)
	  {
	  
	  }
	  else
	  {
	    $sql_oid=mssql_query("select IDNO,OLD_IDNO from Fa1 WHERE OLD_IDNO='$res_id[IDNO]'",$conn);
		$res_oid=mssql_fetch_array($sql_oid);
		
		$ins_fpid="update \"Fp\"  SET \"P_TransferIDNO\"='$res_oid[IDNO]' WHERE \"IDNO\"='$res_id[IDNO]' ";
		if($result=pg_query($ins_fpid))
		 {
		  $statuss ="";
		 }
		 else
		 {
		  $statuss ="error update  Fp at".$ins_fpid;
		 }	 		
				
		echo	$statuss."<br>";	
	  }	  
   }
  ?>
</body>
</html>
