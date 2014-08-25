<?php


include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$conID=pg_escape_string($_POST['hdconid']);
$tag_name=pg_escape_string($_POST['tb_tagname']);
$tag_detail=pg_escape_string($_POST['tb_tagdetail']);
$status_alert=0;
$tag_status=0;

$alday=$_POST['alDate'];
$alh=$_POST['alerthours'];
$alm=$_POST['alertmin'];
$als=00;

$tagday=$_POST['tagDate'];
$tagh=$_POST['taghours'];
$tagm=$_POST['tagmin'];
$tags=00;

$tag_datetime=$tagday." ".$tagh.":".$tagm.":".$tags;
$datetime_alert=$alday." ".$alh.":".$alm.":".$als;

$date=date("Y-m-d H:i:s");

$status = 0;
$status1 = 0;

	pg_query("BEGIN");
	
	
	$sql2 = "Insert into public.\"fu_tag\"(\"conID\",\"tag_name\",\"tag_detail\",\"datetime_alert\",\"status_alert\",\"tag_datetime\",\"tag_status\") 
	values('$conID','$tag_name','$tag_detail','$datetime_alert','$status_alert','$tag_datetime','$tag_status') ";
	$results2=pg_query($sql2);
	
	if($results2)
	{}
	else{
		$status++;
	}
	if($status == 0){
	
	
	
	pg_query("COMMIT");
	$qry_name7=pg_query("select MAX(\"tagID\") as tagid from public.\"fu_tag\" where \"conID\" = '$conID' ");
	$result7=pg_fetch_array($qry_name7);
	$t=$result7['tagid'];
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_tag_data.php?TAGID=$t\">";
	echo "<script type='text/javascript'>alert('Save done')</script>";
	exit();
	
	}else{
	
	pg_query("ROLLBACK");
	//echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
	echo "<script type='text/javascript'>alert('Error')</script>";
	echo "Error Save $sql2";
	echo "window.close()";
	exit();
	}

	
?>
			

