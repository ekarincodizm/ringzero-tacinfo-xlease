s<?php
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
   $sql_fa=mssql_query("select * from fill_CusID",$conn);
   while($res_fa=mssql_fetch_array($sql_fa))
   {
     $fa_cusid=$res_fa["CusID"];
	  
	  $len_name=strlen($res_fa["p_name"]);
	  if($len_name > 29)
	  {
	    $im_name=substr($res_fa["p_name"],0,29);
		$i_pname=iconv('WINDOWS-874','UTF-8',$im_name);
	  }
	  else
	  {
	   $i_pname=iconv('WINDOWS-874','UTF-8',$res_fa["p_name"]);
	   $i_surname=iconv('WINDOWS-874','UTF-8',$res_fa["p_surname"]);
	  }
	  
	 
	  
	 
	 $sql_msfa1=mssql_query("select DISTINCT A_NAME ,A_FIRNAME,
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
	 
	 
	 
	 
	 
	 $find_fa1=pg_query("select \"CusID\" from \"Fa1\" WHERE \"CusID\"='$fa_cusid'");
	 $res_findfa1=pg_fetch_array($find_fa1);
	 
	 if($res_findfa1["CusID"]=='$fa_cusid')
	 {
	    echo "find fa1 at".$fa_cusid."<br>";
	 }
	 else
	 {
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
		  }
		  
		 
		   echo $st;
	 
	
	 
	    
	 
	 $sql_fp=pg_query("SELECT * from \"Fp\" where \"CusID\"='$fa_cusid'");
	 $res_fp=pg_fetch_array($sql_fp);
	 
	 if(empty($res_fp["IDNO"]))
	 {
	    $i_idno="";
	 }
	 else
	 {
	    $i_idno=$res_fp["IDNO"];
	 
	  /*data fn */ 
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
		
		
		if(empty($res_fn["IDNO"]))
		{
		  $ins_fn="insert	into \"Fn\" 
		         (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\", 
                 \"N_IDCARD\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\")
		         values
				 ('$fa_cusid','0','0','0',
				  '0','0','0','0','0')";
		
		
  
   
  
        	
		
		  
		}
		else
		{
	   			  
				  
		$cc_add=str_replace("'","-","$i_contactadd"); 		  
				  
		$ins_fn="insert	into \"Fn\" 
		         (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\", 
                 \"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\")
		         values
				 ('$fa_cusid','0','$i_san','$i_age',
				  '$i_card','$i_idcard','$i_ot_date',
				  '$i_by','$i_occ','$cc_add')";
		
		
        }
   
  
        	
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
	 
	  // echo $res_fp["IDNO"]." / ".$res_fp["CusID"]."<br>";
	   
	   

	 
	 
	 
	   
	   
	      
	   
		
		 
		/*
		$ins_cc="insert into \"ContactCus\" (\"IDNO\",\"CusState\",\"CusID\") 
                  values  
                 ('$i_idno',0,'$fa_cusid')";
				 
	   			 
		if($result_fc=pg_query($ins_cc))
		  {
			$st= "";
		  }
		  else
		  {
			$st= "error at ".$ins_cc;
		  }
		  
		  echo $st;		 	
		  */
	  	  
					  
	   //echo "###".$ins_fa1."<br>"."###".$ins_fn."<br>"."###".$ins_cc."<br>";
	
	  
   }
   }
  ?>


</body>
</html>
