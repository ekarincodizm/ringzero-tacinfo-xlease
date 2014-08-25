<?php
session_start();
include("../config/config.php");

$idno=pg_escape_string($_POST["h_arti_id"]);

$sql_fd=pg_query("select \"IDNO\" from account.\"CostOfCar\" WHERE \"IDNO\"='$idno'");
$res_id=pg_num_rows($sql_fd);

if($res_id==0)
{
 echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_enter_beginx.php?cc_id=$idno\" >";
}
else
{
 echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_edit_beginx.php?cc_id=$idno\" >";
}

?>