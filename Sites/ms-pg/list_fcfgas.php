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
 $sql_fp=mssql_query("SELECT * FROM  fill_CarID",$conn);
 $i=1;
 $si=0;
 //echo $res_row=mssql_num_rows($sql_fp);
 //echo $sql_fp;
 
  while($res_name=mssql_fetch_array($sql_fp))
  { 
  
  
   $car_id=$res_name["car_id"];
   $i_pname=iconv('WINDOWS-874','UTF-8',$res_name["C_CARNUM"]);
   
 
   
           /*gen TAX */
           $gen_id=mssql_query("select * from number_id WHERE typeID='TAX' ",$conn);
		   $res_id=mssql_fetch_array($gen_id);
		   $res_count=$res_id["run_number"];
		   if($res_count==0)
			{
			  $res_sn=0;
			}
			else
			{
			  $res_sn=$res_id["run_number"];
			}
		 
             $tax_i=$res_sn;
		  
		  
		  /*gen GAS */
		   $gen_gid=mssql_query("select * from number_id WHERE typeID='GAS' ",$conn);
		   $res_gid=mssql_fetch_array($gen_gid);
		   $res_gcount=$res_gid["run_number"];
		   if($res_gcount==0)
			{
			  $res_gn=0;
			}
			else
			{
			  $res_gn=$res_gid["run_number"];
			}
		   
		     $gas_i=$res_gn; 
     
		   
		   
		  
 
   
  //echo $name_fa1=$res_name["car_id"]."[".$res_name["car_number"]."]"."<br>";
   $sql_fc=mssql_query("select * from Fc WHERE C_CARNUM='$res_name[car_number]' ",$conn);
   while($res_fc=mssql_fetch_array($sql_fc))
   {
      $idno=trim($res_fc["IDNO"]);
	  
	  $str_idno=substr($idno,0,1);
		 if($str_idno=="6")
		 {
		   $id_asset="2";
		   $gas_i=$res_gn+1;
		   echo  "**".$idno."GAS".insertZero($gas_i,5)."<br>";
		   
		   $gas_asset="GAS".insertZero($gas_i,5);
		   
		   $sql_gas="insert into fill_assetID (t_idno,t_asset_id,t_car_id)values('$idno','$gas_asset','$car_id')";
		   if($res_gas=mssql_query($sql_gas,$conn))
		   {
			$sbd_gas="";
		   }
		   else
		   {
			$sbd_gas="OK at".$sql_gas;
		   }
		   
		   echo $sbd_gas;
		   
		 }
		 else
		 {
		   $id_asset="1";
		   $tax_i=$res_sn+1;
		   echo  "##".$idno."TAX".insertZero($tax_i,5)."<br>";
		   $tax_asset="TAX".insertZero($tax_i,5);
		   $sql_tax="insert into fill_assetID (t_idno,t_asset_id,t_car_id)values('$idno','$tax_asset','$car_id')";
		   if($res_tax=mssql_query($sql_tax,$conn))
		   {
			$sbd_tax="";
		   }
		   else
		   {
			$sbd_tax="OK at".$sql_tax;
		   }
		   
		   echo $sbd_tax;
		   
		 }
	 
	       
	 
	 
	      
		    /*update TAX */ 
	        $update_number="update number_id SET run_number=$tax_i WHERE typeID='TAX'";
	 		if($result=mssql_query($update_number,$conn))
			 {
			   $sbt="";
			 }
			 else
		 	 {
		      $sbt="not OK".$update_number;
		 	 }
			 
			 echo $sbt;
			 
			  /*update GAS */ 
	        $update_gas="update number_id SET run_number=$gas_i WHERE typeID='GAS'";
	 		if($result_gas=mssql_query($update_gas,$conn))
			 {
			   $sb="";
			 }
			 else
		 	 {
		       $sb="not OK".$update_gas;
		 	 }
	 
	          echo $sb;
	 
	 
   }
   
	 
 } 
 ?>
</body>
</html>
