<?php
include("../../../config/config.php");

$astypeID = pg_escape_string($_GET["astypeID"]);
$historyType = pg_escape_string($_GET["historyType"]);

if($historyType == "add")
{
	$whereOther = "AND a.\"add_or_edit\" = '0'";
	$historyText = "เพิ่มรายละเอียด";
	$colspan = "11";
}
elseif($historyType == "edit")
{
	$whereOther = "AND a.\"add_or_edit\" > '0'";
	$historyText = "แก้ไขรายละเอียด";
	$colspan = "11";
}
else
{
	$colspan = "12";
	$historyText = "รายละเอียด";
}

// ดึงข้อมูลประเภทสินทรัพย์มาแสดง
$Sql_Get_Asset_Type = 	"
							SELECT
									\"astypeName\"
							FROM 
									\"thcap_asset_biz_astype\" 
							WHERE 
									\"astypeID\" = $astypeID;		
						";
$Result = pg_query($Sql_Get_Asset_Type);
$astypeName = pg_fetch_result($Result,0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>ประวัติการอนุมัติ<?php echo "$historyText $astypeName "; ?>ทั้งหมด</title>
	<link type="text/css" rel="stylesheet" href="../act.css"></link>
	<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
	<script type="text/javascript">
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
	</script>
</head>
<body>
	<center>
		<h1>ประวัติการอนุมัติ<?php echo "$historyText $astypeName "; ?>ทั้งหมด</h1>
		<table width="100%" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">
			<tr bgcolor="#FFFFFF">
				<td colspan="<?php echo $colspan; ?>" align="right">
					<input type="button" value="ปิด" style="cursor:pointer; width:70px;"  onClick="window.close();" />
				</td>
			</tr>
			<tr bgcolor="#CDC5BF">
				<th>ยี่ห้อ</th>
				<th>รุ่น</th>
				<th>เลขตัวถัง </th>
				<th>เลขเครื่อง </th>
				<th>ผู้ทำรายการ </th>
				<th>วันเวลาที่ทำรายการ </th>
				<th>ผู้อนุมัติ </th>
				<th>วันเวลาที่อนุมัติ</th>
				<?php if($historyType == ""){echo "<th>การกระทำ</th>";} ?>
				<th>ดู</th>
				<th>สถานะ</th>
				<th>เหตุผล</th>
			</tr>
			<?php
				$i = 0;
				$Sql = 	"
							SELECT
								a.\"ascenID\",
								d.\"brand_name\",
								e.\"model_name\",
								b.\"frame_no\",
								b.\"engine_no\",
								f.\"fullname\" AS \"doerName\",
								a.\"doerDate\",
								g.\"fullname\" AS \"appName\",
								a.\"appDate\",
								a.\"statusapp\",
								a.\"add_or_edit\"
							FROM
								\"thcap_asset_biz_detail_central\" a
							LEFT JOIN
								\"thcap_asset_biz_detail_car_temp\" b ON a.\"ascenID\" = b.\"ascenID\"
							LEFT JOIN
								\"thcap_asset_biz_detail\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
							LEFT JOIN
								\"thcap_asset_biz_brand\" d ON c.\"brand\" = d.\"brandID\"
							LEFT JOIN
								\"thcap_asset_biz_model\" e ON c.\"model\" = e.\"modelID\"
							LEFT JOIN
								\"Vfuser\" f ON a.\"doerID\" = f.\"id_user\"
							LEFT JOIN
								\"Vfuser\" g ON a.\"appID\" = g.\"id_user\"
							WHERE
								a.\"statusapp\" IN('1', '2') AND
								a.\"assetDetailID\" IN(select \"assetDetailID\" from \"thcap_asset_biz_detail\" where \"astypeID\" = '$astypeID')
								$whereOther
							ORDER BY
								\"appDate\" DESC
						";		
				$qry_motorcycle = pg_query($Sql);
				$rows = pg_num_rows($qry_motorcycle);
				
				if($rows){
					while($result = pg_fetch_array($qry_motorcycle)){
						$assetDetailID = $result["assetDetailID"];											
						$temp_car_ID = $result["temp_car_ID"];
						$statusapp = $result["statusapp"];
						$fullnameuserdoer = $result["fulldoer"];
						$fullnameuserapp = $result["fullapp"];
						$ascenID = $result["ascenID"];
						if($statusapp == '1'){
							$txtstatus = 'อนุมัติ';
						}else{
							$txtstatus = 'ไม่อนุมัติ';
							$note = "<img style=\"cursor:pointer;\" onclick=\"popU('frm_note_reprint.php?ascenID=$ascenID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=250')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\">";
						}
						
						if($historyType == "")
						{
							if($result["add_or_edit"] == '0'){
								$statustxt = "<td align=\"center\"><font color=\"red\">เพิ่มข้อมูล</font></td>";
							}else{
								$statustxt = "<td align=\"center\"><font color=\"red\">แก้ไขครั้งที่ ".$result["add_or_edit"]."</font></td>";
							}
						}
						else
						{
							$statustxt = "";
						}
						
						$i++;
						if($i%2==0){
							echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
						}else{
							echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
						}	
						echo "
								<td align=\"center\">".$result["brand_name"]."</td>
								<td align=\"center\">".$result["model_name"]."</td>
								<td align=\"center\">".$result["frame_no"]."</td>
								<td align=\"center\">".$result["engine_no"]."</td>
								<td align=\"center\">".$result["doerName"]."</td>	
								<td align=\"center\">".$result["doerDate"]."</td>
								<td align=\"center\">".$result["appName"]."</td>	
								<td align=\"center\">".$result["appDate"]."</td>
								$statustxt
								<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('view_asset_for_sales_car.php?ascenID=$ascenID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
								<td align=\"center\">".$txtstatus."</td>
								<td align=\"center\">$note</td>
							
							</tr>
							";	
						unset($note);
						unset($txtstatus);
					}
					echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"$colspan\">รวม ".number_format($rows,0)." รายการ</td></tr>";
				}else{
					echo "<tr><td colspan=\"$colspan\" align=\"center\">*** ไม่มีข้อมูล ***</td></tr>";
				}
			?>
		</table>
	</center>
</body>
</html>