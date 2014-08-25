<!-- load Distinct_Date From Table thcap_temp_money_hold_secure -->
<?php
  function load_dataDate_from_thcap_temp_money_hold_secure()
  {
     $Str_Qry = "SELECT DISTINCT \"dataDate\" FROM thcap_temp_money_hold_secure ORDER By \"dataDate\" DESC ";
     $query_list = pg_query($Str_Qry);
	 $num_row = pg_num_rows($query_list);
	 return($query_list);
  
  }

?>