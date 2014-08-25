<?php
set_time_limit (0);
ini_set("memory_limit","1024M"); 
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>list fp</title>
</head>

<body>


  <?php

	 $sql_in=mssql_query("SELECT *,convert(varchar,ISNULL(P_STDATE, ' '),111) AS STDATE,convert(varchar,ISNULL(P_FDATE,''),111) AS FDATE,convert(varchar,ISNULL(P_CLDATE, ' '),111) AS P_CLDATE,
	  ISNULL(P_VAT,'0')  AS P_VAT,
	  ISNULL(P_MONTH,'0') AS P_MONTH,
	  ISNULL(P_TOTAL,'0')  AS P_TOTAL,
	  ISNULL(P_DOWN,'0')  AS P_DOWN,
	  ISNULL(P_VatOfDown,'0')  AS P_VatOfDown,
	  ISNULL(P_BEGIN,'0')  AS P_BEGIN,
	  ISNULL(TranIDRef1,'') AS TranIDRef1,
	  ISNULL(TranIDRef2,'') AS TranIDRef2,
	  ISNULL (P_CustByYear,'') AS P_CustByYear,
	  ISNULL (P_ACCLOSE,'') AS P_ACCLOSE,
	  ISNULL (P_SL,0) AS P_SL,
	  ISNULL (P_StopVat,'') AS P_StopVat,
	  ISNULL (P_BEGINX,0) AS P_BEGINX 
	  
	  
	  FROM Fp",$conn);
	  
	  
	 while($res_fp=mssql_fetch_array($sql_in))
	  {
	    $res_id=$res_fp["IDNO"];
		
		
		
		$sql_fa1=mssql_query("SELECT DISTINCT A.IDNO, B.o_name, B.CusID
                              FROM   Fa1 A LEFT OUTER JOIN
                      fill_CusID B ON B.o_name = A.A_NAME 
					  WHERE A.IDNO='$res_fp[IDNO]' ",$conn);
		$res_fa1=mssql_fetch_array($sql_fa1);	
		if(empty($res_fa1["CusID"]))
		{
		  $res_cid=" ";
		}
		else
		{
		  $res_cid=$res_fa1["CusID"];
		}
		

		
		$sql_asset=mssql_query("select * from fill_assetID where t_idno='$res_fp[IDNO]' ",$conn);
		$res_asset=mssql_fetch_array($sql_asset);
		
		$asset_id=$res_asset["t_asset_id"];
		
     
	 $str_idno=substr($res_id,0,1);
	 if($str_idno=="5")
	 {
	   $id_asset="2";
	 }
	 else
	 {
	   $id_asset="1";
	 }
	 
	
	 
$ins_fp="insert into \"Fp\" (\"IDNO\",\"CusID\",\"P_STDATE\",\"TranIDRef1\",\"TranIDRef2\",
                     \"P_DOWN\",\"P_TOTAL\",\"P_MONTH\",\"P_FDATE\",\"P_BEGIN\",
	 			     \"P_VatOfDown\",\"P_VAT\",\"LockContact\",asset_type,asset_id,\"P_CustByYear\",
					 \"P_ACCLOSE\",\"P_SL\",\"P_StopVat\",\"P_CLDATE\",\"P_BEGINX\"
					 ) 
                     values  
                    ('$res_id','$res_cid','$res_fp[STDATE]','$res_fp[TranIDRef1]'
					,'$res_fp[TranIDRef2]'
					,'$res_fp[P_DOWN]','$res_fp[P_TOTAL]','$res_fp[P_MONTH]','$res_fp[FDATE]'
					,'$res_fp[P_BEGIN]'
                    ,'$res_fp[P_VatOfDown]','$res_fp[P_VAT]',FALSE,'$id_asset','$asset_id','$res_fp[P_CustByYear]'
					,'$res_fp[P_ACCLOSE]','$res_fp[P_SL]','$res_fp[P_StopVat]','$res_fp[P_CLDATE]','$res_fp[P_BEGINX]'
					)";

  if($result_fp=pg_query($ins_fp))
  {
    $st= " ";
  }
  else
  {
    $st= "error at ".$ins_fp;
  }
  
 
   echo $st;
   
   
   /* insert Contact Cus */
   $ins_cc="insert into \"ContactCus\" (\"IDNO\",\"CusState\",\"CusID\") 
                  values  
                 ('$res_id',0,'$res_cid')";
				 
	   			 
		if($result_fc=pg_query($ins_cc))
		  {
			$st= "";
		  }
		  else
		  {
			$st= "error at ".$ins_cc;
		  }
		  
		  echo $st;		 	
   
   

  }
  
  ?>


</body>
</html>
