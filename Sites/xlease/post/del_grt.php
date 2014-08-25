<?php
session_start();
include_once("../../config/config.php");
$st_sql="delete from \"Fc\" where id_auto_user=$u_id";
if($result=mssql_query($st_sql))
{
 $st="delete success";
}
else
{
 $st="Error At ".$result;
}
#echo $st;
mssql_close();
echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_listUser.php\">";
?>