<?php 

include("../../config/config.php");
$temp=pg_escape_string($_POST['temname']);
$detail1=pg_escape_string($_POST['temdetail']);
$encode=pg_escape_string($_POST['temcode']);
$checksend=pg_escape_string($_POST['send']);
$header=pg_escape_string($_POST['header']);

$detail = str_replacein($detail1);	


if($checksend == 'senduser'){

	$id_user=pg_escape_string($_POST['hdiduser']);
				
				$qry_name=pg_query("select * from \"fuser\" WHERE \"id_user\" = '$id_user'");
				$result=pg_fetch_array($qry_name); 
				$sendname = $result["fname"]." ".$result["lname"];
				$sendemail = $result["email"];

}else if( $checksend == 'senduserlist'){
				
	$id_user=pg_escape_string($_POST['id_user']);
				
				$qry_name=pg_query("select * from \"fuser\" WHERE \"id_user\" = '$id_user'");
				$result=pg_fetch_array($qry_name); 
				$sendname = $result["fname"]." ".$result["lname"];
				$sendemail = $result["email"];
				
}else if($checksend == 'sendtype'){

				$sendname=pg_escape_string($_POST['sendname']);
				$sendemail=pg_escape_string($_POST['sendemail']);
				
}

$status = 0;
if(!empty($_FILES["fileup"]["name"])){
		
		for($i=0;$i<count($_FILES["fileup"]["name"]);$i++)
		{		
			if($_FILES["fileup"]["name"][$i] != "")
				{
					if(move_uploaded_file($_FILES["fileup"]["tmp_name"][$i],"fileupload/".$_FILES["fileup"]["name"][$i]))
						{
							 $file = $file."/".$_FILES["fileup"]["name"][$i];
						
						}
				}
		}
}else{
		 $file = "";
}			

if(empty($encode)){
		$encode = "";
}
						
		pg_query('BEGIN');			
		$strSQL = "INSERT INTO \"fu_template\"(\"tem_name\",\"tem_detail\",\"tem_file\",\"tem_sendname\",\"tem_send_email\",\"tem_encode\",\"tem_header\") 
				   VALUES ('$temp','$detail','$file','$sendname','$sendemail','$encode','$header')";
		$objQuery = pg_query($strSQL);	
				
			if($objQuery){
			}else{
					$status++;
			}			
										if($status ==0 ){					
												pg_query('COMMIT');	
												$strSQL = "select * from \"fu_template\" order by \"temID\" DESC";
												$objQuery = pg_query($strSQL);	
												$re1 = pg_fetch_array($objQuery);
												$temID = $re1['temID'];
												
													echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_email_template_edit.php?temID=$temID\">";
													echo "<script type='text/javascript'>alert('Save successful')</script>";
													exit();													
										}else{
													pg_query('ROLLBACK');
													echo "<script type='text/javascript'>alert('Can't save this template\nplease try again')</script>";
													exit();	
										}		



?>