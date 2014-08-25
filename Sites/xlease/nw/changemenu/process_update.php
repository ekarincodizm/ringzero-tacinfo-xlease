<?php
include("../../config/config.php");

$id_user=$_GET["id_user"];
$method=$_GET["method"];
$add_date2=$_GET["add_date"];
$id_popup=$_GET["idpopup"];
pg_query("BEGIN WORK");
$status = 0;

if($method == 2){
	$upd="update \"nw_changemenu\" set \"statusOKapprove\"='TRUE' where \"id_user\"='$id_user' and \"statusApprove\"='2' and \"add_date\"='$add_date2'";
	if($resup=pg_query($upd)){
	}else{
		$status++;
	}
}else if($method == 3){
	$upd="update \"nw_changemenu\" set \"statusOKapprove\"='TRUE' where \"id_user\"='$id_user' and \"statusApprove\"='3' and \"add_date\"='$add_date2'";
	if($resup=pg_query($upd)){
	}else{
		$status++;
	}
}
if($status == 0){
	pg_query("COMMIT");
}else{
	pg_query("ROLLBACK");
}

	
?>
<script>
	window.opener.location.reload();
	window.close();
</script>