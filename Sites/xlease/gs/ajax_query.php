<?php
@ini_set('display_errors', '1');
include("../config/config.php");

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0

$company = pg_escape_string($_POST['company']); 
$show = "<select name=\"g_type\" id=\"g_type\" onchange=\"JavaScript:doCallAjax2();\"><option value=\"\">เลือก</option>";

$qry_inf1=pg_query("select * from gas.\"Model\" WHERE \"coid\" = '$company' ORDER BY \"coid\" ASC");
while($res_inf1=pg_fetch_array($qry_inf1)){
    $modelid = $res_inf1["modelid"];
    $modelname = $res_inf1["modelname"];
    $show .= "<option value=\"$modelid\">$modelname</option>";
}
$show .= "</select>";
echo $show;
?>