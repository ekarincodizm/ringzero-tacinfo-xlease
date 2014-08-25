<?php
session_start();
include("../../config/config.php");	
$txtsearch=$_POST["txtsearch"];	 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="document.getElementById('number_running').focus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+แก้ไขการเชื่อมโยงหลักทรัพย์ค้ำประกัน+</h1>
	</div>

	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
		<div align="right" style="padding:15px"><span style="cursor:pointer;" onclick="window.location='frm_IndexLink.php'"><u><--ย้อนกลับ</u></span></div>
		<fieldset><legend><B>ค้นหา</B></legend>
		<form name="frm_edit" method="post" action="frm_IndexLinkEdit.php">
			<div style="padding:20px;"> 
			<table width="100%" border="0" cellpadding="1" cellspacing="1" style="font-weight:bold;" align="center">
			<tr height="30" bgcolor="#FFFFFF">
				<td align="center">ค้นหาจาก รหัสเชื่อมโยง,หลักทรัพย์,เลขที่สัญญา : <input type="text" name="txtsearch" id="txtsearch" size="30" value="<?php echo $txtsearch;?>"><input type="submit" value="ค้นหา"></td>
			</tr>
			</table>
			</div>
		</form>	
		</fieldset><br>
		<table width="800" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE">
			<tr bgcolor="#E8E8E8" align="center">
				<th width="120">รหัสเชื่อมโยง</th>
				<th width="300">เลขที่โฉนด</th>
				<th>เลขที่สัญญา</th>
				<th>สถานะการอนุมัติ</th>
				<th>แก้ไข</th>
			</tr>
			<?php
				
				if($txtsearch==""){
					$qry_search=pg_query("select \"numid\" as numid2 from \"nw_linksecur\" order by \"numid\"");
				}else{
					$qry_search=pg_query("select distinct(a.\"numid\") as numid2 from \"nw_linksecur\" a
					left join nw_linknumsecur b on a.\"numid\"=b.\"numid\"
					left join \"nw_linkIDNO\" c on a.\"numid\"=c.\"numid\"
					left join \"nw_securities\" d on b.\"securID\"=d.\"securID\"
					where CAST(a.\"numid\" AS character varying) like '%$txtsearch%' or c.\"IDNO\" like '%$txtsearch%' or d.\"numDeed\" like '%$txtsearch%' order by a.\"numid\"");
				}
				$numrow=pg_num_rows($qry_search);
				//ดึงข้อมูลขึ้นมาแสดงจาก numid ที่ได้
				while($res_search=pg_fetch_array($qry_search)){
					$numid=$res_search["numid2"];
					
					//แสดงข้อมูลโฉนดที่ดิน
					$qry_numdeed=pg_query("select b.\"numDeed\" from nw_linknumsecur a
					left join \"nw_securities\" b on a.\"securID\"=b.\"securID\"
					left join \"nw_linkIDNO\" c on a.\"numid\"=c.\"numid\"
					where a.\"numid\"='$numid' ");
					
					//echo $qry_numdeed;
					$i=1;
					$numdeed="";
					$numdeedold="";
					while($res_numdeed=pg_fetch_array($qry_numdeed)){
						$numdeed2=$res_numdeed["numDeed"];
						if($i==1){
							$numdeed=$numdeed2;
						}else{
							if($numdeedold==$numdeed2){
								$numdeed=$numdeed;
							}else{
								$numdeed=$numdeed.", ".$numdeed2;
							}
						}
						$i++;
						$numdeedold=$numdeed2;
					}
					
					//แสดงข้อมูลเลขที่สัญญา
					$qry_IDNO=pg_query("select \"IDNO\" from \"nw_linkIDNO\" where \"numid\"='$numid'");
					$i=1;
					$IDNO="";
					$IDNOOLD="";
					while($res_IDNO=pg_fetch_array($qry_IDNO)){
						$IDNO2=$res_IDNO["IDNO"];
						if($i==1){
							$IDNO=$IDNO2;
						}else{
							if($IDNOOLD==$IDNO2){
								$IDNO=$IDNO;
							}else{
								$IDNO=$IDNO.", ".$IDNO2;
							}
						}
						$i++;
						$IDNOOLD=$IDNO2;
					}
					
					//ตรวจสอบสถานะการอนุมัติ
					$qry_check=pg_query("select * from \"temp_linksecur\" where \"number_running\"='$numid' order by \"edittime\" DESC limit(1)");
					$res_check=pg_fetch_array($qry_check);
					$statusApp=$res_check["statusApp"];
					if($statusApp==2){
						$txtstatus="กำลังรออนุมัติแก้ไข";
					}else{
						$txtstatus="-";
					}
					
					echo "<tr bgcolor=#FFFFFF height=25>";
					echo "<td align=center>$numid</td>";
					echo "<td>$numdeed</td>";
					echo "<td>$IDNO</td>";
					echo "<td align=center>$txtstatus</td>";
					if($statusApp==2){
						echo "<td align=center width=50><image src=\"images/edit2.png\" width=16 height=16></td>";
					}else{
						echo "<td align=center width=50><image src=\"images/edit.png\" width=16 height=16 style=\"cursor:pointer;\" onclick=\"window.location='frm_LinkEdit.php?numid=$numid'\"></td>";
					}
					echo "</tr>";
				}
				if($numrow==0){
					echo "<tr><td colspan=5 height=50 align=center bgcolor=#FFFFFF>--ไม่พบข้อมูลการเชื่อมโยง--</td></tr>";
				}
				
			?>
		</table>
	</div>
</div>
</body>
</html>
