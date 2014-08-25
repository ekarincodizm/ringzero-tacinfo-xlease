<?php


include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$COMID = pg_escape_string($_POST['hd_comid']);


$name=pg_escape_string($_POST['tb_comname']);
$address=pg_escape_string($_POST['tb_comadd']);
$com_phone=pg_escape_string($_POST['tb_comphone']);
$fax=pg_escape_string($_POST['tb_fax']);
$email=pg_escape_string($_POST['tb_commail']);
$business=pg_escape_string($_POST['tb_combu']);
$type=pg_escape_string($_POST['lmName1']);
$avg=pg_escape_string($_POST['tb_comavg']);
$date=date("Y-m-d H:m:s");

$status = 0;
if($avg == ""){
	$avg = 0;
	}

if($COMID != ""){



pg_query("BEGIN");
$sql = "update  public.\"fu_company\" SET \"com_name\" = '$name',\"com_address\" = '$address',\"com_phone\" = '$com_phone',\"com_fax\" = '$fax',\"com_email\" = '$email',
\"com_type\" = '$type',\"com_business\" = '$business',\"com_avg_income\" = '$avg',\"com_date\" = '$date',\"id_user\" = '$id_user'
where \"comID\" like '%$COMID%' ";
$results=pg_query($sql);						 

if($results)
{}
else{
	$status++;
}

if($status == 0)
{
	pg_query("COMMIT");
	
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_company_edit.php?COMID=$COMID\">";
	echo "<script type='text/javascript'>alert('Edit successful')</script>";
	exit();
	
}
else
{
	
	pg_query("ROLLBACK");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_company_edit.php?COMID=$COMID\">";
	echo "<script type='text/javascript'>alert('Error')</script>";
	echo "Error Save [".$strSQL."]";
}


}else if($COMID == ""){


	pg_query("BEGIN");
	
	
	$sql = pg_query("select * from  public.\"fu_company\" order by \"runnumber\" desc limit 1");
	while($results = pg_fetch_array($sql))
	{
	//$resultstest = pg_fetch_array($sql);
		$COMID = $results["comID"];
	}

	
	$nrows=pg_num_rows($sql);
			if($nrows==0){
				$COMID = 'com0001';
					
			}else{
				$COMID = substr($COMID,3);
				$COMID++;
				if(strlen($COMID)<4){
					
					do{
					
						$COMID = "0".$COMID;				
				
					}while(strlen($COMID)<4);
				
						$COMID = "com".$COMID;
				
				}else{
				
					$COMID = "com".$COMID;
				
				}
			}
				
	$sql2 = "Insert into public.\"fu_company\"(\"comID\",\"com_name\",\"com_address\",\"com_phone\",\"com_fax\",
	\"com_email\",\"com_type\",\"com_business\",\"com_avg_income\",\"com_date\",\"id_user\") 
	values('$COMID','$name','$address','$com_phone','$fax','$email','$type','$business','$avg','$date','$id_user') ";
	$results2=pg_query($sql2);
	
	if($results2)
	{}
	else{
		$status++;
	}
	if($status == 0){
	pg_query("COMMIT");
	
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_company_edit.php?COMID=i\">";
	echo "<script type='text/javascript'>alert('Save Successful')</script>";
	exit();
	
	}else{
	
	pg_query("ROLLBACK");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_company_edit.php?COMID=i\">";
	echo "<script type='text/javascript'>alert('ไม่สามารถบันทึกรายการได้ เนื่องจากเกิดความผิดพลาดของโปรแกรม โปรลองใหม่ในภายหลัง')</script>";
	echo "Error Save $sql2";
	exit();
	}
	}
	
?>
			

