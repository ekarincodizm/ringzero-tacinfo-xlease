<?php
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$qryspecial=pg_query("SELECT auto_id, \"sendName\" FROM thcap_letter_head order by \"auto_id\" asc");
while($resspec=pg_fetch_array($qryspecial)){
	list($sendId,$sendName)=$resspec;
	echo "<option value=\"$sendId\">$sendName</option>";	
}
?>