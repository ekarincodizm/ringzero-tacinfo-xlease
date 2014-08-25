<?php
include("../config/config.php");

 $ds_id=pg_escape_string($_GET["did"]);
 $ds_name=pg_escape_string($_GET["fd_name"]);
 $os_id=pg_escape_string($_GET["f_new_id"]);

$sql="update department set  dep_name='$ds_name',dep_id='$os_id' where dep_id='$ds_id' ";

 if($result=pg_query($sql))
 {
  $status ="Update department แล้ว จะนำท่านไปยัง manage_menu.php";
 }
 else
 {
  $status ="error Update  department at ".$sql;
 }

echo "<br>".$status;

?>