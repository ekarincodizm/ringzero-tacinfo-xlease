<?php
include("../../config/config.php");
?>
<div align="center">
	<span style="display:inline-block;">ชื่อยี่ห้อ : </span>
	<input type="text" name="tbx_brand_name" id="tbx_brand_name" size="30" style="display:inline-block;" />
	ประเภท : 
	<select name="astype" id="astype">
		<option value="">--ไม่ระบุ--</option>
		<?php
		$qryastype=pg_query("select * from thcap_asset_biz_astype where \"astypeStatus\"='1'");
		while($restype=pg_fetch_array($qryastype)){
			$astypeID=$restype["astypeID"];
			$astypeName=$restype["astypeName"];
			
			echo "<option value=$astypeID>$astypeName</option>";
		}
		?>
	</select>
	<input type="button" name="btn_add_brand" id="btn_add_brand" value="เพิ่ม" onClick="add_brand();" />
</div>