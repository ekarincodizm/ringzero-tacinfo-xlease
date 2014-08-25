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
   $sql_fc=mssql_query("select * from fill_CarID",$conn);
   while($res_fc=mssql_fetch_array($sql_fc))
   {
       //echo "###".$res_fc["car_id"]." ".$res_carid["t_asset_id"]." ".$res_fc["car_number"]."<br>";
	   $qrt_id=mssql_query("select * from fill_assetID WHERE t_car_id='$res_fc[car_id]'",$conn);
	   while($res_id=mssql_fetch_array($qrt_id))
	   {
	   
	     $fc_carid=trim($res_fc["car_id"]);
		 
	     $sql_carid=mssql_query("select *,convert(varchar,ISNULL(C_TAX_DATE,''),111) AS C_TAX_DATE,
	                         ISNULL(C_TAX_MON,'0') AS C_TAX_MON,
							 ISNULL(C_REGIS_BY,'') AS C_REGIS_BY
	                         from Fc WHERE C_CARNUM='$res_fc[car_number]'",$conn);
	     $res_carid=mssql_fetch_array($sql_carid);
	     $i_carname=iconv('WINDOWS-874','UTF-8',$res_carid["C_CARNAME"]);
	     $i_regis=iconv('WINDOWS-874','UTF-8',$res_carid["C_REGIS"]);
	   	 $i_regis_by=iconv('WINDOWS-874','UTF-8',$res_carid["C_REGIS_BY"]);
	     $i_color=iconv('WINDOWS-874','UTF-8',$res_carid["C_COLOR"]);
	   
	     $str_as=substr(trim($res_id["t_asset_id"]),0,1);
	     if($str_as=="T")
	     {
	        
			$qry_fc=pg_query("select \"CarID\" FROM \"Fc\" WHERE \"CarID\"='$res_id[t_asset_id]' ");
			$numr_fc=pg_num_rows($qry_fc);
			if($numr_fc==0)
			{
		     $ins_car="insert into \"Fc\"(\"CarID\",\"C_CARNAME\",\"C_YEAR\",\"C_REGIS\",\"C_REGIS_BY\",
	                               \"C_COLOR\",\"C_CARNUM\",\"C_MARNUM\",\"C_Milage\",
								   \"C_StartDate\",\"C_TAX_MON\")
								   values
								   ('$res_id[t_asset_id]','$i_carname','$res_carid[C_YEAR]',
								    '$i_regis','$i_regis_by','$i_color',
									'$res_carid[C_CARNUM]','$res_carid[C_MARNUM]','$res_carid[C_Milage]',
									'$res_carid[C_TAX_DATE]','$res_carid[C_TAX_MON]')";
		
			if($res_inscar=pg_query($ins_car))
			{
		      $res_ccar="";
			}
			else
			{
		      $res_ccar="error at:".$ins_car;
			}
								   
			  echo  $res_ccar."<br>";
			
			}
	      else
		  {
	        //ข้อมูล CusID ซ้ำ 
		  }
		  
		
		}
		else
		{
		   $qry_gas=pg_query("select \"GasID\" FROM \"FGas\" WHERE \"GasID\"='$res_id[t_asset_id]' ");
			$numr_fgas=pg_num_rows($qry_gas);
			if($numr_fgas==0)
			{
		      $ins_gas="insert into \"FGas\" 
		            (\"GasID\",gas_number,gas_name,gas_type,car_regis,car_regis_by,car_year,carnum,marnum)
		            values
					('$res_id[t_asset_id]','$i_color','$i_carname','GAS','$i_regis',
					 '$i_regis_by','$res_carid[C_YEAR]','$res_carid[C_CARNUM]','$res_carid[C_MARNUM]')";
		
			  if($res_insgas=pg_query($ins_gas))
				{
				  $res_gcar="";
				}
				else
				{
				  $res_gcar="error at:".$ins_gas;
				}
		          echo  $res_gcar."<br>";
		    } 
		else
		{
		 // gas ซ้ำ 
		}	 						   
         
	
	    
	 
	 }
	 
	}  
   }
  
  ?>
</table>

</body>
</html>
