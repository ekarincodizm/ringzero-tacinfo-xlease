<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
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
   $sql_fa=mssql_query("select * from fill_Fn ",$conn);
   while($res_fa=mssql_fetch_array($sql_fa))
   {
      
      if($res_fa["N_state"]=="0") //Fa1
	  {
	 
		$fa_cusid=$res_fa["CusID"];
		$sql_fcusid=mssql_query("select * from fill_CusID where CusID='$fa_cusid'",$conn);
		$res_fcusid=mssql_fetch_array($sql_fcusid);
		
	    $len_name=strlen($res_fcusid["p_name"]);
	    if($len_name > 29)
	    {
	     $im_name=substr($res_fcusid["p_name"],0,29);
		 $i_pname=iconv('WINDOWS-874','UTF-8',$im_name);
	    }
	    else
	    {
	     $i_pname=iconv('WINDOWS-874','UTF-8',$res_fcusid["p_name"]);
	     $i_surname=iconv('WINDOWS-874','UTF-8',$res_fcusid["p_surname"]);
	    }
	   
	    $sql_msfa1=mssql_query("select TOP 1 A_NAME ,A_FIRNAME,
	            A_PAIR,A_NO,A_SUBNO,A_SOI,A_RD,A_TUM,A_AUM,A_PRO
				from 
				Fa1 WHERE A_NAME='$res_fa[o_name]'",$conn);
	     $res_msfa1=mssql_fetch_array($sql_msfa1);
	 
		 
		 $i_firname=iconv('WINDOWS-874','UTF-8',$res_msfa1["A_FIRNAME"]);
		 $i_pair=iconv('WINDOWS-874','UTF-8',$res_msfa1["A_PAIR"]);
		 $i_no=iconv('WINDOWS-874','UTF-8',$res_msfa1["A_NO"]);
		 $i_subno=iconv('WINDOWS-874','UTF-8',$res_msfa1["A_SUBNO"]);
		 $i_soi=iconv('WINDOWS-874','UTF-8',$res_msfa1["A_SOI"]);
		 $i_rd=iconv('WINDOWS-874','UTF-8',$res_msfa1["A_RD"]);
		 $i_tum=iconv('WINDOWS-874','UTF-8',$res_msfa1["A_TUM"]);
		 $i_aum=iconv('WINDOWS-874','UTF-8',$res_msfa1["A_AUM"]);
		 $i_pro=iconv('WINDOWS-874','UTF-8',$res_msfa1["A_PRO"]);
	     
		 
		 $ins_fa1="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",
		               \"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\"
		              )
		              values
					  ('$fa_cusid','$i_firname','$i_pname','$i_surname',
					  
					   '$i_pair','$i_no','$i_subno',
					   '$i_soi','$i_rd','$i_tum','$i_aum',
					   '$i_pro'
					  )";
		
		if($result_fa1=pg_query($ins_fa1))
		  {
			$st= "";
		  }
		  else
		  {
			$st= "error at ".$ins_fa1;
		  }echo $st;
		 
		 $sql_fp=pg_query("SELECT * from \"Fp\" where \"CusID\"='$fa_cusid'");
		 $res_fp=pg_fetch_array($sql_fp);
		 
		 if(empty($res_fp["IDNO"]))
		 {
			$i_idno="";
			
			$ins_fn="insert	into \"Fn\" 
					 (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\", 
					 \"N_IDCARD\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\")
					 values
					 ('$fa_cusid','0','0','0',
					  '0','0','0','0','0')";
			
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
			$i_idno=$res_fp["IDNO"];
		 
		
			$sql_fn=mssql_query("SELECT *,convert(varchar,ISNULL(N_OT_DATE,''),111) AS OTDATE,
			
			ISNULL(N_SAN,'0')  AS N_SAN,
			ISNULL(N_AGE,'0')  AS N_AGE,
			ISNULL(N_CARD,'0')  AS N_CARD,
			ISNULL(N_IDCARD,'0')  AS N_IDCARD,
			ISNULL(N_BY,'0')  AS N_BY,
			ISNULL(N_OCC,'0')  AS N_OCC,
			ISNULL(N_ContactAdd,'0')  AS N_ContactAdd,
			N_STATE,IDNO
			
			
			 from Fn  WHERE (IDNO='$i_idno') AND (N_STATE='0') ",$conn);
			$res_fn=mssql_fetch_array($sql_fn);
			
			$i_san=iconv('WINDOWS-874','UTF-8',$res_fn["N_SAN"]);
			$i_age=iconv('WINDOWS-874','UTF-8',$res_fn["N_AGE"]);
			$i_card=iconv('WINDOWS-874','UTF-8',$res_fn["N_CARD"]);
			$i_idcard=iconv('WINDOWS-874','UTF-8',$res_fn["N_IDCARD"]);
			$i_ot_date=iconv('WINDOWS-874','UTF-8',$res_fn["OTDATE"]);
			$i_by=iconv('WINDOWS-874','UTF-8',$res_fn["N_BY"]);
			$i_occ=iconv('WINDOWS-874','UTF-8',$res_fn["N_OCC"]);
			$i_contactadd=iconv('WINDOWS-874','UTF-8',$res_fn["N_ContactAdd"]);
			
			
		
		    $cc_add=str_replace("'","-","$i_contactadd"); 		  
					  
			$ins_fn="insert	into \"Fn\" 
					 (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\", 
					 \"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\")
					 values
					 ('$fa_cusid','0','$i_san','$i_age',
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
		
	  }
	  else 
	  {
	    //fa2
			
		
		
		
		$fa_cusid=$res_fa["CusID"];
		
		
		$sql_fcusid2=mssql_query("select * from fill_CusID where CusID='$fa_cusid'",$conn);
		$res_fcusid2=mssql_fetch_array($sql_fcusid2);
		
	    $len_name2=strlen($res_fcusid2["p_name"]);
	    if($len_name2 > 29)
	    {
	     $im_name2=substr($res_fcusid2["p_name"],0,29);
		 $i_pname2=iconv('WINDOWS-874','UTF-8',$im_name2);
	    }
	    else
	    {
	     $i_pname2=iconv('WINDOWS-874','UTF-8',$res_fcusid2["p_name"]);
	     $i_surname2=iconv('WINDOWS-874','UTF-8',$res_fcusid2["p_surname"]);
	    }
	   
	    $sql_msfa2=mssql_query("select TOP 1 A2_STATE,A2_NAME ,A2_FIRNAME,
	            A2_PAIR,A2_NO,A2_SUBNO,A2_SOI,A2_RD,A2_TUM,A2_AUM,A2_PRO
				from 
				Fa2 WHERE A2_NAME='$res_fcusid2[o_name]'",$conn);
	     $res_msfa2=mssql_fetch_array($sql_msfa2);
	 
		 $st_a2=$res_msfa2["A2_STATE"];
		 
		 $i_firname2=iconv('WINDOWS-874','UTF-8',$res_msfa2["A2_FIRNAME"]);
		 $i_pair2=iconv('WINDOWS-874','UTF-8',$res_msfa2["A2_PAIR"]);
		 $i_no2=iconv('WINDOWS-874','UTF-8',$res_msfa2["A2_NO"]);
		 $i_subno2=iconv('WINDOWS-874','UTF-8',$res_msfa2["A2_SUBNO"]);
		 $i_soi2=iconv('WINDOWS-874','UTF-8',$res_msfa2["A2_SOI"]);
		 $i_rd2=iconv('WINDOWS-874','UTF-8',$res_msfa2["A2_RD"]);
		 $i_tum2=iconv('WINDOWS-874','UTF-8',$res_msfa2["A2_TUM"]);
		 $i_aum2=iconv('WINDOWS-874','UTF-8',$res_msfa2["A2_AUM"]);
		 $i_pro2=iconv('WINDOWS-874','UTF-8',$res_msfa2["A2_PRO"]);
	     
		 
		 $ins_fa2="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",
		               \"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\"
		              )
		              values
					  ('$fa_cusid','$i_firname2','$i_pname2','$i_surname2',
					  
					   '$i_pair2','$i_no2','$i_subno2',
					   '$i_soi2','$i_rd2','$i_tum2','$i_aum2',
					   '$i_pro2'
					  )";
	
	
		if($result_fa2=pg_query($ins_fa2))
		  {
			$st= "";
		  }
		  else
		  {
			$st= "error at ".$ins_fa2;
		  }echo $st;
		  
	
	  
	     
		 $sql_fa2=mssql_query("SELECT TOP 1 * from Fa2 WHERE (A2_NAME='$res_msfa2[A2_NAME]') AND (A2_STATE='$st_a2')");
		 $res_fa2=mssql_fetch_array($sql_fa2);
			
	     
		 
				
		    $sql_fn2=mssql_query("SELECT *,convert(varchar,ISNULL(N_OT_DATE,''),111) AS OTDATE,
			ISNULL(N_SAN,'0')  AS N_SAN,
			ISNULL(N_AGE,'0')  AS N_AGE,
			ISNULL(N_CARD,'0')  AS N_CARD,
			ISNULL(N_IDCARD,'0')  AS N_IDCARD,
			ISNULL(N_BY,'0')  AS N_BY,
			ISNULL(N_OCC,'0')  AS N_OCC,
			ISNULL(N_ContactAdd,'0')  AS N_ContactAdd,
			N_STATE,IDNO
			from Fn  WHERE (IDNO='$res_fa2[IDNO]') AND (N_STATE='$st_a2')",$conn);
			
			$res_fn2=mssql_fetch_array($sql_fn2);
			
			$i_san2=iconv('WINDOWS-874','UTF-8',$res_fn2["N_SAN"]);
			$i_age2=iconv('WINDOWS-874','UTF-8',$res_fn2["N_AGE"]);
			$i_card2=iconv('WINDOWS-874','UTF-8',$res_fn2["N_CARD"]);
			$i_idcard2=iconv('WINDOWS-874','UTF-8',$res_fn2["N_IDCARD"]);
			$i_ot_date2=iconv('WINDOWS-874','UTF-8',$res_fn2["OTDATE"]);
			$i_by2=iconv('WINDOWS-874','UTF-8',$res_fn2["N_BY"]);
			$i_occ2=iconv('WINDOWS-874','UTF-8',$res_fn2["N_OCC"]);
			$i_contactadd2=iconv('WINDOWS-874','UTF-8',$res_fn2["N_ContactAdd"]);
		    $cc_add2=str_replace("'","-","$i_contactadd2"); 		  
					  
			if(empty($res_fn2["IDNO"]))
			{
			  $ins_fn2="insert into \"Fn\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\", 
					 \"N_IDCARD\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\")
					 values
					 ('$fa_cusid','1','0','0',
					  '0','0','0','0','0')";
			  if($result_fn2=pg_query($ins_fn2))
			  {
				$st2="";
			  }
			  else
			  {
				$st2="error at ".$ins_fn2;
			  }
			  
			  echo $st2;
					  
			}
			else
			{
			
			
			 $ins_fn2="insert into \"Fn\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\", 
					 \"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\")
					 values
					 ('$fa_cusid','1','$i_san2','$i_age2',
					  '$i_card2','$i_idcard2','$i_ot_date2',
					  '$i_by2','$i_occ2','$cc_add2')";
			
		    
			
			if($result_fn2=pg_query($ins_fn2))
			  {
				$st2="";
			  }
			  else
			  {
				$st2="error at ".$ins_fn2;
			  }
			  
			  echo $st2;
	    
		 }
		} 
     }
  ?>
</body>
</html>