<?php
session_start();
include("../../config/config.php");
 $a_gp=$_POST["a_gp"]; //กลุ่มผู้ใช้
 $ad_idmenu=$_POST["ad_idmenu"]; //idmenu
 $cid=$_POST["cid"]; //พนักงานที่เลือกให้ใช้เมนูนี้
 $user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime();
pg_query("BEGIN WORK");
$status = 0;

if(sizeof($cid) == 0){ //กรณีไม่มีการเลือกพนักงานแสดงว่าทุกคนไม่มีสิทธิ์ใช้งานเมนูนั้น
	//ค้นหาพนักงานที่อยู่ในกลุ่มทั้งหมด
	if($a_gp==""){
		$qry_user=pg_query("select * from fuser order by user_group,status_user desc");
	}else{
		$qry_user=pg_query("select * from fuser where \"user_group\"='$a_gp' order by user_group,status_user desc");
	}
	while($res=pg_fetch_array($qry_user)){
		$id_user = $res["id_user"];	
		$list_menu=pg_query("select \"status\" from f_usermenu A LEFT OUTER JOIN f_menu B 
							on A.id_menu=B.id_menu
							where  A.id_user='$id_user' and A.id_menu='$ad_idmenu' order by B.name_menu");
		$nummenu = pg_num_rows($list_menu);
		if($nummenu > 0){ //ถ้าพบว่าพนักงานคนนั้นใช้งานเมนูนี้อยู่ ให้ update สถานะเป็นไม่ให้ใช้งาน
			list($sts_old)=pg_fetch_array($list_menu);
			if($sts_old=='t'){ //ถ้า user คนนั้นใช้เมนูนี้อยู่ให้เก็บข้อมูลในตาราง "nw_changemenu" ด้วยว่ามีการเปลี่ยนแปลงเมนูเป็นไม่อนุญาตให้ใช้
				//ตรวจสอบว่ามีรายการที่รอกดรับทราบหรือไม่
				$querysts=pg_query("select * from nw_changemenu where id_user='$id_user' and id_menu='$ad_idmenu' and \"statusApprove\"='2' and \"statusOKapprove\"='FALSE'");
				$numrowsts=pg_num_rows($querysts);
				if($numrowsts==0){ //กรณีไม่มีให้ insert ใหม่
					$ins="insert into \"nw_changemenu\" (\"id_menu\",\"id_user\",\"status\",\"add_user\",\"add_date\",\"approve_user\",\"approve_date\",\"statusApprove\",\"statusOKapprove\") 
												values ('$ad_idmenu','$id_user','FALSE','$user_id','$add_date','$user_id','$add_date','2','FALSE')";
					if($resins=pg_query($ins)){
					}else{
						$status++;
					}
				}else{ //กรณีมีให้ update ข้อมูลให้เป็นปัจจุบัน
					//update สถานะให้เป็นข้อมูลล่าสุด
					$up="update \"nw_changemenu\" set \"status\"='FALSE',\"add_user\"='$user_id',\"add_date\"='$add_date',\"approve_user\"='$user_id',\"approve_date\"='$add_date'
					where \"id_menu\"='$ad_idmenu' and \"id_user\"='$id_user' and \"statusApprove\"='2' and \"statusOKapprove\"='FALSE'";
					if($resup=pg_query($up)){
					}else{
						$status++;
					}
				}
				//update log ว่ามีการเปลี่ยนแปลงข้อมูล
				$uplog="INSERT INTO nw_changemenu_log(
				id_menu, id_user, \"statusRequest\", statusmenu, \"statusApp\", 
				app_user, app_stamp)
				VALUES ('$ad_idmenu', '$id_user', '2', 'FALSE', 'TRUE', 
						'$user_id', '$add_date')";
				if($ins=pg_query($uplog)){
				}else{
					$status++;
				}
			}
			$upd="update f_usermenu set status='FALSE' where id_menu='$ad_idmenu' and id_user='$id_user'";
			if($result=pg_query($upd)){
			}else{
				$up_error=$result;
				$status++;
			}
		}
	}
}else{ //กรณีมีการเลือกพนักงานให้ใช้เมนู
	for($i=0;$i<sizeof($cid);$i++){ //id ของพนักงานที่ใช้เมนูนี้
	 //ค้นหาพนักงานที่อยู่ในกลุ่มทั้งหมด
		if($a_gp==""){
			$qry_user=pg_query("select * from fuser order by user_group,status_user desc");
		}else{
			$qry_user=pg_query("select * from fuser where \"user_group\"='$a_gp' order by user_group,status_user desc");
		}
		
		while($res=pg_fetch_array($qry_user)){
			$id_user = $res["id_user"];
			if($cid[$i] == $id_user){ //กรณีที่ id user ตรงกันแสดงว่าให้ใช้งานเมนูนี้
				//ค้นหาว่ามีข้อมูลหรือยังถ้ายังให้ insert ข้อมูล
				$list_menu=pg_query("select \"status\" from f_usermenu A LEFT OUTER JOIN f_menu B 
									on A.id_menu=B.id_menu
									where  A.id_user='$id_user' and A.id_menu='$ad_idmenu' order by B.name_menu");
				$nummenu = pg_num_rows($list_menu);
				if($nummenu == 0){ //กรณียังไม่มีการใช้เมนูนี้
					//ตรวจสอบว่ามีการบันทึกในตารางนี้หรือยัง
					$querysts=pg_query("select * from nw_changemenu where id_user='$id_user' and id_menu='$ad_idmenu' and \"statusApprove\"='0' and \"statusOKapprove\"='FALSE'");
					$numrowsts=pg_num_rows($querysts);
					if($numrowsts>0){ //แสดงว่ามีการรออนุมัติจากเมนูขอเปลี่ยนแปลงสิทธิ์อยู่
						$status=-1;
						break;
					}else{
						$ins="insert into f_usermenu(id_menu,id_user,status) values ('$ad_idmenu','$id_user','TRUE')";
						if($result=pg_query($ins)){
						}else{
							$ins_error=$result;
							$status++;
						}
					
						//insert ในตาราง \"nw_changemenu\" ด้วยเพื่อแจ้งการเปลี่ยนแปลง
						$ins="insert into \"nw_changemenu\" (\"id_menu\",\"id_user\",\"status\",\"add_user\",\"add_date\",\"approve_user\",\"approve_date\",\"statusApprove\",\"statusOKapprove\") 
													values ('$ad_idmenu','$id_user','TRUE','$user_id','$add_date','$user_id','$add_date','2','FALSE')";
						if($resins=pg_query($ins)){
						}else{
							$status++;
						}
						
						//update log ว่ามีการเพิ่มเมนู
						$uplog="INSERT INTO nw_changemenu_log(
						id_menu, id_user, \"statusRequest\", statusmenu, \"statusApp\", 
						app_user, app_stamp)
						VALUES ('$ad_idmenu', '$id_user', '1', 'TRUE', 'TRUE', 
								'$user_id', '$add_date')";
						if($ins=pg_query($uplog)){
						}else{
							$status++;
						}
					}
					
				}else{ //ถ้่ามีแล้วให้อัพเดทข้อมูลที่มีให้เป็น TRUE
					list($sts_old)=pg_fetch_array($list_menu);
					if($sts_old=='f'){ //ถ้า user คนนั้นไม่ได้ใช้เมนูนี้อยู่ให้เก็บข้อมูลในตาราง "nw_changemenu" ด้วยว่ามีการเปลี่ยนแปลงเมนูเป็นอนุญาตให้ใช้
						//ตรวจสอบว่ามีรายการที่รอกดรับทราบหรือไม่
						$querysts=pg_query("select * from nw_changemenu where id_user='$id_user' and id_menu='$ad_idmenu' and \"statusApprove\"='2' and \"statusOKapprove\"='FALSE'");
						$numrowsts=pg_num_rows($querysts);
						if($numrowsts==0){ //กรณีไม่มีให้ insert ใหม่
							$ins="insert into \"nw_changemenu\" (\"id_menu\",\"id_user\",\"status\",\"add_user\",\"add_date\",\"approve_user\",\"approve_date\",\"statusApprove\",\"statusOKapprove\") 
														values ('$ad_idmenu','$id_user','TRUE','$user_id','$add_date','$user_id','$add_date','2','FALSE')";
							if($resins=pg_query($ins)){
							}else{
								$status++;
							}
						}else{ //กรณีมีให้ update ข้อมูลให้เป็นปัจจุบัน
							//update สถานะให้เป็นข้อมูลล่าสุด
							$up="update \"nw_changemenu\" set \"status\"='TRUE',\"add_user\"='$user_id',\"add_date\"='$add_date',\"approve_user\"='$user_id',\"approve_date\"='$add_date'
							where \"id_menu\"='$ad_idmenu' and \"id_user\"='$id_user' and \"statusApprove\"='2' and \"statusOKapprove\"='FALSE'";
							if($resup=pg_query($up)){
							}else{
								$status++;
							}
						}
						//update log ว่ามีการเปลี่ยนแปลงเมนู
						$uplog="INSERT INTO nw_changemenu_log(
						id_menu, id_user, \"statusRequest\", statusmenu, \"statusApp\", 
						app_user, app_stamp)
						VALUES ('$ad_idmenu', '$id_user', '2', 'TRUE', 'TRUE', 
								'$user_id', '$add_date')";
						if($ins=pg_query($uplog)){
						}else{
							$status++;
						}
					}
					$upd="update f_usermenu set status='TRUE' where id_menu='$ad_idmenu' and id_user='$id_user'";
					if($result=pg_query($upd)){
					}else{
						$up_error=$result;
						$status++;
					}
				}
			}else{  
				//กรณี iduser ไม่ได้เลือก ให้เช็คดูว่ามีในฐานหรือยังถ้ามีแล้วให้เปลี่ยนสถานะ
				$list_menu=pg_query("select \"status\" from f_usermenu A LEFT OUTER JOIN f_menu B 
									on A.id_menu=B.id_menu
									where  A.id_user='$id_user' and A.id_menu='$ad_idmenu' order by B.name_menu ");
				$nummenu = pg_num_rows($list_menu);
				if($nummenu > 0){
					list($sts_old)=pg_fetch_array($list_menu);
					if($sts_old=='f'){
						$sts_old='FALSE';
					}else{
						$sts_old='TRUE';
					}
					
					$num_comp=0;
					//กรณีพบข้อมูลในฐานให้ตรวจสอบด้วยว่าเราได้เลือกหรือไม่ ถ้าเลือกยังคงสถานะเป็น true เหมือนเดิม
					for($j=0;$j<sizeof($cid);$j++){
						if($cid[$j] == $id_user){
							$num_comp=$num_comp+1;
						}
					}
					if($num_comp == 0){ //กรณีไม่ได้ติ๊กเลือก
						$status_com="FALSE";
					}else{ //กรณีติ๊กเลือก
						$status_com="TRUE";
					}
					
					//กรณีข้อมูลเดิมกับข้อมูลใหม่ไม่ตรงกันให้บันทึกด้วยว่ามีการเปลี่ยนแปลงเมนู
					if($sts_old!=$status_com){ 
						//ตรวจสอบว่ามีรายการที่รอกดรับทราบหรือไม่
						$querysts=pg_query("select * from nw_changemenu where id_user='$id_user' and id_menu='$ad_idmenu' and \"statusApprove\"='2' and \"statusOKapprove\"='FALSE'");
						$numrowsts=pg_num_rows($querysts);
						if($numrowsts==0){ //กรณีไม่มีให้ insert ใหม่
							$ins="insert into \"nw_changemenu\" (\"id_menu\",\"id_user\",\"status\",\"add_user\",\"add_date\",\"approve_user\",\"approve_date\",\"statusApprove\",\"statusOKapprove\") 
														values ('$ad_idmenu','$id_user','$status_com','$user_id','$add_date','$user_id','$add_date','2','FALSE')";
							if($resins=pg_query($ins)){
							}else{
								$status++;
							}
						}else{ //กรณีมีให้ update ข้อมูลให้เป็นปัจจุบัน
							//update สถานะให้เป็นข้อมูลล่าสุด
							$up="update \"nw_changemenu\" set \"status\"='$status_com',\"add_user\"='$user_id',\"add_date\"='$add_date',\"approve_user\"='$user_id',\"approve_date\"='$add_date'
							where \"id_menu\"='$ad_idmenu' and \"id_user\"='$id_user' and \"statusApprove\"='2' and \"statusOKapprove\"='FALSE'";
							if($resup=pg_query($up)){
							}else{
								$status++;
							}
						}
						//update log ว่ามีการเปลี่ยนแปลงเมนู
						$uplog="INSERT INTO nw_changemenu_log(
						id_menu, id_user, \"statusRequest\", statusmenu, \"statusApp\", 
						app_user, app_stamp)
						VALUES ('$ad_idmenu', '$id_user', '2', '$status_com', 'TRUE', 
								'$user_id', '$add_date')";
						if($ins=pg_query($uplog)){
						}else{
							$status++;
						}
					}
					
					$upd="update f_usermenu set status='$status_com' where id_menu='$ad_idmenu' and id_user='$id_user'";
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
if($status==-1){
	pg_query("ROLLBACK");
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>มีบางรายการรออนุมัติขอเปลี่ยนแปลงสิทธิ์การทำงานอยู่ กรุณาตรวจสอบ</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_index.php?ad_idmenu=$ad_idmenu&a_gp=$a_gp'>";
}else if($status == 0){
//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) จัดการสิทธิ', '$add_date')");
//ACTIONLOG---

	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_index.php?ad_idmenu=$ad_idmenu&a_gp=$a_gp'>";
}else{
	pg_query("ROLLBACK");
	echo $ins_error."<br>";
	echo $up_error;
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<meta http-equiv='refresh' content='4; URL=frm_index.php?ad_idmenu=$ad_idmenu&a_gp=$a_gp'>";
}	
?>