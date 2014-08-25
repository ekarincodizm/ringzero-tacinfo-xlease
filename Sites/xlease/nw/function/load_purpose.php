<?php
  
  function load_all_purpose_from_table_thcap_purpose()
  {  // ดึงรายการจุดประสงค์ของใบสำคัญจ่าย  จากตาราง  "account.thcap_purpose"
     $Str_Qry = "SELECT thcap_purpose_id ,thcap_purpose_name FROM account.thcap_purpose Order by thcap_purpose_name ASC";
     $query_list = pg_query($Str_Qry);
	 $num_row = pg_num_rows($query_list);
	 return($query_list);
   }  
  

?>