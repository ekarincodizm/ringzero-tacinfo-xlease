<?php echo "edit_tb_asset_car.php";
include("../../../config/config.php");
$astype = pg_escape_string($_GET["astype"]); echo $astype;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) แก้ไขรายละเอียดสินทรัพย์สำหรับเช่า-ขาย</title>
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
	<fieldset style="width:60%;">
		<legend><b>แก้ไขข้อมูลรถจักรยานยนต์</b></legend>
				<table width="100%" frame="box" bgcolor="#FFFAFA">
					<tr>
						<td>
							<table width="100%" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">
								<tr bgcolor="#CDC5BF">
									<th >ยี่ห้อ</th>
									<th >รุ่น</th>
									<th >รหัสรุ่น </th>
									<th >เลขเครื่อง </th>
									<th >เลขตัวถัง </th>
									
									<th >ขนาดความจุเครื่องยนต์ CC.</th>
									<th >ปีที่จดทะเบียน </th>
									<th >ทะเบียนรถ </th>
									<th >วันที่จดทะเบียน </th>
									<th >แก้ไข </th>
								</tr>
								<?php
									$i = 0;
									$Sql_Cmd = " 
													SELECT 
															a.\"assetDetailID\" as \"assetDetailID\",
															c.\"brand_name\" as \"brand_name\",
															b.\"model_name\" as \"model_name\",
															e.\"secondaryID\" as \"secondaryID\",
															a.\"engine_no\" as \"engine_no\",
															a.\"frame_no\" as \"frame_no\",
															a.\"EngineCC\" as \"Engine_CC\",
															a.\"year_regis\" as \"year_regis\",
															a.\"regiser_no\" as \"regiser_no\",
															a.\"register_date\" as \"register_date\"
													FROM 
															thcap_asset_biz_detail_car a
																LEFT JOIN \"thcap_asset_biz_detail\" e 
																	ON a.\"assetDetailID\" = e.\"assetDetailID\"
																LEFT JOIN \"thcap_asset_biz_model\" b 
																	ON e.\"model\" = b.\"modelID\"
																LEFT JOIN \"thcap_asset_biz_brand\" c 
																	ON e.\"brand\" = c.\"brandID\"
																LEFT JOIN \"thcap_asset_biz\" f 
																	ON e.\"assetID\"=f.\"assetID\"
													WHERE 	e.\"materialisticStatus\"<>'9' and 
															f.\"ActiveStatus\"<>'0' and
															e.\"astypeID\" = $astype
									
												";
									$qry_motorcycle = pg_query($Sql_Cmd);
									$rows = pg_num_rows($qry_motorcycle);
									
									if($rows){
										while($result = pg_fetch_array($qry_motorcycle)){
											$assetDetailID = $result["assetDetailID"];
			
											
											//ตรวจสอบว่ามีการรอนุมัติอยู่หรือไม่
											$qry_checkwait = pg_query("
																		SELECT *
																		FROM \"thcap_asset_biz_detail_central\" a
																		WHERE a.\"assetDetailID\" = '$assetDetailID' AND
																			  a.\"statusapp\" = '0'  AND a.\"add_or_edit\" > '0'
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
													<td align=\"center\">".$result["secondaryID"]."</td>
													<td align=\"center\">".$result["engine_no"]."</td>
													<td align=\"center\">".$result["frame_no"]."</td>
													
													<td align=\"center\">".$result["Engine_CC"]."</td>
													<td align=\"center\">".$result["year_regis"]."</td>
													<td align=\"center\">".$result["regiser_no"]."</td>
													<td align=\"center\">".$result["register_date"]."</td>
											";
											IF($rowswait > 0){
												$resultdetail = pg_fetch_array($qry_checkwait);
												$ascenID = $resultdetail["ascenID"];
												echo "	
													<td align=\"center\">(รออนุมัติ)<img style=\"cursor:pointer;\" onclick=\"popU('../thcap_add_asset/add_asset_for_sales_car_edit.php?ascenID=$ascenID&readonly=t&method=edit','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
												</tr>
												";	
											}else{
												echo "	
													<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('../thcap_add_asset/add_asset_for_sales_car_edit.php?assetdetailID=$assetDetailID&method=edit','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/onebit_20.png\" width=\"20px;\" height=\"20px;\"></td>	
												</tr>
												";
											}	
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
			
<div style="padding-top:80px;"></div>

		<fieldset style="width:60%;">
		<legend><b>ประวัติรายการอนุมัติ แก้ไขรถจักรยานยนต์ 30 รายการล่าสุด</b></legend>
				<table width="100%" frame="box" bgcolor="#FFFAFA">
					<tr>
						<td>
							<table width="100%" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">
								<tr bgcolor="#CDC5BF">
									<th >ยี่ห้อ</th>
									<th >รุ่น</th>
									<th >รหัสรุ่น </th>
									<th >เลขเครื่อง </th>
									<th >ผู้ทำรายการ </th>
									<th >วันเวลาที่ทำรายการ </th>
									<th >ผู้อนุมัติ </th>
									<th >วันเวลาที่อนุมัติ</th>
									<th >แก้ไขครั้งที่</th>
									<th >ดู</th>
									<th >สถานะ</th>
									<th >เหตุผล</th>
								</tr>
								<?php
									$i = 0;
									$qry_motorcycle = pg_query("
																SELECT a.*,b.*,c.*,d.*,e.\"fullname\" as fulldoer,f.\"fullname\" as fullapp
																FROM thcap_asset_biz_detail a
																LEFT JOIN \"thcap_asset_biz_model\" b ON a.\"model\" = b.\"modelID\"
																LEFT JOIN \"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
																LEFT JOIN (	select * from \"thcap_asset_biz_detail_10_temp\" d1
																			left join \"thcap_asset_biz_detail_central\" d2
																			on d1.\"ascenID\" = d2.\"ascenID\" ) d ON a.\"assetDetailID\" = d.\"assetDetailID\"
																LEFT JOIN \"Vfuser\" e ON d.\"doerID\" = e.\"id_user\"
																LEFT JOIN \"Vfuser\" f ON d.\"appID\" = f.\"id_user\"
																WHERE d.\"statusapp\" != '0' AND a.\"astypeID\" = '$astype'  AND d.\"add_or_edit\" > '0'
																ORDER by d.\"appDate\" DESC
																LIMIT 30
															  ");
									$rows = pg_num_rows($qry_motorcycle);
									
									if($rows){
										while($result = pg_fetch_array($qry_motorcycle)){
											$assetDetailID = $result["assetDetailID"];											
											$temp_10_ID = $result["temp_10_ID"];
											$statusapp = $result["statusapp"];
											$fullnameuserdoer = $result["fulldoer"];
											$fullnameuserapp = $result["fullapp"];
											$ascenID = $result["ascenID"];
											if($statusapp == '1'){
												$txtstatus = 'อนุมัติ';
											}else{
												$txtstatus = 'ไม่อนุมัติ';
												$note = "<img style=\"cursor:pointer;\" onclick=\"popU('../thcap_add_asset/frm_note_reprint.php?ascenID=$ascenID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=250')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\">";
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
													<td align=\"center\">".$result["secondaryID"]."</td>
													<td align=\"center\">".$result["productCode"]."</td>
													<td align=\"center\">$fullnameuserdoer</td>	
													<td align=\"center\">".$result["doerDate"]."</td>
													<td align=\"center\">$fullnameuserapp</td>	
													<td align=\"center\">".$result["appDate"]."</td>
													<td align=\"center\">".$result["add_or_edit"]."</td>													
													<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('../thcap_add_asset/add_asset_for_sales_10.php?ascenID=$ascenID&readonly=t&method=edit','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
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
</body>
</html>