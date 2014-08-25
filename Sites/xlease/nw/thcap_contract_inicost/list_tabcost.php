<?php
require_once("../../config/config.php");

$tab_id = pg_escape_string($_GET['tabid']); //id contype ที่ต้องการให้แสดง
$Strsort=pg_escape_string($_GET['sort']);
if($Strsort==""){$Strsort="conDate";}
 $Strorder=pg_escape_string($_GET['order']);
if($Strorder==""){$strorder="DESC";}

if($Strorder=="DESC"){
	$NewStrorder="ASC";
} else {
	$NewStrorder="DESC";
}
?>
	<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">	
			<td><font color="black">รายการที่</font></td>
			<td><a href="frm_Request.php?sort=contractID&order=<?php echo $NewStrorder;?>&tab_id=<?php echo $tab_id;?>"><font color="black"><u>เลขที่สัญญา</u></font></td>
			<td><a href="frm_Request.php?sort=conType&order=<?php echo $NewStrorder;?>&tab_id=<?php echo $tab_id;?>"><font color="black"><u>ประเภทสัญญา</u></font></td>
			<td><a href="frm_Request.php?sort=conDate&order=<?php echo $NewStrorder;?>&tab_id=<?php echo $tab_id;?>"><font color="black"><u>วันที่ทำสัญญา</u></font></td>
			<td><a href="frm_Request.php?sort=investment&order=<?php echo $NewStrorder;?>&tab_id=<?php echo $tab_id;?>"><font color="black"><u>จำนวนเงินที่ลงทุน</u></font></td>
			<td><a href="frm_Request.php?sort=conEndDate&order=<?php echo $NewStrorder;?>&tab_id=<?php echo $tab_id;?>"><font color="black"><u>วันที่ครบกำหนดสัญญา</u></font></td>
			<td><font color="black">ทำรายการ</font></td>
		</tr>
	<?php if($tab_id=='0'){//เลือกทั้งหมด
		$qry_con=pg_query("select *,thcap_get_iniinvestmentamt(\"contractID\") as investment,\"thcap_get_conEndDate\"(\"contractID\") as \"FconEndDate\" from \"thcap_contract\"
			where \"conCredit\" is null 
			and (\"contractID\" not in(select distinct \"contractID\" from \"thcap_contract_inicost\") 
			or \"contractID\"  not in (select \"contractID\" from thcap_contract_inicost where ini_appv_status in ('2','1'))
			and	\"contractID\"  in (select \"contractID\" from thcap_contract_inicost where ini_appv_status in ('0') )) 
			order by \"$Strsort\" $NewStrorder ");}
	else{
		$qry_con=pg_query("select *,thcap_get_iniinvestmentamt(\"contractID\") as investment,\"thcap_get_conEndDate\"(\"contractID\") as \"FconEndDate\" from \"thcap_contract\"
			where \"conCredit\" is null and \"conType\"='$tab_id'
			and (\"contractID\" not in(select distinct \"contractID\" from \"thcap_contract_inicost\") 
			or \"contractID\"  not in (select \"contractID\" from thcap_contract_inicost where ini_appv_status in ('2','1'))
			and	\"contractID\"  in (select \"contractID\" from thcap_contract_inicost where ini_appv_status in ('0') )) 
			order by \"$Strsort\" $NewStrorder ");
	}
			
			$num_con=pg_num_rows($qry_con);
			$i=0;
			$num=0;
			while($res_con=pg_fetch_array($qry_con)){
				$i++;
				$num++;
				$contractID=$res_con["contractID"];
				$contractType = $res_con["conType"]; 
				$contractDate = $res_con["conDate"]; 
				$conEndDate = $res_con["FconEndDate"];
				$autoID = $res_con["ini_auto_id"];
				$investmentAmt = $res_con["investment"];
				
				if($investmentAmt != ""){$txtinvestmentAmt = number_format($investmentAmt,2);}else{$txtinvestmentAmt = "";}
				
				if($i%2==0){
				echo "<tr class=\"odd\" align=center>";
			} else {
				echo "<tr class=\"even\" align=center>";
			}
				echo "<td>$num</td>";
				echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractID</u></font></td>";
				echo "<td>$contractType</td>";
				echo "<td>$contractDate</td>";
				echo "<td align=\"right\">$txtinvestmentAmt</td>";
				echo "<td>$conEndDate</td>";
				echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('Input_inicost.php?contractID=$contractID&ini_auto_id=$autoID&conDate=$contractDate&conEndDate=$conEndDate&conType=$contractType','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=760,height=460')\"style=\"cursor:pointer\"><img src=\"images/detail.gif\"/></a></u></font></td>";
			echo "</tr>";
			} //endwhile
			?>
			<?php
			if($num_con == 0){
				echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?><tr bgcolor="#6699FF">
				<td colspan="9" align="left"><b>รายการทั้งหมด <?php echo $num;?> รายการ<b></td>
			</tr>
		</div>
	</td>
</tr>
</table>
