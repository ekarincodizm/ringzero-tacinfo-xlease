<?php
include("../../config/config.php");

function chk_null($data)
{
	if($data=="")
	{
		$data = "<ไม่มีข้อมูล>";
	}
	else
	{
		$data = $data;
	}
	return $data;
}
?>
<div id="div_data">
    <table border="0" cellpadding="5" cellspacing="1" width="100%">
    	<tr bgcolor="#1da3cf">
            <th>ลำดับ</th>
            <th>ยี่ห้อ</th>
            <th>ประเภท</th>
            <th>เวลาที่บันทึก</th>
            <th>ผู้ทำรายการ</th>
            <th>สถานะ</th>
        </tr>
        <?php
		$qr = pg_query("select * from \"thcap_asset_biz_brand\" a
		left join thcap_asset_biz_astype b on a.\"astypeID\"=b.\"astypeID\"
		order by \"brand_name\" asc");
		if($qr)
		{
			$row = pg_num_rows($qr);
			if($row==0||empty($row))
			{
				echo "
					<tr class=\"odd\">
						<td colspan=\"5\" align=\"center\"><b>********** ไม่มีข้อมูล **********</b></td>
					</tr>
				";
			}
			else
			{
				$i = 0;
				$n = 1;
				while($rs = pg_fetch_array($qr))
				{
					$brand = $rs['brand_name'];
					$astypeName = $rs['astypeName'];
					$do_time = chk_null($rs['doerStamp']);
					$doer_id = $rs['doerID'];
					
					if($doer_id!="")
					{
						$qr1 = pg_query("select \"title\",\"fname\",\"lname\" from \"fuser\" where \"id_user\"='$doer_id'");
						if($qr1)
						{
							$rs1 = pg_fetch_array($qr1);
							$doer_name = $rs1['title'].$rs1['fname']."  ".$rs1['lname'];
						}
					}
					else
					{
						$doer_name = chk_null($doer_id);
					}
					$status = $rs['status'];
					if($status==1)
					{
						$status = "ใช้งานได้";
					}
					else if($status==2)
					{
						$status = "ไม่ใช้งาน";
					}
					if($i%2==0)
					{
						echo "<tr class=\"odd\">";
					}
					else
					{
						echo "<tr class=\"even\">";
					}
					
					echo "
						<td align=\"center\">$n</td>
						<td align=\"left\">$brand</td>
						<td align=\"left\">$astypeName</td>
						<td align=\"center\">$do_time</td>
						<td align=\"center\">$doer_name</td>
						<td align=\"center\">$status</td>
					";
					
					$i++;
					$n++;
				}
			}
		}
		?>
    </table>
</div>