<?php
include("../../../config/config.php");
$astype = pg_escape_string($_GET["astype"]);
// ดึงข้อมูลประเภทสินทรัพย์มาแสดง
$Sql_Get_Asset_Type = 	"
							SELECT
									\"astypeName\"
							FROM 
									\"thcap_asset_biz_astype\" 
							WHERE 
									\"astypeID\" = $astype;		
						";
$Result = pg_query($Sql_Get_Asset_Type);
$Data = pg_fetch_array($Result);
$Asset_Type_Name = $Data[0];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) ใส่รายละเอียดสินทรัพย์สำหรับเช่า-ขาย</title>
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
<body>
	<fieldset style="width:70%;">
		<legend><b>เพิ่มข้อมูล <?php echo $Asset_Type_Name; ?></b></legend>
				<table width="100%" frame="box" bgcolor="#FFFAFA">
					<tr>
						<td>
							<table width="100%" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">
								<tr bgcolor="#CDC5BF">
									<th width="20%">ยี่ห้อ</th>
									<th width="20%">รุ่น</th>
									<th width="20%">เลขตัวถัง </th>
									<th width="20%">เลขเครื่อง </th>
									<th width="20%">เพิ่มข้อมูล </th>
								</tr>
								<?php
									$i = 0;
									$qry_motorcycle = pg_query("
																SELECT
																	c.\"brand_name\",
																	b.\"model_name\",
																	a.\"secondaryID\",
																	a.\"productCode\",
																	a.\"assetDetailID\"
																FROM
																	\"thcap_asset_biz_detail\" a
																LEFT JOIN
																	\"thcap_asset_biz_model\" b ON a.\"model\" = b.\"modelID\"
																LEFT JOIN
																	\"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
																WHERE
																	a.\"astypeID\" = '$astype' AND
																	a.\"assetDetailID\" NOT IN (select \"assetDetailID\" from \"thcap_asset_biz_detail_car\")
															  ");			  
									$rows = pg_num_rows($qry_motorcycle);
									
									if($rows)
									{
										while($result = pg_fetch_array($qry_motorcycle))
										{
											$assetDetailID = $result["assetDetailID"];
											
											//ตรวจสอบว่ามีการรอนุมัติอยู่หรือไม่
											$qry_checkwait = pg_query("
																		SELECT \"ascenID\"
																		FROM \"thcap_asset_biz_detail_central\" a
																		WHERE a.\"assetDetailID\" = '$assetDetailID' AND
																			  a.\"statusapp\" = '0'  AND a.\"add_or_edit\" = '0'
																	  ");
											$rowswait = pg_num_rows($qry_checkwait);
											
											
											$i++;
											if($i%2==0){
												echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
											}else{
												echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
											}	
											echo "
													<td align=\"center\">".$result["brand_name"]."</td>
													<td align=\"center\">".$result["model_name"]."</td>
													<td align=\"center\">".$result["productCode"]."</td>
													<td align=\"center\">".$result["secondaryID"]."</td>
											";
											$to_frame_no = $result["productCode"];
											$to_engine_no = $result["secondaryID"];
											if($rowswait > 0)
											{
												$resultdetail = pg_fetch_array($qry_checkwait);
												$ascenID = $resultdetail["ascenID"];
												echo "	
													<td align=\"center\">(รออนุมัติ)<img style=\"cursor:pointer;\" onclick=\"popU('view_asset_for_sales_car.php?ascenID=$ascenID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
												</tr>
												";	
											}
											else
											{
												echo "	
													<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('add_asset_for_sales_car.php?assetdetailID=$assetDetailID&assettypeID=$astype&frameno=$to_frame_no&engineno=$to_engine_no','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/onebit_20.png\" width=\"20px;\" height=\"20px;\"></td>	
												</tr>
												";
											}
										}
										echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"5\">รวม $rows รายการ</td></tr>";		
									}
									else
									{
										echo "<tr><td colspan=\"5\" align=\"center\">*** ไม่มีข้อมูล ***</td></tr>";
									}
								?>
							</table>
						</td>
					</tr>
					</table>		
			</fieldset>
			
		<div style="padding-top:50px;"></div>
		
		<fieldset style="width:80%;">
		<legend><b>ประวัติรายการอนุมัติ  <?php echo $Asset_Type_Name; ?> 30 รายการล่าสุด (<font style="cursor:pointer;" onClick="popU('frm_history_car.php?astypeID=<?php echo $astype; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=600')"><u>ทั้งหมด</u></font>)</b></legend>
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
									<th >ดู</th>
									<th >สถานะ</th>
									<th >เหตุผล</th>
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
													a.\"statusapp\"
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
													a.\"add_or_edit\" = '0' AND
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
													<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('view_asset_for_sales_car.php?ascenID=$ascenID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
													<td align=\"center\">".$txtstatus."</td>
													<td align=\"center\">$note</td>
												
												</tr>
												";	
											unset($note);
											unset($txtstatus);
										}
										echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"11\">รวม $rows รายการ</td></tr>";		
									}else{
										echo "<tr><td colspan=\"11\" align=\"center\">*** ไม่มีข้อมูล ***</td></tr>";
									}
								?>
							</table>
						</td>
					</tr>	
				</table>	
			</fieldset>	
</body>
</html>