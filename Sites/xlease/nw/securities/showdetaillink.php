<?php
session_start();
include("../../config/config.php");	
$number_running=$_GET["number_running"];
$auto_id=$_GET["auto_id"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>  

<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function confirmappv(no){
if(no=='1'){
	if(confirm('ยืนยันการอนุมัติ!!')){ return true;	}
	else{ return false;}
}
else{
	if(confirm('ยืนยันการไม่อนุมัติ!!')){ return true;	}
	else{ return false;}
	}
}
</script> 
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
}
.style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
}

-->
</style>

<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.style6 {
	color: #FF0000;
	font-weight: bold;
}

.warppage
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

-->
</style>
<!-- InstanceEndEditable -->
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<?php
//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
$qry_check=pg_query("select * from \"temp_linksecur\" where \"auto_id\"='$auto_id' and \"statusApp\" in('0','1')");
$num_check=pg_num_rows($qry_check);
if($num_check > 0){
	$rescheck=pg_fetch_array($qry_check);
	$check_status=trim($rescheck["statusApp"]);
	if($check_status =="1"){
		echo "<div style=\"text-align:center\"><h2>รายการนี้ได้รับการอนุมัติไปแล้ว</h2><br>";
		//echo "<meta http-equiv='refresh' content='2; URL=frm_ApproveLink.php'>";
		echo "<input type=\"submit\" value=\"  ตกลง  \" onclick=\"javascript:RefreshMe();\" /></div>";
	}else if($check_status =="0"){
		echo "<div style=\"text-align:center\"><h2>รายการนี้ไม่ได้รับการอนุมัติ</h2><br>";
		//echo "<meta http-equiv='refresh' content='2; URL=frm_ApproveLink.php'>";
		echo "<input type=\"submit\" value=\"  ตกลง  \" onclick=\"javascript:RefreshMe();\" /></div>";
	}
}else{
?>
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ เปรียบเทียบข้อมูลการเชื่อมโยง +</h1>
	</div>

<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>	
	<?php
	for($i=0;$i<2;$i++){
		if($i==0){
			$qry_sec=pg_query("select * from \"nw_linksecur\" where \"numid\" ='$number_running' ");
			$txt="ข้อมูลเก่า";
		}else{
			$qry_sec=pg_query("select * from \"temp_linksecur\" where \"auto_id\" ='$auto_id' ");
			$txt="ข้อมูลใหม่";
		}

		$res_sec=pg_fetch_array($qry_sec);
		$note=trim($res_sec["note"]);
		$edittime=trim($res_sec["edittime"]);

		//ดึงข้อมูลขึ้นมาอีกรอบเพื่อเปรียบเทียบ
		//ข้อมูลปัจจุบัน
		$qry_sec1=pg_query("select * from \"nw_linksecur\" where \"numid\" ='$number_running'");
		$res_sec1=pg_fetch_array($qry_sec1);
		$edittime1=trim($res_sec1["edittime"]);
		$note1=trim($res_sec1["note"]);	

		//ข้อมูลที่แก้ไข
		$qry_sec2=pg_query("select * from \"temp_linksecur\" where \"auto_id\" ='$auto_id' ");
		$res_sec2=pg_fetch_array($qry_sec2);
		$edittime2=trim($res_sec2["edittime"]);
		$note2=$res_sec2["note"];
	?>
	<td valign="top">
		<?php
		if($i==0){
			$qry_linksec=pg_query("select * from \"nw_linknumsecur\" a
			left join \"nw_securities\" b on a.\"securID\"=b.\"securID\"
			where \"numid\" ='$number_running' ");
		}else{
			$qry_linksec=pg_query("select * from \"temp_linknumsecur\" a
			left join \"nw_securities\" b on a.\"securID\"=b.\"securID\"
			where \"auto_id\" ='$auto_id' ");
		}
		$numlinksec=pg_num_rows($qry_linksec);
		?>
		<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr>
			<td colspan="2"><b>(<?php echo $txt;?>)</b></td>
		</tr>
		<?php
		if($numlinksec>0){
		?>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="100">รหัสเชื่อมโยง : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="number_running" value="<?php echo $number_running?>" readonly="true"></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">หลักทรัพย์ : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table width="100%" border="0" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
				<?php
					//เปรียบเทียบค่าระหว่างอันเก่ากับอันใหม่
					$qry_old=pg_query("select * from \"nw_linknumsecur\" a
						left join \"nw_securities\" b on a.\"securID\"=b.\"securID\"
						where \"numid\" ='$number_running' ");
					$numold=pg_num_rows($qry_old);
					$qry_new=pg_query("select * from \"temp_linknumsecur\" a
						left join \"nw_securities\" b on a.\"securID\"=b.\"securID\"
						where \"auto_id\" ='$auto_id' ");
					$numnew=pg_num_rows($qry_new);
							
					if($numold != $numnew){
						$color="#FFCCCC";
					}else{ //กรณีเท่ากัน
						$x=0;
						while($res_old=pg_fetch_array($qry_old)){
							$secOld=trim($res_old["securID"]);
							while($res_new=pg_fetch_array($qry_new)){
								$secNew=trim($res_new["securID"]);
								if($secOld==$secNew){
									$x=0;
									break;
								}else{
									$x++;
								}
							}	
						}
						if($x>0){
							$color="#FFCCCC";
						}else{
							$color="#E8E8E8";
						}
					}
					
					$j=1;
					while($res_linksec=pg_fetch_array($qry_linksec)){
						$numdeed=$res_linksec["numDeed"];
						$numland=$res_linksec["numLand"];	if($numland=="") $numland="ไม่ระบุ";	
						$securID=$res_linksec["securID"];	
					?>
					<tr bgcolor="<?php echo $color;?>">
						<td>
							<?php echo "$j. เลขที่โฉนด-><a style=\"cursor:pointer;\" onclick=\"popU('showdetail2.php?securID=$securID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\"  ><u>$numdeed</u></a>, เลขที่ดิน -> $numland";?>
						</td>
					</tr>
					<?php 
						$j++;
					}
				?>
				</table>
			</td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">เลขที่สัญญา : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
					<?php
					//เปรียบเทียบค่าระหว่างอันเก่ากับอันใหม่
					$qry_oldidno=pg_query("select * from \"nw_linkIDNO\" where \"numid\" ='$number_running'");
					$numoldidno=pg_num_rows($qry_oldidno);
							
					$qry_newidno=pg_query("select * from \"temp_linkIDNO\" where \"auto_id\" ='$auto_id'");
					$numnewidno=pg_num_rows($qry_newidno);
							
					if($numoldidno != $numnewidno){
						$color="#FFCCCC";
					}else{ //กรณีเท่ากัน
						$x=0;
						while($res_oldidno=pg_fetch_array($qry_oldidno)){
							$idnoOld=trim($res_oldidno["IDNO"]);
							$guaranteeDateOld=trim($res_oldidno["guaranteeDate"]);
									
							while($res_newidno=pg_fetch_array($qry_newidno)){
								$idnoOld=trim($res_newidno["IDNO"]);
								$guaranteeDateNew=trim($res_newidno["guaranteeDate"]);
										
								if($secOld==$secNew and $guaranteeDateOld==$guaranteeDateNew){
									$x=0;
									break;
								}else{
									$x++;
								}
							}	
						}
						if($x>0){
							$color="#FFCCCC";
						}else{
							$color="#E8E8E8";
						}
					}
					
					if($i==0){
						$qry_linkidno=pg_query("select * from \"nw_linkIDNO\" where \"numid\" ='$number_running' ");	
					}else{
						$qry_linkidno=pg_query("select * from \"temp_linkIDNO\" where \"auto_id\" ='$auto_id' ");
					}
					$j=1;
					while($res_linkidno=pg_fetch_array($qry_linkidno)){
					?>
					<tr bgcolor="<?php echo $color;?>">
						<td>
						
							<?php 
								$idnosend = $res_linkidno["IDNO"];
							echo $j.". เลขที่สัญญา-><a style=\"cursor:pointer;\" onclick=\"popU('../../post/frm_viewcuspayment.php?idno_names=$idnosend','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=750')\"  ><u>".$res_linkidno["IDNO"]."</u></a>, วันที่ค้ำประกัน ->".$res_linkidno["guaranteeDate"];?>
						</td>
					</tr>
					<?php
					$j++;
					}
					?>
				</table>
			</td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">หมายเหตุ : </td>
			<td colspan="3" bgcolor="#FFFFFF"><textarea name="note" cols="40" rows="5" readonly="true" <?php if($note1 != $note2){ echo "style=\"background-color:#FFCCCC\"";}?>><?php echo $note;?></textarea></td>
		</tr>
		<?php
		//กรณีไม่พบข้อมูลเก่าแสดงว่าเป็นการเพิ่มข้อมูล
		}else{
			echo "<tr colspan=\"2\"><td bgcolor=\"#FFFFFF\" width=\"400\" height=150 align=center><h2>ไม่พบข้อมูล</h2></td></tr>";
		}
		?>
		</table>
	</td>
	<?php 
	}
	?>
</tr>
<tr>
	<td align="center" height="50" colspan="2">
	<form name="my" method="post" action="process_approvelink.php">
		<input type="hidden" name="auto_id" id="auto_id" value="<?php echo $auto_id; ?>">
		<input type="submit" name="app_ap" value="อนุมัติ" onclick="return confirmappv('1');">&nbsp;
		<input type="submit" name="app_unapp" value="ไม่อนุมัติ" onclick="return confirmappv('0');">&nbsp;
		<input type="button" value="ปิดหน้านี้" onclick="window.close();">		
	</form>
	</td>
</tr>
</table>
</div>
</body>
</html>
<?php
}
?>