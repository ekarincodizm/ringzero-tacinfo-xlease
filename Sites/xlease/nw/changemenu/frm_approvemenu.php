<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$av_iduser=$_SESSION["av_iduser"];
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}

pg_query("BEGIN WORK");
$status = 0;

$id_user=$_POST["id_user"];
$method=$_POST["method"];

$annID=$_POST["annID"]; //รหัสประกาศกรณีแก้ไขเมนู
$annID2=$_POST["annID2"]; //รหัสประกาศกรณีขอเพิ่มเมนู

if($method==""){
	$method=$_GET["method"];
}
$currentdate=nowDate();
$nowdate=nowDateTime();

if($id_user==""){
	$id_user=$_GET["id_user"];
}
$query_name=pg_query("select * from \"Vfuser\" a
left join \"department\" b on a.\"user_group\"=b.\"dep_id\" 
left join \"f_department\" c on a.\"user_dep\"=c.\"fdep_id\" 
where a.\"id_user\"='$id_user'");
if($res_name=pg_fetch_array($query_name)){
	$fullname=$res_name["fullname"];
	$dep_name=$res_name["dep_name"]; if($dep_name=="") $dep_name="-";
	$fdep_name=$res_name["fdep_name"]; if($fdep_name=="") $fdep_name="-";
}

