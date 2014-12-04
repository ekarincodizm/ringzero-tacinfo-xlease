<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติเพิ่ม-แก้ไขประกันภัย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#contractID").autocomplete({
				source: "s_contractID_car.php",
				minLength:1,
				delay:800
			});
		});
		
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
	</script>
 
</head>
<body>
	<div style="text-align:center;"><h2>(THCAP) อนุมัติเพิ่ม-แก้ไขประกันภัย</h2></div>
	<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td align="center">
				<fieldset><legend><B>รายการที่รออนุมัติ</B></legend>
					<table width="100%" valign="middle" bgcolor="#CCCCCC" align="center">
						<tr valign="middle" bgcolor="#79BCFF">
							<th>รายการ</th>
							<th>ประเภทการทำรายการ</th>
							<th>เลขที่สัญญา</th>
							<th>ประเภทประกัน</th>
							<th>ประเภทรถ</th>
							<th>ยี่ห้อ</th>
							<th>รุ่น</th>
							<th>ทะเบียนรถ</th>
							<th>ผู้ทำรายการ</th>
							<th>วันเวลาที่ทำรายการ</th>
							<th>รายละเอียด</th>
						</tr>
						<?php
						$qry_appv = pg_query("
												SELECT
													a.\"requestForceID\" AS \"requestID\",
													a.\"contractID\",
													d.\"astypeName\",
													e.\"brand_name\",
													f.\"model_name\",
													CASE WHEN c.\"astypeID\" = '10' THEN g.\"regiser_no\" ELSE h.\"regiser_no\" END AS \"regiser_no\",
													b.\"fullname\" AS \"doerName\",
													a.\"doerStamp\",
													a.\"editTime\",
													'Force' AS \"insureType\"
												FROM
													insure.\"thcap_InsureForce_request\" a
												LEFT JOIN
													\"Vfuser\" b ON a.\"doerID\" = b.\"id_user\"
												LEFT JOIN
													\"thcap_asset_biz_detail\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
												LEFT JOIN
													\"thcap_asset_biz_astype\" d ON c.\"astypeID\" = d.\"astypeID\"
												LEFT JOIN
													\"thcap_asset_biz_brand\" e ON c.\"brand\" = e.\"brandID\"
												LEFT JOIN
													\"thcap_asset_biz_model\" f ON c.\"model\" = f.\"modelID\"
												LEFT JOIN
													\"thcap_asset_biz_detail_10\" g ON c.\"assetDetailID\" = g.\"assetDetailID\"
												LEFT JOIN
													\"thcap_asset_biz_detail_car\" h ON c.\"assetDetailID\" = h.\"assetDetailID\"
												WHERE
													\"appvStatus\" = '9'
												
												UNION

												SELECT
													a.\"requestUnforceID\" AS \"requestID\",
													a.\"contractID\",
													d.\"astypeName\",
													e.\"brand_name\",
													f.\"model_name\",
													CASE WHEN c.\"astypeID\" = '10' THEN g.\"regiser_no\" ELSE h.\"regiser_no\" END AS \"regiser_no\",
													b.\"fullname\" AS \"doerName\",
													a.\"doerStamp\",
													a.\"editTime\",
													'Unforce' AS \"insureType\"
												FROM
													insure.\"thcap_InsureUnforce_request\" a
												LEFT JOIN
													\"Vfuser\" b ON a.\"doerID\" = b.\"id_user\"
												LEFT JOIN
													\"thcap_asset_biz_detail\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
												LEFT JOIN
													\"thcap_asset_biz_astype\" d ON c.\"astypeID\" = d.\"astypeID\"
												LEFT JOIN
													\"thcap_asset_biz_brand\" e ON c.\"brand\" = e.\"brandID\"
												LEFT JOIN
													\"thcap_asset_biz_model\" f ON c.\"model\" = f.\"modelID\"
												LEFT JOIN
													\"thcap_asset_biz_detail_10\" g ON c.\"assetDetailID\" = g.\"assetDetailID\"
												LEFT JOIN
													\"thcap_asset_biz_detail_car\" h ON c.\"assetDetailID\" = h.\"assetDetailID\"
												WHERE
													\"appvStatus\" = '9'

												ORDER BY
													\"doerStamp\"
											");
						$i = 0;
						while($res_appv = pg_fetch_array($qry_appv))
						{
							$i++;
							$requestID = $res_appv["requestID"];
							$contractID = $res_appv["contractID"];
							$astypeName = $res_appv["astypeName"];
							$brand_name = $res_appv["brand_name"];
							$model_name = $res_appv["model_name"];
							$regiser_no = $res_appv["regiser_no"];
							$doerName = $res_appv["doerName"];
							$doerStamp = $res_appv["doerStamp"];
							$editTime = $res_appv["editTime"];
							$insureType = $res_appv["insureType"];
							
							if($editTime == 0)
							{
								$transactionType = "เพิ่ม";
							}
							elseif($editTime > 0)
							{
								$transactionType = "แก้ไข";
							}
							else
							{
								$transactionType = "";
							}
							
							if($insureType == "Force")
							{
								$insureName = "ประกันภัย ภาคบังคับ (พรบ.)";
								$pathPopup = "popup_force_approve.php";
							}
							elseif($insureType == "Unforce")
							{
								$insureName = "ประกันภัย ภาคสมัครใจ";
								$pathPopup = "popup_unforce_approve.php";
							}
							else
							{
								$insureName = "";
								$pathPopup = "";
							}
							
							if($i%2==0){
								echo "<tr class=\"odd\" align=center>";
							}else{
								echo "<tr class=\"even\" align=center>";
							}
							
							echo "<td align=\"center\">$i</td>";
							echo "<td align=\"center\">$transactionType</td>";
							echo "<td align=\"center\"><font color=\"blue\" style=\"cursor:pointer;\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')\"><u>$contractID</u></font></td>";
							echo "<td align=\"center\">$insureName</td>";
							echo "<td align=\"center\">$astypeName</td>";
							echo "<td align=\"center\">$brand_name</td>";
							echo "<td align=\"center\">$model_name</td>";
							echo "<td align=\"center\">$regiser_no</td>";
							echo "<td align=\"left\">$doerName</td>";
							echo "<td align=\"center\">$doerStamp</td>";
							echo "<td align=\"center\"><img src=\"../thcap/images/detail.gif\" height=\"19\" width=\"19\" style=\"cursor:pointer;\" onClick=\"javascript:popU('$pathPopup?requestID=$requestID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=650')\"></td>";
							
							echo "</tr>";
						}
						
						if($i == 0)
						{
							echo "<tr bgcolor=\"#FFFFFF\">";
							echo "<td align=\"center\" colspan=\"11\">--ไม่พบรายการรออนุมัติ--</td>";
							echo "</tr>";
						}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td align="center">
				<br/><br/>
				<div><?php include("frm_history_limit.php"); ?></div>
			</td>
		</tr>
	</table>
</body>
</html>