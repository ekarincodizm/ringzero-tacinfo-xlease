<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
	<tr bgcolor="#FFFFFF">
		<td colspan="14" align="left" style="font-weight:bold;">ประวัติการอนุมัติ 30 รายการล่าสุด <input type="button" value="แสดงประวัติทั้งหมด" onClick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')" style="cursor:pointer;"></td>
	</tr>
	<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
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
		<th>ผู้อนุมัติ</th>
		<th>วันเวลาที่อนุมัติ</th>
		<th>ผลการอนุมัติ</th>
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
								i.\"fullname\" AS \"appvName\",
								a.\"appvStamp\",
								a.\"appvStatus\",
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
							LEFT JOIN
								\"Vfuser\" i ON a.\"appvID\" = i.\"id_user\"
							WHERE
								\"appvStatus\" IN('0','1')
							
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
								i.\"fullname\" AS \"appvName\",
								a.\"appvStamp\",
								a.\"appvStatus\",
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
							LEFT JOIN
								\"Vfuser\" i ON a.\"appvID\" = i.\"id_user\"
							WHERE
								\"appvStatus\" IN('0','1')

							ORDER BY
								\"appvStamp\" DESC
							LIMIT
								30
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
		$appvName = $res_appv["appvName"];
		$appvStamp = $res_appv["appvStamp"];
		$appvStatus = $res_appv["appvStatus"];
		
		// ผลการอนุมัติ
		if($appvStatus == "0")
		{
			$appvStatusText = "<font color=\"red\">ไม่อนุมัติ</font>";
		}
		elseif($appvStatus == "1")
		{
			$appvStatusText = "<font color=\"green\">อนุมัติ</font>";
		}
		else
		{
			$appvStatusText = "";
		}
		
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
			$pathPopup = "popup_force_view.php";
		}
		elseif($insureType == "Unforce")
		{
			$insureName = "ประกันภัย ภาคสมัครใจ";
			$pathPopup = "popup_unforce_view.php";
		}
		else
		{
			$insureName = "";
			$pathPopup = "";
		}
		
		if($i%2==0){
			echo "<tr bgcolor=#EEEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=center>";
		}else{
			echo "<tr bgcolor=#F5F5F5 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#F5F5F5';\" align=center>";
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
		echo "<td align=\"left\">$appvName</td>";
		echo "<td align=\"center\">$appvStamp</td>";
		echo "<td align=\"center\">$appvStatusText</td>";
		echo "<td align=\"center\"><img src=\"../thcap/images/detail.gif\" height=\"19\" width=\"19\" style=\"cursor:pointer;\" onClick=\"javascript:popU('$pathPopup?requestID=$requestID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=650')\"></td>";
		
		echo "</tr>";
	}
	
	if($i == 0)
	{
		echo "<tr><td colspan=\"14\" align=\"center\">--ไม่พบข้อมูล--</td></tr>";
	}
	?>
	<tr bgcolor="#D6D6D6">
		<td colspan="14" align="left" >รวม : <?php echo number_format($i,0); ?>  รายการ</td>
	</tr>
</table>