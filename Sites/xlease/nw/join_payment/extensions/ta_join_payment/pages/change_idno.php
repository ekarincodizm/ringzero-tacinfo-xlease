<?php

require_once("../../sys_setup.php");
include("../../../../../config/config.php");

	$idno = $_REQUEST[idno];
	$car_no = $_REQUEST[car_no];
 	$sql_query=pg_query("select \"P_FDATE\",\"P_TOTAL\",\"C_CARNAME\",\"C_CARNUM\" from \"VJoin\" v WHERE v.\"asset_id\" = '$car_no' and \"IDNO\" = '$idno'  ");

	if($sql_row5 = pg_fetch_array($sql_query))
				{
					$start_contract_date  = date_ch_form_c($sql_row5['P_FDATE']);
					$car_month = $sql_row5['P_TOTAL'];
			
			
				
					
					$car_brand = $sql_row5['C_CARNAME'];
					$id_body = $sql_row5['C_CARNUM'];
				}

  
  echo $start_contract_date."#".$car_month."#".$car_brand."#".$id_body;
  

  ?>