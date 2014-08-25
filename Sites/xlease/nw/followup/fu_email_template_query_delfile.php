<?php 

include("../../config/config.php");

$temID=pg_escape_string($_GET['temID']);
$null = "";
			
		pg_query('BEGIN');			
		$strSQL = "update \"fu_template\" SET \"tem_file\" = '$null' where \"temID\" = '$temID'";
		$objQuery = pg_query($strSQL);	
				
			if($objQuery){
			}else{
					$status++;
			}			
										if($status ==0 ){					
												pg_query('COMMIT');													
													echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_email_template_edit.php?temID=$temID\">";
													echo "<script type='text/javascript'>alert('Edit successful')</script>";
													exit();													
										}else{
													pg_query('ROLLBACK');
													echo "<script type='text/javascript'>alert('Can't edit this template please try again')</script>";
													exit();	
										}		



?>