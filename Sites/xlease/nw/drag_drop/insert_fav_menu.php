<?php 
session_start();
$user_id = $_SESSION["av_iduser"];
include("../../config/config.php");
$array	= $_POST['idmenu'];
$chk = $_POST['check'];

if($array != ""){

	if($chk == 'add'){
			$count = 0;
			$query0 = pg_query("select id_menunumber from \"f_favorite_menu\" where \"id_user\" = '$user_id' order by id_menunumber");			
			$rowchk0 = pg_num_rows($query0);
			
			
			//จำนวน เมนู ที่ ทั้งหมดของผู้ใช้นั้น ๆ
			$admin_array = GetAdminMenu(); //menu ของ admin
				$o = 1;
				for($p=0;$p<sizeof($admin_array);$p++){
					
					if($o == sizeof($admin_array)){		
						$admenu = $admenu."'".$admin_array[$p]."'";
					}else{			
						$admenu = $admenu."'".$admin_array[$p]."'".",";		
					}$o++;
				}
			$query1 = pg_query("SELECT B.id_menu as idmenu FROM f_usermenu A 
			INNER JOIN f_menu B on A.id_menu=B.id_menu 
			WHERE (A.id_user='$user_id') AND (B.status_menu='1') AND (A.status=true) AND (B.id_menu NOT IN ($admenu)) ORDER BY A.id_menu ASC");
			$rowchk2 = pg_num_rows($query1);
			
			if($rowchk0 < $rowchk2){
			
				$query1 = pg_query("select id_menunumber from \"f_favorite_menu\" where \"id_user\" = '$user_id' and  \"id_menu\" = '$array' order by id_menunumber");
				$rowchk1 = pg_num_rows($query1);
				if($rowchk1 > 0){ echo "doubly";
				}else{
					while($result0 = pg_fetch_array($query0)){ $number[] = $result0['id_menunumber']; }
						for($i=1;$i<=$rowchk2;$i++){
							if($i == $number[$count]){}else{
								$query = pg_query("INSERT INTO \"f_favorite_menu\"(\"id_menu\", \"id_user\", \"id_menunumber\")VALUES ('$array', '$user_id', '$i')");
								break;
							}
						$count++;	
						}
				}	
			}else if($rowchk0 == $rowchk2){
				echo "false";
			}	
	}else if($chk == 'del'){		
			$query0 = pg_query("DELETE FROM f_favorite_menu WHERE \"id_user\" = '$user_id' and \"id_menu\" = '$array'");	
	}
 } 
?>