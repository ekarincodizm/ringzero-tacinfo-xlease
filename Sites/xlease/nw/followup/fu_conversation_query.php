<?php


include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$name=pg_escape_string($_POST["tb_conname"]);
$detail=pg_escape_string($_POST["tb_condetail"]);
$comID=pg_escape_string($_POST["hdcomid"]);
$empID=pg_escape_string($_POST["empname1"]);
$date1=date("Y-m-d H:i:s");

$empdepchoise = $_POST['CH'];
$empdepall = pg_escape_string($_POST['dep']);


if(empty($empdepchoise) AND $empdepall != 'allemp'){
					echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_conversation_add.php?comid=$comID\">";
					echo "<script type='text/javascript'>alert('เลือกคนที่ต้องการให้เห็นด้วยครับ')</script>";
					exit();	
}else if(!empty($empdepchoise)){

		echo $empdep = $_POST['CH'];
		
}else if($empdepall == 'allemp'){
	
		echo $empdep[0] = $_POST['dep'];

}

$tag_name=$_POST["tb_tagname"];
$tag_detail=$_POST["tb_tagdetail"];

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

$check=$_POST['chkanalyze'];
$status2 = 0;
$status1 = 0;
$status = 0;
if($avg == ""){
	$avg = 0;
	}


	pg_query("BEGIN");
	

	$sql2 = "Insert into public.\"fu_conversation\"(\"comID\",\"empconID\",\"con_detail\",\"con_date\",\"id_user\",\"con_name\") 
	values('$comID','$empID','$detail','$date1','$id_user','$name') ";
	$results2=pg_query($sql2);
	
	
	if($results2){}
	else{
		$status++;
	}
	if($status == 0){
	
		if($check == '1'){
	
			$qry_name=pg_query("select MAX(\"conID\") as conid from public.\"fu_conversation\" ");
	
			$result4=pg_fetch_array($qry_name); 
			$conID= $result4["conid"];
	
			$sql3 = "insert into public.\"fu_tag\"(\"conID\",\"tag_name\",\"tag_detail\",\"datetime_alert\",\"status_alert\",\"tag_datetime\",\"tag_status\") 
			values('$conID','$tag_name','$tag_detail','$datetime_alert','0','$tag_datetime','0') ";
			$results3=pg_query($sql3);
	
			if($results3){}
			else{
			$status1++;
			}
				if($status1 == 0){
					
						for($o=0;$o<sizeof($empdep);$o++){
							$sql4 = "insert into public.\"fu_conversation_emp\"(\"conID\",\"id_user\") values('$conID','$empdep[$o]') ";
							$results4=pg_query($sql4);			
						
								if($results4){}
								else{
								$status2++;
								}
						}
										if($status2 == 0){
													pg_query("COMMIT");
													
													$qry_name7=pg_query("select MAX(\"conID\") as conid from public.\"fu_conversation\" ");
													$result7=pg_fetch_array($qry_name7);
													$t=$result7['conid'];
													
													echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_conversation_data.php?CONTID=$t\">";
													echo "<script type='text/javascript'>alert('Save successful')</script>";
													exit();	
										}else{
			
													pg_query("ROLLBACK");
													echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
													echo "<script type='text/javascript'>alert('Error')</script>";
													echo "Error Save $sql2";
													exit();
										}
				}else{
			
							pg_query("ROLLBACK");
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
							echo "<script type='text/javascript'>alert('Error')</script>";
							echo "Error Save $sql2";
							exit();
				}
	
				
				
				
		}else{
		
			$qry_name=pg_query("select MAX(\"conID\") as conid from public.\"fu_conversation\" ");	
			$result4=pg_fetch_array($qry_name); 
			$conID= $result4["conid"];
				
				for($o=0;$o<sizeof($empdep);$o++){
							
					$sql4 = "insert into public.\"fu_conversation_emp\"(\"conID\",\"id_user\") values('$conID','$empdep[$o]') ";
					$results4=pg_query($sql4);			
						
						if($results4){}
						else{$status2++;}
				}
							if($status2 == 0){
							
									pg_query("COMMIT");
									
									
			
									echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_conversation_data.php?CONTID=$conID\">";
									echo "<script type='text/javascript'>alert('Save successful')</script>";
									exit();	
							}else{
										pg_query("ROLLBACK");
										echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
										echo "<script type='text/javascript'>alert('Error')</script>";
										echo "Error Save $sql2";
										exit();
							}	
		}
	
	}else{
			pg_query("ROLLBACK");
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
			echo "<script type='text/javascript'>alert('Error')</script>";
			echo "Error Save $sql2";
			exit();
	}	
?>
			

