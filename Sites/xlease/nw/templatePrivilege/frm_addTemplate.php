<?php
session_start();
include("../../config/config.php");
$_SESSION["av_iduser"];
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

$tempID=pg_escape_string($_GET["tempID"]);
$tempName=pg_escape_string($_GET["tempName"]);
if($tempID==""){
	$tempID=pg_escape_string($_POST["tempID"]);
}

$ad_idmenu=pg_escape_string($_POST["ad_idmenu"]);

pg_query("BEGIN WORK");
$status = 0;
$temp_old=pg_escape_string($_POST["temp_old"]);

if($temp_old=='1'){
	$ad_Template=pg_escape_string($_POST["ad_Template"]);	//รหัสพนักงานสิทธิ์ที่เลือก
	$query=pg_query("select \"id_menu\" from \"f_usermenu\"  where \"id_user\" ='$ad_Template'");
	$numrows=pg_num_rows($query);
	if($numrows>0){
		while($res=pg_fetch_array($query)){
			$id_menu=$res["id_menu"];
			$sql_query=pg_query("select * from \"nw_templateDetail\" where \"id_menu\"='$id_menu' and \"tempID\"='$tempID'");
			$numrow=pg_num_rows($sql_query);
			if($numrow > 0){
				$status1 ="เมนูซ้ำกรุณาเลือกเมนูใหม่!!";
				$status=$status+1;
			}else{
				$in_sql="insert into \"nw_templateDetail\" (\"tempID\",\"id_menu\") values ('$tempID','$id_menu')";
				if($result=pg_query($in_sql)){

				}else{
					$status1 ="error Insert nw_template ".$in_sql;
					$status=$status+1;
				}
			}
		}
		if($status == 0){
			$status1 ="Insert เมนูแล้ว";
			pg_query("COMMIT");
		}else{
			pg_query("ROLLBACK");
		}
	}
}
else{
//เลือกเมนู
if($ad_idmenu != ""){
	$query=pg_query("select * from \"nw_templateDetail\" where \"id_menu\"='$ad_idmenu' and \"tempID\"='$tempID'");
	$numrows=pg_num_rows($query);
	if($numrows > 0){
		$status1 ="เมนูซ้ำกรุณาเลือกเมนูใหม่!!";
	}else{
		$in_sql="insert into \"nw_templateDetail\" (\"tempID\",\"id_menu\") values ('$tempID','$ad_idmenu')";
		if($result=pg_query($in_sql)){
			$status1 ="Insert เมนูแล้ว";
		}else{
			$status1 ="error Insert nw_template ".$in_sql;
			$status=$status+1;
		}
		if($status == 0){
			pg_query("COMMIT");
		}else{
			pg_query("ROLLBACK");
		}
	}	
}
}
$method=$_GET["method"];
if($method=="delete"){
	$id_menu=$_GET["id_menu"];
	$del="delete from \"nw_templateDetail\" where \"id_menu\"='$id_menu' and \"tempID\"='$tempID'";
	if($result=pg_query($del)){
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
<title>เพิ่มเมนูใน Template</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link> 
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
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">เมนูใช้งานปัจจุบัน (Template:<?php echo $tempName; ?>)<hr /></div>
		<div id="contentpage" style="height:auto; padding-left:10px; padding-right:10px;">
		
		<table width="780" border="0" style="background-color:#EEEDCC">
		<?php if($status1!= ""){?>
		<tr><td colspan="4" height="25" bgcolor="#FFCCCC" align="center"><b>***** <?php echo $status1;?> *****</b></td></tr>
		<?php }?>
		<tr style="background-color:#D0DCA0" align="left">
			<th height="25">id menu </th>
			<th>name menu</th>
			<th>สถานะเมนู</th>
			<th align="center">ลบจาก Template</th>
		</tr>
		<?php
		if($tempID !=""){
		$qry_menu=pg_query("select a.\"id_menu\",b.\"name_menu\",b.\"status_menu\" from \"nw_templateDetail\" a 
					LEFT OUTER JOIN f_menu b on a.id_menu=b.id_menu
					where \"tempID\"='$tempID' order by b.name_menu ");
					$numrow_menu=pg_num_rows($qry_menu);
		while($resmenu=pg_fetch_array($qry_menu)){
			$stas=$resmenu['status_menu'];
			if($stas=='1'){
				$txtstas="ใช้งาน";
			}else{
				$txtstas="ระงับใช้งาน";
			}
			?>
			<tr style="background-color:#E4E4E4;" height="25">    
				<td width="85"><?php echo $resmenu["id_menu"]; ?></td>
				<td width="445"><?php echo $resmenu["name_menu"]; ?></td>
				<td width="126"><?php echo $txtstas;?></td>
				<td width="100" align="center"><a href="frm_addTemplate.php?tempID=<?php echo $tempID;?>&tempName=<?php echo $tempName;?>&id_menu=<?php echo $resmenu["id_menu"];?>&method=delete" onclick="return confirm('กรุณายืนยันการลบอีกครั้ง !!!')" ><img src="images/delete.gif" width="16" height="16" border="0"></a></td>
			</tr>		
		<?php
		}
		}//end if
		if($numrow_menu ==0 || $tempID==""){
			echo "<tr height=50><td align=center colspan=4><b>ไม่มีรายการ</b></td></tr>";
		}
		?>	
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<form method="post" name="form1" action="frm_addTemplate.php">
		<tr>
			<td colspan="2" style="background-color:#FFFF99;" height="30">&nbsp;<b>เลือกรายการ</b></td><td style="background-color:#FFFF99;">
				<select name="ad_idmenu" id="ad_idmenu">
				<?php
				$qry_menu=pg_query("select * from f_menu 
				where \"id_menu\" not in(select \"id_menu\" from \"nw_templateDetail\" where \"tempID\"='$tempID')
				order by name_menu"); 
				while($res=pg_fetch_array($qry_menu)){
				?>
					<option value="<?php echo trim($res["id_menu"]); ?>"><?php echo trim($res["name_menu"]); ?></option>
				<?php
				}
				?>
				</select>	
			</td><td style="background-color:#FFFF99;"><input type="hidden" name="tempID" value="<?php echo $tempID;?>"><input type="submit" value="SAVE"/></td>
		</tr>
		</form>
		<!--ชื่อ Template เดิม-->
		<form method="post" name="form1" action="frm_addTemplate.php">
		<tr>
			<td colspan="2" style="background-color:#FFFF99;" height="30">&nbsp;<b>รายการสิทธิของผู้ใช้งาน</b></td><td style="background-color:#FFFF99;">
				<select name="ad_Template" id="ad_Template">
					<option value=""><?php echo "เลือกรายการสิทธิของผู้ใช้งาน"; ?></option>
				<?php
				$query=pg_query("select DISTINCT a.\"id_user\" as \"id_user\",\"fullname\" from \"Vfuser_active\" a
				left join \"f_usermenu\" b on a.\"id_user\"=b.\"id_user\"
				where a.\"status_user\"='t' order by  a.\"id_user\" ");
				
				while($res=pg_fetch_array($query)){
				?>
					<option value="<?php echo trim($res["id_user"]); ?>"><?php echo $res["id_user"].'-'.$res["fullname"]; ?></option>
				<?php
				}
				?>
				</select>	
			</td><td style="background-color:#FFFF99;">
			<input type="hidden" name="temp_old" value="1">
			<input type="hidden" name="tempID" value="<?php echo $tempID;?>">
			<input type="submit" value="SAVE"/></td>
		</tr>
		</form>
		<tr>
			<!--td><input type="hidden" name="tempID" value="<?php echo $tempID;?>"><input type="submit" value="SAVE"/></td-->
			<td colspan="2"></td>
			<td align="right"><input type="button" value="BACK" onclick="window.location='frm_IndexAdd.php?tempID=<?php echo $tempID;?>&sent=1&f_tempName=<?php echo $tempName;?>'"></td>
		</tr>
		</table>		
	</div>
	<div id="footerpage"></div>
</div>
</body>
</html>
