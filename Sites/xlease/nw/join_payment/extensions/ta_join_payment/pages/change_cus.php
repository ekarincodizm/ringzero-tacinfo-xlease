<?php

require_once("../../sys_setup.php");
include("../../../../../config/config.php");

	$cus_id = $_REQUEST[cus_id];
 	$query =	"SELECT \"N_CARD\",\"N_IDCARD\",\"N_CARDREF\" FROM  \"Fn\" WHERE \"CusID\" = '$cus_id' ";
	//echo $query;
	$sql_query = pg_query($query);
	if($sql_row4 = pg_fetch_array($sql_query))
				{
					
					

					$N_IDCARD=trim($sql_row4['N_IDCARD']);
					
					
					if($N_IDCARD!=""){
						$N_CARD = "บัตรประชาชน";
						
					}else{
						$N_CARD = trim($sql_row4['N_CARD']);
						$N_IDCARD = trim($sql_row4['N_CARDREF']);
						
					}

				}

  
  echo $N_CARD."#".$N_IDCARD;
  

  ?>