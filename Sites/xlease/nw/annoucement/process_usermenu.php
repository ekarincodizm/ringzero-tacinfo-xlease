<?php
set_time_limit(0);
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>

<?php 
 $a_gp=$_POST["a_gp"]; //กลุ่มผู้ใช้
 $annId=$_POST["ad_annId"]; //id ของ Template
 $cid=$_POST["cid"]; //พนักงานที่เลือกให้ใช้เมนูนี้
 $chknewemp=$_POST["chknewemp"];
 $newempdep=$_POST["newempdep"];
 
 
pg_query("BEGIN WORK");
$status = 0;

if(sizeof($cid) == 0){ //กรณีไม่เลือกให้ ยกเลิกทั้งหมดเลยทั้งกรณีที่มีข้อมูลแล้วหรือไม่มีข้อมูล
	//ค้นหาพนักงานที่อยู่ในกลุ่มทั้งหมด
	if($a_gp==""){
		$qry_user=pg_query("select * from fuser order by user_group,status_user desc");
	}else{
		$qry_user=pg_query("select * from fuser where \"user_group\"='$a_gp' order by user_group,status_user desc");
	}
	while($res=pg_fetch_array($qry_user)){
		$id_user = $res["id_user"];	
		$list_menu=pg_query("select * from \"nw_annouceuser\" where \"id_user\"='$id_user' and \"annId\"='$annId'");
		$nummenu = pg_num_rows($list_menu);
		if($nummenu > 0){
			//$ins="insert into \"nw_annouceuser\" (\"id_user\",\"annId\",\"statusAccept\") values ('$id_user','$annId','FALSE')";
			$upd="update \"nw_annouceuser\" set \"statusAccept\"='0' where \"annId\"='$annId' and \"id_user\"='$id_user'";
			if($resins=pg_query($upd)){
			}else{
				$status++;
			}
		}
	}
}else{ //กรณีเลือก user 
	for($i=0;$i<sizeof($cid);$i++){ //id ของพนักงานที่ใช้เมนูนี้
	//ค้นหาพนักงานที่อยู่ในกลุ่มทั้งหมด
		if($a_gp==""){
			$qry_user=pg_query("select * from fuser order by user_group,status_user desc");
		}else{
			$qry_user=pg_query("select * from fuser where \"user_group\"='$a_gp' order by user_group,status_user desc");
		}
				
		while($res=pg_fetch_array($qry_user)){
			$id_user = $res["id_user"];
			if($cid[$i] == $id_user){ //กรณีที่ id user ตรงกันแสดงว่าให้ใช้งานเมนูนี้ ดังนั้นถ้ายังไม่มีให้ add มีแ้ล้ว ให้ update
				//ค้นหาว่ามีข้อมูลหรือยังถ้ายังให้ insert ข้อมูล
				$list_menu=pg_query("select * from \"nw_annouceuser\" where \"id_user\"='$id_user' and \"annId\"='$annId'");
				$nummenu = pg_num_rows($list_menu);
				if($nummenu == 0){
					$ins="insert into \"nw_annouceuser\" (\"id_user\",\"annId\",\"statusAccept\") values ('$id_user','$annId','1')";
					if($resins=pg_query($ins)){
					}else{
						$status++;
					}		
				}else{
					$upd="update \"nw_annouceuser\" set \"statusAccept\"='1' where \"annId\"='$annId' and \"id_user\"='$id_user'";
					if($result=pg_query($upd)){
					}else{
						$up_error=$result;
						$status++;
					}
				}
			}else{  //ถ้า user ไม่ตรงกันให้ อัพเดท user ที่มีอยู่ให้ เป็น 0
				//กรณี iduser ไม่ได้เลือก ให้เช็คดูว่ามีในฐานหรือยังถ้ามีแล้วให้เปลี่ยนสถานะเป็น 0 คือไม่อนุญาตให้แสดงประกาศ
				$list_menu=pg_query("select * from \"nw_annouceuser\" where \"id_user\"='$id_user' and \"annId\"='$annId'");
				$nummenu = pg_num_rows($list_menu);
				if($nummenu > 0){ //กรณีมีแล้วให้ update สถานะ
					$num_comp=0;
					//กรณีพบข้อมูลในฐานให้ตรวจสอบด้วยว่าเราได้เลือกหรือไม่ ถ้าเลือกยังคงสถานะเป็น 0 ถ้าไม่ได้เลือกให้คงสถานะเดิม
					for($j=0;$j<sizeof($cid);$j++){
						if($cid[$j] == $id_user){
							$num_comp=$num_comp+1;
						}
					}
					if($num_comp == 0){
						$upd="update \"nw_annouceuser\" set \"statusAccept\"='0' where \"annId\"='$annId' and id_user='$id_user'";
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

if($chknewemp == '1'){ //แจ้งให้พนักงานใหม่ ทราบด้วย
				$list_menunewbie=pg_query("select * from \"nw_annouceuser_newbie\" where \"annId\"='$annId'");
				$nummenunewbie = pg_num_rows($list_menunewbie);
			if($newempdep==""){	

				if($nummenunewbie == 0){ 
					$newbie="INSERT INTO nw_annouceuser_newbie(\"annId\", dep_id,\"statusAccept\")VALUES ('$annId', 'allemp','0')";
				}else{
					$newbie="UPDATE nw_annouceuser_newbie set dep_id='allemp',\"statusAccept\"='0' where \"annId\"='$annId' ";
				}		
			}else{
				
				if($nummenunewbie == 0){ 
					$newbie="INSERT INTO nw_annouceuser_newbie(\"annId\", dep_id,\"statusAccept\")VALUES ('$annId', '$newempdep','0')";
				}else{
					$newbie="UPDATE nw_annouceuser_newbie set dep_id='$newempdep',\"statusAccept\"='0' where \"annId\"='$annId' ";
				}
			}
			
			
}else{
	$newbie="delete from nw_annouceuser_newbie where \"annId\"='$annId'";	
}
	if($relistnewbie=pg_query($newbie)){
	}else{
		$status++;
	}
if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_setupuser.php?ad_annId=$annId&a_gp=$a_gp'>";
}else{
	pg_query("ROLLBACK");
	echo $up_error."<br>";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<meta http-equiv='refresh' content='4; URL=frm_setupuser.php?ad_annId=$annId&a_gp=$a_gp'>";
}	
?>