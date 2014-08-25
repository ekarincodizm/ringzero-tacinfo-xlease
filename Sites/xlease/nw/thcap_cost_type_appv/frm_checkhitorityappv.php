<?php
include("../../config/config.php");
$autoid=pg_escape_string($_GET["idno"]);
$sql = pg_query("select \"edit_last_autoid\"  from \"thcap_cost_type_temp\" where \"autoid\" ='$autoid'");
$result = pg_fetch_array($sql);
$autoid_edit=$result["edit_last_autoid"];
echo $autoid_edit;
?>