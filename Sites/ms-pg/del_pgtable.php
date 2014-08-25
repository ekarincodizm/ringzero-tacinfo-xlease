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
 
   
  // $res_del=mssql_query($sql_del,$conn);
   
   if($_GET["id"]=="Fa1")
   {
     $sql_delfa1="TRUNCATE TABLE \"Fa1\" ";
	 if($res_fa1=pg_query($sql_delfa1))
	 {
	  $st= "del data from Fa1. OK"."<br>";
     }
	 else
	 {
	  $st= "error at ".$ins_fa1;
	 } echo $st;
	 
	 $sql_delfn="TRUNCATE TABLE \"Fn\" ";
	 if($res_fn=pg_query($sql_delfn))
	 {
	  $st= "del data from Fn. OK"."<br>";
     }
	 else
	 {
	  $st= "error at ".$sql_delfn;
	 } echo $st;
	 
	 
   }
   else if($_GET["id"]=="Fp") 
   {
     $res_fp="Fp";
	 $res_cc="ContactCus";
	 $del_pgfp=pg_query("TRUNCATE TABLE \"$res_fp\" ");
	 $del_pgcc=pg_query("TRUNCATE TABLE \"$res_cc\" ");
     
	 echo "Delect data Fp - ContactCus";
    
   }
   else if($_GET["id"]=="ContactCus") 
   {
     
	 $del_pgcc=pg_query("delete from \"ContactCus\" WHERE \"CusState\" !=0");
     
	 echo "Delect data Fp - ContactCus(Fn)";
    
   }
   
   else if($_GET["id"]=="Fc") 
   {
	 $del_pgfp=pg_query("TRUNCATE TABLE \"Fc\" ");
	 $del_pgcc=pg_query("TRUNCATE TABLE \"FGas\" ");
     
	 echo "Delect data FC - FGas";
    
   }
   
   else 
   {
 
    $sql_del="TRUNCATE TABLE \"$_GET[id]\" ";
 	if($res_del=pg_query($sql_del))
		  {
			$st= "del data from $_GET[id]. OK";
		  }
		  else
		  {
			$st= "error at ".$sql_del;
		  }echo $st;
   }		  
?>
<button onclick="javascript:window.close();">CLOSE</button>
</body>
</html>
