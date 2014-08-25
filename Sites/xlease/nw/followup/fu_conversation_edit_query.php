 <?php


 include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$conID=pg_escape_string($_POST['hd_conid']);
$name=pg_escape_string($_POST["tb_conname"]);
$detail=pg_escape_string($_POST["tb_condetail"]);
$comID=pg_escape_string($_POST["company"]);
$empID=pg_escape_string($_POST["empname"]);
$date1=date("Y-m-d H:m:s");

$empdep = $_POST['CH'];
$empdepall = pg_escape_string($_POST['dep']);


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
$status1 = 0;
$status = 0;

if($conID != ""){
	pg_query("BEGIN");
	

	$sql2 = "update public.\"fu_conversation\" SET \"empconID\"='$empID',\"con_detail\" ='$detail',\"con_name\" ='$name' where \"conID\" = '$conID' ";
	$results2=pg_query($sql2);
	
	
	if($results2){}
	else{
		$status++;
	}
	if($status == 0){	
		
						for($o=0;$o<sizeof($empdep);$o++){
								$sql4 = "insert into public.\"fu_conversation_emp\"(\"conID\",\"id_user\") values('$conID','$empdep[$o]') ";
								$results4=pg_query($sql4);			
							
									if($results4){}
									else{
									$status1++;
									}
						}
										if($status1 == 0){
				
		

															if($empdepall == 'allemp' AND empty($empdep)){

	
																	$sql5 = "delete from \"fu_conversation_emp\" where \"conID\" = '$conID' ";
																	$results5=pg_query($sql5);
																		
																	
																		$sql6 = "insert into public.\"fu_conversation_emp\"(\"conID\",\"id_user\") values('$conID','allemp') ";
																		$results6=pg_query($sql6);
																		

															}else if($empdepall != 'allemp' AND $empdepall != 'none' AND !empty($empdep)){

																	$sql5 = "delete from \"fu_conversation_emp\" where \"conID\" = '$conID' ";
																	$results5=pg_query($sql5);
																		
																		for($h=0;$h<sizeof($empdep);$h++){
																		
																		$sql6 = "insert into public.\"fu_conversation_emp\"(\"conID\",\"id_user\") values('$conID','$empdep[$h]') ";
																		$results6=pg_query($sql6);
																		
																		}

															}else if($empdepall != 'allemp' AND $empdepall != 'none' AND empty($empdep)){
																
																pg_query("ROLLBACK");
																echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_conversation_edit.php?CONTID=$conID\">";
																echo "<script type='text/javascript'>alert('คุณลืมเลือกคนที่จะเห็นการสนทนา ??')</script>";
																echo "Error Save $sql2";
																exit();
															
															}
												pg_query("COMMIT");
												echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_conversation_edit.php?CONTID=$conID\">";
												echo "<script type='text/javascript'>alert('Edit successful')</script>";
												exit();						
						
										}else{
												pg_query("ROLLBACK");
												echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_conversation_edit.php?CONTID=$conID\">";
												echo "<script type='text/javascript'>alert('Error')</script>";
												echo "Error Save $sql2";
												exit();
										}
	}else{
					pg_query("ROLLBACK");
					echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_conversation_edit.php?CONTID=$conID\">";
					echo "<script type='text/javascript'>alert('Error')</script>";
					echo "Error Save $sql2";
					exit();
	}										
}else{
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_conversation_edit.php?CONTID=$conID\">";
			echo "<script type='text/javascript'>alert('No Data')</script>";
			exit();

}	
 ?>
			

