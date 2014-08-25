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
$path = $_GET['path'];
$k = $_GET['k'];
$code = $_GET['code'];


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

</script>
<head>
	<title>แนะนำใช้งานเมนู</title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
</head>
<body bgcolor="#F0FFF0">
<div style="margin-top:50px;">
<form method="POST" name="frm" action="swappage.php" >



<table width="90%" align="center">
	<tr>
		<td>
			<table width="100%" >
				<tr>
					<td align="right">
                    	<div style="display:inline-block; height:35px; text-align:right; font-size:15px; font-weight:bold; color:#dd0000;" class="count_down_box">
                        <span>คำแนะนำ : เมนูนี้จะเริ่มใช้งานได้หลังจาก </span>
                        <span id="count_down1">30</span>
                        <span>  วินาที  โปรดอ่านคู่มือให้ละเอียดก่อนเริ่มใช้งานเมนู!!</span>
                    </div>
                    	<input type="submit" name="btn_submit1" id="btn_submit11" value=" ฉันได้อ่านเรียบร้อย-เริ่มใช้เมนู " disabled="disabled" />
                    </td>
				</tr>
			</table>	
		</td>
	</tr>
	<tr>
		<td align="center" valign="top" style="background-color:#CDCDC1;" >									
				<table width="100%" align="center" >									
					<tr bgcolor="#CDC8B1" >
						<td width="25%" colspan="" align="left">
							<b>เมนู :</b> 
							<?php $sqlmenu = pg_query("SELECT id_menu, name_menu, status_menu, path_menu FROM f_menu  where id_menu = '$id_menu' order by id_menu"); 
							 $resultmenu = pg_fetch_array($sqlmenu);
							echo $resultmenu["name_menu"]; ?>
						</td>		  
						<td width="25%" colspan="" align="center">		  
							<b>ชื่อเรื่อง :</b> <?php echo $header; ?>
						</td>
						<td width="25%" colspan="" align="center">	
						<?php $sqluser = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$id_userlist' ");
							$userresult = pg_fetch_array($sqluser);?>
							<b>ผู้แต่ง :</b> <?php echo $userresult['fullname']; ?>
						</td>
						<td width="10%" colspan="" align="center">
							<b>ครั้งที่ :</b> <?php echo $revision_num; ?>
						</td>										
						<td width="15%" colspan="" align="right">
							<b>ประกาศวันที่ :</b> <?php echo $rec_date; ?>
						</td>
					</tr>									
					<tr>
						<td align="center" colspan="5" >
							<div style="width: 100%; height: 560px; overflow: auto;">
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
		<td>
			<table width="100%" >
				<tr>
					<td align="right">
                    <div style="display:inline-block; height:35px; text-align:right; font-size:15px; font-weight:bold; color:#dd0000;" class="count_down_box">
                        <span>คำแนะนำ : เมนูนี้จะเริ่มใช้งานได้หลังจาก </span>
                        <span id="count_down2">30</span>
                        <span>  วินาที  โปรดอ่านคู่มือให้ละเอียดก่อนเริ่มใช้งานเมนู!!</span>
                    </div>
                    <input type="submit" name="btn_submit2" id="btn_submit21" value=" ฉันได้อ่านเรียบร้อย-เริ่มใช้เมนู " disabled="disabled" />
                    </td>
				</tr>
			</table>	
		</td>
	</tr>
</table>

<input type="hidden" name="k" value="<?php echo $k ; ?>">
<input type="hidden" name="path" value="<?php echo $path ; ?>">
<input type="hidden" name="code" value="<?php echo $code ; ?>">
<input type="hidden" name="recmenuidlog" value="<?php echo $recmenuidlog ; ?>">
<input type="hidden" name="id_menu" value="<?php echo $id_menu ; ?>">
</form>	
</div>


<script type="text/javascript">



$(document).ready(function(){
	setInterval(function(){
		var timer = parseInt($('#count_down1').html());
		if(timer>0)
		{
			timer = timer-1;
			$('#count_down1').html(timer);
			$('#count_down2').html(timer);
		}
		else
		{
			$('#btn_submit21').removeAttr('disabled');
			$('#btn_submit11').removeAttr('disabled');
			
			clearInterval();
			
			$('.count_down_box').hide();
		}
	},1000);
});
</script>
</body>