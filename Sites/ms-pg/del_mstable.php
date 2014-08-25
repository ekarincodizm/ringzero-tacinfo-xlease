<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
include("config/config.php");
$_GET["id"]; 
 
 
   if($_GET["id"]=="number_id")
   {
     $sql_del="TRUNCATE TABLE $_GET[id]";
	 if($res_del=mssql_query($sql_del,$conn))
		  {
			$st= "Empty data from $_GET[id]. OK";
		  }
		  else
		  {
			$st= "error at ".$ins_fa1;
		  }echo $st;
	 
		  
		  
   }
   else if($_GET["id"]=="fill_CusID")
   {
       $del_cusid=mssql_query("TRUNCATE TABLE fill_Fn",$conn);
	   $del_cusid=mssql_query("TRUNCATE TABLE fill_CusID",$conn);
	   echo "del fill_CusID , fill_Fn";
   }
   else
    {
 
   
	     $sql_del="TRUNCATE TABLE $_GET[id]";
 	      if($res_del=mssql_query($sql_del,$conn))
		  {
			$st= "del data from $_GET[id]. OK";
		  }
		  else
		  {
			$st= "error at ".$ins_fa1;
		  }echo $st;
	
	
	
	}
	
     		  
?>
<button onclick="javascript:window.close();">CLOSE</button>
</body>
</html>