if($method=="edit"){
	//ดึงเมนูที่เลือก
	$j=0;
	$h=0;
	for($i=0;$i<count($_POST["i_menu"]);$i++) {
		$ii_menu=$_POST["i_menu"][$i]; //รหัสเมนูที่ต้องการเปลี่ยนแปลง
		$se_st=$_POST["stas"][$i]; //สถานะที่อนุมัติ
		$result2=$_POST["result2"][$i];
		
		$querysts=pg_query("select * from nw_changemenu where id_user='$id_user' and id_menu='$ii_menu' and \"statusApprove\"='0'");
		if($res_status=pg_fetch_array($querysts)){
			$sts_old=$res_status["status"];
			$add_date=$res_status["add_date"];
		}
		
		//กรณีข้อมูลเดิมกับข้อมูลใหม่เหมือนกันแสดงว่าอนุมัติตามที่ร้องขอให้อัพเดท f_usermenu ตามที่ร้องขอแล้ว update ตาราง nw_chanesmenu เป็นอนุมัติ
		if($sts_old == $se_st){
			//อัพเดท f_usermenu
			$upfusermenu="update f_usermenu set \"status\"='$se_st' where id_user='$id_user' AND id_menu='$ii_menu' ";
			if($db_query=pg_query($upfusermenu)){
			}else{
				$status++;
			}
			
			//อัพเดท nw_changemenu เป็นอนุมัติ
			$upfusermenu="update nw_changemenu set \"statusApprove\"='2',\"approve_user\"='$av_iduser',\"approve_date\"='$currentdate' where id_user='$id_user' AND id_menu='$ii_menu' and \"add_date\"='$add_date'";
			if($db_query=pg_query($upfusermenu)){
			}else{
				$status++;
			}
			
			//update log ว่าขอเปลี่ยนแปลง
			$uplog="INSERT INTO nw_changemenu_log(
            id_menu, id_user, \"statusRequest\", statusmenu, \"statusApp\", 
            app_user, app_stamp)
			VALUES ('$ii_menu', '$id_user', '2', '$sts_old', 'TRUE', 
					'$av_iduser', '$nowdate')";
			if($ins=pg_query($uplog)){
			}else{
				$status++;
			}
			
			if($se_st=='t'){ //เก็บรหัสเมนูที่ขอใช้งานใน array เพื่อนำไปแสดงตอนประกาศว่ามีเมนูใดบ้างที่ขอใช้งาน	
				$tmenu[$j]=$ii_menu;
				$j++;
			}else{ //เก็บรหัสเมนูที่ขอระงับใช้งานใน array เพื่อนำไปแสดงตอนประกาศว่ามีเมนูใดบ้างที่ขอใช้งาน	
				$fmenu[$h]=$ii_menu;
				$h++;
			}
			
		}else{  //กรณีไม่เท่ากันแสดงว่าไม่อนุมัติ (เปลี่ยนค่าเป็นเหมือนเดิมทำให้ค่า status เหมือนเดิม)
			//อัพเดท nw_changemenu เป็นไม่อนุมัติ
			$upfusermenu="update nw_changemenu set \"statusApprove\"='3',\"approve_user\"='$av_iduser',\"approve_date\"='$currentdate' where id_user='$id_user' AND id_menu='$ii_menu' and \"add_date\"='$add_date'";
			if($db_query=pg_query($upfusermenu)){
			}else{
				$status++;
			}
			
			//update log ว่าไม่อนุมัติ
			$uplog="INSERT INTO nw_changemenu_log(id_menu, id_user, \"statusRequest\", statusmenu, \"statusApp\", app_user, app_stamp)
										  VALUES ('$ii_menu', '$id_user', '2', '$sts_old', 'FALSE', '$av_iduser', '$nowdate')";
			if($ins=pg_query($uplog)){
			}else{
				$status++;
			}
		} 
	} //end for
	
	for($j=0;$j<sizeof($tmenu);$j++){ //กรณีเป็นเมนูที่ใช้งาน
		//ค้นหาชื่อเมนู
		$qrynamemenu=pg_query("SELECT name_menu, path_menu FROM f_menu where id_menu='$tmenu[$j]'");
		list($name_menu)=pg_fetch_array($qrynamemenu);

		$name_menut=$name_menut." - รหัสเมนู <b>$tmenu[$j]</b> ชื่อเมนู <b>$name_menu</b><br>";
	}
	
	for($h=0;$h<sizeof($fmenu);$h++){ //กรณีเป็นเมนูที่ระงับใช้งาน
		//ค้นหาชื่อเมนู
		$qrynamemenu=pg_query("SELECT name_menu, path_menu FROM f_menu where id_menu='$fmenu[$h]'");
		list($name_menu)=pg_fetch_array($qrynamemenu);

		$name_menuf=$name_menuf." - รหัสเมนู <b>$fmenu[$h]</b> ชื่อเมนู <b>$name_menu</b><br>";
	}
	$txtannouncef="
	<pre><strong>ท่านได้ถูกระงับสิทธิการทำงานในเมนูัดังต่อไปนี้ตั้งแต่ <span style=\"color:#ff0000;\">".$currentdate."</span> เป็นต้นไป</strong></pre>
	<pre>$name_menuf</pre>
	";
	
	$txtannouncet="
	<br><br><pre><strong>ท่านได้รับสิทธิการทำงานในเมนูัดังต่อไปนี้ตั้งแต่ <span style=\"color:#ff0000;\">".$currentdate."</span> เป็นต้นไป</strong></pre>
	<pre>$name_menut</pre>
	";
	
	if(sizeof($fmenu)==0){ //กรณีไม่มีเมนูระงับใช้งาน
		$txtannouncef="";
	}
	if(sizeof($tmenu)==0){ //กรณีไม่มีเมนูใช้งาน
		$txtannouncet="";
	}

	$txtann="$txtannouncef $txtannouncet";
	$txtann=checknull(trim($txtann));

	//insert ประกาศ
	$qryid=pg_query("select max(\"annId\") as \"annID\" from \"nw_annoucement\"");
	list($numrowid)=pg_fetch_array($qryid);
	$annId=$numrowid+1; 
	
	$inann="INSERT INTO nw_annoucement(\"annId\",\"typeAnnId\", \"annTitle\", \"annContent\", \"annAuthor\", \"keyDate\", 
			\"statusApprove\", \"annApprove\", \"approveDate\", \"statusImportance\", \"statusCancel\")
	VALUES ('$annId','1', 'แจ้งเปลี่ยนแปลงสิทธิการทำงาน', $txtann, '000', '$currentdate', 
			'TRUE', '000', '$currentdate', 'f', 'FALSE')";
	if($insann=pg_query($inann)){
	}else{
		$status++;
	}
	//insert ให้ user คนนี้ได้รับประกาศ
	$inannuser="INSERT INTO nw_annouceuser(id_user, \"annId\", \"statusAccept\")
	VALUES ('$id_user', '$annId', '1')";
	if($insannuser=pg_query($inannuser)){
	}else{
		$status++;
	}
	

	if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$av_iduser', '(ALL) อนุมัติเปลี่ยนแปลงสิทธิ์การทำงาน', '$datelog')");
		//ACTIONLOG---
		pg_query("COMMIT");
		//pg_query("ROLLBACK");
		$txtapprove="รายการเปลี่ยนแปลงเรียบร้อยแล้ว";
	}else{
		pg_query("ROLLBACK");
	}
}else if($method=="add"){
	$querymenu=pg_query("select * from nw_changemenu where id_user='$id_user' and \"statusApprove\"='0'");
	$numrowmenu=pg_num_rows($querymenu);
	
	$j=0;
	while($res_menu=pg_fetch_array($querymenu)){
		$ad_idmenu=$res_menu["id_menu"];
		
		//ตรวจสอบว่ามีเมนูนี้ใน f_usermenu หรือยัง ถ้ายังให้ add
		$query=pg_query("select * from \"f_usermenu\" where \"id_menu\"='$ad_idmenu' and \"id_user\"='$id_user' ");
		$numrows=pg_num_rows($query);
		
		if($numrows==0){
			$ins="insert into \"f_usermenu\" (\"id_menu\",\"id_user\",\"status\") values ('$ad_idmenu','$id_user','TRUE')";
			if($db_query=pg_query($ins)){
			}else{
				$status++;
			}
		
			$upfusermenu="update nw_changemenu set \"statusApprove\"='2',\"approve_user\"='$av_iduser',\"approve_date\"='$currentdate' where id_user='$id_user' AND id_menu='$ad_idmenu'";
			if($db_query=pg_query($upfusermenu)){
			}else{
				$status++;
			}			
			
			//update log ว่าอนุมัติเพิ่มเมนู
			$uplog="INSERT INTO nw_changemenu_log(id_menu, id_user, \"statusRequest\", statusmenu, \"statusApp\", app_user, app_stamp)
										  VALUES ('$ad_idmenu', '$id_user', '1', 'TRUE', 'TRUE', '$av_iduser', '$nowdate')";
			if($ins=pg_query($uplog)){
			}else{
				$status++;
			}
				
			$tmenu[$j]=$ad_idmenu; //เก็บรหัสเมนูที่เพิ่มไว้ใน array
			$j++;

		}
	}
	
	for($j=0;$j<sizeof($tmenu);$j++){ //กรณีเป็นเมนูที่ใช้งาน
		//ค้นหาชื่อเมนู
		$qrynamemenu=pg_query("SELECT name_menu, path_menu FROM f_menu where id_menu='$tmenu[$j]'");
		list($name_menu)=pg_fetch_array($qrynamemenu);

		$name_menut=$name_menut." - รหัสเมนู <b>$tmenu[$j]</b> ชื่อเมนู <b>$name_menu</b><br>";
	}
	
	$txtannouncet="
	<pre><strong>ท่านได้ัขอเพิ่มเมนูและได้รับสิทธิการทำงานในเมนูัดังต่อไปนี้ตั้งแต่ <span style=\"color:#ff0000;\">".$currentdate."</span> เป็นต้นไป</strong></pre>
	<pre>$name_menut</pre>
	";
	
	if(sizeof($tmenu)==0){
		$txtann="null";
	}else{
		$txtann=checknull(trim($txtannouncet));
	}
	
		
	//insert ประกาศ
	$qryid=pg_query("select max(\"annId\") as \"annID\" from \"nw_annoucement\"");
	list($numrowid)=pg_fetch_array($qryid);
	$annID2=$numrowid+1; 
	
	$inann="INSERT INTO nw_annoucement(\"annId\",\"typeAnnId\", \"annTitle\", \"annContent\", \"annAuthor\", \"keyDate\", 
			\"statusApprove\", \"annApprove\", \"approveDate\", \"statusImportance\", \"statusCancel\")
	VALUES ('$annID2','1', 'แจ้งเปลี่ยนแปลงสิทธิการทำงาน', $txtann, '000', '$currentdate', 
			'TRUE', '000', '$currentdate', 'f', 'FALSE')";
	if($insann=pg_query($inann)){
	}else{
		$status++;
	}
	
	//insert ให้ user คนนี้ได้รับประกาศ
	$inannuser="INSERT INTO nw_annouceuser(id_user, \"annId\", \"statusAccept\")
	VALUES ('$id_user', '$annID2', '1')";
	if($insannuser=pg_query($inannuser)){
	}else{
		$status++;
	}
			
	
	if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$av_iduser', '(ALL) อนุมัติเปลี่ยนแปลงสิทธิ์การทำงาน', '$datelog')");
		//ACTIONLOG---
		pg_query("COMMIT");
		//pg_query("ROLLBACK");
		$status1="อนุมัติการเพิ่มรายการเรียบร้อยแล้ว";
	}else{
		pg_query("ROLLBACK");
	}
}else if($method=="delete"){
	$changeID=$_GET["changeID"];
	$querymenu=pg_query("select * from \"f_menu\" a
	left join \"nw_changemenu\" b on a.\"id_menu\"=b.\"id_menu\"
	where \"changeID\"='$changeID'");
	if($res=pg_fetch_array($querymenu)){
		$name_menu=$res["name_menu"];
		$id_menu=$res["id_menu"];
	}
	$del="update \"nw_changemenu\" set \"statusApprove\"='3' where \"changeID\"='$changeID' ";
	if($resultdel=pg_query($del)){
			$status1 ="ไม่อนุมัติรายการ $name_menu แล้ว";
	}else{
		$status1 ="error Cancel Menu ".$del;
		$status=$status+1;
	}
	
	//update log ว่าไม่อนุมัติเพิ่มเมนู
	$uplog="INSERT INTO nw_changemenu_log(id_menu, id_user, \"statusRequest\", statusmenu, \"statusApp\", app_user, app_stamp)
								  VALUES ('$id_menu', '$id_user', '1', 'TRUE', 'FALSE', '$av_iduser', '$nowdate')";
	if($ins=pg_query($uplog)){
	}else{
		$status++;
	}
	
	if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$av_iduser', '(ALL) อนุมัติเปลี่ยนแปลงสิทธิ์การทำงาน', '$datelog')");
		//ACTIONLOG---
		pg_query("COMMIT");
		//pg_query("ROLLBACK");
	}else{
		pg_query("ROLLBACK");
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>อนุมัติเปลี่ยนแปลงสิทธิ์การทำงาน</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link> 
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body>
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;">
		<span class="style2" style="padding-left:10px; height:60px; width:800px; ">
		<div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div>
		<div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div>
	</div>
	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">ชื่อพนักงาน :</b> <?php echo $fullname; ?>  &nbsp;&nbsp;&nbsp;<b>ฝ่าย :</b> <?php echo $dep_name;?> &nbsp;&nbsp;&nbsp;<b>แผนก :</b> <?php echo $fdep_name;?><hr /></div>
		<div id="contentpage" style="height:auto; padding-left:10px; padding-right:10px;">
		<form method="post" name="form1" action="frm_approvemenu.php">
		<table width="780" border="0" style="background-color:#EEEDCC">
		<tr><td colspan="3"><b>รายการใช้งานปัจจุบัน</b></td><td align="right" colspan="3"><table border="0"><tr><td bgcolor="#FFCCCC" width="15" height="10"></td><td>รายการที่ร้องขอ</td></tr></table></td></tr>		
		<tr style="background-color:#D0DCA0" align="left">
			<th height="25" width="85">id menu</th>
			<th width="300">name menu</th>
			<th width="300">คำอธิบายเมนู</th>
			<th width="100">การใ้ช้งาน<br>ปัจจุบัน</th>
			<th>สถานะเมนู</th>
			<th width="250">เหตุผลที่เปลี่ยนแปลงเมนู</th>
		</tr>
		<?php 
		$qry_menu=pg_query("select * from \"f_usermenu\" a 
				LEFT OUTER JOIN f_menu b on a.id_menu=b.id_menu
				where \"id_user\"='$id_user' order by b.name_menu");
		$numrow_menu=pg_num_rows($qry_menu);
		$numchge=0;
		while($resmenu=pg_fetch_array($qry_menu)){
			$stas=$resmenu['status'];	
			$color="#E4E4E4";
			$qry_change=pg_query("select * from \"nw_changemenu\" where \"id_menu\"='$resmenu[id_menu]' and \"id_user\"='$id_user' and \"statusApprove\"='0'");
			$numrow_chang=pg_num_rows($qry_change);
			if($res_change=pg_fetch_array($qry_change)){
				$staschange=$res_change["status"];
				$result=$res_change["result"];
			}
			
			if($numrow_chang > 0){
				$stas=$staschange;
				$color="#FFCCCC";
				$numchge++;
			}
			?>
			<tr bgcolor="<?php echo $color;?>" height="25">    
				<td><?php echo $resmenu["id_menu"]; ?></td>
				<td><?php echo $resmenu["name_menu"]; ?></td>
				<td><?php echo $resmenu["menu_desc"]; ?></td>
				<td><?php echo $resmenu["menu_status_use"]; ?></td>
				<td>
					<?php 
					if($numrow_chang == 0){
						if($stas == 't'){
							echo "ใช้งาน";
						}else{
							echo "ระงับใช้งาน";
						}
					}else{
					?>
					<select name="stas[]"><option value="t" <?php if($stas=="t"){ echo "selected";}?>>ใช้งาน</option><option value="f" <?php if($stas=="f"){ echo "selected";}?>>ระงับใช้งาน</option></select>
					<?php }?>
				</td>
				<td><?php if($result !=""){?><input type="text" name="result2" size="35" value="<?php echo $result;?>" readonly><?php }?></td>
			</tr>
			<?php 
			if($numrow_chang > 0){
			?>
			<tr>
				<td align="center" colspan="6">
					<input type="hidden" name="id_user" value="<?php echo $id_user;?>">
					<input type="hidden" name="method" value="edit">
					<input type="hidden" name="i_menu[]" value="<?php echo $resmenu['id_menu']; ?>" />	
				</td>
			</tr>
		<?php 
			}	
			$result="";
		}
		if($numchge > 0){
			echo "<tr><td colspan=6 height=30>
			<input type=\"hidden\" name=\"annID\" value=\"$annID\">
			<input type=\"submit\" value=\"อนุมัติการเปลี่ยนแปลง\" id=\"update\" ";
			if($txtapprove !=""){	
				echo " <b>***** $txtapprove *****</b>";
			}
			echo "</td></tr>";
		}
		if($numrow_menu ==0){
			echo "<tr height=50><td align=center colspan=6><b>ไม่มีรายการ</b></td></tr>";
		}
		?>	
		</form>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		<form method="post" name="form2" action="frm_approvemenu.php">
		<?php
		$qry_change2=pg_query("select * from \"nw_changemenu\" a 
				LEFT OUTER JOIN f_menu b on a.\"id_menu\"=b.id_menu
				where \"id_user\"='$id_user' and \"statusApprove\"='0'");
		$num_change2=pg_num_rows($qry_change2);
		if($num_change2 > 0){
			echo "<tr style=\"background-color:#FFF1CE;\">";
			echo "<td colspan=6 height=25><b>รายการที่ต้องการเพิ่ม</b></td>";
			echo "</tr>";
			$p=0;
			while($res_change2=pg_fetch_array($qry_change2)){
				$changeID=$res_change2["changeID"];
				$id_menu2=$res_change2["id_menu"];
				$name_menu=$res_change2["name_menu"];
				$resultadd=$res_change2["result"];
				
				$qry_menu2=pg_query("select * from \"f_usermenu\" 
				where \"id_user\"='$id_user' and id_menu='$id_menu2'");
				$num_menu2=pg_num_rows($qry_menu2);
				
				if($num_menu2==0){
					echo "<tr style=\"background-color:#FFCCCC;\">";
					echo "<td>$id_menu2</td>";
					echo "<td>$name_menu</td>";
					echo "<td colspan=2><input type=\"text\" value=\"$resultadd\" size=\"50\" readonly>&nbsp;<a href=\"frm_approvemenu.php?method=delete&id_user=$id_user&changeID=$changeID\" onclick=\"return confirm('กรุณายืนยันการไม่อนุมัติรายการนี้ !!!')\" ><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" title=\"ไม่อนุมัติ\"></a></td>";
					echo "</tr>";
					$p++;
				}
				
			}
		}
		if($status1!= ""){?>
		<tr><td colspan="6" height="25" bgcolor="#FFF1CE" align="center"><b>***** <?php echo $status1;?> *****</b>&nbsp;</td></tr>
		<?php 
		}
		if($p==0){
			echo "<tr bgcolor=#FFCCCC><td colspan=6 align=left height=50><b>-ไม่พบรายการเพิ่มเติมที่ต้องอนุมัติ-</b></td></tr>";
		}		
		?>			
		<tr align="center">
			<td colspan="6" style="background-color:#FFECB9;" height="80">
				<?php if($p > 0){ ?>
				<input type="hidden" name="method" value="add">
				<input type="hidden" name="id_user" value="<?php echo $id_user;?>">
				<input type="hidden" name="annID2" value="<?php echo $annID2;?>">
				<input type="submit" value="อนุมัติการเพิ่มรายการ" id="add">	
				<?php }?>
				<input type="button" value="BACK" onclick="window.location='approve_menu.php'">				
			</td>
		</tr>
		</form>
		</table>
		
	</div>
	<div id="footerpage"></div>
</div>
</body>
</html>
