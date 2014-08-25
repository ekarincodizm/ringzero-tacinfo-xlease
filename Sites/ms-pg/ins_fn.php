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
	  
   }
  
  ?>
</table>

</body>
</html>
