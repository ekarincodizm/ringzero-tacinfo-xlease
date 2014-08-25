<?php
session_start();
include("../../config/config.php");
$av_iduser=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
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

$id_user=pg_escape_string($_POST["id_user"]);
$method=pg_escape_string($_POST["method"]);
if($method==""){
	$method=pg_escape_string($_GET["method"]);
}
$currentdate=nowDate();

if($id_user==""){
	$id_user=pg_escape_string($_GET["id_user"]);
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
	for($i=0;$i<count($_POST["i_menu"]);$i++) {
		$ii_menu=$_POST["i_menu"][$i];
		$se_st=$_POST["stas"][$i];
		$result2=$_POST["result2"][$i];
		
		//ดึงข้อมูลเก่าขึ้นมาเพื่อตรวจสอบว่าค่า status เดิมคืออะไร
		$querystatus=pg_query("select * from f_usermenu where id_user='$id_user' and id_menu='$ii_menu'");
		if($res_status=pg_fetch_array($querystatus)){
			$sts_old=$res_status["status"];
		}
		
		$querysts=pg_query("select * from nw_changemenu where id_user='$id_user' and id_menu='$ii_menu' and \"statusApprove\"='0' and \"statusOKapprove\"='FALSE'");
		$numrowsts=pg_num_rows($querysts);
		
		//ถ้าสถานะไม่เหมือนกันแสดงว่ามีการเปลี่ยนแปลงให้ add ในตาราง nw_changemenu
		if($sts_old != $se_st){
			if($numrowsts==0){
				$sql="insert into \"nw_changemenu\" (\"id_menu\",\"id_user\",\"status\",\"result\",\"add_user\",\"add_date\",\"statusApprove\",\"statusOKapprove\") values ('$ii_menu','$id_user','$se_st','$result2','$av_iduser','$currentdate','0','FALSE')";
				if($db_query=pg_query($sql)){
				}else{
					$status++;
				}
			}else{
				$sql="update nw_changemenu set \"result\"='$result2' where id_user='$id_user' AND id_menu='$ii_menu' and \"statusOKapprove\"='FALSE'";
				if($db_query=pg_query($sql)){
				}else{
					$status++;
				}
			}
		}else{
			//ตรวจสอบว่าในตาราง nw_changemenu มีหรือไม่ถ้ามีให้แก้ไขเป็นค่าเดิม
			if($numrowsts > 0){
				$sql="update nw_changemenu set \"statusApprove\"='1',\"result\"='$result2',\"statusOKapprove\"='TRUE' where id_user='$id_user' AND id_menu='$ii_menu' and \"statusOKapprove\"='FALSE'";
				if($db_query=pg_query($sql)){
				}else{
					$status++;
				}
			}
		} 
	}
	if($status == 0){
		//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$av_iduser', '(ALL) ขอเปลี่ยนแปลงสิทธิ์การทำงาน', '$add_date')");
		//ACTIONLOG---
		pg_query("COMMIT");
	}else{
		pg_query("ROLLBACK");
	}
}else if($method=="add"){
	$ad_idmenu=$_POST["ad_idmenu"];
	$resultadd=$_POST["resultt"];

	if($ad_idmenu != ""){ //กรณีเลือก id_menu
		//ต้องตรวจสอบด้วยว่า มีเมนูและสถานะเหมือนกับที่เลือกหรือไม่
		$query=pg_query("select * from \"f_usermenu\" where \"id_menu\"='$ad_idmenu' and \"id_user\"='$id_user' ");
		$numrows=pg_num_rows($query);
		if($res_query=pg_fetch_array($query)){
			$statusold=$res_query["status"];
		}
		if($statusold =='t'){
			$status1 ="เมนูซ้ำกรุณาเลือกเมนูใหม่!!";
		}else{
			$querysts=pg_query("select * from nw_changemenu where id_user='$id_user' and id_menu='$ad_idmenu' and \"statusApprove\"='0'");
			$numrowsts=pg_num_rows($querysts);
			if($numrowsts ==0){
				$sql="insert into \"nw_changemenu\" (\"id_menu\",\"id_user\",\"status\",\"result\",\"add_user\",\"add_date\",\"statusApprove\",\"statusOKapprove\") values ('$ad_idmenu','$id_user','TRUE','$resultadd','$av_iduser','$currentdate','0','FALSE')";
				if($db_query=pg_query($sql)){
				}else{
					$status++;
				}
				
				if($status == 0){
					//ACTIONLOG
							$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$av_iduser', '(ALL) ขอเปลี่ยนแปลงสิทธิ์การทำงาน', '$add_date')");
					//ACTIONLOG---
					pg_query("COMMIT");
					$status1 ="ส่งคำร้องขอเรียบร้อยแล้ว!!";
				}else{
					pg_query("ROLLBACK");
				}
			}else{
				$upd="update nw_changemenu set \"result\"='$resultadd' where id_user='$id_user' AND id_menu='$ad_idmenu' and \"statusApprove\"='0' and \"statusOKapprove\"='FALSE'";
				if($upd_query=pg_query($upd)){
				}else{
					$status++;
				}
				$status1 ="เมนูนี้กำลังรออนุมัติ!!";
				if($status == 0){
					pg_query("COMMIT");
				}else{
					pg_query("ROLLBACK");
				}
			}
		}	
	}
}else if($method=="delete"){
	$changeID=$_GET["changeID"];
	$del="delete from \"nw_changemenu\" where \"changeID\"='$changeID' ";
	if($resultdel=pg_query($del)){
			$status1 ="Delete เมนูแล้ว";
	}else{
		$status1 ="error Delete Menu ".$del;
		$status=$status+1;
	}
	if($status == 0){
		pg_query("COMMIT");
	}else{
		pg_query("ROLLBACK");
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ขอเปลี่ยนแปลงสิทธิ์การทำงาน</title>
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
<script type="text/javascript">
//ตรวจสอบ พนักงาน
function chk_userid_insys(no){
		$.post('chk_id_user.php',{
		id_user : document.form1.id.value		
		},
		function(data){	
			if(data == "1"){
				if(no=='1'){					
					document.form1.submit();
				}
				else if(no=='2'){
					document.form2.submit();
				}
			}else{
				alert("ไม่มีข้อมูลอยู่ในระบบ กรุณาเลือกข้อมูล พนักงานใหม่");								
			}
			
		});	
		
};

</script>
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
		<form method="post" name="form1" action="frm_addmenu.php">
		<table width="780" border="0" style="background-color:#EEEDCC">
		<tr><td colspan="5"><b>รายการใช้งานปัจจุบัน</b></td><td align="right"><table border="0"><tr><td bgcolor="#FFCCCC" width="15" height="10"></td><td>รายการที่รออนุมัติเปลี่ยนแปลง</td></tr></table></td></tr>		
		<tr style="background-color:#D0DCA0" align="left">
			<th height="25">id menu </th>
			<th>name menu</th>
			<th>คำอธิบายเมนู</th>
			<th>การใ้ช้งาน<br>ปัจจุบัน</th>
			<th>สถานะเมนู</th>
			<th>เหตุผลที่เปลี่ยนแปลงเมนู</th>
		</tr>
		<?php 
		$qry_menu=pg_query("select * from \"f_usermenu\" a 
				LEFT OUTER JOIN f_menu b on a.id_menu=b.id_menu
				where \"id_user\"='$id_user' order by b.name_menu");
		$numrow_menu=pg_num_rows($qry_menu);
		while($resmenu=pg_fetch_array($qry_menu)){
			$stas=$resmenu['status'];	
			$color="#E4E4E4";
			$qry_change=pg_query("select * from \"nw_changemenu\" where \"id_menu\"='$resmenu[id_menu]' and \"id_user\"='$id_user' and \"statusApprove\"='0' and \"statusOKapprove\"='FALSE'");
			$numrow_chang=pg_num_rows($qry_change);
			if($res_change=pg_fetch_array($qry_change)){
				$staschange=$res_change["status"];
				$result=$res_change["result"];
			}
			
			if($numrow_chang > 0){
				$stas=$staschange;
				$color="#FFCCCC";
			}
			?>
			<tr bgcolor="<?php echo $color;?>" height="25">    
				<td width="85"><?php echo $resmenu["id_menu"]; ?></td>
				<td width="350"><?php echo $resmenu["name_menu"]; ?></td>
				<td width="300"><?php echo $resmenu["menu_desc"]; ?></td>
				<td width="150"><?php echo $resmenu["menu_status_use"]; ?></td>
				<td>
					<select name="stas[]"><option value="t" <?php if($stas=="t"){ echo "selected";}?>>ใช้งาน</option><option value="f" <?php if($stas=="f"){ echo "selected";}?>>ระงับใช้งาน</option></select>
				</td>
				<td><input type="text" name="result2[]" size="35" value="<?php echo $result;?>"></td>
				<td align="center">
				
				<input type="hidden" name="id_user" value="<?php echo $id_user;?>">
				<input type="hidden" name="method" value="edit">
				<input type="hidden" name="i_menu[]" value="<?php echo $resmenu['id_menu']; ?>" />
				</td>
			</tr>		
		<?php
			$result="";
		}?>
		<input type="hidden" name="id" value="<?php echo $id_user;?>">
		<tr><td colspan=7 height=30><input type="button" value="บันทึกการเปลี่ยนแปลง" onclick="chk_userid_insys('1')"></td></tr>
		<?php if($numrow_menu ==0){
			echo "<tr height=50><td align=center colspan=5><b>ไม่มีรายการ</b></td></tr>";
		}
		?>	
		</form>
		<tr>
			<td colspan="7">&nbsp;</td>
		</tr>
		<?php
		$qry_change2=pg_query("select * from \"nw_changemenu\" a 
				LEFT OUTER JOIN f_menu b on a.\"id_menu\"=b.id_menu
				where \"id_user\"='$id_user' and \"statusApprove\"='0' and \"statusOKapprove\"='FALSE'");
		$num_change2=pg_num_rows($qry_change2);
		if($num_change2 > 0){
			echo "<tr style=\"background-color:#FFF1CE;\">";
			echo "<td colspan=5 height=25><b>รายการที่ต้องการเพิ่ม</b></td>";
			echo "</tr>";
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
					echo "<td colspan=3><input type=\"text\" value=\"$resultadd\" size=\"50\" readonly>&nbsp;<a href=\"frm_addmenu.php?method=delete&id_user=$id_user&changeID=$changeID\" onclick=\"return confirm('กรุณายืนยันการลบอีกครั้ง !!!')\" ><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>";
					echo "</tr>";
				}
				
			}
		}
		?>
		<form method="post" name="form2" action="frm_addmenu.php">
		<tr align="center">
			<td colspan="7" style="background-color:#FFECB9;" height="80">&nbsp;<b>เลือกรายการต้องการเพิ่ม</b>
				<select name="ad_idmenu" id="ad_idmenu">
				<?php
				$qry_menu=pg_query("select * from f_menu 
				where \"id_menu\" not in(select \"id_menu\" from f_usermenu where id_user='$id_user')
				order by name_menu"); 
				while($res=pg_fetch_array($qry_menu)){
				?>
					<option value="<?php echo trim($res["id_menu"]); ?>"><?php echo trim($res["name_menu"]); ?></option>
				<?php
				}
				?>
				</select>
				<input type="hidden" name="method" value="add">
				<b>เหตุผลขอเพิ่มรายการ</b><input type="text" name="resultt" size="50"><br><br>
				<input type="hidden" name="id_user" value="<?php echo $id_user;?>">
				<input type="button" value="เพิ่มรายการ" onclick="chk_userid_insys('2')">	
				<input type="button" value="BACK" onclick="window.location='frm_IndexAdd.php?id_user=<?php echo $id_user;?>'">				
			</td>
		</tr>
		<?php if($status1!= ""){?>
		<tr><td colspan="7" height="25" bgcolor="#FFF1CE" align="center"><b>***** <?php echo $status1;?> *****</b>&nbsp;</td></tr>
		<?php }?>
		</form>
		</table>
		
	</div>
	<div id="footerpage"></div>
</div>
</body>
</html>
