<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$usersql = pg_query("SELECT * FROM \"fuser\" where \"id_user\" = '$id_user'  ");
$reuser = pg_fetch_array($usersql);
$leveluser = $reuser['emplevel'];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}


$idshow = $_GET['recid'];

$appstate = $_GET['appstate'];
$menum = $_GET['menum'];
if($menum == ""){
	$menum = $_POST['menum'];
	$chktype = $_POST['hdtype'];
}


if($idshow!=""){
	$selsqlfirst = pg_query("SELECT recmenuid, id_menu, id_user, revision_num, recdetail, rec_date, appuser, app_date, appstatus, recheader,show_login FROM f_menu_manual where recmenuid = '$idshow'");
	$resqlfrist = pg_fetch_array($selsqlfirst);
	$text = str_replaceout($resqlfrist['recdetail']);
	$header = $resqlfrist['recheader'];
	$id_menu = $resqlfrist['id_menu'];
	$show_login = $resqlfrist['show_login'];
	$chktype = 'edit';
}else{
	$text = str_replaceout($_POST['editor1']);
	$header = $_POST['nameheader'];
	$id_menu = $_POST['recmenu'];
	$show_login = 't';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">	

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<style type="text/css">
.button {
    cursor:pointer;
    display:block;
    height:auto;
    width:auto;
    line-height:auto;
    text-align:center;
    background-image:url('images/buttons.png');
    background-repeat:no-repeat;
}
.button.green { background-position:0 0 }
.button.green:hover { background-position:0 -350px }
 </style>
<head>
	<title>จัดการคำแนะนำใช้งานเมนู</title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
	
</head>
<body bgcolor="#EEEEEE">
<div style="margin-top:50px;">
<table width="1250" frame="box" align="center" bgcolor="#8B8682">
	<tr>
		<td align="center"><font color="white" size="15px">จัดการคำแนะนำใช้งานเมนู</font></td>
	
	</tr>
</table>	
<table width="1250" frame="box" align="center">
	<tr>
		<td align="center" width="400" valign="top" style="background-color:#FFFFE0;" rowspan="1">
			<table width="100%" frame="box">
				<tr bgcolor="#8B8878">
					<td align="center" height="25px;">
						<b><font color="white">รายการแนะนำ</font></b>
					</td>	
				</tr>
			</table>
			<table width="100%" >
				<tr bgcolor="#CDC8B1" align="center">
						<td>
							<span style="background-color:#ffff00;"><img src='images/icon/Generic_Yellow.ico' width="15px;" height="15px;"></span>รออนุมัติ<?php echo "&nbsp";	?>	
							<span style="background-color:#00ff00;"><img src='images/icon/Generic_Green.ico' width="15px;" height="15px;"></span>อนุมัติ<?php echo "&nbsp";	?>	
							<span style="background-color:#ff0000;"><img src='images/icon/Generic_Red.ico' width="15px;" height="15px;"></span>ไม่อนุมัติ<?php echo "&nbsp";	?>			
						</td>
						<td>
							<img src='images/icon/Trash Full.ico' width="25px;" height="30px;" style="cursor:pointer;" onclick="javascript:popU('frm_del.php');"/>
						</td>
					</tr>
				<?php if($appstate != '1'){ ?>
				<tr bgcolor="#AFEEEE" onmouseover="javascript:this.bgColor = '#FFFF99';" onmouseout="javascript:this.bgColor = '#AFEEEE';">
					 <td onclick="parent.location='Manage_rec.php'"  style="cursor:pointer;" align="center" valign="middle" colspan="2">
					
					<img src="images/icon/page_add.png" height="25px"/> เพิ่มคำแนะนำใหม่ <img src="images/icon/page_add.png" height="25px"/></td>
				</tr>
				<?php } ?> 
			</table>
		
		
			<table width="100%" >
					<tr>
						<td>
			
					
							<?php 
							$i = 1;
							$sqllist1 = pg_query("select distinct(a.\"id_menu\") as id_menu FROM f_menu_manual a where \"appstatus\" != '3' order by \"id_menu\"");
							while($resqllist1 = pg_fetch_array($sqllist1)){
							$idmenuchk = $resqllist1['id_menu'];
							
							$sqlmenu= pg_query("SELECT name_menu FROM f_menu where id_menu = '$idmenuchk'");
								$menuresult = pg_fetch_array($sqlmenu);
								$mename = $menuresult['name_menu'];
							
							echo "<table width=\"100%\"><tr bgcolor=\"#CDB38B\"><td colspan=\"3\"><b>$mename</b></td></tr>";
							
					
								
								$sqllist = pg_query("select \"recheader\",\"recmenuid\",\"appstatus\",\"id_menu\",\"reason_notapp\" FROM 
								f_menu_manual where id_menu = '$idmenuchk' and \"appstatus\" != '3' order by rec_date DESC ");
								
								
								
								while($resqllist = pg_fetch_array($sqllist)){
									$headerlist = $resqllist['recheader'];
									$recmenuid = $resqllist['recmenuid'];
									$appstatus = $resqllist['appstatus'];
									$idmenulist = $resqllist['id_menu'];
									$reason_notapp = $resqllist['reason_notapp'];
								
								
									
								echo "<tr>";
									
							
								if($appstatus == '0'){
									$status = "<img src='images/icon/Generic_Yellow.ico' width=\"15px;\" height=\"15px;\">";
									$reason = "";
								}else if($appstatus == '1'){
									$status = "<img src='images/icon/Generic_Green.ico' width=\"15px;\" height=\"15px;\">";										
									$reason = "";
									
									$delete = "<img src='images/icon/cross.png' Title=\"ยกเลิก\"  width=\"15px;\" height=\"15px;\" style=\"cursor:pointer;\" onclick=\"JavaScript:if(confirm('ต้องการยกเลิกประกาศนี้  ใช่หรือไม่?')==true){window.location='Process_del.php?recid=$recmenuid&type=del'}\">";
								}else{
									$status = "<img src='images/icon/Generic_Red.ico' width=\"15px;\" height=\"15px;\">";									
									$reason = "title = \"$reason_notapp\" ";
									$delete = "<img src='images/icon/cross.png' Title=\"ยกเลิก\"  width=\"15px;\" height=\"15px;\" style=\"cursor:pointer;\" onclick=\"JavaScript:if(confirm('ต้องการยกเลิกประกาศนี้  ใช่หรือไม่?')==true){window.location='Process_del.php?recid=$recmenuid&type=del'}\">";
								}
								
								if($menum == $i){
									$status = "<img src='images/icon/open.ico' width=\"15px;\" height=\"15px;\">";								
								}
								
								if($appstate != '1'){
									echo "<td width=\"5%\"></td><td style=\"cursor:pointer;background:url(images/bg.png) repeat-x;\" onclick=\"parent.location='Manage_rec.php?recid=$recmenuid&menum=$i'\" $reason><a>$status $headerlist</a><td width=\"5%\">";if($leveluser <= 1){ echo "$delete"; } echo "</td></td>";
								}else{
									echo "<td width=\"5%\"></td><td style=\"cursor:pointer;background:url(images/bg.png) repeat-x;\" onclick=\"parent.location='Manage_rec.php?recid=$recmenuid&appstate=1&menum=$i'\" $reason>$status $headerlist</td>";
								}
									
							$delete="";
							$reason="";
							$status="";
							$i++; 
							} 
							echo "</tr></table>";
						}	?>
						
					</td>
				</tr>
			</table>	
		
		</td>
		<td align="center" valign="top">
			<form method="POST">

						<table width="100%" align="center" >
							<tr bgcolor="#CDC8B1" >
								<td width="45%" colspan="2" align="center">
									แนะนำของเมนู : <select name="recmenu">
									<?php $sqlmenu = pg_query("SELECT id_menu, name_menu, status_menu, path_menu FROM f_menu order by name_menu"); 
											while($resultmenu = pg_fetch_array($sqlmenu)){ ?>
												<option value="<?php echo $resultmenu['id_menu']; ?>" <?php if($id_menu  == $resultmenu['id_menu']){ echo "selected";} ?>><?php echo $resultmenu['name_menu']; ?></option>
											
									<?php	} ?>
									</select>
									
								ชื่อเรื่อง : <input type="text" size="50" name="nameheader" value="<?php echo $header; ?>">									
									<input type="checkbox" name="chkalert" value="1" <?php if($show_login == 't'){echo "checked";} ?>> แจ้งเตือน
								</td>
							</tr>
						
						<?php 	if($text != ""){ ?>
							<tr>
								<td align="center" colspan="2" >
									<table width="100%" frame="box">
										<tr bgcolor="white">
											<td>
												
													<?php echo $text; ?>
												
											</td>
										</tr>
									</table>	
								</td>
							</tr>
						<?php } ?>	
						<?php if($appstate != '1'){ ?>
							<tr>
								<td align="center" colspan="2">
									
										<textarea class="ckeditor" cols="100" id="editor1" name="editor1" rows="10"><?php echo $text; ?></textarea> 			
									
								</td>
							</tr>
						<?php } ?>		
						</table>							
		</td>
	</tr>
	<tr>
		<?php if($idshow == ""){ $idshow  = $_POST['hdrecidmenu']; } ?>
		<td align="center" bgcolor="#8B8878" colspan="2">		
				<?php if($appstate != '1' && $idshow == "" && $chktype != "edit"){ ?>

					<input type="hidden" name="hdtype" value="insert">
				
					<input type="submit" value=" ตัวอย่าง " onclick="show(this.form)" style="width:150px;height:30px;">								
					<input type="submit" value=" บันทึก " onclick="insert(this.form)" style="width:150px;height:30px;">
					
				<?php }else if($appstate != '1' && $idshow != "" && $chktype == "edit"){ ?>

					<input type="hidden" name="hdtype" value="edit">
					
					<input type="hidden" name="hdrecidmenu" value="<?php echo $idshow; ?>">
					<input type="hidden" name="menum" value="<?php echo $menum; ?>">
					
					
					<input type="submit" value=" ตัวอย่าง " onclick="show(this.form)" style="width:150px;height:30px;">								
					<input type="submit" value=" แก้ไข " onclick="insert(this.form)" style="width:150px;height:30px;">
				<?php } ?>					
		</td>
	</tr>
</table>
</form>	
</body>
<script type="text/javascript">
function insert(frmok){	
		frmok.action="Process_manage.php";
		frmok.submit();
		document.frmok.submit.disabled='true';	
		return true;
}

function show(frmshow){	
		frmshow.action="Manage_rec.php";
		frmshow.submit();
		document.frmshow.submit.disabled='true';	
		return true;
}


</script>	