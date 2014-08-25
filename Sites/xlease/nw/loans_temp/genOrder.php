<?php
include("../../config/config.php");
$assetID = $_POST['rcid'];
$type = $_POST['type'];
$allitm = split(",",$_POST['allitm']);
$allitm_size = sizeof($allitm);
switch($type)
{
	case "all":
		$q = "select * from \"vthcap_asset_biz_detail_active\" where \"assetID\" = '$assetID'";
		$qr = pg_query($q);
		$row = pg_num_rows($qr);
		$rcID = "";
		if($row>0)
		{
			while($rs = pg_fetch_array($qr))
			{
				if($rs['pricePerUnit']=="")
				{
					$rs['pricePerUnit'] = "ไม่ระบุ";
				}
				else
				{
					$rs['pricePerUnit'].=" บาท";
					if($rs['receiptNumber']!="")
					{
						$rcID = "เลขที่ใบเสร็จ : <b>".$rs['receiptNumber']."</b>";
					}
					else if($rs['PurchaseOrder']!="")
					{
						$rcID = "เลขที่ใบสั่งซื้อ : <b>".$rs['PurchaseOrder']."</b>";
					}
				}
				
				if($rs['productCode'] != "")
				{ // ถ้ามี Serial
					echo "
						<div class=\"row bottom_solid\">
							<div class=\"chkbx inline\"><input type=\"checkbox\" name=\"all_pick_itm[]\" value=\"".$assetID.",".$rs['assetDetailID']."\" checked=\"checked\" disabled=\"disabled\" /></div>
							<div class=\"chkbx_label inline col1\">
								$rcID ยี่ห้อ : <b>".$rs['brand']."</b> รุ่น : <b>".$rs['model']."</b> Serial : <b>".$rs['productCode']."</b> ราคา/หน่วย : <b>".$rs['pricePerUnit']."</b>
								<br />
								<div class=\"currentaddr\">ที่ตั้งเครื่องปัจจุบัน : <span class=\"span_addr\" style=\"margin-top:3px;\">ไม่ระบุ</span><br/><input type=\"button\" name=\"btn_chg_addr[]\" onclick=\"change_addr(this);\" value=\"เปลี่ยนที่ตั้งเครื่อง\" /></div>
								<input type=\"hidden\" name=\"H_addr[]\" value=\"\" />
							</div>
							<div class=\"delete_row inline\">
								<img src=\"images/delete.png\" width=\"24\" height=\"24\" style=\"cursor:pointer;\" onclick=\"delete_this_row(this);\" />
							</div>
						</div>
					";
				}
				else
				{ // ถ้าไม่มี Serial
					echo "
						<div class=\"row bottom_solid\">
							<div class=\"chkbx inline\"><input type=\"checkbox\" name=\"all_pick_itm[]\" value=\"".$assetID.",".$rs['assetDetailID']."\" checked=\"checked\" disabled=\"disabled\" /></div>
							<div class=\"chkbx_label inline col1\">
								$rcID ยี่ห้อ : <b>".$rs['brand']."</b> รุ่น : <b>".$rs['model']."</b> ราคา/หน่วย : <b>".$rs['pricePerUnit']."</b>
								<br />
								<div class=\"currentaddr\">ที่ตั้งเครื่องปัจจุบัน : <span class=\"span_addr\" style=\"margin-top:3px;\">ไม่ระบุ</span><br/><input type=\"button\" name=\"btn_chg_addr[]\" onclick=\"change_addr(this);\" value=\"เปลี่ยนที่ตั้งเครื่อง\" /></div>
								<input type=\"hidden\" name=\"H_addr[]\" value=\"\" />
							</div>
							<div class=\"delete_row inline\">
								<img src=\"images/delete.png\" width=\"24\" height=\"24\" style=\"cursor:pointer;\" onclick=\"delete_this_row(this);\" />
							</div>
						</div>
					";
				}
			}
		}
		else
		{
			echo 0;
		}
		break;
		case "each":
		$q = "select * from \"vthcap_asset_biz_detail_active\" where \"assetID\" = '$assetID'";
		$qr = pg_query($q);
		$row = pg_num_rows($qr);
		$rcID = "";
		if($row>0)
		{
			while($rs = pg_fetch_array($qr))
			{
				if($rs['pricePerUnit']=="")
				{
					$rs['pricePerUnit'] = "ไม่ระบุ";
				}
				else
				{
					$rs['pricePerUnit'].=" บาท";
					if($rs['receiptNumber']!="")
					{
						$rcID = "เลขที่ใบเสร็จ : <b>".$rs['receiptNumber']."</b>";
					}
					else if($rs['PurchaseOrder']!="")
					{
						$rcID = "เลขที่ใบสั่งซื้อ : <b>".$rs['PurchaseOrder']."</b>";
					}
				}
				
				if($rs['productCode'] != "")
				{ // ถ้ามี Serial
					echo "
						<div class=\"row bottom_solid\">
							<div class=\"chkbx inline\"><input type=\"checkbox\" name=\"each_pick_itm[]\" value=\"".$assetID.",".$rs['assetDetailID']."\" /></div>
							<div class=\"chkbx_label inline col1\">
								$rcID ยี่ห้อ : <b>".$rs['brand']."</b> รุ่น : <b>".$rs['model']."</b> Serial : <b>".$rs['productCode']."</b> ราคา/หน่วย : <b>".$rs['pricePerUnit']."</b>
								<br />
								<div class=\"currentaddr\">ที่ตั้งเครื่องปัจจุบัน : <span class=\"span_addr\" style=\"margin-top:3px;\">ไม่ระบุ</span><br/><input type=\"button\" name=\"btn_chg_addr[]\" onclick=\"change_addr(this);\" value=\"เปลี่ยนที่ตั้งเครื่อง\" /></div>
								<input type=\"hidden\" name=\"H_addr[]\" value=\"\" />
							</div>
						</div>
					";
				}
				else
				{ // ถ้าไม่มี Serial
					echo "
						<div class=\"row bottom_solid\">
							<div class=\"chkbx inline\"><input type=\"checkbox\" name=\"each_pick_itm[]\" value=\"".$assetID.",".$rs['assetDetailID']."\" /></div>
							<div class=\"chkbx_label inline col1\">
								$rcID ยี่ห้อ : <b>".$rs['brand']."</b> รุ่น : <b>".$rs['model']."</b> ราคา/หน่วย : <b>".$rs['pricePerUnit']."</b>
								<br />
								<div class=\"currentaddr\">ที่ตั้งเครื่องปัจจุบัน : <span class=\"span_addr\" style=\"margin-top:3px;\">ไม่ระบุ</span><br/><input type=\"button\" name=\"btn_chg_addr[]\" onclick=\"change_addr(this);\" value=\"เปลี่ยนที่ตั้งเครื่อง\" /></div>
								<input type=\"hidden\" name=\"H_addr[]\" value=\"\" />
							</div>
						</div>
					";
				}
			}
		}
		else
		{
			echo 0;
		}
		break;
		case "group":
		$q = "select \"brand\",\"model\",count(*) as  allitem from \"vthcap_asset_biz_detail_active\" where \"assetID\"='$assetID'";
		$n = 0;
		while($n<$allitm_size)
		{
			if($allitm[$n]!="")
			{
				$q.=" and \"assetDetailID\"<>'".$allitm[$n]."'";
			}
			$n++;
		}
		$q.=" group by \"brand\",\"model\" order by \"brand\"";
		$qr = pg_query($q);
		$row = pg_num_rows($qr);
		if($row>0)
		{
			$i=1;
			while($rs = pg_fetch_array($qr))
			{
				echo "
					<div class=\"row bottom_solid\">
						<div class=\"chkbx inline\"><input type=\"checkbox\" name=\"each_pick_grp[]\" value=\"".$assetID."\" /></div>
						<div class=\"chkbx_label inline col1\">ยี่ห้อ : <b>".$rs['brand']."</b> รุ่น : <b>".$rs['model']."</b> คงเหลือ : <b>".$rs['allitem']."</b> จำนวนที่ต้องการ : <input type=\"text\" name=\"tbx_pick_itm\" id=\"tbx_pick_itm".$i."\" onkeyup=\"chk_max_itm(id);\" /></div>
						<input type=\"hidden\" name=\"max_itm\" id=\"max_itm".$i."\" value=\"".$rs['allitem']."\" />
						<input type=\"hidden\" name=\"each_brand\" id=\"each_brand".$i."\" value=\"".$rs['brand']."\" />
						<input type=\"hidden\" name=\"each_model\" id=\"each_model".$i."\" value=\"".$rs['model']."\" />
					</div>
				";
				$i++;
			}
		}
		else
		{
			echo 0;
		}
		break;
		case "item_grop":
		$brand = $_POST['brand'];
		$model = $_POST['model'];
		$pick = $_POST['pick'];
		$q = "select * from \"vthcap_asset_biz_detail_active\" where \"assetID\" = '$assetID' and \"brand\"='$brand' and \"model\"='$model'";
		$n = 0;
		while($n<$allitm_size)
		{
			if($allitm[$n]!="")
			{
				$q.=" and \"assetDetailID\"<>'".$allitm[$n]."'";
			}
			$n++;
		}
		$q.=" order by \"assetDetailID\" asc limit $pick offset 0";
		$qr = pg_query($q);
		$row = pg_num_rows($qr);
		$rcID = "";
		if($row>0)
		{
			while($rs = pg_fetch_array($qr))
			{
				if($rs['pricePerUnit']=="")
				{
					$rs['pricePerUnit'] = "ไม่ระบุ";
				}
				else
				{
					$rs['pricePerUnit'].=" บาท";
					if($rs['receiptNumber']!="")
					{
						$rcID = "เลขที่ใบเสร็จ : <b>".$rs['receiptNumber']."</b>";
					}
					else if($rs['PurchaseOrder']!="")
					{
						$rcID = "เลขที่ใบสั่งซื้อ : <b>".$rs['PurchaseOrder']."</b>";
					}
				}
				
				if($rs['productCode'] != "")
				{ // ถ้ามี Serial
					echo "
						<div class=\"row bottom_solid\">
							<div class=\"chkbx inline\"><input type=\"checkbox\" name=\"all_pick_itm[]\" value=\"".$assetID.",".$rs['assetDetailID']."\" checked=\"checked\" disabled=\"disabled\" /></div>
							<div class=\"chkbx_label inline col1\">
								$rcID ยี่ห้อ : <b>".$rs['brand']."</b> รุ่น : <b>".$rs['model']."</b> Serial : <b>".$rs['productCode']."</b> ราคา/หน่วย : <b>".$rs['pricePerUnit']."</b>
								<br />
								<div class=\"currentaddr\">ที่ตั้งเครื่องปัจจุบัน : <span class=\"span_addr\" style=\"margin-top:3px;\">ไม่ระบุ</span><br/><input type=\"button\" name=\"btn_chg_addr[]\" onclick=\"change_addr(this);\" value=\"เปลี่ยนที่ตั้งเครื่อง\" /></div>
								<input type=\"hidden\" name=\"H_addr[]\" value=\"\" />
							</div>
							<div class=\"delete_row inline\">
								<img src=\"images/delete.png\" width=\"24\" height=\"24\" style=\"cursor:pointer;\" onclick=\"delete_this_row(this);\" />
							</div>
						</div>
					";
				}
				else
				{ // ถ้าไม่มี Serial
					echo "
						<div class=\"row bottom_solid\">
							<div class=\"chkbx inline\"><input type=\"checkbox\" name=\"all_pick_itm[]\" value=\"".$assetID.",".$rs['assetDetailID']."\" checked=\"checked\" disabled=\"disabled\" /></div>
							<div class=\"chkbx_label inline col1\">
								$rcID ยี่ห้อ : <b>".$rs['brand']."</b> รุ่น : <b>".$rs['model']."</b> ราคา/หน่วย : <b>".$rs['pricePerUnit']."</b>
								<br />
								<div class=\"currentaddr\">ที่ตั้งเครื่องปัจจุบัน : <span class=\"span_addr\" style=\"margin-top:3px;\">ไม่ระบุ</span><br/><input type=\"button\" name=\"btn_chg_addr[]\" onclick=\"change_addr(this);\" value=\"เปลี่ยนที่ตั้งเครื่อง\" /></div>
								<input type=\"hidden\" name=\"H_addr[]\" value=\"\" />
							</div>
							<div class=\"delete_row inline\">
								<img src=\"images/delete.png\" width=\"24\" height=\"24\" style=\"cursor:pointer;\" onclick=\"delete_this_row(this);\" />
							</div>
						</div>
					";
				}
			}
		}
		else
		{
			echo 0;
		}
		break;
}
?>