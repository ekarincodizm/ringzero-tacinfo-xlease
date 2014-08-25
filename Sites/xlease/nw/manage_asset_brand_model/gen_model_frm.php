<?php
include("../../config/config.php");
?>
<div align="center">
	<span style="display:inline-block;">ชื่อยี่ห้อ : </span>
	<select name="tbx_brand_name" id="tbx_brand_name" style="display:inline-block;">
    	<option value="">----- เลือกยี่ห้อ -----</option>
        <?php
			$qr = pg_query("select * from \"thcap_asset_biz_brand\" a
			left join thcap_asset_biz_astype b on a.\"astypeID\"=b.\"astypeID\"
			where a.\"status\"='1'");
			if($qr)
			{
				$row = pg_num_rows($qr);
				if($row!=0)
				{
					while($rs = pg_fetch_array($qr))
					{
						$astypeName="";
						$id = $rs['brandID'];
						$brand_name = $rs['brand_name'];
						$astypeName = $rs['astypeName'];
						if($astypeName!=""){
							$astypeName="($astypeName)";
						}
						echo "
							<option value=\"$id\">$brand_name $astypeName</option>
						";
					}
				}
			}
		?>
    </select>
    <span style="display:inline-block;">ชื่อรุ่น : </span>
	<input type="text" name="tbx_model_name" id="tbx_model_name" size="30" style="display:inline-block;" />
    <input type="button" name="btn_add_brand" id="btn_add_brand" value="เพิ่ม" onClick="add_model();" />
</div>