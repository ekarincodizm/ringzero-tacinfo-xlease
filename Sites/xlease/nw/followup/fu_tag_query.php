<?php


include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$tagID=pg_escape_string($_POST['hd_tagid']);
$conID=pg_escape_string($_POST['hdconid']);
$tag_name=pg_escape_string($_POST['tb_tagname']);
$tag_detail=pg_escape_string($_POST['tb_tagdetail']);
$status_alert=0;
$tag_status=pg_escape_string($_POST['status']);

$alday=pg_escape_string($_POST['alDate']);
$alh=pg_escape_string($_POST['alerthours']);
$alm=pg_escape_string($_POST['alertmin']);
$als=00;

$tagday=pg_escape_string($_POST['tagDate']);
$tagh=pg_escape_string($_POST['taghours']);
$tagm=pg_escape_string($_POST['tagmin']);
$tags=00;

$tag_datetime=$tagday." ".$tagh.":".$tagm.":".$tags;
$datetime_alert=$alday." ".$alh.":".$alm.":".$als;

$date=date("Y-m-d H:i:s");

$status = 0;
$status1 = 0;

	pg_query("BEGIN");
	
	
	$sql2 = "Insert into public.\"fu_tag\"(\"conID\",\"tag_name\",\"tag_detail\",\"datetime_alert\",
	\"status_alert\",\"tag_datetime\",\"tag_status\") 
	values('$conID','$tag_name','$tag_detail','$datetime_alert','$status_alert','$tag_datetime','0') ";
	$results2=pg_query($sql2);
	
	if($results2)
	{}
	else{
		$status++;
	}
	if($status == 0){
	
	$sql = "update  public.\"fu_tag\" SET \"status_alert\" = '1',\"tag_status\" = '1'
where \"tagID\" = '$tagID' ";
$results3=pg_query($sql);
	
		if($results3)
		{}
		else{
		$status1++;
		}
		if($status1 == 0){
	
	
	
		$sql4 = "select \"tagID\" from \"fu_tag\" order by \"tagID\" DESC limit 1";
		$sqll=pg_query($sql4);
		$results4 = pg_fetch_array($sqll);
		$tagIDedit = $results4['tagID'];
		
	pg_query("COMMIT");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_tag_edit.php?TAGID=$tagIDedit\">";
	echo "<script type='text/javascript'>alert('Edit Successful');
			opener.location.reload(true);
			self.close();
		</script>";
	exit();
	}
	
	}else{
	
	pg_query("ROLLBACK");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_tag_edit.php?TAGID=$tagID\">";
	echo "<script type='text/javascript'>alert('Error')</script>";
	echo "Error Save $sql2";
	exit();
	}

	
?>
			

