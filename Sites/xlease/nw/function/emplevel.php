<?php
function emplevel($data){
	$qrylevel=pg_query("select \"ta_get_user_emplevel\"('$data')");
	list($level)=pg_fetch_array($qrylevel);
	return $level;
}
?>