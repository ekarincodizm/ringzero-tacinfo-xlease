<?php
if($bill_id!="")
{
	$bill_id_arr = split("#",$bill_id);
	$assetID = $bill_id_arr[0];
	
	$qry_asset_biz = pg_query("select * from \"thcap_asset_biz\" where \"assetID\"='$assetID'");
	$qry_asset_biz_detail_temp = pg_query("select a.*,b.\"model_name\",c.\"brand_name\" from \"thcap_asset_biz_detail\" a
											left join \"thcap_asset_biz_model\" b ON a.\"model\" = b.\"modelID\"
											left join \"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
											where a.\"assetID\" = '$assetID'");

	while($res_asset_biz = pg_fetch_array($qry_asset_biz))
	{
		$compID = $res_asset_biz["compID"]; // ID บริษัท (ผู้ซื้อ)
		$corpID = $res_asset_biz["corpID"]; // รหัสนิติบุคคล (ผู้ขาย)
		$PurchaseOrder = $res_asset_biz["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
		$receiptNumber = $res_asset_biz["receiptNumber"]; // เลขที่ใบเสร็จ
		$buyDate = $res_asset_biz["buyDate"]; // วันที่ซื้อ
		$payDate = $res_asset_biz["payDate"]; // วันที่จ่ายเงิน
		$vatStatus = $res_asset_biz["vatStatus"]; // ประเภท vat
		$beforeVat = $res_asset_biz["beforeVat"]; // ราคาก่อน vat
		$mainVAT_value = $res_asset_biz["VAT_value"]; // ยอด vat
		$afterVat = $res_asset_biz["afterVat"]; // ราคาหลังรวม vat
		$pathFileReceipt = $res_asset_biz["pathFileReceipt"]; // ไฟล์ใบเสร็จ
		$pathFilePO = $res_asset_biz["pathFilePO"]; // ไฟล์ใบสั่งซื้อ
		
		if($vatStatus == 1)
		{
			$vatStatusTXT = "VAT แยก";
		}
		elseif($vatStatus == 2)
		{
			$vatStatusTXT = "VAT รวม";
		}
		else
		{
			$vatStatusTXT = "ไม่ระบุ";
		}
	}
	
	// หาชื่อบริษัท (ผู้ซื้อ)
	$qry_nameCom = pg_query("select * from public.\"thcap_company\" where \"compID\" = '$compID' ");
	while($result_name = pg_fetch_array($qry_nameCom))
	{
		$compThaiName = $result_name["compThaiName"]; // ชื่อของ บริษัท (ผู้ซื้อ)
	}
	
	// หาชื่อบริษัท (ผู้ซื้อ)
	$qry_nameCorp = pg_query("select * from public.\"VSearchCusCorp\" where \"CusID\" = '$corpID' ");
	while($result_name = pg_fetch_array($qry_nameCorp))
	{
		$fullnameCorp = $result_name["full_name"]; // ชื่อของ (ผู้ขาย)
	}
?>
<script language="JavaScript" type="text/javascript">
function appv(){
	frm.action="process_appv_cancel.php"; 
	frm.submit();
}
</script>
<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center">
			<fieldset><legend><B>ข้อมูลหลัก</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="0" cellPadding="2" bgcolor="#FFFFFF">
					<tr>
						<td align="right">ผู้ซื้อ :</td>
						<td><?php echo $compThaiName; ?></td>
						<td width="10"></td>
						<td align="right">ผู้ขาย :</td><td><?php echo $fullnameCorp; ?></td>
						<td width="10"></td>
						<td align="right">ประเภท VAT :</td><td><?php echo $vatStatusTXT; ?></td>
						<td></td><td></td>
					</tr>
					<tr>
						<td align="right">เลขที่ใบสั่งซื้อ :<br /><span style="font-weight:bold; color:#ff0000;">(THCAP เป็นผู้ออก)</span></td><td><?php echo $PurchaseOrder; ?></td>
						<td width="10"></td>
						<td align="right">เลขที่ใบเสร็จ :<br /><span style="font-weight:bold; color:#ff0000;">(ผู้ขายเป็นผู้ออก)</span></td><td><?php echo $receiptNumber; ?></td>
						<td width="10"></td>
						<td align="right">วันที่ซื้อ :</td><td><?php echo $buyDate; ?></td>
						<td width="10"></td>
						<td align="right">วันที่จ่ายเงิน :</td><td><?php echo $payDate; ?></td>
					</tr>
				</table>
			</center>
			</fieldset>
			
			<br>
			
			<form name="frmCancel" method="post" action="process_cancel_assets.php">
			<fieldset><legend><B>ทำรายการยกเลิกใบเสร็จสินทรัพย์</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="0" cellPadding="2" bgcolor="#FFFFFF">
					<tr>
						<td align="right">เหตุผลในการยกเลิก :</td>
						<?php
						if($typeUseMenu == "appv")
						{ // ถ้ามาจากหน้าอนุมัติยกเลิก
						?>
							<td align="left"><textarea name="reason" id="reason" readOnly><?php echo $reason; ?></textarea></td>
						<?php
						}
						else
						{
						?>
							<td align="left"><textarea name="reason" id="reason"></textarea></td>
						<?php
						}
						?>
					</tr>
					<tr>
						<?php
						if($typeUseMenu == "appv")
						{ // ถ้ามาจากหน้าอนุมัติยกเลิก
						?>
							<td colspan="2" align="center">
								<br><br>
								<input type="button" value="อนุมัติ" onClick="document.forms['my'].appv.click();">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" value="ไม่อนุมัติ" onClick="document.forms['my'].unappv.click();">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" value="ออก" onClick="window.close()">
							</td>
						<?php
						}
						else
						{
						?>
							<td colspan="2" align="center"><br><input type="submit" value="ยืนยันการขอยกเลิก" onClick="return validate()"></td>
						<?php
						}
						?>
					</tr>
				</table>
			</center>
			</fieldset>
			<input type="hidden" name="assetCancel" value="<?php echo $assetID; ?>">
			</form>
			
			<br>
			
			<fieldset><legend><B>รายละเอียด</B></legend>
			<center>
				<table id="tableDetail" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
					<tr align="center" bgcolor="#79BCFF">
						<th>NO.</th>
						<th>ชื่อยี่ห้อ</th>
						<th>ประเภทสินทรัพย์</th>
						<th>ชื่อรุ่น</th>
						<th>รหัสสินค้า<br>(Serial Number)</th>
						<th>รหัสสินค้ารอง<br>(Secondary Serial Number)</th>
						<th>ต้นทุน/ชิ้น</th>
						<th>ภาษีมูลค่าเพิ่ม</th>
						<th>สถานะสินค้า</th>
                        <th>การมีอยู่ของสินค้า</th>
						<th>คำอธิบาย</th>
					</tr>
                    <?php
						$i = 0;
						
						while($res_asset_biz_detail_temp = pg_fetch_array($qry_asset_biz_detail_temp))
						{
							$i++;
							$brand = $res_asset_biz_detail_temp["brand_name"]; // ชื่อยี่ห้อ
							$astypeID = $res_asset_biz_detail_temp["astypeID"]; // ประเภทสินทรัพย์
							$model = $res_asset_biz_detail_temp["model_name"]; // ชื่อรุ่น
							$explanation = $res_asset_biz_detail_temp["explanation"]; // คำอธิบาย
							$pricePerUnit = $res_asset_biz_detail_temp["pricePerUnit"]; // ต้นทุนราคารวมภาษี/ชิ้น
							$productCode = $res_asset_biz_detail_temp["productCode"]; // รหัสสินค้า
							$secondaryID = $res_asset_biz_detail_temp["secondaryID"]; // รหัสสินค้ารอง
							$VAT_value = $res_asset_biz_detail_temp["VAT_value"]; // ยอด VAT
							$ProductStatusID = $res_asset_biz_detail_temp["ProductStatusID"]; // สถานะสินค้า
							$materialisticStatus = $res_asset_biz_detail_temp["materialisticStatus"];
							if($materialisticStatus=="0")
							{
								$materialisticStatus = "ไม่มีสินค้าแล้ว";
							}
							else if($materialisticStatus=="1")
							{
								$materialisticStatus = "มีสินค้าพร้อมใช้";
							}
							else if($materialisticStatus=="2")
							{
								$materialisticStatus = "สินค้าถูกนำไปใช้อยู่";
							}
							else
							{
								$materialisticStatus = "";
							}
							
							// หาชื่อประเภทสินทรัพย์
							$qry_astype = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeID\" = '$astypeID' ");
							while($result_astype = pg_fetch_array($qry_astype))
							{
								$astypeName = $result_astype["astypeName"]; // ชื่อของ นิติบุคคล (ผู้ขาย)
							}
							
							// หาชื่อสถานะสินค้า
							if($ProductStatusID != "") // ถ้าไม่ว่าง
							{
								$qry_pStatus = pg_query("select * from public.\"ProductStatus\" where \"ProductStatusID\" = '$ProductStatusID' ");
								$row_pStatus = pg_num_rows($qry_pStatus);
								if($row_pStatus > 0) // ถ้ามีข้อมูล
								{
									while($result_pStatus = pg_fetch_array($qry_pStatus))
									{
										$ProductStatusName = $result_pStatus["ProductStatusName"]; // ชื่อของ สถานะสินค้า
									}
								}
								else // ถ้าไม่มีข้อมูล
								{
									$ProductStatusName = "";
								}
							}
							else // ถ้าไม่ได้ระบุไว้
							{
								$ProductStatusName = "";
							}
					?>
							<tr bgcolor="#E8E8E8">
								<td align="center"><?php echo $i; ?></td>
								<td><?php echo $brand; ?></td>
								<td>
									<?php echo $astypeName; ?>
								</td>
								<td><?php echo $model; ?></td>
								<td><?php echo $productCode; ?></td>
								<td><?php echo $secondaryID; ?></td>
								<td><?php echo number_format($pricePerUnit,2); ?></td>
								<td><?php if($VAT_value != ""){echo number_format($VAT_value,2);} ?></td>
								<td><?php echo $ProductStatusName; ?></td>
                                <td><?php echo $materialisticStatus; ?></td>
								<td><?php echo $explanation; ?></td>
							</tr>
					<?php
						}
					?>
				</table>
                <table width="100%">
					<tr bgcolor="#CCCC99">
						<td width="100%" align="right">
							<b>ราคาก่อน VAT : <?php echo number_format($beforeVat,2); ?></b>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>ยอด VAT : <?php echo number_format($mainVAT_value,2); ?></b>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>ราคารวม VAT : <?php echo number_format($afterVat,2); ?></b>
							&nbsp;&nbsp;
						</td>
					</tr>
				</table>
			</center>
			</fieldset>
			
			<fieldset><legend><B>ไฟล์ Upload</B></legend>
			<center>
				<table>
					<tr>
						<td align="right">ใบสั่งซื้อ (Purchase Order) : </td>
						<?php
						if($pathFilePO == "")
						{
							echo "<td align=\"left\">ไม่มีไฟล์</td>";
						}
						else
						{
						?>
							<td><a style="cursor:pointer;" onclick="popU('../upload/asset_bill/<?php echo $pathFilePO; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" title="<?php echo "$pathFilePO";?>"><u> แสดงใบสั่งซื้อ </u></a></td>
						<?php
						}
						?>
					</tr>
					<tr>
						<td align="right">ใบเสร็จ  (Receipt) : </td>
						<?php
						if($pathFileReceipt == "")
						{
							echo "<td align=\"left\">ไม่มีไฟล์</td>";
						}
						else
						{
						?>
							<td><a style="cursor:pointer;" onclick="popU('../upload/asset_bill/<?php echo $pathFileReceipt; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')"  title="<?php echo "$pathFileReceipt";?>"><u> แสดงใบเสร็จ </u></a></td>
						<?php
						}
						?>
					</tr>
				</table>
			</center>
			</fieldset>
		</td>
	</tr>
</table>
<form name="my" method="post" action="process_appv_cancel.php"  hidden >	
	<input type="hidden" name="cancelID" id="cancelID" value="<?php echo $cancelID; ?>">			
	<input name="appv" type="submit" value="อนุมัติ" />
	<input name="unappv" type="submit" value="ไม่อนุมัติ" />	
</form>
<?php	
}
if($status==1){
	echo "<div align=center style=\"padding-top:20px\"><input type=\"button\" value=\"ปิด\" onclick=\"window.close();\" style=\"width:100px;height:30px;\"></div>";
}
?>