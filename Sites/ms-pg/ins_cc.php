<?php
set_time_limit (0); 
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>insert to Fc</title>
</head>

<body>
  <?php
   $sql_fp=pg_query("select * from \"Fp\" ");
   while($res_fp=pg_fetch_array($sql_fp))
   {
     
	 $fp_idno=$res_fp["IDNO"];
	 $sql_cc=mssql_query("select IDNO,A2_NAME,A2_STATE from Fa2 WHERE IDNO='$fp_idno' ",$conn);
	 while($res_cc=mssql_fetch_array($sql_cc))
	 {
	   $sql_cusid=mssql_query("select * from fill_CusID WHERE o_name='$res_cc[A2_NAME]'",$conn);
	   $res_cid=mssql_fetch_array($sql_cusid);
	   $i_pname=iconv('WINDOWS-874','UTF-8',$res_cc["A2_NAME"]);
	   
	   //echo $res_cc["IDNO"]." ".$res_cc["A2_STATE"]." ".$i_pname." ".$res_cid["CusID"]."<br>";
	   
	   $ins_cc="insert into \"ContactCus\" (\"IDNO\",\"CusState\",\"CusID\")values('$res_cc[IDNO]','$res_cc[A2_STATE]','$res_cid[CusID]')";
	   if($res_cc=pg_query($ins_cc))
	   {
	      $rebc="";
	   }
	   else
	   {
	      $rebc="error at ".$ins_cc;
	   }
	   
	    echo $rebc."<br>";
	 } 
	  
   }
  
  ?>
</table>

</body>
</html>
