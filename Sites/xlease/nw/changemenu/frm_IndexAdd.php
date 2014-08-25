<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
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

$id_user=$_POST["id_user"];
$id_user=substr($id_user,0,3);

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


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ขอเปลี่ยนแปลงสิทธิ์การทำงาน</title>
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
<div style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;">
		<span class="style2" style="padding-left:10px; height:60px; width:800px; ">
		<div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div>
		<div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div>
	</div>
	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">ขอเปลี่ยนแปลงสิทธิ์การทำงาน<hr /></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<table width="779" border="0" style="background-color:#EEF2DB;" cellspacing="1" >
			<tr style="background-color:#D0DCA0;" align="left">
				<td width="400" height="25" colspan="2"><b>ชื่อพนักงาน :</b> <?php echo $fullname; ?>  &nbsp;&nbsp;&nbsp;<b>ฝ่าย :</b> <?php echo $dep_name;?> &nbsp;&nbsp;&nbsp;<b>แผนก :</b> <?php echo $fdep_name;?></td>
			</tr>
			</table>
			<form method="post" action="frm_addmenu.php" >
			<input type="hidden" name="id_user" value="<?php echo $id_user; ?>"  />
			<table width="778" border="0" style="background-color:#D5EAC8;">
			<tr style="background-color:#A8D38D;">
				<td colspan="2" height="25"><b>เมนูที่ใช้งาน</b></td>
				<td>คำอธิบายเมนู</td>
				<td>การใ้ช้งาน<br>ปัจจุบัน</td>
				<td><b>สถานะ</b></td>
			</tr>
				<?php				
				$qry_menu=pg_query("select * from \"f_usermenu\" a 
				LEFT OUTER JOIN f_menu b on a.id_menu=b.id_menu
				where \"id_user\"='$id_user' order by b.name_menu ");
				$numrow_menu=pg_num_rows($qry_menu);
				$i=0;
				while($resmenu=pg_fetch_array($qry_menu)){
					$stas=$resmenu['status'];
					if($stas=='t'){
						$txtstas="ใช้งาน";
					}else{
						$txtstas="ระงับใช้งาน";
					}
					if($i%2 == 0){
						$color="#EAFCAB";
					}else{
						$color="#F0FDC4";
					}
					?>
					<tr bgcolor="#D5EAC8">    
						<td width="85" height="25"><?php echo $resmenu["id_menu"]; ?></td>
						<td width="350"><?php echo $resmenu["name_menu"]; ?></td>
						<td width="300"><?php echo $resmenu["menu_desc"]; ?></td>
						<td width="150"><?php echo $resmenu["menu_status_use"]; ?></td>
						<td width="126"><?php echo $txtstas;?></td>
					</tr>
				<?php
				$i++;
				}

				if($numrow_menu == 0){
					echo "<tr height=50><td align=center colspan=5><b>ไม่มีรายการ</b></td></tr>";
				}
				?>
			<tr style="background-color:#A8D38D;">
				<td><input type="submit" value="เพิ่มหรือเปลี่ยนแปลงเมนู" <?php if($id_user==""){ echo "disabled";}?>></td>
				<td>&nbsp;</td>
				<td colspan="3"><input type="button" value="BACK" onclick="window.location='frm_Index.php'" /></td>
			</tr>
			</table>
			</form>
		</div>
		<div id="footerpage"></div>
	</div>
</div>
</body>
</html>
