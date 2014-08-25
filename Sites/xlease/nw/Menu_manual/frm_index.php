<?php
include("../../config/config.php");
session_start();
$idnow = $_SESSION["av_iduser"];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}


$idshow = $_GET['recid'];
$menum = $_GET['menum'];



if($idshow!=""){
	$selsqlfirst = pg_query("SELECT recmenuid, id_menu, id_user, revision_num, recdetail, date(rec_date) as daterec, appuser, app_date, appstatus, recheader FROM f_menu_manual where recmenuid = '$idshow'");
	$resqlfrist = pg_fetch_array($selsqlfirst);
	$text = str_replaceout($resqlfrist['recdetail']);
	$header = $resqlfrist['recheader'];
	$id_menu = $resqlfrist['id_menu'];
	$id_userlist = $resqlfrist['id_user'];
	$rec_date = $resqlfrist['daterec'];
	$revision_num = $resqlfrist['revision_num'];
	$recmenuidlog = $resqlfrist['recmenuid'];
	
	//log user
		$logsql = pg_query("INSERT INTO f_menu_manual_user_log(recmenuid, id_user, datetime)VALUES ('$recmenuidlog', '$idnow', '$datenow')");

	
}else{
	$sqlusernow = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$idnow' ");
	$userresultnow = pg_fetch_array($sqlusernow);
									  
	$text = "<p><p><h2><center>สวัสดีคุณ ".$userresultnow['fullname']."</center></h2><p><p>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};

	$("#stext").autocomplete({
        source: "Data_search.php",
        minLength:1
    });

    $('#btn1').click(function(){
		var aaaa = $("#stext").val();
        //var brokenstring=aaaa.split("#");
        newWindow = window.open('frm_search.php?stext='+aaaa,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600');
	});	
});
</script>
<head>
	<title>แนะนำใช้งานเมนู</title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
