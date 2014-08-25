<?php
set_time_limit (0); 
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
	   function insertZero($inputValue , $digit )
			{
				$str = "" . $inputValue;
				while (strlen($str) < $digit)
				{
					$str = "0" . $str;
				}
				return $str;
			}


 $yy=$_POST["f_year"];
 $sql_fa2=mssql_query("SELECT  A2_NAME, COUNT(A2_NAME) AS NumOccurrences
FROM         Fa2
GROUP BY A2_NAME
HAVING      (COUNT(A2_NAME) >= 1)",$conn);
 $i=1;
 $si=0;
 //echo $res_row=mssql_num_rows($sql_fp);
 //echo $sql_fp;
 
  while($res_name2=mssql_fetch_array($sql_fa2))
  { 
   $name_fa2=$res_name2["A2_NAME"];
   $i_pname=iconv('WINDOWS-874','UTF-8',$res_name2["A2_NAME"]);
   
   $newStr = ereg_replace('[[:space:]]+', ',', trim($i_pname));
   $cut_str=explode(",",$newStr);
   //echo $cus_sn." ".$cut_str[0]." ".$cut_str[1]."  ".$res_name["NumOccurrences"]."<br>"; 
   

   // insert to temp
   // preview before insert to temp
   
      //gen id 
	   $gen_id=mssql_query("select * from number_id WHERE typeID='C' ",$conn);
	   $res_id=mssql_fetch_array($gen_id);
	   $res_count=$res_id["run_number"];
	   if($res_count==0)
		{
		  $res_sn=1;
		}
		else
		{
		  $res_sn=$res_id["run_number"]+1;
		}
	   
	   $cus_sn="C".insertZero($res_sn , 5);
	 //  echo $cus_sn." ".$cut_str[0]." ".$cut_str[1]."  ".$res_name["NumOccurrences"]."<br>"; 
     
	 // insert fill_CusID
	   
	    $i_sname=iconv('UTF-8','WINDOWS-874',$cut_str[0]);
		$i_surname=iconv('UTF-8','WINDOWS-874',$cut_str[1]);
	   $sql_infa2="insert into fill_CusID (CusID,o_name,p_name,p_surname)values('$cus_sn','$name_fa2','$i_sname','$i_surname')";
	   if($res_fa2=mssql_query($sql_infa2,$conn))
	   {
	    $sbd="";
	   }
	   else
	   {
	    $sbd="fail at".$res_fa2;
	   }
	 
	 echo "<br>".$sbd;
	 
	 

	  $sql_infn="insert into fill_Fn (CusID,N_state,o_name)values('$cus_sn','1','$name_fa2')";
	   if($res_fn=mssql_query($sql_infn,$conn))
	   {
	    $sbd_fn="";
	   }
	   else
	   {
	    $sbd_fn="fail fn ar".$res_fn;
	   }
	 
	 echo "<br>".$sbd_fn;
	 
	 
	 
	 


	 $update_number="update number_id SET run_number='$res_sn' WHERE typeID='C'";
	 if($result=mssql_query($update_number,$conn))
	 {
	   $sb="";
	 }
	 else
	 {
	   $sb="not OK";
	 }
   } 
?>

</body>
</html>
