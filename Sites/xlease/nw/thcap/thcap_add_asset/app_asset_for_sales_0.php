<?php 
include("../../../config/config.php");
$astype = pg_escape_string($_GET["astype"]);

//-=============================================================
//	อนุมัติการเพิ่มรายละเอียดของสินค้าประเภทรถจักรยายนต์
//-=============================================================
		$i = 0; 
		$Str_Qry = 	"
						SELECT 
  								thcap_asset_biz_brand.brand_name,
  								thcap_asset_biz_model.model_name,
  								thcap_asset_biz_detail.\"secondaryID\",	
  								thcap_asset_biz_detail.\"productCode\",	
  								thcap_asset_biz_detail_central.\"doerID\",
  								thcap_asset_biz_detail_central.add_or_edit,
  								public.\"Vfuser\".\"fullname\",
  								thcap_asset_biz_detail_central.\"doerDate\",
  								thcap_asset_biz_detail.\"assetDetailID\" 
  						FROM 
  								public.thcap_asset_biz_detail, 
  								public.thcap_asset_biz_model, 
  								public.thcap_asset_biz_brand, 
  								public.thcap_asset_biz_astype, 
  								public.thcap_asset_biz_detail_car_temp, 
								public.thcap_asset_biz_detail_central,
  								public.\"Vfuser\"
						WHERE 
  								thcap_asset_biz_detail.brand = thcap_asset_biz_brand.\"brandID\" AND
  								thcap_asset_biz_model.\"modelID\" = thcap_asset_biz_detail.model AND
  								thcap_asset_biz_astype.\"astypeID\" = thcap_asset_biz_detail.\"astypeID\" AND
  								thcap_asset_biz_detail_central.\"ascenID\" = thcap_asset_biz_detail_car_temp.\"ascenID\" AND
  								thcap_asset_biz_detail_central.\"assetDetailID\" = thcap_asset_biz_detail.\"assetDetailID\" AND
  								thcap_asset_biz_detail_central.statusapp = '0' AND
  								thcap_asset_biz_detail_central.\"doerID\" = \"Vfuser\".\"id_user\";

					
					";
					
		// echo $Str_Qry;			
		$qry_motorcycle = pg_query($Str_Qry);
								  
								  
		$rows = pg_num_rows($qry_motorcycle);
		// echo 'no. of row Is..'.$rows;
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
		<legend><b>รายการรออนุมัติ (รถยนต์)</b></legend>
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
									<th >การกระทำ</th>
									<th >ตรวจสอบ</th>
								</tr>
								
								<?php
							
									
									
									if($rows){
										while($result = pg_fetch_array($qry_motorcycle)){
											$assetDetailID = $result["assetDetailID"];											
											$temp_10_ID = $result["temp_10_ID"];
											$fullnameuserdoer = $result["fullname"];
											$ascenID = $result["assetDetailID"];
											
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
													<td align=\"center\">".$result["brand_name"]."</td>
													<td align=\"center\">".$result["model_name"]."</td>
													<td align=\"center\">".$result["secondaryID"]."</td>
													<td align=\"center\">".$result["productCode"]."</td>
													<td align=\"center\">$fullnameuserdoer</td>	
													<td align=\"center\">".$result["doerDate"]."</td>	
													<td align=\"center\"><font color=\"red\">".$statustxt."</font></td>		
													<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('add_asset_for_sales_car_Approve_In.php?ascenID=$ascenID&readonly=t&appv=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
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
		<legend><b>ประวัติรายการอนุมัติ (รถยนต์)</b></legend>
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
									<th >การกระทำ</th>
									<th >ดู</th>									
									<th >สถานะ</th>
									<th >เหตุผล</th>
								</tr>
								<?php
									$i = 0; 
									$Sql_Get_Approve_Car = 	"
																SELECT  
																		thcap_asset_biz_detail_central.\"ascenID\", 
  																		thcap_asset_biz_brand.brand_name,
  																		thcap_asset_biz_model.model_name,
  																		thcap_asset_biz_detail.\"secondaryID\",	
  																		thcap_asset_biz_detail.\"productCode\",	
  														  				\"Vfuser\".\"fullname\",
  																		thcap_asset_biz_detail_central.\"appID\",
  																		thcap_asset_biz_detail_central.\"appDate\",
  																		thcap_asset_biz_detail_central.\"doerDate\",
  																		thcap_asset_biz_detail.\"assetDetailID\",
  																		thcap_asset_biz_detail_central.statusapp,
  																		thcap_asset_biz_detail_central.add_or_edit 
																FROM 
																		thcap_asset_biz_detail, 
  																		thcap_asset_biz_model, 
  																		thcap_asset_biz_brand, 
  																		thcap_asset_biz_astype, 
  																		thcap_asset_biz_detail_car_temp, 
																		thcap_asset_biz_detail_central,
  																		\"Vfuser\"
																WHERE 
																		thcap_asset_biz_detail.brand = thcap_asset_biz_brand.\"brandID\" AND
  																		thcap_asset_biz_model.\"modelID\" = thcap_asset_biz_detail.model AND
  																		thcap_asset_biz_astype.\"astypeID\" = thcap_asset_biz_detail.\"astypeID\" AND
  																		thcap_asset_biz_detail_central.\"ascenID\" = thcap_asset_biz_detail_car_temp.\"ascenID\" AND
  																		thcap_asset_biz_detail_central.\"assetDetailID\" = thcap_asset_biz_detail.\"assetDetailID\" AND
  																		thcap_asset_biz_detail_central.\"doerID\" = \"Vfuser\".\"id_user\";
																
															";
																
									$qry_motorcycle = pg_query($Sql_Get_Approve_Car);
									$rows = pg_num_rows($qry_motorcycle);
									
									if($rows){
										while($result = pg_fetch_array($qry_motorcycle)){
											
											$assetDetailID = $result["assetDetailID"];											
											$temp_10_ID = $result["temp_10_ID"];
											$statusapp = $result["statusapp"];
											$fullnameuserdoer = $result["fullname"];
											$Approve_ID = $result["appID"];  
											$Str_Get_App_Name = "
																	SELECT
																			fullname
																	FROM 
																			\"Vfuser\"
																	WHERE 
																			\"id_user\" = '".$Approve_ID."' 	
																";
																
											$Result_App_Name = pg_query($Str_Get_App_Name);
											$App_Name = pg_fetch_array($Result_App_Name);
											
											$fullnameuserapp = $App_Name["fullname"];
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
													<td align=\"center\">".$result["brand_name"].$assetDetailID."</td>
													<td align=\"center\">".$result["model_name"]."</td>
													<td align=\"center\">".$result["secondaryID"]."</td>
													<td align=\"center\">".$result["productCode"]."</td>
													<td align=\"center\">$fullnameuserdoer</td>	
													<td align=\"center\">".$result["doerDate"]."</td>
													<td align=\"center\">$fullnameuserapp</td>	
													<td align=\"center\">".$result["appDate"]."</td>
													<td align=\"center\"><font color=\"red\">".$statustxt1."</font></td>
													<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('add_asset_for_sales_car_view.php?ascenID=$ascenID&readonly=t&assetdetailID=$assetDetailID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
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