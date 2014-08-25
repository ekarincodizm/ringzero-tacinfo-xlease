<?php
//เงือนไขในการเรียงดำลับข้อมูล
$Strsort2=pg_escape_string($_GET['sort2']);
if($Strsort2==""){$Strsort2="conDate";}
$Strorder2=pg_escape_string($_GET['order2']);
if($Strorder2==""){$strorder2="DESC";}

if($Strorder2=="DESC"){
	$NewStrorder2="ASC";
} else {
	$NewStrorder2="DESC";
}
?>
<form name="frm" method="POST">
<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div align="left"><h2>รายการรออนุมัติต้นทุนสัญญา</h2></div>
		<div class="wrapper">		
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">	
				<td><font color="black">รายการที่</font></td>
				<td><font color="black">เลขที่สัญญา</font></td>
				<td><font color="black">ประเภทสัญญา</font></td>
				<td><font color="black">วันที่ทำสัญญา</font></td>
				<td><font color="black">ผู้ทำรายการ</font></td>
				<td><font color="black">วันเวลาที่ทำรายการ</font></td>
				<td><font color="black">ดูรายละเอียด</font></td>
			</tr>
			<?php
			
			
			$qry_con=pg_query("select a.\"contractID\",a.ini_add_user,a.ini_add_stamp,b.\"conType\",b.\"conDate\",thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\"  from \"thcap_contract_inicost\" a
			left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
			where a.\"ini_appv_status\"='2' group by a.\"contractID\",a.\"ini_add_stamp\",a.\"ini_add_user\",b.\"conType\",b.\"conDate\"  order by a.\"ini_add_stamp\" ASC  ");
			
			
			$num_con=pg_num_rows($qry_con);
			$i=0;
			$num_App=0;
			while($res_con=pg_fetch_array($qry_con)){
			$i++;
			$num_App++;
				$contractID=$res_con["contractID"];
				$contractType = $res_con["conType"]; 
				$contractDate = $res_con["conDate"]; 
				$conEndDate = $res_con["FconEndDate"];
				$addUser = $res_con["ini_add_user"];
				$addStamp = $res_con["ini_add_stamp"];
				$investment = $res_con["investment"];
				$qry_chkStatus = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
				$res_chkStatus = pg_fetch_array($qry_chkStatus);
				
				$conStatus = $res_chkStatus["conStatus"]; // สถานะของสัญญา
				
				
				$qry_fullname_adduser = pg_query("select fullname from \"Vfuser\" where id_user = '$addUser' ");
				$addFulname=pg_fetch_result($qry_fullname_adduser,0);
				
				
			if($i%2==0){
				if($conStatus == "11"){
							echo "<tr bgcolor=\"#CCCCCC\">";
				} else {
				 echo "<tr class=\"odd\" align=center>";
				}
			} else {
				if($conStatus == "11"){
							echo "<tr bgcolor=\"#CCCCCC\">";
				} else {
				 echo "<tr class=\"even\" align=center>";
				}
				
			} 
				echo "<td>$num_App</td>";
				echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractID</u></font></td>";
				echo "<td>$contractType</td>";
				echo "<td>$contractDate</td>";
				echo "<td>$addFulname</td>";
				echo "<td>$addStamp</td>";
				echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('detail_Appv.php?contractID=$contractID&ini_auto_id=$autoID&addStamp=$addStamp&addUser=$addUser','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=760')\"style=\"cursor:pointer\"><img src=\"images/detail.gif\"/></a></u></font></td>";
			echo "</tr>";
			} //endwhile
			?>
			<?php
			if($num_con == 0){
				echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			<tr bgcolor="#6699FF">
				<td colspan="9" align="left"><b>รายการทั้งหมด <?php echo $num_App;?><b></td>
			</tr>
			
		</div>
	</td>
</tr>
</table>
</form>