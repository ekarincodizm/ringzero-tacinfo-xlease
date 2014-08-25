<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php"); 
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
			<table width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
				<tr align="center" bgcolor="#79BCFF">
					<th>เลขทะเบียนนิติบุคคล</th>
					<th>ชื่อนิติบุคคล</th>
					<th>สถานะ</th>
					<th></th>
				</tr>
				<?php
				
				$query_old = pg_query("select a.\"corp_regis\" , a.\"corpType\" , a.\"corpName_THA\" , a.\"Approved\" , a.\"corpEdit\" from public.\"th_corp_temp\" a 
										where a.\"corpEdit\" = (select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" b where b.\"corp_regis\" = a.\"corp_regis\") 
											and (a.\"Approved\" is null or a.\"Approved\" = 'false') 
											and a.\"hidden\" = 'false' and a.\"corpID\" = '0' ");
				$numrows_old = pg_num_rows($query_old);
				while($result_old = pg_fetch_array($query_old))
				{
					$corp_regis_old = $result_old["corp_regis"]; // เลขทะเบียนนิติบุคคล
					$corpType_old = $result_old["corpType"]; // ประเภทนิติบุคคล
					$corpName_THA_old = $result_old["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
					$Approved_old = $result_old["Approved"]; // อนุมัติหรือไม่
					
					if($Approved_old == "")
					{
						$txtAppv = "<a onclick=\"javascript:popU('frm_viewcorp_detail.php?corp_regis=$corp_regis_old&view=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font><u>รออนุมัติ</u></font></a>";
					}
					elseif($Approved_old == "f")
					{
						$txtAppv = "<a onclick=\"javascript:popU('frm_viewcorp_detail.php?corp_regis=$corp_regis_old&view=3','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#FF0000\"><u>ไม่อนุมัติ</u></font></a>";
					}
					
					if($i%2==0){
						echo "<tr class=\"odd\">";
					}else{
						echo "<tr class=\"even\">";
					}
					
					echo "<td align=\"center\">$corp_regis_old</td>";
					echo "<td>$corpType_old $corpName_THA_old</td>";
					echo "<td>$txtAppv</td>";
					
					if($Approved_old == "f")
					{
						echo "<td>&nbsp;&nbsp;&nbsp;<a onclick=\"javascript:popU('frm_confirm_hidden_corp.php?corp_regis=$corp_regis_old&view=3','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=150')\" style=\"cursor:pointer;\"><font color=\"#FF0000\"><u><<--ไม่ต้องแสดงรายการนี้อีก</u></font></a></td>";
					}
					else
					{
						echo "<td></td>";
					}
					
					echo "</tr>";
				}
				
				if($numrows_old==0)
				{
					echo "<tr bgcolor=#FFFFFF><td colspan=4 align=center><b>ไม่พบรายการ</b></td><tr>";
				}
				?>
			</table>