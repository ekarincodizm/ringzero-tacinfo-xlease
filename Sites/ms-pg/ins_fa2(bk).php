<?php
set_time_limit (0); 
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>insert to Fa1</title>
</head>

<body>
  <?php
   $sql_fp=mssql_query("select  * from Fp",$conn);
   while($res_fp=mssql_fetch_array($sql_fp))
   {
     echo "IDNO ".$i_idno=$res_fp["IDNO"]."<br>";
	 //echo $i_idno."<br>";
     $sql_fa2=mssql_query("select * from Fa2 WHERE IDNO='$i_idno' ",$conn);
	 while($res_fa2=mssql_fetch_array($sql_fa2))
	 {
	       if(empty($res_fa2["IDNO"]))
		   {
		     echo "IDNO ".$i_idno." ไม่พบ ใน fa2";
		   }
		   else
		   {
		   
	         
	     
		     $i_firname=iconv('WINDOWS-874','UTF-8',$res_fa2["A2_FIRNAME"]);
			 $i_pair=iconv('WINDOWS-874','UTF-8',$res_fa2["A2_PAIR"]);
			 $i_no=iconv('WINDOWS-874','UTF-8',$res_fa2["A2_NO"]);
			 $i_subno=iconv('WINDOWS-874','UTF-8',$res_fa2["A2_SUBNO"]);
			 $i_soi=iconv('WINDOWS-874','UTF-8',$res_fa2["A2_SOI"]);
			 $i_rd=iconv('WINDOWS-874','UTF-8',$res_fa2["A2_RD"]);
			 $i_tum=iconv('WINDOWS-874','UTF-8',$res_fa2["A2_TUM"]);
			 $i_aum=iconv('WINDOWS-874','UTF-8',$res_fa2["A2_AUM"]);
			 $i_pro=iconv('WINDOWS-874','UTF-8',$res_fa2["A2_PRO"]);
		
		
		
		$sql_fCusid=mssql_query("select DISTINCT o_name,CusID,p_name,p_surname from fill_CusID WHERE o_name='$res_fa2[A2_NAME]'");
		$res_fCusid=mssql_fetch_array($sql_fCusid);
	    
		
		
			
		$len_name=strlen($res_fCusid["p_name"]);
		  if($len_name > 29)
		  {
			$im_name=substr($res_fCusid["p_name"],0,29);
			$i_pname=iconv('WINDOWS-874','UTF-8',$im_name);
		  }
		  else
		  {
		   $i_pname=iconv('WINDOWS-874','UTF-8',$res_fCusid["p_name"]);
		   $i_surname=iconv('WINDOWS-874','UTF-8',$res_fCusid["p_surname"]);
		  }
		
				
		$i_name=iconv('WINDOWS-874','UTF-8',$res_fCusid["o_name"]);
		
		
		
			
		$find_fa1=pg_query("select \"CusID\" from \"Fa1\" WHERE \"CusID\"='$res_fCusid[CusID]'");
		 $res_findfa1=pg_fetch_array($find_fa1);
		 
		 if($res_findfa1["CusID"]=='$res_fCusid[CusID]')
		 {
			//echo "find fa1 at".$fa_cusid."<br>";
		 }
		 else
		 {
		$ins_fa2="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",
		               \"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\"
		              )
		              values
					  ('$res_fCusid[CusID]','$i_firname','$i_pname','$i_surname',
					  
					   '$i_pair','$i_no','$i_subno',
					   '$i_soi','$i_rd','$i_tum','$i_aum',
					   '$i_pro'
					  )";
		
		 }
		
		echo $ins_fa2."<br>";
		 
		 
		     
		/*insert pg.Fn */
		
		$sql_fn=mssql_query("SELECT *,convert(varchar,ISNULL(N_OT_DATE,''),111) AS OTDATE,
		
		ISNULL(N_SAN,'0')  AS N_SAN,
		ISNULL(N_AGE,'0')  AS N_AGE,
		ISNULL(N_CARD,'0')  AS N_CARD,
		ISNULL(N_IDCARD,'0')  AS N_IDCARD,
		ISNULL(N_BY,'0')  AS N_BY,
		ISNULL(N_OCC,'0')  AS N_OCC,
		ISNULL(N_ContactAdd,'0')  AS N_ContactAdd,
		N_STATE,IDNO
		
		
		 from Fn  WHERE (IDNO='$i_idno') AND (N_STATE ='$res_fa2[A2_STATE]') ",$conn);
		$res_fn=mssql_fetch_array($sql_fn);
		
		$i_san=iconv('WINDOWS-874','UTF-8',$res_fn["N_SAN"]);
		$i_age=iconv('WINDOWS-874','UTF-8',$res_fn["N_AGE"]);
		$i_card=iconv('WINDOWS-874','UTF-8',$res_fn["N_CARD"]);
		$i_idcard=iconv('WINDOWS-874','UTF-8',$res_fn["N_IDCARD"]);
		$i_ot_date=iconv('WINDOWS-874','UTF-8',$res_fn["OTDATE"]);
		$i_by=iconv('WINDOWS-874','UTF-8',$res_fn["N_BY"]);
		$i_occ=iconv('WINDOWS-874','UTF-8',$res_fn["N_OCC"]);
		$i_contactadd=iconv('WINDOWS-874','UTF-8',$res_fn["N_ContactAdd"]);
		
		
		if(empty($res_fn["IDNO"]))
		{
		  /*
		  $ins_fn="insert	into \"Fn\" 
		         (\"CusID\",\"N_SAN\",\"N_AGE\",\"N_CARD\", 
                 \"N_IDCARD\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\")
		         values
				 ('$res_fCusid[CusID]','0','0',
				  '0','0','0','0','0')";
		 
		  */
		}
		else
		{
	   			  
		
		$sql_fi_fn=pg_query("select \"CusID\" from \"Fn\" where \"CusID\"='$res_fCusid[CusID]'");
		$res_fi_fn=pg_fetch_array($sql_fi_fn);
		if(empty($res_fi_fn["CusID"]))
		{
		 
		
		
		 		  
		$cc_add=str_replace("'","-","$i_contactadd"); 		  
				  
		$ins_fn="insert	into \"Fn\" 
		         (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\", 
                 \"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\")
		         values
				 ('$res_fCusid[CusID]','$res_fa2[A2_STATE]','$i_san','$i_age',
				  '$i_card','$i_idcard','$i_ot_date',
				  '$i_by','$i_occ','$cc_add')";
		
		if($result_fn=pg_query($ins_fn))
		  {
			$st= "";
		  }
		  else
		  {
			$st= "error at ".$ins_fn;
		  }
		  
		  echo $st;
		   
		}
		else
		{
		
		}
		
        }
   
  		
	   }
	 }
   }
  
  ?>
</table>

</body>
</html>
