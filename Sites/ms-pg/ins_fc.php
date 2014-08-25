<?php
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

<table width="100%" border="1">
  <tr>
    <td width="53">CarID</td>
    <td width="84">C_CARNAME</td>
    <td width="84">C_YEAR</td>
    <td width="82">C_REGIS</td>
    <td width="76">C_REGIS_BY</td>
    <td width="50">C_COLOR</td>
    <td width="69">C_CARNUM</td>
    <td width="69">C_MARNUM</td>
    <td width="101">C_Milage</td>
    <td width="70">C_TAX_ExpDate</td>
    <td width="77">C_TAX_MON</td>
    <td width="96">C_StartDate</td>
    <td width="91">RadioID</td>
  </tr>
  <?php

	 $sql_in=mssql_query("SELECT DISTINCT t_asset_id,t_car_id
FROM         fill_assetID 
ORDER BY t_asset_id",$conn);
	 while($res_fp=mssql_fetch_array($sql_in))
	  {
	    $car_id=$res_fp["t_asset_id"];
		$car_resid=$res_fp["t_car_id"];
		
		 $sql_carid=mssql_query("SELECT * from fill_CarID WHERE car_id='$car_resid'",$conn);
		 $res_carid=mssql_fetch_array($sql_carid);		 
		 
		 $car_carnum=$res_carid["car_number"];
	
		
	    $sql_tax=mssql_query("SELECT DISTINCT C_CARNUM,C_CARNAME,C_YEAR,C_REGIS,C_COLOR,
		                      C_MARNUM,C_Milage,C_TAX_DATE,C_REGIS_BY
							  from Fc WHERE C_CARNUM='$car_carnum' ",$conn);		
        $res_tax=mssql_fetch_array($sql_tax);
		
		/*
		$sql_asset=mssql_query("SELECT * from fill_assetID WHERE t_car_id='$car_id'",$conn);
		$res_asset=mssql_fetch_array($sql_asset);
		*/
		
	   $i_regis=iconv('WINDOWS-874','UTF-8',$res_tax["C_REGIS"]);
	   $i_regis_by=iconv('WINDOWS-874','UTF-8',$res_tax["C_REGIS_BY"]);
	   $i_color=iconv('WINDOWS-874','UTF-8',$res_tax["C_COLOR"]);
	   $i_name=iconv('WINDOWS-874','UTF-8',$res_tax["C_CARNAME"]);
		
  ?>
  
  <tr>
    <td><?php echo $car_id; ?></td>
    <td><?php echo $res_tax["C_CARNAME"]; ?></td>
    <td><?php echo $res_tax["C_YEAR"]; ?></td>
    <td><?php echo $res_tax["C_REGIS"]; ?></td>
	<td><?php echo $res_tax["C_REGIS_BY"]; ?></td>
    <td><?php echo $res_tax["C_COLOR"]; ?></td>
    <td><?php echo $res_tax["C_CARNUM"]; ?></td>
    <td><?php echo $res_tax["C_MARNUM"]; ?></td>
    <td><?php echo $res_tax["C_Milage"]; ?></td>
    <td><?php echo $res_tax["C_TAX_DATE"]; ?></td>
    <td><?php echo $res_tax["C_TAX_MON"]; ?></td>
    <td><?php echo $res_cid; ?></td>
    <td><?php echo $asset_id; ?></td>
  </tr>
    <?php
  
	 $str_type=substr($car_id,0,1);
	 if($str_type=="T")
	 {
	 
	   
	   
	   $ins_fc="insert into \"Fc\"(\"CarID\",\"C_CARNAME\",\"C_YEAR\",\"C_REGIS\",\"C_REGIS_BY\",
                     \"C_COLOR\",\"C_CARNUM\",\"C_MARNUM\",\"C_Milage\"
					 ) 
                     values  
                    ('$car_id','$i_name','$res_tax[C_YEAR]',
					'$i_regis','$i_regis_by','$i_color',
					'$res_tax[C_CARNUM]','$res_tax[C_MARNUM]','$res_tax[C_Milage]')";
			
			  if($result_fc=pg_query($ins_fc))
			  {
				$st= "ok";
			  }
			  else
			  {
				$st= "error at ".$ins_fc;
			  }
			    echo $st;

	   
	   
	   
	 }
	 else
	 {
	    $ins_fgas="insert into \"FGas\"(\"GasID\",gas_name,gas_number,gas_type,car_regis,car_regis_by,car_year,carnum,marnum)
		           values
				   ('$car_id','$i_name','GASNUMBER','GASTYPE','$i_regis','$i_regis_by',
				    '$res_tax[C_YEAR]','$res_tax[C_CARNUM]','$res_tax[C_MARNUM]') ";
	   
	   
			  if($result_fgas=pg_query($ins_fgas))
			  {
				$st= "ok";
			  }
			  else
			  {
				$st= "error at ".$ins_fgas;
			  }
			    echo $st;
	   
	 }
	 
	
	 

  

  }
  
  ?>
</table>

</body>
</html>
