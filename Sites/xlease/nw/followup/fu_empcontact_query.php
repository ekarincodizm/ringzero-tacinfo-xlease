<?php


include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

?>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php

$EMPID = pg_escape_string($_POST['hd_empid']);


$name=pg_escape_string($_POST['tb_empname']);
$lname=pg_escape_string($_POST['tb_emplname']);
$phone=pg_escape_string($_POST['tb_empphone']);
$mobile=pg_escape_string($_POST['tb_mobile']);
$email=pg_escape_string($_POST['tb_empmail']);
$post=pg_escape_string($_POST['tb_emppost']);
$COMID=pg_escape_string($_POST['hdcomid']);
$date=date("Y-m-d H:i:s");

$status = 0;

if($EMPID != ""){



pg_query("BEGIN");

$sql = "update  public.\"fu_empcontact\" SET \"empcon_name\" = '$name',\"empcon_lname\" = '$lname',\"empcon_phone\" = '$phone',
\"empcon_position\" = '$post',\"empcon_email\" = '$email',\"empcon_moblie\" = '$mobile'
where \"empconID\" like '%$EMPID%' ";
$results=pg_query($sql);						 

if($results)
{}
else{
	$status++;
}

if($status == 0)
{
	pg_query("COMMIT");
	
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_empcontact_edit.php?empID=$EMPID\">";
	echo "<script type='text/javascript'>alert('Edit successful')</script>";
	exit();
	
}
else
{
	
	pg_query("ROLLBACK");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_empcontact_edit.php?empID=$EMPID\">";
	echo "<script type='text/javascript'>alert('Error')</script>";
	echo "Error Save [".$strSQL."]";
}


}else if($EMPID == ""){


	pg_query("BEGIN");
	
	
	$sql = pg_query("select * from  public.\"fu_empcontact\" order by \"runnumber\" desc limit 1");
	while($results = pg_fetch_array($sql))
	{
	//$resultstest = pg_fetch_array($sql);
		$EMPID = $results["empconID"];
	}

	
	$nrows=pg_num_rows($sql);
			if($nrows==0){
				$EMPID = 'emp0001';
					
			}else{
				$EMPID = substr($EMPID,3);
				$EMPID++;
				if(strlen($EMPID)<4){
					do{
					
					$EMPID = "0".$EMPID;				
				
				}while(strlen($EMPID)<4);
				
				$EMPID = "emp".$EMPID;
				
				}else{
				
					$EMPID = "emp".$EMPID;
				}
				
				}
	$sql2 = "Insert into public.\"fu_empcontact\"(\"empconID\",\"empcon_name\",\"empcon_lname\",\"empcon_position\",\"empcon_phone\",
	\"empcon_moblie\",\"empcon_email\",\"empcon_Date_submit\",\"comID\",\"id_user\") 
	values('$EMPID','$name','$lname','$post','$phone','$mobile','$email','$date','$COMID','$id_user') ";
	$results2=pg_query($sql2);
	
	if($results2)
	{}
	else{
		$status++;
	}
	if($status == 0){
	pg_query("COMMIT");
	$sql3 = pg_query("select * from  public.\"fu_empcontact\" order by \"runnumber\" desc limit 1");
	$results3 = pg_fetch_array($sql3);
	$t1 = $results3['empconID'];
	
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_empcontact_data.php?empID=$t1\">";
	echo "<script type='text/javascript'>alert('Save successful')</script>";
	
	exit();
	
	}else{
	
	pg_query("ROLLBACK");
	echo "<script type='text/javascript'>alert('can't insert to database')</script>";
	echo "Error Save $sql2";
	exit();
	}
	}
	
?>
			

