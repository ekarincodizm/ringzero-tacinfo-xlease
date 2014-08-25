<?php
set_time_limit(0);
session_start();
include("config/config.php");
?>

<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#CCCCCC">
	<tr align="center"  bgcolor="#7DCFFB">
		<td height="30">InvNo</td>
		<td>InvType</td>
		<td>CusID</td>
		<td>RadioID</td>
		<td>Description</td>
		<td>PrineUnit</td>
		<td>NumUnit</td>
		<td>RecNO</td>
		<td>VatNO</td>
		<td>Discount</td>
		<td>InvAmountExVAT</td>
		<td>RInvNO</td>
		<td>InvIDUser</td>
		<td>CancelIDUser</td>
		<td>OldInvNO</td>
		<td>InvFixDate</td>
		<td>InvDate</td>
		<td>NeedVAT</td>
		<td>InvCancel</td>
		<td>CancelInvDate</td>
		<td>BadDebt</td>
	</tr>
	<?php 
	$query = pg_query("SELECT \"InvNo\" FROM taxiacc.\"TacInvoice\" group by \"InvNo\" having count(\"InvNo\") > 1  order by \"InvNo\""); 
	$numrows = pg_num_rows($query);
	
	$i=0;
	while($result = pg_fetch_array($query)){
		$InvNo = $result["InvNo"];	
		$querycus = pg_query("SELECT \"InvNo\",\"CusID\" from taxiacc.\"TacInvoice\" where \"InvNo\"='$InvNo' group by \"InvNo\",\"CusID\" order by \"InvNo\"");
		$numrowcus=pg_num_rows($querycus);	
		if($numrowcus > 1){
			$queryall=pg_query("select * FROM taxiacc.\"TacInvoice\" where \"InvNo\"='$InvNo'");
			$color1="#FDEDFE";
			$color2="#FBCEFD";
			while($rescus=pg_fetch_array($queryall)){
				$InvNo2=$rescus["InvNo"];
				$InvType=$rescus["InvType"];
				$CusID=$rescus["CusID"];
				$RadioID=$rescus["RadioID"];
				$Description=$rescus["Description"];
				$PriceUnit=$rescus["PriceUnit"];
				$NumUnit=$rescus["NumUnit"];
				$RecNO=$rescus["RecNO"];
				$VatNO=$rescus["VatNO"];
				$Discount=$rescus["Discount"];
				$InvAmountExVAT=$rescus["InvAmountExVAT"];
				$RInvNO=$rescus["RInvNO"];
				$InvIDUser=$rescus["InvIDUser"];
				$CancelIDUser=$rescus["CancelIDUser"];
				$OldInvNO=$rescus["OldInvNO"];
				$InvFixDate=$rescus["InvFixDate"];
				$InvDate=$rescus["InvDate"];
				$NeedVAT=$rescus["NeedVAT"];
				$InvCancel=$rescus["InvCancel"];
				$CancelInvDate=$rescus["CancelInvDate"];
				$BadDebt=$rescus["BadDebt"];
				
				if($i==0){
					$color=$color1;
				}else{
					if($InvNo2==$InvNo_old){
						if($color==$color1){
							$color=$color1;
						}else{
							$color=$color2;
						}
					}else{
						if($color==$color1){
							$color=$color2;
						}else{
							$color=$color1;
						}
					}
				}
				echo "<tr bgcolor=$color align=center>";
				echo "<td height=25>$InvNo2</td>";
				echo "<td>$InvType</td>";
				echo "<td>$CusID</td>";
				echo "<td>$RadioID</td>";
				echo "<td align=left>$Description</td>";
				echo "<td align=right>$PriceUnit</td>";
				echo "<td>$NumUnit</td>";
				echo "<td>$RecNO</td>";
				echo "<td>$VatNO</td>";
				echo "<td>$Discount</td>";
				echo "<td align=right>$InvAmountExVAT</td>";
				echo "<td>$RInvNO</td>";
				echo "<td>$InvIDUser</td>";
				echo "<td>$CancelIDUser</td>";
				echo "<td>$OldInvNO</td>";
				echo "<td>$InvFixDate</td>";
				echo "<td>$InvDate</td>";
				echo "<td>$NeedVAT</td>";
				echo "<td>$InvCancel</td>";
				echo "<td>$CancelInvDate</td>";
				echo "<td>$BadDebt</td>";
				echo "</tr>";	
			$i++;
			$InvNo_old=$InvNo2;
			
			}
		}		
	} //end while

	if($i==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=21 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=#7DCFFB height=30><td colspan=21><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>

