<?php
session_start();
set_time_limit(0);
include("../../config/config.php");
 $a_gp=$_POST["a_gp"]; //กลุ่มผู้ใช้
 $tempID=$_POST["ad_idmenu"]; //id ของ Template
 $cid=$_POST["cid"]; //พนักงานที่เลือกให้ใช้เมนูนี้
 
 $user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
pg_query("BEGIN WORK");
$status = 0;

//หาว่ามีเมนูใน Template ที่เหลือหรือไม่
$query_menu=pg_query("select * from \"nw_templateDetail\" where \"tempID\"='$tempID'");
$numrows_menu=pg_num_rows($query_menu);

if($numrows_menu > 0){  //กรณีพบเมนูใน Template	
	if(sizeof($cid) == 0){ //กรณีไม่ได้เลือกให้ user ใช้งาน Template เลย
		if($a_gp==""){
			$qry_user=pg_query("select * from fuser order by user_group,status_user desc");
		}else{
			$qry_user=pg_query("select * from fuser where \"user_group\"='$a_gp' order by user_group,status_user desc");
		}
		while($res=pg_fetch_array($qry_user)){
			$id_user = $res["id_user"];	
			
			$query_menu1=pg_query("select * from \"nw_templateDetail\" where \"tempID\"='$tempID'");
			$nub_menu=0;
			while($resmenu=pg_fetch_array($query_menu1)){
				$ad_idmenu=$resmenu["id_menu"]; //เมนูที่อยู่ใน Template

				$list_menu=pg_query("select * from f_usermenu A 
									LEFT OUTER JOIN f_menu B on A.id_menu=B.id_menu
									where  A.id_user='$id_user' and A.id_menu='$ad_idmenu' order by B.name_menu");
				$nummenu = pg_num_rows($list_menu);
				if($nummenu >0){
					$nub_menu++;
				}
			}
			if($nub_menu == $numrows_menu){ //ถ้ามีค่าเท่ากันแสดงว่า user รายนี้ใช้ Template นี้ให้สามารถ  update ให้เป็นยกเลิกการใช้ Template ได้
				
				$query_menu2=pg_query("select * from \"nw_templateDetail\" where \"tempID\"='$tempID'");
				while($res1=pg_fetch_array($query_menu2)){
					$ad_idmenu1=$res1["id_menu"]; //เมนูที่อยู่ใน Template
				
					$upd="update f_usermenu set status='FALSE' where id_menu='$ad_idmenu1' and id_user='$id_user'";
					if($result=pg_query($upd)){
					}else{
						$up_error=$result;
						$status++;
					}
				}
			}
		}	
	}else{ //กรณีเลือกให้ใช้บาง user หรือเลือกทั้งหมด
		for($i=0;$i<sizeof($cid);$i++){ //id ของพนักงานที่ใช้เมนูนี้
			if($a_gp==""){
				$qry_user=pg_query("select * from fuser order by user_group,status_user desc");
			}else{
				$qry_user=pg_query("select * from fuser where \"user_group\"='$a_gp' order by user_group,status_user desc");
			}
			while($res=pg_fetch_array($qry_user)){
				$id_user = $res["id_user"];
				
				if($cid[$i] == $id_user){
					$query_menu2=pg_query("select * from \"nw_templateDetail\" where \"tempID\"='$tempID'");
					while($resmenu2=pg_fetch_array($query_menu2)){
						$ad_idmenu2=$resmenu2["id_menu"]; //เมนูที่อยู่ใน Template
						
						$list_menu=pg_query("select * from f_usermenu A 
											LEFT OUTER JOIN f_menu B on A.id_menu=B.id_menu
											where  A.id_user='$id_user' and A.id_menu='$ad_idmenu2' order by B.name_menu");
						$nummenu = pg_num_rows($list_menu);
						if($nummenu == 0){
							$ins="insert into f_usermenu(id_menu,id_user,status) values ('$ad_idmenu2','$id_user','TRUE')";
							if($result=pg_query($ins)){
							}else{
								$ins_error=$result;
								$status++;
							}	
						}else{ //ถ้่ามีแล้วให้อัพเดทข้อมูลที่มีให้เป็น TRUE
							$upd="update f_usermenu set status='TRUE' where id_menu='$ad_idmenu2' and id_user='$id_user'";
							if($result=pg_query($upd)){
							}else{
								$up_error=$result;
								$status++;
							}
						}
								
					}
				}else{
					$nub=0;
					for($j=0;$j<sizeof($cid);$j++){
						if($cid[$j] == $id_user){
							$nub++;
						}
					}
					if($nub==0){
						$query_menu1=pg_query("select * from \"nw_templateDetail\" where \"tempID\"='$tempID'");
						$nub_menu=0;
						while($resmenu=pg_fetch_array($query_menu1)){
							$ad_idmenu=$resmenu["id_menu"]; //เมนูที่อยู่ใน Template

							$list_menu=pg_query("select * from f_usermenu A 
												LEFT OUTER JOIN f_menu B on A.id_menu=B.id_menu
												where  A.id_user='$id_user' and A.id_menu='$ad_idmenu' order by B.name_menu");
							$nummenu = pg_num_rows($list_menu);
							if($nummenu >0){
								$nub_menu++;
							}
						}
						if($nub_menu==$numrows_menu){
							$query_menu2=pg_query("select * from \"nw_templateDetail\" where \"tempID\"='$tempID'");
							while($res1=pg_fetch_array($query_menu2)){
								$ad_idmenu1=$res1["id_menu"]; //เมนูที่อยู่ใน Template
									
								$upd="update f_usermenu set status='FALSE' where id_menu='$ad_idmenu1' and id_user='$id_user'";
								if($result=pg_query($upd)){
								}else{
									$up_error=$result;
									$status++;
								}
							}
						}
					}else{
						$query_menu2=pg_query("select * from \"nw_templateDetail\" where \"tempID\"='$tempID'");
						while($res1=pg_fetch_array($query_menu2)){
							$ad_idmenu1=$res1["id_menu"]; //เมนูที่อยู่ใน Template
									
							$upd="update f_usermenu set status='TRUE' where id_menu='$ad_idmenu1' and id_user='$id_user'";
							if($result=pg_query($upd)){
							}else{
								$up_error=$result;
								$status++;
							}
						}
					}
				}
			}
		}		 
	}
}
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) มอบสิทธิตาม Template', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_index.php?ad_idmenu=$tempID&a_gp=$a_gp'>";
}else{
	pg_query("ROLLBACK");
	echo $ins_error."<br>";
	echo $up_error;
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<meta http-equiv='refresh' content='4; URL=frm_index.php?ad_idmenu=$tempID&a_gp=$a_gp'>";
}	
?>