</head>
<body bgcolor="#F0FFF0">
<div style="margin-top:50px;">
<table width="1250" align="center">
	<tr>
		<td>
			<form action="javascrip:popU('frm_search.php')" method="POST">
			<table width="850" align="left">
				<tr>
					<td align="center" >
						<table width="400" frame="box" align="left" bgcolor="#8B8682">
							<tr>
								<td align="center"><font color="white" size="6px">แนะนำการใช้งานเมนู</font></td>
							</tr>
						</table>
					</td>	
					<td style="padding:25px 0px 0px 0px"><input type="text" name="stext" id="stext" size="70"><input type="button" id="btn1" value="ค้นหา"></td>					
				</tr>
			</table>
			</form>		
		</td>
		
	</tr>
	<tr>
		<td>
			<table width="1250" frame="box" align="center">
				<tr>
					<td align="center" width="400" valign="top" style="background-color:#FFFAFA;" rowspan="1">
						<table width="100%" frame="box">
							<tr bgcolor="#8B8878">
								<td align="center" height="25px;">
									<b><font color="white">รายการแนะนำ</font></b>
								</td>	
							</tr>
						</table>		
					<div style="width: 99%; height: 450px; overflow: auto;">
						<table width="100%">
										<?php 
										$i = 1;
										$sqllist1 = pg_query("select distinct(a.\"id_menu\") as id_menu FROM f_menu_manual a where \"appstatus\" = '1' order by \"id_menu\"");
										while($resqllist1 = pg_fetch_array($sqllist1)){
										$idmenuchk = $resqllist1['id_menu'];
										
										$sqlmenu= pg_query("SELECT name_menu FROM f_menu where id_menu = '$idmenuchk'");
											$menuresult = pg_fetch_array($sqlmenu);
											$mename = $menuresult['name_menu'];
										
										echo "<table width=\"100%\"><tr bgcolor=\"#CDB38B\"><td colspan=\"2\"><b>$mename</b></td></tr>";

										
										/*$sqllist = pg_query("select a.\"recheader\",a.\"recmenuid\",a.\"appstatus\",a.\"id_menu\",a.\"reason_notapp\" FROM f_menu_manual a where 
										revision_num in((select max(\"revision_num\") FROM \"f_menu_manual\" where id_menu = (a.\"id_menu\") and \"appstatus\" = '1')) and
										\"id_menu\" in(SELECT b.id_menu FROM f_usermenu b where b.id_user='$idnow' and b.status='true')  order by \"recmenuid\" DESC");*/
										
										$sqllist = pg_query("select \"recheader\",\"recmenuid\",\"appstatus\",\"id_menu\",\"reason_notapp\" FROM 
										f_menu_manual where id_menu = '$idmenuchk' and \"appstatus\" = '1' order by rec_date DESC ");
									
									
									
										while($resqllist = pg_fetch_array($sqllist)){
											$headerlist = $resqllist['recheader'];
											$recmenuid = $resqllist['recmenuid'];
											$appstatus = $resqllist['appstatus'];
											$idmenulist = $resqllist['id_menu'];
											$reason_notapp = $resqllist['reason_notapp'];
											
											
											echo "<tr>";
											
											$newssql = pg_query("SELECT runrow, recmenuid, id_user, datetime FROM f_menu_manual_user_log where id_user='$idnow' and recmenuid = '$recmenuid'");
											$newsrow = pg_num_rows($newssql);
											if($newsrow == 0){
												$newtext = "<img src='images/icon/new.png' width=\"20px;\" height=\"20px;\">";
											}else{
												$newtext = "";
											}
											
											if($menum == $i){
												$status = "<img src='images/icon/open.ico' width=\"15px;\" height=\"15px;\">";								
											}else{
												$status = "<img src='images/icon/Generic_Green.ico' width=\"15px;\" height=\"15px;\">";	
											}
											
											$sqlmenu= pg_query("SELECT name_menu FROM f_menu where id_menu = '$idmenulist'");
											$menuresult = pg_fetch_array($sqlmenu);
											$mename = $menuresult['name_menu'];
				
												echo "<td width=\"5%\"></td><td style=\"cursor:pointer;background:url(images/bg.png) repeat-x;\" onclick=\"parent.location='frm_index.php?recid=$recmenuid&menum=$i'\" ><a>$status $headerlist  $newtext</a></td>";
											
												echo "</tr>";
										
										$i++; 
										}
									}			?>
									
						</table>				
					</div>
						
					</td>
					<td align="center" valign="top" style="background-color:#CDCDC1;" >
			<form method="POST">
					<table width="100%" align="center" >
									<?php if($idshow != ""){ ?>
										<tr bgcolor="#CDC8B1" >
											<td width="32%" colspan="" align="left">
												<b>เมนู :</b> 
												<?php $sqlmenu = pg_query("SELECT id_menu, name_menu, status_menu, path_menu FROM f_menu  where id_menu = '$id_menu' order by id_menu"); 
													  $resultmenu = pg_fetch_array($sqlmenu);
														echo $resultmenu["name_menu"];
													  ?>
											</td>		  
											<td width="30%" colspan="" align="center">		  
												<b>ชื่อเรื่อง :</b> <?php echo $header; ?>
											</td>
											<td width="30%" colspan="" align="center">	
												<?php $sqluser = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$id_userlist' ");
													  $userresult = pg_fetch_array($sqluser);?>
												<b>ผู้แต่ง :</b> <?php echo $userresult['fullname']; ?>
											</td>
											<td width="10%" colspan="" align="right">
												<b>ครั้งที่ :</b> <?php echo $revision_num; ?>
											</td>
										</tr>
										<tr bgcolor="#CDC8B1" >
											<td width="13%" colspan="4" align="right">
												<b>ประกาศวันที่ :</b> <?php echo $rec_date; ?>
											</td>
										</tr>
									<?php } ?>	
										<tr>
											<td align="center" colspan="5" >
											<div style="width: 100%; height: 460px; overflow: auto;">
												<table width="100%" frame="box">
													<tr bgcolor="white">
														<td>
															
																<?php echo $text; ?>
															
														</td>
													</tr>
												</table>
											</div>	
											</td>
										</tr>
															
									</table>							
					</td>
				</tr>
				<tr>
					
					<td align="center" bgcolor="#8B8878" colspan="2">		
												
					</td>
				</tr>
			</table>
			</form>	
		</td>
	</tr>
</table>
</body>