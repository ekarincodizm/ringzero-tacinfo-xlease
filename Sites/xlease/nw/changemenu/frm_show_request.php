<?php
include("../../config/config.php");
$av_iduser=$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}


$id_user=$_GET["id_user"];
$status=$_GET["status"];
$add_date=$_GET["add_date"];



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
<title>แสดงรายการเปลี่ยนแปลงสิทธิ์การทำงาน</title>
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

	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:0px; padding-right:10px;text-align:center;"><h3>รายการเพิ่มและเปลี่ยนแปลง</h3><hr /></div>
		<div id="contentpage" style="height:auto; padding-left:10px; padding-right:10px;">
		<table width="780" border="0" style="background-color:#EEEDCC">
		<tr><td colspan="4"><b>ชื่อพนักงาน :</b> <?php echo $fullname; ?>  &nbsp;&nbsp;&nbsp;<b>ฝ่าย :</b> <?php echo $dep_name;?> &nbsp;&nbsp;&nbsp;<b>แผนก :</b> <?php echo $fdep_name;?></td></tr>		
		<tr style="background-color:#D0DCA0" align="left">
			<th height="25">id menu</th>
			<th>name menu</th>
			<?php 
				if($status==0){
					echo "<th>ความต้องการ</th>";
				}
			?>
			<th>สถานะเมนูที่ต้องการ</th>
			<th>เหตุผลที่เปลี่ยนแปลงเมนู</th>
		</tr>
		<?php 
		$query=pg_query("select distinct(\"id_menu\") as idmenu from \"nw_changemenu\" where \"id_user\"='$id_user' and \"statusApprove\"='$status' and \"statusOKapprove\"='FALSE' and \"add_date\"='$add_date' ");
		while($resquery=pg_fetch_array($query)){
			$idmenu=$resquery["idmenu"];
			
			$qry_menu=pg_query("select a.\"status\",a.\"result\",a.\"id_menu\",b.\"name_menu\",b.menu_desc from \"nw_changemenu\" a
			left join \"f_menu\" b on a.\"id_menu\"=b.\"id_menu\" 
			where \"id_user\"='$id_user' and \"statusApprove\"='$status' and \"statusOKapprove\"='FALSE' and \"add_date\"='$add_date' and a.\"id_menu\"='$idmenu' order by a.\"changeID\" DESC limit 1");
			$numchge=0;
			if($res_change=pg_fetch_array($qry_menu)){
				$staschange=$res_change["status"];
				$result=$res_change["result"]; if($result=="") $result="-";
				$id_menu=$res_change["id_menu"];
				$menu_desc=$res_change["menu_desc"];
				$queryoldmenu=pg_query("select * from \"f_usermenu\" where \"id_menu\"='$id_menu' and \"id_user\"='$id_user'");
				$numrow_oldmenu=pg_num_rows($queryoldmenu);
				if($numrow_oldmenu == 0){
					$txtwant="เพิ่มรายการ";
				}else{
					$txtwant="เปลี่ยนแปลง";
				}

			if($staschange == 't'){
				$color="#C0FF3E";
			} else{
					$color="#FF7256";
				}
			if($menu_desc==""){
				$textdesc="ไม่มีคำอธิบายเมนู";
			} else {
				$textdesc=$menu_desc;
			}
			?>
			<tr height="25" bgcolor="<?php echo $color; ?>">    
				<td width="85"><?php echo $id_menu; ?></td>
				<td width="220"><?php echo $res_change["name_menu"]; ?></td>
				<?php 
					if($status==0){
						echo "<td align=\"center\">$txtwant</td>";
					}
				?>
				
				<td>
					<?php 
						if($staschange == 't'){
							echo "ใช้งาน";
						}else{
							echo "ระงับใช้งาน";
						}
					?>
				</td>
				<td><input type="text" name="result2" size="50" value="<?php echo $result;?>" readonly></td>
			</tr>
			<tr bgcolor="<?php echo $color; ?>" >
				<td colspan="4"><b>คำอธิบายเมนู: </b><?php echo $textdesc;?></td>
			</tr>
			<?php 
			} //end if
		}//end while
		?>
		<?php //เงือนไขกำหนดการส่งค่าหลังจากกดปุ่ม รับทราบแล้ว
			if($status=='2'){
					$onclick= "process_update.php?id_user=$id_user&method=2&add_date=$add_date";
				}else if($status=='3'){
					$onclick= "process_update.php?id_user=$id_user&method=3&add_date=$add_date";
				}else{
					echo "-";
				}
		?>
		<tr align="center">
			<td colspan="5" style="background-color:#FFECB9;" height="30">
				<input type="button" value="รับทราบ" onclick="window.location.href='<?php echo $onclick ?>';"> <input type="button" value="CLOSE" onclick="window.close();"> 
			</td>
			
			
		</tr>
		</table>
		
	</div>
</div>
</body>
</html>
