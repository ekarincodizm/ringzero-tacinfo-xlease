<?php
include("../config/config.php");
 $v_id=pg_escape_string($_GET["fdep_id"]);
 $v_name=pg_escape_string($_GET["fdep_name"]);
 
 //$v_id=pg_escape_string($_GET["id"]);
 //find last id
 
 $qry_uname=pg_query("select * from department where dep_id='$v_id' ");
 $nur_name=pg_num_rows($qry_uname);
 if($nur_name > 0)
 {
  echo "ชื่อ dep_id ซ้ำ";
 }
 else
 {
$in_sql="insert into department(dep_id,dep_name)values('$v_id','$v_name')";
		  
		  
  
 if($result=pg_query($in_sql))
 {
  $status ="insert ข้อมูลแล้ว";
 }
 else
 {
  $status ="error insert  department ".$in_sql;
 }

echo "<br>".$status;

}

?>