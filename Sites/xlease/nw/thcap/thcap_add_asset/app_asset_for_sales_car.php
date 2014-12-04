<?php 
	include("../../../config/config.php");
	$astype = pg_escape_string($_GET["astype"]);
	
	// ดึงชื่อประเภทสินทรัพย์
   	$Sql_Get_Type = "
						SELECT
								\"astypeName\"
						FROM
								\"thcap_asset_biz_astype\" 
						WHERE
								(\"astypeID\" = $astype)					
				";
	$Result = pg_query($Sql_Get_Type);
	$Data = pg_fetch_array($Result);
	$Txt_Asset_Type = $Data[0]; 			
	
	// ดึงรายการประเภทสินทรัพย์ที่รออนุมัติ	

		$i = 0;
		
			
		$Sql = " 
					SELECT 
							\"thcap_asset_biz_brand\".\"brand_name\" as  \"brand\",
							\"thcap_asset_biz_model\".\"model_name\" as \"model\",
							\"thcap_asset_biz_detail_car_temp\".\"frame_no\" as \"frameno\",
							\"thcap_asset_biz_detail_car_temp\".\"engine_no\" as \"engine_no\",
							\"Vfuser\".\"fullname\",
							\"thcap_asset_biz_detail_central\".\"doerDate\",
							\"thcap_asset_biz_detail_central\".\"add_or_edit\",
							\"thcap_asset_biz_detail\".\"secondaryID\",
							\"thcap_asset_biz_detail_central\".\"ascenID\",
							\"thcap_asset_biz_detail_central\".\"assetDetailID\"
					FROM  
							\"thcap_asset_biz_detail_central\",
							\"thcap_asset_biz_detail_car_temp\",
							\"thcap_asset_biz_detail\",
							\"thcap_asset_biz_model\",
							\"thcap_asset_biz_brand\",
							\"Vfuser\"
					WHERE 
							(\"thcap_asset_biz_detail_central\".\"statusapp\" = 0) AND
      						(\"thcap_asset_biz_detail_central\".\"ascenID\" = \"thcap_asset_biz_detail_car_temp\".\"ascenID\") and
      						(\"thcap_asset_biz_detail_central\".\"assetDetailID\" = \"thcap_asset_biz_detail\".\"assetDetailID\") and 
      						(\"thcap_asset_biz_detail\".\"model\" = thcap_asset_biz_model.\"modelID\")  and
      						(\"thcap_asset_biz_detail\".\"brand\" =  \"thcap_asset_biz_brand\".\"brandID\") and
      						(\"thcap_asset_biz_detail_central\".\"doerID\" = \"Vfuser\".\"id_user\") and
      						(\"thcap_asset_biz_detail\".\"astypeID\" = $astype)
		
				";	
				
		
		$qry_motorcycle = pg_query($Sql);
		$rows = pg_num_rows($qry_motorcycle);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) อนุมัติรายละเอียดสินทรัพย์สำหรับเช่า-ขาย</title>
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body >
<center>
	<div style="padding-top:30px;"></div>
	<fieldset style="width:60%;">
		<legend><b>รายการรออนุมัติ <?php echo $Txt_Asset_Type; ?></b></legend>
				<table width="100%" frame="box" bgcolor="#FFFAFA">
					<tr>
						<td>
							<table width="100%" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">
								<tr bgcolor="#CDC5BF">
									<th >ยี่ห้อ</th>
									<th >รุ่น</th>
									<th >เลขตัวถัง </th>
									<th >เลขเครื่อง </th>
									<th >ผู้ทำรายการ </th>
									<th >วันเวลาที่ทำรายการ </th>
									<th >การกระทำ</th>
									<th >ตรวจสอบ</th>
								</tr>
								
								<?php
							
									
									
									if($rows){
										while($result = pg_fetch_array($qry_motorcycle)){
											$assetDetailID = $result["assetDetailID"];											
											$temp_10_ID = $result["temp_10_ID"];
											$fullnameuserdoer = $result["fullname"];
											$ascenID = $result["ascenID"];
											
											IF($result["add_or_edit"] == '0'){
												$statustxt = 'เพิ่มข้อมูล';
											}else{
												$statustxt = 'แก้ไขครั้งที่ '.$result["add_or_edit"];
											}
											
											$i++;
											if($i%2==0){
												echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
											}else{
												echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
											}	
											echo "
													<td align=\"center\">".$result["brand"]."</td>
													<td align=\"center\">".$result["model"]."</td>
													<td align=\"center\">".$result["frameno"]."</td>
													<td align=\"center\">".$result["engine_no"]."</td>
													<td align=\"center\">".$result["fullname"]."</td>	
													<td align=\"center\">".$result["doerDate"]."</td>	
													<td align=\"center\"><font color=\"red\">".$statustxt."</font></td>		
													<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('add_asset_for_sales_car_Approve_In.php?ascenID=$ascenID&readonly=t&appv=t&assetDetailID=$assetDetailID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
												</tr>
												";	
											
										}
										echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"8\">รวม $rows รายการ</td></tr>";		
									}else{
										echo "<tr><td colspan=\"8\" align=\"center\">*** ไม่มีข้อมูล ***</td></tr>";
									}
								?>
							</table>
						</td>
					</tr>
				</table>	
			</fieldset>
<div style="padding-top:80px;"></div>

		<fieldset style="width:60%;">
		<legend><b>ประวัติรายการอนุมัติ <?php echo $Txt_Asset_Type; ?> 30 รายการล่าสุด  (<font style="cursor:pointer;" onClick="popU('frm_history_car.php?astypeID=<?php echo $astype; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=600')"><u>ทั้งหมด</u></font>)</b></legend>
				<table width="100%" frame="box" bgcolor="#FFFAFA">
					<tr>
						<td>
							<table width="100%" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">
								<tr bgcolor="#CDC5BF">
									<th >ยี่ห้อ</th>
									<th >รุ่น</th>
									<th >เลขตัวถัง </th>
									<th >เลขเครื่อง </th>
									<th >ผู้ทำรายการ </th>
									<th >วันเวลาที่ทำรายการ </th>
									<th >ผู้อนุมัติ </th>
									<th >วันเวลาที่อนุมัติ</th>
									<th >การกระทำ</th>
									<th >ดู</th>									
									<th >สถานะ</th>
									<th >เหตุผล</th>
								</tr>
								<?php
									// ดึงรายการสินทรัพย์ที่ผ่านขั้นตอนพิจารณา อนุมัติ
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
													a.\"assetDetailID\" IN(select \"assetDetailID\" from \"thcap_asset_biz_detail\" where \"astypeID\" = '$astype')
												ORDER BY
													\"appDate\" DESC
												LIMIT
													30
											";
											
									
									$qry_motorcycle = pg_query($Sql);
									$rows = pg_num_rows($qry_motorcycle);
									
									if($rows){
										while($result = pg_fetch_array($qry_motorcycle)){
											$assetDetailID = $result["assetDetailID"];											
											$temp_10_ID = $result["temp_10_ID"];
											$statusapp = $result["statusapp"];
											$fullnameuserdoer = $result["fulldoer"];
											$ascenID = $result["ascenID"];
											if($statusapp == '1'){
												$txtstatus = 'อนุมัติ';
											}else{
												$txtstatus = 'ไม่อนุมัติ';
												$note = "<img style=\"cursor:pointer;\" onclick=\"popU('frm_note_reprint.php?ascenID=$ascenID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=250')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\">";
											}
											IF($result["add_or_edit"] == '0'){
												$statustxt1 = 'เพิ่มข้อมูล';
											}else{
												$statustxt1 = 'แก้ไขครั้งที่ '.$result["add_or_edit"];
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
													<td align=\"center\"><font color=\"red\">".$statustxt1."</font></td>
													<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('view_asset_for_sales_car.php?ascenID=$ascenID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
													<td align=\"center\">$txtstatus</td>
													<td align=\"center\">$note</td>
												
												</tr>
												";	
											unset($note);
											unset($txtstatus);
										}
										echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"12\">รวม $rows รายการ</td></tr>";		
									}else{
										echo "<tr><td colspan=\"12\" align=\"center\">*** ไม่มีข้อมูล ***</td></tr>";
									}
								?>
							</table>
						</td>
					</tr>	
				</table>	
			</fieldset>	

</center>				
</body>
</html>