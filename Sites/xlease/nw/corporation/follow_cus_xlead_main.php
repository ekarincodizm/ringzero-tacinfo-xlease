<?php 
include("../../config/config.php");
$corpID = pg_escape_string($_GET["corpid"]);

if($corpID!=""){ 

	//หาเลขที่สัญญาจาก CorpID
	$qry_idno = pg_query("select \"IDNO\" from \"VContact\" where \"CusID\"='$corpID'");
	$num_row = pg_num_rows($qry_idno);
	
		$Num=0;
		$num_show=0;
		while($res_idno = pg_fetch_array($qry_idno)){
			$get_idno = $res_idno["IDNO"];
	
			$qry_relation = pg_query("select \"CusState\" from \"ContactCus\" where \"IDNO\"='$get_idno' and \"CusID\"='$corpID' ");
			$relation = pg_fetch_result($qry_relation,0);
			
			if($relation==0){
			$Num=0;
				if($checkuser != ""){
						//if($_POST['group'] != "" && $_POST['userid'] != ""){
							$qry_fuc=pg_query("select * from \"FollowUpCus\" WHERE (\"userid\"='$checkuser') AND (\"IDNO\"='$get_idno') AND (\"CusID\"='$corpID'') ORDER BY auto_id DESC");
						
						}else{
							$qry_fuc=pg_query("select * from \"FollowUpCus\" WHERE (\"IDNO\"='$get_idno') AND (\"CusID\"='$corpID') ORDER BY auto_id DESC"); // Not WHERE !!!
						}
						$numr=pg_num_rows($qry_fuc);
				if($numr!=0){
					$num_show++;
?>				
				<fieldset style="background-color:#FFFFFF;"><legend><b><?php echo $get_idno; ?></b></legend>
					<div style="background-color: #ffffff; padding: 2px">
					<?php
						
						if($numr==0){ echo "<div align=center>- ไม่พบข้อมูล -</div>"; }
							while($res_fuc=pg_fetch_array($qry_fuc)){
								$contractID = $res_fuc["contractID"];
								$qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_fuc[userid]')");
								$res_fun=pg_fetch_array($qry_fun);
					?>		
					<div style="background-color: #C0C0C0">
						<div style="float:left; padding:2px">User : <b><?php echo $res_fun["fullname"]; ?></b></div>
						<div style="float:right; padding:2px">วันที่เจรจา : <b><?php echo $res_fuc["FollowDate"]; ?></b></div>
						<div style="clear:both;"></div>
					</div>
					<div style="background-color: #F0F0F0; padding:2px"><?php echo $res_fuc["FollowDetail"]; ?></div>
					<div style="background-color: #FFFFFF; clear:both; height:10px"></div>
					<?php
							}
					?>
					</div>
				</fieldset>
				
<?php			}
			}
		}
	 $showOrNot = $Num-$num_show;
	if($showOrNot==$Num){
		echo "<div align=center>- ไม่พบข้อมูลเลขที่สัญญา -</div>";
	}
}
 ?>