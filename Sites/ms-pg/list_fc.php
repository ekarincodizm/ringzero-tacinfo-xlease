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


 $sql_fp=mssql_query("SELECT  C_CARNUM, COUNT(C_CARNUM) AS NumOccurrences
FROM         Fc
WHERE (C_CARNUM!='-') AND (C_CARNUM!='--')
GROUP BY C_CARNUM
HAVING      (COUNT(C_CARNUM) >= 1)",$conn);
 $i=1;
 $si=0;
 //echo $res_row=mssql_num_rows($sql_fp);
 //echo $sql_fp;
 
  while($res_name=mssql_fetch_array($sql_fp))
  { 
   $name_fa1=$res_name["C_CARNUM"];
   $i_pname=iconv('WINDOWS-874','UTF-8',$res_name["C_CARNUM"]);
   
   
  // $cut_str=explode("  ",$i_pname);
   //echo $cus_sn." ".$cut_str[0]." ".$cut_str[1]."  ".$res_name["NumOccurrences"]."<br>"; 
   

   // insert to temp
   // preview before insert to temp
   
      //gen id 
	   $gen_id=mssql_query("select * from number_id WHERE typeID='CAR' ",$conn);
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
	   
	   $cus_sn="CAR".insertZero($res_sn, 5);
	 //echo $cus_sn." ".$i_pname."  ".$res_name["NumOccurrences"]."<br>"; 
       
	   // insert fill_CusID TRUNCATE TABLE [fill_CarID]
   
	   // $i_sname=iconv('UTF-8','WINDOWS-874',$cut_str[0]);
	   // $i_surname=iconv('UTF-8','WINDOWS-874',$cut_str[1]);
	   $sql_infa1="insert into fill_CarID (car_id,car_number)values('$cus_sn','$i_pname')";
	   if($res_fa1=mssql_query($sql_infa1,$conn))
	   {
	    $sbd="";
	   }
	   else
	   {
	    $sbd="OK ar".$sql_infa1;
	   }
	 
	 echo "<br>".$sbd;
	 
	 $update_number="update number_id SET run_number='$res_sn' WHERE typeID='CAR'";
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
