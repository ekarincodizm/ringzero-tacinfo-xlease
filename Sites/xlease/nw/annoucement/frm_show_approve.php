<?php
session_start();
include("../../config/config.php");
$iduser = $_SESSION['av_iduser'];
$annId=$_GET['annId'];
$statusshow=$_GET['status'];
//ค้นหาประกาศว่ามีหรือไม่
$query_popup=pg_query("select *,d.\"fullname\" as author,e.\"fullname\" as user_approve from \"nw_annoucement\" a 
	left join \"nw_annoucetype\" c on a.\"typeAnnId\"=c.\"typeAnnId\"
	left join \"Vfuser\" d on a.\"annAuthor\"=d.\"id_user\"
	left join \"Vfuser\" e on a.\"annApprove\"=e.\"id_user\"
	where a.\"annId\"='$annId' ");
$numrows_popup=pg_num_rows($query_popup);

if($numrows_popup > 0){ //กรณีมีประกาศที่ได้รับการ Approve แล้ว
	while($res_pop=pg_fetch_array($query_popup)){
		$annId=$res_pop["annId"];
		$typeAnnName=$res_pop["typeAnnName"];
		$annTitle=str_replaceout($res_pop["annTitle"]);
		$annContent=str_replaceout($res_pop["annContent"]);
		$user_author=$res_pop["author"];
		$statusImportance=$res_pop["statusImportance"];
		$user_approve=trim($res_pop["user_approve"]);
		if($statusshow==1){
			$approveDate=$res_pop["approveDate"];
		}else{
			$approveDate=date("Y-m-d");
		}
		$keyDate=$res_pop["keyDate"];
		
		if($user_approve==""){
			$query_name=pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$iduser'");
			$res_name=pg_fetch_array($query_name);
			$user_approve=$res_name["fullname"];
		}
		
		$nowdate_thai=pg_query("select conversiondatetothaitext('$approveDate')");
		$nowdate_thai_show=pg_fetch_result($nowdate_thai,0);
		//ตรวจสอบดูว่า user นี้ ได้ทำการ รับทราบหรือยัง 
		$query_popuser=pg_query("select * from \"nw_annouceuser\" where \"annId\"='$annId' and \"id_user\"='$iduser' and \"statusAccept\"='TRUE'");
		$num_popuser=pg_num_rows($query_popuser);
		if($num_popuser == 0){  //ให้แสดงข้อมูล	
		
		$do_date = date_parse($keyDate);
		$focusdate = date_parse("2012-11-22");
		if($do_date<$focusdate)
		{
			$annContent = str_replace("\r\n","<br />",$annContent);
			$annContent = str_replace("\\\"","\"",$annContent);
		}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ประกาศ</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link> 
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script> 
<style type="text/css">
#warppage
	{
	width:800px;
	margin:0px auto;
	
	min-height: 5em;
	background: rgb(255, 255, 255);
	
	border: rgb(209, 235, 247) solid 0.5px;
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
.weightfont{
	font-weight:bold
}
</style>
<script language=javascript>
</script>
</head>

<body>
<div style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:800px; height:auto;background:url('images/header_01.gif') no-repeat;">
		<?php
		if($statusImportance=='t'){
		?>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px;text-align:center;"><h1><font color="red">ประกาศสำคัญ!!</font></h1></div><br>
		<?php
		}
		?>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px;text-align:center;"><h1><?php echo $typeAnnName;?></h1></div><br>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px;text-align:center;"><h3><?php echo $nowdate_thai_show?></h3></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;margin-top:10px;"><br />
			<table width="100%" border="0" cellspacing="1" align="center">
			<tr style="background-color:#FFFFFF;" align="left">
				<td>
					<table width="100%" border="0" style="background-color:#FFFFFF;" cellspacing="1" align="center">	
						<tr>
							<td width="100" height="20"></td>
							<td width="10"></td>
							<td></td>
						</tr>
						<tr height="25">
							<td align="right" class="weightfont" width="30"><h2>เรื่อง</h2></td>
							<td width="10">:</td>
							<td><h2><?php  echo $annTitle;?></h2>				
						</td>
						</tr>
						<tr>
							<td valign="top" colspan="3" align="center"> 
								<table width="80%" height="400" frame="box">
									<tr bgcolor="white">
										<td valign="top">	
											<div style="height:400px;overflow: auto;background-color:<?php if($statusImportance=='t'){ ?>#FFEAEA;border: red solid 2px; <?php }else{?>#f9fedb;border: rgb(232, 242, 179) solid 0.5px;<?php } ?>" ><?php echo $annContent; ?></div>	
										</td>
									</tr>
								</table>															
							</td>
						</tr>
						<tr height="25">
							<td align="right" class="weightfont" valign="top">รูปภาพ/ไฟล์แนบ</td>
							<td width="10"valign="top">:</td>
							<td>
								<table>
								<tr><td>
								<?php
								$queryfile=pg_query("select * from \"nw_annoucefile\" where \"annId\"='$annId'");
								while($res_file=pg_fetch_array($queryfile)){
									$pathfile=$res_file["pathfile"];
									echo "<a href=\"upload/$pathfile\" target=\"_blank\"><u>$pathfile</u></a><br>";
								}
								?>
								</td></tr>
								<tr><td></td></tr>
								</table>
							</td>
						</tr>
						<tr height="25">
							<td></td>
							<td></td>
							<td align="right"><b>ผู้ตั้งเรื่อง :</b><?php echo $user_author;?></td>
						</tr>
						<tr height="25">
							<td></td>
							<td></td>
							<td align="right"><b>ผู้อนุมัติ :</b><?php echo $user_approve;?></td>
						</tr>
						<tr><td align="center" height="100" colspan="3"><input type="button" value="CLOSE" onclick="window.close();" />
</td></tr>
						<tr>
							<td height="20"></td>
							<td width="10"></td>
							<td></td>
						</tr>
					</table>
				</td>
			</tr>
			</table>

		</div>
		<div class="style2" style="background:url('images/foot.gif');height:50px;"></div>
	</div>
</div>
</body>
</html>
<?php
		} // end if
	} //end while
} //end if

		
		
			
			
			
?>
