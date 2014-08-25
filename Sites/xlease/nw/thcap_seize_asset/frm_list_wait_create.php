<script type="text/javascript">
	function popU(U,N,T) {
		newWindow = window.open(U, N, T);
	}
</script>

<fieldset><legend><B>สัญญาที่รออนุมัติ Create งานยึด</B></legend>
	<div class="ui-widget" align="center">
		<div style="margin:0">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
					<th>ลำดับ</th>
					<th>เลขที่สัญญา</th>
					<th>ผู้ Create งานยึด</th>
					<th>วันเวลาที่ Create งานยึด</th>
					<th>หมายเหตุ Create งานยึด</th>
				</tr>
				<?php
				$qry_create = pg_query("select * from \"thcap_create_seize_asset\" where \"createStatus\" = '9' order by \"doerStamp\" ");
				$nub = pg_num_rows($qry_create);
				$i = 0;
				while($res_create = pg_fetch_array($qry_create))
				{
					$i++;
					$createID = $res_create["createID"]; // รหัส Create งานยึด
					$contractID = $res_create["contractID"]; // เลขที่สัญญา
					$doerID = $res_create["doerID"]; // รหัสผู้ทำรายการ
					$doerStamp = $res_create["doerStamp"]; // วันเวลาที่ทำรายการ
					$doerNote = $res_create["doerNote"]; // หมายเหตุการทำรายการ
					
					// หาชื่อ ผู้ทำรายการ
					$sqlNameUser = pg_query("SELECT \"fullname\"  FROM \"Vfuser\" where \"id_user\" = '$doerID'");
					$fullnameUser = pg_fetch_result($sqlNameUser,0);
					
					if($i%2==0){
						echo "<tr class=\"odd\" align=center>";
					}else{
						echo "<tr class=\"even\" align=center>";
					}
					
					echo "<td align=\"center\">$i</td>";
					echo "<td align=\"center\"><font color=\"0000FF\" style=\"cursor:pointer;\" onClick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');\"><u>$contractID</u></font></td>";
					echo "<td align=\"left\">$fullnameUser</td>";
					echo "<td align=\"center\">$doerStamp</td>";
					echo "<td align=\"left\">$doerNote</td>";
					echo "</tr>";
				}
				
				if($nub == 0)
				{
					echo "<tr><td colspan=5 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
				}
				?>
			</table>
		</div>
	</div>
</fieldset>